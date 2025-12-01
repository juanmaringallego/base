<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

/**
 * Ruta principal - Dashboard
 * Muestra la página de inicio del sistema de turnos
 */
Route::get('/', function () {
    return view('welcome');
})->name('home');

/**
 * Rutas de recursos para Servicios
 *
 * Genera automáticamente las siguientes rutas:
 * GET    /services              -> index   (listar servicios)
 * GET    /services/create       -> create  (formulario crear servicio)
 * POST   /services              -> store   (guardar servicio)
 * GET    /services/{service}    -> show    (ver servicio)
 * GET    /services/{service}/edit -> edit  (formulario editar servicio)
 * PUT    /services/{service}    -> update  (actualizar servicio)
 * DELETE /services/{service}    -> destroy (eliminar servicio)
 */
Route::resource('services', ServiceController::class);

/**
 * Rutas de recursos para Turnos/Citas
 *
 * Genera automáticamente las siguientes rutas:
 * GET    /appointments                    -> index   (listar turnos)
 * GET    /appointments/create             -> create  (formulario crear turno)
 * POST   /appointments                    -> store   (guardar turno)
 * GET    /appointments/{appointment}      -> show    (ver turno)
 * GET    /appointments/{appointment}/edit -> edit    (formulario editar turno)
 * PUT    /appointments/{appointment}      -> update  (actualizar turno)
 * DELETE /appointments/{appointment}      -> destroy (eliminar turno)
 */
Route::resource('appointments', AppointmentController::class);

/**
 * Ruta adicional para actualizar el estado de un turno
 * PATCH /appointments/{appointment}/status
 */
Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
    ->name('appointments.updateStatus');
