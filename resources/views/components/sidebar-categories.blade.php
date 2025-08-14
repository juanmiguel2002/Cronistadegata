<div>
    <article class="card">
        <h3>Temes</h3>
        <ul id="temas">
            @foreach (category() as $category)
                @php
                    $text = wordwrap($category->name, 35, "<br>", true);
                @endphp
                <li><a href="{{ route('category', $category->slug) }}">{!! $text !!} </a>
                    <small class="posts">{{$category->posts->count()}}</small>
                </li>
            @endforeach
        </ul>
    </article>

    {{-- seleccionar categoria quan estiga article unic --}}
</div>
