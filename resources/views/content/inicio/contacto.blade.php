@extends('layouts/front')

@section('title', 'Contacto')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .contenido {
        /* font-family: 'Montserrat', sans-serif !important; */
        /* Aplica la fuente Montserrat con !important */
        font-size: 1rem;
        line-height: 1.3rem;
        color: #221E20;
        font-weight: 350;
    }

    .contenido p {
        color: #221E20 !important;
    }

    .contenido h1 {
        color: #221E20 !important;
    }

    .contenido h2 {
        color: #221E20 !important;
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
    @include('components.toast')

    <!-- Sección de encabezado -->
    {{-- <section class="container-fluid rounded overflow-hidden aos-init aos-animate" data-aos="fade-in"
        style="background-image: url('{{ asset(env('IMAGE_PATH') . basename($contacto->portada)) }}'); background-size: cover; background-position: center; height: 450px;">
        <div class="row m-0">
            <div id="carouselExampleControls" class="carousel slide px-0" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="container text-center text-white py-5">
                            <h6 class="fw-light">{{ $contacto->subtitulo ?? '' }}</h6>
                            <h2 class="fw-bold text-uppercase">{{ $contacto->titulo ?? '' }}</h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center bg-transparent">
                                    <li class="breadcrumb-item"><a href="{{ route('inicio') }}"
                                            class="text-white">Inicio</a></li>
                                    <li class="breadcrumb-item active text-white" aria-current="page">Contacto</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <section class="section hero__v7 position-relative py-0" id="home">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">

                <div class="carousel-item active">
                    <img class="d-block w-100" src="{{ asset(env('IMAGE_PATH') . basename($contacto->portada)) }}"
                        alt="Slide 1" style="max-height: 700px">

                    <div class="hero-caption-layer">
                        <div class="hero-caption-content container justify-content-center text-center">
                            <h1 class="hero-title mb-2 text-white">{{ $contacto->titulo }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de contacto -->
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-lg-6 ">
                    <div class="ql-editor contenido">
                        {!! $contacto->descripcion !!}
                    </div>
                </div>
                <div class="col-lg-6">
                    <form action="{{ route('mensajes.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_propiedad" value="0">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="numero" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="numero" name="numero">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="mensaje" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="mensaje" name="mensaje" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning w-100"
                            style="background-color: #5577b4; border-radius:4px">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
