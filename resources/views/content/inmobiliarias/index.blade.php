@extends('layouts/contentNavbarLayout')

@section('title', 'Inmobiliarias')

@section('content')
    @include('components.toast')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center gap-2">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="q" value="{{ $q }}" class="form-control"
                    placeholder="Buscar por nombre, localidad o email">
                <button class="btn btn-secondary">Buscar</button>
            </form>
            <a class="btn btn-primary" href="{{ route('inmobiliarias.create') }}">Nueva Inmobiliaria</a>
        </div>

        <div class="card-body">
            @if ($items->count() === 0)
                <div class="alert alert-warning">No hay inmobiliarias.</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Nombre</th>
                                <th>Localidad</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Activa</th>
                                <th>Web</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td style="width:72px">
                                        @if ($item->logo_url)
                                            <img src="{{ asset(env('INMOBILIARIA_PATH') . basename($item->logo_url)) }}"
                                                alt="logo" class="rounded" style="height:48px;object-fit:contain;">
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="fw-semibold">{{ $item->nombre }}</td>
                                    <td>{{ $item->localidad ?: '—' }}</td>
                                    <td>
                                        @if ($item->telefono)
                                            <a href="{{ $item->telLink() }}">{{ $item->telefono }}</a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->email)
                                            <a href="mailto:{{ $item->email }}">{{ $item->email }}</a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->activo === 'si')
                                            <span class="badge bg-label-success">Sí</span>
                                        @else
                                            <span class="badge bg-label-danger">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->url_web)
                                            <a href="{{ $item->url_web }}" target="_blank" rel="noopener">Abrir</a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="d-flex align-items-center gap-2">
                                            <a class="btn btn-sm btn-warning"
                                                href="{{ route('inmobiliarias.edit', $item) }}">Editar</a>

                                            <form action="{{ route('inmobiliarias.destroy', $item) }}" method="POST"
                                                class="m-0 p-0">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Borrar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-2">
                    {{ $items->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection
