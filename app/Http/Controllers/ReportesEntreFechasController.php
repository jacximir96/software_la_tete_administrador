<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/* Agregamos los modelos */
use App\ProductosLimpiezaModel;
use App\ProductosHerramientasModel;
use App\AdministradoresModel;
use App\ProductosEPPModel;
use App\CategoriasModel;
use App\EmpleadosModel;
use App\UnidadesMedidaModel;
use App\RecepcionLimpiezaModel;
use App\EquiposModel;
use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */
use PDF;/* Apuntamos al modelo que existe por defecto para obtener información en PDF */
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RecepcionLimpiezaExport;

class ReportesEntreFechasController extends Controller
{
    public function index(){

        $administradores = AdministradoresModel::all();
        $empleados = EmpleadosModel::all();
        $categorias = CategoriasModel::all();
        $equipos = EquiposModel::all();

        return view("paginas.reportesEntreFechas",array("administradores"=>$administradores,"empleados"=>$empleados,
                                                        "categorias"=>$categorias,"equipos"=>$equipos));
    }

    public function createPdfIngresosEgresos(Request $request) {

        $datos = array("fecha_inicial"=>$request->input("fecha_inicial_reportes"),
                       "fecha_final"=>$request->input("fecha_final_reportes"));

        $ingresosPorFechas = DB::SELECT("SELECT ROUND(SUM(cfac_monto_total),2) AS cfac_monto_total, created_at FROM cabecera_factura
                                        WHERE date(created_at) BETWEEN :fecha_inicial AND :fecha_final GROUP BY DATE(created_at)",
                                        ['fecha_inicial'=>$datos["fecha_inicial"],'fecha_final'=>$datos["fecha_final"]]);

        $egresosPorFechas = DB::SELECT("SELECT ROUND(SUM(preciot_gasto),2) AS precio_gasto, created_at FROM gastos
                                        WHERE date(created_at) BETWEEN :fecha_inicial AND :fecha_final GROUP BY DATE(created_at)",
                                        ['fecha_inicial'=>$datos["fecha_inicial"],'fecha_final'=>$datos["fecha_final"]]);

        view()->share('datos',$datos);
        view()->share('ingresosPorFechas',$ingresosPorFechas);
        view()->share('egresosPorFechas',$egresosPorFechas);

        $pdf = PDF::loadView('paginas.reportesEntreFechasR',$ingresosPorFechas);
        $nombreArchivo = "ingresos&egresos(".$datos["fecha_inicial"].")/(".$datos["fecha_final"].")";
        return $pdf->setPaper('a5','landscape')->stream($nombreArchivo.'.pdf');
      }

      public function createPdfCierreCaja(Request $request) {

        $datos = array("fecha_inicial"=>$request->input("fecha_inicial_reportes"),
                       "fecha_final"=>$request->input("fecha_final_reportes"));

        $cierreCajaPorFechas = DB::SELECT("SELECT ROUND(cc_monto,2) AS cc_monto, created_at FROM caja_cuadre
                                          WHERE date(created_at) BETWEEN :fecha_inicial AND :fecha_final",
                                          ['fecha_inicial'=>$datos["fecha_inicial"],'fecha_final'=>$datos["fecha_final"]]);

        view()->share('datos',$datos);
        view()->share('cierreCajaPorFechas',$cierreCajaPorFechas);

        $pdf = PDF::loadView('paginas.reportesEntreFechas1',$cierreCajaPorFechas);
        $nombreArchivo = "cierresCaja(".$datos["fecha_inicial"].")/(".$datos["fecha_final"].")";
        return $pdf->setPaper('a5','landscape')->stream($nombreArchivo.'.pdf');
      }

