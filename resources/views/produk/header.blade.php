@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --gold: #C5A47E;
            --gold-light: #D9BC9A;
            --gold-pale: rgba(197, 164, 126, 0.12);
            --dark: #131210;
            --cream: #FDFCF8;
            --gray-soft: #F5F4F0;
            --border: rgba(197, 164, 126, 0.22);
            --text-muted: #9A9490;
        }

        body {
            background-color: var(--cream);
            font-family: 'DM Sans', sans-serif;
            color: var(--dark);
        }

        .catalog-header {
            padding: 100px 0 56px;
            text-align: center;
            position: relative;
        }

        .catalog-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 1px;
            height: 56px;
            background: linear-gradient(to bottom, var(--gold), transparent);
        }

        .section-tag {
            color: var(--gold);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 7px;
            font-size: 0.62rem;
            display: block;
            margin-bottom: 18px;
        }

        .catalog-title {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            font-size: clamp(2.4rem, 5vw, 3.8rem);
            letter-spacing: -1px;
            line-height: 1;
            color: var(--dark);
        }

        .catalog-subtitle {
            font-size: 0.82rem;
            color: var(--text-muted);
            letter-spacing: 0.5px;
            margin-top: 14px;
            font-weight: 400;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
            padding: 70px 0 80px;
        }

        .product-card {
            background: #fff;
            overflow: hidden;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid transparent;
            opacity: 0;
            transform: translateY(32px);
            position: relative;
            cursor: pointer;
            transition: opacity 0.6s ease,
                transform 0.6s cubic-bezier(0.23, 1, 0.32, 1),
                box-shadow 0.4s cubic-bezier(0.23, 1, 0.32, 1),
                border-color 0.4s;
        }

        .product-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 50px rgba(197, 164, 126, 0.2);
            border-color: var(--border);
        }

        .card-overlay-link {
            position: absolute;
            inset: 0;
            z-index: 5;
        }

        .slideshow-wrapper,
        .card-info .detail-btn,
        .card-info .bahan-chip {
            position: relative;
            z-index: 6;
        }

        .slideshow-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            background: var(--gray-soft);
            overflow: hidden;
        }

        .slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.7s ease;
        }

        .slide.active {
            opacity: 1;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.6s ease;
        }

        .product-card:hover .slide.active img {
            transform: scale(1.04);
        }

        .slide-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: rgba(19, 18, 16, 0.5);
            border: none;
            color: #fff;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            backdrop-filter: blur(6px);
            transition: background 0.3s;
            font-size: 0.65rem;
            opacity: 0;
            transition: opacity 0.3s, background 0.3s;
        }

        .slideshow-wrapper:hover .slide-btn {
            opacity: 1;
        }

        .slide-btn:hover {
            background: rgba(197, 164, 126, 0.9);
        }

        .slide-btn.prev {
            left: 10px;
        }

        .slide-btn.next {
            right: 10px;
        }

        .slide-dots {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 5px;
            z-index: 10;
        }

        .slide-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
        }

        .slide-dot.active {
            background: var(--gold);
            transform: scale(1.5);
        }

        .slide-counter {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(19, 18, 16, 0.6);
            color: #fff;
            font-size: 0.55rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            padding: 3px 9px;
            backdrop-filter: blur(4px);
            z-index: 10;
            text-transform: uppercase;
        }

        .slideshow-wrapper.single-img .slide-btn,
        .slideshow-wrapper.single-img .slide-dots,
        .slideshow-wrapper.single-img .slide-counter {
            display: none;
        }

        .card-corner {
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 28px 28px 0 0;
            border-color: var(--gold) transparent transparent transparent;
            z-index: 11;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .product-card:hover .card-corner {
            opacity: 1;
        }

        .card-info {
            padding: 20px 20px 18px;
        }

        .product-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.22rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            line-height: 1.25;
            margin-bottom: 4px;
            color: var(--dark);
        }

        .product-divider {
            width: 28px;
            height: 1.5px;
            background: var(--gold);
            margin: 10px 0 14px;
        }

        .mat-label {
            font-size: 0.58rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--text-muted);
            margin-bottom: 10px;
        }

        .bahan-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 16px;
        }

        .bahan-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: var(--gray-soft);
            border: 1px solid var(--border);
            padding: 4px 11px;
            font-size: 0.68rem;
            font-weight: 500;
            color: var(--dark);
            transition: background 0.25s, border-color 0.25s, color 0.25s;
        }

        .bahan-chip i {
            color: var(--gold);
            font-size: 0.5rem;
        }

        .bahan-chip:hover {
            background: var(--gold-pale);
            border-color: var(--gold);
        }

        .card-footer-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 12px;
            border-top: 1px solid var(--gray-soft);
        }

        .img-count {
            font-size: 0.62rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 5px;
            letter-spacing: 0.3px;
        }

        .img-count i {
            color: var(--gold);
        }

        .detail-btn {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gold);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border: 1px solid var(--border);
            transition: background 0.25s, gap 0.25s, border-color 0.25s;
        }

        .detail-btn i {
            font-size: 0.55rem;
            transition: transform 0.25s;
        }

        .detail-btn:hover {
            background: var(--gold-pale);
            border-color: var(--gold);
            gap: 8px;
            color: var(--gold);
        }

        .detail-btn:hover i {
            transform: translateX(3px);
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 80px 20px;
        }

        .empty-state i {
            color: #ddd;
            margin-bottom: 16px;
        }

        .empty-state p {
            color: var(--text-muted);
            font-style: italic;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
        }
    </style>

    <div class="container py-3 mt-2">

        <div class="catalog-header">
            <span class="section-tag">Exquisite Collection</span>
            <h2 class="catalog-title">Katalog Produk</h2>
            <p class="catalog-subtitle">Pilih material terbaik untuk karya impian Anda</p>
        </div>

        <div class="product-grid">
            @forelse($produk as $item)
                @php
                    $images = $item['gambar'];
                    $total = count($images);
                    $single = $total === 1;
                    $pid = 'ss-' . $loop->index;
                @endphp

                @php
                    $delay = $loop->index * 60;
                    $slug = \Illuminate\Support\Str::slug($item['nama_produk']);
                @endphp

                <div class="product-card" style="transition-delay: {{ $delay }}ms;">

                    <div class="slideshow-wrapper {{ $single ? 'single-img' : '' }}" id="{{ $pid }}"
                        onclick="event.stopPropagation()">

                        <div class="card-corner"></div>

                        @foreach ($images as $i => $img)
                            <div class="slide {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}">
                                <img src="{{ asset('storage/' . $img) }}"
                                    alt="{{ $item['nama_produk'] }} foto {{ $i + 1 }}" loading="lazy">
                            </div>
                        @endforeach

                        @if (!$single)
                            <button class="slide-btn prev"
                                onclick="event.stopPropagation(); moveSlide('{{ $pid }}', -1)">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="slide-btn next"
                                onclick="event.stopPropagation(); moveSlide('{{ $pid }}', 1)">
                                <i class="fas fa-chevron-right"></i>
                            </button>

                            <div class="slide-dots">
                                @foreach ($images as $i => $img)
                                    <span class="slide-dot {{ $i === 0 ? 'active' : '' }}"
                                        onclick="event.stopPropagation(); goToSlide('{{ $pid }}', {{ $i }})"></span>
                                @endforeach
                            </div>

                            <div class="slide-counter" id="{{ $pid }}-counter">1 / {{ $total }}</div>
                        @endif
                    </div>

                    <div class="card-info">
                        <h3 class="product-name">{{ $item['nama_produk'] }}</h3>
                        <div class="product-divider"></div>

                        <p class="mat-label">Material Tersedia</p>
                        <div class="bahan-list">
                            @foreach ($item['bahan'] as $bahan)
                                <span class="bahan-chip">
                                    <i class="fas fa-gem"></i>
                                    {{ $bahan }}
                                </span>
                            @endforeach
                        </div>

                        <div class="card-footer-row">
                            <span class="img-count">
                                <i class="fas fa-images"></i>
                                {{ $total }} Foto
                            </span>
                            <a href="{{ route('produk.detail', ['slug' => $slug]) }}" class="detail-btn"
                                onclick="event.stopPropagation()">
                                Lihat Detail
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('produk.detail', ['slug' => $slug]) }}" class="card-overlay-link"
                        aria-label="Lihat detail {{ $item['nama_produk'] }}"></a>

                </div>

            @empty
                <div class="empty-state">
                    <i class="fas fa-gem fa-3x"></i>
                    <p>"Belum ada koleksi yang dipublikasikan."</p>
                </div>
            @endforelse
        </div>

    </div>

    <script>
        const ssState = {};

        function getState(id) {
            const wrapper = document.getElementById(id);
            if (!ssState[id]) {
                ssState[id] = {
                    current: 0,
                    total: wrapper.querySelectorAll('.slide').length
                };
            }
            return ssState[id];
        }

        function goToSlide(id, index) {
            const wrapper = document.getElementById(id);
            const state = getState(id);
            const slides = wrapper.querySelectorAll('.slide');
            const dots = wrapper.querySelectorAll('.slide-dot');
            const counter = document.getElementById(id + '-counter');

            slides[state.current].classList.remove('active');
            dots[state.current]?.classList.remove('active');

            state.current = (index + state.total) % state.total;

            slides[state.current].classList.add('active');
            dots[state.current]?.classList.add('active');
            if (counter) counter.textContent = (state.current + 1) + ' / ' + state.total;
        }

        function moveSlide(id, dir) {
            const state = getState(id);
            goToSlide(id, state.current + dir);
        }

        document.querySelectorAll('.slideshow-wrapper:not(.single-img)').forEach(wrapper => {
            setInterval(() => moveSlide(wrapper.id, 1), 4000);
        });

        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.08
            });

            document.querySelectorAll('.product-card').forEach(el => observer.observe(el));
        });
    </script>
@endsection
