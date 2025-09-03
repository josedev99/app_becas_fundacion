<?php

namespace App\Models\becados;

use Illuminate\Database\Eloquent\Model;

class Becados extends Model
{
    protected $table = "estudiante";
    protected $fillable = [
        'nombre_completo',
        'documento',
        'fecha_nacimiento',
        'direccion',
        'telefono',
        'email',
        'telefono_emergencia',
        'beca_id',
        'usuario_id',
    ];
}
