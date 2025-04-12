

    <!-- Modal Crear Tomo -->
    <div class="modal fade" id="modalCrearTomo" tabindex="-1" aria-labelledby="modalCrearTomoLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-dark text-white">
            <h5 class="modal-title" id="modalCrearTomoLabel">Crear Tomo</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body bg-light">
            <form action="{{ route('tomos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ url()->full() }}">

                <!-- Selección de manga -->
                @if(isset($manga))
                <div class="mb-3">
                <label for="manga_id" class="form-label text-dark">Manga</label>
                <select id="manga_id" class="form-select" disabled>
                    <option value="{{ $manga->id }}" selected>{{ $manga->titulo }}</option>
                </select>
                <input type="hidden" name="manga_id" value="{{ $manga->id }}">
                </div>
                @else
                <div class="mb-3">
                <label for="manga_id" class="form-label text-dark">Manga</label>
                <select name="manga_id" id="manga_id" class="form-select" required>
                    <option value="">Selecciona un manga</option>
                    @foreach($mangas as $mangaItem)
                    <option value="{{ $mangaItem->id }}">{{ $mangaItem->titulo }}</option>
                    @endforeach
                </select>
                </div>
                @endif

                <!-- Selección de Editorial -->
                <div class="mb-3">
                <label for="editorial_id" class="form-label text-dark">Editorial</label>
                <select name="editorial_id" id="editorial_id" class="form-select" required>
                    <option value="">Selecciona una editorial</option>
                    @foreach($editoriales as $editorial)
                    <option value="{{ $editorial->id }}"
                        {{ (isset($defaultEditorial) && $editorial->id == $defaultEditorial) ? 'selected' : '' }}>
                        {{ $editorial->nombre }}
                    </option>
                    @endforeach
                </select>
                </div>

                <!-- Número de Tomo -->
                <div class="mb-3">
                <label for="numero_tomo" class="form-label text-dark">Número de Tomo</label>
                <input type="number" name="numero_tomo" id="numero_tomo" class="form-control" readonly>
                </div>

                <!-- Formato -->
                <div class="mb-3">
                <label for="formato" class="form-label text-dark">Formato</label>
                <!-- Select visible para usuario -->
                <select name="formato_visible" id="formato" class="form-select" required>
                    <option value="">Selecciona un formato</option>
                    <option value="Tankōbon">Tankōbon</option>
                    <option value="Aizōban">Aizōban</option>
                    <option value="Kanzenban">Kanzenban</option>
                    <option value="Bunkoban">Bunkoban</option>
                    <option value="Wideban">Wideban</option>
                </select>
                <!-- Campo hidden para enviar el valor -->
                <input type="hidden" name="formato" id="formato_hidden">
                </div>

                <!-- Idioma -->
                <div class="mb-3">
                <label for="idioma" class="form-label text-dark">Idioma</label>
                <select name="idioma_visible" id="idioma" class="form-select" required>
                    <option value="">Selecciona un idioma</option>
                    <option value="Español">Español</option>
                    <option value="Inglés">Inglés</option>
                    <option value="Japonés">Japonés</option>
                </select>
                <input type="hidden" name="idioma" id="idioma_hidden">
                </div>

                <!-- Precio (editable) -->
                <div class="mb-3">
                <label for="precio" class="form-label text-dark">Precio</label>
                <input type="number" step="0.01" name="precio" id="precio" class="form-control" required>
                </div>

                <!-- Fecha de Publicación -->
                <div class="mb-3">
                <label for="fecha_publicacion" class="form-label text-dark">Fecha de Publicación</label>
                <input type="date" name="fecha_publicacion" id="fecha_publicacion" class="form-control" required>
                </div>

                <!-- Stock -->
                <div class="mb-3">
                <label for="stock" class="form-label text-dark">Stock</label>
                <input type="number" name="stock" id="stock" class="form-control" value="0" min="0">
                </div>

                <!-- Portada -->
                <div class="mb-3">
                <label for="portada" class="form-label text-dark">Portada</label>
                <input type="file" name="portada" id="portada" class="form-control" required>
                </div>

                <div class="modal-footer">
                <button type="submit" class="btn btn-primary w-100">Guardar</button>
                </div>
            </form>
            </div>
        </div>
        </div>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <!-- Script para autocompletar número, fecha y sincronizar formato/idioma -->
        <script>
        $(document).ready(function(){
            var nextTomos = @json($nextTomos);
            var ultimoTomosEditorial = @json($ultimoTomosEditorial);

            function updateTomoFields() {
            // Obtener el id del manga
            var mangaId = $('#manga_id').val();
            if (!mangaId) {
                mangaId = $('input[name="manga_id"]').val();
            }

            // Obtener la editorial seleccionada
            var editorialId = $('#editorial_id').val();

            // Actualizar número y fecha
            if (editorialId && nextTomos[editorialId]) {
                var data = nextTomos[editorialId];
                $('#numero_tomo').val(data.numero);

                // Si hay fecha definida, se usa; sino se deja vacía
                if (data.fecha !== '') {
                $('#fecha_publicacion').val(data.fecha);
                $('#fecha_publicacion').attr('min', data.fecha);
                } else {
                $('#fecha_publicacion').val('');
                $('#fecha_publicacion').removeAttr('min');
                }

                // Máximo: hoy
                var today = new Date().toISOString().split("T")[0];
                $('#fecha_publicacion').attr('max', today);
            } else {
                $('#numero_tomo').val('');
                $('#fecha_publicacion').val('');
                $('#fecha_publicacion').removeAttr('min');
                var today = new Date().toISOString().split("T")[0];
                $('#fecha_publicacion').attr('max', today);
            }

            // Actualizar formato e idioma según existencia de registro previo
            if (editorialId && ultimoTomosEditorial[editorialId]) {
                // Si existe un tomo previo para esa editorial, se fijan y deshabilitan
                var lastData = ultimoTomosEditorial[editorialId];
                $('#formato').val(lastData.formato);
                $('#idioma').val(lastData.idioma);
                $('#formato').prop('disabled', true);
                $('#idioma').prop('disabled', true);
                // Sincronizar con los hidden
                $('#formato_hidden').val(lastData.formato);
                $('#idioma_hidden').val(lastData.idioma);
            } else {
                // Si no existe tomo previo, se habilitan los selects y se limpian solo si aún no hay selección
                $('#formato').prop('disabled', false);
                $('#idioma').prop('disabled', false);
                // No se sobrescribe el valor si ya hay selección por el usuario
                // Solo se sincroniza con los hidden si hay un valor seleccionado
                var formatoVal = $('#formato').val();
                var idiomaVal  = $('#idioma').val();
                $('#formato_hidden').val(formatoVal);
                $('#idioma_hidden').val(idiomaVal);
            }
            }

            // Actualizar cuando se muestra el modal y cuando se cambia la editorial
            $('#modalCrearTomo').on('shown.bs.modal', function(){
            updateTomoFields();
            });
            $('#editorial_id').on('change', function(){
            updateTomoFields();
            });

            // Sincronizar los selects de formato e idioma con los hidden cuando el usuario los cambia manualmente
            $('#formato').on('change', function(){
            $('#formato_hidden').val($(this).val());
            });
            $('#idioma').on('change', function(){
            $('#idioma_hidden').val($(this).val());
            });
        });
        $('form').on('submit', function(){
             $('#formato_hidden').val($('#formato').val());
             $('#idioma_hidden').val($('#idioma').val());
             $('#numero_tomo').val($('#numero_tomo').val());
});
        </script>
    </div>
