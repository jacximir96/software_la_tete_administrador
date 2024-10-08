<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdministradoresModel;
use App\UnidadesMedidaModel;
use App\CabeceraFacturaModel;
use App\CabeceraOrdenPedidoModel;
use App\DetalleOrdenPedidoModel;
use App\CajaCuadreModel;
use App\GastosModel;
use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */
use PDF;/* Apuntamos al modelo que existe por defecto para obtener información en PDF */
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GastosExport;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class GastosController extends Controller
{
    public function index()
    {

        $administradores = AdministradoresModel::all();
        $estado = DB::select('SELECT * FROM estado');
        $gastos =   DB::select('SELECT GA.id_gasto,GA.descripcion_gasto,GA.estado_gasto,
                                        GA.id_usuario,GA.created_at,GA.updated_at,E.nombre_estado,U.name,GA.precio_gasto,
                                        GA.cantidad_gasto,GA.preciot_gasto,UM.nombre_unidadMedida FROM
                                        gastos GA INNER JOIN unidades_medida UM ON GA.id_unidadMedida = UM.id_unidadMedida
                                        INNER JOIN estado E ON GA.estado_gasto = E.id_estado
                                        LEFT JOIN users U ON GA.id_usuario = U.id ORDER BY GA.id_gasto DESC');
        $unidadesMedida = UnidadesMedidaModel::all();

        return view("paginas.gastos", array(
            "administradores" => $administradores, "estado" => $estado,
            "gastos" => $gastos,"unidadesMedida" => $unidadesMedida
        ));
    }

    public function store(Request $request)
    {
        $datos =  array(
            "descripcion_gasto" => strtoupper($request->input("descripcion_gasto")),
            "cantidad_gasto" => $request->input("cantidad_gasto"),
            "precio_gasto" => $request->input("precio_gasto"),
            "preciot_gasto" => $request->input("preciot_gasto"),
            "estado_gasto" => 1,
            "usuario_gasto" => $request->input("usuario_gasto"),
            "unidadMedida_gasto"=>$request->input("unidadMedida_gasto"),
        );

        if (!empty($datos)) {
            $validar = \Validator::make($datos, [
                "descripcion_gasto" => 'required|regex:/^[,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                "cantidad_gasto" => 'required',
                "precio_gasto" => 'required',
                "preciot_gasto" => 'required',
                "usuario_gasto" => 'required',
                "unidadMedida_gasto" => 'required'
            ]);

            if ($validar->fails()) { //SI LA VALIDACIÓN FALLA - ENVIAMOS UN MENSAJE EN LA PANTALLA
                return redirect('/gastos')->with("no-validacion", "");
            } else {
                $gasto = new GastosModel();
                $gasto->descripcion_gasto = $datos["descripcion_gasto"];
                $gasto->cantidad_gasto = $datos["cantidad_gasto"];
                $gasto->precio_gasto = $datos["precio_gasto"];
                $gasto->preciot_gasto = $datos["preciot_gasto"];
                $gasto->estado_gasto = $datos["estado_gasto"];
                $gasto->id_usuario = $datos["usuario_gasto"];
                $gasto->id_unidadMedida = $datos["unidadMedida_gasto"];
                $gasto->IDPeriodo = $this->retornarPeriodoActivo();
                $gasto->save();

                return redirect('/gastos')->with("ok-crear", "");
            }
        } else {
            return redirect('/gastos')->with("error", "");
        }
    }

    public function retornarPeriodoActivo() {
        $periodo = DB::select('SELECT IDPeriodo FROM periodo WHERE IDStatus = 3');
        return $periodo[0]->IDPeriodo;
    }

    public function destroy($id)
    {
        $validar = GastosModel::where(["id_gasto" => $id])->get();
        if (!empty($validar)) {

            $gasto = GastosModel::where(["id_gasto" => $validar[0]["id_gasto"]])->delete();
            //Responder al AJAX de JS
            return "ok";
        } else {
            return redirect("/gastos")->with("no-borrar", "");
        }
    }

    public function show($id, Request $request)
    {
        $gasto = GastosModel::where("id_gasto", $id)->get();
        $administradores = AdministradoresModel::all();
        $estado = DB::select('SELECT * FROM estado');
        $gastos =   DB::select('SELECT GA.id_gasto,GA.descripcion_gasto,GA.estado_gasto,
                                GA.id_usuario,GA.created_at,GA.updated_at,E.nombre_estado,U.name,GA.precio_gasto,
                                GA.cantidad_gasto,GA.preciot_gasto,UM.nombre_unidadMedida FROM
                                gastos GA INNER JOIN unidades_medida UM ON GA.id_unidadMedida = UM.id_unidadMedida
                                INNER JOIN estado E ON GA.estado_gasto = E.id_estado
                                LEFT JOIN users U ON GA.id_usuario = U.id ORDER BY GA.id_gasto DESC');
        $unidadesMedida = UnidadesMedidaModel::all();
        $gasto_unidadMedida = DB::select('SELECT * FROM gastos GA INNER JOIN unidades_medida UM ON GA.id_unidadMedida = UM.id_unidadMedida
                                         WHERE GA.id_gasto = ?',[$id]);
        $gasto_estado = DB::select('SELECT * FROM gastos GA INNER JOIN estado E ON GA.estado_gasto = E.id_estado WHERE id_gasto = ?', [$id]);

        if (count($gasto) != 0) {
            return view("paginas.gastos", array(
                "status" => 200, "gasto" => $gasto, "administradores" => $administradores,
                "estado" => $estado, "gastos" => $gastos, "gasto_estado" => $gasto_estado,
                "unidadesMedida" => $unidadesMedida, "gasto_unidadMedida" => $gasto_unidadMedida
            ));
        } else {
            return view("paginas.gastos", array(
                "status" => 404, "gasto" => $gasto, "administradores" => $administradores,
                "estado" => $estado, "gastos" => $gastos, "gasto_estado" => $gasto_estado,
                "unidadesMedida" => $unidadesMedida, "gasto_unidadMedida" => $gasto_unidadMedida
            ));
        }
    }

    public function update($id, Request $request)
    {
        $datos =  array(
            "descripcion_gasto" => strtoupper($request->input("descripcion_gasto")),
            "cantidad_gasto" => $request->input("cantidad_gasto"),
            "precio_gasto" => $request->input("precio_gasto"),
            "preciot_gasto" => $request->input("preciot_gasto"),
            "usuario_gasto" => $request->input("usuario_gasto"),
            "unidadMedida_gasto"=>$request->input("unidadMedida_gasto"),
        );

        if (!empty($datos)) {
            $validar = \Validator::make($datos, [
                "descripcion_gasto" => 'required|regex:/^[,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                "cantidad_gasto" => 'required',
                "precio_gasto" => 'required',
                "preciot_gasto" => 'required',
                "usuario_gasto" => 'required',
                "unidadMedida_gasto" => 'required'
            ]);

            if ($validar->fails()) {
                return redirect("/gastos")->with("no-validacion", "");
            } else {
                $datos =  array(
                    "descripcion_gasto" => strtoupper($request->input("descripcion_gasto")),
                    "cantidad_gasto" => $request->input("cantidad_gasto"),
                    "precio_gasto" => $request->input("precio_gasto"),
                    "preciot_gasto" => $request->input("preciot_gasto"),
                    "estado_gasto" => 1,
                    "id_usuario" => $request->input("usuario_gasto"),
                    "id_unidadMedida" => $request->input("unidadMedida_gasto")
                );

                $gasto = GastosModel::where('id_gasto', $id)->update($datos);
                return redirect("/gastos")->with("ok-editar", "");
            }
        } else {
            return redirect("/gastos")->with("error", "");
        }
    }

    public function createPDF(Request $request)
    {

        $gastos =   DB::select('SELECT GA.id_gasto,GA.descripcion_gasto,GA.estado_gasto,
                                GA.id_usuario,GA.created_at,GA.updated_at,E.nombre_estado,U.name,GA.precio_gasto,
                                GA.cantidad_gasto,GA.preciot_gasto,UM.nombre_unidadMedida FROM
                                gastos GA INNER JOIN unidades_medida UM ON GA.id_unidadMedida = UM.id_unidadMedida
                                INNER JOIN estado E ON GA.estado_gasto = E.id_estado
                                LEFT JOIN users U ON GA.id_usuario = U.id 
                                WHERE GA.control_gasto = 0 ORDER BY GA.id_gasto DESC');

        // compartir datos para ver
        view()->share('gastos', $gastos);

        $pdf = PDF::loadView('paginas.reportesGastos', $gastos);

        // descargar archivo PDF con método de descarga
        return $pdf->setPaper('a4', 'landscape')->stream('gastos.pdf');
    }

    public function createEXCEL(Request $request)
    {

        return Excel::download(new GastosExport, 'gastos.xlsx');
    }
}
