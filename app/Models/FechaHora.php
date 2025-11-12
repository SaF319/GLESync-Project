<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FechaHora extends Model
{
    use HasFactory;

    protected $table = 'fechas_horas';

    protected $fillable = [
        'evento_id',
        'fecha_hora'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}
