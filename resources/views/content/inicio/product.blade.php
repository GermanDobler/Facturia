@extends('layouts/front')

@section('title', 'Inicio')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<!-- Demo styles -->
<style>
    /* Contenedor de miniaturas */
    .gallery-thumbs-vertical {
        width: 100px;
        /* Ancho de las miniaturas */
        height: 600px;
        /* Altura del contenedor */
        position: relative;
    }

    .gallery-thumbs-vertical .swiper-slide {
        height: 100px;
        /* Altura de cada miniatura */
        width: 100%;
        /* Ancho de cada miniatura */
        opacity: 0.4;
        /* Opacidad de miniaturas no seleccionadas */
        cursor: pointer;
    }

    .gallery-thumbs-vertical .swiper-slide-thumb-active {
        opacity: 1;
        /* Resalta la miniatura activa */
    }

    /* Contenedor de imagen principal */
    .gallery-top-vertical {
        width: 100%;
        /* Ancho completo de la imagen principal */
        /* height: 500px; */
        /* Altura del contenedor */
        position: relative;
    }

    .gallery-top-vertical .swiper-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Ajusta la imagen para que cubra todo el espacio */
    }

    /* Botones de navegación */
    .swiper-button-next,
    .swiper-button-prev {
        color: #000;
        /* Color de los botones de navegación */
    }
</style>

<style>
    * {
        box-sizing: border-box;
    }

    h1 {
        font-weight: 400;
        line-height: 1.25;
        margin: 0px;
        color: #221E20 !important;
    }

    h3 {
        font-weight: 400;
        line-height: 1.25;
        margin: 0px;
        color: #221E20 !important;
    }

    .ui-pdp-title {
        color: #221E20;
        font-size: 22px;
        font-weight: 600;
        hyphens: auto;
        line-height: 1.18;
        word-break: break-word;
    }

    .ui-pdp-header .ui-pdp-title {
        flex: 1 1 auto;
        font-size: 22px;
        padding: 0px;
    }

    .ui-pdp-header .ui-pdp-title {
        margin-right: 0px;
    }

    p {
        margin: 0px;
    }

    .ui-pdp-color--BLACK {
        color: #221E20;
    }

    .ui-pdp-size--SMALL {
        font-size: 16px;
    }

    .ui-pdp-family--SEMIBOLD {
        font-weight: 600;
    }

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

    .descripcion-colapsada {
        max-height: 310px;
        overflow: hidden;
        position: relative;
        transition: max-height 0.3s ease;
    }

    .descripcion-colapsada.expandida {
        max-height: 1000px;
        /* lo suficiente para mostrar todo */
    }
</style>


@section('layoutContent')

    <section class="section ecommerce__v1 first-section">
        @include('components.toastCarrito')
        <div class="container pt-5">
            <div class="row align-items-start g-5">
                <div class="col-md-6">
                    <div class="custom-border rounded-4 mb-5 zoom-wrapper">
                        <div class="swiper ecommerceSwiperMain">
                            <div class="swiper-wrapper text-center">
                                @foreach ($producto->imagenes->sortBy('orden') as $imagen)
                                    <div class="swiper-slide">
                                        <div class="zoom-container">
                                            <img class="img-fluid zoom-image"
                                                src="{{ asset(env('IMAGE_PATH') . basename($imagen->url)) }}"
                                                alt="Imagen del producto">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    </div>

                    <div class="swiper ecommerceSwiperThumbnail swiper-thumbs">
                        <div class="swiper-wrapper">
                            @foreach ($producto->imagenes->sortBy('orden') as $imagen)
                                <div class="swiper-slide">
                                    <img class="img-fluid" src="{{ asset(env('IMAGE_PATH') . basename($imagen->url)) }}"
                                        alt="Miniatura del producto">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    {{-- <span class="d-block text-uppercase small">Chair</span> --}}
                    <span>{{ $producto->categoria->categoriaPadre ? $producto->categoria->categoriaPadre->nombre : $producto->categoria->nombre }}</span>
                    <h1 class="fs-2 h-custom">{{ $producto->nombre }}</h1>
                    {{-- <div class="price mb-4"><span class="d-flex gap-3 align-items-center"><span class="text-decoration-line-through">$799 USD</span><span>$420 USD</span><span class="badge badge-primary">Sale</span></span></div> --}}
                    <section class="contenido position-relative" id="descripcionProducto">
                        <div id="descripcionCorta" class="descripcion-colapsada">
                            {!! $producto->descripcion !!}
                        </div>
                        <button id="toggleDescripcion" class="btn p-1 mt-2">Ver más</button>
                    </section>
                    <div class="mt-1">
                        <label for="product-quantity"
                            class="mb-1 ui-pdp-color--BLACK ui-pdp-size--SMALL ui-pdp-family--SEMIBOLD">Cantidad:</label>
                        <div class="row">
                            <div class="col-6">

                                <input type="number" class="form-control" value="1" id="product-quantity">
                            </div>
                            <div class="col-6">

                                <button class="btn btn-warning btn-dark-chunky flex-grow-1 text-white w-100"
                                    id="add-to-cart" data-id="{{ $producto->id }}">
                                    Agregar al pedido
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


