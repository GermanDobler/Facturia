@extends('layouts/front')

@section('title', 'Quienes Somos')

@section('layoutContent')
    @include('components.toast')

    <section class="container-fluid rounded overflow-hidden aos-init aos-animate" data-aos="fade-in">
        <div class="row m-0">
            <div id="carouselExampleControls" class="carousel slide px-0" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="{{ asset(env('IMAGE_PATH') . basename($somos->portada)) }}"
                            alt="Slide image">
                        <div class="carousel-caption d-none d-md-block">
                            <h2>{{ $somos->titulo ?? '' }}</h2>
                            <p>{{ $somos->subtitulo ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Formulario de somos -->
    <section class="bg-light py-5">
        <div class="container " style="max-width: 950px">
            <section class="card">
                <div class="card-body contenido">
                    {!! $somos->descripcion !!}
                </div>
            </section>
        </div>
    </section>
@endsection
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
