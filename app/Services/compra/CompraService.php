<?php

namespace App\Services\compra;

use App\DTO\compra\CompraDTO;
use App\DTO\cuenta\MovimientoCajaChicaDTO;
use App\Models\CajaChica;
use App\Models\compras\CategoriaCompraManual;
use App\Models\compras\CompraManual;
use App\Models\compras\DetCompraManual;
use App\Models\CuentaBanco;
use App\Services\CajaCuenta\CajaChicaService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CompraService
{
    public function __construct(private CajaChicaService $cajaChicaService)
    {
        date_default_timezone_set('America/El_Salvador');
    }

    public function createCategoriaCompra(string $nombre, int $empresa_id)
    {
        $cuenta_id = Auth::user()->cuenta_id;
        $exists = CategoriaCompraManual::where('nombre', $nombre)->where('cuenta_id', $cuenta_id)->first();
        if ($exists) {
            return [
                'status' => 'error',
                'message' => 'La categoría que intentas crear ya existe.'
            ];
        }
        $arrayCreateCat = CategoriaCompraManual::create([
            'nombre' => $nombre,
            'empresa_id' => $empresa_id,
            'cuenta_id' => $cuenta_id
        ]);

        if ($arrayCreateCat) {
            $encryptedId = encrypt($arrayCreateCat->id);
            return [
                'status' => 'success',
                'message' => 'Se ha creado la categoria con exito.',
                'result' => [
                    'id' => $encryptedId,
                    'nombre' => $arrayCreateCat->nombre,
                    'empresa_id' => $arrayCreateCat->empresa_id,
                    'cuenta_id' => $arrayCreateCat->cuenta_id,
                ]
            ];
        }
        return [
            'status' => 'error',
            'message' => 'Ha ocurrido un error.',
        ];
    }

    public function createCompraManual(CompraDTO $datos)
    {
        $cuenta_id = Auth::user()->cuenta_id;
        $usuario_id = Auth::user()->id;
        if (!$cuenta_id || !$usuario_id) {
            return [
                'status'  => 'error',
                'message' => 'Usuario no autenticado.'
            ];
        }

        $hora = date('H:i:s');
        $impuestos_act = 0.00;
        $impuestosTotal = (float)$datos->impuesto;
        if (isset($impuestosTotal) && !empty($impuestosTotal) && is_numeric($impuestosTotal)) {
            $impuestos_act = floatval($impuestosTotal) / count($datos->items_compra);
        }
        $correlativo =  $cuenta_id . $datos->empresa_id . $datos->sucursal_id . '-' . $datos->numero_comprobante;
        $existeCorrelativo = CompraManual::where('numero_comprobante', $correlativo)->where('empresa_id', $datos->empresa_id)->where('cuenta_id', $cuenta_id)->exists();
        if ($existeCorrelativo) {
            [
                'status' => 'error',
                'message' => 'Correlativo ya se encuentra en uso!'
            ];
        } else {
            DB::beginTransaction();
            try {
                $compra = CompraManual::create([
                    'fecha' => $datos->fecha,
                    'hora' => $hora,
                    'tipo_comprobante' => $datos->tipo_comprobante,
                    'numero_comprobante' => $correlativo,
                    'resp_compra' => $datos->resp_compra,
                    'comercio' => $datos->comercio,
                    'observaciones' => $datos->observaciones,
                    'img_comprobante' => $datos->img_comprobante,
                    'monto' => $datos->monto,
                    'iva' => $datos->iva,
                    'impuesto' => $datos->impuesto,
                    'tipo_metodo_pago' => $datos->tipo_metodo_pago,
                    'metodo_pago_id' => $datos->metodo_pago_id,
                    'empresa_id' => $datos->empresa_id,
                    'sucursal_id' => $datos->sucursal_id,
                    'cuenta_id' => $cuenta_id,
                    'usuario_id' => $usuario_id,
                ]);
                $sumaSubtotales = 0;
                foreach ($datos->items_compra as $item) {
                    try {
                        $cantidad   = (int) $item['cantItemC'];
                        $precioUnit = (float) $item['precioItemC'];
                        $subtotal   = $cantidad * $precioUnit;

                        $categoria_id = Crypt::decrypt($item['idCategoria']);
                        $producto_item = trim($item['descItemC']);

                        DetCompraManual::create([
                            'producto'       => $producto_item,
                            'precio_unitario' => $precioUnit,
                            'cantidad'       => $cantidad,
                            'umedida'        => $item['umedida'],
                            'subtotal'       => $subtotal,
                            'iva'            => $item['ivaItem'],
                            'impuestos'      => $impuestos_act,
                            'categoria_id'   => $categoria_id,
                            'compra_id'      => $compra->id,
                            'empresa_id'     => $datos->empresa_id,
                            'sucursal_id'    => $datos->empresa_id
                        ]);

                        $sumaSubtotales += $subtotal;
                    } catch (DecryptException $e) {
                        DB::rollBack();
                        return [
                            'status'  => 'error',
                            'message' => 'Error al decifrar la categoría: ' . $e->getMessage()
                        ];
                    }
                }

                // Manejo de método de pago
                if ($datos->tipo_metodo_pago === "caja_chica") {
                    $arrayCuenta = CajaChica::where('id', $datos->metodo_pago_id)
                        ->where('empresa_id', $datos->empresa_id)
                        ->where('cuenta_id', $cuenta_id)
                        ->first();

                    if (!$arrayCuenta) {
                        DB::rollBack();
                        return [
                            'status'  => 'error',
                            'message' => 'Cuenta de caja chica no encontrada.'
                        ];
                    }
                    //validacion de saldo disponible
                    if ((float)$datos->monto > (float)$arrayCuenta->saldo) {
                        DB::rollBack();
                        return [
                            'status'  => 'error',
                            'message' => 'El monto de la compra excede el saldo disponible en la caja chica.'
                        ];
                    }

                    $arrayCuenta->saldo -= (float) $datos->monto;

                    $obje_mov_caja_chica = new MovimientoCajaChicaDTO(
                        now()->toDateString(),
                        now()->format('H:i:s'),
                        "COMPRA A: " . $datos->comercio,
                        'egreso',
                        "00",
                        'egreso de caja',
                        $datos->monto,
                        $arrayCuenta->saldo,
                        'Activo',
                        $arrayCuenta->id,
                        $datos->empresa_id,
                        $cuenta_id,
                        $usuario_id
                    );

                    $arrayCuenta->save();
                    $this->cajaChicaService->saveAccionesCajaChica($obje_mov_caja_chica);
                } elseif ($datos->tipo_metodo_pago === "transferencia") {
                    $arrayCuenta = CuentaBanco::where('id', $datos->metodo_pago_id)
                        ->where('empresa_id', $datos->empresa_id)
                        ->where('cuenta_id', $cuenta_id)
                        ->first();

                    if (!$arrayCuenta) {
                        DB::rollBack();
                        return [
                            'status'  => 'error',
                            'message' => 'Cuenta bancaria no encontrada.'
                        ];
                    }
                }
                DB::commit();
                return [
                    'status'  => 'success',
                    'message' => 'Se ha registrado una compra por el monto de: $' . number_format($sumaSubtotales, 2)
                ];
            } catch (\Exception $e) {
                DB::rollBack();
                return [
                    'status' => 'error',
                    'message' => 'Ha ocurrido un error, detalle: ' . $e->getMessage()
                ];
            }
        }
    }

    public function listarDatos(int $empresa_id)
    {
        $cuenta_id = Auth::user()->cuenta_id;
        //$sucursal_id = Auth::user()->sucursal_id;
        $collection_compras = DB::table('compra_manuals as c')
            ->join('sucursals as s', 's.id', '=', 'c.sucursal_id')
            ->select('c.*', 's.nombre_comercial')
            ->where('c.empresa_id', $empresa_id)
            ->where('c.cuenta_id', $cuenta_id)
            ->get();

        $data = array();
        $contador = 1;
        foreach ($collection_compras as $compras) {
            $sub_array = array();
            $sub_array[] = $contador;
            $sub_array[] = date('d-m-Y', strtotime($compras->fecha));

            $sub_array[] = substr($compras->numero_comprobante, strpos($compras->numero_comprobante, '-') + 1);
            $sub_array[] = $compras->tipo_comprobante;
            $sub_array[] =  ucwords($compras->comercio);
            $sub_array[] = $compras->nombre_comercial;
            $sub_array[] = "<b>$" . number_format($compras->monto, 2, '.', ',') . "</b>";
            $sub_array[] = '<i onclick="DetallesCompras(this)" data-compra_id="' . encrypt($compras->id) . '" class="bi bi-bag-check-fill" style="cursor: pointer; font-size: 22px; color: #0d7ff0;" title="Detalles" ></i>';
            $color = ($compras->img_comprobante === '') ? 'red' : 'green';
            $function = ($compras->img_comprobante === '') ? 'AgregarComprobante' : 'imagenExiste';
            $sub_array[] = '<i onclick=" ' . $function . '(' . $compras->id . ')" class="bi bi-images" style="cursor: pointer; font-size: 22px; color: ' . $color . ';"></i>'
                . " " . '<i onclick="DeleteCompras(this)" data-id="' . encrypt($compras->id) . '" class="bi bi-bag-x-fill" style="cursor: pointer; font-size: 20px; color: red;" title="Eliminar"></i>'
                . " " . '<i onclick="EditCompra(\'' . $compras->id . '\')"class="bi bi-pencil-square" style="cursor: pointer; font-size: 20px; color: green;" title="Editar"></i>';
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

    public function deleteCompraManual(int $id)
    {
        $cuenta_id = Auth::user()->cuenta_id;
        $compra = CompraManual::where('id', $id)->where('cuenta_id', $cuenta_id)->first();
        if (!$compra) {
            return [
                'status' => 'error',
                'message' => 'No se ha encontrado la compra.'
            ];
        }
        $boolDelete = DetCompraManual::where('compra_id', $compra['id'])->where('empresa_id', $compra['empresa_id'])->delete();
        if (!$boolDelete) {
            return [
                'status' => 'error',
                'message' => 'Ha ocurrido un error al eliminar la compra.'
            ];
        }

        //Reintegro de saldo
        if($compra['tipo_metodo_pago'] == "caja_chica"){
            $arrayCuenta = CajaChica::where('id', $compra['metodo_pago_id'])
                ->where('empresa_id', $compra['empresa_id'])
                ->where('cuenta_id', $cuenta_id)
                ->first();
            if ($arrayCuenta) {
                $obje_mov_caja_chica = new MovimientoCajaChicaDTO(
                    now()->toDateString(),
                    now()->format('H:i:s'),
                    "REINTEGRO POR DEVOLUCIÓN DE COMPRA EN: " . $compra['comercio'],
                    'ingreso',
                    "01",
                    'Devolución - Compra eliminada',
                    $compra['monto'],
                    $arrayCuenta->saldo,
                    'Activo',
                    $arrayCuenta->id,
                    $compra['empresa_id'],
                    $cuenta_id,
                    Auth::user()->id
                );
                $arrayCuenta->saldo += (float) $compra['monto'];
                $arrayCuenta->save();
                $this->cajaChicaService->saveAccionesCajaChica($obje_mov_caja_chica);
            }
        }
        $compra->delete();
        return [
            'status' => 'success',
            'message' => 'Se ha eliminado la compra.'
        ];
    }
    //Obtener los detalles de la compra manual
    public function getDetCompraById(int $compra_id){
        $cuenta_id = Auth::user()->cuenta_id;
        //Compra
        $arrayCompra = CompraManual::where('id', $compra_id)->where('cuenta_id', $cuenta_id)->first();
        if($arrayCompra){
            $arrayCompra->fecha_hora = date('d/m/Y H:i:s A',strtotime($arrayCompra->fecha . " " . $arrayCompra->hora));
            $arrayDetalle = DB::table('det_compra_manuals as det')
                ->join('categoria_compra_manuals as c','c.id','=','det.categoria_id')
                ->where('det.compra_id', $arrayCompra->id)
                ->where('det.empresa_id', $arrayCompra->empresa_id)
                ->select('det.*','c.nombre as categoria')
                ->get();
            $arrayCompra->detalles = $arrayDetalle;
        }
        return $arrayCompra;
    }
}
