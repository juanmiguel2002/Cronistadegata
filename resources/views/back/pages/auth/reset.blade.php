@extends('back.layout.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@section('content')
    <div class="login-box bg-white box-shadow border-radius-10">
        <div class="login-title">
            <h2 class="text-center text-primary">Restablecer contraseña</h2>
        </div>
        <h6 class="mb-20">
            Introduzca su nueva contraseña
        </h6>
        <form action="{{ route('admin.resetPasswordHandler') }}" method="POST">
            <x-form-alerts />
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="input-group custom">
                <input type="email" class="form-control form-control-lg" placeholder="Email" name="email" value="{{ old('email', $email) }}">
                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
                </div>
            </div>
            @error('email')
                <span class="text-danger ml-1">{{ $message }}</span>
            @enderror
            <div class="input-group custom mt-2">
                <input type="password" class="form-control form-control-lg" placeholder="Nueva contraseña" name="password">
                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
                </div>
            </div>
            @error('password')
                <span class="text-danger ml-1">{{ $message }}</span>
            @enderror
            <div class="input-group custom mt-2">
                <input type="password" class="form-control form-control-lg" placeholder="Confirmar contraseña" name="password_confirmation">
                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
                </div>
            </div>
            <div class="row align-items-center mt-2">
                <div class="col-12">
                    <div class="input-group mb-0">
                        <input class="btn btn-primary btn-lg btn-block" type="submit" value="Restablecer contraseña">
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
