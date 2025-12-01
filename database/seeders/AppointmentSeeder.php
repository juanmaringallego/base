<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

/**
 * AppointmentSeeder - Genera datos de ejemplo para turnos
 *
 * Este seeder crea turnos de muestra con diferentes estados
 * para demostrar las capacidades del sistema.
 */
class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtenemos usuarios y servicios existentes
        $users = User::all();
        $services = Service::where('is_active', true)->get();

        // Si no hay usuarios o servicios, no podemos crear turnos
        if ($users->isEmpty() || $services->isEmpty()) {
            $this->command->warn('No hay usuarios o servicios disponibles para crear turnos.');
            return;
        }

        // Creamos turnos de ejemplo con diferentes estados y fechas
        $appointments = [
            // Turnos pasados completados
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->id,
                'appointment_date' => Carbon::now()->subDays(5)->setTime(10, 0),
                'status' => 'completed',
                'notes' => 'Cliente satisfecho con el servicio',
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->id,
                'appointment_date' => Carbon::now()->subDays(3)->setTime(14, 30),
                'status' => 'completed',
                'notes' => null,
            ],

            // Turnos pasados cancelados
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->id,
                'appointment_date' => Carbon::now()->subDays(2)->setTime(16, 0),
                'status' => 'cancelled',
                'notes' => 'Cliente canceló por motivos personales',
            ],

            // Turnos confirmados para el futuro
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->id,
                'appointment_date' => Carbon::now()->addDays(2)->setTime(11, 0),
                'status' => 'confirmed',
                'notes' => 'Primera vez del cliente',
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->id,
                'appointment_date' => Carbon::now()->addDays(3)->setTime(15, 30),
                'status' => 'confirmed',
                'notes' => null,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->id,
                'appointment_date' => Carbon::now()->addDays(4)->setTime(10, 30),
                'status' => 'confirmed',
                'notes' => 'Cliente habitual',
            ],

            // Turnos pendientes
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->id,
                'appointment_date' => Carbon::now()->addDays(5)->setTime(13, 0),
                'status' => 'pending',
                'notes' => 'Espera confirmación',
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->id,
                'appointment_date' => Carbon::now()->addDays(7)->setTime(16, 0),
                'status' => 'pending',
                'notes' => null,
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->id,
                'appointment_date' => Carbon::now()->addDays(10)->setTime(9, 0),
                'status' => 'pending',
                'notes' => 'Solicitud de turno matutino',
            ],
            [
                'user_id' => $users->random()->id,
                'service_id' => $services->random()->id,
                'appointment_date' => Carbon::now()->addDays(14)->setTime(17, 30),
                'status' => 'pending',
                'notes' => null,
            ],
        ];

        foreach ($appointments as $appointment) {
            Appointment::create($appointment);
        }
    }
}
