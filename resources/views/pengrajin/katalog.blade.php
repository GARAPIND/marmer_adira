@extends('layouts.app')

@section('content')
    {{-- Library SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --adira-gold: #C5A47E;
            --adira-dark: #2c3e50;
            --adira-cream: #FDFCF8;
            --adira-gold-light: rgba(197, 164, 126, 0.15);
        }

        body {
            background-color: var(--adira-cream);
            font-family: 'Inter', sans-serif;
        }

        .page-header-premium {
            background: white;
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
            margin-bottom: 2.5rem;
            border-left: 8px solid var(--adira-gold);
        }

        .btn-add-premium {
            background: var(--adira-dark);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 700;
            transition: 0.4s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-add-premium:hover {
            background: #000;
            color: var(--adira-gold);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-table-premium {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            background: white;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.06);
        }

        .table-elegant thead th {
            background: var(--adira-dark);
            color: white;
            padding: 1.5rem;
            border: none;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 2px;
        }

        .table-elegant tbody td {
            vertical-align: middle;
            padding: 1.5rem;
            border-color: #f8f9fa;
        }

        .img-container {
            width: 90px;
            height: 90px;
            border-radius: 15px;
            overflow: hidden;
            border: 2px solid #eee;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-content-premium {
            border-radius: 24px;
            border: none;
            overflow: hidden;
        }

        .form-control-premium {
            border: 2px solid #f1ece1;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 0.85rem;
            transition: 0.3s;
            background: #fafafa;
        }

        .form-control-premium:focus {
            border-color: var(--adira-gold);
            background: white;
            box-shadow: none;
        }

        .size-card {
            background: #fff;
            border: 1.5px solid #eee;
            border-radius: 14px;
            padding: 14px;
        }

        .size-card .size-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 3px 10px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 10px;
        }

        .size-label-kecil {
            background: #fff3cd;
            color: #856404;
        }

        .size-label-sedang {
            background: #d1ecf1;
            color: #0c5460;
        }

        .size-label-besar {
            background: #d4edda;
            color: #155724;
        }

        .modal-body {
            max-height: 78vh;
            overflow-y: auto;
        }
    </style>

    <div class="container py-5 animate__animated animate__fadeIn">
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

        @if ($errors->any())
            <div class="alert alert-danger rounded-4 shadow-sm mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="page-header-premium d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-dark" style="font-family: 'Playfair Display', serif;">Katalog Produk Premium</h2>
                <p class="text-muted small mb-0">Kelola koleksi dan konfigurasi harga kustom Anda</p>
            </div>
            <button class="btn-add-premium shadow-sm" data-bs-toggle="modal" data-bs-target="#modalProduk">
                <i class="fas fa-plus-circle me-2"></i> Tambah Koleksi
            </button>
        </div>

        <div class="card card-table-premium">
            <div class="table-responsive">
                <table class="table table-elegant mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Visual</th>
                            <th>Identitas Produk</th>
                            <th class="text-center">Ukuran Kecil</th>
                            <th class="text-center">Ukuran Sedang</th>
                            <th class="text-center">Ukuran Besar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produk as $p)
                            <tr>
                                <td class="ps-4">
                                    <div class="img-container">
                                        <img src="{{ $p->gambar ? asset('storage/' . $p->gambar) : 'https://placehold.co/200x200/C5A47E/white?text=Marmer' }}"
                                            alt="Produk">
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $p->nama_produk }}</div>
                                    <small class="text-muted">{{ Str::limit($p->deskripsi, 40) }}</small>
                                </td>
                                <td class="text-center">
                                    @if ($p->ukuran_kecil)
                                        <div><strong>Ukuran :</strong> {{ $p->ukuran_kecil }}</div>
                                        <div class="text-success"><strong>Harga :</strong> Rp
                                            {{ number_format($p->harga_kecil, 0, ',', '.') }}</div>
                                        <div><strong>Berat :</strong> {{ $p->berat_kecil ?? '-' }} KG</div>
                                        <div class="text-muted"><strong>Bahan :</strong>
                                            {{ $p->bahan_kecil->nama_bahan ?? '-' }}</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($p->ukuran_sedang)
                                        <div><strong>Ukuran :</strong> {{ $p->ukuran_sedang }}</div>
                                        <div class="text-success"><strong>Harga :</strong> Rp
                                            {{ number_format($p->harga_sedang, 0, ',', '.') }}</div>
                                        <div><strong>Berat :</strong> {{ $p->berat_sedang ?? '-' }} KG</div>
                                        <div class="text-muted"><strong>Bahan :</strong>
                                            {{ $p->bahan_sedang->nama_bahan ?? '-' }}</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($p->ukuran_besar)
                                        <div><strong>Ukuran :</strong> {{ $p->ukuran_besar }}</div>
                                        <div class="text-success"><strong>Harga :</strong> Rp
                                            {{ number_format($p->harga_besar, 0, ',', '.') }}</div>
                                        <div><strong>Berat :</strong> {{ $p->berat_besar ?? '-' }} KG</div>
                                        <div class="text-muted"><strong>Bahan :</strong>
                                            {{ $p->bahan_besar->nama_bahan ?? '-' }}</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-outline-dark rounded-pill px-4"
                                            onclick="editProduk({{ json_encode($p) }})">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        <form id="form-delete-{{ $p->id }}"
                                            action="{{ route('pengrajin.katalog.hapus', $p->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-4"
                                                onclick="confirmDeleteProduk({{ $p->id }}, '{{ $p->nama_produk }}')">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    Katalog Anda masih kosong.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalProduk" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content modal-content-premium shadow-lg">

                <div class="modal-header bg-dark text-white px-4 py-3 border-0">
                    <h5 class="modal-title fw-bold" id="modalTitle">Tambah Koleksi Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="formProduk" method="POST" action="{{ route('pengrajin.katalog.simpan') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="modal-body p-4 bg-white">
                        <div class="row g-4">

                            <div class="col-md-4 d-flex flex-column gap-3">

                                <div>
                                    <label class="small fw-bold text-uppercase text-muted mb-1">Nama Produk</label>
                                    <input type="text" name="nama_produk" id="nama_produk"
                                        value="{{ old('nama_produk') }}"
                                        class="form-control form-control-premium @error('nama_produk') is-invalid @enderror"
                                        placeholder="Nama koleksi…" required>
                                    @error('nama_produk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="flex-grow-1">
                                    <label class="small fw-bold text-uppercase text-muted mb-1">Deskripsi</label>
                                    <textarea name="deskripsi" id="deskripsi"
                                        class="form-control form-control-premium @error('deskripsi') is-invalid @enderror"
                                        style="min-height:140px;resize:none" placeholder="Deskripsi singkat produk…">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="small fw-bold text-uppercase text-muted mb-1">Dokumentasi Visual</label>
                                    <input type="file" name="gambar" id="gambar"
                                        class="form-control form-control-premium @error('gambar') is-invalid @enderror">
                                    @error('gambar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Format: JPG/PNG · Maks 2 MB</small>
                                </div>

                            </div>

                            <div class="col-md-8">
                                <label class="small fw-bold text-uppercase text-muted mb-3 d-block">
                                    Konfigurasi Ukuran &amp; Harga
                                </label>

                                <div class="row g-3">

                                    <div class="col-12 col-lg-4">
                                        <div class="size-card h-100">
                                            <span class="size-label size-label-kecil">
                                                Kecil <span class="text-danger">*</span>
                                            </span>

                                            <div class="mb-2">
                                                <label style="font-size:.75rem" class="text-muted">Label Ukuran</label>
                                                <input type="text" name="ukuran_kecil" id="ukuran_kecil"
                                                    value="{{ old('ukuran_kecil') }}"
                                                    class="form-control form-control-premium @error('ukuran_kecil') is-invalid @enderror"
                                                    placeholder="cth: 10x10 cm" required>
                                                @error('ukuran_kecil')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row g-2 mb-2">
                                                <div class="col-6">
                                                    <label style="font-size:.75rem" class="text-muted">Berat (kg)</label>
                                                    <input type="number" name="berat_kecil" id="berat_kecil"
                                                        value="{{ old('berat_kecil') }}"
                                                        class="form-control form-control-premium @error('berat_kecil') is-invalid @enderror"
                                                        placeholder="kilogram" required>
                                                    @error('berat_kecil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-6">
                                                    <label style="font-size:.75rem" class="text-muted">Harga (Rp)</label>
                                                    <input type="number" name="harga_kecil" id="harga_kecil"
                                                        value="{{ old('harga_kecil') }}"
                                                        class="form-control form-control-premium @error('harga_kecil') is-invalid @enderror"
                                                        placeholder="Rp" required>
                                                    @error('harga_kecil')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div>
                                                <label style="font-size:.75rem" class="text-muted">Bahan</label>
                                                <select name="bahan_kecil_id" id="bahan_kecil_id"
                                                    class="form-control form-control-premium @error('bahan_kecil_id') is-invalid @enderror"
                                                    required>
                                                    <option value="">-- Pilih --</option>
                                                    @foreach ($bahans as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ old('bahan_kecil_id') == $item->id ? 'selected' : '' }}>
                                                            {{ $item->nama_bahan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('bahan_kecil_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-4">
                                        <div class="size-card h-100">
                                            <span class="size-label size-label-sedang">
                                                Sedang <small class="fw-normal opacity-75">(opsional)</small>
                                            </span>

                                            <div class="mb-2">
                                                <label style="font-size:.75rem" class="text-muted">Label Ukuran</label>
                                                <input type="text" name="ukuran_sedang" id="ukuran_sedang"
                                                    value="{{ old('ukuran_sedang') }}"
                                                    class="form-control form-control-premium @error('ukuran_sedang') is-invalid @enderror"
                                                    placeholder="cth: 20x20 cm">
                                                @error('ukuran_sedang')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row g-2 mb-2">
                                                <div class="col-6">
                                                    <label style="font-size:.75rem" class="text-muted">Berat (kg)</label>
                                                    <input type="number" name="berat_sedang" id="berat_sedang"
                                                        value="{{ old('berat_sedang') }}"
                                                        class="form-control form-control-premium @error('berat_sedang') is-invalid @enderror"
                                                        placeholder="kilogram">
                                                    @error('berat_sedang')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-6">
                                                    <label style="font-size:.75rem" class="text-muted">Harga (Rp)</label>
                                                    <input type="number" name="harga_sedang" id="harga_sedang"
                                                        value="{{ old('harga_sedang') }}"
                                                        class="form-control form-control-premium @error('harga_sedang') is-invalid @enderror"
                                                        placeholder="Rp">
                                                    @error('harga_sedang')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div>
                                                <label style="font-size:.75rem" class="text-muted">Bahan</label>
                                                <select name="bahan_sedang_id" id="bahan_sedang_id"
                                                    class="form-control form-control-premium @error('bahan_sedang_id') is-invalid @enderror">
                                                    <option value="">-- Pilih --</option>
                                                    @foreach ($bahans as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ old('bahan_sedang_id') == $item->id ? 'selected' : '' }}>
                                                            {{ $item->nama_bahan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('bahan_sedang_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-4">
                                        <div class="size-card h-100">
                                            <span class="size-label size-label-besar">
                                                Besar <small class="fw-normal opacity-75">(opsional)</small>
                                            </span>

                                            <div class="mb-2">
                                                <label style="font-size:.75rem" class="text-muted">Label Ukuran</label>
                                                <input type="text" name="ukuran_besar" id="ukuran_besar"
                                                    value="{{ old('ukuran_besar') }}"
                                                    class="form-control form-control-premium @error('ukuran_besar') is-invalid @enderror"
                                                    placeholder="cth: 30x30 cm">
                                                @error('ukuran_besar')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row g-2 mb-2">
                                                <div class="col-6">
                                                    <label style="font-size:.75rem" class="text-muted">Berat (kg)</label>
                                                    <input type="number" name="berat_besar" id="berat_besar"
                                                        value="{{ old('berat_besar') }}"
                                                        class="form-control form-control-premium @error('berat_besar') is-invalid @enderror"
                                                        placeholder="kilogram">
                                                    @error('berat_besar')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-6">
                                                    <label style="font-size:.75rem" class="text-muted">Harga (Rp)</label>
                                                    <input type="number" name="harga_besar" id="harga_besar"
                                                        value="{{ old('harga_besar') }}"
                                                        class="form-control form-control-premium @error('harga_besar') is-invalid @enderror"
                                                        placeholder="Rp">
                                                    @error('harga_besar')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div>
                                                <label style="font-size:.75rem" class="text-muted">Bahan</label>
                                                <select name="bahan_besar_id" id="bahan_besar_id"
                                                    class="form-control form-control-premium @error('bahan_besar_id') is-invalid @enderror">
                                                    <option value="">-- Pilih --</option>
                                                    @foreach ($bahans as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ old('bahan_besar_id') == $item->id ? 'selected' : '' }}>
                                                            {{ $item->nama_bahan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('bahan_besar_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 px-4 py-3">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold text-muted"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-dark rounded-pill px-5 shadow-sm fw-bold">
                            Simpan ke Katalog
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                new bootstrap.Modal(document.getElementById('modalProduk')).show();
            });
        </script>
    @endif

    <script>
        function editProduk(data) {
            document.getElementById('modalTitle').innerText = 'Edit Koleksi Produk';

            document.getElementById('formProduk').action = `/pengrajin/katalog/update/${data.id}`;

            document.getElementById('gambar').removeAttribute('required');

            document.getElementById('nama_produk').value = data.nama_produk ?? '';
            document.getElementById('deskripsi').value = data.deskripsi ?? '';

            document.getElementById('ukuran_kecil').value = data.ukuran_kecil ?? '';
            document.getElementById('harga_kecil').value = data.harga_kecil ?? '';
            document.getElementById('berat_kecil').value = data.berat_kecil ?? '';
            document.getElementById('bahan_kecil_id').value = data.bahan_kecil_id ?? '';

            document.getElementById('ukuran_sedang').value = data.ukuran_sedang ?? '';
            document.getElementById('harga_sedang').value = data.harga_sedang ?? '';
            document.getElementById('berat_sedang').value = data.berat_sedang ?? '';
            document.getElementById('bahan_sedang_id').value = data.bahan_sedang_id ?? '';

            document.getElementById('ukuran_besar').value = data.ukuran_besar ?? '';
            document.getElementById('harga_besar').value = data.harga_besar ?? '';
            document.getElementById('berat_besar').value = data.berat_besar ?? '';
            document.getElementById('bahan_besar_id').value = data.bahan_besar_id ?? '';

            new bootstrap.Modal(document.getElementById('modalProduk')).show();
        }

        function confirmDeleteProduk(id, nama) {
            Swal.fire({
                title: '<h4 class="fw-bold mb-0">Hapus Produk?</h4>',
                html: `Apakah Anda yakin ingin menghapus <b>${nama}</b>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-delete-' + id).submit();
                }
            });
        }

        document.getElementById('modalProduk').addEventListener('hidden.bs.modal', function() {
            document.getElementById('modalTitle').innerText = 'Tambah Koleksi Baru';
            document.getElementById('formProduk').action = "{{ route('pengrajin.katalog.simpan') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('gambar').setAttribute('required', 'required');
            document.getElementById('formProduk').reset();
        });
    </script>
@endsection
