<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comprobante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComprobanteReviewController extends Controller
{
    public function index()
    {
        $pendientes = Comprobante::with(['instancia.user', 'instancia.cuota'])
            ->where('estado', 'revision')
            ->latest()->paginate(20);

        return view('content.comprobantes.index', compact('pendientes'));
    }

    public function aprobar(Comprobante $comprobante)
    {
        DB::transaction(function () use ($comprobante) {
            $inst = $comprobante->instancia()->lockForUpdate()->first();

            $comprobante->update([
                'estado'      => 'aprobado',
                'revisado_por' => auth()->id(),
                'revisado_at' => now(),
            ]);

            $nuevo = $inst->monto_pagado + $comprobante->monto_declarado;

            $inst->update([
                'monto_pagado'   => $nuevo,
                'ultimo_pago_at' => now(),
                // Si manejás 'parcial' en el enum, usalo aquí en lugar de 'pendiente'
                'estado'         => $nuevo >= $inst->monto ? 'pagada' : 'pendiente',
            ]);
        });

        return back()->with('ok', 'Comprobante aprobado');
    }

    public function rechazar(Request $r, Comprobante $comprobante)
    {
        $data = $r->validate(['motivo' => ['nullable', 'string', 'max:500']]);

        DB::transaction(function () use ($comprobante, $data) {
            $inst = $comprobante->instancia()->lockForUpdate()->first();

            $comprobante->update([
                'estado'      => 'rechazado',
                'notas_admin' => $data['motivo'] ?? null,
                'revisado_por' => auth()->id(),
                'revisado_at' => now(),
            ]);

            $inst->update(['estado' => 'rechazada']);
        });

        return back()->with('ok', 'Comprobante rechazado');
    }
}
