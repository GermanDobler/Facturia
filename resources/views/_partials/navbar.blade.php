<style>
    .dropdown-item {
        white-space: normal !important;
        word-break: break-word;
    }

    /* Tamaño normal en desktop */
    .navbar-brand img {
        width: 380px;
        height: auto;
    }

    /* En tablets */
    @media (max-width: 991.98px) {
        .navbar-brand img {
            width: 250px;
        }
    }

    /* En celulares */
    @media (max-width: 575.98px) {
        .navbar-brand img {
            width: 180px;
        }
    }
</style>
<header class="fbs__net-navbar navbar navbar-expand-lg dark" aria-label="navbar">
    <div class="d-flex align-items-center justify-content-between" style="max-width: 1400px; margin: auto; width: 100%;">

        <!-- Start Logo-->
        <a class="navbar-brand p-0" href="{{ route('inicio') }}">
            <img class="logo dark img-fluid" src="{{ asset('assets/img/logo-nav.png') }}" alt="img">
        </a>


        <!-- End Logo-->


        <!-- Start offcanvas-->
        <div class="offcanvas offcanvas-start w-75" id="fbs__net-navbars" tabindex="-1"
            aria-labelledby="fbs__net-navbarsLabel">


            <div class="offcanvas-header justify-content-between align-items-center">
                {{-- Logo y login --}}
                <div class="d-flex gap-3">
                    <div class="offcanvas-header-logo">
                        <a class="logo-link" id="fbs__net-navbarsLabel" href="{{ route('inicio') }}">
                            <img class="logo dark img-fluid" width="150px" src="{{ asset('assets/img/logo.png') }}"
                                alt="img">
                        </a>
                    </div>

                    @auth
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('panel.matriculado') }}" class="btn border rounded px-4"
                                style="color:#fff !important; padding-top: 5px; padding-bottom: 5px;text-decoration: none;">
                                <i class="bi bi-person-fill small-placeholder-icon"></i>
                            </a>
                        </div>
                    @else
                        <button class="bg-white border rounded px-4 small-placeholder"
                            style="color:#000 !important; padding-top: 5px; padding-bottom: 5px;text-decoration: none;"
                            href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="bi bi-person-fill small-placeholder-icon"></i>
                        </button>
                    @endauth
                </div>
                {{-- <button class="btn-close btn-close-black" type="button" data-bs-dismiss="offcanvas"
                          aria-label="Close"></button> --}}
            </div>

            <div class="offcanvas-body align-items-lg-center">


                <ul class="navbar-nav nav m-auto pe-lg-3 mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Institucionales <i class="bi bi-chevron-down ms-1"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="mb-0">
                                <a class="dropdown-item p-1" href="{{ route('autoridades.inicio') }}">
                                    Autoridades
                                </a>
                            </li>
                            @forelse ($leyesNavFiles as $archivo)
                                <li class="mb-0">
                                    <a class="dropdown-item d-flex align-items-center justify-content-between p-1"
                                        href="{{ asset(env('ARCHIVOS_GENERALES_LEYES_PATH') . basename($archivo->ruta)) }}"
                                        target="_blank" rel="noopener">
                                        <span class="text-truncate"
                                            style="max-width:260px">{{ \Illuminate\Support\Str::beforeLast($archivo->nombre_original, '.') }}</span>
                                        <i class="bi bi-box-arrow-up-right ms-2"></i>
                                    </a>
                                </li>
                            @empty
                                <li class="mb-0">
                                    <span class="dropdown-item-text text-muted">No hay archivos</span>
                                </li>
                            @endforelse
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Colegiados <i class="bi bi-chevron-down ms-1"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="https://rionegro.gov.ar/?contID=38298" target="_blank">
                                    Requisitos de Matriculación <i class="bi bi-box-arrow-up-right ms-2"></i>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('padron.publico') }}">Padrón de
                                    colegiados</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link scroll-link"
                            href="{{ route('noticias_inicio') }}">Noticias</a></li>
                    {{-- <li class="nav-item"><a class="nav-link scroll-link" href="#portfolio">Servicios</a></li> --}}
                    <li class="nav-item">
                        <a class="nav-link scroll-link" href="{{ route('inmobiliarias.lista') }}">
                            Inmobiliarias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link scroll-link" href="{{ route('convenios_inicio') }}">
                            Convenios
                        </a>
                    </li>
                    {{-- <li class="nav-item"><a class="nav-link scroll-link" href="#pricing">Cursos</a></li> --}}
                    {{-- <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Dropdown <i class="bi bi-chevron-down"></i></a>
                  
                  <ul class="dropdown-menu">
                    <li><a class="nav-link scroll-link dropdown-item" href="page-index.html">Multipages</a></li>
                    <li><a class="nav-link scroll-link dropdown-item" href="#services">Services</a></li>
                    <li><a class="nav-link scroll-link dropdown-item" href="#pricing">Pricing</a></li>
                    <li class="nav-item dropstart"><a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Dropstart <i class="bi bi-chevron-right"></i></a>
                      <ul class="dropdown-menu">
                        <li><a class="nav-link scroll-link dropdown-item" href="#services">Services</a></li>
                        <li><a class="nav-link scroll-link dropdown-item" href="#pricing">Pricing</a></li>
                        <li class="nav-item dropstart"><a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Dropstart <i class="bi bi-chevron-right"></i></a>
                          <ul class="dropdown-menu">
                            <li><a class="nav-link scroll-link dropdown-item" href="#services">Services</a></li>
                            <li><a class="nav-link scroll-link dropdown-item" href="#pricing">Pricing</a></li>
                            <li><a class="nav-link scroll-link dropdown-item" href="#">Something else here</a></li>
                            <li class="nav-item dropend"><a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Dropend <i class="bi bi-chevron-right"></i></a>
                              <ul class="dropdown-menu">
                                <li><a class="nav-link scroll-link dropdown-item" href="#services">Services</a></li>
                                <li><a class="nav-link scroll-link dropdown-item" href="#pricing">Pricing</a></li>
                                <li><a class="nav-link scroll-link dropdown-item" href="#">Something else here</a></li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                      </ul>
                    </li>
                  </ul>
                  
                </li> --}}
                    <li class="nav-item"><a class="nav-link scroll-link"
                            href="{{ route('contacto_inicio') }}">Contacto</a></li>
                </ul>

            </div>
        </div>
        <!-- End offcanvas-->

        <div class="ms-auto w-auto">


            <div class="header-social d-flex align-items-center gap-1">

                <div class="d-flex align-items-center gap-3">
                    @auth
                        <div class="d-flex align-items-center gap-3">
                            @if (Auth::user()->role === 'admin')
                                <a href="{{ route('noticias') }}" class="btn border rounded p-0 px-2 py-1"
                                    style="color:#fff !important;text-decoration: none; background: #5577b4; font-weight: 500;">
                                    Mi panel
                                </a>
                            @else
                                <a href="{{ route('panel.matriculado') }}" class="btn border rounded p-0 px-2 py-2"
                                    style="color:#fff !important;text-decoration: none;background-color: #5577b4;font-weight: 500;">
                                    Mi panel
                                </a>
                            @endif
                        </div>
                    @else
                        <button class="bg-white border rounded small-placeholder"
                            style="color:#000 !important; padding-top: 5px; padding-bottom: 5px;text-decoration: none;background-color: #5577b4;"
                            href="#" data-bs-toggle="modal" data-bs-target="#loginModal">

                            <i class="bi bi-person-fill small-placeholder-icon"></i>
                            <span style="font-weight: 500; color: #000;">Inicia sesion</span>
                        </button>
                    @endauth
                </div>




                <button class="fbs__net-navbar-toggler justify-content-center align-items-center ms-auto"
                    data-bs-toggle="offcanvas" data-bs-target="#fbs__net-navbars" aria-controls="fbs__net-navbars"
                    aria-label="Toggle navigation" aria-expanded="false">
                    <svg class="fbs__net-icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="21" x2="3" y1="6" y2="6"></line>
                        <line x1="15" x2="3" y1="12" y2="12"></line>
                        <line x1="17" x2="3" y1="18" y2="18"></line>
                    </svg>
                    <svg class="fbs__net-icon-close" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>

            </div>

        </div>
    </div>
</header>


<!-- Modal Login -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="loginModalLabel">Inicio Sesion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.user') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="cuil" class="form-label">CUIT</label>
                        <input type="text" class="form-control" id="cuil" name="cuil" autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Contraseña</label>
                        <input type="password" id="password" class="form-control" name="password"
                            placeholder="••••••" aria-describedby="password" />
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary bg-black">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('modal'))
            var modalId = '{{ session('modal') }}Modal';
            var modal = new bootstrap.Modal(document.getElementById(modalId));
            modal.show();
        @endif

        @if ($errors->any())
            var errorModal = new bootstrap.Modal(document.getElementById('registerModal'));
            errorModal.show();
        @endif
    });
</script>
