@extends('layouts.app')

@section('content')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            background-color: var(--marble-cream);
            color: var(--marble-dark);
            line-height: 1.6;
        }

        h1,
        h2,
        h3,
        .hero-tag {
            font-family: 'Playfair Display', serif;
        }

        /* --- Animasi Reveal yang Diperhalus --- */
        .reveal {
            opacity: 0;
            transform: translateY(30px) scale(0.98);
            transition: all 1.2s cubic-bezier(0.2, 1, 0.3, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        /* --- Hero Section dengan Kontras Rendah --- */
        .hero-section {
            height: 100vh;
            background: linear-gradient(rgba(45, 45, 45, 0.5), rgba(45, 45, 45, 0.5)),
                url('/image/hitam-marble.jpg') center/cover no-repeat fixed;
            display: flex;
            align-items: center;
            color: white;
            position: relative;
        }

        .hero-title {
            font-size: clamp(3rem, 8vw, 4.5rem);
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .btn-gold-premium {
            background-color: var(--marble-gold);
            color: white;
            border-radius: 0;
            /* Desain kotak lebih terasa arsitektural */
            padding: 15px 45px;
            font-weight: 600;
            letter-spacing: 2px;
            transition: 0.5s;
            border: 1px solid var(--marble-gold);
            text-transform: uppercase;
            font-size: 0.8rem;
        }

        .btn-gold-premium:hover {
            background-color: transparent;
            border-color: white;
            transform: translateY(-5px);
            color: white;
        }

        .btn-outline-soft {
            border: 1px solid rgba(255, 255, 255, 0.6);
            color: white;
            border-radius: 0;
            padding: 15px 45px;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 2px;
            transition: 0.4s;
        }

        .btn-outline-soft:hover {
            background: white;
            color: var(--marble-dark);
        }

        /* --- Desain Kartu Material --- */
        .material-card {
            border: none;
            border-radius: 0;
            background: white;
            overflow: hidden;
            transition: all 0.6s ease;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.03);
        }

        .material-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(197, 164, 126, 0.15) !important;
        }

        .img-wrapper {
            height: 300px;
            overflow: hidden;
            position: relative;
        }

        .img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 1.8s ease;
        }

        .material-card:hover img {
            transform: scale(1.1);
        }

        .accent-line {
            width: 40px;
            height: 2px;
            background: var(--marble-gold);
            margin: 20px auto;
        }

        .category-label {
            position: absolute;
            bottom: 0;
            left: 0;
            background: var(--marble-gold);
            padding: 8px 20px;
            font-size: 0.65rem;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
    </style>

    <header class="hero-section text-center">
        <div class="container">
            <h4 class="animate__animated animate__fadeInDown fw-light"
                style="letter-spacing: 8px; color: var(--marble-gold); font-size: 0.9rem;">
                SENI DALAM BATU ALAM
            </h4>

            <h1 class="hero-title animate__animated animate__fadeInUp animate__delay-1s">
                Karya Marmer Bernilai Abadi <br> <i>Dibentuk dari Alam</i>
            </h1>

            <p class="lead mb-5 mx-auto animate__animated animate__fadeInUp animate__delay-2s fw-light"
                style="max-width: 600px; opacity: 0.8;">
                Menghadirkan kerajinan marmer pilihan yang diproduksi dengan ketelitian tinggi, memadukan keindahan alami
                batu dan keahlian tangan pengrajin berpengalaman.
            </p>

            <div class="d-flex justify-content-center gap-4 animate__animated animate__fadeInUp animate__delay-3s">
                <a href="{{ route('produk.index') }}" class="btn btn-gold-premium">Katalog Produk</a>
                <a href="#about" class="btn btn-outline-soft">Tentang Kami</a>
            </div>
        </div>
    </header>

    <section class="py-5" style="background-color: var(--marble-cream);">
        <div class="container py-5">
            <div class="text-center mb-5 reveal">
                <h6 class="text-uppercase fw-bold mb-2"
                    style="color: var(--marble-gold); letter-spacing: 4px; font-size: 0.7rem;">Selection</h6>
                <h2 class="fw-bold">Material Pilihan Kami</h2>
                <div class="accent-line"></div>
                <p class="text-muted mx-auto fw-light" style="max-width: 500px;">Batu alam premium untuk estetika hunian
                    tanpa batas.</p>
            </div>

            <div class="row g-5 justify-content-center">
                @php
                    $materials = [
                        [
                            'name' => 'Teraso',
                            'desc' =>
                                'Batu olahan berbahan campuran agregat alami yang kuat dan stabil. Cocok untuk produk dekoratif dan fungsional dengan tampilan modern serta perawatan yang mudah.',
                            'img' => '/image/teraso.jpg',
                        ],
                        [
                            'name' => 'Marmer',
                            'desc' =>
                                'Batu alam dengan corak urat alami yang elegan dan mewah. Ideal untuk produk interior yang mengutamakan estetika dan kesan premium.',
                            'img' => '/image/marmer.jpg',
                        ],
                        [
                            'name' => 'Andesit',
                            'desc' =>
                                'Batu alam bertekstur kuat dan tahan terhadap cuaca ekstrem. Sangat cocok untuk produk berukuran besar atau penggunaan outdoor.',
                            'img' => '/image/andesit.jpg',
                        ],
                        [
                            'name' => 'Onix',
                            'desc' =>
                                'Batu alam semi-transparan dengan karakter cahaya yang unik. Digunakan untuk produk eksklusif yang menonjolkan nilai artistik dan kemewahan.',
                            'img' => '/image/onix.jpg',
                        ],
                        [
                            'name' => 'Granit',
                            'desc' =>
                                'Batu alam dengan struktur keras dan daya tahan tinggi terhadap goresan. Pilihan tepat untuk produk yang membutuhkan kekuatan dan ketahanan jangka panjang.',
                            'img' => '/image/granit.jpg',
                        ],
                    ];
                @endphp

                @foreach ($materials as $m)
                    <div class="col-lg-4 col-md-6 reveal">
                        <div class="card h-100 material-card">
                            <div class="img-wrapper">
                                <img src="{{ $m['img'] }}" alt="{{ $m['name'] }}">
                                <div class="category-label">Material</div>
                            </div>
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-1">{{ $m['name'] }}</h5>
                                <p class="text-muted small fw-light">{{ $m['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-5" style="background-color: var(--marble-accent);">
        <div class="container py-5 text-center">
            <h6 class="text-uppercase fw-bold mb-2 reveal"
                style="color: var(--marble-gold); letter-spacing: 4px; font-size: 0.7rem;">Collections</h6>
            <h2 class="fw-bold reveal">Koleksi Produk Jadi</h2>
            <div class="accent-line reveal"></div>

            <div class="row g-4 mt-4">
                @php
                    $products = [
                        ['name' => 'Asbak', 'img' => 'image/asbak.jpg'],
                        ['name' => 'Tempat Sabun', 'img' => 'image/tempat-sabun.jpg'],
                        ['name' => 'Hiasan Meja', 'img' => 'image/hiasan-meja.jpg'],
                        ['name' => 'Vas Bunga', 'img' => 'image/vas-bunga.jpg'],
                        ['name' => 'Meja', 'img' => 'image/meja.jpg'],
                        ['name' => 'Wastafel', 'img' => 'image/wastafel.jpg'],
                        ['name' => 'Wadah/Cepuk', 'img' => 'image/wadah-cepuk.jpg'],
                        ['name' => 'Lantai', 'img' => 'image/lantai.jpg'],
                    ];
                @endphp

                @foreach ($products as $p)
                    <div class="col-6 col-md-3 reveal">
                        <div class="card h-100 material-card">
                            <div class="img-wrapper" style="height: 220px;">
                                <img src="{{ $p['img'] }}" alt="{{ $p['name'] }}">
                            </div>
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">
                                    {{ $p['name'] }}</h6>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("active");
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll(".reveal").forEach(el => observer.observe(el));
        });
    </script>
@endsection
