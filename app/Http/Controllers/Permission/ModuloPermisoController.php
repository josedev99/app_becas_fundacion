<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreModuloRequest;
use App\Http\Requests\StorePermisoRequest;
use App\Models\Permission\Modulo;
use App\Models\Permission\ModuloAccion;
use App\Models\Permission\ModuloCuenta;
use App\Models\Permission\PermisoUsuario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ModuloPermisoController extends Controller
{
    public function createModulo(StoreModuloRequest $requestModulo)
    {
        try {
            $modulo_id = request()->filled('modulo_id') ? Crypt::decrypt(request()->input('modulo_id')) : 0;
        } catch (Exception $e) {
            $modulo_id = 0;
        }
        $usuario_id = Auth::user()->id;
        $clave_modulo = trim(strtolower($requestModulo->clave_modulo));
        $name_modulo = trim(strtoupper($requestModulo->name_modulo));
        $descripcion_modulo = !empty($requestModulo->descripcion_modulo) ? trim($requestModulo->descripcion_modulo) : '';
        if ($modulo_id == 0) {
            if ($this->validacionExisteModulo($clave_modulo, $name_modulo)) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'El módulo que intentas registrar ya existe.'
                ]);
            }
            $objecModulo = Modulo::create([
                'clave' => $clave_modulo,
                'nombre' => $name_modulo,
                'descripcion' => $descripcion_modulo,
                'estado' => 'Activo',
                'usuario_id' => $usuario_id
            ]);
            $message = 'Se ha registro el módulo.';
        } else {
            $objecModulo = $this->updateModulo($clave_modulo, $name_modulo, $descripcion_modulo, $modulo_id);
            $message = 'El módulo se ha actualizado con éxito.';
        }

        if ($objecModulo) {
            return response()->json([
                'status' => 'success',
                'message' => $message
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error al intentar registrar.'
        ]);
    }
    //create permiso
    public function createPermiso(StorePermisoRequest $storePermiso)
    {
        try {
            $modulo_id = request()->filled('modulo_id') ? Crypt::decrypt(request()->input('modulo_id')) : 0;
            $permiso_id = request()->filled('permiso_id') ? Crypt::decrypt(request()->input('permiso_id')) : 0;
        } catch (Exception $e) {
            $modulo_id = 0;
            $permiso_id = 0;
        }
        if ($modulo_id == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo asociar el permiso al módulo.'
            ]);
        }
        $usuario_id = Auth::user()->id;
        $clave = trim(strtolower($storePermiso->clave_permiso));
        $name = trim(strtoupper($storePermiso->name_permiso));
        $descripcion = !empty($storePermiso->descripcion_permiso) ? trim($storePermiso->descripcion_permiso) : '';
        if ($permiso_id == 0) {
            if ($this->validacionExistePermiso($clave, $name, $modulo_id)) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'El módulo que intentas registrar ya existe.'
                ]);
            }
            $objecModulo = ModuloAccion::create([
                'clave' => $clave,
                'nombre' => $name,
                'descripcion' => $descripcion,
                'estado' => 'Activo',
                'modulo_id' => $modulo_id,
                'usuario_id' => $usuario_id,
            ]);
            $message = 'Se ha registro el permiso.';
        } else {
            $objecModulo = $this->updatePermiso($clave, $name, $descripcion, $permiso_id);
            $message = 'El permiso se ha actualizado con éxito.';
        }

        if ($objecModulo) {
            return response()->json([
                'status' => 'success',
                'message' => $message
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error al intentar registrar.'
        ]);
    }

    protected function updateModulo(string $clave, string $nombre, string $descripcion, int $id)
    {
        return Modulo::where('id', $id)->update([
            'clave' => $clave,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
        ]);
    }
    protected function updatePermiso(string $clave, string $nombre, string $descripcion, int $id)
    {
        return ModuloAccion::where('id', $id)->update([
            'clave' => $clave,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
        ]);
    }

    protected function validacionExisteModulo(string $clave, string $name)
    {
        return Modulo::where('clave', $clave)->orWhere('nombre', $name)->exists();
    }
    protected function validacionExistePermiso(string $clave, string $name, int $modulo_id)
    {
        return ModuloAccion::where('clave', $clave)->where('modulo_id', $modulo_id)->orWhere('nombre', $name)->exists();
    }

    public function listarModulos()
    {
        $array_modulos = Modulo::orderBy('id', 'desc')->get();
        $contador = 1;
        $data = [];
        foreach ($array_modulos as $row) {
            //detalle de items
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = $row['clave'];
            $sub_array[] = $row['nombre'];
            $sub_array[] = $row['descripcion'];
            $sub_array[] = $row['estado'];
            $sub_array[] = '
            <span title="Crear permiso" onclick="crearPermiso(this)" data-id="' . encrypt($row['id']) . '" data-datos_encoded="' . base64_encode(json_encode($row)) . '" class="text-success" style="font-size: 18px;cursor:pointer;margin-right: 6px;"><i class="bi bi-shield-plus"></i></span>

            <span title="Editar modulo" onclick="editarModulo(this)" data-id="' . encrypt($row['id']) . '" data-datos_encoded="' . base64_encode(json_encode($row)) . '" class="text-info" style="font-size: 18px;cursor:pointer;margin-right: 6px;"><i class="bi bi-pencil-square"></i></span>

            <span title="Remover modulo" onclick="deleteModulo(this)" data-id="' . encrypt($row['id']) . '" class="text-danger" style="font-size: 18px;cursor:pointer;margin-right: 6px;"><i class="bi bi-trash3"></i></span>';

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
            ->header('Content-Type', 'application/json');
    }

    public function listarPermisos(Request $request)
    {
        $modulo_id = Crypt::decrypt($request->modulo_id);
        $array_permisos = ModuloAccion::where('modulo_id', $modulo_id)->orderBy('id', 'desc')->get();
        $contador = 1;
        $data = [];
        foreach ($array_permisos as $row) {
            //detalle de items
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = $row['clave'];
            $sub_array[] = $row['nombre'];
            $sub_array[] = $row['descripcion'];
            $sub_array[] = $row['estado'];
            $sub_array[] = '
            <span title="Editar permiso" onclick="editarPermiso(this)" data-id="' . encrypt($row['id']) . '" data-datos_encoded="' . base64_encode(json_encode($row)) . '" class="text-info" style="font-size: 18px;cursor:pointer;margin-right: 6px;"><i class="bi bi-pencil-square"></i></span>

            <span title="Remover permiso" onclick="deletePermiso(this)" data-id="' . encrypt($row['id']) . '" class="text-danger" style="font-size: 18px;cursor:pointer;margin-right: 6px;"><i class="bi bi-trash3"></i></span>';

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
            ->header('Content-Type', 'application/json');
    }

    public function deleteModulo(Request $request)
    {
        try {
            $modulo_id = Crypt::decrypt($request->modulo_id);
        } catch (Exception $e) {
            $modulo_id = 0;
        }

        $arrayModulo = Modulo::where('id', $modulo_id)->first();
        if ($arrayModulo) {
            //validacion si tiene permisos
            $boolValidacion = ModuloAccion::where('modulo_id', $modulo_id)->exists();
            if ($boolValidacion) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'El módulo ya tiene permisos asignados.'
                ]);
            }
            $arrayModulo->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'El módulo se ha eliminado con éxito.'
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error al eliminar el módulo.'
        ]);
    }

    public function deletePermiso(Request $request)
    {
        try {
            $permiso_id = Crypt::decrypt($request->permiso_id);
        } catch (Exception $e) {
            $permiso_id = 0;
        }

        $arrayPermiso = ModuloAccion::where('id', $permiso_id)->first();
        if ($arrayPermiso) {
            $arrayPermiso->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'El permiso se ha eliminado con éxito.'
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error al eliminar el permiso.'
        ]);
    }

    public function getModulosPermisos(Request $request){
        try{
            $cuenta_id = Crypt::decrypt($request->cuenta_id);
        }catch(Exception $e){
            $cuenta_id = 0;
        }
        try{
            $usuario_id = Crypt::decrypt($request->usuario_id);
        }catch(Exception $e){
            $usuario_id = 0;
        }

        $datosModulos = Modulo::where('estado', 'Activo')
            ->with(['modulo_accions' => function ($q) {
                $q->orderBy('id', 'desc');
            }])
            ->orderBy('id', 'desc')
            ->get();
        $moduloIds = $datosModulos->pluck('id')->toArray();
        $accionIds = $datosModulos->flatMap->modulo_accions->pluck('id')->toArray();

        $modulosAsignados = ModuloCuenta::whereIn('modulo_id', $moduloIds)
            ->where('cuenta_id', $cuenta_id)
            ->pluck('modulo_id')
            ->toArray();

        $permisosAsignados = PermisoUsuario::whereIn('modulo_accion_id', $accionIds)
            ->where('usuario_id', $usuario_id)
            ->pluck('modulo_accion_id')
            ->toArray();

        // Construcción final del array
        $modulosArray = $datosModulos->map(function ($modulo) use ($modulosAsignados, $permisosAsignados) {
            $permisos = $modulo->modulo_accions->map(function ($accion) use ($permisosAsignados) {
                $accion->asignadoPermiso = in_array($accion->id, $permisosAsignados);
                return $accion;
            });

            return [
                'id' => encrypt($modulo->id),
                'nombre' => $modulo->nombre,
                'descripcion' => $modulo->descripcion,
                'asignadoModulo' => in_array($modulo->id, $modulosAsignados),
                'permisos' => $permisos
            ];
        })->toArray();
        return response()->json($modulosArray);
    }

    public function getModulosPermisoCuenta(Request $request){
        try{
            $usuario_id = Crypt::decrypt($request->usuario_id);
        }catch(Exception $e){
            $usuario_id = 0;
        }

        $datosModulos = Modulo::where('estado', 'Activo')
            ->with(['modulo_accions' => function ($q) {
                $q->orderBy('id', 'desc');
            }])
            ->orderBy('id', 'desc')
            ->get();
        $accionIds = $datosModulos->flatMap->modulo_accions->pluck('id')->toArray();

        $permisosAsignados = PermisoUsuario::whereIn('modulo_accion_id', $accionIds)
            ->where('usuario_id', $usuario_id)
            ->pluck('modulo_accion_id')
            ->toArray();
        // Construcción final del array
        $modulosArray = $datosModulos->map(function ($modulo) use ($permisosAsignados) {
            $permisos = $modulo->modulo_accions->map(function ($accion) use ($permisosAsignados) {
                $accion->permiso_id = encrypt($accion->id);
                $accion->asignadoPermiso = in_array($accion->id, $permisosAsignados);
                return $accion;
            });

            return [
                'id' => encrypt($modulo->id),
                'nombre' => $modulo->nombre,
                'descripcion' => $modulo->descripcion,
                'permisos' => $permisos
            ];
        })->toArray();
        return response()->json($modulosArray);
    }
}
