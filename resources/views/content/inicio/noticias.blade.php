@extends('layouts/front')

@section('title', 'Preguntas Frecuentes')

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
    @include('components.toast')
    <section class="section hero__v7 position-relative pt-0">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">

                <div class="carousel-item active">
                    <img class="d-block w-100" src="{{ asset('assets/img/noticias.jpg') }}" alt="Slide 1"
                        style="max-height: 700px">

                    <div class="hero-caption-layer">
                        <div class="hero-caption-content container justify-content-center text-center">
                            <h1 class="hero-title mb-2 text-white">Noticias</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="section recentblog__v3 pt-0" id="blog">
        <div class="container">
            <div class="row mb-5 g-4">
                @if ($noticias->count())
                    @foreach ($noticias as $noticia)
                        <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up">
                            @if ($noticia->fuente_url)
                                <a class="blog-entry rounded-4 overflow-hidden" href="{{ $noticia->fuente_url }}"
                                    target="_blank" rel="noopener noreferrer">
                                    <img class="object-fit-cover w-100 img-fluid thumb"
                                        src="{{ asset(env('IMAGE_PATH') . basename($noticia->imagen_url)) ?: asset('assets/inicio/images/img-9-min.jpg') }}"
                                        alt="{{ $noticia->titulo }}" loading="lazy">
                                    <div class="content text-center p-4">
                                        {{-- <span class="d-inline-block mb-2 small">
                                        @if ($noticia->etiquetas->isNotEmpty())
                                            {{ $noticia->etiquetas->first()->nombre }}
                                        @else
                                            General
                                        @endif

                                        &bullet;
                                        <time class="text-muted" datetime="{{ $noticia->created_at->toDateString() }}">
                                            {{ $noticia->created_at->format('d M Y') }}
                                        </time>
                                    </span> --}}

                                        <h2 class="fs-5 fw-bold mb-3">{{ Str::limit($noticia->titulo, 70) }}</h2>
                                        <p class="text-muted
                                        mb-3">fuente:
                                            {{ $noticia->subtitulo ? $noticia->subtitulo : 'propia' }} </p>
                                    </div>

                                </a>
                            @else
                                <a class="blog-entry rounded-4 overflow-hidden"
                                    href="{{ route('getNoticia', ['slug' => Str::slug($noticia->titulo) . '-' . $noticia->id]) }}">
                                    <img class="object-fit-cover w-100 img-fluid thumb"
                                        src="{{ asset(env('IMAGE_PATH') . basename($noticia->imagen_url)) ?: asset('assets/inicio/images/img-9-min.jpg') }}"
                                        alt="{{ $noticia->titulo }}" loading="lazy">
                                    <div class="content text-center p-4">
                                        {{-- <span class="d-inline-block mb-2 small">
                                        @if ($noticia->etiquetas->isNotEmpty())
                                            {{ $noticia->etiquetas->first()->nombre }}
                                        @else
                                            General
                                        @endif

                                        &bullet;
                                        <time class="text-muted" datetime="{{ $noticia->created_at->toDateString() }}">
                                            {{ $noticia->created_at->format('d M Y') }}
                                        </time>
                                    </span> --}}

                                        <h2 class="fs-5 fw-bold mb-3">{{ Str::limit($noticia->titulo, 70) }}</h2>
                                        <p class="text-muted
                                        mb-3">fuente:
                                            {{ $noticia->subtitulo ? $noticia->subtitulo : 'propia' }} </p>
                                    </div>

                                </a>
                            @endif


                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="text-center text-muted">No hay noticias publicadas aún.</div>
                    </div>
                @endif
                <div class="d-flex justify-content-center mt-2">
                    {{ $noticias->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection
