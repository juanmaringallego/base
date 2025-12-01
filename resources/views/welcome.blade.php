@extends('layouts.app')

@section('title', 'Inicio - Sistema de Turnos')

@section('content')
<div class="text-center mb-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-4">Bienvenido al Sistema de Gestión de Turnos</h1>
    <p class="text-xl text-gray-600">Una aplicación demo completa hecha con Laravel</p>
</div>

<div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
    {{-- Card de Servicios --}}
    <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition-shadow">
        <div class="text-blue-600 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-3">Servicios</h2>
        <p class="text-gray-600 mb-6">Gestiona los servicios disponibles para reservar turnos. Configura nombre, descripción, duración y precio.</p>
        <div class="flex gap-3">
            <a href="{{ route('services.index') }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors text-center">
                Ver Servicios
            </a>
            <a href="{{ route('services.create') }}" class="flex-1 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors text-center">
                Crear Servicio
            </a>
        </div>
    </div>

    {{-- Card de Turnos --}}
    <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition-shadow">
        <div class="text-green-600 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-3">Turnos</h2>
        <p class="text-gray-600 mb-6">Administra las citas y turnos reservados. Cambia estados, edita detalles y gestiona la agenda completa.</p>
        <div class="flex gap-3">
            <a href="{{ route('appointments.index') }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors text-center">
                Ver Turnos
            </a>
            <a href="{{ route('appointments.create') }}" class="flex-1 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors text-center">
                Crear Turno
            </a>
        </div>
    </div>
</div>

{{-- Características --}}
<div class="mt-16 bg-white rounded-lg shadow-lg p-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Características de esta Demo</h2>
    <div class="grid md:grid-cols-3 gap-6">
        <div class="text-center">
            <div class="text-blue-600 mb-3">
                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                </svg>
            </div>
            <h3 class="font-bold text-lg mb-2">Eloquent ORM</h3>
            <p class="text-gray-600">Modelos con relaciones, scopes y casting de atributos</p>
        </div>
        <div class="text-center">
            <div class="text-green-600 mb-3">
                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="font-bold text-lg mb-2">Blade Templates</h3>
            <p class="text-gray-600">Sistema de vistas con layouts, componentes y directivas</p>
        </div>
        <div class="text-center">
            <div class="text-purple-600 mb-3">
                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                </svg>
            </div>
            <h3 class="font-bold text-lg mb-2">Controllers CRUD</h3>
            <p class="text-gray-600">Controladores de recursos con validación y lógica de negocio</p>
        </div>
    </div>
</div>
@endsection
