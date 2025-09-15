@extends('layouts/front')

@section('title', 'Inmobiliarias')

@php use Illuminate\Support\Str; @endphp
<style>
    .feature-1 {
        background: #fff;
        padding: 1.25rem 1rem;
        text-align: center;
        height: 100%;
        transition: transform .18s, box-shadow .18s, border-color .18s;
    }

    .feature-1:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(12, 20, 60, .06);
        border-color: #e3e8f0;
    }

    .icon-wrapper {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        margin: 0 auto .75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        /* background: #f4f6fb; */
        overflow: hidden;
    }

    .icon-wrapper.bg-primary {
        background: rgba(32, 42, 154, .12) !important;
    }

    .icon-wrapper img {
        max-width: 78%;
        max-height: 78%;
        object-fit: contain;
    }

    .feature-1-content h2 {
        font-size: 1.05rem;
        font-weight: 700;
        margin: .25rem 0 .35rem;
    }

    .feature-1-content p {
        color: #6b7280;
        font-size: .95rem;
        line-height: 1.4rem;
    }

    .social-wrap a {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        background: #5577B4;
        /* border: 1px solid #e6eaf2; */
        border-radius: 50%;
        margin: 0 .25rem;
        text-decoration: none;
    }

    .social-wrap a:hover {
        background: #000;
        border-color: #d8deea;
    }

    .title-with-line {
        color: #000;
        position: relative;
        padding-bottom: 20px;
        margin-bottom: 20px;
        font-size: 30px;
    }

    .color {
        color: #5577B4;
    }

    .feature-1-content h2 {
        font-size: 1.3rem;
        font-weight: 500;
        line-height: 1.2;
        color: #000;
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
    <section class="section hero__v7 first-section position-relative pt-0">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">

                <div class="carousel-item active">
                    <img class="d-block w-100" src="{{ asset('assets/img/inmobiliarias.jpg') }}" alt="Slide 1"
                        style="max-height: 700px">

                    <div class="hero-caption-layer">
                        <div class="hero-caption-content container justify-content-center text-center">
                            <h1 class="hero-title mb-2 text-white">Inmobiliarias</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container pt-5">

            <form method="GET" class="row g-2 mb-3 justify-content-center text-center">
                <div class="col-md-9">
                    <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control"
                        placeholder="Buscar por nombre, localidad o email">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-secondary w-100"
                        style="background-color: #5577B4; border-radius:4px">Buscar</button>
                </div>
            </form>

            @if (($porLocalidad ?? collect())->isEmpty())
                <div class="alert alert-warning">No se encontraron inmobiliarias.</div>
            @else
                {{-- Índice de localidades --}}
                <nav class="row g-2 mb-3 justify-content-center text-center">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($porLocalidad->keys() as $loc)
                            <a href="#loc-{{ Str::slug($loc) }}" style="background-color: #5577B4; border-radius:4px"
                                class="btn btn-sm btn-outline-primary">{{ $loc }}</a>
                        @endforeach
                    </div>
                </nav>

                {{-- Listado agrupado --}}
                @foreach ($porLocalidad as $localidad => $items)
                    <div class="row justify-content-center text-center pt-5">
                        <div class="col-lg-4">
                            <h2 id="loc-{{ Str::slug($localidad) }}" class="title-with-line text-center">
                                {{ $localidad }}</h2>
                        </div>
                    </div>
                    <div class="row g-5">
                        @foreach ($items as $it)
                            <div class="col-md-6 col-lg-4">
                                <div class="feature-1">
                                    <div class="icon-wrapper">
                                        @if ($it->logo_url)
                                            <img loading="lazy"
                                                src="{{ asset(env('INMOBILIARIA_PATH') . basename($it->logo_url)) }}"
                                                alt="Logo {{ $it->nombre }}" class="img-fluid">
                                        @else
                                            <i class="bi bi-buildings text-primary fs-3"></i>
                                        @endif
                                    </div>

                                    <div class="feature-1-content">
                                        <h2 class="mb-1">{{ $it->nombre }}</h2>

                                        <p class="mb-3">
                                            @if ($it->telefono)
                                                Tel. {{ $it->telefono }} <br>
                                            @endif
                                            @if ($it->email)
                                                {{ $it->email }} <br>
                                            @endif
                                            @if ($it->direccion || $it->localidad)
                                                {{ $it->direccion }}
                                                @if ($it->direccion && $it->localidad)
                                                    -
                                                @endif
                                                {{ $it->localidad }}
                                            @endif
                                        </p>

                                        <div class="social-wrap">
                                            @if ($it->url_web)
                                                <a href="{{ $it->url_web }}" target="_blank" rel="noopener"
                                                    title="Web">
                                                    <i class="bi bi-link-45deg"></i>
                                                </a>
                                            @endif
                                            @if ($it->email)
                                                <a href="mailto:{{ $it->email }}" target="_blank" title="Email">
                                                    <i class="bi bi-envelope-open"></i>
                                                </a>
                                            @endif
                                            @if ($it->whatsapp)
                                                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $it->whatsapp) }}"
                                                    target="_blank" rel="noopener" title="WhatsApp">
                                                    <i class="bi bi-whatsapp"></i>
                                                </a>
                                            @endif
                                            @if ($it->instagram)
                                                <a href="{{ $it->instagram }}" target="_blank" rel="noopener"
                                                    title="Instagram">
                                                    <i class="bi bi-instagram"></i>
                                                </a>
                                            @endif
                                            @if ($it->facebook)
                                                <a href="{{ $it->facebook }}" target="_blank" rel="noopener"
                                                    title="Facebook">
                                                    <i class="bi bi-facebook"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endif
        </div>
    </section>
@endsection
