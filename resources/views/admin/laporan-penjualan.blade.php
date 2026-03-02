@extends('layouts.app')

@section('content')
{{-- Gunakan style yang sama dengan di atas atau taruh di layout utama --}}
<style>
    :root { --adira-gold: #C5A47E; --adira-dark: #2c3e50; }
    .text-gold { color: var(--adira-gold) !important; }
    .btn-gold { background-color: var(--adira-gold); border-color: var(--adira-gold); color: white; transition: all 0.3s ease; }
    .btn-gold:hover { background-color: #b08d44; border-color: #b08d44; color: white; }
    .btn-outline-elegant { color: var(--adira-dark); border-color: #dee2e6; background-color: white; }
    .btn-outline-elegant:hover { background-color: #f8f9fa; border-color: var(--adira-dark); color: var(--adira-dark); }
    .card-filter { border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border-radius: 16px; }
    .table-elegant thead th { background-color: var(--adira-dark); color: white; font-weight: 600; border: none; }
    .table-elegant tbody td { vertical-align: middle; padding: 1.5rem 1rem; border-color: #f1f1f1; }
    .stat-number { font-size: 2rem; font-weight: 800; color: var(--adira-dark); }
</style>

<div class="container py-5 mt-3">
    <div class="d-flex align-items-center mb-4">
        <i class="fas fa-chart-line fa-2x me-3 text-gold"></i>
        <h2 class="fw-bold m-0" style="font-family: 'Inter', sans-serif; color: var(--adira-dark);">Laporan Penjualan</h2>
    </div>

    <div class="card card-filter mb-5 bg-white">
        <div class="card-body p-4">
             <h5 class="fw-bold mb-3 text-muted small text-uppercase">Filter Periode & Transaksi</h5>
            <form action="#" method="GET">
                <div class="row align-items-end g-3">
                    <div class="col-md-5">
                        <label class="fw-bold small mb-2 text-secondary">Rentang Tanggal:</label>
                        <div class="input-group">
                             <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="date" name="tgl_mulai" class="form-control border-start-0">
                            <span class="input-group-text bg-white border-start-0 border-end-0">s/d</span>
                             <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="date" name="tgl_akhir" class="form-control border-start-0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold small mb-2 text-secondary">Jenis Transaksi:</label>
                        <select name="jenis_laporan" class="form-select">
                            <option value="Semua">Semua Transaksi (Lunas & DP)</option>
                            <option value="Lunas">Hanya Pelunasan</option>
                             <option value="DP">Hanya DP</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-gold w-100 py-2 fw-bold shadow-sm">
                            <i class="fas fa-filter me-2"></i>Hitung Penjualan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-elegant mb-0 text-center">
                <thead>
                    <tr>
                        <th class="py-3">Jumlah Produk Terjual</th>
                        <th class="py-3" style="background-color: #1e4d40;">Total Nilai Penjualan</th>
                        <th class="py-3">Transaksi Berhasil</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="stat-number">250</span> <span class="text-muted small">Unit</span></td>
                        {{-- Highlight Nilai Uang dengan warna Emas/Hijau Tua --}}
                        <td class="bg-success bg-opacity-10">
                            <span class="stat-number" style="color: #1e4d40; font-size: 2.2rem;">Rp 500.000.000</span>
                        </td>
                        <td><span class="stat-number">180</span> <span class="text-muted small">Kali</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button class="btn btn-outline-elegant px-4 shadow-sm fw-bold rounded-pill">
            <i class="fas fa-file-pdf text-danger me-2"></i>Export PDF
        </button>
        <button class="btn btn-outline-elegant px-4 shadow-sm fw-bold rounded-pill">
            <i class="fas fa-file-excel text-success me-2"></i>Export Excel
        </button>
    </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection