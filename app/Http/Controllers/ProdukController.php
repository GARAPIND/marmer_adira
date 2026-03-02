<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Bahan;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    /**
     * UNTUK PEMBELI: Menampilkan Katalog Masterpiece (Gallery)
     */
    public function index()
    {
        $produk = Produk::with('bahan_kecil', 'bahan_sedang', 'bahan_besar')
            ->whereNotNull('gambar')
            ->where('nama_produk', '!=', '')
            ->latest()
            ->get();

        return view('produk.index', compact('produk'));
    }

    /**
     * UNTUK PEMBELI: Menampilkan Form Pemesanan Custom
     * PERBAIKAN: Menggunakan input() agar lebih kuat menangkap parameter URL (?produk_id=... atau ?id=...)
     */
    public function showOrderForm(Request $request)
    {
        $listBahan = Bahan::all();
        $listTerminal = Terminal::all();

        // Menangkap ID (mencoba produk_id dulu, jika kosong coba id)
        $idProduk = $request->input('produk_id') ?? $request->input('id');
        $produkTerpilih = $request->input('produk');

        // Cari data lengkap produk berdasarkan ID
        $dataProduk = Produk::find($idProduk);

        // Fallback: Jika ID tidak ketemu (mungkin karena ganti database), cari berdasarkan nama
        if (!$dataProduk && $produkTerpilih) {
            $dataProduk = Produk::where('nama_produk', trim($produkTerpilih))->first();
        }

        // Isi otomatis nama produk jika data ditemukan tetapi variabel produk di URL kosong
        if ($dataProduk && !$produkTerpilih) {
            $produkTerpilih = $dataProduk->nama_produk;
        }

        return view('pesanan.create', compact('listBahan', 'listTerminal', 'produkTerpilih', 'dataProduk'));
    }

    /**
     * UNTUK PENGRAJIN: Menampilkan Tabel Manajemen
     */
    public function katalogPengrajin()
    {
        $produk = Produk::where('pengrajin_id', Auth::id())->latest()->get();
        $bahans = Bahan::all();

        return view('pengrajin.katalog', compact('produk', 'bahans'));
    }

    /**
     * Menyimpan data produk baru dari Pengrajin
     * PERBAIKAN: Menangkap semua level ukuran agar masuk ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk'  => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'ukuran_kecil' => 'required|string',
            'harga_kecil'  => 'required|numeric',
            'ukuran_sedang' => 'nullable|string',
            'harga_sedang' => 'nullable|numeric',
            'ukuran_besar' => 'nullable|string',
            'harga_besar'  => 'nullable|numeric',
            'gambar'       => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->all();
        $data['pengrajin_id'] = Auth::id();
        // Menjaga default bahan jika tidak diisi
        $data['bahan'] = $data['bahan'] ?? 'Marmer';

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke katalog!');
    }

    /**
     * Mengupdate data produk
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama_produk'  => 'required|string|max:255',
            'ukuran_kecil' => 'required|string',
            'harga_kecil'  => 'required|numeric',
            'ukuran_sedang' => 'nullable|string',
            'harga_sedang' => 'nullable|numeric',
            'ukuran_besar' => 'nullable|string',
            'harga_besar'  => 'nullable|numeric',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Menghapus produk
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus');
    }
}
