@extends('layouts.app')

@section('content')
    {{-- Library SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- CUSTOM CSS UNTUK ESTETIKA RIWAYAT PESANAN --}}
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

        .card-detail-sidebar {
            border: none;
            border-radius: 20px;
            background: #fff;
            position: sticky;
            top: 100px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .badge-status-pill {
            padding: 0.5em 1.2em;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
        }

        .selected-row {
            background-color: #fdfbf8 !important;
            border-left: 4px solid var(--adira-gold) !important;
        }

        .price-breakdown-box {
            background: #fdfbf8;
            border: 1px dashed var(--adira-gold);
            border-radius: 12px;
        }

        /* Custom SweetAlert Style */
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
    </style>

    <div class="container py-5 mt-2 animate__animated animate__fadeIn">
        {{-- ALERT SUCCESS --}}
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

        {{-- HEADER HALAMAN --}}
        <div class="page-header-elegant d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="marble-icon-box me-3 shadow-sm">
                    <i class="fas fa-history"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0 text-dark" style="border-left: 5px solid #000; padding-left: 15px;">Riwayat
                        Pesanan</h2>
                    <p class="text-muted small mb-0">Pantau status produksi dan rincian biaya pesanan Anda</p>
                </div>
            </div>
            <a href="{{ route('produk.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm fw-bold">
                <i class="fas fa-plus me-2 text-gold"></i> Buat Pesanan Baru
            </a>
        </div>

        <div class="row g-4">
            {{-- SISI KIRI: TABEL PESANAN --}}
            <div class="col-lg-8">
                {{-- TABEL PESANAN PROSES --}}
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-5">
                    <div class="table-responsive">
                        <table class="table table-elegant hover align-middle mb-0">
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
                                        <td class="fw-semibold text-dark">{{ $item->nama_produk }}</td>
                                        <td class="text-center">
                                            @if ($item->status == 'Selesai')
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
                                                        class="badge badge-status-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                                        Telah Diverifikasi
                                                    </span>
                                                    <div class="text-success small"><b>Sudah Dibayar</b></div>
                                                </div>
                                            @elseif($item->status == 'Diverifikasi')
                                                <div>
                                                    <span
                                                        class="badge badge-status-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                                        Telah Diverifikasi
                                                    </span>
                                                    <div class="text-danger small"><b>Menunggu Pembayaran</b></div>
                                                </div>
                                         @else
                                             <span
                                                 class="badge badge-status-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">{{ $item->status }}</span>
                                         @endif
                                            <div class="small mt-1 text-muted">
                                                {{ $item->status_pembayaran === 'paid' ? 'Lunas' : ($item->status_pembayaran === 'dp' ? 'DP 50%' : 'Belum Bayar') }}
                                                @if ($item->midtrans_bank || $item->midtrans_payment_type)
                                                    &middot; {{ strtoupper($item->midtrans_bank ?? $item->midtrans_payment_type) }}
                                                @endif
                                            </div>
                                         </td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold"
                                                onclick="showDetail({{ json_encode($item) }})">Detail</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted italic small">Belum ada
                                            riwayat transaksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABEL MENUNGGU VERIFIKASI (DENGAN FITUR BATAL) --}}
                <h5 class="fw-bold mb-3"><i class="fas fa-hourglass-half me-2 text-warning"></i>Menunggu Verifikasi Admin
                </h5>
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="table-responsive">
                        <table class="table table-elegant hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted">
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
                                        <td class="ps-4 text-muted small">
                                            ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                                        <td class="text-muted small">{{ $item->created_at->format('d M Y') }}</td>
                                        <td class="fw-semibold text-dark">{{ $item->nama_produk }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge badge-status-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Menunggu</span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button class="btn btn-dark btn-sm rounded-pill px-3 fw-bold"
                                                    onclick="showDetail({{ json_encode($item) }})">Lihat</button>
                                                {{-- Tombol Batal Langsung di Tabel --}}
                                                <button class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold"
                                                    onclick="konfirmasiBatal({{ $item->id }}, '{{ $item->nama_produk }}')">
                                                    <i class="fas fa-times me-1"></i> Batal
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted small italic">Tidak ada
                                            pesanan yang sedang diverifikasi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- SISI KANAN: SIDEBAR DETAIL --}}
            <div class="col-lg-4">
                <div class="card card-detail-sidebar animate__animated animate__fadeInRight">
                    <div class="card-body p-4">
                        <div id="empty-state" class="text-center py-5">
                            <div class="marble-icon-box mx-auto mb-3 opacity-50">
                                <i class="fas fa-search"></i>
                            </div>
                            <p class="text-muted small px-3">Pilih salah satu pesanan untuk melihat rincian lengkap &
                                instruksi pembayaran.</p>
                        </div>

                        <div id="detail-content" style="display: none;">
                            <h5 class="fw-bold border-bottom pb-2 mb-4 text-dark">Detail Pesanan <span id="det-id"
                                    class="text-gold"></span></h5>

                            <div class="mb-4">
                                <label class="text-muted small fw-bold text-uppercase d-block mb-1">Produk &
                                    Material</label>
                                <p class="mb-0 fw-bold text-dark fs-5" id="det-produk"></p>
                                <span class="badge bg-light text-dark border fw-normal" id="det-marmer"></span>
                            </div>

                            <div class="row mb-4">
                                <div class="col-6">
                                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Ukuran</label>
                                    <div class="p-2 bg-light rounded text-center fw-bold small" id="det-ukuran"></div>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted small fw-bold text-uppercase d-block mb-1">Jumlah</label>
                                    <div class="p-2 bg-light rounded text-center fw-bold small" id="det-jumlah"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="text-muted small fw-bold text-uppercase d-block mb-1">Catatan Kustom</label>
                                <div class="p-3 bg-light rounded-3 small fst-italic" id="det-catatan"></div>
                            </div>

                            <div class="mb-4 p-3 price-breakdown-box shadow-sm">
                                <label class="text-gold small fw-bold text-uppercase d-block mb-2"><i
                                        class="fas fa-receipt me-1"></i> Rincian Pembayaran</label>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small text-muted">Harga Produk:</span>
                                    <span id="det-harga-produk" class="small fw-bold text-dark"></span>
                                </div>
                                <div id="det-ongkir-row" class="d-flex justify-content-between mb-2 text-danger">
                                    <span class="small">Ongkos Kirim:</span>
                                    <span id="det-ongkir-val" class="small fw-bold"></span>
                                </div>
                                <hr class="my-2 border-secondary opacity-25">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="small fw-bold text-uppercase m-0">Total Pembayaran</label>
                                    <h4 class="fw-bold text-dark m-0" id="det-total"></h4>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span class="small text-muted">Metode Bayar:</span>
                                    <span id="det-metode-bayar" class="small fw-bold text-dark">-</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="small text-muted">Status Pembayaran:</span>
                                    <span id="det-status-bayar" class="small fw-bold text-dark">-</span>
                                </div>

                                <div id="det-label-status" class="mt-3"></div>

                                <div id="det-alasan-penolakan" class="mt-2"></div>
                            </div>

                            <div class="alert alert-secondary border-0 py-2 small d-flex align-items-center rounded-3">
                                <i class="fas fa-truck me-2 text-dark"></i>
                                <div>
                                    <span class="d-block small">Metode: <strong id="det-metode"></strong></span>
                                    <div id="det-alamat-full" class="fw-bold text-dark" style="font-size: 0.75rem;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM TERSEMBUNYI --}}
    <form id="form-selesai" method="POST" style="display: none;">
        @csrf @method('PATCH')
    </form>

    <form id="form-batal" method="POST" style="display: none;">
        @csrf @method('DELETE')
    </form>

    <script>
        function showDetail(data) {
            document.getElementById('empty-state').style.display = 'none';
            document.getElementById('detail-content').style.display = 'block';

            document.getElementById('det-id').innerText = 'ORD-' + data.id.toString().padStart(3, '0');
            document.getElementById('det-produk').innerText = data.nama_produk;
            document.getElementById('det-marmer').innerText = 'Material: ' + (data.jenis_marmer || 'Teraso');
            document.getElementById('det-ukuran').innerText = data.ukuran;
            document.getElementById('det-jumlah').innerText = data.jumlah + ' Pcs';
            document.getElementById('det-catatan').innerText = data.catatan_khusus || 'Tidak ada catatan tambahan.';
            document.getElementById('det-metode').innerText = data.metode_pengambilan === 'dikirim' ?
                ('Dikirim (' + (data.jenis_pengiriman ? data.jenis_pengiriman.toUpperCase() : 'Pengiriman') + ')') :
                'Ambil di Rumah';

            const alamatFull = document.getElementById('det-alamat-full');
            const ongkirRow = document.getElementById('det-ongkir-row');
            if (data.metode_pengambilan === 'dikirim') {
                alamatFull.innerText = "Tujuan: " + (data.alamat_pengiriman || '-');
                ongkirRow.classList.remove('d-none');
            } else {
                alamatFull.innerText = "";
                ongkirRow.classList.add('d-none');
            }

            const labelStatus = document.getElementById('det-label-status');
            const metodeBayar = document.getElementById('det-metode-bayar');
            const statusBayar = document.getElementById('det-status-bayar');
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });

            metodeBayar.innerText = (data.midtrans_bank || data.midtrans_payment_type || '-').toString().toUpperCase();
            statusBayar.innerText = data.status_pembayaran === 'paid' ? 'Lunas' : (data.status_pembayaran === 'dp' ? 'DP 50%' : 'Belum Bayar');
            const alasanContainer = document.getElementById('det-alasan-penolakan');
            alasanContainer.innerHTML = '';

            if (data.status == 'Menunggu Verifikasi Admin') {
                document.getElementById('det-harga-produk').innerText = formatter.format(data.total_harga);
                document.getElementById('det-ongkir-val').innerText = formatter.format(data.biaya_pengiriman || 0)
                document.getElementById('det-total').innerText = "Verifikasi Admin";

                // Tambahkan Tombol Batal di Sidebar juga agar informatif
                labelStatus.innerHTML = `
                <span class="badge w-100 bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 py-2 mb-2">
                    <i class="fas fa-hourglass-half me-1"></i> Sedang diperiksa Admin
                </span>
                <button class="btn btn-outline-danger w-100 rounded-pill fw-bold py-2 shadow-sm small" onclick="konfirmasiBatal(${data.id}, '${data.nama_produk}')">
                    <i class="fas fa-times-circle me-1"></i> Batalkan Pesanan Ini
                </button>
            `;
            } else {
                document.getElementById('det-harga-produk').innerText = formatter.format(data.total_harga);
                document.getElementById('det-ongkir-val').innerText = formatter.format(data.biaya_pengiriman || 0);

                const totalAkhir = parseInt(data.total_harga) + parseInt(data.biaya_pengiriman || 0);
                document.getElementById('det-total').innerText = formatter.format(totalAkhir);

                if (data.status === 'Ditolak') {
                    labelStatus.innerHTML =
                        '<span class="badge w-100 bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 py-2"><i class="fas fa-times-circle me-1"></i> Pesanan Ditolak</span>';

                    alasanContainer.innerHTML = `
                        <div class="alert alert-danger border-0 small mt-2 py-2" style="border-radius:12px;">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            <b>Alasan Penolakan:</b><br>
                            ${data.alasan_penolakan || '-'}
                        </div>
                    `;
                } else if (data.status === 'Selesai') {
                    labelStatus.innerHTML =
                        '<span class="badge w-100 bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-2"><i class="fas fa-check-circle me-1"></i> Pesanan Selesai</span>';
                } else if (data.status === 'diekspedisi') {
                    labelStatus.innerHTML = `
                    <div class="alert alert-info border-0 small mb-2 py-2" style="border-radius:12px;">
                        <i class="fas fa-info-circle me-1"></i> Pesanan dalam perjalanan via Bus.
                    </div>
                    <button class="btn btn-success w-100 rounded-pill fw-bold shadow-sm py-2" onclick="konfirmasiSelesai(${data.id}, '${data.nama_produk}')">
                        <i class="fas fa-box-open me-2"></i> Konfirmasi Barang Diterima
                    </button>
                `;
                } else if (data.status === 'Diverifikasi' && data.status_pembayaran === 'no_paid') {
                    labelStatus.innerHTML = `
                            <div class="alert alert-warning border-0 small mb-2 py-2" style="border-radius:12px;">
                                <i class="fas fa-exclamation-circle me-1"></i> Pesanan diverifikasi. Pilih metode pembayaran.
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-warning w-100 rounded-pill fw-bold shadow-sm py-2 text-dark" onclick="bayarSekarang(${data.id}, 'dp')">
                                    <i class="fas fa-credit-card me-2"></i> Bayar DP 50%
                                </button>
                                <button class="btn btn-warning w-100 rounded-pill fw-bold shadow-sm py-2 text-dark" onclick="bayarSekarang(${data.id}, 'lunas')">
                                    <i class="fas fa-wallet me-2"></i> Bayar Lunas
                                </button>
                            </div>
                        `;
                } else if (data.status === 'Diverifikasi' && data.status_pembayaran === 'dp') {
                    labelStatus.innerHTML = `
                    <div class="alert alert-info border-0 small mb-2 py-2" style="border-radius:12px;">
                        <i class="fas fa-check-circle me-1"></i> DP 50% sudah dibayar. Lanjutkan pelunasan.
                    </div>
                    <button class="btn btn-success w-100 rounded-pill fw-bold shadow-sm py-2" onclick="bayarSekarang(${data.id}, 'lunas')">
                        <i class="fas fa-money-check-dollar me-2"></i> Bayar Pelunasan
                    </button>
                `;
                } else if (data.status_pembayaran === 'paid') {
                    labelStatus.innerHTML =
                        '<span class="badge w-100 bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-2"><i class="fas fa-check-circle me-1"></i> Sudah Dibayar</span>';
                } else {
                    labelStatus.innerHTML =
                        '<span class="badge w-100 bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 py-2"><i class="fas fa-tools me-1"></i> ' +
                        data.status + '</span>';
                }
            }

            document.querySelectorAll('tr').forEach(tr => tr.classList.remove('selected-row'));
            const activeRow = document.getElementById('row-' + data.id);
            if (activeRow) activeRow.classList.add('selected-row');
        }

        // FUNGSI PEMBATALAN (DELETE)
        function konfirmasiBatal(id, namaProduk) {
            Swal.fire({
                title: '<h4 class="fw-bold mb-0" style="color: var(--adira-dark)">Batalkan Pesanan?</h4>',
                html: `<p class="text-muted small">Apakah Anda yakin ingin menghapus pesanan <b>${namaProduk}</b>? Tindakan ini tidak dapat dibatalkan.</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#2c3e50',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Kembali',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...',
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        allowOutsideClick: false,
                        showConfirmButton: false
                    });

                    const form = document.getElementById('form-batal');
                    form.action = "{{ url('/') }}/pesanan/" + id;
                    form.submit();
                }
            });
        }

        function konfirmasiSelesai(id, namaProduk) {
            Swal.fire({
                title: '<h4 class="fw-bold mb-0" style="color: var(--adira-dark)">Konfirmasi Penerimaan</h4>',
                html: `<p class="text-muted small">Apakah Anda yakin barang <b>${namaProduk}</b> sudah diterima?</p>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#C5A47E',
                cancelButtonColor: '#2c3e50',
                confirmButtonText: 'Ya, Diterima!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
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
                didOpen: () => {
                    Swal.showLoading();
                },
                allowOutsideClick: false,
                showConfirmButton: false
            });

            fetch(`/pesanan/${pesananId}/snap-token?payment_step=${paymentStep}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    Swal.close();
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            fetch(`/pesanan/${pesananId}/payment-success`, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: JSON.stringify(result)
                                })
                                .finally(() => {
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
                        onPending: function(result) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Menunggu Pembayaran',
                                text: 'Selesaikan pembayaran Anda sesuai instruksi.',
                                timer: 2500,
                                showConfirmButton: false
                            });
                        },
                        onError: function(result) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Pembayaran Gagal',
                                text: 'Terjadi kesalahan. Silakan coba lagi.'
                            });
                        },
                        onClose: function() {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Pembayaran Dibatalkan',
                                text: 'Anda menutup jendela pembayaran.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal menghubungi server. Coba lagi.'
                    });
                });
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
@endsection
