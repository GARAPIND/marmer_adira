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

        .table-elegant {
            border-radius: 12px;
            overflow: hidden;
        }

        .table-elegant thead {
            background: #f8f9fa;
            font-weight: 600;
        }

        .table-elegant tbody tr:hover {
            background: #f1f3f5;
        }
    </style>

    <div class="container py-5 mt-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Laporan Keuangan</h2>
                <p class="text-muted small mb-0">Status pembayaran, tanggal DP, tanggal lunas, dan total dibayar</p>
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

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-elegant align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Tanggal Pesanan</th>
                            <th>Pembeli</th>
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                            <th class="text-end">Total Harga Produk</th>
                            <th class="text-end">Ongkos Kirim</th>
                            <th class="text-end">Total Dibayar</th>
                            <th class="text-end">Sisa Pembayaran</th>
                            <th>Status</th>
                            <th>Metode Pembayaran</th>
                            <th>Tanggal DP</th>
                            <th>Waktu Lunas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksi as $item)
                            @php
                                $totalHarga = (int) $item->total_harga;
                                $ongkir = $item->biaya_pengiriman ?? 0;
                                $totalDibayar = $item->payment_summary['total_dibayar'] ?? 0;
                                $sisa = $totalHarga + $ongkir - $totalDibayar;
                            @endphp

                            <tr>
                                <td class="ps-4 fw-bold text-primary">
                                    ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}
                                </td>

                                <td>{{ $item->user->name }}</td>

                                <td>
                                    {{ $item->nama_produk }} ({{ $item->jenis_marmer }})
                                </td>

                                <td>{{ $item->jumlah }}</td>

                                <td class="text-end">
                                    Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format($ongkir, 0, ',', '.') }}
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format($sisa, 0, ',', '.') }}
                                </td>

                                <td>
                                    @if ($item->status_pembayaran === 'paid')
                                        <span class="badge bg-success">Lunas</span>
                                    @elseif ($item->status_pembayaran === 'dp')
                                        <span class="badge bg-warning text-dark">DP</span>
                                    @else
                                        <span class="badge bg-danger">Belum</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $item->payment_summary['metode_terakhir'] ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->payment_summary['waktu_dp']
                                        ? \Carbon\Carbon::parse($item->payment_summary['waktu_dp'])->format('d M Y H:i')
                                        : '-' }}
                                </td>

                                <td>
                                    {{ $item->payment_summary['waktu_lunas']
                                        ? \Carbon\Carbon::parse($item->payment_summary['waktu_lunas'])->format('d M Y H:i')
                                        : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center py-4 text-muted">
                                    Belum ada data transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
