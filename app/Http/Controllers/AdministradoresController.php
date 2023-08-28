<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* Modelos de nuestro proyecto */
use App\AdministradoresModel;
use App\DepartamentosModel;
use App\DireccionesEjecutivasModel;
use App\RolesModel;
use App\ModelHasRolesModel;
/* Fin de Modelos de nuestro proyecto */

use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdministradoresController extends Controller
{
    /* Mostrar todos los registros */
    public function index(){

        $administradores =  DB::select('SELECT U.id as id_usuario,U.name,U.email,U.foto,U.id_rol,U.password,R.id,R.name rol from users U
                                        LEFT JOIN roles R ON U.id_rol = R.id');
        $user = Auth::user();
        $roles = DB::select('SELECT * FROM roles WHERE estado_rol = 1');

        return view("paginas.administradores",array("administradores"=>$administradores,"roles"=>$roles,"user"=>$user));
    }

    /* Mostrar un solo registro */
    public function show($id){
        $administrador = AdministradoresModel::where("id",$id)->get();
        $administradores = DB::SELECT('SELECT U.id as id_usuario,U.name,U.email,U.foto,U.id_rol,U.password,R.id,R.name rol from users U
                                        LEFT JOIN roles R ON U.id_rol = R.id');

        $administrador_rol = DB::select('SELECT U.id as id_usuario,U.name as usuario,U.email,U.foto,U.id_rol,U.password,R.id,R.name from users U
                                        INNER JOIN roles R ON U.id_rol = R.id WHERE U.id = ?',[$id]);

        /*echo '<pre>';print_r($administradores);echo '</pre>';
        return;*/

        $roles = DB::select('SELECT * FROM roles WHERE estado_rol = 1');

        if(count($administrador) != 0){
            return view("paginas.administradores",array("status"=>200,"administrador"=>
            $administrador, "administradores"=>$administradores,"administrador_rol"=>$administrador_rol,"roles"=>$roles));
        }else{
            return view("paginas.administradores",array("status"=>404,"administradores"=>$administradores,"roles"=>$roles));
        }
    }

    /* Editar un registro */
    public function update($id, Request $request){
        //recoger datos
        $datos = array("name"=>$request->input("name"),
                       "email"=>$request->input("email"),
                       "password_actual"=>$request->input("password_actual"),
                       "id_rol"=>$request->input("id_rol"),
                       "imagen_actual"=>$request->input("imagen_actual"));

        $datos1 = array("role_id" => $datos["id_rol"],
                       "model_type" => 'App\User',
                       "model_id" => $id);

        $password = array("password"=>$request->input("password"));
        $foto = array("foto"=>$request->file("foto"));

/*         echo '<pre>';print_r($datos);echo '</pre>';
        echo '<pre>';print_r($datos1);echo '</pre>';
        return; */

        //validar los datos
        if(!empty($datos)){
            $validar = \Validator::make($datos,[
                'name' => "required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i",
                'email' => 'required|regex:/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/i'
            ]);

        if($password["password"] != ""){
            $validarPassword = \Validator::make($password,[
                "password" => "required|regex:/^[_\\-\\;\\,\\+\\-\\*\\.\\&\\%\\$\\#\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ]+$/i"
            ]);

            if($validarPassword->fails()){
                return redirect("/administradores")->with("no-validacion","");
            }else{
                $nuevaPassword = Hash::make($password['password']);
            }
        }else{
            $nuevaPassword = $datos["password_actual"];
        }

        if($foto["foto"] != ""){
            $validarFoto = \Validator::make($foto,[
                "foto" => 'required|image|mimes:jpg,jpeg,png|max:2000000'
            ]);

            if($validarFoto->fails()){
                return redirect("/administradores")->with("no-validacion","");
            }
        }

        if($validar->fails()){
            return redirect("/administradores")->with("no-validacion","");
        }else{
            if($foto["foto"] != ""){

                if(!empty($datos["imagen_actual"])){

                    if($datos["imagen_actual"] != "img/administradores/administrador.png"){

                        unlink($datos["imagen_actual"]);

                    }

                }

                $aleatorio = mt_rand(100,999);

                $ruta = "img/administradores/".$aleatorio.".".$foto["foto"]->guessExtension();

                move_uploaded_file($foto["foto"], $ruta);

            }else{
                $ruta = $datos["imagen_actual"];
            }

            $datos = array("name" => $datos["name"],
                           "email" => $datos["email"],
                           "password" => $nuevaPassword,
                           "id_rol" => $datos["id_rol"],
                           "foto" => $ruta);

            $datos1 = array("role_id" => $datos["id_rol"],
                            "model_type" => 'App\User',
                            "model_id" => $id);

            $administrador = AdministradoresModel::where('id',$id)->update($datos);
            $model_has_roles = ModelHasRolesModel::where('model_id',$id)->update($datos1);
            return redirect("/administradores")->with("ok-editar","");
        }
     }else{
        return redirect("/administradores")->with("error","");
     }
    }

    /* Eliminar un registro */
    public function destroy($id, Request $request){
        $validar = AdministradoresModel::where("id",$id)->get();

        if(!empty($validar) && $id != 1){

    		if(!empty($validar[0]["foto"])){

    			unlink($validar[0]["foto"]);

    		}

    		$administrador = AdministradoresModel::where("id",$validar[0]["id"])->delete();
            $model_has_roles = ModelHasRolesModel::where('model_id',$validar[0]["id"])->delete();

    		//Responder al AJAX de JS
    		return "ok";

    	}else{

    		return redirect("/administradores")->with("no-borrar", "");

    	}
    }
}
