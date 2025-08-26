<?php

namespace App\Http\Controllers;

use App\DTO\empresa\EmpresaDTO;
use App\Http\Requests\CuentaAdminRequest;
use App\Models\Cuenta;
use App\Models\User;
use App\Services\Empresa\EmpresaService;
use App\Services\Modulos_permiso\ModuloPermisoService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CuentaController extends Controller
{
    public function __construct(
        private ModuloPermisoService $moduloPermisoService,
        private EmpresaService $empresaService
    ){}
    public function index(){
        return view('Modulos.Cuenta.index');
    }
    public function save(CuentaAdminRequest $request){
        $userId = Auth::user()->id;
        try{
            DB::beginTransaction();
            $cuentaName = strtoupper(trim($request['cuenta']));
            $telefono = trim($request['telefono']);
            $email = trim($request['email']);
            $data_cuenta = [
                'nombre' => $cuentaName,
                'propietario' => strtoupper(trim($request['propietario'])),
                'telefono' => $telefono,
                'email' => $email,
                'estado' => 'Activo',
            ];
            $modulosAsignadosCuenta = json_decode($request->modulosAsignadosCuenta);
            //validar si existen cuentaId
            if(request()->filled('cuentaId') && request()->input('cuentaId') != null){
                $record_id = Crypt::decrypt(request('cuentaId'));
                if(Auth::user()->categoria == 'SuperAdmin'){
                    $data_cuenta['limite_empresas'] = $request['cantidad_emp'];
                }
                Cuenta::where('id',$record_id)->update($data_cuenta);
                //update asignacion de modulos
                $this->moduloPermisoService->asignarModuloCuenta($modulosAsignadosCuenta, $record_id);
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Cuenta actualizada correctamente.'
                ]);
            }else{
                $saveCuenta = Cuenta::create(array_merge($data_cuenta, [
                    'limite_empresas' => $request['cantidad_emp'],
                    'usuario_id' => $userId
                ]));
        
                if($saveCuenta){
                    //asignacion de modulos a cuenta
                    $this->moduloPermisoService->asignarModuloCuenta($modulosAsignadosCuenta, $saveCuenta->id);
                    //validacion de usuario
                    $userExists = User::where('usuario', $request['user_admin'])->first();
                    if($userExists){
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Ya existe un registro con este usuario.'
                        ]);
                    }
                    //create empresa default
                    $datosDTOEmpresa = EmpresaDTO::fromCreateRequest([
                        'checkAmbienteDte' => '00',
                        'nombre' => $cuentaName,
                        'email' => $email,
                        'telefono' => $telefono
                    ], '', '', '','', $saveCuenta->id, $userId);

                    [$empresaArrayInserted, $message] = $this->empresaService->create($datosDTOEmpresa);
                
                    $passwd = Hash::make($request['clave_admin']);
                    $user = User::create([
                        'nombre' => strtoupper(trim($request['nombre_admin'])),
                        'telefono' => '0000-0000',
                        'documento' => '00000000-0',
                        'direccion' => '-',
                        'email' => 'example@example.com',
                        'usuario' => $request['user_admin'],
                        'password' => $passwd,
                        'password_show' => encrypt($request['clave_admin']),
                        'estado' => 'Activo',
                        'categoria' => 'Administrador',
                        'cargo' => 'Administrador',
                        'cuenta_id' => $saveCuenta->id,
                        'empresa_id' => $empresaArrayInserted->id ?? 0
                    ]);
                    //asignacion de permiso a usuario
                    $this->moduloPermisoService->asignarModuloPermisoUser($modulosAsignadosCuenta, $user->id);
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Cuenta guardada correctamente.'
                    ]);
                }
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error al guardar la cuenta.'
                ]);
            }
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error al guardar la cuenta. ',
                'messageError' => $e->getMessage()
            ]);
        }
    }

    public function listarCuentas(){
        //user auth
        if(!Auth::check()){
            return redirect()->route('app.login.index');
        }
        $cuentaId = Auth::user()->cuenta_id;
        if(Auth::user()->categoria == 'SuperAdmin'){
            $datos = DB::select("SELECT c.id,c.nombre,c.propietario,c.telefono,c.email,c.limite_empresas,c.estado,COALESCE(count(e.id),0) as cantidad_emp FROM `cuentas` as c left join empresas as e on c.id=e.cuenta_id GROUP by c.id ORDER BY c.id asc;");
        }else{
            $datos = DB::select("SELECT c.id,c.nombre,c.propietario,c.telefono,c.email,c.limite_empresas,c.estado,COALESCE(count(e.id),0) as cantidad_emp FROM `cuentas` as c left join empresas as e on c.id=e.cuenta_id where c.id = ? GROUP by c.id ORDER BY c.id asc;",[$cuentaId]);
        }

        $contador = 1;
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();
            $icon_toggle_status = ($row->estado === "Activo") ? 'bi-x-circle' : 'bi-check-circle';
            $bg_btn_status = ($row->estado === "Activo") ? 'btn-outline-danger' : 'btn-outline-success';
            $title_btn = ($row->estado === "Activo") ? 'Desactivar cuenta' : 'Activar cuenta';
            $sub_array[] = $contador;
            $sub_array[] = $row->nombre;
            $sub_array[] = $row->propietario;
            $sub_array[] = $row->telefono;
            $sub_array[] = $row->email;
            $sub_array[] = $row->estado;
            $sub_array[] = $row->limite_empresas;
            $sub_array[] = $row->cantidad_emp;
            $sub_array[] = '
            <button onclick="editAccount(this)" data-record_id="'. encrypt($row->id) .'" title="Editar cuenta" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-person-fill-gear"></i></button>
            <button onclick="toggleStatus(this)" data-record_id="'. encrypt($row->id) .'" data-estado="'.$row->estado.'" title="'.$title_btn.'" class="btn '.$bg_btn_status.' btn-sm" style="border:none;font-size:18px"><i class="bi '.$icon_toggle_status.'"></i></button>
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
        return response()
            ->json($results)
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }

    public function getCuenta(){
        $record_id = Crypt::decrypt(request('cuentaId'));
        $data = Cuenta::where('id',$record_id)->first();
        return response()->json($data);
    }

    public function updateEstado(){
        try{
            $cuenta_id = Crypt::decrypt(request()->get('cuenta_id'));
            $cuenta = Cuenta::where('id',$cuenta_id)->first();
            if ($cuenta) {
                if ($cuenta['estado'] == 'Activo') {
                    $cuenta->estado = 'Desactivado';
                } elseif ($cuenta['estado'] == 'Desactivado') {
                    $cuenta->estado = 'Activo';
                }
            
                if ($cuenta->save()) {
                    $message = 'El estado ha sido actualizado correctamente.';
                    $status = 'success';
                } else {
                    $message = 'Ha ocurrido un error al momento de actualizar el estado.';
                    $status = 'error';
                }
            } else {
                $message = 'La cuenta no existe o no ha sido encontrada.';
                $status = 'error';
            }
            return response()->json([
                'status' => $status,
                'message' => $message
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 'Ha ocurrido un error al momento de actualizar el estado.'
            ]);
        }
    }
}
