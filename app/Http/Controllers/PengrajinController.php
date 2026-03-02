<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Bahan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PengrajinController extends Controller
{
    public function dashboard() {
        $stats = [
            'baru'    => Pesanan::where('status', 'Diverifikasi')->count(), 
            'proses'  => Pesanan::whereIn('status', ['Diproses', 'Dikerjakan'])->count(),
            'selesai' => Pesanan::where('status', 'Selesai')->count(),
        ];
        return view('pengrajin.dashboard', compact('stats'));
    }

    public function pesananMasuk() {
        $pesanan = Pesanan::with('user')
            ->where('status', 'Diverifikasi')
            ->latest()
            ->get();
        return view('pengrajin.pesanan_masuk', compact('pesanan'));
    }

    public function katalog() {
        $produk = Produk::where('pengrajin_id', Auth::id())->latest()->get(); 
        $bahans = Bahan::all(); 
        return view('pengrajin.katalog', compact('produk', 'bahans'));
    }

    public function simpanProduk(Request $request) {
        $request->validate([
            'nama_produk'  => 'required|string|max:255',
            'ukuran_kecil' => 'required|string', 
            'harga_kecil'  => 'required|numeric',
            'gambar'       => 'required|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        // Perbaikan: Ambil semua data ukuran dari request
        $data = $request->only([
            'nama_produk', 'deskripsi', 
            'ukuran_kecil', 'harga_kecil', 
            'ukuran_sedang', 'harga_sedang', 
            'ukuran_besar', 'harga_besar'
        ]);

        $data['stok'] = 0;
        $data['bahan'] = $request->bahan ?? 'Marmer'; // Default Marmer jika tidak ada input
        $data['pengrajin_id'] = Auth::id(); 

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data); 
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function updateProduk(Request $request, $id) {
        $produk = Produk::where('id', $id)->where('pengrajin_id', Auth::id())->firstOrFail();
        
        $request->validate([
            'nama_produk'  => 'required|string',
            'ukuran_kecil' => 'required|string',
            'harga_kecil'  => 'required|numeric',
            'gambar'       => 'nullable|image|max:2048'
        ]);

        // Perbaikan: Sertakan kolom ukuran agar bisa diupdate
        $data = $request->only([
            'nama_produk', 'deskripsi', 
            'ukuran_kecil', 'harga_kecil', 
            'ukuran_sedang', 'harga_sedang', 
            'ukuran_besar', 'harga_besar'
        ]);

        if ($request->hasFile('gambar')) {
            if ($produk->gambar) Storage::disk('public')->delete($produk->gambar);
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);
        return redirect()->back()->with('success', 'Data produk diperbarui.');
    }

    public function hapusProduk($id) {
        $produk = Produk::where('id', $id)->where('pengrajin_id', Auth::id())->firstOrFail();
        if ($produk->gambar) Storage::disk('public')->delete($produk->gambar);
        $produk->delete();
        return redirect()->back()->with('success', 'Produk dihapus.');
    }

    public function updateStatus(Request $request, $id) {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status diperbarui.');
    }

    public function riwayat(Request $request) {
        $search = $request->query('search');
        $riwayat = Pesanan::with('user')->whereIn('status', ['Selesai', 'Dibatalkan', 'diekspedisi'])
            ->when($search, function ($query, $search) {
                return $query->where('id', 'LIKE', "%{$search}%")
                             ->orWhereHas('user', function($u) use ($search) { $u->where('name', 'LIKE', "%{$search}%"); });
            })->latest('updated_at')->get();
        return view('pengrajin.riwayat', compact('riwayat')); 
    }

    public function prosesPengerjaan() {
        $pesananAktif = Pesanan::with('user')->whereIn('status', ['Diproses', 'Dikerjakan'])->latest()->get();
        return view('pengrajin.proses_pengerjaan', compact('pesananAktif'));
    }

    public function detailRiwayat($id) {
        $pesanan = Pesanan::with('user')->findOrFail($id);
        return view('pengrajin.detail-riwayat', compact('pesanan'));
    }
}