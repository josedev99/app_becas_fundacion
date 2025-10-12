<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\becados\Becados;

class DatosSocioeconomicos extends Model
{
    protected $fillable = [
        'situacion_familiar',
        'ingresos',
        'cantidad_personas',
        'necesidades',
        'comunidad',
        'estudiante_id',
        'user_id',
    ];

    public function estudiante()
    {
        return $this->belongsTo(Becados::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
