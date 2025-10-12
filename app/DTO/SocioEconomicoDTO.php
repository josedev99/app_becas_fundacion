<?php

namespace App\DTO;

class SocioEconomicoDTO
{
    public function __construct(
        public readonly string $situacion_familiar,
        public readonly float $ingresos,
        public readonly int $cantidad_personas,
        public readonly string $necesidades, // puede ser null si no se especifica
        public readonly string $comunidad,   // puede ser null si no aplica
        public readonly int $estudiante_id,
        public readonly int $user_id,
    ) {}
}