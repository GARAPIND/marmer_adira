<?php

namespace App\Http\Controllers;

use App\Models\AlamatPembeli;
use App\Models\PesananPaymentHistory;
use App\Models\User;
use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\Bahan; // Pastikan Model Bahan diimport
use App\Models\Produk;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // ==========================================
    // 1. FUNGSI PEMBANTU (HELPERS)
    // ==========================================

    private function buildPaymentSummary(Pesanan $pesanan): array
    {
        $histories = $pesanan->paymentHistories ?? collect();
        $paidDp = $histories->firstWhere('event_type', 'paid_dp');
        $paidLunas = $histories->firstWhere('event_type', 'paid_lunas');
        $paidEvents = $histories->whereIn('event_type', ['paid_dp', 'paid_lunas'])->sortBy('event_time')->values();
        $totalDibayar = (int) $paidEvents->sum(fn($item) => (int) ($item->nominal ?? 0));

        $pernahDp = $paidDp !== null;
        $sudahLunas = $pesanan->status_pembayaran === 'paid' || $paidLunas !== null;
        $waktuBayarPertama = $paidDp->event_time ?? $paidEvents->first()->event_time ?? $pesanan->tanggal_bayar;
        $waktuLunas = $paidLunas->event_time ?? $pesanan->tanggal_lunas;
        $metodeTerakhir = $paidEvents->last()->payment_method
            ?? $pesanan->midtrans_bank
            ?? $pesanan->midtrans_payment_type
            ?? '-';

        if ($totalDibayar <= 0) {
            $totalDibayar = (int) ($pesanan->jumlah_dibayar ?? 0);
        }

        return [
            'pernah_dp' => $pernahDp,
            'sudah_lunas' => $sudahLunas,
            'status_label' => $sudahLunas ? ($pernahDp ? 'DP lalu Lunas' : 'Lunas Langsung') : ($pernahDp ? 'Baru DP' : 'Belum Bayar'),
            'dp_nominal' => (int) ($paidDp->nominal ?? 0),
            'lunas_nominal' => (int) ($paidLunas->nominal ?? 0),
            'waktu_dp' => $paidDp->event_time ?? null,
            'waktu_lunas' => $waktuLunas,
            'metode_terakhir' => strtoupper((string) $metodeTerakhir),
            'total_dibayar' => $totalDibayar,
        ];
    }

    private function getPesananStats($tgl_mulai = null, $tgl_akhir = null)
    {
        $query = Pesanan::query();
        if ($tgl_mulai && $tgl_akhir) {
            $query->whereBetween('created_at', [$tgl_mulai . " 00:00:00", $tgl_akhir . " 23:59:59"]);
        }

        return [
            'total'         => (clone $query)->count(),
            'diverifikasi'  => (clone $query)->whereIn('status', ['Diverifikasi', 'Diproses', 'Dikerjakan', 'Selesai'])->count(),
            'diproses'      => (clone $query)->whereIn('status', ['Diproses', 'Dikerjakan', 'Selesai'])->count(),
            'selesai'       => (clone $query)->where('status', 'Selesai')->count(),
            'ditolak'       => (clone $query)->where('status', 'Ditolak')->count(),
        ];
    }

    private function getKeuanganData($tgl_mulai = null, $tgl_akhir = null)
    {
        $query = Pesanan::with(['user', 'paymentHistories', 'items']);
        if ($tgl_mulai && $tgl_akhir) {
            $query->whereBetween('created_at', [$tgl_mulai . " 00:00:00", $tgl_akhir . " 23:59:59"]);
        }

        $transaksi = (clone $query)->where('status', '!=', 'Ditolak')->orderByDesc('created_at')->get()->map(function ($item) {
            $item->payment_summary = $this->buildPaymentSummary($item);
            return $item;
        });
        $totalTagihan = fn($item) => (int) $item->total_harga + (int) ($item->biaya_pengiriman ?? 0);
        $transaksiBerhasil = $transaksi->whereIn('status_pembayaran', ['dp', 'paid']);

        $stats = [
            'total_pendapatan' => $transaksi->sum(fn($item) => (int) ($item->payment_summary['total_dibayar'] ?? 0)),
            'total_dp'         => $transaksi->sum(fn($item) => (int) ($item->payment_summary['dp_nominal'] ?? 0)),
            'total_pelunasan'  => $transaksi->where('status_pembayaran', 'paid')->sum(fn($item) => max($totalTagihan($item) - (int) ((int) ceil($totalTagihan($item) * 0.5)), 0)),
            'jumlah_transaksi' => $transaksi->count(),
            'total_produk_terjual' => $transaksi->where('status_pembayaran', 'paid')->sum('jumlah'),
            'transaksi_berhasil' => $transaksiBerhasil->count(),
            'status_lunas' => $transaksi->where('status_pembayaran', 'paid')->count(),
            'status_dp_50' => $transaksi->where('status_pembayaran', 'dp')->count(),
            'status_belum_bayar' => $transaksi->where('status_pembayaran', 'no_paid')->count(),
            'metode_bri' => $transaksiBerhasil->where('midtrans_bank', 'BRI')->count(),
            'metode_bca' => $transaksiBerhasil->where('midtrans_bank', 'BCA')->count(),
            'metode_mandiri' => $transaksiBerhasil->where('midtrans_bank', 'MANDIRI')->count(),
        ];

        return [
            'stats' => $stats,
            'transaksi' => $transaksi
        ];
    }

    private function normalizeDecimalInput(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        $value = trim($value);
        if ($value === '') {
            return null;
        }

        return str_replace(',', '.', $value);
    }

    private function calculateOrderWeightFromItems(Pesanan $pesanan, ?array $itemsPayload = null): float
    {
        $items = $pesanan->relationLoaded('items') ? $pesanan->items : $pesanan->items()->get();
        $payloadById = collect($itemsPayload ?? [])->keyBy(fn($item) => (int) ($item['id'] ?? 0));

        return (float) $items->sum(function (PesananItem $item) use ($payloadById) {
            $incoming = $payloadById->get((int) $item->id, []);
            $beratSatuan = (float) ($incoming['berat_satuan'] ?? $item->berat_satuan ?? 0);
            return max($beratSatuan, 0) * (int) ($item->jumlah ?? 0);
        });
    }

    private function resolvePesananCourier(Pesanan $pesanan): ?string
    {
        if (!is_string($pesanan->alamat_pengiriman) || $pesanan->alamat_pengiriman === '') {
            return null;
        }

        if (preg_match('/Ekspedisi:\s*([A-Z0-9]+)/i', $pesanan->alamat_pengiriman, $matches)) {
            return strtolower(trim($matches[1]));
        }

        return null;
    }

    private function resolveBusTerminal(Pesanan $pesanan): ?Terminal
    {
        if (!is_string($pesanan->alamat_pengiriman) || $pesanan->alamat_pengiriman === '') {
            return null;
        }

        $alamat = $pesanan->alamat_pengiriman;

        return Terminal::query()
            ->get()
            ->first(function (Terminal $terminal) use ($alamat) {
                return str_contains(strtolower($alamat), strtolower($terminal->nama_terminal));
            });
    }

    private function ensureInternalResi(Pesanan $pesanan): Pesanan
    {
        if (!empty($pesanan->kode_resi_internal)) {
            return $pesanan;
        }

        $pesanan->update([
            'kode_resi_internal' => 'ADIRA-' . now()->format('Ymd') . '-' . str_pad((string) $pesanan->id, 4, '0', STR_PAD_LEFT),
        ]);

        return $pesanan->fresh(['user', 'items', 'alamatPembeli', 'paymentHistories']);
    }

    // ==========================================
    // 2. DASHBOARD & UPDATE PESANAN
    // ==========================================

    public function dashboard()
    {
        $stats = [
            'baru'      => Pesanan::where('status', 'Menunggu Verifikasi Admin')->count(),
            'diproses'  => Pesanan::whereIn('status', ['Diverifikasi', 'Diproses', 'Dikerjakan', 'Selesai', 'diekspedisi'])->count(),
            'selesai'   => Pesanan::where('status', 'Selesai')->count(),
            'total_bayar' => Pesanan::where('status_pembayaran', 'paid')
                ->selectRaw('SUM(total_harga + COALESCE(biaya_pengiriman, 0)) as total')
                ->first()->total ?? 0,
            'pendapatan_harian' => PesananPaymentHistory::select(
                DB::raw('DATE(event_time) as tanggal'),
                DB::raw('SUM(nominal) as total_pendapatan')
            )
                ->where('status', 'success')
                ->whereBetween('event_time', [
                    Carbon::now()->subDays(6)->startOfDay(),
                    Carbon::now()->endOfDay()
                ])
                ->groupBy(DB::raw('DATE(event_time)'))
                ->orderBy('tanggal', 'asc')
                ->get()->toArray(),
            'produk_terlaris' => DB::table('produk as p')
                ->leftJoin('pesanan as pd', 'p.nama_produk', '=', 'pd.nama_produk')
                ->select(
                    'p.nama_produk',
                    DB::raw('COUNT(DISTINCT pd.id) as total_transaksi'),
                    DB::raw('COALESCE(SUM(pd.jumlah), 0) as total_qty')
                )
                ->groupBy('p.nama_produk')
                ->orderByDesc('total_qty')
                ->get()
                ->toArray()
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function showPesanan($id)
    {
        $pesanan = Pesanan::with(['user', 'items', 'alamatPembeli', 'paymentHistories'])->findOrFail($id);
        return view('admin.pesanan-detail', compact('pesanan'));
    }

    public function printResi($id)
    {
        $pesanan = Pesanan::with(['user', 'items'])->findOrFail($id);
        $pesanan = $this->ensureInternalResi($pesanan);

        $pdf = Pdf::loadView('admin.partials.resi-cetak', compact('pesanan'))->setPaper('a5', 'portrait');

        return $pdf->stream('resi-internal-ORD-' . str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function kirimPesanan(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status_pembayaran !== 'paid') {
            return redirect()->back()->with('error', 'Pesanan belum lunas, nomor resi dan pengiriman belum bisa diproses.');
        }

        if ($pesanan->status !== 'Selesai' && $pesanan->tanggal_siap_dikirim === null) {
            return redirect()->back()->with('error', 'Pesanan belum selesai produksi dari pengrajin, jadi resi cargo belum bisa diproses.');
        }

        $validated = $request->validate([
            'nomor_resi_pengiriman' => 'required|string|max:100',
        ], [
            'nomor_resi_pengiriman.required' => 'Nomor resi cargo wajib diisi.',
            'nomor_resi_pengiriman.max' => 'Nomor resi cargo maksimal 100 karakter.',
        ]);

        $pesanan = $this->ensureInternalResi($pesanan);
        $pesanan->update([
            'status' => 'diekspedisi',
            'nomor_resi_pengiriman' => trim($validated['nomor_resi_pengiriman']),
            'tanggal_dikirim' => now(),
            'tgl_update_proses' => now(),
        ]);

        return redirect()->back()->with('success', 'Pesanan berhasil dikirim ke cargo.');
    }

    public function updatePesanan(Request $request, $id)
    {
        $pesanan = Pesanan::with('items')->findOrFail($id);
        $itemsPayload = collect($request->input('items', []))
            ->map(function ($item) {
                $item['berat_satuan'] = $this->normalizeDecimalInput($item['berat_satuan'] ?? null);
                $item['harga_satuan'] = $this->normalizeDecimalInput($item['harga_satuan'] ?? null);
                return $item;
            })
            ->values()
            ->all();

        $request->merge(['items' => $itemsPayload]);

        $rules = [
            'status' => 'required|in:Diverifikasi,Ditolak',
            'biaya_pengiriman' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.berat_satuan' => 'nullable|numeric|min:0',
            'items.*.harga_satuan' => 'nullable|numeric|min:0',
        ];

        if ($request->status === 'Diverifikasi') {
            $rules['items.*.harga_satuan'] = 'required|numeric|min:0';
        }
        if ($request->status === 'Ditolak') {
            $rules['alasan_penolakan'] = 'required|string|max:1000';
        }

        if ($pesanan->metode_pengambilan === 'dikirim') {
            $rules['biaya_pengiriman'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules, [
            'biaya_pengiriman.required' => 'Ongkir wajib diisi untuk pesanan yang dikirim.',
            'items.*.harga_satuan.required' => 'Harga satuan per item wajib diisi saat pesanan diverifikasi.',
        ]);

        $itemsById = collect($validated['items'])->keyBy(fn($item) => (int) $item['id']);
        $totalHarga = 0;
        $totalBerat = 0;

        foreach ($pesanan->items as $item) {
            $incoming = $itemsById->get((int) $item->id);
            if (!$incoming) {
                continue;
            }

            $beratSatuan = (float) ($incoming['berat_satuan'] ?? 0);
            $hargaSatuan = (int) round((float) ($incoming['harga_satuan'] ?? 0));
            $subtotal = $hargaSatuan * (int) $item->jumlah;
            $itemTotalBerat = $beratSatuan * (int) $item->jumlah;

            $item->update([
                'berat_satuan' => $beratSatuan,
                'total_berat' => $itemTotalBerat,
                'harga_satuan' => $hargaSatuan,
                'subtotal' => $subtotal,
            ]);

            $totalHarga += $subtotal;
            $totalBerat += $itemTotalBerat;
        }

        $firstItem = $pesanan->items()->orderBy('id')->first();

        $pesanan->update([
            'total_harga' => $totalHarga,
            'biaya_pengiriman' => (int) ($validated['biaya_pengiriman'] ?? 0),
            'berat_satuan' => (float) ($firstItem->berat_satuan ?? 0),
            'total_berat' => $totalBerat,
            'status' => $validated['status'],
            'alasan_penolakan' => $validated['status'] === 'Ditolak' ? $request->alasan_penolakan : null,
        ]);

        return redirect()->back()->with('success', 'Pesanan berhasil diperbarui!');
    }

    public function hitungOngkirPesanan(Request $request, $id)
    {
        $pesanan = Pesanan::with(['alamatPembeli', 'items'])->findOrFail($id);

        $itemsPayload = collect($request->input('items', []))
            ->map(function ($item) {
                $item['berat_satuan'] = $this->normalizeDecimalInput($item['berat_satuan'] ?? null);
                return $item;
            })
            ->values()
            ->all();

        $request->merge(['items' => $itemsPayload]);

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.berat_satuan' => 'required|numeric|min:0',
        ], [
            'items.required' => 'Minimal ada satu item untuk menghitung ongkir.',
            'items.*.berat_satuan.required' => 'Berat satuan semua item wajib diisi.',
            'items.*.berat_satuan.numeric' => 'Berat satuan harus berupa angka, bisa memakai koma atau titik.',
        ]);

        if ($pesanan->metode_pengambilan !== 'dikirim') {
            return response()->json([
                'biaya_pengiriman' => 0,
                'total_berat' => 0,
                'summary' => 'Pesanan tidak memakai pengiriman.',
                'calculation' => null,
            ]);
        }

        $totalBerat = max($this->calculateOrderWeightFromItems($pesanan, $validated['items']), 0);

        if ($totalBerat <= 0) {
            return response()->json([
                'biaya_pengiriman' => 0,
                'total_berat' => 0,
                'summary' => 'Isi berat satuan untuk menghitung ongkir otomatis.',
                'calculation' => null,
            ]);
        }

        if ($pesanan->jenis_pengiriman === 'cargo') {
            $alamat = $pesanan->alamatPembeli instanceof AlamatPembeli ? $pesanan->alamatPembeli : null;
            $courier = $this->resolvePesananCourier($pesanan);
            $origin = (string) config('services.rajaongkir.origin_kecamatan_id', '');

            if (!$alamat || !$alamat->kecamatan_id || !$courier || $origin === '') {
                return response()->json([
                    'message' => 'Data alamat, ekspedisi, atau origin RajaOngkir belum lengkap. Ongkir perlu diisi manual.',
                ], 422);
            }

            $weightGram = max((int) ceil($totalBerat * 1000), 1);

            $response = Http::withHeaders([
                'key' => config('services.rajaongkir.key'),
                'Accept' => 'application/json',
            ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/district/domestic-cost', [
                'origin' => $origin,
                'destination' => $alamat->kecamatan_id,
                'weight' => $weightGram,
                'courier' => $courier,
                'price' => 'lowest',
            ]);

            $result = $response->json('data');
            $service = is_array($result) && isset($result[0]) ? $result[0] : null;

            if (!$response->successful() || !is_array($service) || !isset($service['cost'])) {
                return response()->json([
                    'message' => 'Gagal mengambil ongkir otomatis dari RajaOngkir. Ongkir perlu diisi manual.',
                ], 422);
            }

            return response()->json([
                'biaya_pengiriman' => (int) $service['cost'],
                'total_berat' => $totalBerat,
                'summary' => 'Ongkir cargo dihitung otomatis dari RajaOngkir.',
                'calculation' => [
                    'jenis_pengiriman' => 'cargo',
                    'courier' => strtoupper($courier),
                    'service' => $service['service'] ?? null,
                    'description' => $service['description'] ?? null,
                    'etd' => $service['etd'] ?? null,
                    'weight_gram' => $weightGram,
                ],
            ]);
        }

        return response()->json([
            'message' => 'Jenis pengiriman tidak dikenali. Ongkir perlu diisi manual.',
        ], 422);
    }

    public function selesaiPesanan(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        if ($request->status_selesai == 'paid') {
            $pesanan->update([
                'status_pembayaran' => 'paid',
                'tanggal_bayar' => $pesanan->tanggal_bayar ?? Carbon::now(),
                'tanggal_lunas' => Carbon::now()
            ]);
        } else {
            $pesanan->update([
                'status_pembayaran' => 'no_paid',
                'tanggal_bayar' => null,
                'tanggal_lunas' => null
            ]);
        }
        return redirect()->back()->with('success', 'Pesanan berhasil diperbarui!');
    }

    public function destroyPesanan($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->delete();

        return redirect()->back()->with('success', 'Pesanan dipindahkan ke sampah.');
    }

    public function trashPesanan()
    {
        $pesanan = Pesanan::onlyTrashed()
            ->with(['user', 'paymentHistories', 'progressPhotos', 'items'])
            ->latest('deleted_at')
            ->get();

        return view('admin.pesanan-trash', compact('pesanan'));
    }

    public function restorePesanan($id)
    {
        $pesanan = Pesanan::onlyTrashed()->findOrFail($id);
        $pesanan->restore();

        return redirect()->route('admin.pesanan.trash')->with('success', 'Pesanan berhasil dipulihkan dari sampah.');
    }

    // ==========================================
    // 3. SEKSI LAPORAN PESANAN (KODE TETAP)
    // ==========================================

    public function laporanPesanan(Request $request)
    {
        $stats = $this->getPesananStats($request->tgl_mulai, $request->tgl_akhir);
        return view('admin.laporan-pesanan', compact('stats'));
    }

    public function exportPesananPdf(Request $request)
    {
        $stats = $this->getPesananStats($request->tgl_mulai, $request->tgl_akhir);
        $pdf = Pdf::loadView('admin.exports.laporan-pesanan-pdf', compact('stats'));
        return $pdf->download('Laporan-Pesanan-Adira-Marmer.pdf');
    }

    public function exportPesananExcel(Request $request)
    {
        $stats = $this->getPesananStats($request->tgl_mulai, $request->tgl_akhir);
        return response()->streamDownload(function () use ($stats) {
            echo "Kategori,Jumlah\n";
            echo "Total Pesanan Masuk," . $stats['total'] . "\n";
            echo "Pesanan Diverifikasi," . $stats['diverifikasi'] . "\n";
            echo "Pesanan Diproses," . $stats['diproses'] . "\n";
            echo "Pesanan Selesai," . $stats['selesai'] . "\n";
            echo "Pesanan Ditolak," . $stats['ditolak'] . "\n";
        }, 'Laporan-Pesanan.csv');
    }

    // ==========================================
    // 4. SEKSI LAPORAN KEUANGAN (KODE TETAP)
    // ==========================================

    public function laporanKeuangan(Request $request)
    {
        $data = $this->getKeuanganData($request->tgl_mulai, $request->tgl_akhir);
        return view('admin.laporan-keuangan', [
            'stats' => $data['stats'],
            'transaksi' => $data['transaksi']
        ]);
    }

    public function exportKeuanganPdf(Request $request)
    {
        $data = $this->getKeuanganData($request->tgl_mulai, $request->tgl_akhir);

        $pdf = Pdf::loadView('admin.exports.laporan-keuangan-pdf', [
            'stats' => $data['stats'],
            'transaksi' => $data['transaksi']
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Laporan-Keuangan-Adira-Marmer.pdf');
    }

    public function exportKeuanganExcel(Request $request)
    {
        $data = $this->getKeuanganData($request->tgl_mulai, $request->tgl_akhir);

        return response()->streamDownload(function () use ($data) {

            echo "ID,Tanggal Pesanan,Pembeli,Nama Produk,Jumlah,Total Harga,Ongkir,Total Dibayar,Sisa Pembayaran,Status,Metode Pembayaran,Tanggal DP,Waktu Lunas\n";

            foreach ($data['transaksi'] as $item) {
                $summary = $item->payment_summary ?? [];

                $totalHarga = (int) $item->total_harga;
                $ongkir = (int) ($item->biaya_pengiriman ?? 0);
                $totalDibayar = (int) ($summary['total_dibayar'] ?? 0);
                $sisa = $totalHarga + $ongkir - $totalDibayar;

                $status = $item->status_pembayaran === 'paid'
                    ? 'Lunas'
                    : ($item->status_pembayaran === 'dp'
                        ? 'DP'
                        : 'Belum Bayar');

                $tglPesanan = Carbon::parse($item->created_at)->format('d M Y H:i');
                $tglDP = !empty($summary['waktu_dp'])
                    ? Carbon::parse($summary['waktu_dp'])->format('d M Y H:i')
                    : '-';
                $tglLunas = !empty($summary['waktu_lunas'])
                    ? Carbon::parse($summary['waktu_lunas'])->format('d M Y H:i')
                    : '-';

                $namaPembeli = '"' . str_replace('"', '""', $item->user->name) . '"';
                $namaProduk = '"' . str_replace('"', '""', $item->nama_produk . ' (' . $item->jenis_marmer . ')') . '"';
                $metode = '"' . str_replace('"', '""', ($summary['metode_terakhir'] ?? '-')) . '"';

                echo
                "ORD-" . str_pad($item->id, 3, '0', STR_PAD_LEFT) . "," .
                    $tglPesanan . "," .
                    $namaPembeli . "," .
                    $namaProduk . "," .
                    $item->jumlah . "," .
                    $totalHarga . "," .
                    $ongkir . "," .
                    $totalDibayar . "," .
                    $sisa . "," .
                    $status . "," .
                    $metode . "," .
                    $tglDP . "," .
                    $tglLunas . "\n";
            }
        }, 'Laporan-Keuangan-Adira.csv');
    }
    private function getPenggunaQuery(Request $request)
    {
        $query = User::whereNotIn('role', ['admin'])
            ->withCount('pesanan');

        if ($request->filled('tgl_mulai')) {
            $query->whereDate('created_at', '>=', $request->tgl_mulai);
        }
        if ($request->filled('tgl_akhir')) {
            $query->whereDate('created_at', '<=', $request->tgl_akhir);
        }
        if ($request->filled('role') && $request->role !== 'Semua') {
            $query->where('role', $request->role);
        }

        return $query;
    }

    public function laporanPengguna(Request $request)
    {
        $users = $this->getPenggunaQuery($request)->orderBy('created_at', 'desc')->get();

        $roles = User::whereNotIn('role', ['admin'])->distinct()->pluck('role');

        $stats = $roles->mapWithKeys(fn($role) => [$role => $users->where('role', $role)->count()])
            ->put('total', $users->count());

        return view('admin.laporan-pengguna', compact('users', 'stats', 'roles'));
    }

    public function exportPenggunaPdf(Request $request)
    {
        $users = $this->getPenggunaQuery($request)->orderBy('created_at', 'desc')->get();

        $roles = User::whereNotIn('role', ['admin'])->distinct()->pluck('role');

        $stats = $roles->mapWithKeys(fn($role) => [$role => $users->where('role', $role)->count()])
            ->put('total', $users->count());

        $pdf = Pdf::loadView('admin.exports.laporan-pengguna-pdf', compact('users', 'stats', 'roles'));
        return $pdf->download('Laporan-Pengguna-Adira-Marmer.pdf');
    }

    public function exportPenggunaExcel(Request $request)
    {
        $users = $this->getPenggunaQuery($request)->orderBy('created_at', 'desc')->get();

        return response()->streamDownload(function () use ($users) {
            echo "No,Nama,Email,No. Telepon,Role,Jumlah Pesanan,Tanggal Daftar\n";
            foreach ($users as $index => $user) {
                echo ($index + 1) . ",";
                echo $user->name . ",";
                echo $user->email . ",";
                echo ($user->no_telp ?? '-') . ",";
                echo ucfirst($user->role) . ",";
                echo $user->pesanan_count . ",";
                echo $user->created_at->format('d M Y') . "\n";
            }
        }, 'Laporan-Pengguna-Adira.csv');
    }
    public function laporanPenjualan(Request $request)
    {
        $tgl_mulai = $request->tgl_mulai;
        $tgl_akhir = $request->tgl_akhir;

        $query = Pesanan::where('status_pembayaran', 'paid');

        if ($tgl_mulai && $tgl_akhir) {
            $query->whereBetween('tanggal_lunas', [
                Carbon::parse($tgl_mulai)->startOfDay(),
                Carbon::parse($tgl_akhir)->endOfDay()
            ]);
        }

        $data['jumlah_produk_terjual'] = (clone $query)->sum('jumlah');
        $data['total_nilai_penjualan'] = (clone $query)->sum(DB::raw('total_harga + biaya_pengiriman'));
        $data['transaksi_berhasil'] = (clone $query)->count();
        $data['tgl_mulai'] = $tgl_mulai;
        $data['tgl_akhir'] = $tgl_akhir;

        return view('admin.laporan-penjualan', compact('data'));
    }

    // ==========================================
    // 5. MANAJEMEN MASTER BAHAN (KODE TETAP)
    // ==========================================

    public function manajemenProduk()
    {
        $bahans = Bahan::latest()->get();
        return view('admin.manajemen-produk', compact('bahans'));
    }

    public function simpanBahan(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255|unique:bahan,nama_bahan'
        ], [
            'nama_bahan.unique' => 'Nama bahan ini sudah ada di sistem.'
        ]);

        Bahan::create($request->all());
        return redirect()->back()->with('success', 'Bahan baru berhasil ditambahkan.');
    }

    public function updateBahan(Request $request, $id)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255|unique:bahan,nama_bahan,' . $id
        ]);

        $bahan = Bahan::findOrFail($id);
        $bahan->update($request->all());

        return redirect()->back()->with('success', 'Data bahan berhasil diperbarui.');
    }

    public function hapusBahan($id)
    {
        $columns = [
            'kecil'  => ['bahan_kecil_id', 'harga_kecil', 'berat_kecil', 'ukuran_kecil'],
            'sedang' => ['bahan_sedang_id', 'harga_sedang', 'berat_sedang', 'ukuran_sedang'],
            'besar'  => ['bahan_besar_id', 'harga_besar', 'berat_besar', 'ukuran_besar'],
        ];

        foreach ($columns as $size => $fields) {
            Produk::where($fields[0], $id)->update(array_fill_keys($fields, null));
        }

        Produk::whereNull('bahan_kecil_id')
            ->whereNull('bahan_sedang_id')
            ->whereNull('bahan_besar_id')
            ->delete();

        Bahan::destroy($id);

        return redirect()->back()->with('success', 'Bahan berhasil dihapus.');
    }

    // ==========================================
    // 6. MENU DATA PENGGUNA & LAINNYA (KODE TETAP)
    // ==========================================

    public function dataPengrajin()
    {
        $pengrajin = User::where('role', 'pengrajin')->get();
        return view('admin.data-pengguna', compact('pengrajin'));
    }

    public function pesananBaru()
    {
        $pesanan = Pesanan::with(['user', 'items'])->orderBy('created_at', 'DESC')->get();
        // dd($pesanan);
        return view('admin.pesanan-baru', compact('pesanan'));
    }
}
