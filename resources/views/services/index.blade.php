@extends('layouts.app')

@section('title', 'Servicios')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Servicios</h1>
    <a href="{{ route('services.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
        Nuevo Servicio
    </a>
</div>

@if($services->isEmpty())
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-600">No hay servicios registrados</p>
        <a href="{{ route('services.create') }}" class="inline-block mt-4 text-blue-600 hover:underline">
            Crear el primer servicio
        </a>
    </div>
@else
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duración</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($services as $service)
                <tr>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $service->name }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($service->description, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $service->duration }} min</td>
                    <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($service->price, 2) }}</td>
                    <td class="px-6 py-4">
                        @if($service->is_active)
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Activo</span>
                        @else
                            <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Inactivo</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <a href="{{ route('services.show', $service) }}" class="text-blue-600 hover:underline">Ver</a>
                        <a href="{{ route('services.edit', $service) }}" class="text-green-600 hover:underline">Editar</a>
                        <form action="{{ route('services.destroy', $service) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este servicio?')">
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
        {{ $services->links() }}
    </div>
@endif
@endsection
