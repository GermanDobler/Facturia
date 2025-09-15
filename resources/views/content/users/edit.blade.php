@extends('layouts/contentNavbarLayout')

@section('title', 'Editar Usuario')

@section('content')
    @include('components.toast')

    <div class="card mb-4">
        <h5 class="card-header">Editar Usuario</h5>
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Nombre y Apellido --}}
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required
                            value="{{ old('nombre', $user->nombre) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" name="apellido" class="form-control" required
                            value="{{ old('apellido', $user->apellido) }}">
                    </div>

                    {{-- Matrícula y Teléfono --}}
                    <div class="col-md-6 mb-3">
                        <label for="matricula" class="form-label">Matrícula</label>
                        <input type="text" name="matricula" class="form-control" required
                            value="{{ old('matricula', $user->matricula) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" required
                            value="{{ old('telefono', $user->telefono) }}">
                    </div>

                    {{-- CUIL y Email --}}
                    <div class="col-md-6 mb-3">
                        <label for="cuil" class="form-label">CUIT</label>
                        <input type="text" name="cuil" class="form-control" value="{{ old('cuil', $user->cuil) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required
                            value="{{ old('email', $user->email) }}">
                    </div>

                    {{-- Rol y Acceso --}}
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Nueva contraseña (opcional)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="activo" class="form-label">Activo</label>
                        <select name="activo" class="form-control" required>
                            <option value="si" {{ old('activo', $user->activo) === 'si' ? 'selected' : '' }}>Si</option>
                            <option value="no" {{ old('activo', $user->activo) === 'no' ? 'selected' : '' }}>No
                            </option>
                        </select>
                    </div>
                    <input type="hidden" name="role" value="user">
                    {{-- Contraseña --}}
                    <div class="col-md-12 mb-3">
                        <label for="observacion" class="form-label">Observación (opcional)</label>
                        <textarea name="observacion" class="form-control" rows="3">{{ old('observacion', $user->observacion) }}</textarea>
                    </div>

                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
