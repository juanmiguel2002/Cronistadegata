@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Artícles</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Inici</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Artícles
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <a href="{{ route('admin.addPost') }}" class="btn btn-primary">
                    <i class="icon-copy bi bi-plus-circle"></i> Nou Article</a>
            </div>
        </div>
    </div>
    @livewire('admin.posts')
@endsection
@push('scripts')
    <script>
        window.addEventListener('deletePost', function(e) {
            var id = e.detail[0].id;
            Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Esta acción no se puede deshacer!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // El propio método deletePostAction() ya notifica el resultado
                        // (éxito o error) mediante el evento global 'showToastr'.
                        Livewire.dispatch('deletePostAction', [id]);
                    }
                });
        });
    </script>
@endpush
