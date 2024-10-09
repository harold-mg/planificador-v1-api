<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ActividadVehiculo;
use Illuminate\Http\Request;

use App\Models\Vehiculo;
use App\Models\POA; // Si tienes una tabla para los POAs
use App\Models\CentroSalud; // Si tienes una tabla para los centros de salud
use App\Models\Coordinacion;
use App\Models\Municipio;

class ActividadVehiculoController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'poa_id' => 'required|exists:poas,id',
            'detalle_operacion' => 'required|string',
            'resultados_esperados' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'centro_salud_id' => 'required|exists:centros_salud,id',
            'tecnico_a_cargo' => 'required|string',
            'detalles_adicionales' => 'nullable|string',
            //'estado_aprobacion' => 'required|string',
            'usuario_id' => 'required|exists:usuarios,id', // Esta línea se puede omitir
        ]);
            // Crea la actividad y asigna el user_id automáticamente
            //$actividad = ActividadVehiculo::create($request->except('usuario_id'));
            // Verificar si el usuario está autenticado
/*         $userId = auth()->id();
        if (!$userId) {
            return response()->json(['message' => 'Usuario no autenticado.'], 401);
        } */
    
        // Crear la actividad con vehículo
        $actividad = ActividadVehiculo::create([
            'poa_id' => $validated['poa_id'],
            'detalle_operacion' => $validated['detalle_operacion'],
            'resultados_esperados' => $validated['resultados_esperados'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'],
            'centro_salud_id' => $validated['centro_salud_id'],
            'tecnico_a_cargo' => $validated['tecnico_a_cargo'],
            'detalles_adicionales' => $validated['detalles_adicionales'],
            //'usuario_id' => auth()->id(), // ID del usuario autenticado
            'estado_aprobacion' => 'pendiente', // Estado inicial de la actividad
            //'usuario_id' => $request->usuario_id,
            'usuario_id' => $validated['usuario_id'], // Se obtiene del request validado
        ]);
    
        // Retornar la actividad creada o una respuesta adecuada
        return response()->json(['actividad' => $actividad], 201);
    }

/*     public function index(Request $request)
    {
        $actividades = ActividadVehiculo::with(['poa', 'centroSalud', 'usuario']) // Relaciona otras tablas si es necesario
            ->where('usuario_id', auth()->id()) // Solo las actividades del usuario autenticado
            ->get();
    
        return response()->json($actividades);
    } */
    // Método para obtener todas las actividades de vehículo
    public function index()
    {
        $actividades = ActividadVehiculo::all(); // Obtiene todas las actividades
        return response()->json($actividades); // Retorna las actividades como JSON
    }

    public function aprobarActividad(Request $request, $id)
    {
        $actividad = ActividadVehiculo::findOrFail($id);
    
        // Validar que el estado de la actividad aún esté pendiente
        if ($actividad->estado_aprobacion !== 'pendiente') {
            return response()->json(['error' => 'La actividad ya ha sido aprobada o rechazada.'], 400);
        }
    
        // Cambiar el estado de aprobación
        $actividad->estado_aprobacion = $request->input('estado_aprobacion'); // 'aprobado' o 'rechazado'
    
        if ($request->input('estado_aprobacion') === 'aprobado') {
            // Si es aprobado, buscar un vehículo disponible
            $vehiculo = Vehiculo::where('disponible', true)
                ->whereDoesntHave('actividadesVehiculo', function ($query) use ($actividad) {
                    $query->whereBetween('fecha_inicio', [$actividad->fecha_inicio, $actividad->fecha_fin]);
                })->first();
    
            if ($vehiculo) {
                $actividad->vehiculo_id = $vehiculo->id;
                $vehiculo->disponible = false; // Marcar el vehículo como no disponible
                $vehiculo->save();
            } else {
                return response()->json(['error' => 'No hay vehículos disponibles para las fechas seleccionadas.'], 400);
            }
        }
    
        $actividad->save();
    
        return response()->json(['success' => true, 'actividad' => $actividad]);
    }
    
    public function show($id)
    {
        $actividad = ActividadVehiculo::with(['poa', 'centroSalud', 'vehiculo'])->findOrFail($id);
    
        return response()->json($actividad);
    }
  
    public function update(Request $request, $id)
    {
        $actividad = ActividadVehiculo::findOrFail($id);
    
        // Verificar que la actividad esté en estado pendiente
        if ($actividad->estado_aprobacion !== 'pendiente') {
            return response()->json(['error' => 'La actividad ya no se puede editar.'], 400);
        }
    
        // Validar los nuevos datos
        $validated = $request->validate([
            'poa_id' => 'required|exists:poas,id',
            'resultados_esperados' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'centro_salud_id' => 'required|exists:centros_salud,id',
            'tecnico_a_cargo' => 'required|string',
            'detalles_adicionales' => 'nullable|string',
        ]);
    
        // Actualizar la actividad
        $actividad->update($validated);
    
        return response()->json(['success' => true, 'actividad' => $actividad]);
    }

    public function destroy($id)
    {
        $actividad = ActividadVehiculo::findOrFail($id);
    
        // Verificar que la actividad esté en estado pendiente
        if ($actividad->estado_aprobacion !== 'pendiente') {
            return response()->json(['error' => 'Solo se pueden eliminar actividades pendientes.'], 400);
        }
    
        $actividad->delete();
    
        return response()->json(['success' => true]);
    }
    
}
