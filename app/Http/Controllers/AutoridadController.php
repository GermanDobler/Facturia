<?php

namespace App\Http\Controllers;

use App\Models\Autoridad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutoridadController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $items = Autoridad::query()
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where(function ($w) use ($q) {
                    $w->where('nombre', 'like', "%{$q}%")
                        ->orWhere('apellido', 'like', "%{$q}%")
                        ->orWhere('cargo', 'like', "%{$q}%");
                });
            })
            ->orderByRaw('CASE WHEN orden IS NULL THEN 1 ELSE 0 END') // primero los que tienen orden
            ->orderBy('orden')
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->paginate(20)
            ->withQueryString();

        return view('content.autoridades.index', compact('items', 'q'));
    }

    public function create()
    {
        $autoridad = new Autoridad();
        return view('content.autoridades.create', compact('autoridad'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        // nuevo al final
        $data['orden'] = (int) (Autoridad::max('orden') + 1);

        Autoridad::create($data);

        return redirect()->route('autoridades.index')->with('success', 'Autoridad creada correctamente.');
    }


    public function edit(Autoridad $autoridad)
    {
        return view('content.autoridades.edit', compact('autoridad'));
    }

    public function update(Request $request, Autoridad $autoridad)
    {
        $data = $this->validated($request);
        $autoridad->update($data);

        return redirect()->route('autoridades.index')
            ->with('success', 'Autoridad actualizada.');
    }

    public function destroy(Autoridad $autoridad)
    {
        $autoridad->delete();
        return back()->with('success', 'Autoridad eliminada.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'nombre'  => 'required|string|max:80',
            'apellido' => 'required|string|max:80',
            'cargo'   => 'required|string|max:80',
        ]);
    }

    public function sort(Request $request)
    {
        $data = $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:autoridades,id', // 'integer' acepta "1" también
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['ids'] as $pos => $id) {
                Autoridad::where('id', (int)$id)->update(['orden' => $pos + 1]);
            }
        });

        return response()->json(['ok' => true]);
    }
}
