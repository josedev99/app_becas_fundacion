<?php

namespace App\Models\becados;

use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    protected $fillable = [
        'fecha_reporte',
        'nota_adicional',
        'participacion_actividades',
        'observaciones_tutor',
        'estado_beca',
        'proridad',
        'fecha_proximo',
        'responsable_seguimiento',
        'estudiante_id',
        'user_id',
    ];
}
