<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* Agregamos los modelos */
use App\ProductosLimpiezaModel;
use App\AdministradoresModel;
use App\CategoriasModel;
use App\UnidadesMedidaModel;
use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */
use PDF;/* Apuntamos al modelo que existe por defecto para obtener información en PDF */
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductosLimpiezaExport;

class ProductosLimpiezaController extends Controller
{
    public function index(){

        $administradores = AdministradoresModel::all();
        $categorias = CategoriasModel::all();
        $productosLimpieza =    DB::select("SELECT PL.id_productoLimpieza,PL.codigo_productoLimpieza,PL.nombre_productoLimpieza,
                                            PL.descripcion_productoLimpieza,PL.stock_productoLimpieza,PL.id_categoria,
                                            PL.imagen_productoLimpieza,PL.id_usuario,PL.created_at,PL.updated_at,C.id_categoria,
                                            C.nombre_categoria,UM.nombre_unidadMedida,PL.precio_unitario FROM productos_limpieza PL INNER JOIN categorias C ON
                                            C.id_categoria = PL.id_categoria INNER JOIN unidades_medida UM ON PL.id_unidadMedida = UM.id_unidadMedida
                                            ORDER BY id_productoLimpieza DESC");
        $unidadesMedida = UnidadesMedidaModel::all();
        $codificacion_productoLimpieza = DB::SELECT('SELECT codigo_productoLimpieza + 1 as codificacion_productoLimpieza FROM productos_limpieza 
                                                     ORDER BY id_productoLimpieza DESC LIMIT 1');

/*         echo '<pre>';print_r($codificacion_productoLimpieza);echo '</pre>';
        return; */

        return view("paginas.productosLimpieza",  array("administradores"=>$administradores,"categorias"=>$categorias,
                                                        "productosLimpieza"=>$productosLimpieza,"unidadesMedida"=>$unidadesMedida,
                                                        "codificacion_productoLimpieza"=>$codificacion_productoLimpieza));
    }

    public function store(Request $request){
        $datos =  array("categoria_productoLimpieza"=>$request->input("categoria_productoLimpieza"),
                        "codigo_productoLimpieza"=>$request->input("codigo_productoLimpieza"),
                        "nombre_productoLimpieza"=>$request->input("nombre_productoLimpieza"),
                        "descripcion_productoLimpieza"=>$request->input("descripcion_productoLimpieza"),
                        "stock_productoLimpieza"=>$request->input("stock_productoLimpieza"),
                        "precio_productoLimpieza"=>$request->input("precio_productoLimpieza"),
                        "unidadMedida_productoLimpieza"=>$request->input("unidadMedida_productoLimpieza"),
                        "usuario_productoLimpieza"=>$request->input("usuario_productoLimpieza"));

/*         echo '<pre>';print_r($datos);echo '</pre>';
        return; */

        $imagenProductoLimpieza = array("imagen_productoLimpieza"=>$request->file("foto"));

        $codigo_validacion = DB::select('SELECT * FROM productos_limpieza WHERE codigo_productoLimpieza = ?', [$request->input("codigo_productoLimpieza")]);

        if(empty($codigo_validacion) == ""){
            return redirect("/productosLimpieza")->with("codigo-existe","");
        }else{
            /* Validar datos */
            if(!empty($datos)){
                $validar = \Validator::make($datos,[
                    "categoria_productoLimpieza"=>'required',
                    "codigo_productoLimpieza"=>'required|regex:/^[0-9]+$/i',
                    "nombre_productoLimpieza"=>'required|regex:/^[°\\_\\-\\/\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                    "descripcion_productoLimpieza"=>'required|regex:/^[°\\=\\-\\/\\.\\,\\:\\_\\+\\!\\#\\$\\%\\&\\?\\¡\\¿\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                    "stock_productoLimpieza"=>'required|regex:/^[0-9]+$/i',
                    "precio_productoLimpieza"=>'required|regex:/^[.\\0-9]+$/i',
                    "unidadMedida_productoLimpieza"=>'required',
                    "usuario_productoLimpieza"=>'required'
                ]);

                /* Guardar Dirección Ejecutiva */
                if($validar->fails()){
                    return redirect("/productosLimpieza")->with("no-validacion","");
                }else{
                    if($imagenProductoLimpieza["imagen_productoLimpieza"]==''){
                        $ruta = "img/productosLimpieza/sinImagen.jpg";
                    }else{
                        $aleatorio = mt_rand(100,999);
                        $ruta = "img/productosLimpieza/".$aleatorio.".".$imagenProductoLimpieza["imagen_productoLimpieza"]->guessExtension();
                        move_uploaded_file($imagenProductoLimpieza["imagen_productoLimpieza"], $ruta);
                    }

                    $productoLimpieza = new ProductosLimpiezaModel();
                    $productoLimpieza->codigo_productoLimpieza = $datos["codigo_productoLimpieza"];
                    $productoLimpieza->nombre_productoLimpieza = $datos["nombre_productoLimpieza"];
                    $productoLimpieza->descripcion_productoLimpieza = $datos["descripcion_productoLimpieza"];
                    $productoLimpieza->stock_productoLimpieza = $datos["stock_productoLimpieza"];
                    $productoLimpieza->precio_unitario = $datos["precio_productoLimpieza"];
                    $productoLimpieza->id_unidadMedida = $datos["unidadMedida_productoLimpieza"];
                    $productoLimpieza->id_categoria = $datos["categoria_productoLimpieza"];
                    $productoLimpieza->id_usuario = $datos["usuario_productoLimpieza"];
                    $productoLimpieza->imagen_productoLimpieza = $ruta;

                    $productoLimpieza->save();
                    return redirect('/productosLimpieza')->with("ok-crear","");

                }
            }else{
                return redirect('/productosLimpieza')->with("error","");
            }

        }
    }

    public function destroy($id){
        $validar = ProductosLimpiezaModel::where(["id_productoLimpieza"=>$id])->get();
        if(!empty($validar)){
            $productoLimpieza = ProductosLimpiezaModel::where(["id_productoLimpieza"=>$validar[0]["id_productoLimpieza"]])->delete();
            //Responder al AJAX de JS
            return "ok";
        }else{
            return redirect("/productosLimpieza")->with("no-borrar", "");
        }
    }

    public function show($id,Request $request){
        $productoLimpieza = ProductosLimpiezaModel::where("id_productoLimpieza",$id)->get();
        $administradores = AdministradoresModel::all();
        $categorias = CategoriasModel::all();
        $productosLimpieza =    DB::select("SELECT PL.id_productoLimpieza,PL.codigo_productoLimpieza,PL.nombre_productoLimpieza,
                                            PL.descripcion_productoLimpieza,PL.stock_productoLimpieza,PL.id_categoria,
                                            PL.imagen_productoLimpieza,PL.id_usuario,PL.created_at,PL.updated_at,C.id_categoria,
                                            C.nombre_categoria,UM.nombre_unidadMedida,PL.precio_unitario FROM productos_limpieza PL INNER JOIN categorias C ON
                                            C.id_categoria = PL.id_categoria INNER JOIN unidades_medida UM ON PL.id_unidadMedida = UM.id_unidadMedida
                                            ORDER BY id_productoLimpieza DESC");

        $productoLimpieza_categoria =   DB::select('SELECT * FROM productos_limpieza PL INNER JOIN categorias C ON PL.id_categoria = C.id_categoria
                                                    WHERE PL.id_productoLimpieza = ?',[$id]);

        $productoLimpieza_unidadMedida = DB::select('SELECT * FROM productos_limpieza PL INNER JOIN unidades_medida UM ON PL.id_unidadMedida = UM.id_unidadMedida
                                                    WHERE PL.id_productoLimpieza = ?',[$id]);

        $unidadesMedida = UnidadesMedidaModel::all();
        $codificacion_productoLimpieza = DB::SELECT('SELECT codigo_productoLimpieza + 1 as codificacion_productoLimpieza FROM productos_limpieza 
                                                     ORDER BY id_productoLimpieza DESC LIMIT 1');

        if(count($productoLimpieza) != 0){
            return view("paginas.productosLimpieza", array("status"=>200,"productoLimpieza"=>$productoLimpieza,"administradores"=>$administradores,
                                                    "productosLimpieza"=>$productosLimpieza,"categorias"=>$categorias,
                                                    "productoLimpieza_categoria"=>$productoLimpieza_categoria,"unidadesMedida"=>$unidadesMedida,
                                                    "productoLimpieza_unidadMedida"=>$productoLimpieza_unidadMedida,"codificacion_productoLimpieza"=>$codificacion_productoLimpieza));
        }else{
            return view("paginas.productosLimpieza", array("status"=>404,"productoLimpieza"=>$productoLimpieza,"administradores"=>$administradores,
                                                    "productosLimpieza"=>$productosLimpieza,"categorias"=>$categorias,
                                                    "productoLimpieza_categoria"=>$productoLimpieza_categoria,"unidadesMedida"=>$unidadesMedida,
                                                    "productoLimpieza_unidadMedida"=>$productoLimpieza_unidadMedida,"codificacion_productoLimpieza"=>$codificacion_productoLimpieza));
        }
    }

    public function update($id,Request $request){
        $datos =  array("categoria_productoLimpieza"=>$request->input("categoria_productoLimpieza"),
                        "codigo_productoLimpieza"=>$request->input("codigo_productoLimpieza"),
                        "nombre_productoLimpieza"=>$request->input("nombre_productoLimpieza"),
                        "descripcion_productoLimpieza"=>$request->input("descripcion_productoLimpieza"),
                        "stock_productoLimpieza"=>$request->input("stock_productoLimpieza"),
                        "precio_productoLimpieza"=>$request->input("precio_productoLimpieza"),
                        "unidadMedida_productoLimpieza"=>$request->input("unidadMedida_productoLimpieza"),
                        "usuario_productoLimpieza"=>$request->input("usuario_productoLimpieza"));

        $imagen_actual = array("imagen_actual"=>$request->input("imagen_actual"));
        $imagenProductoLimpieza = array("imagen_productoLimpieza"=>$request->file("foto"));

        /* Validar datos */
        if(!empty($datos)){
            $validar = \Validator::make($datos,[
                "categoria_productoLimpieza"=>'required',
                "codigo_productoLimpieza"=>'required|regex:/^[0-9]+$/i',
                "nombre_productoLimpieza"=>'required|regex:/^[°\\_\\-\\/\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                "descripcion_productoLimpieza"=>'required|regex:/^[°\\=\\-\\/\\.\\,\\:\\_\\+\\!\\#\\$\\%\\&\\?\\¡\\¿\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                "stock_productoLimpieza"=>'required|regex:/^[0-9]+$/i',
                "precio_productoLimpieza"=>'required|regex:/^[.\\0-9]+$/i',
                "usuario_productoLimpieza"=>'required'
            ]);

            if($validar->fails()){
                return redirect("/productosLimpieza")->with("no-validacion","");
            }else{
                if($imagenProductoLimpieza["imagen_productoLimpieza"] != ""){

                    if(!empty($imagen_actual["imagen_actual"])){

                        if($imagen_actual["imagen_actual"] != "img/productosLimpieza/sinImagen.jpg"){

                            unlink($imagen_actual["imagen_actual"]);

                        }

                    }

                    $aleatorio = mt_rand(100,999);

                    $ruta = "img/productosLimpieza/".$aleatorio.".".$imagenProductoLimpieza["imagen_productoLimpieza"]->guessExtension();

                    move_uploaded_file($imagenProductoLimpieza["imagen_productoLimpieza"], $ruta);

                }else{
                    $ruta = $imagen_actual["imagen_actual"];
                }

                $datos =  array("id_categoria"=>$request->input("categoria_productoLimpieza"),
                                "codigo_productoLimpieza"=>$request->input("codigo_productoLimpieza"),
                                "nombre_productoLimpieza"=>$request->input("nombre_productoLimpieza"),
                                "descripcion_productoLimpieza"=>$request->input("descripcion_productoLimpieza"),
                                "stock_productoLimpieza"=>$request->input("stock_productoLimpieza"),
                                "precio_unitario"=>$request->input("precio_productoLimpieza"),
                                "id_unidadMedida"=>$request->input("unidadMedida_productoLimpieza"),
                                "id_usuario"=>$request->input("usuario_productoLimpieza"),
                                "imagen_productoLimpieza"=>$ruta);

                $productoLimpieza = ProductosLimpiezaModel::where('id_productoLimpieza',$id)->update($datos);
                return redirect("/productosLimpieza")->with("ok-editar","");
            }
        }else{
            return redirect('/productosLimpieza')->with("error","");
        }

    }

    public function createPDF(Request $request) {

        $productosLimpieza =   DB::select('SELECT PL.id_productoLimpieza,PL.codigo_productoLimpieza,PL.nombre_productoLimpieza,
                                           PL.descripcion_productoLimpieza,PL.stock_productoLimpieza,PL.id_categoria,
                                           PL.imagen_productoLimpieza,PL.id_usuario,PL.created_at,PL.updated_at,
                                           C.id_categoria,C.nombre_categoria,U.id,U.name,UM.nombre_unidadMedida,PL.precio_unitario 
                                           FROM productos_limpieza PL 
                                           INNER JOIN categorias C ON C.id_categoria = PL.id_categoria 
                                           INNER JOIN unidades_medida UM ON PL.id_unidadMedida = UM.id_unidadMedida
                                           LEFT JOIN users U ON U.id = PL.id_usuario 
                                           WHERE PL.stock_productoLimpieza <> 0 ORDER BY id_productoLimpieza DESC');

        /* echo '<pre>';print_r($productosLimpieza);echo '</pre>';
        return; */

        // compartir datos para ver
        view()->share('productosLimpieza',$productosLimpieza);

        $pdf = PDF::loadView('paginas.reportesProductosLimpieza',$productosLimpieza);

        // descargar archivo PDF con método de descarga
        return $pdf->setPaper('a4','landscape')->stream('productosLimpieza.pdf');
      }

      public function createAgotadoPDF(Request $request) {

        $productosLimpieza =    DB::select('SELECT PL.id_productoLimpieza,PL.codigo_productoLimpieza,PL.nombre_productoLimpieza,
                                            PL.descripcion_productoLimpieza,PL.stock_productoLimpieza,PL.id_categoria,
                                            PL.imagen_productoLimpieza,PL.id_usuario,PL.created_at,PL.updated_at,
                                            C.id_categoria,C.nombre_categoria,U.id,U.name,UM.nombre_unidadMedida,PL.precio_unitario 
                                            FROM productos_limpieza PL 
                                            INNER JOIN categorias C ON C.id_categoria = PL.id_categoria 
                                            INNER JOIN unidades_medida UM ON PL.id_unidadMedida = UM.id_unidadMedida
                                            LEFT JOIN users U ON U.id = PL.id_usuario 
                                            WHERE PL.stock_productoLimpieza = 0 ORDER BY id_productoLimpieza DESC');

        // compartir datos para ver
        view()->share('productosLimpieza',$productosLimpieza);

        $pdf = PDF::loadView('paginas.reportesProductosLimpiezaAgotados',$productosLimpieza);

        // descargar archivo PDF con método de descarga
        return $pdf->setPaper('a4','landscape')->stream('productosLimpiezaAgotados.pdf');
      }

      public function createEXCEL(Request $request){

        return Excel::download(new ProductosLimpiezaExport,'productosLimpieza.xlsx');
      }

      public function historialPDF($id,Request $request) {

        $historialLimpieza = DB::select('SELECT P.id_productoLimpieza,P.nombre_productoLimpieza,P.descripcion_productoLimpieza,
                                         P.codigo_productoLimpieza,K.id_kardexLimpieza,K.id_productoLimpieza,K.motivo_kardexLimpieza,
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

/*         echo '<pre>';print_r($productoLimpieza);echo '</pre>';
        return; */

        // compartir datos para ver
        view()->share('historialLimpieza',$historialLimpieza);
        view()->share('historialLimpieza1',$historialLimpieza1);
        view()->share('productoLimpieza',$productoLimpieza);

        $pdf = PDF::loadView('paginas.reportesHistorialLimpieza',$historialLimpieza);

        // descargar archivo PDF con método de descarga
        return $pdf->setPaper('a4','landscape')->stream('historialLimpieza.pdf');
      }
}
