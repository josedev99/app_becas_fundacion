<?php
namespace App\Services\Contabilidad;

use App\Models\catalogoCuenta\CatalogoCuenta;
use App\Models\catalogoCuenta\SubCuentas;
use App\Models\catalogoCuenta\SubsubCuentas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PermissionHelper;

class CatalogoService{
    protected $clasificacionCuentas;
    public function __construct()
    {
        $this->clasificacionCuentas = [
            1 => 'Clasificación General',
            2 => 'Rubros de Agrupación',
            4 => 'Cuenta de Mayor',
            6 => 'Sub Cuenta',
            8 => 'Cuenta de Detalle',
            10 => 'Cuenta Analítica',
        ];
    }

    protected function getClasificacionCuentas($codigo_contable){
        if(isset($this->clasificacionCuentas[strlen($codigo_contable)])){
            return $this->clasificacionCuentas[strlen($codigo_contable)];
        }
        return $this->clasificacionCuentas[10];
    }
    public function createCuentaNivelUno(string $codigo, string $nombre,?int $id = 0){
        $userId = Auth::user()->id;
        $cuentaId = Auth::user()->cuenta_id;
        $empresaId = Auth::user()->empresa_id;
        $datos = [
            'codigo_catalogo' => $codigo,
            'nombre' => $nombre,
        ];
        //Save cuenta
        if($id){
            $objectCatalogo = CatalogoCuenta::where('id', $id)->where('empresa_id', $empresaId)->where('cuenta_id', $cuentaId)->update($datos);
            $message = 'Se ha actualizado el elemento del catálogo.';
        }else{
            $objectCatalogo = CatalogoCuenta::create(array_merge($datos,[
                'estado' => 'Activo',
                'tipo' => $this->getClasificacionCuentas($codigo),
                'nivel' => 1,
                'empresa_id' => $empresaId,
                'cuenta_id' => $cuentaId,
                'usuario_id' => $userId
            ]));
            $message = 'Se ha creado la cuenta.';
        }
        
        if($objectCatalogo){
            return [
                'status' => 'success',
                'message' => $message,
                'result' => $objectCatalogo
            ];
        }
        return [
            'status' => 'error',
            'message' => 'Ha ocurrido un error al crear la cuenta.',
            'result' => []
        ];
    }

    public function createCuentaNivelDos(string $codigo, string $nombre, int $catalogo_cuenta_id,?int $id = 0){
        $userId = Auth::user()->id;
        $cuentaId = Auth::user()->cuenta_id;
        $empresaId = Auth::user()->empresa_id;
        $datos = [
            'codigo_catalogo' => $codigo,
            'nombre' => $nombre,
            'catalogo_cuenta_id' => $catalogo_cuenta_id,
        ];
        if($id){
            $objectCatalogo = SubCuentas::where('id', $id)->where('empresa_id', $empresaId)->where('cuenta_id', $cuentaId)->update($datos);
            $message = 'Se ha actualizado la subcuenta.';
        }else{
            //Save cuenta
            $objectCatalogo = SubCuentas::create(array_merge($datos, [
                'estado' => 'Activo',
                'tipo' => $this->getClasificacionCuentas($codigo),
                'nivel' => 2,
                'empresa_id' => $empresaId,
                'cuenta_id' => $cuentaId,
                'usuario_id' => $userId,
            ]));
            $message = 'Se ha creado la subcuenta.';
        }
        if($objectCatalogo){
            return [
                'status' => 'success',
                'message' => $message
            ];
        }
        return [
            'status' => 'error',
            'message' => 'Ha ocurrido un error al crear la subcuenta.'
        ];
    }

