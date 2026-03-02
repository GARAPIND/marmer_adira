@extends('layouts.app')

@section('content')
{{-- Library SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- CUSTOM CSS UNTUK ESTETIKA MANAJEMEN BAHAN --}}
<style>
    :root {
        --adira-gold: #C5A47E;
        --adira-dark: #2c3e50;
    }
    
    .text-gold { color: var(--adira-gold) !important; }
    
    .page-header-elegant {
        background: white;
        padding: 2rem;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        margin-bottom: 2rem;
        border-left: 8px solid var(--adira-gold);
    }

    .btn-gold {
        background-color: var(--adira-gold);
        border: none;
        color: white;
        font-weight: 700;
        transition: 0.3s;
    }
    .btn-gold:hover { background-color: #b08d44; color: white; transform: translateY(-2px); }

    .product-card-container {
        border: none;
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.06);
        background: #fff;
    }

    .table-elegant thead th {
        background-color: var(--adira-dark);
        color: white;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 2px;
        padding: 1.5rem;
        border: none;
    }

    .table-elegant tbody td {
        vertical-align: middle;
        padding: 1.5rem 1rem;
        border-bottom: 1px solid #f8f9fa;
    }

    .marble-icon-box {
        width: 50px;
        height: 50px;
        background: rgba(197, 164, 126, 0.1);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--adira-gold);
        font-size: 1.3rem;
        border: 1px solid rgba(197, 164, 126, 0.2);
    }

    .modal-content-elegant {
        border-radius: 30px;
        border: none;
        overflow: hidden;
    }

    .modal-header-elegant {
        background: var(--adira-dark);
        color: white;
        border: none;
        padding: 1.5rem 2rem;
    }

    .form-control-premium {
        border: 2px solid #f1ece1;
        border-radius: 15px;
        padding: 12px 18px;
        transition: 0.3s;
        background-color: #fcfcfc;
    }

    .form-control-premium:focus {
        border-color: var(--adira-gold);
        background-color: #fff;
        box-shadow: none;
    }

    /* Custom Swal Style */
    .swal2-popup { border-radius: 25px !important; font-family: 'Inter', sans-serif !important; }
    .swal2-confirm { border-radius: 50px !important; }
    .swal2-cancel { border-radius: 50px !important; }
</style>

<div class="container py-5 mt-2 animate__animated animate__fadeIn">
    {{-- Notifikasi SweetAlert untuk Session Success --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        </script>
    @endif

    {{-- Notifikasi SweetAlert untuk Session Error --}}
    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonColor: '#2c3e50'
            });
        </script>
    @endif

    {{-- HEADER HALAMAN --}}
    <div class="page-header-elegant d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="marble-icon-box me-4" style="width: 70px; height: 70px; font-size: 2rem;">
                <i class="fas fa-gem"></i>
            </div>
            <div>
                <h2 class="fw-bold mb-0 text-dark" style="font-family: 'Playfair Display', serif;">Manajemen Master Bahan</h2>
                <p class="text-muted small mb-0">Kelola daftar material dasar marmer untuk standarisasi katalog</p>
            </div>
        </div>
        <button type="button" class="btn btn-gold px-4 py-2 rounded-pill shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambahBahan">
            <i class="fas fa-plus-circle me-2"></i> Tambah Data Master
        </button>
    </div>

    {{-- TABEL BAHAN --}}
    <div class="product-card-container overflow-hidden">
        <div class="table-responsive">
            <table class="table table-elegant align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-5 text-center" style="width: 100px;">Icon</th>
                        <th>Nama Bahan / Material</th>
                        <th class="text-center pe-5">Kelola</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bahans as $b)
                    <tr>
                        <td class="text-center ps-5">
                            <div class="marble-icon-box mx-auto">
                                <i class="fas fa-layer-group"></i>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark fs-5">{{ $b->nama_bahan }}</div>
                            <small class="text-muted italic">Material Utama Katalog Premium</small>
                        </td>
                        <td class="text-center pe-5">
                            <div class="d-flex justify-content-center gap-3">
                                {{-- Tombol Edit --}}
                                <button class="btn btn-link text-primary p-0 fs-5" title="Edit" onclick="editBahan({{ json_encode($b) }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                {{-- Tombol Hapus dengan SweetAlert --}}
                                <form id="form-delete-{{ $b->id }}" action="{{ route('admin.bahan.hapus', $b->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-link text-danger p-0 fs-5" title="Hapus" onclick="confirmDelete({{ $b->id }}, '{{ $b->nama_bahan }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted">
                            <i class="fas fa-info-circle me-2"></i> Belum ada data bahan master yang tersedia.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL FORM MASTER BAHAN --}}
<div class="modal fade" id="modalTambahBahan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-elegant shadow-lg">
            <div class="modal-header modal-header-elegant">
                <h5 class="modal-title fw-bold" id="modalTitle"><i class="fas fa-edit me-2 text-gold"></i> Form Master Bahan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-5 bg-white">
                <form id="formBahan" action="{{ route('admin.bahan.simpan') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="fw-bold small text-muted mb-2 text-uppercase letter-spacing-1">Nama Bahan / Material</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-4"><i class="fas fa-gem text-gold"></i></span>
                            <input type="text" name="nama_bahan" id="nama_bahan" class="form-control form-control-premium border-start-0 rounded-end-4" placeholder="Contoh: Marmer Carrara" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-5">
                        <button type="submit" class="btn btn-gold py-3 fw-bold rounded-pill shadow">
                            <i class="fas fa-save me-2"></i> Simpan Data Master
                        </button>
                        <button type="button" class="btn btn-light py-2 fw-bold text-muted border-0" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- FontAwesome & Animate.css --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<script>
    // Fungsi Edit Bahan
    function editBahan(data) {
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2 text-gold"></i> Edit Master Bahan';
        document.getElementById('formBahan').action = `/admin/bahan/update/${data.id}`;
        document.getElementById('nama_bahan').value = data.nama_bahan;
        new bootstrap.Modal(document.getElementById('modalTambahBahan')).show();
    }

    // Fungsi Konfirmasi Hapus SweetAlert
    function confirmDelete(id, nama) {
        Swal.fire({
            title: '<h4 class="fw-bold mb-0" style="color: var(--adira-dark)">Hapus Master Bahan?</h4>',
            html: `Apakah Anda yakin ingin menghapus bahan <b>${nama}</b>?<br><small class="text-danger">Data yang dihapus tidak dapat dikembalikan.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#C5A47E',
            cancelButtonColor: '#2c3e50',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading saat proses hapus
                Swal.fire({
                    title: 'Menghapus...',
                    didOpen: () => { Swal.showLoading() },
                    allowOutsideClick: false,
                    showConfirmButton: false
                });
                // Submit form
                document.getElementById('form-delete-' + id).submit();
            }
        });
    }

    // Reset form saat modal ditutup
    document.getElementById('modalTambahBahan').addEventListener('hidden.bs.modal', function () {
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2 text-gold"></i> Form Master Bahan';
        document.getElementById('formBahan').action = "{{ route('admin.bahan.simpan') }}";
        document.getElementById('formBahan').reset();
    });
</script>
@endsection