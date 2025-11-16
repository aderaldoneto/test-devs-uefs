<?php

use App\Http\Controllers\Web\PostController;
use App\Http\Controllers\Web\TagController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'      => Route::has('login'),
        'canRegister'   => Route::has('register'),
        'laravelVersion'=> Application::VERSION,
        'phpVersion'    => PHP_VERSION,
    ]);
})->name('welcome');

Route::get('/users', [UserController::class, 'index'])
    ->name('users.index');

Route::get('/tags', [TagController::class, 'index'])
    ->name('tags.index');

Route::get('/posts', [PostController::class, 'index'])
    ->name('posts.index');

Route::middleware('auth')
    ->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');
        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');
        Route::get('/users/edit/{user}', [UserController::class, 'edit'])
            ->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy'); 

        Route::get('/tags/create', [TagController::class, 'create'])
            ->name('tags.create');
        Route::post('/tags', [TagController::class, 'store'])
            ->name('tags.store');
        Route::get('/tags/edit/{tag}', [TagController::class, 'edit'])
            ->name('tags.edit');
        Route::put('/tags/{tag}', [TagController::class, 'update'])
            ->name('tags.update');
        Route::delete('/tags/{tag}', [TagController::class, 'destroy'])
            ->name('tags.destroy'); 

        Route::get('/posts/create', [PostController::class, 'create'])
            ->name('posts.create');
        Route::post('/posts', [PostController::class, 'store'])
            ->name('posts.store');
        Route::get('/posts/{post}/edit', [PostController::class, 'edit'])
            ->name('posts.edit');
        Route::put('/posts/{post}', [PostController::class, 'update'])
            ->name('posts.update'); 
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])
            ->name('posts.destroy'); 

        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');
});

Route::get('/posts/{post}', [PostController::class, 'show'])
    ->name('posts.show');

Route::get('/tags/{tag}', [TagController::class, 'show'])
    ->name('tags.show');

require __DIR__.'/auth.php';
