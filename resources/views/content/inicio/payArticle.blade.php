@extends('layouts/front')

@section('title', $noticia->titulo) <!-- Título de la noticia -->

<style type="text/css">
    /* Ajusta la relación de aspecto de la imagen */
    .img-top {
        width: 100%;
        aspect-ratio: 16/9;
        /* Ajusta la relación de aspecto */
        object-fit: cover;
        /* Recorta la imagen sin distorsionarla */
    }

    .subtitulo {
        font-size: 1.2rem;
        line-height: 2rem;
        font-weight: 400;
    }

    .contenido {
        font-family: 'Montserrat', sans-serif !important; /* Aplica la fuente Montserrat con !important */
        font-size: 1.125rem;
        line-height: 1.875rem;
        color: #424242;
    }

    .contenido p {
        font-family: 'Montserrat', sans-serif !important; /* Fuerza la fuente Montserrat en los párrafos */
        font-size: 16px !important;
        font-weight: 300 !important;
        line-height: 30px !important;
        color: #1c1e36 !important; /* Mantén el color del texto */
        margin: 0;
    }

    .contenido iframe {
        aspect-ratio: 16 / 9;
    width: 100% !important;
    height: auto !important;
    max-width: 100%;
    border: none;
    display: block;
}
</style>

@section('layoutContent')
<section class="section hero__v7 first-section position-relative pb-0">
    <div class="container mt-5">
        <div class="row justify-center">
            <div class="col-lg-8">
                <!-- Post content-->
                <article>
                    <!-- Post header-->
                    <header class="mb-4">
                        <!-- Post title-->
                        <h1 class="fw-bolder mb-1" style="color:#212121">{{ $noticia->titulo }}</h1>
                        <h2 class="mb-1 subtitulo">{{ $noticia->subtitulo }}</h2>
                        <!-- Post meta content-->
                        <div class="text-muted fst-italic mb-2">
                            <!-- Aquí muestra la fecha de creación o cualquier otra información -->
                            {{ $noticia->created_at->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') }}
                        </div>
                        @if ($noticia->etiquetas->isNotEmpty())
                            @foreach ($noticia->etiquetas as $etiqueta)
                                <span class="badge me-1" style="background-color: #202a9a" >{{ $etiqueta->nombre }}</span>
                            @endforeach
                        @endif
                        <!-- Post categories-->
                        <a class="badge text-decoration-none link-light" style="background-color: #202a9a" 
                            href="#!">{{ $noticia->etiqueta }}</a>
                    </header>
                    <!-- Preview image figure-->
                    @if ($noticia->imagen_url !== null)
                    <figure class="mb-4">
                        <img class="img-top rounded w-100" src="{{ asset(env('IMAGE_PATH') . basename($noticia->imagen_url)) }}" alt="{{ $noticia->titulo }}" />
                    </figure>
                    @endif
                    <!-- Post content-->
                    @if ($noticia->pdf_url !== null)
                    <section class="mb-4">
                        <iframe
                            id="pdf_preview"
                            src="{{ asset(env('IMAGE_PATH') . basename($noticia->pdf_url)) }}"
                            width="100%"
                            height="750px"
                        ></iframe>
                        @if ($noticia->pdf_url)
                        <a href="{{ asset(env('IMAGE_PATH') . basename($noticia->pdf_url)) }}" class="btn btn-primary mt-3" target="_blank">
                            View PDF
                        </a>
                            <a href="{{ asset(env('IMAGE_PATH') . basename($noticia->pdf_url)) }}" class="btn btn-primary mt-3" download>
                                Download PDF
                            </a>
                        @endif
                    </section>
                    @endif
                    <section class="mb-5 contenido">
                        {!! $noticia->contenido_html !!}
                    </section>
                </article>
            </div>
            <div class="col-lg-4">
                <!-- Sidebar widgets-->
                <div class="card" style="position: sticky; top: 100px;">
                    <div class="card-header">Related Articles</div>
                    <div class="card-body">
                        @forelse ($relacionadas as $relacionada)
                            <div class="mb-2">
                                <a href="{{ route('getNoticia', ['slug' => Str::slug($relacionada->titulo) . '-' . $relacionada->id]) }}"
                                    class="text-decoration-none text-dark">
                                    {{ $relacionada->titulo }}
                                </a>
                            </div>
                        @empty
                            <p class="mb-0">No related articles.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
