<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensajes;

class MensajesController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search'));

        $mensajes = Mensajes::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('nombre', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('numero', 'like', "%{$search}%")
                        ->orWhere('mensaje', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->query());

        return view('content.mensajes.index', compact('mensajes', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'numero' => 'nullable|string|max:20',
            'mensaje' => 'required|string',
        ]);

        Mensajes::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'numero' => $request->numero,
            'mensaje' => $request->mensaje,
        ]);

        return redirect()->back()->with('success', 'Mensaje enviado correctamente.');
    }

    public function show($id)
    {
        $mensaje = Mensajes::findOrFail($id);
        return view('content.mensajes.show', compact('mensaje'));
    }

    public function destroy($id)
    {
        $mensaje = Mensajes::findOrFail($id);
        $mensaje->delete();

        return redirect()->route('mensajes.index')->with('success', 'Mensaje eliminado correctamente.');
    }
}
