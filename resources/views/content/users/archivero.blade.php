@extends('layouts/contentNavbarLayout')

@section('title', 'Archivero de ' . $user->apellido)

@section('content')
    @include('components.toast')
    <div class="card mb-4">
        <div class="card-header">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                <h5 class="mb-2 mb-md-0">Archivero de {{ $user->apellido }}, {{ $user->nombre }} ({{ $user->cuil }})</h5>

                <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                    {{-- Botón para crear carpeta --}}
                    <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                        data-bs-target="#modalCrearCarpeta">
                        <i class="bx bx-folder-plus fs-4"></i> Crear carpeta
                    </button>

                    {{-- Botón para subir archivo --}}
                    <button class="btn btn-success d-flex align-items-center gap-2" data-bs-toggle="modal"
                        data-bs-target="#modalSubirArchivo">
                        <i class="bx bx-upload fs-4"></i> Subir archivo
                    </button>

                    {{-- Botón volver --}}
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>


    @foreach ($carpetas as $carpeta)
        @php
            $archivosValidos = $archivos[$carpeta]->filter(
                fn($archivo) => $archivo->nombre_original !== '(carpeta vacía)',
            );
            $tamanoTotal = $archivosValidos->sum('tamano'); // en bytes
        @endphp
        @php
            $paginatedArchivos = $archivos[$carpeta];
        @endphp

        <div class="card mt-4">
            <h5 class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <strong>
                        📁 {{ $carpeta }}
                        <small class="text-muted">({{ number_format($tamanoTotal / 1048576, 2) }} MB)</small>
                    </strong>
                    @if ($carpeta !== 'CARPETA PERSONAL')
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <button type="button" class="btn btn-sm btn-outline-danger w-100"
                                    onclick="openConfirmModal('{{ route('archivos.eliminarCarpeta', ['userId' => $user->id, 'carpeta' => $carpeta]) }}', '¿Eliminar la carpeta: {{ $carpeta }} y todos sus archivos?')">
                                    <i class="bx bx-folder-minus me-1"></i> Eliminar carpeta
                                </button>
                            </div>
                        </div>
                    @endif
                </div>


                {{-- Botón para eliminar múltiples archivos --}}
                {{-- <div class="px-3 py-2">
                    <input type="text" class="form-control form-control-sm" placeholder="Buscar archivo..."
                        onkeyup="filtrarArchivos(this, '{{ $carpeta }}')">
                </div> --}}
                <div class="d-flex gap-2">
                    <form id="formEliminarMultiples-{{ Str::slug($carpeta) }}"
                        action="{{ route('archivos.eliminarMultiples') }}" method="POST"
                        class="mb-0 eliminar-multiple-form">
                        @csrf
                        <input type="hidden" name="ids" value="">
                        <button type="button" class="btn btn-sm btn-danger btn-confirmar-multiple" disabled>
                            <i class="bx bx-trash me-1"></i> Eliminar seleccionados
                        </button>
                    </form>
                </div>


            </h5>


            <div class="table-responsive text-nowrap">
                @if ($archivosValidos->count())
                    <div id="tabla-{{ $carpeta }}">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" onclick="toggleCheckboxesInTable(this)">
                                    </th>
                                    <th style="width:60%; padding-left: 0">Nombre</th>
                                    <th>Tipo</th>
                                    <th>Tamaño</th>
                                    <th>Fecha</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($archivosValidos as $archivo)
                                    <tr style="cursor: pointer"
                                        data-ver="{{ asset(env('ARCHIVOS_PATH') . $user->id . '/' . $carpeta . '/' . basename($archivo->ruta)) }}">
                                        <td>
                                            <input type="checkbox" name="archivos_seleccionados[]"
                                                value="{{ $archivo->id }}">
                                        </td>
                                        <td style="padding-left: 0">
                                            @php
                                                $icono = match (strtolower($archivo->tipo)) {
                                                    'pdf' => 'bxs-file-pdf text-danger',
                                                    'doc', 'docx' => 'bxs-file text-primary',
                                                    'xls', 'xlsx' => 'bxs-file text-success',
                                                    'jpg', 'jpeg', 'png' => 'bxs-file-image text-warning',
                                                    'mp3' => 'bxs-music text-info',
                                                    default => 'bxs-file text-muted',
                                            }; @endphp
                                            <i class="bx {{ $icono }} me-2"></i>
                                            <span class="fw-medium">{{ $archivo->nombre_original }}</span>
                                        </td>
                                        <td>{{ strtoupper($archivo->tipo) }}</td>
                                        <td>{{ number_format($archivo->tamano / 1048576, 2) }} MB</td>
                                        <td>{{ $archivo->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('archivos.descargar', $archivo->id) }}">
                                                        <i class="bx bx-download me-1"></i> Descargar
                                                    </a>
                                                    <a class="dropdown-item"
                                                        href="{{ asset(env('ARCHIVOS_PATH') . $user->id . '/' . $carpeta . '/' . basename($archivo->ruta)) }}"
                                                        target="_blank">
                                                        <i class="bx bx-show me-1"></i> Ver
                                                    </a>
                                                    <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                        onclick="openConfirmModal('{{ route('archivos.eliminar', $archivo->id) }}', '¿Eliminar el archivo: {{ $archivo->nombre_original }}?')">
                                                        <i class="bx bx-trash me-1"></i> Eliminar
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Paginación personalizada por carpeta --}}
                @else
                    <p class="text-muted fst-italic card-footer">No hay archivos.</p>
                @endif
            </div>
        </div>
    @endforeach


    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" id="confirmForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">Confirmar eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p id="confirmText">¿Estás seguro de que querés eliminar esto?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Crear Carpeta -->
    <div class="modal fade" id="modalCrearCarpeta" tabindex="-1" aria-labelledby="modalCrearCarpetaLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('archivos.crearCarpeta', $user->id) }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearCarpetaLabel">Nueva carpeta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <label for="nombreCarpeta" class="form-label">Nombre de la carpeta</label>
                    <input type="text" name="nombre_carpeta" id="nombreCarpeta" class="form-control"
                        placeholder="Ej: Documentos legales" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear carpeta</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Subir Archivo -->
    <div class="modal fade" id="modalSubirArchivo" tabindex="-1" aria-labelledby="modalSubirArchivoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('archivos.subir', $user->id) }}" method="POST" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSubirArchivoLabel">Subir archivos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Seleccionar archivos (max 20MB por archivo)</label>
                        <input type="file" name="archivos[]" class="form-control" multiple required
                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.mp3">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Seleccionar carpeta</label>
                        <select name="carpeta" class="form-select">
                            @foreach ($carpetas as $carpeta)
                                <option value="{{ $carpeta }}">{{ $carpeta }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Subir</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal de confirmación múltiple -->
    <div class="modal fade" id="modalEliminarMultiples" tabindex="-1" aria-labelledby="modalEliminarMultiplesLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEliminarMultiplesLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que querés eliminar <strong><span id="modalEliminarMultiplesCount">0</span>
                            archivos</strong> seleccionados?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminacionFinal">Eliminar</button>
                </div>
            </div>
        </div>
    </div>




@endsection
<script>
    function filtrarArchivos(input, carpeta) {
        const value = input.value.toLowerCase();
        const rows = document.querySelectorAll(`#tabla-${carpeta} tbody tr`);

        rows.forEach(row => {
            const texto = row.innerText.toLowerCase();
            row.style.display = texto.includes(value) ? '' : 'none';
        });
    }
</script>

<script>
    function openConfirmModal(action, message) {
        const form = document.getElementById('confirmForm');
        form.action = action;
        document.getElementById('confirmText').textContent = message;
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        modal.show();
    }
</script>
<script>
    function toggleCheckboxesInTable(masterCheckbox) {
        const table = masterCheckbox.closest('table');
        const checkboxes = table.querySelectorAll('input[name="archivos_seleccionados[]"]');
        checkboxes.forEach(cb => {
            cb.checked = masterCheckbox.checked;
            cb.dispatchEvent(new Event('change')); // ✅ disparar manualmente el evento 'change'
        });
    }
</script>
<script>
    let formEliminarMasivaPendiente = null;

    document.addEventListener('DOMContentLoaded', function() {
        const carpetasCards = document.querySelectorAll('.card.mt-4');

        carpetasCards.forEach(card => {
            const formEliminar = card.querySelector('.eliminar-multiple-form');
            if (!formEliminar) return;

            const checkboxes = card.querySelectorAll('input[name="archivos_seleccionados[]"]');
            const botonConfirmar = formEliminar.querySelector('.btn-confirmar-multiple');

            function actualizarEstadoBoton() {
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);

                botonConfirmar.disabled = !anyChecked;

                // Actualiza los IDs seleccionados
                const idsInput = formEliminar.querySelector('input[name="ids"]');
                idsInput.value = anyChecked ?
                    Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value).join(',') :
                    '';
            }

            checkboxes.forEach(cb => cb.addEventListener('change', actualizarEstadoBoton));

            botonConfirmar.addEventListener('click', () => {
                const ids = formEliminar.querySelector('input[name="ids"]').value.split(',');
                if (ids.length === 0 || ids[0] === '') return;

                document.getElementById('modalEliminarMultiplesCount').textContent = ids.length;
                formEliminarMasivaPendiente = formEliminar;
                const modal = new bootstrap.Modal(document.getElementById(
                    'modalEliminarMultiples'));
                modal.show();
            });

            actualizarEstadoBoton();
        });

        // Confirmar eliminación final desde el modal
        document.getElementById('btnConfirmarEliminacionFinal').addEventListener('click', () => {
            if (formEliminarMasivaPendiente) {
                formEliminarMasivaPendiente.submit();
                formEliminarMasivaPendiente = null;
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carpetasCards = document.querySelectorAll('.card.mt-4');

        carpetasCards.forEach(card => {
            const formEliminar = card.querySelector('form[id^="formEliminarMultiples"]');
            if (!formEliminar) return;

            const checkboxes = card.querySelectorAll('input[name="archivos_seleccionados[]"]');
            const masterCheckbox = card.querySelector(
                'input[type="checkbox"][onclick="toggleCheckboxesInTable(this)"]');
            const botonEliminar = formEliminar.querySelector('button[type="submit"]');

            function actualizarEstadoBoton() {
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);

                // Habilita o deshabilita el botón
                botonEliminar.disabled = !anyChecked;

                // Actualiza los IDs seleccionados
                const idsInput = formEliminar.querySelector('input[name="ids"]');
                idsInput.value = anyChecked ?
                    Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value).join(',') :
                    '';
            }

            actualizarEstadoBoton();

            checkboxes.forEach(cb => {
                cb.addEventListener('change', actualizarEstadoBoton);
            });

            if (masterCheckbox) {
                masterCheckbox.addEventListener('change', actualizarEstadoBoton);
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filas = document.querySelectorAll('table tbody tr');

        filas.forEach(fila => {
            fila.addEventListener('click', function(e) {
                if (e.target.tagName === 'INPUT' || e.target.closest('.dropdown')) return;

                const checkbox = fila.querySelector('input[type="checkbox"]');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });

            fila.addEventListener('dblclick', function() {
                const url = fila.getAttribute('data-ver');
                if (url) window.open(url, '_blank');
            });
        });
    });
</script>
