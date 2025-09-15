@extends('layouts/contentNavbarLayout')

@section('title', 'Archivos Generales')

@section('content')
    @include('components.toast')

    <div class="card mb-4">
        <div class="card-header">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                <h5 class="mb-2 mb-md-0">Repositorio compartido — Archivos Generales</h5>

                <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                    <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                        data-bs-target="#modalCrearCarpetaGeneral">
                        <i class="bx bx-folder-plus fs-4"></i> Crear carpeta
                    </button>

                    <button class="btn btn-success d-flex align-items-center gap-2" data-bs-toggle="modal"
                        data-bs-target="#modalSubirArchivoGeneral">
                        <i class="bx bx-upload fs-4"></i> Subir archivo
                    </button>

                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== ACORDEÓN DE CARPETAS ====== --}}
    <div class="accordion" id="accordionCarpetas">
        @foreach ($carpetas as $carpeta)
            @php
                $items = $archivos[$carpeta] ?? collect();
                $archivosValidos = $items->filter(fn($a) => $a->nombre_original !== '(carpeta vacía)');
                $tamanoTotal = $archivosValidos->sum('tamano'); // bytes
                $slug = Str::slug($carpeta);
                $collapseId = 'collapse-' . $slug;
                $headingId = 'heading-' . $slug;

                // carpetas protegidas (en minúsculas para comparar sin sensibilidad de mayúsculas)
                $protegidas = ['carpeta general', 'leyes y otros'];
                $bloqueada = in_array(mb_strtolower($carpeta), $protegidas, true);
            @endphp

            <div class="accordion-item mb-3 border rounded-3 overflow-hidden">
                <h2 class="accordion-header" id="{{ $headingId }}">
                    <button class="accordion-button d-flex justify-content-between align-items-center" type="button"
                        data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false"
                        aria-controls="{{ $collapseId }}">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fs-5">📁 {{ $carpeta }}</span>
                            <small class="text-muted">({{ number_format(($tamanoTotal ?? 0) / 1048576, 2) }} MB)</small>
                        </div>
                    </button>
                </h2>

                <div id="{{ $collapseId }}" class="accordion-collapse collapse" aria-labelledby="{{ $headingId }}"
                    data-bs-parent="#accordionCarpetas">
                    <div class="accordion-body p-0">
                        <div class="card border-0 rounded-0">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <strong class="me-2">{{ $carpeta }}</strong>

                                    @unless ($bloqueada)
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <button type="button" class="btn btn-sm btn-outline-danger w-100"
                                                    onclick="openConfirmModal('{{ route('archivos.generales.carpeta.eliminar', $carpeta) }}', '¿Eliminar la carpeta: {{ $carpeta }} y todos sus archivos?')">
                                                    <i class="bx bx-folder-minus me-1"></i> Eliminar carpeta
                                                </button>
                                            </div>
                                        </div>
                                    @endunless
                                </div>

                                <div class="d-flex gap-2">
                                    <form id="formEliminarMultiples-{{ $slug }}"
                                        action="{{ route('archivos.eliminarMultiples') }}" method="POST"
                                        class="mb-0 eliminar-multiple-form">
                                        @csrf
                                        <input type="hidden" name="ids" value="">
                                        <button type="button" class="btn btn-sm btn-danger btn-confirmar-multiple"
                                            disabled>
                                            <i class="bx bx-trash me-1"></i> Eliminar seleccionados
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div>
                                @if ($archivosValidos->count())
                                    <div id="tabla-{{ $slug }}">
                                        <div class="table-responsive text-nowrap">
                                            <table class="table table-striped align-middle mb-0">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 40px">
                                                            <input type="checkbox" onclick="toggleCheckboxesInTable(this)">
                                                        </th>
                                                        <th style="width:60%; padding-left:0">Nombre</th>
                                                        <th>Tipo</th>
                                                        <th>Tamaño</th>
                                                        <th>Fecha</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-border-bottom-0">
                                                    @foreach ($archivosValidos as $archivo)
                                                        @php
                                                            $icono = match (strtolower($archivo->tipo)) {
                                                                'pdf' => 'bxs-file-pdf text-danger',
                                                                'doc', 'docx' => 'bxs-file text-primary',
                                                                'xls', 'xlsx' => 'bxs-file text-success',
                                                                'jpg', 'jpeg', 'png' => 'bxs-file-image text-warning',
                                                                'mp3' => 'bxs-music text-info',
                                                                default => 'bxs-file text-muted',
                                                            };
                                                            $basePublic =
                                                                rtrim(
                                                                    env(
                                                                        'ARCHIVOS_GENERALES_PATH',
                                                                        '/storage/archivos_generales/',
                                                                    ),
                                                                    '/',
                                                                ) . '/';
                                                            $publicUrl = $archivo->ruta
                                                                ? $basePublic .
                                                                    $carpeta .
                                                                    '/' .
                                                                    basename($archivo->ruta)
                                                                : null;
                                                        @endphp
                                                        <tr style="cursor:pointer"
                                                            @if ($publicUrl) data-ver="{{ asset($publicUrl) }}" @endif>
                                                            <td>
                                                                <input type="checkbox" name="archivos_seleccionados[]"
                                                                    value="{{ $archivo->id }}">
                                                            </td>
                                                            <td style="padding-left:0">
                                                                <i class="bx {{ $icono }} me-2"></i>
                                                                <span
                                                                    class="fw-medium">{{ $archivo->nombre_original }}</span>
                                                            </td>
                                                            <td>{{ strtoupper($archivo->tipo) }}</td>
                                                            <td>{{ number_format(($archivo->tamano ?? 0) / 1048576, 2) }}
                                                                MB</td>
                                                            <td>{{ $archivo->created_at->format('d/m/Y H:i') }}</td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button type="button"
                                                                        class="btn p-0 dropdown-toggle hide-arrow"
                                                                        data-bs-toggle="dropdown">
                                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('archivos.generales.descargar', $archivo->id) }}">
                                                                            <i class="bx bx-download me-1"></i> Descargar
                                                                        </a>
                                                                        @if ($publicUrl)
                                                                            <a class="dropdown-item"
                                                                                href="{{ asset($publicUrl) }}"
                                                                                target="_blank">
                                                                                <i class="bx bx-show me-1"></i> Ver
                                                                            </a>
                                                                        @endif
                                                                        <a class="dropdown-item text-danger"
                                                                            href="javascript:void(0)"
                                                                            onclick="openConfirmModal('{{ route('archivos.generales.eliminar', $archivo->id) }}', '¿Eliminar el archivo: {{ $archivo->nombre_original }}?')">
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
                                    </div>
                                @else
                                    <p class="text-muted fst-italic mb-0">No hay archivos.</p>
                                @endif
                            </div>
                        </div>
                    </div> {{-- /accordion-body --}}
                </div>{{-- /collapse --}}
            </div>
        @endforeach
    </div>

    {{-- Modal confirmación genérica --}}
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

    {{-- Modal Crear Carpeta General --}}
    <div class="modal fade" id="modalCrearCarpetaGeneral" tabindex="-1" aria-labelledby="modalCrearCarpetaGeneralLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('archivos.generales.carpeta') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearCarpetaGeneralLabel">Nueva carpeta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <label for="nombreCarpetaGeneral" class="form-label">Nombre de la carpeta</label>
                    <input type="text" name="nombre_carpeta" id="nombreCarpetaGeneral" class="form-control"
                        placeholder="Ej: Circulares, Formularios" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear carpeta</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Subir Archivos General --}}
    <div class="modal fade" id="modalSubirArchivoGeneral" tabindex="-1" aria-labelledby="modalSubirArchivoGeneralLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('archivos.generales.subir') }}" method="POST" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSubirArchivoGeneralLabel">Subir archivos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Seleccionar archivos (máx 20MB c/u)</label>
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
@endsection

