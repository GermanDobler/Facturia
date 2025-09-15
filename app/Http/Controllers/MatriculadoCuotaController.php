<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cuota;
use App\Models\MatriculadoCuota;
use Illuminate\Http\Request;

class MatriculadoCuotaController extends Controller
{
    public function index(Cuota $cuota, Request $r)
    {
        $q = MatriculadoCuota::with(['user', 'cuota'])
            ->where('cuota_id', $cuota->id);

        if ($search = $r->input('q')) {
            $q->whereHas('user', function ($qq) use ($search) {
                $qq->where('email', 'like', "%$search%")
                    ->orWhere('nombre', 'like', "%$search%")
                    ->orWhere('apellido', 'like', "%$search%");
            });
        }

        $instancias = $q->orderBy('id', 'desc')->paginate(25)->withQueryString();
        return view('content.cuotas.instancias', compact('cuota', 'instancias'));
    }

    public function update(Request $r, MatriculadoCuota $instancia)
    {
        $data = $r->validate([
            'monto'  => ['nullable', 'numeric', 'min:0'],
            'estado' => ['nullable', 'in:pendiente,revision,pagada,rechazada,exenta,cancelada'],
        ]);

        if (array_key_exists('monto', $data)) {
            $instancia->monto = $data['monto'];
            $instancia->personalizada = true;
        }
        if (array_key_exists('estado', $data)) {
            $instancia->estado = $data['estado'];
        }

        $instancia->save();
        return back()->with('ok', 'Instancia actualizada');
    }
}
