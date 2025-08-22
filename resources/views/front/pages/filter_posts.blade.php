@extends('front.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('main')
    <div class="card">
        <h2 style="text-align: center">{{$pageTitle}}</h2>
    </div>

    @if ($posts->count() > 0)
        @foreach ($posts as $post)
            <article class="card">
                <h2 class="post-title">{{ date_format($post->created_at, 'd/m/Y') }} <a class="link post-title" href="{{ route('post', $post->slug) }}">{{ $post->title }}</a></h2>
                <h5 class="temas">en
                    <a class="link" href="{{ route('category', $post->post_category->slug) }}">
                        {{ $post->post_category->name }}
                    </a>
                    <i class="ti-timer mr-1"> </i> {{readDuration($post->title, $post->content)}} @choice('min|mins', readDuration($post->title, $post->content))
                </h5>
                <div class="fakeimg">
                    @php
                        $imagePath = public_path('images/posts/resized/resized_' . $post->featured_image);
                        $imageUrl = asset('images/posts/resized/resized_' . $post->featured_image);
                    @endphp

                    @if (file_exists($imagePath))
                        <img src="{{ $imageUrl }}" alt="{{ $post->title }}" id="myImg" onclick="openModal(this)" loading="lazy">
                    @else
                        <img src="{{ asset('images/posts/' . $post->featured_image) }}" alt="{{ $post->title }}" id="myImg" onclick="openModal(this)" loading="lazy">
                    @endif
                </div>
                {!!Str::ucfirst(words($post->content, 60))!!}
                <a class="link post-tema" href="{{ route('post', $post->slug) }}">Llegir més</a>
            </article>
        @endforeach
    @else
        <div class="card">
            <h3>No hi ha cap article amb el filtre seleccionat.</h3>
        </div>
    @endif

    {{$posts->appends(request()->input())->links('components.custom_pagination')}}
@endsection
