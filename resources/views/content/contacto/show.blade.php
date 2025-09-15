@extends('layouts/contentNavbarLayout')

@section('title', 'Detalle de Contacto')

@section('content')
    @include('components.toast')

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary">
            <h3 class="mb-0 text-white">Información del Contacto</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="fw-bold">Nombre:</label>
                <p class="form-control-plaintext">{{ $contacto->nombre }}</p>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Email:</label>
                <p class="form-control-plaintext">
                    <a href="mailto:{{ $contacto->email }}" class="text-decoration-none">
                        {{ $contacto->email }}
                    </a>
                </p>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Mensaje:</label>
                <p class="form-control-plaintext border rounded p-2 bg-light">
                    {{ $contacto->mensaje }}
                </p>
            </div>

            <div class="text-muted small">
                <p class="mb-0">Creado el: {{ $contacto->created_at->format('d/m/Y H:i') }}</p>
                {{-- <p class="mb-0">Última actualización: {{ $contacto->updated_at->format('d/m/Y H:i') }}</p> --}}
            </div>
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('contactos.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Volver a la lista de contactos
            </a>
        </div>
    </div>
@endsection
