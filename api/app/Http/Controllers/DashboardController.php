<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use App\Models\Editorial;
use App\Models\Autor;
use App\Models\Dibujante;
use App\Models\Tomo; // Asegúrate de tener definido este modelo

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener las cantidades de cada entidad
        $mangasCount      = Manga::count();
        $editorialesCount = Editorial::count();
        $autoresCount     = Autor::count();
        $dibujantesCount  = Dibujante::count();
        $tomosCount       = Tomo::count();

        // Obtener los registros recientes (por ejemplo, los 5 últimos) para mostrar en el card
        $mangas      = Manga::latest()->take(5)->get();
        $editoriales = Editorial::latest()->take(5)->get();
        $autores     = Autor::latest()->take(5)->get();
        $dibujantes  = Dibujante::latest()->take(5)->get();
        $tomos       = Tomo::latest()->take(5)->get();

        // Pasar los datos a la vista
        return view('dashboard', compact(
            'mangasCount', 'editorialesCount', 'autoresCount', 'dibujantesCount', 'tomosCount',
            'mangas', 'editoriales', 'autores', 'dibujantes', 'tomos'
        ));
    }
}
