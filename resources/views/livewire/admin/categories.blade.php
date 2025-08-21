<div>
    <div class="row">
        <div class="col-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-blue h4">Categories Pare</h4>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click='addParentCategory()' class="btn btn-primary btn-sm">Nova Categoría Pare</a>
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-borderless table-striped table-sm">
                        <thead class="bg-secondary text-white">
                            <th>#</th>
                            <th>Nom</th>
                            <th>Nº de Categoríes</th>
                            <th>Accions</th>
                        </thead>
                        <tbody id="sortable_categories">
                            @forelse ($parentCategories as $item)
                                <tr data-index="{{$item->id}}" data-order="{{$item->order}}">
                                    <td>{{$item->id}}</td>
                                    <td>
                                        <a style="cursor: pointer" href="{{ route('admin.posts', ['category'=>$item->id]) }}">{{$item->name}}</a>
                                    </td>
                                    <td>{{ $item->categoria->count() }}</td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="javascript:;" wire:click='editParentCategory({{$item->id}})' class="text-primary mx-2">
                                                <i class="dw dw-edit2"></i> Editar</a>
                                            <a href="javascript:;" wire:click='deleteParentCategory({{$item->id}})' class="text-danger mx-2">
                                                <i class="dw dw-delete-3"></i> Eliminar</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="4">
                                        <span class="text-info ">No hi ha categoríes pare!</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-block mt-1 text-center">
                    {{ $parentCategories->links('livewire::simple-bootstrap') }}
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-blue h4">Categoríes</h4>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click='addCategory' class="btn btn-primary btn-sm">Nova Categoría</a>
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-borderless table-striped table-sm">
                        <thead class="bg-secondary text-white">
                            <th>#</th>
                            <th>Nom</th>
                            <th>Categories Pare</th>
                            <th>Nº d'artícles</th>
                            <th>Accions</th>
                        </thead>
                        <tbody id="sortable_parent_categories">
                            @forelse ($categories as $item)
                                <tr data-index="{{$item->id}}" data-order="{{$item->order}}">
                                    <td>{{$item->id}}</td>
                                    <td><a style="cursor: pointer" href="{{ route('admin.posts', ['category'=>$item->id]) }}">{{$item->name}}</a></td>
                                    <td>{{ $item->parentCategory ? $item->parentCategory->name : 'Sin categoría'}}</td>
                                    <td>{{$item->posts->count()}}</td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="javascript:;" wire:click='editCategory({{$item->id}})' class="text-primary mx-2">
                                                <i class="dw dw-edit2"></i> Editar</a>
                                            <a href="javascript:;" wire:click='deleteCategory({{$item->id}})' class="text-danger mx-2">
                                                <i class="dw dw-delete-3"></i> Eliminar</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <span class="text-info">No hi ha categoríes!</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-block mt-1 text-center">
                    {{ $categories->links('livewire::simple-bootstrap') }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODALS --}}
    <div wire:ignore.self class="modal fade" id="pcategory_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" wire:submit='{{ $isUpdatePCategory ? 'updateParentCategory()' : 'createParentCategory()' }}'>
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        {{ $isUpdatePCategory ? 'Actualitzar Categoría P.' : 'Nova Categoría P.' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    @if ($isUpdatePCategory)
                        <input type="hidden" wire:model='pcategory_id'>
                    @endif
                    <div class="form-group">
                        <label for="">Nom</label>
                        <input type="text" class="form-control" wire:model='pcategory_name' placeholder="Nom categoría Pare">
                        @error('pcategory_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Tancar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdatePCategory ? 'Actualitzar' : 'Crear' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="category_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" wire:submit='{{ $isUpdateCategory ? 'updateCategory()' : 'createCategory()' }}'>
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        {{ $isUpdateCategory ? 'Actualitzar Categoría' : 'Nova Categoría' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    @if ($isUpdateCategory)
                        <input type="hidden" wire:model='category_id'>
                    @endif
                    <div class="form-group">
                        <label for=""><strong>Categoría P.:</strong></label>
                        <select wire:model='parent' class="custom-select">
                            <option value="0">Selecciona Categoría P.</option>
                            @foreach ($parentCategories as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                        @error('parent') <span class="text-danger">{{ $message }}</span> @enderror

                    </div>
                    <div class="form-group">
                        <label for="">Nom Categoría</label>
                        <input type="text" class="form-control" wire:model='category_name' placeholder="Nom Categoría">
                        @error('category_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Tancar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdateCategory ? 'Actualitzar' : 'Crear' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
