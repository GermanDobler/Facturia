@extends('layouts/contentNavbarLayout')
@section('title', "Resultados del lote: $carpeta")

@section('content')
    @include('components.toast')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Resultados — {{ $carpeta }}</h4>
        <a href="{{ route('archivos.facturas.index') }}" class="btn btn-secondary">Volver</a>
    </div>

    <div class="row g-3 mb-3">
        @foreach (['subtotal' => 'Subtotal', 'base_imponible' => 'Base imponible', 'iva' => 'IVA', 'total' => 'Total'] as $k => $label)
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-muted small">{{ $label }}</div>
                        <div class="h5 mb-0">{{ number_format($totales[$k] ?? 0, 2, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{-- KPIs extra del lote --}}
    <div class="row g-3 mb-3">
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">PDFs en lote</div>
                    <div class="h5 mb-0">{{ $kpis['pdfs_totales'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Procesadas</div>
                    <div class="h5 mb-0">{{ $kpis['procesadas'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Sin procesar</div>
                    <div class="h5 mb-0">{{ $kpis['sin_procesar'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Promedio Total</div>
                    <div class="h5 mb-0">{{ number_format($kpis['promedio_total'] ?? 0, 2, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Inconsistencias</div>
                    <div class="h5 mb-0">{{ $kpis['inconsistentes'] ?? 0 }}</div>
                    <small class="text-muted">| Total vs (Subtotal/Base) + IVA</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts + Top personas --}}
    <div class="row g-3 mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Total por día</div>
                <div class="card-body">
                    <canvas id="chartDia" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Top personas por total</div>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Persona</th>
                        <th class="text-end"># Facturas</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topPersonas as $t)
                        <tr>
                            <td>{{ $t['nombre_persona'] }}</td>
                            <td class="text-end">{{ $t['n'] }}</td>
                            <td class="text-end">{{ number_format($t['suma'], 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">Sin datos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>PDF</th>
                        <th>Nro</th>
                        <th>Fecha</th>
                        <th>Nombre</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-end">IVA</th>
                        <th class="text-end">Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($archivos as $a)
                        @php $e = $a->facturaExtraccion; @endphp
                        <tr>
                            <td>{{ $a->nombre_original }}</td>
                            <td>{{ $e->nro_factura ?? '—' }}</td>
                            <td>{{ $e?->fecha_factura?->format('d/m/Y') ?? '—' }}</td>
                            <td>{{ $e->nombre_persona ?? '—' }}</td>
                            <td class="text-end">
                                {{ $e?->subtotal !== null ? number_format($e->subtotal, 2, ',', '.') : '—' }}
                            </td>
                            <td class="text-end">{{ $e?->iva !== null ? number_format($e->iva, 2, ',', '.') : '—' }}</td>
                            <td class="text-end">{{ $e?->total !== null ? number_format($e->total, 2, ',', '.') : '—' }}
                            </td>
                            <td class="text-end">
                                @if ($e)
                                    <button class="btn btn-sm btn-outline-secondary" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#it{{ $a->id }}">
                                        Ítems ({{ $e->items->count() }})
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @if ($e)
                            <tr class="collapse" id="it{{ $a->id }}">
                                <td colspan="8">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Descripción</th>
                                                    <th class="text-end">Cant.</th>
                                                    <th class="text-end">P. Unit.</th>
                                                    <th class="text-end">Importe</th>
                                                    <th>Moneda</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($e->items as $it)
                                                    <tr>
                                                        <td>{{ $it->descripcion }}</td>
                                                        <td class="text-end">
                                                            {{ $it->cantidad !== null ? number_format($it->cantidad, 2, ',', '.') : '—' }}
                                                        </td>
                                                        <td class="text-end">
                                                            {{ $it->precio_unitario }}
                                                        </td>
                                                        <td class="text-end">
                                                            {{ $it->importe !== null ? number_format($it->importe, 2, ',', '.') : '—' }}
                                                        </td>
                                                        <td>{{ $it->moneda ?? ($e->moneda ?? '—') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted">Sin ítems.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Sin resultados para este lote.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $archivos->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
<script>
    (function() {
        function initCharts() {
            // Datos desde el controller
            const labelsDia = @json($serieDiasLabels ?? []);
            const dataDia = @json($serieDiasData ?? []);
            const monedas = @json($monedas ?? []);

            // ----- Chart: Total por día -----
            const elDia = document.getElementById('chartDia');
            if (elDia && typeof Chart !== 'undefined') {
                const ctx1 = elDia.getContext('2d');
                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: labelsDia,
                        datasets: [{
                            label: 'Total',
                            data: dataDia,
                            tension: 0.2
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // ----- Chart: Monedas -----
            const elMon = document.getElementById('chartMoneda');
            if (elMon && typeof Chart !== 'undefined') {
                const ctx2 = elMon.getContext('2d');
                const mLabels = monedas.map(x => x.moneda);
                const mData = monedas.map(x => x.suma);
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: mLabels,
                        datasets: [{
                            data: mData
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        }

        // Asegurar que el DOM esté listo:
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCharts);
        } else {
            initCharts();
        }
    })();
</script>
