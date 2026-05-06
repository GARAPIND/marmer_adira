@extends('layouts.app')

@section('content')
    <style>
        :root {
            --adira-gold: #C5A47E;
            --adira-dark: #2c3e50;
        }

        .card-stat {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .table-elegant thead th {
            background-color: var(--adira-dark);
            color: white;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            border: none;
        }
    </style>

    <div class="container py-5 mt-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Laporan Keuangan</h2>
                <p class="text-muted small mb-0">Status pembayaran, metode bayar, dan waktu lunas</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.laporan.keuangan.pdf', request()->query()) }}"
                    class="btn btn-outline-dark rounded-pill px-4 fw-bold">Export PDF</a>
                <a href="{{ route('admin.laporan.keuangan.excel', request()->query()) }}"
                    class="btn btn-outline-dark rounded-pill px-4 fw-bold">Export Excel</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body">
                <form method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="small fw-bold">Tanggal Mulai</label>
                            <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold">Tanggal Akhir</label>
                            <input type="date" name="tgl_akhir" class="form-control" value="{{ request('tgl_akhir') }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-dark w-100">Tampilkan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card card-stat p-3">
                    <small class="text-muted">Total Produk Terjual</small>
                    <h4 class="fw-bold mb-0">{{ $stats['total_produk_terjual'] }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat p-3">
                    <small class="text-muted">Transaksi Berhasil</small>
                    <h4 class="fw-bold mb-0">{{ $stats['transaksi_berhasil'] }}</h4>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-stat p-3">
                    <small class="text-muted">Belum Bayar</small>
                    <h4 class="fw-bold mb-0">{{ $stats['status_belum_bayar'] }}</h4>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-stat p-3">
                    <small class="text-muted">DP 50%</small>
                    <h4 class="fw-bold mb-0">{{ $stats['status_dp_50'] }}</h4>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-stat p-3">
                    <small class="text-muted">Lunas</small>
                    <h4 class="fw-bold mb-0">{{ $stats['status_lunas'] }}</h4>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card card-stat p-3">
                    <small class="text-muted">Metode BRI</small>
                    <h5 class="fw-bold mb-0">{{ $stats['metode_bri'] }}</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stat p-3">
                    <small class="text-muted">Metode BCA</small>
                    <h5 class="fw-bold mb-0">{{ $stats['metode_bca'] }}</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stat p-3">
                    <small class="text-muted">Metode Mandiri</small>
                    <h5 class="fw-bold mb-0">{{ $stats['metode_mandiri'] }}</h5>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-elegant align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Pembeli</th>
                            <th>Metode Pembayaran</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Waktu Lunas</th>
                            <th class="text-end pe-4">Total Dibayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksi as $item)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ strtoupper($item->midtrans_bank ?? $item->midtrans_payment_type ?? '-') }}</td>
                                <td>{{ $item->jenis_pembayaran === 'dp' ? 'DP' : 'Lunas' }}</td>
                                <td>
                                    {{ $item->status_pembayaran === 'paid' ? 'Lunas' : ($item->status_pembayaran === 'dp' ? 'Dibayar DP' : 'Belum Bayar') }}
                                </td>
                                <td>{{ $item->tanggal_lunas ? $item->tanggal_lunas->format('d M Y H:i') : '-' }}</td>
                                <td class="text-end pe-4">Rp {{ number_format($item->jumlah_dibayar ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada data transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
