<?php

namespace App\Http\Controllers;

use App\DTO\empresa\EmpresaDTO;
use App\Http\Requests\empresaRequest;
use App\Models\Cuenta;
use App\Models\Empresa;
use App\Services\Empresa\EmpresaService;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    public function __construct(
        private EmpresaService $empresaService
    ){}
    public function index()
    {
        return view('Modulos.Empresa.index');
    }

    public function save(empresaRequest $req)
    {
        try {
            $request = $req->validated();
            $userId = Auth::user()->id;
            $cuentaId = Auth::user()->cuenta_id;

            $empresa_id = $this->getEmpresaId();

            // 1. Validar campos únicos
            $error = $this->empresaService->validateUniqueFields($request, $empresa_id);
            if ($error) return $error;

            // 2. Manejo de archivos
            $path_file = '';
            $messageFile = '';
            [$path_file, $messageFile] = $this->empresaService->handleCertificate($request, $empresa_id);

            [$statuFile,$rutaLogo,$messageFileLogo] = $this->empresaService->handleLogo($request, $empresa_id);

            if(!$statuFile){
                return response()->json([
                    'status'       => 'error',
                    'message'      => $messageFileLogo,
                    'message_file' => $messageFile
                ]);
            }
            
            $array_actividad_econo = explode(' - ', $request['actividad_economica']);
            $cod_actividad = $array_actividad_econo[0];
            $desc_actividad = $array_actividad_econo[1];
            
            if ($empresa_id == 0) {
                $dtoEmpresaCreate = EmpresaDTO::fromCreateRequest($request, $path_file, $cod_actividad, $desc_actividad, $rutaLogo, $cuentaId, $userId);
                [$resultRecord, $messageRecord] = $this->empresaService->create($dtoEmpresaCreate);
            } else {
                $dtoEmpresaUpdate = EmpresaDTO::fromUpdateRequest($request, $path_file, $cod_actividad, $desc_actividad, $rutaLogo);
                [$resultRecord, $messageRecord] = $this->empresaService->update($dtoEmpresaUpdate, $empresa_id, $cuentaId);
            }

            if ($resultRecord) {
                return response()->json([
                    'status'       => 'success',
                    'message'      => $messageRecord,
                    'message_file' => $messageFile
                ]);
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'Ha ocurrido un error inesperado. Por favor, inténtelo nuevamente.'
            ]);
        } catch (Exception $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error inesperado. Por favor, inténtelo nuevamente.',
                'messageError' => $err->getMessage()
            ]);
        }
    }

    private function getEmpresaId(): int
    {
        try {
            $empresa_id = request()->input('empresa_id');
            return !empty($empresa_id) ? Crypt::decrypt($empresa_id) : 0;
        } catch (DecryptException $e) {
            return 0;
        }
    }

    /**Listar empresas */
    public function listarEmpresas()
    {
        //user auth
        if (!Auth::check()) {
            return redirect()->route('app.login.index');
        }
        $cuentaId = Auth::user()->cuenta_id;
        if (Auth::user()->categoria == 'SuperAdmin') {
            $datos = DB::select("SELECT e.id,e.ambiente, e.estado,e.nombre,e.nrc,e.nit,e.telefono,c.nombre as cuenta FROM `empresas` as e inner join cuentas as c on e.cuenta_id=c.id ORDER BY id asc;");
        } else {
            $datos = DB::select("SELECT e.id,e.ambiente, e.estado,e.nombre,e.nrc,e.nit,e.telefono,c.nombre as cuenta FROM `empresas` as e inner join cuentas as c on e.cuenta_id=c.id where cuenta_id = ? ORDER BY id asc;", [$cuentaId]);
        }

        $contador = 1;
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();
            $icon_toggle_status = ($row->estado === "Activo") ? 'bi-building-fill-slash' : 'bi-building-fill-check';
            $bg_btn_status = ($row->estado === "Activo") ? 'btn-outline-danger' : 'btn-outline-success';
            $title_btn = ($row->estado === "Activo") ? 'Desactivar empresa' : 'Activar empresa';
            $sub_array[] = $contador;
            $sub_array[] = ($row->ambiente == "00") ? 'PRUEBA' : 'PRODUCTIVO';
            $sub_array[] = $row->cuenta;
            $sub_array[] = $row->nombre;
            $sub_array[] = $row->telefono;
            $sub_array[] = $row->nrc;
            $sub_array[] = $row->nit;
            $sub_array[] = $row->estado;
            $sub_array[] = '
            <button onclick="editEmpresa(this)" data-record_id="' . encrypt($row->id) . '" title="Editar empresa" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-pencil-square"></i></button>
            <button onclick="addSucursal(this)" data-record_id="' . encrypt($row->id) . '" data-empresa="' . $row->nombre . '" title="Sucursales" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px;background:none"><i class="bi bi-eye"></i></button>
            <button onclick="refreshToken(this)" data-record_id="' . encrypt($row->id) . '" data-empresa="' . $row->nombre . '" title="Refrescar el token de acceso a MH" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-arrow-clockwise"></i></button>
            <button onclick="configEmail(this)" data-record_id="' . encrypt($row->id) . '" title="Configurar email" class="btn btn-outline-success btn-sm" style="border:none;font-size:18px"><i class="bi bi-envelope-plus"></i></button>
            <button onclick="toggleStatus(this)" data-record_id="' . encrypt($row->id) . '" data-estado="' . $row->estado . '" title="' . $title_btn . '" class="btn ' . $bg_btn_status . ' btn-sm" style="border:none;font-size:18px"><i class="bi ' . $icon_toggle_status . '"></i></button>
            ';
            //sucursales icon
            //<button onclick="addSucursal(this)" data-record_id="'. encrypt($row->id) .'" title="Nueva sucursal" class="btn btn-outline-primary btn-sm" style="border:none;font-size:18px"><i class="bi bi bi-building-fill-add"></i></button>

            $data[] = $sub_array;
            $contador++;
        }

        $results = array(
            "sEcho" => 1, // Información para el datatables
            "iTotalRecords" => count($data), // enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), // enviamos el total registros a visualizar
            "aaData" => $data
        );
        return response()
            ->json($results)
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }

    public function getEmpresaById()
    {
        try {
            $empresa_id = Crypt::decrypt(request()->get('empresa_id'));
            $empresa = Empresa::where('id', $empresa_id)->first();
            $array_empresa = [];
            if ($empresa) {
                $array_empresa = [
                    'id' => $empresa['id'],
                    'ambiente' => $empresa['ambiente'],
                    'certificado_path' => basename($empresa['certificado_path']),
                    'nombre' => $empresa['nombre'],
                    'nrc' => $empresa['nrc'],
                    'nit' => $empresa['nit'],
                    'nombre_comercial' => $empresa['nombre_comercial'],
                    'act_economica' => $empresa['act_economica'],
                    'desc_actividad' => $empresa['desc_actividad'],
                    'tipo_establecimiento' => $empresa['tipo_establecimiento'],
                    'depto_code' => $empresa['depto_code'],
                    'munic_code' => $empresa['munic_code'],
                    'direccion' => $empresa['direccion'],
                    'email' => $empresa['email'],
                    'telefono' => $empresa['telefono'],
                    'cod_establecimiento_mh' => $empresa['cod_establecimiento_mh'],
                    'cod_punto_venta_mh' => $empresa['cod_punto_venta_mh'],
                    'clave_cert' => !empty($empresa['clave_cert']) ? Crypt::decrypt($empresa['clave_cert']) : '',
                    'clave_api' => !empty($empresa['clave_api']) ? Crypt::decrypt($empresa['clave_api']) : '',
                    'logo' => ($empresa['logo'] != "") ? Storage::url($empresa['logo']) : ''
                ];
            }
            return response()->json([
                'status' => 'success',
                'result' => $array_empresa
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'result' => []
            ]);
        }
    }

    public function updateEstado()
    {
        try {
            $empresa_id = Crypt::decrypt(request()->get('empresa_id'));
            $empresa = Empresa::where('id', $empresa_id)->first();
            if ($empresa) {
                if ($empresa['estado'] == 'Activo') {
                    $empresa->estado = 'Desactivado';
                } elseif ($empresa['estado'] == 'Desactivado') {
                    $empresa->estado = 'Activo';
                }

                if ($empresa->save()) {
                    $message = 'El estado ha sido actualizado correctamente.';
                    $status = 'success';
                } else {
                    $message = 'Ha ocurrido un error al momento de actualizar el estado.';
                    $status = 'error';
                }
            } else {
                $message = 'La empresa no existe o no ha sido encontrada.';
                $status = 'error';
            }
            return response()->json([
                'status' => $status,
                'message' => $message
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Ha ocurrido un error al momento de actualizar el estado.'
            ]);
        }
    }

    public function getAllEmpresas()
    {
        $cuentaId = Auth::user()->cuenta_id;
        $empresas = Empresa::where('cuenta_id', $cuentaId)->select('id', 'nombre', 'nrc', 'nit')->get();
        $datosEmpresas = [];
        if (count($empresas) > 0) {
            foreach ($empresas as $empresa) {
                $array = [
                    'id' => encrypt($empresa->id),
                    'nombre' => $empresa->nombre,
                    'nrc' => $empresa->nrc,
                    'nit' => $empresa->nit
                ];
                $datosEmpresas[] = $array;
            }
        }
        return response()->json($datosEmpresas);
    }
}
