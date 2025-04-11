@foreach($tomos as $tomo)
    <div class="col-md-4 mb-4">
        <div class="card card-tomo">
            <img src="{{ asset($tomo->portada) }}" alt="Portada" class="card-img-top">
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
    </div>
        {{-- Incluí los modales por tomo aquí mismo --}}
        @include('partials.modal_info_tomo', ['tomo' => $tomo])
        @include('partials.modal_editar_tomo', ['tomo' => $tomo])
        @include('partials.modal_eliminar_tomo', ['tomo' => $tomo])

@endforeach


