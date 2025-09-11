<?php

namespace App\Models\becas;

use Illuminate\Database\Eloquent\Model;

class Beca extends Model
{
    protected $fillable = [
        'fInicio',
        'fFin',
        'nombre',
        'tipo_beca',
        'financiamiento',
        'plazo_monto',
        'forma_entrega',
        'compromisos',
        'responsable',
        'estado',
        'user_id',
    ];
}
