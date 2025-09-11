<?php

namespace App\Http\Controllers\becas;

use App\Http\Controllers\Controller;
use App\Http\Requests\BecaRequest;
use App\Models\becas\Beca;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class BecasController extends Controller
{
    public function index(){
        return view('Modulos.Becas.Index');
    }
    public function getBecas(){
        return response()->json(Beca::orderBy('id','desc')->get());
    }

    public function save(BecaRequest $request){
        $datos = $request->validated();
        $userId = Auth::user()->id;
        $datosDTO = [
            'fInicio' => date('Y-m-d'),
            'fFin' => date('Y-m-d'),
            'nombre' => trim($datos['nombre_beca']),
            'tipo_beca' => $datos['tipo_beca'],
            'financiamiento' => $datos['financiamiento'],
            'plazo_monto' => $datos['plazo_monto'],
            'forma_entrega' => $datos['forma_entrega'],
            'compromisos' => $datos['compromiso'],
            'responsable' => strtoupper(trim($datos['encargado_beca'])),
            'estado' => 'Activo',
        ];
        if((int)$request['record_id'] != 0){
            $create_beca = Beca::where('id', $request['record_id'])->update($datosDTO);
            $message = 'La beca se ha creado con exito.';
        }else{
            $create_beca = Beca::create(array_merge($datosDTO, [
                'user_id' => $userId,
            ]));
            $message = 'La beca se ha actualizado con exito.';
        }
        if($create_beca){
            return response()->json([
                'status' => 'success',
                'message' => $message
            ]);
        }
        return response()->json([
            'status' => 'warning',
            'message' => 'Ha ocurrido un error al crear la beca.'
        ]);
    }

    public function getBecaById(Request $request){
        try{
            $id = Crypt::decrypt($request->record_id);
        }catch(Exception $e){
            $id = 0;
        }
        $beca = Beca::where('id',$id)->first();
        return response()->json([
            'status' => $beca ? 'okDatos' : 'notDatos',
            'result' => $beca
        ]);
    }

    protected function getDataBecasAll(){
        return Beca::orderBy('id','ASC')->get();
    }
    public function listarBecas(){
        $datos = $this->getDataBecasAll();
        $contador = 1;
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = date('d/m/Y H:i:s A',strtotime($row->created_at));
            $sub_array[] = $row->nombre;
            $sub_array[] = $row->tipo_beca;
            $sub_array[] = $row->financiamiento;
            $sub_array[] = $row->plazo_monto;
            $sub_array[] = $row->forma_entrega;
            $sub_array[] = ucwords(strtolower($row->responsable));
            $sub_array[] = '
            <button onclick="editBeca(this)" data-record_id="'. encrypt($row->id) .'" title="Editar beca" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-pencil-square"></i></button>
            <button onclick="destroyBeca(this)" data-record_id="'. encrypt($row->id) .'" title="Remover beca" class="btn btn-outline-danger btn-sm" style="border:none;font-size:18px"><i class="bi bi-x-circle"></i></button>
            ';

            $data[] = $sub_array;
            $contador ++;
        }

        $results = array(
            "sEcho" => 1, // Información para el datatables
            "iTotalRecords" => count($data), // enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), // enviamos el total registros a visualizar
            "aaData" => $data
        );
        return response()->json($results);
    }
}
