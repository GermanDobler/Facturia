<div class="row">
    @forelse ($productos as $producto)
        <div class="col-md-4 mb-4">
            <a href="{{ route('producto', ['slug' => Str::slug($producto->nombre) . '-' . $producto->id]) }}">
                <div class="card">
                    <picture class="position-relative overflow-hidden d-block bg-light">
                        @if ($producto->imagenes->isNotEmpty())
                            <img class="w-100 img-fluid position-relative z-index-bajo" title="{{ $producto->nombre }}"
                                src="{{ asset(env('IMAGE_PATH') . basename($producto->imagenes->first()->url)) }}"
                                alt="{{ $producto->nombre }}">
                        @else
                            <img class="w-100 img-fluid position-relative z-index-bajo" title="{{ $producto->nombre }}"
                                src="{{ asset('/assets/img/producto-1.png') }}" alt="{{ $producto->nombre }}">
                        @endif
                    </picture>
                    <div class="card-body">
                        <h5 class="card-title">{{ $producto->nombre }}</h5>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <p class="text-center">No se encontraron productos.</p>
    @endforelse
</div>

<!-- Paginación -->
<div class="d-flex justify-content-center mt-4">
    {{ $productos->appends(['search' => request('search'), 'categoria' => request('categoria')])->links() }}
</div>
