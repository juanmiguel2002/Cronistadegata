@props([])

@php
    $slides = \App\Models\Carousel::orderBy('orden')->get();
@endphp

<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @foreach ($slides as $slide)
            <div class="swiper-slide">
                <img src="{{ asset('storage/' . $slide->image_path) }}" alt="{{ $slide->title }}">
                @if ($slide->title)
                    <div class="slide-title">{{ $slide->title }}</div>
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

<a class="ir-arriba" javascript:void(0) title="Volver arriba">
    <span class="fa-stack">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-arrow-up fa-stack-1x fa-inverse"></i>
    </span>
</a>
