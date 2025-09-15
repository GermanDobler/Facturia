@extends('layouts/front')
@section('title', 'Autoridades')

<style>
    .cargo-title {
        color: #5577B4;
        font-weight: 400;
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
                    <img class="d-block w-100" src="{{ asset('assets/img/autoridades.jpg') }}" alt="Slide 1"
                        style="max-height: 700px">

                    <div class="hero-caption-layer">
                        <div class="hero-caption-content container justify-content-center text-center">
                            <h1 class="hero-title mb-2 text-white">Autoridades</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section hero__v7 first-section position-relative pt-0">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-8 blog-content">

                    <div class="row">

                        <h3 class="mb-5">Comisión Directiva </h3>
                        @foreach ($porCargo as $cargo => $personas)
                            <h5 class="cargo-title">
                                {{ \Illuminate\Support\Str::of($cargo)->lower()->ucfirst() }}
                            </h5>
                            @foreach ($personas as $p)
                                <p>» {{ $p->nombre }} {{ $p->apellido }}</p>
                            @endforeach
                        @endforeach
                    </div>
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
