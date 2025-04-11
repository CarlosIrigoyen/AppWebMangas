@extends('adminlte::page')

@section('title', 'Dashboard')

    <style>
        /* Ajuste básico para que todas las tarjetas tengan el mismo alto y el texto sea legible */
        .card {
            display: flex;
            flex-direction: column;
            height: 300px; /* Altura fija, puedes ajustarla a tus necesidades */
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
            background-color: #090303; /* Fondo claro */
            color: #333;            /* Texto oscuro */
        }


        /* Header de la tarjeta: fondo oscuro, texto claro */
        .card-header {
            background-color: #343a40; /* Gris oscuro */
            border-bottom: none;
            padding: 10px 15px;
            color: #fff; /* Texto claro */
        }

        /* Título en el header */
        .card-title {
            font-size: 18px;
            margin: 0;
        }

        /* Cuerpo de la tarjeta: texto oscuro, fondo blanco */
        .card-body {
            flex: 1;
            padding: 15px;
            overflow-y: auto; /* Permite scroll cuando hay contenido extenso */
            background-color: #333;
            color: #f8f9fa;
        }

        /* Pie de tarjeta, con un fondo ligeramente más claro que el body */
        .card-footer {
            background-color: #f8f9fa;
            padding: 10px 15px;
            text-align: center;
        }

        /* Íconos */
        .card-title i {
            margin-right: 5px;
        }

        /* Bordes personalizados en la parte izquierda */
        .mangas-card {
            border-left: 5px solid #007bff; /* Azul */
        }
        .editoriales-card {
            border-left: 5px solid #28a745; /* Verde */
        }
        .autores-card {
            border-left: 5px solid #ffc107; /* Amarillo */
        }
        .dibujantes-card {
            border-left: 5px solid #dc3545; /* Rojo */
        }
        .tomos-card {
            border-left: 5px solid #6f42c1; /* Morado */
        }
    </style>


@section('content_header')
    <h1>Panel Administrativo</h1>
@stop

@section('content')
    <p>Bienvenido administrador</p>
    <div class="container">
        <!-- Información básica en el dashboard -->
        <div class="row">
            <!-- Tarjeta Manga -->
            <div class="col-md-3">
                <div class="card mangas-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-book"></i> Mangas Cargados
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($mangasCount > 10)
                            <p class="card-text">Total: {{ $mangasCount }}</p>
                        @else
                            <p class="card-text">Total: {{ $mangasCount }}</p>
                            <ul>
                                @foreach ($mangas as $manga)
                                    <li>{{ $manga->titulo }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('mangas.index') }}" class="btn btn-primary btn-sm">Ver listado</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Editoriales -->
            <div class="col-md-3">
                <div class="card editoriales-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-building"></i> Editoriales Cargadas
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($editorialesCount > 10)
                            <p class="card-text">Total: {{ $editorialesCount }}</p>
                        @else
                            <p class="card-text">Total: {{ $editorialesCount }}</p>
                            <ul>
                                @foreach ($editoriales as $editorial)
                                    <li>{{ $editorial->nombre }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('editoriales.index') }}" class="btn btn-primary btn-sm">Ver listado</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Autores -->
            <div class="col-md-3">
                <div class="card autores-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-user"></i> Autores Cargados
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($autoresCount > 10)
                            <p class="card-text">Total: {{ $autoresCount }}</p>
                        @else
                            <p class="card-text">Total: {{ $autoresCount }}</p>
                            <ul>
                                @foreach ($autores as $autor)
                                    <li>{{ $autor->nombre }} {{ $autor->apellido }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('autores.index') }}" class="btn btn-primary btn-sm">Ver listado</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Dibujantes -->
            <div class="col-md-3">
                <div class="card dibujantes-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-pencil-alt"></i> Dibujantes Cargados
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($dibujantesCount > 10)
                            <p class="card-text">Total: {{ $dibujantesCount }}</p>
                        @else
                            <p class="card-text">Total: {{ $dibujantesCount }}</p>
                            <ul>
                                @foreach ($dibujantes as $dibujante)
                                    <li>{{ $dibujante->nombre }} {{ $dibujante->apellido }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('dibujantes.index') }}" class="btn btn-primary btn-sm">Ver listado</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Tomos -->
            <div class="col-md-3">
                <div class="card tomos-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-book-open"></i> Tomos Cargados
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($tomosCount > 10)
                            <p class="card-text">Total: {{ $tomosCount }}</p>
                        @else
                            <p class="card-text">Total: {{ $tomosCount }}</p>
                            <ul>
                                @foreach ($tomos as $tomo)
                                    <!-- Se asume que el modelo Tomo tiene un atributo 'nombre' o 'titulo' -->
                                    <li>{{ $tomo->nombre }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('tomos.index') }}" class="btn btn-primary btn-sm">Ver listado</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

