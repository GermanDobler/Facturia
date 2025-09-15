<?php

namespace App\Http\Controllers;

use App\Models\Etiqueta;
use App\Models\Footer;
use App\Models\Noticias;
use App\Models\Slider;
use App\Models\SliderSecundario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Storage;

class NoticiasController extends Controller
{
    // Mostrar una lista de todas las noticias con buscador
    public function index(Request $request)
    {
        $query = Noticias::query();

        if ($request->filled('search')) {
            $query->where('titulo', 'like', '%' . $request->search . '%');
        }

        $noticias = $query->orderBy('created_at', 'desc')->paginate(16)->appends(['search' => $request->search]);

        return view('content.noticias.index', compact('noticias'));
    }


    // Mostrar el formulario para crear una nueva noticia
    public function create()
    {
        $etiquetas = Etiqueta::all(); // Obtiene todas las etiquetas disponibles
        return view('content.noticias.create', compact('etiquetas'));
    }

    public function store(Request $request)
    {
        // Validación de los campos del formulario
        $request->validate([
            'titulo' => 'required|string|max:200',
            'subtitulo' => 'nullable|string|max:250',
            'contenido_html' => 'nullable|string',
            'imagen_url' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'pdf_file' => 'nullable|mimes:pdf|max:10240', // 5MB máximo
            'estado' => 'required|string|max:15',
            'is_paid' => 'required|string',
            'etiquetas' => 'nullable|array',
            'featured' => 'nullable|boolean',
            'prioridad' => 'nullable|integer|min:0',
            'fuente_url' => 'nullable|url|max:255',
        ]);

        Log::info('Request data: ', $request->all());

        try {
            // Procesar la imagen
            if ($request->hasFile('imagen_url')) {
                $imagePath = $request->file('imagen_url')->store('public/imagenes');
                $imagen_url = Storage::url($imagePath);
            }

            $pdf_url = null;

            if ($request->hasFile('pdf_file')) {
                $pdfPath = $request->file('pdf_file')->store('public/imagenes');
                $pdf_url = Storage::url($pdfPath);
                Log::info('pdf_path: ', ['path' => $pdfPath]);
                Log::info('pdf_url: ', ['url' => $pdf_url]);
            }

            // Crear la noticia
            $noticia = Noticias::create([
                'titulo' => $request->input('titulo'),
                'subtitulo' => $request->input('subtitulo'),
                'contenido_html' => $request->input('contenido_html'),
                'imagen_url' => $imagen_url ?? null,
                'pdf_url' => $pdf_url,
                'estado' => $request->input('estado'),
                'is_paid' => $request->input('is_paid'),
                'featured' => $request->input('featured', 0),
                'prioridad' => $request->input('prioridad', 0),
                'fuente_url' => $request->input('fuente_url', null),
            ]);

            // Procesar etiquetas
            $etiquetasParaAsociar = [];

            if ($request->has('etiquetas')) {
                foreach ($request->etiquetas as $etiquetaNombre) {
                    if (is_numeric($etiquetaNombre)) {
                        $etiquetasParaAsociar[] = $etiquetaNombre;
                        continue;
                    }

                    $etiqueta = Etiqueta::firstOrCreate(['nombre' => $etiquetaNombre]);
                    $etiquetasParaAsociar[] = $etiqueta->id;
                }

                if (!empty($etiquetasParaAsociar)) {
                    $noticia->etiquetas()->attach($etiquetasParaAsociar);
                }
            }

            return redirect()->route('noticias.index')->with('success', 'Noticia creada correctamente.');
        } catch (\Exception $e) {
            // Mostrar el error exacto
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error: ' . $e->getMessage());
        }
    }


