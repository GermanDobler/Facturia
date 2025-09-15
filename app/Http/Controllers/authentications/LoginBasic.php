<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginBasic extends Controller
{
    public function index()
    {
        return view('content.authentications.auth-login-basic');
    }

    public function login(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        // Intentar iniciar sesión
        if (Auth::attempt(['nombre' => $validatedData['name'], 'password' => $validatedData['password']], $request->has('remember'))) {
            // Obtener el usuario autenticado
            $user = Auth::user();

            // Redirigir según el rol
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/admin')->with('success', 'Bienvenido, Admin General.');
                default:
                    return redirect('/')->with('success', 'Bienvenido.');
            }
        }

        // Si falla la autenticación, redirige con un mensaje de error
        return redirect()->route('login.form')->with('error', 'Credenciales incorrectas.');
    }

    // Método para el logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.form')->with('success', 'Has cerrado sesión exitosamente.');
    }
}
