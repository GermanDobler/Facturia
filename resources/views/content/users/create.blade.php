@extends('layouts/contentNavbarLayout')

@section('title', 'Crear Usuario')

@section('content')
    @include('components.toast')

    {{-- Errores --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Mensajes --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-4">
        <h5 class="card-header">Nuevo Usuario

        </h5>
        <div class="card-body">

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="row">
                    {{-- Nombre y Apellido --}}
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required value="{{ old('nombre') }}"
                            placeholder="Ingresa nombre">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" name="apellido" class="form-control" required value="{{ old('apellido') }}"
                            placeholder="Ingresa apellido">
                    </div>

                    {{-- Matrícula y Teléfono --}}
                    <div class="col-md-6 mb-3">
                        <label for="matricula" class="form-label">Matrícula</label>
                        <input type="text" name="matricula" class="form-control" required value="{{ old('matricula') }}"
                            placeholder="Número de matrícula">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="number" name="telefono" class="form-control" required value="{{ old('telefono') }}"
                            placeholder="Ej: 299-4567890">
                    </div>

                    {{-- CUIL y Email --}}
                    <div class="col-md-6 mb-3">
                        <label for="cuil" class="form-label">CUIT</label>
                        <input type="text" name="cuil" class="form-control" required value="{{ old('cuil') }}"
                            placeholder="Ingresa CUIL">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required value="{{ old('email') }}"
                            placeholder="Ingresa email">
                    </div>

                    {{-- Rol y Acceso --}}
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Contraseña (min. 6 caracteres)</label>
                        <input type="password" name="password" class="form-control" required
                            placeholder="Ingresa contraseña">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="activo" class="form-label">Activo</label>
                        <select name="activo" class="form-control" required>
                            <option value="si" {{ old('activo') == 'si' ? 'selected' : '' }} selected>Si</option>
                            <option value="no" {{ old('activo') == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    {{-- Contraseña --}}

                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Crear</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
