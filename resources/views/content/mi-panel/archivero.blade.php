@extends('content.mi-panel.layout')


@section('title', 'Archivero de ' . Auth::user()->fullName())
@section('panelContent')

    @php $user = Auth::user(); @endphp
    <div class="card mb-4">
        <div class="card-header">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                <h5 class="mb-2 mb-md-0">
                    Archivero de {{ $user->fullName() }}, ({{ $user->cuil ?? '—' }})
                </h5>

                <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                    <a href="{{ route('panel.matriculado') }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>

    @foreach ($carpetas as $carpeta)
        @php
            $lista = ($archivos[$carpeta] ?? collect())->filter(
                fn($a) => ($a->nombre ?? ($a->nombre_original ?? '')) !== '(carpeta vacía)',
            );
            $tamanoTotal = $lista->sum('tamano'); // bytes
            // helper de URL de vista: si usás disco 'public' podés usar Storage::url($a->path)
            $verUrl = function ($a) use ($user, $carpeta) {
                // Preferí la ruta ver para control de permisos:
                return route('panel.archivero.ver', $a->id);
            };
        @endphp

        <div class="card mt-4">
            <h5 class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <strong>
                        📁 {{ $carpeta }}
                        <small class="text-muted">({{ number_format($tamanoTotal / 1048576, 2) }} MB)</small>
                    </strong>
                </div>
            </h5>

            <div class="table-responsive text-nowrap">
                @if ($lista->count())
                    <div id="tabla-{{ $carpeta }}">
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
                                @foreach ($lista as $archivo)
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
                                            <a href="{{ asset(env('ARCHIVOS_PATH') . str_replace('/storage/archivos/', '', $archivo->ruta)) }}"
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
                    <p class="text-muted fst-italic card-footer">No hay archivos.</p>
                @endif
            </div>
        </div>
    @endforeach
@endsection
