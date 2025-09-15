@extends('layouts/contentNavbarLayout')
@section('title', 'Comprobantes pendientes')

@section('content')
    @include('components.toast')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Comprobantes en revisión</h5>
            <form method="GET" class="d-flex">
                <input type="text" name="q" class="form-control form-control-sm me-2"
                    placeholder="Buscar por email / referencia" value="{{ request('q') }}">
                <button class="btn btn-outline-secondary btn-sm" type="submit">Buscar</button>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Cuota</th>
                            <th>Monto declarado</th>
                            <th>Fecha transf.</th>
                            <th>Referencia</th>
                            <th>Archivo</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($pendientes as $cp)
                            <tr>
                                <td>#{{ $cp->id }}</td>
                                <td>
                                    <div class="fw-medium">
                                        {{ $cp->instancia->user->nombre ?? '' }}
                                        {{ $cp->instancia->user->apellido ?? '' }}
                                    </div>
                                    <div class="text-muted small">{{ $cp->instancia->user->email }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-label-info">{{ $cp->instancia->cuota->label ?? '-' }}</span><br>
                                    <small class="text-muted">Instancia: {{ ucfirst($cp->instancia->estado) }}</small>
                                </td>
                                <td>${{ number_format($cp->monto_declarado, 2, ',', '.') }}</td>
                                <td>{{ $cp->fecha_transferencia ? \Illuminate\Support\Carbon::parse($cp->fecha_transferencia)->format('d/m/Y') : '-' }}
                                </td>
                                <td>{{ $cp->referencia ?? '-' }}</td>
                                <td>
                                    @php $url = $cp->archivo_url ?? null; @endphp
                                    @if ($url)
                                        <a class="btn btn-sm btn-outline-primary" href="{{ $url }}"
                                            target="_blank">
                                            <i class="bx bx-file me-1"></i> Ver
                                        </a>
                                    @else
                                        <span class="badge bg-label-secondary">Sin archivo</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <form method="POST" action="{{ route('comprobantes.aprobar', $cp) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-success" type="submit">
                                                <i class="bx bx-check me-1"></i> Aprobar
                                            </button>
                                        </form>

                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#rechazarModal-{{ $cp->id }}">
                                            <i class="bx bx-x me-1"></i> Rechazar
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Rechazar --}}
                            <div class="modal fade" id="rechazarModal-{{ $cp->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form class="modal-content" method="POST"
                                        action="{{ route('comprobantes.rechazar', $cp) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Rechazar comprobante #{{ $cp->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Motivo (opcional)</label>
                                                <input type="text" name="motivo" class="form-control"
                                                    placeholder="Ej: Monto insuficiente / ilegible">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" type="button"
                                                data-bs-dismiss="modal">Cancelar</button>
                                            <button class="btn btn-danger" type="submit">Rechazar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay comprobantes pendientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-2">
                {{ $pendientes->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
