<?php

namespace App\Http\Controllers;

use App\Models\InformacionUtil;
use Illuminate\Http\Request;

class InformacionUtilController extends Controller
{
    // Mostrar la información útil (solo un registro)
    public function index()
    {
        $informacion = InformacionUtil::first(); // Solo hay un registro
        return view('content.informacion_util.index', compact('informacion'));
    }

    // Editar la información útil
    public function edit()
    {
        $informacion = InformacionUtil::first(); // Solo hay un registro
        return view('content.informacion_util.edit', compact('informacion'));
    }

    // Actualizar la información útil
    public function update(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
        ]);

        $informacion = InformacionUtil::first(); // Solo hay un registro
        if (!$informacion) {
            $informacion = new InformacionUtil();
        }
        $informacion->fill($request->only(['titulo', 'contenido']));
        $informacion->save();

        return redirect()->route('informacion_util.index')->with('success', 'Información útil actualizada correctamente.');
    }
}
