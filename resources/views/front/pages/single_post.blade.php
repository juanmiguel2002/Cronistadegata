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
                <span class="post-category">Categoría: <a class="categoria" href="{{ route('category', $post->post_category->slug) }}" title="">{{$post->post_category->name}}</a></span>
                <span class="post-read-time">Temps de lectura: {{readDuration($post->title, $post->content)}} @choice('min|mins', readDuration($post->title, $post->content))</span>
            </div>
        </header>
        <div class="fakeimg">
            @if ($post->featured_image != null && $post->featured_image != 'sin_imagen.jpg')
                <img src="/images/posts/{{ $post->featured_image }}" alt="{{ $post->title }}" id="myImg" onclick="openModal(this)" style="width:100%;max-width:600px">
            @endif
        </div>

        {{-- MODAL --}}
        <div id="myModal" class="modal" onclick="closeModal(event)">
            <span class="close" onclick="closeModal(event)">&times;</span>
            <div class="modal-content-wrapper">
                <a id="modalLink" href="#" target="_blank">
                    <img class="modal-content" id="img01">
                </a>
                <div id="caption"></div>
            </div>
        </div>
        <div class="texto">
            @if($post->content)
                {!!$post->content !!}
            @endif
        </div>
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
                                <img src="{{ asset('images/posts/' . $related->featured_image) }}" alt="{{ $related->featured_image }}" class="related-img">
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
                                Leer más
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection

<script>
    function openModal(img) {
        const modal = document.getElementById("myModal");
        const modalImg = document.getElementById("img01");
        const captionText = document.getElementById("caption");
        const modalLink = document.getElementById("modalLink");

        modal.style.display = "block";
        modalImg.src = img.src;
        captionText.innerHTML = img.alt;
        modalLink.href = img.src;
    }

    function closeModal() {
        var modal = document.getElementById('myModal');
        modal.style.display = "none";
    }
</script>


