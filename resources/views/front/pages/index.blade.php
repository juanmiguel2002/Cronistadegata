@extends('front.layout.pages-layout')
{{-- @section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title') --}}
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection

@section('main')
    @if (!empty($posts))
        @foreach ($posts as $post)
            <article class="card">
                <h2 class="post-title">
                    <a class="link post-title" href="{{ route('post', $post->slug) }}">{{ date_format($post->created_at, 'd/m/Y') }} {{ $post->title }}</a>
                </h2>
                <h5 class="temas">en
                    <a class="post-tema link" href="{{ route('category', $post->post_category->slug) }}">{{ $post->post_category->name }}</a>
                    <i class="ti-timer mr-1"> </i> {{readDuration($post->title, $post->content)}} @choice('min|mins', readDuration($post->title, $post->content))
                </h5>
                <div class="image">
                    @if ($post->featured_image)
                        <img src="{{ post_image_url($post->featured_image, 'resized') }}" alt="{{ $post->title }}" onclick="openModal(this)" loading="lazy">
                    @endif
                </div>

                {!!Str::ucfirst(words($post->content, 65))!!}
                <a class="link post-tema" href="{{ route('post', $post->slug) }}" style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif">Llegir més</a>
            </article>
        @endforeach
        <x-modal />
        {{$posts->appends(request()->input())->links('components.custom_pagination')}}
    @endif
@endsection
