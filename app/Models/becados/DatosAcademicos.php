<?php

namespace App\Models\becados;

use Illuminate\Database\Eloquent\Model;

class DatosAcademicos extends Model
{
    protected $fillable = [
        'nivel_educativo',
        'institucion',
        'carrera_grado',
        'promedio',
        'estado_academico',
        'fInicio',
        'fFin',
        'estudiante_id',
        'user_id',
    ];
}
