@extends('content.mi-panel.layout')


@section('title', 'Archivero de ' . Auth::user()->fullName())
@section('panelContent')
    {{-- ===================== ARCHIVOS GENERALES (COMPARTIDOS) ===================== --}}
    <div class="card mt-5">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📂 ARCHIVOS COMPARTIDOS</h5>
        </div>
    </div>

    @foreach ($carpetasGenerales ?? collect() as $carpetaGen)
        @php
            $itemsGen = ($archivosGenerales[$carpetaGen] ?? collect())->filter(
                fn($a) => ($a->nombre ?? ($a->nombre_original ?? '')) !== '(carpeta vacía)',
            );
            $tamanoGen = $itemsGen->sum('tamano'); // bytes
        @endphp

        <div class="card mt-4">
            <h5 class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <strong>
                        📁 {{ $carpetaGen }}
                        <small class="text-muted">({{ number_format(($tamanoGen ?? 0) / 1048576, 2) }} MB)</small>
                    </strong>
                </div>
            </h5>

            <div class="table-responsive text-nowrap">
                @if ($itemsGen->count())
                    <div id="tabla-gen-{{ Str::slug($carpetaGen) }}">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width:60%">Nombre</th>
                                    <th>Tipo</th>
                                    <th>Tamaño</th>
                                    <th>Fecha</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($itemsGen as $archivo)
                                    @php
                                        $tipo = strtolower(
                                            $archivo->tipo ?? pathinfo($archivo->nombre ?? '', PATHINFO_EXTENSION),
                                        );
                                        $icono = match ($tipo) {
                                            'pdf' => 'filetype-pdf text-danger',
                                            'doc', 'docx' => 'file-earmark text-primary',
                                            'xls', 'xlsx' => 'filetype-xls text-success',
                                            'jpg', 'jpeg', 'png', 'webp' => 'card-image text-warning',
                                            'mp3' => 'music-note-beamed text-info',
                                            default => 'file-earmark text-muted',
                                        };
                                    @endphp
                                    <tr>
                                        <td>
                                            <i class="bi bi-{{ $icono }} me-2"></i>
                                            <span
                                                class="fw-medium">{{ $archivo->nombre_original ?? $archivo->nombre }}</span>
                                        </td>
                                        <td>{{ strtoupper($tipo ?: '—') }}</td>
                                        <td>{{ number_format(($archivo->tamano ?? 0) / 1048576, 2) }} MB</td>
                                        <td>{{ optional($archivo->created_at)->format('d/m/Y H:i') }}</td>
                                        <td class="text-end">
                                            <a href="{{ asset(env('ARCHIVOS_GENERALES_PATH') . str_replace('/storage/archivos_generales', '', $archivo->ruta)) }}"
                                                target="_blank" rel="noopener">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted fst-italic card-footer">No hay archivos en esta carpeta.</p>
                @endif
            </div>
        </div>
    @endforeach

@endsection
