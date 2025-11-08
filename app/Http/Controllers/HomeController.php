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
            'inversion_anual' => $this->getInversionAnual(),
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

    protected function getInversionAnual(){
        $datos = DB::select("SELECT SUM( CASE WHEN b.plazo_monto = 'Anual' THEN b.monto WHEN b.plazo_monto = 'Mensual' THEN b.monto * 12 ELSE b.monto END ) AS total_inversion_anual FROM estudiantes e INNER JOIN becas b ON e.beca_id = b.id INNER JOIN datos_academicos a ON a.estudiante_id = e.id WHERE a.estado_academico = 'Activo';");

        if($datos && isset($datos[0]->total_inversion_anual)){
            return number_format((float)$datos[0]->total_inversion_anual, 2, '.', ',');
        }
        return 0.00;
    }
}
