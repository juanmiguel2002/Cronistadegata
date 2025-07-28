@props([])

@php
    $slides = \App\Models\Carousel::orderBy('orden')->get();
@endphp

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
    .swiper {
        width: 100%;
        max-height: 650px;
    }

    .swiper-slide {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .swiper-slide img {
        width: 100%;
        height: auto;
        object-fit: cover;
        border-radius: 12px;
    }

    .slide-title {
        position: absolute;
        text-shadow: 0 0 8px rgba(0, 0, 0, 0.7);
        text-align: center;
        padding: 0 1rem;
        /* z-index: 10; */
    }

    @media (max-width: 768px) {
        .slide-title {
            font-size: 1.25rem;
        }
        .swiper {
            max-height: 300px;
        }
    }

    @media (max-width: 480px) {
        .slide-title {
            font-size: 1rem;
        }
        .swiper {
            max-height: 200px;
        }
    }
</style>

<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @foreach ($slides as $slide)
            <div class="swiper-slide">
                <img src="{{ asset('storage/' . $slide->image_path) }}" alt="Slide Image">
                @if ($slide->title)
                    <div class="slide-title titulo">{{ $slide->title }}</div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<nav class="navbar" id="menu">
    <div class="navbar-left">
        <div id="logo">
            <a href="/">
                <img class="logo" src="{{ asset('storage/' . settings()->site_logo) }}" alt="Cronistadegata" />
            </a>
        </div>

        <button class="nav-toggle" id="nav-toggle" aria-label="Abrir menú">
            <i class="fa fa-bars"></i>
        </button>

        <ul class="nav-menu" id="nav-menu">
            <li><a href="/">Inici</a></li>
            <li><a href="{{ route('destacats') }}">Destacats</a></li>
            <li><a href="{{ route('contacto') }}">Contacte</a></li>
            <li id="search">
                <form class="navbar-search" action="{{ route('search') }}" method="GET">
                    <input type="text" name="query" placeholder="Buscar...">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    new Swiper(".mySwiper", {
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
    });
    document.getElementById('nav-toggle').addEventListener('click', function () {
        document.getElementById('nav-menu').classList.toggle('show');

    });
</script>

