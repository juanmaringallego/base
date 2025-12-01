@extends('layouts.app')

@section('title', 'Crear Turno')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Crear Nuevo Turno</h1>

    <div class="bg-white rounded-lg shadow p-8">
        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Usuario *</label>
                <select name="user_id" id="user_id" class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 @error('user_id') border-red-500 @enderror">
                    <option value="">Seleccione un usuario</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">Servicio *</label>
                <select name="service_id" id="service_id" class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 @error('service_id') border-red-500 @enderror">
                    <option value="">Seleccione un servicio</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }} - ${{ number_format($service->price, 2) }} ({{ $service->duration }} min)
                        </option>
                    @endforeach
                </select>
                @error('service_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha y Hora *</label>
                <input type="datetime-local" name="appointment_date" id="appointment_date" value="{{ old('appointment_date') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 @error('appointment_date') border-red-500 @enderror">
                @error('appointment_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notas</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('appointments.index') }}" class="px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Crear Turno
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