    public function createCuentaNivelTres(string $codigo, string $nombre, int $catalogo_cuenta_id, $sub_subcuenta_id = 0, $nivel = 3){
        $userId = Auth::user()->id;
        $cuentaId = Auth::user()->cuenta_id;
        $empresaId = Auth::user()->empresa_id;
        if(SubsubCuentas::where('codigo_catalogo', $codigo)->where('empresa_id', $empresaId)->exists()){
            return [
                'status' => 'warning',
                'message' => 'El código catálogo de la subsubcuenta ya está registrado.'
            ];
        }
        //Save cuenta
        $objectCatalogo = SubsubCuentas::create([
            'codigo_catalogo' => $codigo,
            'nombre' => $nombre,
            'estado' => 'Activo',
            'tipo' => $this->getClasificacionCuentas($codigo),
            'nivel' => $nivel,
            'sub_cuenta_id' => $catalogo_cuenta_id,
            'sub_subcuenta_padre_id' => $sub_subcuenta_id,
            'empresa_id' => $empresaId,
            'cuenta_id' => $cuentaId,
            'usuario_id' => $userId,
        ]);
        if($objectCatalogo){
            return [
                'status' => 'success',
                'message' => 'Se ha creado la subsubcuenta.'
            ];
        }
        return [
            'status' => 'error',
            'message' => 'Ha ocurrido un error al crear la subsubcuenta.'
        ];
    }

    public function getElementosCatalogo(){
        //user auth
        if(!Auth::check()){
            return redirect()->route('app.login.index');
        }
        $cuentaId = Auth::user()->cuenta_id;
        $empresaId = Auth::user()->empresa_id;
        $arrayCuentas = CatalogoCuenta::where('estado','Activo')->where('empresa_id',$empresaId)->where('cuenta_id', $cuentaId)->orderBy('codigo_catalogo','desc')->get();

        $contador = 1;
        $data = [];
        foreach ($arrayCuentas as $row) {
            $buttons = '<button onclick="editElementCatalogo(this)" data-id="'. encrypt($row->id) .'" data-nombre="'. $row->nombre .'" data-codigo="'. $row->codigo_catalogo .'" data-nivel="'. $row->nivel .'" title="Agregar subcuenta" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-pencil-square"></i></button>';
            if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasPermissionUser('delete_catalogo_cuentas'))){
                $buttons .= '<button onclick="deleteElementCatalogo(this)" data-id="'. encrypt($row->id) .'" data-nombre="'. $row->nombre .'" data-codigo="'. $row->codigo_catalogo .'" data-nivel="'. $row->nivel .'" title="Agregar subcuenta" class="btn btn-outline-danger btn-sm" style="border:none;font-size:18px"><i class="bi bi-x-circle"></i></button>';
            }
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = date('d/m/Y H:i:s A',\strtotime($row->created_at));
            $sub_array[] = $row->codigo_catalogo;
            $sub_array[] = $row->nombre;
            $sub_array[] = $row->tipo;
            $sub_array[] = $buttons;

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

    public function getRubrosCatalogo(){
        //user auth
        if(!Auth::check()){
            return redirect()->route('app.login.index');
        }
        $cuentaId = Auth::user()->cuenta_id;
        $empresaId = Auth::user()->empresa_id;
        $arrayCuentas = DB::table('subcuentas as sc')
            ->join('catalogo_cuentas as c','sc.catalogo_cuenta_id','=','c.id')
            ->where('sc.empresa_id', $empresaId)
            ->where('sc.cuenta_id', $cuentaId)
            ->select(['sc.*', 'c.nombre as title'])
            ->orderByRaw('sc.codigo_catalogo DESC')
            ->get();

        $contador = 1;
        $data = [];
        foreach ($arrayCuentas as $row) {
            $buttons = '<button onclick="editRubroCatalogo(this)" data-id="'. encrypt($row->id) .'" data-catalogo_cuenta_id="' . $row->catalogo_cuenta_id . '" data-nombre="'. $row->nombre .'" data-codigo="'.         $row->codigo_catalogo .'" data-nivel="'. $row->nivel .'" title="Agregar subcuenta" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-pencil-square"></i></button>';

            if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasPermissionUser('delete_catalogo_cuentas'))){
                $buttons .= '<button onclick="deleteElementCatalogo(this)" data-id="'. encrypt($row->id) .'" data-nombre="'. $row->nombre .'" data-codigo="'. $row->codigo_catalogo .'" data-nivel="'. $row->nivel .'" title="Agregar subcuenta" class="btn btn-outline-danger btn-sm" style="border:none;font-size:18px"><i class="bi bi-x-circle"></i></button>';
            }
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = date('d/m/Y H:i:s A',\strtotime($row->created_at));
            $sub_array[] = $row->codigo_catalogo;
            $sub_array[] = $row->title;
            $sub_array[] = $row->nombre;
            $sub_array[] = $row->tipo;
            $sub_array[] = $buttons;

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

