@extends('layouts.app')

@section('content')
{{-- Library SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    :root { 
        --adira-gold: #C5A47E; 
        --adira-dark: #2c3e50; 
        --adira-cream: #FDFCF8; 
        --adira-gold-light: rgba(197, 164, 126, 0.15); 
    }
    
    body { background-color: var(--adira-cream); font-family: 'Inter', sans-serif; }

    .page-header-premium { 
        background: white; padding: 2.5rem; border-radius: 24px; 
        box-shadow: 0 10px 40px rgba(0,0,0,0.03); margin-bottom: 2.5rem; 
        border-left: 8px solid var(--adira-gold); 
    }

    .btn-add-premium { 
        background: var(--adira-dark); color: white; border: none; 
        border-radius: 50px; padding: 12px 30px; font-weight: 700; 
        transition: 0.4s; text-transform: uppercase; letter-spacing: 1px;
    }
    .btn-add-premium:hover { 
        background: #000; color: var(--adira-gold); 
        transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .card-table-premium { border: none; border-radius: 24px; overflow: hidden; background: white; box-shadow: 0 20px 50px rgba(0,0,0,0.06); }
    
    .table-elegant thead th { 
        background: var(--adira-dark); color: white; padding: 1.5rem; 
        border: none; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px; 
    }

    .table-elegant tbody td { vertical-align: middle; padding: 1.5rem; border-color: #f8f9fa; }

    .img-container { 
        width: 90px; height: 90px; border-radius: 15px; 
        overflow: hidden; border: 2px solid #eee; 
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .img-container img { width: 100%; height: 100%; object-fit: cover; }

    .modal-content-premium { border-radius: 30px; border: none; overflow: hidden; }
    .form-control-premium { 
        border: 2px solid #f1ece1; border-radius: 12px; padding: 12px; 
        transition: 0.3s; background: #fafafa;
    }
    .form-control-premium:focus { 
        border-color: var(--adira-gold); background: white; box-shadow: none; 
    }
</style>

<div class="container py-5 animate__animated animate__fadeIn">
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}",
                showConfirmButton: false, timer: 2500, timerProgressBar: true
            });
        </script>
    @endif

    {{-- BARU: Tampilkan Error jika ada --}}
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
                        <th>Bahan</th>
                        <th>Konfigurasi Ukuran & Harga</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produk as $p)
                    <tr>
                        <td class="ps-4">
                            <div class="img-container">
                                <img src="{{ $p->gambar ? asset('storage/'.$p->gambar) : 'https://placehold.co/200x200/C5A47E/white?text=Marmer' }}" alt="Produk">
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $p->nama_produk }}</div>
                            <small class="text-muted">{{ Str::limit($p->deskripsi, 40) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-opacity-10 text-gold fw-bold" style="background: var(--adira-gold-light); color: var(--adira-gold);">
                                {{ $p->bahan }}
                            </span>
                        </td>
                        <td class="text-success fw-bold">
                            @if($p->ukuran_kecil) <div class="small">{{ $p->ukuran_kecil }}: Rp {{ number_format($p->harga_kecil, 0, ',', '.') }}</div> @endif
                            @if($p->ukuran_sedang) <div class="small">{{ $p->ukuran_sedang }}: Rp {{ number_format($p->harga_sedang, 0, ',', '.') }}</div> @endif
                            @if($p->ukuran_besar) <div class="small">{{ $p->ukuran_besar }}: Rp {{ number_format($p->harga_besar, 0, ',', '.') }}</div> @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-outline-dark rounded-pill px-4" onclick="editProduk({{ json_encode($p) }})">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                                <form id="form-delete-{{ $p->id }}" action="{{ route('pengrajin.katalog.hapus', $p->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-4" onclick="confirmDeleteProduk({{ $p->id }}, '{{ $p->nama_produk }}')">
                                        <i class="fas fa-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted italic">Katalog Anda masih kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH/EDIT PRODUK --}}
<div class="modal fade" id="modalProduk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-premium shadow-lg">
            <div class="modal-header bg-dark text-white p-4 border-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Tambah Koleksi Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formProduk" method="POST" action="{{ route('pengrajin.katalog.simpan') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div class="row g-4">
                        <div class="col-md-7 border-end">
                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase text-muted">Nama Produk Marmer</label>
                                <input type="text" name="nama_produk" id="nama_produk" class="form-control form-control-premium" placeholder="Contoh: Wastafel Onyx" required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase text-muted">Deskripsi & Detail Karya</label>
                                <textarea name="deskripsi" id="deskripsi" class="form-control form-control-premium" rows="9" placeholder="Ceritakan keunikan produk ini..."></textarea>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="p-3 rounded-4 bg-light border mb-4">
                                <label class="small fw-bold text-dark mb-3 d-block text-uppercase"><i class="fas fa-tags me-2"></i>Konfigurasi Ukuran & Harga</label>
                                
                                <div class="mb-3">
                                    <input type="text" name="ukuran_kecil" id="ukuran_kecil" class="form-control form-control-premium mb-1" placeholder="Label Ukuran (ex: D 10cm)" required>
                                    <input type="number" name="harga_kecil" id="harga_kecil" class="form-control form-control-premium" placeholder="Harga Rp" required>
                                </div>

                                <div class="mb-3">
                                    <input type="text" name="ukuran_sedang" id="ukuran_sedang" class="form-control form-control-premium mb-1" placeholder="Label Ukuran (ex: D 20cm)">
                                    <input type="number" name="harga_sedang" id="harga_sedang" class="form-control form-control-premium" placeholder="Harga Rp">
                                </div>

                                <div class="mb-0">
                                    <input type="text" name="ukuran_besar" id="ukuran_besar" class="form-control form-control-premium mb-1" placeholder="Label Ukuran (ex: D 30cm)">
                                    <input type="number" name="harga_besar" id="harga_besar" class="form-control form-control-premium" placeholder="Harga Rp">
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="small fw-bold text-uppercase text-muted">Dokumentasi Visual</label>
                                <input type="file" name="gambar" class="form-control form-control-premium">
                                <small class="text-muted italic">Format: JPG, PNG (Maks 2MB)</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold text-muted" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-5 shadow-sm fw-bold">Simpan ke Katalog</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editProduk(data) {
        document.getElementById('modalTitle').innerText = 'Edit Koleksi Produk';
        document.getElementById('formProduk').action = `/pengrajin/katalog/update/${data.id}`;
        document.getElementById('nama_produk').value = data.nama_produk;
        document.getElementById('deskripsi').value = data.deskripsi;
        
        document.getElementById('ukuran_kecil').value = data.ukuran_kecil || '';
        document.getElementById('harga_kecil').value = data.harga_kecil || '';
        document.getElementById('ukuran_sedang').value = data.ukuran_sedang || '';
        document.getElementById('harga_sedang').value = data.harga_sedang || '';
        document.getElementById('ukuran_besar').value = data.ukuran_besar || '';
        document.getElementById('harga_besar').value = data.harga_besar || '';
        
        new bootstrap.Modal(document.getElementById('modalProduk')).show();
    }

    function confirmDeleteProduk(id, nama) {
        Swal.fire({
            title: '<h4 class="fw-bold mb-0">Hapus Produk?</h4>',
            html: `Apakah Anda yakin ingin menghapus <b>${nama}</b>?`,
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal', reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-' + id).submit();
            }
        });
    }

    document.getElementById('modalProduk').addEventListener('hidden.bs.modal', function () {
        document.getElementById('modalTitle').innerText = 'Tambah Koleksi Baru';
        document.getElementById('formProduk').reset();
    });
</script>
@endsection