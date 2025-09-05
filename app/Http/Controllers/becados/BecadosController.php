<?php

namespace App\Http\Controllers\becados;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstudianteRequest;
use Illuminate\Http\Request;

class BecadosController extends Controller
{
    public function index()
    {
        return view('Modulos.Becados.Index');
    }

    public function saveEstudiante(StoreEstudianteRequest $request){
        $dataRequest = $request->validated();

        return $dataRequest;
    }
}
