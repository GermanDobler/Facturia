@extends('layouts/contentNavbarLayout')

@section('title', 'Periodos')

@section('content')
    @include('components.toast')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Periodos</h5>
                <small class="text-muted">Programados, abiertos, cerrados o anulados</small>
            </div>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex mb-0">
                    <select name="estado" class="form-select form-select-sm me-2">
                        <option value="">Todos</option>
                        @foreach (['programado', 'abierto', 'cerrado', 'anulado'] as $e)
                            <option value="{{ $e }}" @selected(request('estado') === $e)>{{ ucfirst($e) }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-secondary btn-sm">Filtrar</button>
                </form>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoPeriodo">Nuevo
                    periodo</button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            {{-- <th>#</th> --}}
                            <th>Nombre</th>
                            <th>Año/Mes</th>
                            <th>Vence</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Visible</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($periodos as $p)
                            <tr>
                                {{-- <td>{{ $p->id }}</td> --}}
                                <td><a href="{{ route('periodos.show', $p) }}">{{ $p->nombre }}</a></td>
                                <td>{{ $p->anio }}/{{ str_pad($p->mes, 2, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($p->fecha_vencimiento)->format('d/m/Y') }}</td>
                                <td>${{ number_format($p->monto, 2, ',', '.') }}</td>
                                <td>
                                    @php $map=['programado'=>'secondary','abierto'=>'primary','cerrado'=>'success','anulado'=>'danger']; @endphp
                                    <span
                                        class="badge bg-{{ $map[$p->estado] ?? 'secondary' }}">{{ ucfirst($p->estado) }}</span>
                                </td>
                                <td>
                                    @if ($p->es_visible)
                                        <span class="badge bg-success">Sí</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-outline-secondary"
                                            href="{{ route('periodos.show', $p) }}">Ver</a>

                                        @if ($p->estado === 'programado')
                                            <form method="POST" action="{{ route('periodos.abrir', $p) }}" class="mb-0"
                                                onsubmit="return confirm('¿Publicar y generar cuotas?')">
                                                @csrf
                                                <button class="btn btn-sm btn-primary">Abrir</button>
                                            </form>
                                            <form method="POST" action="{{ route('periodos.cancelar', $p) }}"
                                                class="mb-0" onsubmit="return confirm('¿Anular periodo programado?')">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger">Cancelar</button>
                                            </form>
                                        @elseif($p->estado === 'abierto')
                                            <form method="POST" action="{{ route('periodos.cerrar', $p) }}" class="mb-0"
                                                onsubmit="return confirm('¿Cerrar periodo? Esta acción es definitiva.')">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Cerrar</button>
                                            </form>
                                            <form method="POST" action="{{ route('periodos.revertir', $p) }}"
                                                class="mb-0"
                                                onsubmit="return confirm('Revertir y anular: borra cuotas sin pagos, exime las que tienen pagos. ¿Continuar?')">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger">Revertir y anular</button>
                                            </form>
                                        @endif
                                    </div>
                                    @if (!in_array($p->estado, ['cerrado', 'anulado']))
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#modalEditarPeriodo"
                                            data-action="{{ route('periodos.update', $p) }}" data-id="{{ $p->id }}"
                                            data-nombre="{{ $p->nombre }}" data-monto="{{ (float) $p->monto }}"
                                            data-estado="{{ $p->estado }}">
                                            Editar
                                        </button>
                                    @endif

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Sin periodos</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-2">
                {{ $periodos->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    {{-- Modal nuevo periodo --}}
    <div class="modal fade" id="modalNuevoPeriodo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('periodos.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo periodo (queda programado)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label">Nombre</label>
                        <input name="nombre" class="form-control" placeholder="Ene 2026" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Año</label>
                        <input type="number" name="anio" class="form-control" min="2000" max="2100"
                            value="{{ now()->year }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Mes</label>
                        <input type="number" name="mes" class="form-control" min="1" max="12"
                            value="{{ now()->month }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Monto</label>
                        <input type="number" step="0.01" name="monto" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Crear</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Editar Periodo -->
    <div class="modal fade" id="modalEditarPeriodo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" id="formEditarPeriodo" action="">
                @csrf
                @method('PUT') <!-- Add this to spoof the PUT method -->
                <div class="modal-header">
                    <h5 class="modal-title">Editar periodo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="inpPeriodoNombre" class="form-control" maxlength="50"
                            required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Monto</label>
                        <input type="number" step="0.01" min="0" name="monto" id="inpPeriodoMonto"
                            class="form-control" required>
                        <small class="text-muted">Usá punto para decimales (ej: 1234.50).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('button[data-bs-target="#modalEditarPeriodo"]');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const form = document.getElementById('formEditarPeriodo');
                const action = this.getAttribute('data-action');
                const nombre = this.getAttribute('data-nombre');
                const monto = this.getAttribute('data-monto');

                form.action = action; // Set the form action dynamically
                document.getElementById('inpPeriodoNombre').value = nombre;
                document.getElementById('inpPeriodoMonto').value = monto;
            });
        });
    });
</script>
