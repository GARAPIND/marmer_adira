@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;600;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --marble-gold: #C5A47E;
            --marble-dark: #1A1A1A;
            --marble-cream: #FDFCF8;
            --soft-gray: #F7F7F7;
        }

        body {
            background-color: var(--marble-cream);
            font-family: 'Inter', sans-serif;
            color: var(--marble-dark);
        }

        .catalog-header {
            padding: 80px 0 30px;
        }

        .catalog-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 3rem;
            letter-spacing: -1px;
        }

        .title-accent {
            width: 50px;
            height: 2px;
            background: var(--marble-gold);
            margin: 20px auto;
        }

        .section-tag {
            color: var(--marble-gold);
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 5px;
            font-size: 0.75rem;
            display: block;
            margin-bottom: 10px;
        }

        .card-product-premium {
            border: none;
            border-radius: 0;
            background: #ffffff;
            transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        }

        .card-product-premium:hover {
            transform: translateY(-6px);
            box-shadow: 0 30px 60px rgba(197, 164, 126, 0.15) !important;
        }

        .product-img-wrapper {
            position: relative;
            width: 100%;
            padding-top: 100%;
            /* 1:1 ratio */
            overflow: hidden;
            background: #f4f4f4;
            min-height: unset;
            /* hapus min-height lama */
        }

        .product-img-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 1.5s ease;
        }

        .card-product-premium:hover .product-img-wrapper img {
            transform: scale(1.08);
        }

        .stok-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(26, 26, 26, 0.85);
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 5px 12px;
            backdrop-filter: blur(4px);
        }

        .stok-badge.habis {
            background: rgba(180, 50, 50, 0.85);
        }

        .size-tabs {
            display: flex;
            border-bottom: 1px solid #eee;
            margin-bottom: 0;
        }

        .size-tab {
            flex: 1;
            text-align: center;
            padding: 8px 4px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            color: #aaa;
            transition: 0.3s;
        }

        .size-tab.active {
            color: var(--marble-gold);
            border-bottom-color: var(--marble-gold);
        }

        .size-panel {
            display: none;
            padding: 16px 0 8px;
            animation: fadeUp 0.3s ease;
        }

        .size-panel.active {
            display: block;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 7px 0;
            border-bottom: 1px solid var(--soft-gray);
            font-size: 0.78rem;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #999;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.65rem;
        }

        .info-value {
            font-weight: 700;
            color: var(--marble-dark);
        }

        .price-highlight {
            font-size: 1rem;
            color: var(--marble-gold);
        }

        .bahan-chip {
            background: var(--soft-gray);
            border: 1px solid #e8e8e8;
            font-size: 0.7rem;
            padding: 3px 10px;
            display: inline-block;
            font-weight: 600;
        }

        .btn-order {
            background: var(--marble-dark);
            color: white;
            border-radius: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 14px;
            transition: 0.4s;
            border: 1px solid var(--marble-dark);
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-order:hover {
            background: transparent;
            color: var(--marble-dark);
        }

        .btn-order.disabled-order {
            background: #ccc;
            border-color: #ccc;
            pointer-events: none;
        }

        .btn-add-masterpiece {
            background: transparent;
            border: 1px solid var(--marble-gold);
            color: var(--marble-gold);
            padding: 10px 25px;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 2px;
            font-weight: 700;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-add-masterpiece:hover {
            background: var(--marble-gold);
            color: white;
        }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .pengrajin-id-chip {
            font-size: 0.65rem;
            color: #bbb;
            letter-spacing: 0.5px;
        }

        @media (max-width: 576px) {
            .card-product-premium h4 {
                font-size: 0.85rem;
            }

            .size-tab {
                font-size: 0.58rem;
                padding: 6px 2px;
            }

            .info-row {
                font-size: 0.72rem;
            }

            .btn-order {
                font-size: 0.62rem;
                padding: 10px;
            }
        }
    </style>

    <div class="container py-5 mt-5">
        <div class="text-center catalog-header">
            <span class="section-tag animate__animated animate__fadeIn">Exquisite Collection</span>
            <h2 class="catalog-title animate__animated animate__fadeInUp">Katalog Masterpiece</h2>
            <div class="title-accent"></div>
            <p class="text-muted small text-uppercase animate__animated animate__fadeInUp mb-4"
                style="letter-spacing: 3px;">
                Detail Harga, Ukuran & Kurasi Bahan
            </p>
            @auth
                @if (Auth::user()->role == 'pengrajin')
                    <div class="mb-5 animate__animated animate__fadeIn">
                        <a href="{{ route('produk.create') }}" class="btn-add-masterpiece">
                            <i class="fas fa-plus me-2"></i> Tambah Koleksi Baru
                        </a>
                    </div>
                @endif
            @endauth
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-5 border-0 shadow-sm rounded-0" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-5">
            @forelse($produk as $p)
                <div class="col-xl-6 reveal">
                    <div class="card card-product-premium h-100">
                        <div class="row g-0 h-100">

                            <div class="col-5 col-sm-4 product-img-wrapper">
                                @if ($p->gambar)
                                    <img src="{{ asset('storage/' . $p->gambar) }}" alt="{{ $p->nama_produk }}">
                                @else
                                    <img src="https://via.placeholder.com/600x800?text=Adira+Stone" alt="No Image">
                                @endif
                                <div class="stok-badge">
                                    <i class="fas fa-check-circle me-1"></i> Tersedia
                                </div>
                            </div>

                            <div class="col-12 col-sm-12 p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <h4 class="fw-bold mb-1"
                                        style="font-family: 'Playfair Display', serif; text-transform: uppercase; font-size: 1.05rem;">
                                        {{ $p->nama_produk }}
                                    </h4>

                                    @if ($p->pengrajin_id)
                                        <p class="pengrajin-id-chip mb-2">
                                            <i class="fas fa-user-tie me-1"></i> Pengrajin #{{ $p->pengrajin_id }}
                                        </p>
                                    @endif

                                    <p class="small text-muted mb-3"
                                        style="letter-spacing: 0.5px; line-height: 1.7; font-size: 0.78rem;">
                                        {{ $p->deskripsi ?? 'Koleksi Kerajinan Batu Alam pilihan dengan pengerjaan tangan presisi.' }}
                                    </p>

                                    <div class="size-tabs" id="tabs-{{ $p->id }}">
                                        <div class="size-tab active"
                                            onclick="switchTab({{ $p->id }}, 'kecil', this)">
                                            <i class="fas fa-circle fa-xs me-1"></i> Kecil
                                        </div>
                                        <div class="size-tab" onclick="switchTab({{ $p->id }}, 'sedang', this)">
                                            <i class="fas fa-circle me-1"></i> Sedang
                                        </div>
                                        <div class="size-tab" onclick="switchTab({{ $p->id }}, 'besar', this)">
                                            <i class="fas fa-circle fa-lg me-1"></i> Besar
                                        </div>
                                    </div>

                                    <div class="size-panel active" id="panel-{{ $p->id }}-kecil">
                                        <div class="info-row">
                                            <span class="info-label">Ukuran</span>
                                            <span class="info-value">{{ $p->ukuran_kecil ?? '-' }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Bahan</span>
                                            <span class="bahan-chip">{{ $p->bahan_kecil->nama_bahan ?? '-' }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Berat</span>
                                            <span class="info-value">
                                                {{ $p->berat_kecil ? $p->berat_kecil . ' KG' : '-' }}
                                            </span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Harga</span>
                                            <span class="info-value price-highlight">Rp
                                                {{ number_format($p->harga_kecil, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <div class="size-panel" id="panel-{{ $p->id }}-sedang">
                                        <div class="info-row">
                                            <span class="info-label">Ukuran</span>
                                            <span class="info-value">{{ $p->ukuran_sedang ?? '-' }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Bahan</span>
                                            <span class="bahan-chip">{{ $p->bahan_sedang->nama_bahan ?? '-' }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Berat</span>
                                            <span class="info-value">
                                                {{ $p->berat_sedang ? $p->berat_sedang . ' KG' : '-' }}
                                            </span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Harga</span>
                                            <span class="info-value price-highlight">Rp
                                                {{ number_format($p->harga_sedang, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <div class="size-panel" id="panel-{{ $p->id }}-besar">
                                        <div class="info-row">
                                            <span class="info-label">Ukuran</span>
                                            <span class="info-value">{{ $p->ukuran_besar ?? '-' }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Bahan</span>
                                            <span class="bahan-chip">{{ $p->bahan_besar->nama_bahan ?? '-' }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Berat</span>
                                            <span class="info-value">
                                                {{ $p->berat_besar ? $p->berat_besar . ' KG' : '-' }}
                                            </span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Harga</span>
                                            <span class="info-value price-highlight">Rp
                                                {{ number_format($p->harga_besar, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 mt-3">
                                    <a href="{{ route('pesanan.create', ['produk_id' => $p->id]) }}" class="btn btn-order">
                                        <i class="fas fa-gem me-2"></i> Mulai Pemesanan
                                    </a>

                                    @auth
                                        @if (Auth::user()->role == 'pengrajin')
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('produk.edit', $p->id) }}"
                                                    class="btn btn-sm btn-outline-secondary w-100 rounded-0">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('produk.destroy', $p->id) }}" method="POST"
                                                    class="w-100">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger w-100 rounded-0"
                                                        onclick="return confirm('Hapus koleksi ini secara permanen?')">
                                                        <i class="fas fa-trash me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>

                                <div class="mt-2 d-flex justify-content-between"
                                    style="font-size: 0.6rem; color: #ccc; letter-spacing: 0.5px;">
                                    <span><i class="fas fa-clock me-1"></i>
                                        {{ $p->created_at ? $p->created_at->format('d M Y') : '-' }}</span>
                                    <span>ID #{{ $p->id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="py-5 animate__animated animate__fadeIn">
                        <i class="fas fa-gem fa-3x mb-3" style="color: #eee;"></i>
                        <p class="text-muted fst-italic">"Keindahan sedang dipersiapkan. Belum ada koleksi yang
                            dipublikasikan."</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function switchTab(productId, size, clickedTab) {
            document.querySelectorAll(`#tabs-${productId} .size-tab`).forEach(t => t.classList.remove('active'));
            document.querySelectorAll(`[id^="panel-${productId}-"]`).forEach(p => p.classList.remove('active'));
            clickedTab.classList.add('active');
            document.getElementById(`panel-${productId}-${size}`).classList.add('active');
        }

        document.addEventListener("DOMContentLoaded", function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) entry.target.classList.add("active");
                });
            }, {
                threshold: 0.1
            });
            document.querySelectorAll(".reveal").forEach(el => observer.observe(el));
        });
    </script>
@endsection
