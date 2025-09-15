@extends('layouts/contentNavbarLayout')

@section('title', 'Productos')

@section('content')
    @include('components.toast')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <form method="GET" action="{{ route('productos.index') }}" class="d-flex justify-content-between mb-0">
                <input type="text" name="search" id="search" class="form-control" placeholder="Buscar nombre o codigo"
                    value="{{ request()->input('search') }}">
                <button type="submit" class="btn btn-secondary">Buscar</button>
            </form>
            @if (!in_array(auth()->user()->role, ['admin_stock', 'admin_precios']))
                <a href="{{ route('productos.create') }}" class="btn btn-primary">Crear Producto</a>
            @endif
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Codigo</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $producto)
                        <tr>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->codigo }}</td>
                            <td>${{ number_format($producto->precio, 2) }}</td>
                            <td>{{ $producto->stock ? $producto->stock : ' - ' }}</td>
                            <td>{{ ucfirst($producto->estado) }}</td>
                            <td>
                                @if (Auth::user()->role == 'admin_general')
                                    <a href="{{ route('productos.edit', $producto) }}"
                                        class="btn btn-warning btn-sm">Editar</a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        onclick="confirmDelete('{{ route('productos.destroy', $producto) }}', '{{ $producto->nombre }}')">
                                        Eliminar
                                    </button>
                                @elseif(Auth::user()->role == 'admin_stock')
                                    <a href="{{ route('productos.editStock', $producto) }}"
                                        class="btn btn-warning btn-sm">Editar Stock</a>
                                @elseif(Auth::user()->role == 'admin_precios')
                                    <a href="{{ route('productos.editPrecios', $producto) }}"
                                        class="btn btn-warning btn-sm">Editar Precios</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-2">
                {{ $productos->links('pagination::bootstrap-4') }}
            </div>
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
            const modalElement = document.getElementById('deleteModal');
            const deleteModal = bootstrap.Modal.getOrCreateInstance(modalElement);
            deleteModal.show();
        }
    </script>

@endsection
