<?php

namespace App\Http\Controllers\becados;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeguimientoRequest;
use App\Services\Estudiante\SeguimientoService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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

    public function showDetail(Request $request, SeguimientoService $service){
        try{
            $record_id = Crypt::decrypt($request->record_id);
        }catch(Exception $e){
            $record_id = 0;
        }
        return response()->json($service->getDetalleById($record_id));
    }
}
