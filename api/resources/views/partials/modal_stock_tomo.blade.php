<div class="modal fade" id="modalStock" tabindex="-1" aria-labelledby="modalStockLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tomos con bajo stock (menos de 10 unidades)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tomos.updateMultipleStock') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <table class="table table-bordered">
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
                                    <!-- Formulario para actualizar el stock de este tomo -->
                                    <input type="number" name="tomos[{{ $tomo->id }}][stock]" class="form-control" value="{{ $tomo->stock }}" min="1" required>
                                    <input type="hidden" name="tomos[{{ $tomo->id }}][id]" value="{{ $tomo->id }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-primary btn-sm mt-3">Actualizar Stocks</button>
                </form>
            </div>
        </div>
    </div>
</div>
