<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pengguna - Adira Marmer</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #2c3e50;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
            border-bottom: 3px solid #C5A47E;
            padding-bottom: 16px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
            color: #2c3e50;
        }

        .header p {
            margin: 4px 0 0;
            color: #888;
            font-size: 11px;
        }

        .stats-row {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
        }

        .stat-box {
            flex: 1;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }

        .stat-box .num {
            font-size: 22px;
            font-weight: 800;
            color: #2c3e50;
        }

        .stat-box .lbl {
            font-size: 10px;
            color: #888;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        thead th {
            background-color: #2c3e50;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
        }

        tbody td {
            padding: 9px 8px;
            border-bottom: 1px solid #f1f1f1;
            font-size: 11px;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            background: #e9ecef;
            color: #495057;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #aaa;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Pengguna</h2>
        <p>Adira Marmer &mdash; Dicetak pada {{ now()->format('d M Y, H:i') }} WIB</p>
    </div>

    <div class="stats-row">
        @foreach ($roles as $role)
            <div class="stat-box">
                <div class="num">{{ $stats[$role] ?? 0 }}</div>
                <div class="lbl">{{ ucfirst($role) }}</div>
            </div>
        @endforeach
        <div class="stat-box">
            <div class="num">{{ $stats['total'] }}</div>
            <div class="lbl">Total Pengguna</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No. Telepon</th>
                <th>Role</th>
                <th style="text-align:center;">Pesanan</th>
                <th>Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->no_telp ?? '-' }}</td>
                    <td><span class="badge">{{ ucfirst($user->role) }}</span></td>
                    <td style="text-align:center;">{{ $user->pesanan_count }}</td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:#aaa; padding: 20px;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Adira Marmer &copy; {{ now()->year }}</div>
</body>

</html>
