<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Bahan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $photos = $this->normalizeProgressPhotos($pesanan->{$field} ?? []);
        return is_array($photos) && count($photos) > 0;
    }

    private function normalizeProgressPhotos(mixed $photos): array
    {
        if (is_string($photos)) {
            $decodedPhotos = json_decode($photos, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $photos = $decodedPhotos;
            } elseif (trim($photos) !== '') {
                $photos = [$photos];
            } else {
                $photos = [];
            }
        }

        if (!is_array($photos)) {
            return [];
        }

        return array_values(array_unique(array_filter($photos, fn ($photo) => is_string($photo) && trim($photo) !== '')));
    }

    private function extractProgressFiles(Request $request): array
    {
        $files = $request->file('foto_progres');

        if ($files === null) {
            return [];
        }

        if (!is_array($files)) {
            return [$files];
        }

        $normalizedFiles = [];
        array_walk_recursive($files, function ($file) use (&$normalizedFiles) {
            if ($file !== null) {
                $normalizedFiles[] = $file;
            }
        });

        return $normalizedFiles;
    }

    private function uploadProgressPhotos(Pesanan $pesanan, string $field, array $files): array
    {
        $existingPhotos = $this->normalizeProgressPhotos($pesanan->{$field} ?? []);
        $uploadedPhotos = [];

        foreach ($files as $file) {
            $uploadedPhotos[] = $file->store('pesanan/progress', 'public');
        }

        return array_values(array_unique(array_merge($existingPhotos, $uploadedPhotos)));
    }

    private function removeProgressPhoto(Pesanan $pesanan, string $field, string $photoPath): array
    {
        $existingPhotos = $this->normalizeProgressPhotos($pesanan->{$field} ?? []);

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
        ]);

        if ($validated['status'] === 'diekspedisi' && $pesanan->status_pembayaran !== 'paid') {
            return redirect()->back()->withInput()->with('error', 'Pesanan belum bisa dikirim karena pembayaran belum lunas.');
        }

        $updatePayload = [
            'status' => $validated['status'],
            'tgl_update_proses' => now(),
        ];

        $pesanan->update($updatePayload);

        return redirect()->back()->with('success', 'Status diperbarui.');
    }

    public function uploadFotoProgres(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $validated = $request->validate([
            'status_target' => 'required|in:Dikerjakan,Selesai',
            'deleted_existing' => 'nullable|array',
            'deleted_existing.*' => 'string',
        ]);

        $files = $this->extractProgressFiles($request);
        Validator::make([
            'foto_progres' => $files,
        ], [
            'foto_progres' => 'nullable|array',
            'foto_progres.*' => 'file|image|mimes:jpg,jpeg,png|max:4096',
        ], [
            'foto_progres.*.file' => 'File foto progres tidak valid.',
            'foto_progres.*.image' => 'Setiap file foto progres harus berupa gambar.',
            'foto_progres.*.mimes' => 'Foto progres harus berformat jpg, jpeg, atau png.',
            'foto_progres.*.max' => 'Ukuran setiap foto progres maksimal 4MB.',
        ])->validate();

        $field = $this->getProgressPhotoField($validated['status_target']);
        if ($field === null) {
            return redirect()->back()->with('error', 'Status foto tidak valid.');
        }

        $photos = $this->normalizeProgressPhotos($pesanan->{$field} ?? []);

        foreach ($validated['deleted_existing'] ?? [] as $photoPath) {
            $photos = $this->removeProgressPhoto($pesanan->forceFill([$field => $photos]), $field, $photoPath);
        }

        $pesanan->forceFill([$field => $photos]);

        if (count($files) > 0) {
            $photos = $this->uploadProgressPhotos($pesanan, $field, $files);
        } elseif (empty($validated['deleted_existing'] ?? [])) {
            return redirect()->back()->with('error', 'Pilih minimal satu foto untuk diunggah.');
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
