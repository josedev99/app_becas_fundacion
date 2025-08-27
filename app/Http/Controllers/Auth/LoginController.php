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
        $datos_request = request()->only(['usuario', 'clave_user']);
        $user = User::where('usuario', $datos_request['usuario'])->first();
        if (!$user) {
            return redirect()->back()->with('error', 'Usuario o clave incorrectos.');
        }
        if ($user['estado'] != 'Activo') {
            return redirect()->back()->with('error', 'El usuario no está activo. Por favor, contacte al administrador.');
        }
        if (Hash::check($datos_request['clave_user'], $user['password'])) {
            $user->save();
            Auth::login($user);
            // Limpiar sesiones previas
            Session::forget('userPermission');
            Session::forget('modulosAccount');
            Session::forget('userModule');

            // Obtener y almacenar los permisos y subpermisos
            //$this->permissionService->loadUserPermissions($user->id);
            //$this->permissionService->loadUserModules($user->id);
            //$this->permissionService->loadAccountModules($user->cuenta_id);

            return redirect()->route('app.home')->with('success', 'Se ha verificado exitosamente.');
        } else {
            return redirect()->back()->with('error', 'Usuario o clave incorrectos.');
        }
    }
}
