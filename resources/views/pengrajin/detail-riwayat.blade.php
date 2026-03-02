@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root { --adira-gold: #C5A47E; --adira-dark: #2c3e50; }
    .main-title { font-family: 'Playfair Display', serif; color: var(--adira-dark); font-weight: 700; border-bottom: 3px solid var(--adira-gold); display: inline-block; padding-bottom: 5px; margin-bottom: 2.5rem; }
    .section-subtitle { font-family: 'Playfair Display', serif; color: var(--adira-gold); font-weight: 700; margin-bottom: 1.2rem; display: flex; align-items: center; }
    .info-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #888; font-weight: 700; margin-bottom: 2px; }
    .info-value { font-weight: 600; color: var(--adira-dark); margin-bottom: 1.2rem; }
    .time-box { background: #fcfbf7; border-radius: 15px; padding: 1.5rem; border: 1px solid #f1ece1; }
    .price-tag { font-size: 1.2rem; color: #27ae60; font-weight: 800; }
    .shipping-box { background: #f0f7ff; border-radius: 12px; padding: 12px; border: 1px solid #d0e3ff; margin-bottom: 1.2rem; }
    
    /* Style Tambahan untuk Gambar Referensi */
    .img-referensi-wrapper {
        border-radius: 15px;
        overflow: hidden;
        border: 1px solid #eee;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
        background: #fdfdfd;
        text-align: center;
    }
    .img-referensi {
        width: 100%;
        max-height: 350px;
        object-fit: contain;
        padding: 10px;
        transition: transform 0.3s ease;
    }
    .img-referensi:hover {
        transform: scale(1.02);
    }
</style>

<div class="container py-5 mt-4">
    <div class="card border-0 shadow-sm p-5 rounded-4 bg-white animate__animated animate__fadeIn">
        <h2 class="main-title">Detail Riwayat Pesanan</h2>

        <div class="row g-5">
            <div class="col-lg-5 pe-lg-5 border-end">
                <h5 class="section-subtitle"><i class="fas fa-file-invoice me-2"></i> Data Umum Pesanan</h5>
                
                <div class="info-label">ID Pesanan</div>
                <div class="info-value text-primary">ORD-{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}</div>
                
                <div class="info-label">Tanggal Pemesanan</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($pesanan->created_at)->translatedFormat('d F Y') }}</div>
                
                <div class="info-label">Status Akhir</div>
                <div class="info-value">
                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(39, 174, 96, 0.1); color: #27ae60; border: 1px solid #27ae60;">
                        {{ $pesanan->status }}
                    </span>
                </div>

                <h5 class="section-subtitle mt-5"><i class="fas fa-user me-2"></i> Data Pembeli</h5>
                <div class="info-label">Nama Pembeli</div>
                <div class="info-value text-capitalize">{{ $pesanan->user->name ?? 'Pembeli' }}</div>
                
                <div class="info-label">Kontak / WhatsApp</div>
                <div class="info-value">
                    <a href="https://wa.me/{{ $pesanan->user->no_telp ?? '' }}" target="_blank" class="text-decoration-none text-dark">
                        <i class="fab fa-whatsapp text-success"></i> {{ $pesanan->user->no_telp ?? '-' }}
                    </a>
                </div>

                <h5 class="section-subtitle mt-5"><i class="fas fa-wallet me-2"></i> Pembayaran</h5>
                <div class="info-label">Harga Produk</div>
                <div class="info-value">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>

                {{-- MODIFIKASI: INFORMASI BIAYA TITIP BUS --}}
                @if($pesanan->metode_pengambilan == 'dikirim')
                    <div class="info-label">Biaya Titip Bus</div>
                    <div class="info-value text-danger">Rp {{ number_format($pesanan->biaya_pengiriman, 0, ',', '.') }}</div>
                    
                    <div class="shipping-box">
                        <small class="info-label text-primary" style="font-size: 0.65rem;">Rincian Pengiriman:</small>
                        <p class="mb-0 small fw-bold text-dark">{{ $pesanan->alamat_pengiriman ?? 'Rincian tidak tersedia' }}</p>
                    </div>
                @else
                    <div class="info-label">Metode Pengambilan</div>
                    <div class="info-value"><span class="badge bg-secondary">Ambil di Galeri (Tanpa Ongkir)</span></div>
                @endif

                <div class="info-label">Total Keseluruhan</div>
                <div class="price-tag">Rp {{ number_format(($pesanan->total_harga + ($pesanan->biaya_pengiriman ?? 0)), 0, ',', '.') }}</div>
            </div>

            <div class="col-lg-7 ps-lg-5">
                <h5 class="section-subtitle"><i class="fas fa-tools me-2"></i> Detail Pekerjaan / Produk</h5>
                
                <div class="info-label mb-2">Gambar Referensi / Acuan Desain</div>
                <div class="img-referensi-wrapper">
                    @if($pesanan->gambar_referensi)
                        <a href="{{ asset('storage/' . $pesanan->gambar_referensi) }}" target="_blank" title="Klik untuk memperbesar">
                            <img src="{{ asset('storage/' . $pesanan->gambar_referensi) }}" class="img-referensi" alt="Referensi Marmer">
                        </a>
                    @else
                        <div class="py-5 text-muted">
                            <i class="fas fa-image fa-3x mb-3 opacity-25"></i>
                            <p class="small mb-0">Tidak ada gambar referensi yang diunggah.</p>
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-label">Nama/Jenis Produk</div>
                        <div class="info-value">{{ $pesanan->nama_produk }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Jenis Marmer</div>
                        <div class="info-value" style="color: var(--adira-gold);">{{ $pesanan->jenis_marmer }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Ukuran Dimensi</div>
                        <div class="info-value">{{ $pesanan->ukuran }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Jumlah</div>
                        <div class="info-value">{{ $pesanan->jumlah }} Unit</div>
                    </div>
                </div>

                <div class="info-label">Catatan Kustomisasi</div>
                <div class="info-value italic text-muted" style="background: #f8f9fa; padding: 15px; border-radius: 10px; border-left: 4px solid var(--adira-gold);">
                    "{{ $pesanan->catatan_khusus ?? 'Tidak ada catatan kustomisasi.' }}"
                </div>

                <h5 class="section-subtitle mt-5"><i class="fas fa-clock me-2"></i> Informasi Waktu Pengerjaan</h5>
                <div class="time-box">
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <div class="info-label">Mulai Produksi</div>
                            <div class="fw-bold">{{ $pesanan->created_at->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">Selesai Produksi</div>
                            <div class="fw-bold text-success">{{ $pesanan->updated_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 pt-4 text-center border-top">
            <a href="{{ route('pengrajin.riwayat') }}" class="btn px-5 py-2 rounded-pill shadow-sm" style="border: 2px solid var(--adira-dark); color: var(--adira-dark); font-weight: 700;">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Riwayat
            </a>
        </div>
    </div>
</div>
@endsection