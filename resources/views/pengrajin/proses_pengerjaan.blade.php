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

        .table-photo-list td,
        .table-photo-list th {
            vertical-align: middle;
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
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="table-responsive">
                        <table class="table table-elegant mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">ID Pesanan</th>
                                    <th>Nama Pembeli</th>
                                    <th>Nama Produk</th>
                                    <th>Ukuran</th>
                                    <th>Jumlah</th>
                                    <th>Bahan</th>
                                    <th>Gambar</th>
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
                                            <span class="badge bg-light text-dark border">
                                                {{ $item->ukuran }}
                                            </span>
                                        </td>

                                        <td>{{ $item->jumlah }}</td>

                                        <td>{{ $item->jenis_marmer }}</td>

                                        <td>
                                            @if ($item->gambar_referensi)
                                                <img src="{{ asset('storage/' . $item->gambar_referensi) }}" alt="gambar"
                                                    width="100" class="rounded shadow-sm img-preview"
                                                    style="cursor: pointer" data-bs-toggle="modal"
                                                    data-bs-target="#modalGambar"
                                                    data-img="{{ asset('storage/' . $item->gambar_referensi) }}">
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span
                                                class="badge {{ $item->status_pembayaran === 'paid' ? 'bg-success text-success' : ($item->status_pembayaran === 'dp' ? 'bg-warning text-warning' : 'bg-danger text-danger') }} bg-opacity-10 px-3 py-2 rounded-pill fw-bold">
                                                {{ $item->status_pembayaran === 'paid' ? 'Lunas' : ($item->status_pembayaran === 'dp' ? 'DP 50%' : 'Belum Bayar') }}
                                            </span>
                                        </td>

                                        <td>
                                            <span
                                                class="badge {{ $item->status == 'Dikerjakan' ? 'bg-warning' : 'bg-primary' }} bg-opacity-10 {{ $item->status == 'Dikerjakan' ? 'text-warning' : 'text-primary' }} px-3 py-2 rounded-pill fw-bold">
                                                {{ $item->status }}
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
                                                data-action="{{ route('pengrajin.update.status', $item->id) }}">
                                                Lihat Detail
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5 text-muted italic">
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
            <div class="col-lg-4">
                <div class="card detail-card p-4 shadow-sm" id="detail-panel">
                    <h5 class="fw-bold mb-4">Detail Pesanan <span id="display-id">-</span></h5>

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
                            <small class="text-muted">Pesanan siap untuk dikirim</small>
                        </div>
                    </div>

                    <div class="alert alert-light border rounded-4 small mb-4">
                        Upload foto wajib saat pindah ke `Dikerjakan` dan `Selesai`. Status `Selesai` hanya bisa dipilih jika
                        pesanan sudah lunas.
                    </div>

                    <div class="mb-4">
                        <h6 class="small fw-bold text-uppercase text-muted mb-3">Status Pembayaran:</h6>
                        <span id="payment-badge" class="badge bg-secondary px-3 py-2 rounded-pill">-</span>
                    </div>

                    <div class="mb-4">
                        <h6 class="small fw-bold text-uppercase text-muted mb-3">Foto Progres Dikerjakan:</h6>
                        <div id="preview-foto-dikerjakan" class="photo-grid">
                            <span class="text-muted small">Belum ada foto.</span>
                        </div>
                        <button type="button" id="btn-foto-dikerjakan"
                            class="btn btn-outline-dark btn-sm rounded-pill mt-3" disabled
                            data-bs-toggle="modal" data-bs-target="#modalFotoProgres">
                            Kelola Foto Dikerjakan
                        </button>
                    </div>

                    <div class="mb-4">
                        <h6 class="small fw-bold text-uppercase text-muted mb-3">Foto Hasil Selesai:</h6>
                        <div id="preview-foto-selesai" class="photo-grid">
                            <span class="text-muted small">Belum ada foto.</span>
                        </div>
                        <button type="button" id="btn-foto-selesai"
                            class="btn btn-outline-dark btn-sm rounded-pill mt-3" disabled
                            data-bs-toggle="modal" data-bs-target="#modalFotoProgres">
                            Kelola Foto Selesai
                        </button>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <form id="form-dikerjakan" method="POST" action="">
                                @csrf
                                @method('PATCH') {{-- Menambahkan method PATCH agar sesuai dengan web.php --}}
                                <input type="hidden" name="status" value="Dikerjakan">
                                <button type="submit" class="btn btn-outline-dark w-100 py-2 fw-bold small rounded-pill" disabled>
                                    Mulai Pengerjaan
                                </button>
                            </form>
                        </div>
                        <div class="col-6">
                            <form id="form-selesai" method="POST" action="">
                                @csrf
                                @method('PATCH') {{-- Menambahkan method PATCH agar sesuai dengan web.php --}}
                                <input type="hidden" name="status" value="Selesai">
                                <button type="submit" class="btn btn-gold w-100 py-2 fw-bold small rounded-pill shadow-sm" disabled>
                                    Tandai Selesai
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalGambar" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-body p-2 text-center">
                    <img id="previewGambar" src="" class="img-fluid rounded">
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
                        <p class="text-muted small mb-0" id="modal-foto-subtitle">Upload dan kelola foto progres pesanan.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    <form id="form-upload-foto" method="POST" action="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="status_target" id="modal-status-target" value="">
                        <div id="deleted-existing-wrapper"></div>
                        <div class="photo-manager-shell">
                            <div class="photo-manager-dropzone mb-4">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-uppercase text-muted">Pilih Foto</label>
                                        <input type="file" id="modal-photo-input" class="form-control" multiple
                                            accept=".jpg,.jpeg,.png">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" id="btn-add-photo-list" class="btn btn-outline-dark w-100 rounded-pill">
                                            Tambah ke List
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" id="btn-save-photo-list" class="btn btn-dark w-100 rounded-pill">
                                            Simpan Daftar Foto
                                        </button>
                                    </div>
                                </div>
                                <p class="text-muted small mb-0 mt-3">Susun dulu daftar foto di bawah. Yang tersisa di daftar inilah yang akan disimpan.</p>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-1">Daftar Foto</h6>
                                    <p class="text-muted small mb-0">Kelola foto tersimpan dan foto baru dalam satu tampilan.</p>
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
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modalGambar');
            const preview = document.getElementById('previewGambar');

            document.querySelectorAll('.img-preview').forEach(img => {
                img.addEventListener('click', function() {
                    preview.src = this.getAttribute('data-img');
                });
            });
        });

        let modalPhotoState = {
            existing: [],
            deletedExisting: [],
            newFiles: [],
            orderId: null,
            actionUrl: '',
            statusTarget: '',
        };

        document.querySelectorAll('.btn-lihat-detail').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const status = this.getAttribute('data-status');
                const actionUrl = this.getAttribute('data-action');
                const paymentStatus = this.getAttribute('data-payment-status');
                const fotoDikerjakan = JSON.parse(this.getAttribute('data-foto-dikerjakan') || '[]');
                const fotoSelesai = JSON.parse(this.getAttribute('data-foto-selesai') || '[]');
                const paymentBadge = document.getElementById('payment-badge');
                const fotoDikerjakanContainer = document.getElementById('preview-foto-dikerjakan');
                const fotoSelesaiContainer = document.getElementById('preview-foto-selesai');
                const tombolSelesai = document.querySelector('#form-selesai button[type="submit"]');
                const tombolDikerjakan = document.querySelector('#form-dikerjakan button[type="submit"]');
                const uploadForm = document.getElementById('form-upload-foto');
                const modalStatusTarget = document.getElementById('modal-status-target');
                const modalTitle = document.getElementById('modal-foto-title');
                const modalSubtitle = document.getElementById('modal-foto-subtitle');
                const tableBody = document.getElementById('modal-photo-table-body');
                const btnFotoDikerjakan = document.getElementById('btn-foto-dikerjakan');
                const btnFotoSelesai = document.getElementById('btn-foto-selesai');
                const deletedExistingWrapper = document.getElementById('deleted-existing-wrapper');
                const modalPhotoInput = document.getElementById('modal-photo-input');
                const btnAddPhotoList = document.getElementById('btn-add-photo-list');
                const btnSavePhotoList = document.getElementById('btn-save-photo-list');
                const photoListCounter = document.getElementById('photo-list-counter');

                document.getElementById('display-id').innerText = id;
                document.getElementById('form-dikerjakan').action = actionUrl;
                document.getElementById('form-selesai').action = actionUrl;
                tombolDikerjakan.disabled = false;

                if (paymentStatus === 'paid') {
                    paymentBadge.innerText = 'Lunas';
                    paymentBadge.className = 'badge bg-success px-3 py-2 rounded-pill';
                    tombolSelesai.disabled = false;
                } else if (paymentStatus === 'dp') {
                    paymentBadge.innerText = 'DP 50%';
                    paymentBadge.className = 'badge bg-warning text-dark px-3 py-2 rounded-pill';
                    tombolSelesai.disabled = true;
                } else {
                    paymentBadge.innerText = 'Belum Bayar';
                    paymentBadge.className = 'badge bg-danger px-3 py-2 rounded-pill';
                    tombolSelesai.disabled = true;
                }

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

                const renderPhotoTable = (photos, statusTarget) => {
                    const activeExisting = modalPhotoState.existing
                        .filter((photo) => !modalPhotoState.deletedExisting.includes(photo));
                    const existingRows = modalPhotoState.existing
                        .filter((photo) => !modalPhotoState.deletedExisting.includes(photo))
                        .map((photo) => {
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
                                            <button type="button" class="btn btn-outline-danger btn-sm rounded-pill flex-fill btn-delete-existing" data-photo="${photo}">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });

                    const newRows = modalPhotoState.newFiles.map((file, index) => `
                        <div class="photo-manager-card">
                            <div class="photo-manager-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                            <div class="photo-manager-body">
                                <div class="photo-manager-name">${file.name}</div>
                                <div class="photo-manager-meta">Baru ditambahkan, belum disimpan</div>
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill w-100 btn-delete-new" data-index="${index}">
                                    Hapus dari List
                                </button>
                            </div>
                        </div>
                    `);

                    const rows = [...existingRows, ...newRows];
                    const totalPhotos = activeExisting.length + modalPhotoState.newFiles.length;
                    photoListCounter.innerText = `${totalPhotos} foto`;
                    if (!rows.length) {
                        tableBody.innerHTML = `
                            <div class="photo-manager-empty">
                                <i class="fas fa-images fa-2x mb-3 opacity-50"></i>
                                <div class="fw-bold mb-1">Daftar foto masih kosong</div>
                                <div class="small">Tambahkan foto ke list agar siap disimpan.</div>
                            </div>
                        `;
                    } else {
                        tableBody.innerHTML = rows.join('');
                    }

                    deletedExistingWrapper.innerHTML = modalPhotoState.deletedExisting
                        .map((photo) => `<input type="hidden" name="deleted_existing[]" value="${photo}">`)
                        .join('');

                    const transfer = new DataTransfer();
                    modalPhotoState.newFiles.forEach((file) => transfer.items.add(file));
                    modalPhotoInput.files = transfer.files;

                    tableBody.querySelectorAll('.btn-delete-existing').forEach((deleteButton) => {
                        deleteButton.addEventListener('click', function() {
                            modalPhotoState.deletedExisting.push(this.getAttribute('data-photo'));
                            renderPhotoTable([], statusTarget);
                        });
                    });

                    tableBody.querySelectorAll('.btn-delete-new').forEach((deleteButton) => {
                        deleteButton.addEventListener('click', function() {
                            modalPhotoState.newFiles.splice(parseInt(this.getAttribute('data-index'), 10), 1);
                            renderPhotoTable([], statusTarget);
                        });
                    });

                    btnSavePhotoList.innerText = 'Simpan Daftar Foto';
                };

                const configureModal = (statusTarget, photos) => {
                    modalPhotoState = {
                        existing: Array.isArray(photos) ? [...photos] : [],
                        deletedExisting: [],
                        newFiles: [],
                        orderId: actionUrl.split('/').pop(),
                        actionUrl: `/pengrajin/pesanan/${actionUrl.split('/').pop()}/foto-progres`,
                        statusTarget,
                    };

                    modalStatusTarget.value = statusTarget;
                    uploadForm.action = modalPhotoState.actionUrl;
                    modalTitle.innerText = statusTarget === 'Dikerjakan' ? 'Kelola Foto Dikerjakan' : 'Kelola Foto Selesai';
                    modalSubtitle.innerText = `${id} - ${statusTarget === 'Dikerjakan' ? 'Foto tahap produksi' : 'Foto hasil akhir pesanan'}`;
                    modalPhotoInput.value = '';
                    renderPhotoTable(photos, statusTarget);
                };

                btnFotoDikerjakan.onclick = () => configureModal('Dikerjakan', fotoDikerjakan);
                btnFotoSelesai.onclick = () => configureModal('Selesai', fotoSelesai);
                btnFotoDikerjakan.disabled = false;
                btnFotoSelesai.disabled = false;

                btnAddPhotoList.onclick = () => {
                    const pickedFiles = Array.from(modalPhotoInput.files || []);
                    if (!pickedFiles.length) {
                        alert('Pilih foto terlebih dahulu.');
                        return;
                    }

                    modalPhotoState.newFiles.push(...pickedFiles);
                    modalPhotoInput.value = '';
                    renderPhotoTable([], modalPhotoState.statusTarget);
                };

                uploadForm.onsubmit = (event) => {
                    const remainingExisting = modalPhotoState.existing.filter((photo) => !modalPhotoState.deletedExisting.includes(photo));
                    if (!remainingExisting.length && !modalPhotoState.newFiles.length) {
                        event.preventDefault();
                        alert('Minimal harus ada satu foto pada daftar sebelum disimpan.');
                    }
                };

                // Reset Timeline Classes
                const steps = ['step-diproses', 'step-dikerjakan', 'step-selesai'];
                steps.forEach(s => document.getElementById(s).classList.remove('completed', 'active'));

                // Logika Update Timeline Berdasarkan Status
                if (status === 'Diproses') {
                    document.getElementById('step-diproses').classList.add('active');
                } else if (status === 'Dikerjakan') {
                    document.getElementById('step-diproses').classList.add('completed');
                    document.getElementById('step-dikerjakan').classList.add('active');
                } else if (status === 'Selesai') {
                    document.getElementById('step-diproses').classList.add('completed');
                    document.getElementById('step-dikerjakan').classList.add('completed');
                    document.getElementById('step-selesai').classList.add('active');
                }
            });
        });
    </script>
@endsection
