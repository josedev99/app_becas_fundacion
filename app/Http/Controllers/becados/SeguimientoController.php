<?php

namespace App\Http\Controllers\becados;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeguimientoRequest;
use App\Services\Estudiante\SeguimientoService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

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

    public function getDatoSeguimiento(){
        $seguimientosArray = DB::select("SELECT e.nombre_completo, s.fecha_proximo, s.proridad, da.carrera_grado FROM `seguimientos` AS s INNER JOIN estudiantes AS e ON s.estudiante_id=e.id INNER JOIN datos_academicos AS da ON e.id=da.estudiante_id WHERE s.fecha_proximo >= NOW()");
        foreach ($seguimientosArray as &$item) {
            $item->fecha_proximo = date('d/m/Y', strtotime($item->fecha_proximo));
        }
        unset($item);
        return response()->json($seguimientosArray);
    }
}
