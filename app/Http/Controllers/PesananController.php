<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Bahan;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::where('user_id', Auth::id())->latest()->get();
        return view('pesanan.index', compact('pesanan'));
    }

    public function create(Request $request) 
    {
        $produkId = $request->query('produk_id'); 
        $produkTerpilih = null;
        $produkData = null; 

        if ($produkId) {
            $p = Produk::find($produkId);
            if ($p) {
                $produkData = $p; 
                $produkTerpilih = $p->nama_produk;
            }
        }
        
        $produk = Produk::all();
        $listBahan = Bahan::all(); 
        $listTerminal = Terminal::all(); 
        
        return view('pesanan.create', compact('produk', 'produkTerpilih', 'produkData', 'listBahan', 'listTerminal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'ukuran' => 'required',
            'jenis_marmer' => 'required',
            'jumlah' => 'required|integer|min:1',
            'metode_pengambilan' => 'required',
            'terminal_id' => 'required_if:metode_pengambilan,dikirim', 
            'alamat_manual' => 'required_if:terminal_id,lainnya',
            'gambar_referensi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'total_harga' => 'required', 
            'biaya_pengiriman' => 'required',
        ]);

        $alamatFinal = 'Ambil di Tempat (Tulungagung)';
        if ($request->metode_pengambilan == 'dikirim') {
            if ($request->terminal_id === 'lainnya') {
                $alamatFinal = "Kirim ke (Manual): " . $request->alamat_manual;
            } else {
                $terminal = Terminal::find($request->terminal_id);
                if ($terminal) {
                    $alamatFinal = "Kirim via Bus ke: " . $terminal->nama_terminal;
                }
            }
        }

        $pathGambar = null;
        if ($request->hasFile('gambar_referensi')) {
            $pathGambar = $request->file('gambar_referensi')->store('pesanan_custom', 'public');
        }

        Pesanan::create([
            'user_id' => Auth::id(),
            'nama_produk' => $request->nama_produk,
            'ukuran' => $request->ukuran,
            'jenis_marmer' => $request->jenis_marmer,
            'catatan_khusus' => $request->catatan_khusus,
            'gambar_referensi' => $pathGambar,
            'jumlah' => $request->jumlah,
            'metode_pengambilan' => $request->metode_pengambilan,
            'alamat_pengiriman' => $alamatFinal,
            'biaya_pengiriman' => $request->biaya_pengiriman, 
            'total_harga' => $request->total_harga, 
            'status' => 'Menunggu Verifikasi Admin',
        ]);

        return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil diajukan!');
    }

    public function destroy($id)
    {
        $pesanan = Pesanan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        if ($pesanan->gambar_referensi) {
            Storage::disk('public')->delete($pesanan->gambar_referensi);
        }
        $pesanan->delete();
        return redirect()->route('pesanan.index')->with('success', 'Pesanan telah dihapus.');
    }

    public function selesai($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status' => 'Selesai']);
        return redirect()->back()->with('success', 'Pesanan selesai!');
    }
}