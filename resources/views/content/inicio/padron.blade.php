@extends('layouts/front')

@section('title', 'Padrón de Matriculados')

<style>
    .avatar-inicial {
        width: 36px;
        height: 36px;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        background: #f1f3f5;
    }

    @media (max-width: 767.98px) {
        .tabla-desktop {
            display: none;
        }
    }

    @media (min-width: 768px) {
        .cards-mobile {
            display: none;
        }
    }

    .sin-linea {
        text-decoration: none !important;
        color: inherit;
    }

    tr td a {
        color: #5577B4;
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
                    <img class="d-block w-100" src="{{ asset('assets/img/martilleros.jpg') }}" alt="Slide 1"
                        style="max-height: 700px">

                    <div class="hero-caption-layer">
                        <div class="hero-caption-content container justify-content-center text-center">
                            <h1 class="hero-title mb-2 text-white">Padrón de Matriculados</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section hero__v7 first-section position-relative pt-0">
        {{-- <section class="py-5 bg-light border-bottom">
            <div class="container">
                <h1 class="mb-2">Padrón de Matriculados</h1>
                <p class="text-muted mb-0">
                    <strong>{{ number_format($activosCount) }}</strong> activos de
                    <strong>{{ number_format($total) }}</strong>
                    registrados.
                </p>
            </div>
        </section> --}}

        <section class="py-4">
            <div class="container">

                {{-- Filtros --}}
                <form method="GET" action="{{ route('padron.publico') }}" class="row g-2 align-items-end mb-3">
                    <div class="col-12 col-md-9">
                        <label class="form-label">Buscar por nombre, apellido, matrícula, email o CUIT</label>
                        <input type="text" name="q" value="{{ $q }}" class="form-control">
                    </div>
                    {{-- <div class="col-6 col-md-3">
                        <label class="form-label d-block">Solo activos</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="activos" name="activos"
                                {{ $activos ? 'checked' : '' }}>
                            <label class="form-check-label" for="activos">Mostrar solo activos</label>
                        </div>
                    </div> --}}
                    <div class="col-6 col-md-3 text-md-end">
                        <button class="btn w-100" style="background-color: #5577B4;border-radius:4px">Buscar</button>
                    </div>
                </form>
                {{-- Desktop: tabla --}}
                <div class="tabla-desktop table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                {{-- <th style="width:60px"></th> --}}
                                <th>Apellido y Nombre</th>
                                <th>Matrícula</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>CUIT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($matriculados as $m)
                                @php
                                    $inicial = mb_strtoupper(mb_substr(trim($m->apellido ?? '?'), 0, 1));
                                @endphp
                                <tr>
                                    {{-- <td><span class="avatar-inicial">{{ $inicial }}</span></td> --}}
                                    <td class="fw-semibold">{{ $m->apellido }}, {{ $m->nombre }}</td>
                                    <td>{{ $m->matricula }}</td>
                                    <td><a href="mailto:{{ $m->email }}">{{ $m->email }}</a></td>
                                    <td>{{ $m->telefono }}</td>
                                    <td>{{ $m->cuil }}</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">No se encontraron resultados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile: cards --}}
                <div class="cards-mobile">
                    <div class="row g-3">
                        @forelse($matriculados as $m)
                            @php $inicial = mb_strtoupper(mb_substr(trim($m->apellido ?? '?'), 0, 1)); @endphp
                            <div class="col-12">
                                <div class="card shadow-sm">
                                    <div class="card-body d-flex gap-3">
                                        <span class="avatar-inicial flex-shrink-0">{{ $inicial }}</span>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h5 class="card-title mb-1">{{ $m->apellido }}, {{ $m->nombre }}</h5>
                                                @if ($m->activo == 'si')
                                                    <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                @endif
                                            </div>
                                            <ul class="list-unstyled small mb-0">
                                                <li><strong>Matrícula:</strong> {{ $m->matricula }}</li>
                                                <li><strong>Email:</strong> <a class="sin-linea">

                                                        {{ $m->email }}
                                                    </a>
                                                </li>
                                                <li><strong>Tel.:</strong>{{ $m->telefono }}</li>
                                                <li><strong>CUIL:</strong> {{ $m->cuil }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-light border text-center">No se encontraron resultados.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection
