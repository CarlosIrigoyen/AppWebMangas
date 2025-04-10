@extends('adminlte::page')

@section('title', 'Listado de Mangas con Tomos')

@section('css')
    <!-- DataTables y estilos -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@stop

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Listado de Mangas</h1>

    <table id="tabla-mangas" class="table table-striped table-hover dt-responsive nowrap" style="width:100%">
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
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#tabla-mangas').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                }
            });
        });
    </script>
@stop
