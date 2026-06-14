<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\PhotoProsesPesanan;
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

    private function getProgressStatusLabelFromField(string $field): ?string
    {
        return match ($field) {
            'foto_dikerjakan' => 'Dikerjakan',
            'foto_selesai' => 'Selesai',
            default => null,
        };
    }

    private function getProgressPhotos(Pesanan $pesanan, string $field): array
    {
        $statusTarget = $this->getProgressStatusLabelFromField($field);
        if ($statusTarget === null) {
            return [];
        }

        if ($pesanan->relationLoaded('progressPhotos')) {
            return $pesanan->progressPhotos
                ->where('status_target', $statusTarget)
                ->pluck('photo_path')
                ->values()
                ->all();
        }

        return $pesanan->progressPhotos()
            ->where('status_target', $statusTarget)
            ->pluck('photo_path')
            ->values()
            ->all();
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
        $statusTarget = $this->getProgressStatusLabelFromField($field);
        if ($statusTarget === null) {
            return [];
        }

        $urutanTerakhir = (int) $pesanan->progressPhotos()
            ->where('status_target', $statusTarget)
            ->max('urutan');

        foreach ($files as $index => $file) {
            $photoPath = $file->store('pesanan/progress', 'public');
            $pesanan->progressPhotos()->create([
                'status_target' => $statusTarget,
                'photo_path' => $photoPath,
                'urutan' => $urutanTerakhir + $index + 1,
            ]);
        }

        return $this->getProgressPhotos($pesanan->fresh('progressPhotos'), $field);
    }

    private function removeProgressPhoto(Pesanan $pesanan, string $field, string $photoPath): array
    {
        $statusTarget = $this->getProgressStatusLabelFromField($field);
        if ($statusTarget === null) {
            return [];
        }

        $photo = $pesanan->progressPhotos()
            ->where('status_target', $statusTarget)
            ->where('photo_path', $photoPath)
            ->first();

        if ($photo instanceof PhotoProsesPesanan) {
            Storage::disk('public')->delete($photo->photo_path);
            $photo->delete();
        }

        return $this->getProgressPhotos($pesanan->fresh('progressPhotos'), $field);
    }

    public function dashboard()
    {
        $stats = [
            'baru'    => Pesanan::where('status', 'Diverifikasi')->count(),
            'proses'  => Pesanan::whereIn('status', ['Diproses', 'Dikerjakan'])->count(),
            'selesai' => Pesanan::whereIn('status', ['Selesai', 'diekspedisi'])->whereDate('tgl_update_proses', Carbon::today())->count(),
        ];
        return view('pengrajin.dashboard', compact('stats'));
    }

    public function pesananMasuk()
    {
        $pesanan = Pesanan::with('user')
            ->with('progressPhotos')
            ->with('items')
            ->where('status', 'Diverifikasi')
            ->where('status_pembayaran', '!=', 'no_paid')
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

        if ($validated['status'] === 'Selesai') {
            $updatePayload['tanggal_siap_dikirim'] = now();
        }

        if ($validated['status'] === 'diekspedisi') {
            $updatePayload['tanggal_dikirim'] = now();
        }

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

        $field = $this->getProgressPhotoField($validated['status_target']);
        if ($field === null) {
            return redirect()->back()->with('error', 'Status foto tidak valid.');
        }

        foreach ($validated['deleted_existing'] ?? [] as $photoPath) {
            $this->removeProgressPhoto($pesanan, $field, $photoPath);
        }

        if (count($files) === 0 && empty($validated['deleted_existing'] ?? [])) {
            return redirect()->back()->with('error', 'Pilih minimal satu foto untuk diunggah.');
        }

        $photos = $this->getProgressPhotos($pesanan->fresh('progressPhotos'), $field);

        if (count($files) > 0) {
            $photos = $this->uploadProgressPhotos($pesanan, $field, $files);
        }

        if (count($photos) === 0) {
            return redirect()->back()->with('error', 'Minimal harus ada satu foto pada daftar sebelum disimpan.');
        }

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

        $this->removeProgressPhoto($pesanan, $field, $validated['photo_path']);

        return redirect()->back()->with('success', 'Foto progres berhasil dihapus.');
    }

    public function riwayat(Request $request)
    {
        $search         = $request->query('search');
        $status         = $request->query('status');
        $tanggal   = $request->query('tanggal');

        $riwayat = Pesanan::with(['user', 'progressPhotos', 'items'])
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
        $pesananAktif = Pesanan::with(['user', 'progressPhotos', 'items'])->whereIn('status', ['Diproses', 'Dikerjakan'])->latest()->get();
        return view('pengrajin.proses_pengerjaan', compact('pesananAktif'));
    }

    public function detailRiwayat($id)
    {
        $pesanan = Pesanan::with(['user', 'progressPhotos', 'items'])->findOrFail($id);
        return view('pengrajin.detail-riwayat', compact('pesanan'));
    }
}
