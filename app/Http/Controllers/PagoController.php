<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use App\Models\Pago;
use App\Models\ArchivoPago;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PagoController extends Controller
{
    /** Usuario sube comprobante para una cuota */
    public function store(Request $request, Cuota $cuota)
    {
        // Validación de propiedad de la cuota
        abort_unless($request->user()->id === (int)$cuota->usuario_id, 403, 'No podés operar esta cuota.');

        // Solo permitir si está pendiente o rechazada
        abort_unless(in_array($cuota->estado, ['pendiente', 'rechazado']), 400, 'La cuota no admite nuevos pagos.');

        DB::transaction(function () use ($cuota, $request) {
            $pago = Pago::create([
                'cuota_id'  => $cuota->id,
                'metodo'    => "transferencia",
                'pagado_en' => Date::now(),
                'referencia' => null,
                'notas'     => null,
                'estado'    => 'en_revision',
            ]);

            // Guardar archivos
            foreach ($request->file('archivos') as $file) {
                $path = $file->store('comprobantes', 'public'); // ajustar a S3 si corresponde
                ArchivoPago::create([
                    'pago_id' => $pago->id,
                    'ruta'    => $path,
                    'mime'    => $file->getClientMimeType(),
                    'tamano'  => $file->getSize(),
                ]);
            }

            // Marcar cuota como enviada
            $cuota->update(['estado' => 'enviado']);

            Auditoria::create([
                'entidad_tipo' => Pago::class,
                'entidad_id'   => $pago->id,
                'accion'       => 'pago.enviado',
                'actor_id'     => $request->user()->id,
                'meta'         => ['cuota_id' => $cuota->id],
            ]);
        });

        return back()->with('success', 'Comprobante enviado. Queda en revisión.');
    }

    /** Admin aprueba un pago */
    public function aprobar(Request $request, Pago $pago)
    {

        DB::transaction(function () use ($pago, $request) {
            // Lock lógico (opcional): asegurarse que está en revisión
            abort_unless($pago->estado === 'en_revision', 400, 'El pago no está en revisión.');

            $pago->update(['estado' => 'aprobado']);

            $pago->cuota()->update([
                'estado'       => 'aprobado',
                'aprobado_en'  => now(),
                'aprobado_por' => $request->user()->id,
            ]);

            Auditoria::create([
                'entidad_tipo' => Pago::class,
                'entidad_id'   => $pago->id,
                'accion'       => 'pago.aprobado',
                'actor_id'     => $request->user()->id,
                'meta'         => ['cuota_id' => $pago->cuota_id],
            ]);

            Auditoria::create([
                'entidad_tipo' => \App\Models\Cuota::class,
                'entidad_id'   => $pago->cuota_id,
                'accion'       => 'cuota.aprobada',
                'actor_id'     => $request->user()->id,
                'meta'         => null,
            ]);
        });

        return back()->with('success', 'Pago aprobado y cuota marcada como aprobada.');
    }

    /** Admin rechaza un pago (permite reenvío) */
    public function rechazar(Request $request, Pago $pago)
    {

        $data = $request->validate([
            'motivo' => 'required|string|max:2000',
        ]);

        DB::transaction(function () use ($pago, $data, $request) {
            abort_unless($pago->estado === 'en_revision', 400, 'El pago no está en revisión.');

            // Guardar el motivo en notas (append)
            $nuevaNota = trim(($pago->notas ? $pago->notas . "\n" : '') . '[Rechazo] ' . $data['motivo']);
            $pago->update(['estado' => 'rechazado', 'notas' => $nuevaNota]);

            // La cuota vuelve a rechazado (el usuario podrá reenviar)
            $pago->cuota()->update(['estado' => 'rechazado']);

            Auditoria::create([
                'entidad_tipo' => Pago::class,
                'entidad_id'   => $pago->id,
                'accion'       => 'pago.rechazado',
                'actor_id'     => $request->user()->id,
                'meta'         => ['motivo' => $data['motivo']],
            ]);

            Auditoria::create([
                'entidad_tipo' => \App\Models\Cuota::class,
                'entidad_id'   => $pago->cuota_id,
                'accion'       => 'cuota.rechazada',
                'actor_id'     => $request->user()->id,
                'meta'         => ['motivo' => $data['motivo']],
            ]);
        });

        return back()->with('success', 'Pago rechazado. El usuario podrá reenviar con un nuevo comprobante.');
    }
}