{{-- ====== JS helpers ====== --}}
<script>
    function openConfirmModal(action, message) {
        const form = document.getElementById('confirmForm');
        form.action = action;
        document.getElementById('confirmText').textContent = message;
        new bootstrap.Modal(document.getElementById('confirmModal')).show();
    }

    function toggleCheckboxesInTable(masterCheckbox) {
        const table = masterCheckbox.closest('table');
        const checkboxes = table.querySelectorAll('input[name="archivos_seleccionados[]"]');
        checkboxes.forEach(cb => {
            cb.checked = masterCheckbox.checked;
            cb.dispatchEvent(new Event('change'));
        });
    }

    let formEliminarMasivaPendiente = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Por cada acordeón/carpeta, activar manejo de selección múltiple y filas clicables
        document.querySelectorAll('.accordion-item').forEach(item => {
            const formEliminar = item.querySelector('.eliminar-multiple-form');
            if (!formEliminar) return;

            const table = item.querySelector('table');
            if (!table) return;

            const checkboxes = table.querySelectorAll('input[name="archivos_seleccionados[]"]');
            const botonConfirmar = formEliminar.querySelector('.btn-confirmar-multiple');

            function actualizarEstadoBoton() {
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                botonConfirmar.disabled = !anyChecked;

                const idsInput = formEliminar.querySelector('input[name="ids"]');
                idsInput.value = anyChecked ?
                    Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value).join(',') :
                    '';
            }

            checkboxes.forEach(cb => cb.addEventListener('change', actualizarEstadoBoton));
            actualizarEstadoBoton();

            botonConfirmar.addEventListener('click', () => {
                const ids = formEliminar.querySelector('input[name="ids"]').value.split(',');
                if (ids.length === 0 || ids[0] === '') return;
                document.getElementById('modalEliminarMultiplesCount').textContent = ids.length;
                formEliminarMasivaPendiente = formEliminar;
                new bootstrap.Modal(document.getElementById('modalEliminarMultiples')).show();
            });

            // Click fila = toggle checkbox / Doble click = ver
            table.querySelectorAll('tbody tr').forEach(fila => {
                fila.addEventListener('click', function(e) {
                    if (e.target.tagName === 'INPUT' || e.target.closest('.dropdown'))
                        return;
                    const cb = fila.querySelector('input[type="checkbox"]');
                    if (cb) {
                        cb.checked = !cb.checked;
                        cb.dispatchEvent(new Event('change'));
                    }
                });
                fila.addEventListener('dblclick', function() {
                    const url = fila.getAttribute('data-ver');
                    if (url) window.open(url, '_blank');
                });
            });
        });

        document.getElementById('btnConfirmarEliminacionFinal')?.addEventListener('click', () => {
            if (formEliminarMasivaPendiente) {
                formEliminarMasivaPendiente.submit();
                formEliminarMasivaPendiente = null;
            }
        });
    });
</script>

{{-- Modal de confirmación múltiple --}}
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
