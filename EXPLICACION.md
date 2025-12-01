# Sistema de Gestión de Turnos - Laravel Demo

Esta es una aplicación completa desarrollada en Laravel que demuestra las capacidades del framework para crear un sistema de gestión de turnos/citas.

## 📋 Tabla de Contenidos

- [Características](#características)
- [Arquitectura del Proyecto](#arquitectura-del-proyecto)
- [Explicación Detallada de Componentes](#explicación-detallada-de-componentes)
- [Instalación y Configuración](#instalación-y-configuración)
- [Uso de la Aplicación](#uso-de-la-aplicación)

---

## 🎯 Características

- **CRUD completo de Servicios**: Crear, leer, actualizar y eliminar servicios
- **CRUD completo de Turnos**: Gestión de citas con diferentes estados
- **Relaciones Eloquent**: Modelos con relaciones entre usuarios, servicios y turnos
- **Validación de datos**: Validación en el servidor de todos los formularios
- **Interfaz responsive**: Diseño moderno con Tailwind CSS
- **Datos de ejemplo**: Seeders con información de prueba
- **Estados de turnos**: pending, confirmed, cancelled, completed
- **Filtros**: Búsqueda y filtrado de turnos por estado

---

## 🏗️ Arquitectura del Proyecto

### Patrón MVC (Model-View-Controller)

Laravel utiliza el patrón MVC que separa la lógica de negocio, la presentación y el control:

```
app/
├── Models/              # Modelos (M)
│   ├── User.php
│   ├── Service.php
│   └── Appointment.php
│
├── Http/Controllers/    # Controladores (C)
│   ├── ServiceController.php
│   └── AppointmentController.php
│
resources/views/        # Vistas (V)
├── layouts/
│   └── app.blade.php
├── services/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
└── appointments/
    ├── index.blade.php
    ├── create.blade.php
    ├── edit.blade.php
    └── show.blade.php
```

---

## 📚 Explicación Detallada de Componentes

### 1. Migraciones (Database Migrations)

**Ubicación**: `database/migrations/`

Las migraciones son como un "control de versiones" para tu base de datos. Permiten definir y modificar la estructura de las tablas de forma programática.

#### `create_services_table.php`
```php
Schema::create('services', function (Blueprint $table) {
    $table->id();                          // ID auto-incremental
    $table->string('name');                // Nombre del servicio
    $table->text('description')->nullable(); // Descripción (opcional)
    $table->integer('duration');           // Duración en minutos
    $table->decimal('price', 10, 2);      // Precio (10 dígitos, 2 decimales)
    $table->boolean('is_active')->default(true); // Estado activo/inactivo
    $table->timestamps();                  // created_at y updated_at
});
```

**¿Qué hace cada campo?**
- `id()`: Crea un campo auto-incremental que sirve como identificador único
- `string()`: Campo de texto corto (hasta 255 caracteres)
- `text()`: Campo de texto largo
- `integer()`: Número entero
- `decimal(10, 2)`: Número decimal con precisión definida
- `boolean()`: Verdadero o falso
- `timestamps()`: Agrega automáticamente campos de fecha de creación y actualización

#### `create_appointments_table.php`
```php
Schema::create('appointments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')
          ->constrained()
          ->onDelete('cascade');           // Relación con users
    $table->foreignId('service_id')
          ->constrained()
          ->onDelete('cascade');           // Relación con services
    $table->dateTime('appointment_date');  // Fecha y hora del turno
    $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])
          ->default('pending');            // Estado del turno
    $table->text('notes')->nullable();     // Notas adicionales
    $table->timestamps();
});
```

**Conceptos clave**:
- `foreignId()`: Crea una relación con otra tabla
- `constrained()`: Establece la restricción de clave foránea
- `onDelete('cascade')`: Si se elimina el usuario o servicio, se eliminan sus turnos
- `enum()`: Campo que solo puede tener valores específicos predefinidos

---

### 2. Modelos Eloquent (ORM)

**Ubicación**: `app/Models/`

Eloquent es el ORM (Object-Relational Mapping) de Laravel. Convierte tablas de base de datos en objetos PHP para trabajar de forma más intuitiva.

#### `Service.php` - Modelo de Servicio

```php
class Service extends Model
{
    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'name', 'description', 'duration', 'price', 'is_active'
    ];

    // Convierte automáticamente tipos de datos
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',      // Convierte a decimal con 2 decimales
            'is_active' => 'boolean',     // Convierte a true/false
        ];
    }

    // Relación: Un servicio tiene muchos turnos
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
```

**¿Qué significa cada parte?**

- **$fillable**: Lista de campos que se pueden asignar masivamente (seguridad contra asignación masiva)
- **casts()**: Convierte automáticamente los valores de la base de datos a tipos específicos de PHP
- **appointments()**: Define una relación "uno a muchos". Un servicio puede tener múltiples turnos.

#### `Appointment.php` - Modelo de Turno

```php
class Appointment extends Model
{
    protected $fillable = [
        'user_id', 'service_id', 'appointment_date', 'status', 'notes'
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'datetime',  // Convierte a objeto Carbon (DateTime)
        ];
    }

    // Relación: Un turno pertenece a un usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación: Un turno pertenece a un servicio
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // Scope: Método personalizado para filtrar por estado
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope: Obtener solo turnos pendientes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
```

**Conceptos importantes**:

- **BelongsTo**: Relación "pertenece a". Un turno pertenece a UN usuario y UN servicio.
- **HasMany**: Relación "tiene muchos" (inversa de BelongsTo).
- **Scopes**: Métodos reutilizables para consultas. Ejemplo: `Appointment::pending()->get()`
- **Carbon**: Librería de Laravel para manejar fechas y horas fácilmente

#### `User.php` - Modelo de Usuario

```php
class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password'];

    // Relación: Un usuario puede tener muchos turnos
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
```

---

### 3. Controladores (Controllers)

**Ubicación**: `app/Http/Controllers/`

Los controladores manejan la lógica de la aplicación y responden a las peticiones del usuario.

#### `ServiceController.php`

Este controlador implementa el patrón CRUD completo:

**index()** - Listar todos los servicios
```php
public function index()
{
    // Obtiene servicios ordenados por fecha de creación, paginados de 10 en 10
    $services = Service::orderBy('created_at', 'desc')->paginate(10);

    return view('services.index', compact('services'));
}
```

**create()** - Mostrar formulario de creación
```php
public function create()
{
    return view('services.create');
}
```

**store()** - Guardar nuevo servicio
```php
public function store(Request $request)
{
    // Validación de datos
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
```

**¿Qué hace la validación?**
- `required`: El campo es obligatorio
- `string`: Debe ser texto
- `max:255`: Máximo 255 caracteres
- `nullable`: El campo puede estar vacío
- `integer`: Debe ser un número entero
- `min:1`: Valor mínimo de 1
- `numeric`: Debe ser un número
- `min:0`: Valor mínimo de 0
- `boolean`: Debe ser verdadero o falso

**show()** - Mostrar detalles de un servicio
```php
public function show(Service $service)
{
    // Laravel busca automáticamente el servicio por ID (Route Model Binding)
    // Carga también los turnos asociados con sus usuarios
    $service->load('appointments.user');

    return view('services.show', compact('service'));
}
```

**edit()** - Mostrar formulario de edición
```php
public function edit(Service $service)
{
    return view('services.edit', compact('service'));
}
```

**update()** - Actualizar servicio existente
```php
public function update(Request $request, Service $service)
{
    $validated = $request->validate([...]);

    $service->update($validated);

    return redirect()->route('services.index')
        ->with('success', 'Servicio actualizado exitosamente.');
}
```

**destroy()** - Eliminar servicio
```php
public function destroy(Service $service)
{
    // Elimina el servicio (los turnos se eliminan en cascada)
    $service->delete();

    return redirect()->route('services.index')
        ->with('success', 'Servicio eliminado exitosamente.');
}
```

#### `AppointmentController.php`

Similar al ServiceController pero con funcionalidades adicionales:

**index()** - Lista con filtros
```php
public function index(Request $request)
{
    $query = Appointment::with(['user', 'service'])
        ->orderBy('appointment_date', 'desc');

    // Filtro opcional por estado
    if ($request->has('status') && $request->status !== '') {
        $query->where('status', $request->status);
    }

    $appointments = $query->paginate(15);

    return view('appointments.index', compact('appointments'));
}
```

**¿Qué es eager loading?**
- `with(['user', 'service'])`: Carga las relaciones de forma eficiente
- Sin esto, Laravel haría una consulta por cada turno para obtener el usuario y servicio (problema N+1)
- Con esto, hace solo 3 consultas: una para turnos, una para usuarios, una para servicios

**updateStatus()** - Método personalizado para cambiar estado
```php
public function updateStatus(Request $request, Appointment $appointment)
{
    $validated = $request->validate([
        'status' => 'required|in:pending,confirmed,cancelled,completed',
    ]);

    $appointment->update($validated);

    return back()->with('success', 'Estado del turno actualizado.');
}
```

---

### 4. Rutas (Routes)

**Ubicación**: `routes/web.php`

Las rutas definen cómo responde la aplicación a las peticiones HTTP.

```php
// Ruta principal
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas de recursos para servicios (genera 7 rutas automáticamente)
Route::resource('services', ServiceController::class);

// Rutas de recursos para turnos (genera 7 rutas automáticamente)
Route::resource('appointments', AppointmentController::class);

// Ruta adicional personalizada
Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
    ->name('appointments.updateStatus');
```

**¿Qué rutas genera Route::resource?**
```
GET     /services              -> index   (listar)
GET     /services/create       -> create  (formulario crear)
POST    /services              -> store   (guardar)
GET     /services/{id}         -> show    (ver detalles)
GET     /services/{id}/edit    -> edit    (formulario editar)
PUT     /services/{id}         -> update  (actualizar)
DELETE  /services/{id}         -> destroy (eliminar)
```

---

### 5. Vistas Blade (Views)

**Ubicación**: `resources/views/`

Blade es el motor de plantillas de Laravel que permite mezclar PHP con HTML de forma elegante.

#### Layout Base (`layouts/app.blade.php`)

```blade
<!DOCTYPE html>
<html lang="es">
<head>
    <title>@yield('title', 'Sistema de Turnos')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    {{-- Navbar --}}
    <nav class="bg-blue-600 text-white">
        <a href="{{ route('home') }}">Sistema de Turnos</a>
        <a href="{{ route('services.index') }}">Servicios</a>
        <a href="{{ route('appointments.index') }}">Turnos</a>
    </nav>

    {{-- Mensajes de éxito/error --}}
    @if(session('success'))
        <div class="bg-green-100">{{ session('success') }}</div>
    @endif

    {{-- Contenido principal --}}
    <main>
        @yield('content')
    </main>
</body>
</html>
```

**Directivas Blade**:
- `@yield('content')`: Marca dónde se insertará el contenido
- `{{ $variable }}`: Imprime una variable escapada (seguro contra XSS)
- `@if ... @endif`: Condicional
- `{{-- comentario --}}`: Comentario que no se renderiza
- `@foreach ... @endforeach`: Bucle

#### Vista Index (`services/index.blade.php`)

```blade
@extends('layouts.app')

@section('title', 'Servicios')

@section('content')
    <h1>Servicios</h1>

    @if($services->isEmpty())
        <p>No hay servicios registrados</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Duración</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                <tr>
                    <td>{{ $service->name }}</td>
                    <td>{{ $service->duration }} min</td>
                    <td>${{ number_format($service->price, 2) }}</td>
                    <td>
                        <a href="{{ route('services.show', $service) }}">Ver</a>
                        <a href="{{ route('services.edit', $service) }}">Editar</a>

                        <form action="{{ route('services.destroy', $service) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $services->links() }}  {{-- Paginación --}}
    @endif
@endsection
```

**Conceptos importantes**:
- `@extends`: Hereda de un layout
- `@section ... @endsection`: Define una sección
- `route('nombre')`: Genera la URL de una ruta nombrada
- `@csrf`: Token de seguridad contra CSRF (obligatorio en formularios)
- `@method('DELETE')`: Permite usar métodos HTTP como DELETE en formularios

#### Vista Create/Edit (Formularios)

```blade
<form action="{{ route('services.store') }}" method="POST">
    @csrf

    <label for="name">Nombre *</label>
    <input type="text" name="name" id="name" value="{{ old('name') }}">
    @error('name')
        <p class="text-red-500">{{ $message }}</p>
    @enderror

    <button type="submit">Crear Servicio</button>
</form>
```

**Directivas de formulario**:
- `@csrf`: Token de seguridad (OBLIGATORIO)
- `old('name')`: Mantiene el valor anterior si hay error de validación
- `@error('name')`: Muestra mensaje de error si la validación falló

---

### 6. Seeders (Datos de Prueba)

**Ubicación**: `database/seeders/`

Los seeders poblan la base de datos con datos de ejemplo.

#### `ServiceSeeder.php`

```php
public function run(): void
{
    $services = [
        [
            'name' => 'Corte de Cabello',
            'description' => 'Corte profesional',
            'duration' => 30,
            'price' => 25.00,
            'is_active' => true,
        ],
        // ... más servicios
    ];

    foreach ($services as $service) {
        Service::create($service);
    }
}
```

#### `AppointmentSeeder.php`

```php
public function run(): void
{
    $users = User::all();
    $services = Service::where('is_active', true)->get();

    $appointments = [
        [
            'user_id' => $users->random()->id,  // Usuario aleatorio
            'service_id' => $services->random()->id,  // Servicio aleatorio
            'appointment_date' => Carbon::now()->addDays(2)->setTime(11, 0),
            'status' => 'confirmed',
            'notes' => 'Primera vez del cliente',
        ],
        // ... más turnos
    ];

    foreach ($appointments as $appointment) {
        Appointment::create($appointment);
    }
}
```

---

## 🚀 Instalación y Configuración

### Requisitos Previos

- PHP >= 8.2
- Composer
- Extensión SQLite para PHP

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone <url-del-repositorio>
cd base
```

2. **Instalar dependencias**
```bash
composer install
```

3. **Configurar entorno**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Crear base de datos**
```bash
touch database/database.sqlite
```

5. **Ejecutar migraciones y seeders**
```bash
php artisan migrate
php artisan db:seed
```

6. **Iniciar servidor de desarrollo**
```bash
php artisan serve
```

7. **Acceder a la aplicación**
```
http://localhost:8000
```

---

## 📖 Uso de la Aplicación

### Gestión de Servicios

1. **Ver servicios**: Navega a "Servicios" en el menú
2. **Crear servicio**: Click en "Nuevo Servicio", completa el formulario
3. **Editar servicio**: Click en "Editar" en la lista de servicios
4. **Ver detalles**: Click en "Ver" para ver información completa y turnos asociados
5. **Eliminar servicio**: Click en "Eliminar" (se eliminarán también sus turnos)

### Gestión de Turnos

1. **Ver turnos**: Navega a "Turnos" en el menú
2. **Filtrar por estado**: Usa el selector de estado para filtrar
3. **Crear turno**: Click en "Nuevo Turno", selecciona usuario, servicio y fecha
4. **Cambiar estado**: En la vista de detalles, usa el formulario de cambio de estado
5. **Editar turno**: Click en "Editar" para modificar datos
6. **Eliminar turno**: Click en "Eliminar"

### Estados de Turnos

- **Pending (Pendiente)**: Turno solicitado pero no confirmado
- **Confirmed (Confirmado)**: Turno confirmado y agendado
- **Cancelled (Cancelado)**: Turno cancelado
- **Completed (Completado)**: Turno realizado

---

## 🎓 Conceptos Avanzados de Laravel

### 1. Eloquent ORM

**Ventajas**:
- Sintaxis expresiva y fácil de leer
- Relaciones intuitivas entre modelos
- Query Builder potente
- Eager loading para optimizar consultas

**Ejemplo de consulta**:
```php
// Obtener turnos confirmados del próximo mes con sus usuarios y servicios
$appointments = Appointment::with(['user', 'service'])
    ->where('status', 'confirmed')
    ->whereBetween('appointment_date', [
        now(),
        now()->addMonth()
    ])
    ->orderBy('appointment_date')
    ->get();
```

### 2. Validación

Laravel ofrece validación robusta del lado del servidor:

```php
$request->validate([
    'email' => 'required|email|unique:users',
    'age' => 'required|integer|min:18|max:100',
    'website' => 'nullable|url',
]);
```

### 3. Mass Assignment Protection

Protección contra asignación masiva no autorizada:

```php
// INCORRECTO - vulnerable
User::create($request->all());

// CORRECTO - solo campos permitidos en $fillable
User::create($request->validated());
```

### 4. Paginación

Laravel facilita la paginación:

```php
$services = Service::paginate(10);

// En la vista
{{ $services->links() }}  // Genera los links de paginación
```

### 5. Route Model Binding

Laravel busca automáticamente el modelo por ID:

```php
// En lugar de:
public function show($id) {
    $service = Service::findOrFail($id);
}

// Puedes hacer:
public function show(Service $service) {
    // Laravel automáticamente encuentra el servicio
}
```

---

## 📊 Diagrama de Flujo de Datos

```
Usuario hace petición
    ↓
Ruta (web.php) captura la petición
    ↓
Controlador procesa la lógica
    ↓
Modelo interactúa con la base de datos
    ↓
Controlador pasa datos a la vista
    ↓
Vista (Blade) renderiza HTML
    ↓
Respuesta enviada al usuario
```

---

## 🔐 Seguridad

La aplicación implementa:

1. **CSRF Protection**: Tokens en todos los formularios
2. **Mass Assignment Protection**: $fillable en modelos
3. **SQL Injection Prevention**: Eloquent usa prepared statements
4. **XSS Prevention**: Blade escapa automáticamente las variables
5. **Validation**: Validación de datos del lado del servidor

---

## 📝 Conclusión

Este sistema de turnos demuestra:

✅ Arquitectura MVC bien estructurada
✅ CRUD completo con Eloquent ORM
✅ Relaciones entre modelos (HasMany, BelongsTo)
✅ Validación de formularios
✅ Vistas con Blade templates
✅ Migraciones y seeders
✅ Rutas RESTful
✅ Buenas prácticas de Laravel

**Alcance del Sistema:**
- Gestión completa de servicios
- Gestión completa de turnos
- Relaciones entre usuarios, servicios y turnos
- Filtrado y búsqueda
- Cambio de estados
- Interfaz responsive
- Datos de ejemplo

Este proyecto puede servir como base para:
- Sistemas de reservas
- Agendas médicas
- Sistemas de citas
- Plataformas de servicios
- Y muchas otras aplicaciones similares

---

**Desarrollado con Laravel 12** - Framework PHP moderno y elegante
