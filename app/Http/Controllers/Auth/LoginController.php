<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Empresa\EmpresaService;
use App\Services\User\UserPermissionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function __construct(
        private EmpresaService $empresaService,
        private UserPermissionService $permissionService
    ){
    }
    public function index()
    {
        return view('Modulos.Usuario.Login');
    }

    public function login()
    {
        $datos_request = request()->only(['usuario', 'clave_user', 'empresa_id']);
        try {
            $empresa_id = Crypt::decrypt($datos_request['empresa_id']);
        } catch (Exception $e) {
            $empresa_id = 0;
        }
        if ($empresa_id == 0 || !$this->empresaService->userExistsInEmpresa($datos_request['usuario'], $empresa_id)) {
            return redirect()->back()->with('error', 'La empresa es obligatorio.');
        }
        $user = User::where('usuario', $datos_request['usuario'])->first();
        if (!$user) {
            return redirect()->back()->with('error', 'Usuario o clave incorrectos.');
        }
        if ($user['estado'] != 'Activo') {
            return redirect()->back()->with('error', 'El usuario no está activo. Por favor, contacte al administrador.');
        }
        if (Hash::check($datos_request['clave_user'], $user['password'])) {
            $user->empresa_id = $empresa_id;
            $user->save();
            Auth::login($user);
            // Limpiar sesiones previas
            Session::forget('userPermission');
            Session::forget('modulosAccount');
            Session::forget('userModule');

            // Obtener y almacenar los permisos y subpermisos
            $this->permissionService->loadUserPermissions($user->id);
            $this->permissionService->loadUserModules($user->id);
            $this->permissionService->loadAccountModules($user->cuenta_id);

            return redirect()->route('app.home')->with('success', 'Se ha verificado exitosamente.');
        } else {
            return redirect()->back()->with('error', 'Usuario o clave incorrectos.');
        }
    }

    public function getEmpresas(Request $request)
    {
        $user = $request->user;
        $dataEmpresa = DB::table('users as u')
            ->join('empresas as e', 'e.cuenta_id', '=', 'u.cuenta_id')
            ->where('u.usuario', $user)
            ->select('e.id', 'e.nombre')
            ->get();
        $arrayEmpresas = [];
        foreach ($dataEmpresa as $item) {
            $arrayEmpresas[] = [
                'id' => encrypt($item->id),
                'nombre' => $item->nombre,
            ];
        }
        return response()->json($arrayEmpresas);
    }
}
