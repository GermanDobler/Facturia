@extends('layouts/front')

<style>
    p {
        margin-bottom: 0px !important;
    }
</style>
@section('layoutContent')
    <section class="section ecommerce__v1 first-section pt-0">
        <div class="container pt-5">
            <div class="row">
                <h1>{{ $informacion->titulo }}</h1>
                <div>
                    {!! $informacion->contenido !!}
                </div>
            </div>
        </div>
    </section>
@endsection
