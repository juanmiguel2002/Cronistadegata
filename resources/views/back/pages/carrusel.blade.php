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
