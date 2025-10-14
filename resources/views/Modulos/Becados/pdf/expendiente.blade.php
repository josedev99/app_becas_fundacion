<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expediente de {{ $becado->nombre_completo }}</title>
    <style>
        html {
            margin: 10px 15px;
        }

        /* Estilos base */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            background-color: #fff;
            margin: 20px;
        }

        /* Encabezado */
        header {
            text-align: center;
            padding-bottom: 8px;
            margin-bottom: 15px;
            border-bottom: 2px solid #0d6efd;
        }

        header h2 {
            margin: 0;
            color: #0d6efd;
            letter-spacing: 0.5px;
        }

        header .fecha {
            font-size: 11px;
            color: #666;
        }

        /* Secciones */
        .section {
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background-color: #f8f9fa;
            padding: 6px;
        }

        .section h4 {
            background-color: #151f31;
            color: #fff;
            padding: 4px;
            border-radius: 5px 5px 0 0;
            font-size: 12px;
            margin: 0px;
        }

        .title-table{
            background-color: #151f31;
            color: #fff;
            padding: 4px;
            border-radius: 5px 5px 0 0;
            font-size: 12px;
        }

        /* Tablas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #dee2e6;
            padding: 3px;
            text-align: left;
            font-size: 11px;
            vertical-align: top;
        }

        th {
            background-color: #e9ecef;
            font-weight: bold;
        }

        /* Espaciado */
        .mb-2 {
            margin-bottom: 8px;
        }

        /* Subtítulos dentro de secciones */
        .subtitulo {
            color: #0d6efd;
            font-weight: bold;
            margin-top: 10px;
        }

    </style>
</head>
<body>

    <header>
        <h2>FUNDACIÓN - Expediente de Becado</h2>
        <div class="fecha">Fecha de generación: {{ date('d/m/Y') }}</div>
    </header>

    <table>
        <tr>
            <th class="title-table" colspan="6" style="width: 100%;text-align:center">Datos del Becado</th>
        </tr>
        <tr>
            <th class="text-center">DUI</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">F. Nacimiento</th>
            <th class="text-center">Dirección</th>
            <th class="text-center">Tel.</th>
            <th class="text-center">Tel. de Emergencia</th>
        </tr>
        <tr>
            <td>{{ $becado->documento ?? '' }}</td>
            <td>{{ $becado->nombre_completo ?? '' }}</td>
            <td>{{ $becado->fecha_nacimiento ?? '' }}</td>
            <td>{{ $becado->direccion ?? '' }}</td>
            <td>{{ $becado->telefono ?? '' }}</td>
            <td>{{ $becado->telefono_emergencia ?? '' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <th class="title-table" colspan="4" style="width: 100%;text-align:center">Datos Académicos</th>
        </tr>
        <tr>
            <th>Nivel Educativo</th>
            <th>Institución</th>
            <th>Carrera</th>
            <th>Estado</th>
        </tr>
        <tr>
            <td>{{ $academico->nivel_educativo ?? '' }}</td>
            <td>{{ $academico->institucion ?? '' }}</td>
            <td>{{ $academico->carrera_grado ?? '' }}</td>
            <td>{{ $academico->estado_academico ?? '' }}</td>
        </tr>
    </table>

    <div class="section">
        <h4>Datos Socioeconómicos</h4>
        <table>
            <thead>
                <tr>
                    <th>Situación Familiar</th>
                    <th>Ingresos Estimados</th>
                    <th>Cantidad Personas</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $socio->situacion_familiar ?? '' }}</td>
                    <td>${{ number_format($socio->ingresos,2,'.',',') ?? '' }}</td>
                    <td>{{ $socio->cantidad_personas ?? '' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="subtitulo">Necesidades Especiales:</div>
        <p class="mb-2">{{ $socio->necesidades ?? 'Ninguna' }}</p>
    </div>

    @foreach($seguimientos as $key)
        <div class="section">
            <h4>Seguimiento del Becado</h4>
            <table>
                <tr>
                    <th>Fecha Seguimiento</th>
                    <th>Responsable</th>
                    <th>Estado Seguimiento</th>
                    <th>Próximo Seguimiento</th>
                </tr>
                <tr>
                    <td>{{ $key->fecha_reporte ?? '' }}</td>
                    <td>{{ $key->responsable_seguimiento ?? '' }}</td>
                    <td>{{ $key->estado_beca ?? '' }}</td>
                    <td>{{ $key->fecha_proximo ?? '' }}</td>
                </tr>
                <tr>
                    <th colspan="2">Participación</th>
                    <th colspan="2">Observaciones del Tutor</th>
                </tr>
                <tr>
                    <td colspan="2">{{ $key->participacion_actividades ?? '' }}</td>
                    <td colspan="2">{{ $key->observaciones_tutor ?? '' }}</td>
                </tr>
            </table>
        </div>
    @endforeach
</body>
</html>
