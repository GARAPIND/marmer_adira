@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --adira-gold: #C5A47E;
            --adira-dark: #2c3e50;
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

        .table-elegant thead th {
            background-color: var(--adira-dark);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            padding: 1.25rem;
            border: none;
        }

        .table-elegant tbody td {
            vertical-align: middle;
            padding: 1.1rem 1rem;
            border-bottom: 1px solid #f8f9fa;
        }
    </style>

    <div class="container py-5 mt-2">
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2200
                });
            </script>
        @endif

        <div class="page-header-elegant d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="marble-icon-box me-3 shadow-sm">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0 text-dark" style="border-left: 5px solid #000; padding-left: 15px;">Sampah
                        Pesanan</h2>
                    <p class="text-muted small mb-0">Hanya admin yang dapat memulihkan riwayat pesanan yang sudah
                        dihapus.</p>
                </div>
            </div>
            <a href="{{ route('admin.pesanan.baru') }}" class="btn btn-dark rounded-pill px-4 shadow-sm fw-bold">
                <i class="fas fa-arrow-left me-2 text-gold"></i> Kembali ke Daftar Pesanan
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="table-responsive">
                <table class="table table-elegant align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID Pesanan</th>
                            <th>Pembeli</th>
                            <th>Produk</th>
                            <th>Status Terakhir</th>
                            <th>Dihapus Pada</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanan as $item)
                            <tr>
                                <td class="ps-4 fw-bold text-primary small">ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="fw-semibold text-dark">{{ $item->user->name ?? '-' }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $item->nama_produk }}</div>
                                    <div class="small text-muted">{{ $item->ukuran }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                        {{ $item->status_label_pembeli }}
                                    </span>
                                </td>
                                <td class="small text-muted">{{ optional($item->deleted_at)->format('d M Y H:i') }}</td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold"
                                        onclick="restorePesanan({{ $item->id }}, '{{ $item->nama_produk }}')">
                                        <i class="fas fa-rotate-left me-1"></i> Pulihkan
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted small italic">Sampah pesanan masih kosong.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form id="form-restore" method="POST" style="display:none;">
        @csrf
        @method('PATCH')
    </form>

    <script>
        function restorePesanan(id, namaProduk) {
            Swal.fire({
                title: 'Pulihkan pesanan?',
                html: `Pesanan <b>${namaProduk}</b> akan dikembalikan ke daftar pesanan admin.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#2c3e50',
                confirmButtonText: 'Ya, pulihkan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('form-restore');
                    form.action = `/admin/pesanan/${id}/restore`;
                    form.submit();
                }
            });
        }
    </script>
@endsection
