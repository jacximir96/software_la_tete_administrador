<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* Modelos de nuestro proyecto */
use App\AdministradoresModel;
use App\RolesModel;
use Carbon;
/* Fin de Modelos de nuestro proyecto */

use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */

class RolesController extends Controller
{
    /* Mostrar todos los registros */
    public function index(){

        $administradores = AdministradoresModel::all();
        $roles = DB::select('SELECT * FROM roles R INNER JOIN
                             estado E ON R.estado_rol = E.id_estado');
        $role_has_permissions = DB::select('SELECT * FROM role_has_permissions R INNER JOIN permissions P ON R.permission_id = P.id');

        return view("paginas.roles",array("administradores"=>$administradores,"role_has_permissions"=>$role_has_permissions),['roles'=>$roles]);
    }

    public function show($id){

        $rol = RolesModel::where("id",$id)->get();
        $administradores = AdministradoresModel::all();
        $roles = DB::select('SELECT * FROM roles R INNER JOIN
                             estado E ON R.estado_rol = E.id_estado');
        $rol_permisos=DB::select('SELECT * FROM role_has_permissions where role_id = ?',[$id]);
        $role_has_permissions = DB::select('SELECT * FROM role_has_permissions R INNER JOIN permissions P ON R.permission_id = P.id
                                            WHERE R.role_id = 3');

        /*echo '<pre>';print_r($role_has_permissions);echo '</pre>';
        return;*/

        if(count($rol) != 0){
            return view("paginas.roles",array("status"=>200,"rol"=>$rol,
            "administradores"=>$administradores,
            "rol_permisos"=>$rol_permisos,"role_has_permissions"=>$role_has_permissions),['roles'=>$roles]);
        }else{
            return view("paginas.roles",array("status"=>404,"rol"=>$rol,
            "administradores"=>$administradores,
            "rol_permisos"=>$rol_permisos,"role_has_permissions"=>$role_has_permissions),['roles'=>$roles]);
        }
    }

    public function store(Request $request){

                $datos = array("nombre_rol"=>$request->input("nombre_rol"),
                                "estado_rol"=>$request->input("estado_rol"));

                        /* Validar datos */
                        if(!empty($datos)){
                            $validar = \Validator::make($datos,[
                                "nombre_rol"=>'required|regex:/^[,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                                "estado_rol"=>'required'
                            ]);

                            /* Guardar Dirección Ejecutiva */
                            if($validar->fails()){
                                return redirect("/roles")->with("no-validacion","");
                            }else{

                                $rol = new RolesModel();
                                $rol->name = $datos["nombre_rol"];
                                $rol->guard_name = 'web';
                                $rol->estado_rol = $datos["estado_rol"];
/*                                 $rol->created_at = Carbon\Carbon::now(); */

/*                                 echo '<pre>';print_r($rol);echo '</pre>';
                                return; */

                                $rol->save();
                                return redirect('/roles')->with("ok-crear","");

                            }
                        }else{
                            return redirect('/roles')->with("error","");
                        }
            }

        /* Inicio Eliminar un registro */
        public function destroy($id, Request $request){
            $validar = RolesModel::where("id",$id)->get();

            if(!empty($validar) && $id != 1){
                $rol = RolesModel::where("id",$validar[0]["id"])->delete();
                //Responder al AJAX de JS
                return "ok";
            }else{
                return redirect("/roles")->with("no-borrar", "");
            }
        } /* Fin Eliminar un registro */

        public function update($id,Request $request){

            $datos = array("nombre_rol"=>$request->input("nombre_rol"),
                            "estado_rol"=>$request->input("estado_rol"));

            //validar los datos
            if(!empty($datos)){
                $validar = \Validator::make($datos,[
                    "nombre_rol"=>'required|regex:/^[_\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                    "estado_rol"=>'required'
                ]);

                if($validar->fails()){
                    return redirect("/roles")->with("no-validacion","");
                }else{

                    $datos = array("name"=>$request->input("nombre_rol"),
                                    "estado_rol"=>$request->input("estado_rol"));

                    $rol = RolesModel::where('id',$id)->update($datos);
                    return redirect("/roles")->with("ok-editar","");
                }

            }else{
                return redirect("/roles")->with("error","");
            }
        }
}
