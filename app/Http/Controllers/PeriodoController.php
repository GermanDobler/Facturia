<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use App\Models\Cuota;
use App\Models\Pago;
use App\Models\Auditoria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodoController extends Controller
{
    public function index(Request $request)
    {
        $periodos = Periodo::query()
            ->when($request->filled('estado'), fn($qq) => $qq->where('estado', $request->estado))
            ->orderByDesc('anio')->orderByDesc('mes')->paginate(12)->appends($request->query());

        return view('content.periodos.index', compact('periodos'));
    }

    public function show(Periodo $periodo)
    {
        $periodo->load('cuotas.usuario');
        return view('content.periodos.show', compact('periodo'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'             => 'required|string|max:50',
            'anio'               => 'required|integer|min:2000|max:2100',
            'mes'                => 'required|integer|min:1|max:12',
            'fecha_vencimiento'  => 'required|date',
            'monto'              => 'required|numeric|min:0',
        ]);

        $periodo = Periodo::create([
            ...$data,
            'estado'     => 'programado',
            'es_visible' => false,
            'creado_por' => $request->user()->id,
        ]);

        Auditoria::create([
            'entidad_tipo' => Periodo::class,
            'entidad_id'   => $periodo->id,
            'accion'       => 'periodo.creado',
            'actor_id'     => $request->user()->id,
            'meta'         => $data,
        ]);

        return back()->with('success', 'Periodo creado (programado y oculto).');
    }

    /** Publicar periodo: genera cuotas y lo vuelve visible */
    public function abrir(Periodo $periodo, Request $request)
    {
        abort_unless($periodo->estado === 'programado', 400, 'Solo se puede abrir un periodo programado.');

        DB::transaction(function () use ($periodo, $request) {
            // ✅ sin filtros de estado/activo: todos los usuarios
            $usuarios = User::where('role', 'user')->pluck('id');

            $now = now();
            $bulk = $usuarios->map(fn($uid) => [
                'periodo_id'  => $periodo->id,
                'usuario_id'  => $uid,
                'monto'       => $periodo->monto,
                'recargo'     => 0,
                'descuento'   => 0,
                'monto_final' => $periodo->monto,
                'estado'      => 'pendiente',
                'created_at'  => $now,
                'updated_at'  => $now,
            ])->all();

            if (!empty($bulk)) {
                Cuota::insert($bulk);
            }

            $periodo->update(['estado' => 'abierto', 'es_visible' => true]);

            Auditoria::create([
                'entidad_tipo' => Periodo::class,
                'entidad_id'   => $periodo->id,
                'accion'       => 'periodo.abierto',
                'actor_id'     => $request->user()->id,
                'meta'         => ['cuotas_generadas' => count($bulk)],
            ]);
        });

        return back()->with('success', 'Periodo abierto y cuotas generadas para todos los usuarios.');
    }


    /** Cancelar un periodo programado (aún oculto) */
    public function cancelar(Periodo $periodo, Request $request)
    {
        abort_unless($periodo->estado === 'programado', 400, 'Solo se puede cancelar un periodo programado.');

        $periodo->update(['estado' => 'anulado', 'es_visible' => false]);

        Auditoria::create([
            'entidad_tipo' => Periodo::class,
            'entidad_id'   => $periodo->id,
            'accion'       => 'periodo.cancelado',
            'actor_id'     => $request->user()->id,
            'meta'         => null,
        ]);

        return back()->with('success', 'Periodo anulado (nunca fue visible).');
    }

    /** Revertir un periodo abierto: borrar cuotas sin pagos, eximir cuotas con pagos, anular pagos y ocultar periodo */
    public function revertirCancelacion(Periodo $periodo, Request $request)
    {
        abort_unless($periodo->estado === 'abierto', 400, 'Solo se puede revertir un periodo abierto.');

        DB::transaction(function () use ($periodo, $request) {
            // IDs de cuotas del periodo
            $cuotaIds = Cuota::where('periodo_id', $periodo->id)->pluck('id');

            if ($cuotaIds->isEmpty()) {
                $periodo->update(['estado' => 'anulado', 'es_visible' => false]);
                return;
            }

            // Cuotas que tienen pagos
            $cuotasConPagos = Pago::whereIn('cuota_id', $cuotaIds)->pluck('cuota_id')->unique();
            $cuotasSinPagos = $cuotaIds->diff($cuotasConPagos);

            // Borrar cuotas sin pagos
            if ($cuotasSinPagos->isNotEmpty()) {
                Cuota::whereIn('id', $cuotasSinPagos)->delete();
            }

            // Eximir cuotas con pagos y anular sus pagos
            if ($cuotasConPagos->isNotEmpty()) {
                Cuota::whereIn('id', $cuotasConPagos)->update(['estado' => 'exento']);
                Pago::whereIn('cuota_id', $cuotasConPagos)->update(['estado' => 'anulado']);
            }

            $periodo->update(['estado' => 'anulado', 'es_visible' => false]);

            Auditoria::create([
                'entidad_tipo' => Periodo::class,
                'entidad_id'   => $periodo->id,
                'accion'       => 'periodo.revertido_y_cancelado',
                'actor_id'     => $request->user()->id,
                'meta'         => [
                    'cuotas_borradas' => $cuotasSinPagos->count(),
                    'cuotas_exentas'  => $cuotasConPagos->count(),
                ],
            ]);
        });

        return back()->with('success', 'Periodo revertido: cuotas sin pagos borradas, con pagos exentas; periodo anulado y oculto.');
    }

    /** Cerrar manualmente el periodo */
    public function cerrar(Periodo $periodo, Request $request)
    {
        abort_unless($periodo->estado === 'abierto', 400, 'Solo se puede cerrar un periodo abierto.');

        // (Opcional) Validar que no queden cuotas pendientes/enviadas
        $quedan = Cuota::where('periodo_id', $periodo->id)
            ->whereIn('estado', ['pendiente', 'enviado', 'rechazado'])
            ->exists();
        if ($quedan) {
            return back()->with('error', 'Aún hay cuotas sin aprobar. No se puede cerrar.');
        }

        $periodo->update(['estado' => 'cerrado']);

        Auditoria::create([
            'entidad_tipo' => Periodo::class,
            'entidad_id'   => $periodo->id,
            'accion'       => 'periodo.cerrado',
            'actor_id'     => $request->user()->id,
            'meta'         => null,
        ]);

        return back()->with('success', 'Periodo cerrado.');
    }

    public function update(Request $request, Periodo $periodo)
    {
        abort_if(in_array($periodo->estado, ['cerrado', 'anulado']), 400, 'No se puede editar un periodo cerrado/anulado.');

        $data = $request->validate([
            'nombre' => 'required|string|max:50',
            'monto'  => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($periodo, $data) {
            // 1) Actualizar el período
            $periodo->update([
                'nombre' => $data['nombre'],
                'monto'  => $data['monto'],
            ]);

            // 2) Propagar SIEMPRE a las cuotas no aprobadas/exentas
            $estadosEditables = ['pendiente', 'enviada', 'rechazada'];

            Cuota::where('periodo_id', $periodo->id)
                ->whereIn('estado', $estadosEditables)
                ->update([
                    'monto'      => $data['monto'],
                    'monto_final' => $data['monto'], // asumiendo sin recargos/descuentos
                    'updated_at' => now(), // opcional para reflejar el cambio
                ]);
        });

        return back()->with(
            'success',
            'Periodo y cuotas pendientes/enviadas/rechazadas actualizados con el nuevo monto.'
        );
    }
}
