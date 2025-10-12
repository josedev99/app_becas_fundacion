<?php
/* 
Declarados al momento hacer auth login
session: userPermission
session: userModule
session: modulosAccount
*/

use App\Services\User\UserPermissionService;
use Illuminate\Support\Facades\Auth;

class PermissionHelper{
    public static function hasPermissionUser(string $clave_permiso){
        return in_array($clave_permiso, session('userPermission', []));
    }

    public static function hasModuleUser(string $clave_module){
        return in_array($clave_module, session('userModule', []));
    }

    public static function hasAccountModule(string $clave_module){
        return in_array($clave_module, session('modulosAccount', []));
    }

    /**
     * Check if the authenticated user has all of the specified permissions
     *
     * @param array $permisos
     * @return bool
     */
    public static function hasAllPermisos($permisos)
    {
        $userPermisos = session('userPermission', []);
        return count(array_intersect($permisos, $userPermisos)) === count($permisos);
    }

    /**
     * Check if the authenticated user has any of the specified permissions
     *
     * @param array $permisos
     * @return bool
     */
    public static function hasAnyPermiso($permisos)
    {
        $userPermisos = session('userPermission', []);
        return count(array_intersect($permisos, $userPermisos)) > 0;
    }
    /* 
    Function static encargado para cargar los permisos en session
    ::funcionalidad para no volver hacer login
    */
    public static function load(){

        if (Auth::check()) {
            $permissionService = app(UserPermissionService::class);
            $permissionService->loadUserPermissions(Auth::user()->id);
            $permissionService->loadUserModules(Auth::user()->id);
            $permissionService->loadAccountModules(Auth::user()->cuenta_id);
        }
    }
}