<?php

namespace App\Http\Controllers;

use App\Models\Pesanan; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembeliController extends Controller
{
    /**
     * Menampilkan Dashboard Pembeli dengan Statistik Real-time
     */
    public function dashboard()
    {
        $userId = Auth::id();

        // 1. Menghitung data statistik untuk Card Dashboard
        $stats = [
            // Card: VERIFIKASI ADMIN
            'verifikasi' => Pesanan::where('user_id', $userId)
                            ->where('status', 'Menunggu Verifikasi Admin')
                            ->count(),

            // Card: SEDANG DIPROSES
            // Menghitung status aktif termasuk pesanan yang sudah selesai dibuat
            // tetapi masih menunggu pelunasan dari pembeli.
            'proses'     => Pesanan::where('user_id', $userId)
                            ->where(function ($query) {
                                $query->whereIn('status', ['Diverifikasi', 'Diproses', 'Dikerjakan', 'diekspedisi'])
                                    ->orWhere(function ($subQuery) {
                                        $subQuery->where('status', 'Selesai')
                                            ->where('status_pembayaran', '!=', 'paid');
                                    });
                            })
                            ->count(),

            // Card: PESANAN SELESAI
            'selesai'    => Pesanan::where('user_id', $userId)
                            ->where('status', 'Selesai')
                            ->where('status_pembayaran', 'paid')
                            ->count(),
        ];

        // 2. Mengambil data pesanan untuk tabel "Aktivitas Pesanan Terbaru"
        // Menggunakan latest() agar pesanan terbaru muncul di paling atas
        $pesanan = Pesanan::with('progressPhotos')->where('user_id', $userId)->latest()->get();

        // 3. Mengarahkan ke view dengan mengirimkan variabel stats dan pesanan
        return view('pembeli.dashboard', compact('stats', 'pesanan'));
    }
}
