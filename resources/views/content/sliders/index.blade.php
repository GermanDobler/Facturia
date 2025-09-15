@extends('layouts/contentNavbarLayout')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>

@section('title', 'Slider Management')

@section('content')
    @include('components.toast')

    <!-- Main Slider -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Slider Inicio</h5>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSliderModal">Añadir Slide</a>
        </div>
        <div class="card-body">
            <div class="row mt-3" id="image-previews">
                @foreach ($sliders->sortBy('orden') as $slide)
                    <div class="col-md-6 col-lg-4 mb-4" data-id="{{ $slide->id }}">
                        <div class="card image-card position-relative editable-card" role="button" tabindex="0"
                            data-type="main" data-update-url="{{ route('sliders.update', $slide->id) }}"
                            data-imagen="{{ asset(env('IMAGE_PATH') . basename($slide->imagen_url)) }}"
                            data-titulo="{{ $slide->titulo }}" data-subtitulo="{{ $slide->subtitulo }}"
                            data-boton_titulo="{{ $slide->boton_titulo }}" data-boton_url="{{ $slide->boton_url }}"
                            data-boton_color="{{ $slide->boton_color ?? '#0d6efd' }}">
                            <div class="drag-handle">☰ Mover</div>
                            <img src="{{ asset(env('IMAGE_PATH') . basename($slide->imagen_url)) }}" class="card-img-top"
                                alt="Slider Image">

                            {{-- Overlay de contenido (preview) --}}
                            @if ($slide->titulo || $slide->subtitulo || $slide->boton_titulo)
                                <div class="caption-overlay">
                                    @if ($slide->titulo)
                                        <h6 class="mb-1 fw-bold">{{ $slide->titulo }}</h6>
                                    @endif
                                    @if ($slide->subtitulo)
                                        <p class="mb-2 small">{{ $slide->subtitulo }}</p>
                                    @endif
                                    @if ($slide->boton_titulo && $slide->boton_url)
                                        <a href="{{ $slide->boton_url }}" class="btn btn-sm border-0"
                                            style="background-color: {{ $slide->boton_color ?? '#0d6efd' }};">
                                            {{ $slide->boton_titulo }}
                                        </a>
                                    @endif
                                </div>
                            @endif

                            <button type="button" class="btn btn-danger position-absolute top-0 end-0 delete-btn"
                                data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $slide->id }}"
                                data-url="{{ route('sliders.destroy', $slide->id) }}">X</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- SI TENÉS EL SLIDER SECUNDARIO EN ESTA VISTA, LISTALO IGUAL QUE ARRIBA PERO CAMBIANDO data-type Y RUTAS --}}
    {{-- Ejemplo (opcional): --}}
    {{-- @if (isset($slidersSecundarios))
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Slider Secundario</h5>
                <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#addSliderSecundarioModal">Añadir Slide</a>
            </div>
            <div class="card-body">
                <div class="row mt-3" id="image-previews-secundario">
                    @foreach ($slidersSecundarios->sortBy('orden') as $slide)
                        <div class="col-md-6 col-lg-4 mb-4" data-id="{{ $slide->id }}">
                            <div class="card image-card position-relative editable-card" role="button" tabindex="0"
                                data-type="secondary"
                                data-update-url="{{ route('sliders-secundario.update', $slide->id) }}"
                                data-imagen="{{ asset(env('IMAGE_PATH') . basename($slide->imagen_url)) }}"
                                data-titulo="{{ $slide->titulo }}" data-subtitulo="{{ $slide->subtitulo }}"
                                data-boton_titulo="{{ $slide->boton_titulo }}" data-boton_url="{{ $slide->boton_url }}"
                                data-boton_color="{{ $slide->boton_color ?? '#0d6efd' }}">
                                <div class="drag-handle">☰ Mover</div>
                                <img src="{{ asset(env('IMAGE_PATH') . basename($slide->imagen_url)) }}"
                                    class="card-img-top" alt="Slider Image">

                                @if ($slide->titulo || $slide->subtitulo || $slide->boton_titulo)
                                    <div class="caption-overlay">
                                        @if ($slide->titulo)
                                            <h6 class="mb-1 fw-bold">{{ $slide->titulo }}</h6>
                                        @endif
                                        @if ($slide->subtitulo)
                                            <p class="mb-2 small">{{ $slide->subtitulo }}</p>
                                        @endif
                                        @if ($slide->boton_titulo && $slide->boton_url)
                                            <a href="{{ $slide->boton_url }}" class="btn btn-sm border-0"
                                                style="background-color: {{ $slide->boton_color ?? '#0d6efd' }};">
                                                {{ $slide->boton_titulo }}
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                <button type="button" class="btn btn-danger position-absolute top-0 end-0 delete-btn"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $slide->id }}"
                                    data-url="{{ route('sliders-secundario.destroy', $slide->id) }}">X</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif --}}

    <!-- Modals -->
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar acción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este slide?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Borrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal to Add Main Slider -->
    <div class="modal fade" id="addSliderModal" tabindex="-1" aria-labelledby="addSliderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Añadir Slider Principal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('sliders.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="imagen_url_principal" class="form-label">Seleccionar imagen</label>
                            <input type="file" name="imagen_url" id="imagen_url_principal" class="form-control"
                                accept="image/*" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" name="titulo" class="form-control" maxlength="120"
                                placeholder="Ej: Bienvenidos a ...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subtítulo</label>
                            <input type="text" name="subtitulo" class="form-control" maxlength="200"
                                placeholder="Ej: Nuestra propuesta...">
                        </div>

                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label">Texto del botón</label>
                                <input type="text" name="boton_titulo" class="form-control" maxlength="60"
                                    placeholder="Ej: Ver más">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">URL del botón</label>
                                <input type="url" name="boton_url" class="form-control" maxlength="255"
                                    placeholder="https://...">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Color</label>
                                <input type="color" name="boton_color" class="form-control form-control-color"
                                    value="#5577B4" title="Color del botón">
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <label class="form-label">Otra pestaña</label>
                            <select name="boton_target" class="form-select">
                                <option value="_self" selected>Misma pestaña</option>
                                <option value="_blank">Nueva pestaña</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success mt-4">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL (reutilizable para principal y secundario) --}}
    <div class="modal fade" id="editSlideModal" tabindex="-1" aria-labelledby="editSlideModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="editSlideForm" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="editSlideModalLabel">Editar Slide</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-5">
                            <img id="editSlidePreview" src="" alt="Preview" class="img-fluid rounded border">
                            <div class="mt-3">
                                <label class="form-label">Cambiar imagen</label>
                                <input type="file" name="imagen_url" id="editSlideImage" class="form-control"
                                    accept="image/*">
                                <small class="text-muted d-block mt-1">Opcional. Máx 10MB.</small>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label">Título</label>
                                <input type="text" name="titulo" id="edit_titulo" class="form-control"
                                    maxlength="120">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Subtítulo</label>
                                <input type="text" name="subtitulo" id="edit_subtitulo" class="form-control"
                                    maxlength="200">
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Texto del botón</label>
                                    <input type="text" name="boton_titulo" id="edit_boton_titulo"
                                        class="form-control" maxlength="60">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">URL del botón</label>
                                    <input type="url" name="boton_url" id="edit_boton_url" class="form-control"
                                        maxlength="255" placeholder="https://...">
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label class="form-label">Otra pestaña</label>
                                    <select name="boton_target" class="form-select">
                                        <option value="_self" selected>Misma pestaña</option>
                                        <option value="_blank">Nueva pestaña</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Color</label>
                                    <input type="color" name="boton_color" id="edit_boton_color"
                                        class="form-control form-control-color" value="#5577B4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-text btn-outline-secondary"
                        data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>

