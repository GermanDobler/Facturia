@extends('layouts/contentNavbarLayout')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
@section('content')
    <h1>Politica privacidad</h1>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('informacion_util.update') }}" method="POST">
                @csrf
                @method('POST')

                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-control"
                        value="{{ $informacion->titulo ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label for="contenido" class="form-label">Contenido</label>
                    <div id="editor-container">{!! $informacion->contenido !!}</div>
                    <input style="display: none;" id="contenido" name="contenido"
                        value="{{ old('contenido', $informacion->contenido) }}">
                </div>

                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
@endsection


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
        var contenido = @json($informacion->contenido);
        if (contenido) {
            quill.root.innerHTML = contenido;
        }
        const contenidoInput = document.getElementById('contenido');

        quill.on('text-change', function() {
            contenidoInput.value = quill.root.innerHTML;
        });
    });
</script>
