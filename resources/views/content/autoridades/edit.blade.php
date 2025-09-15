@extends('layouts/contentNavbarLayout')
@section('title', 'Editar autoridad')

@section('content')
    @include('components.toast')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Editar {{ $autoridad->nombre_completo }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('autoridades.update', $autoridad) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" class="form-control" required
                            value="{{ old('nombre', $autoridad->nombre) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido *</label>
                        <input type="text" name="apellido" class="form-control" required
                            value="{{ old('apellido', $autoridad->apellido) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cargo *</label>
                        <input type="text" name="cargo" class="form-control" required
                            value="{{ old('cargo', $autoridad->cargo) }}" placeholder="Presidente, Vice, Tesorero, ...">
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Guardar</button>
                    <a href="{{ route('autoridades.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>

            </form>
        </div>
    </div>
@endsection
