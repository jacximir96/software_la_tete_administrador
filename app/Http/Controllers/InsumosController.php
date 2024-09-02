<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\InsumosModel;
use App\AdministradoresModel;
use App\UnidadesMedidaModel;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductosLimpiezaExport;

class InsumosController extends Controller
{
    public function index(){

        $administradores = AdministradoresModel::all();
        $insumos =    DB::select("SELECT PL.IDInsumo,PL.codigo_insumo,PL.nombre_insumo,
                                PL.descripcion_insumo,PL.stock_insumo,
                                PL.id_usuario,PL.created_at,PL.updated_at,
                                UM.nombre_unidadMedida FROM insumo PL INNER JOIN unidades_medida UM 
                                ON PL.id_unidadMedida = UM.id_unidadMedida
                                ORDER BY IDInsumo DESC");
        $unidadesMedida = UnidadesMedidaModel::all();
        $codificacion_insumo = DB::SELECT('SELECT IFNULL(MAX(codigo_insumo) + 1, 1) AS codificacion_insumo 
                                        FROM insumo ORDER BY IDInsumo DESC LIMIT 1');

        return view("paginas.insumos",  array(  "administradores"=>$administradores,
                                                "insumos"=>$insumos,"unidadesMedida"=>$unidadesMedida,
                                                "codificacion_insumo"=>$codificacion_insumo));
    }

    public function store(Request $request){
        $datos =  array("codigo_insumo"=>$request->input("codigo_insumo"),
                        "nombre_insumo"=>$request->input("nombre_insumo"),
                        "descripcion_insumo"=>$request->input("descripcion_insumo"),
                        "stock_insumo"=>$request->input("stock_insumo"),
                        "unidadMedida_insumo"=>$request->input("unidadMedida_insumo"),
                        "usuario_insumo"=>$request->input("usuario_insumo"));

        $codigo_validacion = DB::select('SELECT * FROM insumo WHERE codigo_insumo = ?', [$request->input("codigo_insumo")]);

        if(empty($codigo_validacion) == ""){
            return redirect("/insumos")->with("codigo-existe","");
        }else{
            /* Validar datos */
            if(!empty($datos)){
                $validar = \Validator::make($datos,[
                    "codigo_insumo"=>'required|regex:/^[0-9]+$/i',
                    "nombre_insumo"=>'required|regex:/^[°\\_\\-\\/\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                    "descripcion_insumo"=>'required|regex:/^[°\\=\\-\\/\\.\\,\\:\\_\\+\\!\\#\\$\\%\\&\\?\\¡\\¿\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                    "stock_insumo"=>'required|regex:/^[0-9]+$/i',
                    "unidadMedida_insumo"=>'required',
                    "usuario_insumo"=>'required'
                ]);

                if($validar->fails()){
                    return redirect("/insumos")->with("no-validacion","");
                }else{
                    $insumo = new InsumosModel();
                    $insumo->codigo_insumo = $datos["codigo_insumo"];
                    $insumo->nombre_insumo = $datos["nombre_insumo"];
                    $insumo->descripcion_insumo = $datos["descripcion_insumo"];
                    $insumo->stock_insumo = $datos["stock_insumo"];
                    $insumo->id_unidadMedida = $datos["unidadMedida_insumo"];
                    $insumo->id_usuario = $datos["usuario_insumo"];
                    $insumo->save();

                    return redirect('/insumos')->with("ok-crear","");
                }
            }else{
                return redirect('/insumos')->with("error","");
            }

        }
    }

    public function destroy($id){
        $validar = InsumosModel::where(["IDInsumo"=>$id])->get();
        if(!empty($validar)){
            $insumo = InsumosModel::where(["IDInsumo"=>$validar[0]["IDInsumo"]])->delete();
            return "ok";
        }else{
            return redirect("/insumos")->with("no-borrar", "");
        }
    }

    public function show($id,Request $request){
        $insumo = InsumosModel::where("IDInsumo",$id)->get();
        $administradores = AdministradoresModel::all();
        $insumos =    DB::select("SELECT PL.IDInsumo,PL.codigo_insumo,PL.nombre_insumo,
                                    PL.descripcion_insumo,PL.stock_insumo,
                                    PL.id_usuario,PL.created_at,PL.updated_at,
                                    UM.nombre_unidadMedida FROM insumo PL INNER JOIN unidades_medida UM 
                                    ON PL.id_unidadMedida = UM.id_unidadMedida
                                    ORDER BY IDInsumo DESC");

        $insumo_unidadMedida = DB::select('SELECT * FROM insumo PL INNER JOIN unidades_medida UM ON PL.id_unidadMedida = UM.id_unidadMedida
                                                    WHERE PL.IDInsumo = ?',[$id]);

        $unidadesMedida = UnidadesMedidaModel::all();
        $codificacion_insumo = DB::SELECT('SELECT IFNULL(MAX(codigo_insumo) + 1, 1) AS codificacion_insumo 
                                        FROM insumo ORDER BY IDInsumo DESC LIMIT 1');
        if(count($insumo) != 0){
            return view("paginas.insumos", array("status"=>200,"insumo"=>$insumo,"administradores"=>$administradores,
                                                    "insumos"=>$insumos,"unidadesMedida"=>$unidadesMedida,
                                                    "insumo_unidadMedida"=>$insumo_unidadMedida,"codificacion_insumo"=>$codificacion_insumo));
        }else{
            return view("paginas.insumos", array("status"=>404,"insumo"=>$insumo,"administradores"=>$administradores,
                                                    "insumos"=>$insumos,"unidadesMedida"=>$unidadesMedida,
                                                    "insumo_unidadMedida"=>$insumo_unidadMedida,"codificacion_insumo"=>$codificacion_insumo));
        }
    }

    public function update($id,Request $request){
        $datos =  array("codigo_insumo"=>$request->input("codigo_insumo"),
                        "nombre_insumo"=>$request->input("nombre_insumo"),
                        "descripcion_insumo"=>$request->input("descripcion_insumo"),
                        "stock_insumo"=>$request->input("stock_insumo"),
                        "unidadMedida_insumo"=>$request->input("unidadMedida_insumo"),
                        "usuario_insumo"=>$request->input("usuario_insumo"));

        if(!empty($datos)){
            $validar = \Validator::make($datos,[
                "codigo_insumo"=>'required|regex:/^[0-9]+$/i',
                "nombre_insumo"=>'required|regex:/^[°\\_\\-\\/\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                "descripcion_insumo"=>'required|regex:/^[°\\=\\-\\/\\.\\,\\:\\_\\+\\!\\#\\$\\%\\&\\?\\¡\\¿\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                "stock_insumo"=>'required|regex:/^[0-9]+$/i',
                "usuario_insumo"=>'required'
            ]);

            if($validar->fails()){
                return redirect("/insumos")->with("no-validacion","");
            }else{
                $datos =  array("codigo_insumo"=>$request->input("codigo_insumo"),
                                "nombre_insumo"=>$request->input("nombre_insumo"),
                                "descripcion_insumo"=>$request->input("descripcion_insumo"),
                                "stock_insumo"=>$request->input("stock_insumo"),
                                "id_unidadMedida"=>$request->input("unidadMedida_insumo"),
                                "id_usuario"=>$request->input("usuario_insumo"));

                $insumo = InsumosModel::where('IDInsumo',$id)->update($datos);
                return redirect("/insumos")->with("ok-editar","");
            }
        }else{
            return redirect('/insumos')->with("error","");
        }
    }

    public function createPDF(Request $request) {

        $insumos =   DB::select('SELECT PL.IDInsumo,PL.codigo_insumo,PL.nombre_insumo,
                                           PL.descripcion_insumo,PL.stock_insumo,
                                           PL.id_usuario,PL.created_at,PL.updated_at,
                                           U.id,U.name,UM.nombre_unidadMedida
                                           FROM insumo PL 
                                           INNER JOIN unidades_medida UM ON PL.id_unidadMedida = UM.id_unidadMedida
                                           LEFT JOIN users U ON U.id = PL.id_usuario 
                                           WHERE PL.stock_insumo <> 0 ORDER BY IDInsumo DESC');

        view()->share('insumos',$insumos);
        $pdf = PDF::loadView('paginas.reportesInsumos',$insumos);

        return $pdf->setPaper('a4','landscape')->stream('insumos.pdf');
      }

      public function createAgotadoPDF(Request $request) {

        $insumos =    DB::select('SELECT PL.IDInsumo,PL.codigo_insumo,PL.nombre_insumo,
                                           PL.descripcion_insumo,PL.stock_insumo,
                                           PL.id_usuario,PL.created_at,PL.updated_at,
                                           U.id,U.name,UM.nombre_unidadMedida
                                           FROM insumo PL 
                                           INNER JOIN unidades_medida UM ON PL.id_unidadMedida = UM.id_unidadMedida
                                           LEFT JOIN users U ON U.id = PL.id_usuario 
                                           WHERE PL.stock_insumo = 0 ORDER BY IDInsumo DESC');

        // compartir datos para ver
        view()->share('insumos',$insumos);

        $pdf = PDF::loadView('paginas.reportesInsumosAgotados',$insumos);

        // descargar archivo PDF con método de descarga
        return $pdf->setPaper('a4','landscape')->stream('insumosAgotados.pdf');
      }

      public function createEXCEL(Request $request){

        return Excel::download(new ProductosLimpiezaExport,'insumos.xlsx');
      }

      public function historialPDF($id,Request $request) {

/*         $historialLimpieza = DB::select('SELECT P.IDInsumo,P.nombre_insumo,P.descripcion_insumo,
                                         P.codigo_insumo,K.id_kardexLimpieza,K.id_productoLimpieza,K.motivo_kardexLimpieza,
                                         K.entradas_kardexLimpieza,K.restante_kardexLimpieza,K.salidas_kardexLimpieza,K.created_at,
                                         U.id,U.name FROM kardex_limpieza K INNER JOIN productos_limpieza P 
                                         ON K.id_productoLimpieza = P.id_productoLimpieza 
                                         LEFT JOIN users U ON U.id = K.id_usuario WHERE P.id_productoLimpieza = ? AND
                                         K.entradas_kardexLimpieza <> 0
                                         ORDER BY K.id_kardexLimpieza DESC',[$id]);

        $historialLimpieza1 = DB::select('SELECT P.id_productoLimpieza,P.nombre_productoLimpieza,P.descripcion_productoLimpieza,
                                         P.codigo_productoLimpieza,K.id_kardexLimpieza,K.id_productoLimpieza,K.motivo_kardexLimpieza,
                                         K.entradas_kardexLimpieza,K.restante_kardexLimpieza,K.salidas_kardexLimpieza,K.created_at,
                                         U.id,U.name FROM kardex_limpieza K INNER JOIN productos_limpieza P 
                                         ON K.id_productoLimpieza = P.id_productoLimpieza 
                                         LEFT JOIN users U ON U.id = K.id_usuario WHERE P.id_productoLimpieza = ? AND
                                         K.salidas_kardexLimpieza <> 0
                                         ORDER BY K.id_kardexLimpieza DESC',[$id]);

        $productoLimpieza = DB::SELECT('SELECT * FROM productos_limpieza P WHERE id_productoLimpieza = ?',[$id]);
        view()->share('historialLimpieza',$historialLimpieza);
        view()->share('historialLimpieza1',$historialLimpieza1);
        view()->share('productoLimpieza',$productoLimpieza);
        $pdf = PDF::loadView('paginas.reportesHistorialLimpieza',$historialLimpieza);

        return $pdf->setPaper('a4','landscape')->stream('historialLimpieza.pdf'); */
      }
}
