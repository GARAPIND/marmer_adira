@extends('layouts.app')

@section('content')
<style>
    :root { --adira-gold: #C5A47E; --adira-dark: #2c3e50; }
    .page-header-elegant { background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 2rem; }
    .marble-icon-box { width: 60px; height: 60px; background: rgba(197, 164, 126, 0.15); border-radius: 15px; display: flex; align-items: center; justify-content: center; color: var(--adira-gold); font-size: 1.8rem; }
    .table-elegant thead th { background-color: var(--adira-dark); color: white; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; padding: 1.25rem; border: none; }

    /* Timeline Status Styling */
    .timeline-container { position: relative; padding-left: 30px; border-left: 2px dashed #ddd; margin-left: 10px; }
    .timeline-item { position: relative; margin-bottom: 25px; }
    .timeline-item::before { content: ''; position: absolute; left: -39px; top: 0; width: 16px; height: 16px; border-radius: 50%; background: #ddd; border: 3px solid white; box-shadow: 0 0 0 2px #ddd; }
    
    /* Warna Status */
    .timeline-item.completed::before { background: #27ae60; box-shadow: 0 0 0 2px #27ae60; }
    .timeline-item.active::before { background: var(--adira-gold); box-shadow: 0 0 0 2px var(--adira-gold); }

    .detail-card { border: none; border-radius: 20px; background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.08); position: sticky; top: 100px; }
    .btn-gold { background-color: var(--adira-gold); color: white; border: none; font-weight: 700; border-radius: 50px; transition: 0.3s; }
    .btn-gold:hover { background-color: #b08d44; transform: translateY(-2px); }
</style>

<div class="container py-5 mt-2 animate__animated animate__fadeIn">
    <div class="page-header-elegant d-flex align-items-center shadow-sm">
        <div class="marble-icon-box me-3 shadow-sm">
            <i class="fas fa-sync-alt"></i>
        </div>
        <div>
            <h2 class="fw-bold mb-0 text-dark" style="border-left: 5px solid #000; padding-left: 15px;">Update Status Pengerjaan</h2>
            <p class="text-muted small mb-0">Kelola progres produksi setiap pesanan marmer</p>
        </div>
    </div>

    {{-- ALERT NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        {{-- SISI KIRI: TABEL PEMANTAUAN --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="table-responsive">
                    <table class="table table-elegant mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">ID Pesanan</th>
                                <th>Nama Produk</th>
                                <th>Ukuran</th>
                                <th>Status</th>
                                <th class="pe-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesananAktif as $item)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="fw-semibold">{{ $item->nama_produk }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $item->ukuran }}</span></td>
                                <td>
                                    <span class="badge {{ $item->status == 'Dikerjakan' ? 'bg-warning' : 'bg-primary' }} bg-opacity-10 {{ $item->status == 'Dikerjakan' ? 'text-warning' : 'text-primary' }} px-3 py-2 rounded-pill fw-bold">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="pe-4 text-center">
                                    {{-- PERBAIKAN: Nama route disesuaikan dengan web.php (pengrajin.update.status) --}}
                                    <button class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold btn-lihat-detail"
                                        data-id="ORD-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}"
                                        data-status="{{ $item->status }}"
                                        data-action="{{ route('pengrajin.update.status', $item->id) }}">
                                        Lihat Detail
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted italic">Tidak ada pesanan yang sedang dalam proses.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- SISI KANAN: PANEL TIMELINE --}}
        <div class="col-lg-4">
            <div class="card detail-card p-4 shadow-sm" id="detail-panel">
                <h5 class="fw-bold mb-4">Detail Pesanan <span id="display-id">-</span></h5>
                
                <h6 class="small fw-bold text-uppercase text-muted mb-4">Timeline Status:</h6>
                
                <div class="timeline-container mb-4">
                    <div id="step-diproses" class="timeline-item">
                        <p class="mb-0 fw-bold">Diproses</p>
                        <small class="text-muted">Pesanan dikonfirmasi oleh pengrajin</small>
                    </div>
                    <div id="step-dikerjakan" class="timeline-item">
                        <p class="mb-0 fw-bold">Dikerjakan</p>
                        <small class="text-muted">Dalam tahap pembentukan/pemotongan</small>
                    </div>
                    <div id="step-selesai" class="timeline-item">
                        <p class="mb-0 fw-bold">Selesai</p>
                        <small class="text-muted">Pesanan siap untuk dikirim</small>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <form id="form-dikerjakan" method="POST" action="">
                            @csrf
                            @method('PATCH') {{-- Menambahkan method PATCH agar sesuai dengan web.php --}}
                            <input type="hidden" name="status" value="Dikerjakan">
                            <button type="submit" class="btn btn-outline-dark w-100 py-2 fw-bold small rounded-pill">
                                Mulai Pengerjaan
                            </button>
                        </form>
                    </div>
                    <div class="col-6">
                        <form id="form-selesai" method="POST" action="">
                            @csrf
                            @method('PATCH') {{-- Menambahkan method PATCH agar sesuai dengan web.php --}}
                            <input type="hidden" name="status" value="Selesai">
                            <button type="submit" class="btn btn-gold w-100 py-2 fw-bold small rounded-pill shadow-sm">
                                Tandai Selesai
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-lihat-detail').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const status = this.getAttribute('data-status');
        const actionUrl = this.getAttribute('data-action');

        document.getElementById('display-id').innerText = id;
        document.getElementById('form-dikerjakan').action = actionUrl;
        document.getElementById('form-selesai').action = actionUrl;

        // Reset Timeline Classes
        const steps = ['step-diproses', 'step-dikerjakan', 'step-selesai'];
        steps.forEach(s => document.getElementById(s).classList.remove('completed', 'active'));

        // Logika Update Timeline Berdasarkan Status
        if (status === 'Diproses') {
            document.getElementById('step-diproses').classList.add('active');
        } else if (status === 'Dikerjakan') {
            document.getElementById('step-diproses').classList.add('completed');
            document.getElementById('step-dikerjakan').classList.add('active');
        } else if (status === 'Selesai') {
            document.getElementById('step-diproses').classList.add('completed');
            document.getElementById('step-dikerjakan').classList.add('completed');
            document.getElementById('step-selesai').classList.add('active');
        }
    });
});
</script>
@endsection