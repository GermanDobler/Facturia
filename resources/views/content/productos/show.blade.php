@extends('layouts/contentNavbarLayout')

@section('title', 'Detalles del Producto')

@section('content')
    @include('components.toast')
    <div class="card">
        <h1>{{ $producto->nombre }}</h1>
        <p><strong>Descripción:</strong> {{ $producto->descripcion }}</p>
        <p><strong>Estado:</strong> {{ ucfirst($producto->estado) }}</p>

        <h3>Imágenes:</h3>
        <div class="row">
            @foreach ($producto->imagenes as $imagen)
                <div class="col-4">
                    <img src="{{ $imagen->url }}" alt="Imagen del producto" class="img-thumbnail"
                        style="width: 100%; height: auto;">
                </div>
            @endforeach
        </div>

        <a href="{{ route('productos.index') }}" class="btn btn-secondary mt-3">Volver a la lista de productos</a>
    </div>
@endsection
