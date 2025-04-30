<div>
    <div class="tab">
        <ul class="nav nav-tabs customtab" role="tablist">
            <li class="nav-item">
                <a wire:click='selectTab("general_settings")' class="nav-link {{ $tab == 'general_settings' ? 'active' : '' }}" data-toggle="tab" href="#general_settings" role="tab" aria-selected="true">Ajustes Generales</a>
            </li>
            <li class="nav-item">
                <a wire:click='selectTab("logo_favicon")' class="nav-link {{ $tab == 'logo_favicon' ? 'active' : '' }}" data-toggle="tab" href="#logo_favicon" role="tab" aria-selected="false">Logo & Favicon</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade {{ $tab == 'general_settings' ? 'active show' : ''}}" id="general_settings" role="tabpanel">
                <div class="pd-20">
                    <form wire:submit='updateSiteInfo()'>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Nombre sitio</strong></label>
                                    <input type="text" class="form-control" placeholder="Site Name" wire:model='site_name'>
                                    @error('site_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Site email</strong></label>
                                    <input type="text" class="form-control" placeholder="Enter site Email" wire:model='site_email'>
                                    @error('site_email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>Site Meta Keywords</strong><small> (Opcional)</small></label>
                                    <input type="text" class="form-control" placeholder="Site Meta Keywords" wire:model='site_meta_keywords'>
                                    @error('site_meta_keywords') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for=""><strong>Site Meta Description</strong><small> (Opcional)</small></label>
                            <textarea class="form-control" wire:model='site_meta_description' cols="4" rows="4" placeholder="Type site meta description..."></textarea>
                            @error('site_meta_description') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
            <div class="tab-pane fade {{ $tab == 'logo_favicon' ? 'active show' : '' }}" id="logo_favicon" role="tabpanel">
                <div class="pd-20">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Site Logo</h6>
                            <div class="mb-2 mt-1" style="max-width: 200px;">
                                <!-- Placeholder para previsualizar la imagen -->
                                <img src="{{ $current_logo ?? '' }}" alt="" class="img-thumbnail" id="preview-site-logo">
                            </div>
                            <form wire:submit.prevent="uploadSiteLogo" method="POST" enctype="multipart/form-data" id="updateLogoForm">
                                @csrf
                                <div class="mb-2">
                                    <!-- Input para cargar la imagen -->
                                    <input type="file" class="form-control" id="site-logo" wire:model="site_logo" accept="image/*">
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">Change Logo</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <h6>Site Favicon</h6>
                            <div class="mb-2 mt-1" style="max-width: 100px;">
                                <!-- Previsualización del favicon actual o predeterminado -->
                                <img src="{{ $current_favicon ?? '' }}" class="img-thumbnail" id="preview-site-favicon" />
                            </div>
                            <form wire:submit.prevent="uploadFavicon" method="POST" enctype="multipart/form-data" id="updateFaviconForm">
                                <div class="mb-2">
                                    <!-- Input para cargar el nuevo favicon -->
                                    <input type="file" class="form-control" id="site-favicon" wire:model="site_favicon" >
                                    @error('favicon')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">Change Favicon</button>
                            </form>
                            @if (session()->has('success'))
                                <div class="alert alert-success mt-3">
                                    {{ session('success') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
