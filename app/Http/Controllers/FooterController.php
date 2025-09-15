<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Models\Footer;

class FooterController extends Controller
{
    public function index()
    {
        $footer = Footer::first();

        return view('content.footer.index', compact('footer'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $footer = Footer::firstOrCreate([]);
        $footer->update($request->all());

        return redirect()->back()->with('success', 'Se actualizo correctamente');
    }
}
