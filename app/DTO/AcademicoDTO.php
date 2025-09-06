<?php

namespace App\DTO;

class AcademicoDTO
{
    public function __construct(
        public readonly string $nivel_educativo,
        public readonly string $institucion,
        public readonly string $carrera_grado,
        public readonly float $promedio,
        public readonly string $estado_academico,
        public readonly string $fInicio, // si preferís, podés tiparlo como \DateTimeInterface
        public readonly string $fFin,   // puede ser null si aún no ha finalizado
        public readonly int $estudiante_id,
        public readonly int $user_id,
    ) {}
}