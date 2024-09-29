<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Unidad;
use Illuminate\Http\Request;

class UnidadController extends Controller
{
    // Método para crear una nueva unidad
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|unique:unidades|max:255',
        ]);

        Unidad::create([
            'nombre' => $validated['nombre'],
        ]);

        return response()->json(['message' => 'Unidad creada correctamente']);
    }

    // Método para obtener todas las unidades
    public function index()
    {
        return Unidad::all();
    }

    // Método para obtener una unidad específica
    public function show($id)
    {
        return Unidad::findOrFail($id);
    }
}