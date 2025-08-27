<?php

namespace App\Http\Controllers\becados;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BecadosController extends Controller
{
    public function index()
    {
        return view('Modulos.Becados.Index');
    }
}
