<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Archivo;
use App\Models\Autoridad;
use App\Models\Contacto;
use App\Models\Faq;
use App\Models\Footer;
use App\Models\InformacionUtil;
use App\Models\Inmobiliaria;
use App\Models\Noticias;
use App\Models\Slider;
use App\Models\SliderSecundario;
use App\Models\TerminoYCondicion;
use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function index(Request $request)
    {
        $slider = Slider::orderBy('orden', 'asc')->get();
        $footer = Footer::first();
        $sliderSec = SliderSecundario::orderBy('orden', 'asc')->get();
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->orderBy('created_at', 'desc')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->get();
        $noticiasQuery = Noticias::where('estado', '!=', 'archivada')
            ->where('is_paid', 'no');

        if ($request->filled('buscar')) {
            $noticiasQuery->where('titulo', 'like', '%' . $request->buscar . '%');
        }

        $noticias = $noticiasQuery->orderBy('created_at', 'desc')->paginate(4);

        $matriculadosActivosCount = User::where('role', 'user')->where('activo', 'si')->count();

        $inmobiliariasRegistradasCount = Inmobiliaria::count();

        return view(
            'content.inicio.index',
            compact(
                'slider',
                'sliderSec',
                'footer',
                'noticias',
                'leyesNavFiles',
                'matriculadosActivosCount',
                'inmobiliariasRegistradasCount'
            )
        );
    }

    // NOTICIAS 

    public function showNoticia($slug)
    {
        $footer = Footer::first();
        $parts = explode('-', $slug);
        $id = end($parts); // Toma la última parte como el ID

        // Busca la noticia solo por el ID
        $noticia = Noticias::with('etiquetas')->findOrFail($id);
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->orderBy('created_at', 'desc')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->get();
        // Obtener IDs de etiquetas asociadas
        $etiquetaIds = $noticia->etiquetas->pluck('id');

        // Buscar otras noticias que compartan alguna etiqueta
        $relacionadas = Noticias::where('id', '!=', $noticia->id)
            ->where('estado', '!=', 'archivada')
            ->where('is_paid', 'no')
            ->whereHas('etiquetas', function ($query) use ($etiquetaIds) {
                $query->whereIn('etiquetas.id', $etiquetaIds);
            })
            ->with('etiquetas')
            ->latest()
            ->take(5)
            ->get();

        return view('content.inicio.noticia', compact('noticia', 'relacionadas', 'footer', 'leyesNavFiles'));
    }

    public function noticias(Request $request)
    {
        $footer = Footer::first();
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->orderBy('created_at', 'desc')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->get();
        $noticiasQuery = Noticias::where('estado', '!=', 'archivada')
            ->where('is_paid', 'no');

        if ($request->filled('buscar')) {
            $noticiasQuery->where('titulo', 'like', '%' . $request->buscar . '%');
        }

        $noticias = $noticiasQuery->orderBy('created_at', 'desc')->paginate(12);

        return view('content.inicio.noticias', compact('noticias', 'footer', 'leyesNavFiles'));
    }

    public function terminosCondiciones()
    {
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->orderBy('created_at', 'desc')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->get();
        $footer = Footer::first();
        $terminos = TerminoYCondicion::first();
        return view('content.inicio.terminos', compact('footer', 'terminos', 'leyesNavFiles'));
    }

    public function informacionUtil()
    {
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->orderBy('created_at', 'desc')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->get();
        $footer = Footer::first();
        $informacion = InformacionUtil::first();
        return view('content.inicio.informacion', compact('footer', 'informacion', 'leyesNavFiles'));
    }

    public function listaAutoridades()
    {
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->orderBy('created_at', 'desc')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->get();

        $footer = Footer::first();

        // Trae todo; el orden final lo definimos abajo
        $autoridades = Autoridad::query()->get();

        // Normalizador de cargo → Título
        $normalizeCargo = function ($cargo) {
            $cargo = trim((string)$cargo);
            $cargo = preg_replace('/\s+/', ' ', $cargo);
            return mb_convert_case($cargo, MB_CASE_TITLE, 'UTF-8');
        };

        // 1) Ranking deseado para el ORDEN DE LOS CARGOS (ajustá a gusto)
        $rank = [
            'Presidente' => 1,
            'Vicepresidente' => 2,
            'Secretario' => 3,
            'Prosecretario' => 4,
            'Tesorero' => 5,
            'Protesorero' => 6,
            'Primer vocal' => 7,
            'Segundo vocal' => 8,
            'Vocal' => 9,
            'Vocal suplente 1' => 10,
            'Vocal suplente 2' => 11,
            'Revisores de cuenta' => 12,
            'Revisor de cuenta' => 12,
            'Revisor de cuenta suplente' => 13,
            'Tribunal de disciplina' => 14,
            'Tribunal de disciplina suplente' => 15,
        ];

        // 2) Agrupar por cargo normalizado
        $porCargo = $autoridades
            ->groupBy(fn($a) => $normalizeCargo($a->cargo))

            // 3) Ordenar DENTRO de cada cargo por orden asc (null al final), luego apellido/nombre
            ->map(function ($grupo) {
                return $grupo->sortBy(fn($a) => [
                    $a->orden ?? PHP_INT_MAX,
                    mb_strtoupper($a->apellido, 'UTF-8'),
                    mb_strtoupper($a->nombre, 'UTF-8'),
                ]);
            })

            // 4) Ordenar LOS CARGOS por ranking; si no está, por min(orden) del grupo; luego por nombre de cargo
            ->sortBy(function ($grupo, $cargo) use ($rank) {
                $minOrden = $grupo->whereNotNull('orden')->min('orden') ?? PHP_INT_MAX;
                $peso = $rank[$cargo] ?? 1000; // cargos no rankeados van después
                return [$peso, $minOrden, $cargo];
            });

        return view('content.inicio.autoridades', compact('footer', 'leyesNavFiles', 'porCargo'));
    }



    public function faqs()
    {
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->orderBy('created_at', 'desc')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->get();
        $footer = Footer::first();
        $faqs = Faq::orderBy('order', 'asc')->get();
        return view('content.inicio.faqs', compact('footer', 'faqs', 'leyesNavFiles'));
    }

    public function contacto()
    {
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->orderBy('created_at', 'desc')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->get();
        $contacto = Contacto::first();
        $footer = Footer::first();

        return view('content.inicio.contacto', compact('contacto', 'footer', 'leyesNavFiles'));
    }

    public function padronPublico(Request $request)
    {
        // Para layout / navbar
        $footer = Footer::first();
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->latest('created_at')
            ->get(['id', 'nombre_original', 'ruta', 'tipo', 'tamano', 'carpeta', 'created_at']);

        // Filtros del padrón
        $q       = trim((string) $request->get('q', ''));
        $activos = $request->boolean('activos', false); // <= por defecto FALSE (no filtra)

        // Escapar comodines para LIKE
        $term = $q !== '' ? str_replace(['%', '_'], ['\%', '\_'], $q) : '';

        $query = User::query()
            ->where('role', 'user')
            ->select(['nombre', 'apellido', 'matricula', 'email', 'telefono', 'cuil', 'activo'])
            ->when($q !== '', function ($qb) use ($term) {
                $like = "%{$term}%";
                $qb->where(function ($w) use ($like) {
                    $w->where('apellido',  'like', $like)
                        ->orWhere('nombre',   'like', $like)
                        ->orWhere('matricula', 'like', $like)
                        ->orWhere('email',    'like', $like)
                        ->orWhere('cuil',     'like', $like);
                });
            })
            // 🔽 Sólo si el checkbox "activos" viene marcado:
            ->where('activo', 'si')
            ->orderBy('apellido')
            ->orderBy('nombre');

        // Paginación que conserva la query
        $matriculados = $query->get();

        // Métricas (totales generales, sin filtrar)
        $total        = User::where('role', 'user')->count();
        $activosCount = User::where('role', 'user')->where('activo', 'si')->count();

        return view('content.inicio.padron', compact(
            'matriculados',
            'q',
            'activos',
            'total',
            'activosCount',
            'leyesNavFiles',
            'footer'
        ));
    }

    public function listaInmobiliaria(Request $request)
    {
        $footer = Footer::first();
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->orderBy('created_at', 'desc')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->get();

        $q = trim((string) $request->input('q', ''));

        $inmobiliarias = Inmobiliaria::query()
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where(function ($w) use ($q) {
                    $w->where('nombre', 'like', "%{$q}%")
                        ->orWhere('localidad', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when('activo', fn($qb) => $qb->where('activo', 'si'))
            ->orderBy('localidad')
            ->orderBy('nombre')
            ->get([
                'id',
                'nombre',
                'telefono',
                'email',
                'direccion',
                'localidad',
                'url_web',
                'instagram',
                'facebook',
                'whatsapp',
                'logo_url'
            ]);

        // Agrupar por localidad (normalizando y manejando vacíos)
        $porLocalidad = $inmobiliarias
            ->groupBy(function ($i) {
                $loc = trim((string) $i->localidad);
                return $loc !== '' ? mb_strtoupper($loc) : 'SIN LOCALIDAD';
            })
            ->sortKeys(); // ordena alfabéticamente las claves (localidades)

        return view('content.inicio.inmobiliarias', compact('footer', 'leyesNavFiles', 'porLocalidad', 'q'));
    }

    public function denuncias()
    {
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->orderBy('created_at', 'desc')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->get();
        $footer = Footer::first();

        return view('content.inicio.denuncias', compact('footer', 'leyesNavFiles'));
    }

    public function convenios()
    {
        // Igual que antes:
        $leyesNavFiles = Archivo::where('carpeta', 'Leyes y otros')
            ->where('nombre_original', '!=', '(carpeta vacía)')
            ->orderBy('created_at', 'desc')
            ->get();

        // 🔹 Nuevo: traer TODO lo que esté en carpetas cuyo nombre contenga "convenio"
        // (ej: "convenio municipalidad de Cipolletti", "Convenio Fernández Oro", etc.)
        $conveniosPorCarpeta = Archivo::where('nombre_original', '!=', '(carpeta vacía)')
            ->whereRaw('LOWER(carpeta) LIKE ?', ['%convenio%'])
            ->orderBy('carpeta')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('carpeta'); // para listarlo agrupado en la vista

        $footer = Footer::first();

        return view('content.inicio.convenios', compact('footer', 'leyesNavFiles', 'conveniosPorCarpeta'));
    }
}
