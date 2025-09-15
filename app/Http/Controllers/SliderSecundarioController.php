<?php

namespace App\Http\Controllers;

use App\Models\SliderSecundario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderSecundarioController extends Controller
{
    // Mostrar todos los sliders secundarios
    // public function index()
    // {
    //     $slidersec = SliderSecundario::all();
    //     return view('content.sliders_secundario.index', compact('sliders'));
    // }

    // Mostrar el formulario para crear un nuevo slider secundario
    public function create()
    {
        return view('content.sliders_secundario.create');
    }

    // Almacenar un nuevo slider secundario
    public function store(Request $request)
    {
        $request->validate([
            'imagen_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if ($request->hasFile('imagen_url')) {
            $imagePath = $request->file('imagen_url')->store('public/imagenes');
            $imagen_url = Storage::url($imagePath);
        }

        SliderSecundario::create([
            'imagen_url' => $imagen_url ?? null,
        ]);

        return redirect()->route('sliders.index');
    }

    // Eliminar un slider secundario
    public function destroy($id)
    {
        $slider = SliderSecundario::findOrFail($id);

        Storage::delete('public/imagenes/' . basename($slider->imagen_url));

        $slider->delete();

        return redirect()->route('sliders.index')->with('success', 'Slide eliminado correctamente.');
    }

    // Actualizar el orden de los sliders secundarios
    public function updateOrder(Request $request)
    {
        $ids = $request->input('order');

        foreach ($ids as $index => $id) {
            SliderSecundario::where('id', $id)->update(['orden' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
