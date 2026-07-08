<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\BienvenidoMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/*
|===========================================================================
| ApiAuthController
|===========================================================================
| Controlador de autenticación para API usando Sanctum.
| Proporciona registro, login, logout y gestión de tokens.
|
| Flujo:
| 1. Usuario se registra en POST /api/register
| 2. Usuario inicia sesión en POST /api/login → recibe un token
| 3. Usa el token en header: Authorization: Bearer {token}
| 4. Puede cerrar sesión en POST /api/logout
|===========================================================================
*/

class ApiAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | POST /api/register
    |--------------------------------------------------------------------------
    | Crea un nuevo usuario y devuelve un token de API.
    | Body: name, email, password, password_confirmation
    |--------------------------------------------------------------------------
    */
    public function register(Request $request)
    {
        // Validar datos de registro
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Crear usuario
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generar token de API
        $token = $user->createToken('auth_token')->plainTextToken;

        // Enviar correo de bienvenida (Mailtrap / SMTP)
        try {
            Mail::to($user)->send(new BienvenidoMail($user));
        } catch (\Exception $e) {
            // Si el correo falla, no bloquear el registro
            logger()->warning('Error al enviar correo de bienvenida: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Usuario registrado correctamente. Se envió un correo de bienvenida.',
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | POST /api/login
    |--------------------------------------------------------------------------
    | Autentica un usuario existente y devuelve un token de API.
    | Body: email, password
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Buscar usuario por email
        $user = User::where('email', $request->email)->first();

        // Verificar credenciales
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Eliminar tokens anteriores (sesión limpia)
        $user->tokens()->delete();

        // Generar nuevo token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'user'  => $user,
            'token' => $token,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | POST /api/logout
    |--------------------------------------------------------------------------
    | Cierra la sesión del usuario eliminando el token actual.
    | Requiere: auth:sanctum
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        // Eliminar el token de la petición actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente',
        ]);
    }
}
