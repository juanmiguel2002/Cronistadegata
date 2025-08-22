<!DOCTYPE html>
<html lang="es-es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @yield('meta_tags')
    <title>@yield('pageTitle')</title>
    <link href="{{ asset('front/css/style.css') }}" rel="stylesheet" type='text/css' />
    <link rel="shortcut icon" href="{{ asset('storage/'. settings()->site_favicon) }}" type="image/jpg" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Yanone+Kaffeesatz&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('front/vendors/styles/icon-font.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body>
    <header>
        <x-header-carrousel />
    </header>

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        new Swiper(".mySwiper", {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
        })
        document.getElementById('nav-toggle').addEventListener('click', function () {
            document.getElementById('nav-menu').classList.toggle('show');

        });

        $(document).ready(function(){ irArriba(); }); //Hacia arriba

        // FUNCION PARA IR ARRIBA
        function irArriba(){
            $('.ir-arriba').click(function(){ $('body,html').animate({ scrollTop:'0px' },1000); });
            $(window).scroll(function(){
                if($(this).scrollTop() > 0){ $('.ir-arriba').slideDown(600); }else{ $('.ir-arriba').slideUp(600); }
            });
            $('.ir-abajo').click(function(){ $('body,html').animate({ scrollTop:'1000px' },1000); });
        }
        document.addEventListener("DOMContentLoaded", function() {
            var lazyImages = [].slice.call(document.querySelectorAll("img[data-src]"));

            if ("IntersectionObserver" in window) {
                let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            let lazyImage = entry.target;
                            lazyImage.src = lazyImage.dataset.src;
                            lazyImage.classList.remove("lazy");
                            lazyImageObserver.unobserve(lazyImage);
                        }
                    });
                });

                lazyImages.forEach(function(lazyImage) {
                    lazyImageObserver.observe(lazyImage);
                });
            }
        });
    </script>
</body>
</html>
