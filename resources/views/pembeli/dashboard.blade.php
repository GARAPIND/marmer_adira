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

        .progress-photo-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .progress-photo-grid a {
            display: block;
        }

        .progress-photo-grid img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #eee;
        }

        .detail-soft-card {
            background: linear-gradient(180deg, #fcfbf8 0%, #f7f3ec 100%);
            border: 1px solid rgba(197, 164, 126, 0.18);
            border-radius: 18px;
            padding: 16px;
        }

        .detail-info-card {
            background: #fff;
            border: 1px solid rgba(44, 62, 80, 0.08);
            border-radius: 16px;
            padding: 14px;
            height: 100%;
        }

        .buyer-photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: 14px;
        }

        .buyer-photo-card {
            background: #fff;
            border: 1px solid rgba(44, 62, 80, 0.08);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(44, 62, 80, 0.05);
        }

        .buyer-photo-card img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            display: block;
        }

        .buyer-photo-card-body {
            padding: 10px 12px;
        }

        .buyer-photo-empty {
            border: 1px dashed rgba(44, 62, 80, 0.2);
            border-radius: 16px;
            padding: 22px 16px;
            text-align: center;
            color: #7b8794;
            background: rgba(255, 255, 255, 0.78);
        }

        .order-item-stack {
            overflow-x: auto;
        }

        .order-item-table {
            width: 100%;
            min-width: 520px;
            border-collapse: collapse;
        }

        .order-item-table th,
        .order-item-table td {
            padding: 10px 12px;
            border-bottom: 1px solid rgba(44, 62, 80, 0.08);
            vertical-align: top;
        }

        .order-item-table th {
            font-size: 0.72rem;
            text-transform: uppercase;
            color: #7b8794;
            font-weight: 800;
            background: #f8fafb;
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
                            <h2 class="fw-bold m-0 text-dark">{{ $stats['proses'] }}</h2>
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
                            <h2 class="fw-bold m-0 text-dark">{{ $stats['selesai'] }}</h2>
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
                                            @elseif($item->is_menunggu_pelunasan)
                                                <span
                                                    class="badge badge-status-custom bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">{{ $item->status_label_pembeli }}</span>
                                            @elseif($item->status == 'Diproses')
                                                <span
                                                    class="badge badge-status-custom bg-info bg-opacity-10 text-info border border-info border-opacity-25">Sedang
                                                    Diproses</span>
                                            @elseif($item->status == 'Diverifikasi' && $item->status_pembayaran == 'paid')
                                                <div>
                                                    <span
                                                        class="badge badge-status-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                                        Telah Diverifikasi
                                                    </span>
                                                    <div class="text-success small"><b>Lunas</b></div>
                                                </div>
                                            @elseif($item->status == 'Diverifikasi' && $item->status_pembayaran == 'dp')
                                                <div>
                                                    <span
                                                        class="badge badge-status-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                                        Telah Diverifikasi
                                                    </span>
                                                    <div class="text-warning small"><b>Dibayar DP</b></div>
                                                </div>
                                            @elseif($item->status == 'Diverifikasi')
                                                <div>
                                                    <span
                                                        class="badge badge-status-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                                        Telah Diverifikasi
                                                    </span>
                                                    <div class="text-danger small"><b>Belum Bayar</b></div>
                                                </div>
                                            @else
                                                <span
                                                    class="badge badge-status-custom bg-success bg-opacity-10 text-success border border-success border-opacity-25">{{ $item->status_label_pembeli }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold"
                                                onclick="showDashboardDetail({{ json_encode($item) }})">
                                                Detail
                                            </button>
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

            <div class="col-lg-4">
                <h5 class="fw-bold mb-3 d-flex align-items-center">
                    <i class="fab fa-whatsapp me-2 text-gold"></i> Bantuan Cepat
                </h5>
                <div class="card mt-4 border-0 rounded-4 shadow-lg" style="background: var(--adira-dark);">
                    <div class="card-body p-4 text-center">
                        <div class="marble-icon-box mx-auto mb-3" style="background: rgba(255,255,255,0.1); color: white;">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <h6 class="fw-bold text-white">Butuh Konsultasi?</h6>
                        <p class="small text-white-50">Tanyakan detail bahan & harga khusus langsung ke pengrajin kami.</p>
                        <a href="https://wa.me/6285894626729" target="_blank" class="btn btn-gold w-100 py-2 shadow-sm">Chat Admin
                            Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetailFotoPembeli" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 overflow-hidden">
                <div class="modal-header bg-dark text-white">
                    <div>
                        <h5 class="modal-title fw-bold mb-1">Detail Progres Pesanan</h5>
                        <small id="dashboard-det-id" class="text-white-50">-</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="detail-soft-card">
                        <div class="detail-info-card mb-3">
                            <div class="small text-muted text-uppercase fw-bold mb-1">Produk</div>
                            <div id="dashboard-det-produk" class="fw-bold text-dark">-</div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="detail-info-card">
                                    <div class="small text-muted text-uppercase fw-bold mb-1">Status Pesanan</div>
                                    <div id="dashboard-det-status" class="fw-semibold text-dark">-</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-info-card">
                                    <div class="small text-muted text-uppercase fw-bold mb-1">Status Pembayaran</div>
                                    <div id="dashboard-det-bayar" class="fw-semibold text-dark">-</div>
                                </div>
                            </div>
                        </div>

                        <div class="detail-info-card mb-4">
                            <div class="small text-muted text-uppercase fw-bold mb-2">Catatan</div>
                            <div id="dashboard-det-catatan" class="small">-</div>
                        </div>

                        <div class="detail-info-card mb-4">
                            <div class="small text-muted text-uppercase fw-bold mb-2">Daftar Item</div>
                            <div id="dashboard-det-items" class="order-item-stack"></div>
                        </div>

                        <div class="detail-info-card mb-4">
                            <div class="small text-muted text-uppercase fw-bold mb-2">Resi Pengiriman</div>
                            <div id="dashboard-det-resi" class="small">-</div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="small text-muted text-uppercase fw-bold">Foto Saat Dikerjakan</div>
                                <span class="badge bg-light text-dark rounded-pill px-3 py-2">Progress</span>
                            </div>
                            <div id="dashboard-foto-dikerjakan" class="buyer-photo-grid"></div>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="small text-muted text-uppercase fw-bold">Foto Saat Selesai</div>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Final</span>
                            </div>
                            <div id="dashboard-foto-selesai" class="buyer-photo-grid"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <a href="{{ route('pesanan.index') }}" class="btn btn-dark rounded-pill px-4 fw-bold">Buka Riwayat Lengkap</a>
                </div>
            </div>
        </div>
    </div>

    {{-- FontAwesome & Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script>
        function showDashboardDetail(data) {
            const modal = new bootstrap.Modal(document.getElementById('modalDetailFotoPembeli'));
            const renderPhotos = (containerId, photos, emptyText) => {
                const container = document.getElementById(containerId);
                if (!Array.isArray(photos) || !photos.length) {
                    container.innerHTML = `
                        <div class="buyer-photo-empty">
                            <i class="fas fa-images fa-2x mb-3 opacity-50"></i>
                            <div class="fw-bold mb-1">Belum ada foto</div>
                            <div class="small">${emptyText}</div>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = photos.map((photo) =>
                    `<div class="buyer-photo-card">
                        <a href="/storage/${photo}" target="_blank">
                            <img src="/storage/${photo}" alt="Foto progres pesanan">
                        </a>
                        <div class="buyer-photo-card-body">
                            <a href="/storage/${photo}" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill w-100">Lihat Foto</a>
                        </div>
                    </div>`
                ).join('');
            };

            document.getElementById('dashboard-det-id').innerText = 'ORD-' + data.id.toString().padStart(3, '0');
            document.getElementById('dashboard-det-produk').innerText = data.nama_produk || '-';
            document.getElementById('dashboard-det-status').innerText = data.status_label_pembeli || data.status || '-';
            document.getElementById('dashboard-det-bayar').innerText = data.status_pembayaran === 'paid' ? 'Lunas' :
                (data.status_pembayaran === 'dp' ? 'Dibayar DP 50%' : 'Belum Bayar');
            document.getElementById('dashboard-det-catatan').innerText = data.catatan_khusus || 'Tidak ada catatan tambahan.';
            document.getElementById('dashboard-det-resi').innerText = data.nomor_resi_pengiriman || 'Belum ada resi cargo.';
            document.getElementById('dashboard-det-items').innerHTML = Array.isArray(data.items) && data.items.length ? `
                <table class="order-item-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Ukuran / Bahan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.items.map(item => `
                            <tr>
                                <td><strong>${item.nama_produk || '-'}</strong></td>
                                <td>${item.jumlah || 0}</td>
                                <td>${item.ukuran || '-'}<br><span class="text-muted small">${item.jenis_marmer || '-'}</span>${item.foto_sampel_terpilih ? `<div class="mt-2"><img src="${item.foto_sampel_terpilih}" alt="Sampel bahan terpilih" style="width:56px;height:56px;object-fit:cover;border-radius:10px;border:2px solid #C5A47E;"></div>` : ''}</td>
                                <td>Rp ${Number(item.subtotal || 0).toLocaleString('id-ID')}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            ` : '<div class="text-muted small">Detail item belum tersedia.</div>';

            renderPhotos('dashboard-foto-dikerjakan', data.foto_dikerjakan || [], 'Belum ada foto progres.');
            renderPhotos('dashboard-foto-selesai', data.foto_selesai || [], 'Belum ada foto hasil.');

            modal.show();
        }
    </script>
@endsection
