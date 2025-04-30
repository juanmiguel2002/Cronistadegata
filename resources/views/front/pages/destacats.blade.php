@extends('front.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('main')
    @if (!empty($posts))
        @foreach ($posts as $post)
            <article class="card">
                <h2 class="card-title">{{ date_format($post->created_at, 'd/m/Y') }} <a class="link" href="{{ route('post', $post->slug) }}">{{ $post->title }}</a></h2>
                <h5 class="temas">
                    en<strong>
                        <a class="link" href="{{ route('category', $post->post_category->slug) }}">
                            {{ $post->post_category->name }}
                        </a>
                    </strong>
                    <i class="ti-timer mr-1"></i>
                    {{ readDuration($post->title, $post->content) }}
                    @choice('min|mins', readDuration($post->title, $post->content)) -
                    <span class="visitas">{{ $post->visitas }} @choice('visita|visitas', $post->visitas)</span>
                </h5>

                <div class="fakeimg">
                    <img src="{{ asset('images/posts/' . $post->featured_image) }}" alt="{{ $post->featured_image }}">
                </div>

                {!!Str::ucfirst(words($post->content, 65))!!}
                <a class="link" href="{{ route('post', $post->slug) }}">Llegir més</a>
            </article>
        @endforeach
        {{$posts->appends(request()->input())->links('components.custom_pagination')}}
    @endif
@endsection
