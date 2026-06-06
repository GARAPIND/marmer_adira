@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        :root {
            --adira-gold: #C5A47E;
            --adira-dark: #2c3e50;
        }

        .text-gold {
            color: var(--adira-gold) !important;
        }

        .page-header-elegant {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .marble-icon-box {
            width: 60px;
            height: 60px;
            background: rgba(197, 164, 126, 0.1);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--adira-gold);
            font-size: 1.8rem;
            flex-shrink: 0;
        }

        .card-stat-elegant {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            background: #fff;
        }

        .card-stat-elegant:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .table-elegant thead th {
            background-color: var(--adira-dark);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            padding: 1.25rem;
            border: none;
        }

        .table-elegant tbody td {
            vertical-align: middle;
            padding: 1.2rem 1rem;
            border-bottom: 1px solid #f8f9fa;
        }

        .modal-content-elegant {
            border-radius: 25px;
            border: none;
            overflow: hidden;
        }

        .modal-header-elegant {
            background: var(--adira-dark);
            color: white;
            padding: 1.5rem 2rem;
        }

        .badge-pill-custom {
            padding: 0.5em 1.2em;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
        }

        .btn-gold {
            background-color: var(--adira-gold);
            border: none;
            color: white;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-gold:hover {
            background-color: #b08d44;
            color: white;
        }

        .info-price-box {
            background: #fdfbf8;
            border: 1px solid #e9ecef;
            border-radius: 15px;
            padding: 15px;
        }

        .chart-card {
            background: #fff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 1.75rem;
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.09);
        }

        .chart-card-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0;
        }

        .chart-card-subtitle {
            font-size: 0.75rem;
            color: #adb5bd;
        }

        .chart-icon-box {
            width: 42px;
            height: 42px;
            background: rgba(197, 164, 126, 0.12);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--adira-gold);
            font-size: 1.1rem;
            flex-shrink: 0;
        }
    </style>

    <div class="container py-5 mt-2 animate__animated animate__fadeIn">

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 animate__animated animate__bounceIn">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="page-header-elegant d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="marble-icon-box me-3 shadow-sm">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0 text-dark" style="border-left: 5px solid #000; padding-left: 15px;">
                        Ringkasan Statistik
                    </h2>
                </div>
            </div>
            <span class="badge bg-dark px-4 py-2 rounded-pill shadow-sm fw-bold">ADMIN PANEL</span>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card card-stat-elegant p-4 shadow-sm border-start border-4 border-primary">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="small fw-bold text-uppercase text-muted mb-0">Pesanan Baru</p>
                        <i class="fas fa-shopping-basket text-primary opacity-50"></i>
                    </div>
                    <h2 class="fw-bold m-0 text-dark">{{ $stats['baru'] }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat-elegant p-4 shadow-sm border-start border-4 border-warning">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="small fw-bold text-uppercase text-muted mb-0">Diverifikasi</p>
                        <i class="fas fa-check-double text-warning opacity-50"></i>
                    </div>
                    <h2 class="fw-bold m-0 text-dark">{{ $stats['diproses'] }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat-elegant p-4 shadow-sm border-start border-4 border-success">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="small fw-bold text-uppercase text-muted mb-0">Selesai</p>
                        <i class="fas fa-clipboard-check text-success opacity-50"></i>
                    </div>
                    <h2 class="fw-bold m-0 text-dark">{{ $stats['selesai'] }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat-elegant p-4 shadow-sm border-start border-4 border-info">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="small fw-bold text-uppercase text-muted mb-0">Total Pendapatan</p>
                        <i class="fas fa-wallet text-info opacity-50"></i>
                    </div>
                    <h4 class="fw-bold m-0 text-info">Rp {{ number_format($stats['total_bayar'], 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">

            <div class="col-lg-8">
                <div class="chart-card">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="chart-icon-box">
                            <i class="fas fa-chart-area"></i>
                        </div>
                        <div>
                            <p class="chart-card-title">Pendapatan Harian</p>
                            <p class="chart-card-subtitle mb-0">7 hari terakhir</p>
                        </div>
                    </div>
                    <div id="chart-pendapatan"></div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="chart-card">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="chart-icon-box">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div>
                            <p class="chart-card-title">Produk Terlaku</p>
                            <p class="chart-card-subtitle mb-0">Berdasarkan jumlah terjual</p>
                        </div>
                    </div>
                    <div id="chart-produk"></div>
                </div>
            </div>

        </div>

    </div>

    <script>
        let activeAdminOrder = null;

        function getAdminCsrf() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        function formatAdminCurrency(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(Number(value || 0));
        }

        function parseDecimalInput(value) {
            return parseFloat(String(value ?? '').replace(',', '.'));
        }

        function updateAdminTotals() {
            const harga = parseInt(document.getElementById('input_harga').value || 0, 10) || 0;
            const ongkir = parseInt(document.getElementById('input_ongkir').value || 0, 10) || 0;
            document.getElementById('display_harga_produk').innerText = formatAdminCurrency(harga);
            document.getElementById('display_ongkir_admin').innerText = formatAdminCurrency(ongkir);
            document.getElementById('display_total_seluruh').innerText = formatAdminCurrency(harga + ongkir);
        }

        async function recalculateAdminShipping() {
            const hint = document.getElementById('ongkir_calc_hint');
            const inputOngkir = document.getElementById('input_ongkir');

            if (!activeAdminOrder || activeAdminOrder.metode_pengambilan !== 'dikirim') {
                hint.innerText = '';
                updateAdminTotals();
                return;
            }

            const beratSatuan = parseDecimalInput(document.getElementById('input_berat_satuan').value);
            if (!Number.isFinite(beratSatuan) || beratSatuan <= 0) {
                inputOngkir.value = 0;
                hint.innerText = 'Isi berat satuan agar ongkir bisa dihitung otomatis.';
                updateAdminTotals();
                return;
            }

            hint.innerText = 'Menghitung ongkir otomatis...';

            try {
                const response = await fetch(`/admin/pesanan/${activeAdminOrder.id}/hitung-ongkir`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': getAdminCsrf(),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        berat_satuan: document.getElementById('input_berat_satuan').value
                    }),
                });

                const json = await response.json();
                if (!response.ok) throw new Error(json.message || 'Gagal menghitung ongkir otomatis.');

                inputOngkir.value = parseInt(json.biaya_pengiriman || 0, 10) || 0;

                const calc = json.calculation || {};
                if (calc.jenis_pengiriman === 'cargo') {
                    const service = calc.service ? ` ${calc.service}` : '';
                    hint.innerText =
                        `Otomatis via ${calc.courier || 'cargo'}${service}. Berat total ${Number(json.total_berat || 0).toFixed(2)} kg.`;
                } else if (calc.jenis_pengiriman === 'bus') {
                    hint.innerText =
                        `Otomatis via bus ${calc.terminal || ''}: ${Number(json.total_berat || 0).toFixed(2)} kg x Rp ${(calc.tarif_per_kg || 0).toLocaleString('id-ID')}/kg.`;
                } else {
                    hint.innerText = json.summary || 'Ongkir dihitung otomatis.';
                }

                updateAdminTotals();
            } catch (error) {
                inputOngkir.value = parseInt(inputOngkir.value || 0, 10) || 0;
                hint.innerText = error.message;
                updateAdminTotals();
            }
        }

        function showAdminDetail(data) {
            activeAdminOrder = data;
            const modal = new bootstrap.Modal(document.getElementById('modalDetailAdmin'));
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });

            document.getElementById('md-id').innerText = 'ORD-' + data.id.toString().padStart(3, '0');
            document.getElementById('md-nama').innerText = data.user.name;
            document.getElementById('md-telp').innerText = 'WA: ' + (data.user.no_telp || '-');
            document.getElementById('md-produk').innerText = data.nama_produk;
            document.getElementById('md-info').innerText = data.ukuran + ' | Qty: ' + data.jumlah;
            document.getElementById('md-catatan').innerText = data.catatan_khusus || 'Tidak ada catatan tambahan.';
            document.getElementById('input_alasan').value = data.alasan_penolakan || '';

            setTimeout(() => {
                toggleAlasan();
            }, 100);

            const gambarContainer = document.getElementById('md-gambar-container');
            if (data.gambar_referensi) {
                gambarContainer.innerHTML =
                    `<a href="/storage/${data.gambar_referensi}" target="_blank">
                        <img src="/storage/${data.gambar_referensi}" class="img-fluid rounded-3" style="max-height:200px;cursor:zoom-in;">
                    </a>`;
            } else {
                gambarContainer.innerHTML = '<p class="text-muted small mb-0 py-3">Tidak ada gambar referensi.</p>';
            }

            const alamatSection = document.getElementById('md-alamat-section');
            const ongkirRow = document.getElementById('display_ongkir_row');
            const ongkirInputGroup = document.getElementById('group_ongkir_input');
            const inputHarga = document.getElementById('input_harga');
            const inputOngkir = document.getElementById('input_ongkir');
            const inputBerat = document.getElementById('input_berat_satuan');
            const ongkirHint = document.getElementById('ongkir_calc_hint');

            if (data.metode_pengambilan === 'dikirim') {
                alamatSection.style.display = 'block';
                document.getElementById('md-alamat-text').innerText = data.alamat_pengiriman;
                ongkirRow.classList.remove('d-none');
                ongkirInputGroup.classList.remove('d-none');
                ongkirHint.innerText = 'Isi atau ubah berat satuan untuk menghitung ongkir otomatis.';
            } else {
                alamatSection.style.display = 'none';
                ongkirRow.classList.add('d-none');
                ongkirInputGroup.classList.add('d-none');
                ongkirHint.innerText = '';
            }

            const hrgProduk = parseInt(data.total_harga || 0);
            const hrgOngkir = parseInt(data.biaya_pengiriman || 0);

            document.getElementById('display_harga_produk').innerText = formatter.format(hrgProduk);
            document.getElementById('display_ongkir_admin').innerText = formatter.format(hrgOngkir);
            document.getElementById('display_total_seluruh').innerText = formatter.format(hrgProduk + hrgOngkir);

            inputHarga.value = hrgProduk > 0 ? hrgProduk : '';
            inputOngkir.value = hrgOngkir > 0 ? hrgOngkir : 0;
            inputBerat.value = (parseFloat(data.berat_satuan || 0) > 0) ? parseFloat(data.berat_satuan) : '';

            const statusSelect = document.getElementById('select_status');
            statusSelect.value = data.status === 'Ditolak' ? 'Ditolak' : 'Diverifikasi';

            const alasanForm = document.getElementById('form_alasan');

            function toggleAlasan() {
                statusSelect.value === 'Ditolak' ?
                    alasanForm.classList.remove('d-none') :
                    alasanForm.classList.add('d-none');
            }

            statusSelect.addEventListener('change', toggleAlasan);
            document.getElementById('formUpdatePesanan').action = `/admin/pesanan/${data.id}/update`;
            modal.show();

            const verificationForm = document.getElementById('form_verifikasi');
            const submitButton = document.getElementById('btn_submit');

            if (data.status === 'Menunggu Verifikasi Admin') {
                verificationForm.classList.remove('d-none');
                submitButton.classList.remove('d-none');
                inputHarga.readOnly = inputOngkir.readOnly = inputBerat.readOnly = false;
            } else {
                verificationForm.classList.add('d-none');
                submitButton.classList.add('d-none');
                inputHarga.readOnly = inputOngkir.readOnly = inputBerat.readOnly = true;
            }

            inputHarga.oninput = updateAdminTotals;
            inputOngkir.oninput = updateAdminTotals;
            inputBerat.oninput = recalculateAdminShipping;

            updateAdminTotals();

            if (data.metode_pengambilan === 'dikirim' && parseDecimalInput(inputBerat.value) > 0) {
                recalculateAdminShipping();
            }
        }

        const pendapatanHarian = @json($stats['pendapatan_harian']);
        const produkTerlaris = @json($stats['produk_terlaris']);

        (function() {
            const today = new Date();
            const labels = [];
            const dataMap = {};

            for (let i = 6; i >= 0; i--) {
                const d = new Date(today);
                d.setDate(today.getDate() - i);
                const key = d.toISOString().slice(0, 10);
                labels.push(key);
                dataMap[key] = 0;
            }

            pendapatanHarian.forEach(function(row) {
                const key = row.tanggal ? String(row.tanggal).slice(0, 10) : null;
                if (key && Object.prototype.hasOwnProperty.call(dataMap, key)) {
                    dataMap[key] = parseFloat(row.total_pendapatan) || 0;
                }
            });

            const values = labels.map(function(k) {
                return dataMap[k];
            });
            const formattedLabels = labels.map(function(k) {
                return new Date(k + 'T00:00:00').toLocaleDateString('id-ID', {
                    weekday: 'short',
                    day: 'numeric',
                    month: 'short'
                });
            });

            new ApexCharts(document.getElementById('chart-pendapatan'), {
                chart: {
                    type: 'area',
                    height: 290,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    fontFamily: 'inherit',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                    },
                },
                series: [{
                    name: 'Pendapatan',
                    data: values
                }],
                xaxis: {
                    categories: formattedLabels,
                    labels: {
                        style: {
                            fontSize: '11px',
                            colors: '#9aa3ad'
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(1) + 'jt';
                            if (val >= 1000) return 'Rp ' + (val / 1000).toFixed(0) + 'rb';
                            return 'Rp ' + val;
                        },
                        style: {
                            fontSize: '10px',
                            colors: '#9aa3ad'
                        },
                    },
                },
                colors: ['#C5A47E'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.01,
                        stops: [0, 100],
                    },
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                dataLabels: {
                    enabled: false
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return 'Rp ' + Number(val).toLocaleString('id-ID');
                        },
                    },
                    theme: 'light',
                },
                grid: {
                    borderColor: '#f4f4f4',
                    strokeDashArray: 5,
                    padding: {
                        left: 8,
                        right: 8
                    },
                },
                markers: {
                    size: 4,
                    colors: ['#C5A47E'],
                    strokeColors: '#fff',
                    strokeWidth: 2,
                    hover: {
                        size: 6
                    },
                },
            }).render();
        })();

        (function() {
            if (!produkTerlaris || !produkTerlaris.length) {
                document.getElementById('chart-produk').innerHTML =
                    '<p class="text-center text-muted small py-5">Belum ada data produk.</p>';
                return;
            }

            // Ambil top 6
            const top = produkTerlaris.slice(0, 6);
            const labels = top.map(function(p) {
                return p.nama_produk;
            });
            const values = top.map(function(p) {
                return parseInt(p.total_qty) || 0;
            });

            const palette = ['#C5A47E', '#2c3e50', '#e67e22', '#27ae60', '#2980b9', '#8e44ad'];

            new ApexCharts(document.getElementById('chart-produk'), {
                chart: {
                    type: 'donut',
                    height: 290,
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'inherit',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                    },
                },
                series: values,
                labels: labels,
                colors: palette,
                legend: {
                    position: 'bottom',
                    fontSize: '11px',
                    itemMargin: {
                        horizontal: 4,
                        vertical: 4
                    },
                    formatter: function(label, opts) {
                        return label + ' (' + opts.w.globals.series[opts.seriesIndex] + ')';
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return Math.round(val) + '%';
                    },
                    style: {
                        fontSize: '11px',
                        fontWeight: '600',
                        colors: ['#fff']
                    },
                    dropShadow: {
                        enabled: false
                    },
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'Total',
                                    fontSize: '12px',
                                    color: '#6c757d',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce(function(a, b) {
                                            return a + b;
                                        }, 0) + ' terjual';
                                    },
                                },
                                value: {
                                    fontSize: '18px',
                                    fontWeight: '700',
                                    color: '#2c3e50',
                                },
                            },
                        },
                    },
                },
                stroke: {
                    width: 2,
                    colors: ['#fff']
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + ' terjual';
                        }
                    },
                    theme: 'light',
                },
            }).render();
        })();
    </script>
@endsection
