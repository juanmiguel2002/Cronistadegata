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
        {{-- <div id="myModal" class="modal">
            <span class="close" onclick="closeModal()">×</span>
            <div class="imagen">
                <a href="https://gatadegorgos.cronista.blog/storage/upload/TioPepeLluís.jpg" target="_blank">
                    <img class="modal-content" id="img01">
                </a>
            </div>
            <div id="caption"></div>
        </div> --}}
    </article>
@endsection
