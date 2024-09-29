<?php

use App\Http\Controllers\Api\V1\AreaController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CentroSaludController;
use App\Http\Controllers\Api\V1\CoordinacionController;
use App\Http\Controllers\Api\V1\MunicipioController;
use App\Http\Controllers\Api\V1\PoaController;
use App\Http\Controllers\Api\V1\UnidadController;
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
    });
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
    
    //COORDINACIONES
    Route::apiResource('coordinaciones', CoordinacionController::class);

    //MUNICIPIOS
    Route::apiResource('municipios', MunicipioController::class);

    //CENTRO DE SALUD
    Route::apiResource('centros_salud', CentroSaludController::class);
    Route::get('municipios/coordinacion/{id}', [CentroSaludController::class, 'getMunicipiosByCoordinacion']);
    /* Route::apiResource('centros_salud', CentroSaludController::class);
    Route::get('municipios/{coordinacion_id}', function ($coordinacion_id) {
        return Municipio::where('coordinacion_id', $coordinacion_id)->get();
    }); */
});

