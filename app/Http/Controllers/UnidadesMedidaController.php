<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdministradoresModel;
use App\UnidadesMedidaModel;
use App\CabeceraFacturaModel;
use App\CabeceraOrdenPedidoModel;
use App\DetalleOrdenPedidoModel;
use App\CajaCuadreModel;
use App\PeriodoModel;
use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */
use PDF;/* Apuntamos al modelo que existe por defecto para obtener información en PDF */
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UnidadesMedidaExport;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
        $categorias = DB::select('SELECT * FROM categorias');

        return $this->response_json(200, "", $categorias);
    }

    public function listarFormaPago()
    {
        $formaPago = DB::select('SELECT * FROM forma_pago');

        return $this->response_json(200, "", $formaPago);
    }

    public function listarProductoPorCategoria(Request $request)
    {
        $productoCategoria = DB::select('SELECT * FROM productos_limpieza WHERE id_categoria = ?', [$request->idCategoriaSelected]);

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
            if ($request->clave !== 'abc123$$') {
                return $this->response_json(400, "Error de credencial");
            }

            return $this->response_json(200, "", $usuario);
        }
    }

    public function createOrdenPedido(Request $request)
    {
        $cabeceraOrdenPedido = new CabeceraOrdenPedidoModel();
        $cabeceraOrdenPedido->odp_monto_total = $request->odp_monto_total;
        $cabeceraOrdenPedido->IDMesa = $request->IDMesa;
        $cabeceraOrdenPedido->IDStatus = $request->IDStatus;
        $cabeceraOrdenPedido->IDPeriodo = $this->retornarPeriodoActivo();
        $cabeceraOrdenPedido->save();

        $insertedId = $cabeceraOrdenPedido->id;
        $numero_final = str_pad($insertedId, 10, '0', STR_PAD_LEFT);
        DB::UPDATE("UPDATE cabecera_orden_pedido SET odp_correlativo = :correlativo WHERE IDCabeceraOrdenPedido = :idInsert", ["correlativo" => $numero_final, "idInsert" => $insertedId]);

        foreach ($request->detalleOrdenPedido as $detalleOrdenPedidoValue) {
            $detalleOrdenPedido = new DetalleOrdenPedidoModel();
            $detalleOrdenPedido->IDCabeceraOrdenPedido = $insertedId;
            $detalleOrdenPedido->IDProducto = $detalleOrdenPedidoValue['id_producto'];
            $detalleOrdenPedido->dop_cantidad = $detalleOrdenPedidoValue['cantidad'];
            $detalleOrdenPedido->dop_precio = $detalleOrdenPedidoValue['precio'];
            $detalleOrdenPedido->dop_total = $detalleOrdenPedidoValue['total'];
            $detalleOrdenPedido->save();
        }

        return $this->response_json(200, "", $cabeceraOrdenPedido);
    }

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

    public function cobrarPedidoMesaSeleccionada(Request $request)
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

        $validaciónPorTarjeta = $request->valueFormaPago == 2 ? 
        $ordenesPedidoSuma[0]->odp_monto_total_suma - $calculoTotalPorcenaje : 
        $ordenesPedidoSuma[0]->odp_monto_total_suma;

        $cabeceraFactura = new CabeceraFacturaModel();
        $cabeceraFactura->cfac_ordenes_pedido = $valorSeparadoComas;
        $cabeceraFactura->cfac_monto_total = $validaciónPorTarjeta;
        $cabeceraFactura->IDFormaPago = $request->valueFormaPago;
        $cabeceraFactura->IDStatus = 4;
        $cabeceraFactura->IDPeriodo = $this->retornarPeriodoActivo();
        $cabeceraFactura->save();

        $insertedId = $cabeceraFactura->id;
        $numero_final = str_pad($insertedId, 10, '0', STR_PAD_LEFT);
        DB::UPDATE("UPDATE cabecera_factura SET cfac_correlativo = :correlativo WHERE 
        IDCabeceraFactura = :idInsert", ["correlativo" => $numero_final, "idInsert" => $insertedId]);
        DB::UPDATE("UPDATE cabecera_orden_pedido SET IDStatus = 4 WHERE 
        IDCabeceraOrdenPedido IN (" . $valorSeparadoComas . ")");

        return $this->response_json(200, "", $cabeceraFactura);
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
            "nombre_unidadMedida" => $request->input("nombre_unidadMedida"),
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
            "nombre_unidadMedida" => $request->input("nombre_unidadMedida"),
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
                    "nombre_unidadMedida" => $request->input("nombre_unidadMedida"),
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
