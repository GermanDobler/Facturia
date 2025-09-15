@extends('layouts/contentNavbarLayout')

@section('title', 'Quienes Somos')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
@section('content')
    @include('components.toast')

    <form action="{{ route('storeSomos') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Pagina /</span> somos <button type="submit" class="btn btn-primary float-end">
                Guardar cambios
            </button>
        </h4>

        <div class="card mb-4">
            <h5 class="card-header">PORTADA

            </h5>
            <div class="card-body">
                <div class="row">
                    <div class="row mb-3">
                        <div class="col-4">
                            <label class="col-form-label" for="portada">Portada</label>
                            <input type="file" class="form-control" id="portada" name="portada"
                                {{ empty($somos->portada) ? 'required' : '' }}>
                        </div>
                        <div class="col-4">
                            <label class="col-form-label" for="titulo">Titulo</label>
                            <input type="text" class="form-control" id="titulo" name="titulo"
                                placeholder="(opcional)" value="{{ old('titulo', $somos->titulo ?? '') }}">
                        </div>
                        <div class="col-4">
                            <label class="col-form-label" for="subtitulo">Subtitulo</label>
                            <input type="text" class="form-control" id="subtitulo" name="subtitulo"
                                placeholder="(opcional)" value="{{ old('subtitulo', $somos->subtitulo ?? '') }}">
                        </div>
                    </div>
                    <div id="image-portada" class="row">
                        @if (!empty($somos->portada))
                            <!-- Mostrar imagen previa de la portada -->
                            <div class="row">
                                <img src="{{ asset(env('IMAGE_PATH') . basename($somos->portada)) }}" class="card-img-top"
                                    alt="Image preview" style="object-fit: cover; height: 520px; width: 100%;">
                            </div>
                        @endif
                    </div>

                    @error('portada')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card">
            <h5 class="card-header">CONTENIDO</h5>
            <div class="card-body">
                {{-- <div class="row mb-3">
                    <label class="col-form-label" for="tituloc">Titulo</label>
                    <input type="text" class="form-control" id="tituloc" name="tituloc" placeholder="(opcional)"
                        value="{{ old('tituloc', $somos->tituloc ?? '') }}">
                </div> --}}
                <div class="row">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <div id="editor-container"></div>
                    <input type="hidden" id="descripcion" name="descripcion"
                        value="{{ old('descripcion', $somos->descripcion ?? '') }}">
                </div>
                {{-- <div class="row mb-3">
                    <label class="col-form-label" for="contenido">Contenido</label>
                    <input type="file" class="form-control" id="contenido" name="contenido"
                        {{ empty($somos->contenido) ? 'required' : '' }}>
                </div> --}}
                {{-- <div id="image-contenido" class="row">
                    @if (!empty($somos->contenido))
                        <!-- Mostrar imagen previa del contenido -->
                        <div class="row">
                            <img src="{{ asset(env('IMAGE_PATH') . basename($somos->contenido)) }}" class="card-img-top"
                                alt="Image preview" style="object-fit: cover; height: 388px; width: 568;">
                        </div>
                    @endif
                </div> --}}


                @error('portada')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </form>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imagePortada = document.getElementById('portada');
        const previewsPortada = document.getElementById('image-portada');
        imagePortada.addEventListener('change', function(event) {
            // Limpia el contenedor de vistas previas y el arreglo de archivos
            previewsPortada.innerHTML = '';
            selectedFiles = Array.from(event.target.files);

            selectedFiles.forEach(file => {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;

                        const card = document.createElement('div');
                        card.innerHTML = `
                <div class="row" data-file-index="${selectedFiles.indexOf(file)}">
                  <img src="${img.src}" class="card-img-top" alt="Image preview" style="object-fit: cover; height: 520px; width: 100%;">
                </div>
              `;

                        previewsPortada.appendChild(card);
                    };

                    reader.readAsDataURL(file);
                }
            });
        });
        const imageContenido = document.getElementById('contenido');
        const previewsContenido = document.getElementById('image-contenido');
        imageContenido.addEventListener('change', function(event) {
            // Limpia el contenedor de vistas previas y el arreglo de archivos
            previewsContenido.innerHTML = '';
            selectedFiles = Array.from(event.target.files);

            selectedFiles.forEach(file => {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;

                        const card = document.createElement('div');
                        card.innerHTML = `
                <div class="row" data-file-index="${selectedFiles.indexOf(file)}">
                  <img src="${img.src}" class="card-img-top" alt="Image preview" style="object-fit: cover; height: 388px; width: 568;">
                </div>
              `;

                        previewsContenido.appendChild(card);
                    };

                    reader.readAsDataURL(file);
                }
            });
        });
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
                    ['image'],
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
        var descripcion = @json($somos->descripcion);
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
