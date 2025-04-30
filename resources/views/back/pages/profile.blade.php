@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Perfil</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Inici</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Perfil
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    @livewire('admin.profile')
@endsection
@push('scripts')
    <script>
       $('input[type="file"][id="profilePicture"]').kropify({
        preview:'image#profilePicturePreview',
        viewMode:1,
        aspectRatio:1,
        cancelButtonText:'Cancel',
        resetButtonText:'Reset',
        cropButtonText:'Crop & update',
        processURL:'{{ route("admin.updateProfilePicture") }}',
        maxSize:2097152, //2MB
        // showLoader:true,
        // animationClass:'headShake', //headShake, bounceIn, pulse
        fileName: 'profilePicture',
        success:function(data){
            Swal.fire({
                icon: data.type, // 'success', 'error', 'warning', 'info', 'question'
                title: data.message,
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
          },
          errors:function(error, text){
             console.log(text);
          },
        });
    </script>
@endpush
