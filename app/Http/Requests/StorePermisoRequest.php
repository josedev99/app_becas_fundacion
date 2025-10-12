<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class StorePermisoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() ? true : false;
    }
    public function rules()
    {
        return [
            'clave_permiso' => [
                'required',
                'string',
                'max:40',
                'alpha_dash', // Solo letras, números, guiones y guion bajo
                Rule::unique('modulo_accions', 'clave')
                    /* ->where(function ($query) {
                        return $query->where('modulo_id', $this->modulo_id);
                    }) */
                    ->ignore($this->permiso_id),
            ],
            'name_permiso' => [
                'required',
                'string',
                'max:75',
            ],
            'descripcion_permiso' => [
                'nullable',
                'string',
                'max:150',
            ],
        ];
    }

    protected function prepareForValidation()
    {
        if($this->permiso_id){
            $this->merge([
                'permiso_id' => Crypt::decrypt($this->permiso_id)
            ]);
        }
    }

    public function messages()
    {
        return [
            'clave_permiso.required' => 'La clave del permiso es obligatoria.',
            'clave_permiso.max' => 'La clave no puede superar los 25 caracteres.',
            'clave_permiso.alpha_dash' => 'La clave solo puede contener letras, números, guiones y guion bajo.',
            'clave_permiso.unique' => 'Esta clave ya está registrada.',
            
            'name_permiso.required' => 'El nombre del permiso es obligatorio.',
            'name_permiso.max' => 'El nombre no puede superar los 25 caracteres.',
            
            'descripcion_permiso.max' => 'La descripción no puede superar los 50 caracteres.',
        ];
    }
}
