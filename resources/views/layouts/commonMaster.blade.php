<!DOCTYPE html>

<html class="light-style layout-menu-fixed" data-theme="theme-default" data-assets-path="{{ asset('/assets') . '/' }}"
    data-base-url="{{ url('/') }}" data-framework="laravel" data-template="vertical-menu-laravel-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ env('APP_NAME') }} </title>
    <meta name="description"
        content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
    <meta name="keywords"
        content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
    <!-- laravel CRUD token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Canonical SEO -->
    <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />



    <!-- Include Styles -->
    @include('layouts/sections/styles')

    <!-- Include Scripts for customizer, helper, analytics, config -->
    @include('layouts/sections/scriptsIncludes')
    <!-- Custom CSS for footer positioning -->
    <style>
        /* Set the body and html to fill the full height */
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        /* Main content container to take up remaining space */
        .main-content {
            flex: 1;
        }

        /* Footer styling */
        footer {
            position: relative;
            width: 100%;
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
</head>
<div id="loading-screen" class="loading-screen">
    <div class="spinner"></div>
    <p class="loading-text">Cargando...</p>
</div>

<body>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Layout Content -->
        @yield('layoutContent')
        <!--/ Layout Content -->
    </div>
    <!-- Include Scripts -->
    @include('layouts/sections/scripts')

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
