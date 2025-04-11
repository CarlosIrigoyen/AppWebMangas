<?php

namespace App\Http\Controllers;

use App\Models\Tomo;
use App\Models\Manga;
use App\Models\Editorial;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TomoController extends Controller
{
    /**
     * index(): Listar únicamente los mangas.
     * Esta función se encargará de mostrar en pantalla (por ejemplo, en la vista "listadomangas.blade.php")
     * una lista de todos los mangas.
     */
    public function index()
    {
        $mangas = Manga::all();
          // Obtener todos los tomos con stock menor a 10 (sin depender de los filtros)
          $lowStockTomos = Tomo::where('stock', '<', 10)->with('manga')->get();
          $hasLowStock   = $lowStockTomos->isNotEmpty();
        return view('tomos.index', compact('mangas','lowStockTomos', 'hasLowStock'));

    }



    /**
     * porManga(): Mostrar la lista de tomos de un manga.
     *
     * Esta función se encargará de mostrar en pantalla (por ejemplo, en la vista "listado.blade.php")
     * una lista de todos los tomos de un manga, junto con los botones para crear uno nuevo.

     */
    public function porManga(Request $request, $id)
{
    $manga = Manga::findOrFail($id);

    // Consulta base de los tomos para el manga
    $query = Tomo::where('manga_id', $id)->orderBy('numero_tomo', 'asc');

    // Paginar 6 tomos por página y conservar los parámetros en la URL
    $tomos = $query->paginate(6)->appends($request->query());

    // (Se omite la verificación y redirección a la última página)

    // Obtener los tomos con stock bajo (menos de 10)
    $lowStockTomos = Tomo::where('manga_id', $id)
                         ->where('stock', '<', 10)
                         ->get();

    $editoriales = Editorial::all();
    $mangas = Manga::all(); // Para otros contextos si fuera necesario

    // Último tomo general (opcional para definir valor por defecto)
    $ultimoTomoGeneral = Tomo::where('manga_id', $id)
                             ->orderBy('numero_tomo', 'desc')
                             ->first();

    // Construir arreglos de apoyo por editorial
    $nextTomos = [];
    $ultimoTomosEditorial = [];
    foreach ($editoriales as $editorial) {
        $ultimoTomo = Tomo::where('manga_id', $id)
                          ->where('editorial_id', $editorial->id)
                          ->orderBy('numero_tomo', 'desc')
                          ->first();
        if ($ultimoTomo) {
            $nextNumber = $ultimoTomo->numero_tomo + 1;
            $nextDate = date('Y-m-d', strtotime('+1 month', strtotime($ultimoTomo->fecha_publicacion)));
            $nextTomos[$editorial->id] = ['numero' => $nextNumber, 'fecha' => $nextDate];
            $ultimoTomosEditorial[$editorial->id] = $ultimoTomo;
        } else {
            // Sin tomo previo: número 1 y sin fecha predeterminada
            $nextTomos[$editorial->id] = ['numero' => 1, 'fecha' => ''];
        }
    }

    $defaultEditorial = $ultimoTomoGeneral ? $ultimoTomoGeneral->editorial_id : '';

    return view('tomos.listado', compact(
        'manga',
        'tomos',
        'editoriales',
        'mangas',
        'nextTomos',
        'ultimoTomosEditorial',
        'defaultEditorial',
        'lowStockTomos'
    ));
}

    public function store(Request $request)
{
    $manga_id = $request->input('manga_id');

    // Obtener el último tomo para el manga seleccionado
    $lastTomo = Tomo::where('manga_id', $manga_id)
                    ->orderBy('numero_tomo', 'desc')
                    ->first();

    if ($lastTomo) {
        $nextNumero = $lastTomo->numero_tomo + 1;
        $minFecha = Carbon::parse($lastTomo->fecha_publicacion)
                         ->addMonth()
                         ->format('Y-m-d');
    } else {
        $nextNumero = 1;
        $minFecha = date('Y-m-d');
    }

    $rules = [
        'manga_id'          => 'required|exists:mangas,id',
        'editorial_id'      => 'required|exists:editoriales,id',
        'formato'           => 'required|in:Tankōbon,Aizōban,Kanzenban,Bunkoban,Wideban',
        'idioma'            => 'required|in:Español,Inglés,Japonés',
        'precio'            => 'required|numeric',
        'fecha_publicacion' => $lastTomo
                               ? "required|date|after_or_equal:$minFecha|before:" . date('Y-m-d')
                               : "required|date|before:" . date('Y-m-d'),
        'portada'           => 'required|image|max:2048',
        'stock'             => 'sometimes|numeric|min:0'
    ];

    $validated = $request->validate($rules);
    $validated['numero_tomo'] = $nextNumero;

    // Procesar la imagen (portada)
    if ($request->hasFile('portada')) {
        $file = $request->file('portada');
        $manga = Manga::findOrFail($manga_id);
        $mangaTitle = Str::slug($manga->titulo);
        $filename = "portada{$nextNumero}." . $file->getClientOriginalExtension();
        $destinationPath = public_path("tomo_portada/{$mangaTitle}");
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        $file->move($destinationPath, $filename);
        $validated['portada'] = "tomo_portada/{$mangaTitle}/" . $filename;
    }

    if (!isset($validated['stock'])) {
        $validated['stock'] = 0;
    }

    Tomo::create($validated);
    $redirectTo = $request->input('redirect_to', route('tomos.index'));
    return redirect($redirectTo)->with('success', 'Tomo creado exitosamente.');
}



    public function destroy($id, Request $request)
    {
        $tomo = Tomo::findOrFail($id);
        if (file_exists(public_path($tomo->portada))) {
            unlink(public_path($tomo->portada));
        }
        $tomo->delete();

        // Se conserva la lógica de redirección (aunque relacionada a filtros se omite)
        $redirectTo = $request->input('redirect_to', route('tomos.index'));
        return redirect($redirectTo)->with('success', 'Tomo eliminado exitosamente.');
    }
    public function edit($id)
{
    // Se carga el tomo junto con sus relaciones: manga y editorial
    $tomo = Tomo::with('manga', 'editorial')->findOrFail($id);
    // Si también necesitas la lista completa de mangas para el select (por ejemplo, para mostrar el manga relacionado)
    $mangas = Manga::all();

    return response()->json([
        'tomo'   => $tomo,
        'mangas' => $mangas,
        // No es necesario retornar la colección completa de editoriales
    ]);
}


    public function update(Request $request, $id)
    {
        $tomo = Tomo::findOrFail($id);

        $rules = [
            'manga_id'         => 'required|exists:mangas,id',
            'editorial_id'     => 'required|exists:editoriales,id',
            'formato'          => 'required|in:Tankōbon,Aizōban,Kanzenban,Bunkoban,Wideban',
            'idioma'           => 'required|in:Español,Inglés,Japonés',
            'precio'           => 'required|numeric',
            'fecha_publicacion'=> 'required|date|before:' . date('Y-m-d'),
            'stock'            => 'sometimes|numeric|min:0',
        ];

        if ($request->hasFile('portada')) {
            $rules['portada'] = 'image|max:2048';
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('portada')) {
            if (file_exists(public_path($tomo->portada))) {
                unlink(public_path($tomo->portada));
            }
            $file = $request->file('portada');
            $manga = Manga::find($validated['manga_id']);
            $mangaTitle = Str::slug($manga->titulo);
            $filename = "portada{$tomo->numero_tomo}." . $file->getClientOriginalExtension();
            $destinationPath = public_path("tomo_portada/{$mangaTitle}");
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $validated['portada'] = "tomo_portada/{$mangaTitle}/" . $filename;
        }

        $tomo->update($validated);
        $redirectTo = $request->input('redirect_to', route('tomos.index'));
        return redirect($redirectTo)->with('success', 'Tomo actualizado correctamente.');
    }
    public function updateMultipleStock(Request $request)
{
    // Validar que se envíe un array de tomos con el stock y que cada stock sea un entero mínimo 1.
    $request->validate([
        'tomos' => 'required|array',
        'tomos.*.id' => 'required|exists:tomos,id',
        'tomos.*.stock' => 'required|integer|min:1',
    ]);

    // Recorrer cada entrada y actualizar el stock correspondiente.
    foreach ($request->tomos as $tomoData) {
        $tomo = Tomo::findOrFail($tomoData['id']);
        $tomo->update([
            'stock' => $tomoData['stock'],
        ]);
    }

    // Redireccionar al listado con mensaje de éxito.
    return redirect()->route('tomos.index')->with('success', 'Stocks actualizados correctamente.');
}



    public function indexPublic(Request $request)
    {
        $query = Tomo::with('manga', 'editorial', 'manga.autor', 'manga.generos');

        if ($request->has('authors')) {
            $authors = explode(',', $request->get('authors'));
            $query->whereHas('manga.autor', function ($q) use ($authors) {
                $q->whereIn('id', $authors);
            });
        }
        if ($request->has('languages')) {
            $languages = explode(',', $request->get('languages'));
            $query->whereIn('idioma', $languages);
        }
        if ($request->has('mangas')) {
            $mangas = explode(',', $request->get('mangas'));
            $query->whereIn('manga_id', $mangas);
        }
        if ($request->has('editorials')) {
            $editorials = explode(',', $request->get('editorials'));
            $query->whereIn('editorial_id', $editorials);
        }

        if ($filterType = $request->get('filter_type')) {
            if ($filterType == 'idioma' && $idioma = $request->get('idioma')) {
                $query->where('idioma', $idioma);
            } elseif ($filterType == 'autor' && $autor = $request->get('autor')) {
                $query->whereHas('manga.autor', function ($q) use ($autor) {
                    $q->where('id', $autor);
                });
            } elseif ($filterType == 'manga' && $mangaId = $request->get('manga_id')) {
                $query->where('manga_id', $mangaId);
            } elseif ($filterType == 'editorial' && $editorialId = $request->get('editorial_id')) {
                $query->where('editorial_id', $editorialId);
            }
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_tomo', 'like', "%$search%")
                  ->orWhereHas('manga', function ($q2) use ($search) {
                      $q2->where('titulo', 'like', "%$search%");
                  })
                  ->orWhereHas('editorial', function ($q3) use ($search) {
                      $q3->where('nombre', 'like', "%$search%");
                  });
            });
        }

        if ($request->has('applyPriceFilter')
            && $request->get('applyPriceFilter') == 1
            && $request->has('minPrice')
            && $request->has('maxPrice')
        ) {
            $minPrice = (float)$request->get('minPrice');
            $maxPrice = (float)$request->get('maxPrice');
            $query->whereBetween('precio', [$minPrice, $maxPrice]);
        }

        $query->orderByRaw("(select titulo from mangas where mangas.id = tomos.manga_id) asc");
        $query->orderBy('numero_tomo', 'asc');

        $tomos = $query->paginate(8)->appends($request->query());

        return response()->json($tomos);
    }
}
