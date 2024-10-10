<?php

use App\Http\Controllers\Api\V1\ActividadVehiculoController;
use App\Http\Controllers\Api\V1\AreaController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CentroSaludController;
use App\Http\Controllers\Api\V1\CoordinacionController;
use App\Http\Controllers\Api\V1\MunicipioController;
use App\Http\Controllers\Api\V1\OperacionController;
use App\Http\Controllers\Api\V1\PoaController;
use App\Http\Controllers\Api\V1\UnidadController;
use App\Http\Controllers\Api\V1\VehiculoController;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
// Rutas protegidas por autenticación Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);
    
    // Solo los planificadores pueden acceder a estas rutas
    Route::middleware('check.planificador')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        //UNIDADES
        Route::post('/unidades', [UnidadController::class, 'store']);
        Route::get('/unidades', [UnidadController::class, 'index']);
        Route::get('/unidades/{id}', [UnidadController::class, 'show']);
    
        //AREAS
        Route::post('/areas', [AreaController::class, 'store']);
        Route::get('/areas', [AreaController::class, 'index']);
        Route::get('/areas/{id}', [AreaController::class, 'show']);
    
        Route::get('/unidades/{unidad}/areas', [AreaController::class, 'getAreasPorUnidad']);
    
        //POAS
        Route::apiResource('poas', PoaController::class);
        Route::get('unidades/{unidad_id}/areas', [UnidadController::class, 'getAreasByUnidad']);
        //Route::get('/poas/{codigo_poa}/operaciones', [PoaController::class, 'getOperacionesByCodigo']);    
        
        //OPERACIONES
        Route::apiResource('operaciones', OperacionController::class);
        Route::post('/operaciones', [OperacionController::class, 'store']);
    
        //COORDINACIONES
        Route::apiResource('coordinaciones', CoordinacionController::class);
    
        //MUNICIPIOS
        Route::apiResource('municipios', MunicipioController::class);
    
        //CENTRO DE SALUD
        Route::apiResource('centros_salud', CentroSaludController::class);
        Route::get('municipios/coordinacion/{id}', [CentroSaludController::class, 'getMunicipiosByCoordinacion']);
        Route::get('/coordinaciones/{coordinacionId}/municipios', [MunicipioController::class, 'getMunicipiosByCoordinacion']);
        Route::get('/municipios/{municipioId}/centros_salud', [CentroSaludController::class, 'getCentrosSaludByMunicipio']);
        
        //VEHICULO
        Route::apiResource('vehiculos', VehiculoController::class);
        Route::get('vehiculos/disponibles', [VehiculoController::class, 'disponibles']);
        
    });
    //ACTIVIDAD VEHICULO
    // Actividades de vehículos - CRUD básico
    Route::apiResource('actividad_vehiculos', ActividadVehiculoController::class);
});
    //ACTIVIDAD VEHICULO
    //Route::middleware('auth:api')->post('/actividad_vehiculos', [ActividadVehiculoController::class, 'store']);
    // Actividades de vehículos - CRUD básico
    Route::apiResource('actividad_vehiculos', ActividadVehiculoController::class);
// Ruta para que el planificador apruebe/rechace una actividad
Route::post('/actividad_vehiculos/{id}/aprobar', [ActividadVehiculoController::class, 'aprobarActividad']);
Route::get('municipios/coordinacion/{id}', [CentroSaludController::class, 'getMunicipiosByCoordinacion']);
Route::get('/coordinaciones/{coordinacionId}/municipios', [MunicipioController::class, 'getMunicipiosByCoordinacion']);
Route::get('/municipios/{municipioId}/centros_salud', [CentroSaludController::class, 'getCentrosSaludByMunicipio']);
Route::get('vehiculos/disponibles', [VehiculoController::class, 'getVehiculosDisponibles']);
Route::get('vehiculos/disponibles', [VehiculoController::class, 'disponibles']);

//CENTRO DE SALUD
Route::apiResource('centros_salud', CentroSaludController::class);
Route::get('municipios/coordinacion/{id}', [CentroSaludController::class, 'getMunicipiosByCoordinacion']);
Route::get('/coordinaciones/{coordinacionId}/municipios', [MunicipioController::class, 'getMunicipiosByCoordinacion']);
Route::get('municipio/{municipioId}', [CentroSaludController::class, 'getCentrosSaludByMunicipio']);

//COORDINACIONES
//Route::apiResource('coordinaciones', CoordinacionController::class);
Route::get('/coordinaciones', [CoordinacionController::class, 'index']);
//MUNICIPIOS
Route::apiResource('municipios', MunicipioController::class);

//POAS
//Route::apiResource('poas', PoaController::class);
Route::get('unidades/{unidad_id}/areas', [UnidadController::class, 'getAreasByUnidad']);
Route::get('/poas/{codigo_poa}/operaciones', [PoaController::class, 'getOperacionesByCodigo']);    
Route::get('/poas/{codigo_poa}/operaciones', [PoaController::class, 'getOperaciones']);


//OPERACIONES
Route::apiResource('operaciones', OperacionController::class);
Route::post('/operaciones', [OperacionController::class, 'store']);

Route::middleware('auth:sanctum')->group(function() {
    // Ruta para obtener los POAs filtrados por el área o unidad del usuario autenticado
    Route::get('/poas', [PoaController::class, 'getPoas']);
});