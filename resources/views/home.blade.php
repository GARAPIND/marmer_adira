@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

<style>
    :root {
        --marble-gold: #C5A47E; /* Gold yang lebih redup dan mewah */
        --marble-dark: #2D2D2D; /* Bukan hitam pekat agar kontras lebih lembut */
        --marble-cream: #FDFCF8; /* Putih tulang/marmer agar tidak menyilaukan */
        --marble-accent: #F7F3EF; /* Warna pasir lembut untuk selingan section */
    }

    body { 
        font-family: 'Inter', sans-serif; 
        overflow-x: hidden; 
        background-color: var(--marble-cream); 
        color: var(--marble-dark);
        line-height: 1.6;
    }

    h1, h2, h3, .hero-tag {
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
        border-radius: 0; /* Desain kotak lebih terasa arsitektural */
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
        border: 1px solid rgba(255,255,255,0.6);
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
        box-shadow: 0 15px 40px rgba(0,0,0,0.03);
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
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 1.8s ease;
    }

    .material-card:hover img { transform: scale(1.1); }

    .accent-line {
        width: 40px; height: 2px;
        background: var(--marble-gold);
        margin: 20px auto;
    }

    .category-label {
        position: absolute; bottom: 0; left: 0;
        background: var(--marble-gold);
        padding: 8px 20px;
        font-size: 0.65rem; font-weight: 600; color: white;
        text-transform: uppercase; letter-spacing: 2px;
    }

    /* --- Footer Kontras Lembut --- */
    .footer-premium {
        background-color: var(--marble-dark);
        color: rgba(255,255,255,0.7);
        padding: 100px 0 40px;
        border-top: 1px solid rgba(197, 164, 126, 0.2);
    }

    .footer-title {
        color: var(--marble-gold);
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 2rem;
        text-transform: uppercase;
        letter-spacing: 3px;
    }

    .footer-link {
        color: rgba(255,255,255,0.5);
        text-decoration: none;
        transition: 0.3s;
        display: block;
        margin-bottom: 12px;
        font-size: 0.9rem;
    }

    .footer-link:hover {
        color: var(--marble-gold);
        transform: translateX(5px);
    }

    .social-icon {
        width: 45px; height: 45px;
        border: 1px solid rgba(255,255,255,0.1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 12px;
        transition: 0.4s;
    }

    .social-icon:hover {
        border-color: var(--marble-gold);
        color: var(--marble-gold);
        transform: translateY(-3px);
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
            Menghadirkan kerajinan marmer pilihan yang diproduksi dengan ketelitian tinggi, memadukan keindahan alami batu dan keahlian tangan pengrajin berpengalaman.
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
            <h6 class="text-uppercase fw-bold mb-2" style="color: var(--marble-gold); letter-spacing: 4px; font-size: 0.7rem;">Selection</h6>
            <h2 class="fw-bold">Material Pilihan Kami</h2>
            <div class="accent-line"></div>
            <p class="text-muted mx-auto fw-light" style="max-width: 500px;">Batu alam premium untuk estetika hunian tanpa batas.</p>
        </div>

        <div class="row g-5 justify-content-center">
            @php
                $materials = [
                    ['name' => 'Teraso', 'desc' => 'Batu olahan berbahan campuran agregat alami yang kuat dan stabil. Cocok untuk produk dekoratif dan fungsional dengan tampilan modern serta perawatan yang mudah.', 'img' => '/image/teraso.jpg'],
                    ['name' => 'Marmer', 'desc' => 'Batu alam dengan corak urat alami yang elegan dan mewah. Ideal untuk produk interior yang mengutamakan estetika dan kesan premium.', 'img' => '/image/marmer.jpg'],
                    ['name' => 'Andesit', 'desc' => 'Batu alam bertekstur kuat dan tahan terhadap cuaca ekstrem. Sangat cocok untuk produk berukuran besar atau penggunaan outdoor.', 'img' => '/image/andesit.jpg'],
                    ['name' => 'Onix', 'desc' => 'Batu alam semi-transparan dengan karakter cahaya yang unik. Digunakan untuk produk eksklusif yang menonjolkan nilai artistik dan kemewahan.', 'img' => '/image/onix.jpg'],
                    ['name' => 'Granit', 'desc' => 'Batu alam dengan struktur keras dan daya tahan tinggi terhadap goresan. Pilihan tepat untuk produk yang membutuhkan kekuatan dan ketahanan jangka panjang.', 'img' => '/image/granit.jpg']
                ];
            @endphp

            @foreach($materials as $m)
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
        <h6 class="text-uppercase fw-bold mb-2 reveal" style="color: var(--marble-gold); letter-spacing: 4px; font-size: 0.7rem;">Collections</h6>
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
                    ['name' => 'Lantai', 'img' => 'image/lantai.jpg']
                ];
            @endphp

            @foreach($products as $p)
            <div class="col-6 col-md-3 reveal">
                <div class="card h-100 material-card">
                    <div class="img-wrapper" style="height: 220px;">
                        <img src="{{ $p['img'] }}" alt="{{ $p['name'] }}">
                    </div>
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">{{ $p['name'] }}</h6>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<footer class="footer-premium">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-3 col-md-6 reveal">
                <h5 class="footer-title">Koleksi</h5>
                <a href="#" class="footer-link">Asbak</a>
                <a href="#" class="footer-link">Tempat Sabun</a>
                <a href="#" class="footer-link">Hiasan Meja</a>
                <a href="#" class="footer-link">Vas Bunga</a>
                <a href="#" class="footer-link">Meja</a>
                <a href="#" class="footer-link">Wastafel</a>
                <a href="#" class="footer-link">Wadah/Cepuk</a>
                <a href="#" class="footer-link">Lantai</a>

            </div>

            <div class="col-lg-3 col-md-6 reveal">
                <h5 class="footer-title">Navigasi</h5>
                <a href="{{ route('produk.index') }}" class="footer-link">Katalog Produk</a>
                <a href="#about" class="footer-link">Tentang Kami</a>
                <a href="#" class="footer-link">Hubungi Kami</a>
                <a href="#" class="footer-link">Kebijakan Layanan</a>
            </div>

            <div class="col-lg-3 col-md-6 reveal">
                <h5 class="footer-title">Kontak</h5>
                <p class="small mb-2"><i class="fas fa-phone me-2 text-gold"></i> +62 858-9462-6729</p>
                <p class="small mb-2"><i class="fas fa-envelope me-2 text-gold"></i> info@adiramarmer.com</p>
                <p class="small"><i class="fas fa-map-marker-alt me-2 text-gold"></i> Campurdarat, Tulungagung</p>
            </div>

            <div class="col-lg-3 col-md-6 reveal text-lg-end">
                <h3 class="fw-bold mb-4" style="color: var(--marble-gold); letter-spacing: 2px;">
                    ADIRA <span class="text-white fw-light">MARMER</span>
                </h3>
                <div class="d-flex justify-content-lg-end">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>

        <div class="pt-5 mt-5 text-center border-top border-secondary opacity-50">
            <p class="small mb-0">© 2026 Adira Marmer Eksklusif. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("active");
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll(".reveal").forEach(el => observer.observe(el));
    });
</script>
@endsection