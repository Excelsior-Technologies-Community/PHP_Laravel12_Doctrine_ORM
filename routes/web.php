<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');

Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

Route::get('/trash', [PostController::class, 'trash'])->name('posts.trash');

Route::get('/restore/{id}', [PostController::class, 'restore'])->name('posts.restore');

Route::delete('/force-delete/{id}', [PostController::class, 'forceDelete'])->name('posts.forceDelete');