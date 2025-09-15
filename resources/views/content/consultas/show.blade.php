@extends('layouts.contentNavbarLayout')

@section('title', 'Detalle de Consulta')

@section('content')
    <div class="row">
        <!-- Card: Información de la consulta -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 text-white">{{ $contact->first_name }}</h4>
                </div>
                <div class="card-body py-2">
                    <p><strong>Teléfono:</strong> {{ $contact->phone }}</p>
                    <p><strong>Email:</strong> {{ $contact->email }}</p>
                    <p><strong>Mensaje:</strong> {{ $contact->message }}</p>
                    <p><strong>Fecha:</strong> {{ $contact->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Card: Productos Asociados -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-secondary text-white py-3">
                    <h4 class="mb-0 text-white">Productos Asociados</h4>
                </div>
                <div class="card-body">
                    @if ($products->isEmpty())
                        <p>No hay productos asociados a esta consulta.</p>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td><!-- Enlace al detalle del producto en el admin -->
                                            <a
                                                href="{{ route('producto', ['slug' => Str::slug($product->nombre) . '-' . $product->id]) }}">
                                                {{ $product->nombre }}
                                            </a>
                                        </td>
                                        <td>{{ $productQuantities[$product->id] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Botón de regreso -->
    <div class="mt-3">
        <a href="{{ route('consultas') }}" class="btn btn-secondary">Volver</a>
    </div>
@endsection
