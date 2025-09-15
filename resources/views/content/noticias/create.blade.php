@extends('layouts/contentNavbarLayout')

@section('title', 'Create News')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 with jQuery
        $('#etiquetas').select2({
            placeholder: 'Select tags',
            tags: true
        });
    });
</script>

@section('content')
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <strong>Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
            <strong>Revisá los siguientes errores:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Noticias /</span> Crear Noticia
    </h4>
    <form action="{{ route('noticias.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <h5 class="card-header d-flex justify-content-between align-items-center">
                <span>Nueva Noticia</span>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </h5>
            <div class="card-body">
                <div class="row">
                    <!-- Title -->
                    <div class="col-md-6 mb-3">
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Titulo</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="subtitulo" class="form-label">Fuente</label>
                                <input type="text" class="form-control" id="subtitulo" name="subtitulo">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fuente_url" class="form-label">Fuente URL (opcional)</label>
                                <input type="url" class="form-control" id="fuente_url" name="fuente_url">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-control" id="estado" name="estado" required>
                                    <option value="activa">Activa</option>
                                    <option value="archivada">Archivada</option>
                                </select>
                            </div>
                            <input type="hidden" name="is_paid" value="no">

                            <div class="col-md-6 mb-3">
                                <label for="etiquetas" class="form-label">Tags</label>
                                <select class="form-control" id="etiquetas" name="etiquetas[]" multiple="multiple"
                                    style="width: 100%">
                                    @foreach ($etiquetas as $etiqueta)
                                        <option value="{{ $etiqueta->id }}">{{ $etiqueta->nombre }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Selecciona o crea nuevos tags.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="imagen_url" class="form-label">Imagen</label>
                        <div class="border rounded bg-light d-flex justify-content-center align-items-center position-relative"
                            style="height: 300px; cursor: pointer;" onclick="document.getElementById('imagen_url').click()">
                            <img id="preview" src="#" alt="Preview"
                                class="img-fluid d-none h-100 w-100 object-fit-cover rounded">
                            <div id="placeholder" class="text-center text-secondary">
                                <i class="bi bi-image fs-1"></i>
                                <p>Click para subir imagen</p>
                            </div>
                        </div>
                        <input type="file" class="d-none" id="imagen_url" name="imagen_url"
                            accept="image/jpeg, image/png, image/jpg" onchange="previewImage(event)">
                        <small class="form-text text-muted">Tamaño maximo 10MB.</small>
                    </div>
                </div>
                <div class="row">
                    <label for="pdf_file" class="form-label">PDF</label>
                    <div class="border rounded bg-light p-3 d-flex align-items-center justify-content-between">
                        <span id="pdf_filename" class="text-muted">No hay archivo seleccionado</span>
                        <button type="button" class="btn btn-outline-secondary"
                            onclick="document.getElementById('pdf_file').click()">Subir PDF</button>
                    </div>
                    <input type="file" class="d-none" id="pdf_file" name="pdf_file" accept="application/pdf"
                        onchange="showPDFName(event)">
                    <small class="form-text text-muted">Solo archivos pdf. Max tamaño 5MB.</small>
                </div>
                <div class="row">
                    <div id="pdf_preview_container" class="mt-3 d-none">
                        <iframe id="pdf_preview" width="100%" height="750px"></iframe>
                    </div>
                </div>
                <div class="row">
                    <label class="form-label">Contenido</label>
                    <div id="editor-container"></div>
                    <!-- En vez del input hidden -->
                    <textarea id="contenido_html" name="contenido_html" style="display: none;"></textarea>
                </div>
            </div>
        </div>
    </form>
    <script>
        document.getElementById('pdf_file').addEventListener('change', function(event) {
            const input = event.target;
            const label = document.getElementById('pdf_filename');
            const previewContainer = document.getElementById('pdf_preview_container');
            const preview = document.getElementById('pdf_preview');

            if (input.files.length > 0) {
                const file = input.files[0];
                label.textContent = file.name;

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                label.textContent = 'No file selected';
                previewContainer.classList.add('d-none');
                preview.src = '';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Quill init
            const quill = new Quill('#editor-container', {
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
                        ['image', 'video'],
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

            const form = document.querySelector('form');
            const contenidoInput = document.getElementById('contenido_html');

            // Log en cada cambio de Quill
            quill.on('text-change', function() {
                contenidoInput.value = quill.root.innerHTML;
            });

            // Preview image
            document.getElementById('imagen_url').addEventListener('change', function(event) {
                const input = event.target;
                const preview = document.getElementById('preview');
                const placeholder = document.getElementById('placeholder');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('d-none');
                        placeholder.classList.add('d-none');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            });

            // Select2 init
            $('#etiquetas').select2({
                placeholder: 'Select tags',
                tags: true
            });
        });
    </script>

@endsection
