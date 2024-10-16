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
    public function __construct()
    {
        $this->middleware('auth:sanctum');  // Asegúrate de que este middleware esté aplicado
    }
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
        // Determinar el nivel de aprobación en función del rol del usuario
        // Supongamos que el request trae el rol del usuario autenticado
        $usuario = auth()->user(); // Obtiene el usuario autenticado
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no autenticado'], 401); // Retorna error si no está autenticado
        }
        $nivel_aprobacion = 'unidad'; // Por defecto, actividades creadas por responsables de área necesitan revisión de unidad
        
        if ($usuario->rol == 'responsable_unidad') {
            // Si el usuario es Responsable de Unidad, su actividad pasa directamente al planificador
            $nivel_aprobacion = 'planificador';
        }
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
            'nivel_aprobacion' => $nivel_aprobacion,
            //'nivel_aprobacion' => 'unidad',
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

    // Método para aprobar actividad por parte del responsable de unidad
    public function aprobarPorUnidad($id)
    {
        $actividad = ActividadVehiculo::findOrFail($id);
        
        // Verificar que el usuario tenga el rol correcto para aprobar
        if (auth()->user()->rol !== 'responsable_unidad') {
            return response()->json(['error' => 'No tienes permisos para aprobar esta actividad'], 403);
        }

        // Verificar que la actividad esté en el nivel correcto de aprobación
        if ($actividad->nivel_aprobacion === 'unidad') {
            $actividad->nivel_aprobacion = 'planificador'; // Cambiar al siguiente nivel de aprobación
            $actividad->save();
            
            return response()->json(['message' => 'Actividad aprobada por la unidad y enviada al planificador']);
        }

        return response()->json(['message' => 'No se puede aprobar esta actividad'], 400);
    }

    // Método para aprobar actividad por parte del planificador
    public function aprobarPorPlanificador($id)
    {
        $actividad = ActividadVehiculo::findOrFail($id);
        
        // Verificar que el usuario tenga el rol correcto para aprobar
        if (auth()->user()->rol !== 'planificador') {
            return response()->json(['error' => 'No tienes permisos para aprobar esta actividad'], 403);
        }

        // Verificar que la actividad esté en el nivel correcto de aprobación
        if ($actividad->nivel_aprobacion === 'planificador') {
            $actividad->estado_aprobacion = 'aprobado'; // Cambiar el estado a aprobado
            $actividad->save();
            
            return response()->json(['message' => 'Actividad aprobada por el planificador']);
        }

        return response()->json(['message' => 'No se puede aprobar esta actividad'], 400);
    }

    // Método para rechazar actividad
    public function rechazar($id)
    {
        $actividad = ActividadVehiculo::findOrFail($id);
        
        // Verificar que el usuario tenga el rol correcto para rechazar
        if (auth()->user()->rol !== 'responsable_unidad' && auth()->user()->rol !== 'planificador') {
            return response()->json(['error' => 'No tienes permisos para rechazar esta actividad'], 403);
        }

        $actividad->estado_aprobacion = 'rechazado'; // Cambiar el estado a rechazado
        $actividad->save();

        return response()->json(['message' => 'Actividad rechazada']);
    }

    
}
