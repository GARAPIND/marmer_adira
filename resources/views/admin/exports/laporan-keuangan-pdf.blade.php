<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan Adira Marmer</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 10px; text-align: left; }
        .text-center { text-align: center; }
        .header { text-align: center; margin-bottom: 20px; }
        .summary-box { background: #f9f9f9; padding: 15px; border-radius: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KEUANGAN ADIRA MARMER</h2>
        <p>Ringkasan Transaksi Pembayaran Produk Marmer</p>
    </div>

    <div class="summary-box">
        <strong>Total Pendapatan:</strong> Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}<br>
        <strong>Total DP Masuk:</strong> Rp {{ number_format($stats['total_dp'], 0, ',', '.') }}<br>
        <strong>Jumlah Transaksi:</strong> {{ $stats['jumlah_transaksi'] }}
    </div>

    <table>
        <thead>
            <tr style="background-color: #2c3e50; color: white;">
                <th>ID Pesanan</th>
                <th>Nama Pembeli</th>
                <th>Tanggal Bayar</th>
                <th>Jenis</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $item)
            <tr>
                <td class="text-center">ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->updated_at->format('d M Y') }}</td>
                <td>{{ $item->status == 'Diverifikasi' ? 'DP (30%)' : 'Pelunasan' }}</td>
                <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>