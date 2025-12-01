<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Appointment - Representa los turnos/citas reservados
 *
 * Este modelo gestiona las citas o turnos reservados por los usuarios.
 * Cada turno está asociado a un usuario y a un servicio específico.
 * Tiene un estado que puede ser: pending, confirmed, cancelled o completed.
 */
class Appointment extends Model
{
    /**
     * Atributos que pueden ser asignados masivamente
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'service_id',
        'appointment_date',
        'status',
        'notes',
    ];

    /**
     * Casting de atributos a tipos específicos
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'appointment_date' => 'datetime',
        ];
    }

    /**
     * Relación: Un turno pertenece a un usuario
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un turno pertenece a un servicio
     *
     * @return BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Scope: Filtrar turnos por estado
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Obtener turnos pendientes
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
