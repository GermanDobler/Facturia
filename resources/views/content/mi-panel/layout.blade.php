{{-- @extends('layouts/front')

@section('layoutContent')
    <section class="section ecommerce__v1">
        <div class="container pt-5 pb-5">
            <div class="row">
                <!-- ASIDE -->
                <aside class="col-12 col-lg-3 mb-4 mb-lg-0">
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush" id="panel-aside">
                                <a href="{{ route('panel.matriculado') }}"
                                    class="list-group-item list-group-item-action {{ request()->routeIs('panel.matriculado') ? 'active' : '' }}"
                                    data-ajax-nav>
                                    🏠 Inicio
                                </a>
                                <a href="{{ route('panel.cuotas') }}"
                                    class="list-group-item list-group-item-action {{ request()->routeIs('panel.cuotas') ? 'active' : '' }}"
                                    data-ajax-nav>
                                    💳 Mis cuotas
                                </a>
                                <a href="{{ route('panel.archivero.index') }}"
                                    class="list-group-item list-group-item-action {{ request()->routeIs('panel.archivero.index') ? 'active' : '' }}"
                                    data-ajax-nav>
                                    📂 Archivero Personal
                                </a>
                                <a href="{{ route('panel.archivero.general') }}"
                                    class="list-group-item list-group-item-action {{ request()->routeIs('panel.archivero.general') ? 'active' : '' }}"
                                    data-ajax-nav>
                                    📁 Archivos Generales
                                </a>
                                <form action="{{ route('logout.user') }}" method="POST" class="mb-0">
                                    @csrf
                                    <button class="list-group-item list-group-item-action btn border-0"
                                        style="border-radius: 0px;" type="submit">
                                        Cerrar Sesión
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </aside>
                <main class="col-12 col-lg-9">
                    <div id="panel-main">
                        @yield('panelContent')
                    </div>
                </main>
            </div>
        </div>
    </section>
@endsection --}}
@extends('layouts/front')
<style>
    .aside-card {
        border: 1px solid var(--bs-border-color);
        box-shadow: none;
        border-radius: .75rem
    }

    #panel-aside .list-group-item {
        padding: .65rem .9rem;
        border: 0;
        background: transparent;
        color: var(--bs-body-color);
        display: flex;
        align-items: center;
        gap: .6rem;
        border-radius: .5rem
    }

    #panel-aside .list-group-item .bi {
        width: 1.1rem;
        height: 1.1rem;
        opacity: .7
    }

    #panel-aside .list-group-item:hover {
        background: var(--bs-secondary-bg)
    }

    #panel-aside .list-group-item.active {
        position: relative;
        background: var(--bs-secondary-bg);
        font-weight: 600
    }

    #panel-aside .list-group-item.active::before {
        content: "";
        position: absolute;
        left: -.5rem;
        top: .35rem;
        bottom: .35rem;
        width: 3px;
        background: var(--bs-primary);
        border-radius: 2px
    }

    .sticky-aside {
        position: sticky;
        top: 1.25rem
    }
</style>
@section('layoutContent')
    @include('components.toastFront')
    <section class="section ecommerce__v1">
        <div class="container pt-5 pb-5">
            <div class="row">
                <!-- ASIDE -->
                <aside class="col-12 col-lg-3 mb-4 mb-lg-0">
                    <div class="card aside-card sticky-aside">
                        <div class="card-body p-2">
                            <div class="list-group list-group-flush" id="panel-aside">
                                <a href="{{ route('panel.matriculado') }}"
                                    class="list-group-item list-group-item-action {{ request()->routeIs('panel.matriculado') ? 'active' : '' }}"
                                    data-ajax-nav>
                                    <i class="bi bi-house"></i>
                                    <span>Inicio</span>
                                </a>

                                <a href="{{ route('panel.cuotas') }}"
                                    class="list-group-item list-group-item-action {{ request()->routeIs('panel.cuotas') ? 'active' : '' }}"
                                    data-ajax-nav>
                                    <i class="bi bi-credit-card"></i>
                                    <span>Mis cuotas</span>
                                </a>

                                <a href="{{ route('panel.archivero.index') }}"
                                    class="list-group-item list-group-item-action {{ request()->routeIs('panel.archivero.index') ? 'active' : '' }}"
                                    data-ajax-nav>
                                    <i class="bi bi-folder2"></i>
                                    <span>Archivos Personales</span>
                                </a>

                                <a href="{{ route('panel.archivero.general') }}"
                                    class="list-group-item list-group-item-action {{ request()->routeIs('panel.archivero.general') ? 'active' : '' }}"
                                    data-ajax-nav>
                                    <i class="bi bi-archive"></i>
                                    <span>Archivos Generales</span>
                                </a>

                                <form action="{{ route('logout.user') }}" method="POST" class="mt-1 mb-0">
                                    @csrf
                                    <button type="submit"
                                        class="list-group-item list-group-item-action bg-transparent border-0 w-100 text-start">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Cerrar sesión</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </aside>
                <main class="col-12 col-lg-9">
                    <div id="panel-main">
                        @yield('panelContent')
                    </div>
                </main>
            </div>
        </div>
    </section>
@endsection
