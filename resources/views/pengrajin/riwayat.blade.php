@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --adira-gold: #C5A47E;
            --adira-dark: #2c3e50;
            --adira-cream: #FDFCF8;
        }

        body {
            background-color: var(--adira-cream);
            font-family: 'Inter', sans-serif;
        }

        .page-header-elegant {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            border-bottom: 4px solid var(--adira-gold);
        }

        .marble-icon-box {
            width: 65px;
            height: 65px;
            background: rgba(197, 164, 126, 0.15);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--adira-gold);
            font-size: 2rem;
        }

        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
            margin-bottom: 2rem;
        }

        .card-table {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .table-elegant thead th {
            background-color: var(--adira-dark);
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1.5px;
            padding: 1.5rem 1rem;
            border: none;
        }

        .table-elegant tbody td {
            vertical-align: middle;
            padding: 1.3rem 1rem;
            border-color: #f8f9fa;
            font-size: 0.95rem;
            color: #444;
        }

        .status-badge {
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.7rem;
            text-transform: uppercase;
        }

        .btn-action-custom {
            font-weight: 700;
            border-radius: 50px;
            padding: 6px 20px;
            transition: 0.3s;
            font-size: 0.85rem;
            border: 2px solid;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        }

        .btn-detail {
            border-color: var(--adira-dark);
            color: var(--adira-dark);
            background: transparent;
        }

        .btn-detail:hover {
            background: var(--adira-dark);
            color: white;
        }

        .btn-bus {
            border-color: #17a2b8;
            color: #17a2b8;
            background: transparent;
        }

        .btn-bus:hover {
            background: #17a2b8;
            color: white;
        }

        /* SweetAlert Custom Style */
        .swal2-popup {
            border-radius: 25px !important;
            font-family: 'Inter', sans-serif;
        }

        .swal2-styled.swal2-confirm {
            border-radius: 50px !important;
            padding: 12px 30px !important;
        }
    </style>

    <div class="container py-5 mt-2 animate__animated animate__fadeIn">
        <div class="page-header-elegant d-flex align-items-center shadow-sm">
            <div class="marble-icon-box me-4 shadow-sm"><i class="fas fa-history"></i></div>
            <div>
                <h2 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif;">Riwayat Pesanan Pengrajin</h2>
                <p class="text-muted small mb-0">Daftar arsip pengerjaan pesanan yang telah diselesaikan</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="filter-section shadow-sm">
            <form action="{{ route('pengrajin.riwayat') }}" method="GET">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-3" style="border: 2px solid #f1f1f1;">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0"
                        placeholder="Cari ID Pesanan atau Nama Pembeli..." value="{{ request('search') }}"
                        style="border: 2px solid #f1f1f1; border-radius: 0 12px 12px 0;">
                </div>
            </form>
        </div>

        <div class="card card-table shadow-sm">
            <div class="table-responsive">
                <table class="table table-elegant mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID Pesanan</th>
                            <th>Tanggal Selesai</th>
                            <th>Nama Pembeli</th>
                            <th>Produk / Jenis</th>
                            <th>Status Akhir</th>
                            <th class="pe-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $item)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>{{ $item->updated_at->format('d/m/Y') }}</td>
                                <td class="fw-semibold">{{ $item->user->name ?? 'Pelanggan' }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->nama_produk }}</div>
                                    <small class="text-gold fw-bold"
                                        style="color: var(--adira-gold);">{{ $item->jenis_marmer }}</small>
                                </td>
                                <td>
                                    @if ($item->status == 'diekspedisi')
                                        <span class="status-badge bg-info bg-opacity-10 text-info">
                                            <i class="fas fa-truck-moving me-1"></i> Diekspedisi
                                        </span>
                                    @else
                                        <span class="status-badge bg-success bg-opacity-10 text-success">
                                            <i class="fas fa-check-circle me-1"></i> Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="pe-4 text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('pengrajin.riwayat.detail', $item->id) }}"
                                            class="btn-action-custom btn-detail">Detail</a>

                                        @if ($item->metode_pengambilan == 'dikirim' && $item->status != 'diekspedisi')
                                            <form id="form-bus-{{ $item->id }}"
                                                action="{{ route('pengrajin.update.status', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="diekspedisi">
                                                <button type="button" class="btn-action-custom btn-bus"
                                                    onclick="konfirmasiKirimBus(
                                            '{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}', 
                                            '{{ number_format($item->biaya_pengiriman, 0, ',', '.') }}', 
                                            '{{ number_format($item->total_harga + $item->biaya_pengiriman, 0, ',', '.') }}', 
                                            '{{ $item->alamat_pengiriman }}', 
                                            {{ $item->id }}
                                        )">
                                                    <i class="fas fa-bus"></i> Kirim
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat pesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function konfirmasiKirimBus(orderId, ongkir, total, tujuan, id) {
            Swal.fire({
                title: '<h4 class="fw-bold mb-0" style="color: var(--adira-dark)">Konfirmasi Pengiriman</h4>',
                html: `
            <div class="text-start p-3 mt-3" style="background: #f8f9fa; border-radius: 15px; border-left: 5px solid var(--adira-gold);">
                <div class="mb-2 small">
                    <label class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">ID Pesanan</label>
                    <div class="fw-bold text-primary">ORD-${orderId}</div>
                </div>
                <div class="mb-2 small">
                    <label class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Tujuan & Rincian</label>
                    <div class="fw-bold text-dark">${tujuan}</div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Ongkos Kirim</label>
                        <div class="fw-bold text-danger">Rp ${ongkir}</div>
                    </div>
                    <div class="col-6">
                        <label class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Total Bayar</label>
                        <div class="fw-bold text-success">Rp ${total}</div>
                    </div>
                </div>
            </div>
            <p class="text-muted small mt-3 mb-0 italic">Pastikan barang sudah diberikan ke eksepedisi.</p>
        `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#C5A47E',
                cancelButtonColor: '#2c3e50',
                confirmButtonText: 'Ya, Sudah Dikirim!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-bus-' + id).submit();
                }
            });
        }
    </script>
@endsection
