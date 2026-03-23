<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Mostrar el formulario de login por GET.
     *
     * @return \Illuminate\Contracts\View\View
     */

    public function mostrar_form_login()
    {
        return view('auth.login');
    }

    /**
     * Procesar el intento de inicio de sesión.
     *
     * Valida las credenciales ('email' y 'password')
     *     
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    
    public function funcion_login(Request $request)
    {
        // Validar los datos del formulario
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Se verifica email/password (true si ok)
        if (Auth::attempt($credentials)) {
            //Si ok--> se regenera sesión (se anota que está autenticado en la sesión).
            $request->session()->regenerate();
            //Redireccionamos a la página principal de la zona autenticada
            return redirect()->intended(route('zonaprivada'));
        }

        // Si la autenticación falla, volver al formulario con un error
        return back()->withErrors([
            'email' => 'El email o la contraseña no son válidos.',
        ])->onlyInput('email');
    }

    /**
     * Cerrar la sesión del usuario autenticado.
     *
     * Ejecuta 'Auth::logout()', invalida la sesión y regenera el token CSRF y redirige a la zona pública.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function funcion_logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('zonapublica'));
    }
}