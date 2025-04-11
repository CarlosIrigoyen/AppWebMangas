@extends('adminlte::page')

@section('title', 'Listado de Tomos')

@section('content_header')
    <!-- Puedes agregar un título o dejarlo vacío -->
@stop

@section('css')
    <!-- Archivos de estilos: Bootstrap, DataTables, FontAwesome y CSS personalizado -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
    <link rel="stylesheet" href="{{ asset('css/tomos.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@stop

@section('content')
<div class="container mt-4">
    <!-- Muestra mensaje de error si existe -->
    @if(session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif
    <!-- Botón Crear Tomo -->
    <h1 class="mb-4">Listado de Tomos - {{$manga->titulo}}</h1>

    <!-- Si no se encontraron tomos -->
    @if($tomos->total() == 0)
        <div class="alert alert-warning text-center">
            No se encontraron tomos.
        </div>
    @else
        <!-- Card contenedor para los tomos -->
        <div class="card">
            <div class="card-body g-3">
                <!-- Botón Crear Tomo alineado a la izquierda -->
                <div class="mb-4 d-flex justify-content-start">
                    <button type="button" class="btn btn-crear-tomo" data-bs-toggle="modal" data-bs-target="#modalCrearTomo">
                        <i class="fas fa-plus"></i> Crear Tomo
                    </button>
                </div>
                <!-- Paginación personalizada -->
                <div class="separator"></div>
                <div class="pagination-container d-flex justify-content-center my-3">
                    <button class="btn btn-light"
                            onclick="window.location.href='{{ $tomos->previousPageUrl() }}'"
                            {{ $tomos->onFirstPage() ? 'disabled' : '' }}>
                        &laquo; Anterior
                    </button>
                    <span id="pageIndicator" class="mx-3">
                        Página {{ $tomos->currentPage() }} / {{ $tomos->lastPage() }}
                    </span>
                    <button class="btn btn-light"
                            onclick="window.location.href='{{ $tomos->nextPageUrl() }}'"
                            {{ $tomos->currentPage() == $tomos->lastPage() ? 'disabled' : '' }}>
                        Siguiente &raquo;
                    </button>
                </div>

                <div class="row" id="tomoList">
                    @foreach($tomos as $tomo)
                    <div class="col-md-4 mb-4">
                        <div class="card card-tomo">
                            <img src="{{ asset($tomo->portada) }}" alt="Portada" class="card-img-top">
                            <!-- Información del Tomo -->
                            <div class="card-body">
                                <h5 class="card-title text-center">
                                    {{ $manga->titulo }} - Tomo {{ $tomo->numero_tomo }}
                                </h5>
                            </div>
                            <!-- Botones debajo de la información -->
                            <div class="card-footer d-flex justify-content-around">
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalInfo-{{ $tomo->id }}">
                                    <i class="fas fa-info-circle me-1"></i> Información
                                </button>
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit-{{ $tomo->id }}">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete-{{ $tomo->id }}">
                                    <i class="fas fa-trash me-1"></i> Eliminar
                                </button>
                            </div>
                        </div>
                        <!-- Se incluyen los parciales de modales -->
                        @include('partials.modal_info_tomo')
                        @include('partials.modal_editar_tomo')
                        @include('partials.modal_eliminar_tomo')
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Se incluye el modal de creación -->
@include('partials.modal_crear_tomo')

@stop

@section('js')
    <!-- Cargamos los scripts de Bootstrap, jQuery y DataTables -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>
@stop
