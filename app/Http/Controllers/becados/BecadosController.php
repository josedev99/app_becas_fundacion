<?php

namespace App\Http\Controllers\becados;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstudianteRequest;
use App\Services\Estudiante\EstudianteService;
use Illuminate\Http\Request;

class BecadosController extends Controller
{
    public function index()
    {
        return view('Modulos.Becados.Index');
    }

    public function saveEstudiante(StoreEstudianteRequest $request, EstudianteService $service){
        $dataRequest = $request->validated();
        return response()->json($service->saveDatosBecado($dataRequest));
    }

    public function listarEstudiantes(EstudianteService $service){
        return response()->json($service->getDatosEstudianteTabla());
    }
}
