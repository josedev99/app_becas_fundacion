<?php

namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermisoUsuario extends Model
{
    use HasFactory;
    protected $fillable = [
        'modulo_accion_id',
        'usuario_id',
        'asignador_id',
    ];
}
