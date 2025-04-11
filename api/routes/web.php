<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\DibujanteController;
use App\Http\Controllers\EditorialController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\GeneroController;
use App\Http\Controllers\TomoController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Agrupar todas las rutas protegidas por autenticación
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas protegidas
    Route::resource('autores', AutorController::class);
    Route::resource('dibujantes', DibujanteController::class);
    Route::resource('editoriales', EditorialController::class);
    Route::resource('mangas', MangaController::class)->except(['update']);
    Route::resource('tomos', TomoController::class)->except(['show']);
    Route::resource('generos', GeneroController::class);

    Route::put('/mangas/{id}', [MangaController::class, 'update'])->name('mangas.update');

    // Rutas adicionales protegidas
    Route::put('/tomos/updateMultipleStock', [TomoController::class, 'updateMultipleStock'])->name('tomos.updateMultipleStock');
    Route::get('/mangas/{id}/tomos', [TomoController::class, 'porManga'])->name('tomos.por_manga');
    Route::get('/tomos/listado', [TomoController::class, 'listado'])->name('tomos.listado');
});

// Ruta pública
Route::get('/public/tomos', [TomoController::class, 'indexPublic'])->name('public.tomos');
