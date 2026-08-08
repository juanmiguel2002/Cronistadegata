<div>
    <div class="tab">
        <ul class="nav nav-tabs customtab" role="tablist">
            <li class="nav-item">
                <a wire:click='selectTab("personalizar")' class="nav-link {{ $tab == 'personalizar' ? 'active' : '' }}" data-toggle="tab" href="#personalizar" role="tab" aria-selected="true">Personalitzar</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade {{ $tab == 'personalizar' ? 'active show' : ''}}" id="personalizar" role="tabpanel">
                <div class="pd-20">
                    @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                    <div class="row">
                        <div class="col-md-6">
                            <form wire:submit.prevent="updatePaginationLimit" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="paginationLimit">Nº d'artícles per pàgina <i>(Inici)</i>:</label>
                                    <input type="number" id="paginationLimit" wire:model="paginationLimit"  class="form-control" min="1" max="100">
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Guardar</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form wire:submit.prevent="updatePaginationMesVisitas" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="pagination">Nº d'artícles per pàgina <i>(Més Destacats)</i>:</label>
                                    <input type="number" wire:model="pagination_destacados"  class="form-control" min="1" max="100">
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Guardar</button>
                            </form>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-8">
                            <form wire:submit.prevent="updateResizedSize" method="POST">
                                @csrf
                                <label class="mb-2"><b>Mida de les miniatures dels llistats</b> <i>(resized)</i>:</label>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="resized_width">Amplada (px)</label>
                                        <input type="number" id="resized_width" wire:model="resized_width" class="form-control" min="50" max="2000">
                                        @error('resized_width') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                    <div class="form-group col">
                                        <label for="resized_height">Alçada (px)</label>
                                        <input type="number" id="resized_height" wire:model="resized_height" class="form-control" min="50" max="2000">
                                        @error('resized_height') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>
                                <small class="text-muted d-block mb-2">S'aplica a les imatges que es pugen a partir d'ara. Les existents mantenen la mida actual fins que es regenerin.</small>
                                <button type="submit" class="btn btn-primary mt-1">Guardar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
