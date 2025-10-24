<?php

namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuloAccion extends Model
{
    use HasFactory;
    protected $fillable = [
        'clave',
        'nombre',
        'descripcion',
        'estado',
        'modulo_id',
        'usuario_id',
    ];
}
