<!DOCTYPE html>
<html lang="es-es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    @yield('meta_tags')
    <title>@yield('pageTitle')</title>
    <link href="{{ asset('front/css/style.css') }}" rel="stylesheet" type='text/css' />
    <link rel="shortcut icon" href="{{ asset('storage/'. settings()->site_favicon) }}" type="image/jpg" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Yanone+Kaffeesatz&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('front/vendors/styles/icon-font.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <style>
        /* Estilos adicionales para el slider */
        .slider {
            width: 80%;
            margin: 0 auto;
        }
        .slider img {
            width: 100%;
        }
    </style>
</head>

<body>
    <x-header-carrousel />

    <nav class="navbar" id="myTopnav">
        <img class="logo" src="{{ asset('storage/'.settings()->site_logo) }}" width="69" alt="Cronistadegata" />
        <a href="/">Inici</a>
        <a href="{{ route('destacats') }}">Destacats</a>
        <a href="{{ route('contacto') }}">Contacte</a>
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">
            <i class="fa fa-bars"></i>
        </a>
        <!--Buscador-->
        <form class="navbar-search" action="{{ route('search') }}" method="GET">
            <input type="text" name="query" placeholder="Buscar...">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    <style>

    </style>
    </nav>

    <main class="row">
        <section class="leftcolumn">
            @yield('main')
        </section>

        <section class="rightcolumn">
            <!--Filtro-->
            <x-sidebar-filter />

            <!--Temes-->
            <x-sidebar-categories />

            {{-- <article class="card">
                <h3>Enllaços</h3><br>
                <div class="social">
                    <a title="Facebook" href="https://www.facebook.com/cronista.degata.5" target="_blank">
                        <img src="img/facebook-logo.png" class="foto" alt="Facebook">
                    </a>
                    <a title="Instagram" href="https://www.instagram.com/cronistagata/" target="_blank">
                        <img src="img/instagram.png" class="foto" alt="Instagram" />
                    </a>
                </div>
            </article> --}}
        </section>
    </main>

    <footer>
        <h4>© Copyright 2025 <a href="/">Cronistadegata</a> | By Juanmi</h4>
    </footer>
    <script src="front/js/menu.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
