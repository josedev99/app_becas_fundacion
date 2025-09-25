<?php
namespace App\Services\Estudiante;

use App\Models\becados\Becados;
use App\Models\becados\Seguimiento;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SeguimientoService{
    public function __construct(private Seguimiento $seguimientoModel){
        date_default_timezone_set('America/El_Salvador');
    }
    public function save($datos){
        DB::beginTransaction();
        try{
            $user_id = Auth::user()->id;
            $saveSeguimiento = Seguimiento::create([
                'fecha_reporte' => $datos['fecha_seguimiento'] . ' ' . date('H:i:s'),
                'nota_adicional' => trim($datos['notas_add']),
                'participacion_actividades' => trim($datos['participacion']),
                'observaciones_tutor' => trim($datos['observaciones_tutor']),
                'estado_beca' =>$datos['estado_beca'],
                'proridad' => $datos['prioridad_segui'],
                'fecha_proximo' => !empty($datos['fecha_proximo']) ? $datos['fecha_proximo'] : '',
                'responsable_seguimiento' => strtoupper(trim($datos['responsable_seguimiento'])),
                'estudiante_id' => $datos['becado_seguimiento'],
                'user_id' => $user_id,
            ]);
            if($saveSeguimiento){
                DB::commit();
                return [
                    'status' => 'success',
                    'message' => 'El seguimiento del becado se ha registrado.'
                ];
            }
            return [
                'status' => 'error',
                'message' => 'Ha ocurrido un error al crear el seguimiento.'
            ];
        }catch(Exception $e){
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => "Error interno: ". $e->getMessage()
            ];
        }
    }

    public function getDataDtSeguimiento(){
        $datos = DB::table('estudiantes AS e')
            ->join('seguimientos AS s','s.estudiante_id','=','e.id')
            ->select('e.id','e.nombre_completo','e.documento', 's.fecha_reporte', 's.estado_beca','s.responsable_seguimiento','s.proridad', 's.created_at')
            ->orderBy('e.id','ASC')
            ->get();

        $contador = 1;
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = ucwords(strtolower($row->nombre_completo));
            $sub_array[] = date('d/m/Y H:i:s A',strtotime($row->fecha_reporte));
            $sub_array[] = $row->estado_beca;
            $sub_array[] = $row->proridad;
            $sub_array[] = ucfirst(strtolower($row->responsable_seguimiento));
            $sub_array[] = '
                <button onclick="showDetails(this)" data-record_id="'. encrypt($row->id) .'" title="Editar usuario" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-eye"></i></button>
                <button onclick="deleteSeguimiento(this)" data-record_id="'. encrypt($row->id) .'" title="Remover usuario" class="btn btn-outline-danger btn-sm" style="border:none;font-size:18px"><i class="bi bi-x-circle"></i></button>
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
        return $results;
    }

    public function getDetalleById(int $id = 0){
        $seguimientoArray = Seguimiento::where('id', $id)->first();
        if($seguimientoArray){
            $estudiante = DB::table('estudiantes AS e')
                ->join('datos_academicos AS a','a.estudiante_id','=','e.id')
                ->join('becas AS b', 'b.id', '=', 'e.beca_id')
                ->where('e.id', $seguimientoArray['estudiante_id'])
                ->select('e.nombre_completo','e.documento', 'b.nombre','b.tipo_beca', 'a.carrera_grado','a.created_at')
                ->first();
            $estudiante->created_at = date('d/m/Y H:i:s',strtotime($estudiante->created_at));
            $estudiante->seguimiento = $seguimientoArray;
            return $estudiante;
        }
        return [];
    }
}