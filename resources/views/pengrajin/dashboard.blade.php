@extends('layouts.app')

@section('content')
<style>
    :root {
        --adira-gold: #C5A47E;
        --adira-dark: #2c3e50;
    }
    
    .page-header-elegant {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 2.5rem;
    }

    .marble-icon-box {
        width: 60px; height: 60px;
        background: rgba(197, 164, 126, 0.15);
        border-radius: 15px;
        display: flex; align-items: center; justify-content: center;
        color: var(--adira-gold); font-size: 1.8rem;
    }

    /* Card Stat Gaya Sketsa Namun Premium */
    .card-stat-big {
        border: none;
        border-radius: 24px;
        background: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        padding: 4rem 1rem; /* Ukuran kotak besar sesuai sketsa */
        text-align: center;
        border-bottom: 6px solid transparent;
        height: 100%;
    }
    
    .card-stat-big:hover { 
        transform: translateY(-12px); 
        box-shadow: 0 20px 40px rgba(197, 164, 126, 0.2);
    }
    
    .stat-label {
        font-size: 1.2rem;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 1.5rem;
    }

    .stat-value {
        font-size: 5.5rem; /* Angka sangat besar sesuai gambar sketsa */
        font-weight: 800;
        color: var(--adira-dark);
        line-height: 1;
        margin: 0;
    }

    /* Warna Aksen Bawah */
    .border-baru { border-bottom-color: #3498db; }
    .border-proses { border-bottom-color: var(--adira-gold); }
    .border-selesai { border-bottom-color: #27ae60; }
</style>

<div class="container py-5 mt-2 animate__animated animate__fadeIn">
    {{-- HEADER ELEGAN --}}
    <div class="page-header-elegant d-flex align-items-center shadow-sm">
        <div class="marble-icon-box me-3 shadow-sm">
            <i class="fas fa-tools"></i>
        </div>
        <div>
            <h2 class="fw-bold mb-0 text-dark" style="border-left: 5px solid #000; padding-left: 15px;">Dashboard Pengrajin</h2>
            <p class="text-muted small mb-0">Ringkasan status pengerjaan marmer hari ini</p>
        </div>
    </div>

    {{-- 3 KOTAK BESAR SESUAI SKETSA --}}
    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="card card-stat-big border-baru">
                <p class="stat-label">Pesanan Baru</p>
                <h2 class="stat-value text-primary">{{ $stats['baru'] }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-stat-big border-proses">
                <p class="stat-label">Pesanan Dalam Proses</p>
                <h2 class="stat-value" style="color: var(--adira-gold);">{{ $stats['proses'] }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-stat-big border-selesai">
                <p class="stat-label">Pesanan Selesai</p>
                <h2 class="stat-value text-success">{{ $stats['selesai'] }}</h2>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endsection