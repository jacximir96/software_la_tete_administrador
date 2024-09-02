<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdministradoresModel;
use App\ProductosLimpiezaModel;
use App\InsumosModel;
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

        $insumoIDs = DB::table('productos_limpieza as pl')
            ->join('insumo as i', DB::raw('FIND_IN_SET(i.IDInsumo, pl.insumos_asociados)'), '>', DB::raw('0'))
            ->whereNotNull('pl.insumos_asociados')
            ->where('pl.insumos_asociados', '<>', '')
            ->distinct()
            ->pluck('i.IDInsumo');

        $insumosEnUso = $insumoIDs->isNotEmpty() 
            ? DB::table('insumo')
            ->select('IDInsumo as id_productoLimpieza', 'stock_insumo as stock_productoLimpieza', 'nombre_insumo as nombre_productoLimpieza', 'descripcion_insumo as descripcion_productoLimpieza', 'codigo_insumo as codigo_productoLimpieza', DB::raw("'insumo' as tipo"))
            ->whereIn('IDInsumo', $insumoIDs)->get() 
            : collect();

        $productosSinInsumos = DB::table('productos_limpieza')
            ->select('id_productoLimpieza as id_productoLimpieza', 'stock_productoLimpieza as stock_productoLimpieza', 'nombre_productoLimpieza as nombre_productoLimpieza', 'descripcion_productoLimpieza as descripcion_productoLimpieza', 'codigo_productoLimpieza as codigo_productoLimpieza', DB::raw("'producto' as tipo"))
            ->whereNull('insumos_asociados')
            ->get();

        $productosLimpiezaEntradas = $insumosEnUso->merge($productosSinInsumos);
        $productosLimpieza = DB::select('SELECT id_productoLimpieza,stock_productoLimpieza,nombre_productoLimpieza,descripcion_productoLimpieza,codigo_productoLimpieza,"producto" AS tipo 
                                         FROM productos_limpieza WHERE stock_productoLimpieza <> 0 UNION 
                                         SELECT IDInsumo AS id_productoLimpieza,stock_insumo AS stock_productoLimpieza,nombre_insumo AS nombre_productoLimpieza,
                                         descripcion_insumo AS descripcion_productoLimpieza,codigo_insumo AS codigo_productoLimpieza,"insumo" AS tipo FROM insumo WHERE stock_insumo <> 0');

        /* $kardexLimpiezaTotal = DB::select('SELECT P.id_productoLimpieza,P.nombre_productoLimpieza,P.descripcion_productoLimpieza,K.id_kardexLimpieza,K.id_productoLimpieza,K.entradas_kardexLimpieza,K.restante_kardexLimpieza,
                                           K.salidas_kardexLimpieza,K.created_at,E.nombres_empleado,E.apellidos_empleado FROM kardex_limpieza K INNER JOIN productos_limpieza P ON K.id_productoLimpieza = P.id_productoLimpieza
                                           LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                           ORDER BY K.id_kardexLimpieza DESC'); */

        $kardexLimpiezaProductos = DB::select('
                                           SELECT P.id_productoLimpieza, P.nombre_productoLimpieza, P.descripcion_productoLimpieza,
                                                  K.id_kardexLimpieza, K.entradas_kardexLimpieza, K.restante_kardexLimpieza,
                                                  K.salidas_kardexLimpieza, K.created_at,
                                                  E.nombres_empleado, E.apellidos_empleado,
                                                  "producto" as tipo
                                           FROM kardex_limpieza K
                                           INNER JOIN productos_limpieza P ON K.id_productoLimpieza = P.id_productoLimpieza
                                           LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                           ORDER BY K.id_kardexLimpieza DESC
                                       ');

        $kardexLimpiezaInsumos = DB::select('
                                            SELECT I.IDInsumo as id_productoLimpieza, I.nombre_insumo as nombre_productoLimpieza, I.descripcion_insumo as descripcion_productoLimpieza,
                                                K.id_kardexLimpieza, K.entradas_kardexLimpieza, K.restante_kardexLimpieza,
                                                K.salidas_kardexLimpieza, K.created_at,
                                                E.nombres_empleado, E.apellidos_empleado,
                                                "insumo" as tipo
                                            FROM kardex_limpieza K
                                            INNER JOIN insumo I ON K.id_insumo = I.IDInsumo
                                            LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                            ORDER BY K.id_kardexLimpieza DESC
                                        ');

        $kardexLimpiezaTotal = collect($kardexLimpiezaProductos)->merge($kardexLimpiezaInsumos);

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
        $insumoIDs = DB::table('productos_limpieza as pl')
        ->join('insumo as i', DB::raw('FIND_IN_SET(i.IDInsumo, pl.insumos_asociados)'), '>', DB::raw('0'))
        ->whereNotNull('pl.insumos_asociados')
        ->where('pl.insumos_asociados', '<>', '')
        ->distinct()
        ->pluck('i.IDInsumo');

        $insumosEnUso = $insumoIDs->isNotEmpty() 
            ? DB::table('insumo')
            ->select('IDInsumo as id_productoLimpieza', 'stock_insumo as stock_productoLimpieza', 'nombre_insumo as nombre_productoLimpieza', 'descripcion_insumo as descripcion_productoLimpieza', 'codigo_insumo as codigo_productoLimpieza', DB::raw("'insumo' as tipo"))
            ->whereIn('IDInsumo', $insumoIDs)->get() 
            : collect();

        $productosSinInsumos = DB::table('productos_limpieza')
            ->select('id_productoLimpieza as id_productoLimpieza', 'stock_productoLimpieza as stock_productoLimpieza', 'nombre_productoLimpieza as nombre_productoLimpieza', 'descripcion_productoLimpieza as descripcion_productoLimpieza', 'codigo_productoLimpieza as codigo_productoLimpieza', DB::raw("'producto' as tipo"))
            ->whereNull('insumos_asociados')
            ->get();

        $productosLimpiezaEntradas = $insumosEnUso->merge($productosSinInsumos);
        /* $productosLimpiezaEntradas = DB::select('SELECT * FROM productos_limpieza'); */
        /* $productosLimpieza = DB::select('SELECT * FROM productos_limpieza WHERE stock_productoLimpieza <> 0'); */
        $productosLimpieza = DB::select('SELECT id_productoLimpieza,stock_productoLimpieza,nombre_productoLimpieza,descripcion_productoLimpieza,codigo_productoLimpieza,"producto" AS tipo 
        FROM productos_limpieza WHERE stock_productoLimpieza <> 0 UNION 
        SELECT IDInsumo AS id_productoLimpieza,stock_insumo AS stock_productoLimpieza,nombre_insumo AS nombre_productoLimpieza,
        descripcion_insumo AS descripcion_productoLimpieza,codigo_insumo AS codigo_productoLimpieza,"insumo" AS tipo FROM insumo WHERE stock_insumo <> 0');

        $kardexLimpiezaProductos = DB::select('
            SELECT P.id_productoLimpieza, P.nombre_productoLimpieza, P.descripcion_productoLimpieza,
                K.id_kardexLimpieza, K.entradas_kardexLimpieza, K.restante_kardexLimpieza,
                K.salidas_kardexLimpieza, K.created_at,
                E.nombres_empleado, E.apellidos_empleado,
                "producto" as tipo
            FROM kardex_limpieza K
            INNER JOIN productos_limpieza P ON K.id_productoLimpieza = P.id_productoLimpieza
            LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
            WHERE MONTH(K.created_at) = :mfecha AND YEAR(K.created_at) = :afecha
            ORDER BY K.id_kardexLimpieza DESC
        ',["mfecha"=>$mfecha,"afecha"=>$afecha]);

        $kardexLimpiezaInsumos = DB::select('
            SELECT I.IDInsumo as id_productoLimpieza, I.nombre_insumo as nombre_productoLimpieza, I.descripcion_insumo as descripcion_productoLimpieza,
                K.id_kardexLimpieza, K.entradas_kardexLimpieza, K.restante_kardexLimpieza,
                K.salidas_kardexLimpieza, K.created_at,
                E.nombres_empleado, E.apellidos_empleado,
                "insumo" as tipo
            FROM kardex_limpieza K
            INNER JOIN insumo I ON K.id_insumo = I.IDInsumo
            LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
            WHERE MONTH(K.created_at) = :mfecha AND YEAR(K.created_at) = :afecha
            ORDER BY K.id_kardexLimpieza DESC
        ',["mfecha"=>$mfecha,"afecha"=>$afecha]);

        $kardexLimpiezaTotal = collect($kardexLimpiezaProductos)->merge($kardexLimpiezaInsumos);

/*         $kardexLimpiezaTotal = DB::select('SELECT P.id_productoLimpieza,P.nombre_productoLimpieza,P.descripcion_productoLimpieza,K.id_kardexLimpieza,K.id_productoLimpieza,K.entradas_kardexLimpieza,K.restante_kardexLimpieza,
                                            K.salidas_kardexLimpieza,K.created_at,E.nombres_empleado,E.apellidos_empleado FROM kardex_limpieza K INNER JOIN productos_limpieza P ON K.id_productoLimpieza = P.id_productoLimpieza
                                            LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                            WHERE MONTH(K.created_at) = :mfecha AND YEAR(K.created_at) = :afecha
                                            ORDER BY K.id_kardexLimpieza DESC',["mfecha"=>$mfecha,"afecha"=>$afecha]); */

        return view('paginas.kardexLimpieza', array("administradores"=>$administradores,"productosLimpieza"=>$productosLimpieza,"empleados"=>$empleados,
                    "kardexLimpiezaTotal"=>$kardexLimpiezaTotal,"productosLimpiezaEntradas"=>$productosLimpiezaEntradas,"fecha"=>$fecha,"mfecha"=>$mfecha,"afecha"=>$afecha));
    }

    public function entradas_kardex(Request $request){

        $tipo = $request->input('verificar_tipo_producto_insumo');

        if ($tipo == 'insumo') {
            $datos =  array("id_productoLimpieza"=>$request->input("seleccionar_producto"),
                            "motivo_kardex"=>$request->input("seleccionar_motivo"),
                            "cantidad_kardex"=>$request->input("seleccionar_cantidad"),
                            "restante_kardexLimpieza"=>$request->input("seleccionar_stock"),
                            "id_usuario"=>$request->input("usuario_entradaProducto"));

            $extraer_stock =    DB::select('SELECT stock_insumo FROM insumo WHERE 
                                            IDInsumo = ?',[$request->input("seleccionar_producto")]);

            $kardexLimpieza = new KardexLimpiezaModel();
            $kardexLimpieza->id_insumo = $datos["id_productoLimpieza"];
            $kardexLimpieza->motivo_kardexLimpieza = $datos["motivo_kardex"];
            $kardexLimpieza->entradas_kardexLimpieza = $datos["cantidad_kardex"];
            $kardexLimpieza->restante_kardexLimpieza = $datos["restante_kardexLimpieza"] + $datos["cantidad_kardex"];
            $kardexLimpieza->id_usuario = $datos["id_usuario"];
            $kardexLimpieza->save();

            $datos1 = array("stock_insumo"=>$extraer_stock[0]->stock_insumo + $request->input("seleccionar_cantidad"));

            $insumo = InsumosModel::where('IDInsumo',$datos["id_productoLimpieza"])->update($datos1);
        } else if ($tipo == 'producto') {
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
        }

        return redirect('/kardexLimpieza')->with("entrada-producto","");
    }

    public function salidas_kardex(Request $request){

        $tipo = $request->input('verificar_tipo_producto_insumo_salida');

        if ($tipo == 'insumo') {
            $datos =  array("id_productoLimpieza"=>$request->input("seleccionar_producto"),
                            "motivo_kardex"=>$request->input("seleccionar_motivo"),
                            "cantidad_kardex"=>$request->input("seleccionar_cantidad"),
                            "restante_kardexLimpieza"=>$request->input("seleccionar_stock"),
                            "id_empleado"=>$request->input("seleccionar_empleado"),
                            "id_usuario"=>$request->input("usuario_entradaProducto"));

            $extraer_stock =    DB::select('SELECT stock_insumo FROM insumo WHERE 
                                            IDInsumo = ?',[$request->input("seleccionar_producto")]);

            if(($extraer_stock[0]->stock_insumo - $request->input("seleccionar_cantidad") < 0)) {
                return redirect('/kardexLimpieza')->with("stock-negativo","");
            } else {
                $kardexLimpieza = new KardexLimpiezaModel();
                $kardexLimpieza->id_insumo = $datos["id_productoLimpieza"];
                $kardexLimpieza->motivo_kardexLimpieza = $datos["motivo_kardex"];
                $kardexLimpieza->salidas_kardexLimpieza = $datos["cantidad_kardex"];
                $kardexLimpieza->restante_kardexLimpieza = $datos["restante_kardexLimpieza"] - $datos["cantidad_kardex"];
                $kardexLimpieza->id_empleado = $datos["id_empleado"];
                $kardexLimpieza->id_usuario = $datos["id_usuario"];
                $kardexLimpieza->save();

                $datos1 = array("stock_insumo"=>$extraer_stock[0]->stock_insumo - $request->input("seleccionar_cantidad"));

                $insumo = InsumosModel::where('IDInsumo',$datos["id_productoLimpieza"])->update($datos1);

                return redirect('/kardexLimpieza')->with("salida-producto","");
            }
        } else if ($tipo == 'producto') {
            $datos =  array("id_productoLimpieza"=>$request->input("seleccionar_producto"),
                            "motivo_kardex"=>$request->input("seleccionar_motivo"),
                            "cantidad_kardex"=>$request->input("seleccionar_cantidad"),
                            "restante_kardexLimpieza"=>$request->input("seleccionar_stock"),
                            "id_empleado"=>$request->input("seleccionar_empleado"),
                            "id_usuario"=>$request->input("usuario_entradaProducto"));

            $extraer_stock =    DB::select('SELECT stock_productoLimpieza FROM productos_limpieza WHERE 
                                        id_productoLimpieza = ?',[$request->input("seleccionar_producto")]);

            if(($extraer_stock[0]->stock_productoLimpieza - $request->input("seleccionar_cantidad") < 0)) {
                return redirect('/kardexLimpieza')->with("stock-negativo","");
            } else {
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
