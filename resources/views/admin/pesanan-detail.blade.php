@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --adira-gold: #C5A47E;
            --adira-dark: #2c3e50;
        }

        .page-shell {
            background: white;
            border-radius: 24px;
            box-shadow: 0 18px 44px rgba(44, 62, 80, 0.08);
            overflow: hidden;
        }

        .page-head {
            padding: 2rem;
            background: linear-gradient(135deg, #2c3e50, #1f2b38);
            color: white;
        }

        .info-card {
            border: 1px solid rgba(44, 62, 80, 0.08);
            border-radius: 18px;
            padding: 16px;
            background: #fff;
            height: 100%;
        }

        .info-label {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #7b8794;
            margin-bottom: 6px;
        }

        .order-table th {
            background: var(--adira-dark);
            color: white;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: none;
            vertical-align: middle;
        }

        .order-table td {
            vertical-align: top;
        }

        .item-thumb-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(44, 62, 80, 0.15);
            border-radius: 999px;
            padding: 6px 12px;
            text-decoration: none;
            color: var(--adira-dark);
            font-size: 0.8rem;
            font-weight: 700;
        }

        .summary-box {
            background: #fcfbf8;
            border: 1px solid rgba(197, 164, 126, 0.25);
            border-radius: 18px;
            padding: 18px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 8px;
            color: #6c757d;
        }

        .summary-row strong {
            color: var(--adira-dark);
        }

        .reference-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
        }

        .reference-grid a {
            display: block;
        }

        .reference-grid img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 14px;
            border: 1px solid #eee;
        }
    </style>

    @php
        $isVerifiable = $pesanan->status === 'Menunggu Verifikasi Admin';
        $defaultItemPayload = $pesanan->items
            ->map(fn($item) => ['id' => $item->id, 'berat_satuan' => $item->berat_satuan ?? 0])
            ->values();
    @endphp

    <div class="container py-5 mt-2">
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-4">{{ $errors->first() }}</div>
        @endif

        <div class="page-shell">
            <div class="page-head d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="small text-white-50 mb-2">Verifikasi Pesanan</div>
                    <h2 class="fw-bold mb-1">ORD-{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}</h2>
                    <div class="text-white-50">{{ $pesanan->user->name }} | {{ $pesanan->created_at->format('d M Y H:i') }}</div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.pesanan.baru') }}" class="btn btn-light rounded-pill px-4 fw-bold">Kembali</a>
                </div>
            </div>

            <div class="p-4 p-lg-5">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label">Pembeli</div>
                            <div class="fw-bold text-dark">{{ $pesanan->user->name }}</div>
                            <div class="text-muted small">{{ $pesanan->user->email }}</div>
                            <div class="text-muted small">{{ $pesanan->user->no_telp ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label">Pengiriman</div>
                            <div class="fw-bold text-dark">{{ $pesanan->metode_pengambilan === 'dikirim' ? 'Dikirim' : 'Ambil di Tempat' }}</div>
                            <div class="text-muted small">{{ $pesanan->alamat_pengiriman ?? 'Tidak ada alamat kirim' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label">Status</div>
                            <div class="fw-bold text-dark">{{ $pesanan->status }}</div>
                            <div class="text-muted small">
                                {{ $pesanan->status_pembayaran === 'paid' ? 'Lunas' : ($pesanan->status_pembayaran === 'dp' ? 'DP 50%' : 'Belum Bayar') }}
                            </div>
                            @if ($pesanan->kode_resi_internal)
                                <div class="text-muted small mt-2">Resi Internal: {{ $pesanan->kode_resi_internal }}</div>
                            @endif
                            @if ($pesanan->nomor_resi_pengiriman)
                                <div class="text-muted small">Resi Cargo: {{ $pesanan->nomor_resi_pengiriman }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.pesanan.update', $pesanan->id) }}" id="formVerifikasiPesanan">
                    @csrf

                    <div class="table-responsive mb-4">
                        <table class="table order-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tipe</th>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th>Berat Satuan</th>
                                    <th>Total Berat</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                    <th>Referensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pesanan->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge {{ $item->is_custom ? 'bg-warning text-dark' : 'bg-dark' }}">
                                                {{ $item->is_custom ? 'Custom' : 'Katalog' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->nama_produk }}</div>
                                            <div class="small text-muted">{{ $item->ukuran }} | {{ $item->jenis_marmer }}</div>
                                            @if ($item->catatan_khusus)
                                                <div class="small fst-italic text-secondary mt-1">{{ $item->catatan_khusus }}</div>
                                            @endif
                                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                        </td>
                                        <td>
                                            <span class="fw-bold item-qty" data-qty="{{ $item->jumlah }}">{{ $item->jumlah }}</span>
                                        </td>
                                        <td style="min-width: 130px;">
                                            <input type="number" step="0.01" min="0"
                                                class="form-control item-berat"
                                                name="items[{{ $index }}][berat_satuan]"
                                                value="{{ old("items.$index.berat_satuan", $item->berat_satuan) }}">
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark item-total-berat">{{ number_format($item->total_berat ?? 0, 2, ',', '.') }}</span>
                                            <div class="small text-muted">kg</div>
                                        </td>
                                        <td style="min-width: 150px;">
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" min="0"
                                                    class="form-control item-harga"
                                                    name="items[{{ $index }}][harga_satuan]"
                                                    value="{{ old("items.$index.harga_satuan", $item->harga_satuan) }}">
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark item-subtotal">Rp {{ number_format($item->subtotal ?? 0, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            @php $images = is_array($item->gambar_referensi) ? $item->gambar_referensi : []; @endphp
                                            @if (count($images))
                                                <button type="button"
                                                    class="btn btn-outline-dark btn-sm rounded-pill btn-open-images"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalReferensi"
                                                    data-product="{{ $item->nama_produk }}"
                                                    data-images='@json($images)'>
                                                    Lihat Foto ({{ count($images) }})
                                                </button>
                                            @else
                                                <span class="text-muted small">Tidak ada</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-7">
                            <div class="info-card">
                                <div class="info-label">Keputusan Admin</div>
                                <div class="mb-3">
                                    <select name="status" id="statusPesanan" class="form-select">
                                        <option value="Diverifikasi" {{ old('status', $pesanan->status) === 'Diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                                        <option value="Ditolak" {{ old('status', $pesanan->status) === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                                <div id="wrapperAlasan" class="{{ old('status') === 'Ditolak' ? '' : 'd-none' }}">
                                    <label class="form-label small fw-bold">Alasan Penolakan</label>
                                    <textarea name="alasan_penolakan" rows="4" class="form-control">{{ old('alasan_penolakan', $pesanan->alasan_penolakan) }}</textarea>
                                </div>
                            </div>

                            @if ($pesanan->metode_pengambilan === 'dikirim' && in_array($pesanan->status, ['Siap Dikirim', 'diekspedisi']))
                                <div class="info-card mt-4">
                                    <div class="info-label">Resi & Pengiriman Cargo</div>
                                    <div class="small text-muted mb-3">
                                        Sistem akan generate resi internal untuk label cetak. Nomor resi cargo resmi diisi setelah paket benar-benar diserahkan ke ekspedisi.
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <a href="{{ route('admin.pesanan.resi', $pesanan->id) }}" target="_blank"
                                            class="btn btn-outline-dark rounded-pill px-4 fw-bold">
                                            Cetak Resi
                                        </a>
                                    </div>

                                    @if ($pesanan->status === 'Siap Dikirim')
                                        <form method="POST" action="{{ route('admin.pesanan.kirim', $pesanan->id) }}">
                                            @csrf
                                            <label class="form-label small fw-bold">Nomor Resi Cargo Resmi</label>
                                            <input type="text" name="nomor_resi_pengiriman" class="form-control mb-3"
                                                value="{{ old('nomor_resi_pengiriman', $pesanan->nomor_resi_pengiriman) }}"
                                                placeholder="Contoh: JNECARGO-00123456789">
                                            <button type="submit" class="btn btn-dark rounded-pill px-4 fw-bold">
                                                Kirim Pesanan
                                            </button>
                                        </form>
                                    @else
                                        <div class="alert alert-success border-0 mb-0">
                                            Pesanan sudah dikirim ke cargo.
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-5">
                            <div class="summary-box">
                                <div class="info-label">Ringkasan Hitungan</div>
                                <div class="summary-row">
                                    <span>Total Berat</span>
                                    <strong id="summaryTotalBerat">{{ number_format($pesanan->total_berat ?? 0, 2, ',', '.') }} kg</strong>
                                </div>
                                <div class="summary-row">
                                    <span>Total Produk</span>
                                    <strong id="summaryTotalProduk">Rp {{ number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}</strong>
                                </div>
                                <div class="summary-row">
                                    <span>Ongkir</span>
                                    <strong>
                                        <input type="number" min="0" name="biaya_pengiriman" id="inputOngkir"
                                            class="form-control mt-2"
                                            value="{{ old('biaya_pengiriman', $pesanan->biaya_pengiriman ?? 0) }}">
                                    </strong>
                                </div>
                                <div class="summary-row">
                                    <span>Grand Total</span>
                                    <strong id="summaryGrandTotal">Rp 0</strong>
                                </div>
                                @if ($pesanan->metode_pengambilan === 'dikirim')
                                    <button type="button" class="btn btn-outline-dark w-100 rounded-pill mt-3" id="btnHitungOngkir">
                                        Hitung Ongkir Otomatis
                                    </button>
                                    <div id="ongkirHint" class="small text-muted mt-2"></div>
                                @endif
                                @if ($isVerifiable)
                                    <button type="submit" class="btn btn-dark w-100 rounded-pill mt-4 py-3 fw-bold">
                                        Simpan Verifikasi Pesanan
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalReferensi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0">
                    <div>
                        <h5 class="modal-title fw-bold mb-1" id="referensiTitle">Referensi Item</h5>
                        <div class="small text-muted">Foto acuan item pesanan</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="referensiGrid" class="reference-grid"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatRupiah(value) {
            return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
        }

        function recalcAdminTotals() {
            let totalProduk = 0;
            let totalBerat = 0;

            document.querySelectorAll('.order-table tbody tr').forEach((row) => {
                const qty = parseInt(row.querySelector('.item-qty')?.dataset.qty || '0', 10);
                const beratSatuan = parseFloat(row.querySelector('.item-berat')?.value || '0');
                const hargaSatuan = parseInt(row.querySelector('.item-harga')?.value || '0', 10);
                const subtotal = qty * (Number.isFinite(hargaSatuan) ? hargaSatuan : 0);
                const itemTotalBerat = qty * (Number.isFinite(beratSatuan) ? beratSatuan : 0);

                row.querySelector('.item-subtotal').innerText = formatRupiah(subtotal);
                row.querySelector('.item-total-berat').innerText = itemTotalBerat.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                totalProduk += subtotal;
                totalBerat += itemTotalBerat;
            });

            const ongkir = parseInt(document.getElementById('inputOngkir')?.value || '0', 10);
            document.getElementById('summaryTotalProduk').innerText = formatRupiah(totalProduk);
            document.getElementById('summaryTotalBerat').innerText = totalBerat.toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + ' kg';
            document.getElementById('summaryGrandTotal').innerText = formatRupiah(totalProduk + (Number.isFinite(ongkir) ? ongkir : 0));
        }

        async function hitungOngkirOtomatis() {
            const hint = document.getElementById('ongkirHint');
            const items = Array.from(document.querySelectorAll('.order-table tbody tr')).map((row) => ({
                id: row.querySelector('input[name$="[id]"]').value,
                berat_satuan: row.querySelector('.item-berat')?.value || 0,
            }));

            hint.innerText = 'Menghitung ongkir otomatis...';

            const response = await fetch(@json(route('admin.pesanan.hitung-ongkir', $pesanan->id)), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ items }),
            });

            const data = await response.json();
            if (!response.ok) {
                hint.innerText = data.message || 'Gagal menghitung ongkir.';
                return;
            }

            document.getElementById('inputOngkir').value = data.biaya_pengiriman || 0;
            hint.innerText = data.summary || 'Ongkir berhasil dihitung.';
            recalcAdminTotals();
        }

        document.addEventListener('DOMContentLoaded', function() {
            recalcAdminTotals();

            document.querySelectorAll('.item-berat, .item-harga, #inputOngkir').forEach((input) => {
                input.addEventListener('input', recalcAdminTotals);
            });

            const statusPesanan = document.getElementById('statusPesanan');
            const wrapperAlasan = document.getElementById('wrapperAlasan');
            statusPesanan?.addEventListener('change', () => {
                wrapperAlasan.classList.toggle('d-none', statusPesanan.value !== 'Ditolak');
            });

            document.getElementById('btnHitungOngkir')?.addEventListener('click', hitungOngkirOtomatis);

            document.querySelectorAll('.btn-open-images').forEach((button) => {
                button.addEventListener('click', function() {
                    const images = JSON.parse(this.dataset.images || '[]');
                    document.getElementById('referensiTitle').innerText = this.dataset.product || 'Referensi Item';
                    document.getElementById('referensiGrid').innerHTML = images.map((img) =>
                        `<a href="/storage/${img}" target="_blank"><img src="/storage/${img}" alt="Referensi item"></a>`
                    ).join('');
                });
            });
        });
    </script>
@endsection
