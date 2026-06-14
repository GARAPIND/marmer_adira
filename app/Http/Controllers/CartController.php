<?php

namespace App\Http\Controllers;

use App\Models\AlamatPembeli;
use App\Models\CartItem;
use App\Models\Bahan;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
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

    private function itemRules(Request $request): array
    {
        return [
            'produk_id' => 'nullable|integer',
            'nama_produk' => 'required|string|max:255',
            'ukuran' => 'required',
            'jenis_marmer' => 'required',
            'jumlah' => 'required|integer|min:1',
            'berat_satuan' => 'nullable|numeric|min:0',
            'harga_satuan' => 'nullable|numeric|min:0',
            'subtotal' => 'nullable|numeric|min:0',
            'gambar_referensi' => 'nullable|array|max:5',
            'gambar_referensi.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->latest()->get();
        $listAlamat = AlamatPembeli::where('user_id', Auth::id())->latest()->get();
        $listTerminal = Terminal::all();
        $listBahan = Bahan::all();

        return view('cart.index', compact('cartItems', 'listAlamat', 'listTerminal', 'listBahan'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'berat_satuan' => $this->normalizeDecimalInput($request->input('berat_satuan')),
        ]);

        $request->validate($this->itemRules($request), [
            'berat_satuan.numeric' => 'Berat satuan harus berupa angka.',
            'gambar_referensi.max' => 'Maksimal 5 gambar referensi yang dapat diunggah.',
            'gambar_referensi.*.image' => 'Setiap file harus berupa gambar.',
            'gambar_referensi.*.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'gambar_referensi.*.max' => 'Ukuran setiap gambar maksimal 2MB.',
        ]);

        $pathsGambar = [];
        if ($request->hasFile('gambar_referensi')) {
            foreach ($request->file('gambar_referensi') as $file) {
                $pathsGambar[] = $file->store('pesanan_custom', 'public');
            }
        }

        $jumlah = (int) $request->jumlah;
        $beratSatuan = (float) ($request->berat_satuan ?? 0);
        $hargaSatuan = (int) ($request->harga_satuan ?? 0);
        $subtotal = (int) ($request->subtotal ?? ($hargaSatuan * $jumlah));

        CartItem::create([
            'user_id' => Auth::id(),
            'produk_id' => $request->filled('produk_id') ? (int) $request->produk_id : null,
            'is_custom' => !$request->filled('produk_id'),
            'nama_produk' => $request->nama_produk,
            'ukuran' => $request->ukuran,
            'jenis_marmer' => $request->jenis_marmer,
            'catatan_khusus' => $request->catatan_khusus,
            'gambar_referensi' => !empty($pathsGambar) ? $pathsGambar : null,
            'jumlah' => $jumlah,
            'berat_satuan' => $beratSatuan,
            'total_berat' => $beratSatuan * $jumlah,
            'harga_satuan' => $hargaSatuan,
            'subtotal' => $subtotal,
        ]);

        return redirect()->route('cart.index')->with('success', 'Barang berhasil ditambahkan ke keranjang.');
    }

    public function destroy($id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Barang berhasil dihapus dari keranjang.');
    }
}
