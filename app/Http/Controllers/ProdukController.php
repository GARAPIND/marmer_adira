<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Bahan;
use App\Models\Terminal;
use App\Models\AlamatPembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    public function header()
    {
        $produk = Produk::with('bahan_kecil', 'bahan_sedang', 'bahan_besar')
            ->whereNotNull('gambar')
            ->where('nama_produk', '!=', '')
            ->latest()
            ->get()
            ->groupBy('nama_produk')
            ->map(function ($items) {

                $bahan = $items->flatMap(function ($item) {
                    return [
                        optional($item->bahan_kecil)->nama_bahan,
                        optional($item->bahan_sedang)->nama_bahan,
                        optional($item->bahan_besar)->nama_bahan,
                    ];
                })->filter()->unique()->values()->toArray();
                return [
                    'nama_produk' => $items->first()->nama_produk,
                    'gambar'      => $items->pluck('gambar')->toArray(),
                    'bahan'       => $bahan,
                ];
            })
            ->values();

        return view('produk.header', compact('produk'));
    }
    public function index($slug)
    {
        $produk = Produk::with('bahan_kecil', 'bahan_sedang', 'bahan_besar')
            ->whereNotNull('gambar')
            ->where('nama_produk', $slug)
            ->latest()
            ->get();

        return view('produk.index', compact('produk'));
    }

    public function showOrderForm(Request $request)
    {
        $listBahan    = Bahan::all();
        $listTerminal = Terminal::all();
        $listAlamat   = AlamatPembeli::where('user_id', Auth::id())->latest()->get();

        $idProduk       = $request->input('produk_id') ?? $request->input('id');
        $produkTerpilih = $request->input('produk');

        $dataProduk = Produk::with('bahan_kecil', 'bahan_sedang', 'bahan_besar')->find($idProduk);

        if (!$dataProduk && $produkTerpilih) {
            $dataProduk = Produk::where('nama_produk', trim($produkTerpilih))->first();
        }

        if ($dataProduk && !$produkTerpilih) {
            $produkTerpilih = $dataProduk->nama_produk;
        }

        return view('pesanan.create', compact('listBahan', 'listTerminal', 'listAlamat', 'produkTerpilih', 'dataProduk'));
    }

    public function katalogPengrajin()
    {
        $produk = Produk::where('pengrajin_id', Auth::id())->latest()->get();
        $bahans = Bahan::all();

        return view('pengrajin.katalog', compact('produk', 'bahans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk'   => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'ukuran_kecil'  => 'required|string',
            'harga_kecil'   => 'required|numeric',
            'ukuran_sedang' => 'nullable|string',
            'harga_sedang'  => 'nullable|numeric',
            'ukuran_besar'  => 'nullable|string',
            'harga_besar'   => 'nullable|numeric',
            'gambar'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->all();
        $data['pengrajin_id'] = Auth::id();
        $data['bahan']        = $data['bahan'] ?? 'Marmer';

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke katalog!');
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama_produk'   => 'required|string|max:255',
            'ukuran_kecil'  => 'required|string',
            'harga_kecil'   => 'required|numeric',
            'ukuran_sedang' => 'nullable|string',
            'harga_sedang'  => 'nullable|numeric',
            'ukuran_besar'  => 'nullable|string',
            'harga_besar'   => 'nullable|numeric',
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

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus');
    }

    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.show', compact('produk'));
    }
}
