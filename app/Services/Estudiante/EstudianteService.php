<?php
namespace App\Services\Estudiante;

use App\DTO\AcademicoDTO;
use App\DTO\EstudianteDTO;
use App\DTO\SocioEconomicoDTO;
use App\Models\becados\Becados;
use App\Models\becados\DatosAcademicos;
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
            $insertEstudiante = $this->saveEstudiante($datosEstudiante);
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
                $inserAcademico = $this->saveDatosAcedemicos($datosAcademico);
                $datosSocioEconomico = new SocioEconomicoDTO(
                    $datos['situacion_familiar'],
                    $datos['ingreso_aprox'],
                    $datos['numero_personas'],
                    trim($datos['necesidades_esp']),
                    trim($datos['comunidad_residencia']),
                    $insertEstudiante->id,
                    $user_id,
                );
                $insertSocioEconomico = $this->saveSocioEconomico($datosSocioEconomico);
                if($inserAcademico && $insertSocioEconomico){
                    DB::commit();
                    return [
                        'status' => 'success',
                        'message' => 'El estudiante se ha creado con exito.'
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
    public function saveEstudiante(EstudianteDTO $datos){
        return $this->becadoModel->create([
            'nombre_completo' => $datos->nombre_completo,
            'documento' => $datos->documento,
            'fecha_nacimiento' => $datos->fecha_nacimiento,
            'direccion' => $datos->direccion,
            'telefono' => $datos->telefono,
            'email' => $datos->email,
            'telefono_emergencia' => $datos->telefono_emergencia,
            'beca_id' => $datos->beca_id,
            'usuario_id' => $datos->usuario_id,
        ]);
    }

    public function saveDatosAcedemicos(AcademicoDTO $datos){
        return DatosAcademicos::create([
            'nivel_educativo' => $datos->nivel_educativo,
            'institucion' => $datos->institucion,
            'carrera_grado' => $datos->carrera_grado,
            'promedio' => $datos->promedio,
            'estado_academico' => $datos->estado_academico,
            'fInicio' => $datos->fInicio,
            'fFin' => $datos->fFin,
            'estudiante_id' => $datos->estudiante_id,
            'user_id' => $datos->user_id,
        ]);
    }

    public function saveSocioEconomico(SocioEconomicoDTO $datos){
        return DatosSocioeconomicos::create([
            'situacion_familiar' => $datos->situacion_familiar,
            'ingresos' => $datos->ingresos,
            'cantidad_personas' => $datos->cantidad_personas,
            'necesidades' => $datos->necesidades,
            'comunidad' => $datos->comunidad,
            'estudiante_id' => $datos->estudiante_id,
            'user_id' => $datos->user_id,
        ]);
    }
    //Listar datos de estudiantes
    public function getDatosEstudianteTabla(){
        $datos = DB::table('estudiantes AS e')
            ->join('becas AS b','b.id','=','e.beca_id')
            ->select('e.id','e.nombre_completo','e.documento', 'e.fecha_nacimiento', 'e.email',DB::raw('CONCAT(b.nombre, " ",b.tipo_beca) as beca'), 'e.created_at')
            ->orderBy('e.id','DESC')
            ->get();

        $contador = 1;
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = date('d/m/Y H:i:s A',strtotime($row->created_at));
            $sub_array[] = $row->nombre_completo;
            $sub_array[] = $row->documento;
            $sub_array[] = $this->getEdad($row->fecha_nacimiento);
            $sub_array[] = $row->email;
            $sub_array[] = $row->beca;
            $sub_array[] = '
            <button onclick="editEstudiante(this)" data-record_id="'. encrypt($row->id) .'" title="Editar usuario" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-person-gear"></i></button>
            <button onclick="destroyUser(this)" data-record_id="'. encrypt($row->id) .'" title="Remover usuario" class="btn btn-outline-danger btn-sm" style="border:none;font-size:18px"><i class="bi bi-person-check"></i></button>
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
}