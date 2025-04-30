<div>
    <article class="card">
        <h3>Temes</h3>
        <ul id="temas">
            @foreach (category() as $category)
                <li><a href="{{ route('category', $category->slug) }}">{{$category->name}} </a><small class="posts">{{$category->posts->count()}}</small></li>
            @endforeach
        </ul>
    </article>

    {{-- seleccionar categoria quan estiga article unic --}}
</div>
