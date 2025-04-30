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
        color: white;
        font-size: 2rem;
        font-weight: bold;
        text-shadow: 0 0 8px rgba(0, 0, 0, 0.7);
        text-align: center;
        padding: 0 1rem;
        z-index: 10;
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
                    <div class="slide-title">{{ $slide->title }}</div>
                @endif
            </div>
        @endforeach
    </div>
</div>

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
</script>

