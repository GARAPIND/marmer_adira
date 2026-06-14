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

        /* Timeline Status Styling */
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

        /* Warna Status */
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
            position: sticky;
            top: 100px;
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

        .photo-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .photo-grid a {
            display: block;
        }

        .photo-grid img {
            width: 100%;
            height: 90px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #eee;
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
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
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
            height: 155px;
            object-fit: cover;
            background: #eef1f4;
        }

        .photo-manager-placeholder {
            width: 100%;
            height: 155px;
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
    </style>

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

        {{-- ALERT NOTIFIKASI --}}
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

        <div class="row g-4">
            {{-- SISI KIRI: TABEL PEMANTAUAN --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
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
                                            <button
                                                class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold btn-lihat-detail"
                                                data-id="ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}"
                                                data-status="{{ $item->status }}"
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
            </div>

            {{-- SISI KANAN: PANEL TIMELINE --}}
            <div class="col-lg-6">
                <div class="card detail-card p-4 shadow-sm" id="detail-panel">
                    <h5 class="fw-bold mb-4">Detail Pesanan <span id="display-id">-</span></h5>

                    <div class="mb-4">
                        <h6 class="small fw-bold text-uppercase text-muted mb-3">Daftar Item Pesanan:</h6>
                        <div id="preview-order-items" class="order-item-stack">
                            <span class="text-muted small">Pilih pesanan untuk melihat daftar item.</span>
                        </div>
                    </div>

                    <h6 class="small fw-bold text-uppercase text-muted mb-4">Timeline Status:</h6>

                    <div class="timeline-container mb-4">
                        <div id="step-diproses" class="timeline-item">
                            <p class="mb-0 fw-bold">Diproses</p>
                            <small class="text-muted">Pesanan dikonfirmasi oleh pengrajin</small>
                        </div>
                        <div id="step-dikerjakan" class="timeline-item">
                            <p class="mb-0 fw-bold">Dikerjakan</p>
                            <small class="text-muted">Dalam tahap pembentukan/pemotongan</small>
                        </div>
                        <div id="step-selesai" class="timeline-item">
                            <p class="mb-0 fw-bold">Selesai</p>
                            <small class="text-muted">Produksi selesai, admin bisa lanjut proses resi dan kirim</small>
                        </div>
                    </div>

                    <div class="alert alert-light border rounded-4 small mb-4">
                        Klik `Mulai Pengerjaan` terlebih dahulu. Setelah status menjadi `Dikerjakan`, tombol upload foto
                        akan muncul dan Anda bisa upload banyak gambar sekaligus.
                    </div>

                    <div id="status-action-hint" class="alert alert-warning border-0 small mb-4 d-none"></div>

                    <div class="mb-4">
                        <h6 class="small fw-bold text-uppercase text-muted mb-3">Status Pembayaran:</h6>
                        <span id="payment-badge" class="badge bg-secondary px-3 py-2 rounded-pill">-</span>
                    </div>

                    <div class="mb-4 upload-hidden" id="section-info-pengiriman">
                        <h6 class="small fw-bold text-uppercase text-muted mb-3">Info Pengiriman:</h6>
                        <div class="shipping-mini-card">
                            <div class="small text-muted mb-1">Nomor Resi Cargo</div>
                            <div id="preview-nomor-resi" class="fw-bold text-dark">-</div>
                        </div>
                    </div>

                    <div class="mb-4 upload-hidden" id="section-foto-dikerjakan">
                        <h6 class="small fw-bold text-uppercase text-muted mb-3">Foto Progres Dikerjakan:</h6>
                        <div id="preview-foto-dikerjakan" class="photo-grid">
                            <span class="text-muted small">Belum ada foto.</span>
                        </div>
                        <button type="button" id="btn-foto-dikerjakan"
                            class="btn btn-outline-dark btn-sm rounded-pill mt-3" data-bs-toggle="modal"
                            data-bs-target="#modalFotoProgres">
                            Kelola Foto Dikerjakan
                        </button>
                    </div>

                    <div class="mb-4 upload-hidden" id="section-foto-selesai">
                        <h6 class="small fw-bold text-uppercase text-muted mb-3">Foto Hasil Selesai:</h6>
                        <div id="preview-foto-selesai" class="photo-grid">
                            <span class="text-muted small">Belum ada foto.</span>
                        </div>
                        <button type="button" id="btn-foto-selesai" class="btn btn-outline-dark btn-sm rounded-pill mt-3"
                            data-bs-toggle="modal" data-bs-target="#modalFotoProgres">
                            Kelola Foto Selesai
                        </button>
                    </div>

                    <div class="row g-2">
                        <div class="col-6" id="col-form-dikerjakan">
                            <form id="form-dikerjakan" method="POST" action="">
                                @csrf
                                @method('PATCH') {{-- Menambahkan method PATCH agar sesuai dengan web.php --}}
                                <input type="hidden" name="status" value="Dikerjakan">
                                <button type="submit" class="btn btn-outline-dark w-100 py-2 fw-bold small rounded-pill"
                                    disabled>
                                    Mulai Pengerjaan
                                </button>
                            </form>
                        </div>
                        <div class="col-6 upload-hidden" id="col-form-selesai">
                            <form id="form-selesai" method="POST" action="">
                                @csrf
                                @method('PATCH') {{-- Menambahkan method PATCH agar sesuai dengan web.php --}}
                                <input type="hidden" name="status" value="Selesai" id="input-status-selesai">
                                <button type="submit"
                                    class="btn btn-gold w-100 py-2 fw-bold small rounded-pill shadow-sm" disabled>
                                    <span id="label-btn-selesai">Tandai Selesai</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                <p class="text-muted small mb-0 mt-3">Upload satu per satu atau banyak sekaligus. Foto yang
                                    sudah tersimpan tetap tampil di bawah dan bisa dihapus satu per satu.</p>
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
        document.querySelectorAll('.btn-lihat-detail').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const status = this.getAttribute('data-status');
                const actionUrl = this.getAttribute('data-action');
                const paymentStatus = this.getAttribute('data-payment-status');
                const fotoDikerjakan = JSON.parse(this.getAttribute('data-foto-dikerjakan') || '[]');
                const fotoSelesai = JSON.parse(this.getAttribute('data-foto-selesai') || '[]');
                const orderItems = JSON.parse(this.getAttribute('data-items') || '[]');
                const paymentBadge = document.getElementById('payment-badge');
                const fotoDikerjakanContainer = document.getElementById('preview-foto-dikerjakan');
                const fotoSelesaiContainer = document.getElementById('preview-foto-selesai');
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
                const nomorResi = this.getAttribute('data-nomor-resi') || '-';

                document.getElementById('display-id').innerText = id;
                document.getElementById('form-dikerjakan').action = actionUrl;
                document.getElementById('form-selesai').action = actionUrl;
                tombolDikerjakan.disabled = false;
                tombolSelesai.disabled = false;
                previewNomorResi.innerText = nomorResi;
                statusActionHint.classList.add('d-none');
                statusActionHint.innerHTML = '';

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

                if (status === 'Dikerjakan') {
                    colFormDikerjakan.classList.add('d-none');
                    colFormSelesai.classList.remove('upload-hidden', 'col-6');
                    colFormSelesai.classList.add('col-12');
                    sectionInfoPengiriman.classList.add('upload-hidden');
                    sectionFotoDikerjakan.classList.remove('upload-hidden');
                    sectionFotoSelesai.classList.remove('upload-hidden');
                    inputStatusSelesai.value = 'Selesai';
                    labelBtnSelesai.innerText = 'Tandai Selesai';
                    statusActionHint.classList.remove('d-none');
                    statusActionHint.className = 'alert alert-info border-0 small mb-4';
                    statusActionHint.innerHTML =
                        '<i class="fas fa-circle-info me-1"></i> Upload foto progres dan foto hasil selesai. Jika produksi sudah benar-benar selesai, klik <b>Tandai Selesai</b>. Setelah itu pesanan masuk riwayat pengrajin dan admin bisa lanjut proses resi.';
                } else {
                    colFormDikerjakan.classList.remove('d-none');
                    colFormSelesai.classList.add('upload-hidden');
                    colFormSelesai.classList.remove('col-12');
                    colFormSelesai.classList.add('col-6');
                    sectionInfoPengiriman.classList.add('upload-hidden');
                    sectionFotoDikerjakan.classList.add('upload-hidden');
                    sectionFotoSelesai.classList.add('upload-hidden');
                    statusActionHint.classList.remove('d-none');
                    statusActionHint.className = 'alert alert-warning border-0 small mb-4';
                    statusActionHint.innerHTML =
                        '<i class="fas fa-hammer me-1"></i> Klik <b>Mulai Pengerjaan</b> terlebih dahulu. Setelah itu menu upload foto akan muncul.';
                }

                document.getElementById('form-selesai').onsubmit = null;

                const renderPhotos = (container, photos) => {
                    if (!Array.isArray(photos) || !photos.length) {
                        container.innerHTML = '<span class="text-muted small">Belum ada foto.</span>';
                        return;
                    }

                    container.innerHTML = photos.map((photo) =>
                        `<a href="/storage/${photo}" target="_blank"><img src="/storage/${photo}" alt="Foto progres"></a>`
                    ).join('');
                };

                renderPhotos(fotoDikerjakanContainer, fotoDikerjakan);
                renderPhotos(fotoSelesaiContainer, fotoSelesai);
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
                                    <td>
                                        <strong>${item.nama_produk || '-'}</strong>
                                    </td>
                                    <td>${item.jumlah || 0}</td>
                                    <td>
                                        <div class="small text-dark fw-semibold">${item.ukuran || '-'}</div>
                                        <div class="text-muted small">${item.jenis_marmer || '-'}</div>
                                        ${item.catatan_khusus ? `<div class="item-note mt-2">${item.catatan_khusus}</div>` : '<span class="text-muted small">Tanpa catatan.</span>'}
                                    </td>
                                    <td>
                                        ${Array.isArray(item.gambar_referensi) && item.gambar_referensi.length ? item.gambar_referensi.map((img, index) => `
                                                    <a href="/storage/${img}" target="_blank" class="item-photo-link ${index > 0 ? 'mt-1' : ''}">
                                                        <i class="fas fa-image"></i> Foto ${index + 1}
                                                    </a>
                                                `).join('<br>') : '<span class="text-muted small">Tidak ada foto</span>'}
                                    </td>
                                    <td>Rp ${Number(item.subtotal || 0).toLocaleString('id-ID')}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                ` : '<span class="text-muted small">Detail item belum tersedia.</span>';

                const renderPhotoTable = (photos, statusTarget) => {
                    photoListCounter.innerText = `${Array.isArray(photos) ? photos.length : 0} foto`;
                    if (!Array.isArray(photos) || !photos.length) {
                        tableBody.innerHTML = `
                            <div class="photo-manager-empty">
                                <i class="fas fa-images fa-2x mb-3 opacity-50"></i>
                                <div class="fw-bold mb-1">Belum ada foto tersimpan</div>
                                <div class="small">Upload beberapa foto sekaligus melalui form di atas.</div>
                            </div>
                        `;
                        return;
                    }

                    tableBody.innerHTML = photos.map((photo) => {
                        const fileName = photo.split('/').pop();
                        return `
                            <div class="photo-manager-card">
                                <a href="/storage/${photo}" target="_blank">
                                    <img src="/storage/${photo}" alt="Foto progres" class="photo-manager-thumb">
                                </a>
                                <div class="photo-manager-body">
                                    <div class="photo-manager-name">${fileName}</div>
                                    <div class="photo-manager-meta">Tersimpan di sistem</div>
                                    <div class="d-flex gap-2">
                                        <a href="/storage/${photo}" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill flex-fill">Lihat</a>
                                        <form method="POST" action="/pengrajin/pesanan/${actionUrl.split('/').pop()}/foto-progres" class="flex-fill">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="status_target" value="${statusTarget}">
                                            <input type="hidden" name="photo_path" value="${photo}">
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill w-100">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        `;
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

                // Reset Timeline Classes
                const steps = ['step-diproses', 'step-dikerjakan', 'step-selesai'];
                steps.forEach(s => document.getElementById(s).classList.remove('completed', 'active'));

                // Logika Update Timeline Berdasarkan Status
                if (status === 'Diproses') {
                    document.getElementById('step-diproses').classList.add('active');
                } else if (status === 'Dikerjakan') {
                    document.getElementById('step-diproses').classList.add('completed');
                    document.getElementById('step-dikerjakan').classList.add('active');
                } else if (status === 'Selesai' || status === 'diekspedisi') {
                    document.getElementById('step-diproses').classList.add('completed');
                    document.getElementById('step-dikerjakan').classList.add('completed');
                    document.getElementById('step-selesai').classList.add('active');
                }
            });
        });
    </script>
@endsection
