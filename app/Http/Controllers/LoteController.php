<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Models\FacturaExtraccion;
use App\Models\FacturaItem;
use App\Models\Lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class LoteController extends Controller
{
    // Index de Lotes (similar a generalesIndex)
    public function index()
    {
        // Carpetas existentes (de Archivo user_id=1) menos nulos/vacíos
        $carpetas = Archivo::where('user_id', 1)
            ->select('carpeta')
            ->distinct()
            ->pluck('carpeta')
            ->filter(fn($c) => $c !== null && $c !== '')
            ->values();

        // Asegurar carpeta raíz visible "CARPETA FACTURAS"

        // Agrupar archivos por carpeta
        $archivos = Archivo::where('user_id', 1)->get()->groupBy('carpeta');


        // Listado de lotes (si querés mostrarlos aparte)
        $lotes = Lote::orderByDesc('id')->get();

        return view('content.archivos_facturas.index', compact('carpetas', 'archivos', 'lotes'));
    }

    // Crear carpeta/lote (calco de generalesCrearCarpeta)
    public function crearLote(Request $request)
    {
        $request->validate([
            'nombre_carpeta' => 'required|string|max:100',
            'fecha_inicio'   => 'nullable|date',
            'fecha_fin'      => 'nullable|date|after_or_equal:fecha_inicio',
            'estado'         => 'nullable|in:abierto,cerrado,anulado',
        ]);

        $nombreCarpeta = trim($request->input('nombre_carpeta'));

        $baseFolder   = env('ARCHIVOS_FACTURAS', 'public/archivos_facturas');
        $targetFolder = "{$baseFolder}/{$nombreCarpeta}";
        $basePath     = storage_path("app/{$baseFolder}");
        $targetPath   = storage_path("app/{$targetFolder}");


        if (!Storage::exists($baseFolder)) {
            Storage::makeDirectory($baseFolder);
            @chmod($basePath, 0755);
        }
        if (!Storage::exists($targetFolder)) {
            Storage::makeDirectory($targetFolder);
            @chmod($targetPath, 0755);
        }

        // Registro “marcador”
        Archivo::create([
            'user_id'         => 1,
            'nombre_original' => '(carpeta vacía)',
            'ruta'            => null,
            'tipo'            => null,
            'carpeta'         => $nombreCarpeta,
        ]);

        // Alta opcional en tabla lotes (para meta/datos)
        Lote::firstOrCreate(
            ['nombre' => $nombreCarpeta],
            [
                'fecha_inicio' => $request->input('fecha_inicio'),
                'fecha_fin'    => $request->input('fecha_fin'),
                'estado'       => $request->input('estado', 'abierto'),
            ]
        );

        return redirect()->route('archivos.facturas.index')->with('success', 'Lote creado.');
    }

    // Subir archivos a un lote (calco de generalesSubir)
    public function subir(Request $request)
    {
        $request->validate([
            'archivos.*' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx,xls,xlsx,mp3|max:20480',
            'carpeta'    => 'nullable|string|max:100',
        ]);

        $carpeta     = $request->input('carpeta') ?? 'CARPETA FACTURAS';
        $baseFolder  = env('ARCHIVOS_FACTURAS', 'public/archivos_facturas');
        $targetFolder = "{$baseFolder}/{$carpeta}";
        $basePath     = storage_path("app/{$baseFolder}");
        $targetPath   = storage_path("app/{$targetFolder}");

        try {
            if (!Storage::exists($baseFolder)) {
                Storage::makeDirectory($baseFolder);
                @chmod($basePath, 0755);
            }
            if (!Storage::exists($targetFolder)) {
                Storage::makeDirectory($targetFolder);
                @chmod($targetPath, 0755);
            }

            foreach ($request->file('archivos') as $archivo) {
                $nombreOriginal = $archivo->getClientOriginalName();
                $extension      = $archivo->getClientOriginalExtension();
                $size           = $archivo->getSize();

                $rutaArchivo = $archivo->store($targetFolder);     // ruta interna     

                @chmod(storage_path('app/' . $rutaArchivo), 0644);

                Archivo::create([
                    'user_id'         => 1,
                    'nombre_original' => $nombreOriginal,
                    'ruta'            => $rutaArchivo,          // calco de tu lógica (URL pública)
                    'tipo'            => $extension,
                    'carpeta'         => $carpeta,
                    'tamano'          => $size,
                ]);
            }

            return redirect()->route('archivos.facturas.index')
                ->with('success', 'Archivo(s) subido(s) al lote.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al subir: ' . $e->getMessage());
        }
    }

    // Eliminar carpeta/lote completo (calco de generalesEliminarCarpeta)
    public function eliminarLote($carpeta)
    {
        $protegidas = ['carpeta facturas']; // protege la raíz
        if (in_array(mb_strtolower($carpeta), $protegidas, true)) {
            abort(403, 'Esta carpeta está protegida y no puede eliminarse.');
        }

        // Borrar registros y archivos
        $archivos = Archivo::where('user_id', 1)->where('carpeta', $carpeta)->get();
        foreach ($archivos as $archivo) {
            if ($archivo->ruta) {
                $rutaStorage = str_replace('/storage', 'public', $archivo->ruta);
                if (Storage::exists($rutaStorage)) {
                    Storage::delete($rutaStorage);
                }
            }
            $archivo->delete();
        }

        // Borrar carpeta física
        $targetFolder = env('ARCHIVOS_FACTURAS', 'public/archivos_facturas') . "/{$carpeta}";
        if (Storage::exists($targetFolder)) {
            Storage::deleteDirectory($targetFolder);
        }

        // Borrar registro de Lote (si existe)
        Lote::where('nombre', $carpeta)->delete();

        return back()->with('success', "Lote '{$carpeta}' eliminado.");
    }

    // Descargar (calco)
    public function descargar($id)
    {
        $archivo = Archivo::where('user_id', 1)->findOrFail($id);

        if (! $archivo->ruta) {
            return back()->with('error', 'El archivo no se encuentra.');
        }

        $rutaStorage = str_replace('/storage', 'public', $archivo->ruta);
        if (!Storage::exists($rutaStorage)) {
            return back()->with('error', 'El archivo no se encuentra en el almacenamiento.');
        }

        return Storage::download($rutaStorage, $archivo->nombre_original);
    }

    // Eliminar archivo individual (calco)
    public function eliminar($id)
    {
        $archivo = Archivo::where('user_id', 1)->findOrFail($id);

        if ($archivo->ruta) {
            $rutaStorage = str_replace('/storage', 'public', $archivo->ruta);
            if (Storage::exists($rutaStorage)) {
                Storage::delete($rutaStorage);
            }
        }

        $archivo->delete();

        return back()->with('success', 'Archivo eliminado del lote.');
    }

    // public function procesar($lote)
    // {
    //     $api = config('services.facturas_api.url');
    //     $resp = Http::timeout(600)->get($api . '/lotes/' . rawurlencode($lote) . '/facturas');

    //     if (!$resp->ok()) {
    //         return back()->with('error', 'FastAPI error: ' . $resp->body());
    //     }

    //     $payload = $resp->json();
    //     $resultados = $payload['resultados'] ?? [];
    //     if (!is_array($resultados)) {
    //         return back()->with('error', 'Respuesta inválida de FastAPI (sin resultados).');
    //     }

    //     $ok = 0;
    //     $miss = 0;
    //     try {
    //         foreach ($resultados as $pdfName => $data) {
    //             // Matchear el PDF por nombre y carpeta en tu "Archivos Generales" (user_id = 1)
    //             $archivo = Archivo::where('user_id', 1)
    //                 ->where('carpeta', $lote)
    //                 ->where('nombre_original', $pdfName)
    //                 ->first();

    //             if (!$archivo) {
    //                 $miss++;
    //                 continue;
    //             }

    //             $tot   = $data['totales'] ?? [];
    //             $items = $data['items'] ?? [];

    //             // Moneda raíz (si viene), fallback al primer ítem
    //             $monedaTop = $data['moneda'] ?? null;
    //             if (!$monedaTop && is_array($items) && count($items) && isset($items[0]['moneda'])) {
    //                 $monedaTop = $items[0]['moneda'];
    //             }

    //             $extr = FacturaExtraccion::updateOrCreate(
    //                 ['archivo_id' => $archivo->id],
    //                 [
    //                     'raw_json'       => $data,
    //                     'moneda'         => $monedaTop,
    //                     'base_imponible' => $this->parseNumber($tot['base_imponible'] ?? null),
    //                     'iva'            => $this->parseNumber($tot['iva'] ?? null),
    //                     'total'          => $this->parseNumber($tot['total'] ?? null),
    //                     'subtotal'       => $this->parseNumber($tot['subtotal'] ?? null),
    //                     'fecha_factura'  => !empty($data['fecha_factura']) ? \Carbon\Carbon::parse($data['fecha_factura'])->format('Y-m-d') : null,
    //                     'nro_factura'    => $data['nro_factura'] ?? null,
    //                     'nombre_persona' => $data['nombre_persona'] ?? null,
    //                     'extracted_at'   => now(),
    //                 ]
    //             );

    //             // Reemplazar ítems
    //             $extr->items()->delete();
    //             if (is_array($items)) {
    //                 foreach ($items as $it) {
    //                     $extr->items()->create([
    //                         'descripcion' => $it['descripcion'] ?? '',
    //                         'cantidad'    => $this->parseNumber($it['cantidad'] ?? null),
    //                         'importe'     => $this->parseNumber($it['importe'] ?? null),
    //                         'moneda'      => $it['moneda'] ?? $monedaTop,
    //                     ]);
    //                 }
    //             }

    //             $ok++;
    //         }
    //     } catch (\Throwable $e) {
    //         return back()->with('error', 'Error guardando resultados: ' . $e->getMessage());
    //     }

    //     return redirect()
    //         ->route('archivos.facturas.resultados', $lote)
    //         ->with('success', "Procesado e importado: {$ok} | No encontrados en 'archivos': {$miss}");
    // }

    // --- helper para convertir strings numéricos a float (acepta coma o punto) ---
    private function parseNumber(?string $s): ?float
    {
        if ($s === null) return null;
        $s = trim((string)$s);
        if ($s === '') return null;
        $s = preg_replace('/[^\d,.\-]/', '', $s);
        $hasComma = str_contains($s, ',');
        $hasDot   = str_contains($s, '.');
        if ($hasComma && $hasDot) {
            $s = str_replace('.', '', $s);
            $s = str_replace(',', '.', $s);
        } elseif ($hasComma) {
            $s = str_replace(',', '.', $s);
        }
        return is_numeric($s) ? (float)$s : null;
    }

    public function procesar($lote)
    {
        set_time_limit(0);
        $api = config('services.facturas_api.url');
        $resp = Http::timeout(5000)->get($api . '/lotes/' . rawurlencode($lote) . '/facturas');

        if (!$resp->ok()) {
            return back()->with('error', 'FastAPI error: ' . $resp->body());
        }

        $payload = $resp->json();
        $resultados = $payload['resultados'] ?? [];
        if (!is_array($resultados)) {
            return back()->with('error', 'Respuesta inválida de FastAPI (sin resultados).');
        }

        $ok = 0;
        $miss = 0;
        $missNames = [];

        DB::beginTransaction();
        try {
            foreach ($resultados as $pdfName => $data) {
                // Match por nombre_original OR por el nombre físico dentro de la URL/ruta
                $archivo = Archivo::where('user_id', 1)
                    ->where('carpeta', $lote)
                    ->where('nombre_original', '!=', '(carpeta vacía)')
                    ->where(function ($q) use ($pdfName) {
                        $q->where('nombre_original', $pdfName)
                            ->orWhere('ruta', 'like', '%/' . $pdfName);
                    })
                    ->first();

                if (!$archivo) {
                    $miss++;
                    $missNames[] = $pdfName;
                    continue;
                }

                $tot   = $data['totales'] ?? [];
                $items = $data['items'] ?? [];

                $monedaTop = $data['moneda'] ?? (is_array($items) && isset($items[0]['moneda']) ? $items[0]['moneda'] : null);

                $extr = FacturaExtraccion::updateOrCreate(
                    ['archivo_id' => $archivo->id],
                    [
                        'raw_json'       => $data,
                        'moneda'         => $monedaTop,
                        'base_imponible' => $this->parseNumber($tot['base_imponible'] ?? null),
                        'iva'            => $this->parseNumber($tot['iva'] ?? null),
                        'total'          => $this->parseNumber($tot['total'] ?? null),
                        'subtotal'       => $this->parseNumber($tot['subtotal'] ?? null),
                        'fecha_factura'  => !empty($data['fecha_factura']) ? \Carbon\Carbon::parse($data['fecha_factura'])->format('Y-m-d') : null,
                        'nro_factura'    => $data['nro_factura'] ?? null,
                        'nombre_persona' => $data['nombre_persona'] ?? null,
                        'extracted_at'   => now(),
                    ]
                );

                // Reemplazar ítems
                $extr->items()->delete();
                if (is_array($items)) {
                    foreach ($items as $it) {
                        $extr->items()->create([
                            'descripcion' => $it['descripcion'] ?? '',
                            'cantidad'    => $this->parseNumber($it['cantidad'] ?? null),
                            'importe'     => $this->parseNumber($it['importe'] ?? null),
                            'moneda'      => $it['moneda'] ?? $monedaTop,
                        ]);
                    }
                }

                $ok++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error guardando resultados: ' . $e->getMessage());
        }

        $msg = "Procesado e importado: {$ok} | No encontrados: {$miss}";
        if ($miss > 0) {
            // lista corta para debug
            $m = implode(', ', array_slice($missNames, 0, 5));
            $msg .= " (ej.: {$m})";
        }

        return redirect()
            ->route('archivos.facturas.resultados', $lote)
            ->with('success', $msg);
    }
    // 3.2) Listado de resultados (por lote/carpeta)
    // public function resultados(string $carpeta)
    // {
    //     // PDFs del lote
    //     $archivos = Archivo::where('user_id', 1)
    //         ->where('carpeta', $carpeta)
    //         ->where('nombre_original', '!=', '(carpeta vacía)')
    //         ->with(['facturaExtraccion' => function ($q) {
    //             $q->with('items');
    //         }])
    //         ->orderBy('id', 'desc')
    //         ->paginate(20);

    //     // Sumatorias rápidas
    //     $totales = [
    //         'subtotal'       => FacturaExtraccion::whereHas('archivo', fn($q) => $q->where('user_id', 1)->where('carpeta', $carpeta))->sum('subtotal'),
    //         'base_imponible' => FacturaExtraccion::whereHas('archivo', fn($q) => $q->where('user_id', 1)->where('carpeta', $carpeta))->sum('base_imponible'),
    //         'iva'            => FacturaExtraccion::whereHas('archivo', fn($q) => $q->where('user_id', 1)->where('carpeta', $carpeta))->sum('iva'),
    //         'total'          => FacturaExtraccion::whereHas('archivo', fn($q) => $q->where('user_id', 1)->where('carpeta', $carpeta))->sum('total'),
    //     ];

    //     return view('content.archivos_facturas.resultados', compact('carpeta', 'archivos', 'totales'));
    // }

    public function resultados(string $carpeta)
    {
        // PDFs del lote
        $archivos = Archivo::where('user_id', 1)
            ->where('carpeta', $carpeta)
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->with(['facturaExtraccion' => function ($q) {
                $q->with('items');
            }])
            ->orderBy('id', 'desc')
            ->paginate(20);

        // Base para agregados del lote
        $baseExtr = FacturaExtraccion::query()
            ->whereHas('archivo', function ($q) use ($carpeta) {
                $q->where('user_id', 1)->where('carpeta', $carpeta)->where('nombre_original', '!=', '(carpeta vacía)');
            });

        // Totales (igual que antes)
        $totales = [
            'subtotal'       => (clone $baseExtr)->sum('subtotal'),
            'base_imponible' => (clone $baseExtr)->sum('base_imponible'),
            'iva'            => (clone $baseExtr)->sum('iva'),
            'total'          => (clone $baseExtr)->sum('total'),
        ];

        // KPIs
        $totalProcesadas = (clone $baseExtr)->count();
        $totalPDFsLote   = Archivo::where('user_id', 1)->where('carpeta', $carpeta)
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->count();
        $sinProcesar     = Archivo::where('user_id', 1)->where('carpeta', $carpeta)
            ->whereDoesntHave('facturaExtraccion')->where('nombre_original', '!=', '(carpeta vacía)')
            ->count();
        $promedioTotal   = (clone $baseExtr)->whereNotNull('total')->avg('total');
        $minFecha        = (clone $baseExtr)->min('fecha_factura');
        $maxFecha        = (clone $baseExtr)->max('fecha_factura');

        // Inconsistencias (total vs (subtotal o base_imponible) + iva) tolerancia 0.5
        $inconsistentes  = (clone $baseExtr)
            ->whereRaw('ABS(COALESCE(total,0) - (IFNULL(subtotal, IFNULL(base_imponible,0)) + COALESCE(iva,0))) > 0.5')
            ->count();

        // Cantidad total de ítems del lote
        $itemsCount = FacturaItem::whereHas('extraccion.archivo', function ($q) use ($carpeta) {
            $q->where('user_id', 1)->where('carpeta', $carpeta);
        })->count();

        // Top personas (top 5 por total)
        $topPersonas = (clone $baseExtr)
            ->select('nombre_persona', DB::raw('COUNT(*) as n'), DB::raw('SUM(total) as suma'))
            ->groupBy('nombre_persona')
            ->orderByDesc(DB::raw('suma'))
            ->limit(5)
            ->get()
            ->map(function ($r) {
                return [
                    'nombre_persona' => $r->nombre_persona ?: '(sin nombre)',
                    'n'   => (int)$r->n,
                    'suma' => (float)$r->suma,
                ];
            });

        // Serie por día (sum total por fecha_factura)
        $porDia = (clone $baseExtr)
            ->select(DB::raw('DATE(fecha_factura) as fecha'), DB::raw('SUM(total) as suma'))
            ->whereNotNull('fecha_factura')
            ->groupBy(DB::raw('DATE(fecha_factura)'))
            ->orderBy('fecha', 'asc')
            ->get();

        $serieDiasLabels = $porDia->pluck('fecha')->map(function ($d) {
            return \Carbon\Carbon::parse($d)->format('Y-m-d');
        })->values();
        $serieDiasData   = $porDia->pluck('suma')->map(fn($v) => (float)$v)->values();

        // Breakdown por moneda
        $monedas = (clone $baseExtr)
            ->select('moneda', DB::raw('COUNT(*) as n'), DB::raw('SUM(total) as suma'))
            ->groupBy('moneda')
            ->orderByDesc(DB::raw('suma'))
            ->get()
            ->map(function ($r) {
                return [
                    'moneda' => $r->moneda ?: '(sin)',
                    'n'      => (int)$r->n,
                    'suma'   => (float)$r->suma,
                ];
            });

        // KPIs para la vista
        $kpis = [
            'pdfs_totales'    => $totalPDFsLote,
            'procesadas'      => $totalProcesadas,
            'sin_procesar'    => $sinProcesar,
            'promedio_total'  => $promedioTotal ? (float)$promedioTotal : 0,
            'rango'           => [$minFecha, $maxFecha],
            'inconsistentes'  => $inconsistentes,
            'items_count'     => $itemsCount,
        ];

        return view('content.archivos_facturas.resultados', compact(
            'carpeta',
            'archivos',
            'totales',
            'kpis',
            'topPersonas',
            'serieDiasLabels',
            'serieDiasData',
            'monedas'
        ));
    }
}
