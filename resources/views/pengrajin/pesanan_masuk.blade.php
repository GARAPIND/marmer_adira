@extends('layouts.app')

@section('content')
    <style>
        :root {
            --adira-gold: #C5A47E;
            --adira-dark: #2c3e50;
            --adira-cream: #FDFCF8;
        }

        body {
            background-color: var(--adira-cream);
        }

        .page-header-elegant {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            border-left: 5px solid var(--adira-gold);
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

        .detail-card {
            border: none;
            border-radius: 24px;
            background: white;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 100px;
            border: 1px solid rgba(197, 164, 126, 0.2);
        }

        .info-group {
            background: #fcfbf7;
            padding: 1rem;
            border-radius: 15px;
            margin-bottom: 1rem;
            border: 1px solid #f1ece1;
        }

        .info-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            font-weight: 700;
            margin-bottom: 5px;
            display: block;
        }

        .info-value {
            font-weight: 700;
            color: var(--adira-dark);
            margin-bottom: 0;
        }

        .catatan-box {
            background: white;
            border-left: 4px solid var(--adira-gold);
            padding: 1rem;
            border-radius: 8px;
            font-style: italic;
            color: #555;
            min-height: 60px;
        }

        .gambar-referensi-wrapper {
            background: #f8f9fa;
            border-radius: 15px;
            overflow: hidden;
            border: 1px dashed #ddd;
            text-align: center;
            margin-bottom: 1rem;
            position: relative;
            min-height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .img-referensi {
            max-width: 100%;
            max-height: 250px;
            object-fit: contain;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-konfirmasi {
            background-color: var(--adira-gold);
            color: white;
            font-weight: 800;
            border-radius: 50px;
            padding: 15px;
            border: none;
            transition: 0.3s;
            width: 100%;
            text-transform: uppercase;
        }

        .btn-konfirmasi:hover {
            background-color: #b08d44;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(197, 164, 126, 0.4);
        }

        .gambar-referensi-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 10px;
            justify-content: center;
        }

        .img-referensi-thumb {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            cursor: zoom-in;
            border: 1px solid #eee;
            transition: 0.3s;
        }

        .img-referensi-thumb:hover {
            transform: scale(1.05);
        }
    </style>

    <div class="container py-5 mt-2 animate__animated animate__fadeIn">
        <div class="page-header-elegant shadow-sm">
            <h2 class="fw-bold mb-0 text-dark">Daftar Pesanan Masuk</h2>
            <p class="text-muted mb-0 small">Verifikasi pesanan yang telah disetujui Admin untuk mulai diproduksi.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="table-responsive">
                        <table class="table table-elegant mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">ID Pesanan</th>
                                    <th>Nama Produk</th>
                                    <th>Item</th>
                                    <th>Ukuran</th>
                                    <th class="pe-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pesanan as $item)
                                    <tr>
                                        <td class="ps-4 fw-bold text-primary">
                                            ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                                        <td class="fw-semibold">{{ $item->nama_produk }}</td>
                                        @if ($item->relationLoaded('items') && $item->items->count() > 1)
                                            <td><span class="badge bg-dark">{{ $item->items->count() }} item</span></td>
                                        @else
                                            <td><span class="badge bg-light text-dark border">1 item</span></td>
                                        @endif
                                        <td><span class="badge bg-light text-dark border">{{ $item->ukuran }}</span></td>
                                        <td class="pe-4 text-center">
                                            <button
                                                class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold btn-show-detail"
                                                data-id="ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}"
                                                data-pembeli="{{ $item->user->name ?? 'Pembeli' }}"
                                                data-produk="{{ $item->nama_produk }}" data-ukuran="{{ $item->ukuran }}"
                                                data-bahan="{{ $item->jenis_marmer }}"
                                                data-status_pembayaran="{{ $item->status_pembayaran }}"
                                                data-catatan="{{ $item->catatan_khusus }}"
                                                data-gambar="{{ json_encode($item->gambar_referensi ?? []) }}"
                                                data-action="{{ route('pengrajin.update.status', $item->id) }}">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Belum ada pesanan masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card detail-card p-4 shadow-sm">
                    <div class="text-center mb-4">
                        <h5 class="fw-bold text-dark mb-1">Detail Pesanan</h5>
                        <span id="display-id" class="badge bg-dark px-3 py-2 rounded-pill shadow-sm">-</span>
                    </div>

                    <div class="mb-3">
                        <span class="info-label text-center">Gambar Referensi / Acuan</span>
                        <div class="gambar-referensi-wrapper" id="gambar-wrapper">
                            <p class="text-muted small mb-0" id="no-image-text">Pilih pesanan untuk melihat gambar</p>
                            <div class="gambar-referensi-grid" id="gambar-grid" style="display:none;"></div>
                        </div>
                    </div>

                    <div class="info-group">
                        <span class="info-label">Nama Pembeli</span>
                        <p class="info-value" id="detail-pembeli">-</p>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="info-group">
                                <span class="info-label">Nama Produk</span>
                                <p class="info-value" id="detail-produk">-</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-group">
                                <span class="info-label">Ukuran</span>
                                <p class="info-value" id="detail-ukuran">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="info-group">
                        <span class="info-label">Jenis Marmer</span>
                        <p class="info-value" id="detail-bahan">-</p>
                    </div>

                    <div class="mb-4">
                        <span class="info-label">Catatan Kustom</span>
                        <div class="catatan-box" id="detail-catatan">
                            "Tidak ada catatan khusus."
                        </div>
                    </div>

                    <div class="info-group text-center">
                        <span class="info-label">Status Pembayaran</span>
                        <p class="info-value">
                            <span id="detail-status-pembayaran" class="badge bg-secondary px-3 py-2">-</span>
                        </p>
                    </div>

                    <form id="form-mulai" method="POST" action="">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="Diproses">
                        <button type="submit" class="btn-konfirmasi mt-2 shadow-sm">
                            <i class="fas fa-hammer me-2"></i> Konfirmasi Mulai Produksi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-show-detail').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('display-id').innerText = this.getAttribute('data-id');
                document.getElementById('detail-pembeli').innerText = this.getAttribute('data-pembeli');
                document.getElementById('detail-produk').innerText = this.getAttribute('data-produk');
                document.getElementById('detail-ukuran').innerText = this.getAttribute('data-ukuran');
                document.getElementById('detail-bahan').innerText = this.getAttribute('data-bahan');

                const gambarJson = this.getAttribute('data-gambar');
                const gambarGrid = document.getElementById('gambar-grid');
                const noImageText = document.getElementById('no-image-text');

                let gambarList = [];
                try {
                    gambarList = JSON.parse(gambarJson);
                } catch (e) {
                    gambarList = [];
                }

                if (Array.isArray(gambarList) && gambarList.length > 0) {
                    gambarGrid.innerHTML = gambarList.map(img => `
                        <a href="/storage/${img}" target="_blank">
                            <img src="/storage/${img}" class="img-referensi-thumb">
                        </a>
                    `).join('');
                    gambarGrid.style.display = 'flex';
                    noImageText.style.display = 'none';
                } else {
                    gambarGrid.style.display = 'none';
                    gambarGrid.innerHTML = '';
                    noImageText.style.display = 'block';
                    noImageText.innerText = 'Tidak ada gambar referensi.';
                }

                const catatan = this.getAttribute('data-catatan');
                document.getElementById('detail-catatan').innerText = (catatan && catatan.trim() !== "") ?
                    `"${catatan}"` : '"Tidak ada catatan kustomisasi."';
                document.getElementById('form-mulai').action = this.getAttribute('data-action');

                const statusPembayaran = this.getAttribute('data-status_pembayaran');
                const statusElement = document.getElementById('detail-status-pembayaran');

                if (statusPembayaran === 'paid') {
                    statusElement.innerText = 'Lunas';
                    statusElement.className = 'badge bg-success px-3 py-2';
                } else if (statusPembayaran === 'dp') {
                    statusElement.innerText = 'DP 50%';
                    statusElement.className = 'badge bg-warning text-dark px-3 py-2';
                } else {
                    statusElement.innerText = 'Belum Dibayar';
                    statusElement.className = 'badge bg-danger px-3 py-2';
                }
            });
        });
    </script>
@endsection
