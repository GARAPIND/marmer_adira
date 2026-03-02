@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white p-4">
                    <h5 class="fw-bold mb-0" style="color: #C5A47E;">Master Data Bahan</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.bahan.simpan') }}" method="POST" class="mb-5">
                        @csrf
                        <label class="small fw-bold text-muted text-uppercase mb-2">Tambah Jenis Bahan Baru</label>
                        <div class="input-group">
                            <input type="text" name="nama_bahan" class="form-control border-2" placeholder="Contoh: Onyx Premium" required>
                            <button class="btn btn-dark px-4" type="submit">Tambah</button>
                        </div>
                    </form>

                    <table class="table table-hover">
                        <thead>
                            <tr class="text-muted small">
                                <th>NAMA BAHAN</th>
                                <th class="text-end">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bahans as $b)
                            <tr>
                                <td class="fw-bold">{{ $b->nama_bahan }}</td>
                                <td class="text-end">
                                    <form action="{{ route('admin.bahan.hapus', $b->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection