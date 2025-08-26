<?php
namespace App\Services\DTE;

use App\DTO\dte\notaCreditoDebitoDTO;
use App\Http\Services\ConexionMH;
use App\Http\Services\EmailService;
use App\Http\Services\generatePDF;
use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\genJsonDte;
use App\Models\DetVenta;
use App\Models\DocumentoDte;
use App\Models\DocumentosRelacionadosDte;
use App\Models\Sucursal;
use App\Models\Ventas;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class notasService{
    public function __construct()
    {
        date_default_timezone_set('America/El_Salvador');
    }

    public function generarCodigoNota(int $empresa_id){
        $codigo = strtoupper('NC-' . date('Ymd.His') . "." . $empresa_id);
        return [
            'status' => 'success',
            'message' => 'Codigo de notas de credito o debido generado con exito',
            'codigo' => $codigo
        ];
    }

    protected function getSucursalCodigos($numeroControl){
        $arrayNumeroControl = explode('-', $numeroControl);
        if(count($arrayNumeroControl) > 1){
            $codEstableMH = substr($arrayNumeroControl['2'], 0,4);
            $codPuntoVenta = substr($arrayNumeroControl['2'], 4,8);
            return [
                'codEstableMH' => $codEstableMH,
                'codPuntoVenta' => $codPuntoVenta
            ];
        }
        return [
            'codEstableMH' => '',
            'codPuntoVenta' => ''
        ];
    }
    
    public function emitirCredito(notaCreditoDebitoDTO $datos){
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        DB::beginTransaction();
        try{
            $cuenta_id = Auth::user()->cuenta_id;
            //Obtener el cliente
            $cliente = Cliente::where('nrc', $datos->nrc_receptor)->where('numDocumento', $datos->nit_receptor)->where('empresa_id', $datos->empresa_id)->orderBy('id','desc')->first();
            if(!$cliente){
                return [
                    'status' => 'error',
                    'messsage' => 'El cliente solicitado no fue encontrado.'
                ];
            }

            $numeroDocumento = '';
            foreach($datos->documentosRelacionados as $documentoR){
                $numeroDocumento = $documentoR['codigo_generacion'];
            }
            if($numeroDocumento === ""){
                return [
                    'status' => 'error',
                    'messsage' => 'Debe proporcionar al menos un documento relacionado.'
                ];
            }
            $documentoRelacionado = DocumentoDte::where('codigoGeneracion', $numeroDocumento)->where('empresa_id', $datos->empresa_id)->where('cuenta_id', $cuenta_id)->first();
            if(!$documentoRelacionado){
                return [
                    'status' => 'error',
                    'message' => 'No se encontro el DTE solicitado.'
                ];
            }
            //Version DTE O JSON
            $versionJSON = 3;
            $tipo_dte = "05";
            $arraySucursalCodigos = $this->getSucursalCodigos($documentoRelacionado['numeroControl']);
            $codEstableMH = $arraySucursalCodigos['codEstableMH'];
            $codPuntoVenta = $arraySucursalCodigos['codPuntoVenta'];

            $empresa = Empresa::where('id', $datos->empresa_id)->where('cuenta_id', $cuenta_id)->first();
            $sucursal = Sucursal::where('cod_establecimiento', $codEstableMH)->where('cod_punto_venta', $codPuntoVenta)->where('empresa_id', $empresa['id'])->first();

            if(!$sucursal){
                return [
                    'status' => 'error',
                    'message' => 'No se encontro la sucursal.'
                ];
            }

            $emisorData = genJsonDte::getEmisorDTE($empresa['nrc'],$empresa['nit'],$empresa['id'],$tipo_dte, $codPuntoVenta, $codEstableMH);

            if($emisorData && isset($emisorData['status'])){
                return $emisorData;
            }
            $ambiente = $emisorData['authCred']['ambiente'];
            $token = $emisorData['authCred']['token'];
            $clave_cert = $emisorData['authCred']['claveCert'];
            //Contribuyente
            $contribuyente = 'No';
            $retencion = 0;
            $object_resumen = $datos->object_resumen;
            if((float)$object_resumen['retencion'] != 0){
                $contribuyente = 'Si';
                $retencion = 1;
            }
            $bodyDocumento = genJsonDte::getProductosItems($tipo_dte,$datos->items_documento,
                [
                    'tipo_venta' => 2,
                    'forma_pago' => '-'
                ],
                $contribuyente,
                $retencion
            );

            $documentosRelacionados = [
                [
                    "tipoDocumento" => $documentoRelacionado['tipo_dte'],
                    "tipoGeneracion" => 2,
                    "numeroDocumento" => $documentoRelacionado['codigoGeneracion'],
                    "fechaEmision" => $documentoRelacionado['fecha_gen']
                ]
            ];

            $documentoDTE = DocumentoDte::where('cod_ref', $datos->codigo_dte)->where('cuenta_id',$cuenta_id)->where('empresa_id', $empresa['id'])->orderBy('id', 'desc')->first();

            if($documentoDTE && in_array($documentoDTE['estado'],["PROCESADO"])){
                //DTE EXISTENTE
                $base64_pdf = generatePDF::getPDF(json_decode($documentoDTE['dte_json'],true),'',true);
                return [
                    'status' => 'success',
                    "message" => "El documento ya fue validado",
                    'result' => [
                        'estado' => $documentoDTE['estado'],
                        'codigoGeneracion' => $documentoDTE['codigoGeneracion'],
                        'numeroControl' => $documentoDTE['numeroControl'],
                        'selloRecibido' => $documentoDTE['selloRecibido'],
                        'fecha' => $documentoDTE['fecha_gen'] . " ". $documentoDTE['hora_gen'],
                        "base64_pdf" => $base64_pdf,
                    ]
                ];
            }
            if($documentoDTE && in_array($documentoDTE['estado'],["NO VALIDADO","RECHAZADO"])){
                $venta = Ventas::where('id', $documentoDTE['venta_id'])->where('empresa_id', $datos->empresa_id)->update([
                    'tipo_venta' => 2,
                    'forma_pago' => "-"
                ]);
                //Datos DB documento
                $codigoGeneracion = $documentoDTE['codigoGeneracion'];
                $numeroControl = $documentoDTE['numeroControl'];

                $this->saveDetalleVenta($tipo_dte,$bodyDocumento['productos'],$documentoDTE['id'],$documentoDTE['venta_id'],$datos->empresa_id);

                //identificacion
                $identificacion = genJsonDte::getIdentificacion($versionJSON,$tipo_dte,$ambiente,$codigoGeneracion,$numeroControl,$documentoDTE['fecha_gen'],$documentoDTE['hora_gen']);
            }else{
                //Nuevo DTE
                $codigoGeneracion = genJsonDte::getCodigoGeneracion(); //New codigo de generacion
                $numeroControl = "DTE-" . $tipo_dte . "-" . $codEstableMH . $codPuntoVenta . "-" . genJsonDte::getNumeroControl($empresa['ambiente'],$cuenta_id,$empresa['id'],$tipo_dte);
                //identificacion
                $identificacion = genJsonDte::getIdentificacion($versionJSON,$tipo_dte,$empresa['ambiente'],$codigoGeneracion,$numeroControl,$fecha,$hora);

                $venta = Ventas::create([
                    'tipo_venta' => 2,
                    'forma_pago' => "05",
                    'empresa_id' => $datos->empresa_id
                ]);

                $documentoDte = DocumentoDte::create([
                    'ambiente' => $ambiente,
                    'intentos' => 0,
                    'cod_ref' => $datos->codigo_dte,
                    'tipo_dte' => $tipo_dte,
                    'version_dte' => $versionJSON,
                    'fecha_gen' => $fecha,
                    'hora_gen' => $hora,
                    'fecha_recepcion' => '',
                    'estado' => 'NO VALIDADO',
                    'tipo_transmision' => 'Transmisión normal',
                    'codigoGeneracion' => $codigoGeneracion,
                    'numeroControl' => $numeroControl,
                    'selloRecibido' => '',
                    'cliente_id' => $cliente->id,
                    'venta_id' => $venta->id,
                    'cuenta_id' => $cuenta_id,
                    'empresa_id' => $empresa['id'],
                    'sucursal_id' => $sucursal['id']
                ]);

                foreach($documentosRelacionados as $item){
                    DocumentosRelacionadosDte::create([
                        'tipoDocumento' => $item['tipoDocumento'],
                        'tipoGeneracion' => $item['tipoGeneracion'],
                        'numeroDocumento' => $item['numeroDocumento'],
                        'fechaEmision' => $item['fechaEmision'],
                        'empresa_id' => $empresa['id'],
                        'cuenta_id' => $cuenta_id,
                        'documento_dte_id' => $documentoDte->id,
                    ]);
                }
                $this->saveDetalleVenta($tipo_dte,$bodyDocumento['productos'],$documentoDte->id,$venta->id,$datos->empresa_id);
            }

            //firmador
            $claveCifrado = [
                "nit" => str_replace('-','', $empresa['nit']),
                "active" => true,
                "passwordPri" => Crypt::decrypt($clave_cert)
            ];

            $receptor = [
                "nrc" => (string)$cliente['nrc'],
                "nit" => (string)$cliente['numDocumento'],
                "nombre" => $cliente['nombre'],
                "codActividad" => (string)$cliente['codActividad'],
                "descActividad" => (string)trim($cliente['descActividad']),
                "nombreComercial" => $datos->nombre_receptor,
                "direccion" => [
                    "departamento" => (string)$cliente['codDepto'],
                    "municipio" => (string)$cliente['codMunic'],
                    "complemento" => $datos->direccion_receptor
                ],
                "telefono" => $datos->telefono_receptor,
                "correo" => $datos->email_receptor
            ];
            //JSON DTE
            $jsonDTE = [];
            $jsonDTE = $claveCifrado;
            if($tipo_dte == "05" || $tipo_dte == "06"){
                $jsonDTE['dteJson'] = [
                    "identificacion" => $identificacion,
                    "documentoRelacionado" => $documentosRelacionados,
                    "emisor" => $emisorData['emisor'],
                    "receptor" => $receptor,
                    "ventaTercero" => null,
                    "cuerpoDocumento" => $bodyDocumento['productos'],
                    "resumen" => $bodyDocumento['resumen'],
                    "extension" => [
                        "nombEntrega" => null,
                        "docuEntrega" => null,
                        "nombRecibe" => null,
                        "docuRecibe" => null,
                        "observaciones" => $datos->observaciones_doc
                    ],
                    "apendice" => null
                ];
            }
            //Correlativo
            $documentoDTE = DocumentoDte::where('codigoGeneracion', $codigoGeneracion)->where('cuenta_id', $cuenta_id)->where('empresa_id', $empresa['id'])->first();
            //DTE Firmar
            $dte_firmado = ConexionMH::firmarDTE($jsonDTE);
            if(!$dte_firmado){
                return [
                    'status' => 'error',
                    'message' => 'Ocurrió un error al intentar firmar el documento electrónico.'
                ];
            }
            $result = ConexionMH::sendDTE($versionJSON,$ambiente,$dte_firmado['body'],$codigoGeneracion,$token,$tipo_dte);
            if($result == ""){ //Sin respuesta por parte de MH
                $documentoDTE['intentos'] = (int)$documentoDTE['intentos'] + 1;
                if((int)$documentoDTE['intentos'] > 3){ //Modo contingencia
                    $documentoDTE['tipo_transmision'] = "Transmisión por contingencia";
                }
                $documentoDTE->save();
                return [
                    'status' => 'error',
                    'message' => 'No se pudo establecer conexión con el servicio de Hacienda. Inténtelo nuevamente más tarde.'
                ];
            }
            $messageGenPdf = '';
            $outputPdfData = '';
            if($documentoDTE){
                if($result['estado'] == "PROCESADO"){
                    $documentoDTE['fecha_recepcion'] = substr($result['fhProcesamiento'],0,10);
                    $documentoDTE['estado'] =  'PROCESADO';
                    $documentoDTE['selloRecibido'] =  $result['selloRecibido'];
                    //incluir sello y firma Electronica
                    $jsonDTE['dteJson'] += [
                        'selloRecibido' => $result['selloRecibido'],
                        'firmaElectronica' => $dte_firmado['body']
                    ];
                    $documentoDTE['dte_json'] = json_encode($jsonDTE['dteJson']);
                    $message = 'DTE recibido y procesado.';
                    $status = 'success';
                    try{
                        $outputPdfData = generatePDF::getPDF($jsonDTE['dteJson'],'',true);
                        EmailService::sendNotificationDTE($jsonDTE['dteJson'],$outputPdfData,$cuenta_id,$datos->empresa_id);
                        $messageGenPdf = 'Generado con éxito';
                    }catch(Exception $e){
                        $messageGenPdf = $e->getMessage();
                    }
                }else{
                    $documentoDTE['intentos'] = (int)$documentoDTE['intentos'] + 1;
                    if((int)$documentoDTE['intentos'] > 3){ //Modo contingencia
                        $documentoDTE['tipo_transmision'] = "Transmisión por contingencia";
                    }
                    $message = 'Error :' . $result['descripcionMsg'];
                    $status = 'error';
                    $outputPdfData = '';
                }
                $documentoDTE['estado'] = $result['estado'];
                $documentoDTE->save();
                $result['tipo_dte'] = $tipo_dte;
                $result['numeroControl'] = $numeroControl;
                $result['base64_pdf'] = $outputPdfData;
            }

            DB::commit();
            return [
                'status' => $status,
                'message' => $message,
                'messagePdf' => $messageGenPdf,
                'result' => $result,
                'jsonDTE' => $jsonDTE
            ];
        }catch(Exception $e){
            return [
                'status' => 'error',
                'message' => 'Ha ocurrido un error: ' . $e->getMessage()
            ];
        }
    }

    public function saveDetalleVenta($tipo_dte, $productos, $documento_id,$venta_id, $empresa_id){
        //delete det_venta
        DetVenta::where('documento_dte_id', $documento_id)->where('empresa_id', $empresa_id)->delete();
        //Detalle de venta
        foreach($productos as $item){
            switch($tipo_dte){
                case "05":
                    DetVenta::create([
                        'numItem' => $item['numItem'],
                        'tipoItem' => $item['tipoItem'],
                        'codigo' => $item['codigo'],
                        'codTributo' => $item['codTributo'],
                        'numeroDocumento' => $item['numeroDocumento'],
                        'descripcion' => $item['descripcion'],
                        'cantidad' => $item['cantidad'],
                        'uniMedida' => $item['uniMedida'],
                        'precioUni' => $item['precioUni'],
                        'montoDescu' => $item['montoDescu'],
                        'ventaNoSuj' => $item['ventaNoSuj'],
                        'ventaExenta' => $item['ventaExenta'],
                        'ventaGravada' => $item['ventaGravada'],
                        'noGravada' => 0.00,
                        'psv' => 0.00,
                        'tributos' => $item['tributos'][0],
                        'documento_dte_id' => $documento_id,
                        'venta_id' => $venta_id,
                        'empresa_id' => $empresa_id
                    ]);
                    break;
                case "06":
                    DetVenta::create([
                        'numItem' => $item['numItem'],
                        'tipoItem' => $item['tipoItem'],
                        'codigo' => $item['codigo'],
                        'codTributo' => $item['codTributo'],
                        'numeroDocumento' => $item['numeroDocumento'],
                        'descripcion' => $item['descripcion'],
                        'cantidad' => $item['cantidad'],
                        'uniMedida' => $item['uniMedida'],
                        'precioUni' => $item['precioUni'],
                        'montoDescu' => $item['montoDescu'],
                        'ventaNoSuj' => $item['ventaNoSuj'],
                        'ventaExenta' => $item['ventaExenta'],
                        'ventaGravada' => $item['ventaGravada'],
                        'noGravada' => $item['noGravado'],
                        'psv' => $item['psv'],
                        'tributos' => $item['tributos'][0],
                        'documento_dte_id' => $documento_id,
                        'venta_id' => $venta_id,
                        'empresa_id' => $empresa_id
                    ]);
                    break;
            }
        }
    }
}