{{-- @extends('layouts/contentNavbarLayout')

@section('title', 'Agregar Producto')

@section('content')
    @include('components.toast')
    <div class="card">
        <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data"
            onsubmit="return validateForm()">
            @csrf
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title">Agregar Producto</h5>
                <button type="submit" class="btn btn-primary">Crear Producto</button>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control"
                                value="{{ old('nombre') }}" required>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control">{{ old('descripcion') }}</textarea>
                        </div>
                    </div>
                    <!-- Categoría -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="categoria_id" class="form-label">Categoría</label>
                            <select name="categoria_id" id="categoria_id" class="form-control" required>
                                <option value="">Seleccionar Categoría</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}"
                                        {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Estado -->
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select name="estado" id="estado" class="form-control" required>
                                <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Imágenes -->
                <div class="mb-3">
                    <label for="imagenes" class="form-label">Imágenes</label>
                    <input type="file" name="imagenes[]" id="imagenes" class="form-control" multiple>
                    <small class="form-text text-muted">Seleccione una o varias imágenes para el producto (JPEG, PNG, JPG,
                        GIF).</small>
                </div>
            </div>
        </form>
    </div>
@endsection

<script>
    function validateForm() {
        const newImages = document.getElementById('imagenes').files.length; // Nuevas imágenes seleccionadas

        if (newImages === 0) {
            const validationModal = new bootstrap.Modal(document.getElementById('validationModal'));
            validationModal.show(); // Muestra el modal
            return false; // Previene el envío del formulario
        }

        return true; // Permite el envío del formulario
    }
</script> --}}
@extends('layouts/contentNavbarLayout')

@section('title', 'Agregar Producto')

@section('content')
    @include('components.toast')

    <!-- Modal para mensaje de validación -->
    <div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="validationModalLabel">Advertencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Debe cargar al menos una imagen antes de crear el producto.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data"
            onsubmit="return validateForm()">
            @csrf
            <div class="card-header d-flex justify-content-between">
                <!-- Portada -->
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="portada" name="portada" value="1">
                    <label class="form-check-label" for="portada">Mostrar en portada</label>
                </div>
                <button type="submit" class="btn btn-primary">Crear Producto</button>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Nombre -->
                    <div class="col-lg-4 mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}"
                            required>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <label for="codigo" class="form-label">Codigo</label>
                        <input type="text" name="codigo" id="codigo" class="form-control">
                    </div>

                    <!-- Categoría -->
                    <div class="col-lg-3 mb-3">
                        <label for="categoria_id" class="form-label">Categoría</label>
                        {{-- <select name="categoria_id" id="categoria_id" class="form-control" required>
                            <option value="">Seleccionar Categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}"
                                    {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select> --}}
                        <select name="categoria_id" id="categoria_id" class="form-control" required>
                            <option value="">Seleccionar Categoría</option>
                            @foreach ($categorias->where('categoria_padre_id', null) as $categoria)
                                <option value="{{ $categoria->id }}"
                                    {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>

                                @foreach ($categorias->where('categoria_padre_id', $categoria->id) as $subcategoria)
                                    <option value="{{ $subcategoria->id }}"
                                        {{ old('categoria_id') == $subcategoria->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;→ {{ $subcategoria->nombre }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <!-- Estado -->
                    <div class="col-lg-3 mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select name="estado" id="estado" class="form-control" required>
                            <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <div id="editor-container"></div>
                    <input type="hidden" id="descripcion" name="descripcion" required>
                </div>

                <!-- Imágenes -->
                <div class="mb-3">
                    <label for="imagenes" class="form-label">Imágenes</label>
                    <input type="file" name="imagenes[]" id="imagenes" class="form-control" multiple>
                    <small class="form-text text-muted">Seleccione una o varias imágenes para el producto (JPEG, PNG, JPG,
                        GIF).</small>
                </div>
            </div>
        </form>
    </div>
@endsection

<script>
    function validateForm() {
        const newImages = document.getElementById('imagenes').files.length; // Nuevas imágenes seleccionadas

        if (newImages === 0) {
            const validationModal = new bootstrap.Modal(document.getElementById('validationModal'));
            validationModal.show(); // Muestra el modal
            return false; // Previene el envío del formulario
        }

        return true; // Permite el envío del formulario
    }
    document.addEventListener('DOMContentLoaded', function() {
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': '1'
                    }, {
                        'header': '2'
                    }],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    ['bold', 'italic', 'underline'],
                    ['link', 'blockquote'],
                    // ['image'],
                    [{
                        'align': []
                    }],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    ['clean']
                ]
            }
        });

        document.querySelector('form').addEventListener('submit', function() {
            var contenido = quill.root.innerHTML;
            document.getElementById('descripcion').value = contenido;
        });
    });
</script>
