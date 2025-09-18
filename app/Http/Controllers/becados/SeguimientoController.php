<?php

namespace App\Http\Controllers\becados;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeguimientoRequest;
use App\Services\Estudiante\SeguimientoService;
use Illuminate\Http\Request;

class SeguimientoController extends Controller
{
    public function index(){
        return view('Modulos.Seguimiento.Index');
    }

    public function saveSeguimiento(SeguimientoRequest $request, SeguimientoService $service){
        $datosValidados = $request->validated();
        return response()->json($service->save($datosValidados));
    }

    public function listarSeguimientos(SeguimientoService $service){
        return response()->json($service->getDataDtSeguimiento());
    }
}
