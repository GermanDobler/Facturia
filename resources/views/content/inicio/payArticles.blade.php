<style>
    .slider-container {
        overflow: hidden;
        width: 100%;
        height: 200px;
        display: flex;
        align-items: center;
        /* ✅ centra verticalmente */
        justify-content: flex-start;
        position: relative;
    }

    .slider-track {
        display: flex;
        align-items: center;
        /* ✅ también por si acaso */
        width: max-content;
        animation: scroll 10s linear infinite;
    }

    .slider-track img {
        max-height: 100%;
        /* ✅ para que respeten el alto */
        height: auto;
        width: auto;
        margin-right: 30px;
        flex-shrink: 0;
        display: block;
        object-fit: contain;
    }

    @keyframes scroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }


    .app-article-list-row {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .app-article-list-row__item {
        border-bottom: 1px solid #d5d5d5;
        padding-bottom: 16px;
        margin-bottom: 16px;
    }
</style>
@extends('layouts/front')
@section('layoutContent')
    <!-- ======= Hero =======-->

    <!-- ======= Hero =======-->
    <section class="section hero__v7 first-section position-relative pb-0" id="home">
        <div class="container-fluid p-0">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner ">
                    @foreach ($slider as $index => $slide)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img class="d-block w-100" src="{{ asset(env('IMAGE_PATH') . basename($slide->imagen_url)) }}"
                                alt="Slide image">
                        </div>
                    @endforeach
                </div>

                <!-- Controles del carrusel si hay más de una imagen -->
                @if (count($slider) > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                @endif
            </div>
        </div>
    </section>
    <!-- End Hero-->

    <section class="position-relative section pb-0 products__v1 mb-5" id="best-sellers">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="h-custom fs-1" data-aos="fade-up" data-aos-delay="0">Articles</h2>
                </div>
                {{-- 
                <div class="col-md-6 mx-auto">
                    <form method="GET" action="{{ route('articles-pay') }}">
                        <div class="input-group">
                            <input type="text" name="buscar" class="form-control" placeholder="Buscar artículo..."
                                value="{{ request('buscar') }}">
                            <button class="btn btn-primary bg-black" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div> --}}
            </div>

            @if (Auth::check() && Auth::user()->is_paid === 'no')
                <div class="alert alert-warning text-center" role="alert">
                    Estás viendo una versión limitada de los artículos. <span href="#" class="fw-bold">Hazte usuario
                        premium</span> para acceder al contenido completo.
                </div>
            @endif

            <div class="row">
                @if ($noticias->count())
                    <ul class="app-article-list-row">
                        @foreach ($noticias as $noticia)
                            @php
                                $mostrar = false;
                                $slug = Str::slug($noticia->titulo) . '-' . $noticia->id;

                                if ($noticia->is_paid === 'no') {
                                    $mostrar = true;
                                    $ruta = route('getNoticia', ['slug' => $slug]);
                                } elseif (
                                    $noticia->is_paid === 'yes' &&
                                    Auth::check() &&
                                    Auth::user()->activo === 'si'
                                ) {
                                    $mostrar = true;
                                    $ruta = route('showNoticiasMatriculados', ['slug' => $slug]);
                                }
                            @endphp

                            @if ($mostrar)
                                <li class="app-article-list-row__item">
                                    <article>
                                        <a href="{{ $ruta }}" class="text-decoration-none text-dark" itemprop="url">
                                            <div class="row g-0 align-items-center">

                                                {{-- Columna IZQUIERDA (fecha y estado) --}}
                                                @php
                                                    $tieneVideo = false;
                                                    $contenido = $noticia->contenido_html;

                                                    if (
                                                        Str::contains($contenido, [
                                                            '<iframe',
                                                            'youtube.com',
                                                            'youtu.be',
                                                            '<video',
                                                        ])
                                                    ) {
                                                        $tieneVideo = true;
                                                    }
                                                @endphp

                                                <div class="col-md-2 d-none d-md-flex flex-column align-items-start ps-3">
                                                    <span>{{ ucfirst($noticia->estado ?? 'Artículo') }}</span>

                                                    @if ($noticia->pdf_url !== null)
                                                        <p class="mb-0">
                                                            PDF
                                                            <i class="bi bi-file-earmark-pdf" style="color: red"></i>
                                                        </p>
                                                    @endif

                                                    @if ($tieneVideo)
                                                        <p class="mb-0">
                                                            Video
                                                            <i class="bi bi-youtube" style="color: red"></i>
                                                        </p>
                                                    @endif

                                                    <time class="text-muted small"
                                                        datetime="{{ $noticia->created_at->toDateString() }}">
                                                        {{ $noticia->created_at->format('d M Y') }}
                                                    </time>
                                                </div>

                                                {{-- COLUMNA CENTRO (contenido) --}}
                                                <div class="col-md-8 col-12 px-3 py-3 py-md-0">
                                                    <h5 class="card-title mb-1">
                                                        {{ $noticia->titulo }}
                                                    </h5>
                                                    @if ($noticia->subtitulo)
                                                        <p class="card-text mb-0 small">
                                                            {{ $noticia->subtitulo }}
                                                        </p>
                                                    @endif
                                                </div>

                                                {{-- COLUMNA DERECHA (imagen) --}}
                                                @if ($noticia->imagen_url !== null)
                                                    <div class="col-md-2 d-none d-md-block">
                                                        <img src="{{ asset(env('IMAGE_PATH') . basename($noticia->imagen_url)) }}"
                                                            alt="{{ $noticia->titulo }}" class="img-fluid"
                                                            style="object-fit: cover; height: 100px; width: 100%;">
                                                    </div>
                                                @endif

                                                {{-- MOBILE: FECHA Y ESTADO AL FINAL --}}
                                                <div class="col-12 d-md-none mt-2 d-flex align-items-center">
                                                    <span>{{ ucfirst($noticia->estado ?? 'Artículo') }}</span>
                                                    @if ($noticia->pdf_url !== null)
                                                        <p class="mb-0 px-2">
                                                            PDF
                                                            <i class="bi bi-file-earmark-pdf" style="color: red"></i>
                                                        </p>
                                                    @endif

                                                    @if ($tieneVideo)
                                                        <p class="mb-0">
                                                            Video
                                                            <i class="bi bi-youtube" style="color: red"></i>
                                                        </p>
                                                    @endif
                                                    <time class="text-muted small d-block px-2"
                                                        datetime="{{ $noticia->created_at->toDateString() }}">
                                                        {{ $noticia->created_at->format('d M Y') }}
                                                    </time>
                                                </div>
                                            </div>
                                        </a>
                                    </article>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <div class="col-12 card p-5">
                        <p>No hay noticias disponibles.</p>
                    </div>
                @endif

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-2">
                    {{ $noticias->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </section>
@endsection
