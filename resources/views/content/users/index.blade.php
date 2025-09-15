@extends('layouts/contentNavbarLayout')

@section('title', 'Usuarios Registrados')

{{-- Estilos rápidos para la tabla --}}
<style>
    .table thead th {
        white-space: nowrap;
    }

    .sticky-top {
        position: sticky;
        top: 0;
    }

    .bg-label-success {
        background: rgba(113, 221, 55, .16);
        color: #71dd37;
    }

    .bg-label-danger {
        background: rgba(255, 62, 29, .16);
        color: #ff3e1d;
    }
</style>

@section('content')
    @include('components.toast')
    <div class="card">
        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center gap-2">
            {{-- Filtros --}}
            <form action="{{ route('users.index') }}" method="GET" class="mb-0 flex-grow-1">
                <div class="row g-2 align-items-center">

                    {{-- Búsqueda --}}
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bx bx-search"></i></span>
                            <input type="text" name="search" class="form-control"
                                placeholder="Buscar por nombre, matrícula, email o CUIL" value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- Activo --}}
                    <div class="col-sm-2">
                        <select name="activo" class="form-select">
                            <option value="">Activo (todos)</option>
                            <option value="si" @selected(request('activo') === 'si')>Solo activos</option>
                            <option value="no" @selected(request('activo') === 'no')>Solo inactivos</option>
                        </select>
                    </div>

                    {{-- Al día --}}
                    <div class="col-sm-2">
                        <select name="al_dia" class="form-select">
                            <option value="">Todos</option>
                            <option value="si" @selected(request('al_dia') === 'si')>Al día</option>
                        </select>
                    </div>

                    {{-- Botones --}}
                    <div class="col-sm d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-filter-alt"></i> Filtrar
                        </button>
                        @if (request()->hasAny(['search', 'activo', 'al_dia', 'ultimo_pago_desde', 'ultimo_pago_hasta']))
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-x-circle"></i> Limpiar
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Botón crear usuario --}}
            <a href="{{ route('users.create') }}" class="btn btn-success">
                <i class="bx bx-user-plus"></i> Nuevo Usuario
            </a>
        </div>


        <div class="table-responsive text-nowrap">
            <table class="table align-middle">
                <thead class="table-light sticky-top" style="z-index:1;">
                    <tr>
                        <th style="min-width:250px">Usuario</th>
                        <th class="px-0" style="min-width:140px">Matrícula</th>
                        <th style="min-width:130px">CUIT</th>
                        <th style="min-width:220px">Email</th>
                        <th style="min-width:90px">Activo</th>
                        <th style="min-width:90px">Al día</th>
                        <th style="min-width:150px">Último pago</th>
                        <th style="min-width:120px" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        @if ($user->role !== 'admin')
                            <tr>
                                {{-- Usuario: Apellido, Nombre --}}
                                <td class="fw-medium">
                                    {{ $user->apellido }}, {{ $user->nombre }}
                                </td>

                                {{-- Matrícula en monoespacio para legibilidad --}}
                                <td class="px-0"><code class="fw-bold">{{ $user->matricula ?: '—' }}</code></td>

                                {{-- CUIT/CUIL con truncado si es largo --}}
                                <td class="text-truncate" style="max-width:150px" title="{{ $user->cuil }}">
                                    {{ $user->cuil ?: '—' }}</td>

                                {{-- Email clickable + truncado --}}
                                <td class="text-truncate" style="max-width:260px">
                                    @if ($user->email)
                                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Activo --}}
                                <td>
                                    <span class="badge bg-label-{{ $user->activo === 'si' ? 'success' : 'danger' }}">
                                        {{ $user->activo === 'si' ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>

                                {{-- Al día / Deuda (usa cuotas_pendientes_count cargado en el controller) --}}
                                <td>
                                    @if (($user->cuotas_pendientes_count ?? 0) == 0)
                                        <span class="badge bg-label-success">Al día</span>
                                    @else
                                        <span class="badge bg-label-danger"
                                            title="Cuotas con estado pendiente/rechazado/enviado">
                                            Deuda ({{ $user->cuotas_pendientes_count }})
                                        </span>
                                    @endif
                                </td>

                                {{-- Último pago: usa pagos (limit 1, aprobado, pagado_en desc) y periodo desde ultimaCuotaAprobada --}}
                                <td>
                                    @php
                                        // $user->ultimo_pago_en viene del withMax (puede ser null)
                                        $fechaUltimoPago = $user->ultimo_pago_en
                                            ? \Carbon\Carbon::parse($user->ultimo_pago_en)
                                            : null;
                                    @endphp
                                    @if ($fechaUltimoPago)
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">{{ $fechaUltimoPago->format('d/m/Y') }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>


                                {{-- Acciones --}}
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-light dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Opciones
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="{{ route('users.edit', $user->id) }}" class="dropdown-item"
                                                title="Editar usuario">
                                                <i class="bx bx-edit-alt me-1"></i> Editar
                                            </a>

                                            <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->apellido }}, {{ $user->nombre }}"
                                                title="Eliminar usuario">
                                                <i class="bx bx-trash me-1"></i> Borrar
                                            </button>

                                            <a href="{{ route('users.archivero', $user->id) }}" class="dropdown-item"
                                                title="Ver archivero">
                                                <i class="bx bx-archive me-1"></i> Archivero
                                            </a>

                                            <a href="{{ url('/admin/cuotas?estado=&periodo_id=&usuario_id=' . $user->id) }}"
                                                class="dropdown-item" title="Ver cuotas">
                                                <i class="bx bx-money me-1"></i> Cuotas
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">No se encontraron usuarios con los filtros aplicados.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Eliminar Usuario -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="deleteUserForm">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        ¿Seguro que deseas eliminar al usuario <strong id="userName"></strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var userId = button.getAttribute('data-user-id');
            var userName = button.getAttribute('data-user-name');

            // Ajustar el form con la ruta correcta
            var form = document.getElementById('deleteUserForm');
            form.action = '/admin/users/' + userId;

            // Poner nombre en el texto
            document.getElementById('userName').textContent = userName;
        });
    });
</script>
