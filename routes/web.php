<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// FRONTEND ROUTES
Route::get('/', [BlogController::class, 'index'])->name('home');
Route::get('/post/{slug}', [BlogController::class, 'showPost'])->name('post');
Route::get('/posts/category/{slug}', [BlogController::class, 'showCategory'])->name('category');
Route::get('/search', [BlogController::class, 'showSearch'])->name('search');
Route::get('/posts/filter', [BlogController::class, 'filterPosts'])->name('posts.filter');
Route::get('/posts/destacats', [BlogController::class, 'showDestacats'])->name('destacats');

// ADMIN ROUTES

Route::prefix('admin')->name('admin.')->group(function (){
    Route::middleware(['guest','preventBackHistory'])->group(function(){
        Route::controller(AuthController::class)->group(function () {
            Route::get('/login', 'loginForm')->name('login');
            Route::post('/login', 'loginHandler')->name('login_handler');
            Route::get('/forgot-password', 'forgotForm')->name('forgot');
            Route::post('/send-password-link', 'sendPassword')->name('sendPasswordLink');
            Route::get('/reset-password/{token}', 'resetForm')->name('reset');
        });
    });

    Route::middleware(['auth', 'preventBackHistory'])->group(function(){
        Route::controller(AdminController::class)->group(function () {
            Route::get('/dashboard', 'adminDashboard')->name('dashboard');
            Route::post('/logout', 'logout')->name('logout');
            Route::get('/profile', 'profileView')->name('profile');
            Route::post('/updateProfilePicture', 'updateProfilePicture')->name('updateProfilePicture');
            Route::get('/categories', 'categoriesPage')->name('categories');
            Route::get('/personalizar', 'personalizar')->name('personalizar');
            Route::get('/carrusel', 'carrusel')->name('carrusel');
            
            Route::middleware(['onlySuperAdmin'])->group(function(){
                Route::get('/settings', 'generalSettings')->name('settings');
            });
        });

        Route::controller(PostController::class)->group(function () {
            Route::get('/posts', 'allPosts')->name('posts');
            Route::get('/post/new', 'addPost')->name('addPost');
            Route::post('/post/create', 'createPost')->name('createPost');
            Route::get('/post/{id}/edit', 'editPost')->name('editPost');
            Route::post('/post/update', 'updatePost')->name('updatePost');
        });
    });
});
