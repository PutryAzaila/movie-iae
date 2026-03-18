<?php

use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\MovieController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Film Catalog API berjalan dengan baik 🎬',
        'version' => '1.0.0',
        'endpoints' => [
            'movies'    => url('/api/movies'),
            'favorites' => url('/api/favorites'),
        ],
    ]);
});
Route::prefix('movies')->name('api.movies.')->group(function () {
    Route::get('/', [MovieController::class, 'index'])->name('index');
    Route::get('/popular', [MovieController::class, 'popular'])->name('popular');
    Route::get('/trending', [MovieController::class, 'trending'])->name('trending');
    Route::get('/search', [MovieController::class, 'search'])->name('search');
    Route::get('/{id}', [MovieController::class, 'show'])
        ->where('id', '[0-9]+') 
        ->name('show');
});
Route::prefix('favorites')->name('api.favorites.')->group(function () {
    Route::get('/', [FavoriteController::class, 'index'])->name('index');
    Route::post('/', [FavoriteController::class, 'store'])->name('store');
    Route::delete('/{id}', [FavoriteController::class, 'destroy'])
        ->where('id', '[0-9]+')
        ->name('destroy');
});
