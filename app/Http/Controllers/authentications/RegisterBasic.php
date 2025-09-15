<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Log;

class RegisterBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-register-basic');
  }
  public function register(Request $request)
  {
    // Guardar usuario
    try {
      $user = User::create([
        'name' => $request->input('username'),
        'email' => $request->input('email'),
        'password' => $request->input('password'), // Laravel lo hashea automáticamente
      ]);
    } catch (\Exception $e) {
      // Si ocurre un error de integridad por duplicado
      return redirect()->back()->with('error', 'El correo electrónico ya está registrado.');
    }

    // Redirigir o devolver una respuesta
    return redirect()->route('dashboard-analytics')->with('success', 'Registro exitoso');
  }
}
