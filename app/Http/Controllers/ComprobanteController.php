<?php

namespace App\Http\Controllers;

use App\Models\MatriculadoCuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComprobanteController extends Controller
{
    public function store(Request $r, MatriculadoCuota $instancia)
    {
        abort_unless($instancia->user_id === auth()->id(), 403);
        abort_if(!in_array($instancia->estado, ['pendiente', 'revision', 'rechazada']), 422, 'Estado inválido');

        $data = $r->validate([
            'monto_declarado'     => ['required', 'numeric', 'min:0.01'],
            'fecha_transferencia' => ['nullable', 'date'],
            'referencia'          => ['nullable', 'string', 'max:120'],
            'archivo'             => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:8192'],
        ]);

        $path = $r->file('archivo')->store('comprobantes', 'public');

        DB::transaction(function () use ($instancia, $data, $path) {
            $instancia->comprobantes()->create([
                'monto_declarado'     => $data['monto_declarado'],
                'fecha_transferencia' => $data['fecha_transferencia'] ?? null,
                'referencia'          => $data['referencia'] ?? null,
                'archivo_path'        => $path,
                'estado'              => 'revision',
            ]);
            $instancia->update(['estado' => 'revision']);
        });

        return back()->with('ok', 'Comprobante enviado. Queda en revisión.');
    }
}
