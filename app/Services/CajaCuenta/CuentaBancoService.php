<?php
namespace App\Services\CajaCuenta;

use App\Models\CuentaBanco;
use Illuminate\Support\Facades\Auth;

class CuentaBancoService{
    public function getCuentaBancoAll(int $empresa_id){
        $cuenta_id = Auth::user()->cuenta_id;
        return CuentaBanco::where('empresa_id', $empresa_id)->where('cuenta_id', $cuenta_id)->orderBy('id','desc')->get();
    }
}