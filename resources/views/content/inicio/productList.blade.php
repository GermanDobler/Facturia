@extends('layouts/front')

@section('title', 'Inicio')
<style>
    .category-list ul {
        /* Style the main category list */
        padding: 10px;
    }

    .category-item {
        /* Style each main category item */
        margin-bottom: 5px;
        /* Add spacing between items */
    }

    .category-link {
        font-weight: bold;
        /* Make main categories stand out */
        display: block;
        /* Make the whole list item clickable */
        padding: 5px 10px;
        /* Add some padding */
    }

    .subcategoria-list {
        /* Style the subcategory lists */
        margin-left: 20px;
    }

    .subcategoria-item {
        /* Style each subcategory item */
        margin-bottom: 3px;
    }

    .subcategoria-link {
        padding: 3px 10px;
        display: block;
        /* Make the whole list item clickable */
    }

    /* Hover effects */
    .category-link,
    .subcategoria-link {
        color: inherit !important;
        /* Hereda el color natural */
        transition: color 0.2s ease-in-out;
        /* Transición suave */
    }

    .category-link:hover,
    .subcategoria-link:hover {
        color: #ffab00 !important;
        /* Amarillo */
    }

    /* Add borders or other visual separators as needed */
    .category-item {
        border-bottom: 1px solid #eee;
        /* Light gray line between categories */
    }
</style>
<style>
    .pagination .page-link {
        background-color: #ffab00;
        color: white;
        border-color: #ffab00;
    }

    .pagination .page-link:hover {
        background-color: #e68900;
        color: white;
        border-color: #e68900;
    }

    .pagination .page-item.active .page-link {
        background-color: #e68900;
        color: white;
        border-color: #e68900;
    }

    .pagination .page-item.disabled .page-link {
        background-color: #f0f0f0;
        color: #cccccc;
        border-color: #f0f0f0;
    }

    /* Eliminar color azul al hacer hover sobre el número de página activo */
    .pagination .page-item.active .page-link:hover {
        background-color: #e68900;
        /* Mantener el tono amarillo oscuro */
        color: white;
        /* Texto blanco */
        border-color: #e68900;
        /* Borde amarillo oscuro */
    }

    /* Eliminar el color azul en el enlace activo */
    .pagination .page-item.active .page-link {
        background-color: #e68900;
        /* Tono amarillo oscuro */
        color: white;
        /* Texto blanco */
        border-color: #e68900;
        /* Borde amarillo oscuro */
    }
</style>

