<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Empresa;
use App\Models\User;
use App\Services\Modulos_permiso\ModuloPermisoService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(private ModuloPermisoService $moduloPermisoService){
        //$this->middleware('check.permission:show_user')->only(['index']);
    }
    public function index(){
        if(!Auth::check()){
            return redirect()->route('app.login.index');
        }
        return view('Modulos.Usuario.Index');
    }
    public function save(UserRequest $req){
        DB::beginTransaction();
        try{
            $request = $req->validated();
            $clave = Hash::make($request['clave_user']);
            $clave_show = encrypt($request['clave_user']);

            $telefono = !is_null(request()->input('telefono_user')) ? trim(request()->input('telefono_user')) : '0000-0000';
            $documento = !is_null(request()->input('doc_user')) ? request()->input('doc_user','00000000-0') : '00000000-0';
            $direccion = !is_null(request()->input('direccion_user')) ? trim(request()->input('direccion_user')) : '-';
            $email = !is_null(request()->input('email_user')) ? trim(request()->input('email_user')) : '';
            $cargo = !is_null(request()->input('cargo_user')) ? trim(request()->input('cargo_user','-')) : '-';

            //record_id se utiliza para actualizar registro
            $record_id = request()->input('record_id') != 0 ? Crypt::decrypt(request()->input('record_id')) : 0;
            //array de permisos
            $permisosAsignadosUser = json_decode(request()->get('permisosAsignadosUser'));
            //validar existe user
            $dataUser = [
                'nombre' => trim(strtoupper($request['nombre_user'])),
                'telefono' => $telefono,
                'documento' => $documento,
                'direccion' => $direccion,
                'email' => $email,
                'usuario' => $request['usuario_user'],
                'password' => $clave,
                'pass_show' => $clave_show,
                'estado' => $request['estado_user'],
                'categoria' => $request['categoria_user'],
                'cargo' => $cargo,
            ];
            $userExists = User::where('usuario', $request['usuario_user'])->first();
            if($record_id == 0){
                if($userExists){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Ya existe un registro con este usuario.'
                    ]);
                }
                
                $saveUser = User::create($dataUser);
                $this->moduloPermisoService->asignarPermisoUser($permisosAsignadosUser,$saveUser->id);
                $message = 'El usuario se ha creado exitosamente.';
            } else {
                $saveUser = User::where('id', $record_id)->update($dataUser);
                $this->moduloPermisoService->asignarPermisoUser($permisosAsignadosUser,$record_id);
                $message = 'El usuario se ha actualizado exitosamente.';
            }

            if($saveUser){
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => $message
                ]);
            }
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error al momento de crear el usuario.'
            ]);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error al momento de crear el usuario.',
                'messageError' => $e->getMessage()
            ]);
        }
    }

    public function listarAll(){
        $datos = DB::select("select u.id, u.nombre,u.telefono,u.usuario,u.estado,u.categoria from users as u order by u.id asc");

        $contador = 1;
        $data = [];
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = $row->nombre;
            $sub_array[] = $row->telefono;
            $sub_array[] = $row->usuario;
            $sub_array[] = $row->estado;
            $sub_array[] = $row->categoria;
            $sub_array[] = '
            <button onclick="editUser(this)" data-record_id="'. encrypt($row->id) .'" title="Editar usuario" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-person-gear"></i></button>
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
        return response()
            ->json($results)
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=86400');
    }
    //Obtener datos del usuario
    public function getUserById(){
        try{
            $record_id = !is_null(request()->input('record_id')) ? Crypt::decrypt(request()->input('record_id')) : 0;
            $dataUser = [];
            if($record_id != 0){
                $data_user = User::where('id',$record_id)->get();
                foreach($data_user as $user){
                    $dataUser = [
                        'id' => $user['id'],
                        'nombre' => $user['nombre'],
                        'telefono' => $user['telefono'],
                        'documento' => $user['documento'],
                        'direccion' => $user['direccion'],
                        'email' => $user['email'],
                        'usuario' => $user['usuario'],
                        'clave' => ($user['pass_show'] != "") ? Crypt::decrypt($user['pass_show']) : '',
                        'estado' => $user['estado'],
                        'categoria' => $user['categoria'],
                        'cargo' => $user['cargo'],
                    ];
                }
                $message = 'Usuario obtenido exitosamente.';
            }else{
                $message = 'Usuario no encontrado.';
            }
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'result' => $dataUser
            ]);
        }catch(Exception $err){
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error al obtener la información del usuario',
                'messageError' => $err->getMessage(),
                'result' => []
            ]);
        }
    }
}