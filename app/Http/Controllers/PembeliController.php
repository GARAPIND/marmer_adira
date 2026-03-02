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
            // Menghitung status Diverifikasi (siap dikerjakan), Diproses, dan Dikerjakan
            'proses'     => Pesanan::where('user_id', $userId)
                            ->whereIn('status', ['Diverifikasi', 'Diproses', 'Dikerjakan'])
                            ->count(),

            // Card: PESANAN SELESAI
            'selesai'    => Pesanan::where('user_id', $userId)
                            ->where('status', 'Selesai')
                            ->count(),
        ];

        // 2. Mengambil data pesanan untuk tabel "Aktivitas Pesanan Terbaru"
        // Menggunakan latest() agar pesanan terbaru muncul di paling atas
        $pesanan = Pesanan::where('user_id', $userId)->latest()->get();

        // 3. Mengarahkan ke view dengan mengirimkan variabel stats dan pesanan
        return view('pembeli.dashboard', compact('stats', 'pesanan'));
    }
}