@extends('layouts.app')

@section('content')
    {{-- Library SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- CUSTOM CSS UNTUK ESTETIKA MANAJEMEN PENGGUNA --}}
    <style>
        :root {
            --adira-gold: #C5A47E;
            --adira-dark: #2c3e50;
            --adira-gold-light: rgba(197, 164, 126, 0.1);
        }

        .text-gold {
            color: var(--adira-gold) !important;
        }

        .page-header-elegant {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 2.5rem;
            border-bottom: 4px solid var(--adira-gold);
        }

        .btn-gold {
            background-color: var(--adira-gold);
            border: none;
            color: white;
            font-weight: 600;
            transition: 0.3s;
            border-radius: 50px;
        }

        .btn-gold:hover {
            background-color: #b08d44;
            color: white;
            transform: translateY(-2px);
        }

        .user-card-container {
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            background: #fff;
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
            padding: 1.5rem 1rem;
            border-bottom: 1px solid #f8f9fa;
        }

        .user-avatar-elegant {
            width: 48px;
            height: 48px;
            background: var(--adira-gold-light);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--adira-gold);
            font-weight: 800;
            font-size: 1.2rem;
        }

        .status-badge-elegant {
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-active {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .badge-pembeli {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .badge-pengrajin {
            background-color: var(--adira-gold-light);
            color: var(--adira-gold);
        }

        .modal-content-elegant {
            border-radius: 25px;
            border: none;
            overflow: hidden;
        }

        .modal-header-elegant {
            background: var(--adira-dark);
            color: white;
            padding: 1.5rem 2rem;
        }

        .icon-box-header {
            width: 55px;
            height: 55px;
            background: var(--adira-gold-light);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--adira-gold);
            font-size: 1.5rem;
        }

        /* Custom Swal Style */
        .swal2-popup {
            border-radius: 25px !important;
            font-family: 'Inter', sans-serif !important;
        }

        .swal2-confirm {
            border-radius: 50px !important;
        }

        .swal2-cancel {
            border-radius: 50px !important;
        }

        /* Error Validation Style */
        .is-invalid {
            border-color: #dc3545 !important;
            background-color: #fff8f8 !important;
        }

        .invalid-feedback {
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>

    <div class="container py-5 mt-2 animate__animated animate__fadeIn">
        {{-- Notifikasi SweetAlert untuk Session Success --}}
        @if (session('success'))
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

        {{-- HEADER HALAMAN --}}
        <div class="page-header-elegant d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="icon-box-header me-3">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0 text-dark" style="border-left: 5px solid #000; padding-left: 15px;">Manajemen
                        Pengguna</h2>
                    <p class="text-muted mb-0 small">Kelola data pengguna dan akses operasional Adira Marmer</p>
                </div>
            </div>
            <button type="button" class="btn btn-gold px-4 py-2 shadow-sm fw-bold" data-bs-toggle="modal"
                data-bs-target="#modalTambahPengrajin">
                <i class="fas fa-user-plus me-2"></i> Tambah Pengguna
            </button>
        </div>

        {{-- TABEL DATA PENGGUNA --}}
        <div class="user-card-container overflow-hidden bg-white">
            <div class="table-responsive">
                <table class="table table-elegant hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Profil Pengguna</th>
                            <th>Alamat Email</th>
                            <th class="text-center">Nomor Telepon</th>
                            <th class="text-center">Role</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar-elegant me-3 shadow-sm">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-6">{{ $user->name }}</div>
                                            <small class="text-muted fw-semibold">ID:
                                                P-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-secondary fw-medium">{{ $user->email }}</td>
                                <td class="text-center text-dark fw-bold small">
                                    <i class="fab fa-whatsapp text-success me-1"></i> {{ $user->no_telp }}
                                </td>
                                <td class="text-center">
                                    @if ($user->role == 'pembeli')
                                        <span class="status-badge-elegant badge-pembeli shadow-sm">
                                            <i class="fas fa-shopping-cart me-1"></i> PEMBELI
                                        </span>
                                    @elseif($user->role == 'pengrajin')
                                        <span class="status-badge-elegant badge-pengrajin shadow-sm">
                                            <i class="fas fa-hammer me-1"></i> PENGRAJIN
                                        </span>
                                    @else
                                        <span class="status-badge-elegant badge-active shadow-sm">
                                            <i class="fas fa-user me-1"></i> {{ $user->role }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-1">
                                        {{-- Tombol Edit --}}
                                        <button class="btn btn-link text-primary p-2" title="Edit Data"
                                            onclick="editPengguna({{ json_encode($user) }})">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <form id="form-delete-{{ $user->id }}"
                                            action="{{ route('admin.pengguna.destroy', $user->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-link text-danger p-2"
                                                title="Hapus Pengguna"
                                                onclick="confirmDeleteUser({{ $user->id }}, '{{ $user->name }}')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-user-slash fa-4x mb-3 text-light"></i>
                                        <h5 class="text-muted">Belum ada data pengrajin terdaftar.</h5>
                                        <p class="small text-muted">Silakan klik tombol "Tambah Pengrajin" untuk memulai.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH/EDIT PENGRAJIN --}}
    <div class="modal fade" id="modalTambahPengrajin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-elegant shadow-lg">
                <div class="modal-header modal-header-elegant p-4">
                    <h5 class="modal-title fw-bold" id="modalTitle"><i class="fas fa-user-plus me-2 text-gold"></i> Tambah
                        Pengguna Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <form id="formPengrajin" action="{{ route('admin.pengguna.store') }}" method="POST">
                        @csrf
                        <div id="method-field"></div>

                        <div class="mb-3">
                            <label class="fw-bold small text-muted mb-2 text-uppercase">Nama Lengkap</label>
                            <input type="text" name="name" id="name"
                                class="form-control rounded-3 border-light shadow-sm py-2 @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Contoh: Hadi Pengrajin" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold small text-muted mb-2 text-uppercase">Alamat Email</label>
                            <input type="email" name="email" id="email"
                                class="form-control rounded-3 border-light shadow-sm py-2 @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="hadi@gmail.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold small text-muted mb-2 text-uppercase">Nomor Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-light shadow-sm text-gold"><i
                                        class="fas fa-phone"></i></span>
                                <input type="text" name="no_telp" id="no_telp"
                                    class="form-control border-light shadow-sm py-2 @error('no_telp') is-invalid @enderror"
                                    value="{{ old('no_telp') }}" placeholder="085xxxx" required>
                            </div>
                            @error('no_telp')
                                <div class="text-danger small fw-bold mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold small text-muted mb-2 text-uppercase">Role Pengguna</label>
                            <select name="role" id="role"
                                class="form-select rounded-3 border-light shadow-sm py-2 @error('role') is-invalid @enderror"
                                required>
                                <option value="">-- Pilih Role --</option>
                                <option value="pembeli" {{ old('role') == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                                <option value="pengrajin" {{ old('role') == 'pengrajin' ? 'selected' : '' }}>Pengrajin
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4" id="password-group">
                            <label class="fw-bold small text-muted mb-2 text-uppercase">Password Akses</label>
                            <input type="password" name="password" id="password"
                                class="form-control rounded-3 border-light shadow-sm py-2 @error('password') is-invalid @enderror"
                                placeholder="Minimal 8 karakter" required>
                            <small class="text-muted d-none" id="password-info">*Kosongkan jika tidak ingin mengubah
                                password.</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 pt-2">
                            <button type="submit" class="btn btn-gold py-3 fw-bold shadow">
                                <i class="fas fa-save me-2"></i> <span id="btn-text">Simpan Data Pengguna</span>
                            </button>
                            <button type="button" class="btn btn-white py-2 fw-bold text-muted border-0"
                                data-bs-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- FontAwesome & Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <script>
        // Membuka modal otomatis jika ada error validasi dari server
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                let modalElement = document.getElementById('modalTambahPengrajin');
                let modal = new bootstrap.Modal(modalElement);
                modal.show();
            @endif
        });

        // Fungsi Edit Pengrajin
        function editPengguna(user) {
            document.getElementById('modalTitle').innerHTML =
                '<i class="fas fa-user-edit me-2 text-gold"></i> Edit Data Pengguna';
            document.getElementById('btn-text').innerText = 'Perbarui Data Pengguna';

            // Sesuaikan URL action untuk Update
            document.getElementById('formPengrajin').action = `/admin/pengguna/${user.id}`;
            document.getElementById('method-field').innerHTML = '@method('PUT')';

            // Isi field dengan data yang ada
            document.getElementById('name').value = user.name;
            document.getElementById('email').value = user.email;
            document.getElementById('no_telp').value = user.no_telp;
            document.getElementById('role').value = user.role;

            // Penyesuaian Password saat edit agar opsional
            document.getElementById('password').required = false;
            document.getElementById('password').placeholder = "Ubah password (opsional)";
            document.getElementById('password-info').classList.remove('d-none');

            var editModal = new bootstrap.Modal(document.getElementById('modalTambahPengrajin'));
            editModal.show();
        }

        // Fungsi Konfirmasi Hapus SweetAlert
        function confirmDeleteUser(id, nama) {
            Swal.fire({
                title: '<h4 class="fw-bold mb-0" style="color: var(--adira-dark)">Hapus Akses Pengguna?</h4>',
                html: `Akun <b>${nama}</b> akan dihapus permanen dari sistem Adira Marmer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#2c3e50',
                confirmButtonText: 'Ya, Hapus Akun!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        allowOutsideClick: false,
                        showConfirmButton: false
                    });
                    document.getElementById('form-delete-' + id).submit();
                }
            });
        }

        // Reset form saat modal ditutup agar kembali ke mode "Tambah"
        document.getElementById('modalTambahPengrajin').addEventListener('hidden.bs.modal', function() {
            document.getElementById('modalTitle').innerHTML =
                '<i class="fas fa-user-plus me-2 text-gold"></i> Tambah Pengguna Baru';
            document.getElementById('btn-text').innerText = 'Simpan Data Pengguna';
            document.getElementById('formPengrajin').action = "{{ route('admin.pengguna.store') }}";
            document.getElementById('method-field').innerHTML = '';
            document.getElementById('formPengrajin').reset();

            // Kembalikan status password menjadi required
            document.getElementById('password').required = true;
            document.getElementById('password').placeholder = "Minimal 8 karakter";
            document.getElementById('password-info').classList.add('d-none');

            document.getElementById('role').value = '';

            // Bersihkan class error validasi
            var inputs = document.querySelectorAll('.is-invalid');
            inputs.forEach(function(input) {
                input.classList.remove('is-invalid');
            });
        });
    </script>
@endsection
