@extends('layouts/contentNavbarLayout')

@section('title', 'Mensajes')

@section('content')
    @include('components.toast')
    <div class="container mt-4">
        <h2>Mensaje de {{ $mensaje->nombre }}</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Email: {{ $mensaje->email }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">Telefono: {{ $mensaje->numero }}</h6>
                <p class="card-text">{{ $mensaje->mensaje }}</p>
                <a href="{{ route('mensajes.index') }}" class="btn btn-primary">Volver</a>
            </div>
        </div>
    </div>
@endsection
