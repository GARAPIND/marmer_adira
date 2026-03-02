@extends('layouts.app')

@section('content')
<style>
    :root {
        --adira-gold: #C5A47E;
        --adira-dark: #2c3e50;
    }
    .text-gold { color: var(--adira-gold) !important; }
    
    .page-header-elegant {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }

    .marble-icon-box {
        width: 60px;
        height: 60px;
        background: rgba(197, 164, 126, 0.15);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--adira-gold);
        font-size: 1.8rem;
    }

    .card-filter {
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-radius: 16px;
    }
    
    .table-elegant thead th {
        background-color: var(--adira-dark);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        padding: 1.25rem;
        border: none;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: var(--adira-dark);
    }
    .btn-gold {
        background-color: var(--adira-gold);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 50px;
    }
    .btn-gold:hover { background-color: #b08d44; color: white; }
</style>

<div class="container py-5 mt-2 animate__animated animate__fadeIn">
    {{-- HEADER DENGAN LOGO GOLD --}}
    <div class="page-header-elegant d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="marble-icon-box me-3 shadow-sm">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div>
                <h2 class="fw-bold mb-0 text-dark" style="border-left: 5px solid #000; padding-left: 15px;">Laporan Pesanan</h2>
                <p class="text-muted small mb-0">Ringkasan statistik data pesanan masuk dan status operasional</p>
            </div>
        </div>
    </div>

    {{-- FILTER BOX --}}
    <div class="card card-filter mb-5 bg-white shadow-sm">
        <div class="card-body p-4">
            {{-- Submit GET ke halaman yang sama untuk memproses filter --}}
            <form action="" method="GET">
                <div class="row align-items-end g-3">
                    <div class="col-md-8">
                        <label class="fw-bold small mb-2 text-secondary">Rentang Tanggal:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-gold"><i class="fas fa-calendar-alt"></i></span>
                            {{-- Input mempertahankan nilai tanggal setelah klik Tampilkan --}}
                            <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" class="form-control border-start-0 ps-0 shadow-none">
                            <span class="input-group-text bg-white border-0">s/d</span>
                            <input type="date" name="tgl_akhir" value="{{ request('tgl_akhir') }}" class="form-control shadow-none">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-gold w-100 py-2 fw-bold shadow-sm">
                            <i class="fas fa-filter me-2"></i> Tampilkan Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL STATISTIK DINAMIS --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
        <div class="table-responsive">
            <table class="table table-elegant mb-0 text-center">
                <thead>
                    <tr>
                        <th class="py-3">Pesanan Masuk</th>
                        <th class="py-3 bg-info bg-opacity-10 text-dark">Diverifikasi</th>
                        <th class="py-3 bg-warning bg-opacity-10 text-dark">Diproses</th>
                        <th class="py-3 bg-success bg-opacity-10 text-dark">Selesai</th>
                        <th class="py-3 bg-danger bg-opacity-10 text-dark">Dibatalkan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        {{-- Data statistik ditampilkan dari variabel $stats --}}
                        <td><span class="stat-number">{{ $stats['total'] }}</span></td>
                        <td><span class="stat-number text-info">{{ $stats['diverifikasi'] }}</span></td>
                        <td><span class="stat-number text-warning">{{ $stats['diproses'] }}</span></td>
                        <td><span class="stat-number text-success">{{ $stats['selesai'] }}</span></td>
                        <td><span class="stat-number text-danger text-opacity-50">{{ $stats['dibatalkan'] }}</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- TOMBOL EXPORT AKTIF --}}
    <div class="d-flex justify-content-end gap-2">
        {{-- Parameter query request() ditambahkan agar PDF/Excel mengikuti filter di layar --}}
        <a href="{{ route('admin.laporan.pesanan.pdf', request()->query()) }}" class="btn btn-outline-dark px-4 shadow-sm fw-bold rounded-pill">
            <i class="fas fa-file-pdf text-danger me-2"></i> Export PDF
        </a>
        
        <a href="{{ route('admin.laporan.pesanan.excel', request()->query()) }}" class="btn btn-outline-dark px-4 shadow-sm fw-bold rounded-pill">
            <i class="fas fa-file-excel text-success me-2"></i> Export Excel
        </a>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endsection