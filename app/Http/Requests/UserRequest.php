<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() ? true : false;
    }

    public function rules(): array
    {
        return [
            'nombre_user' => 'required|string|max:150', // Obligatorio, cadena, longitud máxima 255
            //'doc_user' => 'nullable|string|max:20',
            //'telefono_user' => 'nullable|string|max:15',
            //'direccion_user' => 'nullable|string|max:150',
            //'email_user' => 'nullable|string|max:100',
            'estado_user' => 'required|string|max:15', // Obligatorio, cadena, longitud máxima 100
            'usuario_user' => 'required|string', // Opcional, cadena, longitud máxima 100
            'clave_user' => 'required|string',
            'categoria_user' => 'required|string|max:25',
            //'cargo_user' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'nombre_user.required' => 'El nombre del usuario es obligatorio.',
            'nombre_user.string' => 'El nombre del usuario debe ser una cadena de texto.',
            'nombre_user.max' => 'El nombre del usuario no debe superar los 150 caracteres.',
    
            'doc_user.string' => 'El documento debe ser una cadena de texto.',
            'doc_user.max' => 'El documento no debe superar los 20 caracteres.',
    
            'telefono_user.string' => 'El teléfono debe ser una cadena de texto.',
            'telefono_user.max' => 'El teléfono no debe superar los 15 caracteres.',
    
            'direccion_user.string' => 'La dirección debe ser una cadena de texto.',
            'direccion_user.max' => 'La dirección no debe superar los 150 caracteres.',
    
            'email_user.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email_user.max' => 'El correo electrónico no debe superar los 100 caracteres.',
    
            'estado_user.required' => 'El estado es obligatorio.',
            'estado_user.string' => 'El estado debe ser una cadena de texto.',
            'estado_user.max' => 'El estado no debe superar los 15 caracteres.',
    
            'usuario_user.required' => 'El nombre de usuario es obligatorio.',
            'usuario_user.string' => 'El nombre de usuario debe ser una cadena de texto.',
    
            'clave_user.required' => 'La clave es obligatoria.',
            'clave_user.string' => 'La clave debe ser una cadena de texto.',
    
            'categoria_user.required' => 'La categoría es obligatoria.',
            'categoria_user.string' => 'La categoría debe ser una cadena de texto.',
            'categoria_user.max' => 'La categoría no debe superar los 25 caracteres.',
    
            'cargo_user.string' => 'El cargo debe ser una cadena de texto.',
        ];
    }
}
