@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@section('content')
    @livewire('admin.categories')
@endsection
@push('scripts')
    <script>
        window.addEventListener('showParentCategoryModalForm', function() {
            $('#pcategory_modal').modal('show');
        });
        window.addEventListener('closePcategoria', function() {
            $('#pcategory_modal').modal('hide');
        });

        $('table tbody#sortable_parent_categories').sortable({
            cursor: 'move',
            opacity: 0.6,
            update: function(event, ui) {
                $(this).children().each(function(index) {
                    if ($(this).attr('data-order') != (index + 1)) {
                        $(this).attr('data-order', (index + 1)).addClass('updated');
                    }
                });
                var positions = [];
                $('.updated').each(function() {
                    positions.push([$(this).attr('data-index'), $(this).attr('data-order')]);
                    $(this).removeClass('updated');
                });
                Livewire.dispatch('updatePCategoryOrder', [positions]);
            }
        });

        window.addEventListener('showCategoryForm', function() {
            $('#category_modal').modal('show');
        });
        window.addEventListener('closeCategoria', function() {
            $('#category_modal').modal('hide');
        });

        $('table tbody#sortable_categories').sortable({
            cursor: 'move',
            opacity: 0.6,
            update: function(event, ui) {
                $(this).children().each(function(index) {
                    if ($(this).attr('data-order') != (index + 1)) {
                        $(this).attr('data-order', (index + 1)).addClass('updated');
                    }
                });
                var positions = [];
                $('.updated').each(function() {
                    positions.push([$(this).attr('data-index'), $(this).attr('data-order')]);
                    $(this).removeClass('updated');
                });
                Livewire.dispatch('updateCategoryOrder', [positions]);
            }
        });

        window.addEventListener('deleteParentCategory', function(event) {
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
                    Livewire.dispatch('confirmDeleteParentCategory', [id]);
                }
            });
        });
        window.addEventListener('deleteCategory', function(event) {
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
                    Livewire.dispatch('confirmDeleteCategory', [id]);
                }
            });
        });
    </script>
@endpush
