@extends('layouts.app')

@section('title', 'Turnos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Gestión de Turnos</h1>
    <a href="{{ route('appointments.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
        Nuevo Turno
    </a>
</div>

<div class="mb-6 bg-white rounded-lg shadow p-4">
    <form method="GET" action="{{ route('appointments.index') }}" class="flex gap-4 items-end">
        <div class="flex-1">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Filtrar por estado</label>
            <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded">
                <option value="">Todos</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completado</option>
            </select>
        </div>
        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Filtrar</button>
        <a href="{{ route('appointments.index') }}" class="px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Limpiar</a>
    </form>
</div>

@if($appointments->isEmpty())
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-600">No hay turnos registrados</p>
        <a href="{{ route('appointments.create') }}" class="inline-block mt-4 text-blue-600 hover:underline">
            Crear el primer turno
        </a>
    </div>
@else
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($appointments as $appointment)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $appointment->user->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $appointment->service->name }}</td>
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
                    <td class="px-6 py-4 text-sm space-x-2">
                        <a href="{{ route('appointments.show', $appointment) }}" class="text-blue-600 hover:underline">Ver</a>
                        <a href="{{ route('appointments.edit', $appointment) }}" class="text-green-600 hover:underline">Editar</a>
                        <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este turno?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $appointments->links() }}
    </div>
@endif
@endsection
