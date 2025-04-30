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
                                    <label for="">Nº d'artícles per pàgina <i>(Inici)</i>:</label>
                                    <input type="number" wire:model="paginationLimit"  class="form-control" min="1" max="100">
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
                </div>
            </div>
        </div>
    </div>
</div>
