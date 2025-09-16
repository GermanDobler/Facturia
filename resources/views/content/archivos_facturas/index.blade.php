@extends('layouts/contentNavbarLayout')

@section('title', 'Lotes (Facturas)')

@section('content')
    @include('components.toast')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Lotes (Facturas)</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoLote">
            Nuevo lote
        </button>
    </div>

    {{-- Subida de archivos al lote seleccionado --}}
    <div class="card mb-4">
        <div class="card-header">Subir archivos al lote</div>
        <div class="card-body">
            <form action="{{ route('archivos.facturas.subir') }}" method="POST" enctype="multipart/form-data"
                class="row g-2">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Carpeta/Lote</label>
                    <select name="carpeta" class="form-select">
                        @foreach ($carpetas as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Base: {{ env('ARCHIVOS_FACTURAS') }}</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Archivo(s)</label>
                    <input type="file" name="archivos[]" class="form-control" multiple
                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.mp3" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Subir</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Listado por carpetas (calco) --}}
    @foreach ($carpetas as $carpeta)
        <div class="card mb-3" id="{{ urlencode($carpeta) }}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="fw-medium">{{ $carpeta }}</div>


                <div class="d-flex gap-2">
                    <form class="mb-0" action="{{ route('lotes.procesar', $carpeta) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-primary">Procesar lote</button>
                    </form>
                    <a href="{{ route('archivos.facturas.resultados', $carpeta) }}"
                        class="btn btn-sm btn-outline-secondary">
                        Ver resultados
                    </a>
                    <form class="mb-0" action="{{ route('archivos.facturas.eliminarLote', $carpeta) }}" method="POST"
                        onsubmit="return confirm('Eliminar lote/carpeta {{ $carpeta }} y todo su contenido?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Eliminar lote</button>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tamaño</th>
                            {{-- <th>Tipo</th> --}}
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($archivos[$carpeta] ?? collect()) as $a)
                            @if ($a->nombre_original !== '(carpeta vacía)')
                                <tr>
                                    <td>{{ $a->nombre_original }}</td>
                                    <td>{{ number_format(($a->tamano ?? 0) / 1024, 2) }} KB</td>
                                    {{-- <td>{{ strtoupper($a->tipo ?? '-') }}</td> --}}
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-primary"
                                            href="{{ route('archivos.facturas.descargar', $a->id) }}">Descargar</a>
                                        <form action="{{ route('archivos.facturas.eliminar', $a->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Eliminar archivo {{ $a->nombre_original }}?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Sin archivos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    {{-- Modal Nuevo Lote --}}
    <div class="modal fade" id="modalNuevoLote" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('archivos.facturas.crearLote') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo lote</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre del lote</label>
                        <input type="text" name="nombre_carpeta" class="form-control" maxlength="100" required>
                        <small class="text-muted">Se creará en {{ env('ARCHIVOS_FACTURAS') }}/&lt;nombre&gt;.</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha fin</label>
                            <input type="date" name="fecha_fin" class="form-control">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="abierto" selected>Abierto</option>
                            <option value="cerrado">Cerrado</option>
                            <option value="anulado">Anulado</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Crear lote</button>
                </div>
            </form>
        </div>
    </div>
@endsection
