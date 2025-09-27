<?php

namespace App\Http\Controllers;

use App\Models\ValFormDinamico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValFormDinmicoController extends Controller
{
    public function save(Request $request){
        $datosValidados = $request->validate([
            'nombre_dinamico' => 'required|string|max:200|unique:val_form_dinamicos,nombre',
            'modulo'          => 'required|string|max:50',
            'identificador'   => 'required|string|max:50',
        ], [
            'nombre_dinamico.required' => 'El nombre es obligatorio.',
            'nombre_dinamico.unique'   => 'Este nombre ya existe, ingresa uno diferente.',
            'modulo.required'          => 'El módulo es obligatorio.',
            'identificador.required'   => 'El identificador es obligatorio.',
        ]);
        
        $saveValForm = ValFormDinamico::create([
            'nombre' => trim($datosValidados['nombre_dinamico']),
            'modulo_form' => $datosValidados['modulo'],
            'identicador' => $datosValidados['identificador'],
            'usuario_id' => Auth::user()->id,
        ]);
        if($saveValForm){
            return response()->json([
                'status' => 'success',
                'message' => 'Se ha guardado con exito el valor.',
                'result' => $saveValForm
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Ha ocurrido un error al guardar los datos.',
            'result' => []
        ]);
    }

    public function getValores(){
        return response()->json(ValFormDinamico::orderBy('id','DESC')->get());
    }
}
