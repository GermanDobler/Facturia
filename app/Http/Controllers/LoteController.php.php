<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Archivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LoteController extends Controller
{
    // Listado + alta
    public function index(Request $request)
    {
        $q      = trim((string) $request->input('q', ''));
        $estado = (string) $request->input('estado', '');

        $lotes = Lote::query()
            ->when($q !== '', fn($qq) => $qq->where('nombre', 'like', "%{$q}%"))
            ->when($estado !== '', fn($qq) => $qq->where('estado', $estado))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('content.lotes.index', compact('lotes', 'q', 'estado'));
    }

    // Crear lote: hijo de Archivos Generales
    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:100|unique:lotes,nombre',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin'    => 'nullable|date|after_or_equal:fecha_inicio',
            'estado'       => 'nullable|in:abierto,cerrado,anulado',
        ]);

        $nombre       = trim($request->nombre);
        $fechaInicio  = $request->fecha_inicio;
        $fechaFin     = $request->fecha_fin;
        $estado       = $request->estado ?: 'abierto';

        // Carpetas bajo Archivos Generales (igual que tu lógica actual)
        $baseFolder = "public/archivos_generales";
        $loteFolder = "public/archivos_generales/{$nombre}";
        $basePath   = storage_path("app/{$baseFolder}");
        $lotePath   = storage_path("app/{$loteFolder}");

        DB::transaction(function () use (
            $nombre,
            $fechaInicio,
            $fechaFin,
            $estado,
            $baseFolder,
            $loteFolder,
            $basePath,
            $lotePath
        ) {
            if (!Storage::exists($baseFolder)) {
                Storage::makeDirectory($baseFolder);
                @chmod($basePath, 0755);
            }
            if (!Storage::exists($loteFolder)) {
                Storage::makeDirectory($loteFolder);
                @chmod($lotePath, 0755);
            }

            // 1) Crear el Lote
            $lote = Lote::create([
                'nombre'       => $nombre,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin'    => $fechaFin,
                'estado'       => $estado,
            ]);

            // 2) Crear “marcador” en Archivos (igual a tu crearCarpeta general)
            Archivo::create([
                'user_id'         => 1, // área general
                'nombre_original' => '(carpeta vacía)',
                'ruta'            => null,     // marcador de carpeta
                'tipo'            => null,
                'carpeta'         => $nombre,  // <— así tus listados agrupan por la carpeta del lote
            ]);
        });

        return back()->with('success', "Lote '{$nombre}' creado en Archivos Generales.");
    }

    // Borrar lote (opcionalmente protege estados o nombres)
    public function destroy(Lote $lote)
    {
        // Protecciones opcionales:
        // if ($lote->isCerrado()) abort(400, 'No se puede eliminar un lote cerrado.');

        // 1) Borrar carpeta y archivos del área general que estén en esa “carpeta”
        $archivos = Archivo::where('user_id', 1)->where('carpeta', $lote->nombre)->get();
        foreach ($archivos as $a) {
            if ($a->ruta) {
                $rutaStorage = ltrim(str_replace('/storage', 'public', $a->ruta), '/');
                if (Storage::exists($rutaStorage)) {
                    Storage::delete($rutaStorage);
                }
            }
            $a->delete();
        }
        $targetFolder = "public/archivos_generales/{$lote->nombre}";
        if (Storage::exists($targetFolder)) {
            Storage::deleteDirectory($targetFolder);
        }

        // 2) Borrar el Lote
        $lote->delete();

        return back()->with('success', "Lote '{$lote->nombre}' eliminado.");
    }
}
