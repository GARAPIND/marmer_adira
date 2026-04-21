<!DOCTYPE html>
<html>

<head>
    <title>Laporan Keuangan Adira Marmer</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 10px;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .summary-box {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>LAPORAN KEUANGAN ADIRA MARMER</h2>
        <p>Ringkasan Transaksi Pembayaran Produk Marmer</p>
    </div>

    <div class="summary-box">
        <strong>Total Pendapatan:</strong> Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}<br>
        <strong>Total Produk Terjual:</strong> {{ $stats['total_produk_terjual'] }}<br>
        <strong>Transaksi Berhasil:</strong> {{ $stats['transaksi_berhasil'] }}
    </div>

    <table>
        <thead>
            <tr style="background-color: #2c3e50; color: white;">
                <th>ID Pesanan</th>
                <th>Nama Pembeli</th>
                <th>Tanggal Update</th>
                <th>Produk</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Ongkir</th>
                <th>Total Bayar</th>
                <th>Metode</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksi as $item)
                <tr>
                    <td class="text-center">
                        ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                    </td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->updated_at->format('d M Y') }}</td>
                    <td>{{ $item->nama_produk }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>

                    <td>
                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                    </td>

                    <td>
                        Rp {{ number_format($item->biaya_pengiriman ?? 0, 0, ',', '.') }}
                    </td>

                    <td>
                        Rp {{ number_format($item->total_harga + ($item->biaya_pengiriman ?? 0), 0, ',', '.') }}
                    </td>

                    <td class="text-center">
                        @if ($item->metode_pengambilan === 'dikirim')
                            Dikirim
                        @elseif ($item->metode_pengambilan === 'dirumah')
                            Ambil di rumah
                        @endif
                    </td>

                    @if ($item->status_pembayaran == 'paid')
                        <td class="text-center">
                            Lunas
                        </td>
                    @else
                        <td class="text-center">
                            Belum dibayar
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">
                        Tidak ada data transaksi
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
