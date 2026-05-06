<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pesanan;
use App\Models\Bahan; // Pastikan Model Bahan diimport
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ==========================================
    // 1. FUNGSI PEMBANTU (HELPERS)
    // ==========================================

    private function getPesananStats($tgl_mulai = null, $tgl_akhir = null)
    {
        $query = Pesanan::query();
        if ($tgl_mulai && $tgl_akhir) {
            $query->whereBetween('created_at', [$tgl_mulai . " 00:00:00", $tgl_akhir . " 23:59:59"]);
        }

        return [
            'total'         => (clone $query)->count(),
            'diverifikasi'  => (clone $query)->whereIn('status', ['Diverifikasi', 'Diproses', 'Dikerjakan'])->count(),
            'diproses'      => (clone $query)->whereIn('status', ['Diproses', 'Dikerjakan'])->count(),
            'selesai'       => (clone $query)->where('status', 'Selesai')->count(),
            'dibatalkan'    => (clone $query)->where('status', 'Dibatalkan')->count(),
        ];
    }

    private function getKeuanganData($tgl_mulai = null, $tgl_akhir = null)
    {
        $query = Pesanan::with('user');
        if ($tgl_mulai && $tgl_akhir) {
            $query->whereBetween('created_at', [$tgl_mulai . " 00:00:00", $tgl_akhir . " 23:59:59"]);
        }

        $transaksi = (clone $query)->orderByDesc('created_at')->get();
        $totalTagihan = fn($item) => (int) $item->total_harga + (int) ($item->biaya_pengiriman ?? 0);
        $transaksiBerhasil = $transaksi->whereIn('status_pembayaran', ['dp', 'paid']);

        $stats = [
            'total_pendapatan' => $transaksi->sum(fn($item) => (int) ($item->jumlah_dibayar ?? 0)),
            'total_dp'         => $transaksi->where('status_pembayaran', 'dp')->sum(fn($item) => (int) ($item->jumlah_dibayar ?? 0)),
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

    // ==========================================
    // 2. DASHBOARD & UPDATE PESANAN
    // ==========================================

    public function dashboard()
    {
        // REVISI: total_bayar sekarang menghitung (Harga Produk + Ongkir) dari status 'Selesai'
        $stats = [
            'baru'      => Pesanan::where('status', 'Menunggu Verifikasi Admin')->count(),
            'diproses'  => Pesanan::whereIn('status', ['Diverifikasi', 'Diproses', 'Dikerjakan', 'diekspedisi'])->count(),
            'selesai'   => Pesanan::where('status', 'Selesai')->count(),
            // tanpa where baru atau where status_pembayaran paid
            'total_bayar' => Pesanan::where('status_pembayaran', 'paid')
                ->selectRaw('SUM(total_harga + COALESCE(biaya_pengiriman, 0)) as total')
                ->first()->total ?? 0
        ];

        $pesananTerbaru = Pesanan::with('user')->latest()->take(5)->get();
        return view('admin.dashboard', compact('stats', 'pesananTerbaru'));
    }

    public function updatePesanan(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $request->validate([
            'total_harga' => 'required|numeric|min:0',
            'biaya_pengiriman' => 'nullable|numeric|min:0',
            'berat_satuan' => 'nullable|numeric|min:0',
            'status' => 'required|string',
        ]);

        // Pastikan admin mengisi rincian pengiriman jika metode pengirimannya via bus
        $rincianOngkir = $pesanan->alamat_pengiriman; // default tetap yang lama
        if ($request->filled('jarak_final')) {
            $rincianOngkir = "Kirim via Bus (Perhitungan: " . ($request->jarak_final ?? 0) . " KM x Rp " . number_format($request->tarif_final ?? 0, 0, ',', '.') . "/KM)";
        }

        $beratSatuan = (float) ($request->berat_satuan ?? $pesanan->berat_satuan ?? 0);

        $pesanan->update([
            'total_harga'      => $request->total_harga,
            'biaya_pengiriman' => $request->biaya_pengiriman ?? 0,
            'berat_satuan'     => $beratSatuan,
            'total_berat'      => $beratSatuan * (int) $pesanan->jumlah,
            'alamat_pengiriman' => $rincianOngkir,
            'status'           => $request->status,
        ]);

        return redirect()->back()->with('success', 'Pesanan berhasil diperbarui!');
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
            echo "Pesanan Dibatalkan," . $stats['dibatalkan'] . "\n";
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
        ]);
        return $pdf->download('Laporan-Keuangan-Adira-Marmer.pdf');
    }

    public function exportKeuanganExcel(Request $request)
    {
        $data = $this->getKeuanganData($request->tgl_mulai, $request->tgl_akhir);
        return response()->streamDownload(function () use ($data) {
            echo "ID Pesanan,Nama Pembeli,Metode Pembayaran,Status Pembayaran,Tanggal Lunas,Nominal Dibayar\n";
            foreach ($data['transaksi'] as $item) {
                $metodeBayar = $item->midtrans_bank ?: strtoupper((string) ($item->midtrans_payment_type ?? '-'));
                echo "ORD-" . str_pad($item->id, 3, '0', STR_PAD_LEFT) . ",";
                echo $item->user->name . ",";
                echo $metodeBayar . ",";
                echo ($item->status_pembayaran === 'paid' ? 'Lunas' : ($item->status_pembayaran === 'dp' ? 'DP 50%' : 'Belum Bayar')) . ",";
                echo ($item->tanggal_lunas ? Carbon::parse($item->tanggal_lunas)->format('d M Y H:i') : '-') . ",";
                echo ((int) ($item->jumlah_dibayar ?? 0)) . "\n";
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
        $pesanan = Pesanan::with('user')->orderBy('created_at', 'DESC')->get();
        // dd($pesanan);
        return view('admin.pesanan-baru', compact('pesanan'));
    }
}
