<?php

namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;
    protected $fillable = [
        'clave',
        'nombre',
        'descripcion',
        'estado',
        'usuario_id',
    ];

    public function modulo_accions()
    {
        return $this->hasMany(ModuloAccion::class, 'modulo_id', 'id');
    }
}
