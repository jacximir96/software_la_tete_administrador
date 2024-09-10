<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdministradoresModel;
use App\CategoriasModel;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CategoriasExport;

class CategoriasController extends Controller
{
    public function index(){

        $administradores = AdministradoresModel::all();
        $categorias = DB::select('SELECT * FROM categorias ORDER BY id_categoria DESC');

        return view('paginas.categorias',array("categorias"=>$categorias,"administradores"=>$administradores));
    }

    public function store(Request $request){
        $datos =  array("nombre_categoria"=>strtoupper($request->input("nombre_categoria")),
                        "descripcion_categoria"=>strtoupper($request->input("descripcion_categoria")),
                        "usuario_categoria"=>$request->input("usuario_categoria"));

        if(!empty($datos)){
            $validar = \Validator::make($datos,[
                "nombre_categoria" =>'required|regex:/^[,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                "descripcion_categoria" =>'required|regex:/^[_\\-\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                "usuario_categoria" => 'required'
            ]);

            if($validar->fails()){
                return redirect('/categorias')->with("no-validacion","");
            }else{
                $categoria = new CategoriasModel();
                $categoria->nombre_categoria = $datos["nombre_categoria"];
                $categoria->descripcion_categoria = $datos["descripcion_categoria"];
                $categoria->id_usuario = $datos["usuario_categoria"];
                $categoria->save();

                return redirect('/categorias')->with("ok-crear","");
            }
        }else{
            return redirect('/categorias')->with("error","");
        }
    }

    public function destroy($id){
        $validar = CategoriasModel::where(["id_categoria"=>$id])->get();
        if(!empty($validar) && $id != 1 && $id != 2 && $id != 3){
            $categoria = CategoriasModel::where(["id_categoria"=>$validar[0]["id_categoria"]])->delete();
            return "ok";
        }else{
            return redirect("/categorias")->with("no-borrar", "");
        }
    }

    public function show($id,Request $request){
        $categoria = CategoriasModel::where("id_categoria",$id)->get();
        $administradores = AdministradoresModel::all();
        $categorias =   DB::select('SELECT C.id_categoria,C.nombre_categoria,C.codigo_categoria,C.descripcion_categoria,C.id_usuario,
                                    C.created_at,C.updated_at,U.id,U.name
                                    FROM categorias C LEFT JOIN users U ON U.id = C.id_usuario
                                    ORDER BY id_categoria DESC');

        if(count($categoria) != 0){
            return view("paginas.categorias", array("status"=>200,"categoria"=>$categoria,"administradores"=>$administradores,
                                                    "categorias"=>$categorias));
        }else{
            return view("paginas.categorias", array("status"=>404,"categoria"=>$categoria,"administradores"=>$administradores,
                                                    "categorias"=>$categorias));
        }
    }

    public function update($id, Request $request){
        $datos =  array("nombre_categoria"=>strtoupper($request->input("nombre_categoria")),
                        "descripcion_categoria"=>strtoupper($request->input("descripcion_categoria")),
                        "usuario_categoria"=>$request->input("usuario_categoria"));

        //validar los datos
        if(!empty($datos)){
            $validar = \Validator::make($datos,[
                "nombre_categoria" =>'required|regex:/^[,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                "descripcion_categoria" =>'required|regex:/^[_\\-\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                "usuario_categoria" => 'required'
            ]);

            if($validar->fails()){
                return redirect("/categorias")->with("no-validacion","");
            }else{

                $datos =  array("nombre_categoria"=>strtoupper($request->input("nombre_categoria")),
                                "descripcion_categoria"=>strtoupper($request->input("descripcion_categoria")),
                                "id_usuario"=>$request->input("usuario_categoria"));

                $categoria = CategoriasModel::where('id_categoria',$id)->update($datos);
                return redirect("/categorias")->with("ok-editar","");
            }

        }else{
            return redirect("/categorias")->with("error","");
        }
    }

    public function createPDF(Request $request) {

        $categorias =   DB::select('SELECT C.id_categoria,C.nombre_categoria,C.descripcion_categoria,C.id_usuario,
                                    C.created_at,C.updated_at,U.id,U.name
                                    FROM categorias C LEFT JOIN users U ON U.id = C.id_usuario
                                    ORDER BY id_categoria DESC');

        view()->share('categorias',$categorias);
        $pdf = PDF::loadView('paginas.reportesCategorias',$categorias);

        return $pdf->setPaper('a4','landscape')->stream('categorias.pdf');
      }

      public function createEXCEL(Request $request){

        $categorias =   DB::select('SELECT C.id_categoria,C.nombre_categoria,C.codigo_categoria,C.descripcion_categoria,C.id_usuario,
                                    C.created_at,C.updated_at,U.id,U.name
                                    FROM categorias C LEFT JOIN users U ON U.id = C.id_usuario
                                    ORDER BY id_categoria DESC');

        return Excel::download(new CategoriasExport,'categorias.xlsx');
      }
}
