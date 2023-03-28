<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    use HasFactory;

    const MAX_PER_DAY = 3; // 3 reminders per day per user

    protected $fillable = [
        'user_id',
        'reminder_text',
        'reminder_day',
        'reminder_hour',
        'reminder_timezone_date',
        'reminder_timezone',
        'notified_at',
    ];

    // Estos atributos se agregaran al modelo de forma dinamica.
    // No se guardaran en la BD. Ni siquiera se crearan las columnas en la BD
    protected $appends = [
        'reminder_hour_formatted',
        'date_formatted',
    ];

    // Esto es para que la columna reminder_day se guarde como un objeto Carbon
    protected $dates = [
        'reminder_day',
    ];

    // Este metodo se ejecuta cada vez que el modelo es instanciado
    protected static function boot()
    {
        parent::boot();

        // En el momento que se este creando el registro Reminder
        // Ejecutaremos una funcion, que recibe por parametro el objeto Reminder qeu se esta creando
        self::creating(function (Reminder $reminder) {
            // Si la aplicacion no esta corriendo en la consola
            if (! app()->runningInConsole()) {
                // Entonces el usuario que esta creando el recordatorio es el usuario autenticado
                $reminder->user_id = auth()->id();
            }
        });

        // Agregamos una consulta de alcance global
        // Cada vez que se haga una consulta a BD del modelo Reminder,
        // Entonces siempre se aplicara este filtro.
        // Espercificamente se filtrara por el usuario autenticado
        static::addGlobalScope('forCustomer', function (Builder $builder) {
                $builder->where('user_id', auth()->id());
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessor para el atributo reminder_hour_formatted
    public function getReminderHourFormattedAttribute(): ?string
    {
        if (! $this->reminder_hour) return null;

        $parts = explode(':', $this->reminder_hour);
        if (count($parts) !== 2) return null;
        return sprintf('%s:%s', $parts[0], $parts[1]);
    }

    // Accessor para el atributo date_formatted
    public function getDateFormattedAttribute(): ?string
    {
        if (! $this->reminder_day) return null;
        return sprintf('%s %s', $this->reminder_day?->format("d-m-Y"), $this->reminder_hour_formatted);
    }

    // Verificar si el recordatorio puede ser eliminado
    public function canDelete() : bool
    {
        return ! $this->notified_at && (
            $this->reminder_day->format("Y-m-d") . " " . $this->reminder_hour > now()->format('Y-m-d H:i')
        );
    }

}
