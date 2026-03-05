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

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
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

        /* =====================
           FOOTER
        ===================== */
        footer {
            background-color: #111;
            color: #ccc;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
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

    {{-- FOOTER --}}
    <footer class="text-center py-4 mt-5">
        <p>&copy; {{ date('Y') }} Adira Marmer | Sistem Informasi Pemesanan Produk Marmer</p>
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
