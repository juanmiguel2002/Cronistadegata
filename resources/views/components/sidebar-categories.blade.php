<div>
    <article class="card">
        <h3>Temes</h3>
        <ul id="temas">
            @foreach($pcategories as $pc)
                <li class="parent">
                    <button class="accordion">{{ $pc->name }}</button>
                    <ul class="children">
                        @foreach($pc->categoria as $c)
                            <li>
                                <a href="{{ route('category', $c->slug) }}">{{ $c->name }}</a>
                                <small class="posts">{{ $c->posts->count() }}</small>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach

            @foreach($categories as $c)
                <li>
                    <a class="parent-link" href="{{ route('category', $c->slug) }}">{{ $c->name }}</a>
                    <small class="posts">{{ $c->posts->count() }}</small>
                </li>
            @endforeach
        </ul>
    </article>
</div>
<script>
    document.querySelectorAll(".accordion").forEach(btn => {
        btn.addEventListener("click", () => {
            const panel = btn.nextElementSibling;
            panel.style.display = panel.style.display === "block" ? "none" : "block";
        });
    });
</script>
