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

        .btn-gold {
            background-color: var(--adira-gold);
            border-color: var(--adira-gold);
            color: white;
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            background-color: #b08d44;
            border-color: #b08d44;
            color: white;
        }

        .btn-outline-elegant {
            color: var(--adira-dark);
            border-color: #dee2e6;
            background-color: white;
        }

        .btn-outline-elegant:hover {
            background-color: #f8f9fa;
            border-color: var(--adira-dark);
            color: var(--adira-dark);
        }

        .card-filter {
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 16px;
        }

        .table-elegant thead th {
            background-color: var(--adira-dark);
            color: white;
            font-weight: 600;
            border: none;
        }

        .table-elegant tbody td {
            vertical-align: middle;
            padding: 1rem;
            border-color: #f1f1f1;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--adira-dark);
        }
    </style>

    <div class="container py-5 mt-3">
        <div class="d-flex align-items-center mb-4">
            <i class="fas fa-users fa-2x me-3 text-gold"></i>
            <h2 class="fw-bold m-0" style="font-family: 'Inter', sans-serif; color: var(--adira-dark);">Laporan Pengguna</h2>
        </div>

        <div class="card card-filter mb-5 bg-white">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 text-muted small text-uppercase">Filter Periode Pendaftaran</h5>
                <form action="{{ route('admin.laporan.pengguna') }}" method="GET">
                    <div class="row align-items-end g-3">
                        <div class="col-md-5">
                            <label class="fw-bold small mb-2 text-secondary">Rentang Tanggal:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i
                                        class="fas fa-calendar-alt text-muted"></i></span>
                                <input type="date" name="tgl_mulai" class="form-control border-start-0"
                                    value="{{ request('tgl_mulai') }}">
                                <span class="input-group-text bg-white border-start-0 border-end-0">s/d</span>
                                <span class="input-group-text bg-light border-end-0"><i
                                        class="fas fa-calendar-alt text-muted"></i></span>
                                <input type="date" name="tgl_akhir" class="form-control border-start-0"
                                    value="{{ request('tgl_akhir') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold small mb-2 text-secondary">Role Pengguna:</label>
                            <select name="role" class="form-select">
                                <option value="Semua" {{ request('role', 'Semua') == 'Semua' ? 'selected' : '' }}>Semua
                                    Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                        {{ ucfirst($role) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-gold w-100 py-2 fw-bold shadow-sm">
                                <i class="fas fa-search me-2"></i>Cari Pengguna
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-elegant mb-0 text-center">
                    <thead>
                        <tr>
                            @foreach ($roles as $role)
                                <th class="py-3">
                                    <i class="fas fa-user me-2"></i>{{ ucfirst($role) }}
                                </th>
                            @endforeach
                            <th class="py-3">Total Akun Aktif</th>
                            <th class="py-3">Status Dominan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($roles as $role)
                                <td><span class="stat-number">{{ $stats[$role] ?? 0 }}</span></td>
                            @endforeach
                            <td><span class="stat-number text-primary">{{ $stats['total'] }}</span></td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold">
                                    <i class="fas fa-check-circle me-1"></i> Aktif
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold m-0" style="color: var(--adira-dark);">
                    <i class="fas fa-list me-2 text-gold"></i>Daftar Pengguna
                    <span class="badge bg-light text-secondary ms-2 fw-normal">{{ $users->count() }} data</span>
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-elegant mb-0">
                    <thead>
                        <tr>
                            <th class="py-3 text-center" style="width: 50px;">No</th>
                            <th class="py-3">Nama</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">No. Telepon</th>
                            <th class="py-3 text-center">Role</th>
                            <th class="py-3 text-center">Jumlah Pesanan</th>
                            <th class="py-3 text-center">Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td class="text-center text-muted">{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td class="text-muted">{{ $user->email }}</td>
                                <td class="text-muted">{{ $user->no_telp ?? '-' }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge px-3 py-2 rounded-pill fw-bold
                                @if ($user->role === 'pembeli') bg-primary bg-opacity-10 text-primary
                                @elseif($user->role === 'pengrajin') bg-warning bg-opacity-10 text-warning
                                @else bg-secondary bg-opacity-10 text-secondary @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold {{ $user->pesanan_count > 0 ? 'text-success' : 'text-muted' }}">
                                        {{ $user->pesanan_count }}
                                    </span>
                                </td>
                                <td class="text-center text-muted">{{ $user->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-users-slash fa-2x mb-3 d-block opacity-30"></i>
                                    Tidak ada data pengguna ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.laporan.pengguna.pdf', request()->query()) }}"
                class="btn btn-outline-elegant px-4 shadow-sm fw-bold rounded-pill">
                <i class="fas fa-file-pdf text-danger me-2"></i>Export PDF
            </a>
            <a href="{{ route('admin.laporan.pengguna.excel', request()->query()) }}"
                class="btn btn-outline-elegant px-4 shadow-sm fw-bold rounded-pill">
                <i class="fas fa-file-excel text-success me-2"></i>Export Excel
            </a>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
