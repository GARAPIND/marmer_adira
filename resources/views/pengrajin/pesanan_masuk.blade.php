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

        .order-item-stack {
            overflow-x: auto;
        }

        .order-item-table {
            width: 100%;
            min-width: 520px;
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
                                                data-status_pembayaran="{{ $item->status_pembayaran }}"
                                                data-estimasi="{{ $item->estimasi_selesai }}"
                                                data-items='@json($item->items ?? [])'
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

                    <div class="info-group">
                        <span class="info-label">Nama Pembeli</span>
                        <p class="info-value" id="detail-pembeli">-</p>
                    </div>

                    <div class="mb-4">
                        <span class="info-label">Daftar Item Pesanan</span>
                        <div id="detail-items" class="order-item-stack">
                            <div class="text-muted small">Pilih pesanan untuk melihat daftar item.</div>
                        </div>
                    </div>

                    <div class="info-group text-center">
                        <span class="info-label">Estimasi Selesai</span>
                        <p class="info-value mb-0" id="detail-estimasi">-</p>
                        <small id="detail-estimasi-sisa" class="text-muted"></small>
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
                const itemsContainer = document.getElementById('detail-items');
                const itemsJson = this.getAttribute('data-items');

                let itemsList = [];
                try {
                    itemsList = JSON.parse(itemsJson);
                } catch (e) {
                    itemsList = [];
                }
                itemsContainer.innerHTML = Array.isArray(itemsList) && itemsList.length ? `
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
                            ${itemsList.map(item => `
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
                ` : '<div class="text-muted small">Detail item belum tersedia.</div>';
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

                const estimasi = this.getAttribute('data-estimasi');
                const estimasiEl = document.getElementById('detail-estimasi');
                const sisaEl = document.getElementById('detail-estimasi-sisa');

                if (estimasi) {
                    const tglEstimasi = new Date(estimasi);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    tglEstimasi.setHours(0, 0, 0, 0);

                    const selisihMs = tglEstimasi - today;
                    const selisihHari = Math.round(selisihMs / (1000 * 60 * 60 * 24));

                    // Format tanggal → "15 Juli 2025"
                    const formatted = tglEstimasi.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                    estimasiEl.innerText = formatted;

                    if (selisihHari > 0) {
                        sisaEl.innerText = `${selisihHari} hari lagi`;
                        sisaEl.className = selisihHari <= 3 ? 'text-danger fw-semibold small' :
                            'text-muted small';
                    } else if (selisihHari === 0) {
                        sisaEl.innerText = 'Hari ini!';
                        sisaEl.className = 'text-warning fw-bold small';
                    } else {
                        sisaEl.innerText = `Terlambat ${Math.abs(selisihHari)} hari`;
                        sisaEl.className = 'text-danger fw-bold small';
                    }
                } else {
                    estimasiEl.innerText = 'Belum ditentukan';
                    sisaEl.innerText = '';
                }
            });
        });
    </script>
@endsection
