<?php

namespace App\Http\Controllers\reportes;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ActividadVehiculo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReporteConVehiculo extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');  // Asegúrate de que este middleware esté aplicado
    }
    public function generarReporteMensual(Request $request, $mes, $year)
    {
        // Obtener el año actual
        $year = date('Y');
    
        // Consultar las actividades aprobadas en el mes y año seleccionados
            $actividades = ActividadVehiculo::with(['poa.operaciones', 'usuario.area', 'usuario.unidad', 'centroSalud.municipio'])
            ->where('estado_aprobacion', 'aprobado')
            ->whereMonth('fecha_inicio', $mes)
            ->whereYear('fecha_inicio', $year)
            ->get();
        // Formatear las fechas en el formato día-mes-año
        $actividades->map(function ($actividad) {
            $actividad->fecha_inicio = \Carbon\Carbon::parse($actividad->fecha_inicio)->format('d-m-Y');
            $actividad->fecha_fin = \Carbon\Carbon::parse($actividad->fecha_fin)->format('d-m-Y');
            return $actividad;
        });
        // Preparar los datos para el PDF
        $data = [
            'mes' => $mes,
            'year' => $year,
            'actividades' => $actividades
        ];
    
        // Generar el PDF con DomPDF
        $pdf = PDF::loadView('reports.reporte_actividad_conv', $data)
               ->setPaper('a4', 'landscape'); // Establecer tamaño A4 y orientación horizontal
        // Retornar el PDF
        //return $pdf->download('reporte_actividades_'.$mes.'_'.$year.'.pdf');
        return $pdf->stream('reporte_mensual.pdf');
    }

}
