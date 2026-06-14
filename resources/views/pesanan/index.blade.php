@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            letter-spacing: 1px;
            padding: 1.25rem;
            border: none;
        }

        .table-elegant tbody td {
            vertical-align: middle;
            padding: 1.2rem 1rem;
            border-bottom: 1px solid #f8f9fa;
        }

        .badge-status-pill {
            padding: 0.5em 1.2em;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
        }

        .swal2-popup {
            border-radius: 25px !important;
            font-family: 'Inter', sans-serif !important;
        }

        .swal2-confirm {
            border-radius: 50px !important;
        }

        .swal2-cancel {
            border-radius: 50px !important;
        }

        .accordion-row>td {
            padding: 0 !important;
            background: #fafafa;
        }

        .accordion-inner {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease;
        }

        .accordion-inner.open {
            max-height: 3000px;
        }

        .accordion-content {
            border-top: 3px solid var(--adira-gold);
            padding: 1.5rem 1.75rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
        }

        .acc-block label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #6c757d;
            display: block;
            margin-bottom: 5px;
        }

        .acc-block .val {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .acc-block .val-lg {
            font-size: 1.15rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .acc-block .val-muted {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .price-box {
            background: #fdfbf8;
            border: 1px dashed var(--adira-gold);
            border-radius: 12px;
            padding: 1rem;
            grid-column: span 2;
        }

        @media (max-width: 576px) {
            .price-box {
                grid-column: span 1;
            }
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            margin-bottom: 5px;
            color: #6c757d;
        }

        .price-row .pval {
            font-weight: 600;
            color: #2c3e50;
        }

        .price-row.total-row {
            border-top: 1px solid #dee2e6;
            margin-top: 8px;
            padding-top: 8px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .price-row.total-row .pval {
            font-size: 1.15rem;
        }

        .payment-history-entry {
            font-size: 0.75rem;
            border-bottom: 1px solid #f0f0f0;
            padding: 6px 0;
        }

        .payment-history-entry:last-child {
            border-bottom: none;
        }

        .progress-photo-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-top: 6px;
        }

        .progress-photo-grid img {
            width: 100%;
            height: 90px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #eee;
        }

        .btn-detail-acc {
            transition: all 0.15s;
        }

        .btn-detail-acc .chevron-icon {
            transition: transform 0.2s;
            display: inline-block;
        }

        .btn-detail-acc.active {
            background: var(--adira-dark) !important;
            color: #fff !important;
            border-color: var(--adira-dark) !important;
        }

        .btn-detail-acc.active .chevron-icon {
            transform: rotate(180deg);
        }

        .selected-row {
            background-color: #fdfbf8 !important;
            border-left: 4px solid var(--adira-gold) !important;
        }
    </style>

    <div class="container py-5 mt-2 animate__animated animate__fadeIn">

        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });
            </script>
        @endif

        <div class="page-header-elegant d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <div class="marble-icon-box me-3 shadow-sm">
                    <i class="fas fa-history"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0 text-dark" style="border-left:5px solid #000;padding-left:15px;">Riwayat Pesanan
                    </h2>
                    <p class="text-muted small mb-0">Pantau status produksi dan rincian biaya pesanan Anda</p>
                </div>
            </div>
            <a href="{{ route('produk.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm fw-bold">
                <i class="fas fa-plus me-2 text-gold"></i> Buat Pesanan Baru
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-5">
            <div class="table-responsive">
                <table class="table table-elegant align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanan->whereNotIn('status', ['Menunggu Verifikasi Admin']) as $item)
                            <tr id="row-{{ $item->id }}">
                                <td class="ps-4 fw-bold text-primary small">
                                    ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="text-muted small">{{ $item->created_at->format('d M Y') }}</td>
                                <td class="fw-semibold text-dark">
                                    {{ $item->nama_produk }}
                                    @if ($item->relationLoaded('items') && $item->items->count() > 1)
                                        <div class="small text-muted">{{ $item->items->count() }} item</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->is_menunggu_pelunasan)
                                        <span
                                            class="badge badge-status-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25"><i
                                                class="fas fa-wallet me-1"></i> {{ $item->status_label_pembeli }}</span>
                                    @elseif($item->status == 'Selesai')
                                        <span
                                            class="badge badge-status-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25"><i
                                                class="fas fa-check-circle me-1"></i> {{ $item->status }}</span>
                                    @elseif($item->status == 'diekspedisi')
                                        <span
                                            class="badge badge-status-pill bg-info bg-opacity-10 text-info border border-info border-opacity-25"><i
                                                class="fas fa-bus me-1"></i> Dikirim</span>
                                    @elseif($item->status == 'Ditolak')
                                        <span
                                            class="badge badge-status-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">{{ $item->status }}</span>
                                    @elseif($item->status == 'Diverifikasi' && $item->status_pembayaran == 'paid')
                                        <div>
                                            <span
                                                class="badge badge-status-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25">Telah
                                                Diverifikasi</span>
                                            <div class="text-success small"><b>Sudah Dibayar</b></div>
                                        </div>
                                    @elseif($item->status == 'Diverifikasi' && $item->status_pembayaran == 'dp')
                                        <div>
                                            <span
                                                class="badge badge-status-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Telah
                                                Diverifikasi</span>
                                            <div class="text-warning small"><b>Dibayar DP</b></div>
                                        </div>
                                    @elseif($item->status == 'Diverifikasi')
                                        <div>
                                            <span
                                                class="badge badge-status-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">Telah
                                                Diverifikasi</span>
                                            <div class="text-danger small"><b>Menunggu Pembayaran</b></div>
                                        </div>
                                    @else
                                        <span
                                            class="badge badge-status-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">{{ $item->status }}</span>
                                    @endif
                                    <div class="small mt-1 text-muted">
                                        {{ $item->status_pembayaran === 'paid' ? 'Lunas' : ($item->status_pembayaran === 'dp' ? 'Dibayar DP' : 'Belum Bayar') }}
                                        @if ($item->midtrans_bank || $item->midtrans_payment_type)
                                            &middot; {{ strtoupper($item->midtrans_bank ?? $item->midtrans_payment_type) }}
                                        @endif
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold btn-detail-acc"
                                        onclick="toggleDetail('acc-{{ $item->id }}', this, {{ json_encode($item) }})">
                                        Detail <span class="chevron-icon"><i class="fas fa-chevron-down ms-1"
                                                style="font-size:0.65rem;"></i></span>
                                    </button>
                                </td>
                            </tr>
                            <tr class="accordion-row">
                                <td colspan="5">
                                    <div class="accordion-inner" id="acc-{{ $item->id }}">
                                        <div class="accordion-content">
                                            @include('pesanan._accordion_detail', ['item' => $item])
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small fst-italic">Belum ada riwayat
                                    transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <h5 class="fw-bold mb-3"><i class="fas fa-hourglass-half me-2 text-warning"></i>Menunggu Verifikasi Admin</h5>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="table-responsive">
                <table class="table table-elegant align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanan->where('status', 'Menunggu Verifikasi Admin') as $item)
                            <tr id="row-{{ $item->id }}">
                                <td class="ps-4 text-muted small">ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="text-muted small">{{ $item->created_at->format('d M Y') }}</td>
                                <td class="fw-semibold text-dark">
                                    {{ $item->nama_produk }}
                                    @if ($item->relationLoaded('items') && $item->items->count() > 1)
                                        <div class="small text-muted">{{ $item->items->count() }} item</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge badge-status-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Menunggu</span>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-dark btn-sm rounded-pill px-3 fw-bold btn-detail-acc"
                                        onclick="toggleDetail('acc-{{ $item->id }}', this, {{ json_encode($item) }})">
                                        Lihat <span class="chevron-icon"><i class="fas fa-chevron-down ms-1"
                                                style="font-size:0.65rem;"></i></span>
                                    </button>
                                </td>
                            </tr>
                            <tr class="accordion-row">
                                <td colspan="5">
                                    <div class="accordion-inner" id="acc-{{ $item->id }}">
                                        <div class="accordion-content">
                                            @include('pesanan._accordion_detail', ['item' => $item])
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small fst-italic">Tidak ada pesanan
                                    yang sedang diverifikasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <form id="form-selesai" method="POST" style="display:none;">
        @csrf @method('PATCH')
    </form>

    <script>
        function toggleDetail(accId, btn, data) {
            const inner = document.getElementById(accId);
            const isOpen = inner.classList.contains('open');

            document.querySelectorAll('.accordion-inner.open').forEach(el => el.classList.remove('open'));
            document.querySelectorAll('.btn-detail-acc.active').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('tr.selected-row').forEach(tr => tr.classList.remove('selected-row'));

            if (!isOpen) {
                inner.classList.add('open');
                btn.classList.add('active');

                const row = document.getElementById('row-' + data.id);
                if (row) row.classList.add('selected-row');

                renderDynamicAccordion(accId, data);

                setTimeout(() => inner.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                }), 50);
            }
        }

        function renderDynamicAccordion(accId, data) {
            const labelEl = document.getElementById(accId + '-label');
            const historyEl = document.getElementById(accId + '-history');
            if (!labelEl) return;

            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });

            let events = Array.isArray(data.payment_histories) ? data.payment_histories : [];
            try {
                if (!events.length) {
                    const payloadObj = typeof data.midtrans_payload === 'string' ? JSON.parse(data.midtrans_payload) : data
                        .midtrans_payload;
                    events = Array.isArray(payloadObj?.payment_events) ? payloadObj.payment_events : [];
                }
            } catch (e) {
                events = [];
            }

            if (historyEl) {
                const sorted = [...events]
                    .filter(ev => ['paid_dp', 'paid_lunas'].includes(ev.event_type))
                    .sort((a, b) => new Date(a.event_time) - new Date(b.event_time));

                historyEl.innerHTML = sorted.length ? sorted.map(ev => {
                    const jenis = ev.event_type === 'paid_dp' ? 'Dibayar DP' : 'Dibayar Lunas';
                    const waktu = ev.event_time ? new Date(ev.event_time).toLocaleString('id-ID') : '-';
                    const nominal = formatter.format(parseInt(ev.nominal || 0));
                    const metode = (ev.payment_method || '-').toUpperCase();
                    return `<div class="payment-history-entry">
                        <div class="fw-bold text-dark">${jenis}</div>
                        <div>Waktu: ${waktu}</div>
                        <div>Nominal: ${nominal}</div>
                        <div>Metode: ${metode}</div>
                        <div>Order ID: ${ev.order_id || '-'}</div>
                        <div>Transaction ID: ${ev.transaction_id || '-'}</div>
                    </div>`;
                }).join('') : '<span class="text-muted small">Belum ada riwayat pembayaran.</span>';
            }

            let html = '';
            if (data.status === 'Menunggu Verifikasi Admin') {
                html = `<span class="badge w-100 bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 py-2">
                    <i class="fas fa-hourglass-half me-1"></i> Sedang diperiksa Admin</span>`;
            } else if (data.status === 'Ditolak') {
                html = `<span class="badge w-100 bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 py-2">
                    <i class="fas fa-times-circle me-1"></i> Pesanan Ditolak</span>
                    <div class="alert alert-danger border-0 small mt-2 py-2" style="border-radius:12px;">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        <b>Alasan Penolakan:</b><br>${data.alasan_penolakan || '-'}
                    </div>`;
            } else if (data.status === 'Selesai' && data.status_pembayaran !== 'paid') {
                html = `<div class="alert alert-warning border-0 small mb-2 py-2" style="border-radius:12px;">
                    <i class="fas fa-exclamation-circle me-1"></i> Pengerjaan selesai. Lunasi terlebih dahulu agar pesanan bisa dikirim.
                </div>
                <button class="btn btn-success w-100 rounded-pill fw-bold shadow-sm py-2" onclick="bayarSekarang(${data.id},'lunas')">
                    <i class="fas fa-money-check-dollar me-2"></i> Bayar Pelunasan</button>`;
            } else if (data.status === 'Selesai') {
                html = `<span class="badge w-100 bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-2">
                    <i class="fas fa-check-circle me-1"></i> Pesanan Selesai</span>`;
            } else if (data.status === 'diekspedisi') {
                html = `<div class="alert alert-info border-0 small mb-2 py-2" style="border-radius:12px;">
                    <i class="fas fa-info-circle me-1"></i> Pesanan dalam perjalanan via cargo${data.nomor_resi_pengiriman ? `.<br>Resi: <b>${data.nomor_resi_pengiriman}</b>` : ''}.</div>
                <button class="btn btn-success w-100 rounded-pill fw-bold shadow-sm py-2" onclick="konfirmasiSelesai(${data.id},'${data.nama_produk}')">
                    <i class="fas fa-box-open me-2"></i> Konfirmasi Barang Diterima</button>`;
            } else if (data.status === 'Diverifikasi' && data.status_pembayaran === 'no_paid') {
                html = `<div class="alert alert-warning border-0 small mb-2 py-2" style="border-radius:12px;">
                    <i class="fas fa-exclamation-circle me-1"></i> Pesanan diverifikasi. Pilih metode pembayaran.</div>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-warning w-100 rounded-pill fw-bold shadow-sm py-2 text-dark" onclick="bayarSekarang(${data.id},'dp')">
                        <i class="fas fa-credit-card me-2"></i> Bayar DP 50%</button>
                    <button class="btn btn-warning w-100 rounded-pill fw-bold shadow-sm py-2 text-dark" onclick="bayarSekarang(${data.id},'lunas')">
                        <i class="fas fa-wallet me-2"></i> Bayar Lunas</button>
                </div>`;
            } else if (data.status === 'Diverifikasi' && data.status_pembayaran === 'dp') {
                html = `<div class="alert alert-info border-0 small mb-2 py-2" style="border-radius:12px;">
                    <i class="fas fa-check-circle me-1"></i> DP 50% sudah dibayar. Lanjutkan pelunasan.</div>
                <button class="btn btn-success w-100 rounded-pill fw-bold shadow-sm py-2" onclick="bayarSekarang(${data.id},'lunas')">
                    <i class="fas fa-money-check-dollar me-2"></i> Bayar Pelunasan</button>`;
            } else if ((data.status === 'Diproses' || data.status === 'Dikerjakan') && data.status_pembayaran !== 'paid') {
                html = `<div class="alert alert-warning border-0 small mb-2 py-2" style="border-radius:12px;">
                    <i class="fas fa-exclamation-circle me-1"></i> Pesanan sedang dikerjakan. Lunasi agar pesanan bisa dikirim.</div>
                <button class="btn btn-success w-100 rounded-pill fw-bold shadow-sm py-2" onclick="bayarSekarang(${data.id},'lunas')">
                    <i class="fas fa-money-check-dollar me-2"></i> Bayar Pelunasan</button>`;
            } else if (data.status === 'Dikerjakan' && data.status_pembayaran === 'paid') {
                html =
                    `<span class="badge w-100 bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 py-2">
                    <i class="fas fa-hammer me-1"></i> Sedang dikerjakan. Akan dikirim setelah selesai produksi.</span>`;
            } else if (data.status_pembayaran === 'paid') {
                html = `<span class="badge w-100 bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-2">
                    <i class="fas fa-check-circle me-1"></i> Sudah Dibayar</span>`;
            } else {
                html = `<span class="badge w-100 bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 py-2">
                    <i class="fas fa-tools me-1"></i> ${data.status_label_pembeli || data.status}</span>`;
            }
            labelEl.innerHTML = html;
        }

        function konfirmasiSelesai(id, namaProduk) {
            Swal.fire({
                title: '<h4 class="fw-bold mb-0" style="color:var(--adira-dark)">Konfirmasi Penerimaan</h4>',
                html: `<p class="text-muted small">Apakah Anda yakin barang <b>${namaProduk}</b> sudah diterima?</p>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#C5A47E',
                cancelButtonColor: '#2c3e50',
                confirmButtonText: 'Ya, Diterima!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.getElementById('form-selesai');
                    form.action = "{{ url('/') }}/pesanan/" + id + "/selesai";
                    form.submit();
                }
            });
        }

        function bayarSekarang(pesananId, paymentStep = 'lunas') {
            Swal.fire({
                title: 'Memproses...',
                didOpen: () => Swal.showLoading(),
                allowOutsideClick: false,
                showConfirmButton: false
            });

            fetch(`/pesanan/${pesananId}/snap-token?payment_step=${paymentStep}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Gagal membuat transaksi pembayaran.');
                    return data;
                })
                .then(data => {
                    if (!data.snap_token) throw new Error(data.message || 'Snap token tidak tersedia.');
                    Swal.close();
                    snap.pay(data.snap_token, {
                        onSuccess: result => {
                            fetch(`/pesanan/${pesananId}/payment-success`, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify(result)
                            }).finally(() => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pembayaran Berhasil!',
                                    text: 'Status pembayaran telah diperbarui.',
                                    timer: 2500,
                                    showConfirmButton: false
                                });
                                setTimeout(() => location.reload(), 2600);
                            });
                        },
                        onPending: () => Swal.fire({
                            icon: 'info',
                            title: 'Menunggu Pembayaran',
                            text: 'Selesaikan pembayaran Anda sesuai instruksi.',
                            timer: 2500,
                            showConfirmButton: false
                        }),
                        onError: () => Swal.fire({
                            icon: 'error',
                            title: 'Pembayaran Gagal',
                            text: 'Terjadi kesalahan. Silakan coba lagi.'
                        }),
                        onClose: () => Swal.fire({
                            icon: 'warning',
                            title: 'Pembayaran Dibatalkan',
                            text: 'Anda menutup jendela pembayaran.',
                            timer: 2000,
                            showConfirmButton: false
                        })
                    });
                })
                .catch(err => Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: err?.message || 'Gagal menghubungi server. Coba lagi.'
                }));
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection
