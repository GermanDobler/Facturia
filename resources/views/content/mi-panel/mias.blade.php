@extends('content.mi-panel.layout')

@section('panelContent')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Mis cuotas</h5>
                <small class="text-muted">Solo ves periodos abiertos y visibles</small>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Periodo</th>
                            <th>Vence</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th class="text-end">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cuotas as $c)
                            @php
                                $periodo = $c->periodo;
                                $puedeSubir = in_array($c->estado, ['pendiente', 'rechazado']);
                                $modalId = 'modalComprobante' . $c->id;
                            @endphp
                            <tr>
                                <td>{{ $periodo?->nombre ?? '#' . $c->periodo_id }}</td>
                                <td>{{ $periodo ? \Illuminate\Support\Carbon::parse($periodo->fecha_vencimiento)->format('d/m/Y') : '—' }}
                                </td>
                                <td>${{ number_format($c->monto_final, 2, ',', '.') }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ ['pendiente' => 'secondary', 'enviado' => 'info', 'aprobado' => 'success', 'rechazado' => 'danger', 'exento' => 'warning'][$c->estado] }}">
                                        {{ ucfirst($c->estado) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    @if ($puedeSubir)
                                        <form id="upload-form-{{ $c->id }}"
                                            action="{{ route('panel.pagos.store', $c) }}" method="POST"
                                            enctype="multipart/form-data" class="d-inline">
                                            @csrf
                                            <input type="file" id="file-{{ $c->id }}" name="archivos[]"
                                                class="d-none" accept=".pdf,.jpg,.jpeg,.png,.webp" multiple
                                                onchange="handleUploadChange({{ $c->id }})">
                                            <button type="button" id="btn-{{ $c->id }}"
                                                class="btn btn-primary btn-sm"
                                                onclick="document.getElementById('file-{{ $c->id }}').click()">
                                                Subir comprobante
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No tenés cuotas visibles en este
                                    momento
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $cuotas->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
<script>
    function handleUploadChange(id) {
        const input = document.getElementById('file-' + id);
        if (!input || !input.files || input.files.length === 0) return;

        const btn = document.getElementById('btn-' + id);
        const form = document.getElementById('upload-form-' + id);

        // UI feedback rápido
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = 'Enviando...';
        }

        form.submit();
    }
</script>
