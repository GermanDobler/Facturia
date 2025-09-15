<?php

namespace App\Http\Controllers;

use App\Models\Inmobiliaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InmobiliariaController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $items = Inmobiliaria::query()
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where(function ($w) use ($q) {
                    $w->where('nombre', 'like', "%{$q}%")
                        ->orWhere('localidad', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();

        return view('content.inmobiliarias.index', compact('items', 'q'));
    }

    public function create()
    {
        $inmobiliaria = new Inmobiliaria();
        return view('content.inmobiliarias.create', compact('inmobiliaria'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        // Logo (opcional)
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $path = $request->file('logo')->store('inmobiliarias', 'public');
            $data['logo_url'] = Storage::url($path); // guardamos URL pública (preferencia tuya)
        }

        Inmobiliaria::create($data);

        return redirect()->route('inmobiliarias.index')
            ->with('success', 'Inmobiliaria creada correctamente.');
    }

    public function edit(Inmobiliaria $inmobiliaria)
    {
        return view('content.inmobiliarias.edit', compact('inmobiliaria'));
    }

    public function update(Request $request, Inmobiliaria $inmobiliaria)
    {
        $data = $this->validated($request, $inmobiliaria->id);

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            // borrar logo anterior si era de /storage
            if ($inmobiliaria->logo_url && str_starts_with($inmobiliaria->logo_url, '/storage/')) {
                $old = str_replace('/storage/', '', $inmobiliaria->logo_url);
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('logo')->store('inmobiliarias', 'public');
            $data['logo_url'] = Storage::url($path);
        }

        $inmobiliaria->update($data);

        return redirect()->route('inmobiliarias.index')
            ->with('success', 'Inmobiliaria actualizada.');
    }

    public function destroy(Inmobiliaria $inmobiliaria)
    {
        // Opcional: borrar archivo al eliminar
        if ($inmobiliaria->logo_url && str_starts_with($inmobiliaria->logo_url, '/storage/')) {
            $old = str_replace('/storage/', '', $inmobiliaria->logo_url);
            Storage::disk('public')->delete($old);
        }
        $inmobiliaria->delete();

        return back()->with('success', 'Inmobiliaria eliminada.');
    }

    protected function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'nombre'     => 'required|string|max:150',
            'telefono'   => 'nullable|string|max:40',
            'email'      => 'nullable|email|max:150',
            'direccion'  => 'nullable|string|max:200',
            'localidad'  => 'nullable|string|max:120',
            'url_web'    => 'nullable|string|max:200',
            'activo'     => 'required|string|in:si,no',
            'instagram'  => 'nullable|string|max:200',
            'facebook'   => 'nullable|string|max:200',
            'whatsapp'   => 'nullable|string|max:40',
            'logo'       => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);
    }
}
