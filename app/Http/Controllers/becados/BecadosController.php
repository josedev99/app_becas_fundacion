<?php

namespace App\Http\Controllers\becados;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEstudianteRequest;
use App\Models\becados\Becados;
use App\Models\becados\DatosAcademicos;
use App\Models\becados\Seguimiento;
use App\Models\becas\Beca;
use App\Models\DatosSocioeconomicos;
use App\Services\Estudiante\EstudianteService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PDF;

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
    //Generar pdf expediente
    public function printExpediente(Request $request){
        try{
            $becado_id = Crypt::decrypt($request->becado_id);
        }catch(Exception $err){
            $becado_id = 0;
        }
        $becado = Becados::where('id', $becado_id)->first();
        $beca = Beca::where('id', $becado['beca_id'])->first();
        $academico = DatosAcademicos::where('estudiante_id', $becado_id)->first();
        $socio = DatosSocioeconomicos::where('estudiante_id', $becado_id)->first();
        $seguimientos = Seguimiento::where('estudiante_id', $becado_id)->get();

        $pdf = PDF::loadView('Modulos.Becados.pdf.expendiente',compact('becado','beca','academico','socio','seguimientos'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('Expediente_'.str_replace(' ','_',ucwords(strtolower($becado['nombre_completo']))).'.pdf');
    }
}
