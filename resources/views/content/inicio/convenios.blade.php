@extends('layouts/front')
@section('title', 'Autoridades')

<style>
    .cargo-title {
        color: #5577B4;
        font-weight: 700;
    }

    .titulo {
        font-size: 1.5rem !important;
        font-weight: 500 !important;
        color: #5577B4 !important;
        margin-bottom: 0.5rem;
        font-family: inherit;
        line-height: 1.2;
    }

    aside {
        background: #f9f9f9;
        padding: 1rem;
        border-radius: 8px;
    }

    aside h5 {
        font-weight: 600;
        margin-bottom: 1rem;
    }

    aside ul {
        padding-left: 1rem;
    }

    aside ul li {
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .categories li,
    .sidelink li {
        position: relative;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px dotted #dee2e6;
        list-style: none;
    }

    .categories li a,
    .sidelink li a {
        color: #5476b3;
        text-decoration: none;
        font-weight: 500;
    }

    .sidebar-box h3 {
        font-size: 18px;
        margin-bottom: 15px;
    }

    .color {
        color: #5476b3
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    .h1,
    .h2,
    .h3,
    .h4,
    .h5 {
        font-family: "Muli", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        color: #5577B4 !important;
        font-weight: 400 !important;
    }

    p a {
        color: #5476b3;
        text-decoration: none;
        font-weight: 500;
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
                    <img class="d-block w-100" src="{{ asset('assets/img/convenios.jpg') }}" alt="Slide 1"
                        style="max-height: 700px">

                    <div class="hero-caption-layer">
                        <div class="hero-caption-content container justify-content-center text-center">
                            <h1 class="hero-title mb-2 text-white">Convenios</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section hero__v7 first-section position-relative pt-0">
        <div class="container">
            <div class="row">
                <div class="col-md-8 blog-content">
                    <h3 class="mb-3">Convenios</h3>
                    <p>Requisitos para participar en la realización de Informes Técnicos de Valuación, Tasaciones,
                        Remates&nbsp;y demás tareas que competen a nuestra profesión: </p>

                    <div>
                        <ul class="list-unstyled ul-arrow">
                            <li>El Colegiado no deberá tener deuda con el Colegio por ningún concepto </li>
                            <li>El Colegiado no debe tener sanciones firmes en el ámbito de la colegiación ni en sede penal
                                (aún cumplidas) </li>
                            <li>El Colegiado deberá tener 2 (dos) años de antigüedad&nbsp;en la Matrícula de Colegiación
                            </li>
                        </ul>
                    </div> <br>

                    <h5>Se hace saber que los sorteos son públicos y abiertos a todos los colegiados, integren la lista de
                        inscriptos o no. </h5><br>

                    @forelse($conveniosPorCarpeta as $carpeta => $files)
                        <div class="mb-4">
                            <h4 class="titulo mb-2">{{ $carpeta }}</h4>
                            <ul class="list-unstyled ul-arrow">
                                @foreach ($files as $a)
                                    @php
                                        // Ajustá estos campos a tu modelo Archivo:
                                        // - si guardás la ruta tipo "public/archivos_generales/Carpeta/archivo.pdf", usá Storage::url(...)
                                        $titulo =
                                            $a->nombre_publico ?? ($a->titulo ?? ($a->nombre_original ?? 'Archivo'));

                                        $baseName =
                                            $a->nombre_guardado ??
                                            ($a->ruta ?? ($a->path ?? ($a->nombre_original ?? '')));
                                        $ext = strtolower(pathinfo($baseName, PATHINFO_EXTENSION));
                                        $icon = match ($ext) {
                                            'pdf' => 'bi-filetype-pdf',
                                            'doc', 'docx' => 'bi-filetype-docx',
                                            'xls', 'xlsx' => 'bi-filetype-xlsx',
                                            'png', 'jpg', 'jpeg', 'webp' => 'bi-image',
                                            'mp3', 'wav', 'ogg' => 'bi-file-music',
                                            default => 'bi-file-earmark',
                                        };
                                    @endphp

                                    <li class="mb-2">
                                        <a href="{{ env('ARCHIVOS_GENERALES_PATH') . str_replace('storage/archivos_generales/', '', $a->ruta) }}"
                                            target="_blank" rel="noopener" style="text-decoration: none;">
                                            <i class="bi {{ $icon }}"></i> {{ $titulo }}
                                        </a>
                                        @if (!empty($a->descripcion))
                                            <div class="text-muted small">{{ $a->descripcion }}</div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <hr>
                    @empty
                        <div class="alert alert-info">No hay convenios para mostrar.</div>
                    @endforelse
                </div>

                <div class="col-md-4 sidebar">

                    <div class="sidebar-box">
                        <div class="categories">
                            <h3 class="color">Links </h3>
                            <li><a href="{{ route('padron.publico') }}">Padron Colegiados </a></li>
                            <li><a href="{{ route('inmobiliarias.lista') }}">Inmobiliarias </a></li>
                            <li><a href="{{ route('denuncia_inicio') }}">Denuncias </a></li>
                            <li><a href="{{ route('noticias_inicio') }}">Noticias </a></li>
                        </div>
                    </div>
                    <div class="sidebar-box">
                        <img src="{{ asset('assets/img/martillero.jpg') }}" alt=" "
                            class="img-fluid mb-4 w-50 rounded-circle">
                        <h3 class="text-black">Martillero</h3>
                        <p>Es la persona legalmente facultada para realizar la operación de remate, es un profesional
                            autónomo, que puede adquirir la calidad de comerciante y también cumplir la función de auxiliar
                            o colaborador del empresario mercantil. </p>
                        <h3 class="text-black">Corredor</h3>
                        <p>Es la persona que realiza actos de corretaje es decir de mediación entre la oferta y lademanda,
                            buscando un interesado para la operación que desea realizar el comerciante. </p>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
