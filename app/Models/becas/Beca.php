<?php

namespace App\Models\becas;

use Illuminate\Database\Eloquent\Model;

class Beca extends Model
{
    protected $fillable = [
        'fInicio',
        'fFin',
        'tipo_beca',
        'financiamiento',
        'monto',
        'forma_entrega',
        'compromisos',
        'responsable',
        'estado',
        'user_id',
    ];
}
