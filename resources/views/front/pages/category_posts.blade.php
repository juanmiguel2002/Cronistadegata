@extends('front.layout.pages-layout')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('main')
    <div class="card">
        <h2 style="text-align: center">{{$pageTitle}}</h2>
    </div>
    @foreach ($posts as $post)
            <article class="card">
                <h2 class="post-title">{{ date_format($post->created_at, 'd/m/Y') }} <a class="link post-title" href="{{ route('post', $post->slug) }}">{{ $post->title }}</a></h2>
                <h5 class="temas">en <strong class="post-tema">{{ $post->post_category->name }} </strong>
                    <i class="ti-timer mr-1"> </i> {{readDuration($post->title, $post->content)}} @choice('min|mins', readDuration($post->title, $post->content))
                </h5>
                <div class="fakeimg">
                    @if ($post->featured_image)
                        <img src="{{ post_image_url($post->featured_image, 'resized') }}" alt="{{ $post->title }}" onclick="openModal(this)" loading="lazy">
                    @endif
                </div>
                {!!Str::ucfirst(words($post->content, 60))!!}
                <a class="link post-tema" href="{{ route('post', $post->slug) }}">Llegir més</a>
            </article>
        @endforeach
    @if ($posts->count() > 0)

    @else
        <div class="card">
            <h3>No hi ha articles relacionats amb aquesta categoria.</h3>
        </div>
    @endif

    {{$posts->appends(request()->input())->links('components.custom_pagination')}}
@endsection
