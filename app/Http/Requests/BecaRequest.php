<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BecaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() ? true: false;
    }

   /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [
            'nombre_beca'   => ['required', 'string', 'max:150'],
            'tipo_beca'     => ['required', 'string', Rule::in(['Total', 'Parcial', 'Alimentaria', 'Transporte'])],
            'financiamiento'=> ['required', 'string', Rule::in(['Donante', 'Empresa aliada', 'Fondos internos'])],
            'plazo_monto'   => ['required', 'string', Rule::in(['Mensual', 'Anual'])],
            'forma_entrega' => ['required', 'string', Rule::in(['Transferencia', 'Efectivo', 'Insumos'])],
            'compromiso'    => ['required', 'string', Rule::in(['Horas sociales', 'Talleres', 'Rendimiento minimo'])],
            'encargado_beca'=> ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * Mensajes personalizados.
     */
    public function messages(): array
    {
        return [
            'nombre_beca.required'    => 'El nombre de la beca es obligatorio.',
            'nombre_beca.max'         => 'El nombre de la beca no puede exceder los 150 caracteres.',
            'tipo_beca.required'      => 'Seleccione el tipo de beca.',
            'tipo_beca.in'            => 'Tipo de beca inválido.',
            'financiamiento.required' => 'Seleccione el tipo de financiamiento.',
            'financiamiento.in'       => 'Financiamiento inválido.',
            'plazo_monto.required'    => 'Seleccione el plazo del monto.',
            'plazo_monto.in'          => 'Plazo de monto inválido.',
            'forma_entrega.required'  => 'Seleccione la forma de entrega.',
            'forma_entrega.in'        => 'Forma de entrega inválida.',
            'compromiso.required'     => 'Seleccione el compromiso asociado.',
            'compromiso.in'           => 'Compromiso inválido.',
            'encargado_beca.max'      => 'El nombre del encargado no puede exceder los 100 caracteres.',
        ];
    }
}
