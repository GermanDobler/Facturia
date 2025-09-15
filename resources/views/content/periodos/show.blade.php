@extends('layouts/contentNavbarLayout')

@section('title', 'Periodo: ' . $periodo->nombre)

@section('content')
    @include('components.toast')

    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">{{ $periodo->nombre }}
                    ({{ $periodo->anio }}/{{ str_pad($periodo->mes, 2, '0', STR_PAD_LEFT) }})</h5>
                <div class="text-muted">
                    Vence: {{ \Illuminate\Support\Carbon::parse($periodo->fecha_vencimiento)->format('d/m/Y') }} ·
                    Monto base: ${{ number_format($periodo->monto, 2, ',', '.') }} ·
                    Estado: <span
                        class="badge bg-{{ ['programado' => 'secondary', 'abierto' => 'primary', 'cerrado' => 'success', 'anulado' => 'danger'][$periodo->estado] }}">{{ ucfirst($periodo->estado) }}</span>
                    ·
                    Visible: <strong>{{ $periodo->es_visible ? 'Sí' : 'No' }}</strong>
                </div>
            </div>
            <a href="{{ route('periodos.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Cuotas del periodo</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Matriculado</th>
                            <th>Monto final</th>
                            <th>Estado</th>
                            <th>Último pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($periodo->cuotas as $c)
                            @php $ultimo = $c->pagos()->latest()->first(); @endphp
                            <tr>
                                <td>{{ $c->id }}</td>
                                <td>{{ optional($c->usuario)->nombre ?? 'Usuario #' . $c->usuario_id }}</td>
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
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No hay cuotas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
