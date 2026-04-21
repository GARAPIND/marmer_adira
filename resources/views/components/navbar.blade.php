<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Inter', sans-serif !important;
    }

    .navbar {
        background-color: #ffffff !important;
        padding: 1rem 0 !important;
        border-bottom: 2px solid #000000 !important;
        box-shadow: none !important;
    }

    .navbar-brand {
        font-size: 1.5rem !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
        display: flex !important;
        align-items: center !important;
        gap: 5px !important;
    }

    .brand-adira {
        font-weight: 800 !important;
        color: #000000 !important;
    }

    .brand-marmer {
        font-weight: 400 !important;
        color: #7f8c8d !important;
    }

    .nav-link {
        font-weight: 600 !important;
        color: #333333 !important;
        padding: 0.5rem 1.25rem !important;
        font-size: 0.9rem !important;
        transition: all 0.2s ease;
    }

    .nav-link.active {
        color: #000000 !important;
        text-decoration: underline !important;
    }

    .nav-link:hover {
        color: #b08d44 !important;
    }

    .btn-logout {
        background: none !important;
        border: 2px solid #000 !important;
        border-radius: 50px;
        font-weight: 700 !important;
        color: #000 !important;
        padding: 5px 20px !important;
        font-size: 0.85rem !important;
        text-transform: uppercase;
        transition: 0.3s;
    }

    .btn-logout:hover {
        background: #000 !important;
        color: #fff !important;
    }

    .user-greeting {
        font-size: 0.85rem;
        color: #666;
        margin-right: 15px;
        font-style: italic;
    }
</style>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <span class="brand-adira">ADIRA</span>
            <span class="brand-marmer">MARMER</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="/">Beranda</a>
                </li>

                @auth
                    {{-- --- MENU UNTUK ADMIN --- --}}
                    @if (Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/dashboard*') ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/pengguna*') ? 'active' : '' }}"
                                href="{{ route('admin.pengguna.index') }}">Data Pengguna</a>
                        </li>
                        <li class="nav-item">
                            {{-- PERBAIKAN DI SINI: admin.produk diganti admin.produk.index --}}
                            <a class="nav-link {{ Request::is('admin/produk*') ? 'active' : '' }}"
                                href="{{ route('admin.produk.index') }}">Manajemen Produk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/pesanan*') ? 'active' : '' }}"
                                href="{{ route('admin.pesanan.baru') }}">Pesanan</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarLaporan" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Laporan
                            </a>
                            <ul class="dropdown-menu border-0 shadow" aria-labelledby="navbarLaporan">
                                <li><a class="dropdown-item" href="{{ route('admin.laporan.pesanan') }}">Laporan Pesanan</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('admin.laporan.keuangan') }}">Laporan
                                        Keuangan</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.laporan.pengguna') }}">Laporan
                                        Pengguna</a></li>
                                {{-- <li><a class="dropdown-item" href="{{ route('admin.laporan.penjualan') }}">Laporan
                                        Penjualan</a></li> --}}
                            </ul>
                        </li>

                        {{-- --- MENU UNTUK PENGRAJIN --- --}}
                    @elseif(Auth::user()->role == 'pengrajin')
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('pengrajin/dashboard*') ? 'active' : '' }}"
                                href="{{ route('pengrajin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('pengrajin/katalog*') ? 'active' : '' }}"
                                href="{{ route('pengrajin.katalog') }}">Katalog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('pengrajin/pesanan-masuk*') ? 'active' : '' }}"
                                href="{{ route('pengrajin.pesanan.masuk') }}">Pesanan Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('pengrajin/proses-pengerjaan*') ? 'active' : '' }}"
                                href="{{ route('pengrajin.proses') }}">Proses Pengerjaan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('pengrajin/riwayat*') ? 'active' : '' }}"
                                href="{{ route('pengrajin.riwayat') }}">Riwayat Pesanan</a>
                        </li>

                        {{-- --- MENU UNTUK PEMBELI --- --}}
                    @elseif(Auth::user()->role == 'pembeli')
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}"
                                href="{{ route('pembeli.dashboard') }}">Dashboard</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('produk*') || Request::is('katalog*') ? 'active' : '' }}"
                                href="{{ route('produk.index') }}">Katalog Produk</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('pesanan*') ? 'active' : '' }}"
                                href="{{ route('pesanan.index') }}">Riwayat Pesanan</a>
                        </li>
                    @endif

                    <li class="nav-item ms-lg-3 d-flex align-items-center">
                        <span class="user-greeting d-none d-lg-inline">Halo, {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn-logout shadow-sm">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('produk*') || Request::is('katalog*') ? 'active' : '' }}"
                            href="{{ route('produk.index') }}">Katalog</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn-logout text-decoration-none">Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
