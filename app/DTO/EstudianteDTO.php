<?php

namespace App\DTO;

class EstudianteDTO
{
    public function __construct(
        public readonly string $nombre_completo,
        public readonly string $documento,
        public readonly string $fecha_nacimiento,
        public readonly string $direccion,
        public readonly string $telefono,
        public readonly string $email,
        public readonly ?string $telefono_emergencia, // puede ser null
        public readonly int $beca_id,
        public readonly int $usuario_id,
    ) {}
}