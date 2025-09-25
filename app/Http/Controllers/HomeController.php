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
            'total_nivel_educativo' => $this->getBecadosPorNivel(),
            'graduados' => $this->getGraduados(),
            'inversion_anual' => 0.00
        ]);
    }

    protected function getCounterBecados(){
        return Becados::count();
    }

    protected function getPromedioGeneral(){
        return DB::table('datos_academicos')->avg('promedio');
    }
    protected function getBecadosPorNivel(){
        $NivelesEducativos = ["Basico", "Bachillerato", "Universidad", "Tecnico"];
        $result = [];
        foreach($NivelesEducativos as $nivel){
            $count = DB::table('datos_academicos')
                ->where('nivel_educativo', $nivel)
                ->count();
            $result[$nivel] = [
                'nivel' => $nivel,
                'count' => $count
            ];
        }
        return array_values($result);
    }

    protected function getGraduados(){
        $cantidadBecados = $this->getCounterBecados();
        $cantidadGraduados = DB::table('datos_academicos')
            ->where('estado_academico', 'Graduado')
            ->count();
        return [
            'total_becados' => $cantidadBecados,
            'porcentaje' => (int)$cantidadBecados > 0 ? (($cantidadGraduados / $cantidadBecados) * 100) : 0
        ];
    }
}
