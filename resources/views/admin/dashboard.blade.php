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
            box-shadow: 0 4px 15px rgba(0, 0, 0, .05);
            margin-bottom: 2rem;
        }

        .marble-icon-box {
            width: 60px;
            height: 60px;
            background: rgba(197, 164, 126, .1);
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
            transition: all .3s ease;
            background: #fff;
        }

        .card-stat-elegant:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .1) !important;
        }

        .chart-card {
            background: #fff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .05);
            padding: 1.75rem;
            transition: all .3s ease;
        }

        .chart-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, .09);
        }

        .chart-card-title {
            font-size: .95rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0;
        }

        .chart-card-subtitle {
            font-size: .75rem;
            color: #adb5bd;
        }

        .chart-icon-box {
            width: 42px;
            height: 42px;
            background: rgba(197, 164, 126, .12);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--adira-gold);
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .periode-btn {
            border: 1.5px solid #dee2e6;
            background: #fff;
            color: #6c757d;
            padding: .4rem 1rem;
            border-radius: 50px;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s ease;
        }

        .periode-btn:hover {
            border-color: var(--adira-gold);
            color: var(--adira-gold);
        }

        .periode-btn.active {
            background: var(--adira-gold);
            border-color: var(--adira-gold);
            color: #fff;
            box-shadow: 0 4px 12px rgba(197, 164, 126, .35);
        }

        .stat-skeleton {
            animation: shimmer 1.2s infinite;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            border-radius: 8px;
            height: 36px;
            width: 120px;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0
            }

            100% {
                background-position: -200% 0
            }
        }
    </style>

    <div class="container py-5 mt-2 animate__animated animate__fadeIn">

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 animate__animated animate__bounceIn">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="page-header-elegant d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <div class="marble-icon-box me-3 shadow-sm">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0 text-dark" style="border-left:5px solid #000;padding-left:15px;">
                        Ringkasan Statistik
                    </h2>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                {{-- Filter Periode --}}
                <div class="d-flex gap-2" id="periode-filter">
                    <button class="periode-btn {{ $stats['periode'] === 'hari' ? 'active' : '' }}" data-periode="hari">Hari
                        Ini</button>
                    <button class="periode-btn {{ $stats['periode'] === 'minggu' ? 'active' : '' }}"
                        data-periode="minggu">Minggu Ini</button>
                    <button class="periode-btn {{ $stats['periode'] === 'bulan' ? 'active' : '' }}"
                        data-periode="bulan">Bulan Ini</button>
                    <button class="periode-btn {{ $stats['periode'] === 'tahun' ? 'active' : '' }}"
                        data-periode="tahun">Tahun Ini</button>
                </div>
                <span class="badge bg-dark px-4 py-2 rounded-pill shadow-sm fw-bold">ADMIN PANEL</span>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card card-stat-elegant p-4 shadow-sm border-start border-4 border-primary">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="small fw-bold text-uppercase text-muted mb-0">Pesanan Baru</p>
                        <i class="fas fa-shopping-basket text-primary opacity-50"></i>
                    </div>
                    <h2 class="fw-bold m-0 text-dark" id="stat-baru">{{ $stats['baru'] }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat-elegant p-4 shadow-sm border-start border-4 border-warning">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="small fw-bold text-uppercase text-muted mb-0">Diverifikasi</p>
                        <i class="fas fa-check-double text-warning opacity-50"></i>
                    </div>
                    <h2 class="fw-bold m-0 text-dark" id="stat-diproses">{{ $stats['diproses'] }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat-elegant p-4 shadow-sm border-start border-4 border-success">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="small fw-bold text-uppercase text-muted mb-0">Selesai</p>
                        <i class="fas fa-clipboard-check text-success opacity-50"></i>
                    </div>
                    <h2 class="fw-bold m-0 text-dark" id="stat-selesai">{{ $stats['selesai'] }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat-elegant p-4 shadow-sm border-start border-4 border-info">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="small fw-bold text-uppercase text-muted mb-0">Total Pendapatan</p>
                        <i class="fas fa-wallet text-info opacity-50"></i>
                    </div>
                    <h4 class="fw-bold m-0 text-info" id="stat-pendapatan">
                        Rp {{ number_format($stats['total_bayar'], 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="row g-4 mb-5">
            <div class="col-lg-8">
                <div class="chart-card">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="chart-icon-box"><i class="fas fa-chart-area"></i></div>
                        <div>
                            <p class="chart-card-title">Pendapatan Harian</p>
                            <p class="chart-card-subtitle mb-0" id="subtitle-pendapatan">
                                {{ $stats['periode'] === 'hari' ? 'Hari ini' : ($stats['periode'] === 'minggu' ? '7 hari terakhir' : ($stats['periode'] === 'bulan' ? 'Bulan ini' : 'Tahun ini')) }}
                            </p>
                        </div>
                    </div>
                    <div id="chart-pendapatan"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="chart-card">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="chart-icon-box"><i class="fas fa-trophy"></i></div>
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
        // ── Data awal dari server ──────────────────────────────────────────────
        let pendapatanHarian = @json($stats['pendapatan_harian']);
        let produkTerlaris = @json($stats['produk_terlaris']);
        let currentPeriode = @json($stats['periode']);

        // ── ApexCharts instances ───────────────────────────────────────────────
        let chartPendapatan = null;
        let chartProduk = null;

        // ── Label helper ──────────────────────────────────────────────────────
        function buildPendapatanSeries(data, periode) {
            const today = new Date();
            const labels = [];
            const dataMap = {};

            if (periode === 'hari') {
                for (let h = 0; h < 24; h++) {
                    const key = String(h).padStart(2, '0') + ':00';
                    labels.push(key);
                    dataMap[key] = 0;
                }
                data.forEach(function(row) {
                    const key = row.tanggal ? String(row.tanggal).slice(0, 10) : null;
                    if (key) dataMap['00:00'] = (dataMap['00:00'] || 0) + (parseFloat(row.total_pendapatan) || 0);
                });

            } else if (periode === 'tahun') {
                const bulanNama = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
                for (let m = 0; m < 12; m++) {
                    labels.push(bulanNama[m]);
                    dataMap[String(m + 1).padStart(2, '0')] = 0;
                }
                data.forEach(function(row) {
                    const key = row.tanggal ? String(row.tanggal).slice(5, 7) : null;
                    if (key && Object.prototype.hasOwnProperty.call(dataMap, key)) {
                        dataMap[key] += parseFloat(row.total_pendapatan) || 0;
                    }
                });
                return {
                    labels,
                    values: Object.values(dataMap)
                };

            } else {
                const startOffset = periode === 'minggu' ? 6 : (today.getDate() - 1);

                for (let i = startOffset; i >= 0; i--) {
                    const d = new Date(today);
                    d.setDate(today.getDate() - i);
                    const key = d.toISOString().slice(0, 10);
                    labels.push(key);
                    dataMap[key] = 0;
                }
                data.forEach(function(row) {
                    const key = row.tanggal ? String(row.tanggal).slice(0, 10) : null;
                    if (key && Object.prototype.hasOwnProperty.call(dataMap, key)) {
                        dataMap[key] = parseFloat(row.total_pendapatan) || 0;
                    }
                });

                const formattedLabels = labels.map(function(k) {
                    return new Date(k + 'T00:00:00').toLocaleDateString('id-ID', {
                        weekday: periode === 'minggu' ? 'short' : undefined,
                        day: 'numeric',
                        month: 'short'
                    });
                });
                return {
                    labels: formattedLabels,
                    values: labels.map(k => dataMap[k])
                };
            }

            return {
                labels: Object.keys(dataMap),
                values: Object.values(dataMap)
            };
        }

        // ── Render chart pendapatan — selalu destroy & recreate ───────────────
        function renderChartPendapatan(data, periode) {
            const {
                labels,
                values
            } = buildPendapatanSeries(data, periode);

            const subtitleMap = {
                hari: 'Hari ini',
                minggu: '7 hari terakhir',
                bulan: 'Bulan ini',
                tahun: 'Tahun ini'
            };
            document.getElementById('subtitle-pendapatan').innerText = subtitleMap[periode] || '';

            // Selalu destroy instance lama
            if (chartPendapatan) {
                chartPendapatan.destroy();
                chartPendapatan = null;
            }
            document.getElementById('chart-pendapatan').innerHTML = '';

            const options = {
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
                        speed: 600
                    },
                },
                series: [{
                    name: 'Pendapatan',
                    data: values
                }],
                xaxis: {
                    categories: labels,
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
                        opacityFrom: .4,
                        opacityTo: .01,
                        stops: [0, 100]
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
                        formatter: val => 'Rp ' + Number(val).toLocaleString('id-ID')
                    },
                    theme: 'light',
                },
                grid: {
                    borderColor: '#f4f4f4',
                    strokeDashArray: 5,
                    padding: {
                        left: 8,
                        right: 8
                    }
                },
                markers: {
                    size: 4,
                    colors: ['#C5A47E'],
                    strokeColors: '#fff',
                    strokeWidth: 2,
                    hover: {
                        size: 6
                    }
                },
            };

            chartPendapatan = new ApexCharts(document.getElementById('chart-pendapatan'), options);
            chartPendapatan.render();
        }

        // ── Render chart produk — selalu destroy & recreate ───────────────────
        function renderChartProduk(data) {
            const el = document.getElementById('chart-produk');

            // Selalu destroy instance lama terlebih dahulu
            if (chartProduk) {
                chartProduk.destroy();
                chartProduk = null;
            }
            el.innerHTML = '';

            if (!data || !data.length || data.every(p => parseInt(p.total_qty) === 0)) {
                el.innerHTML = '<p class="text-center text-muted small py-5">Belum ada data produk pada periode ini.</p>';
                return;
            }

            const top = data.slice(0, 6);
            const labels = top.map(p => p.nama_produk);
            const values = top.map(p => parseInt(p.total_qty) || 0);
            const palette = ['#C5A47E', '#2c3e50', '#e67e22', '#27ae60', '#2980b9', '#8e44ad'];

            const options = {
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
                        speed: 600
                    },
                },
                series: values,
                labels,
                colors: palette,
                legend: {
                    position: 'bottom',
                    fontSize: '11px',
                    itemMargin: {
                        horizontal: 4,
                        vertical: 4
                    },
                    formatter: (label, opts) => label + ' (' + opts.w.globals.series[opts.seriesIndex] + ')',
                },
                dataLabels: {
                    enabled: true,
                    formatter: val => Math.round(val) + '%',
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
                                    formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0) + ' terjual',
                                },
                                value: {
                                    fontSize: '18px',
                                    fontWeight: '700',
                                    color: '#2c3e50'
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
                        formatter: val => val + ' terjual'
                    },
                    theme: 'light',
                },
            };

            chartProduk = new ApexCharts(el, options);
            chartProduk.render();
        }

        // ── Fetch data dari server via AJAX ───────────────────────────────────
        async function fetchDashboard(periode) {
            document.querySelectorAll('.periode-btn').forEach(b => b.disabled = true);

            try {
                const res = await fetch(`{{ route('admin.dashboard') }}?periode=${periode}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });
                const data = await res.json();

                // Update stat cards
                document.getElementById('stat-baru').innerText = data.baru;
                document.getElementById('stat-diproses').innerText = data.diproses;
                document.getElementById('stat-selesai').innerText = data.selesai;
                document.getElementById('stat-pendapatan').innerText =
                    'Rp ' + Number(data.total_bayar).toLocaleString('id-ID');

                // Destroy lama & render baru
                renderChartPendapatan(data.pendapatan_harian, periode);
                renderChartProduk(data.produk_terlaris);

            } catch (e) {
                console.error('Gagal memuat data dashboard:', e);
            } finally {
                document.querySelectorAll('.periode-btn').forEach(b => b.disabled = false);
            }
        }

        // ── Event listener tombol filter ──────────────────────────────────────
        document.getElementById('periode-filter').addEventListener('click', function(e) {
            const btn = e.target.closest('.periode-btn');
            if (!btn) return;
            const periode = btn.dataset.periode;
            if (periode === currentPeriode) return;

            currentPeriode = periode;
            document.querySelectorAll('.periode-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            fetchDashboard(periode);
        });

        // ── Initial render ────────────────────────────────────────────────────
        renderChartPendapatan(pendapatanHarian, currentPeriode);
        renderChartProduk(produkTerlaris);
    </script>
@endsection
