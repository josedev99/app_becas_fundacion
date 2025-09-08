<?php

namespace App\Http\Controllers\becas;

use App\Http\Controllers\Controller;
use App\Http\Requests\BecaRequest;
use App\Models\becas\Beca;
use Illuminate\Http\Request;

class BecasController extends Controller
{
    public function index(){
        return view('Modulos.Becas.Index');
    }
    public function getBecas(){
        return response()->json(Beca::orderBy('id','desc')->get());
    }

    public function save(BecaRequest $request){
        $datos = $request->validated();
        return $datos;
    }
}
