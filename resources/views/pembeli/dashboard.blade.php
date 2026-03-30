@extends('layouts.app')

@section('content')
    {{-- CUSTOM CSS UNTUK ESTETIKA DASHBOARD PEMBELI --}}
    <style>
        :root {
            --adira-gold: #C5A47E;
            --adira-dark: #2c3e50;
        }

        .text-gold {
            color: var(--adira-gold) !important;
        }

        /* Banner Selamat Datang yang Mewah */
        .welcome-banner-elegant {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1200');
            background-size: cover;
            background-position: center;
            padding: 4rem 2rem;
            border-radius: 20px;
            color: white;
            margin-bottom: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
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

        .card-stat-elegant {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            background: #fff;
        }

        .card-stat-elegant:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05) !important;
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

        .table-elegant tbody td {
            vertical-align: middle;
            padding: 1.2rem 1rem;
            border-bottom: 1px solid #f8f9fa;
        }

        .quick-link-card {
            border: none;
            background: white;
            border-radius: 15px;
            transition: 0.3s;
            text-decoration: none;
            color: inherit;
            border-left: 0 solid var(--adira-gold);
        }

        .quick-link-card:hover {
            border-left: 6px solid var(--adira-gold);
            transform: translateX(5px);
            background-color: #fdfbf8;
        }

        .btn-gold {
            background-color: var(--adira-gold);
            border: none;
            color: white;
            font-weight: 700;
            border-radius: 50px;
            transition: 0.3s;
        }

        .btn-gold:hover {
            background-color: #b08d44;
            color: white;
            transform: scale(1.05);
        }

        .badge-status-custom {
            padding: 0.5em 1.2em;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
        }
    </style>

    <div class="container py-5 mt-2 animate__animated animate__fadeIn">

        {{-- WELCOME BANNER --}}
        <div class="welcome-banner-elegant text-center shadow-lg animate__animated animate__zoomIn">
            <h1 class="fw-bold mb-2" style="letter-spacing: 1px;">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p class="lead opacity-75 fw-light">Wujudkan hunian impian dengan sentuhan marmer eksklusif Adira Marmer.</p>
        </div>

        {{-- STATS CARDS --}}
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card card-stat-elegant p-4 shadow-sm border-start border-4 border-warning">
                    <div class="d-flex align-items-center">
                        <div class="marble-icon-box me-3"><i class="fas fa-hourglass-half"></i></div>
                        <div>
                            <p class="small fw-bold text-uppercase text-muted mb-1">Verifikasi Admin</p>
                            <h2 class="fw-bold m-0 text-dark">
                                {{ $pesanan->where('status', 'Menunggu Verifikasi Admin')->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-stat-elegant p-4 shadow-sm border-start border-4 border-info">
                    <div class="d-flex align-items-center">
                        <div class="marble-icon-box me-3"><i class="fas fa-hammer"></i></div>
                        <div>
                            <p class="small fw-bold text-uppercase text-muted mb-1">Sedang Diproses</p>
                            <h2 class="fw-bold m-0 text-dark">{{ $pesanan->where('status', 'Diproses')->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-stat-elegant p-4 shadow-sm border-start border-4 border-success">
                    <div class="d-flex align-items-center">
                        <div class="marble-icon-box me-3"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <p class="small fw-bold text-uppercase text-muted mb-1">Pesanan Selesai</p>
                            <h2 class="fw-bold m-0 text-dark">{{ $pesanan->where('status', 'Selesai')->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- TABEL AKTIVITAS TERBARU --}}
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-history me-2 text-gold"></i>
                    <h4 class="fw-bold m-0 text-dark">Aktivitas Pesanan Terbaru</h4>
                </div>
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="table-responsive">
                        <table class="table table-elegant hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">ID Pesanan</th>
                                    <th>Produk</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pesanan->take(5) as $item)
                                    <tr>
                                        <td class="ps-4 fw-bold text-primary small">
                                            ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                                        <td class="fw-semibold text-dark">{{ $item->nama_produk }}</td>
                                        <td class="text-center">
                                            @if ($item->status == 'Menunggu Verifikasi Admin')
                                                <span
                                                    class="badge badge-status-custom bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Verifikasi
                                                    Admin</span>
                                            @elseif($item->status == 'Diproses')
                                                <span
                                                    class="badge badge-status-custom bg-info bg-opacity-10 text-info border border-info border-opacity-25">Sedang
                                                    Diproses</span>
                                            @else
                                                <span
                                                    class="badge badge-status-custom bg-success bg-opacity-10 text-success border border-success border-opacity-25">{{ $item->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('pesanan.index') }}"
                                                class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold">Pantau</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted small italic">Belum ada
                                            riwayat pesanan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- AKSI CEPAT --}}
            <div class="col-lg-4">
                <h5 class="fw-bold mb-3 d-flex align-items-center">
                    <i class="fas fa-bolt me-2 text-gold"></i> Aksi Cepat
                </h5>
                <div class="d-grid gap-3">
                    <a href="{{ route('produk.index') }}" class="card p-3 quick-link-card shadow-sm">
                        <div class="d-flex align-items-center">
                            <div class="marble-icon-box me-3" style="width: 45px; height: 45px; font-size: 1.2rem;">
                                <i class="fas fa-th-large"></i>
                            </div>
                            <div>
                                <span class="fw-bold d-block text-dark">Katalog Produk</span>
                                <small class="text-muted">Lihat koleksi marmer premium</small>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- WA CONSULTATION --}}
                <div class="card mt-4 border-0 rounded-4 shadow-lg" style="background: var(--adira-dark);">
                    <div class="card-body p-4 text-center">
                        <div class="marble-icon-box mx-auto mb-3" style="background: rgba(255,255,255,0.1); color: white;">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <h6 class="fw-bold text-white">Butuh Konsultasi?</h6>
                        <p class="small text-white-50">Tanyakan detail bahan & harga khusus langsung ke pengrajin kami.</p>
                        <a href="https://wa.me/your-number" class="btn btn-gold w-100 py-2 shadow-sm">Chat Admin
                            Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FontAwesome & Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
@endsection
