<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class StoreModuloRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() ? true : false;
    }
    public function rules()
    {
        return [
            'clave_modulo' => [
                'required',
                'string',
                'max:40',
                'alpha_dash', // Solo letras, números, guiones y guion bajo
                Rule::unique('modulos', 'clave')->ignore($this->modulo_id), // ignora el ID actual
            ],
            'name_modulo' => [
                'required',
                'string',
                'max:75',
            ],
            'descripcion_modulo' => [
                'nullable',
                'string',
                'max:150',
            ],
        ];
    }

    protected function prepareForValidation()
    {
        if($this->modulo_id){
            $this->merge([
                'modulo_id' => Crypt::decrypt($this->modulo_id)
            ]);
        }
    }

    public function messages()
    {
        return [
            'clave_modulo.required' => 'La clave del módulo es obligatoria.',
            'clave_modulo.max' => 'La clave no puede superar los 25 caracteres.',
            'clave_modulo.alpha_dash' => 'La clave solo puede contener letras, números, guiones y guion bajo.',
            'clave_modulo.unique' => 'Esta clave ya está registrada.',
            
            'name_modulo.required' => 'El nombre del módulo es obligatorio.',
            'name_modulo.max' => 'El nombre no puede superar los 25 caracteres.',
            
            'descripcion_modulo.max' => 'La descripción no puede superar los 50 caracteres.',
        ];
    }
}
