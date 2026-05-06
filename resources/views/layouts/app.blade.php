<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Adira Marmer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Inter:wght@300;400;600&display=swap"
        rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --marble-gold: #C5A47E;
            /* Gold yang lebih redup dan mewah */
            --marble-dark: #2D2D2D;
            /* Bukan hitam pekat agar kontras lebih lembut */
            --marble-cream: #FDFCF8;
            /* Putih tulang/marmer agar tidak menyilaukan */
            --marble-accent: #F7F3EF;
            /* Warna pasir lembut untuk selingan section */
        }

        /* =====================
           GLOBAL STYLE
        ===================== */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            color: #222;
        }

        a {
            text-decoration: none;
        }

        /* =====================
           NAVBAR EFFECT
        ===================== */
        .navbar {
            transition: all 0.3s ease-in-out;
            padding: 18px 0;
        }

        .navbar.scrolled {
            background-color: #ffffff !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            padding: 10px 0;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* =====================
           HERO SECTION
        ===================== */
        .hero-home {
            height: 100vh;
            background:
                linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.55)),
                url('https://images.pexels.com/photos/275484/pexels-photo-275484.jpeg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        .hero-home h1 {
            font-size: 3.4rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .hero-home p {
            font-size: 1.2rem;
            margin-top: 20px;
            opacity: 0.9;
        }

        .hero-home .btn {
            margin-top: 30px;
            padding: 14px 36px;
            font-weight: 600;
            border-radius: 30px;
        }

        /* =====================
           SECTION TITLE
        ===================== */
        .section-title {
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 40px;
        }

        /* =====================
           PRODUK CARD
        ===================== */
        .card-produk {
            border: none;
            border-radius: 14px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card-produk:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        .card-produk img {
            height: 230px;
            object-fit: cover;
        }

        /* --- Footer Kontras Lembut --- */
        .footer-premium {
            background-color: var(--marble-dark);
            color: rgba(255, 255, 255, 0.7);
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
            color: rgba(255, 255, 255, 0.5);
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
            width: 45px;
            height: 45px;
            border: 1px solid rgba(255, 255, 255, 0.1);
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
</head>

<body>

    {{-- NAVBAR --}}
    @include('components.navbar')

    {{-- CONTENT --}}
    <main>
        @yield('content')
    </main>

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
                    <a href="{{ route('tentang.kami') }}" class="footer-link">Tentang Kami</a>
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
                        <a href="https://wa.me/6285894626729" target="_blank" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>

            <div class="pt-5 mt-5 text-center border-top border-secondary opacity-50">
                <p class="small mb-0">© 2026 Adira Marmer Eksklusif. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Navbar Scroll Effect -->
    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>

</body>

</html>
