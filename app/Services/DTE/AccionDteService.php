<?php
namespace App\Services\DTE;

use App\Models\AccionDocumentoDte;

class AccionDteService{
    public function saveAccion(string $accion, string $codigoGeneracion, string $json,int $usuario_id,int $empresa_id,int $cuenta_id){
        return AccionDocumentoDte::create([
            'fecha' => date('Y-m-d'),
            'hora' => date('H:i:s'),
            'accion' => $accion,
            'codigoGeneracion' => $codigoGeneracion,
            'respuesta_mh' => $json,
            'usuario_id' => $usuario_id,
            'empresa_id' => $empresa_id,
            'cuenta_id' => $cuenta_id
        ]);
    }
}