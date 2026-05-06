<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan Adira Marmer</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        .text-center { text-align: center; }
        .header { text-align: center; margin-bottom: 20px; }
        .summary-box { background: #f9f9f9; padding: 15px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KEUANGAN ADIRA MARMER</h2>
        <p>Ringkasan Status Pembayaran, Riwayat DP, dan Metode Bayar</p>
    </div>

    <div class="summary-box">
        <strong>Total Pendapatan:</strong> Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}<br>
        <strong>Total Produk Terjual:</strong> {{ $stats['total_produk_terjual'] }}<br>
        <strong>Transaksi Berhasil:</strong> {{ $stats['transaksi_berhasil'] }}<br>
        <strong>Belum Bayar:</strong> {{ $stats['status_belum_bayar'] }} |
        <strong>DP 50%:</strong> {{ $stats['status_dp_50'] }} |
        <strong>Lunas:</strong> {{ $stats['status_lunas'] }}
    </div>

    <table>
        <thead>
            <tr style="background-color: #2c3e50; color: white;">
                <th>ID Pesanan</th>
                <th>Nama Pembeli</th>
                <th>Metode</th>
                <th>Status Bayar</th>
                <th>Riwayat</th>
                <th>Bayar Pertama</th>
                <th>Waktu Lunas</th>
                <th>Nominal Dibayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi as $item)
                <tr>
                    <td class="text-center">ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->payment_summary['metode_terakhir'] }}</td>
                    <td>{{ $item->status_pembayaran === 'paid' ? 'Lunas' : ($item->status_pembayaran === 'dp' ? 'Dibayar DP' : 'Belum Bayar') }}</td>
                    <td>{{ $item->payment_summary['status_label'] }}</td>
                    <td>{{ $item->payment_summary['waktu_bayar_pertama'] ? \Carbon\Carbon::parse($item->payment_summary['waktu_bayar_pertama'])->format('d M Y H:i') : '-' }}</td>
                    <td>{{ $item->payment_summary['waktu_lunas'] ? \Carbon\Carbon::parse($item->payment_summary['waktu_lunas'])->format('d M Y H:i') : '-' }}</td>
                    <td>Rp {{ number_format($item->jumlah_dibayar ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
