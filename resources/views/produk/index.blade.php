@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

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

    /* --- Header Styling --- */
    .catalog-header { padding: 80px 0 30px; }
    .catalog-title { 
        font-family: 'Playfair Display', serif;
        font-weight: 700; 
        font-size: 3rem;
        letter-spacing: -1px;
    }
    .title-accent { width: 50px; height: 2px; background: var(--marble-gold); margin: 20px auto; }
    .section-tag {
        color: var(--marble-gold);
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: 5px;
        font-size: 0.75rem;
        display: block;
        margin-bottom: 10px;
    }

    /* --- Card Product Gallery Style --- */
    .card-product-premium { 
        border: none; 
        border-radius: 0; 
        background: #ffffff; 
        transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1); 
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }
    
    .card-product-premium:hover { 
        transform: translateY(-8px); 
        box-shadow: 0 30px 60px rgba(197, 164, 126, 0.15) !important; 
    }

    .product-img-wrapper { 
        min-height: 320px;
        overflow: hidden; 
        position: relative;
        background: #f4f4f4;
    }

    .product-img-wrapper img { 
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
        transition: transform 1.5s ease; 
    }
    
    .card-product-premium:hover .product-img-wrapper img { transform: scale(1.1); }

    /* --- Price Table Refinement --- */
    .table-price { font-size: 0.8rem; margin-top: 15px; border-top: 1px solid var(--soft-gray); }
    .table-price thead th { 
        color: var(--marble-gold); 
        font-weight: 700; 
        text-transform: uppercase; 
        font-size: 0.6rem; 
        letter-spacing: 1px;
        padding: 12px 5px;
    }

    .price-val { font-weight: 700; color: var(--marble-dark); }

    /* --- Button Premium --- */
    .btn-order { 
        background: var(--marble-dark); 
        color: white; 
        border-radius: 0; 
        text-transform: uppercase; 
        letter-spacing: 2px; 
        font-size: 0.7rem; 
        font-weight: 600;
        padding: 15px; 
        transition: 0.4s; 
        border: 1px solid var(--marble-dark); 
        text-decoration: none; 
        display: block; 
        text-align: center; 
    }

    .btn-order:hover { background: transparent; color: var(--marble-dark); }

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
    }

    .btn-add-masterpiece:hover {
        background: var(--marble-gold);
        color: white;
    }

    .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s ease-out; }
    .reveal.active { opacity: 1; transform: translateY(0); }
</style>

<div class="container py-5 mt-5">
    <div class="text-center catalog-header">
        <span class="section-tag animate__animated animate__fadeIn">Exquisite Collection</span>
        <h2 class="catalog-title animate__animated animate__fadeInUp">Katalog Masterpiece</h2>
        <div class="title-accent"></div>
        <p class="text-muted small text-uppercase tracking-widest animate__animated animate__fadeInUp mb-4">Detail Harga Berdasarkan Ukuran & Kurasi Bahan</p>
        
        {{-- Tombol Tambah Khusus Pengrajin --}}
        @auth
            @if(Auth::user()->role == 'pengrajin')
                <div class="mb-5 animate__animated animate__fadeIn">
                    <a href="{{ route('produk.create') }}" class="btn-add-masterpiece">
                        <i class="fas fa-plus me-2"></i> Tambah Koleksi Baru
                    </a>
                </div>
            @endif
        @endauth
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-5 border-0 shadow-sm rounded-0" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-5">
        @forelse($produk as $p)
        <div class="col-lg-6 reveal">
            <div class="card card-product-premium h-100">
                <div class="row g-0 h-100">
                    <div class="col-md-5 product-img-wrapper">
                        @if($p->gambar)
                            <img src="{{ asset('storage/' . $p->gambar) }}" alt="{{ $p->nama_produk }}">
                        @else
                            <img src="https://via.placeholder.com/600x800?text=Masterpiece+Adira" alt="No Image">
                        @endif
                    </div>
                    <div class="col-md-7 p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-start">
                                <h4 class="fw-bold mb-1" style="font-family: 'Playfair Display', serif; text-transform: uppercase;">
                                    {{ $p->nama_produk }}
                                </h4>
                            </div>
                            <p class="small text-muted mb-3" style="letter-spacing: 1px; line-height: 1.6;">
                                {{ $p->deskripsi ?? 'Koleksi Kerajinan Batu Alam pilihan dengan pengerjaan tangan presisi.' }}
                            </p>
                            
                            <table class="table table-sm table-price mb-4">
                                <thead>
                                    <tr>
                                        <th>Bahan</th>
                                        <th>Kecil</th>
                                        <th>Sedang</th>
                                        <th>Besar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span class="badge bg-light text-dark fw-normal p-2" style="border: 1px solid #eee;">
                                                {{ $p->bahan->nama_bahan ?? 'Natural Marble' }}
                                            </span>
                                        </td>
                                        <td class="price-val">{{ number_format($p->harga_k / 1000, 0) }}rb</td>
                                        <td class="price-val">{{ number_format($p->harga_s / 1000, 0) }}rb</td>
                                        <td class="price-val">{{ number_format($p->harga_b / 1000, 0) }}rb</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('pesanan.create', ['produk_id' => $p->id]) }}" class="btn btn-order">
                                Mulai Pemesanan
                            </a>

                            {{-- Proteksi Tombol Admin/Pengrajin --}}
                            @auth
                                @if(Auth::user()->role == 'pengrajin')
                                <div class="d-flex gap-1 mt-2">
                                    <a href="{{ route('produk.edit', $p->id) }}" class="btn btn-sm btn-outline-secondary w-100 rounded-0">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('produk.destroy', $p->id) }}" method="POST" class="w-100">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-0" onclick="return confirm('Hapus koleksi ini secara permanen?')">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="py-5 animate__animated animate__fadeIn">
                <i class="fas fa-gem fa-3x mb-3" style="color: #eee;"></i>
                <p class="text-muted italic">"Keindahan sedang dipersiapkan. Belum ada koleksi yang dipublikasikan."</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("active");
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll(".reveal").forEach(el => observer.observe(el));
    });
</script>
@endsection