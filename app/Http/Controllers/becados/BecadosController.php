<?php

namespace App\Http\Controllers\becados;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstudianteRequest;
use App\Services\Estudiante\EstudianteService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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

    public function getEstudianteById(Request $request,EstudianteService $service){
        try{
            $record_id = Crypt::decrypt($request->record_id);
        }catch(Exception $e){
            $record_id = 0;
        }
        return response()->json($service->getBecadoById($record_id));
    }

    public function getBecadosAll(EstudianteService $service){
        return response()->json($service->getBecadosAll());
    }

    public function destroyBecado(Request $request, EstudianteService $service){
        try{
            $id = Crypt::decrypt($request->get('record_id'));
        }catch(Exception $err){
            $id = 0;
        }

        return response()->json($service->destroy($id));
    }
}
