@extends('layouts/contentNavbarLayout')

@section('title', 'Configuración del Footer')

@section('content')
    @include('components.toast')

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title">Configuración del Footer</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('footer.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control" value="{{ $footer->direccion ?? '' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ $footer->telefono ?? '' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Instagram</label>
                    <input type="text" name="instagram" class="form-control" value="{{ $footer->instagram ?? '' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Facebook</label>
                    <input type="text" name="facebook" class="form-control" value="{{ $footer->facebook ?? '' }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $footer->email ?? '' }}">
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>
    </div>
@endsection
