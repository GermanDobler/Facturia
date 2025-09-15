@extends('layouts/contentNavbarLayout')

@section('title', 'Mensajes')

@section('content')
    @include('components.toast')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <form method="GET" action="{{ route('mensajes.index') }}" class="d-flex justify-content-between">
                <input type="text" name="search" id="search" class="form-control" placeholder="Buscar por nombre"
                    value="{{ request()->input('search') }}">
                <button type="submit" class="btn btn-secondary">Buscar</button>
            </form>
        </div>
        <div class="card-body">
            @if ($mensajes->isEmpty())
                <div class="alert alert-warning" role="alert">
                    No hay mensajes para mostrar.
                </div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mensajes as $mensaje)
                            <tr>
                                <td>{{ $mensaje->nombre }}</td>
                                <td>{{ $mensaje->email }}</td>
                                <td>{{ $mensaje->numero }}</td>
                                <td>{{ $mensaje->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('mensajes.show', $mensaje) }}" class="btn btn-info btn-sm">Ver</a>
                                    <form action="{{ route('mensajes.destroy', $mensaje) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            <!-- Modal de Confirmación -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de que deseas eliminar el mensaje de <strong id="mensajeNombre"></strong>?</p>
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('mensajes.destroy', ':id') }}" id="deleteForm" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-2">
                {{ $mensajes->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const mensajeId = button.getAttribute('data-id');
            const mensajeNombre = button.getAttribute('data-nombre');

            // Actualiza el nombre del mensaje en el modal
            const mensajeNombreElement = document.getElementById('mensajeNombre');
            mensajeNombreElement.textContent = mensajeNombre;

            // Actualiza la acción del formulario
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/admin/mensajes/${mensajeId}`;
        });
    </script>
@endpush
