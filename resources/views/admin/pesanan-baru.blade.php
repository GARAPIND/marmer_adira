@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        :root {
            --adira-gold: #C5A47E;
            --adira-dark: #2c3e50;
            --adira-soft-gold: rgba(197, 164, 126, 0.2);
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
            background: var(--adira-soft-gold);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--adira-gold);
            font-size: 1.8rem;
            transition: all 0.3s ease;
        }

        .marble-icon-box:hover {
            transform: scale(1.05);
            background: rgba(197, 164, 126, 0.3);
        }

        .btn-gold {
            background-color: var(--adira-gold);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 50px;
            padding: 0.6rem 1.5rem;
            transition: 0.3s;
        }

        .btn-gold:hover {
            background-color: #b08d44;
            color: white;
            transform: translateY(-2px);
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

        .info-box-custom {
            background: #fdfbf8;
            border-left: 4px solid var(--adira-gold);
            padding: 15px;
            border-radius: 8px;
        }

        .breadcrumb-item a {
            color: var(--adira-gold);
            text-decoration: none;
            font-weight: 600;
        }
    </style>

    <div class="container py-5 mt-2 animate__animated animate__fadeIn">
        <div class="page-header-elegant d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="marble-icon-box me-3 shadow-sm">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0 text-dark" style="border-left: 5px solid #000; padding-left: 15px;">Pesanan Baru
                    </h2>
                    <p class="text-muted small mb-0">Validasi dan tentukan harga untuk pesanan masuk</p>
                </div>
            </div>
            <div class="text-end d-none d-md-block">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active fw-bold text-dark">Pesanan Baru</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 animate__animated animate__bounceIn">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="table-responsive">
                <table class="table table-elegant hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID Pesanan</th>
                            <th>Nama Pembeli</th>
                            <th>Tanggal Masuk</th>
                            <th>Detail Produk</th>
                            <th>Status</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanan as $item)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="fw-semibold text-dark">{{ $item->user->name }}</td>
                                <td class="text-muted small">
                                    <i class="far fa-calendar-alt me-1 text-gold"></i>
                                    {{ $item->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    <div class="fw-bold mb-0">{{ $item->nama_produk }}</div>
                                    <small class="text-muted">Dimensi: {{ $item->ukuran }}</small>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusClass = [
                                            'Diverifikasi' => 'bg-success text-success',
                                            'Ditolak' => 'bg-danger text-danger',
                                            'Menunggu Verifikasi Admin' => 'bg-warning text-warning',
                                            'Selesai' => 'bg-primary text-primary',
                                            'default' => 'bg-secondary text-secondary',
                                        ];

                                        $currentStatusClass = $statusClass[$item->status] ?? $statusClass['default'];

                                        $paymentClass = [
                                            'paid' => 'bg-success text-success',
                                            'dp' => 'bg-warning text-warning',
                                            'no_paid' => 'bg-danger text-danger',
                                        ];

                                        $currentPaymentClass =
                                            $paymentClass[$item->status_pembayaran] ?? 'bg-secondary text-secondary';
                                    @endphp

                                    <span
                                        class="badge badge-pill-custom {{ $currentStatusClass }} bg-opacity-10 border border-opacity-25">
                                        {{ $item->status == 'Menunggu Verifikasi Admin' ? 'Menunggu Verifikasi' : $item->status }}
                                    </span>

                                    @if (in_array($item->status_pembayaran, ['dp', 'paid']))
                                        <br>
                                        <span
                                            class="badge badge-pill-custom {{ $currentPaymentClass }} bg-opacity-10 border border-opacity-25 mt-1">
                                            {{ $item->status_pembayaran == 'paid' ? 'Lunas' : 'DP 50%' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <button class="btn btn-gold btn-sm px-4 shadow-sm fw-bold"
                                        onclick="showDetailAdmin({{ json_encode($item) }})">
                                        <i class="fas fa-check-double me-1 text-white"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x mb-3 text-light"></i>
                                    <h6 class="text-muted italic">Belum ada pesanan baru untuk diverifikasi.</h6>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetailPesanan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 25px; overflow: hidden;">
                <div class="modal-header bg-dark text-white p-4">
                    <h5 class="modal-title fw-bold"><i class="fas fa-file-signature me-2 text-gold"></i> Verifikasi Pesanan
                        Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="formUpdateHarga" method="POST">
                    @csrf
                    <div class="modal-body p-4 bg-white text-dark">
                        <div class="row g-4">
                            <div class="col-md-7 border-end pe-md-4">
                                <label class="text-muted small fw-bold text-uppercase d-block mb-3">Rincian
                                    Permintaan</label>

                                <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                                    <div class="marble-icon-box me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold" id="md-nama"></p>
                                        <small class="text-muted" id="md-telp"></small>
                                    </div>
                                </div>

                                <div class="info-box-custom mb-3">
                                    <h6 id="md-produk" class="fw-bold mb-1"></h6>
                                    <p class="small text-muted mb-0">Ukuran: <span id="md-ukuran" class="fw-bold"></span> |
                                        Jml: <span id="md-jumlah" class="fw-bold"></span></p>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Catatan
                                        Pembeli</label>
                                    <p id="md-catatan" class="p-2 border rounded bg-light small fst-italic mb-0"></p>
                                </div>

                                <div id="md-alamat-section" class="alert alert-info border-0 shadow-sm py-2 mb-0"
                                    style="display:none;">
                                    <i class="fas fa-truck-moving me-2"></i>
                                    <small class="fw-bold">Tujuan Bus:</small> <span id="md-alamat"
                                        class="small fw-bold text-primary"></span>
                                </div>
                            </div>

                            <div class="col-md-5 ps-md-4">
                                <div class="p-3 border rounded-4 bg-light shadow-sm">
                                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-calculator me-2 text-gold"></i>
                                        Rincian</h6>

                                     <div class="mb-3">
                                         <label class="form-label small fw-bold">Harga Produk (Rp)</label>
                                         <div class="input-group">
                                             <span class="input-group-text border-dark bg-dark text-white">Rp</span>
                                             <input type="number" name="total_harga" id="input_harga"
                                                 class="form-control border-dark" placeholder="0" required min="1">
                                         </div>
                                     </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Berat Satuan (kg)</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-dark bg-dark text-white">kg</span>
                                            <input type="number" name="berat_satuan" id="input_berat_satuan"
                                                class="form-control border-dark" placeholder="0" min="0" step="0.01">
                                        </div>
                                    </div>

                                    <div class="mb-3" id="group-ongkir" style="display:none;">
                                        <label class="form-label small fw-bold text-danger">Ongkos Kirim (Rp)</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-danger bg-danger text-white">Rp</span>
                                            <input type="number" name="biaya_pengiriman" id="input_ongkir"
                                                class="form-control border-danger" value="0">
                                        </div>
                                    </div>

                                    <div class="mb-4" id="form_verification">
                                        <label class="form-label small fw-bold">Keputusan</label>
                                        <select name="status" class="form-select border-dark shadow-sm">
                                            <option value="Diverifikasi">Setujui & Kirim Harga</option>
                                            <option value="Ditolak">Tolak Pesanan</option>
                                        </select>
                                    </div>

                                    <div class="mb-3 d-none" id="form_alasan">
                                        <label class="form-label small fw-bold text-danger">Alasan Penolakan</label>
                                        <textarea name="alasan_penolakan" id="input_alasan" class="form-control border-danger shadow-sm" rows="3"
                                            placeholder="Masukkan alasan penolakan..."></textarea>
                                    </div>

                                    <div class="mb-4" id="form_confirmation">
                                        <label class="form-label small fw-bold">Konfirmasi Pesanan</label>
                                        <select name="status_selesai" class="form-select border-dark shadow-sm">
                                            <option value="paid">Barang diterima dan lunas</option>
                                            <option value="no_paid">Barang belum diambil</option>
                                        </select>
                                    </div>

                                    <div class="d-flex gap-2 mt-3">
                                        <button type="button" class="btn btn-secondary flex-fill py-2 fw-semibold"
                                            data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i> Tutup
                                        </button>

                                        <button type="submit" id="btn_submit"
                                            class="btn btn-gold flex-fill py-2 fw-semibold">
                                            <i class="fas fa-paper-plane me-1"></i> Simpan & Kirim
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const statusSelect = document.querySelector('select[name="status"]');
            const alasanForm = document.getElementById('form_alasan');
            const inputAlasan = document.getElementById('input_alasan');

            function toggleAlasan() {
                if (statusSelect.value === 'Ditolak') {
                    alasanForm.classList.remove('d-none');
                    inputAlasan.setAttribute('required', 'required');
                } else {
                    alasanForm.classList.add('d-none');
                    inputAlasan.removeAttribute('required');
                    inputAlasan.value = '';
                }
            }

            statusSelect.addEventListener('change', toggleAlasan);

            // trigger awal saat modal dibuka
            toggleAlasan();
        });

        function showDetailAdmin(data) {
            const modal = new bootstrap.Modal(document.getElementById('modalDetailPesanan'));

            document.getElementById('md-nama').innerText = data.user.name;
            document.getElementById('md-telp').innerText = 'Hubungi: ' + (data.user.no_telp || '-');
            document.getElementById('md-produk').innerText = data.nama_produk;
            document.getElementById('md-ukuran').innerText = data.ukuran;
            document.getElementById('md-jumlah').innerText = data.jumlah + ' Pcs';
            document.getElementById('md-catatan').innerText = data.catatan_khusus || 'Tidak ada catatan khusus.';

            document.getElementById('input_harga').value = data.total_harga > 0 ? data.total_harga : '';
            document.getElementById('input_berat_satuan').value = data.berat_satuan > 0 ? data.berat_satuan : '';

            const alamatSection = document.getElementById('md-alamat-section');
            const ongkirGroup = document.getElementById('group-ongkir');
            const inputOngkir = document.getElementById('input_ongkir');

            if (data.metode_pengambilan === 'dikirim') {
                alamatSection.style.display = 'block';
                document.getElementById('md-alamat').innerText = data.alamat_pengiriman || 'Alamat terminal belum diisi';
                ongkirGroup.style.display = 'block';
                inputOngkir.value = data.biaya_pengiriman > 0 ? data.biaya_pengiriman : 0;
            } else {
                alamatSection.style.display = 'none';
                ongkirGroup.style.display = 'none';
                inputOngkir.value = 0;
            }

            const verificationForm = document.getElementById('form_verification');
            const confirmationForm = document.getElementById('form_confirmation');
            const submitButton = document.getElementById('btn_submit');
            const inputHarga = document.getElementById('input_harga');
            const formUpdate = document.getElementById('formUpdateHarga');

            const isVerify = data.status === 'Menunggu Verifikasi Admin';
            const isDone = data.status === 'Selesai' && data.status_pembayaran === 'no_paid';

            const statusSelect = document.querySelector('select[name="status"]');
            const alasanForm = document.getElementById('form_alasan');
            const inputAlasan = document.getElementById('input_alasan');

            statusSelect.value = 'Diverifikasi';
            alasanForm.classList.add('d-none');
            inputAlasan.value = '';
            inputAlasan.removeAttribute('required');

            if (isVerify) formUpdate.action = `/admin/pesanan/${data.id}/update`;
            if (isDone) formUpdate.action = `/admin/pesanan/${data.id}/selesai`;

            verificationForm.classList.toggle('d-none', !isVerify);
            confirmationForm.classList.toggle('d-none', !isDone);
            submitButton.classList.toggle('d-none', !(isVerify || isDone));

            inputHarga.readOnly = !isVerify;
            inputOngkir.readOnly = !isVerify;
            document.getElementById('input_berat_satuan').readOnly = !isVerify;

            modal.show();
        }
    </script>
@endsection
