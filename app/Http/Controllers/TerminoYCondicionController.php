<?php
// app/Http/Controllers/TerminoYCondicionController.php
namespace App\Http\Controllers;

use App\Models\TerminoYCondicion;
use Illuminate\Http\Request;

class TerminoYCondicionController extends Controller
{
    // Mostrar el único término y condición
    public function index()
    {
        $termino = TerminoYCondicion::first(); // Solo hay un registro
        return view('content.terminos_y_condiciones.index', compact('termino'));
    }

    // Actualizar el término y condición
    public function update(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
        ]);

        $termino = TerminoYCondicion::first(); // Solo hay un registro
        $termino->update($request->only(['titulo', 'contenido']));

        return redirect()->route('terminos_y_condiciones.index')->with('success', 'Término y Condición actualizado correctamente.');
    }
}
