@extends('front.layout.layout-contact')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('main')
    <nav class="nav justify-content-start navbar p-2">
      <a class="nav-link" href="{{ route('home') }}">Inici</a>
      <a class="nav-link" href="{{ route('destacats') }}">Destacats</a>
      <a class="nav-link active" href="#">Contacte</a>
    </nav>
    <div class="container my-5">
        <div class="row contact-wrapper shadow-sm p-4 rounded bg-white animate__animated animate__fadeIn">
            <!-- Información de contacto -->
            <div class="col-md-5 mb-4 contact-info">
                <h2 class="mb-3">Contacte</h2>
                <p class="mb-4">Si tens alguna pregunta o comentari, no dubtes a posar-te en contacte amb mi.
                    Estaré encantat d'ajudar-te.</p>
                <p><i class="bi bi-envelope-fill me-2"></i> info@cronista.blog</p>

                <h5 class="mt-4">Xarxes socials</h5>
                <div class="social-icons mt-2">
                    <a href="https://www.facebook.com/cronista.degata"><i class="bi bi-facebook me-3"></i></a>
                    <a href="#"><i class="bi bi-instagram me-3"></i></a>
                </div>
            </div>

            <!-- Formulario -->
            <div class="col-md-7">
                <h3 class="mb-4">Envía un missatge</h3>
                <form action="{{ route('contacto.send') }}" method="POST" class="animate__animated animate__fadeInRight">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Tu nombre" required>
                        <label for="name">Nom</label>
                        @error('name')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="correo@ejemplo.com" required>
                        <label for="email">Email</label>
                        @error('email')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="message" name="message" style="height: 150px;" placeholder="Escribe tu mensaje" required></textarea>
                        <label for="message">Missatge</label>
                    </div>
                    <button type="submit" class="btn btn-primary px-4">Enviar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
