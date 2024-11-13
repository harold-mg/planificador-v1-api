<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Operacion;
use App\Models\Poa;
use Illuminate\Http\Request;

class OperacionController extends Controller
{
    public function index()
    {
        // Retornar todas las operaciones
        $operaciones = Operacion::with('poa')->get();
        return response()->json($operaciones);
    }

    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'poa_id' => 'required|exists:poas,id',
            'descripcion' => 'required|string',
        ]);

        // Crear una nueva operación
        $operacion = Operacion::create($request->all());
        return response()->json($operacion, 201);
    }

    public function show($id)
    {
        // Mostrar una operación específica
        $operacion = Operacion::with('poa')->findOrFail($id);
        return response()->json($operacion);
    }

    public function update(Request $request, $id)
    {
        $operacion = Operacion::findOrFail($id); // Buscar la operación por ID

        // Validar los datos
        $request->validate([
            'descripcion' => 'sometimes|required|string',
            'poa_id' => 'sometimes|required|exists:poas,id',
        ]);

        $operacion->update($request->all()); // Actualizar la operación
        return response()->json($operacion);
    }

    public function destroy($id)
    {
        $operacion = Operacion::findOrFail($id);
        $operacion->delete();
        return response()->json(null, 204);
    }
    public function getOperaciones($codigoPoa) {
        $operaciones = Poa::where('codigo_poa', $codigoPoa)->first()->operaciones; // O ajusta según la lógica de tu app
        return response()->json($operaciones);
    }
}