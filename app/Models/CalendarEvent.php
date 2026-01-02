<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CalendarEvent extends Model
{
    use HasFactory;

    protected $table = 'calendar_events';

    protected $fillable = [
        'title',
        'start',
        'end',
        'all_day',
        'color',
        'op_number',
        'cliente',
        'cantidad_req',
        'fecha_entrega',
        'fecha_produccion',
        'estado',
        'codigos'
    ];

    protected $casts = [
        'all_day' => 'boolean',
        'start' => 'datetime',
        'end' => 'datetime',
        'fecha_entrega' => 'date',
        'fecha_produccion' => 'date',
        'cantidad_req' => 'integer',
        'codigos' => 'array' // Cast automático para JSON
    ];

    /**
     * Convertir a formato para FullCalendar
     */
    public function toEventArray()
    {
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start' => $this->start,
            'end' => $this->end,
            'allDay' => $this->all_day,
            'color' => $this->color,
            'extendedProps' => [
                'op_number' => $this->op_number,
                'cliente' => $this->cliente,
                'cantidadReq' => $this->cantidad_req,
                'fechaEntrega' => $this->fecha_entrega ? $this->fecha_entrega->format('Y-m-d') : null,
                'fechaProduccion' => $this->fecha_produccion ? $this->fecha_produccion->format('Y-m-d') : null,
                'estado' => $this->estado,
                'codigos' => $this->codigos ?: []
            ]
        ];
    }

    /**
     * Buscar por número de OP
     */
    public static function findByOpNumber($opNumber)
    {
        return static::where('op_number', $opNumber)->first();
    }

    /**
     * Obtener eventos en un rango de fechas
     */
    public static function getEventsInRange($start, $end)
    {
        return static::whereBetween('start', [$start, $end])
                    ->orWhereBetween('end', [$start, $end])
                    ->orWhere(function($query) use ($start, $end) {
                        $query->where('start', '<=', $start)
                              ->where('end', '>=', $end);
                    })
                    ->get();
    }
    
}