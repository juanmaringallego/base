@extends('layouts.app')

@section('title', 'Ver Turno')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalles del Turno</h1>
        <div class="space-x-2">
            <a href="{{ route('appointments.edit', $appointment) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Editar
            </a>
            <a href="{{ route('appointments.index') }}" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">
                Volver
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-8 mb-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Usuario</h3>
                <p class="text-lg text-gray-900">{{ $appointment->user->name }}</p>
                <p class="text-sm text-gray-600">{{ $appointment->user->email }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Servicio</h3>
                <p class="text-lg text-gray-900">{{ $appointment->service->name }}</p>
                <p class="text-sm text-gray-600">
                    Duración: {{ $appointment->service->duration }} min |
                    Precio: ${{ number_format($appointment->service->price, 2) }}
                </p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Fecha y Hora</h3>
                <p class="text-lg text-gray-900">{{ $appointment->appointment_date->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-600">{{ $appointment->appointment_date->format('H:i') }} hs</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Estado</h3>
                <span class="px-3 py-1 text-sm rounded
                    @if($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($appointment->status === 'confirmed') bg-blue-100 text-blue-800
                    @elseif($appointment->status === 'completed') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($appointment->status) }}
                </span>
            </div>

            @if($appointment->notes)
            <div class="col-span-2">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Notas</h3>
                <p class="text-gray-900">{{ $appointment->notes }}</p>
            </div>
            @endif

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Creado</h3>
                <p class="text-sm text-gray-900">{{ $appointment->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Última Actualización</h3>
                <p class="text-sm text-gray-900">{{ $appointment->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    @if($appointment->status !== 'cancelled' && $appointment->status !== 'completed')
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Cambiar Estado</h2>
        <form action="{{ route('appointments.updateStatus', $appointment) }}" method="POST" class="flex gap-3">
            @csrf
            @method('PATCH')
            <select name="status" class="flex-1 px-4 py-2 border border-gray-300 rounded">
                <option value="pending" {{ $appointment->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                <option value="cancelled" {{ $appointment->status === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                <option value="completed" {{ $appointment->status === 'completed' ? 'selected' : '' }}>Completado</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Actualizar Estado
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