<!-- Initialize Swiper -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // const galleryThumbs = new Swiper(".gallery-thumbs-vertical", {
        //     direction: "vertical",
        //     slidesPerView: 5,
        //     freeMode: true,
        //     watchSlidesProgress: true,
        //     spaceBetween: 10,
        //     loop: true
        // });

        // const galleryTop = new Swiper(".gallery-top-vertical", {
        //     spaceBetween: 10,
        //     navigation: {
        //         nextEl: ".swiper-button-next",
        //         prevEl: ".swiper-button-prev",
        //     },
        //     thumbs: {
        //         swiper: galleryThumbs,
        //     },
        //     loop: true,
        //     zoom: true, // Habilita el zoom
        // });
        const thumbsSwiper = new Swiper(".ecommerceSwiperThumbnail", {
            spaceBetween: 10,
            slidesPerView: 5,
            freeMode: true,
            watchSlidesProgress: true,
        });

        const mainSwiper = new Swiper(".ecommerceSwiperMain", {
            spaceBetween: 10,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            zoom: true,
            thumbs: {
                swiper: thumbsSwiper,
            },
        });

    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const desc = document.getElementById('descripcionCorta');
        const btn = document.getElementById('toggleDescripcion');

        btn.addEventListener('click', function() {
            desc.classList.toggle('expandida');
            btn.textContent = desc.classList.contains('expandida') ? 'Ver menos' : 'Ver más';
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const addToCartButton = document.getElementById("add-to-cart");
        const quantitySelect = document.getElementById("product-quantity");
        const productImageUrl = "{{ $producto->imagenes[0]->url }}"; // URL de la primera imagen del producto
        const addToCartUrl = "{{ route('cart.add') }}"; // URL generada con Blade

        addToCartButton.addEventListener("click", () => {
            const productId = addToCartButton.dataset.id;
            const quantity = quantitySelect.value;

            // Mostrar mensaje de carga
            addToCartButton.innerText = "Agregando...";
            addToCartButton.disabled = true;

            // Petición POST al servidor
            fetch(addToCartUrl, { // Usar la URL generada
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Token CSRF para seguridad
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity,
                        image_url: productImageUrl, // URL de la imagen del producto
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', 'Producto agregado',
                            'El producto se agregó correctamente al pedido.'
                        );
                    } else {
                        showToast('error', 'Error',
                            'Hubo un problema al agregar el producto al pedido.');
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    showToast('error', 'Error',
                        'No se pudo agregar al pedido. Intente nuevamente.');
                })
                .finally(() => {
                    addToCartButton.innerText = "Agregar al pedido";
                    addToCartButton.disabled = false;
                });
        });
    });
</script>
