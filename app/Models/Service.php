<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Service - Representa los servicios disponibles para reservar turnos
 *
 * Este modelo gestiona los diferentes servicios que ofrece el negocio.
 * Cada servicio tiene un nombre, descripción, duración y precio.
 * Un servicio puede tener múltiples turnos/citas (appointments) asociados.
 */
class Service extends Model
{
    /**
     * Atributos que pueden ser asignados masivamente
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
        'duration',
        'price',
        'is_active',
    ];

    /**
     * Casting de atributos a tipos específicos
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relación: Un servicio puede tener muchos turnos/citas
     *
     * @return HasMany
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
