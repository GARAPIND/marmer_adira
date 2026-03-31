<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlamatPembeliController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PengrajinController;

// --- 1. HALAMAN UTAMA & PUBLIK ---
Route::get('/', function () {
    return view('home');
})->name('home');
Route::get('/katalog', [ProdukController::class, 'header'])->name('produk.index');
Route::get('/detail-katalog/{slug}', [ProdukController::class, 'index'])->name('produk.detail');
Route::get('/produk/{id}', [ProdukController::class, 'show'])->name('produk.show');

// --- 2. AUTHENTICATION ---
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')->name('login.proses');
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register')->name('register.proses');
    Route::post('/logout', 'logout')->name('logout');

    // --- LOGIN GOOGLE ---
    Route::get('auth/google', 'redirectToGoogle')->name('google.login');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});

Route::post('/midtrans/callback', [PesananController::class, 'midtransCallback'])->name('midtrans.callback');
// --- 3. KHUSUS USER TERAUTENTIKASI ---
Route::middleware(['auth'])->group(function () {
    Route::get('/pesanan/{id}/snap-token', [PesananController::class, 'getSnapToken'])->name('pesanan.snapToken');
    Route::post('/pesanan/{id}/payment-success', [PesananController::class, 'paymentSuccess'])->name('pesanan.paymentSuccess');

    // --- A. ROLE PEMBELI ---
    Route::get('/dashboard', [PembeliController::class, 'dashboard'])->name('pembeli.dashboard');
    Route::prefix('pesanan')->name('pesanan.')->group(function () {
        Route::get('/', [PesananController::class, 'index'])->name('index');
        Route::get('/create', [ProdukController::class, 'showOrderForm'])->name('create');
        Route::post('/', [PesananController::class, 'store'])->name('store');
        Route::delete('/{id}', [PesananController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/status', [PesananController::class, 'updateStatus'])->name('updateStatus');

        Route::patch('/{id}/selesai', [PesananController::class, 'selesai'])->name('selesai');
    });
    Route::prefix('alamat')->name('alamat.')->group(function () {
        Route::get('/', [AlamatPembeliController::class, 'index'])->name('index');
        Route::post('/', [AlamatPembeliController::class, 'store'])->name('store');
        Route::put('/{id}', [AlamatPembeliController::class, 'update'])->name('update');
        Route::delete('/{id}', [AlamatPembeliController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/utama', [AlamatPembeliController::class, 'setUtama'])->name('utama');
        Route::get('/list', [AlamatPembeliController::class, 'getList'])->name('list');
    });

    Route::prefix('ongkir')->name('ongkir.')->group(function () {
        Route::get('/provinsi', [AlamatPembeliController::class, 'getProvinsi'])->name('provinsi');
        Route::get('/kota', [AlamatPembeliController::class, 'getKota'])->name('kota');
        Route::get('/kecamatan', [AlamatPembeliController::class, 'getKecamatan'])->name('kecamatan');
        Route::post('/hitung', [AlamatPembeliController::class, 'hitungOngkir'])->name('hitung');
    });

    // --- B. ROLE ADMIN ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/pesanan/{id}/update', [AdminController::class, 'updatePesanan'])->name('pesanan.update');
        Route::post('/pesanan/{id}/selesai', [AdminController::class, 'selesaiPesanan'])->name('pesanan.selesai');

        Route::resource('/pengguna', PenggunaController::class);

        Route::get('/produk', [AdminController::class, 'manajemenProduk'])->name('produk.index');
        Route::get('/pesanan/baru', [AdminController::class, 'pesananBaru'])->name('pesanan.baru');

        // --- MANAJEMEN MASTER BAHAN (Sync dengan AdminController) ---
        Route::post('/bahan/simpan', [AdminController::class, 'simpanBahan'])->name('bahan.simpan');
        Route::post('/bahan/update/{id}', [AdminController::class, 'updateBahan'])->name('bahan.update');
        Route::delete('/bahan/hapus/{id}', [AdminController::class, 'hapusBahan'])->name('bahan.hapus');

        // --- SEKSI LAPORAN (Sync dengan AdminController) ---
        Route::prefix('laporan')->name('laporan.')->group(function () {
            // Laporan Pesanan
            Route::get('/pesanan', [AdminController::class, 'laporanPesanan'])->name('pesanan');
            Route::get('/pesanan/pdf', [AdminController::class, 'exportPesananPdf'])->name('pesanan.pdf');
            Route::get('/pesanan/excel', [AdminController::class, 'exportPesananExcel'])->name('pesanan.excel');

            // Laporan Keuangan
            Route::get('/keuangan', [AdminController::class, 'laporanKeuangan'])->name('keuangan');
            Route::get('/keuangan/pdf', [AdminController::class, 'exportKeuanganPdf'])->name('keuangan.pdf');
            Route::get('/keuangan/excel', [AdminController::class, 'exportKeuanganExcel'])->name('keuangan.excel');

            // Laporan Tambahan
            Route::get('/pengguna', [AdminController::class, 'laporanPengguna'])->name('pengguna');
            Route::get('pengguna/pdf', [AdminController::class, 'exportPenggunaPdf'])->name('pengguna.pdf');
            Route::get('pengguna/excel', [AdminController::class, 'exportPenggunaExcel'])->name('pengguna.excel');

            Route::get('/penjualan', [AdminController::class, 'laporanPenjualan'])->name('penjualan');
        });
    });

    // --- C. ROLE PENGRAJIN ---
    Route::middleware(['role:pengrajin'])->prefix('pengrajin')->name('pengrajin.')->group(function () {
        Route::get('/dashboard', [PengrajinController::class, 'dashboard'])->name('dashboard');
        Route::get('/pesanan-masuk', [PengrajinController::class, 'pesananMasuk'])->name('pesanan.masuk');
        Route::match(['post', 'patch'], '/pesanan/update/{id}', [PengrajinController::class, 'updateStatus'])->name('update.status');
        Route::get('/proses-pengerjaan', [PengrajinController::class, 'prosesPengerjaan'])->name('proses');
        Route::get('/riwayat', [PengrajinController::class, 'riwayat'])->name('riwayat');
        Route::get('/riwayat/{id}', [PengrajinController::class, 'detailRiwayat'])->name('riwayat.detail');
        Route::get('/katalog', [PengrajinController::class, 'katalog'])->name('katalog');
        Route::post('/katalog/simpan', [PengrajinController::class, 'simpanProduk'])->name('katalog.simpan');
        Route::post('/katalog/update/{id}', [PengrajinController::class, 'updateProduk'])->name('katalog.update');
        Route::delete('/katalog/hapus/{id}', [PengrajinController::class, 'hapusProduk'])->name('katalog.hapus');
    });
});
