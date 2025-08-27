<?php
namespace App\Services\CajaCuenta;

use App\DTO\cuenta\CajaChicaDTO;
use App\DTO\cuenta\MovimientoCajaChicaDTO;
use App\Models\AccionesCajaChica;
use App\Models\CajaChica;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CajaChicaService{
    public function createCajaChica(CajaChicaDTO $datos){
        DB::beginTransaction();
        try{
            $cuenta_id = Auth::user()->cuenta_id;
            $usuario_id = Auth::user()->id;
            if($this->validateExistsCajaChica($datos->nombre, $datos->codigo_contable, $datos->empresa_id, $cuenta_id)){
                return [
                    'status' => 'error',
                    'message' => 'Ya existe una caja chica con este nombre o codigo contable.'
                ];
            }
            $arrayCajaChica = CajaChica::create([
                'nombre' => $datos->nombre,
                'responsable' => $datos->responsable,
                'saldo' => $datos->saldo,
                'codigo_contable' => $datos->codigo_contable,
                'estado' => $datos->estado,
                'empresa_id' => $datos->empresa_id,
                'cuenta_id' => $cuenta_id,
                'usuario_id' => $usuario_id,
            ]);
            if($arrayCajaChica){
                if($datos->saldo > 0){
                    $obje_mov_caja_chica = new MovimientoCajaChicaDTO(
                        date('Y-m-d'),
                        date('H:i:s'),
                        "APERTURA DE CAJA",
                        'ingreso',
                        "01",
                        'Ingreso a caja chica',
                        $datos->saldo,
                        0,
                        'Activo',
                        $arrayCajaChica->id,
                        $datos->empresa_id,
                        $cuenta_id,
                        $usuario_id
                    );
                    $this->saveAccionesCajaChica($obje_mov_caja_chica);
                }
                DB::commit();
                return [
                    'status' => 'success',
                    'message' => 'Se ha registrado una caja chica',
                    'result' => $arrayCajaChica,
                ];
            }
            return [
                'status' => 'warning',
                'message' => 'Ha ocurrido un error al crear la caja chica',
                'result' => $arrayCajaChica,
            ];
        }catch(Exception $err){
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => 'Ha ocurrido un error inesperado.',
                'message_error' => $err->getMessage()
            ];
        }
    }

    public function validateExistsCajaChica(string $nombre, string $codigo_contable, int $empresa_id, int $cuenta_id)
    {
        $query = CajaChica::where('nombre', $nombre)
            ->where('empresa_id', $empresa_id)
            ->where('cuenta_id', $cuenta_id);

        if (!empty($codigo_contable)) {
            $query->where('codigo_contable', $codigo_contable);
        }
        return $query->first();
    }

    public function listarCajasChicas(int $empresa_id){
        $cuenta_id = Auth::user()->cuenta_id;
        //$sucursal_id = Auth::user()->sucursal_id;
        $collection_cajas = DB::select("SELECT cc.id, cc.nombre, cc.responsable, cc.saldo, cc.codigo_contable, cc.estado, e.nombre as empresa, cc.created_at, cc.updated_at FROM `caja_chicas` AS cc INNER JOIN empresas AS e ON cc.empresa_id=e.id AND cc.cuenta_id=e.cuenta_id WHERE cc.empresa_id = ? AND cc.cuenta_id = ?", [$empresa_id,$cuenta_id]);

        $data = array();
        $contador = 1;
        foreach ($collection_cajas as $item) {
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = date('d-m-Y H:i:s', strtotime($item->created_at));
            $sub_array[] = date('d-m-Y H:i:s', strtotime($item->updated_at));

            $sub_array[] = $item->empresa;
            $sub_array[] = $item->nombre;
            $sub_array[] = $item->responsable;
            $sub_array[] = $item->codigo_contable;
            $sub_array[] = "<b>$" . number_format($item->saldo,2,'.',',') . "</b>";
            $sub_array[] = '
            <i onclick="abonarCaja(this)" data-id="' . encrypt($item->id) . '" data-nombre="' . $item->nombre . '" class="fa-solid fa-hand-holding-dollar" style="cursor: pointer; font-size: 20px; color: green;" title="Depositar a cuenta de caja"></i>
            <i onclick="showHistorial(this)" data-id="' . encrypt($item->id) . '" data-nombre="' . $item->nombre . '" class="fa-solid fa-money-check-dollar text-info" style="cursor: pointer; font-size: 20px;" title="Historial de ingresos y egresos"></i>';
            $data[] = $sub_array;
            $contador ++;
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        return $results;
    }

    public function saveIngresoCaja(string $concepto, string $forma_ingreso, float $monto_de_deposito, int $id){
        DB::beginTransaction();
        try{
            $cuenta_id = Auth::user()->cuenta_id;
            $usuario_id = Auth::user()->id;
            $cajaChica = CajaChica::where('id', $id)->where('cuenta_id', $cuenta_id)->first();
            $boolStatus = false;
            if($cajaChica){
                $boolResult = $this->updateSaldoCaja($monto_de_deposito, 'ingreso', $id);
                if($boolResult){
                    $obje_mov_caja_chica = new MovimientoCajaChicaDTO(
                        date('Y-m-d'),
                        date('H:i:s'),
                        $concepto,
                        'ingreso',
                        $forma_ingreso,
                        'Ingreso a caja chica',
                        $monto_de_deposito,
                        $cajaChica->saldo,
                        'Activo',
                        $cajaChica->id,
                        $cajaChica->empresa_id,
                        $cuenta_id,
                        $usuario_id
                    );
                    $this->saveAccionesCajaChica($obje_mov_caja_chica);
                    $boolStatus = true;
                }else{
                    $boolStatus = false;
                }
            }
            if($boolStatus){
                DB::commit();
            }
            return [
                'status' => $boolStatus ? 'success' : 'error',
                'message' => $boolStatus
                    ? 'El saldo fue ingresado correctamente en la caja chica.'
                    : 'Ocurrió un error al registrar el ingreso de saldo en la caja chica. Por favor, intente nuevamente.',
            ];
        }catch(Exception $err){
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => 'Ha ocurrido un error: código error: 500',
                'message_error' => $err->getMessage()
            ];
        }
    }

    public function updateSaldoCaja(float $monto_de_deposito, string $tipo, int $id){
        $cuenta_id = Auth::user()->cuenta_id;
        if($tipo == "ingreso"){
            return CajaChica::where('id', $id)->where('cuenta_id', $cuenta_id)->increment('saldo', $monto_de_deposito);
        }else if($tipo == "egreso"){
            return CajaChica::where('id', $id)->where('cuenta_id', $cuenta_id)->increment('saldo', $monto_de_deposito);
        }else{
            return false;
        }
    }

    public function listar_movimientos_caja(int $id){
        $cuenta_id = Auth::user()->cuenta_id;
        $collection_cajas = DB::select("SELECT acc.fecha, acc.hora, cc.nombre, acc.concepto, acc.accion, acc.abono as monto, acc.saldo_actual FROM `caja_chicas` AS cc INNER JOIN acciones_caja_chicas AS acc ON cc.id=acc.caja_chica_id and cc.empresa_id=acc.empresa_id where cc.id = ? AND cc.cuenta_id = ?;", [$id,$cuenta_id]);

        $data = array();
        $contador = 1;
        foreach ($collection_cajas as $item) {
            //validacion de segun tipo de accion
            $ingreso = 0;
            $egreso = 0;
            $saldo_item = 0;
            if($item->accion == "ingreso"){
                $ingreso = $item->monto;
                $saldo_item = $item->monto + $item->saldo_actual;
            }else if($item->accion == "egreso"){
                $egreso = $item->monto;
                $saldo_item = $item->saldo_actual;
            }
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = date('d/m/Y H:i:s', strtotime($item->fecha . ' ' . $item->hora));
            $sub_array[] = $item->nombre;
            $sub_array[] = $item->concepto;
            $sub_array[] = "<b>$" . number_format($ingreso,2,'.',',') . "</b>";
            $sub_array[] = "<b>$" . number_format($egreso,2,'.',',') . "</b>";
            $sub_array[] = "<b>$" . number_format($saldo_item,2,'.',',') . "</b>";
            $data[] = $sub_array;
            $contador ++;
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        return $results;
    }

    public function getCajaChicasAll(int $empresa_id){
        $cuenta_id = Auth::user()->cuenta_id;
        return CajaChica::where('empresa_id', $empresa_id)->where('cuenta_id', $cuenta_id)->orderBy('id','desc')->get();
    }

    public function saveAccionesCajaChica(MovimientoCajaChicaDTO $datos){
        return AccionesCajaChica::create([
            'fecha' => $datos->fecha,
            'hora' => $datos->hora,
            'concepto' => $datos->concepto,
            'accion' => $datos->accion,
            'forma_ing_egre' => $datos->forma_ing_egre,
            'observaciones' => $datos->observaciones,
            'abono' => $datos->abono,
            'saldo_actual' => $datos->saldo_actual,
            'estado' => $datos->estado,
            'caja_chica_id' => $datos->caja_chica_id,
            'empresa_id' => $datos->empresa_id,
            'cuenta_id' => $datos->cuenta_id,
            'usuario_id' => $datos->usuario_id,
        ]);
    }
}
