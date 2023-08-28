<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* Modelos de nuestro proyecto */
use App\AdministradoresModel;
use App\RolesModel;
use App\RoleHasPermissionsModel;
/* Fin de Modelos de nuestro proyecto */

use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */

class PermisosRolesController extends Controller
{
    /* Mostrar todos los registros */
    public function index(){

        $administradores = AdministradoresModel::all();
        $roles = DB::select('SELECT * FROM roles R INNER JOIN
                             estado E ON R.estado_rol = E.id_estado');
        $role_has_permissions = DB::select('SELECT * FROM role_has_permissions R INNER JOIN permissions P ON R.permission_id = P.id');

        return view("paginas.permisos",array("administradores"=>$administradores,"role_has_permissions"=>$role_has_permissions),['roles'=>$roles]);
    }

    public function show($id){

        $rol = RolesModel::where("id",$id)->get();
        $administradores = AdministradoresModel::all();
        $roles = DB::select('SELECT * FROM roles R INNER JOIN
                             estado E ON R.estado_rol = E.id_estado');
        $rol_unidad = DB::select('SELECT * FROM roles where id = ?',[$id]);
        $rol_permisos=DB::select('SELECT * FROM role_has_permissions where role_id = ?',[$id]);
        $role_has_permissions = DB::select('SELECT * FROM role_has_permissions R INNER JOIN permissions P ON R.permission_id = P.id
                                            WHERE role_id = ?',[$id]);

        $permission = DB::select("SELECT * FROM permissions");

        if(count($rol) != 0){
            return view("paginas.permisos",array("status"=>200,"rol"=>$rol,
        "administradores"=>$administradores,
        "rol_permisos"=>$rol_permisos,"role_has_permissions"=>$role_has_permissions,"permission"=>$permission,"rol_unidad"=>$rol_unidad),['roles'=>$roles]);
        }else{
            return view("paginas.permisos",array("status"=>404,"rol"=>$rol,
            "administradores"=>$administradores,
            "rol_permisos"=>$rol_permisos,"role_has_permissions"=>$role_has_permissions,"permission"=>$permission,"rol_unidad"=>$rol_unidad),['roles'=>$roles]);
        }
    }

    public function store(Request $request){

        $datos = array("permission_id"=>$request->input("select_permisos"),
                        "role_id"=>$request->input("role_id"));

        /* Validar datos */
        if(!empty($datos)){
            $validar = \Validator::make($datos,[
                "permission_id"=>'required',
                "role_id"=>'required'
            ]);

            /* Guardar Dirección Ejecutiva */
            if($validar->fails()){
                return redirect('/permisos/'.$datos["role_id"])->with("no-validacion","");
            }else{

                foreach($datos["permission_id"] as $key => $element_permisos){

                $permiso = new RoleHasPermissionsModel();
                $permiso->permission_id = $element_permisos;
                $permiso->role_id = $datos["role_id"];

                $permiso->save();

                }

                return redirect('/permisos/'.$datos["role_id"])->with("ok-crear","");

            }
        }else{
            return redirect('/permisos/'.$datos["role_id"])->with("error","");
        }

    }

            /* Inicio Eliminar un registro */
            public function destroy($role_id,$permission_id){
                $validar = RoleHasPermissionsModel::where(["role_id"=>$role_id,"permission_id"=>$permission_id])->get();

                if(!empty($validar)){
                    $permission = RoleHasPermissionsModel::where(["role_id"=>$validar[0]["role_id"],"permission_id"=>$permission_id])->delete();
                    //Responder al AJAX de JS
                    return "ok";
                }else{
                    return redirect("/permisos/17")->with("no-borrar", "");
                }
            } /* Fin Eliminar un registro */

            /* Inicio Eliminar un registro */
            public function destroy1($role_id){
                $validar = RoleHasPermissionsModel::where(["role_id"=>$role_id])->get();
            
                if(!empty($validar)){
                    $permission = RoleHasPermissionsModel::where(["role_id"=>$validar[0]["role_id"]])->delete();
                    //Responder al AJAX de JS
                    return "ok";
                }else{
                    return redirect("/permisos/".$role_id)->with("no-borrar", "");
                }
            } /* Fin Eliminar un registro */
}
