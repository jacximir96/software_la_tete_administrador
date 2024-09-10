<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdministradoresModel;
use App\UnidadesMedidaModel;
use App\CabeceraFacturaModel;
use App\CabeceraOrdenPedidoModel;
use App\DetalleOrdenPedidoModel;
use App\ProductosLimpiezaModel;
use App\CajaCuadreModel;
use App\PeriodoModel;
use App\ClienteModel;
use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */
use PDF;/* Apuntamos al modelo que existe por defecto para obtener información en PDF */
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UnidadesMedidaExport;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class UnidadesMedidaController extends Controller
{
    public function index()
    {

        $administradores = AdministradoresModel::all();
        $estado = DB::select('SELECT * FROM estado');
        $unidadesMedida =   DB::select('SELECT UM.id_unidadMedida,UM.nombre_unidadMedida,UM.estado_unidadMedida,
                                        UM.id_usuario,UM.created_at,UM.updated_at,E.nombre_estado,U.name FROM
                                        unidades_medida UM INNER JOIN estado E ON UM.estado_unidadMedida = E.id_estado
                                        LEFT JOIN users U ON UM.id_usuario = U.id ORDER BY UM.id_unidadMedida DESC');

        return view("paginas.unidadesMedida", array(
            "administradores" => $administradores, "estado" => $estado,
            "unidadesMedida" => $unidadesMedida
        ));
    }

    public function listarMesas()
    {
        $estado = DB::select('SELECT * FROM mesa');

        return $this->response_json(200, "", $estado);
    }

    public function listarCategorias()
    {
        $categorias = DB::select('SELECT * FROM categorias ORDER BY nombre_categoria ASC');

        return $this->response_json(200, "", $categorias);
    }

    public function listarFormaPago()
    {
        $formaPago = DB::select('SELECT * FROM forma_pago');

        return $this->response_json(200, "", $formaPago);
    }

    public function listarProductoPorCategoria(Request $request)
    {
        $productoCategoria = DB::select('SELECT * FROM productos_limpieza WHERE id_categoria = ? ORDER BY nombre_productoLimpieza ASC', [$request->idCategoriaSelected]);

        return $this->response_json(200, "", $productoCategoria);
    }

    public function getProductoById(Request $request)
    {
        $productoById = DB::select('SELECT * FROM productos_limpieza WHERE id_productoLimpieza = ?', [$request->idProductoSelected]);

        return $this->response_json(200, "", $productoById);
    }

    public function iniciarSesion(Request $request)
    {
        $usuario = DB::select('SELECT * FROM users WHERE email = :email', ["email" => $request->usuario]);

        if (count($usuario) == 0) {
            return $this->response_json(400, "Usuario no existe");
        } else {
            if (!Hash::check($request->clave, $usuario[0]->password)) {
                return $this->response_json(400, "Error de credencial");
            }

            return $this->response_json(200, "", $usuario);
        }
    }

    public function createOrdenPedido(Request $request)
    {
        try {
            /* Validar error de stock */
            foreach ($request->detalleOrdenPedido as $detalleOrdenPedidoValue) {
                $productoId = $detalleOrdenPedidoValue['id_producto'];
            
                // Verificar si el producto tiene insumos asociados
                $producto = DB::table('productos_limpieza')
                    ->where('id_productoLimpieza', $productoId)
                    ->first();
            
                if (!$producto) {
                    return response()->json(['error' => 'Producto no encontrado'], 404);
                }
            
                // Verificar si el producto tiene insumos asociados
                if (!empty($producto->insumos_asociados)) {
                    $insumosIds = explode(',', $producto->insumos_asociados);
            
                    // Verificar el stock de cada insumo
                    foreach ($insumosIds as $insumoId) {
                        $insumo = DB::table('insumo')
                            ->where('IDInsumo', $insumoId)
                            ->first();
            
                    if (!$insumo) {
                        return response()->json(['error' => 'Insumo no encontrado: ' . $insumoId], 404);
                    }
        
                    if ($insumo->stock_insumo <= 0) {
                        return response()->json(['error' => 'Stock insuficiente para el insumo: ' . $insumo->nombre_insumo], 400);
                    }
                    }
                } else {
                    // Verificar el stock del producto si no tiene insumos asociados
                    if ($producto->stock_productoLimpieza <= 0) {
                        return response()->json(['error' => 'Stock insuficiente para el producto: ' . $producto->nombre_productoLimpieza], 400);
                    }
                }
            
                // Si todo está bien, añade el producto a la lista de disponibles
                $productoDisponibleStock[] = $productoId;
            }
            /* Validar error de stock */

            try {
                DB::beginTransaction();

                $tipoDocumento = 'orden_pedido';
                $cabeceraOrdenPedido = $this->crearCabeceraOrdenPedido($request);
                $this->actualizarCorrelativo($cabeceraOrdenPedido->id, $tipoDocumento);
                $detalleOrdenPedidoJson = $this->procesarDetalleOrdenPedido($request->detalleOrdenPedido, $cabeceraOrdenPedido->id);

                /* AGREGAMOS VALIDACION PARA DESCONTAR STOCK - INICIO */
                foreach ($request->detalleOrdenPedido as $detalleOrdenPedidoValue) {
                    $productoId = $detalleOrdenPedidoValue['id_producto'];
                    $cantidad = 1;
            
                    // Verificar si el producto tiene insumos asociados
                    $producto = DB::table('productos_limpieza')
                        ->where('id_productoLimpieza', $productoId)
                        ->first();
            
                    if (!empty($producto->insumos_asociados)) {
                        $insumosIds = explode(',', $producto->insumos_asociados);
            
                        foreach ($insumosIds as $insumoId) {
                            // Descontar stock del insumo
                            DB::table('insumo')
                                ->where('IDInsumo', $insumoId)
                                ->decrement('stock_insumo', $cantidad);
                        }
                    } else {
                        // Descontar stock del producto si no tiene insumos asociados
                        DB::table('productos_limpieza')
                            ->where('id_productoLimpieza', $productoId)
                            ->decrement('stock_productoLimpieza', $cantidad);
                    }
                }

                DB::commit();

                /* AGREGAMOS VALIDACION PARA DESCONTAR STOCK - FINAL */

                Log::info("APPLY_PRINTER_LOCAL: " . Config::get('app.APPLY_PRINTER_LOCAL'));
                if ( Config::get('app.APPLY_PRINTER_LOCAL', false) ) {
                    $payload = $this->prepararDatosImpresion($cabeceraOrdenPedido->id, $tipoDocumento, $cabeceraOrdenPedido, $detalleOrdenPedidoJson, $request->IDMesa);

                    try {
                        $this->enviarImpresion($payload);
                    } catch (\Exception $printException) {
                        Log::error("Error al intentar imprimir: " . $printException->getMessage());
                    }

                    try {

                    } catch (\Exception $printException) {
                        Log::error("Error al intentar descontar el stock: " . $printException->getMessage());
                    }

                    $mensajeRespuesta = 'Orden de pedido y impresión realizada con éxito';
                } else {
                    $payload = $cabeceraOrdenPedido;
                    $mensajeRespuesta = 'Orden de pedido creada con éxito';
                }

                return $this->response_json(200, $mensajeRespuesta, $payload);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error al procesar la orden: " . $e->getMessage());
                return response()->json(['error' => 'Error al procesar la orden'], 500);
            }
    
        } catch (\Exception $e) {
            Log::error("Error al crear la orden de pedido: " . $e->getMessage());
            return $this->response_json(500, "Ha ocurrido un error durante la creación de la orden de pedido.", [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function createCliente(Request $request)
    {
        $cliente = new ClienteModel();
        $cliente->cli_nombres = $request->cli_nombres;
        $cliente->cli_documento = $request->cli_documento;
        $cliente->cli_telefono = $request->cli_telefono;
        $cliente->cli_direccion = $request->cli_direccion;
        $cliente->cli_email = $request->cli_email;
        $cliente->save();
    
        return $this->response_json(200, "", $cliente);
    }

    public function obtenerCliente(Request $request)
    {
        $clienteById = DB::select('SELECT * FROM cliente WHERE 	cli_documento = ?', [$request->cli_documento]);

        return $this->response_json(200, "", $clienteById);
    }

    /* Funciones referente a la creación de orden de pedido y sus detalles - Inicio */
    private function crearCabeceraOrdenPedido(Request $request)
    {
        $cabeceraOrdenPedido = new CabeceraOrdenPedidoModel();
        $cabeceraOrdenPedido->odp_monto_total = $request->odp_monto_total;
        $cabeceraOrdenPedido->IDMesa = $request->IDMesa;
        $cabeceraOrdenPedido->IDStatus = $request->IDStatus;
        $cabeceraOrdenPedido->IDPeriodo = $this->retornarPeriodoActivo();
        $cabeceraOrdenPedido->odp_despachado = 0;
        $cabeceraOrdenPedido->save();
    
        return $cabeceraOrdenPedido;
    }

    private function crearCabeceraFactura(Request $request)
    {
        $ordenesPedidoSuma = DB::select(
            'SELECT SUM(odp_monto_total) as odp_monto_total_suma FROM cabecera_orden_pedido WHERE IDMesa = :IDMesa AND IDStatus = 3',
            ["IDMesa" => $request->idMesaSeleccionadaActual]
        );

        $ordenesPedidoIdentificador = DB::select(
            'SELECT IDCabeceraOrdenPedido FROM cabecera_orden_pedido WHERE IDMesa = :IDMesa AND IDStatus = 3',
            ["IDMesa" => $request->idMesaSeleccionadaActual]
        );

        $arregloIdentificador = [];

        foreach ($ordenesPedidoIdentificador as $ordenesIdentificador) {
            array_push($arregloIdentificador, $ordenesIdentificador->IDCabeceraOrdenPedido);
        }

        $calculoTarjetaPorcentaje34 = $ordenesPedidoSuma[0]->odp_monto_total_suma * 0.0344;
        $calculoTarjetaPorcentaje18 = $calculoTarjetaPorcentaje34 * 0.18;
        $calculoTotalPorcenaje = $calculoTarjetaPorcentaje34 + $calculoTarjetaPorcentaje18;

        $valorSeparadoComas = implode(",", $arregloIdentificador);

        $validacionPorTarjeta = $request->valueFormaPago == 2 ? 
        $ordenesPedidoSuma[0]->odp_monto_total_suma - $calculoTotalPorcenaje : 
        $ordenesPedidoSuma[0]->odp_monto_total_suma;

        $cabeceraFactura = new CabeceraFacturaModel();
        $cabeceraFactura->cfac_ordenes_pedido = $valorSeparadoComas;
        $cabeceraFactura->cfac_monto_total = number_format((float)$validacionPorTarjeta, 2, '.', '');
        $cabeceraFactura->IDFormaPago = $request->valueFormaPago;
        $cabeceraFactura->IDStatus = 4;
        $cabeceraFactura->IDPeriodo = $this->retornarPeriodoActivo();
        $cabeceraFactura->IDCliente = $request->valueClienteID;
        $cabeceraFactura->save();

        return $cabeceraFactura;
    }

    private function actualizarCorrelativo($insertedId, $tipoDocumento, $valorSeparadoComas = null)
    {
        $numero_final = str_pad($insertedId, 10, '0', STR_PAD_LEFT);

        if ( $tipoDocumento == 'orden_pedido' )
        {
            DB::UPDATE("UPDATE cabecera_orden_pedido SET odp_correlativo = :correlativo WHERE IDCabeceraOrdenPedido = :idInsert", [
                "correlativo" => $numero_final,
                "idInsert" => $insertedId
            ]);
        } else {
            DB::UPDATE("UPDATE cabecera_factura SET cfac_correlativo = :correlativo WHERE IDCabeceraFactura = :idInsert", [
                "correlativo" => $numero_final, 
                "idInsert" => $insertedId
            ]);

            DB::UPDATE("UPDATE cabecera_orden_pedido SET IDStatus = 4 WHERE IDCabeceraOrdenPedido IN (" . $valorSeparadoComas . ")");
        }
    }

    private function procesarDetalleOrdenPedido($detalles, $insertedId)
    {
        $detalleOrdenPedidoJson = [];

        foreach ($detalles as $detalleOrdenPedidoValue) {
            $detalleOrdenPedido = new DetalleOrdenPedidoModel();
            $detalleOrdenPedido->IDCabeceraOrdenPedido = $insertedId;
            $detalleOrdenPedido->IDProducto = $detalleOrdenPedidoValue['id_producto'];
            $detalleOrdenPedido->dop_cantidad = $detalleOrdenPedidoValue['cantidad'];
            $detalleOrdenPedido->dop_precio = number_format((float)$detalleOrdenPedidoValue['precio'], 2, '.', '');
            $detalleOrdenPedido->dop_total = number_format((float)$detalleOrdenPedidoValue['total'], 2, '.', '');
            $detalleOrdenPedido->observacion = $detalleOrdenPedidoValue['observacion'];
            $detalleOrdenPedido->save();

            $obtenerNombreProducto = DB::table('productos_limpieza')
                ->select('nombre_productoLimpieza', 'cat.nombre_categoria')
                ->join('categorias as cat', 'productos_limpieza.id_categoria', '=', 'cat.id_categoria')
                ->where('id_productoLimpieza', $detalleOrdenPedidoValue['id_producto'])
                ->get();

            $observacionLimpia = trim($detalleOrdenPedidoValue['observacion']);

            if ($observacionLimpia != '') {
                $producto = $obtenerNombreProducto[0]->nombre_categoria . ' - ' . $obtenerNombreProducto[0]->nombre_productoLimpieza . "\n\t - " . $observacionLimpia;
            } else {
                $producto = $obtenerNombreProducto[0]->nombre_categoria . ' - ' . $obtenerNombreProducto[0]->nombre_productoLimpieza;
            }

            $detalleOrdenPedidoJson[] = [
                'cantidad'      => $detalleOrdenPedidoValue['cantidad'],
                'producto'      => $producto
            ];
        }

        return $detalleOrdenPedidoJson;
    }

    private function obtenerDetalleOrdenPedido($idCabeceraOrdenPedido)
    {
        $idCabeceraArray = explode(',', $idCabeceraOrdenPedido);

        $detallesOrdenPedido = DB::table('detalle_orden_pedido')
        ->join('productos_limpieza', 'detalle_orden_pedido.IDProducto', '=', 'productos_limpieza.id_productoLimpieza')
        ->join('categorias as cat', 'productos_limpieza.id_categoria', '=', 'cat.id_categoria')
        ->select(
            'detalle_orden_pedido.dop_cantidad as cantidad',
            'productos_limpieza.nombre_productoLimpieza as producto',
            'cat.nombre_categoria as categoria',
            'detalle_orden_pedido.dop_precio as precio',
            'detalle_orden_pedido.dop_total as total',
            'detalle_orden_pedido.observacion as observacion'
        )
        ->whereIn('detalle_orden_pedido.IDCabeceraOrdenPedido', $idCabeceraArray)
        ->get();

        $detalleOrdenPedidoJson = [];

        foreach ($detallesOrdenPedido as $detalle) {
            $observacionLimpia = trim($detalle->observacion);
    
            if ($observacionLimpia != '') {
                $producto = $detalle->categoria . ' - ' . $detalle->producto . "\n\t - " . $observacionLimpia;
            } else {
                $producto = $detalle->categoria . ' - ' . $detalle->producto;
            }
    
            $detalleOrdenPedidoJson[] = [
                'cantidad' => $detalle->cantidad,
                'producto' => $producto,
                'pUnitario' =>number_format((float)$detalle->precio, 2, '.', ''),
                'valor' => number_format((float)$detalle->total, 2, '.', '')
            ];
        }
    
        return $detalleOrdenPedidoJson;
    }

    private function prepararDatosImpresion($transaccion, $tipoDocumento, $datosTransaccion, $detalleOrdenPedidoJson, $mesa = null)
    {
        if ( $tipoDocumento == 'orden_pedido' ) {
            $data = [
                'mesa' => $mesa,
                'transaccion' => (string) $transaccion,
                'numeroCuenta' => '1',
                'cajero' => 'Administrador',
                'fecha' => Carbon::now()->format('d/m/Y h:ia')
            ];

            $xmlFile = resource_path('xmls/OrdenPedidoXML.xml');
        } else {
            $obtenerDatosCabeceraFactura = DB::table('cabecera_factura')
            ->select('cfac_correlativo')
            ->where('IDCabeceraFactura', $datosTransaccion->id)
            ->first();

            if ($datosTransaccion->IDCliente != '') {
                $obtenerDatosCliente = DB::table('cliente')
                ->where('IDCliente', $datosTransaccion->IDCliente)
                ->first();

                $cli_nombres = $obtenerDatosCliente->cli_nombres;
                $cli_documento = $obtenerDatosCliente->cli_documento;
                $cli_telefono = $obtenerDatosCliente->cli_telefono;
                $cli_direccion = $obtenerDatosCliente->cli_direccion;
                $cli_email = $obtenerDatosCliente->cli_email;
            } else {
                $cli_nombres = "CONSUMIDOR FINAL";
                $cli_documento = "9999999999999";
                $cli_telefono = "980806534";
                $cli_direccion = "";
                $cli_email = "consumidor.final@kfc.com.ec";
            }

            $data = [
                "razonSocial"           => "LA TETE BURGUER",
                "ruc"                   => "10624722197",
                "direccion"             => "1ERA ETAPA URB.PACHACAMAC MZ.D1 LT.13",
                "distrito"              => "LIMA - VILLA EL SALVADOR",
                "fecha"                 => Carbon::now()->format('d/m/Y h:ia'),
                "nroComprobante"        => (string) $obtenerDatosCabeceraFactura->cfac_correlativo,
                "nroOrden"              => (string) $transaccion,
                "nroBoleta"             => (string) $obtenerDatosCabeceraFactura->cfac_correlativo,
                "ambiente"              => "PRUEBAS",
                "emision"               => "EMISION NORMAL ",
                "cliente"               => $cli_nombres,
                "rucCliente"            => $cli_documento,
                "telefono"              => $cli_telefono,
                "email"                 => $cli_email,
                "cajero"                => "Administrador",
                "simboloMoneda"         => "S./",
                "leyendaCondiciones"    => "Estimado cliente: Por favor verifique los datos de su boleta, unicamente se aceptaran cambios el mismo dia de emision.",
                "valorTotal"            => (string) $datosTransaccion->cfac_monto_total,
                "subtotal"              => (string) $datosTransaccion->cfac_monto_total,
                "descuento"             => "0.00",
                "formaPago"             => $this->obtenerFormaPagoTransaccion($datosTransaccion->id)
            ];

            $xmlFile = resource_path('xmls/BoletaElectronicaXML.xml');
        }

        $registros = [
            'registrosDetalle' => $detalleOrdenPedidoJson
        ];

        $xmlContent = File::get($xmlFile);
        $xmlContent = str_replace('\\', '', $xmlContent);

        return [
            'idImpresora' => 'CognitiveTPG Receipt',
            'idMarca' => 'BEMATECH',
            'aplicaBalanceo' => '0',
            'idPlantilla' => $xmlContent,
            'data' => $data,
            'registros' => [$registros]
        ];
    }

    private function obtenerFormaPagoTransaccion($transaccion)
    {
        $obtenerFormaPago = DB::table('forma_pago')
        ->select('forma_pago.fp_descripcion')
        ->join('cabecera_factura as cf', 'forma_pago.IDFormaPago', '=', 'cf.IDFormaPago')
        ->where('IDCabeceraFactura', $transaccion)
        ->first();

        return $obtenerFormaPago->fp_descripcion;
    }

    private function enviarImpresion($payload)
    {
        $headers = [
            "Content-Type: application/json",
            "Accept: application/json"
        ];

        Log::info("URL_SERVICE_PRINTER_LOCAL: " . Config::get('app.URL_SERVICE_PRINTER_LOCAL'));
        $ch = curl_init(Config::get('app.URL_SERVICE_PRINTER_LOCAL'));

        $encodedPayload = json_encode($payload, JSON_UNESCAPED_UNICODE);
        if ($encodedPayload === false) {
            Log::error('Error en la codificación JSON: ' . json_last_error_msg());
            return;
        }
    
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedPayload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

        $reintentos = 3;

        for ($intento = 1; $intento <= $reintentos; $intento++) {
            $response = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $responseData = json_decode($response, true);

            if ($statusCode === 200 && isset($responseData['error']) && $responseData['error'] === true) {
                Log::error("Error en la impresión: " . $responseData['mensaje']);
                break;
            } elseif ($statusCode === 200 && isset($responseData['error']) && $responseData['error'] === false) {
                Log::info("Se imprimió correctamente.");
                break;
            } elseif ($statusCode === 404) {
                Log::info("Recurso no encontrado.");
                break;
            } else {
                if ($intento == $reintentos) {
                    $error = curl_error($ch);
                    $result = [
                        "error" => true,
                        "mensaje" => "Ha ocurrido un error al momento de imprimir, por favor comuníquese con soporte.",
                        "detalle" => $error
                    ];
                    Log::error("Error al imprimir en el intento $intento: $error");
                } else {
                    Log::warning("Intento $intento fallido, reintentando...");
                }
            }
        }

        curl_close($ch);
    }
    /* Funciones referente a la creación de orden de pedido y sus detalles - Final */

    public function getDatosCabeceraOrdenPedido(Request $request)
    {
        $datosCabeceraOrdenPedido = DB::select(
            'SELECT cop.odp_monto_total,cop.IDCabeceraOrdenPedido,cop.odp_correlativo,cop.created_at,me.mesa_descripcion 
        FROM cabecera_orden_pedido cop INNER JOIN mesa me 
        ON cop.IDMesa = me.IDMesa WHERE cop.IDMesa = :idMesaSeleccionadaActual AND cop.IDStatus = 3',
            ["idMesaSeleccionadaActual" => $request->idMesaSeleccionadaActual]
        );

        $arregloIdentificador = [];
        $arregloSumaTotal = array();

        foreach ($datosCabeceraOrdenPedido as $datosCabeceraOrdenPedidoIdentificador) {
            array_push($arregloIdentificador, $datosCabeceraOrdenPedidoIdentificador->IDCabeceraOrdenPedido);
            array_push($arregloSumaTotal, $datosCabeceraOrdenPedidoIdentificador->odp_monto_total);
            $mesaDescripcion = $datosCabeceraOrdenPedidoIdentificador->mesa_descripcion;
            $fechaUltimoPedido = $datosCabeceraOrdenPedidoIdentificador->created_at;
        }

        $valorSeparadoComas = implode(",", $arregloIdentificador);

        if (!empty($datosCabeceraOrdenPedido)) {
            $datosDetalleOrdenPedido = DB::SELECT("SELECT pl.id_productoLimpieza,ca.nombre_categoria,SUM(dop.dop_cantidad) AS dop_cantidad,
            dop.IDDetalleOrdenPedido,pl.nombre_productoLimpieza,dop.dop_precio,SUM(dop.dop_total) AS dop_total 
            FROM detalle_orden_pedido dop
            INNER JOIN productos_limpieza pl ON dop.IDProducto = pl.id_productoLimpieza
            INNER JOIN categorias ca ON pl.id_categoria = ca.id_categoria WHERE
            IDCabeceraOrdenPedido IN (" . $valorSeparadoComas . ") GROUP BY pl.id_productoLimpieza");
        }

        $jsonEnvioArreglo = [
            "odp_monto_total" => array_sum($arregloSumaTotal),
            "odp_correlativo" => null,
            "created_at"      => $fechaUltimoPedido,
            "mesa_descripcion" => $mesaDescripcion,
            "detalleOrdenPedido" => $datosDetalleOrdenPedido
        ];

        return $this->response_json(200, "", array($jsonEnvioArreglo));
    }

    public function getDatosCabeceraOrdenPedidoMesas()
    {
        $datosCabeceraOrdenPedidoMesas = DB::SELECT(
            'SELECT me.IDMesa 
            FROM cabecera_orden_pedido cop INNER JOIN mesa me 
            ON cop.IDMesa = me.IDMesa WHERE cop.IDStatus = 3 GROUP BY me.IDMesa'
        );

        $jsonEnvioArregloGeneral = [];

        foreach ($datosCabeceraOrdenPedidoMesas as $datosCabeceraOrdenPedidoMesa) {

            $datosCabeceraOrdenPedido = DB::select(
            'SELECT cop.odp_monto_total,cop.IDCabeceraOrdenPedido,cop.odp_correlativo,cop.created_at,me.mesa_descripcion 
            FROM cabecera_orden_pedido cop INNER JOIN mesa me 
            ON cop.IDMesa = me.IDMesa WHERE cop.IDMesa = :idMesaSeleccionadaActual AND cop.IDStatus = 3 AND (cop.odp_despachado = 0 OR cop.odp_despachado IS NULL)',
                ["idMesaSeleccionadaActual" => $datosCabeceraOrdenPedidoMesa->IDMesa]
            );

            if(!empty($datosCabeceraOrdenPedido)) {
                $arregloIdentificador = [];
                $arregloSumaTotal = array();
        
                foreach ($datosCabeceraOrdenPedido as $datosCabeceraOrdenPedidoIdentificador) {
                    array_push($arregloIdentificador, $datosCabeceraOrdenPedidoIdentificador->IDCabeceraOrdenPedido);
                    array_push($arregloSumaTotal, $datosCabeceraOrdenPedidoIdentificador->odp_monto_total);
                    $mesaDescripcion = $datosCabeceraOrdenPedidoIdentificador->mesa_descripcion;
                    $fechaUltimoPedido = $datosCabeceraOrdenPedidoIdentificador->created_at;
                }
        
                $valorSeparadoComas = implode(",", $arregloIdentificador);
        
                if (!empty($datosCabeceraOrdenPedido)) {
                    $datosDetalleOrdenPedido = DB::SELECT("SELECT pl.id_productoLimpieza,ca.nombre_categoria,SUM(dop.dop_cantidad) AS dop_cantidad,
                    dop.IDDetalleOrdenPedido,pl.nombre_productoLimpieza,dop.dop_precio,SUM(dop.dop_total) AS dop_total,dop.observacion
                    FROM detalle_orden_pedido dop
                    INNER JOIN productos_limpieza pl ON dop.IDProducto = pl.id_productoLimpieza
                    INNER JOIN categorias ca ON pl.id_categoria = ca.id_categoria WHERE
                    IDCabeceraOrdenPedido IN (" . $valorSeparadoComas . ") GROUP BY pl.id_productoLimpieza");
                }
        
                $jsonEnvioArreglo = [
                    "odp_monto_total" => array_sum($arregloSumaTotal),
                    "odp_correlativo" => null,
                    "created_at"      => $fechaUltimoPedido,
                    "mesa_descripcion" => $mesaDescripcion,
                    "detalleOrdenPedido" => $datosDetalleOrdenPedido,
                    "id_cabedera_ordenes_pedido" => $valorSeparadoComas
                ];
    
                array_push($jsonEnvioArregloGeneral,$jsonEnvioArreglo);
            }
        }

        return $this->response_json(200, "", array($jsonEnvioArregloGeneral));
    }

    public function getDatosDetalleOrdenPedido(Request $request)
    {
        $datosCabeceraOrdenPedido = DB::select('SELECT cop.odp_correlativo,cop.created_at,me.mesa_descripcion 
        FROM cabecera_orden_pedido cop INNER JOIN mesa me 
        ON cop.IDMesa = me.IDMesa WHERE cop.IDMesa = :idMesaSeleccionadaActual ORDER by cop.IDCabeceraOrdenPedido 
        DESC LIMIT 1', ["idMesaSeleccionadaActual" => $request->idMesaSeleccionadaActual]);

        return $this->response_json(200, "", $datosCabeceraOrdenPedido);
    }

    public function cambiarStatusMesa(Request $request)
    {
        $updateStatusMesa = DB::UPDATE("UPDATE mesa SET IDStatus = :IDStatus WHERE IDMesa = :IDMesa", ["IDStatus" => $request->IDStatus, "IDMesa" => $request->IDMesa]);
        return $this->response_json(200, "", $updateStatusMesa);
    }

    public function actualizarDatosDespachoCocina(Request $request)
    {
        $fechaHoy = date("Y-m-d H:i:s");
        //$convertirFechaIndicada = str_replace("+"," ",$request->hora_inicial_pedido);

        $explode = explode(" ",$request->hora_inicial_pedido);
        $explode1 = explode(":",$explode[1]);

        $explodeFechaHoy = explode(" ",$fechaHoy);
        $explode1FechaHoy = explode(":",$explodeFechaHoy[1]);

        //Convertir fecha obtenida en minutos para resta
        $horasMinutos = $explode1[0] * 60 + $explode1[1];//obtenermos los minutos de la fecha recibida
        $horasMinutosFechaHoy = $explode1FechaHoy[0] * 60 + $explode1FechaHoy[1];//obtenemos la fecha actual

        $horasMinutosFechaHoyInt = (int) $horasMinutosFechaHoy;
        $horasMinutosInt = (int) $horasMinutos;

        $restaTiempoTranscurrido = $horasMinutosFechaHoyInt - $horasMinutosInt;

        $updateStatusCabeceraOrdenPedidoDespachado = DB::UPDATE("UPDATE cabecera_orden_pedido SET odp_despachado = 1,odp_tiempo_despacho = ".$restaTiempoTranscurrido." WHERE IDCabeceraOrdenPedido IN (".$request->id_cabecera_ordenes_pedido.")");
        return $this->response_json(200, "", $updateStatusCabeceraOrdenPedidoDespachado);
    }

    public function cobrarPedidoMesaSeleccionada(Request $request)
    {
        try {
            $tipoDocumento = 'boleta_electronica';
            $cabeceraFactura = $this->crearCabeceraFactura($request);
            $this->actualizarCorrelativo($cabeceraFactura->id, $tipoDocumento, $cabeceraFactura->cfac_ordenes_pedido);
            $detalleOrdenPedidoJson = $this->obtenerDetalleOrdenPedido($cabeceraFactura->cfac_ordenes_pedido);

            Log::info("APPLY_PRINTER_LOCAL: " . Config::get('app.APPLY_PRINTER_LOCAL'));
            if ( Config::get('app.APPLY_PRINTER_LOCAL', false) ) {
                $payload = $this->prepararDatosImpresion($cabeceraFactura->cfac_ordenes_pedido, 'boleta_electronica', $cabeceraFactura, $detalleOrdenPedidoJson);

                try {
                    $this->enviarImpresion($payload);
                } catch (\Exception $printException) {
                    Log::error("Error al intentar imprimir: " . $printException->getMessage());
                }

                $mensajeRespuesta = 'Boleta electrónica y impresión realizada con éxito';
            } else {
                $payload = $cabeceraFactura;
                $mensajeRespuesta = 'Boleta electrónica creada con éxito';
            }

            return $this->response_json(200, $mensajeRespuesta, $payload);
        } catch (\Exception $e) {
            Log::error("Error al crear la boleta electrónica: " . $e->getMessage());
            return $this->response_json(500, "Ha ocurrido un error durante la creación de la boleta electrónica.", [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function totalRegistrosCierreCaja()
    {
        $notasVentaSuma = DB::select('SELECT ROUND(SUM(cf.cfac_monto_total),2) as cfac_monto_total_suma, COUNT(cf.IDCabeceraFactura) AS cfac_cantidad_total, 
        fp.IDFormaPago, fp.fp_descripcion FROM cabecera_factura cf
        INNER JOIN forma_pago fp ON cf.IDFormaPago = fp.IDFormaPago
        WHERE cf.IDStatus = 4 AND cfac_status_control = 0 GROUP BY cf.IDFormaPago');

        $notasVentaSumaTotal = DB::select('SELECT ROUND(SUM(cf.cfac_monto_total),2) as cfac_monto_total_suma 
        FROM cabecera_factura cf WHERE cf.IDStatus = 4 AND cfac_status_control = 0');
        $montoTotalGastos = DB::SELECT('SELECT ROUND(SUM(ga.preciot_gasto),2) as ga_monto_total 
        FROM gastos ga WHERE ga.control_gasto = 0');
        $montoUltimoCuadreCaja = DB::SELECT('SELECT ROUND(cc_monto,2) as cc_monto_ultimo FROM caja_cuadre 
        ORDER BY IDCajaCuadre DESC LIMIT 1');

        $response["cfac_cantidades_total"] = $notasVentaSuma;
        $response["cfac_monto_total_general"] = $notasVentaSumaTotal[0]->cfac_monto_total_suma == null ? 0 : $notasVentaSumaTotal[0]->cfac_monto_total_suma;
        $response["ga_monto_total_general"] = $montoTotalGastos[0]->ga_monto_total == null ? 0 : $montoTotalGastos[0]->ga_monto_total;
        $response["monto_total_ultimo_cuadre"] = $montoUltimoCuadreCaja[0]->cc_monto_ultimo == null ? 0 : $montoUltimoCuadreCaja[0]->cc_monto_ultimo;

        return $this->response_json(200, "", $response);
    }

    public function actualizarTotalRegistrosCierreCaja(Request $request) {
        $cabeceraFactura = new CajaCuadreModel();
        $cabeceraFactura->cc_monto = $request->calculoMontoCaja;
        $cabeceraFactura->cc_monto_efectivo = $request->cantidadSumaTotalEfectivo;
        $cabeceraFactura->cc_monto_izipay = $request->cantidadSumaTotalIzipay;
        $cabeceraFactura->cc_monto_interbank = $request->cantidadSumaTotalInterbank;
        $cabeceraFactura->IDPeriodo = $this->retornarPeriodoActivo();
        $cabeceraFactura->save();

        $updateStatusTotalGastos = DB::UPDATE("UPDATE gastos SET control_gasto = 1 WHERE 
        control_gasto = 0");

        $updateStatusTotalCabeceraFactura = DB::UPDATE("UPDATE cabecera_factura SET cfac_status_control = 1 WHERE 
        cfac_status_control = 0");

        //EL ULTIMO PERIODO CARGADO ACTUALIZAR AL CERRAR CAJA
        $fecha = Carbon::now();

        $updateStatusPeriodo = DB::UPDATE("UPDATE periodo SET prd_fechacierre = ?,prd_usuariocierre=1,IDStatus=4 WHERE IDStatus = 3",[
            $fecha->toDateTimeString()
        ]);

        $updatePermissionCrearGasto = DB::UPDATE("UPDATE permissions SET name = 'crear_gastos1' WHERE id = 113");

        return $this->response_json(200, "", $updateStatusTotalCabeceraFactura);
    }

    public function abrirPeriodoVenta() {
        $fecha = Carbon::now();

        $periodo = new PeriodoModel();
        $periodo->prd_fechaapertura = $fecha->toDateTimeString();
        $periodo->prd_fechacierre = null;
        $periodo->prd_usuarioapertura = 1;
        $periodo->prd_usuariocierre = null;
        $periodo->IDStatus = 3;
        $periodo->save();

        $updatePermissionCrearGasto = DB::UPDATE("UPDATE permissions SET name = 'crear_gastos' WHERE id = 113");

        return $this->response_json(200, "", $periodo);
    }

    public function verificarPeriodoActivo(Request $request) {
        $periodo = DB::select('SELECT * FROM periodo WHERE IDStatus = 3');

        return $this->response_json(200, "", $periodo);
    }

    public function retornarPeriodoActivo() {
        $periodo = DB::select('SELECT IDPeriodo FROM periodo WHERE IDStatus = 3');
        return $periodo[0]->IDPeriodo;
    }

    protected function response_json($status_code = 0, $msg = "", $data = null)
    {
        return response()->json([
            "error" => [
                'status_code' => $status_code,
                'msg' => $msg
            ],
            "data" => $data,
        ]);
    }

    public function store(Request $request)
    {
        $datos =  array(
            "nombre_unidadMedida" => strtoupper($request->input("nombre_unidadMedida")),
            "estado_unidadMedida" => $request->input("estado_unidadMedida"),
            "usuario_unidadMedida" => $request->input("usuario_unidadMedida")
        );

        if (!empty($datos)) {
            $validar = \Validator::make($datos, [
                "nombre_unidadMedida" => 'required|regex:/^[,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                "estado_unidadMedida" => 'required',
                "usuario_unidadMedida" => 'required'
            ]);

            if ($validar->fails()) { //SI LA VALIDACIÓN FALLA - ENVIAMOS UN MENSAJE EN LA PANTALLA
                return redirect('/unidadesMedida')->with("no-validacion", "");
            } else {
                $UnidadMedida = new UnidadesMedidaModel();
                $UnidadMedida->nombre_unidadMedida = $datos["nombre_unidadMedida"];
                $UnidadMedida->estado_unidadMedida = $datos["estado_unidadMedida"];
                $UnidadMedida->id_usuario = $datos["usuario_unidadMedida"];
                $UnidadMedida->save();

                return redirect('/unidadesMedida')->with("ok-crear", "");
            }
        } else {
            return redirect('/unidadesMedida')->with("error", "");
        }
    }

    public function destroy($id)
    {
        $validar = UnidadesMedidaModel::where(["id_unidadMedida" => $id])->get();
        if (!empty($validar)) {

            $unidadMedida = UnidadesMedidaModel::where(["id_unidadMedida" => $validar[0]["id_unidadMedida"]])->delete();
            //Responder al AJAX de JS
            return "ok";
        } else {
            return redirect("/unidadesMedida")->with("no-borrar", "");
        }
    }

    public function show($id, Request $request)
    {
        $unidadMedida = UnidadesMedidaModel::where("id_unidadMedida", $id)->get();
        $administradores = AdministradoresModel::all();
        $estado = DB::select('SELECT * FROM estado');
        $unidadesMedida =   DB::select('SELECT UM.id_unidadMedida,UM.nombre_unidadMedida,UM.estado_unidadMedida,
                                        UM.id_usuario,UM.created_at,UM.updated_at,E.nombre_estado,U.name FROM
                                        unidades_medida UM INNER JOIN estado E ON UM.estado_unidadMedida = E.id_estado
                                        LEFT JOIN users U ON UM.id_usuario = U.id ORDER BY UM.id_unidadMedida DESC');
        $unidadMedida_estado = DB::select('SELECT * FROM unidades_medida UM INNER JOIN estado E ON UM.estado_unidadMedida = E.id_estado WHERE id_unidadMedida = ?', [$id]);

        if (count($unidadMedida) != 0) {
            return view("paginas.unidadesMedida", array(
                "status" => 200, "unidadMedida" => $unidadMedida, "administradores" => $administradores,
                "estado" => $estado, "unidadesMedida" => $unidadesMedida, "unidadMedida_estado" => $unidadMedida_estado
            ));
        } else {
            return view("paginas.unidadesMedida", array(
                "status" => 404, "unidadMedida" => $unidadMedida, "administradores" => $administradores,
                "estado" => $estado, "unidadesMedida" => $unidadesMedida, "unidadMedida_estado" => $unidadMedida_estado
            ));
        }
    }

    public function update($id, Request $request)
    {
        $datos =  array(
            "nombre_unidadMedida" => strtoupper($request->input("nombre_unidadMedida")),
            "estado_unidadMedida" => $request->input("estado_unidadMedida"),
            "usuario_unidadMedida" => $request->input("usuario_unidadMedida")
        );

        if (!empty($datos)) {
            $validar = \Validator::make($datos, [
                "nombre_unidadMedida" => 'required|regex:/^[,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                "estado_unidadMedida" => 'required',
                "usuario_unidadMedida" => 'required'
            ]);

            if ($validar->fails()) {
                return redirect("/unidadesMedida")->with("no-validacion", "");
            } else {
                $datos =  array(
                    "nombre_unidadMedida" => strtoupper($request->input("nombre_unidadMedida")),
                    "estado_unidadMedida" => $request->input("estado_unidadMedida"),
                    "id_usuario" => $request->input("usuario_unidadMedida")
                );

                $unidadMedida = UnidadesMedidaModel::where('id_unidadMedida', $id)->update($datos);
                return redirect("/unidadesMedida")->with("ok-editar", "");
            }
        } else {
            return redirect("/unidadesMedida")->with("error", "");
        }
    }

    public function createPDF(Request $request)
    {

        $unidadesMedida =   DB::select('SELECT UM.id_unidadMedida,UM.nombre_unidadMedida,UM.estado_unidadMedida,
                                        UM.id_usuario,UM.created_at,UM.updated_at,E.nombre_estado,U.name FROM
                                        unidades_medida UM INNER JOIN estado E ON UM.estado_unidadMedida = E.id_estado
                                        LEFT JOIN users U ON UM.id_usuario = U.id ORDER BY UM.id_unidadMedida DESC');

        // compartir datos para ver
        view()->share('unidadesMedida', $unidadesMedida);

        $pdf = PDF::loadView('paginas.reportesUnidadesMedida', $unidadesMedida);

        // descargar archivo PDF con método de descarga
        return $pdf->setPaper('a4', 'landscape')->stream('unidadesMedida.pdf');
    }

    public function createEXCEL(Request $request)
    {
        return Excel::download(new UnidadesMedidaExport, 'unidadesMedida.xlsx');
    }
}
