<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\DibujanteController;
use App\Http\Controllers\EditorialController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\GeneroController;
use App\Http\Controllers\TomoController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de recursos básicas
Route::resource('autores', AutorController::class);
Route::resource('dibujantes', DibujanteController::class);
Route::resource('editoriales', EditorialController::class);
Route::resource('mangas', MangaController::class)->except(['update']);
Route::resource('tomos', TomoController::class)->except(['show']);
Route::resource('generos', GeneroController::class);

// Ruta para actualizar un manga (como se excluyó en el resource)
Route::put('/mangas/{id}', [MangaController::class, 'update'])->name('mangas.update');

// Ruta para actualizar múltiples stocks de tomos
Route::put('/tomos/updateMultipleStock', [TomoController::class, 'updateMultipleStock'])->name('tomos.updateMultipleStock');

// 🔍 Ruta para ver los tomos de un manga específico (usada por el botón "Ver Tomos" en el listado de mangas en la sección de tomos)
Route::get('/mangas/{id}/tomos', [TomoController::class, 'porManga'])->name('tomos.por_manga');

// Otras rutas (listado global de tomos, tomos con stock bajo, etc.)
Route::get('/tomos/listado', [TomoController::class, 'listado'])->name('tomos.listado');
Route::get('/tomos/stock-bajo', [TomoController::class, 'stockBajoGlobal'])->name('tomos.stock_bajo_global');
Route::get('/public/tomos', [TomoController::class, 'indexPublic'])->name('public.tomos');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
