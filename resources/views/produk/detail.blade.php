@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <div class="row gx-5">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm overflow-hidden rounded-4">
                <img src="{{ $produk->gambar ?? 'https://source.unsplash.com/600x600/?marble,stone' }}" class="img-fluid w-100" style="object-fit: cover; height: 500px;" alt="{{ $produk->nama_produk }}">
            </div>
        </div>

        <div class="col-md-6">
            <h6 class="text-uppercase text-muted fw-bold ls-1">Katalog Adira Marmer</h6>
            <h1 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif;">{{ $produk->nama_produk }}</h1>
            <h3 class="text-danger mb-4">Rp {{ number_format($produk->harga) }} <span class="fs-6 text-muted fw-normal">/ meter</span></h3>
            
            <p class="text-muted mb-4">{{ $produk->deskripsi }}</p>

            <div class="card bg-light border-0 rounded-3 p-4">
                <h5 class="fw-bold mb-3">📋 Form Spesifikasi Pesanan</h5>
                
                @auth
                    <a href="{{ route('pesanan.create', ['produk_id' => $produk->id]) }}" class="btn btn-dark w-100 py-3 fw-bold rounded-pill">
                        Atur Spesifikasi & Tambah ke Keranjang
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-dark w-100 py-3 rounded-pill">
                        Login untuk Memesan
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
