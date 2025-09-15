<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\SliderSecundario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    // Mostrar todos los sliders
    public function index()
    {
        $sliders = Slider::all();
        $slidersSecundarios = SliderSecundario::all();
        return view('content.sliders.index', compact('sliders', 'slidersSecundarios'));
    }

    // Mostrar el formulario para crear un nuevo slider
    public function create()
    {
        return view('content.sliders.create');
    }

    // Almacenar un nuevo slider
    public function store(Request $request)
    {
        $data = $request->validate([
            'imagen_url'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'titulo'        => 'nullable|string|max:120',
            'subtitulo'     => 'nullable|string|max:200',
            'boton_titulo'  => 'nullable|string|max:60',
            'boton_url'     => 'nullable|url|max:255',
            'boton_color'    => 'nullable|string|max:20',
        ]);

        $imagen_url = null;
        if ($request->hasFile('imagen_url')) {
            $path = $request->file('imagen_url')->store('public/imagenes');
            $imagen_url = Storage::url($path);
        }

        Slider::create([
            'imagen_url'   => $imagen_url,
            'titulo'       => $data['titulo']        ?? null,
            'subtitulo'    => $data['subtitulo']     ?? null,
            'boton_titulo' => $data['boton_titulo']  ?? null,
            'boton_url'    => $data['boton_url']     ?? null,
            'boton_color'  => $data['boton_color']   ?? '#0d6efd',
            // si usás orden incremental:
            'orden'        => (int) (Slider::max('orden') + 1),
        ]);

        return redirect()->route('sliders.index')->with('success', 'Slide creado.');
    }

    // (Opcional) Update de slider principal
    public function update(Request $request, Slider $slider)
    {
        $data = $request->validate([
            'imagen_url'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'titulo'        => 'nullable|string|max:120',
            'subtitulo'     => 'nullable|string|max:200',
            'boton_titulo'  => 'nullable|string|max:60',
            'boton_url'     => 'nullable|url|max:255',
            'boton_color'   => 'nullable|string|max:20',
            'boton_target'  => 'nullable|string|max:10',
        ]);

        $imagen_url = $slider->imagen_url;
        if ($request->hasFile('imagen_url')) {
            // borrar anterior si existía
            if ($slider->imagen_url) {
                Storage::delete('public/imagenes/' . basename($slider->imagen_url));
            }
            $path = $request->file('imagen_url')->store('public/imagenes');
            $imagen_url = Storage::url($path);
        }

        $slider->update([
            'imagen_url'   => $imagen_url,
            'titulo'       => $data['titulo']        ?? null,
            'subtitulo'    => $data['subtitulo']     ?? null,
            'boton_titulo' => $data['boton_titulo']  ?? null,
            'boton_url'    => $data['boton_url']     ?? null,
            'boton_color'  => $data['boton_color']   ?? '#0d6efd',
            'boton_target' => $data['boton_target']  ?? '_self',
        ]);

        return back()->with('success', 'Slide actualizado.');
    }
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        Storage::delete('public/imagenes/' . basename($slider->imagen_url));

        // Eliminar el registro de la base de datos
        $slider->delete();

        return redirect()->route('sliders.index')->with('success', 'Slide eliminado correctamente.');
    }

    public function destroySecundario($id)
    {
        $slider = SliderSecundario::findOrFail($id);

        Storage::delete('public/imagenes/' . basename($slider->imagen_url));

        // Eliminar el registro de la base de datos
        $slider->delete();

        return redirect()->route('sliders.index')->with('success', 'Slider Secundario eliminado correctamente.');
    }

    public function updateOrder(Request $request)
    {
        $ids = $request->input('order');

        foreach ($ids as $index => $id) {
            Slider::where('id', $id)->update(['orden' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
