@extends('layouts/contentNavbarLayout')

@section('title', 'Actualizar Producto')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>

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
                    Debe cargar al menos una imagen antes de actualizar el producto.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <form action="{{ route('productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data"
            onsubmit="return validateForm()">
            @csrf
            @method('PUT')
            <div class="card-body">
                <!-- Mostrar imágenes existentes -->
                <div class="card-header d-flex justify-content-between p-0 pb-2">
                    <!-- Portada -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="portada" name="portada" value="1"
                            {{ old('portada', $producto->portada) ? 'checked' : '' }}>
                        <label class="form-check-label" for="portada">Mostrar en portada </label>
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                </div>
                <div class="row">
                    <!-- Nombre -->
                    <div class="col-lg-4 mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control"
                            value="{{ old('nombre', $producto->nombre) }}" required>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <label for="codigo" class="form-label">Codigo</label>
                        <input type="text" name="codigo" id="codigo" class="form-control"
                            value="{{ old('codigo', $producto->codigo) }}">
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="categoria_id">Categoría</label>
                            <select class="form-control" id="categoria_id" name="categoria_id" required>
                                <option value="">Seleccionar Categoría</option>
                                @foreach ($categorias->where('categoria_padre_id', null) as $categoria)
                                    <option value="{{ $categoria->id }}"
                                        {{ $producto->categoria_id == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>

                                    @foreach ($categorias->where('categoria_padre_id', $categoria->id) as $subcategoria)
                                        <option value="{{ $subcategoria->id }}"
                                            {{ $producto->categoria_id == $subcategoria->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;→ {{ $subcategoria->nombre }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="estado">Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="activo" {{ $producto->estado == 'activo' ? 'selected' : '' }}>Activo
                                </option>
                                <option value="inactivo" {{ $producto->estado == 'inactivo' ? 'selected' : '' }}>Inactivo
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <div id="editor-container"></div>
                    <input type="hidden" id="descripcion" name="descripcion"
                        value="{{ old('descripcion', $producto->descripcion) }}">
                </div>
                <div class="mb-3">
                    <label class="col-sm-2 col-form-label" for="imagenes">Imágenes</label>
                    <div class="col-sm-12">
                        <input type="hidden" id="images-to-delete" name="images_to_delete">
                        <input type="file" class="form-control" id="imagenes" name="imagenes[]" multiple>
                        <div id="image-previews" class="row mt-3">
                            @foreach ($producto->imagenes->sortBy('orden') as $item)
                                <div class="col-sm-4 mb-3" data-file-id="{{ $item->id }}">
                                    <div class="card image-card">
                                        <img src="{{ asset(env('IMAGE_PATH') . basename($item->url)) }}"
                                            class="card-img-top" alt="Image preview">
                                        <div class="card-body d-flex justify-content-between">
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="removeImage(this, {{ $item->id }})">Eliminar</button>
                                            <span class="drag-handle" style="cursor: grab;">☰</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" id="image-order" name="image_order">
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

<style>
    .image-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .image-card:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>


<script>
    function validateForm() {
        const existingImages = document.querySelectorAll('#image-previews .col-sm-4').length; // Imágenes existentes
        const newImages = document.getElementById('imagenes').files.length; // Nuevas imágenes seleccionadas

        if (existingImages === 0 && newImages === 0) {
            const validationModal = new bootstrap.Modal(document.getElementById('validationModal'));
            validationModal.show(); // Muestra el modal
            return false; // Previene el envío del formulario
        }

        return true; // Permite el envío del formulario
    }
    // Array para almacenar los IDs de las imágenes a eliminar
    let imagesToDelete = [];

    // Función para manejar la eliminación de una imagen
    function removeImage(button, mediaId) {
        console.log('Eliminando imagen con ID:', mediaId);
        // Encuentra la tarjeta (div) de la imagen
        const card = button.closest('.card');
        const imageContainer = card.closest('.col-sm-4');

        // Agrega el ID de la imagen a la lista de imágenes a eliminar
        imagesToDelete.push(mediaId);

        // Elimina la tarjeta del DOM
        imageContainer.remove();

        // Actualiza el campo oculto con los IDs de las imágenes a eliminar
        document.getElementById('images-to-delete').value = imagesToDelete.join(',');
        console.log(document.getElementById('images-to-delete').value)
        // Actualiza el input de archivos si hay imágenes seleccionadas
        updateFileInput();
    }

    // Función para actualizar el input de archivos (en caso de que se eliminen imágenes)
    function updateFileInput() {
        const fileInput = document.getElementById('imagenes');
        const dataTransfer = new DataTransfer();

        // Recorre todos los archivos seleccionados y los agrega al DataTransfer
        Array.from(fileInput.files).forEach(file => dataTransfer.items.add(file));

        // Actualiza el campo de archivos
        fileInput.files = dataTransfer.files;
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let sortable = new Sortable(document.getElementById('image-previews'), {
            animation: 150,
            handle: ".drag-handle",
            onEnd: function(evt) {
                updateImageOrder();
            }
        });
    });

    function updateImageOrder() {
        let imageOrder = [];
        document.querySelectorAll('#image-previews .col-sm-4').forEach((el, index) => {
            imageOrder.push(el.getAttribute('data-file-id'));
        });
        document.getElementById('image-order').value = imageOrder.join(',');
    }
</script>
<script>
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

        // Inicializar el contenido del editor
        var descripcion = @json($producto->descripcion);
        if (descripcion) {
            quill.root.innerHTML = descripcion;
        }

        // Actualizar el input oculto antes de enviar el formulario
        document.querySelector('form').addEventListener('submit', function() {
            var contenido = quill.root.innerHTML;
            document.getElementById('descripcion').value = contenido;
        });
    });
</script>
