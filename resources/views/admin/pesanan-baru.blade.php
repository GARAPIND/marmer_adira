@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .gambar-referensi-item {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            cursor: zoom-in;
            border: 1px solid #eee;
        }

        .order-item-stack {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-height: 240px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .order-item-card {
            border: 1px solid rgba(44, 62, 80, 0.08);
            border-radius: 14px;
            background: #fff;
            padding: 12px 14px;
        }

        .order-item-card .topline {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
        }

        .order-item-card .item-name {
            font-weight: 700;
            color: var(--adira-dark);
        }

        .order-item-card .item-meta {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .order-item-card .item-note {
            font-size: 0.8rem;
            color: #7b8794;
            margin-top: 6px;
            font-style: italic;
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
                <a href="{{ route('admin.pesanan.trash') }}"
                    class="btn btn-outline-dark rounded-pill px-4 shadow-sm fw-bold mt-3">
                    <i class="fas fa-trash-alt me-2"></i> Sampah Pesanan
                </a>
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
                                    @if ($item->relationLoaded('items') && $item->items->count() > 1)
                                        <small class="text-muted d-block">{{ $item->items->count() }} item dalam pesanan ini</small>
                                    @endif
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
                                            {{ $item->status_pembayaran == 'paid' ? 'Lunas' : 'Dibayar DP' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.pesanan.show', $item->id) }}"
                                            class="btn btn-gold btn-sm px-4 shadow-sm fw-bold">
                                            <i class="fas fa-check-double me-1 text-white"></i> Verifikasi
                                        </a>
                                        <button class="btn btn-outline-danger btn-sm px-3 shadow-sm fw-bold"
                                            onclick="konfirmasiHapusPesanan({{ $item->id }}, '{{ $item->nama_produk }}')">
                                            <i class="fas fa-trash-alt me-1"></i> Hapus
                                        </button>
                                    </div>
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

    <form id="form-hapus-pesanan" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        let activeAdminOrder = null;

        function konfirmasiHapusPesanan(id, namaProduk) {
            Swal.fire({
                title: 'Pindahkan ke sampah?',
                html: `Pesanan <b>${namaProduk}</b> akan dipindahkan ke sampah dan masih bisa dipulihkan oleh admin.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#2c3e50',
                confirmButtonText: 'Ya, pindahkan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('form-hapus-pesanan');
                    form.action = `/admin/pesanan/${id}`;
                    form.submit();
                }
            });
        }
    </script>
@endsection
