<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = User::query();

    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         $query->where(function ($q) use ($search) {
    //             $q->where('nombre', 'like', "%{$search}%")
    //                 ->orWhere('apellido', 'like', "%{$search}%")
    //                 ->orWhere('email', 'like', "%{$search}%")
    //                 ->orWhere('cuil', 'like', "%{$search}%")
    //                 ->orWhere('matricula', 'like', "%{$search}%");
    //         });
    //     }

    //     $users = $query
    //         ->withCount(['cuotas as cuotas_pendientes_count' => function ($q) {
    //             // Consideramos como deuda las cuotas que no estén pagadas/anuladas
    //             $q->whereIn('estado', ['pendiente', 'rechazado', 'enviado']);

    //             // 🔹 Si querés limitar solo al periodo abierto/visible:
    //             // $q->whereHas('periodo', fn($p) => $p->where('estado', 'abierto')->where('es_visible', true));
    //         }])
    //         ->where('role', 'user')
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('content.users.index', compact('users'));
    // }

    public function index(Request $request)
    {
        $query = User::query()
            ->with([
                'cuotas' => function ($q) {
                    $q->whereHas('periodo', function ($p) {
                        $p->where('periodos.estado', 'abierto')        // 👈 calificado
                            ->where('periodos.es_visible', true);        // 👈 calificado
                    });
                },
                'ultimaCuotaAprobada' => function ($q) {
                    $q->where('cuotas.estado', 'aprobado')    // 👈 calificado (por si acaso)
                        ->orderBy('cuotas.id', 'desc')
                        ->limit(1);
                },
                // 🔴 QUITAR el eager de 'pagos' con limit(1)
            ])
            // ✅ Agregado por usuario: último pago aprobado (fecha)
            ->withMax([
                'pagos as ultimo_pago_en' => function ($q) {
                    $q->where('pagos.estado', 'aprobado');   // 👈 calificado
                }
            ], 'pagado_en')

            ->where('role', 'user');


        // Filtro de búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('apellido', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('cuil', 'like', "%{$search}%")
                    ->orWhere('matricula', 'like', "%{$search}%");
            });
        }

        // Filtro por activo
        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }

        // Filtro por al día (basado en cuotas pendientes)
        if ($request->filled('al_dia')) {
            if ($request->al_dia === 'si') {
                // Al día: 0 cuotas pendientes
                $query->whereDoesntHave('cuotas', function ($q) {
                    $q->whereIn('estado', ['pendiente', 'rechazado', 'enviado'])
                        ->whereHas('periodo', function ($p) {
                            $p->where('estado', 'abierto')->where('es_visible', true);
                        });
                });
            } else {
                // Con deuda: al menos 1 cuota pendiente
                $query->whereHas('cuotas', function ($q) {
                    $q->whereIn('estado', ['pendiente', 'rechazado', 'enviado'])
                        ->whereHas('periodo', function ($p) {
                            $p->where('estado', 'abierto')->where('es_visible', true);
                        });
                });
            }
        }

        // Agregar conteo de cuotas pendientes (solo periodos abiertos/visibles)
        $query->withCount(['cuotas as cuotas_pendientes_count' => function ($q) {
            $q->whereIn('cuotas.estado', ['pendiente', 'rechazado', 'enviado']) // 👈 calificado
                ->whereHas('periodo', function ($p) {
                    $p->where('periodos.estado', 'abierto')      // 👈 calificado
                        ->where('periodos.es_visible', true);      // 👈 calificado
                });
        }]);


        $users = $query->orderBy('created_at', 'desc')->get();

        return view('content.users.index', compact('users'));
    }


    public function create()
    {
        return view('content.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cuil' => 'nullable|string|max:20',
            'matricula' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'nullable|string|max:20',
            'password' => 'required|string|min:5',
            'activo' => 'required|string|in:si,no',
        ]);

        try {
            User::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'cuil' => $request->cuil,
                'email' => $request->email,
                'matricula' => $request->matricula,
                'password' => bcrypt($request->password),
                'telefono' => $request->telefono,
                'role' => 'user',
                'activo' => $request->activo,
            ]);

            return redirect()
                ->route('users.create')
                ->with('success', 'Matriculado Registrado con exito!.');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        return view('content.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'cuil' => 'nullable|string|max:20',
            'matricula' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string|max:20',
            'role' => 'required',
            'activo' => 'required|string|in:si,no',
            'observacion' => 'nullable|string',
        ]);

        $user->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'cuil' => $request->cuil,
            'matricula' => $request->matricula,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'role' => 'user',
            'activo' => $request->activo,
            'observacion' => $request->observacion,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario eliminado.');
    }

    public function archivero($id)
    {
        $user = User::findOrFail($id);
        $archivos = Archivo::where('user_id', $id)->get();

        return view('content.users.archivero', compact('user', 'archivos'));
    }
}