    public function getCuentasCatalogo(){
        //user auth
        if(!Auth::check()){
            return redirect()->route('app.login.index');
        }
        $arrayCuentas = $this->getCatalogoCuentas();

        $contador = 1;
        $data = [];
        foreach ($arrayCuentas as $row) {
            $buttons = '';
            if(\strlen($row->codigo_catalogo) > 2){
                $buttons .= '
                <button onclick="addMultiNivelCuenta(this)" data-rubro_id="'. encrypt($row->sub_cuenta_id) .'" data-subcuenta_id="'. encrypt($row->id) .'" data-nombre="'. $row->nombre .'" data-codigo="'. $row->codigo_catalogo .'" data-nivel="'. $row->nivel .'" title="Agregar subcuenta" class="btn btn-outline-info btn-sm" style="border:none;font-size:18px"><i class="bi bi-plus-square"></i></button>
                ';
            }
            //permision delete item catalogo
            if(Auth::check() && (Auth::user()->categoria== "SuperAdmin" || PermissionHelper::hasPermissionUser('delete_catalogo_cuentas'))){
                $buttons .= '<button onclick="deleteElementCatalogo(this)" data-id="'. encrypt($row->id) .'" data-nombre="'. $row->nombre .'" data-codigo="'. $row->codigo_catalogo .'" data-nivel="'. $row->nivel .'" title="Agregar subcuenta" class="btn btn-outline-danger btn-sm" style="border:none;font-size:18px"><i class="bi bi-x-circle"></i></button>';
            }
            if(\in_array(strlen($row->codigo_catalogo), [1, 2, 4])){
                $codigo_catalogo = "<b>" . $row->codigo_catalogo . "</b>";
                $nombre = "<b>" . $row->nombre . "</b>";
            }else{
                $codigo_catalogo = $row->codigo_catalogo;
                $nombre = $row->nombre;
            }
            if(strlen($row->codigo_catalogo) >= 6){
                $nombre = ucfirst(\strtolower($nombre));
            }
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = date('d/m/Y H:i:s A',\strtotime($row->created_at));
            $sub_array[] = $codigo_catalogo;
            $sub_array[] = $nombre;
            $sub_array[] = $row->tipo;
            $sub_array[] = $buttons;

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

    function getCatalogoCuentas()
    {
        $empresaId = Auth::user()->empresa_id;
        $cuentaId = Auth::user()->cuenta_id;

        $cuentas = CatalogoCuenta::where('empresa_id', $empresaId)
            ->where('cuenta_id', $cuentaId)
            ->orderBy('codigo_catalogo', 'ASC')
            ->get();

        $arrayCatalogoCuentas = [];

        foreach ($cuentas as $row) {
            $arrayCatalogoCuentas[] = $row;

            $sub_cuenta = SubCuentas::where('catalogo_cuenta_id', $row->id)
                ->where('empresa_id', $empresaId)
                ->orderBy('codigo_catalogo', 'ASC')
                ->get();

            foreach ($sub_cuenta as $sub) {
                $arrayCatalogoCuentas[] = $sub;
                $todasLasSubsub = $this->obtenerSubsubcuentasJerarquicas($sub->id, $empresaId);
                
                foreach ($todasLasSubsub as $subsub) {
                    $arrayCatalogoCuentas[] = $subsub;
                }
            }
        }
        return $arrayCatalogoCuentas;
    }

    private function obtenerSubsubcuentasJerarquicas($subCuentaId, $empresaId) {
        $resultado = [];
        $raices = SubsubCuentas::where('sub_cuenta_id', $subCuentaId)
            ->where('sub_subcuenta_padre_id', 0)
            ->where('empresa_id', $empresaId)
            ->orderBy('codigo_catalogo', 'ASC')
            ->get();
        
        foreach ($raices as $raiz) {
            $resultado[] = $raiz;
            $this->agregarHijasRecursivo($raiz->id, $empresaId, $resultado);
        }
        return $resultado;
    }

    private function agregarHijasRecursivo($padreId, $empresaId, &$resultado) {
        $hijas = SubsubCuentas::where('sub_subcuenta_padre_id', $padreId)
            ->where('empresa_id', $empresaId)
            ->orderBy('codigo_catalogo', 'ASC')
            ->get();
        
        foreach ($hijas as $hija) {
            $resultado[] = $hija;
            $this->agregarHijasRecursivo($hija->id, $empresaId, $resultado);
        }
    }

    public function deleteElemento(int $id, int $nivel){
        $empresaId = Auth::user()->empresa_id;
        $cuentaId = Auth::user()->cuenta_id;
        $statusDelete = false;
        if(is_null($nivel)){
            return [
                'status' => 'error',
                'message' => 'No se pudo eliminar el elemento del catálogo. Inténtelo nuevamente.'
            ];
        }
        if($nivel == 1){
            $catalogo = CatalogoCuenta::where('id', $id)->where('empresa_id', $empresaId)->where('cuenta_id', $cuentaId)->first();
            if(!$catalogo){
                return [
                    'status'  => 'error',
                    'message' => 'El catálogo no existe.'
                ];
            }
             // Validar si tiene subcuentas relacionadas
            if ($catalogo->subcuentas()->exists()) {
                return [
                    'status'  => 'error',
                    'message' => 'No se puede eliminar el catálogo porque existen subcuentas relacionadas.'
                ];
            }
            $statusDelete = $catalogo->delete();

        }else if($nivel == 2){
            $subCuentas = SubCuentas::where('id', $id)->where('empresa_id', $empresaId)->where('cuenta_id', $cuentaId)->first();
            if(!$subCuentas){
                return [
                    'status'  => 'error',
                    'message' => 'La subcuenta no existe en el catálogo.'
                ];
            }
            if ($subCuentas->subsubcuentas()->exists()) {
                return [
                    'status'  => 'error',
                    'message' => 'No se puede eliminar el catálogo porque existen subsubcuentas relacionadas.'
                ];
            }
            $statusDelete = $subCuentas->delete();
        }else {
            $subSubCuenta = SubsubCuentas::where('id', $id)->where('empresa_id', $empresaId)->where('cuenta_id', $cuentaId)->first();

            if(!$subSubCuenta){
                return [
                    'status'  => 'error',
                    'message' => 'La sub-subcuenta no existe.'
                ];
            }

            if ($subSubCuenta->cuentasMasNivel()->exists()) {
                return [
                    'status'  => 'error',
                    'message' => 'No se puede eliminar porque existen sub-subcuentas dependientes.'
                ];
            }

            $statusDelete = $subSubCuenta->delete();
        }
        if ($statusDelete) {
            return [
                'status' => 'success',
                'message' => 'El elemento del catálogo se eliminó correctamente.'
            ];
        }
        return [
            'status' => 'error',
            'message' => 'No se pudo eliminar el elemento del catálogo. Inténtelo nuevamente.'
        ];
    }
}