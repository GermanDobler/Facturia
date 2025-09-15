@extends('layouts/contentNavbarLayout')

@section('title', 'Editar Inmobiliaria')
@section('content')
    @include('components.toast')

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Editar {{ $inmobiliaria->nombre }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('inmobiliarias.update', $inmobiliaria) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $inmobiliaria->nombre) }}"
                            class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="email" value="{{ old('email', $inmobiliaria->email) }}"
                            class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $inmobiliaria->telefono) }}"
                            class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $inmobiliaria->whatsapp) }}"
                            class="form-control" placeholder="+549387xxxxxxx">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" value="{{ old('direccion', $inmobiliaria->direccion) }}"
                            class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Localidad</label>
                        <input type="text" name="localidad" value="{{ old('localidad', $inmobiliaria->localidad) }}"
                            class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Sitio web</label>
                        <input type="text" name="url_web"
                            value="{{ old('url_web', $inmobiliaria->getRawOriginal('url_web')) }}" class="form-control"
                            placeholder="tusitio.com">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Instagram</label>
                        <input type="text" name="instagram"
                            value="{{ old('instagram', $inmobiliaria->getRawOriginal('instagram')) }}" class="form-control"
                            placeholder="instagram.com/tuusuario">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Facebook</label>
                        <input type="text" name="facebook"
                            value="{{ old('facebook', $inmobiliaria->getRawOriginal('facebook')) }}" class="form-control"
                            placeholder="facebook.com/tuusuario">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Activa *</label>
                        <select name="activo" class="form-control" required>
                            <option value="si" {{ old('activo', $inmobiliaria->activo) === 'si' ? 'selected' : '' }}>Sí
                            </option>
                            <option value="no" {{ old('activo', $inmobiliaria->activo) === 'no' ? 'selected' : '' }}>No
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Logo (jpg, png, webp, svg - máx. 2MB)</label>
                        <input type="file" name="logo" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
                        @if ($inmobiliaria->logo_url)
                            <div class="mt-2">
                                <img src="{{ asset(env('INMOBILIARIA_PATH') . basename($inmobiliaria->logo_url)) }}"
                                    alt="logo" style="height:64px;object-fit:contain">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Guardar</button>
                    <a href="{{ route('inmobiliarias.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection
