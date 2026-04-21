<!DOCTYPE html>
<html>

<head>
    <title>Laporan Pesanan Adira Marmer</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Statistik Pesanan</h2>
        <p>Adira Marmer - Sistem Informasi Pemesanan Produk Marmer</p>
    </div>
    <table>
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>Total Pesanan</th>
                <th>Diverifikasi</th>
                <th>Diproses</th>
                <th>Selesai</th>
                <th>Ditolak</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $stats['total'] }}</td>
                <td>{{ $stats['diverifikasi'] }}</td>
                <td>{{ $stats['diproses'] }}</td>
                <td>{{ $stats['selesai'] }}</td>
                <td>{{ $stats['ditolak'] }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
