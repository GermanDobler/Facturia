<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use App\Models\Producto;
use Illuminate\Http\Request;

class ImagenController extends Controller
{
    public function store(Request $request, Producto $producto)
    {
        $request->validate([
            'url' => 'required|string|max:255',
        ]);

        $producto->imagenes()->create($request->only('url'));
        return redirect()->route('productos.edit', $producto)->with('success', 'Imagen añadida exitosamente.');
    }

    public function destroy(Imagen $imagen)
    {
        $imagen->delete();
        return back()->with('success', 'Imagen eliminada exitosamente.');
    }
}
