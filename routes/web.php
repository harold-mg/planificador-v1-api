<?php

use App\Http\Controllers\Api\V1\ActividadVehiculoController;
use App\Http\Controllers\reportes\ReporteConVehiculo;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
}); */
//Route::get('/reporte-mensual/{mes}', [ActividadVehiculoController::class, 'generarReporteMensual'])->name('reporte.mensual');
Route::get('/reporte-mensual-con-vehiculo/{mes}', [ReporteConVehiculo::class, 'generarReporteMensual'])->name('reporte.mensual');
