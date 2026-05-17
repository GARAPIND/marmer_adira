<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Bahan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PengrajinController extends Controller
{
    private function getProgressPhotoField(string $status): ?string
    {
        return match ($status) {
            'Dikerjakan' => 'foto_dikerjakan',
            'Selesai' => 'foto_selesai',
            default => null,
        };
    }

    private function hasProgressPhotos(Pesanan $pesanan, string $field): bool
    {
        $photos = $pesanan->{$field} ?? [];
        return is_array($photos) && count($photos) > 0;
    }

    private function uploadProgressPhotos(Request $request, Pesanan $pesanan, string $field): array
    {
        $existingPhotos = $pesanan->{$field} ?? [];

        if (!is_array($existingPhotos)) {
            $existingPhotos = [];
        }

        $uploadedPhotos = [];
        foreach ($request->file('foto_progres', []) as $file) {
            $uploadedPhotos[] = $file->store('pesanan/progress', 'public');
        }

        return array_values(array_merge($existingPhotos, $uploadedPhotos));
    }

    private function removeProgressPhoto(Pesanan $pesanan, string $field, string $photoPath): array
    {
        $existingPhotos = $pesanan->{$field} ?? [];

        if (!is_array($existingPhotos)) {
            $existingPhotos = [];
        }

        $remainingPhotos = array_values(array_filter($existingPhotos, fn ($photo) => $photo !== $photoPath));

        if (in_array($photoPath, $existingPhotos, true)) {
            Storage::disk('public')->delete($photoPath);
        }

        return $remainingPhotos;
    }

    public function dashboard()
    {
        $stats = [
            'baru'    => Pesanan::where('status', 'Diverifikasi')->count(),
            'proses'  => Pesanan::whereIn('status', ['Diproses', 'Dikerjakan'])->count(),
            'selesai' => Pesanan::where('status', 'Selesai')->whereDate('tgl_update_proses', Carbon::today())->count(),
        ];
        return view('pengrajin.dashboard', compact('stats'));
    }

    public function pesananMasuk()
    {
        $pesanan = Pesanan::with('user')
            ->where('status', 'Diverifikasi')
            ->latest()
            ->get();
        return view('pengrajin.pesanan_masuk', compact('pesanan'));
    }

    public function katalog()
    {
        $produk = Produk::with('bahan_kecil', 'bahan_sedang', 'bahan_besar')->where('pengrajin_id', Auth::id())->latest()->get();
        $bahans = Bahan::all();
        return view('pengrajin.katalog', compact('produk', 'bahans'));
    }

    public function simpanProduk(Request $request)
    {
        $rules = [
            'nama_produk' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'gambar'      => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ];

        $sizes = ['kecil', 'sedang', 'besar'];
        foreach ($sizes as $size) {
            if ($size === 'kecil') {
                $rules["ukuran_$size"]     = 'required|string';
                $rules["harga_$size"]      = 'required|numeric';
                $rules["berat_$size"]      = 'required|numeric';
                $rules["bahan_{$size}_id"] = 'required|exists:bahan,id';
            } else {
                $rules["ukuran_$size"]     = 'nullable|string';
                $rules["harga_$size"]      = 'nullable|numeric|required_with:ukuran_' . $size;
                $rules["berat_$size"]      = 'nullable|numeric|required_with:ukuran_' . $size;
                $rules["bahan_{$size}_id"] = 'nullable|exists:bahan,id|required_with:ukuran_' . $size;
            }
        }

        $validated = $request->validate($rules);

        $data = $request->only([
            'nama_produk',
            'deskripsi',

            'ukuran_kecil',
            'harga_kecil',
            'berat_kecil',
            'bahan_kecil_id',

            'ukuran_sedang',
            'harga_sedang',
            'berat_sedang',
            'bahan_sedang_id',

            'ukuran_besar',
            'harga_besar',
            'berat_besar',
            'bahan_besar_id',
        ]);

        $data['stok'] = 0;
        $data['pengrajin_id'] = Auth::id();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function updateProduk(Request $request, $id)
    {
        $produk = Produk::where('id', $id)
            ->where('pengrajin_id', Auth::id())
            ->firstOrFail();

        $rules = [
            'nama_produk' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'gambar'      => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ];

        $sizes = ['kecil', 'sedang', 'besar'];

        foreach ($sizes as $size) {
            if ($size === 'kecil') {
                $rules["ukuran_$size"]     = 'required|string';
                $rules["harga_$size"]      = 'required|numeric';
                $rules["berat_$size"]      = 'required|numeric';
                $rules["bahan_{$size}_id"] = 'required|exists:bahan,id';
            } else {
                $rules["ukuran_$size"]     = 'nullable|string';
                $rules["harga_$size"]      = 'nullable|numeric|required_with:ukuran_' . $size;
                $rules["berat_$size"]      = 'nullable|numeric|required_with:ukuran_' . $size;
                $rules["bahan_{$size}_id"] = 'nullable|exists:bahan,id|required_with:ukuran_' . $size;
            }
        }

        $validated = $request->validate($rules);

        $data = $request->only([
            'nama_produk',
            'deskripsi',

            'ukuran_kecil',
            'harga_kecil',
            'berat_kecil',
            'bahan_kecil_id',

            'ukuran_sedang',
            'harga_sedang',
            'berat_sedang',
            'bahan_sedang_id',

            'ukuran_besar',
            'harga_besar',
            'berat_besar',
            'bahan_besar_id',
        ]);

        if ($request->hasFile('gambar')) {
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->back()->with('success', 'Data produk diperbarui.');
    }

    public function hapusProduk($id)
    {
        $produk = Produk::where('id', $id)->where('pengrajin_id', Auth::id())->firstOrFail();
        if ($produk->gambar) Storage::disk('public')->delete($produk->gambar);
        $produk->delete();
        return redirect()->back()->with('success', 'Produk dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:Diproses,Dikerjakan,Selesai,diekspedisi',
            'foto_progres' => 'nullable|array',
            'foto_progres.*' => 'image|mimes:jpg,jpeg,png|max:4096',
        ], [
            'foto_progres.*.image' => 'Setiap file foto progres harus berupa gambar.',
            'foto_progres.*.mimes' => 'Foto progres harus berformat jpg, jpeg, atau png.',
            'foto_progres.*.max' => 'Ukuran setiap foto progres maksimal 4MB.',
        ]);

        $photoField = $this->getProgressPhotoField($validated['status']);
        if ($photoField !== null && !$request->hasFile('foto_progres') && !$this->hasProgressPhotos($pesanan, $photoField)) {
            return redirect()->back()->withInput()->with('error', 'Foto progres wajib diunggah terlebih dahulu sebelum status ini disimpan.');
        }

        if ($validated['status'] === 'diekspedisi' && $pesanan->status_pembayaran !== 'paid') {
            return redirect()->back()->withInput()->with('error', 'Pesanan belum bisa dikirim karena pembayaran belum lunas.');
        }

        $updatePayload = [
            'status' => $validated['status'],
            'tgl_update_proses' => now(),
        ];

        if ($validated['status'] === 'Dikerjakan' && $request->hasFile('foto_progres')) {
            $updatePayload['foto_dikerjakan'] = $this->uploadProgressPhotos($request, $pesanan, 'foto_dikerjakan');
        }

        if ($validated['status'] === 'Selesai' && $request->hasFile('foto_progres')) {
            $updatePayload['foto_selesai'] = $this->uploadProgressPhotos($request, $pesanan, 'foto_selesai');
        }

        $pesanan->update($updatePayload);

        return redirect()->back()->with('success', 'Status diperbarui.');
    }

    public function uploadFotoProgres(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $validated = $request->validate([
            'status_target' => 'required|in:Dikerjakan,Selesai',
            'foto_progres' => 'nullable|array',
            'foto_progres.*' => 'image|mimes:jpg,jpeg,png|max:4096',
            'deleted_existing' => 'nullable|array',
            'deleted_existing.*' => 'string',
        ], [
            'foto_progres.*.image' => 'Setiap file foto progres harus berupa gambar.',
            'foto_progres.*.mimes' => 'Foto progres harus berformat jpg, jpeg, atau png.',
            'foto_progres.*.max' => 'Ukuran setiap foto progres maksimal 4MB.',
        ]);

        $field = $this->getProgressPhotoField($validated['status_target']);
        if ($field === null) {
            return redirect()->back()->with('error', 'Status foto tidak valid.');
        }

        $photos = $pesanan->{$field} ?? [];
        if (!is_array($photos)) {
            $photos = [];
        }

        foreach ($validated['deleted_existing'] ?? [] as $photoPath) {
            $photos = $this->removeProgressPhoto($pesanan->forceFill([$field => $photos]), $field, $photoPath);
        }

        $pesanan->forceFill([$field => $photos]);

        if ($request->hasFile('foto_progres')) {
            $photos = $this->uploadProgressPhotos($request, $pesanan, $field);
        }

        if (count($photos) === 0) {
            return redirect()->back()->with('error', 'Minimal harus ada satu foto pada daftar sebelum disimpan.');
        }

        $pesanan->update([$field => $photos]);

        return redirect()->back()->with('success', 'Foto progres berhasil diunggah.');
    }

    public function hapusFotoProgres(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $validated = $request->validate([
            'status_target' => 'required|in:Dikerjakan,Selesai',
            'photo_path' => 'required|string',
        ]);

        $field = $this->getProgressPhotoField($validated['status_target']);
        if ($field === null) {
            return redirect()->back()->with('error', 'Status foto tidak valid.');
        }

        $pesanan->update([
            $field => $this->removeProgressPhoto($pesanan, $field, $validated['photo_path']),
        ]);

        return redirect()->back()->with('success', 'Foto progres berhasil dihapus.');
    }

    public function riwayat(Request $request)
    {
        $search         = $request->query('search');
        $status         = $request->query('status');
        $tanggal   = $request->query('tanggal');

        $riwayat = Pesanan::with('user')
            ->whereIn('status', ['Selesai', 'Dibatalkan', 'diekspedisi'])
            ->when($search, function ($query, $search) {
                return $query->where('id', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'LIKE', "%{$search}%");
                    });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($tanggal, function ($query, $tanggal) {
                return $query->whereDate('tgl_update_proses', $tanggal);
            })
            ->latest('updated_at')
            ->get();

        return view('pengrajin.riwayat', compact('riwayat'));
    }

    public function prosesPengerjaan()
    {
        $pesananAktif = Pesanan::with('user')->whereIn('status', ['Diproses', 'Dikerjakan'])->latest()->get();
        return view('pengrajin.proses_pengerjaan', compact('pesananAktif'));
    }

    public function detailRiwayat($id)
    {
        $pesanan = Pesanan::with('user')->findOrFail($id);
        return view('pengrajin.detail-riwayat', compact('pesanan'));
    }
}
