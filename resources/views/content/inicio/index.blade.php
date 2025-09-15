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
        /* border-bottom: 1px solid #d5d5d5; */
        padding-bottom: 16px;
        margin-bottom: 16px;
    }
</style>

<style>
    /* Capa de overlay ocupando todo el slide */
    /* Capa de overlay ocupando todo el slide */
    .hero-caption-layer {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        /* centra vertical */
        justify-content: center;
        /* centra horizontal */
        pointer-events: none;
        /* deja pasar clicks a controles */
        background: linear-gradient(0deg,
                rgba(0, 0, 0, 0.65) 0%,
                rgba(0, 0, 0, 0.25) 40%,
                rgba(0, 0, 0, 0.0) 70%);
    }

    /* Contenido clickeable */
    .hero-caption-content {
        pointer-events: auto;
        color: #fff;
        max-width: 1100px;
        text-align: center;
        /* centrado horizontal del texto */
    }

    /* Posición del bloque de texto: abajo en mobile, centro en desktop */
    @media (max-width: 767.98px) {
        .hero-caption-content {
            padding: 18px;
        }
    }

    @media (min-width: 768px) {
        .hero-caption-content {
            padding: 40px 56px;
        }
    }

    .hero-title {
        font-weight: 900;
        line-height: 1.1;
        font-size: 2.5rem;
    }

    .hero-subtitle {
        font-size: 1.3rem;
        line-height: 1.6;
        opacity: .95;
        max-width: 60ch;
        margin-left: auto;
        /* centra el bloque */
        margin-right: auto;
        /* centra el bloque */
        text-align: center;
        /* centra el texto dentro */
    }

    .hero-btn {
        box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
        border-radius: 4px !important;
        padding: 14px 20px;
        font-size: 12px;
        text-transform: uppercase;
    }
</style>

