<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MoviePageController;
use App\Http\Controllers\FavoritePageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Moviesiae
|--------------------------------------------------------------------------
| File ini: routes/web.php
|
| PENTING — Penyebab bug favorites ke API:
| Selama ini link/button di navbar mengarah ke '/api/favorites' (route API).
| Seharusnya mengarah ke '/favorites' (route web di file ini).
|
| Aturan:
| - routes/web.php  → Halaman HTML/Blade untuk user
| - routes/api.php  → Endpoint JSON untuk data (tetap dipakai di belakang layar)
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('movies')->group(function () {
    Route::get('/', [MoviePageController::class, 'index'])->name('movies.index');
    Route::get('/{id}', [MoviePageController::class, 'show'])
        ->where('id', '[0-9]+')
        ->name('movies.show');
});

// ✅ Route web untuk halaman Favorites (BUKAN /api/favorites)
Route::prefix('favorites')->group(function () {
    Route::get('/', [FavoritePageController::class, 'index'])->name('favorites.index');
    Route::post('/', [FavoritePageController::class, 'store'])->name('favorites.store');
    Route::delete('/{id}', [FavoritePageController::class, 'destroy'])->name('favorites.destroy');
});
