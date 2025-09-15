<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    // Obtener todas las FAQs
    public function index()
    {
        $faqs = Faq::orderBy('order', 'asc')->get();
        return view('content.faqs.index', compact('faqs'));
    }

    public function show($id)
    {
        $faq = Faq::find($id);
        if (!$faq) {
            return response()->json(['error' => 'FAQ no encontrada'], 404);
        }
        return response()->json($faq);
    }

    // Crear una nueva FAQ
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order' => 'integer'
        ]);

        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'order' => $request->order ?? 0
        ]);

        // Redirecciona de nuevo a la vista de FAQs
        return redirect()->route('faqs.index')->with('success', 'FAQ creada exitosamente.');
    }

    // En tu controlador de FAQs
    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $faq = Faq::find($id);

        if (!$faq) {
            return response()->json(['error' => 'FAQ no encontrada'], 404);
        }

        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();

        return response()->json(['success' => 'FAQ actualizada con éxito']);
    }

    // Eliminar una FAQ
    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        // Redirecciona de nuevo a la vista de FAQs
        return redirect()->route('faqs.index')->with('success', 'FAQ eliminada exitosamente.');
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $index => $id) {
            Faq::where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

}