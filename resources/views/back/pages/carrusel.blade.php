@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Carrusel </h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Inici</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Carrusel
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    @livewire('admin.carrusel')
@endsection
@push('scripts')
    <script>
        window.addEventListener('showModal', function(event) {
            var show = event.detail && event.detail.length ? event.detail[0] : true;
            if (show === false) {
                $('#carrusel').modal('hide');
            } else {
                $('#carrusel').modal('show');
            }
        });
        window.addEventListener('deleteImage', function(event) {
            var id = event.detail[0].id;
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('confirmDeleteImage', [id]);
                }
            });
        });
    </script>
@endpush
