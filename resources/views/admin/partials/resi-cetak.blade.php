<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resi Pengiriman ORD-{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2933;
            font-size: 12px;
        }

        .sheet {
            border: 2px solid #2c3e50;
            border-radius: 18px;
            padding: 20px;
        }

        .header {
            border-bottom: 2px solid #c5a47e;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .subtitle {
            color: #52606d;
            font-size: 11px;
        }

        .grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .grid td {
            vertical-align: top;
            padding: 8px 10px;
            border: 1px solid #d9e2ec;
        }

        .label {
            font-size: 10px;
            text-transform: uppercase;
            color: #7b8794;
            margin-bottom: 4px;
        }

        .value {
            font-weight: bold;
            line-height: 1.45;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
        }

        .items th,
        .items td {
            border: 1px solid #d9e2ec;
            padding: 8px 10px;
        }

        .items th {
            background: #2c3e50;
            color: #fff;
            text-transform: uppercase;
            font-size: 10px;
        }

        .footer-note {
            margin-top: 16px;
            padding: 10px 12px;
            background: #f8fafb;
            border-left: 4px solid #c5a47e;
            color: #52606d;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="header">
            <div class="title">Resi Internal Pengiriman</div>
            <div class="subtitle">Label ini dicetak dari sistem untuk ditempel ke paket sebelum diserahkan ke cargo.</div>
        </div>

        <table class="grid">
            <tr>
                <td width="50%">
                    <div class="label">Kode Resi Internal</div>
                    <div class="value">{{ $pesanan->kode_resi_internal }}</div>
                </td>
                <td width="50%">
                    <div class="label">Order</div>
                    <div class="value">ORD-{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="label">Penerima</div>
                    <div class="value">{{ $pesanan->user->name }}</div>
                </td>
                <td>
                    <div class="label">Tanggal Cetak</div>
                    <div class="value">{{ now()->format('d M Y H:i') }}</div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="label">Alamat Pengiriman</div>
                    <div class="value">{{ $pesanan->alamat_pengiriman ?? '-' }}</div>
                </td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Ukuran</th>
                    <th>Bahan</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesanan->items as $item)
                    <tr>
                        <td>{{ $item->nama_produk }}</td>
                        <td>{{ $item->ukuran ?? '-' }}</td>
                        <td>{{ $item->jenis_marmer ?? '-' }}</td>
                        <td>{{ $item->jumlah }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer-note">
            Nomor resi resmi cargo diisi setelah paket benar-benar diterima ekspedisi.
        </div>
    </div>
</body>
</html>
