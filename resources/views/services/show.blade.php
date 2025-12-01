@extends('layouts.app')

@section('title', 'Ver Servicio')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalles del Servicio</h1>
        <div class="space-x-2">
            <a href="{{ route('services.edit', $service) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Editar
            </a>
            <a href="{{ route('services.index') }}" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">
                Volver
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-8 mb-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Nombre</h3>
                <p class="text-lg text-gray-900">{{ $service->name }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Estado</h3>
                @if($service->is_active)
                    <span class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded">Activo</span>
                @else
                    <span class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded">Inactivo</span>
                @endif
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Duración</h3>
                <p class="text-lg text-gray-900">{{ $service->duration }} minutos</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Precio</h3>
                <p class="text-lg text-gray-900">${{ number_format($service->price, 2) }}</p>
            </div>

            <div class="col-span-2">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Descripción</h3>
                <p class="text-gray-900">{{ $service->description ?: 'Sin descripción' }}</p>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-bold text-gray-800 mb-4">Turnos Asociados ({{ $service->appointments->count() }})</h2>

    @if($service->appointments->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-600">No hay turnos asociados a este servicio</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($service->appointments as $appointment)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $appointment->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $appointment->appointment_date->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded
                                @if($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($appointment->status === 'confirmed') bg-blue-100 text-blue-800
                                @elseif($appointment->status === 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
