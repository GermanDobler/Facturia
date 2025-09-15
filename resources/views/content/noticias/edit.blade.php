@extends('layouts/contentNavbarLayout')

@section('title', 'Editar Noticia')

@section('content')
    <!-- Formulario de Edición -->
    <form action="{{ route('noticias.update', $noticia->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <h5 class="card-header d-flex justify-content-between align-items-center">
                <span>Editar Noticia</span>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </h5>
            <div class="card-body">

                <div class="row">
                    <!-- Título -->
                    <div class="col-md-6 mb-3">
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Titulo</label>
                            <input type="text" class="form-control" id="titulo" name="titulo"
                                value="{{ old('titulo', $noticia->titulo) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="subtitulo" class="form-label">Fuente</label>
                                <input class="form-control" id="subtitulo" name="subtitulo"
                                    value="{{ old('subtitulo', $noticia->subtitulo) }}">
                            </div>
                            <input type="hidden" name="is_paid" value="no">
                            <div class="col-md-6 mb-3">
                                <label for="fuente_url" class="form-label">Fuente URL (opcional)</label>
                                <input type="url" class="form-control" id="fuente_url" name="fuente_url"
                                    value="{{ old('fuente_url', $noticia->fuente_url) }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-control" id="estado" name="estado" required>
                                    <option value="activa"
                                        {{ old('estado', $noticia->estado) == 'activa' ? 'selected' : '' }}>
                                        Activa
                                    </option>
                                    <option value="archivada"
                                        {{ old('estado', $noticia->estado) == 'archivada' ? 'selected' : '' }}>
                                        Archivada
                                    </option>
                                </select>
                            </div>
                            <input type="hidden" name="is_paid" value="no">
                            <div class="col-md-6 mb-3">
                                <label for="etiquetas" class="form-label">Tags</label>
                                <select class="form-control" id="etiquetas" name="etiquetas[]" multiple="multiple"
                                    style="width: 100%">
                                    @foreach ($etiquetas as $etiqueta)
                                        <option value="{{ $etiqueta->id }}"
                                            {{ $noticia->etiquetas->contains($etiqueta->id) ? 'selected' : '' }}>
                                            {{ $etiqueta->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Selecciona o crea.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="imagen_url" class="form-label">Imagen</label>
                        @if ($noticia->imagen_url)
                            <!-- Mostrar imagen actual -->
                            <div id="image-preview" class="position-relative">
                                <img src="{{ asset(env('IMAGE_PATH') . basename($noticia->imagen_url)) }}"
                                    alt="Imagen actual" class="img-fluid mb-2" style="max-width: 100%; height: auto;">
                                <button type="button" id="remove-image"
                                    class="btn btn-danger position-absolute top-0 end-0" style="z-index: 10;">X</button>
                            </div>
                        @endif

                        <!-- Input para cargar nueva imagen -->
                        <div id="new-image-upload" class="{{ $noticia->imagen_url ? 'd-none' : '' }}">
                            <input type="file" class="form-control mt-2" id="imagen_url" name="imagen_url"
                                accept="image/jpeg, image/png, image/jpg">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="pdf_file" class="form-label">PDF adjunto</label>
                    <div class="border rounded bg-light p-3 d-flex align-items-center justify-content-between">
                        <span id="pdf_filename" class="text-muted">
                            {{ $noticia->pdf_url ? basename($noticia->pdf_url) : 'No file selected' }}
                        </span>
                        <button type="button" class="btn btn-outline-secondary"
                            onclick="document.getElementById('pdf_file').click()">Subir PDF</button>
                    </div>
                    <input type="file" class="d-none" id="pdf_file" name="pdf_file" accept="application/pdf">
                    <input type="hidden" name="remove_pdf" id="remove_pdf" value="0">
                    <small class="form-text text-muted">Solo archivos PDF. Max tamaño 5MB.</small>
                </div>

                <div class="row mb-3">
                    <div id="pdf_preview_container" class="mt-3 {{ $noticia->pdf_url ? '' : 'd-none' }}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Preview PDF</strong>
                            <button type="button" class="btn btn-danger btn-sm" id="remove-pdf-btn">eliminar
                                PDF</button>
                        </div>
                        <iframe id="pdf_preview" src="{{ asset(env('IMAGE_PATH') . basename($noticia->pdf_url)) }}"
                            width="100%" height="750px"></iframe>
                    </div>
                </div>

                <div class="row">
                    <label for="contenido_html" class="form-label">Contenido</label>
                    <div id="editor-container"></div>
                    <input style="display: none;" id="contenido_html" name="contenido_html"
                        value="{{ old('contenido_html', $noticia->contenido_html) }}">
                </div>
            </div>
        </div>
    </form>

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pdfInput = document.getElementById('pdf_file');
        const pdfLabel = document.getElementById('pdf_filename');
        const pdfPreviewContainer = document.getElementById('pdf_preview_container');
        const pdfPreview = document.getElementById('pdf_preview');
        const removePdfBtn = document.getElementById('remove-pdf-btn');
        const removePdfInput = document.getElementById('remove_pdf');

        pdfInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                if (file.type !== 'application/pdf') {
                    alert('Solo se permiten archivos PDF.');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) { // 5MB
                    alert('El archivo excede el tamaño máximo de 5MB.');
                    return;
                }

                pdfLabel.textContent = file.name;
                removePdfInput.value = '0'; // Se está subiendo uno nuevo

                const reader = new FileReader();
                reader.onload = function(e) {
                    pdfPreview.src = e.target.result;
                    pdfPreviewContainer.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                resetPdf();
            }
        });

        removePdfBtn.addEventListener('click', function() {
            resetPdf();
            removePdfInput.value = '1'; // Marca para eliminar el PDF en el servidor si ya existía
        });

        function resetPdf() {
            pdfInput.value = '';
            pdfLabel.textContent = 'No file selected';
            pdfPreview.src = '';
            pdfPreviewContainer.classList.add('d-none');
        }
    });
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

        // Inicializar el contenido del editor
        var contenido_html = @json($noticia->contenido_html);
        if (contenido_html) {
            quill.root.innerHTML = contenido_html;
        }

        // Actualizar el input oculto antes de enviar el formulario
        // document.querySelector('form').addEventListener('submit', function() {
        //     var contenido = quill.root.innerHTML;
        //     document.getElementById('contenido_html').value = contenido;
        // });

        const contenidoInput = document.getElementById('contenido_html');

        quill.on('text-change', function() {
            contenidoInput.value = quill.root.innerHTML;
        });

        // Botón para eliminar imagen
        const removeImageButton = document.getElementById('remove-image');
        const imagePreview = document.getElementById('image-preview');
        const newImageUpload = document.getElementById('new-image-upload');

        if (removeImageButton) {
            removeImageButton.addEventListener('click', function() {
                if (imagePreview) {
                    imagePreview.remove(); // Eliminar contenedor de la imagen
                }
                if (newImageUpload) {
                    newImageUpload.classList.remove('d-none'); // Mostrar input de archivo
                }
            });
        }

        // Inicialización de Select2 con jQuery
        $('#etiquetas').select2({
            placeholder: 'Select tags',
            tags: true
        });
    });
</script>
