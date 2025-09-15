<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ env('APP_NAME') }} </title>
    <meta name="description"
        content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
    <meta name="keywords" content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
    <!-- laravel CRUD token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Canonical SEO -->
    <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">
    <!-- Favicon -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    {{-- <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet"> --}}
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
    <!-- End Google Font-->

    <!-- ======= Styles =======-->
    <link href="{{ asset('assets/vendor/learnify/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/learnify/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/learnify/glightbox/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/learnify/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/learnify/aos/aos.css') }}" rel="stylesheet">
    <!-- End Styles-->

    <!-- ======= Theme Style =======-->
    <link href="{{ asset('assets/css/onepage.css') }}" rel="stylesheet">
    <!-- End Theme Style-->

</head>
<style>
    body {
        font-family: "Muli", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji" !important;
        font-size: 0.95rem;

    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: "Muli", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji" !important;
        font-weight: 700;
    }


    .whatsapp-float {
        position: fixed;
        bottom: 19px;
        right: 20px;
        /* Movido más a la izquierda */
        width: 60px;
        height: 60px;
        background-color: #25d366;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        z-index: 1000;
    }

    .whatsapp-float img {
        width: 35px;
        height: 35px;
    }

    .loading-screen {
        display: none;
        /* Oculto por defecto */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        /* Fondo oscuro transparente */
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        color: white;
        font-size: 18px;
        z-index: 9999;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid rgba(255, 255, 255, 0.3);
        border-top: 5px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 10px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
<div id="loading-screen" class="loading-screen">
    <div class="spinner"></div>
    <p class="loading-text">Cargando...</p>
</div>

<body class="d-flex flex-column min-vh-100 bg-light">

    @include('_partials.navbar')
    <!-- ======= Site Wrap =======-->
    <div class="site-wrap">
        <!-- ======= Main =======-->
        <main class="flex-grow-1">

            @yield('layoutContent')

        </main>
    </div>
    @include('_partials.footer')

    <!--- whatsapp -------------------------------->
    <a href="https://wa.me/+5492995288909" target="_blank" class="whatsapp-float">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
    </a>

    <!-- ======= Back to Top =======-->
    {{-- <button id="back-to-top"><i class="bi bi-arrow-up-short"></i></button> --}}
    <!-- End Back to top-->

    <!-- ======= Javascripts =======-->
    <script src="{{ asset('assets/vendor/learnify/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/learnify/gsap/gsap.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/learnify/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/learnify/isotope/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/learnify/glightbox/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/learnify/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/learnify/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/learnify/purecounter/purecounter.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <!-- End JavaScripts-->
    @yield('page-script')
</body>

</html>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadingScreen = document.getElementById('loading-screen');

        function showLoader() {
            loadingScreen.style.display = 'flex';
        }

        function hideLoader() {
            loadingScreen.style.display = 'none';
        }

        // Mostrar el loader cuando se envía un formulario
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                showLoader();
            });
        });

        // Ocultar el loader cuando la página cargue completamente
        window.addEventListener('load', hideLoader);
    });
</script>
