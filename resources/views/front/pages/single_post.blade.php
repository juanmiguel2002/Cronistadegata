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
            <img src="/images/posts/{{ $post->featured_image }}" alt="{{ $post->featured_image }}">
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
@endsection
