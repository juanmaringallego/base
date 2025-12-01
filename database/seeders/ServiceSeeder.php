<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * ServiceSeeder - Genera datos de ejemplo para servicios
 *
 * Este seeder crea servicios de muestra para demostrar
 * las capacidades del sistema de turnos.
 */
class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Corte de Cabello',
                'description' => 'Corte de cabello profesional con lavado incluido',
                'duration' => 30,
                'price' => 25.00,
                'is_active' => true,
            ],
            [
                'name' => 'Coloración',
                'description' => 'Tinte completo con productos de alta calidad',
                'duration' => 120,
                'price' => 80.00,
                'is_active' => true,
            ],
            [
                'name' => 'Manicura',
                'description' => 'Tratamiento completo de uñas de manos',
                'duration' => 45,
                'price' => 20.00,
                'is_active' => true,
            ],
            [
                'name' => 'Pedicura',
                'description' => 'Tratamiento completo de uñas de pies',
                'duration' => 60,
                'price' => 30.00,
                'is_active' => true,
            ],
            [
                'name' => 'Masaje Relajante',
                'description' => 'Masaje corporal de relajación y bienestar',
                'duration' => 60,
                'price' => 50.00,
                'is_active' => true,
            ],
            [
                'name' => 'Tratamiento Facial',
                'description' => 'Limpieza profunda y tratamiento facial',
                'duration' => 90,
                'price' => 65.00,
                'is_active' => true,
            ],
            [
                'name' => 'Depilación',
                'description' => 'Servicio de depilación con cera',
                'duration' => 45,
                'price' => 35.00,
                'is_active' => true,
            ],
            [
                'name' => 'Peinado para Eventos',
                'description' => 'Peinado profesional para eventos especiales',
                'duration' => 90,
                'price' => 70.00,
                'is_active' => false, // Este servicio está desactivado como ejemplo
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
