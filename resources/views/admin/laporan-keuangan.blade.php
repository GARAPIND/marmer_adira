@extends('layouts.app')

@section('content')
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

        .card-stat {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .card-stat:hover {
            transform: translateY(-5px);
        }

        .table-elegant thead th {
            background-color: var(--adira-dark);
            color: white;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            padding: 1.2rem;
            border: none;
        }

        .btn-gold {
            background-color: var(--adira-gold);
            color: white;
            border: none;
            font-weight: 600;
            border-radius: 50px;
        }

        .btn-gold:hover {
            background-color: #b08d44;
            color: white;
        }
    </style>

    <div class="container py-5 mt-2 animate__animated animate__fadeIn">
        {{-- HEADER --}}
        <div class="page-header-elegant d-flex align-items-center">
            <div class="marble-icon-box me-3 shadow-sm">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <h2 class="fw-bold mb-0 text-dark" style="border-left: 5px solid #000; padding-left: 15px;">Laporan Keuangan
                </h2>
                <p class="text-muted small mb-0">Pantau arus kas, uang muka, dan pelunasan pesanan</p>
            </div>
        </div>

        {{-- FILTER --}}
        <div class="card border-0 shadow-sm rounded-4 mb-5">
            <div class="card-body p-4">
                <form action="" method="GET">
                    <div class="row align-items-end g-3">
                        <div class="col-md-4">
                            <label class="fw-bold small mb-2">Tanggal Mulai:</label>
                            <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}"
                                class="form-control shadow-none border-light bg-light">
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold small mb-2">Tanggal Akhir:</label>
                            <input type="date" name="tgl_akhir" value="{{ request('tgl_akhir') }}"
                                class="form-control shadow-none border-light bg-light">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-gold w-100 py-2 shadow-sm">
                                <i class="fas fa-search me-2"></i> Tampilkan Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- STATS CARDS --}}
        <div class="row g-4 mb-5 text-center">
            <div class="col-md-4">
                <div class="card card-stat p-4 border-start border-4 border-primary">
                    <p class="text-muted small fw-bold text-uppercase mb-1">Total Pendapatan</p>
                    <h4 class="fw-extrabold text-dark">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stat p-4 border-start border-4 border-warning">
                    <p class="text-muted small fw-bold text-uppercase mb-1">Total Produk Terjual</p>
                    <h4 class="fw-extrabold text-dark">{{ number_format($stats['total_produk_terjual'], 0, ',', '.') }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stat p-4 border-start border-4 border-success">
                    <p class="text-muted small fw-bold text-uppercase mb-1">Transaksi Berhasil</p>
                    <h4 class="fw-extrabold text-dark">{{ number_format($stats['transaksi_berhasil'], 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        {{-- TABEL RINCIAN --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0"><i class="fas fa-list-ul me-2 text-gold"></i>Rincian Transaksi</h5>
            <div class="gap-2 d-flex">
                {{-- Tombol disamakan dengan Laporan Pesanan: Teks "Export PDF" dan gaya 'btn-outline-dark' --}}
                <a href="{{ route('admin.laporan.keuangan.pdf', request()->query()) }}"
                    class="btn btn-outline-dark px-4 shadow-sm fw-bold rounded-pill">
                    <i class="fas fa-file-pdf text-danger me-2"></i> Export PDF
                </a>

                <a href="{{ route('admin.laporan.keuangan.excel', request()->query()) }}"
                    class="btn btn-outline-dark px-4 shadow-sm fw-bold rounded-pill">
                    <i class="fas fa-file-excel text-success me-2"></i> Export Excel
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="table-responsive">
                <table class="table table-elegant hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID Pesanan</th>
                            <th>Nama Pembeli</th>
                            <th>Tanggal Update</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th>Ongkir</th>
                            <th>Total Bayar</th>
                            <th>Metode</th>
                            <th class="pe-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $item)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="fw-semibold">{{ $item->user->name }}</td>
                                <td class="text-muted small">{{ $item->updated_at->format('d M Y') }}</td>
                                <td>{{ $item->nama_produk }}</td>
                                <td class="fw-bold">{{ $item->jumlah }}</td>
                                <td class="fw-bold">Rp
                                    {{ number_format($item->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="fw-bold">Rp
                                    {{ number_format($item->biaya_pengiriman, 0, ',', '.') }}
                                </td>
                                <td class="fw-bold">Rp
                                    {{ number_format($item->total_harga + ($item->biaya_pengiriman ?? 0), 0, ',', '.') }}
                                </td>
                                <td>
                                    @if ($item->metode_pengambilan === 'dikirim')
                                        <span class="badge bg-light text-primary border border-primary px-3">Dikirim</span>
                                    @elseif ($item->metode_pengambilan === 'dirumah')
                                        <span class="badge bg-light text-success border border-success px-3">Ambil di
                                            rumah</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    @if ($item->status_pembayaran === 'paid')
                                        <span
                                            class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2 fw-bold">
                                            <i class="fas fa-check-circle me-1"></i> Lunas
                                        </span>
                                    @else
                                        <span
                                            class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2 fw-bold">
                                            <i class="fas fa-close me-1"></i> Belum dibayar
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted italic">Belum ada data transaksi untuk
                                    periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
@endsection
