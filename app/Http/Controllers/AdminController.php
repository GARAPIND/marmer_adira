<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pesanan;
use App\Models\Bahan; // Pastikan Model Bahan diimport
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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
            $query->whereBetween('updated_at', [$tgl_mulai . " 00:00:00", $tgl_akhir . " 23:59:59"]);
        }

        $transaksi = (clone $query)->whereIn('status', ['Diverifikasi', 'Diproses', 'Dikerjakan', 'Selesai'])->get();

        // REVISI: Perhitungan Keuangan sekarang menjumlahkan total_harga + biaya_pengiriman
        $stats = [
            'total_pendapatan' => (clone $query)->where('status', 'Selesai')
                ->selectRaw('SUM(total_harga + COALESCE(biaya_pengiriman, 0)) as total')
                ->first()->total ?? 0,

            'total_dp'         => (clone $query)->whereIn('status', ['Diverifikasi', 'Diproses', 'Dikerjakan'])
                ->selectRaw('SUM(total_harga + COALESCE(biaya_pengiriman, 0)) * 0.3 as total')
                ->first()->total ?? 0,

            'total_pelunasan'  => (clone $query)->where('status', 'Selesai')
                ->selectRaw('SUM(total_harga + COALESCE(biaya_pengiriman, 0)) * 0.7 as total')
                ->first()->total ?? 0,

            'jumlah_transaksi' => $transaksi->count(),
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

        // Pastikan admin mengisi rincian pengiriman jika metode pengirimannya via bus
        $rincianOngkir = $pesanan->alamat_pengiriman; // default tetap yang lama
        if ($request->filled('jarak_final')) {
            $rincianOngkir = "Kirim via Bus (Perhitungan: " . ($request->jarak_final ?? 0) . " KM x Rp " . number_format($request->tarif_final ?? 0, 0, ',', '.') . "/KM)";
        }

        $pesanan->update([
            'total_harga'      => $request->total_harga,
            'biaya_pengiriman' => $request->biaya_pengiriman,
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
                'tanggal_bayar' => Carbon::now()
            ]);
        } else {
            $pesanan->update([
                'status_pembayaran' => 'no_paid',
                'tanggal_bayar' => null
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
            echo "ID Pesanan,Nama Pembeli,Tanggal Bayar,Jenis,Nominal,Status\n";
            foreach ($data['transaksi'] as $item) {
                $jenis = in_array($item->status, ['Diverifikasi', 'Diproses', 'Dikerjakan']) ? 'DP (30%)' : 'Pelunasan';
                echo "ORD-" . str_pad($item->id, 3, '0', STR_PAD_LEFT) . ",";
                echo $item->user->name . ",";
                echo $item->updated_at->format('d M Y') . ",";
                echo $jenis . ",";
                echo ($item->total_harga + $item->biaya_pengiriman) . ",";
                echo "Lunas\n";
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
    public function laporanPenjualan()
    {
        return view('admin.laporan-penjualan', ['stats' => []]);
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
