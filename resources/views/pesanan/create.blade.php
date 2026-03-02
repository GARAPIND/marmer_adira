@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    :root {
        --adira-gold: #C5A47E;
        --adira-gold-light: rgba(197, 164, 126, 0.1);
        --adira-dark: #34495e;
        --adira-soft-grey: #f8f9fa;
    }
    body { background-color: #fcfcfc; }
    
    .page-header-container {
        background: white; padding: 2.5rem; border-radius: 20px;
        border-bottom: 4px solid var(--adira-gold); box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        margin-bottom: 3rem;
    }

    .icon-box-header {
        width: 70px; height: 70px; background: var(--adira-gold);
        border-radius: 20px; display: flex; align-items: center;
        justify-content: center; color: white; font-size: 2.2rem;
        box-shadow: 0 10px 20px rgba(197, 164, 126, 0.3);
    }

    .form-card-aesthetic {
        border: none; border-radius: 30px; background: #fff;
        box-shadow: 0 15px 50px rgba(0,0,0,0.04); overflow: hidden;
    }
    .form-section-title {
        font-size: 0.9rem; font-weight: 800; letter-spacing: 1.2px;
        color: var(--adira-gold); text-transform: uppercase; margin-bottom: 25px;
        display: flex; align-items: center;
    }
    .form-section-title::after {
        content: ""; flex: 1; height: 1px; background: #eee; margin-left: 15px;
    }
    .label-aesthetic { font-weight: 600; font-size: 0.85rem; color: var(--adira-dark); margin-bottom: 8px; }
    .input-aesthetic {
        border: 1.5px solid #f0f0f0; border-radius: 14px; padding: 0.85rem 1.2rem;
        background-color: var(--adira-soft-grey); transition: all 0.3s ease; font-size: 0.95rem;
    }
    .input-aesthetic:focus {
        border-color: var(--adira-gold); background-color: #fff;
        box-shadow: 0 0 0 4px var(--adira-gold-light); outline: none;
    }
    .shipping-box-highlight {
        background: #fffdfa; border: 2px dashed var(--adira-gold);
        border-radius: 20px; padding: 2rem; margin-top: 1.5rem;
    }
    .btn-submit-elegant {
        background-color: var(--adira-dark); color: white; font-weight: 700;
        letter-spacing: 1px; padding: 1.2rem; border-radius: 18px; border: none;
        transition: all 0.3s ease; box-shadow: 0 10px 20px rgba(52, 73, 94, 0.2);
    }
    .summary-panel {
        background: var(--adira-dark); color: white; border-radius: 20px; padding: 1.5rem;
    }
    .price-tag-display {
        font-size: 1.5rem; color: #ffc107; font-weight: 800;
    }
</style>

<div class="container py-5 mt-2 animate__animated animate__fadeIn">
    <div class="page-header-container d-flex align-items-center">
        <div class="icon-box-header me-4">
            <i class="fa-solid fa-gem"></i>
        </div>
        <div>
            <h2 class="fw-bold mb-0 text-dark">Pemesanan Marmer Custom</h2>
            <p class="text-muted small mb-0">Personalisasi produk marmer Anda dengan standar kualitas premium</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card form-card-aesthetic">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('pesanan.store') }}" method="POST" enctype="multipart/form-data" id="orderForm">
                        @csrf
                        
                        <input type="hidden" name="total_harga" id="total_harga_hidden" value="0">
                        <input type="hidden" name="biaya_pengiriman" id="biaya_pengiriman_hidden" value="0">

                        <div class="row g-5">
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
                                        value="{{ $produkTerpilih ?? '' }}" 
                                        {{ $produkTerpilih ? 'readonly' : '' }} required>
                                </div>

                                <div class="mb-4">
                                    <label class="label-aesthetic">Pilih Ukuran / Dimensi</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted rounded-start-4"><i class="fa-solid fa-maximize"></i></span>
                                        <select name="ukuran" id="ukuran" class="form-select input-aesthetic border-start-0" onchange="updateHarga()" required>
                                            <option value="" disabled selected>-- Pilih Ukuran --</option>
                                            
                                            {{-- PERBAIKAN: Looping Ukuran & Harga dari pengrajin --}}
                                            @if(isset($dataProduk))
                                                @if($dataProduk->ukuran_kecil)
                                                    <option value="{{ $dataProduk->ukuran_kecil }}" data-harga="{{ $dataProduk->harga_kecil }}">
                                                        {{ $dataProduk->ukuran_kecil }}
                                                    </option>
                                                @endif
                                                @if($dataProduk->ukuran_sedang)
                                                    <option value="{{ $dataProduk->ukuran_sedang }}" data-harga="{{ $dataProduk->harga_sedang }}">
                                                        {{ $dataProduk->ukuran_sedang }}
                                                    </option>
                                                @endif
                                                @if($dataProduk->ukuran_besar)
                                                    <option value="{{ $dataProduk->ukuran_besar }}" data-harga="{{ $dataProduk->harga_besar }}">
                                                        {{ $dataProduk->ukuran_besar }}
                                                    </option>
                                                @endif
                                            @else
                                                <option value="" disabled>Data produk tidak ditemukan</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="label-aesthetic">Jenis Bahan Marmer</label>
                                    <select name="jenis_marmer" id="jenis_marmer" class="form-select input-aesthetic" onchange="updateHarga()" required>
                                        <option value="" disabled selected>-- Pilih Material --</option>
                                        @foreach($listBahan as $bahan)
                                            <option value="{{ $bahan->nama_bahan }}">{{ $bahan->nama_bahan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 ps-md-5">
                                <div class="form-section-title">2. Detail Kustomisasi</div>
                                
                                <div class="mb-4">
                                    <label class="label-aesthetic">Catatan Khusus (Warna/Tekstur)</label>
                                    <textarea name="catatan_khusus" class="form-control input-aesthetic" rows="3" placeholder="Gambarkan keinginan detail Anda..."></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="label-aesthetic">Gambar Acuan / Referensi (Penting)</label>
                                    <input type="file" name="gambar_referensi" class="form-control input-aesthetic">
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-6">
                                        <label class="label-aesthetic">Jumlah (Qty)</label>
                                        <input type="number" name="jumlah" id="input_qty" class="form-control input-aesthetic" min="1" value="1" onchange="updateHarga()" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="label-aesthetic">Metode Ambil</label>
                                        <select name="metode_pengambilan" id="metode_pengambilan" class="form-select input-aesthetic" onchange="toggleAlamat()" required>
                                            <option value="dirumah">Ambil di Rumah</option>
                                            <option value="dikirim">Dikirim via Bus</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="form_alamat" style="display: none;" class="animate__animated animate__fadeIn">
                                    <div class="shipping-box-highlight">
                                        <label class="label-aesthetic text-gold"><i class="fa-solid fa-bus me-2"></i>Pilih Terminal Tujuan</label>
                                        <select name="terminal_id" id="terminal_id" class="form-select input-aesthetic border-gold" onchange="hitungOngkirBerat()">
                                            <option value="" data-tarif-per-kg="0" selected disabled>-- Pilih Terminal --</option>
                                            @foreach($listTerminal as $t)
                                                <option value="{{ $t->id }}" data-tarif-per-kg="{{ $t->tarif_per_kg ?? $t->tarif_per_km }}">
                                                    {{ $t->nama_terminal }} (Rp {{ number_format($t->tarif_per_kg ?? $t->tarif_per_km, 0, ',', '.') }}/kg)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="summary-panel mt-4 animate__animated animate__fadeInUp">
                                    <div class="d-flex justify-content-between mb-2 small opacity-75">
                                        <span>Estimasi Berat Total:</span>
                                        <span id="label_total_berat" class="fw-bold">0 kg</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 border-bottom pb-2 border-secondary">
                                        <span class="small opacity-75">Total Produk:</span>
                                        <span class="fw-bold" id="label_total_produk">Rp 0</span>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <span class="small opacity-75">Ongkos Kirim:</span>
                                        <span class="fw-bold text-warning" id="label_ongkir">Rp 0</span>
                                    </div>
                                    <hr class="bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-uppercase" style="letter-spacing: 1px;">Total:</span>
                                        <span class="price-tag-display" id="label_grand_total">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 text-center">
                            <button type="submit" class="btn btn-submit-elegant w-100">
                                <i class="fa-solid fa-circle-check me-2"></i> AJUKAN PESANAN SEKARANG
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const weightMatrix = {
        "Asbak": 0.8,
        "Tempat Sabun": 1.5,
        "Vas Bunga": 5.0,
        "Wastafel": 20.0,
        "Ubin": 10.0,
    };

    let hargaProdukGlobal = 0;
    let beratTotalGlobal = 0;
    let ongkirGlobal = 0;

    function updateHarga() {
        const selectUkuran = document.getElementById('ukuran');
        const selectedOption = selectUkuran.options[selectUkuran.selectedIndex];
        
        // AMBIL HARGA DARI DATA-HARGA OPTION (DIBUAT DINAMIS)
        const hargaSatuan = (selectedOption && selectedOption.getAttribute('data-harga')) ? parseInt(selectedOption.getAttribute('data-harga')) : 0;
        const qty = parseInt(document.getElementById('input_qty').value) || 1;
        const produk = document.getElementById('nama_produk').value || "";

        hargaProdukGlobal = hargaSatuan * qty;
        
        let kategoriBerat = "Asbak"; 
        const p_lower = produk.toLowerCase();
        if(p_lower.includes("sabun")) kategoriBerat = "Tempat Sabun";
        else if(p_lower.includes("vas")) kategoriBerat = "Vas Bunga";
        else if(p_lower.includes("wastafel")) kategoriBerat = "Wastafel";
        else if(p_lower.includes("ubin") || p_lower.includes("lantai")) kategoriBerat = "Ubin";
        
        beratTotalGlobal = (weightMatrix[kategoriBerat] || 2.0) * qty;

        document.getElementById('label_total_produk').innerText = 'Rp ' + hargaProdukGlobal.toLocaleString('id-ID');
        document.getElementById('label_total_berat').innerText = beratTotalGlobal.toFixed(1) + ' kg';
        
        const metode = document.getElementById('metode_pengambilan').value;
        if(metode === 'dikirim') {
            hitungOngkirBerat();
        } else {
            ongkirGlobal = 0;
            document.getElementById('label_ongkir').innerText = 'Rp 0';
            refreshGrandTotal();
        }
    }

    function toggleAlamat() {
        const metode = document.getElementById('metode_pengambilan').value;
        document.getElementById('form_alamat').style.display = (metode === 'dikirim') ? 'block' : 'none';
        
        if (metode !== 'dikirim') {
            ongkirGlobal = 0;
            document.getElementById('label_ongkir').innerText = 'Rp 0';
        } else {
            hitungOngkirBerat();
        }
        refreshGrandTotal();
    }

    function hitungOngkirBerat() {
        const terminalSelect = document.getElementById('terminal_id');
        const selectedOption = terminalSelect.options[terminalSelect.selectedIndex];
        const tarifPerKg = (selectedOption && !selectedOption.disabled) ? parseInt(selectedOption.getAttribute('data-tarif-per-kg')) : 0;
        
        ongkirGlobal = Math.round(beratTotalGlobal * (tarifPerKg || 0));
        document.getElementById('label_ongkir').innerText = 'Rp ' + ongkirGlobal.toLocaleString('id-ID');
        refreshGrandTotal();
    }

    function refreshGrandTotal() {
        const total = (hargaProdukGlobal || 0) + (ongkirGlobal || 0);
        document.getElementById('label_grand_total').innerText = 'Rp ' + total.toLocaleString('id-ID');
        
        document.getElementById('total_harga_hidden').value = hargaProdukGlobal;
        document.getElementById('biaya_pengiriman_hidden').value = ongkirGlobal;
    }

    window.onload = function() {
        updateHarga();
    };
</script>
@endsection