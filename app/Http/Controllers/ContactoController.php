<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacto;
use Illuminate\Support\Facades\Storage;

class ContactoController extends Controller
{

    public function index(Request $request)
    {
        $contacto = Contacto::first();

        return view('content.contacto.index', compact('contacto'));
    }


    public function store(Request $request)
    {
        $contacto = Contacto::first() ?? new Contacto();

        // Manejo de la portada
        if ($request->hasFile('portada')) {
            // Eliminar la imagen anterior si existe
            if ($contacto->portada && Storage::exists($contacto->portada)) {
                Storage::delete($contacto->portada);
            }
            // Guardar la nueva imagen y almacenar solo la ruta interna
            $portada = $request->file('portada')->store('public/imagenes');
        } else {
            $portada = $contacto->portada; // Mantener la imagen existente si no se subió una nueva
        }

        // Manejo del contenido
        if ($request->hasFile('contenido')) {
            // Eliminar la imagen anterior si existe
            if ($contacto->contenido && Storage::exists($contacto->contenido)) {
                Storage::delete($contacto->contenido);
            }
            // Guardar la nueva imagen y almacenar solo la ruta interna
            $contenido = $request->file('contenido')->store('public/imagenes');
        } else {
            $contenido = $contacto->contenido; // Mantener la imagen existente si no se subió una nueva
        }

        // Guardar los datos
        $contacto->titulo = $request->input('titulo');
        $contacto->subtitulo = $request->input('subtitulo');
        // Almacenar solo la ruta interna en lugar de la URL
        $contacto->portada = $portada ? $portada : $contacto->portada;
        $contacto->contenido = $contenido ? $contenido : $contacto->contenido;
        $contacto->tituloc = $request->input('tituloc');
        $contacto->descripcion = $request->input('descripcion');

        // Guardar los cambios en la base de datos
        $contacto->save();

        return redirect()->back()->with('success', 'Datos de la pagina guardados correctamente');
    }
}
