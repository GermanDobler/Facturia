{{-- @extends('layouts.contentNavbarLayout')

@section('title', 'Consultas')

@section('content')
    @include('components.toast')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <form method="GET" action="{{ route('consultas') }}" class="d-flex justify-content-between">
                <input type="text" name="search" id="search" class="form-control" placeholder="Buscar por nombre o email"
                    value="{{ request()->input('search') }}">
                <button type="submit" class="btn btn-secondary">Buscar</button>
            </form>
            <!-- Puedes agregar otras opciones de filtrado si lo deseas -->
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($contacts->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay consultas disponibles.</td>
                        </tr>
                    @else
                        @foreach ($contacts as $contact)
                            <tr>
                                <td>{{ $contact->first_name }}</td>
                                <td>{{ $contact->phone }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('consultas.show', $contact->id) }}"
                                        class="btn btn-info btn-sm">Ver</a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal" data-id="{{ $contact->id }}"
                                        data-nombre="{{ $contact->first_name }}">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

            <!-- Enlaces de paginación -->
            <div class="d-flex justify-content-center mt-2">
                {{ $contacts->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar la consulta de <strong id="contactNombre"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('consultas.destroy', $contact->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('¿Estás seguro de eliminar esta consulta?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const contactId = button.getAttribute('data-id');
                const contactNombre = button.getAttribute('data-nombre');

                // Asegurar que la acción del formulario sea la correcta
                const deleteForm = document.getElementById('deleteForm');
                deleteForm.action = "{{ route('consultas.destroy', '') }}/" + contactId;

                // Actualiza el nombre de la consulta en el modal
                document.getElementById('contactNombre').textContent = contactNombre;
            });
        });
    </script>
@endpush --}}
@extends('layouts/contentNavbarLayout')

@section('title', 'Consultas')

@section('content')
    @include('components.toast')

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <form method="GET" action="{{ route('consultas') }}" class="d-flex justify-content-between">
                <input type="text" name="search" id="search" class="form-control" placeholder="Buscar por nombre o email"
                    value="{{ request()->input('search') }}">
                <button type="submit" class="btn btn-secondary">Buscar</button>
            </form>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($contacts->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay consultas disponibles.</td>
                        </tr>
                    @else
                        @foreach ($contacts as $contact)
                            <tr>
                                <td>{{ $contact->first_name }}</td>
                                <td>{{ $contact->phone }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('consultas.show', $contact->id) }}"
                                        class="btn btn-info btn-sm">Ver</a>
                                    <button class="btn btn-danger btn-sm"
                                        onclick="confirmDelete('{{ route('consultas.destroy', $contact->id) }}', '{{ $contact->first_name }}')">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-2">
                {{ $contacts->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar la consulta de <strong id="deleteItemName"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(route, name) {
            document.getElementById('deleteForm').action = route;
            document.getElementById('deleteItemName').textContent = name;
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>

@endsection