      public function createPDF1(Request $request) {
        $datos = array("fecha_inicial"=>$request->input("fecha_inicial_reportes"),
                       "fecha_final"=>$request->input("fecha_final_reportes"),
                       "id_categoria"=>$request->input("categoria_reportes"),
                       "id_producto"=>$request->input("producto_reportes"));

        if(!empty($datos)){
        $validar = \Validator::make($datos,[
                "fecha_inicial" =>'required',
                "fecha_final" =>'required|after_or_equal:fecha_inicial'
        ]);
                            
        if($validar->fails()){//SI LA VALIDACIÓN FALLA - ENVIAMOS UN MENSAJE EN LA PANTALLA
                return redirect('/reportesEntreFechas')->with("no-validacion","");
        }else{
        // recuperar todos los registros de la base de datos

        $productoEPP = DB::select('SELECT * FROM productos_epp WHERE id_productoEPP = ?',[$datos["id_producto"]]);
        $productoLimpieza = DB::select('SELECT * FROM productos_limpieza WHERE id_productoLimpieza = ?',[$datos["id_producto"]]);
        $productoHerramienta = DB::select('SELECT * FROM productos_herramientas WHERE id_productoHerramienta = ?',[$datos["id_producto"]]);
        $cantidadGeneral_limpieza =  DB::select("SELECT SUM(KL.salidas_kardexLimpieza) as suma_salidas,motivo_kardexLimpieza,PL.codigo_productoLimpieza,PL.nombre_productoLimpieza,PL.descripcion_productoLimpieza,KL.created_at
                                                 FROM kardex_limpieza KL LEFT JOIN productos_limpieza PL ON
                                                 KL.id_productoLimpieza = PL.id_productoLimpieza
                                                 WHERE date(KL.created_at) BETWEEN :fecha_inicial AND :fecha_final GROUP BY PL.codigo_productoLimpieza",
                                                 ['fecha_inicial'=>$datos["fecha_inicial"],'fecha_final'=>$datos["fecha_final"]]);

        $historialEPP = DB::select('SELECT P.id_productoEPP,P.nombre_productoEPP,P.descripcion_productoEPP,
                                     P.codigo_productoEPP,K.id_kardexEPP,K.id_productoEPP,K.motivo_kardexEPP,
                                     K.entradas_kardexEPP,K.restante_kardexEPP,K.salidas_kardexEPP,K.created_at,
                                     U.id,U.name,E.nombres_empleado,apellidos_empleado 
                                     FROM kardex_epp K INNER JOIN productos_epp P 
                                     ON K.id_productoEPP = P.id_productoEPP 
                                     LEFT JOIN users U ON U.id = K.id_usuario
                                     LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                     WHERE P.id_productoEPP = ? AND
                                     K.entradas_kardexEPP <> 0
                                     ORDER BY K.id_kardexEPP DESC',[$datos["id_producto"]]);

        $historialEPP1 = DB::select('SELECT P.id_productoEPP,P.nombre_productoEPP,P.descripcion_productoEPP,
                                     P.codigo_productoEPP,K.id_kardexEPP,K.id_productoEPP,K.motivo_kardexEPP,
                                     K.entradas_kardexEPP,K.restante_kardexEPP,K.salidas_kardexEPP,K.created_at,
                                     U.id,U.name,E.nombres_empleado,apellidos_empleado 
                                     FROM kardex_epp K INNER JOIN productos_epp P 
                                     ON K.id_productoEPP = P.id_productoEPP 
                                     LEFT JOIN users U ON U.id = K.id_usuario
                                     LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                     WHERE P.id_productoEPP = ? AND
                                     K.salidas_kardexEPP <> 0
                                     ORDER BY K.id_kardexEPP DESC',[$datos["id_producto"]]);

        $sumaTotalEPP = DB::select('SELECT SUM(K.salidas_kardexEPP) as suma_total
                                     FROM kardex_epp K INNER JOIN productos_epp P 
                                     ON K.id_productoEPP = P.id_productoEPP 
                                     LEFT JOIN users U ON U.id = K.id_usuario
                                     LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                     WHERE P.id_productoEPP = ? AND
                                     K.salidas_kardexEPP <> 0
                                     ORDER BY K.id_kardexEPP DESC',[$datos["id_producto"]]);

        $sumaTotalEPP1 = DB::select('SELECT SUM(K.entradas_kardexEPP) as suma_total
                                    FROM kardex_epp K INNER JOIN productos_epp P 
                                    ON K.id_productoEPP = P.id_productoEPP 
                                    LEFT JOIN users U ON U.id = K.id_usuario
                                    LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                    WHERE P.id_productoEPP = ? AND
                                    K.entradas_kardexEPP <> 0
                                    ORDER BY K.id_kardexEPP DESC',[$datos["id_producto"]]);

        $historialHerramienta = DB::select('SELECT P.id_productoHerramienta,P.nombre_productoHerramienta,P.descripcion_productoHerramienta,
                                            P.codigo_productoHerramienta,K.id_kardexHerramienta,K.id_productoHerramienta,K.motivo_kardexHerramienta,
                                            K.entradas_kardexHerramienta,K.restante_kardexHerramienta,K.salidas_kardexHerramienta,K.created_at,
                                            U.id,U.name,E.nombres_empleado,apellidos_empleado 
                                            FROM kardex_herramientas K INNER JOIN productos_herramientas P 
                                            ON K.id_productoHerramienta = P.id_productoHerramienta 
                                            LEFT JOIN users U ON U.id = K.id_usuario
                                            LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                            WHERE P.id_productoHerramienta = ? AND
                                            K.entradas_kardexHerramienta <> 0
                                            ORDER BY K.id_kardexHerramienta DESC',[$datos["id_producto"]]);

        $historialHerramienta1 = DB::select('SELECT P.id_productoHerramienta,P.nombre_productoHerramienta,P.descripcion_productoHerramienta,
                                             P.codigo_productoHerramienta,K.id_kardexHerramienta,K.id_productoHerramienta,K.motivo_kardexHerramienta,
                                             K.entradas_kardexHerramienta,K.restante_kardexHerramienta,K.salidas_kardexHerramienta,K.created_at,
                                             U.id,U.name,E.nombres_empleado,apellidos_empleado 
                                             FROM kardex_herramientas K INNER JOIN productos_herramientas P 
                                             ON K.id_productoHerramienta = P.id_productoHerramienta 
                                             LEFT JOIN users U ON U.id = K.id_usuario
                                             LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                             WHERE P.id_productoHerramienta = ? AND
                                             K.salidas_kardexHerramienta <> 0
                                             ORDER BY K.id_kardexHerramienta DESC',[$datos["id_producto"]]);

        $sumaTotalHerramienta = DB::select('SELECT SUM(K.salidas_kardexHerramienta) as suma_total
                                            FROM kardex_herramientas K INNER JOIN productos_herramientas P 
                                            ON K.id_productoHerramienta = P.id_productoHerramienta 
                                            LEFT JOIN users U ON U.id = K.id_usuario
                                            LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                            WHERE P.id_productoHerramienta = ? AND
                                            K.salidas_kardexHerramienta <> 0
                                            ORDER BY K.id_kardexHerramienta DESC',[$datos["id_producto"]]);

        $sumaTotalHerramienta1 = DB::select('SELECT SUM(K.entradas_kardexHerramienta) as suma_total
                                            FROM kardex_herramientas K INNER JOIN productos_herramientas P 
                                            ON K.id_productoHerramienta = P.id_productoHerramienta 
                                            LEFT JOIN users U ON U.id = K.id_usuario
                                            LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                            WHERE P.id_productoHerramienta = ? AND
                                            K.entradas_kardexHerramienta <> 0
                                            ORDER BY K.id_kardexHerramienta DESC',[$datos["id_producto"]]);

        $historialLimpieza = DB::select('SELECT P.id_productoLimpieza,P.nombre_productoLimpieza,P.descripcion_productoLimpieza,
                                          P.codigo_productoLimpieza,K.id_kardexLimpieza,K.id_productoLimpieza,K.motivo_kardexLimpieza,
                                          K.entradas_kardexLimpieza,K.restante_kardexLimpieza,K.salidas_kardexLimpieza,K.created_at,
                                          U.id,U.name,E.nombres_empleado,apellidos_empleado 
                                          FROM kardex_limpieza K INNER JOIN productos_limpieza P 
                                          ON K.id_productoLimpieza = P.id_productoLimpieza 
                                          LEFT JOIN users U ON U.id = K.id_usuario
                                          LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                          WHERE P.id_productoLimpieza = ? AND
                                          K.entradas_kardexLimpieza <> 0
                                          ORDER BY K.id_kardexLimpieza DESC',[$datos["id_producto"]]);

        $historialLimpieza1 = DB::select('SELECT P.id_productoLimpieza,P.nombre_productoLimpieza,P.descripcion_productoLimpieza,
                                          P.codigo_productoLimpieza,K.id_kardexLimpieza,K.id_productoLimpieza,K.motivo_kardexLimpieza,
                                          K.entradas_kardexLimpieza,K.restante_kardexLimpieza,K.salidas_kardexLimpieza,K.created_at,
                                          U.id,U.name,E.nombres_empleado,apellidos_empleado 
                                          FROM kardex_limpieza K INNER JOIN productos_limpieza P 
                                          ON K.id_productoLimpieza = P.id_productoLimpieza 
                                          LEFT JOIN users U ON U.id = K.id_usuario
                                          LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                          WHERE P.id_productoLimpieza = ? AND
                                          K.salidas_kardexLimpieza <> 0
                                          ORDER BY K.id_kardexLimpieza DESC',[$datos["id_producto"]]);

        $sumaTotalLimpieza = DB::select('SELECT SUM(K.salidas_kardexLimpieza) as suma_total
                                         FROM kardex_limpieza K INNER JOIN productos_limpieza P 
                                         ON K.id_productoLimpieza = P.id_productoLimpieza 
                                         LEFT JOIN users U ON U.id = K.id_usuario
                                         LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                         WHERE P.id_productoLimpieza = ? AND
                                         K.salidas_kardexLimpieza <> 0
                                         ORDER BY K.id_kardexLimpieza DESC',[$datos["id_producto"]]);

        $sumaTotalLimpieza1 = DB::select('SELECT SUM(K.entradas_kardexLimpieza) as suma_total
                                         FROM kardex_limpieza K INNER JOIN productos_limpieza P 
                                         ON K.id_productoLimpieza = P.id_productoLimpieza 
                                         LEFT JOIN users U ON U.id = K.id_usuario
                                         LEFT JOIN empleados E ON K.id_empleado = E.id_empleado
                                         WHERE P.id_productoLimpieza = ? AND
                                         K.entradas_kardexLimpieza <> 0
                                         ORDER BY K.id_kardexLimpieza DESC',[$datos["id_producto"]]);

        // compartir datos para ver
        view()->share('datos',$datos);
        view()->share('productoLimpieza',$productoLimpieza);
        view()->share('productoEPP',$productoEPP);
        view()->share('productoHerramienta',$productoHerramienta);
        view()->share('historialEPP',$historialEPP);
        view()->share('historialEPP1',$historialEPP1);
        view()->share('sumaTotalEPP',$sumaTotalEPP);
        view()->share('sumaTotalEPP1',$sumaTotalEPP1);
        view()->share('historialLimpieza',$historialLimpieza);
        view()->share('historialLimpieza1',$historialLimpieza1);
        view()->share('sumaTotalLimpieza',$sumaTotalLimpieza);
        view()->share('sumaTotalLimpieza1',$sumaTotalLimpieza1);
        view()->share('historialHerramienta',$historialHerramienta);
        view()->share('historialHerramienta1',$historialHerramienta1);
        view()->share('sumaTotalHerramienta',$sumaTotalHerramienta);
        view()->share('sumaTotalHerramienta1',$sumaTotalHerramienta1);
        view()->share('cantidadGeneral_limpieza',$cantidadGeneral_limpieza);

        $pdf = PDF::loadView('paginas.reportesEntreFechas1',$cantidadGeneral_limpieza);

        // descargar archivo PDF con método de descarga
        return $pdf->setPaper('a4','landscape')->stream('reportesEntreFechasGeneral.pdf');
        }
        }else{
                return redirect('/reportesEntreFechas')->with("error","");
        }
      }

      public function createPDF2(Request $request) {
        $datos = array("fecha_inicial"=>$request->input("fecha_inicial_reportes"),
                       "fecha_final"=>$request->input("fecha_final_reportes"),
                       "id_categoria"=>$request->input("seleccion_reportes"),
                       "id_equipo"=>$request->input("equipo_reportes"));

        if(!empty($datos)){
        $validar = \Validator::make($datos,[
                "fecha_inicial" =>'required',
                "fecha_final" =>'required|after_or_equal:fecha_inicial'
        ]);
            
        if($validar->fails()){//SI LA VALIDACIÓN FALLA - ENVIAMOS UN MENSAJE EN LA PANTALLA
                return redirect('/reportesEntreFechas')->with("no-validacion","");
        }else{
        // recuperar todos los registros de la base de datos

        $equipos = DB::SELECT('SELECT * FROM equipos WHERE id_equipo = ?',[$datos["id_equipo"]]);
        $empleados_equiposCargos = DB::SELECT('SELECT PE.id_productoEPP,PE.nombre_productoEPP,PE.descripcion_productoEPP,PE.codigo_productoEPP,
                                               SUM(KE.salidas_kardexEPP) as suma_productos,E.nombres_empleado,E.id_empleado,E.apellidos_empleado,
                                               C.nombre_cargo,EC.id_equipo FROM kardex_epp KE INNER JOIN empleados E ON KE.id_empleado = E.id_empleado 
                                               INNER JOIN cargos C ON E.id_cargo = C.id_cargo INNER JOIN equipos_cargos EC ON C.id_cargo = EC.id_cargo 
                                               INNER JOIN productos_epp PE ON KE.id_productoEPP = PE.id_productoEPP WHERE EC.id_equipo = ? GROUP BY 
                                               KE.id_productoEPP,E.id_empleado ORDER BY E.nombres_empleado ASC',[$datos["id_equipo"]]);

        $empleados_equiposCargosLimpieza = DB::SELECT('SELECT PE.id_productoLimpieza,PE.nombre_productoLimpieza,PE.descripcion_productoLimpieza,PE.codigo_productoLimpieza,
                                               SUM(KE.salidas_kardexLimpieza) as suma_productos,E.nombres_empleado,E.id_empleado,E.apellidos_empleado,
                                               C.nombre_cargo,EC.id_equipo FROM kardex_limpieza KE INNER JOIN empleados E ON KE.id_empleado = E.id_empleado 
                                               INNER JOIN cargos C ON E.id_cargo = C.id_cargo INNER JOIN equipos_cargos EC ON C.id_cargo = EC.id_cargo 
                                               INNER JOIN productos_limpieza PE ON KE.id_productoLimpieza = PE.id_productoLimpieza WHERE EC.id_equipo = ? GROUP BY 
                                               KE.id_productoLimpieza,E.id_empleado ORDER BY E.nombres_empleado ASC',[$datos["id_equipo"]]);

        $empleados_equiposCargosHerramienta = DB::SELECT('SELECT PE.id_productoHerramienta,PE.nombre_productoHerramienta,PE.descripcion_productoHerramienta,PE.codigo_productoHerramienta,
                                               SUM(KE.salidas_kardexHerramienta) as suma_productos,E.nombres_empleado,E.id_empleado,E.apellidos_empleado,
                                               C.nombre_cargo,EC.id_equipo FROM kardex_herramientas KE INNER JOIN empleados E ON KE.id_empleado = E.id_empleado 
                                               INNER JOIN cargos C ON E.id_cargo = C.id_cargo INNER JOIN equipos_cargos EC ON C.id_cargo = EC.id_cargo 
                                               INNER JOIN productos_herramientas PE ON KE.id_productoHerramienta = PE.id_productoHerramienta WHERE EC.id_equipo = ? GROUP BY 
                                               KE.id_productoHerramienta,E.id_empleado ORDER BY E.nombres_empleado ASC',[$datos["id_equipo"]]);

        $empleados_equiposCargos1 = DB::SELECT('SELECT PE.id_productoEPP,PE.nombre_productoEPP,PE.descripcion_productoEPP,PE.codigo_productoEPP, 
                                                SUM(KE.salidas_kardexEPP) as suma_productos,E.nombres_empleado,E.id_empleado,E.apellidos_empleado,
                                                C.nombre_cargo,EC.id_equipo,C.nombre_cargo FROM kardex_epp KE INNER JOIN empleados E ON 
                                                KE.id_empleado = E.id_empleado INNER JOIN cargos C ON E.id_cargo = C.id_cargo INNER JOIN equipos_cargos EC 
                                                ON C.id_cargo = EC.id_cargo INNER JOIN productos_epp PE ON KE.id_productoEPP = PE.id_productoEPP WHERE 
                                                EC.id_equipo = ? GROUP BY KE.id_productoEPP ORDER BY PE.nombre_productoEPP ASC',[$datos["id_equipo"]]);

        $empleados_equiposCargosLimpieza1 = DB::SELECT('SELECT PE.id_productoLimpieza,PE.nombre_productoLimpieza,PE.descripcion_productoLimpieza,PE.codigo_productoLimpieza, 
                                                SUM(KE.salidas_kardexLimpieza) as suma_productos,E.nombres_empleado,E.id_empleado,E.apellidos_empleado,
                                                C.nombre_cargo,EC.id_equipo,C.nombre_cargo FROM kardex_limpieza KE INNER JOIN empleados E ON 
                                                KE.id_empleado = E.id_empleado INNER JOIN cargos C ON E.id_cargo = C.id_cargo INNER JOIN equipos_cargos EC 
                                                ON C.id_cargo = EC.id_cargo INNER JOIN productos_limpieza PE ON KE.id_productoLimpieza = PE.id_productoLimpieza WHERE 
                                                EC.id_equipo = ? GROUP BY KE.id_productoLimpieza ORDER BY PE.nombre_productoLimpieza ASC',[$datos["id_equipo"]]);

        $empleados_equiposCargosHerramienta1 = DB::SELECT('SELECT PE.id_productoHerramienta,PE.nombre_productoHerramienta,PE.descripcion_productoHerramienta,PE.codigo_productoHerramienta, 
                                                SUM(KE.salidas_kardexHerramienta) as suma_productos,E.nombres_empleado,E.id_empleado,E.apellidos_empleado,
                                                C.nombre_cargo,EC.id_equipo,C.nombre_cargo FROM kardex_herramientas KE INNER JOIN empleados E ON 
                                                KE.id_empleado = E.id_empleado INNER JOIN cargos C ON E.id_cargo = C.id_cargo INNER JOIN equipos_cargos EC 
                                                ON C.id_cargo = EC.id_cargo INNER JOIN productos_herramientas PE ON KE.id_productoHerramienta = PE.id_productoHerramienta WHERE 
                                                EC.id_equipo = ? GROUP BY KE.id_productoHerramienta ORDER BY PE.nombre_productoHerramienta ASC',[$datos["id_equipo"]]);

        // compartir datos para ver
        view()->share('datos',$datos);
        view()->share('empleados_equiposCargos',$empleados_equiposCargos);
        view()->share('empleados_equiposCargosLimpieza',$empleados_equiposCargosLimpieza);
        view()->share('empleados_equiposCargosHerramienta',$empleados_equiposCargosHerramienta);
        view()->share('empleados_equiposCargos1',$empleados_equiposCargos1);
        view()->share('empleados_equiposCargosLimpieza1',$empleados_equiposCargosLimpieza1);
        view()->share('empleados_equiposCargosHerramienta1',$empleados_equiposCargosHerramienta1);
        view()->share('equipos',$equipos);
     
        $pdf = PDF::loadView('paginas.reportesEntreFechas2',$datos);
        
        // descargar archivo PDF con método de descarga
        return $pdf->setPaper('a4','landscape')->stream('reportesEntreFechasGeneral.pdf');
        }
        }else{
                return redirect('/reportesEntreFechas')->with("error","");
        }
      }

      public function getProductos(Request $request) {
        if($request->ajax()){
                $productosEPP = ProductosEPPModel::where('id_categoria',$request->id_categoria)->get();
                $productosLimpieza = ProductosLimpiezaModel::where('id_categoria',$request->id_categoria)->get();
                $productosHerramienta = ProductosHerramientasModel::where('id_categoria',$request->id_categoria)->get();

                if($request->id_categoria == 3){
                        foreach($productosEPP as $productos_epp){
                                $productosArray[$productos_epp->id_productoEPP] = [$productos_epp->nombre_productoEPP,$productos_epp->descripcion_productoEPP];
                        }
                }       

                if($request->id_categoria == 1){
                        foreach($productosLimpieza as $productos_limpieza){
                                $productosArray[$productos_limpieza->id_productoLimpieza] = [$productos_limpieza->nombre_productoLimpieza,$productos_limpieza->descripcion_productoLimpieza];
                        }
                }       

                if($request->id_categoria == 2){
                        foreach($productosHerramienta as $productos_herramientas){
                                $productosArray[$productos_herramientas->id_productoHerramienta] = [$productos_herramientas->nombre_productoHerramienta,$productos_herramientas->descripcion_productoHerramienta];
                        }
                }

                return response()->json($productosArray);
        }
      }
}
