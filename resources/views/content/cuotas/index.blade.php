@extends('layouts/contentNavbarLayout')

@section('title', 'Cuotas')

@section('content')
    @include('components.toast')

    @php
        // Para filtros rápidos (podés pasarlos desde el controlador si preferís)
        $periodos = \App\Models\Periodo::orderByDesc('anio')->orderByDesc('mes')->limit(100)->get();
    @endphp

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Cuotas</h5>
                <small class="text-muted">Filtrá por estado/periodo/usuario</small>
            </div>
            <form method="GET" class="row gx-2">
                <div class="col-auto">
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Estado (todos)</option>
                        @foreach (['pendiente', 'enviado', 'aprobado', 'rechazado', 'exento'] as $e)
                            <option value="{{ $e }}" @selected(request('estado') === $e)>{{ ucfirst($e) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select name="periodo_id" class="form-select form-select-sm">
                        <option value="">Periodo (todos)</option>
                        @foreach ($periodos as $p)
                            <option value="{{ $p->id }}" @selected(request('periodo_id') == $p->id)>{{ $p->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <input type="number" name="usuario_id" class="form-control form-control-sm" placeholder="Usuario ID"
                        value="{{ request('usuario_id') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-secondary btn-sm">Filtrar</button>
                </div>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            {{-- <th>#</th> --}}
                            <th>Usuario</th>
                            <th>Periodo</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Pago (último)</th>
                            <th>Archivos</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cuotas as $c)
                            @php $ultimo = $c->pagos()->latest()->first(); @endphp
                            <tr>
                                {{-- <td>{{ $c->id }}</td> --}}
                                <td>
                                    <a href="{{ url('/admin/users?search=' . $c->usuario->cuil) }}">
                                        {{ $c->usuario->cuil }}
                                    </a>
                                </td>
                                <td>{{ optional($c->periodo)->nombre ?? '#' . $c->periodo_id }}</td>
                                <td>${{ number_format($c->monto_final, 2, ',', '.') }}</td>
                                <td><span
                                        class="badge bg-{{ ['pendiente' => 'secondary', 'enviado' => 'info', 'aprobado' => 'success', 'rechazado' => 'danger', 'exento' => 'warning'][$c->estado] }}">{{ ucfirst($c->estado) }}</span>
                                </td>
                                <td>
                                    @if ($ultimo)
                                        <span
                                            class="badge bg-{{ ['en_revision' => 'info', 'aprobado' => 'success', 'rechazado' => 'danger', 'anulado' => 'secondary'][$ultimo->estado] }}">{{ str_replace('_', ' ', $ultimo->estado) }}</span>
                                        @if ($ultimo->referencia)
                                            · Ref: {{ $ultimo->referencia }}
                                        @endif
                                        @if ($ultimo->pagado_en)
                                            · {{ $ultimo->pagado_en->format('d/m/Y H:i') }}
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td style="max-width:300px">
                                    @if ($ultimo)
                                        @foreach ($ultimo->archivos as $a)
                                            <div class="small">
                                                <a href="{{ asset(env('COMPROBANTES_PATH') . $a->ruta) }}"
                                                    target="_blank">{{ basename($a->ruta) }}</a>
                                                <span class="text-muted">({{ $a->mime }},
                                                    {{ number_format($a->tamano / 1024, 0) }} KB)</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if ($ultimo && $ultimo->estado === 'en_revision')
                                        <!-- Botón: Aprobar (abre modal genérico) -->
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalAccionPago" data-mode="aprobar"
                                            data-action="{{ route('pagos.aprobar', $ultimo) }}"
                                            data-title="Aprobar pago #{{ $ultimo->id }}"
                                            data-body="¿Confirmás aprobar el pago y marcar la cuota como aprobada?">
                                            Aprobar
                                        </button>

                                        <!-- Botón: Rechazar (abre modal genérico) -->
                                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalAccionPago" data-mode="rechazar"
                                            data-action="{{ route('pagos.rechazar', $ultimo) }}"
                                            data-title="Rechazar pago #{{ $ultimo->id }}"
                                            data-body="Ingresá el motivo del rechazo (será visible para el usuario).">
                                            Rechazar
                                        </button>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Sin cuotas para los filtros seleccionados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $cuotas->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    <!-- Modal genérico para Aprobar / Rechazar -->
    <div class="modal fade" id="modalAccionPago" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="formAccionPago" method="POST" action="#">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAccionPagoTitle">Acción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <p id="modalAccionPagoBody" class="mb-3">Confirmá la acción.</p>

                    <!-- Campo visible solo para RECHAZAR -->
                    <div id="grupoMotivo" class="d-none">
                        <label class="form-label">Motivo del rechazo</label>
                        <textarea name="motivo" id="motivoRechazo" class="form-control" rows="4"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnConfirmarAccion" class="btn">Confirmar</button>
                </div>
            </form>
        </div>
    </div>

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modalEl = document.getElementById('modalAccionPago');
        var form = document.getElementById('formAccionPago');
        var titleEl = document.getElementById('modalAccionPagoTitle');
        var bodyEl = document.getElementById('modalAccionPagoBody');
        var grupoMotivo = document.getElementById('grupoMotivo');
        var motivoRechazo = document.getElementById('motivoRechazo');
        var btnConfirmar = document.getElementById('btnConfirmarAccion');

        modalEl.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var mode = button.getAttribute('data-mode'); // 'aprobar' | 'rechazar'
            var action = button.getAttribute('data-action'); // URL de la acción
            var title = button.getAttribute('data-title') || 'Acción';
            var body = button.getAttribute('data-body') || '';

            // Setear action y textos
            form.action = action;
            titleEl.textContent = title;
            bodyEl.textContent = body;

            // Reset motivo
            motivoRechazo.value = '';

            // Modo aprobar: no pide motivo, botón verde
            if (mode === 'aprobar') {
                grupoMotivo.classList.add('d-none');
                motivoRechazo.required = false;
                btnConfirmar.classList.remove('btn-outline-danger');
                btnConfirmar.classList.add('btn-success');
                btnConfirmar.textContent = 'Aprobar';
            }

            // Modo rechazar: muestra motivo obligatorio, botón rojo
            if (mode === 'rechazar') {
                grupoMotivo.classList.remove('d-none');
                motivoRechazo.required = true;
                btnConfirmar.classList.remove('btn-success');
                btnConfirmar.classList.add('btn-outline-danger');
                btnConfirmar.textContent = 'Rechazar';
            }
        });
    });
</script>
