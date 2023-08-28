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
use App\ComprimidoZipModel;
use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */
use PDF;/* Apuntamos al modelo que existe por defecto para obtener información en PDF */
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Response;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use File;
use Madzipper;
use SoapClient;
use SimpleXMLElement;

class FuasAcervoController extends Controller
{
    public function index(){

        /* Si existe un requerimiento del tipo AJAX */
      if(request()->ajax()){
        return datatables()->of(DB::SELECT("SELECT FU.IdAtencion,FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ FU.PrimerNombre +' '+ CASE WHEN FU.OtrosNombres IS NULL THEN '' ELSE FU.OtrosNombres END AS Paciente,
                                            FU.HistoriaClinica,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraAtencion,FORMAT(CAST(FU.FechaHoraDigitacion AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraDigitacion,
                                            FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                                            FU.cie1_cod,digitarFua_estado,FU.IdAtencion as Fua_id,U.name,FU.nombrePaquete_tramas FROM ECONOMIA.dbo.FUA2 FU INNER JOIN [software_ufpa_general].[dbo].[FUA] F ON FU.IdAtencion = F.NroFua LEFT JOIN software_ufpa_general.dbo.users U ON F.IdUsuario_digitacion = U.id
                                            LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id WHERE 
                                            CAST(FU.FechaHoraRegistro AS DATE) = CONVERT(DATE,GETDATE()) AND digitarFua_estado = '1'"))->make(true);
      }

      $administradores = AdministradoresModel::on('sqlsrv')->get();
      $estados = DB::select('SELECT * FROM software_ufpa_general.dbo.estado');
      $concPrestacional = DB::SELECT("SELECT CP.descripcion AS descripcion,id FROM ECONOMIA.dbo.sisMODALIDADATENCION CP");
      $destinoAsegurado =DB::SELECT("SELECT DA.descripcion AS descripcion,id FROM ECONOMIA.dbo.sisDESTINOASEGURADO DA");
      $codPrestacional = DB::SELECT("SELECT * FROM economia.dbo.sisSERVICIOS WHERE flag = 'A' AND id <> 000");
      $componente = DB::SELECT("SELECT SC.descripcion AS descripcion,SC.id FROM ECONOMIA.dbo.sisCOMPONENTES SC WHERE id <> 3");
      $personalAtiende = DB::SELECT("SELECT SOP.descripcion AS descripcion,SOP.id
                                     FROM ECONOMIA.dbo.sisORIGENPERSONAL SOP");
      $lugarAtencion = DB::SELECT("SELECT LA.descripcion AS descripcion,LA.id FROM ECONOMIA.dbo.sisLUGARATENCION LA");
      $tipoAtencion = DB::SELECT("SELECT TA.descripcion AS descripcion,id FROM ECONOMIA.dbo.sisTIPOATENCION TA");
      $datosPersonalC = DB::SELECT("SELECT PER.id AS Profesional_id,PER.per_apat+' '+PER.per_amat+', '+PER.per_nomb AS Profesional FROM PERSONAL.dbo.personal PER 
                                    ORDER BY PER.per_apat ASC,PER.per_amat ASC");
      $sisTipoPersonalSalud = DB::SELECT("SELECT STPS.descripcion AS descripcion,STPS.id FROM ECONOMIA.dbo.sisTIPOPERSONALSALUD STPS WHERE id <> 00");
      $sisEgresado = DB::SELECT("SELECT SE.descripcion AS descripcion,SE.id FROM ECONOMIA.dbo.sisEgresado SE");
      $sisEspecialidad = DB::SELECT("SELECT E.descripcion AS descripcion,E.id FROM ECONOMIA.dbo.sisESPECIALIDAD E WHERE id <> 00");

      return view('paginas.fuasAcervo',array("administradores"=>$administradores,"estados"=>$estados,"concPrestacional"=>$concPrestacional,
                                             "destinoAsegurado"=>$destinoAsegurado,"codPrestacional"=>$codPrestacional,
                                             "componente"=>$componente,"personalAtiende"=>$personalAtiende,"lugarAtencion"=>$lugarAtencion,
                                             "tipoAtencion"=>$tipoAtencion,"datosPersonalC"=>$datosPersonalC,
                                             "sisTipoPersonalSalud"=>$sisTipoPersonalSalud,"sisEgresado"=>$sisEgresado,
                                             "sisEspecialidad"=>$sisEspecialidad));
  }

    public function buscarPorMes(Request $request){

    $datos = array("fechaInicio_fuasAcervo"=>$request->input("fechaInicio_fuasAcervo"),
                   "fechaFin_fuasAcervo"=>$request->input("fechaFin_fuasAcervo"));

         /* Si existe un requerimiento del tipo AJAX */
         if(request()->ajax()){
              return datatables()->of(DB::SELECT("SELECT FU.IdAtencion,FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ FU.PrimerNombre +' '+ CASE WHEN FU.OtrosNombres IS NULL THEN '' ELSE FU.OtrosNombres END AS Paciente,
                                                 FU.HistoriaClinica,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraAtencion,FORMAT(CAST(FU.FechaHoraDigitacion AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraDigitacion,
                                                 FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                                                 FU.cie1_cod,digitarFua_estado,FU.IdAtencion as Fua_id,U.name,FU.nombrePaquete_tramas FROM ECONOMIA.dbo.FUA2 FU INNER JOIN [software_ufpa_general].[dbo].[FUA] F ON FU.IdAtencion = F.NroFua LEFT JOIN software_ufpa_general.dbo.users U ON F.IdUsuario_digitacion = U.id
                                                 LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id WHERE CAST(FU.FechaHoraAtencion AS DATE) 
                                                 BETWEEN :fechaInicial AND :fechaFinal AND digitarFua_estado = '1'",["fechaInicial"=>$request->input('fechaInicio_fuasAcervo'),
                                                                                                                    "fechaFinal"=>$request->input('fechaFin_fuasAcervo')]))->make(true);
         }
    }

    public function buscarPorHistoriaBD(Request $request){
        if(request()->ajax()){
              return datatables()->of(DB::SELECT("SELECT FU.IdAtencion,FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ FU.PrimerNombre +' '+ CASE WHEN FU.OtrosNombres IS NULL THEN '' ELSE FU.OtrosNombres END AS Paciente,
                                                  FU.HistoriaClinica,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy HH:mm:ss') as FechaHoraAtencion,FORMAT(CAST(FU.FechaHoraDigitacion AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraDigitacion,
                                                  FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy HH:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                                                  FU.cie1_cod,generarFua_estado,FU.IdAtencion as Fua_id,FU.digitarFua_estado,DATEDIFF(DAY,CAST(FU.FechaHoraRegistro AS DATETIME),GETDATE()) as DiasTranscurridos,U.name,FU.nombrePaquete_tramas FROM ECONOMIA.dbo.FUA2 FU 
                                                  INNER JOIN [software_ufpa_general].[dbo].[FUA] F ON FU.IdAtencion = F.NroFua LEFT JOIN software_ufpa_general.dbo.users U ON F.IdUsuario_digitacion = U.id
                                                  LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id 
                                                  WHERE FU.HistoriaClinica = :hClinica AND digitarFua_estado = '1'",["hClinica"=>$request->numHistoriaBD]))->make(true);
        }
   }

   public function buscarPorDocumentoBD(Request $request){
        if(request()->ajax()){
              return datatables()->of(DB::SELECT("SELECT FU.IdAtencion,FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ FU.PrimerNombre +' '+ CASE WHEN FU.OtrosNombres IS NULL THEN '' ELSE FU.OtrosNombres END AS Paciente,
                                                  FU.HistoriaClinica,FU.NroDocumentoIdentidad,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy HH:mm:ss') as FechaHoraAtencion,FORMAT(CAST(FU.FechaHoraDigitacion AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraDigitacion,
                                                  FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy HH:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                                                  FU.cie1_cod,generarFua_estado,FU.IdAtencion as Fua_id,FU.digitarFua_estado,DATEDIFF(DAY,CAST(FU.FechaHoraRegistro AS DATETIME),GETDATE()) as DiasTranscurridos,U.name,FU.nombrePaquete_tramas FROM ECONOMIA.dbo.FUA2 FU 
                                                  INNER JOIN [software_ufpa_general].[dbo].[FUA] F ON FU.IdAtencion = F.NroFua LEFT JOIN software_ufpa_general.dbo.users U ON F.IdUsuario_digitacion = U.id
                                                  LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id 
                                                  WHERE FU.NroDocumentoIdentidad = :nroDoc AND digitarFua_estado = '1'",["nroDoc"=>$request->numDocumentoBD]))->make(true);
        }
   }

   public function buscarPorFuaBD(Request $request){
        if(request()->ajax()){
              return datatables()->of(DB::SELECT("SELECT FU.IdAtencion,FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ FU.PrimerNombre +' '+ CASE WHEN FU.OtrosNombres IS NULL THEN '' ELSE FU.OtrosNombres END AS Paciente,
                                                  FU.HistoriaClinica,FU.NroDocumentoIdentidad,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy HH:mm:ss') as FechaHoraAtencion,FORMAT(CAST(FU.FechaHoraDigitacion AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraDigitacion,
                                                  FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy HH:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                                                  FU.cie1_cod,generarFua_estado,FU.IdAtencion as Fua_id,FU.digitarFua_estado,DATEDIFF(DAY,CAST(FU.FechaHoraRegistro AS DATETIME),GETDATE()) as DiasTranscurridos,U.name,FU.nombrePaquete_tramas FROM ECONOMIA.dbo.FUA2 FU 
                                                  INNER JOIN [software_ufpa_general].[dbo].[FUA] F ON FU.IdAtencion = F.NroFua LEFT JOIN software_ufpa_general.dbo.users U ON F.IdUsuario_digitacion = U.id
                                                  LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id 
                                                  WHERE CONVERT(int,FU.Numero) = :nroFua AND digitarFua_estado = '1'",["nroFua"=>$request->numFuaBD]))->make(true);
        }
   }
}
