<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValFormDinamico extends Model
{
    protected $fillable = [
        'nombre',
        'modulo_form',
        'identicador',
        'usuario_id',
    ];
}
