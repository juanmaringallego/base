<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

/**
 * ServiceController - Controlador para gestionar servicios
 *
 * Este controlador maneja todas las operaciones CRUD (Create, Read, Update, Delete)
 * para los servicios disponibles en el sistema de turnos.
 *
 * Métodos principales:
 * - index: Lista todos los servicios
 * - create: Muestra formulario para crear un servicio
 * - store: Guarda un nuevo servicio en la base de datos
 * - show: Muestra detalles de un servicio específico
 * - edit: Muestra formulario para editar un servicio
 * - update: Actualiza un servicio existente
 * - destroy: Elimina un servicio
 */
class ServiceController extends Controller
{
    /**
     * Muestra una lista de todos los servicios
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtiene todos los servicios con paginación de 10 por página
        $services = Service::orderBy('created_at', 'desc')->paginate(10);

        return view('services.index', compact('services'));
    }

    /**
     * Muestra el formulario para crear un nuevo servicio
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Almacena un nuevo servicio en la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validación de datos recibidos del formulario
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        // Crea el servicio en la base de datos
        Service::create($validated);

        // Redirige con mensaje de éxito
        return redirect()->route('services.index')
            ->with('success', 'Servicio creado exitosamente.');
    }

    /**
     * Muestra los detalles de un servicio específico
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\View\View
     */
    public function show(Service $service)
    {
        // Carga los turnos asociados al servicio
        $service->load('appointments.user');

        return view('services.show', compact('service'));
    }

    /**
     * Muestra el formulario para editar un servicio
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\View\View
     */
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Actualiza un servicio existente en la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Service $service)
    {
        // Validación de datos recibidos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        // Actualiza el servicio
        $service->update($validated);

        return redirect()->route('services.index')
            ->with('success', 'Servicio actualizado exitosamente.');
    }

    /**
     * Elimina un servicio de la base de datos
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Service $service)
    {
        // Elimina el servicio (los turnos asociados se eliminan en cascada)
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Servicio eliminado exitosamente.');
    }
}
