@extends('content.mi-panel.layout')

@section('panelContent')
    @php
        /** @var \App\Models\User $u */
        $u = Auth::user();
        $isActive = $u->activo === true || $u->activo === 1 || $u->activo === 'si';
        $roleBadge = $u->role === 'admin' ? 'bg-dark' : 'bg-secondary';

        // Iniciales del usuario (para el avatar)
        $initials = collect([$u->nombre ?? '', $u->apellido ?? ''])
            ->filter()
            ->map(fn($p) => mb_strtoupper(mb_substr(trim($p), 0, 1)))
            ->implode('');

        // Helpers para mostrar valores
        function showOr($value, $fallback = '—')
        {
            return filled($value) ? e($value) : $fallback;
        }
    @endphp

    <style>
        .profile-hero {
            background: radial-gradient(1200px 300px at 10% -10%, rgba(32, 42, 154, .10), transparent),
                radial-gradient(900px 250px at 110% -20%, rgba(0, 153, 255, .10), transparent),
                linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
            border: 1px solid rgba(0, 0, 0, .05);
        }

        .avatar-initials {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.25rem;
            background: #202a9a;
            color: #fff;
            box-shadow: 0 8px 18px rgba(32, 42, 154, .25);
        }

        .meta-item {
            font-size: .95rem;
        }

        .meta-item .label {
            color: #6c757d;
            display: block;
            margin-bottom: .25rem;
        }

        .meta-item .value {
            font-weight: 600;
        }

        .card-soft {
            border: 1px solid rgba(0, 0, 0, .06);
        }
    </style>

    {{-- Hero de perfil --}}
    <div class="card profile-hero mb-4">
        <div class="card-body p-4 p-lg-5">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="avatar-initials">{{ $initials ?: 'U' }}</div>
                <div class="flex-grow-1">
                    <h1 class="h4 mb-1">Bienvenido, {{ $u->fullName() }}</h1>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge {{ $roleBadge }}">{{ $u->role === 'admin' ? 'Admin' : 'Matriculado' }}</span>
                        <span class="badge {{ $isActive ? 'bg-success' : 'bg-danger' }}">
                            {{ $isActive ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>
                <div class="ms-auto d-flex gap-2">
                    <a href="{{ route('panel.cuotas') }}" class="btn btn-primary" data-ajax-nav>
                        {{-- <i class="bi bi-cash-coin me-1"></i> --}} Ver mis cuotas
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Datos del usuario en grilla --}}
    <div class="card card-soft mb-4">
        <div class="card-header bg-white">
            <strong>Mis datos</strong>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4 meta-item">
                    <span class="label">Matrícula</span>
                    <span class="value">{{ showOr($u->matricula) }}</span>
                </div>
                <div class="col-md-4 meta-item">
                    <span class="label">Email</span>
                    <span class="value">{{ showOr($u->email) }}</span>
                </div>
                <div class="col-md-4 meta-item">
                    <span class="label">Teléfono</span>
                    <span class="value">{{ showOr($u->telefono, 'N/A') }}</span>
                </div>

                <div class="col-md-4 meta-item">
                    <span class="label">CUIT</span>
                    <span class="value">{{ showOr($u->cuil) }}</span>
                </div>
                <div class="col-md-4 meta-item">
                    <span class="label">Estado</span>
                    <span class="value">
                        <span class="badge {{ $isActive ? 'bg-success' : 'bg-danger' }}">
                            {{ $isActive ? 'Activo' : 'Inactivo' }}
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Cuota actual / acceso rápido --}}
    {{-- <div class="card card-soft mb-4">
        <div class="card-header bg-white d-flex align-items-center justify-content-between">
            <strong>Mi cuota actual</strong>
            @php $estado = $cuotaActual->estado ?? 'Pendiente'; @endphp
            <span
                class="badge {{ $estado === 'Pagada' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $estado }}</span>
        </div>
        <div class="card-body">

            <a href="{{ route('panel.cuotas') }}" class="btn btn-primary" data-ajax-nav>
                Ver mis cuotas
            </a>
        </div>
    </div> --}}

    {{-- Accesos rápidos opcionales (dejá lo que uses) --}}
    {{-- 
    <div class="card card-soft mb-4">
        <div class="card-header bg-white">
            <strong>Accesos rápidos</strong>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-outline-primary" data-ajax-nav>Cuotas</a>
                <a class="btn btn-outline-secondary">Noticias</a>
                <a class="btn btn-outline-secondary">Cursos</a>
                <a class="btn btn-outline-dark">Editar perfil</a>
            </div>
        </div>
    </div> --}}
@endsection
