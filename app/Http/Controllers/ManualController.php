<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/* Modelos de nuestro proyecto */
use App\AdministradoresModel;
use App\DepartamentosModel;
use App\DireccionesEjecutivasModel;
use App\RolesModel;
/* Fin de Modelos de nuestro proyecto */

use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */

class ManualController extends Controller
{
        /* Mostrar todos los registros */
        public function index(){

            $administradores = AdministradoresModel::all();

            return view("paginas.manual",array("administradores"=>$administradores));
        }
}
