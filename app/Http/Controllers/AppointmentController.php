<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * AppointmentController - Controlador para gestionar turnos/citas
 *
 * Este controlador maneja todas las operaciones CRUD para los turnos del sistema.
 * Permite crear, ver, editar, actualizar y eliminar citas, así como cambiar su estado.
 *
 * Métodos principales:
 * - index: Lista todos los turnos con filtros
 * - create: Muestra formulario para crear un turno
 * - store: Guarda un nuevo turno
 * - show: Muestra detalles de un turno
 * - edit: Muestra formulario para editar un turno
 * - update: Actualiza un turno existente
 * - destroy: Elimina un turno
 * - updateStatus: Actualiza el estado de un turno
 */
class AppointmentController extends Controller
{
    /**
     * Muestra una lista de todos los turnos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Query base con relaciones
        $query = Appointment::with(['user', 'service'])
            ->orderBy('appointment_date', 'desc');

        // Filtro por estado si se proporciona
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Paginación
        $appointments = $query->paginate(15);

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Muestra el formulario para crear un nuevo turno
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Obtiene servicios activos y usuarios para los selects del formulario
        $services = Service::where('is_active', true)->get();
        $users = User::orderBy('name')->get();

        return view('appointments.create', compact('services', 'users'));
    }

    /**
     * Almacena un nuevo turno en la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validación de datos
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        // Establece el estado inicial como 'pending'
        $validated['status'] = 'pending';

        // Crea el turno
        Appointment::create($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Turno creado exitosamente.');
    }

    /**
     * Muestra los detalles de un turno específico
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\View\View
     */
    public function show(Appointment $appointment)
    {
        // Carga las relaciones
        $appointment->load(['user', 'service']);

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Muestra el formulario para editar un turno
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\View\View
     */
    public function edit(Appointment $appointment)
    {
        $services = Service::where('is_active', true)->get();
        $users = User::orderBy('name')->get();

        return view('appointments.edit', compact('appointment', 'services', 'users'));
    }

    /**
     * Actualiza un turno existente
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Validación de datos
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'notes' => 'nullable|string',
        ]);

        // Actualiza el turno
        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Turno actualizado exitosamente.');
    }

    /**
     * Elimina un turno
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Turno eliminado exitosamente.');
    }

    /**
     * Actualiza el estado de un turno
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $appointment->update($validated);

        return back()->with('success', 'Estado del turno actualizado.');
    }
}
