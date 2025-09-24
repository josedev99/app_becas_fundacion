<?php

namespace App\Http\Controllers;

use App\Models\becados\Becados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return view('Home');
    }

    public function datosDashboard(){
        return response()->json([
            'total_becados' => $this->getCounterBecados(),
            'promedio_general' => $this->getPromedioGeneral(),
            'total_solicitudes' => 0
        ]);
    }

    protected function getCounterBecados(){
        return Becados::count();
    }

    protected function getPromedioGeneral(){
        return DB::table('datos_academicos')->avg('promedio');
    }
}
