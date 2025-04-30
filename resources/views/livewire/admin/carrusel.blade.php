<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-blue h4">Gestió del Carrusel</h4>
                    </div>
                    <div class="pull-right">
                        <button data-toggle="modal" data-target="#carrusel" class="btn btn-primary btn-sm rounded">Añadir Imágenes</button>
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-borderless table-striped table-sm">
                        <thead class="bg-secondary text-white">
                            <th>#</th>
                            <th>Imatge</th>
                            <th>Títol</th>
                            <th>Orden</th>
                            <th>Actions</th>
                        </thead>
                        <tbody wire:sortable="updateOrder">
                            @forelse ($images as $image)
                                <tr wire:sortable-item="{{ $image->id }}">
                                    <td>{{ $image->id }}</td>
                                    <td><img src="{{ asset('storage/' . $image->image_path) }}" width="125px"></td>
                                    <td>{{ $image->title }}</td>
                                    <td>{{ $image->orden }}</td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="javascript:;" wire:click='editParentCategory({{$image->id}})' class="text-primary mx-2">
                                                <i class="dw dw-edit2"></i> Editar</a>
                                            <a href="javascript:;" wire:click='deleteParentCategory({{$image->id}})' class="text-danger mx-2">
                                                <i class="dw dw-delete-3"></i> Eliminar</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="5">
                                        <span class="text-info">No hi ha img!</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- modal añadir img --}}
    <div wire:ignore.self class="modal fade" id="carrusel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" wire:submit="addImages">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Subir Imágenes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div>
                        <label>Seleccionar Imágenes:</label>
                        <input type="file" wire:model="newImages" multiple>
                        @error('newImages.*') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mt-4">
                        @foreach ($newImages as $index => $image)
                            <div class="mt-2">
                                <label>Título para la imagen {{ $index + 1 }}:</label>
                                <input type="text" wire:model="titles.{{ $index }}" class="border rounded w-full p-1">
                                @error('titles.' . $index) <span class="error text-red-500">{{ $message }}</span> @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Subir Imágenes</button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            Livewire.on('closeModal', () => {
                $('#carrusel').modal('hide');
            });
        </script>
    @endpush
</div>

