<?php
namespace App\Services\Estudiante;

use App\DTO\AcademicoDTO;
use App\DTO\EstudianteDTO;
use App\DTO\SocioEconomicoDTO;
use App\Models\becados\Becados;
use App\Models\becados\DatosAcademicos;
use App\Models\becados\Seguimiento;
use App\Models\DatosSocioeconomicos;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EstudianteService{
    public function __construct(private Becados $becadoModel){}
    public function saveDatosBecado($datos){
        DB::beginTransaction();
        try{
            $user_id = Auth::user()->id;
            $datosEstudiante = new EstudianteDTO(
                strtoupper(trim($datos['nombre_completo'])),
                $datos['documento'],
                $datos['fecha_nacimiento'],
                trim($datos['direccion']),
                $datos['telefono'],
                trim($datos['email_becado']),
                $datos['contacto_emergencia'],
                $datos['beca_id'],
                $user_id
            );
            $insertEstudiante = $this->saveOrUpdateEstudiante($datosEstudiante, $datos['record_id']);
            if($insertEstudiante){
                $datosAcademico = new AcademicoDTO(
                    $datos['nivel_educativo'],
                    $datos['institucion'],
                    $datos['carrera'],
                    $datos['promedio'],
                    $datos['estado_academico'],
                    $datos['fInicio_beca'],
                    $datos['fFin_beca'],
                    $insertEstudiante->id,
                    $user_id,
                );
                $inserAcademico = $this->saveOrUpdateDatosAcedemicos($datosAcademico, $datos['record_id']);
                $datosSocioEconomico = new SocioEconomicoDTO(
                    !empty($datos['situacion_familiar']) ? $datos['situacion_familiar'] : '',
                    !empty($datos['ingreso_aprox'])      ? $datos['ingreso_aprox']      : 0.00,
                    !empty($datos['numero_personas'])    ? $datos['numero_personas']    : 0,
                    !empty($datos['necesidades_esp'])    ? trim($datos['necesidades_esp']) : '',
                    !empty($datos['comunidad_residencia']) ? trim($datos['comunidad_residencia']) : '',
                    $insertEstudiante->id,
                    $user_id
                );
                $insertSocioEconomico = $this->saveOrUpdateSocioEconomico($datosSocioEconomico, $datos['record_id']);
                if($inserAcademico && $insertSocioEconomico){
                    DB::commit();
                    return [
                        'status' => 'success',
                        'message' => ($datos['record_id'] == 0)
                            ? 'El estudiante ha sido registrado exitosamente.'
                            : 'La información del estudiante se ha actualizado correctamente.'
                    ];
                }
            }
            return [
                'status' => 'error',
                'message' => 'Ha ocurrido un error al crear el estudiante.'
            ];
        }catch(Exception $e){
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => "Error interno: ". $e->getMessage()
            ];
        }
    }
    public function saveOrUpdateEstudiante(EstudianteDTO $datos, int $record_id = 0){
        return $this->becadoModel->updateOrCreate(
            [
                'id' => $record_id
            ],[
                'nombre_completo' => $datos->nombre_completo,
                'documento' => $datos->documento,
                'fecha_nacimiento' => $datos->fecha_nacimiento,
                'direccion' => $datos->direccion,
                'telefono' => $datos->telefono,
                'email' => $datos->email,
                'telefono_emergencia' => $datos->telefono_emergencia,
                'beca_id' => $datos->beca_id,
            ] + (
                $record_id == 0 ? ['usuario_id' => $datos->usuario_id] : []
            )
        );
    }

    public function saveOrUpdateDatosAcedemicos(AcademicoDTO $datos, int $estudiante_id = 0){
        return DatosAcademicos::updateOrcreate([
                "estudiante_id" => $estudiante_id
            ],[
                'nivel_educativo' => $datos->nivel_educativo,
                'institucion' => $datos->institucion,
                'carrera_grado' => $datos->carrera_grado,
                'promedio' => $datos->promedio,
                'estado_academico' => $datos->estado_academico,
                'fInicio' => $datos->fInicio,
                'fFin' => $datos->fFin,
            ] + (
                $estudiante_id == 0 ? [
                    'estudiante_id' => $datos->estudiante_id,
                    'user_id' => $datos->user_id
                ] : []
            )
        );
    }

    public function saveOrUpdateSocioEconomico(SocioEconomicoDTO $datos, int $estudiante_id = 0){
        return DatosSocioeconomicos::updateOrcreate([
                "estudiante_id" => $estudiante_id
            ],[
                'situacion_familiar' => $datos->situacion_familiar,
                'ingresos' => $datos->ingresos,
                'cantidad_personas' => $datos->cantidad_personas,
                'necesidades' => $datos->necesidades,
                'comunidad' => $datos->comunidad,
            ] + (
                $estudiante_id == 0 ? [
                    'estudiante_id' => $datos->estudiante_id,
                    'user_id' => $datos->user_id
                ] : []
            )
        );
    }
    //Listar datos de estudiantes
    public function getDatosEstudianteTabla(){
        $datos = DB::table('estudiantes AS e')
            ->join('becas AS b','b.id','=','e.beca_id')
            ->select('e.id','e.nombre_completo','e.documento', 'e.fecha_nacimiento', 'e.email','b.nombre','b.tipo_beca', 'e.created_at')
            ->orderBy('e.id','ASC')
            ->get();

        $contador = 1;
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = date('d/m/Y H:i:s A',strtotime($row->created_at));
            $sub_array[] = ucwords(strtolower($row->nombre_completo));
            $sub_array[] = $row->documento;
            $sub_array[] = $this->getEdad($row->fecha_nacimiento);
            $sub_array[] = $row->email;
            $sub_array[] = ucfirst(strtolower($row->nombre)) . ' - ' . ucfirst($row->tipo_beca);
            $sub_array[] = '
            <button onclick="editEstudiante(this)" data-record_id="'. encrypt($row->id) .'" title="Editar usuario" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-person-gear"></i></button>
            <button onclick="removeEstudiante(this)" data-record_id="'. encrypt($row->id) .'" data-nombre="'.ucwords(strtolower($row->nombre_completo)).'" title="Remover usuario" class="btn btn-outline-danger btn-sm" style="border:none;font-size:18px"><i class="bi bi-person-x"></i></button>
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

    //Obtener edad
    protected function getEdad($fechaNacimiento = ''){
        return Carbon::parse($fechaNacimiento)->age;
    }

    public function getBecadoById(int $estudiante_id){
        return DB::table('estudiantes AS e')
            ->join('datos_academicos AS da','da.estudiante_id','=', 'e.id')
            ->join('datos_socioeconomicos AS ds', 'ds.estudiante_id', '=','e.id')
            ->where('e.id', $estudiante_id)
            ->select('e.*','da.id AS academica_id', 'da.nivel_educativo', 'da.institucion', 'da.carrera_grado', 'da.promedio', 'da.estado_academico','da.fInicio','da.fFin', 'ds.id AS economico_id', 'ds.situacion_familiar', 'ds.ingresos','ds.cantidad_personas', 'ds.necesidades', 'ds.comunidad')
            ->first();
    }

    public function getBecadosAll(){
        return Becados::select('id','nombre_completo','documento')->orderBy('id','DESC')->get();
    }

    public function destroy(int $id){
        DB::beginTransaction();
        try{
            $becado = Becados::where('id', $id)->first();
            if($becado){
                $seguimientoCount = Seguimiento::where('estudiante_id', $becado['id'])->count();
                if((int) $seguimientoCount > 0){
                    return [
                        "status" => "error",
                        "message" => "El becado tiene seguimientos asociados."
                    ];
                }

                $academicos = DatosAcademicos::where('estudiante_id', $becado['id'])->delete();
                $socioeconomico = DatosSocioeconomicos::where('estudiante_id', $becado['id'])->delete();

                if($academicos && $socioeconomico){
                    $becado->delete();
                    DB::commit();
                    return [
                        "status" => "success",
                        "message" => "El becado se ha eliminado con éxito."
                    ];
                }
                return [
                    "status" => "error",
                    "message" => "Error al eliminar datos asociados del becado."
                ];
            }
        }catch(Exception $err){
            DB::rollBack();
            return [
                "status" => "error",
                "message" => "Ha ocurrido un error al procesar la solicitud."
            ];
        }
    }
}