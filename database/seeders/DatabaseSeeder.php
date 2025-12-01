<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder - Seeder principal
 *
 * Este seeder ejecuta todos los demás seeders en el orden correcto
 * para poblar la base de datos con datos de ejemplo.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuarios de ejemplo
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@ejemplo.com',
        ]);

        User::factory()->create([
            'name' => 'Juan Pérez',
            'email' => 'juan@ejemplo.com',
        ]);

        User::factory()->create([
            'name' => 'María García',
            'email' => 'maria@ejemplo.com',
        ]);

        User::factory()->create([
            'name' => 'Carlos López',
            'email' => 'carlos@ejemplo.com',
        ]);

        // Crear más usuarios aleatorios
        User::factory(6)->create();

        // Ejecutar seeders en orden
        $this->call([
            ServiceSeeder::class,
            AppointmentSeeder::class,
        ]);
    }
}
