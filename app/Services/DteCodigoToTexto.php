<?php

namespace App\Services;

class DteCodigoToTexto
{
    public function getTipoDocumento(string $codigo): string
    {
        $tipos_documento = [
            ['codigo' => '01', 'text' => 'Factura'],
            ['codigo' => '03', 'text' => 'Comprobante de crédito fiscal'],
            ['codigo' => '04', 'text' => 'Nota de remisión'],
            ['codigo' => '05', 'text' => 'Nota de crédito'],
            ['codigo' => '06', 'text' => 'Nota de débito'],
            ['codigo' => '07', 'text' => 'Comprobante de retención'],
            ['codigo' => '08', 'text' => 'Comprobante de liquidación'],
            ['codigo' => '09', 'text' => 'Documento contable de liquidación'],
            ['codigo' => '11', 'text' => 'Facturas de exportación'],
            ['codigo' => '14', 'text' => 'Factura de sujeto excluido'],
            ['codigo' => '15', 'text' => 'Comprobante de donación'],
        ];
        foreach ($tipos_documento as $tipo) {
            if ($tipo['codigo'] === $codigo) {
                return $tipo['text'];
            }
        }
        return '';
    }
}
