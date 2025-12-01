@extends('layouts.app')

@section('title', 'Crear Servicio')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Crear Nuevo Servicio</h1>

    <div class="bg-white rounded-lg shadow p-8">
        <form action="{{ route('services.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duración (minutos) *</label>
                    <input type="number" name="duration" id="duration" value="{{ old('duration') }}" min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('duration') border-red-500 @enderror">
                    @error('duration')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Precio *</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror">
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Servicio activo</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('services.index') }}" class="px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Crear Servicio
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