@extends('layouts/front')
@section('layoutContent')
    <!-- ======= Hero =======-->
    <section class="section hero__v7 position-relative pt-0" id="home">
        <div class="container-fluid p-0">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">

                    @foreach ($slider as $index => $slide)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img class="d-block w-100" src="{{ asset(env('IMAGE_PATH') . basename($slide->imagen_url)) }}"
                                alt="Slide {{ $index + 1 }}" style="max-height: 650px">

                            @if ($slide->titulo || $slide->subtitulo || $slide->boton_titulo)
                                <div class="hero-caption-layer">
                                    <div class="hero-caption-content container justify-content-center text-center">
                                        @if ($slide->titulo)
                                            <h1 class="hero-title mb-2 text-white">{{ $slide->titulo }}</h1>
                                        @endif

                                        @if ($slide->subtitulo)
                                            <p class="hero-subtitle mb-3 mb-md-4">{{ $slide->subtitulo }}</p>
                                        @endif

                                        @if ($slide->boton_titulo && $slide->boton_url)
                                            <a href="{{ $slide->boton_url }}" class="btn border-0 px-4 py-2 hero-btn"
                                                style="background-color: {{ $slide->boton_color ?? '#5577B4' }};"
                                                target="{{ $slide->boton_target ?? '_self' }}">
                                                {{ $slide->boton_titulo }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach

                </div>

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
    <section class="section clients__v2 py-5 bg-light position-relative">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo con sombra y animación -->
                <div class="col-lg-6 mb-4 mb-lg-0 text-center">
                    <img src="{{ asset('assets/img/logomartilleros.png') }}" alt="Colegio de Martilleros" class="img-fluid"
                        style="max-height: 300px;">
                </div>

                <!-- Texto principal -->
                <div class="col-lg-6">
                    <span class="badge bg-primary text-uppercase mb-2 px-3 py-2">
                        Colegio Profesional de Martilleros y corredores Públicos
                    </span>
                    <h2 class="title-with-line mb-3 fw-bold">
                        IV Circunscripción Judicial Río Negro
                    </h2>
                    <p class="lead text-muted">
                        El <strong>11 de octubre</strong> se celebra el
                        "Día del Martillero y Corredor Público", fecha instaurada en 1943 con la creación de la
                        <strong>Federación Argentina de Entidades de Martilleros</strong>.
                        Cada año recordamos este hito que dio inicio a nuestra institución.
                    </p>
                </div>
            </div>

            <!-- Números destacados con íconos -->
            <div class="row text-center mt-5">
                <div class="col-md-6 mb-4">
                    <div class="p-4 bg-white shadow-sm rounded-4 h-100 hover-shadow transition">
                        <i class="bi bi-people-fill fs-1 text-primary mb-3"></i>
                        <h3 class="fw-bold counter" data-count="79">{{ $matriculadosActivosCount }}</h3>
                        <p><a href="{{ route('padron.publico') }}" class="text-decoration-none text-dark">Matriculados
                                activos</a>
                        </p>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="p-4 bg-white shadow-sm rounded-4 h-100 hover-shadow transition">
                        <i class="bi bi-building fs-1 text-success mb-3"></i>
                        <h3 class="fw-bold counter" data-count="33">
                            {{ $inmobiliariasRegistradasCount }}
                        </h3>
                        <p><a href="{{ route('inmobiliarias.lista') }}"
                                class="text-decoration-none text-dark">Inmobiliarias registradas</a>
                        </p>
                    </div>
                </div>

                {{-- <div class="col-lg-4 col-md-12 mb-4">
                    <div class="p-4 bg-white shadow-sm rounded-4 h-100 hover-shadow transition">
                        <i class="bi bi-calendar-event fs-1 text-warning mb-3"></i>
                        <h3 class="fw-bold">1943</h3>
                        <p>Año de fundación de la Federación</p>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>

    <section class="section about__v2 py-5" id="about">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">

                <div class="carousel-item active">
                    <img class="d-block w-100" src="{{ asset('assets/img/franquicia.jpg') }}" alt="Slide 1"
                        style="max-height: 700px">

                    <div class="hero-caption-layer">
                        <div class="hero-caption-content container justify-content-center text-center">
                            <h1 class="hero-title mb-2 text-white">Las Profesiones NO se franquician</h1>
                            <p class="hero-subtitle mb-3 mb-md-4">La profesión de Martillero y Corredor Público es
                                INDIVIDUAL e INDELEGABLE. Las franquicias inmobiliarias están fomentando que cientos de
                                personas que no tienen Matrícula habilitante y están fuera de la regulación profesional
                                realicen esta actividad, por lo tanto, están fuera de la ley.</p>
                            <a href="{{ route('denuncia_inicio') }}" class="btn border-0 px-4 py-2 hero-btn"
                                style="background-color: #5577b4;" target="'_self">
                                DENUNCIEMOS
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- noticias -->
    <div class="section recentblog__v3" id="blog">
        <div class="container">
            <div class="row" data-aos="fade-up" data-aos-delay="0">
                <div class="col-lg-5 mx-auto text-center">
                    <h2 class="fw-bold mb-4">Noticias</h2>
                </div>
            </div>
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
            </div>
            <div class="row justify-content-center">
                <div class="col-md-5 mx-auto text-center" data-aos="fade-up" data-aos-delay="300"><a class="btn"
                        style="border-radius: 4px; background-color: #5577b4; color: white;"
                        href="{{ route('noticias_inicio') }}">Ver todas</a></div>
            </div>
        </div>
    </div>
    <!-- End noticias -->


    {{-- <!-- ======= Partners =======-->
        <div class="section clients__v2 py-5">
          <div class="container">
            <h2 class="h6 text-center fw-normal" data-aos="fade-up" data-aos-delay="0">Recommended by these great companies.</h2>
            <div class="pb-0 position-relative gradient-x overflow-hidden" data-aos="fade-up" data-aos-delay="0">
              <div class="d-flex align-items-center logo-wrapper w-100">
                <div class="logo-item d-flex align-items-center justify-content-center overflow-hidden position-relative"><img class="mw-100 js-img-to-inline-svg" src="assets/inicio/images/logo/logo-air-bnb__black.svg" alt=""></div>
                <div class="logo-item d-flex align-items-center justify-content-center overflow-hidden position-relative"><img class="mw-100 js-img-to-inline-svg" src="assets/inicio/images/logo/logo-netflix__black.svg" alt=""></div>
                <div class="logo-item d-flex align-items-center justify-content-center overflow-hidden position-relative"><img class="mw-100 js-img-to-inline-svg" src="assets/inicio/images/logo/logo-apple__black.svg" alt=""></div>
                <div class="logo-item d-flex align-items-center justify-content-center overflow-hidden position-relative"><img class="mw-100 js-img-to-inline-svg" src="assets/inicio/images/logo/logo-ibm__black.svg" alt=""></div>
                <div class="logo-item d-flex align-items-center justify-content-center overflow-hidden position-relative"><img class="mw-100 js-img-to-inline-svg" src="assets/inicio/images/logo/logo-american-apparel__black.svg" alt=""></div>
                <div class="logo-item d-flex align-items-center justify-content-center overflow-hidden position-relative"><img class="mw-100 js-img-to-inline-svg" src="assets/inicio/images/logo/logo-google__black.svg" alt=""></div>
                <div class="logo-item d-flex align-items-center justify-content-center overflow-hidden position-relative"><img class="mw-100 js-img-to-inline-svg" src="assets/inicio/images/logo/logo-ebay__black.svg" alt=""></div>
                <div class="logo-item d-flex align-items-center justify-content-center overflow-hidden position-relative"><img class="mw-100 js-img-to-inline-svg" src="assets/inicio/images/logo/logo-microsoft__black.svg" alt=""></div>
              </div>
            </div>
          </div>
        </div>
        <!-- End Partners--> --}}

    <!-- ======= Courses =======-->
    {{-- <div class="section products__v2" id="courses">
        <div class="container">
            <div class="row align-items-end justify-content-end mb-5" data-aos="fade-up">
                <div class="col-md-6">
                    <h2 class="fw-bold fs-5">Cursos</h2>
                    <p class="mb-0">Lorem ipsum dolor sit amet.</p>
                </div>
                <div class="col-md-6 text-end"><a class="d-inline-flex gap-2 align-items-center" href="#"><span>Ver
                            todos los cursos</span><span class="icon-arrow"><i class="bi bi-arrow-up-short"> </i></span></a>
                </div>
            </div>
            <div class="row" data-aos="fade-up" data-aos-delay="100">
                <div class="col-md-12 position-relative">
                    <div class="custom-nav w-100">
                        <a href="#"
                            class="custom-prev d-flex align-items-center justify-content-center rounded-circle"><i
                                class="bi bi-chevron-left"></i></a>
                        <a href="#"
                            class="custom-next d-flex align-items-center justify-content-center rounded-circle"><i
                                class="bi bi-chevron-right"></i></a>
                    </div>
                    <div class="swiper carousel">
                        <div class="swiper-pagination"></div>
                        <div class="swiper-wrapper">

                            <div class="swiper-slide">
                                <div class="course-item"><a href="page-course-detail.html">
                                        <img class="img-fluid rounded-2 mb-2"
                                            src="assets/inicio/images/business/business-img-11-min.jpg"
                                            alt="Image placeholder"></a>
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex justify-content-between gap-2 align-items-center"><span
                                                class="icon"><i class="bi bi-layers"></i></span><span>5 módulos</span>
                                        </div>
                                        <div class="d-flex justify-content-between gap-2 align-items-center"><span
                                                class="icon"><i class="bi bi-clock"></i></span><span>10 horas</span></div>
                                    </div>
                                    <div class="mb-3">
                                        <h3 class="fs-6 fw-semibold">Rehabilitación Funcional en Lesiones Deportivas</h3>
                                    </div>
                                    <div class="d-flex flex-column gap-2 align-items-start mb-2 reviews pb-1">
                                        <div class="d-flex gap-2"><span>4.8</span><span><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-half"></i></span><span>(450)</span></div>
                                        <div class="d-flex instructor gap-2 align-items-center mb-4">
                                            <div class="author-img"><img class="img-fluid rounded-circle"
                                                    src="assets/inicio/images/person-sq-6-min.jpg"
                                                    alt="Image Placeholder"></div>
                                            <div>
                                                <h3 class="fs-6">por Lic. María Fernández</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="swiper-slide">
                                <div class="course-item"><a href="page-course-detail.html">
                                        <img class="img-fluid rounded-2 mb-2"
                                            src="assets/inicio/images/business/business-img-12-min.jpg"
                                            alt="Image placeholder"></a>
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex justify-content-between gap-2 align-items-center"><span
                                                class="icon"><i class="bi bi-layers"></i></span><span>4 módulos</span>
                                        </div>
                                        <div class="d-flex justify-content-between gap-2 align-items-center"><span
                                                class="icon"><i class="bi bi-clock"></i></span><span>8 horas</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <h3 class="fs-6 fw-semibold">Evaluación Biomecánica del Movimiento Humano</h3>
                                    </div>
                                    <div class="d-flex flex-column gap-2 align-items-start mb-2 reviews pb-1">
                                        <div class="d-flex gap-2"><span>4.6</span><span><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i><i
                                                    class="bi bi-star"></i></span><span>(390)</span></div>
                                        <div class="d-flex instructor gap-2 align-items-center mb-4">
                                            <div class="author-img"><img class="img-fluid rounded-circle"
                                                    src="assets/inicio/images/person-sq-6-min.jpg"
                                                    alt="Image Placeholder"></div>
                                            <div>
                                                <h3 class="fs-6">por Dr. Pablo Ruiz</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="swiper-slide">
                                <div class="course-item"><a href="page-course-detail.html">
                                        <img class="img-fluid rounded-2 mb-2"
                                            src="assets/inicio/images/business/business-img-11-min.jpg"
                                            alt="Image placeholder"></a>
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex justify-content-between gap-2 align-items-center"><span
                                                class="icon"><i class="bi bi-layers"></i></span><span>6 módulos</span>
                                        </div>
                                        <div class="d-flex justify-content-between gap-2 align-items-center"><span
                                                class="icon"><i class="bi bi-clock"></i></span><span>14 horas</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <h3 class="fs-6 fw-semibold">Kinesiología Respiratoria en Pacientes Crónicos</h3>
                                    </div>
                                    <div class="d-flex flex-column gap-2 align-items-start mb-2 reviews pb-1">
                                        <div class="d-flex gap-2"><span>4.9</span><span><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i></span><span>(670)</span></div>
                                        <div class="d-flex instructor gap-2 align-items-center mb-4">
                                            <div class="author-img"><img class="img-fluid rounded-circle"
                                                    src="assets/inicio/images/person-sq-6-min.jpg"
                                                    alt="Image Placeholder"></div>
                                            <div>
                                                <h3 class="fs-6">por Lic. Andrea López</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Puedes seguir el mismo patrón para los cursos restantes -->
                            <div class="swiper-slide">
                                <div class="course-item"><a href="page-course-detail.html">
                                        <img class="img-fluid rounded-2 mb-2"
                                            src="assets/inicio/images/business/business-img-12-min.jpg"
                                            alt="Curso de Kinesiología">
                                    </a>
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex justify-content-between gap-2 align-items-center">
                                            <span class="icon"><i class="bi bi-layers"></i></span><span>8 módulos</span>
                                        </div>
                                        <div class="d-flex justify-content-between gap-2 align-items-center">
                                            <span class="icon"><i class="bi bi-clock"></i></span><span>14 horas</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <h3 class="fs-6 fw-semibold">Terapias Manuales para el Tratamiento del Dolor Lumbar
                                        </h3>
                                    </div>
                                    <div class="d-flex flex-column gap-2 align-items-start mb-2 reviews pb-1">
                                        <div class="d-flex gap-2">
                                            <span>4.9</span>
                                            <span><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i></span>
                                            <span>(987)</span>
                                        </div>
                                        <div class="d-flex instructor gap-2 align-items-center mb-4">
                                            <div class="author-img">
                                                <img class="img-fluid rounded-circle"
                                                    src="assets/inicio/images/person-sq-6-min.jpg" alt="Instructor">
                                            </div>
                                            <div>
                                                <h3 class="fs-6">por Lic. Fernando Ruiz</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="swiper-slide">
                                <div class="course-item"><a href="page-course-detail.html">
                                        <img class="img-fluid rounded-2 mb-2"
                                            src="assets/inicio/images/business/business-img-12-min.jpg"
                                            alt="Curso de Kinesiología">
                                    </a>
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex justify-content-between gap-2 align-items-center">
                                            <span class="icon"><i class="bi bi-layers"></i></span><span>8 módulos</span>
                                        </div>
                                        <div class="d-flex justify-content-between gap-2 align-items-center">
                                            <span class="icon"><i class="bi bi-clock"></i></span><span>14 horas</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <h3 class="fs-6 fw-semibold">Terapias Manuales para el Tratamiento del Dolor Lumbar
                                        </h3>
                                    </div>
                                    <div class="d-flex flex-column gap-2 align-items-start mb-2 reviews pb-1">
                                        <div class="d-flex gap-2">
                                            <span>4.9</span>
                                            <span><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                                    class="bi bi-star-fill"></i></span>
                                            <span>(987)</span>
                                        </div>
                                        <div class="d-flex instructor gap-2 align-items-center mb-4">
                                            <div class="author-img">
                                                <img class="img-fluid rounded-circle"
                                                    src="assets/inicio/images/person-sq-6-min.jpg" alt="Instructor">
                                            </div>
                                            <div>
                                                <h3 class="fs-6">por Lic. Fernando Ruiz</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div> --}}
    <!-- End Courses-->

    <!-- ======= Features =======-->
    {{-- <div class="section features__v10" id="features">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-12" data-aos="fade-up" data-aos-delay="0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="fw-bold fs-5">Browse Courses By Category</h2>
                        <div><a class="btn btn-primary" href="#">Browse All</a></div>
                    </div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="0"><a
                        class="d-block active feature" href="#">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 682.667 682.667"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <defs>
                                        <clippath id="a" clippathunits="userSpaceOnUse">
                                            <path d="M0 512h512V0H0Z" fill="currentColor" opacity="1"
                                                data-original="currentColor"></path>
                                        </clippath>
                                    </defs>
                                    <g clip-path="url(#a)" transform="matrix(1.33333 0 0 -1.33333 0 682.667)">
                                        <path
                                            d="M0 0h-350.365c-14.36 0-26 11.641-26 26v20a4 4 0 0 0 4 4h489a4 4 0 0 0 4-4V26c0-14.359-11.641-26-26-26H32.371"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(383.865 31)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path
                                            d="m0 0 .008-203.495c0-11.04-8.95-20-20-20l-389.99-.01c-11.05 0-20 8.95-20 20l-.01 259.99c0 11.04 8.95 20 20 20l214.865.01"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(470.992 304.505)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0h40.195c11.05 0 20-8.95 20-20l.001-24.681"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(410.795 381)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0h-56.642"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(371.586 381)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="m0 0-.007 158.392 200.89.009"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(71.007 192.599)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0h22.8l.01-239.99-369.991-.01-.002 49.227"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(418.19 351)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0h-68c-6.627 0-12 5.373-12 12v12h92V12C12 5.373 6.627 0 0 0Z"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(290 57)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path
                                            d="M0 0c0 18.414-14.965 33.341-33.424 33.341-18.46 0-33.424-14.927-33.424-33.341s14.964-33.342 33.424-33.342C-14.965-33.342 0-18.414 0 0Zm-14.155-62.953c-.557-3.948-1.887-9.603-3.075-14.198a9.718 9.718 0 0 0-9.407-7.294h-13.575a9.716 9.716 0 0 0-9.406 7.294c-1.189 4.595-2.519 10.25-3.075 14.198-.415 2.942-2.182 5.517-4.757 7.001l-12.543 7.223c-2.571 1.482-5.683 1.722-8.435.612-3.713-1.497-9.311-3.182-13.905-4.456a9.715 9.715 0 0 0-11.011 4.488l-6.778 11.712a9.692 9.692 0 0 0 1.617 11.783c3.399 3.327 7.65 7.31 10.801 9.766 2.342 1.825 3.691 4.639 3.691 7.609v14.43c0 2.969-1.349 5.784-3.691 7.608-3.151 2.456-7.402 6.439-10.801 9.766a9.692 9.692 0 0 0-1.617 11.783l6.778 11.712a9.715 9.715 0 0 0 11.011 4.488c4.594-1.274 10.192-2.958 13.905-4.456 2.752-1.11 5.864-.869 8.435.612l12.543 7.224c2.575 1.483 4.342 4.058 4.757 7 .556 3.948 1.886 9.604 3.075 14.198a9.716 9.716 0 0 0 9.406 7.294h13.575a9.718 9.718 0 0 0 9.407-7.294c1.188-4.594 2.518-10.25 3.075-14.198a9.646 9.646 0 0 1 4.757-7l12.543-7.224c2.572-1.481 5.682-1.722 8.434-.612 3.714 1.498 9.312 3.182 13.905 4.456a9.715 9.715 0 0 0 11.011-4.488l6.778-11.712a9.692 9.692 0 0 0-1.617-11.783c-3.398-3.327-7.648-7.31-10.8-9.766-2.342-1.824-3.692-4.639-3.692-7.608v-14.43c0-2.97 1.35-5.784 3.692-7.609 3.152-2.456 7.402-6.439 10.8-9.766a9.692 9.692 0 0 0 1.617-11.783l-6.778-11.712a9.715 9.715 0 0 0-11.011-4.488c-4.593 1.274-10.191 2.959-13.905 4.456-2.752 1.11-5.862.87-8.434-.612l-12.543-7.223a9.65 9.65 0 0 1-4.757-7.001z"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(376.69 396.556)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path
                                            d="M0 0c0 18.414-14.965 33.341-33.424 33.341-18.46 0-33.424-14.927-33.424-33.341s14.964-33.342 33.424-33.342C-14.965-33.342 0-18.414 0 0Zm-14.155-62.953c-.557-3.948-1.887-9.603-3.075-14.198a9.718 9.718 0 0 0-9.407-7.294h-13.575a9.716 9.716 0 0 0-9.406 7.294c-1.189 4.595-2.519 10.25-3.075 14.198-.415 2.942-2.182 5.517-4.757 7.001l-12.543 7.223c-2.571 1.482-5.683 1.722-8.435.612-3.714-1.497-9.311-3.182-13.905-4.456a9.715 9.715 0 0 0-11.011 4.488l-6.778 11.712a9.692 9.692 0 0 0 1.617 11.783c3.399 3.327 7.649 7.31 10.801 9.766 2.342 1.825 3.691 4.639 3.691 7.609v14.43c0 2.969-1.349 5.784-3.691 7.608-3.152 2.456-7.402 6.439-10.801 9.766a9.692 9.692 0 0 0-1.617 11.783l6.778 11.712a9.715 9.715 0 0 0 11.011 4.488c4.594-1.274 10.191-2.959 13.905-4.456 2.752-1.11 5.864-.869 8.435.612l12.543 7.224c2.575 1.483 4.342 4.058 4.757 7 .556 3.948 1.886 9.603 3.075 14.198a9.716 9.716 0 0 0 9.406 7.294h13.575a9.718 9.718 0 0 0 9.407-7.294c1.188-4.595 2.518-10.25 3.075-14.198a9.646 9.646 0 0 1 4.757-7l12.543-7.224c2.571-1.481 5.682-1.722 8.434-.612 3.714 1.497 9.312 3.182 13.905 4.456a9.715 9.715 0 0 0 11.011-4.488l6.778-11.712a9.692 9.692 0 0 0-1.617-11.783c-3.398-3.327-7.648-7.31-10.801-9.766-2.341-1.824-3.691-4.639-3.691-7.608v-14.43c0-2.97 1.35-5.784 3.691-7.609 3.153-2.456 7.403-6.439 10.801-9.766a9.692 9.692 0 0 0 1.617-11.783l-6.778-11.712a9.715 9.715 0 0 0-11.011-4.488c-4.593 1.274-10.191 2.959-13.905 4.456-2.752 1.11-5.863.87-8.434-.612l-12.543-7.223a9.65 9.65 0 0 1-4.757-7.001z"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(259.078 266.556)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <h3 class="h6 fw-bold mb-3">Technology &amp; IT</h3>
                        <p class="mb-0">Nn eque unde quia tempora quas sit eos nulla repellendus ipsam corrupti aliquid
                            et quo.</p>
                    </a></div>
                <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="100"> <a
                        class="d-block feature" href="#">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 24 24"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <path
                                        d="m14 9.09 8.81 1.75c.71.15 1.19.75 1.19 1.46v10.2c0 .83-.67 1.5-1.5 1.5h-9c.28 0 .5-.22.5-.5V23h8.5c.27 0 .5-.22.5-.5V12.3c0-.23-.16-.44-.39-.49L14 10.11z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="M19.5 14c.28 0 .5.22.5.5s-.22.5-.5.5h-2c-.28 0-.5-.22-.5-.5s.22-.5.5-.5zM19.5 17c.28 0 .5.22.5.5s-.22.5-.5.5h-2c-.28 0-.5-.22-.5-.5s.22-.5.5-.5zM19.5 20c.28 0 .5.22.5.5s-.22.5-.5.5h-2c-.28 0-.5-.22-.5-.5s.22-.5.5-.5zM14 23.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5v-14c0-.15.07-.29.18-.39.12-.09.27-.13.42-.1l.4.08V23z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="M13 23v.5c0 .28.22.5.5.5h-4c.28 0 .5-.22.5-.5V23zM10.5 5c.28 0 .5.22.5.5s-.22.5-.5.5h-2c-.28 0-.5-.22-.5-.5s.22-.5.5-.5zM11 8.5c0 .28-.22.5-.5.5h-2c-.28 0-.5-.22-.5-.5s.22-.5.5-.5h2c.28 0 .5.22.5.5zM10.5 11c.28 0 .5.22.5.5s-.22.5-.5.5h-2c-.28 0-.5-.22-.5-.5s.22-.5.5-.5zM10.5 14c.28 0 .5.22.5.5s-.22.5-.5.5h-2c-.28 0-.5-.22-.5-.5s.22-.5.5-.5zM6 14.5c0 .28-.22.5-.5.5h-2c-.28 0-.5-.22-.5-.5s.22-.5.5-.5h2c.28 0 .5.22.5.5zM5.5 5c.28 0 .5.22.5.5s-.22.5-.5.5h-2c-.28 0-.5-.22-.5-.5s.22-.5.5-.5zM5.5 8c.28 0 .5.22.5.5s-.22.5-.5.5h-2c-.28 0-.5-.22-.5-.5s.22-.5.5-.5zM5.5 11c.28 0 .5.22.5.5s-.22.5-.5.5h-2c-.28 0-.5-.22-.5-.5s.22-.5.5-.5zM9 18.5c0-.28-.23-.5-.5-.5h-3c-.28 0-.5.22-.5.5V23H4v-4.5c0-.83.67-1.5 1.5-1.5h3c.83 0 1.5.67 1.5 1.5V23H9z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path d="M5 23h5v.5c0 .28-.22.5-.5.5h-5c-.28 0-.5-.22-.5-.5V23z" fill="currentColor"
                                        opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="m1.75.2 10.99 1.67c.73.12 1.26.74 1.26 1.48v5.74l-.4-.08c-.15-.03-.3.01-.42.1-.11.1-.18.24-.18.39V3.35c0-.25-.18-.46-.42-.5L1.59 1.19c-.03-.01-.06-.01-.09-.01-.12 0-.23.04-.32.12-.12.1-.18.23-.18.38V22.5c0 .28.23.5.5.5H4v.5c0 .28.22.5.5.5h-3C.67 24 0 23.33 0 22.5V1.68C0 1.24.19.82.53.54.87.25 1.31.13 1.75.2z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                        <h3 class="h6 fw-bold mb-3">Business &amp; Management</h3>
                        <p class="mb-0">Nn eque unde quia tempora quas sit eos nulla repellendus ipsam corrupti aliquid
                            et quo.</p>
                    </a></div>
                <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="200"> <a
                        class="d-block feature" href="#">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 512.01 512.01"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <path
                                        d="M447.183 66.584h-6.075c11.58-18.853 7.304-44.164-11.281-57.953-20.025-14.856-48.482-10.074-62.457 10.81l-31.546 47.144H64.827c-22.275 0-40.397 18.122-40.397 40.397v258.274c0 22.275 18.122 40.397 40.397 40.397h156.914l-11.856 49.797h-25.589c-16.677 0-30.245 13.567-30.245 30.244v6.315h-31.545c-5.523 0-10 4.478-10 10s4.477 10 10 10h266.999c5.523 0 10-4.478 10-10s-4.477-10-10-10H357.96v-6.315c0-16.677-13.568-30.244-30.245-30.244h-25.589l-11.856-49.797h156.914c22.275 0 40.397-18.122 40.397-40.397V106.981c0-22.275-18.122-40.397-40.398-40.397zM303.901 242.383c-15.635 10.945-44.926-3.932-72.898 10.994-6.825-35.455-2.522-68.8 33.003-76.16 4.591-.957 9.534-1.39 13.742-1.306l36.176 26.841c5.249 15.268 3.279 30.32-10.023 39.631zm10.078-107.188 28.307 21.002-21.769 26.541-25.628-19.015 19.09-28.528zm70.014-104.631c7.574-11.321 23.021-13.955 33.917-5.87 10.9 8.086 12.852 23.632 4.215 34.163l-67.142 81.859-29.87-22.162zm-46.034 455.13v6.315H174.051v-6.315c0-5.648 4.596-10.244 10.245-10.244h143.418c5.649 0 10.245 4.596 10.245 10.244zm-56.393-30.244h-51.122l11.856-49.797h27.41zm186.015-90.194c0 11.247-9.15 20.397-20.397 20.397H64.828c-11.247 0-20.397-9.15-20.397-20.397v-46.83h28.576c5.523 0 10-4.478 10-10s-4.477-10-10-10H44.429V106.981c0-11.247 9.15-20.397 20.397-20.397h257.615l-46.382 69.314c-19.597.177-39.162 7.004-51.97 21.956-17.429 20.345-20.491 52.313-9.101 95.015 1.947 7.302 11.058 9.918 16.571 4.652 25.857-24.707 58.061 2.243 86.9-21.099 17.604-14.25 21.77-36.904 15.132-58.088l91.658-111.75h21.933c11.247 0 20.397 9.15 20.397 20.397v191.444H153.005c-5.523 0-10 4.478-10 10s4.477 10 10 10h314.576z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="M111.075 298.619a9.989 9.989 0 0 0-8.04 9.811c0 6.255 5.719 11.045 11.95 9.8a10 10 0 0 0 7.85-11.76c-1.113-5.47-6.419-8.926-11.76-7.851z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                        <h3 class="h6 fw-bold mb-3">Design &amp; Creativity</h3>
                        <p class="mb-0">Nn eque unde quia tempora quas sit eos nulla repellendus ipsam corrupti aliquid
                            et quo.</p>
                    </a></div>
                <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="300"> <a
                        class="d-block feature" href="#">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 32 32"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <path
                                        d="M23.996 22.506a.5.5 0 0 1-.399-.801A9.418 9.418 0 0 0 25.5 16c0-.652-.067-1.305-.199-1.94a.5.5 0 1 1 .979-.203c.146.702.22 1.423.22 2.143 0 2.295-.728 4.477-2.104 6.307a.5.5 0 0 1-.4.199zM6.216 18.584a.502.502 0 0 1-.489-.397A10.704 10.704 0 0 1 5.5 16c0-2.285.721-4.458 2.086-6.282a.5.5 0 1 1 .801.599A9.412 9.412 0 0 0 6.5 16c0 .665.069 1.332.206 1.981a.5.5 0 0 1-.49.603zM17.5 21h-3a.5.5 0 0 1-.5-.5V18h-2.5a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5H14v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V14h2.5a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5H18v2.5a.5.5 0 0 1-.5.5zM15 20h2v-2.5a.5.5 0 0 1 .5-.5H20v-2h-2.5a.5.5 0 0 1-.5-.5V12h-2v2.5a.5.5 0 0 1-.5.5H12v2h2.5a.5.5 0 0 1 .5.5zM18.141 30H12.9a6.563 6.563 0 0 1-2.836-.648L6.561 27.65a6.52 6.52 0 0 0-2.84-.65H1.5a.5.5 0 0 1 0-1h2.221c1.136 0 2.238.252 3.275.75l3.502 1.701A5.576 5.576 0 0 0 12.9 29h5.141l4.367-1.802c0-.042.059-.429-.133-.758-.293-.504-.97-.52-1.483-.445-.745.115-1.361.322-1.904.505l-.206.069a.5.5 0 0 1-.315-.949l.202-.067c.582-.196 1.241-.418 2.075-.546 2.062-.3 2.577.988 2.688 1.389.183.661.031 1.488-.54 1.727l-4.461 1.841a.534.534 0 0 1-.19.036zM11.635 22.877a.501.501 0 0 1-.454-.289c-.98-2.109-3.526-2.411-5.419-1.626A.506.506 0 0 1 5.57 21H1.5a.5.5 0 0 1 0-1h3.973c2.707-1.075 5.536-.152 6.616 2.167a.501.501 0 0 1-.454.71z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="M16.374 27.623a.504.504 0 0 1-.191-.038l-5.726-2.377a.5.5 0 1 1 .384-.924l5.223 2.168c.219-1.031-.345-1.833-1.685-2.388l-2.943-1.222a.5.5 0 0 1 .384-.924l2.942 1.222c2.061.854 2.817 2.375 2.074 4.172a.497.497 0 0 1-.271.271.471.471 0 0 1-.191.04zM10.771 7.039c-1.573 0-2.003-1.074-2.103-1.434-.183-.661-.031-1.488.54-1.727l4.461-1.841a.514.514 0 0 1 .19-.037h5.24c.977 0 1.931.218 2.836.648l3.503 1.702c.899.431 1.856.65 2.841.65H30.5a.5.5 0 0 1 0 1h-2.221a7.509 7.509 0 0 1-3.275-.75l-3.502-1.7A5.562 5.562 0 0 0 19.1 3h-5.141L9.591 4.802c0 .042-.059.429.133.758.294.504.969.52 1.483.445.745-.115 1.361-.322 1.904-.505l.206-.069a.5.5 0 1 1 .315.949l-.202.067c-.582.196-1.241.418-2.075.546a3.88 3.88 0 0 1-.584.046zM24.259 12.445c-1.89 0-3.572-.944-4.348-2.612a.501.501 0 0 1 .908-.422c.979 2.108 3.524 2.409 5.419 1.626A.525.525 0 0 1 26.43 11h4.07a.5.5 0 0 1 0 1h-3.973c-.76.301-1.53.445-2.268.445z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="M20.372 10.12a.502.502 0 0 1-.192-.038L17.238 8.86c-2.061-.854-2.817-2.375-2.074-4.172a.497.497 0 0 1 .654-.271l5.726 2.377a.5.5 0 1 1-.384.924L15.937 5.55c-.219 1.031.345 1.833 1.685 2.388l2.943 1.222a.5.5 0 0 1 .27.654.504.504 0 0 1-.463.306zM22.515 24.035a.5.5 0 0 1-.354-.853c.196-.195.547-.23.742-.035s.23.477.035.672l-.07.07a.498.498 0 0 1-.353.146zM9.515 9.035a.5.5 0 0 1-.351-.856l.071-.07a.5.5 0 1 1 .702.712l-.071.07a.497.497 0 0 1-.351.144z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                        <h3 class="h6 fw-bold mb-3">Health &amp; Wellness</h3>
                        <p class="mb-0">Nn eque unde quia tempora quas sit eos nulla repellendus ipsam corrupti aliquid
                            et quo.</p>
                    </a></div>
                <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="0"> <a
                        class="d-block feature" href="#">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 128 128"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <path
                                        d="M64 50.25A13.75 13.75 0 1 0 77.75 64 13.765 13.765 0 0 0 64 50.25Zm0 24A10.25 10.25 0 1 1 74.25 64 10.261 10.261 0 0 1 64 74.25Z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="M70 62.25A1.75 1.75 0 0 0 68.25 64a4.274 4.274 0 0 1-2.119 3.676 1.75 1.75 0 1 0 1.763 3.024A7.785 7.785 0 0 0 71.75 64 1.75 1.75 0 0 0 70 62.25Z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="M109.23 59.91a7.8 7.8 0 0 0 1.954.256 7.751 7.751 0 0 0 5.48-13.228c-.127-.126-.262-.24-.4-.357 1.3-4.714 1-8.82-.953-12.206-4.2-7.28-15.229-9.665-30.362-6.652C79.976 13.111 72.392 4.75 64 4.75s-15.975 8.36-20.953 22.974c-15.136-3.015-26.162-.629-30.361 6.65-1.893 3.27-2.225 7.379-1.018 11.94a7.75 7.75 0 0 0 7.039 13.515 72.339 72.339 0 0 0 3.4 4.177C11.938 75.61 8.489 86.353 12.685 93.625c3.015 5.227 9.541 7.938 18.583 7.938a61.159 61.159 0 0 0 11.777-1.292C48.022 114.887 55.607 123.25 64 123.25c3.792 0 7.532-1.778 10.893-5.124a7.746 7.746 0 0 0 8.587-12.571c-.126-.127-.261-.242-.394-.358a75.999 75.999 0 0 0 1.874-4.926 61.023 61.023 0 0 0 11.772 1.292c9.041 0 15.568-2.712 18.583-7.938 4.2-7.272.747-18.015-9.42-29.618a72.337 72.337 0 0 0 3.335-4.097Zm4.958-4.486a4.255 4.255 0 0 1-6.009 0 4.248 4.248 0 1 1 6.01 0Zm-1.905-19.3c1.369 2.377 1.633 5.332.809 8.8A7.745 7.745 0 0 0 105.7 57.9c.126.127.261.241.394.358q-1.218 1.586-2.572 3.148A105.745 105.745 0 0 0 89.56 49.245a107.039 107.039 0 0 0-3.54-18.169c13.188-2.55 22.941-.711 26.263 5.047ZM82.589 96.182a100.254 100.254 0 0 1-14.278-4.641 133.954 133.954 0 0 0 17.375-10.042 100.2 100.2 0 0 1-3.097 14.683Zm-37.178 0a100.179 100.179 0 0 1-3.1-14.683A134.255 134.255 0 0 0 59.69 91.541a100.265 100.265 0 0 1-14.279 4.641Zm0-64.364a100.213 100.213 0 0 1 14.278 4.641 135.519 135.519 0 0 0-17.38 10.047 100.151 100.151 0 0 1 3.102-14.688ZM38 74.034A98.792 98.792 0 0 1 26.838 64 98.907 98.907 0 0 1 38 53.966a133.757 133.757 0 0 0 0 20.068Zm14.567 9.78a128.955 128.955 0 0 1-10.807-6.977c-.418-4.191-.638-8.5-.638-12.837s.22-8.645.638-12.837a128.896 128.896 0 0 1 10.807-6.977 131.248 131.248 0 0 1 11.434-5.878 131.41 131.41 0 0 1 11.436 5.878 128.955 128.955 0 0 1 10.807 6.977c.418 4.191.638 8.5.638 12.837s-.22 8.645-.638 12.837a128.896 128.896 0 0 1-10.807 6.977 131.248 131.248 0 0 1-11.434 5.878 131.158 131.158 0 0 1-11.438-5.878Zm30.024-52a100.179 100.179 0 0 1 3.1 14.683 136.804 136.804 0 0 0-8.5-5.347 138.105 138.105 0 0 0-8.881-4.691 100.265 100.265 0 0 1 14.279-4.641ZM90 53.966A98.792 98.792 0 0 1 101.162 64 98.907 98.907 0 0 1 90 74.034a133.757 133.757 0 0 0 0-20.068ZM64 8.25c6.638 0 13.113 7.529 17.515 20.225a107.329 107.329 0 0 0-17.516 6 107.245 107.245 0 0 0-17.514-6C50.888 15.778 57.362 8.25 64 8.25ZM13.429 55.424a4.256 4.256 0 1 1 3.006 1.242 4.219 4.219 0 0 1-3.007-1.242Zm8.485-8.486a7.75 7.75 0 0 0-7.023-2.109c-.808-3.426-.538-6.35.825-8.7 3.323-5.761 13.076-7.6 26.264-5.049a107.109 107.109 0 0 0-3.541 18.17A105.667 105.667 0 0 0 24.476 61.4q-1.461-1.686-2.73-3.352c.055-.052.114-.1.168-.153a7.757 7.757 0 0 0 0-10.96Zm-6.2 44.937c-3.314-5.752-.032-15.121 8.767-25.275A105.714 105.714 0 0 0 38.44 78.755a107.039 107.039 0 0 0 3.54 18.169c-13.189 2.548-22.942.71-26.264-5.049Zm65.291 22.165a4.257 4.257 0 1 1 1.242-3.006 4.256 4.256 0 0 1-1.242 3.006Zm-3-10.753a7.747 7.747 0 0 0-5.934 12.728c-2.6 2.477-5.308 3.735-8.066 3.735-6.638 0-13.113-7.529-17.515-20.225a107.329 107.329 0 0 0 17.516-6 107.259 107.259 0 0 0 17.518 6c-.49 1.405-1 2.75-1.534 4.025a7.773 7.773 0 0 0-1.99-.263Zm34.284-11.412c-3.323 5.76-13.076 7.6-26.264 5.049a107.109 107.109 0 0 0 3.541-18.17A105.662 105.662 0 0 0 103.519 66.6c8.799 10.154 12.081 19.523 8.765 25.275Z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                        <h3 class="h6 fw-bold mb-3">Science &amp; Engineering </h3>
                        <p class="mb-0">Nn eque unde quia tempora quas sit eos nulla repellendus ipsam corrupti aliquid
                            et quo.</p>
                    </a></div>
                <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="100"> <a
                        class="d-block feature" href="#">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 682.667 682.667"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <defs>
                                        <clippath id="a" clippathunits="userSpaceOnUse">
                                            <path d="M0 512h512V0H0Z" fill="currentColor" opacity="1"
                                                data-original="currentColor"></path>
                                        </clippath>
                                    </defs>
                                    <g clip-path="url(#a)" transform="matrix(1.33333 0 0 -1.33333 0 682.667)">
                                        <path d="m0 0 42.804 112.387c.875 2.139 3.902 2.142 4.781.005L90 0"
                                            style="stroke-width:20;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(133 142.007)" fill="none" stroke="currentColor"
                                            stroke-width="20" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0h63.517"
                                            style="stroke-width:20;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(146.35 169.997)" fill="none" stroke="currentColor"
                                            stroke-width="20" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0h134"
                                            style="stroke-width:20;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(267 414.5)" fill="none" stroke="currentColor"
                                            stroke-width="20" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0v-9.685c0-50.46-37.56-93.03-87.627-99.315v0"
                                            style="stroke-width:20;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(358.127 414.5)" fill="none" stroke="currentColor"
                                            stroke-width="20" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0v-9.685c0-50.46 37.56-93.03 87.627-99.315v0"
                                            style="stroke-width:20;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(309.873 414.5)" fill="none" stroke="currentColor"
                                            stroke-width="20" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0v36"
                                            style="stroke-width:20;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(334 414.5)" fill="none" stroke="currentColor"
                                            stroke-width="20" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path
                                            d="M0 0h-79.962C-103.731 0-123-19.269-123-43.039v-161.922c0-23.77 19.269-43.039 43.038-43.039H95l74.961-64v64c23.77 0 43.039 19.269 43.039 43.039v161.922C213-19.269 193.731 0 169.961 0H90"
                                            style="stroke-width:20;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(289 502)" fill="none" stroke="currentColor"
                                            stroke-width="20" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path
                                            d="M0 0v-136.961C0-160.731-19.269-180-43.039-180H-218l-74.961-64v64c-23.77 0-43.039 19.269-43.039 43.039V24.961C-336 48.731-316.731 68-292.961 68H-180"
                                            style="stroke-width:20;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(346 254)" fill="none" stroke="currentColor"
                                            stroke-width="20" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0v0"
                                            style="stroke-width:20;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(334 502)" fill="none" stroke="currentColor"
                                            stroke-width="20" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <h3 class="h6 fw-bold mb-3">Language &amp; Communication</h3>
                        <p class="mb-0">Nn eque unde quia tempora quas sit eos nulla repellendus ipsam corrupti aliquid
                            et quo.</p>
                    </a></div>
                <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="200"> <a
                        class="d-block feature" href="#">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 512 512"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <path
                                        d="M448.785 167.356h-36.907V94.727c0-12.437-4.946-24.029-13.04-32.504L374.99 37.269c-20.238-20.852-22.792-26.743-34.846-32.594C334.544 1.88 327.165 0 319.725 0H145.533c-25.949 0-47.052 21.113-47.052 47.052V300.79h-9.923c-14.97 0-27.145 12.175-27.145 27.145 0 7.45 3.016 14.196 7.882 19.102-4.866 4.906-7.882 11.652-7.882 19.102 0 2.795.392 4.454.533 5.369C3.04 377.5-20.426 450.41 22.685 489.982c13.02 11.954 30.302 18.73 49.042 17.966 6.193 0 133.695-.05 133.856-.05 14.96 0 27.145-12.175 27.145-27.145 0-7.45-3.016-14.196-7.882-19.102 4.575-4.615 7.51-10.838 7.842-17.755h10.185v5.439c0 34.555 28.11 62.665 62.665 62.665h143.247c34.555 0 62.665-28.11 62.665-62.665V230.021c.001-34.555-28.11-62.665-62.665-62.665zm-64.032-96.577s.01 0 .02.02h.01c.01.02.03.04.04.05l.01.01c.02.03.04.05.06.06.01.01.02.02.03.04.01.01.02.01.02.02.01.01.02.01.03.03.02.01.03.03.04.04h-40.306V28.442a81023.526 81023.526 0 0 1 40.046 42.337zM88.558 316.876h117.026c6.093 0 11.059 4.967 11.059 11.059 0 6.103-4.967 11.059-11.059 11.059H88.558c-6.093 0-11.059-4.956-11.059-11.059-.001-6.093 4.966-11.059 11.059-11.059zm0 38.204h117.026c6.093 0 11.059 4.967 11.059 11.059 0 6.103-4.967 11.059-11.059 11.059h-80.833a65.506 65.506 0 0 0-10.185-3.68 67.801 67.801 0 0 0-16.086-2.363 67.968 67.968 0 0 0-4.735.04h-15.01a10.873 10.873 0 0 1-1.237-5.057c0-6.091 4.967-11.058 11.06-11.058zm128.085 49.264c0 6.103-4.967 11.059-11.059 11.059h-45.011a67.658 67.658 0 0 0-13.653-22.118h58.664c6.092 0 11.059 4.966 11.059 11.059zM49.428 389.907a69.402 69.402 0 0 0-12.668 16.247h-9.37c6.123-7.591 13.734-12.99 22.038-16.247zm-30.594 31.327h11.713c-.261 1.126-1.589 5.459-2.192 12.055H15.979a52.85 52.85 0 0 1 2.855-12.055zm1.297 39.864a52.806 52.806 0 0 1-3.78-12.728h12.286c2.202 12.487 2.322 10.335 2.875 12.728H20.131zm10.034 15.08c4.936-.01 7.802.02 8.475-.02a67.92 67.92 0 0 0 10.818 13.09c-7.289-2.865-13.773-7.238-19.293-13.07zm68.627 16.7c-29.427 1.267-54.341-21.525-55.588-51.023-2.021-46.961 54.069-74.006 89.388-41.583 34.818 31.961 13.624 90.655-33.8 92.606zm106.792-1.066h-64.917a68.9 68.9 0 0 0 17.282-22.118h47.635c6.093 0 11.059 4.967 11.059 11.059 0 6.102-4.967 11.059-11.059 11.059zm10.969-47.916c-.664 5.469-5.329 9.712-10.969 9.712h-42.085a69.1 69.1 0 0 0 .975-22.118h41.11c6.776-.001 11.833 6.062 10.969 12.406zm26.32-16.087h-14.518a26.687 26.687 0 0 0-3.509-4.363c10.506-10.597 10.516-27.608 0-38.204 4.866-4.906 7.882-11.652 7.882-19.102 0-7.45-3.016-14.196-7.882-19.102 16.91-17.051 4.826-46.247-19.263-46.247h-91.017V47.052c0-17.071 13.894-30.966 30.966-30.966h183.09v63.007a8.04 8.04 0 0 0 8.043 8.043h58.171c1.679 6.515.643 4.363.955 80.219h-90.253c-34.555 0-62.665 28.11-62.665 62.665v197.789zm253.497 21.526c0 26.24-21.344 47.585-47.585 47.585H305.539c-26.23 0-47.585-21.344-47.585-47.585V230.021c0-26.24 21.354-47.585 47.585-47.585h143.247c26.24 0 47.585 21.344 47.585 47.585v219.314z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="M448.253 199.538H306.072c-13.981 0-25.315 11.334-25.315 25.315v27.598c0 13.981 11.334 25.315 25.315 25.315h27.356a7.54 7.54 0 0 0 0-15.08h-27.356c-5.629 0-10.235-4.606-10.235-10.235v-27.598c0-5.629 4.606-10.235 10.235-10.235h142.181c5.65 0 10.235 4.595 10.235 10.235v27.598c0 5.64-4.585 10.235-10.235 10.235h-82.31a7.54 7.54 0 0 0 0 15.08h82.31c13.965 0 25.315-11.361 25.315-25.315v-27.598c0-13.954-11.351-25.315-25.315-25.315zM322.309 358.428h-25.768c-8.697 0-15.784 7.078-15.784 15.784v25.758c0 8.707 7.088 15.784 15.784 15.784h25.768c8.707 0 15.784-7.078 15.784-15.784v-25.758c0-8.706-7.078-15.784-15.784-15.784zm.703 41.542a.706.706 0 0 1-.704.704H296.54a.706.706 0 0 1-.704-.704v-25.758c0-.392.322-.704.704-.704h25.768c.382 0 .704.312.704.704v25.758zM458.598 291.068H432.84c-8.707 0-15.784 7.078-15.784 15.784v25.758c0 8.707 7.078 15.784 15.784 15.784h25.758c8.707 0 15.784-7.078 15.784-15.784v-25.758c0-8.706-7.078-15.784-15.784-15.784zm.704 41.542a.7.7 0 0 1-.704.704H432.84a.706.706 0 0 1-.704-.704c.312-25.486-.654-26.462.704-26.462h25.758a.7.7 0 0 1 .704.704v25.758zM322.309 291.068h-25.768c-8.697 0-15.784 7.078-15.784 15.784v25.758c0 8.707 7.088 15.784 15.784 15.784h25.768c8.707 0 15.784-7.078 15.784-15.784v-25.758c0-8.706-7.078-15.784-15.784-15.784zm.703 41.542a.706.706 0 0 1-.704.704H296.54a.706.706 0 0 1-.704-.704v-25.758c0-.392.322-.704.704-.704h25.768c.382 0 .704.312.704.704v25.758zM334.122 427.809c-2.896-3.257-7.118-5.318-11.813-5.318h-25.768a15.737 15.737 0 0 0-11.803 5.318c-5.479 6.143-3.619 12.839-3.981 16.086v20.138c0 8.707 7.088 15.784 15.784 15.784h25.768c8.707 0 15.784-7.078 15.784-15.784v-20.138c-.362-3.176 1.478-9.943-3.971-16.086zm-11.813 36.928c-25.456-.312-26.472.654-26.472-.704 0-27.276-.302-26.462.704-26.462h25.768c1.237 0 .473 1.719.704 6.324-.001.001.653 20.842-.704 20.842zM454.596 358.428h-17.755c-10.908 0-19.786 8.878-19.786 19.786v79.998c0 11.914 9.692 21.606 21.596 21.606h14.136c11.914 0 21.596-9.692 21.596-21.606v-79.998c-.001-10.908-8.868-19.786-19.787-19.786zm-1.809 106.309h-14.136c-3.589 0-6.515-2.926-6.515-6.525v-79.998a4.71 4.71 0 0 1 4.705-4.705h17.755a4.71 4.71 0 0 1 4.705 4.705v79.998c.001 3.599-2.915 6.525-6.514 6.525zM194.647 219.028c-9.194-3.378-15.836-5.818-15.836-14.336 0-7.763 5.156-11.699 15.323-11.699 10.793 0 13.699 4.751 17.501 4.751 4.138 0 6.427-4.391 6.427-7.428 0-6.282-10.247-9.397-19.407-10.219v-5.664a7.153 7.153 0 0 0-7.145-7.145 7.153 7.153 0 0 0-7.145 7.145V181c-12.525 2.974-19.407 11.625-19.407 24.443 0 17.55 12.92 22.405 23.304 26.308 10.166 3.82 18.197 6.838 18.197 18.445 0 9.587-5.323 14.448-15.824 14.448-15.368 0-15.512-10.376-21.374-10.376-3.63 0-6.554 4.064-6.554 7.428 0 5.942 8.849 13.752 21.658 15.779v5.473c0 3.94 3.205 7.145 7.145 7.145s7.145-3.205 7.145-7.145v-5.62c13.778-2.593 21.658-12.71 21.658-27.883-.001-20.987-14.231-26.215-25.666-30.417zM400.99 426.915a15.72 15.72 0 0 0-10.939-4.424h-25.768c-4.695 0-8.918 2.061-11.813 5.318-5.459 6.153-3.609 12.839-3.971 16.086v20.138c0 8.707 7.078 15.784 15.784 15.784h25.768c8.697 0 15.774-7.078 15.774-15.784v-25.768c0-4.453-1.86-8.485-4.835-11.35zm-10.939 37.822c-25.456-.312-26.472.654-26.472-.704v-25.768c0-.382.322-.694.704-.694h25.768c.382 0 .694.312.694.694-.302 25.517.643 26.472-.694 26.472zM390.051 291.068h-25.768c-8.707 0-15.784 7.078-15.784 15.784v25.758c0 8.707 7.078 15.784 15.784 15.784h25.768c2.031 0 3.961-.382 5.741-1.086 5.871-2.302 10.034-8.023 10.034-14.699v-25.758c-.001-8.736-7.119-15.783-15.775-15.783zm0 42.246h-25.768a.706.706 0 0 1-.704-.704v-25.758c0-1.357.935-.402 26.472-.704.382 0 .694.312.694.704v25.758a.698.698 0 0 1-.694.704zM395.792 359.514a15.543 15.543 0 0 0-5.741-1.086h-25.768c-8.707 0-15.784 7.078-15.784 15.784v25.758c0 8.707 7.078 15.784 15.784 15.784h25.768c8.697 0 15.774-7.078 15.774-15.784v-25.758c0-6.675-4.162-12.396-10.033-14.698zm-5.047 40.456a.698.698 0 0 1-.694.704h-25.768a.706.706 0 0 1-.704-.704v-25.758c0-.392.322-.704.704-.704h25.768c.382 0 .694.312.694.704v25.758zM346.528 103.041h-18.941a7.543 7.543 0 0 0-7.54 7.54 7.536 7.536 0 0 0 7.54 7.54h18.941a7.536 7.536 0 0 0 7.54-7.54 7.542 7.542 0 0 0-7.54-7.54zM297.425 103.041H163.71a7.543 7.543 0 0 0-7.54 7.54 7.536 7.536 0 0 0 7.54 7.54h133.716a7.536 7.536 0 0 0 7.54-7.54 7.543 7.543 0 0 0-7.541-7.54zM187.678 140.472H163.71a7.543 7.543 0 0 0-7.54 7.54 7.543 7.543 0 0 0 7.54 7.54h23.968a7.543 7.543 0 0 0 7.54-7.54 7.543 7.543 0 0 0-7.54-7.54zM346.528 140.472H217.839a7.543 7.543 0 0 0-7.54 7.54 7.543 7.543 0 0 0 7.54 7.54h128.689a7.543 7.543 0 0 0 7.54-7.54 7.542 7.542 0 0 0-7.54-7.54zM92.233 425.159c0-1.895.698-2.439 1.351-2.801 2.026-1.121 6.674-1.451 10.932.739 1.131.581 2.413 1.24 4.081 1.24 7.437 0 13.319-13.819-4.446-16.874v-.475c0-4.213-3.428-7.641-7.641-7.641s-7.641 3.428-7.641 7.641v1.45c-7.444 2.7-11.655 8.839-11.655 17.172 0 12.86 9.343 16.371 16.167 18.934 6.507 2.445 8.757 3.515 8.757 7.941 0 2.767-.745 5.328-6.154 5.328-7.882 0-6.938-6.23-12.837-6.23-4.169 0-7.286 4.123-7.286 7.81 0 5.224 5.432 10.182 13.008 12.192v.572c0 4.213 3.428 7.641 7.641 7.641s7.641-3.427 7.641-7.641v-.772c8.198-2.635 13.007-9.694 13.007-19.35 0-22.913-24.925-19.472-24.925-26.876z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                        <h3 class="h6 fw-bold mb-3">Finance &amp; Investment</h3>
                        <p class="mb-0">Nn eque unde quia tempora quas sit eos nulla repellendus ipsam corrupti aliquid
                            et quo.</p>
                    </a></div>
                <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="300"> <a
                        class="d-block feature" href="#">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 16.933 16.933"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <path
                                        d="M8.467.53a1.058 1.058 0 1 0-.002 2.115A1.058 1.058 0 0 0 8.467.529zm0 .528a.529.529 0 1 1 .002 1.058.529.529 0 0 1-.002-1.058zm-1.78 1.059L3.145 3.75c-.32.14-.106.63.221.48L6.91 2.598c.247-.11.183-.486-.094-.506a.257.257 0 0 0-.129.026zm6.88 2.115c.327.149.541-.34.222-.481l-3.543-1.634a.263.263 0 0 0-.129-.026c-.276.02-.34.396-.093.506zm-5.794-.684a.618.618 0 0 0-.314.348c-.044.104-.06.163-.115.23-.055.067-.155.153-.385.25-.232.098-.366.112-.456.106-.089-.006-.142-.033-.243-.073a.643.643 0 0 0-.456-.025c-.177.058-.35.199-.544.395-.21.213-.337.388-.387.549a.637.637 0 0 0 .035.452c.041.1.068.155.076.243.008.088-.004.223-.098.455s-.179.337-.246.394c-.066.058-.124.076-.222.118a.627.627 0 0 0-.342.295c-.078.15-.11.36-.107.652-.002.268.021.488.107.652a.629.629 0 0 0 .342.295c.098.042.153.059.22.116.066.058.154.164.248.396.093.227.105.361.098.45-.007.087-.032.14-.072.235a.65.65 0 0 0-.047.434.989.989 0 0 0 .212.38c-.18.296-.322.61-.42.95a2.103 2.103 0 0 0-.74-.668 1.322 1.322 0 1 0-2.33-.858c-.001.327.118.626.317.858a2.115 2.115 0 0 0-1.11 1.869v.31c0 .663.534 1.196 1.19 1.196h1.851c.245 0 .473-.073.663-.202v.998c0 .317.1.628.342.833.242.205.597.269 1.002.19l.018-.004 2.605-.702 2.616.704c.414.08.759.025 1.012-.188.242-.204.342-.514.342-.83v-1c.19.129.418.2.663.2h1.851c.656 0 1.19-.532 1.19-1.194v-.311c0-.809-.445-1.51-1.11-1.87.2-.23.318-.53.318-.857a1.324 1.324 0 1 0-2.33.858c-.296.16-.55.387-.741.67a3.917 3.917 0 0 0-.42-.953.982.982 0 0 0 .212-.379.65.65 0 0 0-.047-.434c-.04-.095-.065-.148-.072-.236-.007-.088.005-.222.098-.45.094-.231.182-.337.249-.395.067-.057.12-.074.22-.116a.629.629 0 0 0 .342-.295c.085-.164.109-.383.107-.652.002-.293-.03-.502-.107-.652a.628.628 0 0 0-.342-.295c-.1-.042-.156-.06-.223-.118-.067-.057-.152-.162-.246-.394s-.106-.367-.098-.455c.008-.088.035-.142.077-.243a.635.635 0 0 0 .035-.452c-.05-.16-.177-.336-.387-.549-.194-.196-.368-.336-.545-.395-.196-.064-.354-.014-.455.026s-.155.066-.244.072c-.09.006-.224-.008-.455-.106-.231-.097-.33-.183-.385-.25-.055-.067-.072-.126-.116-.23a.618.618 0 0 0-.314-.348c-.171-.083-.377-.109-.693-.11-.317 0-.522.027-.694.11zm-6.186.155a1.057 1.057 0 1 0-.001 2.115 1.057 1.057 0 0 0 .001-2.115zm13.759 0a1.058 1.058 0 1 0-.002 2.116 1.058 1.058 0 0 0 .002-2.116zm-6.416.32c.036.017.04.046.056.078.03.07.075.213.194.359.119.146.301.284.587.405.285.12.512.159.7.146.187-.013.325-.079.4-.108.044-.017.067-.027.096-.017.038.012.134.061.334.264.19.193.246.298.258.336.008.031-.005.059-.02.093-.025.06-.097.21-.113.397-.016.187.018.415.134.7.117.287.249.474.391.597.142.122.288.173.362.205.042.018.062.02.078.05.016.032.053.135.05.409.002.257-.029.366-.05.408-.016.031-.046.037-.078.05-.075.032-.22.083-.362.206-.142.122-.274.31-.39.595-.114.281-.15.505-.135.69.014.185.075.31.111.398.016.044.031.068.023.098a.387.387 0 0 1-.023.056 3.979 3.979 0 0 0-1.47-1.11c.326-.373.52-.859.52-1.39A2.121 2.121 0 0 0 8.468 5.82c-1.166 0-2.117.951-2.117 2.117 0 .532.194 1.018.52 1.39a3.977 3.977 0 0 0-1.47 1.11.408.408 0 0 1-.023-.055c-.008-.03.008-.054.023-.098.037-.087.097-.213.111-.398.014-.185-.02-.409-.134-.69-.116-.286-.248-.473-.39-.595-.143-.123-.288-.174-.362-.206-.033-.013-.062-.019-.078-.05-.022-.042-.053-.151-.051-.408-.002-.274.034-.377.05-.409.017-.03.037-.032.079-.05.074-.032.219-.083.361-.205.143-.123.275-.31.391-.596.116-.286.15-.514.134-.701-.016-.187-.088-.336-.113-.397-.014-.034-.028-.062-.02-.093.012-.038.068-.143.258-.336.2-.203.297-.252.335-.264.029-.01.051 0 .095.017.075.03.214.095.4.108.188.013.415-.026.7-.146.286-.121.469-.26.588-.405.118-.146.164-.289.193-.36.017-.031.02-.06.057-.077.042-.02.182-.055.463-.055.28 0 .42.034.463.055zm-7.343.21A.529.529 0 1 1 1.59 5.29a.529.529 0 0 1-.003-1.057zm13.759 0a.528.528 0 1 1 0 1.058.53.53 0 0 1 0-1.058zM2.064 6.25a.265.265 0 0 0-.271.336l.465 1.762c.08.355.615.214.511-.135l-.465-1.761a.265.265 0 0 0-.24-.202zm12.805 0a.264.264 0 0 0-.24.202l-.465 1.761c-.104.35.43.49.512.135l.465-1.762a.265.265 0 0 0-.272-.336zm-6.402.099a1.588 1.588 0 1 1 0 3.176 1.588 1.588 0 0 1 0-3.176zM2.91 9.524a.793.793 0 1 1 0 1.587.793.793 0 0 1 0-1.587zm11.113 0a.794.794 0 1 1 .002 1.587.794.794 0 0 1-.002-1.587zm-7.408 4.354v-.916c-.008-.345-.521-.345-.53 0v1.052l-.065.017c-.343.087-.212.6.13.513l4-1.027a.553.553 0 1 1 .288 1.069L5.74 15.85h-.002c-.3.058-.462.005-.556-.074-.095-.08-.154-.219-.154-.43v-2.382c0-1.509.962-2.782 2.293-3.247a2.117 2.117 0 0 0 2.29 0 3.426 3.426 0 0 1 2.295 3.247v2.386c0 .208-.06.346-.154.426-.095.08-.256.132-.557.074l-1.71-.46 1.09-.294a1.083 1.083 0 0 0 .272-1.977v-.158c-.008-.345-.521-.342-.529.009a1.04 1.04 0 0 0-.3.032zm2.91-2.501c-.345.008-.345.521 0 .529h.793c.345-.008.345-.522 0-.53zm-7.127.16a1.326 1.326 0 0 0 1.026 0 1.589 1.589 0 0 1 1.074 1.509v.31a.661.661 0 0 1-.663.667H1.984a.662.662 0 0 1-.661-.667v-.31c0-.699.446-1.298 1.075-1.51zm11.111 0a1.32 1.32 0 0 0 1.027 0c.628.211 1.074.81 1.074 1.509v.31c0 .365-.29.667-.66.667h-1.852a.663.663 0 0 1-.663-.667v-.31c0-.698.446-1.298 1.074-1.51z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                        <h3 class="h6 fw-bold mb-3">Personal Development </h3>
                        <p class="mb-0">Nn eque unde quia tempora quas sit eos nulla repellendus ipsam corrupti aliquid
                            et quo.</p>
                    </a></div>
            </div>
        </div>
    </div>
    <!-- End Features-->

    <!-- ======= About =======-->
    <div class="section about__v11" id="about">
        <div class="container">
            <div class="row g-3 justify-content-between">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="">
                    <h2 class="fw-bold mb-4">Start Better Learning Future From Here</h2>
                    <p class="mb-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quibusdam modi eligendi
                        rerum. Provident temporibus beatae dolorum, dignissimos fuga alias illo quod sed! Odio eveniet
                        doloribus eius nulla dolorem eligendi facere.</p>
                    <p> <a class="btn btn-primary" href="#">Learn More</a></p>
                </div>
                <div class="col-lg-7">
                    <div class="row g-3">
                        <div class="col-6 text-end">
                            <div class="mb-3" data-aos="fade-right" data-aos-delay="100"><img
                                    class="img-fluid rounded-4 mt-5" src="assets/inicio/images/freepik-02-min.jpg"
                                    alt=" image placeholder"></div>
                            <div class="contact d-inline-flex" data-aos="fade-right" data-aos-delay="200">
                                <div class="d-flex gap-3">
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 74 74"
                                            style="enable-background:new 0 0 512 512" xml:space="preserve">
                                            <g>
                                                <path
                                                    d="M62.337 69.086H12.413a3.778 3.778 0 0 1-3.774-3.774V58.7a1 1 0 0 1 1-1h55.472a1 1 0 0 1 1 1v6.611a3.778 3.778 0 0 1-3.774 3.775zM10.639 59.7v5.611a1.776 1.776 0 0 0 1.774 1.774h49.924a1.776 1.776 0 0 0 1.774-1.774V59.7zM31.417 28.006h-4.8a1 1 0 0 1-1-1v-2.843a2.522 2.522 0 0 1 2.52-2.521H29.9a2.523 2.523 0 0 1 2.521 2.521v2.843a1 1 0 0 1-1.004 1zm-3.8-2h2.8v-1.843a.521.521 0 0 0-.521-.521h-1.754a.52.52 0 0 0-.52.521zM48.128 28.006h-4.8a1 1 0 0 1-1-1v-2.843a2.523 2.523 0 0 1 2.521-2.521h1.754a2.522 2.522 0 0 1 2.52 2.521v2.843a1 1 0 0 1-.995 1zm-3.8-2h2.8v-1.843a.52.52 0 0 0-.52-.521h-1.754a.521.521 0 0 0-.521.521zM70.567 30.005a.981.981 0 0 1-.233-.027l-15.082-3.6a1 1 0 1 1 .465-1.945l15.082 3.6a1 1 0 0 1-.232 1.972z"
                                                    fill="currentColor" opacity="1" data-original="currentColor">
                                                </path>
                                                <path
                                                    d="M6.844 34.671a3.351 3.351 0 0 1-3.325-2.9l-1.061-7.846a9.337 9.337 0 0 1 5.108-9.593C18.222 9.014 29.657 7.9 37.375 7.9c7.74 0 19.2 1.115 29.818 6.429a9.339 9.339 0 0 1 5.1 9.589l-1.062 7.85a3.352 3.352 0 0 1-4.1 2.812l-10.544-2.519a3.364 3.364 0 0 1-2.555-3.614l.6-5.7a2.125 2.125 0 0 0-1.667-2.289 71 71 0 0 0-31.138 0 2.126 2.126 0 0 0-1.666 2.289l.6 5.706a3.363 3.363 0 0 1-2.558 3.614L7.619 34.581a3.341 3.341 0 0 1-.775.09zM37.375 9.9c-7.5 0-18.609 1.08-28.916 6.223a7.336 7.336 0 0 0-4.018 7.536L5.5 31.5a1.352 1.352 0 0 0 1.655 1.135l10.588-2.521a1.357 1.357 0 0 0 1.032-1.458l-.6-5.706a4.127 4.127 0 0 1 3.218-4.45 73 73 0 0 1 32.008 0 4.126 4.126 0 0 1 3.222 4.452l-.6 5.7a1.356 1.356 0 0 0 1.031 1.458l10.541 2.518a1.353 1.353 0 0 0 1.653-1.128l1.061-7.85a7.334 7.334 0 0 0-4.009-7.534C56.026 10.977 44.9 9.9 37.375 9.9z"
                                                    fill="currentColor" opacity="1" data-original="currentColor">
                                                </path>
                                                <path
                                                    d="M4.181 30.005a1 1 0 0 1-.231-1.972L19.2 24.39a1 1 0 1 1 .465 1.945l-15.25 3.643a1 1 0 0 1-.234.027zM65.111 59.7H9.639a1 1 0 0 1-1-1v-9.548a6.631 6.631 0 0 1 3.615-5.925A18.1 18.1 0 0 0 22.13 29.58a4.127 4.127 0 0 1 4.1-3.58h22.288a4.127 4.127 0 0 1 4.1 3.58A18.1 18.1 0 0 0 62.5 43.227a6.631 6.631 0 0 1 3.615 5.925V58.7a1 1 0 0 1-1.004 1zm-54.472-2h53.472v-8.548a4.616 4.616 0 0 0-2.5-4.133A20.1 20.1 0 0 1 50.64 29.864 2.14 2.14 0 0 0 48.518 28H26.232a2.14 2.14 0 0 0-2.122 1.864 20.1 20.1 0 0 1-10.967 15.155 4.616 4.616 0 0 0-2.5 4.133z"
                                                    fill="currentColor" opacity="1" data-original="currentColor">
                                                </path>
                                                <path
                                                    d="M37.375 54.694A11.145 11.145 0 1 1 48.52 43.549a11.157 11.157 0 0 1-11.145 11.145zm0-20.29a9.145 9.145 0 1 0 9.145 9.145 9.156 9.156 0 0 0-9.145-9.149z"
                                                    fill="currentColor" opacity="1" data-original="currentColor">
                                                </path>
                                                <path
                                                    d="M37.375 38.452a1 1 0 0 1-1-1v-.62a1 1 0 1 1 2 0v.62a1 1 0 0 1-1 1zM33.063 40.237a1 1 0 0 1-.706-.292l-.439-.438a1 1 0 1 1 1.414-1.415l.438.438a1 1 0 0 1-.707 1.707zM31.277 44.549h-.619a1 1 0 1 1 0-2h.619a1 1 0 0 1 0 2zM32.625 49.3a1 1 0 0 1-.707-1.708l.439-.438a1 1 0 0 1 1.413 1.415l-.438.438a1 1 0 0 1-.707.293zM37.375 51.267a1 1 0 0 1-1-1v-.62a1 1 0 1 1 2 0v.62a1 1 0 0 1-1 1zM42.125 49.3a1 1 0 0 1-.707-.293l-.438-.438a1 1 0 1 1 1.413-1.415l.439.438a1 1 0 0 1-.707 1.708zM44.092 44.549h-.619a1 1 0 0 1 0-2h.619a1 1 0 0 1 0 2zM41.687 40.237a1 1 0 0 1-.707-1.707l.438-.438a1 1 0 1 1 1.414 1.415l-.439.438a1 1 0 0 1-.706.292z"
                                                    fill="currentColor" opacity="1" data-original="currentColor">
                                                </path>
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="text-start"><strong class="d-block">Contact Us</strong><span>+1 2234 567
                                            7890</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6" data-aos="fade-right" data-aos-delay="300"><img class="img-fluid rounded-4"
                                src="assets/inicio/images/about-1-img.jpg" alt=" image placeholder"></div>
                    </div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>
    <!-- End About-->

    <!-- ======= CTA =======-->
    <div class="section cta__v3 pb-4" id="cta">
        <div class="container" data-aos="fade-in" data-aos-delay="0">
            <div class="d-md-flex inner rounded-5 justify-content-between"><img class="img-fluid img-absolute"
                    src="assets/inicio/images/freepik-03-png-min.png" alt=" image placeholder" data-aos="fade-right"
                    data-aos-delay="200">
                <div class="col-md-6 mb-4 mb-md-0 content" data-aos="fade-up" data-aos-delay="300">
                    <h3 class="fs-6 mb-2 fw-bold text-white">Your Learning Journey Awaits</h3>
                    <h2 class="fw-bold fs-3 mb-5">Whether you're starting from scratch or advancing your expertise, we're
                        here to help you succeed.</h2>
                    <p class="mb-0 d-flex gap-2"><a
                            class="btn btn-white hover-outline d-inline-flex gap-2 align-items-center"
                            href="page-contact.html"><i class="bi bi-cursor fs-5"></i><span>Get Started Now</span></a><a
                            class="btn btn-border-white d-inline-flex gap-2 align-items-center"
                            href="page-courses.html"><i class="bi bi-book fs-5"></i><span>Browse All Courses</span></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- End CTA-->

    <!-- ======= Stats =======-->
    <div class="section stats__v8 py-0">
        <div class="container">
            <div class="row pt-md-5 g-4">
                <div class="col-6 col-sm-6 col-md-3 col-lg-2 stats-item text-center" data-aos="fade-up"
                    data-aos-delay="0">
                    <h2 class="fs-3 fw-bold"><span class="purecounter" data-purecounter-start="0"
                            data-purecounter-end="100000" data-purecounter-duration="2">0</span><span>+</span></h2>
                    <p>Students</p>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-2 stats-item text-center" data-aos="fade-up"
                    data-aos-delay="100">
                    <h2 class="fs-3 fw-bold"><span class="purecounter" data-purecounter-start="0"
                            data-purecounter-end="5000" data-purecounter-duration="2">0</span><span>+</span></h2>
                    <p>Graduates/Month</p>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-2 stats-item text-center" data-aos="fade-up"
                    data-aos-delay="200">
                    <h2 class="fs-3 fw-bold"><span class="purecounter" data-purecounter-start="0"
                            data-purecounter-end="200" data-purecounter-duration="2">0</span><span>+</span></h2>
                    <p>Instructors</p>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-2 stats-item text-center" data-aos="fade-up"
                    data-aos-delay="300">
                    <h2 class="fs-3 fw-bold"><span class="purecounter" data-purecounter-start="0"
                            data-purecounter-end="98" data-purecounter-duration="2">0</span><span>%</span></h2>
                    <p>Satisfaction</p>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-2 stats-item text-center" data-aos="fade-up"
                    data-aos-delay="400">
                    <h2 class="fs-3 fw-bold"><span class="purecounter" data-purecounter-start="0"
                            data-purecounter-end="50" data-purecounter-duration="2">0</span><span>+</span></h2>
                    <p>Countries</p>
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-2 stats-item text-center" data-aos="fade-up"
                    data-aos-delay="500">
                    <h2 class="fs-3 fw-bold"><span class="purecounter" data-purecounter-start="0"
                            data-purecounter-end="20" data-purecounter-duration="2">0</span><span>+</span></h2>
                    <p>Years</p>
                </div>
            </div>
        </div>
    </div>
    <!-- End Stats-->

    <!-- ======= Services =======-->
    <div class="section services__v8" id="services">
        <div class="container">
            <div class="row" data-aos="fade-up" data-aos-delay="100">
                <div class="col-md-7 mx-auto text-center mb-5">
                    <h2 class="h2 fw-bold mb-4">Smart Learning for a Smarter Future </h2>
                    <p class="mb-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maiores quae dolorem
                        tempora. Sequi odit facilis dolores corporis nemo. Totam deserunt temporibus dolor iure suscipit
                        eligendi fugiat quam eum accusamus aliquam?</p>
                    <p><a class="btn btn-primary" href="#">Learn More </a></p>
                </div>
            </div>
            <div class="row g-2">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-item h-100 rounded-4 p-4">
                        <div class="icon mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 64 64"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <path
                                        d="M59 24.07v-2a3.08 3.08 0 0 0-.94-2.22A3.11 3.11 0 0 0 55.8 19a74.37 74.37 0 0 0-11 1.12l-1.32.26a11.56 11.56 0 0 0-22.94-.51v.52A73.78 73.78 0 0 0 8.2 19 3.11 3.11 0 0 0 5 22.1v2A5 5 0 0 0 1 29v28a5 5 0 0 0 5 5h52a5 5 0 0 0 5-5V29a5 5 0 0 0-4-4.93Zm-36.46-3.92a9.61 9.61 0 0 1 8.37-8.09A10 10 0 0 1 32 12a9.45 9.45 0 0 1 6.36 2.44 9.56 9.56 0 0 1-.56 14.72 6.8 6.8 0 0 0-2.75 5.42A1.43 1.43 0 0 1 33.63 36H33v-7a1 1 0 0 0-.23-.64L29.13 24h5.45l-1.79 1.79a1 1 0 0 0 1.41 1.41l3.5-3.5A1 1 0 0 0 37 22H27a1 1 0 0 0-.77 1.64L31 29.36V36h-.63a1.43 1.43 0 0 1-1.43-1.44 6.83 6.83 0 0 0-2.76-5.41 9.51 9.51 0 0 1-3.64-9ZM7 22.1A1.11 1.11 0 0 1 8.15 21a71.65 71.65 0 0 1 12.34 1.42A11.42 11.42 0 0 0 25 30.73a4.85 4.85 0 0 1 2 3.84A3.43 3.43 0 0 0 30.37 38h3.26a3.44 3.44 0 0 0 3.43-3.46 4.81 4.81 0 0 1 2-3.8 11.46 11.46 0 0 0 4.48-8.32l1.66-.33A72.38 72.38 0 0 1 55.85 21a1.05 1.05 0 0 1 .81.32 1.09 1.09 0 0 1 .33.79V53.9a1.1 1.1 0 0 1-1.05 1.1A66.2 66.2 0 0 0 33 59.43V42h1a1 1 0 0 0 0-2h-4a1 1 0 0 0 0 2h1v17.44A66 66 0 0 0 8.06 55 1.1 1.1 0 0 1 7 53.9ZM3 57V29a3 3 0 0 1 2-2.82V53.9A3.12 3.12 0 0 0 8 57a68.67 68.67 0 0 1 18.51 3H6a3 3 0 0 1-3-3Zm58 0a3 3 0 0 1-3 3H37.48A68.67 68.67 0 0 1 56 57a3.12 3.12 0 0 0 3-3.1V26.15A3 3 0 0 1 61 29Z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="M10.93 27c1.32.09 2.65.21 3.95.37H15a1 1 0 0 0 .12-2c-1.33-.16-2.7-.29-4.05-.38a1 1 0 0 0-.13 2ZM10.93 31.13a71.76 71.76 0 0 1 7.28.85h.17a1 1 0 0 0 .17-2 73.93 73.93 0 0 0-7.48-.88 1 1 0 1 0-.13 2ZM10 37.07a1 1 0 0 0 .93 1.06A67.5 67.5 0 0 1 26.69 41a1 1 0 0 0 .6-1.91 69.59 69.59 0 0 0-16.23-3 1 1 0 0 0-1.06.98ZM27.3 44a69.6 69.6 0 0 0-16.23-3 1 1 0 0 0-.13 2 67.51 67.51 0 0 1 15.76 2.88 1 1 0 0 0 .6-1.88ZM27.3 49a69.59 69.59 0 0 0-16.23-3 1 1 0 0 0-.13 2 67.5 67.5 0 0 1 15.75 3 1 1 0 0 0 .61-2ZM37 41.06a1 1 0 0 0 .3 0 67.5 67.5 0 0 1 15.76-2.88 1 1 0 0 0-.13-2 69.59 69.59 0 0 0-16.23 3 1 1 0 0 0 .3 2ZM37 45.93a1 1 0 0 0 .3 0A67.51 67.51 0 0 1 53.06 43a1 1 0 0 0-.13-2 69.6 69.6 0 0 0-16.23 3 1 1 0 0 0 .3 2ZM37 51a1 1 0 0 0 .3 0 67.5 67.5 0 0 1 15.76-2.88 1 1 0 0 0-.13-2A69.59 69.59 0 0 0 36.7 49a1 1 0 0 0 .3 2ZM32 7a1 1 0 0 0 1-1V2a1 1 0 0 0-2 0v4a1 1 0 0 0 1 1ZM39.07 9.93a1 1 0 0 0 .71-.29l2.83-2.83A1 1 0 0 0 41.2 5.4l-2.84 2.82a1 1 0 0 0 .71 1.71ZM24.22 9.64a1 1 0 0 0 1.41-1.41l-2.82-2.84A1 1 0 0 0 21.4 6.8Z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                        <h3 class="h6 mb-3 fw-semibold">Flexible Learning Options</h3>
                        <p>Ipsum dolor sit amet consectetur adipisicing elit. Deleniti facilis ipsam incidunt distinctio
                            laboriosam.</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-item h-100 rounded-4 p-4">
                        <div class="icon mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 64 64"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <path
                                        d="M57.645 52.256a.994.994 0 0 0-.682-.569c2.108-2.84-.443-6.577-4.053-6.498h-5.893a6.18 6.18 0 0 0-4.247-4.563l-6.225-1.857-1.78-2.67a1 1 0 0 0-1.363-.293v-2.06a10.414 10.414 0 0 0 3.572-6.202c2.453.177 4.599-1.74 4.588-4.263 0-.979-.348-1.87-.91-2.586v-4.441c-.745-16.4-27.117-16.409-27.872 0v4.453c-2.281 2.844.025 7.143 3.681 6.837.43 2.479 1.73 4.662 3.583 6.212l-.03 2.033a.997.997 0 0 0-1.332.31l-1.78 2.67-6.223 1.856a6.206 6.206 0 0 0-4.405 5.91v12.349a1 1 0 0 0 1 1c11.9-.143 37.841.265 49.453.232.845.024 1.334-1.083.727-1.686-1.66-1.761-1.66-3.331 0-5.091.274-.291.349-.716.19-1.083zm-1.918-2.836c0 1.231-1.264 2.232-2.817 2.232H37.566c.21-.408.355-.82.449-1.232h8.158c1.307-.005 1.308-1.995 0-2h-8.159a5.196 5.196 0 0 0-.448-1.232H52.91c1.553 0 2.817 1.001 2.817 2.232zM33.623 37.99l1.134 1.7-2.306 4.532-3.929-3.28 5.101-2.952zm3.723-12.447h-.208v-4.48c3.038-.165 3.286 4.372.208 4.48zM26.72 5.884c6.579 0 11.931 4.652 11.931 10.37v3.038a4.272 4.272 0 0 0-1.514-.229v-4.04a1 1 0 0 0-.945-.998c-2.58-.145-4.4-.535-5.563-1.192a.997.997 0 0 0-1.111.085c-2.971 2.34-6.873 2.718-11.925 1.15a1 1 0 0 0-1.296.955v4.04a4.262 4.262 0 0 0-1.518.23v-3.04c0-5.717 5.357-10.37 11.941-10.37zM13.872 23.28c-.006-1.307 1.134-2.316 2.425-2.218v4.48a2.247 2.247 0 0 1-2.425-2.262zm4.425 2.483c.02-2.25-.013-7.21 0-9.418 4.799 1.226 8.807.733 11.942-1.472 1.226.556 2.815.906 4.899 1.082v9.808c-.423 11.152-16.418 11.154-16.84 0zm3.747 9.3c2.78 1.48 6.581 1.476 9.358-.006v1.906l-4.678 2.71-4.68-2.71zm-2.219 2.926 5.1 2.953-3.927 3.28-2.308-4.532zM8.274 46.535c0-1.829 1.224-3.47 2.976-3.994l5.77-1.72 2.765 5.427c.27.562 1.062.725 1.532.314l4.458-3.723-.058 15.045H8.274V46.535zm19.5-3.61 4.359 3.637c.469.411 1.263.247 1.532-.315l2.761-5.427 5.771 1.722a4.164 4.164 0 0 1 2.736 2.646h-9.314c-.85-.027-1.33 1.087-.727 1.686 1.66 1.76 1.66 3.331 0 5.093a.994.994 0 0 0 .49 1.65c-.932 1.205-1.014 2.995-.165 4.267h-7.5zm11.662 15.191c-3.712-.082-3.722-4.38 0-4.464H54.78c-.21.408-.356.82-.45 1.232h-8.157c-1.307.005-1.309 1.995 0 2h8.158c.093.413.239.824.448 1.232H39.436z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                        <h3 class="h6 mb-3 fw-semibold">Expert Instructors </h3>
                        <p>Ipsum dolor sit amet consectetur adipisicing elit. Deleniti facilis ipsam incidunt distinctio
                            laboriosam.</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-item h-100 rounded-4 p-4">
                        <div class="icon mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 44 44"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <path
                                        d="M4 0C1.801 0 0 1.801 0 4v6h42v20c0 1.125-.875 2-2 2H24.984c.01-.171.016-.342.016-.514a9.494 9.494 0 0 0-5.27-8.494A5.46 5.46 0 0 0 21 19.49c0-3.02-2.472-5.49-5.494-5.49s-5.496 2.47-5.496 5.49a5.46 5.46 0 0 0 1.271 3.502A9.497 9.497 0 0 0 6.025 32H4c-1.125 0-2-.875-2-2V12H0v18c0 2.199 1.801 4 4 4h36c2.199 0 4-1.801 4-4V4c0-2.199-1.801-4-4-4zm0 2h36c1.125 0 2 .875 2 2v4H2V4c0-1.125.875-2 2-2zm11.506 14A3.477 3.477 0 0 1 19 19.49a3.477 3.477 0 0 1-3.494 3.49 3.479 3.479 0 0 1-3.496-3.49A3.479 3.479 0 0 1 15.506 16zm-2.436 8.408a5.45 5.45 0 0 0 2.436.572c.873 0 1.7-.206 2.435-.572A7.476 7.476 0 0 1 22.981 32H8.028a7.476 7.476 0 0 1 5.041-7.592zM10 36c-1.852 0-3.419 1.282-3.867 3H0v2h6.133c.448 1.718 2.015 3 3.867 3 1.852 0 3.423-1.281 3.871-3H44v-2H13.871c-.448-1.719-2.019-3-3.871-3zm0 2c1.116 0 2 .884 2 2s-.884 2-2 2-2-.884-2-2 .884-2 2-2z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path fill-opacity=".999"
                                        d="M29.486 14.125A1 1 0 0 0 28 15v10a1 1 0 0 0 1.486.875l9-5a1 1 0 0 0 0-1.75zM30 16.701 35.94 20 30 23.3z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path d="M38 4v2h2V4zM34 4v2h2V4zM30 4v2h2V4zM26 4v2h2V4z" fill="currentColor"
                                        opacity="1" data-original="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                        <h3 class="h6 mb-3 fw-semibold">Interactive Course Materials</h3>
                        <p>Ipsum dolor sit amet consectetur adipisicing elit. Deleniti facilis ipsam incidunt distinctio
                            laboriosam.</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 text-center" data-aos="fade-up" data-aos-delay="">
                    <div class="service-item h-100 p-4 rounded-4">
                        <div class="icon mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 512.002 512.002"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <path
                                        d="M223.792 189.999H127.26h0l-79.127 45.684v-45.684l-.002-.046C26.99 188.969 10 171.373 10 149.999V50c0-22 18-40 40-40h296.774c21.999 0 40 18.001 40 40v40.344M223.792 276.319H50c-22 0-40 18.001-40 40v99.999c0 22 18 40 40 40h219.515l79.127 45.684v-45.684l.002-.046c21.141-.985 38.131-18.581 38.131-39.955v-40.344"
                                        style="fill-rule:evenodd;clip-rule:evenodd;stroke-width:20;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:22.9256;"
                                        fill-rule="evenodd" clip-rule="evenodd" fill="none" stroke="currentColor"
                                        stroke-width="20" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-miterlimit="22.9256" data-original="currentColor"></path>
                                    <path
                                        d="m70.004 99.999-.003.006M120.004 99.999l-.003.006M170.003 99.999l-.003.006M220.003 99.999l-.003.006M220 366.318l.003.006M170 366.318l.003.006M120.001 366.318l.003.006M70.001 366.318l.003.006"
                                        style="fill-rule:evenodd;clip-rule:evenodd;stroke-width:20;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:2.6131;"
                                        fill-rule="evenodd" clip-rule="evenodd" fill="none" stroke="currentColor"
                                        stroke-width="20" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-miterlimit="2.6131" data-original="currentColor"></path>
                                    <path
                                        d="M362.002 233.159h-98.824c0 54.579 44.245 98.824 98.824 98.824s98.824-44.245 98.824-98.824h-98.824z"
                                        style="fill-rule:evenodd;clip-rule:evenodd;stroke-width:20;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:22.9256;"
                                        fill-rule="evenodd" clip-rule="evenodd" fill="none" stroke="currentColor"
                                        stroke-width="20" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-miterlimit="22.9256" data-original="currentColor"></path>
                                    <path
                                        d="M280.965 289.186c23.257-7.658 52.343-11.84 82.35-11.84 28.554 0 56.318 3.787 79.024 10.781"
                                        style="fill-rule:evenodd;clip-rule:evenodd;stroke-width:20;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:22.9256;"
                                        fill-rule="evenodd" clip-rule="evenodd" fill="none" stroke="currentColor"
                                        stroke-width="20" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-miterlimit="22.9256" data-original="currentColor"></path>
                                    <path
                                        d="M488.539 173.257a139.993 139.993 0 0 1 13.463 59.902c0 77.32-62.68 140-140 140s-140-62.68-140-140 62.68-140 140-140a140 140 0 0 1 65.019 16.014"
                                        style="fill-rule:evenodd;clip-rule:evenodd;stroke-width:20;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:22.9256;"
                                        fill-rule="evenodd" clip-rule="evenodd" fill="none" stroke="currentColor"
                                        stroke-width="20" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-miterlimit="22.9256" data-original="currentColor"></path>
                                    <path d="m400.529 183.227-.003.005M323.478 183.227l-.003.005M462.991 136.206h.007"
                                        style="fill-rule:evenodd;clip-rule:evenodd;stroke-width:20;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:2.6131;"
                                        fill-rule="evenodd" clip-rule="evenodd" fill="none" stroke="currentColor"
                                        stroke-width="20" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-miterlimit="2.6131" data-original="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                        <h3 class="h6 mb-3 fw-semibold">Real-Time Support & Mentorship</h3>
                        <p>Ipsum dolor sit amet consectetur adipisicing elit. Deleniti facilis ipsam incidunt distinctio
                            laboriosam.</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-item h-100 p-4 rounded-4">
                        <div class="icon mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 682.667 682.667"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <defs>
                                        <clippath id="a" clippathunits="userSpaceOnUse">
                                            <path d="M0 512h512V0H0Z" fill="currentColor" opacity="1"
                                                data-original="currentColor"></path>
                                        </clippath>
                                    </defs>
                                    <g clip-path="url(#a)" transform="matrix(1.33333 0 0 -1.33333 0 682.667)">
                                        <path d="M0 0v-57.443c0-8.29 6.72-15 15-15h289.12"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(7.5 153.323)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path
                                            d="M0 0h109.12c8.28 0 15 6.71 15 15v349.01c0 8.29-6.72 15-15 15h-467c-8.28 0-15-6.71-15-15V107.443"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(380.38 80.88)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0h-260.167c0-13.81-11.19-25-25-25v-249.01c13.81 0 25-11.2 25-25h239.12"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(332.667 419.89)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0h59.12c0 13.8 11.19 25 25 25v249.01c-13.81 0-25 11.19-25 25h-71.833"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(380.38 120.88)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path
                                            d="M0 0c0-32.548-26.385-58.934-58.933-58.934S-117.867-32.548-117.867 0s26.386 58.933 58.934 58.933S0 32.548 0 0Z"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(217.391 315.959)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0c-.93 2.88-5.9 19.83 4.29 36.03C16.35 55.21 38.63 55.88 40.24 55.9"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(177.148 260.058)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0c1.61-.021 23.89-.69 35.95-19.87 10.19-16.2 5.22-33.151 4.29-36.03"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(99.528 315.958)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path
                                            d="M0 0c-17.032 2.612-35.9 4.526-58.423 4.463-21.614-.062-42.161-1.931-58.669-4.463"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(216.88 315.959)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0h162.951"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(252.766 374.892)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0h162.951"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(252.766 344.892)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0h87.951"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(252.766 314.892)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0h123.242"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(99.524 221.057)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0h123.242"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(99.524 191.057)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path d="M0 0h78.242"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(99.524 161.057)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path
                                            d="M0 0v-90.63c0-3.76-4.21-5.99-7.32-3.87l-24.42 16.641a4.707 4.707 0 0 1-5.28 0L-61.44-94.5c-3.11-2.12-7.32.11-7.32 3.87V0"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(380.38 147.43)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path
                                            d="M0 0c0-9.622-12.836-16.622-16.306-24.989-3.596-8.673.395-22.667-6.142-29.203-6.536-6.537-20.53-2.546-29.203-6.143-8.367-3.469-15.367-16.305-24.989-16.305-9.621 0-16.621 12.836-24.988 16.305-8.673 3.597-22.668-.394-29.204 6.143-6.536 6.536-2.545 20.53-6.142 29.204-3.469 8.366-16.305 15.366-16.305 24.988 0 9.621 12.836 16.621 16.305 24.988 3.597 8.673-.394 22.668 6.142 29.204 6.537 6.537 20.531 2.545 29.204 6.142 8.367 3.47 15.367 16.306 24.988 16.306 9.622 0 16.622-12.836 24.989-16.306 8.673-3.597 22.668.395 29.204-6.143 6.536-6.536 2.545-20.53 6.141-29.203C-12.836 16.621 0 9.621 0 0Z"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(422.64 206.057)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                        <path
                                            d="M0 0c0-16.487-13.365-29.853-29.852-29.853-16.487 0-29.852 13.366-29.852 29.853 0 16.486 13.365 29.852 29.852 29.852C-13.365 29.852 0 16.486 0 0Z"
                                            style="stroke-width:15;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(375.852 206.057)" fill="none" stroke="currentColor"
                                            stroke-width="15" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                            data-original="currentColor"></path>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <h3 class="h6 mb-3 fw-semibold">Certification & Career Support</h3>
                        <p>Ipsum dolor sit amet consectetur adipisicing elit. Deleniti facilis ipsam incidunt distinctio
                            laboriosam.</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-item h-100 p-4 rounded-4">
                        <div class="icon mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 64 64"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <path
                                        d="M63 24a1 1 0 0 0-1 1v19H2V15c0-.551.448-1 1-1h29a1 1 0 1 0 0-2H3c-1.654 0-3 1.346-3 3v34c0 1.654 1.346 3 3 3h21.867l-.75 6H19c-1.654 0-3 1.346-3 3v2a1 1 0 0 0 1 1h30a1 1 0 0 0 1-1v-2c0-1.654-1.346-3-3-3h-5.117l-.75-6H61c1.654 0 3-1.346 3-3V25a1 1 0 0 0-1-1zM46 61v1H18v-1c0-.551.448-1 1-1h26c.552 0 1 .449 1 1zm-8.133-3H26.133l.75-6h10.234zM61 50H3c-.552 0-1-.449-1-1v-3h60v3c0 .551-.448 1-1 1z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="M7.648 32.937a1.004 1.004 0 0 0 1.288-.585l.507-1.351h3.114l.507 1.351a1 1 0 0 0 1.874-.703l-3-8a1 1 0 0 0-1.874 0l-3 8a1.002 1.002 0 0 0 .585 1.288zM11 26.849l.807 2.152h-1.614zM17 33a1 1 0 0 0 1-1v-8a1 1 0 1 0-2 0v8a1 1 0 0 0 1 1zM49 9c-3.309 0-6 2.691-6 6s2.691 6 6 6 6-2.691 6-6-2.691-6-6-6zm0 10c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4-1.794 4-4 4z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="m63.1 11.005-3.589-.359 2.284-2.791a1 1 0 0 0-.067-1.34l-4.242-4.243a1 1 0 0 0-1.34-.067l-2.792 2.284-.358-3.588a1 1 0 0 0-.995-.9h-6a1 1 0 0 0-.995.9l-.358 3.588-2.792-2.284a1 1 0 0 0-1.34.067l-4.242 4.243a1 1 0 0 0-.067 1.34l2.284 2.791-3.589.359a1 1 0 0 0-.9.995v6a1 1 0 0 0 .9.995l3.589.359-2.284 2.791a1 1 0 0 0 .067 1.34l4.242 4.243a1 1 0 0 0 1.34.067l2.792-2.284.358 3.588a1 1 0 0 0 .995.9h6a1 1 0 0 0 .995-.9l.358-3.588 2.792 2.284a1 1 0 0 0 1.34-.067l4.242-4.243a1 1 0 0 0 .067-1.34l-2.284-2.791 3.589-.359A1 1 0 0 0 64 18v-6a1 1 0 0 0-.9-.995zm-1.1 6.09-4.543.454a1 1 0 0 0-.675 1.628l2.892 3.533-2.963 2.963-3.534-2.891a1.001 1.001 0 0 0-1.628.674l-.454 4.543h-4.189l-.454-4.543a1.001 1.001 0 0 0-1.628-.674l-3.534 2.891-2.963-2.963 2.892-3.533a1 1 0 0 0-.675-1.628l-4.543-.454v-4.19l4.543-.454a1 1 0 0 0 .675-1.628L38.327 7.29l2.963-2.963 3.534 2.891a1 1 0 0 0 1.628-.674l.454-4.543h4.189l.454 4.543a1.001 1.001 0 0 0 1.628.674l3.534-2.891 2.963 2.963-2.892 3.533a1 1 0 0 0 .675 1.628l4.543.454zM30.184 30A2.996 2.996 0 0 0 33 32c1.654 0 3-1.346 3-3s-1.346-3-3-3a2.996 2.996 0 0 0-2.816 2H22v-2h6a1 1 0 0 0 1-1v-4.184A2.996 2.996 0 0 0 31 18c0-1.654-1.346-3-3-3s-3 1.346-3 3c0 1.302.839 2.402 2 2.816V24h-5v-3a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-3h5v3.184A2.996 2.996 0 0 0 25 40c0 1.654 1.346 3 3 3s3-1.346 3-3a2.996 2.996 0 0 0-2-2.816V33a1 1 0 0 0-1-1h-6v-2zM33 28a1.001 1.001 0 1 1-1 1c0-.551.448-1 1-1zm-5-11a1.001 1.001 0 1 1-1 1c0-.551.448-1 1-1zm-8 19H6V22h14zm8 5a1.001 1.001 0 0 1 0-2 1.001 1.001 0 0 1 0 2zM41 34h12v2H41zM55 34h2v2h-2zM45 38h12v2H45zM41 38h2v2h-2z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                        <h3 class="h6 mb-3 fw-semibold"> Smart Learning Tools</h3>
                        <p>Ipsum dolor sit amet consectetur adipisicing elit. Deleniti facilis ipsam incidunt distinctio
                            laboriosam.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Services-->

    <!-- ======= Testimonials =======-->
    <div class="section testimonials__v8">
        <div class="container">
            <div class="row">
                <div class="col-md-12" data-aos="fade-up">
                    <div class="blockquote-wrap rounded-4">
                        <div class="blockquote-inner">
                            <blockquote>
                                <div class="quote">
                                    <svg viewbox="0 0 123 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M44.9346 95.8984H0.753906V64.2959C0.753906 51.5081 1.85449 41.4456 4.05566 34.1084C6.36165 26.6663 10.5544 20.0104 16.6338 14.1406C22.7132 8.27083 30.4697 3.65885 39.9033 0.304688L48.5508 18.543C39.7461 21.4779 33.4046 25.5658 29.5264 30.8066C25.7529 36.0475 23.7614 43.0179 23.5518 51.7178H44.9346V95.8984ZM118.674 95.8984H74.4932V64.2959C74.4932 51.4033 75.5938 41.2884 77.7949 33.9512C80.1009 26.6139 84.2936 20.0104 90.373 14.1406C96.5573 8.27083 104.314 3.65885 113.643 0.304688L122.29 18.543C113.485 21.4779 107.144 25.5658 103.266 30.8066C99.4922 36.0475 97.5007 43.0179 97.291 51.7178H118.674V95.8984Z"
                                            fill="#ED671F"></path>
                                    </svg>
                                </div>
                                <p class="fs-5 mb-4 fw-bold">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                    Aperiam excepturi dignissimos ea quisquam, magnam fugiat facere accusantium, nesciunt
                                    itaque neque ad facilis quam corporis accusamus aliquam, repellendus alias veritatis
                                    dolorem?</p>
                                <p class="text-white"> <strong class="d-block text-white"> Stefanni
                                        Stewart</strong><span>Science Professor</span></p>
                            </blockquote>
                        </div><img class="img-person img-fluid" src="assets/inicio/images/freepik-06-png-min.png"
                            alt=" image placeholder">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Testimonials-->

    <!-- ======= Top Categories =======-->
    <div class="section features__v10-a" id="features">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-12" data-aos="fade-up" data-aos-delay="0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold fs-5">Popular Categories</h2>
                            <p>Lorem ipsum dolor sit.</p>
                        </div>
                        <div><a class="btn btn-primary" href="#">Show More Categories</a></div>
                    </div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0"><a
                        class="d-flex gap-3 align-items-start feature" href="#"><span class="icon"><i
                                class="bi bi-display"></i></span>
                        <div>
                            <h3 class="h6 fw-bold mb-0">Web Development </h3><span class="number-total">(1,220
                                courses)</span>
                        </div>
                    </a></div>
                <div class="col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100"> <a
                        class="d-flex gap-3 align-items-start feature" href="#"><span class="icon"><i
                                class="bi bi-phone"></i></span>
                        <div>
                            <h3 class="h6 fw-bold mb-0">Mobile App Development</h3><span class="number-total">(892
                                courses)</span>
                        </div>
                    </a></div>
                <div class="col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200"> <a
                        class="d-flex gap-3 align-items-start feature" href="#"><span class="icon"><i
                                class="bi bi-database"></i></span>
                        <div>
                            <h3 class="h6 fw-bold mb-0">Database Management </h3><span class="number-total">(520
                                courses)</span>
                        </div>
                    </a></div>
                <div class="col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300"> <a
                        class="d-flex gap-3 align-items-start feature" href="#"><span class="icon"><i
                                class="bi bi-palette2"></i></span>
                        <div>
                            <h3 class="h6 fw-bold mb-0">UI/UX Design</h3><span class="number-total">(1,899
                                courses)</span>
                        </div>
                    </a></div>
                <div class="col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0"> <a
                        class="d-flex gap-3 align-items-start feature" href="#"><span class="icon"><i
                                class="bi bi-feather"></i></span>
                        <div>
                            <h3 class="h6 fw-bold mb-0">Graphic Design</h3><span class="number-total">(2,012
                                courses)</span>
                        </div>
                    </a></div>
                <div class="col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100"> <a
                        class="d-flex gap-3 align-items-start feature" href="#"><span class="icon"><i
                                class="bi bi-bag"></i></span>
                        <div>
                            <h3 class="h6 fw-bold mb-0">E-commerce</h3><span class="number-total">(920 courses)</span>
                        </div>
                    </a></div>
                <div class="col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200"> <a
                        class="d-flex gap-3 align-items-start feature" href="#"><span class="icon"><i
                                class="bi bi-pen"></i></span>
                        <div>
                            <h3 class="h6 fw-bold mb-0">Copywriting</h3><span class="number-total">(299 courses)</span>
                        </div>
                    </a></div>
                <div class="col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300"> <a
                        class="d-flex gap-3 align-items-start feature" href="#"><span class="icon"><i
                                class="bi bi-envelope"></i></span>
                        <div>
                            <h3 class="h6 fw-bold mb-0">Email Marketing</h3><span class="number-total">(288
                                courses)</span>
                        </div>
                    </a></div>
                <div class="col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300"> <a
                        class="d-flex gap-3 align-items-start feature" href="#"><span class="icon"><i
                                class="bi bi-camera-video"></i></span>
                        <div>
                            <h3 class="h6 fw-bold mb-0">Video Editing</h3><span class="number-total">(772
                                courses)</span>
                        </div>
                    </a></div>
            </div>
        </div>
    </div>
    <!-- End Features-->

    <!-- ======= Team =======-->
    <div class="section team__v4" id="team">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-7 mx-auto text-center" data-aos="fade-up">
                    <h2 class="fw-bold mb-4">Teachers</h2>
                    <p>Necessitatibus eos cumque adipisci aut iusto fugit culpa, voluptatibus explicabo labore delectus ab
                        tempora magnam reiciendis.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="card-item text-center rounded-5 pb-5 overflow-hidden">
                        <div class="img mb-5 px-5 pt-4"><img class="img-fluid rounded-circle"
                                src="assets/inicio/images/person-sq-1-min.jpg" alt=" image placeholder"></div>
                        <div class="info px-5 pt-5 mb-4">
                            <h3 class="fs-6 fw-bold mb-2">Dr. Emma Johnson</h3><span
                                class="title text-sm lh-sm">Professor of Computer Science</span>
                        </div>
                        <div class="social px-2 d-inline-flex gap-1"><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-facebook"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-twitter-x"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-instagram"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-linkedin"></i></a></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-item text-center rounded-5 pb-5 overflow-hidden">
                        <div class="img mb-5 px-5 pt-4"><img class="img-fluid rounded-circle"
                                src="assets/inicio/images/person-sq-2-min.jpg" alt=" image placeholder"></div>
                        <div class="info px-5 pt-5 mb-4">
                            <h3 class="fs-6 fw-bold mb-2">Mr. Liam Wilson</h3><span class="title text-sm lh-sm">Senior
                                Mathematics Instructor</span>
                        </div>
                        <div class="social px-2 d-inline-flex gap-1"><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-facebook"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-twitter-x"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-instagram"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-linkedin"></i></a></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-item text-center rounded-5 pb-5 overflow-hidden">
                        <div class="img mb-5 px-5 pt-4"><img class="img-fluid rounded-circle"
                                src="assets/inicio/images/person-sq-3-min.jpg" alt=" image placeholder"></div>
                        <div class="info px-5 pt-5 mb-4">
                            <h3 class="fs-6 fw-bold mb-2">Ms. Olivia Smith</h3><span class="title text-sm lh-sm">English
                                Language Specialist</span>
                        </div>
                        <div class="social px-2 d-inline-flex gap-1"><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-facebook"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-twitter-x"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-instagram"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-linkedin"></i></a></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-item text-center rounded-5 pb-5 overflow-hidden">
                        <div class="img mb-5 px-5 pt-4"><img class="img-fluid rounded-circle"
                                src="assets/inicio/images/person-sq-4-min.jpg" alt=" image placeholder"></div>
                        <div class="info px-5 pt-5 mb-4">
                            <h3 class="fs-6 fw-bold mb-2">Mrs. Sarah Miller</h3><span
                                class="title text-sm lh-sm">Business & Marketing Expert</span>
                        </div>
                        <div class="social px-2 d-inline-flex gap-1"><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-facebook"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-twitter-x"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-instagram"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-linkedin"></i></a></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="card-item text-center rounded-5 pb-5 overflow-hidden">
                        <div class="img mb-5 px-5 pt-4"><img class="img-fluid rounded-circle"
                                src="assets/inicio/images/person-sq-5-min.jpg" alt=" image placeholder"></div>
                        <div class="info px-5 pt-5 mb-4">
                            <h3 class="fs-6 fw-bold mb-2">Prof. James Anderson</h3><span
                                class="title text-sm lh-sm">Certified Science Educator</span>
                        </div>
                        <div class="social px-2 d-inline-flex gap-1"><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-facebook"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-twitter-x"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-instagram"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-linkedin"></i></a></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-item text-center rounded-5 pb-5 overflow-hidden">
                        <div class="img mb-5 px-5 pt-4"><img class="img-fluid rounded-circle"
                                src="assets/inicio/images/person-sq-6-min.jpg" alt=" image placeholder"></div>
                        <div class="info px-5 pt-5 mb-4">
                            <h3 class="fs-6 fw-bold mb-2">Ms. Sophia Brown</h3><span class="title text-sm lh-sm">Lead
                                Graphic Design Trainer</span>
                        </div>
                        <div class="social px-2 d-inline-flex gap-1"><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-facebook"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-twitter-x"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-instagram"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-linkedin"></i></a></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-item text-center rounded-5 pb-5 overflow-hidden">
                        <div class="img mb-5 px-5 pt-4"><img class="img-fluid rounded-circle"
                                src="assets/inicio/images/person-sq-7-min.jpg" alt=" image placeholder"></div>
                        <div class="info px-5 pt-5 mb-4">
                            <h3 class="fs-6 fw-bold mb-2">Mr. Robert Anderson</h3><span
                                class="title text-sm lh-sm">Health & Wellness Coach</span>
                        </div>
                        <div class="social px-2 d-inline-flex gap-1"><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-facebook"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-twitter-x"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-instagram"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-linkedin"></i></a></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-item text-center rounded-5 pb-5 overflow-hidden">
                        <div class="img mb-5 px-5 pt-4"><img class="img-fluid rounded-circle"
                                src="assets/inicio/images/person-sq-8-min.jpg" alt=" image placeholder"></div>
                        <div class="info px-5 pt-5 mb-4">
                            <h3 class="fs-6 fw-bold mb-2">Ms. Emily Carter</h3><span
                                class="title text-sm lh-sm">Engineering & Robotics Mentor</span>
                        </div>
                        <div class="social px-2 d-inline-flex gap-1"><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-facebook"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-twitter-x"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-instagram"></i></a><a
                                class="d-flex rounded-circle justify-content-center align-items-center" href="#">
                                <i class="bi bi-linkedin"></i></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Team-->

    <!-- ======= Testimonials =======-->
    <div class="section section-py-2x testimonials__v7" id="testimonials">
        <div class="container">
            <div class="row justify-content-between" data-aos="fade-up" data-aos-delay="0">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <h2 class="fs-4 mb-4 fw-bold">What our students are saying...</h2>
                    <p class="mb-4">Ipsum dolor sit amet consectetur adipisicing elit. Velit ipsam assumenda similique
                        optio enim.</p>
                    <div class="counter d-flex gap-5">
                        <div>
                            <h2 class="fs-4 fw-bold"><span class="purecounter" data-purecounter-start="0"
                                    data-purecounter-end="13" data-purecounter-duration="2">0</span><span>M+</span></h2>
                            <p>Happy students</p>
                        </div>
                        <div>
                            <h2 class="fs-4 fw-bold d-flex"><span class="purecounter" data-purecounter-start="0"
                                    data-purecounter-end="4" data-purecounter-duration="2">0</span><span
                                    class="d-flex align-items-center"><span>.88 </span><span class="px-1"> </span><i
                                        class="fs-6 bi bi-star-fill"></i><i class="fs-6 bi bi-star-fill"></i><i
                                        class="fs-6 bi bi-star-fill"></i><i class="fs-6 bi bi-star-fill"></i><i
                                        class="fs-6 bi bi-star-half"></i></span></h2>
                            <p>Overall rating</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="swiper swiperTestimonialsV7 mb-4">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="testimonial">
                                    <div class="author d-flex align-items-center gap-3 mb-3"><img
                                            class="img-fluid quote position-absolute"
                                            src="assets/inicio/images/quote-3.svg" alt=" image placeholcer">
                                        <div class="img"> <img class="rounded-circle img-fluid"
                                                src="assets/inicio/images/person-sq-2-min.jpg" alt=" image placeholder">
                                        </div>
                                        <div class="text">
                                            <h3 class="fs-5 fw-bold mb-0">Liam Wilson</h3>
                                            <p class="mb-0">Lawyer student</p>
                                        </div>
                                    </div>
                                    <div class="quote">
                                        <p>&ldquo;Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores quia
                                            quidem dicta. Sit quam commodi totam quae fugiat optio repudiandae quasi dolores
                                            repellendus veniam quisquam ipsa voluptatem, beatae dignissimos q uaerat&rdquo;
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial">
                                    <div class="author d-flex align-items-center gap-3 mb-3"><img
                                            class="img-fluid quote position-absolute"
                                            src="assets/inicio/images/quote-3.svg" alt=" image placeholcer">
                                        <div class="img"> <img class="rounded-circle img-fluid"
                                                src="assets/inicio/images/person-sq-3-min.jpg" alt=" image placeholder">
                                        </div>
                                        <div class="text">
                                            <h3 class="fs-5 fw-bold mb-0">Olivia Smith</h3>
                                            <p class="mb-0">Accounting student</p>
                                        </div>
                                    </div>
                                    <div class="quote">
                                        <p>&ldquo;Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores quia
                                            quidem dicta. Sit quam commodi totam quae fugiat optio repudiandae quasi dolores
                                            repellendus veniam quisquam ipsa voluptatem, beatae dignissimos q uaerat&rdquo;
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial">
                                    <div class="author d-flex align-items-center gap-3 mb-3"><img
                                            class="img-fluid quote position-absolute"
                                            src="assets/inicio/images/quote-3.svg" alt=" image placeholcer">
                                        <div class="img"> <img class="rounded-circle img-fluid"
                                                src="assets/inicio/images/person-sq-4-min.jpg" alt=" image placeholder">
                                        </div>
                                        <div class="text">
                                            <h3 class="fs-5 fw-bold mb-0">Sarah Miller</h3>
                                            <p class="mb-0">Web Developer student</p>
                                        </div>
                                    </div>
                                    <div class="quote">
                                        <p>&ldquo;Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores quia
                                            quidem dicta. Sit quam commodi totam quae fugiat optio repudiandae quasi dolores
                                            repellendus veniam quisquam ipsa voluptatem, beatae dignissimos q uaerat&rdquo;
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial">
                                    <div class="author d-flex align-items-center gap-3 mb-3"><img
                                            class="img-fluid quote position-absolute"
                                            src="assets/inicio/images/quote-3.svg" alt=" image placeholcer">
                                        <div class="img"> <img class="rounded-circle img-fluid"
                                                src="assets/inicio/images/person-sq-5-min.jpg" alt=" image placeholder">
                                        </div>
                                        <div class="text">
                                            <h3 class="fs-5 fw-bold mb-0">James Anderson</h3>
                                            <p class="mb-0">Front-end Developer student</p>
                                        </div>
                                    </div>
                                    <div class="quote">
                                        <p>&ldquo;Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores quia
                                            quidem dicta. Sit quam commodi totam quae fugiat optio repudiandae quasi dolores
                                            repellendus veniam quisquam ipsa voluptatem, beatae dignissimos q uaerat&rdquo;
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial">
                                    <div class="author d-flex align-items-center gap-3 mb-3"><img
                                            class="img-fluid quote position-absolute"
                                            src="assets/inicio/images/quote-3.svg" alt=" image placeholcer">
                                        <div class="img"> <img class="rounded-circle img-fluid"
                                                src="assets/inicio/images/person-sq-6-min.jpg" alt=" image placeholder">
                                        </div>
                                        <div class="text">
                                            <h3 class="fs-5 fw-bold mb-0">Sophia Brown</h3>
                                            <p class="mb-0">Marketing student</p>
                                        </div>
                                    </div>
                                    <div class="quote">
                                        <p>&ldquo;Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores quia
                                            quidem dicta. Sit quam commodi totam quae fugiat optio repudiandae quasi dolores
                                            repellendus veniam quisquam ipsa voluptatem, beatae dignissimos q uaerat&rdquo;
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 align-items-center"><a
                            class="testimonials__v7-prev d-flex align-items-center justify-content-center rounded-3 prev"
                            href="#"> <i class="bi bi-chevron-left"></i></a>
                        <div class="d-flex gap-3 slide-count justify-content-center">
                            <div class="d-flex gap-2"><span id="current-slide">0</span><span>&mdash;</span><span
                                    id="total-slides">0</span></div>
                        </div><a
                            class="testimonials__v7-next d-flex align-items-center justify-content-center rounded-3 next"
                            href="#"> <i class="bi bi-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Testimonials-->

    <!-- ======= Pricing =======-->
    <div class="section pricing__v3" id="pricing">
        <div class="container py-5">
            <div class="row mb-5">
                <div class="col-md-7 mx-auto text-center" data-aos="fade-up">
                    <h2 class="fw-bold mb-4">Simple, transparent pricing</h2>
                    <p>Necessitatibus eos cumque adipisci aut iusto fugit culpa, voluptatibus explicabo labore delectus ab
                        tempora magnam reiciendis.</p>
                </div>
            </div>
            <div class="row text-start">
                <!-- Basic Plan-->
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="pricing h-100 d-flex justify-content-between flex-column">
                        <div>
                            <div class="mb-4">
                                <div
                                    class="icon-svg rounded-circle mb-4 d-inline-flex justify-content-center align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 512 512"
                                        style="enable-background:new 0 0 512 512" xml:space="preserve">
                                        <g>
                                            <path
                                                d="M324.95 2.264H30.973C14.998 2.264 2 15.262 2 31.238V481.29c0 15.976 12.998 28.974 28.974 28.974h293.975c15.976 0 28.974-12.998 28.974-28.974V31.238c0-15.976-12.998-28.974-28.974-28.974zM18 481.29V31.238c0-7.154 5.82-12.974 12.974-12.974h31.755v476H30.974c-7.154 0-12.974-5.82-12.974-12.974zm319.923 0c0 7.154-5.82 12.974-12.974 12.974H78.73v-476h246.22c7.154 0 12.974 5.82 12.974 12.974zM488.146 61.346h-15.46V31.238c0-15.976-12.998-28.974-28.975-28.974h-22.323c-15.976 0-28.974 12.998-28.974 28.974v402.181a59.37 59.37 0 0 0 14.765 39.191l17.37 19.783v9.87a8 8 0 1 0 16 0v-9.87l17.371-19.783a59.37 59.37 0 0 0 14.765-39.19V77.345h15.46A5.861 5.861 0 0 1 494 83.2v78.98a8 8 0 1 0 16 0V83.2c0-12.05-9.804-21.854-21.854-21.854zm-66.758-43.082h22.323c7.154 0 12.974 5.82 12.974 12.974v30.108h-48.27V31.238c0-7.154 5.82-12.974 12.973-12.974zm35.297 415.155a43.377 43.377 0 0 1-10.787 28.634l-13.348 15.201-13.349-15.201a43.375 43.375 0 0 1-10.787-28.634V77.346h48.271z"
                                                fill="currentColor" opacity="1" data-original="currentColor">
                                            </path>
                                        </g>
                                    </svg>
                                </div>
                                <h4 class="mb-2 fs-6 fw-bold">Basic</h4>
                                <h2 class="fs-4 pricing-card-title fw-bold">$9 <small class="text-muted">/ mo</small>
                                </h2>
                            </div>
                            <div class="pricing-body">
                                <ul class="list-unstyled mt-3 mb-4 d-flex flex-column gap-3">
                                    <li class="d-flex gap-2 align-items-center"><span
                                            class="icon d-flex align-items-center justify-content-center rounded-circle"><i
                                                class="bi bi-check"></i></span><span class="mt-1">10 Courses</span>
                                    </li>
                                    <li class="d-flex gap-2 align-items-center"><span
                                            class="icon danger d-flex align-items-center justify-content-center rounded-circle"><i
                                                class="bi bi-x"></i></span><span class="mt-1">Live Sessions</span>
                                    </li>
                                    <li class="d-flex gap-2 align-items-center"><span
                                            class="icon danger d-flex align-items-center justify-content-center rounded-circle"><i
                                                class="bi bi-x"></i></span><span class="mt-1">Certifications</span>
                                    </li>
                                    <li class="d-flex gap-2 align-items-center"><span
                                            class="icon danger d-flex align-items-center justify-content-center rounded-circle"><i
                                                class="bi bi-x"></i></span><span class="mt-1">Community Access</span>
                                    </li>
                                    <li class="d-flex gap-2 align-items-center"><span
                                            class="icon danger d-flex align-items-center justify-content-center rounded-circle"><i
                                                class="bi bi-x"></i></span><span class="mt-1">Email Support</span>
                                    </li>
                                </ul>
                            </div>
                        </div><a class="btn btn-white-outline" href="#">Get Started</a>
                    </div>
                </div>
                <!-- Standard Plan-->
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="pricing h-100 d-flex justify-content-between flex-column popular">
                        <div class="ribbon"><span>Popular</span></div>
                        <div>
                            <div class="mb-4">
                                <div
                                    class="icon-svg rounded-circle mb-4 d-inline-flex justify-content-center align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 512 512"
                                        style="enable-background:new 0 0 512 512" xml:space="preserve">
                                        <g>
                                            <g fill-rule="evenodd" clip-rule="evenodd">
                                                <path
                                                    d="M456.544 390.259v60.277c0 8.901-3.644 16.997-9.513 22.866l-.022.023c-5.869 5.867-13.965 9.514-22.866 9.514h-244.89v-11.511h244.89c5.749 0 10.978-2.348 14.763-6.127 3.78-3.786 6.129-9.016 6.129-14.765v-35.553c-5.648 4.784-12.949 7.678-20.892 7.678H314.447v-11.509h109.695c5.739 0 10.964-2.354 14.751-6.142 3.786-3.786 6.141-9.011 6.141-14.75h11.51zM158.929 482.94h-14.552l7.276-8.342zm-34.877 0v-11.511H91.35c-6.702 0-12.799-2.745-17.22-7.165l-.287-.267c-4.25-4.384-6.879-10.37-6.879-16.951 0-6.7 2.745-12.797 7.165-17.219 4.42-4.42 10.518-7.165 17.22-7.165h32.702v-5.754h55.201v5.754h96.886v-11.509H91.35c-9.877 0-18.853 4.037-25.354 10.54-6.503 6.501-10.54 15.477-10.54 25.354 0 9.684 3.903 18.518 10.214 25.001l.325.354c6.501 6.501 15.477 10.54 25.354 10.54h32.703zm178.793-60.279v-11.509h-15.104v11.509z"
                                                    fill="currentColor" opacity="1" data-original="currentColor">
                                                </path>
                                                <path
                                                    d="M87.857 0h336.286c8.914 0 17.018 3.646 22.886 9.515l.332.36c5.677 5.851 9.183 13.807 9.183 22.526v357.858c0 8.914-3.644 17.018-9.515 22.887-5.869 5.869-13.972 9.515-22.886 9.515H314.447v-11.509h109.695c5.739 0 10.964-2.354 14.751-6.142 3.786-3.786 6.141-9.011 6.141-14.75V32.401a20.814 20.814 0 0 0-5.86-14.489l-.281-.26c-3.788-3.788-9.012-6.143-14.751-6.143H87.857a20.818 20.818 0 0 0-14.751 6.119l-.022.024a20.809 20.809 0 0 0-6.119 14.749v76.345H55.456V32.401c0-8.887 3.645-16.984 9.515-22.863l.036-.037C70.885 3.639 78.976 0 87.857 0zm214.988 422.661v-11.509h-15.104v11.509zm-26.706 0v-11.509H91.35c-9.393 0-17.974 3.653-24.385 9.604V154.064H55.456v292.981h11.509c0-6.7 2.745-12.797 7.165-17.219 4.42-4.42 10.518-7.165 17.22-7.165zM55.456 138.689h11.509v-14.568H55.456z"
                                                    fill="currentColor" opacity="1" data-original="currentColor">
                                                </path>
                                                <path
                                                    d="M174.892 69.356H337.11c6.24 0 11.911 2.551 16.016 6.657 4.107 4.107 6.659 9.779 6.659 16.017v54.358c0 6.218-2.555 11.886-6.669 16.003-4.095 4.117-9.767 6.669-16.006 6.669H174.892c-6.239 0-11.911-2.552-16.016-6.657l-.339-.367c-3.908-4.087-6.319-9.609-6.319-15.648V92.03c0-6.239 2.551-11.911 6.658-16.017 4.105-4.106 9.777-6.657 16.016-6.657zM337.11 80.867H174.892a11.133 11.133 0 0 0-7.881 3.282 11.132 11.132 0 0 0-3.283 7.881v54.358c0 2.959 1.155 5.65 3.029 7.639l.254.238c2.024 2.024 4.817 3.284 7.881 3.284H337.11c3.064 0 5.857-1.26 7.881-3.284h.022a11.109 11.109 0 0 0 3.261-7.878V92.03c0-3.063-1.258-5.857-3.282-7.881a11.137 11.137 0 0 0-7.882-3.282zM124.052 411.152h55.202a5.755 5.755 0 0 1 5.753 5.756v89.337a5.755 5.755 0 0 1-10.389 3.41l-22.964-26.334-23.284 26.701a5.718 5.718 0 0 1-4.317 1.957V512a5.755 5.755 0 0 1-5.755-5.756v-55.329h11.509v40.006l17.532-20.099a5.736 5.736 0 0 1 8.632 0l17.528 20.098v-68.258h-43.692v16.654h-11.509v-22.408a5.755 5.755 0 0 1 5.754-5.756zM98.145 5.756h11.51v411.152h-11.51z"
                                                    fill="currentColor" opacity="1" data-original="currentColor">
                                                </path>
                                                <path
                                                    d="M180.204 99.563h151.593v11.509H180.204zM180.204 127.345h151.593v11.509H180.204z"
                                                    fill="currentColor" opacity="1" data-original="currentColor">
                                                </path>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                                <h4 class="mb-2 fs-6 fw-bold">Pro</h4>
                                <h2 class="fs-4 pricing-card-title fw-bold">$19 <small class="text-muted">/ mo</small>
                                </h2>
                            </div>
                            <div class="pricing-body">
                                <ul class="list-unstyled mt-3 mb-4 d-flex flex-column gap-3">
                                    <li class="d-flex gap-2 align-items-center"><span
                                            class="icon d-flex align-items-center justify-content-center rounded-circle"><i
                                                class="bi bi-check"></i></span><span class="mt-1">50 Courses</span>
                                    </li>
                                    <li class="d-flex gap-2 align-items-center"><span
                                            class="icon d-flex align-items-center justify-content-center rounded-circle"><i
                                                class="bi bi-check"></i></span><span class="mt-1">Live Sessions</span>
                                    </li>
                                    <li class="d-flex gap-2 align-items-center"><span
                                            class="icon d-flex align-items-center justify-content-center rounded-circle"><i
                                                class="bi bi-check"></i></span><span
                                            class="mt-1">Certifications</span></li>
                                    <li class="d-flex gap-2 align-items-center"><span
                                            class="icon d-flex align-items-center justify-content-center rounded-circle"><i
                                                class="bi bi-check"></i></span><span class="mt-1">Community
                                            Access</span></li>
                                    <li class="d-flex gap-2 align-items-center"><span
                                            class="icon danger d-flex align-items-center justify-content-center rounded-circle"><i
                                                class="bi bi-x"></i></span><span class="mt-1">Email Support</span>
                                    </li>
                                </ul>
                            </div>
                        </div><a class="btn" href="#">Get Started</a>
                    </div>
                </div>
                <!-- Premium Plan-->
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="pricing h-100 d-flex justify-content-between flex-column">
                        <div>
                            <div class="mb-4">
                                <div
                                    class="icon-svg rounded-circle mb-4 d-inline-flex justify-content-center align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 512 512"
                                        style="enable-background:new 0 0 512 512" xml:space="preserve">
                                        <g>
                                            <path
                                                d="M487 418.05h-4.655a41.05 41.05 0 0 1-13.005-30.031 41.052 41.052 0 0 1 13.004-30.03H487c6.672 0 12.949-2.602 17.674-7.327s7.326-11 7.326-17.672c0-13.785-11.215-25-25-25h-22.98a74.576 74.576 0 0 0 7.494-6.537c13.946-13.947 21.592-32.491 21.526-52.218-.125-38.017-29.732-69.355-67.345-72.919a23.416 23.416 0 0 0 1.696-8.777c0-12.919-10.507-23.43-23.421-23.43h-3.971a36.685 36.685 0 0 1-11.43-26.653 36.684 36.684 0 0 1 11.435-26.657h3.966c6.243 0 12.123-2.432 16.573-6.866 4.416-4.431 6.848-10.314 6.848-16.564 0-12.914-10.507-23.42-23.421-23.42H125.3c-40.952 0-74.368 32.868-74.489 73.265-.065 19.727 7.58 38.271 21.526 52.217a74.576 74.576 0 0 0 7.494 6.537h-30.7c-12.92 0-23.431 10.506-23.431 23.42 0 6.259 2.436 12.142 6.857 16.564 4.429 4.428 10.314 6.866 16.573 6.866h3.961a36.689 36.689 0 0 1 11.436 26.658 36.685 36.685 0 0 1-11.44 26.662h-3.956c-12.92 0-23.431 10.506-23.431 23.42 0 6.25 2.432 12.133 6.866 16.583a23.558 23.558 0 0 0 5.527 4.099C15.317 334.375.092 359.347 0 387.758c-.063 21.48 8.26 41.668 23.437 56.845 15.12 15.12 35.219 23.447 56.594 23.447H487c6.672 0 12.948-2.602 17.674-7.327 4.725-4.725 7.326-11.001 7.326-17.673 0-13.785-11.215-25-25-25zm10-85.06a9.93 9.93 0 0 1-2.934 7.067A9.93 9.93 0 0 1 487 342.99h-59.974v-20H487c5.514 0 10 4.486 10 10zM82.945 158.826c-11.101-11.1-17.186-25.86-17.134-41.564.097-32.153 26.783-58.312 59.489-58.312h278.67c4.644 0 8.421 3.777 8.421 8.42a8.396 8.396 0 0 1-2.454 5.957 8.404 8.404 0 0 1-5.967 2.473H164.24c-4.143 0-7.5 3.358-7.5 7.5s3.357 7.5 7.5 7.5h216.705a51.686 51.686 0 0 0-7.377 26.658c0 9.532 2.6 18.703 7.374 26.652H124.321c-7.229 0-14.001-2.858-19.068-8.048-5.064-5.187-7.758-12.027-7.584-19.261.345-14.337 12.588-26.001 27.291-26.001h9.28c4.143 0 7.5-3.358 7.5-7.5s-3.357-7.5-7.5-7.5h-9.28c-22.776 0-41.747 18.23-42.288 40.64-.271 11.308 3.937 21.998 11.847 30.1 7.914 8.106 18.498 12.57 29.802 12.57H403.97c4.644 0 8.421 3.782 8.421 8.43a8.367 8.367 0 0 1-2.463 5.956 8.378 8.378 0 0 1-5.958 2.474H124.321c-15.628 0-30.323-6.089-41.376-17.144zm-39.781 46.52a8.367 8.367 0 0 1-2.464-5.957c0-4.643 3.782-8.42 8.431-8.42H418.54c32.703 0 59.395 26.16 59.5 58.315.052 15.701-6.033 30.461-17.134 41.561-9.266 9.267-21.093 15.027-33.88 16.653v-17.042c8.425-1.525 16.159-5.606 22.289-11.885 7.914-8.106 12.123-18.8 11.853-30.11-.528-22.411-19.494-40.643-42.277-40.643H49.131a8.374 8.374 0 0 1-5.967-2.472zm28.987 17.474h346.74c14.705 0 26.943 11.662 27.281 25.999.174 7.239-2.521 14.084-7.59 19.275-3.258 3.337-7.222 5.704-11.556 6.972V259.38a7.5 7.5 0 0 0-7.5-7.5H271.181c-4.143 0-7.5 3.358-7.5 7.5s3.357 7.5 7.5 7.5h50.622v9.26H72.148a51.694 51.694 0 0 0 7.38-26.662 51.696 51.696 0 0 0-7.377-26.658zm264.652 44.06h75.224v106.331l-34.163-17.697a7.498 7.498 0 0 0-6.899.001l-34.161 17.696V266.88zM49.131 291.139h272.672v16.85H49.131a8.411 8.411 0 0 1-8.431-8.43c.001-4.642 3.783-8.42 8.431-8.42zm444.935 158.978A9.93 9.93 0 0 1 487 453.05H80.031c-17.368 0-33.7-6.767-45.986-19.053C21.712 421.665 14.949 405.259 15 387.804c.115-35.738 29.772-64.814 66.11-64.814h240.692v20H80.731c-24.63 0-45.139 19.707-45.719 43.932-.289 12.228 4.262 23.786 12.816 32.545s19.99 13.583 32.202 13.583h273.38c4.143 0 7.5-3.358 7.5-7.5s-3.357-7.5-7.5-7.5H80.031c-8.138 0-15.764-3.219-21.471-9.063-5.708-5.845-8.745-13.555-8.552-21.708.387-16.15 14.169-29.289 30.723-29.289h241.072v27.552a7.5 7.5 0 0 0 10.951 6.659l41.661-21.582 41.663 21.582a7.49 7.49 0 0 0 7.346-.251 7.502 7.502 0 0 0 3.603-6.408v-27.553h36.012a56.035 56.035 0 0 0-8.699 30.03 56.03 56.03 0 0 0 8.7 30.031h-79.63c-4.143 0-7.5 3.358-7.5 7.5s3.357 7.5 7.5 7.5H487c5.514 0 10 4.486 10 10a9.93 9.93 0 0 1-2.934 7.067z"
                                                fill="currentColor" opacity="1" data-original="currentColor">
                                            </path>
                                        </g>
                                    </svg>
                                </div>
                                <h4 class="mb-2 fs-6 fw-bold">Premium</h4>
                                <h2 class="fs-4 pricing-card-title fw-bold">$29 <small class="text-muted">/ mo</small>
                                </h2>
                            </div>
                            <div class="pricing-body">
                                <ul class="list-unstyled mt-3 mb-4 d-flex flex-column gap-3">
                                    <li class="d-flex gap-2 align-items-center"><span
                                            class="icon d-flex align-items-center justify-content-center rounded-circle"><i
                                                class="bi bi-check"></i></span><span>Unlimited</span></li>
                                    <li class="d-flex gap-2 align-items-center"><span
                                            class="icon d-flex align-items-center justify-content-center rounded-circle"><i
                                                class="bi bi-check"></i></span><span>Live Sessions</span></li>
                                    <li class="d-flex gap-2 align-items-center"><span
                                            class="icon d-flex align-items-center justify-content-center rounded-circle"><i
                                                class="bi bi-check"></i></span><span>Certifications</span></li>
                                    <li class="d-flex gap-2 align-items-center"><span
                                            class="icon d-flex align-items-center justify-content-center rounded-circle"><i
                                                class="bi bi-check"></i></span><span>Community Access</span></li>
                                    <li class="d-flex gap-2 align-items-center"><span
                                            class="icon d-flex align-items-center justify-content-center rounded-circle"><i
                                                class="bi bi-check"></i></span><span>Email Support</span></li>
                                </ul>
                            </div>
                        </div><a class="btn btn-white-outline" href="#">Get Started</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Pricing-->

    <!-- ======= Portfolio =======-->
    <div class="section portfolio__v1" id="portfolio">
        <div class="container">
            <div class="row mb-5 text-center">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="fw-bold" data-aos="fade-up" data-aos-delay="100">My Awesome Works</h2>
                </div>
            </div>
            <!-- Filter Buttons-->
            <div class="row mb-3">
                <div class="mb-4 filter gap-0 d-sm-flex justify-content-center" data-aos="fade-up"
                    data-aos-delay="200"><a class="filter-button active" href="#" data-filter="*">All</a><a
                        class="filter-button" href="#" data-filter=".design">Design</a><a
                        class="filter-button" href="#" data-filter=".development"> Development </a><a
                        class="filter-button" href="#" data-filter=".print">Print</a><a class="filter-button"
                        href="#" data-filter=".branding"> Branding </a></div>
            </div>
            <!-- Portfolio Grid-->
            <div class="row g-4" id="portfolio-grid" data-aos="fade-up" data-aos-delay="300">
                <div class="col-6 col-md-6 portfolio-item design"><a class="portfolio-entry"
                        href="page-portfolio-single.html"><i class="bi bi-link-45deg fs-4"></i><img
                            class="img-fluid rounded-4" src="assets/inicio/images/img-1-min.jpg"
                            alt=" Free Bootstrap Templates"></a></div>
                <div class="col-6 col-md-6 portfolio-item development"><a class="portfolio-entry glightbox"
                        href="assets/inicio/images/img-2-min.jpg"><i class="bi bi-arrows-angle-expand fs-4"></i><img
                            class="img-fluid rounded-4" src="assets/inicio/images/img-2-min.jpg"
                            alt=" Free Bootstrap Templates"></a></div>
                <div class="col-6 col-md-6 portfolio-item print"><a class="portfolio-entry glightbox"
                        href="assets/inicio/images/img-3-min.jpg"><i class="bi bi-arrows-angle-expand fs-4"></i><img
                            class="img-fluid rounded-4" src="assets/inicio/images/img-3-min.jpg"
                            alt=" Free Bootstrap Templates"></a></div>
                <div class="col-6 col-md-6 portfolio-item design"><a class="portfolio-entry glightbox"
                        href="assets/inicio/images/img-4-min.jpg"><i class="bi bi-arrows-angle-expand fs-4"></i><img
                            class="img-fluid rounded-4" src="assets/inicio/images/img-4-min.jpg"
                            alt=" Free Bootstrap Templates"></a></div>
                <div class="col-6 col-md-6 portfolio-item development"><a class="portfolio-entry glightbox"
                        href="assets/inicio/images/img-5-min.jpg"><i class="bi bi-arrows-angle-expand fs-4"></i><img
                            class="img-fluid rounded-4" src="assets/inicio/images/img-5-min.jpg"
                            alt=" Free Bootstrap Templates"></a></div>
                <div class="col-6 col-md-6 portfolio-item print"><a class="portfolio-entry glightbox"
                        href="assets/inicio/images/img-6-min.jpg"><i class="bi bi-arrows-angle-expand fs-4"></i><img
                            class="img-fluid rounded-4" src="assets/inicio/images/img-6-min.jpg"
                            alt=" Free Bootstrap Templates"></a></div>
                <div class="col-6 col-md-6 portfolio-item branding"><a class="portfolio-entry glightbox"
                        href="assets/inicio/images/img-8-min.jpg"><i class="bi bi-arrows-angle-expand fs-4"></i><img
                            class="img-fluid rounded-4" src="assets/inicio/images/img-8-min.jpg"
                            alt=" Free Bootstrap Templates"></a></div>
                <div class="col-6 col-md-6 portfolio-item print"><a class="portfolio-entry glightbox"
                        href="assets/inicio/images/img-9-min.jpg"><i class="bi bi-arrows-angle-expand fs-4"></i><img
                            class="img-fluid rounded-4" src="assets/inicio/images/img-9-min.jpg"
                            alt=" Free Bootstrap Templates"></a></div>
                <!-- Add more items as needed-->
            </div>
        </div>
    </div>
    <!-- End Portfolio-->

    <!-- ======= FAQ =======-->
    <div class="section faq__v3" id="faq">
        <div class="container" data-aos="fade-up" data-aos-delay="200">
            <div class="row">
                <div class="col-lg-5 mx-auto text-center">
                    <h2 class="fw-bold mb-4">Frequetly Asked Questions</h2>
                    <p class="mb-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas aliquid iure est!
                        Alias, quos.</p>
                </div>
            </div>
            <div class="content rounded-4 py-5 px-4">
                <div class="row">
                    <div class="col-lg-5 mb-5 mb-lg-0 order-lg-2">
                        <div class="row">
                            <div class="col-lg-11"><img class="img-fluid rounded-5 object-fit-cover faq-img"
                                    src="assets/inicio/images/freepik-04-min.jpg" alt=" image placeholder"></div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="faq-content">
                            <div class="accordion custom-accordion" id="accordionPanelsStayOpenExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                            aria-controls="panelsStayOpen-collapseOne">How do I enroll in a
                                            course?</button>
                                    </h2>
                                    <div class="accordion-collapse collapse show" id="panelsStayOpen-collapseOne">
                                        <div class="accordion-body">Lorem ipsum dolor sit amet consectetur adipisicing
                                            elit. Deleniti consectetur vel qui eius placeat temporibus nam quod iure alias.
                                            Autem veritatis tempora in laboriosam eveniet alias perferendis dolorum hic
                                            facere?</div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo"
                                            aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">What
                                            payment methods do you accept?</button>
                                    </h2>
                                    <div class="accordion-collapse collapse" id="panelsStayOpen-collapseTwo">
                                        <div class="accordion-body">Lorem ipsum dolor sit amet consectetur adipisicing
                                            elit. Deleniti consectetur vel qui eius placeat temporibus nam quod iure alias.
                                            Autem veritatis tempora in laboriosam eveniet alias perferendis dolorum hic
                                            facere?</div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree"
                                            aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">Are the
                                            courses self-paced or instructor-led?</button>
                                    </h2>
                                    <div class="accordion-collapse collapse" id="panelsStayOpen-collapseThree">
                                        <div class="accordion-body">Lorem ipsum dolor sit amet consectetur adipisicing
                                            elit. Deleniti consectetur vel qui eius placeat temporibus nam quod iure alias.
                                            Autem veritatis tempora in laboriosam eveniet alias perferendis dolorum hic
                                            facere?</div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour"
                                            aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">Do you
                                            offer certificates upon completion?</button>
                                    </h2>
                                    <div class="accordion-collapse collapse" id="panelsStayOpen-collapseFour">
                                        <div class="accordion-body">Lorem ipsum dolor sit amet consectetur adipisicing
                                            elit. Deleniti consectetur vel qui eius placeat temporibus nam quod iure alias.
                                            Autem veritatis tempora in laboriosam eveniet alias perferendis dolorum hic
                                            facere?</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End FAQ-->
        </div>
    </div>
    <!-- End FAQ-->

    <!-- ======= How it works =======-->
    <div class="section howitworks__v4" id="howitworks">
        <div class="container">
            <div class="row mb-3" data-aos="fade-up" data-aos-delay="0">
                <div class="col-lg-5 mx-auto text-center">
                    <h2 class="fw-bold mb-4">3 simple steps to get started</h2>
                    <p class="mb-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas aliquid iure est!
                        Alias, quos.</p>
                </div>
            </div>
            <div class="row g-5 mb-5">
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="step text-center">
                        <div class="curve">
                            <svg width="89" height="15" viewbox="0 0 89 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.90332 2.05957C15.5 14 58 21.5 88.3962 0.94151" stroke="#2E9FA0"
                                    stroke-width="2" stroke-dasharray="8 8"></path>
                            </svg>
                        </div>
                        <div
                            class="icon mx-auto d-inline-flex justify-content-center align-items-center rounded-circle mb-3 position-relative">
                            <span class="number">1</span>
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 32 32"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <path
                                        d="M8.689 12.535a.9.9 0 0 0 0 1.8h9.65a.9.9 0 0 0 0-1.8zM13.028 17.644H8.689a.9.9 0 0 0 0 1.8h4.339a.9.9 0 0 0 0-1.8z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="M12.895 29.1h-6.85a2.677 2.677 0 0 1-2.67-2.68V8.19a2.677 2.677 0 0 1 2.67-2.68h1.859v.226a2.383 2.383 0 0 0 2.381 2.38h8.6a2.383 2.383 0 0 0 2.381-2.38V5.51h1.85c1.497 0 2.669 1.177 2.669 2.68v5.13a.9.9 0 0 0 1.8 0V8.19a4.48 4.48 0 0 0-4.47-4.48h-1.85v-.23a2.383 2.383 0 0 0-2.381-2.38h-8.6a2.383 2.383 0 0 0-2.381 2.38v.23H6.045a4.481 4.481 0 0 0-4.471 4.48v18.23c0 2.47 2.006 4.48 4.471 4.48h6.85a.9.9 0 0 0 0-1.8zM9.705 3.48c0-.32.26-.58.58-.58h8.6c.32 0 .58.26.58.58v2.255c0 .32-.26.58-.58.58h-8.6a.58.58 0 0 1-.58-.58z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="M29.47 17.358c-1.232-1.233-3.383-1.234-4.616 0l-7.979 7.979a1.736 1.736 0 0 0-.486.942l-.438 2.589a1.742 1.742 0 0 0 2.008 2.007l2.588-.437c.359-.061.685-.229.942-.486l7.979-7.979a3.268 3.268 0 0 0 .002-4.615zm-9.222 11.306-2.521.503.422-2.557 5.719-5.719 2.071 2.071zm7.948-7.964-.986.988-2.07-2.07.987-.987a1.455 1.455 0 0 1 1.034-.429 1.464 1.464 0 0 1 1.035 2.498z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                        <h3 class="fs-5 fw-bold mb-2">Sign Up</h3>
                        <p>Necessitatibus eos cumque adipisci aut iusto fugit culpa, voluptatibus explicabo labore delectus
                            ab tempora.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="step text-center">
                        <div class="curve curve-2">
                            <svg width="90" height="16" viewbox="0 0 90 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 13.2334C15.5967 1.29297 58.0967 -6.20703 88.4928 14.3515" stroke="#2E9FA0"
                                    stroke-width="2" stroke-dasharray="8 8"></path>
                            </svg>
                        </div>
                        <div
                            class="icon mx-auto d-inline-flex justify-content-center align-items-center rounded-circle mb-3 position-relative">
                            <span class="number">2</span>
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 32 32"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <path
                                        d="M6.199 24.424h19.602a4.104 4.104 0 0 0 4.099-4.1V6.741c0-2.261-1.839-4.1-4.099-4.1H6.199a4.104 4.104 0 0 0-4.099 4.1v13.583c0 2.261 1.839 4.1 4.099 4.1zM3.9 6.741a2.302 2.302 0 0 1 2.299-2.299h19.602A2.301 2.301 0 0 1 28.1 6.741v13.583a2.302 2.302 0 0 1-2.299 2.299H6.199A2.301 2.301 0 0 1 3.9 20.324z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="M13.367 18.567a2.84 2.84 0 0 0 2.948-.202l3.553-2.512a2.846 2.846 0 0 0 1.202-2.322c0-.921-.45-1.789-1.202-2.32L16.314 8.7a2.829 2.829 0 0 0-2.948-.202 2.833 2.833 0 0 0-1.536 2.524v5.021a2.834 2.834 0 0 0 1.537 2.524zm.264-7.545c0-.397.21-.743.563-.926a1.033 1.033 0 0 1 1.081.074l3.553 2.511c.28.197.44.508.44.851s-.161.653-.441.852l-3.553 2.511a1.025 1.025 0 0 1-1.081.074 1.025 1.025 0 0 1-.563-.926v-5.021zM30 27.558H2a.9.9 0 0 0 0 1.8h28a.9.9 0 0 0 0-1.8z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                        <h3 class="fs-5 fw-bold mb-2">Start Learning</h3>
                        <p>Necessitatibus eos cumque adipisci aut iusto fugit culpa, voluptatibus explicabo labore delectus
                            ab tempora.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="step text-center">
                        <div
                            class="icon mx-auto d-inline-flex justify-content-center align-items-center rounded-circle mb-3 position-relative">
                            <span class="number">3</span>
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewbox="0 0 32 32"
                                style="enable-background:new 0 0 512 512" xml:space="preserve">
                                <g>
                                    <path
                                        d="M9.67 8.454h9.243a.9.9 0 0 0 0-1.8H9.67a.9.9 0 0 0 0 1.8zM9.67 13.347h4.155a.9.9 0 0 0 0-1.8H9.67a.9.9 0 0 0 0 1.8z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="M24.21 1.1H7.79a4.365 4.365 0 0 0-4.36 4.36v18.25a4.365 4.365 0 0 0 4.36 4.36h4.56a.9.9 0 0 0 0-1.8H7.79a2.563 2.563 0 0 1-2.56-2.56V5.46A2.562 2.562 0 0 1 7.79 2.9h16.42a2.563 2.563 0 0 1 2.56 2.56v18.25a2.543 2.543 0 0 1-1.759 2.425.9.9 0 1 0 .559 1.711 4.34 4.34 0 0 0 3.001-4.136V5.46A4.367 4.367 0 0 0 24.21 1.1z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                    <path
                                        d="M18.821 16.677a4.813 4.813 0 0 0-4.808 4.807c0 1.337.551 2.546 1.435 3.418V30a.9.9 0 0 0 1.395.752l1.979-1.298 1.979 1.298a.898.898 0 0 0 .922.039.9.9 0 0 0 .473-.792V24.9a4.792 4.792 0 0 0 1.434-3.418 4.814 4.814 0 0 0-4.809-4.805zm0 1.8c1.657 0 3.006 1.349 3.006 3.007s-1.349 3.007-3.006 3.007c-1.658 0-3.007-1.349-3.007-3.007s1.349-3.007 3.007-3.007zm1.573 9.856-1.078-.708a.904.904 0 0 0-.988 0l-1.078.708v-2.328a4.746 4.746 0 0 0 1.572.286 4.74 4.74 0 0 0 1.572-.286z"
                                        fill="currentColor" opacity="1" data-original="currentColor"></path>
                                </g>
                            </svg>
                        </div>
                        <h3 class="fs-5 fw-bold mb-2">Grow Your Skills</h3>
                        <p>Necessitatibus eos cumque adipisci aut iusto fugit culpa, voluptatibus explicabo labore delectus
                            ab tempora.</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-5 mx-auto text-center" data-aos="fade-up" data-aos-delay="300"><a class="btn"
                        href="#">Get Started</a></div>
            </div>
        </div>
    </div>
    <!-- End How it works-->

    <!-- ======= Recent Blog =======-->
    <div class="section recentblog__v3" id="blog">
        <div class="container">
            <div class="row" data-aos="fade-up" data-aos-delay="0">
                <div class="col-lg-5 mx-auto text-center">
                    <h2 class="fw-bold mb-4">Recent Blog</h2>
                    <p class="mb-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas aliquid iure est!
                        Alias, quos.</p>
                </div>
            </div>
            <div class="row mb-5 g-4">
                <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="0"> <a
                        class="blog-entry rounded-4 overflow-hidden" href="page-blog-single.html"><img
                            class="object-fit-cover w-100 img-fluid thumb" src="assets/inicio/images/img-9-min.jpg"
                            alt=" image placeholder">
                        <div class="content text-center p-4"><span class="d-inline-block mb-2 small">Business &bullet;
                                January 20, 2024</span>
                            <h2 class="fs-5 fw-bold mb-3">Simple Changes for a Greener Lifestyle</h2><span
                                class="fw-bold readmore d-inline-flex flex-row gap-2 align-items-center"><span>Read More
                                </span><i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a></div>
                <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="100"> <a
                        class="blog-entry rounded-4 overflow-hidden" href="page-blog-single.html"><img
                            class="object-fit-cover w-100 img-fluid thumb" src="assets/inicio/images/img-10-min.jpg"
                            alt=" image placeholder">
                        <div class="content text-center p-4"><span class="d-inline-block mb-2 small">Business &bullet;
                                March 10, 2024</span>
                            <h2 class="fs-5 fw-bold mb-3">Creative Hobbies to Boost Your Happiness</h2><span
                                class="fw-bold readmore d-inline-flex flex-row gap-2 align-items-center"><span>Read More
                                </span><i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a></div>
                <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="200"><a
                        class="blog-entry rounded-4 overflow-hidden" href="page-blog-single.html"><img
                            class="object-fit-cover w-100 img-fluid thumb" src="assets/inicio/images/img-11-min.jpg"
                            alt=" image placeholder">
                        <div class="content text-center p-4"><span class="d-inline-block mb-2 small">Tech &bullet; April
                                25, 2024</span>
                            <h2 class="fs-5 fw-bold mb-3">Tech Trends to Watch in 2024</h2><span
                                class="fw-bold readmore d-inline-flex flex-row gap-2 align-items-center"><span>Read More
                                </span><i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a></div>
                <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="300"><a
                        class="blog-entry rounded-4 overflow-hidden" href="page-blog-single.html"><img
                            class="object-fit-cover w-100 img-fluid thumb" src="assets/inicio/images/img-13-min.jpg"
                            alt=" image placeholder">
                        <div class="content text-center p-4"><span class="d-inline-block mb-2 small">Tech &bullet; May
                                5, 2024</span>
                            <h2 class="fs-5 fw-bold mb-3">The Art of Minimalism: Declutter Your Life</h2><span
                                class="fw-bold readmore d-inline-flex flex-row gap-2 align-items-center"><span>Read More
                                </span><i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a></div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-5 mx-auto text-center" data-aos="fade-up" data-aos-delay="300"><a class="btn"
                        href="#">View All Posts</a></div>
            </div>
        </div>
    </div>
    <!-- End Recent Blog-->

    <!-- ======= Contact =======-->
    <div class="section contact__v5" id="contact">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 mb-4" data-aos="fade-up" data-aos-delay="0">
                    <h2 class="fw-bold mb-4">Contact Us</h2>
                </div>
                <div class="col-md-6 mb-5 mb-md-0">
                    <div class="d-flex flex-column gap-5">
                        <div class="d-flex align-items-start gap-3" data-aos="fade-up" data-aos-delay="0">
                            <div class="icon d-block"><i class="bi bi-telephone fs-5"></i></div>
                            <div> <span class="d-block fw-semibold">Phone</span><span>+(01 234 567 890)</span></div>
                        </div>
                        <div class="d-flex align-items-start gap-3" data-aos="fade-up" data-aos-delay="100">
                            <div class="icon d-block"><i class="bi bi-send fs-5"></i></div>
                            <div> <span class="d-block fw-semibold">Email</span><span>info@mydomain.com</span></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="d-flex flex-column gap-5">
                        <div class="d-flex align-items-start gap-3" data-aos="fade-up" data-aos-delay="200">
                            <div class="icon d-block"><i class="bi bi-geo-alt fs-5"></i></div>
                            <div> <span class="d-block fw-semibold">Address</span>
                                <address class="mb-0">123 Main Street Apt 4B Springfield, <br> IL 62701 United States
                                </address>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3" data-aos="fade-up" data-aos-delay="300">
                            <div class="icon d-block"><i class="bi bi-globe2 fs-5"></i></div>
                            <div> <span class="d-block fw-semibold">Website</span><span>www.mywebsite.com </span></div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="social mt-4" data-aos="fade-up" data-aos-delay="300">
                        <ul class="list-unstyled d-flex gap-2 flex-row flex-wrap">
                            <li><a class="social-link d-flex justify-content-center" href="#"><i
                                        class="bi bi-twitter-x"></i></a></li>
                            <li><a class="social-link d-flex justify-content-center" href="#"><i
                                        class="bi bi-facebook"></i></a></li>
                            <li><a class="social-link d-flex justify-content-center" href="#"><i
                                        class="bi bi-youtube"></i></a></li>
                            <li><a class="social-link d-flex justify-content-center" href="#"><i
                                        class="bi bi-linkedin"></i></a></li>
                            <li><a class="social-link d-flex justify-content-center" href="#"><i
                                        class="bi bi-pinterest"></i></a></li>
                            <li><a class="social-link d-flex justify-content-center" href="#"><i
                                        class="bi bi-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="rounded-5 form-wrapper" data-aos="fade-up" data-aos-delay="300">
                        <form id="contactForm">
                            <div class="row gap-3 mb-3">
                                <div class="col-md-12">
                                    <label class="mb-2" for="name">Name</label>
                                    <input class="form-control" id="name" type="text" name="name"
                                        required="">
                                </div>
                                <div class="col-md-12">
                                    <label class="mb-2" for="email">Email</label>
                                    <input class="form-control" id="email" type="email" name="email"
                                        required="">
                                </div>
                            </div>
                            <div class="row gap-3 mb-3">
                                <div class="col-md-12">
                                    <label class="mb-2" for="subject">Subject</label>
                                    <input class="form-control" id="subject" type="text" name="subject">
                                </div>
                            </div>
                            <div class="row gap-3 gap-md-0 mb-3">
                                <div class="col-md-12">
                                    <label class="mb-2" for="message">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required=""></textarea>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100 fw-semibold" type="submit">Send Message</button>
                        </form>
                        <div class="mt-3 d-none alert alert-success" id="successMessage">Message sent successfully!
                        </div>
                        <div class="mt-3 d-none alert alert-danger" id="errorMessage">Message sending failed. Please try
                            again later.</div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- End Contact-->
@endsection
