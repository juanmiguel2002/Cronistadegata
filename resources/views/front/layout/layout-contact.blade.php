<!DOCTYPE html>
<html lang="es-es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    @yield('meta_tags')
    <title>@yield('pageTitle')</title>
    <link rel="stylesheet" href="{{ asset('front/css/contact.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('front/css/footer.css') }}">
    <link rel="shortcut icon" href="{{ asset('storage/'. settings()->site_favicon) }}" type="image/jpg" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Yanone+Kaffeesatz&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('front/vendors/styles/icon-font.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>


</head>

<body>
    <main>
        @yield('main')
    </main>

    <x-footer />
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {!! NoCaptcha::renderJs() !!}
</body>
</html>
