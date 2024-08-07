<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LogRequests;
use App\Http\Controllers\GifController;

Route::group(['middleware' => [LogRequests::class, 'auth:api']], function () {
    // Favoritos
    Route::get('/favorites', [GifController::class, 'getFavorites']);
    Route::post('/favorite', [GifController::class, 'addToFavorite']);

    // Busqueda
    Route::get('/{ghipyId}', [GifController::class, 'getById']);
    Route::post('/paginate', [GifController::class, 'paginate']);
});
