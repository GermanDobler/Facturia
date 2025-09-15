@extends('layouts/contentNavbarLayout')
@section('title', 'Autoridades')

@section('content')
    @include('components.toast')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center gap-2">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="q" value="{{ $q }}" class="form-control"
                    placeholder="Buscar por nombre, apellido o cargo">
                <button class="btn btn-secondary">Buscar</button>
            </form>
            <a class="btn btn-primary" href="{{ route('autoridades.create') }}">Nueva autoridad</a>
        </div>

        <div class="card-body">
            @if ($items->count() === 0)
                <div class="alert alert-warning">No hay autoridades.</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Cargo</th>
                                <th>Orden</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-body">
                            @foreach ($items as $it)
                                <tr class="row-draggable" data-id="{{ $it->id }}">
                                    <td>{{ $it->nombre }}</td>
                                    <td>{{ $it->apellido }}</td>
                                    <td>{{ $it->cargo }}</td>
                                    <td data-col="orden">{{ $it->orden ?? '—' }}</td>
                                    <td class="text-end no-drag">
                                        <a class="btn btn-sm btn-outline-primary"
                                            href="{{ route('autoridades.edit', $it) }}">Editar</a>
                                        <form action="{{ route('autoridades.destroy', $it) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('¿Eliminar {{ $it->nombre_completo }}?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

<style>
    /* Drag & drop para toda la fila */
    .row-draggable {
        cursor: grab;
        user-select: none;
        touch-action: manipulation;
    }

    .row-draggable:active {
        cursor: grabbing;
    }

    tbody tr.sortable-chosen {
        opacity: .95;
    }

    tbody tr.dragging {
        background: #393e44;
    }

    /* Evitar que botones/links activen el drag */
    .no-drag {
        user-select: auto;
    }
</style>

<script>
    (function() {
        // Iniciar cuando el DOM esté listo (por si el layout inyecta scripts deferidos)
        const ready = (fn) => (document.readyState !== 'loading') ? fn() : document.addEventListener(
            'DOMContentLoaded', fn);

        const boot = () => {
            const tbody = document.getElementById('sortable-body');
            if (!tbody) return;

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Evitar doble init si se reinyecta la vista
            if (tbody.__sortableInit) return;
            tbody.__sortableInit = true;

            new Sortable(tbody, {
                animation: 150,
                draggable: 'tr', // toda la fila arrastra
                handle: undefined, // sin handle
                forceFallback: true, // más estable en tablas/Firefox
                fallbackOnBody: true,
                fallbackTolerance: 3,
                ghostClass: 'dragging',
                chosenClass: 'sortable-chosen',
                scroll: true,
                scrollSensitivity: 60,
                scrollSpeed: 16,
                // No iniciar drag si clickean elementos interactivos
                filter: '.no-drag, .no-drag *, a, button, .btn, input, textarea, select, label, [role="button"]',
                preventOnFilter: false,
                setData: function(dataTransfer, dragEl) {
                    // Firefox requiere setData para HTML5 DnD (aunque usemos fallback viene bien)
                    dataTransfer.setData('text/plain', dragEl.dataset.id || '');
                },
                onEnd: function() {
                    const ids = Array.from(tbody.querySelectorAll('tr')).map(tr => tr.dataset.id);

                    fetch("{{ route('autoridades.sort') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({
                                ids
                            })
                        })
                        .then(r => r.json())
                        .then(() => {
                            // Actualizar columna "Orden" en UI
                            Array.from(tbody.querySelectorAll('tr')).forEach((tr, i) => {
                                const ordenCell = tr.querySelector(
                                    'td[data-col="orden"]');
                                if (ordenCell) ordenCell.textContent = (i + 1);
                            });
                        })
                        .catch((e) => {
                            console.error(e);
                            alert('No se pudo guardar el nuevo orden.');
                        });
                }
            });
        };

        // Si Sortable ya existe, iniciar; si no, cargar fallback y luego iniciar
        const start = () => {
            if (window.Sortable) boot();
            else loadSortable(boot);
        };

        const loadSortable = (cb) => {
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js';
            s.onload = cb;
            s.onerror = () => console.error('No se pudo cargar SortableJS');
            document.head.appendChild(s);
        };

        ready(start);
    })();
</script>
