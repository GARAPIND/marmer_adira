@php
    $formatter = new \NumberFormatter('id_ID', \NumberFormatter::CURRENCY);
    $totalAkhir = intval($item->total_harga) + intval($item->biaya_pengiriman ?? 0);
    $fotosDikerjakan = is_array($item->foto_dikerjakan)
        ? $item->foto_dikerjakan
        : json_decode($item->foto_dikerjakan, true) ?? [];
    $fotosSelesai = is_array($item->foto_selesai) ? $item->foto_selesai : json_decode($item->foto_selesai, true) ?? [];
@endphp

<div class="acc-block">
    <label>Produk &amp; Material</label>
    <div class="val">{{ $item->nama_produk }}</div>
    <div class="val-muted">Material: {{ $item->jenis_marmer ?? 'Teraso' }}</div>
</div>

@if ($item->relationLoaded('items') && $item->items->count())
    <div class="acc-block" style="grid-column: span 2;">
        <label>Daftar Item Pesanan</label>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0 bg-white rounded-3 overflow-hidden">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Ukuran</th>
                        <th>Bahan</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item->items as $detailItem)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $detailItem->nama_produk }}</div>
                                @if ($detailItem->catatan_khusus)
                                    <div class="small fst-italic text-muted">{{ $detailItem->catatan_khusus }}</div>
                                @endif
                            </td>
                            <td>{{ $detailItem->ukuran }}</td>
                            <td>{{ $detailItem->jenis_marmer }}</td>
                            <td>{{ $detailItem->jumlah }}</td>
                            <td>Rp {{ number_format($detailItem->subtotal ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<div class="acc-block">
    <label>Jumlah</label>
    <div class="val">{{ $item->jumlah }} Pcs</div>
</div>

<div class="acc-block">
    <label>Catatan Kustom</label>
    <div class="val-muted fst-italic">{{ $item->catatan_khusus ?? 'Tidak ada catatan tambahan.' }}</div>
    @if (!empty($item->foto_sampel_terpilih))
        <div class="mt-2">
            <img src="{{ $item->foto_sampel_terpilih }}" alt="Sampel terpilih"
                style="width:68px;height:68px;object-fit:cover;border-radius:12px;border:2px solid #C5A47E;">
        </div>
    @endif
</div>

<div class="acc-block">
    <label>Pengiriman</label>
    @if ($item->metode_pengambilan === 'dikirim')
        <div class="val">Dikirim ({{ strtoupper($item->jenis_pengiriman ?? 'Pengiriman') }})</div>
        <div class="val-muted">Tujuan: {{ $item->alamat_pengiriman ?? '-' }}</div>
        @if (!empty($item->nomor_resi_pengiriman))
            <div class="val-muted mt-1">Resi Cargo: <strong>{{ $item->nomor_resi_pengiriman }}</strong></div>
        @endif
    @else
        <div class="val">Ambil di Rumah</div>
    @endif
</div>

@if ($item->estimasi_selesai)
    <div class="acc-block">
        <label>Estimasi Selesai</label>
        <div class="val">
            {{ \Carbon\Carbon::parse($item->estimasi_selesai)->locale('id')->isoFormat('D MMMM YYYY') }}
        </div>
    </div>
@endif

<div class="price-box">
    <div class="d-flex align-items-center mb-2">
        <i class="fas fa-receipt me-2 text-gold"></i>
        <span class="small fw-bold text-uppercase" style="color:var(--adira-gold);letter-spacing:0.7px;">Rincian
            Pembayaran</span>
    </div>

    <div class="price-row">
        <span>Harga Produk</span>
        <span class="pval">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</span>
    </div>

    @if ($item->metode_pengambilan === 'dikirim')
        <div class="price-row">
            <span class="text-danger">Ongkos Kirim</span>
            <span class="pval text-danger">Rp {{ number_format($item->biaya_pengiriman ?? 0, 0, ',', '.') }}</span>
        </div>
    @endif

    <div class="price-row total-row">
        <span>Total Pembayaran</span>
        @if ($item->status === 'Menunggu Verifikasi Admin')
            <span class="pval text-muted" style="font-size:0.85rem;">Menunggu verifikasi</span>
        @else
            <span class="pval">Rp {{ number_format($totalAkhir, 0, ',', '.') }}</span>
        @endif
    </div>

    <hr class="my-2 border-secondary opacity-25">

    <div class="price-row">
        <span>Metode Bayar</span>
        <span class="pval">{{ strtoupper($item->midtrans_bank ?? ($item->midtrans_payment_type ?? '-')) }}</span>
    </div>
    <div class="price-row">
        <span>Status Pembayaran</span>
        <span class="pval">
            {{ $item->status_pembayaran === 'paid' ? 'Lunas' : ($item->status_pembayaran === 'dp' ? 'Dibayar DP' : 'Belum Bayar') }}
        </span>
    </div>
    <div class="price-row">
        <span>Waktu Bayar Pertama</span>
        <span
            class="pval">{{ $item->tanggal_bayar ? \Carbon\Carbon::parse($item->tanggal_bayar)->locale('id')->isoFormat('D MMM YYYY, HH:mm') : '-' }}</span>
    </div>
    <div class="price-row">
        <span>Waktu Pelunasan</span>
        <span
            class="pval">{{ $item->tanggal_lunas ? \Carbon\Carbon::parse($item->tanggal_lunas)->locale('id')->isoFormat('D MMM YYYY, HH:mm') : '-' }}</span>
    </div>

    <div id="acc-{{ $item->id }}-label" class="mt-3"></div>
</div>

<div class="acc-block" style="grid-column: span 2;">
    <label>Riwayat Pembayaran</label>
    <div id="acc-{{ $item->id }}-history" class="p-2 bg-light rounded-3 small text-muted">
        Belum ada riwayat pembayaran.
    </div>
</div>

@if (count($fotosDikerjakan))
    <div class="acc-block">
        <label>Foto Saat Dikerjakan</label>
        <div class="progress-photo-grid">
            @foreach ($fotosDikerjakan as $foto)
                <a href="/storage/{{ $foto }}" target="_blank">
                    <img src="/storage/{{ $foto }}" alt="Foto progres">
                </a>
            @endforeach
        </div>
    </div>
@endif

@if (count($fotosSelesai))
    <div class="acc-block">
        <label>Foto Saat Selesai</label>
        <div class="progress-photo-grid">
            @foreach ($fotosSelesai as $foto)
                <a href="/storage/{{ $foto }}" target="_blank">
                    <img src="/storage/{{ $foto }}" alt="Foto selesai">
                </a>
            @endforeach
        </div>
    </div>
@endif
