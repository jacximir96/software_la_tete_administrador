<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdministradoresModel;
use App\ProductosLimpiezaModel;
use App\KardexLimpiezaModel;
use App\EmpleadosModel;
use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */
use PDF;/* Apuntamos al modelo que existe por defecto para obtener información en PDF */
use Carbon\Carbon;


class KardexLimpiezaController extends Controller
{
    public function index(){

        $administradores = AdministradoresModel::all();
        $empleados = EmpleadosModel::all();
        $productosLimpiezaEntradas = DB::select('SELECT * FROM productos_limpieza');
        $productosLimpieza = DB::select('SELECT * FROM productos_limpieza WHERE stock_productoLimpieza <> 0');
        $kardexLimpiezaTotal = DB::select('SELECT P.id_productoLimpieza,P.nombre_productoLimpieza,P.descripcion_productoLimpieza,K.id_kardexLimpieza,K.id_productoLimpieza,K.entradas_kardexLimpieza,K.restante_kardexLimpieza,
                                           K.salidas_kardexLimpieza,K.created_at,E.nombres_empleado,E.apellidos_empleado FROM kardex_limpieza K INNER JOIN productos_limpieza P ON K.id_productoLimpieza = P.id_productoLimpieza
                                           LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                           ORDER BY K.id_kardexLimpieza DESC');

        $fecha = Carbon::now();
        $mfecha = $fecha->month;
        $afecha = $fecha->year;

        return view('paginas.kardexLimpieza', array("administradores"=>$administradores,"productosLimpieza"=>$productosLimpieza,
                                                    "kardexLimpiezaTotal"=>$kardexLimpiezaTotal,"productosLimpiezaEntradas"=>$productosLimpiezaEntradas,
                                                    "mfecha"=>$mfecha,"afecha"=>$afecha,"empleados"=>$empleados));
    }

    public function buscar_mes(Request $request){
        $datos = array("txtMes"=>$request->input("txtMes"));
        $fecha = Carbon::parse($datos["txtMes"]);
        $mfecha = $fecha->month;
        $afecha = $fecha->year;

        $administradores = AdministradoresModel::all();
        $empleados = EmpleadosModel::all();
        $productosLimpiezaEntradas = DB::select('SELECT * FROM productos_limpieza');
        $productosLimpieza = DB::select('SELECT * FROM productos_limpieza WHERE stock_productoLimpieza <> 0');
        $kardexLimpiezaTotal = DB::select('SELECT P.id_productoLimpieza,P.nombre_productoLimpieza,P.descripcion_productoLimpieza,K.id_kardexLimpieza,K.id_productoLimpieza,K.entradas_kardexLimpieza,K.restante_kardexLimpieza,
                                            K.salidas_kardexLimpieza,K.created_at,E.nombres_empleado,E.apellidos_empleado FROM kardex_limpieza K INNER JOIN productos_limpieza P ON K.id_productoLimpieza = P.id_productoLimpieza
                                            LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                            WHERE MONTH(K.created_at) = :mfecha AND YEAR(K.created_at) = :afecha
                                            ORDER BY K.id_kardexLimpieza DESC',["mfecha"=>$mfecha,"afecha"=>$afecha]);

        return view('paginas.kardexLimpieza', array("administradores"=>$administradores,"productosLimpieza"=>$productosLimpieza,"empleados"=>$empleados,
                    "kardexLimpiezaTotal"=>$kardexLimpiezaTotal,"productosLimpiezaEntradas"=>$productosLimpiezaEntradas,"fecha"=>$fecha,"mfecha"=>$mfecha,"afecha"=>$afecha));
    }

    public function entradas_kardex(Request $request){
        $datos =  array("id_productoLimpieza"=>$request->input("seleccionar_producto"),
                        "motivo_kardex"=>$request->input("seleccionar_motivo"),
                        "cantidad_kardex"=>$request->input("seleccionar_cantidad"),
                        "restante_kardexLimpieza"=>$request->input("seleccionar_stock"),
                        "id_usuario"=>$request->input("usuario_entradaProducto"));

        $extraer_stock =    DB::select('SELECT stock_productoLimpieza FROM productos_limpieza WHERE 
                                        id_productoLimpieza = ?',[$request->input("seleccionar_producto")]);

        $kardexLimpieza = new KardexLimpiezaModel();
        $kardexLimpieza->id_productoLimpieza = $datos["id_productoLimpieza"];
        $kardexLimpieza->motivo_kardexLimpieza = $datos["motivo_kardex"];
        $kardexLimpieza->entradas_kardexLimpieza = $datos["cantidad_kardex"];
        $kardexLimpieza->restante_kardexLimpieza = $datos["restante_kardexLimpieza"] + $datos["cantidad_kardex"];
        $kardexLimpieza->id_usuario = $datos["id_usuario"];
        $kardexLimpieza->save();

        $datos1 = array("stock_productoLimpieza"=>$extraer_stock[0]->stock_productoLimpieza + $request->input("seleccionar_cantidad"));

        $productoLimpieza = ProductosLimpiezaModel::where('id_productoLimpieza',$datos["id_productoLimpieza"])->update($datos1);

        return redirect('/kardexLimpieza')->with("entrada-producto","");

    }

    public function salidas_kardex(Request $request){
        $datos =  array("id_productoLimpieza"=>$request->input("seleccionar_producto"),
                        "motivo_kardex"=>$request->input("seleccionar_motivo"),
                        "cantidad_kardex"=>$request->input("seleccionar_cantidad"),
                        "restante_kardexLimpieza"=>$request->input("seleccionar_stock"),
                        "id_empleado"=>$request->input("seleccionar_empleado"),
                        "id_usuario"=>$request->input("usuario_entradaProducto"));

        $extraer_stock =    DB::select('SELECT stock_productoLimpieza FROM productos_limpieza WHERE 
                                        id_productoLimpieza = ?',[$request->input("seleccionar_producto")]);

        if(($extraer_stock[0]->stock_productoLimpieza - $request->input("seleccionar_cantidad") < 0)){
            return redirect('/kardexLimpieza')->with("stock-negativo","");
        }else{
            $kardexLimpieza = new KardexLimpiezaModel();
            $kardexLimpieza->id_productoLimpieza = $datos["id_productoLimpieza"];
            $kardexLimpieza->motivo_kardexLimpieza = $datos["motivo_kardex"];
            $kardexLimpieza->salidas_kardexLimpieza = $datos["cantidad_kardex"];
            $kardexLimpieza->restante_kardexLimpieza = $datos["restante_kardexLimpieza"] - $datos["cantidad_kardex"];
            $kardexLimpieza->id_empleado = $datos["id_empleado"];
            $kardexLimpieza->id_usuario = $datos["id_usuario"];
            $kardexLimpieza->save();
    
            $datos1 = array("stock_productoLimpieza"=>$extraer_stock[0]->stock_productoLimpieza - $request->input("seleccionar_cantidad"));
    
            $productoLimpieza = ProductosLimpiezaModel::where('id_productoLimpieza',$datos["id_productoLimpieza"])->update($datos1);
    
            return redirect('/kardexLimpieza')->with("salida-producto","");
        }
    }

    public function createMovimientosPDF(Request $request){
        $valores = $request->all();
        $mfecha = $request->mfecha;
        $afecha = $request->afecha;

        $kardexLimpiezaTotal =  DB::select('SELECT P.nombre_productoLimpieza,P.descripcion_productoLimpieza,P.codigo_productoLimpieza,P.stock_productoLimpieza,SUM(K.entradas_kardexLimpieza) as suma_entradas,SUM(K.salidas_kardexLimpieza) as suma_salidas FROM kardex_limpieza K LEFT JOIN 
                                            productos_limpieza P ON K.id_productoLimpieza = P.id_productoLimpieza WHERE MONTH(K.created_at) = :mfecha AND YEAR(K.created_at) = :afecha GROUP BY K.id_productoLimpieza 
                                            ORDER BY K.id_kardexLimpieza',["mfecha"=>$mfecha,"afecha"=>$afecha]);

        $sumaEntradasSalidasTotal = DB::select('SELECT SUM(K.entradas_kardexLimpieza) as suma_entradasTotal,SUM(K.salidas_kardexLimpieza) as suma_salidasTotal FROM kardex_limpieza K WHERE MONTH(K.created_at) = :mfecha AND 
                                                YEAR(K.created_at) = :afecha',["mfecha"=>$mfecha,"afecha"=>$afecha]);

        // compartir datos para ver
        view()->share('kardexLimpiezaTotal',$kardexLimpiezaTotal);
        view()->share('mfecha',$mfecha);
        view()->share('afecha',$afecha);
        view()->share('sumaEntradasSalidasTotal',$sumaEntradasSalidasTotal);

        $pdf = PDF::loadView('paginas.reportesKardexLimpieza',$kardexLimpiezaTotal);
        
        // descargar archivo PDF con método de descarga
        return $pdf->setPaper('a4','landscape')->stream('MovimientoskardexLimpieza.pdf');
    }

    public function createEntradasPDF(Request $request){
        $valores = $request->all();
        $mfecha = $request->mfecha;
        $afecha = $request->afecha;

        $kardexLimpiezaTotal =  DB::select('SELECT P.id_productoLimpieza,P.nombre_productoLimpieza,P.descripcion_productoLimpieza,P.codigo_productoLimpieza,K.id_kardexLimpieza,K.id_productoLimpieza,K.motivo_kardexLimpieza,K.entradas_kardexLimpieza,K.restante_kardexLimpieza,
                                            K.salidas_kardexLimpieza,K.created_at,U.id,U.name FROM kardex_limpieza K INNER JOIN productos_limpieza P ON K.id_productoLimpieza = P.id_productoLimpieza 
                                            LEFT JOIN users U ON U.id = K.id_usuario WHERE MONTH(K.created_at) = :mfecha AND YEAR(K.created_at) = :afecha AND K.entradas_kardexLimpieza <> 0
                                            ORDER BY K.id_kardexLimpieza DESC',["mfecha"=>$mfecha,"afecha"=>$afecha]);

        // compartir datos para ver
        view()->share('kardexLimpiezaTotal',$kardexLimpiezaTotal);
        view()->share('mfecha',$mfecha);
        view()->share('afecha',$afecha);


        $pdf = PDF::loadView('paginas.reportesEntradasLimpieza',$kardexLimpiezaTotal);
        
        // descargar archivo PDF con método de descarga
        return $pdf->setPaper('a4','landscape')->stream('EntradasKardexLimpieza.pdf');
    }

    public function createSalidasPDF(Request $request){
        $valores = $request->all();
        $mfecha = $request->mfecha;
        $afecha = $request->afecha;

        $kardexLimpiezaTotal =  DB::select('SELECT P.id_productoLimpieza,P.nombre_productoLimpieza,P.descripcion_productoLimpieza,P.codigo_productoLimpieza,K.id_kardexLimpieza,K.id_productoLimpieza,K.motivo_kardexLimpieza,K.entradas_kardexLimpieza,K.restante_kardexLimpieza,
                                            K.salidas_kardexLimpieza,K.created_at,U.id,U.name,E.nombres_empleado,E.apellidos_empleado FROM kardex_limpieza K INNER JOIN productos_limpieza P ON K.id_productoLimpieza = P.id_productoLimpieza 
                                            LEFT JOIN users U ON U.id = K.id_usuario LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                            WHERE MONTH(K.created_at) = :mfecha AND YEAR(K.created_at) = :afecha AND K.salidas_kardexLimpieza <> 0
                                            ORDER BY K.id_kardexLimpieza DESC',["mfecha"=>$mfecha,"afecha"=>$afecha]);

        // compartir datos para ver
        view()->share('kardexLimpiezaTotal',$kardexLimpiezaTotal);
        view()->share('mfecha',$mfecha);
        view()->share('afecha',$afecha);

        $pdf = PDF::loadView('paginas.reportesSalidasLimpieza',$kardexLimpiezaTotal);
        
        // descargar archivo PDF con método de descarga
        return $pdf->setPaper('a4','landscape')->stream('SalidasKardexLimpieza.pdf');
    }
}
