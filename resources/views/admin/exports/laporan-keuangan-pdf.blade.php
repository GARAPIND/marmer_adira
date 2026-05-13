<!DOCTYPE html>
<html>

<head>
    <title>Laporan Keuangan Adira Marmer</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .summary-box {
            background: #f4f4f4;
            padding: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>LAPORAN KEUANGAN ADIRA MARMER</h2>
        <p>Periode: {{ request('tgl_mulai') ?? '-' }} s/d {{ request('tgl_akhir') ?? '-' }}</p>
    </div>

    <div class="summary-box">
        <strong>Total Pendapatan:</strong> Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}<br>
        <strong>Total Produk Terjual:</strong> {{ $stats['total_produk_terjual'] }}<br>
        <strong>Transaksi Berhasil:</strong> {{ $stats['transaksi_berhasil'] }}<br>
        <strong>Belum Bayar:</strong> {{ $stats['status_belum_bayar'] }} |
        <strong>DP:</strong> {{ $stats['status_dp_50'] }} |
        <strong>Lunas:</strong> {{ $stats['status_lunas'] }}
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tgl Pesanan</th>
                <th>Pembeli</th>
                <th>Produk</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Ongkir</th>
                <th>Dibayar</th>
                <th>Sisa</th>
                <th>Status</th>
                <th>Metode</th>
                <th>Tgl DP</th>
                <th>Lunas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi as $item)
                @php
                    $summary = $item->payment_summary ?? [];
                    $totalHarga = (int) $item->total_harga;
                    $ongkir = (int) ($item->biaya_pengiriman ?? 0);
                    $totalDibayar = (int) ($summary['total_dibayar'] ?? 0);
                    $sisa = $totalHarga + $ongkir - $totalDibayar;

                    $status =
                        $item->status_pembayaran === 'paid'
                            ? 'Lunas'
                            : ($item->status_pembayaran === 'dp'
                                ? 'DP'
                                : 'Belum');

                    $tglPesanan = \Carbon\Carbon::parse($item->created_at)->format('d M Y');
                    $tglDP = !empty($summary['waktu_dp'])
                        ? \Carbon\Carbon::parse($summary['waktu_dp'])->format('d M Y')
                        : '-';
                    $tglLunas = !empty($summary['waktu_lunas'])
                        ? \Carbon\Carbon::parse($summary['waktu_lunas'])->format('d M Y')
                        : '-';
                @endphp

                <tr>
                    <td class="text-center">
                        ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                    </td>
                    <td>{{ $tglPesanan }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->nama_produk }} ({{ $item->jenis_marmer }})</td>
                    <td class="text-center">{{ $item->jumlah }}</td>

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

                    <td class="text-center">{{ $status }}</td>
                    <td>{{ $summary['metode_terakhir'] ?? '-' }}</td>
                    <td>{{ $tglDP }}</td>
                    <td>{{ $tglLunas }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
