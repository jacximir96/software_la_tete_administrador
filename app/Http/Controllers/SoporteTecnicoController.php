<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdministradoresModel;
use App\RolesModel;
use Carbon;
use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */
use PDF;/* Apuntamos al modelo que existe por defecto para obtener información en PDF */

class SoporteTecnicoController extends Controller
{
    public function index(){

        /* Si existe un requerimiento del tipo AJAX */
        if(request()->ajax()){
            return datatables()->of(DB::SELECT("SELECT * FROM SOFTWARE_UFPA_GENERAL.dbo.soporteTecnico ST"))->make(true);
        }

        $administradores = AdministradoresModel::all();

        return view("paginas.soporteTecnico",array("administradores"=>$administradores));
    }
}
