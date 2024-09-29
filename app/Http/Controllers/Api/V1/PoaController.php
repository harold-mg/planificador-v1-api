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
            'operacion' => 'required|string',
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
            'operacion' => 'sometimes|required|string',
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

}
