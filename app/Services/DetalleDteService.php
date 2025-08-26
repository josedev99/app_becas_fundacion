<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DetalleDteService
{
    public function __construct(
        private DteCodigoToTexto $dteCodigoToTexto
    ){}
    public function getDetalle(string $codigoGeneracion, string $tipoDte, int $empresa_id)
    {
        $cuenta_id = Auth::user()->cuenta_id;
        $documentoDte = DB::table('documento_dtes')
            ->where('codigoGeneracion', $codigoGeneracion)
            ->where('tipo_dte', $tipoDte)
            ->where('empresa_id', $empresa_id)
            ->where('cuenta_id', $cuenta_id)
            ->first();
        if($documentoDte){
            $dte_json = json_decode($documentoDte->dte_json, true);
            
            if(!empty($dte_json)){
                $totalPagar = $dte_json['resumen']['totalPagar'];
            }else{
                $totalPagar = 0.00;
            }

            $documentoDte->tipo_documento_str = $this->dteCodigoToTexto->getTipoDocumento($documentoDte->tipo_dte);
            $documentoDte->totalPagar = $totalPagar;
            $documentoDte->fecha_gen = date('d-m-Y',strtotime($documentoDte->fecha_gen));

            $items_doc = $dte_json['cuerpoDocumento'] ?? [];
            $cuerpoDocumento = [];
            foreach($items_doc as $item){
                $iva = ((float)$item['precioUni']) * 0.13;
                $precioUnitIva = ((float)$item['precioUni']) + $iva;
                $cuerpoDocumento[] = [
                    'codigo' => $item['codigo'],
                    'cantidad' => (int)$item['cantidad'],
                    'descripcion' => $item['descripcion'],
                    'precio_unit' => number_format($precioUnitIva,4,'.',''),
                    'descuento' => number_format($item['montoDescu'],3,'.',''),
                    'numeroDocumento' => (string)$documentoDte->codigoGeneracion,
                    'tipoItem' => $item['tipoItem']
                ];
            }
            $documentoDte->cuerpoDocumento = $cuerpoDocumento;
        }

        return [
            'status' => $documentoDte ? 'success' : 'error',
            'result' => $documentoDte
        ];
    }

    public function getClienteDte(string $nrc, int $empresa_id){
        return DB::table('clientes as c')
            ->where('c.nrc', $nrc)
            ->where('c.empresa_id', $empresa_id)
            ->orderBy('id','desc')
            ->first();
    }
}