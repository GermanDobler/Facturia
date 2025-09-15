<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Models\Contacto;
use App\Models\Faq;
use App\Models\Footer;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Cuota;
use App\Models\InformacionUtil;
use App\Models\Noticias;
use App\Models\Slider;
use App\Models\SliderSecundario;
use App\Models\Somos;
use App\Models\TerminoYCondicion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PanelMatriculadoController extends Controller
{
    public function index(Request $request)
    {
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->orderBy('created_at', 'desc')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->get();
        $sliderSec = SliderSecundario::orderBy('orden', 'asc')->get();
        $footer = Footer::first();

        return view('content.mi-panel.index', compact('sliderSec', 'footer', 'leyesNavFiles'));
    }

    /** Listado de cuotas del usuario (matriculado) */
    public function misCuotas(Request $request)
    {
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->orderBy('created_at', 'desc')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->get();
        $sliderSec = SliderSecundario::orderBy('orden', 'asc')->get();
        $footer    = Footer::first();
        $userId    = $request->user()->id;

        $cuotas = Cuota::with('periodo')
            ->where('usuario_id', $userId)
            ->whereHas('periodo', fn($p) => $p->where('es_visible', 1)->where('estado', 'abierto'))
            ->orderByDesc('id')
            ->paginate(20);

        return view('content.mi-panel.mias', [
            'sliderSec' => $sliderSec,
            'footer'    => $footer,
            'cuotas'    => $cuotas,
            'leyesNavFiles' => $leyesNavFiles,
        ]);
    }

    public function archiveroIndex()
    {
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->orderBy('created_at', 'desc')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->get();
        $user    = Auth::user();
        $footer  = Footer::first();

        // === Carpetas/archivos personales ===
        $carpetas = Archivo::where('user_id', $user->id)
            ->select('carpeta')
            ->distinct()
            ->pluck('carpeta')
            ->filter(fn($c) => $c !== null && $c !== '')
            ->values();

        if (!$carpetas->contains('CARPETA PERSONAL')) {
            $carpetas->prepend('CARPETA PERSONAL');
        }

        $archivos = Archivo::where('user_id', $user->id)->get()->groupBy('carpeta');
        if (!$archivos->has('CARPETA PERSONAL')) {
            $archivos['CARPETA PERSONAL'] = collect();
        }

        return view('content.mi-panel.archivero', compact(
            'user',
            'footer',
            'carpetas',
            'archivos',
            'leyesNavFiles'
        ));
    }


    public function archiveroGeneral()
    {
        $user = Auth::user();
        $footer = Footer::first();
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->orderBy('created_at', 'desc')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->get();
        $carpetasGenerales = Archivo::where('user_id', 1)
            ->select('carpeta')
            ->distinct()
            ->pluck('carpeta')
            ->filter(fn($c) => $c !== null && $c !== '')
            ->values();

        if (!$carpetasGenerales->contains('CARPETA GENERAL')) {
            $carpetasGenerales->prepend('CARPETA GENERAL');
        }

        $archivosGenerales = Archivo::where('user_id', 1)->get()->groupBy('carpeta');
        if (!$archivosGenerales->has('CARPETA GENERAL')) {
            $archivosGenerales['CARPETA GENERAL'] = collect();
        }

        return view('content.mi-panel.archivero_general', compact(
            'user',
            'carpetasGenerales',
            'archivosGenerales',
            'footer',
            'leyesNavFiles'
        ));
    }


    public function archiveroCarpeta(string $carpeta)
    {
        $carpeta = urldecode($carpeta);
        $userId = Auth::id();
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->orderBy('created_at', 'desc')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->get();
        $archivos = Archivo::where('user_id', $userId)
            ->where('carpeta', $carpeta === 'null' ? null : $carpeta)
            ->latest()
            ->paginate(20);

        return view('content.mi-panel.archivos_por_carpeta', compact('archivos', 'carpeta', 'leyesNavFiles'));
    }

    public function archiveroCrearCarpeta(Request $request)
    {
        $request->validate(['carpeta' => 'required|string|max:120']);
        $carpeta = trim($request->carpeta);
        $userId  = Auth::id();

        $path = "archivos/{$userId}/{$carpeta}";
        if (!Storage::disk('local')->exists($path)) {
            Storage::disk('local')->makeDirectory($path);
        }

        return back()->with('success', 'Carpeta creada');
    }

    public function archiveroSubir(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|max:20480',
            'carpeta' => 'nullable|string|max:120',
        ]);

        $user    = Auth::user();
        $carpeta = $request->carpeta ?: 'CARPETA PERSONAL';
        $dir     = "archivos/{$user->id}/{$carpeta}";

        $storedPath = $request->file('archivo')->store($dir, 'local');

        $archivo = new Archivo();
        $archivo->user_id = $user->id;
        $archivo->nombre  = $request->file('archivo')->getClientOriginalName();
        $archivo->carpeta = $carpeta;
        $archivo->path    = $storedPath;
        $archivo->mime    = $request->file('archivo')->getClientMimeType();
        $archivo->tamano  = $request->file('archivo')->getSize();
        $archivo->save();

        return back()->with('success', 'Archivo subido correctamente');
    }

    // Descargar archivo
    public function archiveroDescargar($id)
    {
        $archivo = Archivo::findOrFail($id);

        if (! $archivo->ruta) {
            return back()->with('error', 'El archivo no se encuentra.');
        }

        return Storage::download(asset(env('ARCHIVOS_PATH') . str_replace('/storage/archivos', '', $archivo->ruta)), $archivo->nombre_original);
    }
}
