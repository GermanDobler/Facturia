@extends('layouts.front')
<style>
    p {
        margin-bottom: 0px !important;
    }
</style>
@section('layoutContent')
<section class="section ecommerce__v1 first-section">
    <div class="container pt-5">
            <div class="row">
                <h1>{{ $terminos->titulo }}</h1>
                <div>
                    {!! $terminos->contenido !!}
                </div>
            </div>
        </div>
    </section>
@endsection
