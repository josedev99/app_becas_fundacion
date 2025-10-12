<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SeguimientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() ? true : false;
    }
    public function rules(): array
    {
        return [
            'becado_seguimiento'    => ['required','integer','exists:estudiantes,id'],
            'fecha_seguimiento'     => ['required','date','before_or_equal:today'],
            'responsable_seguimiento' => ['required','string','max:200'],

            'participacion'         => ['nullable','string','max:1000'],
            'observaciones_tutor'   => ['nullable','string','max:1000'],
            'notas_add'             => ['nullable','string','max:1000'],

            'estado_beca'           => ['required','in:Activo,Graduado,Suspendida,Retirado'],
            'prioridad_segui'       => ['required','in:Baja,Media,Alta,Urgente'],
            'fecha_proximo'         => ['nullable','date','after_or_equal:today'],
        ];
    }

    /**
     * Mensajes personalizados (opcional).
     */
    public function messages(): array
    {
        return [
            'becado_seguimiento.required' => 'El campo Becado es obligatorio.',
            'becado_seguimiento.exists'   => 'El becado seleccionado no es válido.',
            'fecha_seguimiento.required'  => 'La fecha de seguimiento es obligatoria.',
            'fecha_seguimiento.before_or_equal' => 'La fecha de seguimiento no puede ser futura.',
            'responsable_seguimiento.required' => 'El responsable es obligatorio.',
            'estado_beca.required'        => 'Debe seleccionar un estado de beca.',
            'estado_beca.in'              => 'El estado de beca seleccionado no es válido.',
            'prioridad_segui.required'    => 'Debe seleccionar una prioridad.',
            'prioridad_segui.in'          => 'La prioridad seleccionada no es válida.',
            'fecha_proximo.after_or_equal' => 'La fecha del próximo seguimiento no puede ser anterior a hoy.',
        ];
    }
}
