@extends('layouts/contentNavbarLayout')

@section('title', 'Gestión de Categorías')

@section('content')
    <div class="card">
        <form action="{{ isset($categoria) ? route('categorias.update', $categoria) : route('categorias.store') }}"
            method="POST">
            @csrf
            @if (isset($categoria))
                @method('PUT')
            @endif
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title">Crear categoría</h5>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
            <div class="card-body">


                <!-- Nombre -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                        value="{{ $categoria->nombre ?? old('nombre') }}" required>
                </div>

                <!-- Estado -->
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-control">
                        <option value="activo" {{ isset($categoria) && $categoria->estado == 'activo' ? 'selected' : '' }}>
                            Activo
                        </option>
                        <option value="inactivo"
                            {{ isset($categoria) && $categoria->estado == 'inactivo' ? 'selected' : '' }}>
                            Inactivo</option>
                    </select>
                </div>

                <!-- Es subcategoría -->
                <div class="form-group">
                    <label for="es_subcategoria" class="form-label">¿Es subcategoría?</label>
                    <input type="checkbox" name="es_subcategoria" id="es_subcategoria" value="1"
                        {{ old('es_subcategoria', $categoria->es_subcategoria ?? false) ? 'checked' : '' }}>
                </div>

                <!-- Selección de categoría padre -->
                <div class="form-group" id="categoria_padre_select" style="display: none;">
                    <label for="categoria_padre_id">Categoría Padre</label>
                    <select name="categoria_padre_id" id="categoria_padre_id" class="form-control">
                        <option value="">-- Seleccione --</option>
                        @foreach ($categorias as $cat)
                            @if (!$cat->es_subcategoria)
                                <option value="{{ $cat->id }}"
                                    {{ old('categoria_padre_id', $categoria->categoria_padre_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nombre }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const esSubcategoriaCheckbox = document.getElementById('es_subcategoria');
        const categoriaPadreSelect = document.getElementById('categoria_padre_select');
        const categoriaPadreField = document.getElementById('categoria_padre_id');

        if (esSubcategoriaCheckbox) {
            // Función para manejar el cambio del checkbox
            function toggleCategoriaPadreOptions() {
                if (esSubcategoriaCheckbox.checked) {
                    categoriaPadreSelect.style.display = 'block';
                    // Eliminar la opción "Sin Padre" si está presente
                    const sinPadreOption = categoriaPadreField.querySelector('option[value=""]');
                    if (sinPadreOption) {
                        sinPadreOption.remove();
                    }
                } else {
                    categoriaPadreSelect.style.display = 'none';
                    // Restaurar la opción "Sin Padre" si no existe
                    if (!categoriaPadreField.querySelector('option[value=""]')) {
                        const sinPadreOption = document.createElement('option');
                        sinPadreOption.value = '';
                        sinPadreOption.textContent = 'Sin Padre';
                        categoriaPadreField.prepend(sinPadreOption);
                    }
                    // Borrar el valor seleccionado
                    categoriaPadreField.value = '';
                }
            }

            // Configurar estado inicial
            toggleCategoriaPadreOptions();

            // Agregar evento al checkbox
            esSubcategoriaCheckbox.addEventListener('change', toggleCategoriaPadreOptions);
        }
    });
</script>
