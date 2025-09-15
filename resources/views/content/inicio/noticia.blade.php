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

    .contenido iframe {
        aspect-ratio: 16 / 9;
        width: 100% !important;
        height: auto !important;
        max-width: 100%;
        border: none;
        display: block;
    }
</style>
<style>
    .rel-item {
        transition: transform .15s ease, box-shadow .15s ease, background-color .15s ease;
    }

    .rel-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
        background-color: #f8f9fa;
    }

    .rel-thumb {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: 12px;
        flex: 0 0 72px;
    }

    .rel-badge {
        background-color: #202a9a;
    }

    .rel-title {
        font-weight: 600;
        line-height: 1.25rem;
        font-size: .95rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Opcional: card más “apretada” en pantallas chicas */
    @media (max-width: 992px) {
        .rel-thumb {
            width: 64px;
            height: 64px;
            border-radius: 10px;
        }
    }
</style>
<style>
    /* Capa de overlay ocupando todo el slide */
    .hero-caption-layer {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        /* centra vertical en desktop */
        pointer-events: none;
        /* deja pasar clicks a controles */
        background: linear-gradient(0deg, rgba(0, 0, 0, 0.65) 0%, rgba(0, 0, 0, 0.25) 40%, rgba(0, 0, 0, 0.0) 70%);
    }

    /* Contenido clickeable */
    .hero-caption-content {
        pointer-events: auto;
        color: #fff;
        max-width: 1100px;
        /* opcional */
    }

    /* Posición del bloque de texto: abajo en mobile, centro en desktop */
    @media (max-width: 767.98px) {
        .hero-caption-layer {
            align-items: flex-end;
        }

        .hero-caption-content {
            padding: 18px;
            text-align: center;
        }
    }

    @media (min-width: 768px) {
        .hero-caption-content {
            padding: 40px 56px;
            text-align: left;
        }
    }

    .hero-title {
        font-size: 60px;
        font-weight: 900;
        line-height: 1;
        color: #fff;
    }

    .hero-subtitle {
        font-size: 1.3rem;
        line-height: 1.6;
        opacity: .95;
        max-width: 60ch;
    }

    .hero-btn {
        box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
        border-radius: .65rem;
    }
</style>
@section('layoutContent')
    <section class="section hero__v7 position-relative pt-0">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">

                <div class="carousel-item active">
                    <img class="d-block w-100" src="{{ asset('assets/img/noticias.jpg') }}" alt="Slide 1"
                        style="max-height: 700px">

                    <div class="hero-caption-layer">
                        <div class="hero-caption-content container justify-content-center text-center">
                            <h1 class="hero-title mb-2 text-white">{{ $noticia->titulo }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section hero__v7 first-section position-relative pt-0 ">
        <div class="container">
            <div class="row justify-center">
                <div class="col-lg-7">
                    <!-- Post content-->
                    <article>
                        <!-- Post header-->
                        <header class="mb-4">
                            <!-- Post title-->
                            {{-- <h1 class="fw-bolder mb-1" style="color:#212121">{{ $noticia->titulo }}</h1> --}}
                            {{-- <h2 class="mb-1 subtitulo">{{ $noticia->subtitulo }}</h2> --}}
                            <!-- Post meta content-->
                            {{-- <div class="text-muted fst-italic mb-2">
                                <!-- Aquí muestra la fecha de creación o cualquier otra información -->
                                {{ $noticia->created_at->locale('es')->translatedFormat('j \\d\\e F \\d\\e Y') }}
                            </div>
                            @if ($noticia->etiquetas->isNotEmpty())
                                @foreach ($noticia->etiquetas as $etiqueta)
                                    <span class="badge me-1"
                                        style="background-color: #5577b4">{{ $etiqueta->nombre }}</span>
                                @endforeach
                            @endif --}}
                            <!-- Post categories-->
                            <a class="badge text-decoration-none link-light" style="background-color: #5577b4"
                                href="#!">{{ $noticia->etiqueta }}</a>
                        </header>
                        <!-- Preview image figure-->
                        {{-- @if ($noticia->imagen_url !== null)
                            <figure class="mb-4">
                                <img class="img-top rounded w-100"
                                    src="{{ asset(env('IMAGE_PATH') . basename($noticia->imagen_url)) }}"
                                    alt="{{ $noticia->titulo }}" />
                            </figure>
                        @endif --}}



                        <!-- Post content-->
                        <section class="contenido">
                            {!! $noticia->contenido_html !!}
                        </section>
                        @if ($noticia->pdf_url !== null)
                            <section class="mb-4">
                                {{-- <a href="{{ asset(env('IMAGE_PATH') . basename($noticia->pdf_url)) }}">>Ver
                                    PDF</a> --}}
                                @if ($noticia->pdf_url)
                                    <a href="{{ asset(env('IMAGE_PATH') . basename($noticia->pdf_url)) }}" class="btn"
                                        style="border-radius: 4px;background-color: #5577b4;" target="_blank">
                                        Ver PDF
                                    </a>
                                    <a href="{{ asset(env('IMAGE_PATH') . basename($noticia->pdf_url)) }}" class="btn"
                                        style="border-radius: 4px;background-color: #5577b4;" download>
                                        Descargar PDF
                                    </a>
                                @endif
                            </section>
                        @endif
                    </article>
                </div>
                <div class="col-lg-4 px-3">
                    <!-- Sidebar widgets-->
                    <div class="card" style="position: sticky; top: 100px;">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Noticias Relacionadas</span>
                            <a href="{{ route('noticias_inicio') }}" class="small text-decoration-none">Ver todas</a>
                        </div>

                        <div class="card-body p-2">
                            @forelse ($relacionadas as $r)
                                <a href="{{ route('getNoticia', ['slug' => Str::slug($r->titulo) . '-' . $r->id]) }}"
                                    class="rel-item d-flex gap-3 align-items-center p-2 rounded-3 text-decoration-none">
                                    <img class="rel-thumb" width="64" height="64"
                                        src="{{ asset(env('IMAGE_PATH') . basename($r->imagen_url)) }}"
                                        alt="{{ $r->titulo }}" loading="lazy">
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                            <time class="text-muted small" datetime="{{ $r->created_at->toDateString() }}">
                                                {{ $r->created_at->format('d M Y') }}
                                            </time>
                                        </div>
                                        <h6 class="rel-title mb-0 text-dark">
                                            {{ $r->titulo }}
                                        </h6>
                                    </div>
                                </a>
                                <div class="border-bottom my-2"></div>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-newspaper" style="font-size:1.6rem;"></i>
                                    <p class="mb-0 mt-2">No hay noticias relacionadas.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
