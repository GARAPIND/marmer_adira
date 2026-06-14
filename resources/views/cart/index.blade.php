@extends('layouts.app')

@section('content')
    <style>
        :root {
            --adira-gold: #C5A47E;
            --adira-dark: #2c3e50;
        }

        .cart-shell {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 18px 45px rgba(44, 62, 80, 0.08);
            overflow: hidden;
        }

        .cart-header {
            background: linear-gradient(135deg, #2c3e50, #1d2833);
            color: #fff;
            padding: 2rem;
        }

        .cart-item {
            border: 1px solid rgba(44, 62, 80, 0.08);
            border-radius: 18px;
            padding: 1rem;
            background: #fff;
        }

        .summary-card {
            background: #fcfbf8;
            border: 1px solid rgba(197, 164, 126, 0.25);
            border-radius: 20px;
            padding: 1.5rem;
            position: sticky;
            top: 100px;
        }

        .alamat-card-select {
            border: 1.5px solid #ececec;
            border-radius: 14px;
            padding: 1rem;
            cursor: pointer;
        }

        .alamat-card-select.selected {
            border-color: var(--adira-gold);
            background: #fffdf9;
        }

        .courier-btn {
            border: 1px solid #ddd;
            border-radius: 999px;
            padding: 0.55rem 1rem;
            cursor: pointer;
            font-weight: 700;
        }

        .courier-btn.selected {
            background: var(--adira-gold);
            border-color: var(--adira-gold);
            color: #fff;
        }
    </style>

    @php
        $subtotal = (int) $cartItems->sum('subtotal');
        $totalQty = (int) $cartItems->sum('jumlah');
        $totalBerat = (float) $cartItems->sum('total_berat');
    @endphp

    <div class="container py-5 mt-2">
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4">{{ session('error') }}</div>
        @endif

        <div class="cart-shell">
            <div class="cart-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1">Keranjang Belanja</h2>
                    <p class="mb-0 text-white-50">Gabungkan beberapa barang ke dalam satu pesanan.</p>
                </div>
                <a href="{{ route('produk.index') }}" class="btn btn-light rounded-pill px-4 fw-bold">Tambah Barang</a>
            </div>

            <div class="p-4 p-lg-5">
                <div class="row g-4">
                    <div class="col-lg-7">
                        @forelse ($cartItems as $item)
                            <div class="cart-item mb-3">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $item->nama_produk }}</div>
                                        <div class="small text-muted">{{ $item->ukuran }} | {{ $item->jenis_marmer }}</div>
                                        <div class="small text-muted">Qty {{ $item->jumlah }} | Berat {{ number_format($item->total_berat, 2) }} kg</div>
                                        @if ($item->catatan_khusus)
                                            <div class="small mt-2 fst-italic text-secondary">{{ $item->catatan_khusus }}</div>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="mt-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-light border rounded-4">
                                Keranjang masih kosong. Tambahkan barang dari katalog terlebih dahulu.
                            </div>
                        @endforelse
                    </div>

                    <div class="col-lg-5">
                        <div class="summary-card">
                            <h5 class="fw-bold mb-3">Checkout Pesanan</h5>
                            <div class="small text-muted mb-3">Total item {{ $cartItems->count() }} | Total qty {{ $totalQty }} | Berat {{ number_format($totalBerat, 2) }} kg</div>

                            <div class="d-flex justify-content-between mb-3">
                                <span>Subtotal Produk</span>
                                <span class="fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4">
                                <span>Ongkir</span>
                                <span class="text-muted">Dihitung admin</span>
                            </div>

                            @if ($cartItems->isNotEmpty())
                                <form action="{{ route('pesanan.store') }}" method="POST" id="cartCheckoutForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Metode Pengambilan</label>
                                        <select name="metode_pengambilan" id="metode_pengambilan" class="form-select" onchange="toggleCheckoutMetode()" required>
                                            <option value="dirumah">Ambil di Tempat</option>
                                            <option value="dikirim">Dikirim</option>
                                        </select>
                                    </div>

                                    <div id="section_pengiriman" style="display:none;">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Jenis Pengiriman</label>
                                            <div class="alert alert-light border rounded-4 mb-0">
                                                <div class="fw-bold text-dark">Cargo</div>
                                                <div class="small text-muted">Pengiriman pesanan saat ini hanya tersedia melalui cargo.</div>
                                            </div>
                                            <input type="hidden" name="jenis_pengiriman" id="jenis_pengiriman_hidden">
                                        </div>

                                        <div id="section_cargo" style="display:block;">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Pilih Alamat</label>
                                                @forelse ($listAlamat as $alamat)
                                                    <div class="alamat-card-select mb-2 {{ $alamat->is_utama ? 'selected' : '' }}"
                                                        data-id="{{ $alamat->id }}"
                                                        onclick="pilihAlamat(this)">
                                                        <div class="fw-bold">{{ $alamat->label_alamat ?? 'Alamat' }}</div>
                                                        <div class="small text-muted">{{ $alamat->alamat_lengkap }}, {{ $alamat->kecamatan_nama }}, {{ $alamat->kota_nama }}</div>
                                                    </div>
                                                @empty
                                                    <div class="small text-danger">Belum ada alamat. Tambahkan di menu alamat terlebih dahulu.</div>
                                                @endforelse
                                                <input type="hidden" name="alamat_pembeli_id" id="alamat_pembeli_id_hidden" value="{{ optional($listAlamat->firstWhere('is_utama', true))->id }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Ekspedisi</label>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach (['jne', 'tiki', 'pos', 'jnt', 'sicepat'] as $kurir)
                                                        <div class="courier-btn" data-kurir="{{ $kurir }}" onclick="pilihKurir('{{ $kurir }}')">{{ strtoupper($kurir) }}</div>
                                                    @endforeach
                                                </div>
                                                <input type="hidden" name="courier" id="courier_hidden">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-dark w-100 rounded-pill py-3 fw-bold">Ajukan Pesanan</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedKurir = null;

        function toggleCheckoutMetode() {
            const metode = document.getElementById('metode_pengambilan').value;
            document.getElementById('section_pengiriman').style.display = metode === 'dikirim' ? 'block' : 'none';
            document.getElementById('jenis_pengiriman_hidden').value = metode === 'dikirim' ? 'cargo' : '';
        }

        function pilihJenisPengiriman(jenis) {
            document.getElementById('jenis_pengiriman_hidden').value = 'cargo';
        }

        function pilihAlamat(el) {
            document.querySelectorAll('.alamat-card-select').forEach(card => card.classList.remove('selected'));
            el.classList.add('selected');
            document.getElementById('alamat_pembeli_id_hidden').value = el.dataset.id;
        }

        function pilihKurir(kurir) {
            selectedKurir = kurir;
            document.getElementById('courier_hidden').value = kurir;
            document.querySelectorAll('.courier-btn').forEach(btn => btn.classList.toggle('selected', btn.dataset.kurir === kurir));
        }

        document.addEventListener('DOMContentLoaded', toggleCheckoutMetode);
    </script>
@endsection
