@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --adira-gold: #C5A47E;
            --adira-dark: #2c3e50;
        }

        .page-header-elegant {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
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

        .table-elegant thead th {
            background-color: var(--adira-dark);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 1.25rem;
            border: none;
        }

        .timeline-container {
            position: relative;
            padding-left: 30px;
            border-left: 2px dashed #ddd;
            margin-left: 10px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 25px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -39px;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #ddd;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #ddd;
        }

        .timeline-item.completed::before {
            background: #27ae60;
            box-shadow: 0 0 0 2px #27ae60;
        }

        .timeline-item.active::before {
            background: var(--adira-gold);
            box-shadow: 0 0 0 2px var(--adira-gold);
        }

        .detail-card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .btn-gold {
            background-color: var(--adira-gold);
            color: white;
            border: none;
            font-weight: 700;
            border-radius: 50px;
            transition: 0.3s;
        }

        .btn-gold:hover {
            background-color: #b08d44;
            transform: translateY(-2px);
        }

        /* Photo grid - clickable thumbnails */
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
        }

        .photo-grid a {
            display: block;
            cursor: zoom-in;
        }

        .photo-grid img {
            width: 100%;
            height: 90px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #eee;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .photo-grid img:hover {
            transform: scale(1.04);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }

        .btn:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }

        .photo-manager-shell {
            background: linear-gradient(180deg, #fcfbf8 0%, #f6f2eb 100%);
            border: 1px solid rgba(197, 164, 126, 0.2);
            border-radius: 20px;
            padding: 18px;
        }

        .photo-manager-dropzone {
            border: 1.5px dashed rgba(44, 62, 80, 0.25);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.9);
            padding: 18px;
        }

        .photo-manager-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 14px;
        }

        .photo-manager-card {
            background: #fff;
            border: 1px solid rgba(44, 62, 80, 0.08);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(44, 62, 80, 0.06);
        }

        .photo-manager-thumb {
            width: 100%;
            height: 140px;
            object-fit: cover;
            background: #eef1f4;
            cursor: zoom-in;
            transition: transform 0.2s;
        }

        .photo-manager-thumb:hover {
            transform: scale(1.03);
        }

        .photo-manager-placeholder {
            width: 100%;
            height: 140px;
            background: linear-gradient(135deg, #f3f4f6 0%, #e8ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8a94a0;
            font-size: 1.6rem;
        }

        .photo-manager-body {
            padding: 14px;
        }

        .photo-manager-name {
            font-weight: 700;
            color: var(--adira-dark);
            word-break: break-word;
            margin-bottom: 6px;
            font-size: 0.82rem;
        }

        .photo-manager-meta {
            font-size: 0.78rem;
            color: #7b8794;
            margin-bottom: 12px;
        }

        .photo-manager-empty {
            border: 1px dashed rgba(44, 62, 80, 0.2);
            border-radius: 18px;
            padding: 28px 18px;
            text-align: center;
            color: #7b8794;
            background: rgba(255, 255, 255, 0.78);
        }

        .upload-hidden {
            display: none;
        }

        .order-item-stack {
            overflow-x: auto;
        }

        .order-item-table {
            width: 100%;
            min-width: 540px;
            border-collapse: collapse;
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
        }

        .order-item-table th,
        .order-item-table td {
            padding: 10px 12px;
            border-bottom: 1px solid rgba(44, 62, 80, 0.08);
            vertical-align: top;
        }

        .order-item-table th {
            background: #f8fafb;
            color: #7b8794;
            font-size: 0.72rem;
            text-transform: uppercase;
            font-weight: 800;
        }

        .item-photo-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--adira-dark);
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
        }

        .item-photo-link:hover {
            color: var(--adira-gold);
        }

        .item-note {
            display: inline-block;
            background: #f8fafb;
            border: 1px solid #edf2f7;
            border-radius: 10px;
            padding: 8px 10px;
            color: #52606d;
            font-size: 0.82rem;
            line-height: 1.5;
        }

        .shipping-mini-card {
            background: #f8fafb;
            border: 1px solid rgba(44, 62, 80, 0.08);
            border-radius: 14px;
            padding: 12px 14px;
        }

        /* --- Detail panel below table --- */
        #detail-panel {
            display: none;
            animation: fadeSlideIn 0.3s ease;
        }

        #detail-panel.visible {
            display: block;
        }

        @keyframes fadeSlideIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Active row highlight */
        .table-elegant tbody tr.active-row td {
            background: rgba(197, 164, 126, 0.07);
        }

        /* ---- Lightbox ---- */
        #lightbox-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.88);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        #lightbox-overlay.open {
            display: flex;
        }

        #lightbox-img {
            max-width: 90vw;
            max-height: 80vh;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            object-fit: contain;
        }

        #lightbox-close {
            position: absolute;
            top: 20px;
            right: 28px;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            line-height: 1;
            background: none;
            border: none;
            opacity: 0.85;
        }

        #lightbox-close:hover {
            opacity: 1;
        }

        #lightbox-nav {
            position: absolute;
            bottom: 28px;
            display: flex;
            gap: 12px;
        }

        #lightbox-prev,
        #lightbox-next {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 50px;
            padding: 8px 22px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background 0.2s;
        }

        #lightbox-prev:hover,
        #lightbox-next:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        #lightbox-counter {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
            text-align: center;
            margin-top: 14px;
        }
    </style>

    {{-- LIGHTBOX --}}
    <div id="lightbox-overlay">
        <button id="lightbox-close">&times;</button>
        <img id="lightbox-img" src="" alt="Foto">
        <div id="lightbox-counter"></div>
        <div id="lightbox-nav">
            <button id="lightbox-prev">&#8592; Prev</button>
            <button id="lightbox-next">Next &#8594;</button>
        </div>
    </div>

    <div class="container py-5 mt-2 animate__animated animate__fadeIn">
        <div class="page-header-elegant d-flex align-items-center shadow-sm">
            <div class="marble-icon-box me-3 shadow-sm">
                <i class="fas fa-sync-alt"></i>
            </div>
            <div>
                <h2 class="fw-bold mb-0 text-dark" style="border-left: 5px solid #000; padding-left: 15px;">Update Status
                    Pengerjaan</h2>
                <p class="text-muted small mb-0">Kelola progres produksi setiap pesanan marmer</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                <i class="fas fa-circle-exclamation me-2"></i> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                <i class="fas fa-circle-exclamation me-2"></i> {{ $errors->first() }}
            </div>
        @endif

        {{-- TABEL PEMANTAUAN --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
            <div class="table-responsive">
                <table class="table table-elegant mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID Pesanan</th>
                            <th>Nama Pembeli</th>
                            <th>Nama Produk</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th class="pe-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesananAktif as $item)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">
                                    ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td class="fw-semibold">{{ $item->nama_produk }}</td>
                                <td>
                                    <span
                                        class="badge {{ $item->status_pembayaran === 'paid' ? 'bg-success text-success' : ($item->status_pembayaran === 'dp' ? 'bg-warning text-warning' : 'bg-danger text-danger') }} bg-opacity-10 px-3 py-2 rounded-pill fw-bold">
                                        {{ $item->status_pembayaran === 'paid' ? 'Lunas' : ($item->status_pembayaran === 'dp' ? 'DP 50%' : 'Belum Bayar') }}
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="badge {{ $item->status == 'Dikerjakan' ? 'bg-warning text-warning' : ($item->status == 'diekspedisi' ? 'bg-info text-info' : ($item->status == 'Selesai' ? 'bg-success text-success' : 'bg-primary text-primary')) }} bg-opacity-10 px-3 py-2 rounded-pill fw-bold">
                                        {{ $item->status == 'diekspedisi' ? 'Dikirim' : $item->status }}
                                    </span>
                                </td>
                                <td class="pe-4 text-center">
                                    <button class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold btn-lihat-detail"
                                        data-id="ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}"
                                        data-estimasi="{{ $item->estimasi_selesai }}" data-status="{{ $item->status }}"
                                        data-payment-status="{{ $item->status_pembayaran }}"
                                        data-foto-dikerjakan='@json($item->foto_dikerjakan ?? [])'
                                        data-foto-selesai='@json($item->foto_selesai ?? [])'
                                        data-nomor-resi="{{ $item->nomor_resi_pengiriman }}"
                                        data-items='@json($item->items ?? [])'
                                        data-action="{{ route('pengrajin.update.status', $item->id) }}">
                                        Lihat Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted italic">
                                    Tidak ada pesanan yang sedang dalam proses.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PANEL DETAIL PESANAN (di bawah tabel) --}}
        <div class="card detail-card p-4" id="detail-panel">

            {{-- Header detail --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold mb-0">Detail Pesanan <span id="display-id" class="text-primary">-</span></h5>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="btn-tutup-detail">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
            </div>

            <div class="row g-4">
                {{-- KOLOM KIRI: item + timeline + estimasi + foto --}}
                <div class="col-lg-8">

                    {{-- Daftar Item --}}
                    <div class="mb-4">
                        <h6 class="small fw-bold text-uppercase text-muted mb-3">Daftar Item Pesanan:</h6>
                        <div id="preview-order-items" class="order-item-stack">
                            <span class="text-muted small">Pilih pesanan untuk melihat daftar item.</span>
                        </div>
                    </div>

                    {{-- Foto Dikerjakan --}}
                    <div class="mb-4 upload-hidden" id="section-foto-dikerjakan">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="small fw-bold text-uppercase text-muted mb-0">Foto Progres Dikerjakan:</h6>
                            <button type="button" id="btn-foto-dikerjakan"
                                class="btn btn-outline-dark btn-sm rounded-pill px-3" data-bs-toggle="modal"
                                data-bs-target="#modalFotoProgres">
                                <i class="fas fa-images me-1"></i> Kelola Foto
                            </button>
                        </div>
                        <div id="preview-foto-dikerjakan" class="photo-grid">
                            <span class="text-muted small">Belum ada foto.</span>
                        </div>
                    </div>

                    {{-- Foto Selesai --}}
                    <div class="mb-4 upload-hidden" id="section-foto-selesai">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="small fw-bold text-uppercase text-muted mb-0">Foto Hasil Selesai:</h6>
                            <button type="button" id="btn-foto-selesai"
                                class="btn btn-outline-dark btn-sm rounded-pill px-3" data-bs-toggle="modal"
                                data-bs-target="#modalFotoProgres">
                                <i class="fas fa-images me-1"></i> Kelola Foto
                            </button>
                        </div>
                        <div id="preview-foto-selesai" class="photo-grid">
                            <span class="text-muted small">Belum ada foto.</span>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: timeline + status + aksi --}}
                <div class="col-lg-4">

                    {{-- Timeline --}}
                    <h6 class="small fw-bold text-uppercase text-muted mb-3">Timeline Status:</h6>
                    <div class="timeline-container mb-4">
                        <div id="step-dikerjakan" class="timeline-item">
                            <p class="mb-0 fw-bold">Dikerjakan</p>
                            <small class="text-muted">Dalam tahap pembentukan/pemotongan</small>
                        </div>
                        <div id="step-selesai" class="timeline-item">
                            <p class="mb-0 fw-bold">Selesai</p>
                            <small class="text-muted">Produksi selesai, admin bisa lanjut proses resi</small>
                        </div>
                    </div>

                    {{-- Hint --}}
                    <div id="status-action-hint" class="alert alert-warning border-0 small mb-4 d-none"></div>

                    {{-- Payment --}}
                    <div class="mb-4">
                        <h6 class="small fw-bold text-uppercase text-muted mb-2">Status Pembayaran:</h6>
                        <span id="payment-badge" class="badge bg-secondary px-3 py-2 rounded-pill">-</span>
                    </div>

                    {{-- Estimasi --}}
                    <div class="mb-4" id="section-estimasi">
                        <h6 class="small fw-bold text-uppercase text-muted mb-2">Estimasi Selesai:</h6>
                        <div class="shipping-mini-card d-flex align-items-center justify-content-between">
                            <div>
                                <div class="small text-muted mb-1">Target Penyelesaian</div>
                                <div id="preview-estimasi-tanggal" class="fw-bold text-dark">-</div>
                            </div>
                            <span id="preview-estimasi-sisa" class="badge bg-secondary px-3 py-2 rounded-pill">-</span>
                        </div>
                    </div>

                    {{-- Info Pengiriman --}}
                    <div class="mb-4 upload-hidden" id="section-info-pengiriman">
                        <h6 class="small fw-bold text-uppercase text-muted mb-2">Info Pengiriman:</h6>
                        <div class="shipping-mini-card">
                            <div class="small text-muted mb-1">Nomor Resi Cargo</div>
                            <div id="preview-nomor-resi" class="fw-bold text-dark">-</div>
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div class="row g-2">
                        <div class="col-12" id="col-form-dikerjakan">
                            <form id="form-dikerjakan" method="POST" action="">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="Dikerjakan">
                                <button type="submit" class="btn btn-outline-dark w-100 py-2 fw-bold small rounded-pill"
                                    disabled>
                                    <i class="fas fa-hammer me-1"></i> Mulai Pengerjaan
                                </button>
                            </form>
                        </div>
                        <div class="col-12 upload-hidden" id="col-form-selesai">
                            <form id="form-selesai" method="POST" action="">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="Selesai" id="input-status-selesai">
                                <button type="submit"
                                    class="btn btn-gold w-100 py-2 fw-bold small rounded-pill shadow-sm" disabled>
                                    <i class="fas fa-check me-1"></i> <span id="label-btn-selesai">Tandai Selesai</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL KELOLA FOTO --}}
    <div class="modal fade" id="modalFotoProgres" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="fw-bold mb-1" id="modal-foto-title">Kelola Foto Progres</h5>
                        <p class="text-muted small mb-0" id="modal-foto-subtitle">Upload dan kelola foto progres pesanan.
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    <form id="form-upload-foto" method="POST" action="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="status_target" id="modal-status-target" value="">
                        <div class="photo-manager-shell">
                            <div class="photo-manager-dropzone mb-4">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-8">
                                        <label class="form-label small fw-bold text-uppercase text-muted">Pilih
                                            Foto</label>
                                        <input type="file" id="modal-photo-input" name="foto_progres[]"
                                            class="form-control" multiple>
                                        <div id="modal-photo-helper" class="form-text">Bisa pilih 1 foto atau beberapa
                                            foto sekaligus.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" id="btn-save-photo-list"
                                            class="btn btn-dark w-100 rounded-pill">
                                            Upload Foto
                                        </button>
                                    </div>
                                </div>
                                <p class="text-muted small mb-0 mt-3">Foto yang sudah tersimpan tetap tampil di bawah dan
                                    bisa dihapus satu per satu.</p>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-1">Foto Tersimpan</h6>
                                    <p class="text-muted small mb-0">Daftar foto yang sudah masuk ke sistem.</p>
                                </div>
                                <span id="photo-list-counter" class="badge bg-dark rounded-pill px-3 py-2">0 foto</span>
                            </div>

                            <div id="modal-photo-table-body" class="photo-manager-grid"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ===================== LIGHTBOX =====================
        const lightboxOverlay = document.getElementById('lightbox-overlay');
        const lightboxImg = document.getElementById('lightbox-img');
        const lightboxCounter = document.getElementById('lightbox-counter');
        const lightboxClose = document.getElementById('lightbox-close');
        const lightboxPrev = document.getElementById('lightbox-prev');
        const lightboxNext = document.getElementById('lightbox-next');

        let lightboxPhotos = [];
        let lightboxIndex = 0;

        function openLightbox(photos, index) {
            lightboxPhotos = photos;
            lightboxIndex = index;
            updateLightbox();
            lightboxOverlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function updateLightbox() {
            lightboxImg.src = '/storage/' + lightboxPhotos[lightboxIndex];
            lightboxCounter.innerText = `${lightboxIndex + 1} / ${lightboxPhotos.length}`;
            lightboxPrev.style.display = lightboxPhotos.length > 1 ? '' : 'none';
            lightboxNext.style.display = lightboxPhotos.length > 1 ? '' : 'none';
        }

        function closeLightbox() {
            lightboxOverlay.classList.remove('open');
            document.body.style.overflow = '';
        }

        lightboxClose.addEventListener('click', closeLightbox);
        lightboxOverlay.addEventListener('click', function(e) {
            if (e.target === lightboxOverlay) closeLightbox();
        });
        lightboxPrev.addEventListener('click', function(e) {
            e.stopPropagation();
            lightboxIndex = (lightboxIndex - 1 + lightboxPhotos.length) % lightboxPhotos.length;
            updateLightbox();
        });
        lightboxNext.addEventListener('click', function(e) {
            e.stopPropagation();
            lightboxIndex = (lightboxIndex + 1) % lightboxPhotos.length;
            updateLightbox();
        });
        document.addEventListener('keydown', function(e) {
            if (!lightboxOverlay.classList.contains('open')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') {
                lightboxIndex = (lightboxIndex - 1 + lightboxPhotos.length) % lightboxPhotos.length;
                updateLightbox();
            }
            if (e.key === 'ArrowRight') {
                lightboxIndex = (lightboxIndex + 1) % lightboxPhotos.length;
                updateLightbox();
            }
        });

        // ===================== RENDER PHOTOS =====================
        const renderPhotos = (container, photos, allowLightbox = true) => {
            if (!Array.isArray(photos) || !photos.length) {
                container.innerHTML = '<span class="text-muted small">Belum ada foto.</span>';
                return;
            }
            container.innerHTML = photos.map((photo, index) =>
                `<a href="#" class="photo-lightbox-link" data-index="${index}">
                    <img src="/storage/${photo}" alt="Foto progres">
                </a>`
            ).join('');

            if (allowLightbox) {
                container.querySelectorAll('.photo-lightbox-link').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        openLightbox(photos, parseInt(this.getAttribute('data-index')));
                    });
                });
            }
        };

        let currentPesananId = null;

        // ===================== MAIN DETAIL LOGIC =====================
        document.querySelectorAll('.btn-lihat-detail').forEach(button => {
            button.addEventListener('click', function() {
                // Hapus highlight row sebelumnya
                document.querySelectorAll('.table-elegant tbody tr').forEach(r => r.classList.remove(
                    'active-row'));
                this.closest('tr').classList.add('active-row');

                const id = this.getAttribute('data-id');
                const status = this.getAttribute('data-status');
                const actionUrl = this.getAttribute('data-action');
                currentPesananId = actionUrl.split('/').pop();
                const paymentStatus = this.getAttribute('data-payment-status');
                const fotoDikerjakan = JSON.parse(this.getAttribute('data-foto-dikerjakan') || '[]');
                const fotoSelesai = JSON.parse(this.getAttribute('data-foto-selesai') || '[]');
                const orderItems = JSON.parse(this.getAttribute('data-items') || '[]');
                const nomorResi = this.getAttribute('data-nomor-resi') || '-';

                const panel = document.getElementById('detail-panel');
                const paymentBadge = document.getElementById('payment-badge');
                const fotoDikerjakanEl = document.getElementById('preview-foto-dikerjakan');
                const fotoSelesaiEl = document.getElementById('preview-foto-selesai');
                const previewOrderItems = document.getElementById('preview-order-items');
                const tombolSelesai = document.querySelector('#form-selesai button[type="submit"]');
                const tombolDikerjakan = document.querySelector('#form-dikerjakan button[type="submit"]');
                const statusActionHint = document.getElementById('status-action-hint');
                const colFormDikerjakan = document.getElementById('col-form-dikerjakan');
                const colFormSelesai = document.getElementById('col-form-selesai');
                const sectionInfoPengiriman = document.getElementById('section-info-pengiriman');
                const sectionFotoDikerjakan = document.getElementById('section-foto-dikerjakan');
                const sectionFotoSelesai = document.getElementById('section-foto-selesai');
                const previewNomorResi = document.getElementById('preview-nomor-resi');
                const uploadForm = document.getElementById('form-upload-foto');
                const modalStatusTarget = document.getElementById('modal-status-target');
                const modalTitle = document.getElementById('modal-foto-title');
                const modalSubtitle = document.getElementById('modal-foto-subtitle');
                const modalPhotoInput = document.getElementById('modal-photo-input');
                const modalPhotoHelper = document.getElementById('modal-photo-helper');
                const tableBody = document.getElementById('modal-photo-table-body');
                const btnFotoDikerjakan = document.getElementById('btn-foto-dikerjakan');
                const btnFotoSelesai = document.getElementById('btn-foto-selesai');
                const photoListCounter = document.getElementById('photo-list-counter');
                const inputStatusSelesai = document.getElementById('input-status-selesai');
                const labelBtnSelesai = document.getElementById('label-btn-selesai');

                document.getElementById('display-id').innerText = id;
                document.getElementById('form-dikerjakan').action = actionUrl;
                document.getElementById('form-selesai').action = actionUrl;
                tombolDikerjakan.disabled = false;
                tombolSelesai.disabled = false;
                previewNomorResi.innerText = nomorResi;
                statusActionHint.classList.add('d-none');
                statusActionHint.innerHTML = '';

                // Estimasi
                const estimasi = this.getAttribute('data-estimasi');
                const estimasiTanggalEl = document.getElementById('preview-estimasi-tanggal');
                const estimasiSisaEl = document.getElementById('preview-estimasi-sisa');
                if (estimasi) {
                    const tglEstimasi = new Date(estimasi);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    tglEstimasi.setHours(0, 0, 0, 0);
                    const selisihHari = Math.round((tglEstimasi - today) / (1000 * 60 * 60 * 24));
                    estimasiTanggalEl.innerText = tglEstimasi.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                    if (selisihHari > 0) {
                        estimasiSisaEl.innerText = `${selisihHari} hari lagi`;
                        estimasiSisaEl.className = selisihHari <= 3 ?
                            'badge bg-danger px-3 py-2 rounded-pill' :
                            'badge bg-success px-3 py-2 rounded-pill';
                    } else if (selisihHari === 0) {
                        estimasiSisaEl.innerText = 'Hari ini!';
                        estimasiSisaEl.className = 'badge bg-warning text-dark px-3 py-2 rounded-pill';
                    } else {
                        estimasiSisaEl.innerText = `Terlambat ${Math.abs(selisihHari)} hari`;
                        estimasiSisaEl.className = 'badge bg-danger px-3 py-2 rounded-pill';
                    }
                } else {
                    estimasiTanggalEl.innerText = 'Belum ditentukan';
                    estimasiSisaEl.innerText = '-';
                    estimasiSisaEl.className = 'badge bg-secondary px-3 py-2 rounded-pill';
                }

                // Payment badge
                if (paymentStatus === 'paid') {
                    paymentBadge.innerText = 'Lunas';
                    paymentBadge.className = 'badge bg-success px-3 py-2 rounded-pill';
                } else if (paymentStatus === 'dp') {
                    paymentBadge.innerText = 'DP 50%';
                    paymentBadge.className = 'badge bg-warning text-dark px-3 py-2 rounded-pill';
                } else {
                    paymentBadge.innerText = 'Belum Bayar';
                    paymentBadge.className = 'badge bg-danger px-3 py-2 rounded-pill';
                }

                // Status logika
                if (status === 'Dikerjakan') {
                    colFormDikerjakan.classList.add('d-none');
                    colFormSelesai.classList.remove('upload-hidden');
                    sectionInfoPengiriman.classList.add('upload-hidden');
                    sectionFotoDikerjakan.classList.remove('upload-hidden');
                    sectionFotoSelesai.classList.remove('upload-hidden');
                    inputStatusSelesai.value = 'Selesai';
                    labelBtnSelesai.innerText = 'Tandai Selesai';
                    statusActionHint.classList.remove('d-none');
                    statusActionHint.className = 'alert alert-info border-0 small mb-4';
                    statusActionHint.innerHTML =
                        '<i class="fas fa-circle-info me-1"></i> Upload foto progres dan foto hasil selesai. Jika produksi sudah selesai, klik <b>Tandai Selesai</b>.';
                } else {
                    colFormDikerjakan.classList.remove('d-none');
                    colFormSelesai.classList.add('upload-hidden');
                    sectionInfoPengiriman.classList.add('upload-hidden');
                    sectionFotoDikerjakan.classList.add('upload-hidden');
                    sectionFotoSelesai.classList.add('upload-hidden');
                    statusActionHint.classList.remove('d-none');
                    statusActionHint.className = 'alert alert-warning border-0 small mb-4';
                    statusActionHint.innerHTML =
                        '<i class="fas fa-hammer me-1"></i> Klik <b>Mulai Pengerjaan</b> untuk memulai proses produksi. Setelah itu menu upload foto akan muncul.';
                }

                // Render foto grid dengan lightbox
                renderPhotos(fotoDikerjakanEl, fotoDikerjakan);
                renderPhotos(fotoSelesaiEl, fotoSelesai);

                // Render order items
                previewOrderItems.innerHTML = Array.isArray(orderItems) && orderItems.length ? `
                    <table class="order-item-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Detail</th>
                                <th>Referensi</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${orderItems.map(item => `
                                                        <tr>
                                                            <td><strong>${item.nama_produk || '-'}</strong></td>
                                                            <td>${item.jumlah || 0}</td>
                                                            <td>
                                                                <div class="small text-dark fw-semibold">${item.ukuran || '-'}</div>
                                                                <div class="text-muted small">${item.jenis_marmer || '-'}</div>
                                                                ${item.catatan_khusus ? `<div class="item-note mt-2">${item.catatan_khusus}</div>` : '<span class="text-muted small">Tanpa catatan.</span>'}
                                                            </td>
                                                            <td>
                                                                ${Array.isArray(item.gambar_referensi) && item.gambar_referensi.length
                                                                    ? item.gambar_referensi.map((img, idx) => `
                                                <a href="/storage/${img}" target="_blank" class="item-photo-link ${idx > 0 ? 'mt-1' : ''}">
                                                    <i class="fas fa-image"></i> Foto ${idx + 1}
                                                </a>
                                            `).join('<br>')
                                                                    : '<span class="text-muted small">Tidak ada foto</span>'}
                                                            </td>
                                                            <td>Rp ${Number(item.subtotal || 0).toLocaleString('id-ID')}</td>
                                                        </tr>
                                                    `).join('')}
                        </tbody>
                    </table>
                ` : '<span class="text-muted small">Detail item belum tersedia.</span>';

                // Modal foto helper
                const renderPhotoTable = (photos, statusTarget) => {
                    photoListCounter.innerText = `${Array.isArray(photos) ? photos.length : 0} foto`;
                    if (!Array.isArray(photos) || !photos.length) {
                        tableBody.innerHTML = `
            <div class="photo-manager-empty">
                <i class="fas fa-images fa-2x mb-3 opacity-50"></i>
                <div class="fw-bold mb-1">Belum ada foto tersimpan</div>
                <div class="small">Upload beberapa foto sekaligus melalui form di atas.</div>
            </div>`;
                        return;
                    }
                    tableBody.innerHTML = photos.map((photo) => {
                        const fileName = photo.split('/').pop();
                        return `
            <div class="photo-manager-card">
                <img src="/storage/${photo}" alt="Foto progres" class="photo-manager-thumb"
                    onclick="openLightbox(${JSON.stringify(photos)}, ${photos.indexOf(photo)})">
                <div class="photo-manager-body">
                    <div class="photo-manager-name">${fileName}</div>
                    <div class="photo-manager-meta">Tersimpan di sistem</div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-dark btn-sm rounded-pill flex-fill"
                            onclick="openLightbox(${JSON.stringify(photos)}, ${photos.indexOf(photo)})">
                            Lihat
                        </button>
                        <button type="button"
                            class="btn btn-outline-danger btn-sm rounded-pill flex-fill btn-hapus-foto-progres"
                            data-photo-path="${photo}" data-status-target="${statusTarget}">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>`;
                    }).join('');
                };

                const configureModal = (statusTarget, photos) => {
                    modalStatusTarget.value = statusTarget;
                    uploadForm.action = `/pengrajin/pesanan/${actionUrl.split('/').pop()}/foto-progres`;
                    modalTitle.innerText = statusTarget === 'Dikerjakan' ? 'Kelola Foto Dikerjakan' :
                        'Kelola Foto Selesai';
                    modalSubtitle.innerText =
                        `${id} - ${statusTarget === 'Dikerjakan' ? 'Foto tahap produksi' : 'Foto hasil akhir pesanan'}`;
                    modalPhotoInput.value = '';
                    modalPhotoHelper.innerText = 'Bisa pilih 1 foto atau beberapa foto sekaligus.';
                    renderPhotoTable(photos, statusTarget);
                };

                btnFotoDikerjakan.onclick = () => configureModal('Dikerjakan', fotoDikerjakan);
                btnFotoSelesai.onclick = () => configureModal('Selesai', fotoSelesai);

                modalPhotoInput.addEventListener('change', () => {
                    const totalFiles = modalPhotoInput.files.length;
                    modalPhotoHelper.innerText = totalFiles > 0 ?
                        `${totalFiles} foto siap diupload.` :
                        'Bisa pilih 1 foto atau beberapa foto sekaligus.';
                });

                // Reset & Update Timeline
                ['step-dikerjakan', 'step-selesai'].forEach(s =>
                    document.getElementById(s).classList.remove('completed', 'active')
                );
                if (status === 'Dikerjakan') {
                    document.getElementById('step-dikerjakan').classList.add('active');
                } else if (status === 'Selesai' || status === 'diekspedisi') {
                    document.getElementById('step-dikerjakan').classList.add('completed');
                    document.getElementById('step-selesai').classList.add('active');
                }

                // Tampilkan panel & scroll ke panel
                panel.classList.add('visible');
                setTimeout(() => {
                    panel.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 80);
            });
        });

        // Hapus foto progres (tanpa nested <form>, dibuat & submit secara dinamis)
        document.getElementById('modal-photo-table-body').addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-hapus-foto-progres');
            if (!btn || !currentPesananId) return;

            if (!confirm('Hapus foto ini?')) return;

            const photoPath = btn.getAttribute('data-photo-path');
            const statusTarget = btn.getAttribute('data-status-target');
            const csrfToken = document.querySelector('#form-upload-foto input[name="_token"]').value;

            const tempForm = document.createElement('form');
            tempForm.method = 'POST';
            tempForm.action = `/pengrajin/pesanan/${currentPesananId}/foto-progres`;
            tempForm.style.display = 'none';
            tempForm.innerHTML = `
        <input type="hidden" name="_token" value="${csrfToken}">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="status_target" value="${statusTarget}">
        <input type="hidden" name="photo_path" value="${photoPath}">
    `;

            document.body.appendChild(tempForm);
            tempForm.submit();
        });

        // Tombol tutup panel
        document.getElementById('btn-tutup-detail').addEventListener('click', function() {
            const panel = document.getElementById('detail-panel');
            panel.classList.remove('visible');
            document.querySelectorAll('.table-elegant tbody tr').forEach(r => r.classList.remove('active-row'));
        });
    </script>
@endsection
