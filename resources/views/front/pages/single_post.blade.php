@extends('front.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('main')
    <article class="card">
        <header class="post-header">
            <h2 class="post-title">{{$post->title}}</h2>
            <div class="post-meta">
                <span class="post-date">Publicat el: {{date_format($post->created_at,'d/m/Y')}}</span>
                <span>Categoría: <a class="categoria" href="{{ route('category', $post->post_category->slug) }}" title="">{{$post->post_category->name}}</a></span>
                <span class="post-read-time">Temps de lectura: {{readDuration($post->title, $post->content)}} @choice('min|mins', readDuration($post->title, $post->content))</span>
            </div>
        </header>
        <div class="fakeimg">
            @if ($post->featured_image != null && $post->featured_image != 'sin_imagen.jpg')
                <img src="{{ asset('storage/images/posts/'. $post->featured_image) }}" alt="{{ $post->title }}" id="myImg" onclick="openModal(this)" style="width:100%;max-width:600px">
            @endif
        </div>
        <div class="texto">
            @if($post->content)
                {!!$post->content !!}
            @endif
        </div>
        {{-- Carrusel de imágenes --}}
        @if ($post->images->count() > 0)
            <div id="gallery">
                <div class="gallery-container">
                    @foreach ($post->images as $image)
                        <figure class="gallery-item" >
                            <img src="{{ asset('storage/images/posts/carousel/resized/resized_' . $image->image_name) }}" alt="{{ $post->title }}" id="myImg" onclick="openModal(this)">
                        </figure>
                    @endforeach
                </div>
                <nav class="gallery-navigation">
                    <button class="nav-button prev-button"><span>&#60;</span></button>
                    <button class="nav-button next-button"><span>&#62;</span></button>
                </nav>
            </div>
        @endif
        <div class="share-buttons-container">
            <div class="share-list">
                <!-- FACEBOOK -->
                <a class="fb-h" onclick="return fbs_click()" target="_blank">
                    <img src="{{ asset('front/img/facebook-logo.png') }}" alt="Facebook">
                    <span class="button-text">Compartir</span>
                </a>
            </div>
        </div>
        <x-modal />
    </article>
    <div class="prev-next-posts mt-4 mb-4 p-3">
        <div class="nav-links">
            @if($prevPost)
                <div class="nav-item text-start">
                    <span class="label">&laquo; Anterior</span>
                    <a href="{{ route('post', $prevPost->slug) }}"
                       title="Ver post anterior: {{ $prevPost->title }}"
                       aria-label="Post anterior: {{ $prevPost->title }}"
                       rel="prev"
                       class="titlePost">
                        {{ $prevPost->title }}
                    </a>
                </div>
            @else
                <div class="nav-item"></div>
            @endif

            @if($nextPost)
                <div class="nav-item text-end">
                    <span class="label">Siguiente &raquo;</span>
                    <a href="{{ route('post', $nextPost->slug) }}"
                       title="Ver post siguiente: {{ $nextPost->title }}"
                       aria-label="Post siguiente: {{ $nextPost->title }}"
                       rel="next"
                       class="titlePost">
                        {{ $nextPost->title }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    @if($relatedPosts->count())
        <div class="related-posts mt-5">
            <h3 style="text-align: center">Articles Relacionats</h3>
            <div class="related-posts-grid">
                @foreach($relatedPosts as $related)
                    <div class="related-post-card">
                        <a href="{{ route('post', $related->slug) }}" title="{{ $related->title }}">
                            @if($related->featured_image)
                                <img src="{{ asset('storage/images/posts/' . $related->featured_image) }}" alt="{{ $related->featured_image }}" class="related-img">
                            @endif
                            <h5 class="related-title">{{ $related->title }}</h5>
                        </a>
                        <div class="related-content">
                            <p class="related-date">
                                {{ $related->created_at->format('d M Y') }}
                            </p>
                            <p class="related-excerpt">
                                {{ Str::limit(strip_tags($related->content), 120) }}
                            </p>
                            <a href="{{ route('post', $related->slug) }}" class="btn btn-primary">
                                Llegir més
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    <script src="{{ asset('front/js/slider.js') }}"></script>
    <script>
        // function fbs_click() {
        //     u = location.href;
        //     t = document.title;
        //     window.open('http://www.facebook.com/sharer.php?u=' + encodeURIComponent(u) + '&t=' + encodeURIComponent(t),
        //         'sharer',
        //         'toolbar=0,status=0,width=626,height=436');
        //     return false;
        // }
        var pageLink = "{{ url()->current() }}";
        var pageTitle = "{{ addslashes($post->title) }}";
        function fbs_click() {
            window.open(`http://www.facebook.com/sharer.php?u=${pageLink}&quote=${pageTitle}`, 'sharer', 'toolbar=0,status=0,width=626,height=436');
            return false;
        }
    </script>
@endsection