@section('layoutContent')
    @include('components.toast')
    <div class="container mt-4">
        <div class="row">
            <!-- ASIDE DE CATEGORÍAS -->
            <div class="col-md-3">
                <aside class="w-100">
                    <div class="card mb-4">
                        <h2 class="p-4 fs-6 fw-bolder mb-0 contenido-h1">Categorías</h2>
                        <div class="category-list">
                            <div class="accordion" id="accordionCategorias">
                                @foreach ($categorias as $categoria)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $categoria->id }}">
                                            @if ($categoria->subcategorias->isNotEmpty())
                                                <button class="accordion-button p-0" style="padding-right: 18px !important"
                                                    type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{ $categoria->id }}" aria-expanded="true"
                                                    aria-controls="collapse{{ $categoria->id }}">
                                                    <a class="contenido-h2 category-link"
                                                        href="{{ route('productList', ['categoria' => $categoria->id]) }}"
                                                        data-categoria-id="{{ $categoria->id }}"
                                                        onclick="handleLinkClick(event, {{ $categoria->id }})">
                                                        {{ $categoria->nombre }}
                                                    </a>
                                                </button>
                                            @else
                                                <a class="contenido-h2 category-link"
                                                    href="{{ route('productList', ['categoria' => $categoria->id]) }}"
                                                    data-categoria-id="{{ $categoria->id }}">
                                                    {{ $categoria->nombre }}
                                                </a>
                                            @endif
                                        </h2>
                                        @if ($categoria->subcategorias->isNotEmpty())
                                            <div id="collapse{{ $categoria->id }}" class="accordion-collapse collapse"
                                                aria-labelledby="heading{{ $categoria->id }}"
                                                data-bs-parent="#accordionCategorias">
                                                <div class="accordion-body p-0">
                                                    <ul class="list-unstyled">
                                                        @foreach ($categoria->subcategorias as $subcategoria)
                                                            <li class="subcategoria-item">
                                                                <a class="subcategoria-link"
                                                                    href="{{ route('productList', ['categoria' => $subcategoria->id]) }}"
                                                                    data-categoria-id="{{ $subcategoria->id }}"
                                                                    onclick="keepParentOpen(event, {{ $categoria->id }})">
                                                                    {{ $subcategoria->nombre }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </aside>

            </div>

            <!-- SECCIÓN DE PRODUCTOS -->
            <section class="col-md-9">
                <h1 class="mb-4 contenido-h1">
                    Productos
                    @if (request('categoria'))
                        @php
                            // Intentar encontrar la categoría seleccionada, puede ser una categoría principal o subcategoría
                            $categoriaSeleccionada =
                                $categorias->firstWhere('id', request('categoria')) ??
                                $categorias
                                    ->flatMap(fn($cat) => $cat->subcategorias)
                                    ->firstWhere('id', request('categoria'));
                        @endphp
                        @if ($categoriaSeleccionada)
                            de categoría: <span
                                style="color: #ffab00; font-weight: 600;">{{ $categoriaSeleccionada->nombre }}</span>
                        @else
                            de categoría desconocida
                        @endif
                    @endif
                </h1>



                <!-- Buscador -->
                <form action="{{ route('productList') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Buscar producto..."
                            value="{{ request('search') }}">
                        <button class="btn btn-warning z-1" type="submit">Buscar</button>
                    </div>
                </form>
                <div id="product-list">
                    <div class="row">
                        @forelse ($productos as $producto)
                            <div class="col-md-4 mb-4">
                                <a
                                    href="{{ route('producto', ['slug' => Str::slug($producto->nombre) . '-' . $producto->id]) }}">
                                    <div class="card">
                                        <picture class="position-relative overflow-hidden d-block bg-light">
                                            @if ($producto->imagenes->isNotEmpty())
                                                <!-- Verifica si el producto tiene imágenes -->
                                                <img class="w-100 img-fluid position-relative z-index-bajo"
                                                    title="{{ $producto->nombre }}"
                                                    src="{{ asset(env('IMAGE_PATH') . basename($producto->imagenes->first()->url)) }}"
                                                    alt="{{ $producto->nombre }}">
                                            @else
                                                <img class="w-100 img-fluid position-relative z-index-bajo"
                                                    title="{{ $producto->nombre }}"
                                                    src="{{ asset('/assets/img/producto-1.png') }}"
                                                    alt="{{ $producto->nombre }}">
                                            @endif
                                        </picture>
                                        <div class="card-body">
                                            <h2 class="contenido-grande">{{ $producto->nombre }}</h2>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <p class="text-center">No se encontraron productos.</p>
                        @endforelse
                    </div>
                </div>
                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $productos->appends(['search' => request('search'), 'categoria' => request('categoria')])->links('pagination::bootstrap-4') }}
                </div>
            </section>
        </div>
    </div>

@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const productList = document.getElementById("product-list");

        // Función para hacer la petición AJAX
        function fetchProducts(url) {
            fetch(url, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    productList.innerHTML = data.html;
                    history.pushState({}, "", data.url); // Actualizar la URL sin recargar la página
                })
                .catch(error => console.error("Error al cargar productos:", error));
        }

        // Capturar el evento del formulario de búsqueda
        document.getElementById("search-form").addEventListener("submit", function(event) {
            event.preventDefault();
            let url = new URL(this.action);
            let formData = new FormData(this);
            formData.forEach((value, key) => url.searchParams.set(key, value));
            fetchProducts(url);
        });

        // Capturar eventos de los enlaces de categoría
        document.querySelectorAll(".categoria-link").forEach(link => {
            link.addEventListener("click", function(event) {
                event.preventDefault();
                fetchProducts(this.href);
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const categoriaId = urlParams.get('categoria');

        // Si hay una categoría en la URL, mantenemos su acordeón abierto
        if (categoriaId) {
            const parentCollapse = document.getElementById(`collapse${categoriaId}`);
            if (parentCollapse) {
                const bootstrapCollapse = new bootstrap.Collapse(parentCollapse, {
                    toggle: false // No tocamos el estado de colapso automáticamente
                });
                parentCollapse.classList.add('show'); // Aseguramos que el acordeón está abierto
            }
        }
    });

    function handleLinkClick(event, categoriaId) {
        // Prevenir que el clic sobre el enlace cierre el acordeón
        event.stopPropagation();

        // Hacer la navegación a la URL correspondiente
        window.location.href = event.target.href;
    }

    function keepParentOpen(event, categoriaId) {
        // Prevenir que el clic cierre el acordeón de la categoría padre
        event.stopPropagation();

        // Asegurarse de que el acordeón de la categoría padre esté abierto
        const parentCollapse = document.getElementById(`collapse${categoriaId}`);
        if (parentCollapse && !parentCollapse.classList.contains('show')) {
            const bootstrapCollapse = new bootstrap.Collapse(parentCollapse, {
                toggle: false // No tocamos el estado de colapso automáticamente
            });
            parentCollapse.classList.add('show'); // Forzamos el acordeón a estar abierto
        }

        // Hacer la navegación a la subcategoría
        window.location.href = event.target.href;
    }
</script>
