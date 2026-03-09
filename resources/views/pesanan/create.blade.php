@extends('layouts.app')

@section('content')
    {{-- CSRF & config via meta tag agar tidak expired --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="origin-kecamatan-id" content="{{ config('services.rajaongkir.origin_kecamatan_id', '') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --adira-gold: #C5A47E;
            --adira-gold-light: rgba(197, 164, 126, 0.1);
            --adira-dark: #34495e;
            --adira-soft-grey: #f8f9fa;
        }

        body {
            background-color: #fcfcfc;
        }

        .page-header-container {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            border-bottom: 4px solid var(--adira-gold);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            margin-bottom: 3rem;
        }

        .icon-box-header {
            width: 70px;
            height: 70px;
            background: var(--adira-gold);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.2rem;
            box-shadow: 0 10px 20px rgba(197, 164, 126, 0.3);
        }

        .form-card-aesthetic {
            border: none;
            border-radius: 30px;
            background: #fff;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .form-section-title {
            font-size: 0.9rem;
            font-weight: 800;
            letter-spacing: 1.2px;
            color: var(--adira-gold);
            text-transform: uppercase;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }

        .form-section-title::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
            margin-left: 15px;
        }

        .label-aesthetic {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--adira-dark);
            margin-bottom: 8px;
        }

        .input-aesthetic {
            border: 1.5px solid #f0f0f0;
            border-radius: 14px;
            padding: 0.85rem 1.2rem;
            background-color: var(--adira-soft-grey);
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .input-aesthetic:focus {
            border-color: var(--adira-gold);
            background-color: #fff;
            box-shadow: 0 0 0 4px var(--adira-gold-light);
            outline: none;
        }

        .input-aesthetic[readonly] {
            background-color: #f0f4f8;
            color: #6c757d;
            cursor: not-allowed;
        }

        .shipping-box-highlight {
            background: #fffdfa;
            border: 2px dashed var(--adira-gold);
            border-radius: 20px;
            padding: 2rem;
            margin-top: 1.5rem;
        }

        .btn-submit-elegant {
            background-color: var(--adira-dark);
            color: white;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 1.2rem;
            border-radius: 18px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(52, 73, 94, 0.2);
        }

        .summary-panel {
            background: var(--adira-dark);
            color: white;
            border-radius: 20px;
            padding: 1.5rem;
        }

        .price-tag-display {
            font-size: 1.5rem;
            color: #ffc107;
            font-weight: 800;
        }

        .berat-info-box {
            background: linear-gradient(135deg, #fff8f0, #fff3e6);
            border: 1.5px solid var(--adira-gold);
            border-radius: 14px;
            padding: 0.85rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .jenis-kirim-tabs .btn {
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .jenis-kirim-tabs .btn.active {
            background: var(--adira-gold);
            color: white;
            border-color: var(--adira-gold);
        }

        .alamat-card-select {
            border: 1.5px solid #f0f0f0;
            border-radius: 14px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .alamat-card-select:hover {
            border-color: var(--adira-gold);
        }

        .alamat-card-select.selected {
            border-color: var(--adira-gold);
            background: #fffdf9;
        }

        .badge-utama-sm {
            background: var(--adira-gold);
            color: white;
            font-size: 0.65rem;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .courier-btn {
            border: 1.5px solid #f0f0f0;
            border-radius: 12px;
            padding: 0.6rem 1.2rem;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .courier-btn.selected {
            border-color: var(--adira-gold);
            background: var(--adira-gold);
            color: white;
        }

        .service-option {
            border: 1.5px solid #f0f0f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .service-option:hover {
            border-color: var(--adira-gold);
        }

        .service-option.selected {
            border-color: var(--adira-gold);
            background: #fffdf9;
        }
    </style>

    <div class="container py-5 mt-2 animate__animated animate__fadeIn">
        <div class="page-header-container d-flex align-items-center">
            <div class="icon-box-header me-4"><i class="fa-solid fa-gem"></i></div>
            <div>
                <h2 class="fw-bold mb-0 text-dark">Pemesanan Marmer Custom</h2>
                <p class="text-muted small mb-0">Personalisasi produk marmer Anda dengan standar kualitas premium</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="card form-card-aesthetic">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('pesanan.store') }}" method="POST" enctype="multipart/form-data"
                            id="orderForm">
                            @csrf
                            <input type="hidden" name="total_harga" id="total_harga_hidden" value="0">
                            <input type="hidden" name="biaya_pengiriman" id="biaya_pengiriman_hidden" value="0">
                            <input type="hidden" name="jenis_pengiriman" id="jenis_pengiriman_hidden" value="">
                            <input type="hidden" name="alamat_pembeli_id" id="alamat_pembeli_id_hidden" value="">
                            <input type="hidden" name="courier" id="courier_hidden" value="">

                            <div class="row g-5">
                                {{-- KOLOM KIRI --}}
                                <div class="col-md-6 border-end pe-md-5">
                                    <div class="form-section-title">1. Informasi Produk</div>

                                    <div class="mb-4">
                                        <label class="label-aesthetic">Nama Pemesan</label>
                                        <div class="p-3 rounded-4 border bg-light d-flex align-items-center">
                                            <i class="fa-solid fa-circle-user text-muted me-3 fa-lg"></i>
                                            <span class="fw-bold text-dark">{{ Auth::user()->name }}</span>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="label-aesthetic">Nama Produk</label>
                                        <input type="text" name="nama_produk" id="nama_produk"
                                            class="form-control input-aesthetic fw-bold {{ $produkTerpilih ? 'bg-light' : '' }}"
                                            value="{{ $produkTerpilih ?? '' }}" {{ $produkTerpilih ? 'readonly' : '' }}
                                            required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="label-aesthetic">Pilih Ukuran / Dimensi</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted rounded-start-4">
                                                <i class="fa-solid fa-maximize"></i>
                                            </span>
                                            <select name="ukuran" id="ukuran"
                                                class="form-select input-aesthetic border-start-0" onchange="updateHarga()"
                                                required>
                                                <option value="" disabled selected>-- Pilih Ukuran --</option>
                                                @if (isset($dataProduk))
                                                    @if ($dataProduk->ukuran_kecil)
                                                        <option value="{{ $dataProduk->ukuran_kecil }}"
                                                            data-harga="{{ $dataProduk->harga_kecil }}"
                                                            data-berat="{{ $dataProduk->berat_kecil ?? 0 }}">
                                                            {{ $dataProduk->ukuran_kecil }}</option>
                                                    @endif
                                                    @if ($dataProduk->ukuran_sedang)
                                                        <option value="{{ $dataProduk->ukuran_sedang }}"
                                                            data-harga="{{ $dataProduk->harga_sedang }}"
                                                            data-berat="{{ $dataProduk->berat_sedang ?? 0 }}">
                                                            {{ $dataProduk->ukuran_sedang }}</option>
                                                    @endif
                                                    @if ($dataProduk->ukuran_besar)
                                                        <option value="{{ $dataProduk->ukuran_besar }}"
                                                            data-harga="{{ $dataProduk->harga_besar }}"
                                                            data-berat="{{ $dataProduk->berat_besar ?? 0 }}">
                                                            {{ $dataProduk->ukuran_besar }}</option>
                                                    @endif
                                                @else
                                                    <option value="" disabled>Data produk tidak ditemukan</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-4" id="wrapper_berat_satuan" style="display:none;">
                                        <label class="label-aesthetic"><i class="fa-solid fa-weight-hanging me-2"
                                                style="color:var(--adira-gold);"></i>Berat Satuan</label>
                                        <div class="berat-info-box">
                                            <i class="fa-solid fa-cube" style="color:var(--adira-gold);"></i>
                                            <input type="text" id="display_berat_satuan"
                                                class="form-control border-0 bg-transparent p-0 fw-bold" readonly>
                                            <span class="text-muted small ms-auto">per item</span>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="label-aesthetic">Jenis Bahan Marmer</label>
                                        <select name="jenis_marmer" id="jenis_marmer" class="form-select input-aesthetic"
                                            required>
                                            <option value="" disabled selected>-- Pilih Material --</option>
                                            @foreach ($listBahan as $bahan)
                                                <option value="{{ $bahan->nama_bahan }}">{{ $bahan->nama_bahan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- KOLOM KANAN --}}
                                <div class="col-md-6 ps-md-5">
                                    <div class="form-section-title">2. Detail Kustomisasi</div>

                                    <div class="mb-4">
                                        <label class="label-aesthetic">Catatan Khusus (Warna/Tekstur)</label>
                                        <textarea name="catatan_khusus" class="form-control input-aesthetic" rows="3"
                                            placeholder="Gambarkan keinginan detail Anda..."></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="label-aesthetic">Gambar Acuan / Referensi (Penting)</label>
                                        <input type="file" name="gambar_referensi"
                                            class="form-control input-aesthetic">
                                    </div>

                                    <div class="row g-3 mb-4">
                                        <div class="col-6">
                                            <label class="label-aesthetic">Jumlah (Qty)</label>
                                            <input type="number" name="jumlah" id="input_qty"
                                                class="form-control input-aesthetic" min="1" value="1"
                                                required>
                                        </div>
                                        <div class="col-6">
                                            <label class="label-aesthetic">Metode Ambil</label>
                                            <select name="metode_pengambilan" id="metode_pengambilan"
                                                class="form-select input-aesthetic" onchange="toggleMetode()" required>
                                                <option value="dirumah">Ambil di Rumah</option>
                                                <option value="dikirim">Dikirim</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- SECTION PENGIRIMAN --}}
                                    <div id="section_pengiriman" style="display:none;">
                                        <div class="shipping-box-highlight">
                                            <label class="label-aesthetic mb-3">Jenis Pengiriman</label>
                                            <div class="jenis-kirim-tabs d-flex gap-2 mb-4">
                                                <button type="button" class="btn btn-outline-secondary flex-fill"
                                                    id="tab_bus" onclick="pilihJenisPengiriman('bus')">
                                                    <i class="fa-solid fa-bus me-2"></i>Via Bus
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary flex-fill"
                                                    id="tab_cargo" onclick="pilihJenisPengiriman('cargo')">
                                                    <i class="fa-solid fa-truck me-2"></i>Cargo
                                                </button>
                                            </div>

                                            {{-- BUS --}}
                                            <div id="section_bus" style="display:none;">
                                                <label class="label-aesthetic"><i class="fa-solid fa-bus me-2"></i>Pilih
                                                    Terminal Tujuan</label>
                                                <select name="terminal_id" id="terminal_id"
                                                    class="form-select input-aesthetic" onchange="hitungOngkirBerat()">
                                                    <option value="" data-tarif-per-kg="0" selected disabled>--
                                                        Pilih Terminal --</option>
                                                    @foreach ($listTerminal as $t)
                                                        <option value="{{ $t->id }}"
                                                            data-tarif-per-kg="{{ $t->tarif_per_kg ?? $t->tarif_per_km }}">
                                                            {{ $t->nama_terminal }} (Rp
                                                            {{ number_format($t->tarif_per_kg ?? $t->tarif_per_km, 0, ',', '.') }}/kg)
                                                        </option>
                                                    @endforeach
                                                    <option value="lainnya">Lainnya (Isi Manual)</option>
                                                </select>
                                                <div id="wrapper_alamat_manual" class="mt-3" style="display:none;">
                                                    <label class="label-aesthetic">Alamat Tujuan Manual</label>
                                                    <input type="text" name="alamat_manual" id="alamat_manual"
                                                        class="form-control input-aesthetic"
                                                        placeholder="Nama terminal / alamat tujuan">
                                                </div>
                                            </div>

                                            {{-- CARGO --}}
                                            <div id="section_cargo" style="display:none;">
                                                <label class="label-aesthetic mb-2"><i
                                                        class="fa-solid fa-location-dot me-2"></i>Alamat Pengiriman</label>

                                                @if ($listAlamat->isEmpty())
                                                    <div class="text-center py-3">
                                                        <p class="text-muted small mb-2">Belum ada alamat tersimpan.</p>
                                                        <a href="{{ route('alamat.index') }}" target="_blank"
                                                            class="btn btn-sm"
                                                            style="background:var(--adira-gold);color:white;border-radius:10px;">
                                                            <i class="fa-solid fa-plus me-1"></i>Tambah Alamat
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="small text-muted">Pilih alamat terdaftar</span>
                                                        <a href="{{ route('alamat.index') }}" target="_blank"
                                                            class="btn btn-sm"
                                                            style="background:var(--adira-gold);color:white;border-radius:8px;font-size:0.78rem;">
                                                            <i class="fa-solid fa-plus me-1"></i>Kelola Alamat
                                                        </a>
                                                    </div>
                                                    <div id="list_alamat_cargo" class="d-flex flex-column gap-2 mb-3">
                                                        @foreach ($listAlamat as $a)
                                                            <div class="alamat-card-select {{ $a->is_utama ? 'selected' : '' }}"
                                                                data-id="{{ $a->id }}"
                                                                data-kecamatan-id="{{ $a->kecamatan_id }}"
                                                                onclick="pilihAlamat(this)">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-start">
                                                                    <span
                                                                        class="fw-semibold small">{{ $a->label }}</span>
                                                                    @if ($a->is_utama)
                                                                        <span class="badge-utama-sm">Utama</span>
                                                                    @endif
                                                                </div>
                                                                <p class="mb-0 small text-muted mt-1">
                                                                    {{ $a->nama_penerima }} &middot; {{ $a->no_telepon }}
                                                                </p>
                                                                <p class="mb-0 small text-muted">
                                                                    {{ Str::limit($a->alamat_lengkap, 55) }},
                                                                    {{ $a->kecamatan_nama }}, {{ $a->kota_nama }}</p>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <label class="label-aesthetic mb-2">Pilih Kurir</label>
                                                    <div class="d-flex flex-wrap gap-2 mb-3" id="courier_list">
                                                        @foreach (['jne', 'tiki', 'pos', 'jnt', 'sicepat'] as $kurir)
                                                            <div class="courier-btn" data-kurir="{{ $kurir }}"
                                                                onclick="pilihKurir('{{ $kurir }}')">
                                                                {{ strtoupper($kurir) }}</div>
                                                        @endforeach
                                                    </div>

                                                    <div id="section_layanan_cargo" style="display:none;">
                                                        <label class="label-aesthetic mb-2">Pilih Layanan</label>
                                                        <div id="loading_ongkir" class="text-muted small text-center py-2"
                                                            style="display:none;">
                                                            <i class="fa-solid fa-spinner fa-spin me-2"></i>Menghitung
                                                            ongkos kirim...
                                                        </div>
                                                        <div id="list_layanan_cargo" class="d-flex flex-column gap-2">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="summary-panel mt-4">
                                        <div class="d-flex justify-content-between mb-2 small opacity-75">
                                            <span>Estimasi Berat Total:</span>
                                            <span id="label_total_berat" class="fw-bold">0 kg</span>
                                        </div>
                                        <div
                                            class="d-flex justify-content-between mb-2 border-bottom pb-2 border-secondary">
                                            <span class="small opacity-75">Total Produk:</span>
                                            <span class="fw-bold" id="label_total_produk">Rp 0</span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span class="small opacity-75">Ongkos Kirim:</span>
                                            <span class="fw-bold text-warning" id="label_ongkir">Rp 0</span>
                                        </div>
                                        <hr class="bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-uppercase" style="letter-spacing:1px;">Total:</span>
                                            <span class="price-tag-display" id="label_grand_total">Rp 0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 pt-4 text-center">
                                <button type="submit" class="btn btn-submit-elegant w-100">
                                    <i class="fa-solid fa-circle-check me-2"></i>AJUKAN PESANAN SEKARANG
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const ORIGIN_KECAMATAN_ID = document.querySelector('meta[name="origin-kecamatan-id"]').getAttribute('content');

        let hargaProdukGlobal = 0;
        let beratSatuanGlobal = 0;
        let beratTotalGlobal = 0;
        let ongkirGlobal = 0;
        let selectedKecamatanId = null;
        let selectedKurir = null;

        function getCsrf() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        async function apiFetch(url, method = 'GET', body = null) {
            const opts = {
                method,
                headers: {
                    'X-CSRF-TOKEN': getCsrf(),
                    'Accept': 'application/json',
                },
            };
            if (body) {
                opts.headers['Content-Type'] = 'application/json';
                opts.body = JSON.stringify(body);
            }
            const res = await fetch(url, opts);
            if (res.status === 419) {
                alert('Sesi habis, halaman akan dimuat ulang.');
                location.reload();
                return null;
            }
            return res.json();
        }

        function updateHarga() {
            const sel = document.getElementById('ukuran');
            const opt = sel.options[sel.selectedIndex];
            const hargaSatuan = (opt && opt.getAttribute('data-harga')) ? parseInt(opt.getAttribute('data-harga')) : 0;
            beratSatuanGlobal = (opt && opt.getAttribute('data-berat')) ? parseFloat(opt.getAttribute('data-berat')) : 0;
            const qty = parseInt(document.getElementById('input_qty').value) || 1;

            const wrapperBerat = document.getElementById('wrapper_berat_satuan');
            if (beratSatuanGlobal > 0 && opt && !opt.disabled) {
                document.getElementById('display_berat_satuan').value = beratSatuanGlobal + ' kg';
                wrapperBerat.style.display = 'block';
            } else {
                wrapperBerat.style.display = 'none';
            }

            hargaProdukGlobal = hargaSatuan * qty;
            beratTotalGlobal = beratSatuanGlobal * qty;

            document.getElementById('label_total_produk').innerText = 'Rp ' + hargaProdukGlobal.toLocaleString('id-ID');
            document.getElementById('label_total_berat').innerText = beratTotalGlobal.toFixed(1) + ' kg';

            const metode = document.getElementById('metode_pengambilan').value;
            if (metode === 'dikirim') {
                const jenis = document.getElementById('jenis_pengiriman_hidden').value;
                if (jenis === 'bus') hitungOngkirBerat();
                else if (jenis === 'cargo' && selectedKecamatanId && selectedKurir) hitungOngkirCargo();
            } else {
                ongkirGlobal = 0;
                refreshGrandTotal();
            }
        }

        function toggleMetode() {
            const metode = document.getElementById('metode_pengambilan').value;
            document.getElementById('section_pengiriman').style.display = metode === 'dikirim' ? 'block' : 'none';
            ongkirGlobal = 0;
            document.getElementById('jenis_pengiriman_hidden').value = '';
            refreshGrandTotal();
        }

        function pilihJenisPengiriman(jenis) {
            document.getElementById('jenis_pengiriman_hidden').value = jenis;
            document.getElementById('section_bus').style.display = jenis === 'bus' ? 'block' : 'none';
            document.getElementById('section_cargo').style.display = jenis === 'cargo' ? 'block' : 'none';
            document.getElementById('tab_bus').classList.toggle('active', jenis === 'bus');
            document.getElementById('tab_cargo').classList.toggle('active', jenis === 'cargo');
            ongkirGlobal = 0;
            document.getElementById('label_ongkir').innerText = 'Rp 0';
            refreshGrandTotal();
        }

        function hitungOngkirBerat() {
            const sel = document.getElementById('terminal_id');
            const opt = sel.options[sel.selectedIndex];
            document.getElementById('wrapper_alamat_manual').style.display =
                (opt && opt.value === 'lainnya') ? 'block' : 'none';
            const tarifPerKg = (opt && !opt.disabled) ? parseInt(opt.getAttribute('data-tarif-per-kg')) || 0 : 0;
            ongkirGlobal = Math.round(beratTotalGlobal * tarifPerKg);
            document.getElementById('label_ongkir').innerText = 'Rp ' + ongkirGlobal.toLocaleString('id-ID');
            refreshGrandTotal();
        }

        function pilihAlamat(el) {
            document.querySelectorAll('.alamat-card-select').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');
            selectedKecamatanId = el.dataset.kecamatanId;
            document.getElementById('alamat_pembeli_id_hidden').value = el.dataset.id;
            if (selectedKurir) hitungOngkirCargo();
        }

        function pilihKurir(kurir) {
            selectedKurir = kurir;
            document.getElementById('courier_hidden').value = kurir;
            document.querySelectorAll('.courier-btn').forEach(b => b.classList.toggle('selected', b.dataset.kurir ===
                kurir));
            if (selectedKecamatanId) hitungOngkirCargo();
        }

        async function hitungOngkirCargo() {
            if (!selectedKecamatanId || !selectedKurir || !ORIGIN_KECAMATAN_ID) return;
            const beratGram = Math.max(Math.round(beratTotalGlobal * 1000), 1);

            document.getElementById('section_layanan_cargo').style.display = 'block';
            document.getElementById('loading_ongkir').style.display = 'block';
            document.getElementById('list_layanan_cargo').innerHTML = '';

            const json = await apiFetch('/ongkir/hitung', 'POST', {
                origin: ORIGIN_KECAMATAN_ID,
                destination: selectedKecamatanId,
                weight: beratGram,
                courier: selectedKurir,
            });

            document.getElementById('loading_ongkir').style.display = 'none';
            if (!json) return;

            // V2: data = [ { code, name, service, description, cost, etd }, ... ]
            const results = json.data || [];
            const container = document.getElementById('list_layanan_cargo');
            container.innerHTML = '';

            if (!results.length) {
                container.innerHTML = '<p class="text-muted small">Layanan tidak tersedia untuk tujuan ini.</p>';
                return;
            }

            results.forEach(item => {
                const cost = item.cost;
                const etd = item.etd;
                const el = document.createElement('div');
                el.className = 'service-option';
                el.dataset.cost = cost;
                el.innerHTML = `<div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fw-bold small">${item.code.toUpperCase()} - ${item.service}</span><br>
                        <span class="text-muted" style="font-size:0.78rem;">${item.description} &middot; Est. ${item.etd}</span>
                    </div>
                    <span class="fw-bold text-success">Rp ${cost.toLocaleString('id-ID')}</span>
                </div>`;
                el.onclick = function() {
                    document.querySelectorAll('.service-option').forEach(x => x.classList.remove(
                        'selected'));
                    el.classList.add('selected');
                    ongkirGlobal = cost;
                    document.getElementById('label_ongkir').innerText = 'Rp ' + cost.toLocaleString(
                    'id-ID');
                    refreshGrandTotal();
                };
                container.appendChild(el);
            });
        }

        function refreshGrandTotal() {
            const total = (hargaProdukGlobal || 0) + (ongkirGlobal || 0);
            document.getElementById('label_grand_total').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('total_harga_hidden').value = hargaProdukGlobal;
            document.getElementById('biaya_pengiriman_hidden').value = ongkirGlobal;
        }

        document.getElementById('input_qty').addEventListener('input', updateHarga);

        window.onload = function() {
            updateHarga();
            @if ($listAlamat->isNotEmpty())
                const utama = document.querySelector('.alamat-card-select.selected');
                if (utama) {
                    selectedKecamatanId = utama.dataset.kecamatanId;
                    document.getElementById('alamat_pembeli_id_hidden').value = utama.dataset.id;
                }
            @endif
        };
    </script>
@endsection
