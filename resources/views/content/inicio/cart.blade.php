@extends('layouts/front')

@section('title', 'Carrito')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

@section('layoutContent')
    <div class="container" style="max-width: 1300px">

        <h1 class="my-5">Pedidos</h1>

        <div class="h-auto row cart-table-section">
            @if (empty($cart) || count($cart) === 0)
                <div class="col-lg-12 text-center align-content-center">
                    <img class="logo-container" src="{{ asset('/assets/img/empty-cart.png') }}" alt="Carrito vacío"
                        width="300px">
                    <h3 class="text-muted">No hay pedidos.</h3>
                    <a href="{{ route('productList') }}" class="btn btn-warning mt-4">Ver más productos</a>
                </div>
            @else
                <div class="mr-4 col-12 col-lg-6 bg-light pt-lg-10 aside-checkout p-0 my-5 my-lg-0">
                    <table class="table shop_table shop_table_responsive cart">
                        <thead>
                            <tr>
                                <th class="product-remove text-center"></th>
                                <th class="product-thumbnail text-center">Imagen</th>
                                <th class="product-name text-center">Producto</th>
                                <th class="product-quantity text-center">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart as $productId => $product)
                                <tr>
                                    <td class="product-remove text-center p-1">
                                        <button
                                            class="btn btn-outline-danger remove-product d-flex align-items-center justify-content-center"
                                            data-product-id="{{ $productId }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                    <td class="product-thumbnail">
                                        <a>
                                            @if (is_array($product) && array_key_exists('image_url', $product))
                                                <img src="{{ asset(env('IMAGE_PATH') . basename($product['image_url'])) }}"
                                                    alt="{{ $product['name'] }}" width="80px">
                                            @else
                                                <p class="text-danger">Error: Producto no disponible.</p>
                                            @endif
                                        </a>
                                    </td>
                                    <td class="product-name">
                                        @if (is_array($product) && isset($product['name']))
                                            <a>{{ $product['name'] }}</a>
                                        @else
                                            <a>Producto no disponible</a>
                                        @endif
                                    </td>
                                    <td class="product-quantity">
                                        <div class="input-group">
                                            <button class="btn btn-outline-secondary btn-sm decrement-btn z-1"
                                                type="button">-</button>
                                            <input type="number" class="form-control text-center quantity-input"
                                                value="{{ $product['quantity'] }}" data-product-id="{{ $productId }}">
                                            <button class="btn btn-outline-secondary btn-sm increment-btn z-1"
                                                type="button">+</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="ml-4 col-lg-6 px-4 pt-4 card">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                        <label class="fs-4 fw-bold m-0 lh-1 contenido-h1">Contacto</>
                    </div>
                    <form id="checkoutForm" method="POST" action="{{ route('cart.store') }}">
                        @csrf
                        <div class="row g-3">
                            <!-- First Name-->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="firstNameBilling" class="form-label">Nombre *</label>
                                    <input type="text" class="form-control" id="firstNameBilling" name="first_name"
                                        placeholder="" value="" required>
                                </div>
                            </div>

                            <!-- Last Name-->
                            {{-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="lastNameBilling" class="form-label">Apellido *</label>
                                    <input type="text" class="form-control" id="lastNameBilling" name="last_name"
                                        placeholder="" value="" required>
                                </div>
                            </div> --}}

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="Telefono" class="form-label">Telefono *</label>
                                    <input type="text" class="form-control" id="Telefono" name="phone"
                                        placeholder="299 9999999" value="" required>
                                </div>
                            </div>

                            <!-- Last Name-->
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="Email" class="form-label">Email *</label>
                                    <input type="text" class="form-control" id="Email" name="email" placeholder=""
                                        value="" required="">
                                </div>
                            </div>

                            <!-- mensaje-->
                            <div class="col-12 pt-1">
                                <div class="form-group ">
                                    <label for="mensaje" class="form-label">Mensaje</label>
                                    <textarea type="mensaje" class="form-control" name="message" id="mensaje" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Productos en el carrito (campos ocultos) -->
                        <input type="hidden" name="cart" value="{{ json_encode($cart) }}">
                        <div class="py-4 d-flex justify-content-md-end align-items-center">

                            <button type="submit" class="btn btn-warning w-100 w-md-auto" id="submitButton">
                                <span id="spinner" class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true" style="display: none;"></span>
                                Enviar
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        console.log("DOM completamente cargado");

        // Función para actualizar el carrito en el formulario
        function actualizarCarritoEnFormulario() {
            let carritoActualizado = {};
            document.querySelectorAll('.quantity-input').forEach(input => {
                let productId = input.dataset.productId;
                let cantidad = parseInt(input.value);
                carritoActualizado[productId] = cantidad;
            });
            document.querySelector('input[name="cart"]').value = JSON.stringify(carritoActualizado);
            console.log("Carrito actualizado en el formulario:", carritoActualizado);
        }

        // Función para mostrar el spinner
        function mostrarLoader() {
            document.querySelector('#spinner').style.display = 'inline-block'; // Muestra el spinner
            document.querySelector('#submitButton').disabled = true; // Deshabilita el botón
        }

        // Función para ocultar el spinner
        function ocultarLoader() {
            document.querySelector('#spinner').style.display = 'none'; // Oculta el spinner
            document.querySelector('#submitButton').disabled = false; // Habilita el botón
        }

        // Manejar cambios en la cantidad de productos
        document.querySelectorAll('.decrement-btn').forEach(button => {
            button.addEventListener('click', () => {
                let input = button.parentElement.querySelector('.quantity-input');
                let newValue = Math.max(parseInt(input.value) - 1, 1);
                input.value = newValue;
                console.log(`Producto ${input.dataset.productId}: Nueva cantidad ${newValue}`);
                actualizarCarritoEnFormulario();
            });
        });

        document.querySelectorAll('.increment-btn').forEach(button => {
            button.addEventListener('click', () => {
                let input = button.parentElement.querySelector('.quantity-input');
                let newValue = parseInt(input.value) + 1;
                input.value = newValue;
                console.log(`Producto ${input.dataset.productId}: Nueva cantidad ${newValue}`);
                actualizarCarritoEnFormulario();
            });
        });

        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', () => {
                let newValue = Math.max(parseInt(input.value) || 1, 1);
                input.value = newValue;
                console.log(
                    `Producto ${input.dataset.productId}: Cantidad cambiada manualmente a: ${newValue}`
                );
                actualizarCarritoEnFormulario();
            });
        });

        document.querySelectorAll('.remove-product').forEach(button => {
            button.addEventListener('click', () => {
                let row = button.closest('tr');
                let input = row.querySelector('.quantity-input');
                let productId = input.dataset.productId;
                row.remove();
                console.log(`Producto ${productId} eliminado del carrito.`);
                actualizarCarritoEnFormulario();
            });
        });

        // Al enviar el formulario, mostramos el loader y actualizamos el carrito
        document.querySelector('#checkoutForm').addEventListener('submit', (event) => {
            event.preventDefault(); // Prevenir envío de formulario hasta mostrar el loader
            mostrarLoader(); // Muestra el spinner
            actualizarCarritoEnFormulario(); // Actualizar carrito en el formulario

            // Luego, enviamos el formulario con un pequeño retraso para ver el spinner
            setTimeout(() => {
                event.target.submit(); // Enviar el formulario después de mostrar el spinner
            }, 500);
            console.log("Formulario enviado con carrito actualizado.");
        });
    });
</script>
<style>
    h1 {
        font-size: 1.7rem !important;
        color: #221E20 !important;
        font-weight: 700 !important;
    }
</style>
