<div>
    <div class="pd-20 card-box mb-30">
        <div class="row mb-20">
            <div class="col-md-4">
                <label for="search"><b class="text-secondary">Buscar</b>:</label>
                <input class="form-control" type="text" wire:model.live='search' id="search" placeholder="Buscar artícle...">
            </div>
            @if (auth()->user()->type == 'superAdmin')
                <div class="col-md-2">
                    <label for="author"><b class="text-secondary">Author</b>:</label>
                    <select wire:model.live='user' class="custom-select form-control" id="author">
                        <option value="">Select Author</option>
                        @foreach (App\Models\User::whereHas('posts')->get() as $user)
                            <option value="{{ $user->id }}">{{$user->name}}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="col-md-2">
                <label for="category"><b class="text-secondary">Categoría</b>:</label>
                <select wire:model.live='category' class="custom-select form-control" id="category">
                    <option value="">Selecciona Categoría</option>
                    {!! $categories_html !!}
                </select>
            </div>
            <div class="col-md-2">
                <label for="visibility"><b class="text-secondary">Visibilitat</b>:</label>
                <select wire:model.live='visibility' class="custom-select form-control" id="visibility">
                    <option value="">Selecciona visibilitat</option>
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="visibility"><b class="text-secondary">Ordenar</b>:</label>
                <select wire:model.live='sort' class="custom-select form-control" id="sort">
                    <option value="asc">ASC</option>
                    <option value="desc">DESC</option>
                </select>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-auto table-sm">
                <thead class="bg-secondary text-white">
                    <th scope="col">ID</th>
                    <th scope="col">Image</th>
                    <th scope="col">Titol</th>
                    <th scope="col">Author</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Visibility</th>
                    <th scope="col">Accions</th>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <td scope="row">{{$post->id}}</td>
                            <td>
                                <img src="/images/posts/{{$post->featured_image}}" width="100">
                            </td>
                            <td><a href="{{ route('post', ['slug'=>$post->slug]) }}" target="_black">{{ $post->title }}</a></td>
                            <td>{{ $post->user->name }}</td>
                            <td>{{ $post->post_category->name }}</td>
                            <td>
                                @if ($post->visibility == 1)
                                    <span class="badge badge-pill badge-success">
                                        <i class="icon-copy ti-world"></i> Public
                                    </span>
                                @else
                                    <span class="badge badge-pill badge-warning">
                                        <i class="icon-copy ti-lock"></i> Private
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.editPost', ['id'=> $post->id ]) }}" data-color="#265ed7" style="color: rgb(38, 94, 215)">
                                        <i class="icon-copy dw dw-edit2"></i>
                                    </a>
                                    <a href="javascript:;" wire:click='deletePost({{$post->id}})' data-color="#e95959" style="color: rgb(233, 89, 89)">
                                        <i class="icon-copy dw dw-delete-3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <span class="text-danger">No posts </span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="block mt-1">
            {{$posts->links('livewire::simple-bootstrap')}}
        </div>
    </div>
</div>
