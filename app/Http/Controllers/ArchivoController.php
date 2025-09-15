<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchivoController extends Controller
{
    // // Mostrar el archivero del usuario
    public function archivero($id)
    {
        $user = User::findOrFail($id);

        // Obtener carpetas únicas del usuario (excluye nulos y vacíos)
        $carpetas = Archivo::where('user_id', $user->id)
            ->select('carpeta')
            ->distinct()
            ->pluck('carpeta')
            ->filter(fn($carpeta) => $carpeta !== null && $carpeta !== '')
            ->values();

        // Asegurarse de que "CARPETA PERSONAL" exista en carpetas
        if (! $carpetas->contains('CARPETA PERSONAL')) {
            $carpetas->prepend('CARPETA PERSONAL');
        }

        // Agrupar archivos por carpeta
        $archivos = Archivo::where('user_id', $id)->get()->groupBy('carpeta');

        // Asegurar que 'CARPETA PERSONAL' exista como clave en archivos (aunque vacía)
        if (! $archivos->has('CARPETA PERSONAL')) {
            $archivos['CARPETA PERSONAL'] = collect();
        }

        return view('content.users.archivero', compact('user', 'carpetas', 'archivos'));
    }

    public function subir(Request $request, $id)
    {
        $request->validate([
            'archivos.*' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx,xls,xlsx,mp3|max:20480', // máx 20MB c/u
            'carpeta'    => 'nullable|string|max:100',
        ]);

        $user    = User::findOrFail($id);
        $carpeta = $request->input('carpeta') ?? 'CARPETA PERSONAL';

        try {
            // Ruta base para la carpeta del usuario
            $userFolder = "public/archivos/{$user->id}";
            $fullFolder = "public/archivos/{$user->id}/{$carpeta}";
            $basePathUser = storage_path("app/{$userFolder}");
            $basePathFolder = storage_path("app/{$fullFolder}");

            // Crear la carpeta del usuario si no existe
            if (!Storage::exists($userFolder)) {
                Storage::makeDirectory($userFolder);
                chmod($basePathUser, 0755); // Permisos para la carpeta del usuario
            }

            // Crear la subcarpeta si no existe
            if (!Storage::exists($fullFolder)) {
                Storage::makeDirectory($fullFolder);
                chmod($basePathFolder, 0755); // Permisos para la subcarpeta
            }

            foreach ($request->file('archivos') as $archivo) {
                $nombreOriginal = $archivo->getClientOriginalName();
                $extension      = $archivo->getClientOriginalExtension();
                $size           = $archivo->getSize(); // tamaño en bytes

                $rutaArchivo = $archivo->store($fullFolder);
                $url         = Storage::url($rutaArchivo); // ej: /storage/archivos/...

                // Establecer permisos 0644 para el archivo
                chmod(storage_path('app/' . $rutaArchivo), 0644);

                Archivo::create([
                    'user_id'         => $user->id,
                    'nombre_original' => $nombreOriginal,
                    'ruta'            => $url,
                    'tipo'            => $extension,
                    'carpeta'         => $carpeta,
                    'tamano'          => $size,
                ]);
            }

            return redirect()
                ->route('users.archivero', $user->id)
                ->with('success', 'Archivo(s) subido(s) correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al subir archivos: ' . $e->getMessage());
        }
    }

    public function crearCarpeta(Request $request, $id)
    {
        $request->validate([
            'nombre_carpeta' => 'required|string|max:100',
        ]);

        $nombreCarpeta = trim($request->input('nombre_carpeta'));
        if (strtoupper($nombreCarpeta) === 'CARPETA PERSONAL') {
            return back()->with('error', 'No es necesario crear la carpeta personal, ya existe.');
        }

        // Ruta para la carpeta
        $userFolder = "public/archivos/{$id}";
        $fullFolder = "public/archivos/{$id}/{$nombreCarpeta}";
        $basePathUser = storage_path("app/{$userFolder}");
        $basePathFolder = storage_path("app/{$fullFolder}");

        // Crear la carpeta del usuario si no existe
        if (!Storage::exists($userFolder)) {
            Storage::makeDirectory($userFolder);
            chmod($basePathUser, 0755);
        }

        // Crear la subcarpeta si no existe
        if (!Storage::exists($fullFolder)) {
            Storage::makeDirectory($fullFolder);
            chmod($basePathFolder, 0755);
        }

        // Crear un registro en la base de datos
        Archivo::create([
            'user_id'         => $id,
            'nombre_original' => '(carpeta vacía)',
            'ruta'            => null,
            'tipo'            => null,
            'carpeta'         => $nombreCarpeta,
        ]);

        return redirect()->route('users.archivero', $id)->with('success', 'Carpeta creada.');
    }

    public function eliminarCarpeta($userId, $carpeta)
    {
        // Buscar todos los archivos de esa carpeta del usuario
        $archivos = Archivo::where('user_id', $userId)
            ->where('carpeta', $carpeta)
            ->get();

        foreach ($archivos as $archivo) {
            // Eliminar del disco si existe y tiene ruta
            if ($archivo->ruta) {
                Storage::delete(str_replace('/storage', 'public', $archivo->ruta));
            }

            // Eliminar de la base de datos
            $archivo->delete();
        }

        return back()->with('success', "Carpeta $carpeta y todos sus archivos fueron eliminados.");
    }

    // Descargar archivo
    public function descargar($id)
    {
        $archivo = Archivo::findOrFail($id);

        if (! $archivo->ruta) {
            return back()->with('error', 'El archivo no se encuentra.');
        }

        return Storage::download(str_replace('/storage', 'public', $archivo->ruta), $archivo->nombre_original);
    }

    public function eliminar($id)
    {
        $archivo = Archivo::findOrFail($id);

        // Eliminar el archivo del almacenamiento
        Storage::delete(str_replace('/storage', 'public', $archivo->ruta));

        // Eliminar de la base de datos

        $archivo->delete();

        return back()->with('success', 'Archivo eliminado correctamente.');
    }

    public function ver($id)
    {
        $archivo = Archivo::findOrFail($id);

        // Verificar si el archivo existe en el almacenamiento
        if (! $archivo->ruta || ! Storage::exists($archivo->ruta)) {
            return back()->with('error', 'El archivo no se encuentra.');
        }

        // Obtener la URL del archivo
        $url = Storage::url($archivo->ruta);

        return view('content.users.ver_archivo', compact('archivo', 'url'));
    }

    public function eliminarMultiples(Request $request)
    {
        $ids = explode(',', $request->input('ids'));

        Archivo::whereIn('id', $ids)->each(function ($archivo) {
            if ($archivo->ruta) {
                // Convertir la ruta pública a ruta interna de Storage
                $rutaStorage = str_replace('/storage', 'public', $archivo->ruta);
                if (Storage::exists($rutaStorage)) {
                    Storage::delete($rutaStorage);
                }
            }
            $archivo->delete();
        });

        return back()->with('success', 'Archivos eliminados correctamente.');
    }

    // Archivos generales (user_id = 1)


    public function generalesIndex()
    {

        $carpetas = Archivo::where('user_id', 1)
            ->select('carpeta')
            ->distinct()
            ->pluck('carpeta')
            ->filter(fn($c) => $c !== null && $c !== '')
            ->values();

        if (!$carpetas->contains('CARPETA GENERAL')) {
            $carpetas->prepend('CARPETA GENERAL');
        }

        $archivos = Archivo::where('user_id', 1)->get()->groupBy('carpeta');

        if (!$archivos->has('CARPETA GENERAL')) {
            $archivos['CARPETA GENERAL'] = collect();
        }

        return view('content.archivos_generales.index', compact('carpetas', 'archivos'));
    }

    public function generalesSubir(Request $request)
    {

        $request->validate([
            'archivos.*' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx,xls,xlsx,mp3|max:20480',
            'carpeta'    => 'nullable|string|max:100',
        ]);

        $carpeta = $request->input('carpeta') ?? 'CARPETA GENERAL';

        try {
            $baseFolder   = "public/archivos_generales";
            $targetFolder = "public/archivos_generales/{$carpeta}";
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

            foreach ($request->file('archivos') as $archivo) {
                $nombreOriginal = $archivo->getClientOriginalName();
                $extension      = $archivo->getClientOriginalExtension();
                $size           = $archivo->getSize();

                $rutaArchivo = $archivo->store($targetFolder);
                $url         = Storage::url($rutaArchivo);
                @chmod(storage_path('app/' . $rutaArchivo), 0644);

                Archivo::create([
                    'user_id'         => 1, // <- antes iba null
                    'nombre_original' => $nombreOriginal,
                    'ruta'            => $url,
                    'tipo'            => $extension,
                    'carpeta'         => $carpeta,
                    'tamano'          => $size,
                ]);
            }

            return redirect()->route('archivos.generales.index')
                ->with('success', 'Archivo(s) subido(s) correctamente al área general.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al subir: ' . $e->getMessage());
        }
    }

    public function generalesCrearCarpeta(Request $request)
    {

        $request->validate([
            'nombre_carpeta' => 'required|string|max:100',
        ]);

        $nombreCarpeta = trim($request->input('nombre_carpeta'));
        if (strtoupper($nombreCarpeta) === 'CARPETA GENERAL') {
            return back()->with('error', 'La carpeta "CARPETA GENERAL" ya existe.');
        }

        $targetFolder = "public/archivos_generales/{$nombreCarpeta}";
        $targetPath   = storage_path("app/{$targetFolder}");

        if (!Storage::exists("public/archivos_generales")) {
            Storage::makeDirectory("public/archivos_generales");
            @chmod(storage_path("app/public/archivos_generales"), 0755);
        }

        if (!Storage::exists($targetFolder)) {
            Storage::makeDirectory($targetFolder);
            @chmod($targetPath, 0755);
        }

        // registro “marcador” de carpeta
        Archivo::create([
            'user_id'         => 1, // <- antes iba null
            'nombre_original' => '(carpeta vacía)',
            'ruta'            => null,
            'tipo'            => null,
            'carpeta'         => $nombreCarpeta,
        ]);

        return redirect()->route('archivos.generales.index')->with('success', 'Carpeta general creada.');
    }

    public function generalesEliminarCarpeta($carpeta)
    {
        $protegidas = ['carpeta general', 'Leyes y otros'];
        if (in_array(mb_strtolower($carpeta), $protegidas, true)) {
            abort(403, 'Esta carpeta está protegida y no puede eliminarse.');
        }

        $archivos = Archivo::where('user_id', 1)
            ->where('carpeta', $carpeta)
            ->get();

        foreach ($archivos as $archivo) {
            if ($archivo->ruta) {
                Storage::delete(str_replace('/storage', 'public', $archivo->ruta));
            }
            $archivo->delete();
        }

        $targetFolder = "public/archivos_generales/{$carpeta}";
        if (Storage::exists($targetFolder)) {
            Storage::deleteDirectory($targetFolder);
        }

        return back()->with('success', "Carpeta general '{$carpeta}' eliminada.");
    }

    public function generalesEliminar($id)
    {

        $archivo = Archivo::where('user_id', 1)->findOrFail($id);

        if ($archivo->ruta) {
            Storage::delete(str_replace('/storage', 'public', $archivo->ruta));
        }

        $archivo->delete();

        return back()->with('success', 'Archivo eliminado del área general.');
    }
}
