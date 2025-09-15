{{-- @extends('layouts/contentNavbarLayout')

@section('title', 'Categorías')

@section('content')
    <!-- Toast para mensajes -->
    @include('components.toast')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title">Listado de Categorías</h5>
            <a class="btn btn-primary" href="{{ route('categorias.create') }}">
                Crear Categoría
            </a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->nombre }}</td>
                            <td>{{ ucfirst($categoria->estado) }}</td>
                            <td>
                                <a class="btn btn-sm btn-warning" href="{{ route('categorias.edit', $categoria) }}">
                                    Editar
                                </a>
                                <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        <!-- Subcategorías -->
                        @foreach ($categoria->subcategorias as $subcategoria)
                            <tr>
                                <td>&nbsp;&nbsp;— {{ $subcategoria->nombre }}</td>
                                <td>{{ ucfirst($subcategoria->estado) }}</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{ route('categorias.edit', $subcategoria) }}">
                                        Editar
                                    </a>
                                    <form action="{{ route('categorias.destroy', $subcategoria) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Estás seguro de eliminar esta subcategoría?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection --}}
@extends('layouts/contentNavbarLayout')

@section('title', 'Categorías')

@section('content')
    @include('components.toast')

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title">Listado de Categorías</h5>
            <a class="btn btn-primary" href="{{ route('categorias.create') }}">
                Crear Categoría
            </a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->nombre }}</td>
                            <td>{{ ucfirst($categoria->estado) }}</td>
                            <td>
                                <a class="btn btn-sm btn-warning" href="{{ route('categorias.edit', $categoria) }}">
                                    Editar
                                </a>
                                <button class="btn btn-sm btn-danger"
                                    onclick="confirmDelete('{{ route('categorias.destroy', $categoria) }}', '{{ $categoria->nombre }}')">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                        @foreach ($categoria->subcategorias as $subcategoria)
                            <tr>
                                <td>&nbsp;&nbsp;— {{ $subcategoria->nombre }}</td>
                                <td>{{ ucfirst($subcategoria->estado) }}</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{ route('categorias.edit', $subcategoria) }}">
                                        Editar
                                    </a>
                                    <button class="btn btn-sm btn-danger"
                                        onclick="confirmDelete('{{ route('categorias.destroy', $subcategoria) }}', '{{ $subcategoria->nombre }}')">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar <strong id="deleteItemName"></strong>?
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
