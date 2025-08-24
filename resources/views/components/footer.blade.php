<div>
    <footer class="pie-pagina">
        <div class="grupo-1">
            <div class="box">
                <figure>
                    <a href="/">
                        <img src="{{ asset('/front/img/logo1.png') }}" alt="Cronsista de Gata de Gorgos">
                    </a>
                </figure>
            </div>
            <div class="box">
                <h2>Qui soc?</h2>
                <p>
                    {!!Str::ucfirst(words($user->bio, 55))!!}
                </p>
            </div>
            <div class="box">
                <h2>Segueix-me en</h2>
                <div class="red-social">
                    <a href="{{$links[0]['facebook_url']}}" class="fa fa-facebook"></a>
                    <a href="{{$links[0]['instagram_url']}}" class="fa fa-instagram"></a>
                </div>
            </div>
        </div>
        <div class="grupo-2">
            <small>&copy; {{ date('Y')}} <b>Cronista de Gata de Gorgos</b> - Todos los Derechos Reservados.</small>
        </div>
    </footer>
</div>
