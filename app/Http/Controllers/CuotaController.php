<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use Illuminate\Http\Request;

class CuotaController extends Controller
{
    /** Listado admin con filtros */
    public function index(Request $request)
    {
        $q = Cuota::with(['usuario', 'periodo'])
            ->when($request->filled('estado'), fn($qq) => $qq->where('estado', $request->estado))
            ->when($request->filled('periodo_id'), fn($qq) => $qq->where('periodo_id', $request->periodo_id))
            ->when($request->filled('usuario_id'), fn($qq) => $qq->where('usuario_id', $request->usuario_id))
            ->orderByDesc('id');

        return view('content.cuotas.index', ['cuotas' => $q->paginate(50)]);
    }
}
