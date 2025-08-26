<?php
namespace App\Services\Modulos_permiso;

use App\Models\Permission\Modulo;
use App\Models\Permission\ModuloCuenta;
use App\Models\Permission\PermisoUsuario;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class ModuloPermisoService{
    public function asignarModuloCuenta(array $modulos, int $cuenta_id){
        $usuario_id = Auth::user()->id;
        $modulosSeleccionados = array_map(fn($item) => Crypt::decrypt($item->modulo_id), $modulos);
        // Módulos ya asignados
        $modulosExistentes = ModuloCuenta::where('cuenta_id', $cuenta_id)
            ->pluck('modulo_id')
            ->toArray();

        // Insertar nuevos
        $modulosParaInsertar = array_diff($modulosSeleccionados, $modulosExistentes);
        foreach ($modulosParaInsertar as $modulo_id) {
            ModuloCuenta::create([
                'modulo_id' => $modulo_id,
                'cuenta_id' => $cuenta_id,
                'asignador_id' => $usuario_id,
            ]);
        }
        // Eliminar los que se quitaron
        $modulosParaEliminar = array_diff($modulosExistentes, $modulosSeleccionados);
        if (!empty($modulosParaEliminar)) {
            ModuloCuenta::where('cuenta_id', $cuenta_id)
                ->whereIn('modulo_id', $modulosParaEliminar)
                ->delete();
        }
        return !empty($modulosParaInsertar) || !empty($modulosParaEliminar);
    }

    public function asignarModuloPermisoUser(array $modulos, int $usuario_id){
        $asignador_id = Auth::user()->id;
        $modulo_ids = array_map(fn($item) => Crypt::decrypt($item->modulo_id), $modulos);

        $datosModulos = Modulo::where('estado', 'Activo')
            ->with(['modulo_accions' => fn($q) => $q->orderBy('id', 'desc')])
            ->whereIn('id', $modulo_ids)
            ->get();

        $accionIds = $datosModulos->flatMap->modulo_accions->pluck('id')->toArray();
        if (empty($accionIds)) {
            return false;
        }
        $permisosExistentes = PermisoUsuario::where('usuario_id', $usuario_id)
            ->whereIn('modulo_accion_id', $accionIds)
            ->pluck('modulo_accion_id')
            ->toArray();

        $permisosParaInsertar = array_map(fn($accion_id) => [
            'modulo_accion_id' => $accion_id,
            'usuario_id' => $usuario_id,
            'asignador_id' => $asignador_id,
        ], array_diff($accionIds, $permisosExistentes));

        if (!empty($permisosParaInsertar)) {
            PermisoUsuario::insert($permisosParaInsertar);
            return true;
        }
        return false;
    }

    public function asignarPermisoUser(array $array_permisos, int $usuario_id, int $cuenta_id){
         $authUser = Auth::user();
        if (!$authUser || !isset($authUser->id)) {
            Log::warning("Intento de asignar permisos sin usuario autenticado.");
            return false;
        }
        $asignador_id = $authUser->id;

        try{
            $modulosExistentes = ModuloCuenta::where('cuenta_id', $cuenta_id)
                ->pluck('modulo_id')
                ->toArray();

            $modulo_ids_selected = array_map(fn($item) => Crypt::decrypt($item->modulo_id), $array_permisos);

            $modulosFiltrados = array_intersect($modulosExistentes, $modulo_ids_selected);

            // Filtrar permisos para que correspondan solo a los módulos válidos
            $permisosFiltrados = collect($array_permisos)
                ->map(function ($item) {
                    return [
                        'modulo_id' => Crypt::decrypt($item->modulo_id),
                        'permiso_id' => Crypt::decrypt($item->permiso_id),
                    ];
                })
                ->filter(fn($item) => in_array($item['modulo_id'], $modulosFiltrados))
                ->pluck('permiso_id')
                ->unique()
                ->toArray();
            if (empty($permisosFiltrados)) {
                return false;
            }

            // Permisos que el usuario ya tiene
            $permisosExistentes = PermisoUsuario::where('usuario_id', $usuario_id)
                ->pluck('modulo_accion_id')
                ->toArray();
            // Insertar solo los nuevos
            $permisosParaInsertar = array_map(fn($permiso_id) => [
                'modulo_accion_id' => $permiso_id,
                'usuario_id' => $usuario_id,
                'asignador_id' => $asignador_id,
            ], array_diff($permisosFiltrados, $permisosExistentes));

            if (!empty($permisosParaInsertar) && count($permisosParaInsertar) > 0) {
                PermisoUsuario::insert($permisosParaInsertar);
            }

            $permisosParaEliminar = array_diff($permisosExistentes, $permisosFiltrados);
            if (!empty($permisosParaEliminar)) {
                PermisoUsuario::where('usuario_id', $usuario_id)
                    ->whereIn('modulo_accion_id', $permisosParaEliminar)
                    ->delete();
            }
            return !empty($permisosParaInsertar) || !empty($permisosParaEliminar);
        }catch(Exception $e){
            Log::error("Error en asignarPermisoUser: " . $e->getMessage());
            return false;
        }
    }
}