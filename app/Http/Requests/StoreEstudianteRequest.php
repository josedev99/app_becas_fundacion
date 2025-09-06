<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreEstudianteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() ? true: false;
    }

    public function rules(): array
    {
        return [
            // Datos personales (puedes ajustarlos si quieres obligatorios)
            'nombre_completo'        => [
                'required', 
                'string', 
                'max:150',
                Rule::unique('estudiantes', 'nombre_completo')
                    ->where(fn ($query) => $query->where('documento', $this->documento)),
            ],
            'documento'           => ['required', 'string', 'max:50'],
            'fecha_nacimiento'   => ['required', 'date'],
            'direccion'     => ['required', 'string', 'max:250'],
            'telefono'           => ['required', 'string', 'max:15'],
            'contacto_emergencia'=> ['required', 'string', 'max:150'],
            'email_becado'         => ['required', 'email', 'max:150'],
            'beca_id'            => ['required', 'integer'],

            // Datos académicos (OBLIGATORIOS)
            'nivel_educativo'    => ['required', 'string', 'in:Basico,Bachillerato,Universidad,Tecnico'],
            'institucion'        => ['required', 'string', 'max:150'],
            'carrera'            => ['required', 'string', 'max:150'],
            'promedio'           => ['required', 'numeric', 'between:1,10'],
            'estado_academico'   => ['required', 'string', 'in:Activo,Graduado,Retirado'],
            'fInicio_beca'       => ['required', 'date'],
            'fFin_beca'          => ['required', 'date', 'after_or_equal:fInicio_beca'],

            // Datos socioeconómicos (OPCIONALES)
            'situacion_familiar'   => ['nullable', 'string', 'in:Nuclear,Monoparental,Tutor'],
            'ingreso_aprox'        => ['nullable', 'numeric', 'min:0'],
            'numero_personas'      => ['nullable', 'integer', 'min:1'],
            'necesidades_esp'      => ['nullable', 'string', 'max:250'],
            'comunidad_residencia' => ['nullable', 'string', 'max:250'],
        ];
    }

    public function messages(): array
    {
        return [
            // Personales
            'nombre_completo.required'         => 'El nombre completo es obligatorio.',
            'nombre_completo.unique'     => 'Ya existe un registro con ese nombre y documento.',
            'documento.required'         => 'El numero de documento es obligatorio.',
            'fecha_nacimiento.required'         => 'La fecha de nacimiento es obligatorio.',
            'direccion.required'         => 'La dirección es obligatorio.',
            'telefono.required'            => 'El teléfono es obligatorio.',
            'contacto_emergencia.required' => 'El contacto de emergencia es obligatorio.',
            'email_becado.required'          => 'El email es obligatorio.',
            'email_becado.email'             => 'El email debe ser válido.',
            'beca_id.required'             => 'Debe seleccionar una beca.',

            // Académicos
            'nivel_educativo.required'     => 'Debe seleccionar el nivel educativo.',
            'institucion.required'         => 'La institución es obligatoria.',
            'carrera.required'             => 'La carrera o grado es obligatoria.',
            'promedio.required'            => 'El promedio es obligatorio.',
            'promedio.numeric'             => 'El promedio debe ser un número.',
            'promedio.between'             => 'El promedio debe estar entre 1 y 10.',
            'estado_academico.required'    => 'Debe seleccionar el estado académico.',
            'fFin_beca.after_or_equal'     => 'La fecha de finalización no puede ser menor a la fecha de inicio.',

            // Socioeconómicos
            'situacion_familiar.in'        => 'La situación familiar seleccionada no es válida.',
            'ingreso_aprox.numeric'        => 'El ingreso debe ser un número.',
            'numero_personas.integer'      => 'El número de personas debe ser un entero.',
        ];
    }
}
