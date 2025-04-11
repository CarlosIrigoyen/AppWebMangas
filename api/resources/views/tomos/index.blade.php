@extends('adminlte::page')

@section('title', 'Listado de Mangas con Tomos')

@section('css')
    <!-- DataTables y estilos -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <style>
        /* Contenedor flex para los botones de acción */
        .acciones-container {
            display: flex;
            gap: 10px; /* Espacio entre botones */
            justify-content: center;
            align-items: center;
        }
        /* Evitar parpadeos en la tabla */
        #Contenido {
            visibility: hidden;
        }
    </style>
@stop

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Listado de Mangas</h1>
    @if($hasLowStock)
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalStock">
            Ver Stock Bajo
        </button>
    @endif
    <table id="Contenido" class="table table-striped table-hover dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Título del Manga</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mangas as $manga)
                <tr>
                    <td>{{ $manga->titulo }}</td>
                    <td>
                        <!-- Se redirige a la ruta para ver los tomos del manga seleccionado -->
                        <a href="{{ route('tomos.por_manga', $manga->id) }}" class="btn btn-primary">
                            Ver todos los Tomos
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="modal fade" id="modalStock" tabindex="-1" aria-labelledby="modalUpdateMultipleStockLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Formulario para actualizar múltiples tomos -->
            <form action="{{ route('tomos.updateMultipleStock') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title" id="modalUpdateMultipleStockLabel">Actualizar Stock de Tomos</h5>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Cerrar">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="updateStockTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Manga</th>
                                    <th>Número de Tomo</th>
                                    <th>Stock Actual</th>
                                    <th>Nuevo Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockTomos as $tomo)
                                <tr>
                                    <td>{{ $tomo->manga->titulo }}</td>
                                    <td>{{ $tomo->numero_tomo }}</td>
                                    <td>{{ $tomo->stock }}</td>
                                    <td>
                                        <!-- Enviamos el id y el nuevo stock en un array -->
                                        <input type="hidden" name="tomos[{{ $tomo->id }}][id]" value="{{ $tomo->id }}">
                                        <input type="number" name="tomos[{{ $tomo->id }}][stock]" class="form-control" value="{{ $tomo->stock }}" min="{{ $tomo->stock }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Actualizar Stock</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap Bundle (incluye Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#Contenido').DataTable({
                responsive: true,
                order: [[0, 'desc']],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar:"
                }
            });
            $('#Contenido').css('visibility', 'visible');
        });
    </script>
@stop