    // Mostrar el formulario para editar una noticia existente
    public function edit($id)
    {
        $noticia = Noticias::findOrFail($id);
        $etiquetas = Etiqueta::all();
        return view('content.noticias.edit', compact('noticia', 'etiquetas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:200',
            'subtitulo' => 'nullable|string|max:250',
            'contenido_html' => 'nullable|string',
            'imagen_url' => 'nullable|file|image|max:10240', // Valida que sea un archivo de imagen
            'estado' => 'required',
            'is_paid' => 'required|string',
            'etiquetas' => 'nullable|array', // Etiquetas como array
            'fuente_url' => 'nullable|url|max:255',
        ]);

        // Encontrar la noticia
        $noticia = Noticias::findOrFail($id);

        // Actualizar campos básicos
        $noticia->titulo = $request->input('titulo');
        $noticia->subtitulo = $request->input('subtitulo');
        $noticia->contenido_html = $request->input('contenido_html');
        $noticia->estado = $request->input('estado');
        $noticia->is_paid = $request->input('is_paid');
        $noticia->fuente_url = $request->input('fuente_url');

        // Manejo de la imagen
        if ($request->hasFile('imagen_url')) {
            // Subir nueva imagen
            $path = $request->file('imagen_url')->store('public/imagenes');
            // Eliminar imagen anterior si existe
            if ($noticia->imagen_url) {
                Storage::delete('public/imagenes/' . basename($noticia->imagen_url));
            }

            // Guardar nueva ruta de la imagen
            $noticia->imagen_url = Storage::url($path);
        } elseif ($request->input('remove_image') === '1') {
            // Si se solicita eliminar la imagen
            if ($noticia->imagen_url) {
                Storage::delete('public/imagenes/' . basename($noticia->imagen_url));
                $noticia->imagen_url = null;
            }
        }

        // PDF
        if ($request->hasFile('pdf_file')) {
            $pdfPath = $request->file('pdf_file')->store('public/imagenes');
            if ($noticia->pdf_url) {
                Storage::delete('public/imagenes/' . basename($noticia->pdf_url));
            }
            $noticia->pdf_url = Storage::url($pdfPath);
        } elseif ($request->input('remove_pdf') === '1') {
            if ($noticia->pdf_url) {
                Storage::delete('public/imagenes/' . basename($noticia->pdf_url));
                $noticia->pdf_url = null;
            }
        }

        // Procesar etiquetas
        $etiquetasParaAsociar = [];

        if ($request->has('etiquetas')) {
            foreach ($request->etiquetas as $etiquetaNombre) {
                // Evitar agregar etiquetas con nombre numérico (ID de etiqueta)
                if (is_numeric($etiquetaNombre)) {
                    $etiquetasParaAsociar[] = $etiquetaNombre;
                    continue; // No agregar etiquetas con nombre numérico
                }

                // Verificar si la etiqueta ya existe por nombre
                $etiqueta = Etiqueta::firstOrCreate(['nombre' => $etiquetaNombre]);

                // Almacenar la ID de la etiqueta para asociarla
                $etiquetasParaAsociar[] = $etiqueta->id;
            }

            // Usar `sync` para evitar relaciones duplicadas
            $noticia->etiquetas()->sync($etiquetasParaAsociar);
        }

        $noticia->save();

        return redirect()->route('noticias.index')->with('success', 'Noticia actualizada correctamente');
    }

    // Eliminar una noticia de la base de datos
    public function destroy($id)
    {
        $noticia = Noticias::findOrFail($id);
        // Eliminar la imagen de la carpeta 'public/imagenes'
        Storage::delete('public/imagenes/' . basename($noticia->imagen_url));
        // Eliminar el PDF de la carpeta 'public/pdfs'
        Storage::delete('public/imagenes/' . basename($noticia->pdf_url));
        // Eliminar la noticia de la base de datos
        $noticia->delete();

        return redirect()->route('noticias.index')->with('success', 'Noticia eliminada correctamente');
    }


    public function togglePaid($id)
    {
        $noticia = Noticias::findOrFail($id);
        $noticia->is_paid = !$noticia->is_paid;
        $noticia->save();

        return response()->json([
            'success' => true,
            'is_paid' => $noticia->is_paid,
        ]);
    }
}
