<?php
namespace App\Services\User;

use App\Models\Permission\Modulo;
use App\Models\Permission\ModuloAccion;
use App\Models\Permission\ModuloCuenta;
use App\Models\Permission\PermisoUsuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserPermissionService{
    public function loadUserPermissions(int $userId): array
    {
        if (!Auth::check()) {
            return [];
        }

        $permisoIds = PermisoUsuario::where('usuario_id', $userId)
            ->pluck('modulo_accion_id')
            ->toArray();

        if (empty($permisoIds)) {
            return [];
        }

        $permissions = ModuloAccion::whereIn('id', $permisoIds)
            ->pluck('clave')
            ->toArray();

        Session::put('userPermission', $permissions);

        return $permissions;
    }

    public function loadUserModules(int $userId): array
    {
        if (!Auth::check()) {
            return [];
        }

        $permisoIds = PermisoUsuario::where('usuario_id', $userId)
            ->pluck('modulo_accion_id')
            ->toArray();

        if (empty($permisoIds)) {
            return [];
        }

        $moduleIds = ModuloAccion::whereIn('id', $permisoIds)
            ->pluck('modulo_id')
            ->unique()
            ->toArray();

        $modules = Modulo::whereIn('id', $moduleIds)
            ->pluck('clave')
            ->unique()
            ->toArray();

        Session::put('userModule', $modules);

        return $modules;
    }

    public function loadAccountModules(int $accountId): array
    {
        if (!Auth::check()) {
            return [];
        }

        $moduleIds = ModuloCuenta::where('cuenta_id', $accountId)
            ->pluck('modulo_id')
            ->toArray();

        $modules = Modulo::whereIn('id', $moduleIds)
            ->pluck('clave')
            ->toArray();

        Session::put('modulosAccount', $modules);

        return $modules;
    }
}