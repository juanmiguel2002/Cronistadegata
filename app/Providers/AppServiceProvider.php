<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        RedirectIfAuthenticated::redirectUsing(function(){
            return route('admin.dashboard'); // Redirect authenticated users to dashboard route instead of login page
        });

        Authenticate::redirectUsing(function(){
            Session::flash('fail', 'No has iniciado sesión. Por favor, inicia sesión para continuar.');
            return route('admin.login');
        });

        ResetPassword::createUrlUsing(function ($user, string $token) {
            return route('admin.reset', ['token' => $token, 'email' => $user->email]);
        });


    }
}
