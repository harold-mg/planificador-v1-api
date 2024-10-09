<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Poa;
use Illuminate\Http\Request;

class PoaController extends Controller
{
    public function index()
    {
        $poas = Poa::with(['area', 'unidad'])->get(); // Obtiene todos los POAs junto con sus áreas y unidades
        return response()->json($poas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo_poa' => 'required|string',
            //'operacion' => 'required|string',
            'area_id' => 'nullable|exists:areas,id', // area_id puede ser null
            'unidad_id' => 'required|exists:unidades,id', // unidad_id es obligatorio
        ]);

        $poa = Poa::create($request->all());
        return response()->json($poa, 201);
    }

    public function show(Poa $poa)
    {
        return response()->json($poa->load(['area', 'unidad']));
    }

    public function update(Request $request, Poa $poa)
    {
        $request->validate([
            'codigo_poa' => 'sometimes|required|string|unique:poas,codigo_poa,' . $poa->id,
            //'operacion' => 'sometimes|required|string',
            'area_id' => 'sometimes|required|exists:areas,id',
            'unidad_id' => 'sometimes|required|exists:unidades,id', // Agrega validación para unidad_id
        ]);

        $poa->update($request->all());
        return response()->json($poa);
    }

    public function destroy(Poa $poa)
    {
        $poa->delete();
        return response()->json(null, 204);
    }
    public function getAreasByUnidad($unidad_id)
    {
        // Obtener las áreas que pertenecen a la unidad seleccionada
        $areas = Area::where('unidad_id', $unidad_id)->get();

        return response()->json($areas);
    }

    public function getOperacionesByPoa($codigo_poa)
    {
        // Buscar el POA por su código
        $poa = Poa::where('codigo_poa', $codigo_poa)->first();
    
        // Verificar si el POA existe
        if (!$poa) {
            return response()->json(['message' => 'POA no encontrado'], 404);
        }
    
        // Devolver las operaciones directamente
        return response()->json($poa->operacion);
    }
    public function getOperacionesByCodigo($codigoPoa) {
        $operaciones = Poa::where('codigo_poa', $codigoPoa)->first()->operaciones; // O ajusta según la lógica de tu app
        return response()->json($operaciones);
    }
    public function getOperaciones($poaId) {
        // Busca el POA por su ID y luego obtén las operaciones relacionadas
        $poa = Poa::find($poaId);
    
        if ($poa) {
            $operaciones = $poa->operaciones; // Asumiendo que 'operaciones' es una relación en el modelo Poa
            return response()->json($operaciones, 200);
        } else {
            return response()->json(['error' => 'POA no encontrado'], 404);
        }
    }
/*     public function getOperacionesByPoa($codigo_poa)
    {
        // Buscar el POA por su código
        $poa = Poa::where('codigo_poa', $codigo_poa)->first();

        // Verificar si el POA existe
        if (!$poa) {
            return response()->json(['message' => 'POA no encontrado'], 404);
        }

        // Devolver las operaciones relacionadas con ese POA
        return response()->json($poa->operacion);
    } */


}
