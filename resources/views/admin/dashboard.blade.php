@extends('layouts.app')

@section('content')
    {{-- CUSTOM CSS UNTUK ESTETIKA DASHBOARD ADMIN --}}
    <style>
        :root {
            --adira-gold: #C5A47E;
            --adira-dark: #2c3e50;
        }

        .text-gold {
            color: var(--adira-gold) !important;
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
            background: rgba(197, 164, 126, 0.1);
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
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
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

        .modal-content-elegant {
            border-radius: 25px;
            border: none;
            overflow: hidden;
        }

        .modal-header-elegant {
            background: var(--adira-dark);
            color: white;
            padding: 1.5rem 2rem;
        }

        .badge-pill-custom {
            padding: 0.5em 1.2em;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
        }

        .btn-gold {
            background-color: var(--adira-gold);
            border: none;
            color: white;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-gold:hover {
            background-color: #b08d44;
            color: white;
        }

        /* Box informasi harga */
        .info-price-box {
            background: #fdfbf8;
            border: 1px solid #e9ecef;
            border-radius: 15px;
            padding: 15px;
        }
    </style>

    <div class="container py-5 mt-2 animate__animated animate__fadeIn">
        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 animate__animated animate__bounceIn">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        {{-- HEADER HALAMAN --}}
        <div class="page-header-elegant d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="marble-icon-box me-3 shadow-sm">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0 text-dark" style="border-left: 5px solid #000; padding-left: 15px;">Ringkasan
                        Statistik</h2>
                    <p class="text-muted small mb-0">Update operasional terbaru periode bulan ini</p>
                </div>
            </div>
            <span class="badge bg-dark px-4 py-2 rounded-pill shadow-sm fw-bold">ADMIN PANEL</span>
        </div>

        {{-- STATS CARDS --}}
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card card-stat-elegant p-4 shadow-sm border-start border-4 border-primary">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="small fw-bold text-uppercase text-muted mb-0">Pesanan Baru</p>
                        <i class="fas fa-shopping-basket text-primary opacity-50"></i>
                    </div>
                    <h2 class="fw-bold m-0 text-dark">{{ $stats['baru'] }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat-elegant p-4 shadow-sm border-start border-4 border-warning">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="small fw-bold text-uppercase text-muted mb-0">Diverifikasi</p>
                        <i class="fas fa-check-double text-warning opacity-50"></i>
                    </div>
                    <h2 class="fw-bold m-0 text-dark">{{ $stats['diproses'] }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat-elegant p-4 shadow-sm border-start border-4 border-success">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="small fw-bold text-uppercase text-muted mb-0">Selesai</p>
                        <i class="fas fa-clipboard-check text-success opacity-50"></i>
                    </div>
                    <h2 class="fw-bold m-0 text-dark">{{ $stats['selesai'] }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat-elegant p-4 shadow-sm border-start border-4 border-info">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="small fw-bold text-uppercase text-muted mb-0">Total Pendapatan</p>
                        <i class="fas fa-wallet text-info opacity-50"></i>
                    </div>
                    <h4 class="fw-bold m-0 text-info">Rp {{ number_format($stats['total_bayar'], 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        {{-- TABEL PESANAN TERBARU --}}
        <div class="d-flex align-items-center mb-3">
            <i class="fas fa-list-ul me-2 text-gold"></i>
            <h4 class="fw-bold m-0 text-dark">Pesanan Terbaru</h4>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="table-responsive">
                <table class="table table-elegant hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID Pesanan</th>
                            <th>Nama Pembeli</th>
                            <th>Produk</th>
                            <th class="text-center">Status</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesananTerbaru as $item)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="fw-semibold text-dark">{{ $item->user->name }}</td>
                                <td>
                                    <div class="fw-bold mb-0 small">{{ $item->nama_produk }}</div>
                                    <small class="text-muted">{{ $item->ukuran }}</small>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusClass = [
                                            'Diverifikasi' => 'bg-success text-success',
                                            'Ditolak' => 'bg-danger text-danger',
                                            'Menunggu Verifikasi Admin' => 'bg-warning text-warning',
                                            'default' => 'bg-secondary text-secondary',
                                        ];
                                        $currentStatusClass =
                                            $statusClass[$item->status] ?? $statusClass['Menunggu Verifikasi Admin'];
                                    @endphp
                                    <span
                                        class="badge badge-pill-custom {{ $currentStatusClass }} bg-opacity-10 border border-opacity-25">
                                        {{ $item->status == 'Menunggu Verifikasi Admin' ? 'Menunggu Verifikasi' : $item->status }}
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <button class="btn btn-gold btn-sm px-3 rounded-pill shadow-sm fw-bold"
                                        onclick="showAdminDetail({{ json_encode($item) }})">
                                        <i class="fas fa-eye me-1 text-white"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted italic small">Belum ada aktivitas
                                    pesanan terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL --}}
    <div class="modal fade" id="modalDetailAdmin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-elegant shadow-lg">
                <div class="modal-header modal-header-elegant">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-file-invoice me-2 text-gold"></i> Rincian Pesanan <span id="md-id"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formUpdatePesanan" method="POST">
                    @csrf
                    <div class="modal-body p-4 bg-white">
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="text-muted small fw-bold text-uppercase d-block mb-1">Identitas
                                    Pembeli</label>
                                <p id="md-nama" class="fw-bold mb-0 text-dark"></p>
                                <small id="md-telp" class="text-muted"></small>
                            </div>
                            <div class="col-6 text-end">
                                <label class="text-muted small fw-bold text-uppercase d-block mb-1">Produk</label>
                                <p id="md-produk" class="fw-bold mb-0 text-dark"></p>
                                <small id="md-info" class="text-muted"></small>
                            </div>
                        </div>

                        <div id="md-alamat-section"
                            class="mb-3 p-3 bg-info bg-opacity-10 border border-info border-opacity-25 rounded-4"
                            style="display: none;">
                            <label class="text-info small fw-bold text-uppercase d-block mb-1"><i
                                    class="fas fa-truck me-1"></i> Alamat Pengiriman:</label>
                            <p id="md-alamat-text" class="mb-0 fw-bold text-dark small"></p>
                        </div>

                        <div class="mb-4">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Catatan & Referensi</label>
                            <p id="md-catatan" class="small p-3 border rounded-4 bg-light mb-2 italic"></p>
                            <div id="md-gambar-container" class="text-center border rounded-4 p-2 bg-light shadow-sm"></div>
                        </div>

                        <hr class="my-4">

                        <div class="p-3 bg-light rounded-4">
                            <h6 class="fw-bold mb-3 text-dark">
                                <i class="fas fa-receipt me-2 text-gold"></i> Rincian Biaya (Dari Pembeli)
                            </h6>

                            <div class="info-price-box mb-3 shadow-sm bg-white">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small text-muted">Harga Produk:</span>
                                    <span id="display_harga_produk" class="fw-bold text-dark"></span>
                                </div>
                                <div id="display_ongkir_row" class="d-flex justify-content-between">
                                    <span class="small text-muted">Ongkos Kirim:</span>
                                    <span id="display_ongkir_admin" class="fw-bold text-danger"></span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small fw-bold text-uppercase">Total Pembayaran:</span>
                                    <span id="display_total_seluruh" class="fw-bold text-dark fs-5"></span>
                                </div>
                            </div>

                            <input type="hidden" name="total_harga" id="input_harga_hidden">
                            <input type="hidden" name="biaya_pengiriman" id="input_ongkir_hidden">

                            <div class="mt-3" id="form_verifikasi">
                                <label class="form-label small fw-bold text-uppercase"><i class="fas fa-tasks me-1"></i>
                                    Update Status Pesanan</label>
                                <select name="status" id="select_status"
                                    class="form-select border-dark shadow-sm rounded-3">
                                    <option value="Menunggu Verifikasi Admin">Menunggu Verifikasi</option>
                                    <option value="Diverifikasi">Diverifikasi</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0" id="modal-footer-admin">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold text-muted"
                            data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-dark rounded-pill px-4 shadow-sm fw-bold"
                            id="btn_submit">Update
                            Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showAdminDetail(data) {
            const modal = new bootstrap.Modal(document.getElementById('modalDetailAdmin'));
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });

            // Identitas & Produk
            document.getElementById('md-id').innerText = 'ORD-' + data.id.toString().padStart(3, '0');
            document.getElementById('md-nama').innerText = data.user.name;
            document.getElementById('md-telp').innerText = 'WA: ' + (data.user.no_telp || '-');
            document.getElementById('md-produk').innerText = data.nama_produk;
            document.getElementById('md-info').innerText = data.ukuran + ' | Qty: ' + data.jumlah;
            document.getElementById('md-catatan').innerText = data.catatan_khusus || 'Tidak ada catatan tambahan.';

            // Gambar Referensi
            const gambarContainer = document.getElementById('md-gambar-container');
            if (data.gambar_referensi) {
                gambarContainer.innerHTML =
                    `<a href="/storage/${data.gambar_referensi}" target="_blank"><img src="/storage/${data.gambar_referensi}" class="img-fluid rounded-3" style="max-height: 200px; cursor: zoom-in;"></a>`;
            } else {
                gambarContainer.innerHTML = '<p class="text-muted small mb-0 py-3">Tidak ada gambar referensi.</p>';
            }

            // Alamat & Ongkir Row
            const alamatSection = document.getElementById('md-alamat-section');
            const ongkirRow = document.getElementById('display_ongkir_row');

            if (data.metode_pengambilan === 'dikirim') {
                alamatSection.style.display = 'block';
                document.getElementById('md-alamat-text').innerText = data.alamat_pengiriman;
                ongkirRow.classList.remove('d-none');
            } else {
                alamatSection.style.display = 'none';
                ongkirRow.classList.add('d-none');
            }

            // Rincian Harga
            const hrgProduk = parseInt(data.total_harga || 0);
            const hrgOngkir = parseInt(data.biaya_pengiriman || 0);

            document.getElementById('display_harga_produk').innerText = formatter.format(hrgProduk);
            document.getElementById('display_ongkir_admin').innerText = formatter.format(hrgOngkir);
            document.getElementById('display_total_seluruh').innerText = formatter.format(hrgProduk + hrgOngkir);

            // Isi Hidden Input
            document.getElementById('input_harga_hidden').value = hrgProduk;
            document.getElementById('input_ongkir_hidden').value = hrgOngkir;

            // Set Nilai Select (Handle logic jika status di DB diluar 3 pilihan ini)
            const statusSelect = document.getElementById('select_status');
            if (data.status === 'Diverifikasi' || data.status === 'Ditolak' || data.status ===
                'Menunggu Verifikasi Admin') {
                statusSelect.value = data.status;
            } else {
                statusSelect.value = 'Menunggu Verifikasi Admin';
            }

            document.getElementById('formUpdatePesanan').action = `/admin/pesanan/${data.id}/update`;
            modal.show();

            const verificationForm = document.getElementById('form_verifikasi');
            const submitButton = document.getElementById('btn_submit');
            if (data.status === 'Menunggu Verifikasi Admin') {
                verificationForm.classList.remove('d-none');
                submitButton.classList.remove('d-none');
            } else {
                verificationForm.classList.add('d-none');
                submitButton.classList.add('d-none');
            }
        }
    </script>
@endsection
