<?php

namespace App\Http\Controllers;

use App\Mail\NuevoRegistro;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    // Muestra el formulario de login para usuarios comunes
    public function showUserLoginForm()
    {
        return view('content.login.index');
    }

    // Muestra el formulario de login para admin
    // public function showAdminLoginForm()
    // {
    //     return view('auth.login-admin');
    // }

    // Procesa el login (puede ser compartido)
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'cuil' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['cuil' => $validatedData['cuil'], 'password' => $validatedData['password']], $request->has('remember'))) {
            $user = Auth::user();

            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/admin')->with('success', 'Bienvenido, Admin.');
                case 'user':
                    return redirect()->intended('/mi-panel')->with('success', 'Bienvenido.');
                default:
                    Auth::logout();
                    return redirect()->route('inicio')->with('error', 'Rol no autorizado.');
            }
        }

        return back()->with('error', 'Credenciales incorrectas.');
    }

    public function registro(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'passw' => 'required|string|min:5',
        ]);

        try {
            $user = User::create([
                'nombre' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->passw),
                'role' => "user",
                'is_paid' => "no",
            ]);

            // Loguear al usuario automáticamente
            Auth::login($user);

            Mail::to('email@email.com')->send(new NuevoRegistro($user));

            return redirect()->back()->with('success', 'Usuario creado correctamente');
        } catch (\Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el usuario: ' . $e->getMessage())
                ->with('modal', 'register');
        }
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('inicio')->with('success', 'Logout.');
    }
}
