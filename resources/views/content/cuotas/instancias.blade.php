@extends('layouts/contentNavbarLayout')
@section('title', 'Instancias de ' . $cuota->label)

@section('content')
    @include('components.toast')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">Instancias — {{ $cuota->label }}</h5>
                <p class="text-muted mb-0">
                    Vence: {{ \Illuminate\Support\Carbon::parse($cuota->vence_el)->format('d/m/Y') }} ·
                    Estado cuota: <strong>{{ ucfirst($cuota->estado) }}</strong>
                </p>
            </div>
            <a href="{{ route('cuotas.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
        </div>

        <div class="card-body">
            <form method="GET" class="row g-2 mb-3">
                <div class="col-auto">
                    <input type="text" name="q" class="form-control form-control-sm"
                        placeholder="Buscar nombre, apellido o email" value="{{ request('q') }}">
                </div>
                <div class="col-auto">
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach (['pendiente', 'revision', 'pagada', 'rechazada', 'exenta', 'cancelada'] as $opt)
                            <option value="{{ $opt }}" @selected(request('estado') === $opt)>{{ ucfirst($opt) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-secondary btn-sm" type="submit">Filtrar</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th style="width:140px">Monto</th>
                            <th style="width:170px">Estado</th>
                            <th>Pagado</th>
                            <th>Últ. pago</th>
                            <th>Pers.</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @php
                            $instEstadoClass = [
                                'pendiente' => 'bg-label-danger',
                                'revision' => 'bg-label-warning',
                                'pagada' => 'bg-label-success',
                                'rechazada' => 'bg-label-secondary',
                                'exenta' => 'bg-label-info',
                                'cancelada' => 'bg-label-dark',
                            ];
                        @endphp

                        @forelse ($instancias as $inst)
                            <tr>
                                <td class="fw-medium">
                                    {{ $inst->user->nombre ?? '' }} {{ $inst->user->apellido ?? '' }}
                                </td>
                                <td class="text-muted">{{ $inst->user->email }}</td>

                                <td>
                                    <form action="{{ route('instancias.update', $inst) }}" method="POST"
                                        class="d-flex align-items-center mb-0">
                                        @csrf @method('PATCH')
                                        <input type="number" step="0.01" name="monto"
                                            class="form-control form-control-sm" value="{{ $inst->monto }}">
                                </td>

                                <td>
                                    <select name="estado" class="form-select form-select-sm">
                                        @foreach (array_keys($instEstadoClass) as $opt)
                                            <option value="{{ $opt }}" @selected($inst->estado === $opt)>
                                                {{ ucfirst($opt) }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>${{ number_format($inst->monto_pagado, 2, ',', '.') }}</td>
                                <td>{{ $inst->ultimo_pago_at ? $inst->ultimo_pago_at->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    <span
                                        class="badge {{ $inst->personalizada ? 'bg-label-primary' : 'bg-label-secondary' }}">
                                        {{ $inst->personalizada ? 'Sí' : 'No' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-primary" type="submit">Guardar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay instancias.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-2">
                {{ $instancias->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
