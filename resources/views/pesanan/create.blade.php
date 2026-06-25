@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        /* ── BENTUK TOGGLE ── */
        .bentuk-toggle-box {
            background: linear-gradient(135deg, #fffdf9, #fff8f0);
            border: 1.5px solid var(--adira-gold);
            border-radius: 16px;
            padding: 0.9rem 1.2rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .bentuk-toggle-box:hover {
            box-shadow: 0 4px 14px rgba(197, 164, 126, 0.2);
        }

        .bentuk-toggle-box input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--adira-gold);
            cursor: pointer;
            flex-shrink: 0;
        }

        .bentuk-toggle-label {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--adira-dark);
            user-select: none;
            margin: 0;
        }

        .bentuk-toggle-label span {
            color: var(--adira-gold);
        }

        .dim-input-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .dim-input-item {
            flex: 1;
            min-width: 90px;
        }

        .dim-input-item label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 5px;
            display: block;
        }

        .dim-input-item input {
            width: 100%;
        }

        .dim-preview-badge {
            display: inline-block;
            background: var(--adira-gold-light);
            border: 1px solid var(--adira-gold);
            border-radius: 30px;
            padding: 4px 14px;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--adira-dark);
            margin-top: 10px;
            letter-spacing: 0.5px;
            min-height: 26px;
        }

        /* ── GAMBAR REFERENSI ── */
        .upload-zone {
            border: 2px dashed #ddd;
            border-radius: 16px;
            padding: 1.2rem;
            background: var(--adira-soft-grey);
            transition: all 0.2s;
            cursor: pointer;
        }

        .upload-zone:hover {
            border-color: var(--adira-gold);
            background: #fffdf9;
        }

        .upload-zone input[type="file"] {
            display: none;
        }

        .upload-zone-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            color: #aaa;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .upload-zone-label i {
            font-size: 1.6rem;
            color: var(--adira-gold);
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            margin-top: 10px;
        }

        .preview-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            aspect-ratio: 1;
            background: #f0f0f0;
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .preview-item .remove-btn {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 20px;
            height: 20px;
            background: rgba(0, 0, 0, 0.55);
            border-radius: 50%;
            border: none;
            color: white;
            font-size: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            line-height: 1;
        }

        .preview-item .remove-btn:hover {
            background: #e74c3c;
        }

        .upload-counter {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--adira-gold);
            margin-top: 6px;
        }

        .upload-counter.full {
            color: #e74c3c;
        }

        .sample-picker {
            margin-top: 1rem;
        }

        .sample-thumb-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 88px));
            gap: 12px;
            align-items: start;
        }

        .sample-thumb-option {
            position: relative;
            width: 88px;
            height: 88px;
            border-radius: 14px;
            border: 2px solid #e9ecef;
            overflow: hidden;
            cursor: pointer;
            background: #fff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .sample-thumb-option:hover {
            border-color: var(--adira-gold);
            transform: translateY(-1px);
        }

        .sample-thumb-option.active {
            border-color: var(--adira-gold);
            box-shadow: 0 0 0 4px rgba(197, 164, 126, 0.16);
        }

        .sample-thumb-option img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .sample-thumb-check {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 22px;
            height: 22px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(44, 62, 80, 0.72);
            color: white;
            font-size: 0.72rem;
            opacity: 0;
            transition: opacity 0.2s ease, background-color 0.2s ease;
        }

        .sample-thumb-option.active .sample-thumb-check {
            opacity: 1;
            background: var(--adira-gold);
        }

        .sample-picker-empty {
            font-size: 0.82rem;
            color: #8b8f94;
            background: #f8f9fa;
            border: 1px dashed #d8dde3;
            border-radius: 14px;
            padding: 0.9rem 1rem;
        }

        /* ── DISCLAIMER ── */
        .disclaimer-box {
            background: linear-gradient(135deg, #fffbf4, #fff5e6);
            border-left: 4px solid var(--adira-gold);
            border-radius: 12px;
            padding: 0.9rem 1.1rem;
            margin-top: 10px;
            font-size: 0.78rem;
            color: #7a6040;
            line-height: 1.6;
        }

        .disclaimer-box i {
            color: var(--adira-gold);
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
                        <form action="{{ route('cart.store') }}" method="POST" enctype="multipart/form-data"
                            id="orderForm">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $dataProduk->id ?? '' }}">
                            <input type="hidden" name="harga_satuan" id="harga_satuan_hidden" value="0">
                            <input type="hidden" name="subtotal" id="subtotal_hidden" value="0">
                            <input type="hidden" name="berat_satuan" id="berat_satuan_hidden" value="0">
                            <input type="hidden" name="foto_sampel_terpilih" id="foto_sampel_terpilih" value="">

                            <div class="row g-5">
                                {{-- ══ KOLOM KIRI ══ --}}
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
                                        @if (isset($dataProduk) && $dataProduk)
                                            {{-- ── KATALOG: dropdown ukuran ── --}}
                                            <div class="input-group">
                                                <span
                                                    class="input-group-text bg-white border-end-0 text-muted rounded-start-4">
                                                    <i class="fa-solid fa-maximize"></i>
                                                </span>
                                                <select name="ukuran" id="ukuran"
                                                    class="form-select input-aesthetic border-start-0"
                                                    onchange="updateHarga()" required>
                                                    <option value="" disabled selected>-- Pilih Ukuran --</option>
                                                    @if ($dataProduk->ukuran_kecil)
                                                        <option value="{{ $dataProduk->ukuran_kecil }}"
                                                            data-bahan-id="{{ $dataProduk->bahan_kecil_id }}"
                                                            data-bahan="{{ $dataProduk->bahan_kecil->nama_bahan ?? '' }}"
                                                            data-harga="{{ $dataProduk->harga_kecil }}"
                                                            data-berat="{{ $dataProduk->berat_kecil ?? 0 }}">
                                                            {{ $dataProduk->ukuran_kecil }}</option>
                                                    @endif
                                                    @if ($dataProduk->ukuran_sedang)
                                                        <option value="{{ $dataProduk->ukuran_sedang }}"
                                                            data-bahan-id="{{ $dataProduk->bahan_sedang_id }}"
                                                            data-bahan="{{ $dataProduk->bahan_sedang->nama_bahan ?? '' }}"
                                                            data-harga="{{ $dataProduk->harga_sedang }}"
                                                            data-berat="{{ $dataProduk->berat_sedang ?? 0 }}">
                                                            {{ $dataProduk->ukuran_sedang }}</option>
                                                    @endif
                                                    @if ($dataProduk->ukuran_besar)
                                                        <option value="{{ $dataProduk->ukuran_besar }}"
                                                            data-bahan-id="{{ $dataProduk->bahan_besar_id }}"
                                                            data-bahan="{{ $dataProduk->bahan_besar->nama_bahan ?? '' }}"
                                                            data-harga="{{ $dataProduk->harga_besar }}"
                                                            data-berat="{{ $dataProduk->berat_besar ?? 0 }}">
                                                            {{ $dataProduk->ukuran_besar }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        @else
                                            {{-- ── CUSTOM: input dimensi dinamis ── --}}
                                            <label class="bentuk-toggle-box" for="is_silinder">
                                                <input type="checkbox" id="is_silinder" onchange="toggleBentuk()">
                                                <span class="bentuk-toggle-label">
                                                    <i class="fa-solid fa-circle me-1" style="color:var(--adira-gold);"></i>
                                                    Bentuk <span>Bulat / Silinder</span>
                                                </span>
                                            </label>

                                            <div id="input_persegi" class="dim-input-group">
                                                <div class="dim-input-item">
                                                    <label>Panjang (cm)</label>
                                                    <input type="number" id="dim_panjang"
                                                        class="form-control input-aesthetic" placeholder="cth: 40"
                                                        min="1" step="0.1" oninput="previewUkuran()">
                                                </div>
                                                <div class="dim-input-item">
                                                    <label>Lebar (cm)</label>
                                                    <input type="number" id="dim_lebar"
                                                        class="form-control input-aesthetic" placeholder="cth: 40"
                                                        min="1" step="0.1" oninput="previewUkuran()">
                                                </div>
                                                <div class="dim-input-item">
                                                    <label>Tinggi (cm)</label>
                                                    <input type="number" id="dim_tinggi_persegi"
                                                        class="form-control input-aesthetic" placeholder="cth: 10"
                                                        min="1" step="0.1" oninput="previewUkuran()">
                                                </div>
                                            </div>

                                            <div id="input_silinder" class="dim-input-group" style="display:none;">
                                                <div class="dim-input-item">
                                                    <label>Diameter (cm)</label>
                                                    <input type="number" id="dim_diameter"
                                                        class="form-control input-aesthetic" placeholder="cth: 30"
                                                        min="1" step="0.1" oninput="previewUkuran()">
                                                </div>
                                                <div class="dim-input-item">
                                                    <label>Tinggi (cm)</label>
                                                    <input type="number" id="dim_tinggi_silinder"
                                                        class="form-control input-aesthetic" placeholder="cth: 10"
                                                        min="1" step="0.1" oninput="previewUkuran()">
                                                </div>
                                            </div>

                                            <div id="dim_preview" class="dim-preview-badge" style="display:none;"></div>

                                            <input type="hidden" name="ukuran" id="ukuran">

                                            <small class="text-muted mt-2 d-block">
                                                <i class="fa-solid fa-circle-info me-1"></i>
                                                Produk custom akan dikonfirmasi admin (harga, berat, dan status).
                                            </small>
                                        @endif
                                    </div>

                                    <div class="mb-4" id="wrapper_berat_satuan" style="display:none;">
                                        <label class="label-aesthetic">
                                            <i class="fa-solid fa-weight-hanging me-2"
                                                style="color:var(--adira-gold);"></i>Berat Satuan
                                        </label>
                                        <div class="berat-info-box">
                                            <i class="fa-solid fa-cube" style="color:var(--adira-gold);"></i>
                                            <input type="text" id="display_berat_satuan"
                                                class="form-control border-0 bg-transparent p-0 fw-bold" readonly>
                                            <span class="text-muted small ms-auto">per item</span>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="label-aesthetic">Jenis Bahan Marmer</label>
                                        @if (isset($dataProduk) && $dataProduk)
                                            <input type="text" name="jenis_marmer" id="jenis_marmer"
                                                class="form-control input-aesthetic"
                                                placeholder="Otomatis terisi saat ukuran dipilih" readonly>
                                        @else
                                            <select name="jenis_marmer" id="jenis_marmer"
                                                class="form-select input-aesthetic" onchange="handleCustomBahanChange()"
                                                required>
                                                <option value="" selected disabled>-- Pilih Bahan Marmer --</option>
                                                @foreach ($listBahan as $bahan)
                                                    <option value="{{ $bahan->nama_bahan }}"
                                                        data-bahan-id="{{ $bahan->id }}">{{ $bahan->nama_bahan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>

                                {{-- ══ KOLOM KANAN ══ --}}
                                <div class="col-md-6 ps-md-5">
                                    <div class="form-section-title">2. Detail Kustomisasi</div>

                                    <div class="mb-4">
                                        <label class="label-aesthetic">Catatan Khusus (Warna/Tekstur)</label>
                                        <textarea name="catatan_khusus" class="form-control input-aesthetic" rows="3"
                                            placeholder="Gambarkan keinginan detail Anda..."></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="label-aesthetic">Pilihan Sampel Tekstur & Warna</label>
                                        <div id="samplePicker" class="sample-picker-empty">
                                            Pilih bahan marmer terlebih dahulu untuk melihat sampel tekstur dan warna.
                                        </div>
                                    </div>

                                    {{-- ── GAMBAR REFERENSI MAX 5 ── --}}
                                    <div class="mb-4">
                                        <label class="label-aesthetic">
                                            Gambar Acuan / Referensi
                                            <span class="text-muted fw-normal" style="font-size:0.78rem;">(Maks. 5
                                                foto)</span>
                                        </label>

                                        <div class="upload-zone" id="uploadZone"
                                            onclick="document.getElementById('gambar_referensi_input').click()">
                                            <input type="file" id="gambar_referensi_input" name="gambar_referensi[]"
                                                accept="image/jpeg,image/png,image/jpg" multiple>
                                            <label class="upload-zone-label" for="gambar_referensi_input"
                                                onclick="event.stopPropagation()">
                                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                                <span>Klik untuk pilih gambar</span>
                                                <span class="text-muted" style="font-weight:400;">JPG / PNG · maks. 2MB
                                                    per foto</span>
                                            </label>
                                        </div>

                                        {{-- Preview grid --}}
                                        <div class="preview-grid" id="previewGrid" style="display:none;"></div>

                                        {{-- Counter --}}
                                        <div class="upload-counter" id="uploadCounter" style="display:none;"></div>

                                        {{-- Disclaimer --}}
                                        <div class="disclaimer-box mt-3">
                                            <i class="fa-solid fa-triangle-exclamation me-1"></i>
                                            <strong>Catatan:</strong> Gambar di atas hanya sampel visual corak dan warna
                                            dasar.
                                            Karena marmer merupakan batu alam murni, motif asli produk tidak akan bisa
                                            100% sama persis dengan sampel.
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-4">
                                        <div class="col-6">
                                            <label class="label-aesthetic">Jumlah (Qty)</label>
                                            <input type="number" name="jumlah" id="input_qty"
                                                class="form-control input-aesthetic" min="1" value="1"
                                                required>
                                        </div>
                                        <div class="col-6">
                                            <label class="label-aesthetic">Alur Checkout</label>
                                            <div class="p-3 rounded-4 border bg-light small text-muted h-100 d-flex align-items-center">
                                                Metode ambil dan pengiriman dipilih nanti saat checkout di keranjang.
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
                                        <span class="small opacity-75">Status Checkout:</span>
                                        <span class="fw-bold text-warning" id="label_ongkir">Akan dipilih di keranjang</span>
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
                                    <i class="fa-solid fa-cart-plus me-2"></i>TAMBAHKAN KE KERANJANG
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $bahanSamplesPayload = $listBahan->mapWithKeys(function ($bahan) {
            return [
                (string) $bahan->id => [
                    'id' => $bahan->id,
                    'nama_bahan' => $bahan->nama_bahan,
                    'foto_sampel' => collect($bahan->foto_sampel ?? [])
                        ->map(function ($foto) {
                            return asset('storage/' . $foto);
                        })
                        ->values()
                        ->all(),
                ],
            ];
        })->all();
    @endphp

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const isProdukKatalog = @json(isset($dataProduk) && $dataProduk);
        const bahanSamples = @json($bahanSamplesPayload);

        let hargaProdukGlobal = 0;
        let beratSatuanGlobal = 0;
        let beratTotalGlobal = 0;
        let selectedSamplePhoto = '';

        // ── FILE LIST untuk gambar referensi ──────────────────────
        let fileList = []; // array of File objects, max 5

        const MAX_FILES = 5;
        const inputEl = document.getElementById('gambar_referensi_input');
        const previewGrid = document.getElementById('previewGrid');
        const counter = document.getElementById('uploadCounter');
        const samplePicker = document.getElementById('samplePicker');
        const selectedSampleInput = document.getElementById('foto_sampel_terpilih');

        inputEl.addEventListener('change', function() {
            const incoming = Array.from(this.files);

            incoming.forEach(file => {
                if (fileList.length >= MAX_FILES) return;
                // Hindari duplikat nama+size
                const isDup = fileList.some(f => f.name === file.name && f.size === file.size);
                if (!isDup) fileList.push(file);
            });

            // Reset value supaya event change bisa trigger lagi untuk file yg sama
            this.value = '';

            renderPreviews();
            syncFilesToInput();
        });

        function renderPreviews() {
            previewGrid.innerHTML = '';

            if (fileList.length === 0) {
                previewGrid.style.display = 'none';
                counter.style.display = 'none';
                return;
            }

            previewGrid.style.display = 'grid';
            counter.style.display = 'block';
            counter.className = 'upload-counter' + (fileList.length >= MAX_FILES ? ' full' : '');
            counter.innerText = fileList.length + ' / ' + MAX_FILES + ' foto dipilih';

            fileList.forEach((file, idx) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const item = document.createElement('div');
                    item.className = 'preview-item';
                    item.innerHTML = `
                        <img src="${e.target.result}" alt="preview">
                        <button type="button" class="remove-btn" onclick="removeFile(${idx})">
                            <i class="fa-solid fa-xmark"></i>
                        </button>`;
                    previewGrid.appendChild(item);
                };
                reader.readAsDataURL(file);
            });

            // Sembunyikan upload zone jika sudah penuh
            document.getElementById('uploadZone').style.opacity = fileList.length >= MAX_FILES ? '0.5' : '1';
            document.getElementById('uploadZone').style.pointerEvents = fileList.length >= MAX_FILES ? 'none' : 'auto';
        }

        function removeFile(idx) {
            fileList.splice(idx, 1);
            renderPreviews();
            syncFilesToInput();
        }

        /**
         * Sync fileList (array of File) ke <input type="file">
         * menggunakan DataTransfer API agar file benar-benar terkirim via form
         */
        function syncFilesToInput() {
            const dt = new DataTransfer();
            fileList.forEach(f => dt.items.add(f));
            inputEl.files = dt.files;
        }

        function renderSamplePicker(sampleUrls = []) {
            samplePicker.innerHTML = '';

            if (!sampleUrls.length) {
                samplePicker.className = 'sample-picker-empty';
                samplePicker.textContent = 'Belum ada foto sampel untuk bahan marmer ini.';
                selectedSamplePhoto = '';
                selectedSampleInput.value = '';
                return;
            }

            samplePicker.className = 'sample-picker';

            const grid = document.createElement('div');
            grid.className = 'sample-thumb-grid';

            sampleUrls.slice(0, 4).forEach((url, index) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'sample-thumb-option' + (index === 0 ? ' active' : '');
                button.dataset.sampleUrl = url;
                button.innerHTML = `
                    <img src="${url}" alt="Sampel tekstur marmer ${index + 1}">
                    <span class="sample-thumb-check"><i class="fa-solid fa-check"></i></span>
                `;
                button.addEventListener('click', function() {
                    selectSamplePhoto(url, button);
                });
                grid.appendChild(button);
            });

            samplePicker.appendChild(grid);
            selectSamplePhoto(sampleUrls[0], grid.querySelector('.sample-thumb-option'));
        }

        function selectSamplePhoto(url, activeButton = null) {
            selectedSamplePhoto = url || '';
            selectedSampleInput.value = selectedSamplePhoto;

            document.querySelectorAll('.sample-thumb-option').forEach((button) => {
                const isActive = activeButton ? button === activeButton : button.dataset.sampleUrl === url;
                button.classList.toggle('active', isActive);
            });
        }

        function syncSamplePickerByBahanId(bahanId) {
            const bahan = bahanId ? bahanSamples[String(bahanId)] : null;
            renderSamplePicker(bahan?.foto_sampel || []);
        }

        function handleCustomBahanChange() {
            if (isProdukKatalog) return;

            const select = document.getElementById('jenis_marmer');
            const option = select.options[select.selectedIndex];
            syncSamplePickerByBahanId(option?.dataset?.bahanId || '');
        }

        // ── CSRF ──────────────────────────────────────────────────
        function getCsrf() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        async function apiFetch(url, method = 'GET', body = null) {
            const opts = {
                method,
                headers: {
                    'X-CSRF-TOKEN': getCsrf(),
                    'Accept': 'application/json'
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

        // ── CUSTOM DIMENSION ──────────────────────────────────────
        function toggleBentuk() {
            if (isProdukKatalog) return;

            const isSilinder = document.getElementById('is_silinder').checked;
            const wrapperPersegi = document.getElementById('input_persegi');
            const wrapperSilinder = document.getElementById('input_silinder');

            wrapperPersegi.style.display = isSilinder ? 'none' : 'flex';
            wrapperSilinder.style.display = isSilinder ? 'flex' : 'none';

            ['dim_panjang', 'dim_lebar', 'dim_tinggi_persegi', 'dim_diameter', 'dim_tinggi_silinder']
            .forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });

            const preview = document.getElementById('dim_preview');
            if (preview) {
                preview.style.display = 'none';
                preview.innerText = '';
            }
            const hiddenUkuran = document.getElementById('ukuran');
            if (hiddenUkuran) hiddenUkuran.value = '';
        }

        function previewUkuran() {
            if (isProdukKatalog) return;

            const isSilinder = document.getElementById('is_silinder')?.checked;
            const preview = document.getElementById('dim_preview');
            if (!preview) return;

            let label = '';
            if (isSilinder) {
                const d = document.getElementById('dim_diameter')?.value;
                const t = document.getElementById('dim_tinggi_silinder')?.value;
                if (d && t) label = `diameter ${d} cm × Tinggi ${t} cm`;
            } else {
                const p = document.getElementById('dim_panjang')?.value;
                const l = document.getElementById('dim_lebar')?.value;
                const t = document.getElementById('dim_tinggi_persegi')?.value;
                if (p && l && t) label = `${p} × ${l} × ${t} cm`;
            }

            if (label) {
                preview.innerText = '📐 ' + label;
                preview.style.display = 'inline-block';
            } else {
                preview.style.display = 'none';
                preview.innerText = '';
            }
        }

        function composeUkuran() {
            if (isProdukKatalog) return true;

            const isSilinder = document.getElementById('is_silinder')?.checked;
            const ukuranField = document.getElementById('ukuran');

            if (isSilinder) {
                const d = document.getElementById('dim_diameter')?.value?.trim();
                const t = document.getElementById('dim_tinggi_silinder')?.value?.trim();
                if (!d || !t) {
                    alert('Silakan isi Diameter dan Tinggi terlebih dahulu.');
                    return false;
                }
                ukuranField.value = `Diameter ${d}cm x Tinggi ${t}cm`;
            } else {
                const p = document.getElementById('dim_panjang')?.value?.trim();
                const l = document.getElementById('dim_lebar')?.value?.trim();
                const t = document.getElementById('dim_tinggi_persegi')?.value?.trim();
                if (!p || !l || !t) {
                    alert('Silakan isi Panjang, Lebar, dan Tinggi terlebih dahulu.');
                    return false;
                }
                ukuranField.value = `${p}x${l}x${t} cm`;
            }
            return true;
        }

        // ── HARGA & BERAT (katalog only) ──────────────────────────
        function updateHarga() {
            if (!isProdukKatalog) {
                hargaProdukGlobal = 0;
                beratSatuanGlobal = 0;
                beratTotalGlobal = 0;
                document.getElementById('label_total_produk').innerText = 'Rp 0';
                document.getElementById('label_total_berat').innerText = '0 kg';
                document.getElementById('wrapper_berat_satuan').style.display = 'none';
                document.getElementById('berat_satuan_hidden').value = 0;
                refreshGrandTotal();
                return;
            }

            const sel = document.getElementById('ukuran');
            const opt = sel.options[sel.selectedIndex];
            const bahanNama = (opt && opt.getAttribute('data-bahan')) ? opt.getAttribute('data-bahan') : null;
            const bahanId = (opt && opt.getAttribute('data-bahan-id')) ? opt.getAttribute('data-bahan-id') : null;
            const hargaSatuan = (opt && opt.getAttribute('data-harga')) ? parseInt(opt.getAttribute('data-harga')) : 0;
            beratSatuanGlobal = (opt && opt.getAttribute('data-berat')) ? parseFloat(opt.getAttribute('data-berat')) : 0;
            const qty = parseInt(document.getElementById('input_qty').value) || 1;

            document.getElementById('jenis_marmer').value = bahanNama || '';
            syncSamplePickerByBahanId(bahanId);

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
            document.getElementById('berat_satuan_hidden').value = beratSatuanGlobal;

            refreshGrandTotal();
        }

        // ── METODE & PENGIRIMAN ───────────────────────────────────
        function updateOngkirLabel() {
            document.getElementById('label_ongkir').innerText = 'Akan dipilih di keranjang';
        }

        function refreshGrandTotal() {
            const total = (hargaProdukGlobal || 0);
            document.getElementById('label_grand_total').innerText = 'Rp ' + total.toLocaleString('id-ID');
            const qty = parseInt(document.getElementById('input_qty').value) || 1;
            document.getElementById('harga_satuan_hidden').value = qty > 0 ? Math.round(hargaProdukGlobal / qty) : 0;
            document.getElementById('subtotal_hidden').value = hargaProdukGlobal;
        }

        // ── EVENT LISTENERS ───────────────────────────────────────
        document.getElementById('input_qty').addEventListener('input', updateHarga);

        document.getElementById('orderForm').addEventListener('submit', function(e) {
            if (!isProdukKatalog) {
                if (!composeUkuran()) {
                    e.preventDefault();
                    return;
                }
            }

            return;
        });

        window.onload = function() {
            updateHarga();
            if (!isProdukKatalog) {
                handleCustomBahanChange();
            }
        };
    </script>
@endsection
