@extends('layouts/contentNavbarLayout')

@section('title', 'Gestión de Noticias')

@section('content')
    @include('components.toast')

    <!-- Tabla de Noticias -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5>Lista Noticias</h5>
                <div class="d-flex align-items-center gap-2">
                    <form method="GET" action="{{ route('noticias.index') }}" class="d-flex mb-0">
                        <input type="text" name="search" class="form-control form-control-sm me-2"
                            placeholder="Buscar por titulo" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary btn-sm" type="submit">Buscar</button>
                    </form>
                </div>

            </div>
            <div>
                <a href="{{ route('noticias.create') }}" class="btn btn-primary float-end">Nueva noticia</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Titulo</th>
                        <th>Estado</th>
                        <th>Fuente</th>
                        <th>Publicado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($noticias as $noticia)
                        <tr>
                            <td>{{ $noticia->titulo }}</td>
                            <td>
                                <span
                                    class="badge
                                    @if ($noticia->estado === 'activa') bg-label-primary
                                    @elseif($noticia->estado === 'archivada') bg-label-secondary @endif">
                                    {{ ucfirst($noticia->estado) }}
                                </span>
                            </td>
                            <td>
                                @if ($noticia->fuente_url)
                                    <a href="{{ $noticia->fuente_url }}" target="_blank" rel="noopener noreferrer">
                                        {{ $noticia->subtitulo ?? 'Fuente' }}
                                    </a>
                                @else
                                    {{ $noticia->subtitulo ?? 'propia' }}
                                @endif
                            </td>
                            <td>{{ $noticia->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('noticias.edit', $noticia->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Editar
                                        </a>
                                        <form id="deleteForm-{{ $noticia->id }}" method="POST"
                                            action="{{ route('noticias.destroy', $noticia->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#confirmDeleteModal"
                                                onclick="setDeleteFormAction({{ $noticia->id }})">
                                                <i class="bx bx-trash me-1"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay noticias registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- Modal de Confirmación -->
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Estás seguro de que deseas eliminar esta noticia?
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
            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-2">
                {{ $noticias->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
    <script>
        function setDeleteFormAction(id) {
            const deleteForm = document.getElementById('deleteForm');
            const formToDelete = document.getElementById(`deleteForm-${id}`);
            deleteForm.action = formToDelete.action;
        }
    </script>


@endsection