@endsection

<style>
    .image-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        overflow: hidden;
    }

    .image-card:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .editable-card {
        cursor: pointer;
    }

    .drag-handle {
        cursor: grab;
        padding: 7px;
        background: #f8f9fa;
        text-align: center;
        border-bottom: 1px solid #e9ecef;
        font-size: 0.95rem;
    }

    .caption-overlay {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        padding: 12px 14px;
        color: #fff;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.0) 0%, rgba(0, 0, 0, 0.55) 40%, rgba(0, 0, 0, 0.75) 100%);
    }

    .caption-overlay h6,
    .caption-overlay p {
        color: #fff;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ordenar principal
        const mainContainer = document.getElementById('image-previews');
        if (mainContainer) {
            new Sortable(mainContainer, {
                animation: 150,
                handle: ".drag-handle",
                onEnd: function() {
                    updateImageOrder("{{ route('sliders.updateOrder') }}", 'image-previews');
                }
            });
        }

        // Ordenar secundario (si existe)
        const secondaryContainer = document.getElementById('image-previews-secundario');
        if (secondaryContainer) {
            new Sortable(secondaryContainer, {
                animation: 150,
                handle: ".drag-handle",
                onEnd: function() {
                    updateImageOrder("{{ route('sliders-secundario.updateOrder') }}",
                        'image-previews-secundario');
                }
            });
        }

        // Asignar acción al modal de borrado
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const url = button.getAttribute('data-url');
                deleteForm.setAttribute('action', url);
            });
        }

        // Abrir modal de edición al hacer click en la card
        const editModalEl = document.getElementById('editSlideModal');
        const editModal = new bootstrap.Modal(editModalEl);
        const editForm = document.getElementById('editSlideForm');

        document.querySelectorAll('.editable-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Evitar conflicto con drag handle, botón borrar y links del overlay
                if (e.target.closest('.drag-handle') || e.target.closest('.delete-btn')) return;
                if (e.target.tagName === 'A') e.preventDefault();

                const action = this.dataset.updateUrl;
                const img = this.dataset.imagen || '';
                const t = this.dataset.titulo || '';
                const st = this.dataset.subtitulo || '';
                const bt = this.dataset.boton_titulo || '';
                const bu = this.dataset.boton_url || '';
                const bc = this.dataset.boton_color || '#0d6efd';

                // Setear action + valores
                editForm.setAttribute('action', action);
                document.getElementById('editSlidePreview').setAttribute('src', img);
                document.getElementById('edit_titulo').value = t;
                document.getElementById('edit_subtitulo').value = st;
                document.getElementById('edit_boton_titulo').value = bt;
                document.getElementById('edit_boton_url').value = bu;
                document.getElementById('edit_boton_color').value = bc;

                // Limpiar input file
                document.getElementById('editSlideImage').value = '';

                editModal.show();
            });
        });

        // Preview en vivo al cambiar imagen en modal de edición
        const editImageInput = document.getElementById('editSlideImage');
        if (editImageInput) {
            editImageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const url = URL.createObjectURL(this.files[0]);
                    document.getElementById('editSlidePreview').src = url;
                }
            });
        }

        // Evitar que el click del botón borrar abra el modal de edición
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', e => e.stopPropagation());
        });
    });

    function updateImageOrder(route, containerId) {
        let imageOrder = [];
        document.querySelectorAll(`#${containerId} .col-md-6.col-lg-4`).forEach(el => {
            imageOrder.push(el.getAttribute('data-id'));
        });

        fetch(route, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    order: imageOrder
                })
            })
            .then(response => response.json())
            .then(data => console.log("Order updated:", data))
            .catch(error => console.error("Error:", error));
    }
</script>

<script>
    // Loader: evita error si no existe #loading-screen
    document.addEventListener('DOMContentLoaded', function() {
        const loadingScreen = document.getElementById('loading-screen');

        function showLoader() {
            if (loadingScreen) loadingScreen.style.display = 'flex';
        }

        function hideLoader() {
            if (loadingScreen) loadingScreen.style.display = 'none';
        }

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                showLoader();
            });
        });

        window.addEventListener('load', hideLoader);
    });
</script>
