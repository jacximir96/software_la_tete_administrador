<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdministradoresModel;
use App\PacientesCitadosModel;
use App\FuaModel;
use App\FuaNModel;
use App\Fua1ActualizacionModel;
use App\Fua2ActualizacionModel;
use App\Fua3ActualizacionModel;
use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */
use PDF;/* Apuntamos al modelo que existe por defecto para obtener información en PDF */
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Exports\FuasExport;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportesEntreFechasController extends Controller
{
    public function index(){
        $administradores = AdministradoresModel::on('sqlsrv')->get();
        $estados = DB::select('SELECT * FROM software_ufpa_general.dbo.estado');
        $usuarios = DB::SELECT("SELECT * FROM software_ufpa_general.dbo.users WHERE id <> 1");
        return view('paginas.reportesEntreFechas',array("administradores"=>$administradores,"estados"=>$estados,
                                                        "usuarios"=>$usuarios));
    }

    public function fuasGeneradosEXCEL(Request $request)
    {
        $fuasGenerados = DB::TABLE('software_ufpa_general.dbo.FUA')
        ->JOIN('software_ufpa_general.dbo.users', 'software_ufpa_general.dbo.FUA.IdUsuario', '=', 'software_ufpa_general.dbo.users.id')
        ->JOIN('ECONOMIA.dbo.FUA2', 'ECONOMIA.dbo.FUA2.IdAtencion', '=', 'software_ufpa_general.dbo.FUA.NroFua')
        ->SELECT(DB::raw("FUA2.DISA + '-' + FUA2.LOTE + '-' + FUA2.Numero AS [NRO. DE FUA]"),DB::RAW("FORMAT(CAST(software_ufpa_general.dbo.FUA.created_at AS DATE), 'dd-MM-yyyy') AS [FECHA DE CREACIÓN]"),DB::RAW("FORMAT(CAST(ECONOMIA.dbo.FUA2.FechaHoraAtencion AS DATE), 'dd-MM-yyyy') AS [FECHA DE ATENCIÓN]"),DB::RAW("software_ufpa_general.dbo.users.name AS [NOMBRE DE USUARIO]"),'FUA.TipoObservacion AS OBSERVACIÓN')
        ->WHERE('software_ufpa_general.dbo.FUA.IdUsuario', '=', $request->usuario_reportesEntreFechas)
        ->WHEREBETWEEN(DB::raw('CONVERT(date,software_ufpa_general.dbo.FUA.created_at)'), array($request->fechaInicial_reportesEntreFechas,$request->fechaFinal_reportesEntreFechas))
        ->GET();

        /* return $fuasGenerados; */

        return (new FastExcel($fuasGenerados))->download('Reporte_Fuas_Generados.xlsx');
    }
}
