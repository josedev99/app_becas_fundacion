<?php

namespace App\Http\Controllers\becas;

use App\Http\Controllers\Controller;
use App\Models\becas\Beca;
use Illuminate\Http\Request;

class BecasController extends Controller
{
    public function index(){
        return view();
    }
    public function getBecas(){
        return response()->json(Beca::orderBy('id','desc')->get());
    }
}
