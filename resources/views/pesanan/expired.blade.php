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
            letter-spacing: 1px;
            padding: 1.25rem;
            border: none;
        }

        .table-elegant tbody td {
            vertical-align: middle;
            padding: 1.1rem 1rem;
            border-bottom: 1px solid #f8f9fa;
        }

        .badge-status-pill {
            padding: 0.5em 1.2em;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
        }
    </style>

    <div class="container py-5 mt-2">
        <div class="page-header-elegant d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <div class="marble-icon-box me-3 shadow-sm">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0 text-dark" style="border-left: 5px solid #000; padding-left: 15px;">Pesanan Expired</h2>
                    <p class="text-muted small mb-0">Pesanan yang melewati batas 1 hari tanpa pembayaran dipindahkan ke sini dan tidak bisa diproses lagi.</p>
                </div>
            </div>
            <a href="{{ route('pesanan.index') }}" class="btn btn-dark rounded-pill px-4 shadow-sm fw-bold">
                <i class="fas fa-arrow-left me-2 text-gold"></i> Kembali ke Riwayat
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="table-responsive">
                <table class="table table-elegant align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID Pesanan</th>
                            <th>Produk</th>
                            <th>Status</th>
                            <th>Expired Pada</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanan as $item)
                            <tr>
                                <td class="ps-4 fw-bold text-primary small">ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $item->nama_produk }}</div>
                                    <div class="small text-muted">{{ $item->created_at->format('d M Y H:i') }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-status-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Expired</span>
                                </td>
                                <td class="small text-muted">
                                    {{ optional($item->expired_at ?? $item->effective_expires_at)->format('d M Y H:i') }}
                                </td>
                                <td class="small text-muted">
                                    Pesanan ini tidak bisa dibayar, dikonfirmasi, atau diproses ulang.
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted small fst-italic">Belum ada pesanan expired.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
