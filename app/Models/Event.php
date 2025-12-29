<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'op_number',
        'title',
        'start',
        'end',
        'all_day',
        'estado',
        'codigos_json',
        'cantidad_req',
        'fecha_entrega',
        'fecha_produccion',
        'created_by',
        'version',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'all_day' => 'boolean',
        'codigos_json' => 'array',
        'fecha_entrega' => 'date',
        'fecha_produccion' => 'date',
    ];

    // Optional: helper to get safe title
    public function getSafeTitleAttribute()
    {
        return e($this->title);
    }
}
