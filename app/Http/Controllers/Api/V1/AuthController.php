<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Método para registrar usuarios
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cedula_identidad' => 'required|string|max:255|unique:usuarios',
            'nombre_usuario' => 'required|string|max:255|unique:usuarios',
            'password' => 'required|string|min:6|confirmed',  // Confirmar que las contraseñas coincidan
            'rol' => 'required|string|in:responsable_area,responsable_unidad,planificador',
        ]);

        $usuario = Usuario::create([
            'nombre' => $validatedData['nombre'],
            'apellido' => $validatedData['apellido'],
            'cedula_identidad' => $validatedData['cedula_identidad'],
            'nombre_usuario' => $validatedData['nombre_usuario'],
            'password' => Hash::make($validatedData['password']),
            'rol' => $validatedData['rol'], // Usar el rol especificado en la solicitud
            //'rol' => 'responsable_area', // Por defecto, asignar un rol (ajusta según tus necesidades)
        ]);

        // Crear un token para el usuario
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Método para iniciar sesión
    public function login(Request $request)
    {
        $credentials = $request->only('nombre_usuario', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $usuario = Auth::user();
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Método para cerrar sesión
    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    // Método para obtener el usuario autenticado
    public function me()
    {
        return response()->json(Auth::user());
    }
    public function user()
    {
        return response()->json(auth()->user());
    }
}

