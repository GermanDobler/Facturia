@extends('layouts/front')
@section('title', 'Autoridades')

<style>
    .cargo-title {
        color: #5577B4;
        font-weight: 700;
    }

    .titulo {
        font-size: 1.5rem !important;
        font-weight: 500 !important;
        color: #5577B4 !important;
        margin-bottom: 0.5rem;
        font-family: inherit;
        line-height: 1.2;
    }

    aside {
        background: #f9f9f9;
        padding: 1rem;
        border-radius: 8px;
    }

    aside h5 {
        font-weight: 600;
        margin-bottom: 1rem;
    }

    aside ul {
        padding-left: 1rem;
    }

    aside ul li {
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .categories li,
    .sidelink li {
        position: relative;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px dotted #dee2e6;
        list-style: none;
    }

    .categories li a,
    .sidelink li a {
        color: #5476b3;
        text-decoration: none;
        font-weight: 500;
    }

    .sidebar-box h3 {
        font-size: 18px;
        margin-bottom: 15px;
    }

    .color {
        color: #5476b3
    }
</style>

@section('layoutContent')
    <section class="section hero__v7 first-section position-relative pt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-8 blog-content">
                    <h3 class="titulo">Para la presentación de denuncias ante el Tribunal de Ética del Colegio Profesional de
                        Martilleros y
                        Corredores Publicos IV Circunscripcion Judicial Rio Negro </h3><br>

                    <div>
                        <ul class="list-unstyled ul-arrow">
                            <li><strong><a href="{{ asset('assets/img/Formulario-denuncia-2021.docx') }}"
                                        target="_blank">Descargar el formulario
                                    </a></strong></li>
                            <li>Completar el formulario con todos los campos obligatorios </li>
                            <li>Adjuntar documentación como prueba de la denuncia </li>
                            <li>Enviar en archivo .pdf el formulario completo y documentación solicitada a
                                <strong>denuncias@martillerosrionegro.org.ar </strong>
                            </li>
                            <li>La información suministrada tiene carácter de Declaración Jurada </li>
                        </ul>
                    </div>

                    <p>Para más información consultar a <strong>denuncias@martillerosrionegro.org.ar </strong>antes de
                        enviar la documentación. </p>
                    <p>Completar solo digitalmente. </p>

                </div>

                <div class="col-md-4 sidebar">

                    <div class="sidebar-box">
                        <div class="categories">
                            <h3 class="color">Links </h3>
                            <li><a href="{{ route('padron.publico') }}">Padron Colegiados </a></li>
                            <li><a href="{{ route('inmobiliarias.lista') }}">Inmobiliarias </a></li>
                            <li><a href="{{ route('denuncia_inicio') }}">Denuncias </a></li>
                            <li><a href="{{ route('noticias_inicio') }}">Noticias </a></li>
                        </div>
                    </div>
                    <div class="sidebar-box">
                        <img src="{{ asset('assets/img/martillero.jpg') }}" alt=" "
                            class="img-fluid mb-4 w-50 rounded-circle">
                        <h3 class="text-black">Martillero</h3>
                        <p>Es la persona legalmente facultada para realizar la operación de remate, es un profesional
                            autónomo, que puede adquirir la calidad de comerciante y también cumplir la función de auxiliar
                            o colaborador del empresario mercantil. </p>
                        <h3 class="text-black">Corredor</h3>
                        <p>Es la persona que realiza actos de corretaje es decir de mediación entre la oferta y lademanda,
                            buscando un interesado para la operación que desea realizar el comerciante. </p>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
