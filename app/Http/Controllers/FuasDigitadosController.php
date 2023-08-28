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

class FuasDigitadosController extends Controller
{
    public function index(){

        /* Si existe un requerimiento del tipo AJAX */
      if(request()->ajax()){
        return datatables()->of(DB::SELECT("SELECT FU.IdAtencion,FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ FU.PrimerNombre +' '+ CASE WHEN FU.OtrosNombres IS NULL THEN '' ELSE FU.OtrosNombres END AS Paciente,
                                            FU.HistoriaClinica,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraAtencion,FORMAT(CAST(FU.FechaHoraDigitacion AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraDigitacion,
                                            FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                                            FU.cie1_cod,digitarFua_estado,FU.IdAtencion as Fua_id,U.name,FU.nombrePaquete_tramas FROM ECONOMIA.dbo.FUA2 FU INNER JOIN [software_ufpa_general].[dbo].[FUA] F ON FU.IdAtencion = F.NroFua LEFT JOIN software_ufpa_general.dbo.users U ON F.IdUsuario_digitacion = U.id
                                            LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id WHERE
                                            CAST(FU.FechaHoraRegistro AS DATE) = CONVERT(DATE,GETDATE()) AND generarFua_estado = '1'"))->make(true);
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

      return view('paginas.fuasDigitados',array("administradores"=>$administradores,"estados"=>$estados,"concPrestacional"=>$concPrestacional,
                                                "destinoAsegurado"=>$destinoAsegurado,"codPrestacional"=>$codPrestacional,
                                                "componente"=>$componente,"personalAtiende"=>$personalAtiende,"lugarAtencion"=>$lugarAtencion,
                                                "tipoAtencion"=>$tipoAtencion,"datosPersonalC"=>$datosPersonalC,
                                                "sisTipoPersonalSalud"=>$sisTipoPersonalSalud,"sisEgresado"=>$sisEgresado,
                                                "sisEspecialidad"=>$sisEspecialidad));
  }

  public function buscarPorMes(Request $request){

    $datos = array("fechaInicio_fuasDigitados"=>$request->input("fechaInicio_fuasDigitados"),
                   "fechaFin_fuasDigitados"=>$request->input("fechaFin_fuasDigitados"));

         /* Si existe un requerimiento del tipo AJAX */
         if(request()->ajax()){
              return datatables()->of(DB::SELECT("SELECT FU.IdAtencion,FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ FU.PrimerNombre +' '+ CASE WHEN FU.OtrosNombres IS NULL THEN '' ELSE FU.OtrosNombres END AS Paciente,
                                                 FU.HistoriaClinica,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraAtencion,FORMAT(CAST(FU.FechaHoraDigitacion AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraDigitacion,
                                                 FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                                                 FU.cie1_cod,digitarFua_estado,FU.IdAtencion as Fua_id,U.name,FU.nombrePaquete_tramas FROM ECONOMIA.dbo.FUA2 FU INNER JOIN [software_ufpa_general].[dbo].[FUA] F ON FU.IdAtencion = F.NroFua LEFT JOIN software_ufpa_general.dbo.users U ON F.IdUsuario_digitacion = U.id
                                                 LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id WHERE CAST(FU.FechaHoraAtencion AS DATE)
                                                 BETWEEN :fechaInicial AND :fechaFinal AND generarFua_estado = '1'",["fechaInicial"=>$request->input('fechaInicio_fuasDigitados'),
                                                                                                                    "fechaFinal"=>$request->input('fechaFin_fuasDigitados')]))->make(true);
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
                                                  WHERE FU.HistoriaClinica = :hClinica AND generarFua_estado = '1'",["hClinica"=>$request->numHistoriaBD]))->make(true);
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
                                                  WHERE FU.NroDocumentoIdentidad = :nroDoc AND generarFua_estado = '1'",["nroDoc"=>$request->numDocumentoBD]))->make(true);
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
                                                  WHERE CONVERT(int,FU.Numero) = :nroFua AND generarFua_estado = '1'",["nroFua"=>$request->numFuaBD]))->make(true);
        }
   }

   public function anularDigitacion(Request $request){
        if(request()->ajax()){

            DB::TABLE('ECONOMIA.DBO.FUA2')
            ->WHERE('ECONOMIA.DBO.FUA2.IdAtencion', $request->idFuaA_fuasDigitados)
            ->UPDATE(['ECONOMIA.DBO.FUA2.digitarFua_estado' => null,'ECONOMIA.DBO.FUA2.FechaHoraDigitacion' => null,'ECONOMIA.DBO.FUA2.nombrePaquete_tramas' => null]);

            return "anular-correcto";
        }
   }

    public function generadorTxt(Request $request){

        $ids = explode(",",$request->idFua_fuasDigitados);

        $separador = "|";
        $saltoLinea = "\n";

        $datosFua_056_presencial = DB::table('ECONOMIA.dbo.FUA2')->whereIn('IdAtencion', $ids)->where('CodigoPrestacional','=','056')->where('LugarAtencion','=','1')->get();
        $datosFua_056_noPresencial = DB::table('ECONOMIA.dbo.FUA2')->whereIn('IdAtencion', $ids)->where('CodigoPrestacional','=','056')->where('LugarAtencion','=','2')->get();

        $datosFuaArray_056_presencial = array();
        $datosFuaArray1_056_presencial = array();
        $atencionDatos_056_presencial = array();

        $datosFuaArray_056_noPresencial = array();
        $datosFuaArray1_056_noPresencial = array();
        $atencionDatos_056_noPresencial = array();

        /* 056 PRESENCIAL (INICIO)*/
        foreach($datosFua_056_presencial as $t){

            $t->TipoAtencion = 2;

            $fechaNacimientoC_056_presencial = date("d/m/Y", strtotime($t->FechaNacimiento));
            $fechaHoraAtencionC_056_presencial = date("d/m/Y H:i", strtotime($t->FechaHoraAtencion));
            $fechaHoraRegistroC_056_presencial = date("d/m/Y H:i");

            if($t->FechaIngreso != ''){
                $fechaIngresoC_056_presencial = date("d/m/Y", strtotime($t->FechaIngreso));
            }else{
                $fechaIngresoC_056_presencial = '';
            }

            if($t->FechaAlta != ''){
                $fechaAltaC_056_presencial = date("d/m/Y", strtotime($t->FechaAlta));
            }else{
                $fechaAltaC_056_presencial = '';
            }

            if($t->LugarAtencion == 2){
                $t->PersonaAtiende = 5;
            }

            if($t->TipoAtencion == 2 && $t->PersonaAtiende != 3){
                $IpressC_056_presencial = $t->IPRESSRefirio;
                $NroReferenciaC_056_presencial = $t->NroHojaReferencia;
            }else{
                $IpressC_056_presencial = '';
                $NroReferenciaC_056_presencial = '';
            }

            $datosFuaArray_056_presencial[] = $t->Numero;
            $datosFuaArray1_056_presencial[] = $t->IdAtencion;
            $atencionDatos_056_presencial[] = $t->IdAtencion . $separador . '350' . $separador . $t->Lote . $separador . $t->Numero . $separador . '022' . $separador . $t->IPRESS . $separador . '08' . $separador . '3' . $separador . '' . $separador . $t->EsReconsiderecion . $separador . $t->DISAReconsideracion . $separador . $t->LoteReconsideracion . $separador . $t->NumeroReconsideracion . $separador .
            $t->IdConvenio . $separador . $t->Componente . $separador . $t->DISAAsegurado . $separador . $t->LoteAsegurado . $separador . $t->NumeroAsegurado . $separador . $t->correlativo . $separador . $t->tablaContrato . $separador . $t->idFormatoContrato . $separador . $t->planAsegurado . $separador . $t->grupoPoblacional . $separador . $t->TipoDocumentoIdentidad . $separador .
            $t->NroDocumentoIdentidad . $separador . $t->ApellidoPaterno . $separador . $t->ApellidoMaterno . $separador . $t->PrimerNombre . $separador . $t->OtrosNombres . $separador . $fechaNacimientoC_056_presencial . $separador . $t->Sexo . $separador . $t->ubigeoAsegurado . $separador . $t->HistoriaClinica . $separador . '2' . $separador . $t->SaludMaterna . $separador . $t->ModalidadAtencion . $separador .
            '' . $separador . '' . $separador . $fechaHoraAtencionC_056_presencial . $separador . $IpressC_056_presencial . $separador . $NroReferenciaC_056_presencial . $separador . $t->CodigoPrestacional . $separador . $t->PersonaAtiende . $separador . $t->LugarAtencion . $separador . $t->DestinoAsegurado . $separador . $fechaIngresoC_056_presencial . $separador . $fechaAltaC_056_presencial . $separador . $t->IPRESSContrareferencia . $separador . $t->NroHojaContrareferencia . $separador .
            '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . $t->TipoDocResponsable .
            $separador . $t->NroDocResponsable . $separador . $t->TipoPersonalSalud . $separador . $t->Especialidad . $separador . $t->EsEgresado . $separador . $t->NroColegiatura . $separador . $t->NroRNE . $separador . date("Y") . $separador . date("m") . $separador . '1' . $separador . '70448533' . $separador . $fechaHoraRegistroC_056_presencial . $separador . '' . $separador . 'SOFT.UFPA' . $separador . '' . $saltoLinea;
        }/* 056 PRESENCIAL (FIN)*/

        /* 056 NO PRESENCIAL (INICIO)*/
        foreach($datosFua_056_noPresencial as $t){

            $t->TipoAtencion = 2;

            $fechaNacimientoC_056_noPresencial = date("d/m/Y", strtotime($t->FechaNacimiento));
            $fechaHoraAtencionC_056_noPresencial = date("d/m/Y H:i", strtotime($t->FechaHoraAtencion));
            $fechaHoraRegistroC_056_noPresencial = date("d/m/Y H:i");

            if($t->FechaIngreso != ''){
                $fechaIngresoC_056_noPresencial = date("d/m/Y", strtotime($t->FechaIngreso));
            }else{
                $fechaIngresoC_056_noPresencial = '';
            }

            if($t->FechaAlta != ''){
                $fechaAltaC_056_noPresencial = date("d/m/Y", strtotime($t->FechaAlta));
            }else{
                $fechaAltaC_056_noPresencial = '';
            }

            if($t->LugarAtencion == 2){
                $t->PersonaAtiende = 5;
            }

            if($t->TipoAtencion == 2 && $t->PersonaAtiende != 3){
                $IpressC_056_noPresencial = $t->IPRESSRefirio;
                $NroReferenciaC_056_noPresencial = $t->NroHojaReferencia;
            }else{
                $IpressC_056_noPresencial = '';
                $NroReferenciaC_056_noPresencial = '';
            }

            $datosFuaArray_056_noPresencial[] = $t->Numero;
            $datosFuaArray1_056_noPresencial[] = $t->IdAtencion;
            $atencionDatos_056_noPresencial[] = $t->IdAtencion . $separador . '350' . $separador . $t->Lote . $separador . $t->Numero . $separador . '022' . $separador . $t->IPRESS . $separador . '08' . $separador . '3' . $separador . '' . $separador . $t->EsReconsiderecion . $separador . $t->DISAReconsideracion . $separador . $t->LoteReconsideracion . $separador . $t->NumeroReconsideracion . $separador .
            $t->IdConvenio . $separador . $t->Componente . $separador . $t->DISAAsegurado . $separador . $t->LoteAsegurado . $separador . $t->NumeroAsegurado . $separador . $t->correlativo . $separador . $t->tablaContrato . $separador . $t->idFormatoContrato . $separador . $t->planAsegurado . $separador . $t->grupoPoblacional . $separador . $t->TipoDocumentoIdentidad . $separador .
            $t->NroDocumentoIdentidad . $separador . $t->ApellidoPaterno . $separador . $t->ApellidoMaterno . $separador . $t->PrimerNombre . $separador . $t->OtrosNombres . $separador . $fechaNacimientoC_056_noPresencial . $separador . $t->Sexo . $separador . $t->ubigeoAsegurado . $separador . $t->HistoriaClinica . $separador . '2' . $separador . $t->SaludMaterna . $separador . $t->ModalidadAtencion . $separador .
            '' . $separador . '' . $separador . $fechaHoraAtencionC_056_noPresencial . $separador . $IpressC_056_noPresencial . $separador . $NroReferenciaC_056_noPresencial . $separador . $t->CodigoPrestacional . $separador . $t->PersonaAtiende . $separador . $t->LugarAtencion . $separador . $t->DestinoAsegurado . $separador . $fechaIngresoC_056_noPresencial . $separador . $fechaAltaC_056_noPresencial . $separador . $t->IPRESSContrareferencia . $separador . $t->NroHojaContrareferencia . $separador .
            '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . $t->TipoDocResponsable .
            $separador . $t->NroDocResponsable . $separador . $t->TipoPersonalSalud . $separador . $t->Especialidad . $separador . $t->EsEgresado . $separador . $t->NroColegiatura . $separador . $t->NroRNE . $separador . date("Y") . $separador . date("m") . $separador . '1' . $separador . '70448533' . $separador . $fechaHoraRegistroC_056_noPresencial . $separador . '' . $separador . 'SOFT.UFPA' . $separador . '' . $saltoLinea;
        }/* 056 NO PRESENCIAL (FIN)*/

        $atencionDatos_completo = array_merge($atencionDatos_056_noPresencial,$atencionDatos_056_presencial);
        $idAtencionDatos_completo = array_merge($datosFuaArray1_056_noPresencial,$datosFuaArray1_056_presencial);

        //INICIO VALIDACION DE MEDICAMENTOS PARA DIAGNOSTICO Z10
        $datosFarmacia = DB::TABLE('INR.dbo.DocFCabecera')
                        ->JOIN('INR.dbo.DocFDetalles', 'INR.dbo.DocFCabecera.docf_trans', '=', 'INR.dbo.DocFDetalles.docf_trans')
                        ->JOIN('INR.dbo.Catalogo', 'INR.dbo.Catalogo.catalogo_cod', '=', 'INR.dbo.DocFDetalles.catalogo_cod')
                        ->JOIN('ECONOMIA.DBO.FUA2','INR.dbo.DocFCabecera.fua_nro','=','ECONOMIA.DBO.FUA2.Numero')
                        ->WHEREIN('INR.dbo.DocFCabecera.fua_nro', $datosFuaArray_056_presencial)
                        ->WHERE('INR.dbo.DocFCabecera.tdoc_cod', '=', 204)
                        ->WHERE('INR.dbo.DocFCabecera.docf_flag','=','O')
                        ->WHERE('INR.dbo.Catalogo.catalogo_far_clase_cod','=','A')
                        ->GET();

        $datosFarmacia_noPresencial = DB::TABLE('INR.dbo.DocFCabecera')
                        ->JOIN('INR.dbo.DocFDetalles', 'INR.dbo.DocFCabecera.docf_trans', '=', 'INR.dbo.DocFDetalles.docf_trans')
                        ->JOIN('INR.dbo.Catalogo', 'INR.dbo.Catalogo.catalogo_cod', '=', 'INR.dbo.DocFDetalles.catalogo_cod')
                        ->JOIN('ECONOMIA.DBO.FUA2','INR.dbo.DocFCabecera.fua_nro','=','ECONOMIA.DBO.FUA2.Numero')
                        ->WHEREIN('INR.dbo.DocFCabecera.fua_nro', $datosFuaArray_056_noPresencial)
                        ->WHERE('INR.dbo.DocFCabecera.tdoc_cod', '=', 204)
                        ->WHERE('INR.dbo.DocFCabecera.docf_flag','=','O')
                        ->WHERE('INR.dbo.Catalogo.catalogo_far_clase_cod','=','A')
                        ->GET();

        $medicamentosDatosP = array();
        foreach($datosFarmacia as $t){
            $medicamentosDatosP[] = $t->IdAtencion;
        }

        $sinMedicamentosD = array_unique($medicamentosDatosP);

        $arraySinMedicamentosD =  array_diff(array_merge($datosFuaArray1_056_presencial, $sinMedicamentosD),array_intersect($datosFuaArray1_056_presencial, $sinMedicamentosD));

        //INICIO VALIDACION DE INSUMOS PARA DIAGNOSTICO Z10
        $datosFarmaciaInsumos = DB::TABLE('INR.dbo.DocFCabecera')
                                ->JOIN('INR.dbo.DocFDetalles', 'INR.dbo.DocFCabecera.docf_trans', '=', 'INR.dbo.DocFDetalles.docf_trans')
                                ->JOIN('INR.dbo.Catalogo', 'INR.dbo.Catalogo.catalogo_cod', '=', 'INR.dbo.DocFDetalles.catalogo_cod')
                                ->JOIN('ECONOMIA.DBO.FUA2','INR.dbo.DocFCabecera.fua_nro','=','ECONOMIA.DBO.FUA2.Numero')
                                ->WHEREIN('INR.dbo.DocFCabecera.fua_nro', $datosFuaArray_056_presencial)
                                ->WHERE('INR.dbo.DocFCabecera.tdoc_cod', '=', 204)
                                ->WHERE('INR.dbo.DocFCabecera.docf_flag','=','O')
                                ->WHERE('INR.dbo.Catalogo.catalogo_far_clase_cod','=','F')
                                ->GET();

        $datosFarmaciaInsumos_noPresencial = DB::TABLE('INR.dbo.DocFCabecera')
                                ->JOIN('INR.dbo.DocFDetalles', 'INR.dbo.DocFCabecera.docf_trans', '=', 'INR.dbo.DocFDetalles.docf_trans')
                                ->JOIN('INR.dbo.Catalogo', 'INR.dbo.Catalogo.catalogo_cod', '=', 'INR.dbo.DocFDetalles.catalogo_cod')
                                ->JOIN('ECONOMIA.DBO.FUA2','INR.dbo.DocFCabecera.fua_nro','=','ECONOMIA.DBO.FUA2.Numero')
                                ->WHEREIN('INR.dbo.DocFCabecera.fua_nro', $datosFuaArray_056_noPresencial)
                                ->WHERE('INR.dbo.DocFCabecera.tdoc_cod', '=', 204)
                                ->WHERE('INR.dbo.DocFCabecera.docf_flag','=','O')
                                ->WHERE('INR.dbo.Catalogo.catalogo_far_clase_cod','=','F')
                                ->GET();

        $insumosDatosP = array();
        foreach($datosFarmaciaInsumos as $t){
            $insumosDatosP[] = $t->IdAtencion;
        }

        $sinInsumosD = array_unique($insumosDatosP);

        $arraySinInsumosD =  array_diff(array_merge($datosFuaArray1_056_presencial, $sinInsumosD),array_intersect($datosFuaArray1_056_presencial, $sinInsumosD));

        // INICIO VALIDACION DE PROCEDIMIENTOS PARA DIAGNOSTICO Z10
        $datosProcedimientos = DB::TABLE('INRDIS_II.dbo.PDiarioDetalles')
                                   ->SELECT(['ECONOMIA.dbo.FUA2.IdAtencion','INRDIS_II.dbo.tbCPMS.codigoCPMS','INRDIS_II.dbo.tbCPT.codigoCPT','INRDIS_II.dbo.tbCPT.descripcion',DB::raw('1 AS ind'),DB::raw('1 AS eje'),DB::raw('1 AS dx')])
                                   ->JOIN('INRDIS_II.dbo.ActividadEspecifica','INRDIS_II.dbo.PDiarioDetalles.actividadEspecifica_id','=','INRDIS_II.dbo.ActividadEspecifica.id')
                                   ->JOIN('ECONOMIA.dbo.FUA2','INRDIS_II.dbo.PDiarioDetalles.FUA_id','=','ECONOMIA.dbo.FUA2.IdAtencion')
                                   ->JOIN('INRDIS_II.dbo.tbCPT','INRDIS_II.dbo.ActividadEspecifica.CPT','=',DB::raw('INRDIS_II.dbo.tbCPT.codigoCPT COLLATE Modern_Spanish_CI_AS'))
                                   ->JOIN('INRDIS_II.dbo.tbCPMS','INRDIS_II.dbo.ActividadEspecifica.tbCPMS_id','=','INRDIS_II.dbo.tbCPMS.id')
                                   ->WHEREIN('ECONOMIA.dbo.FUA2.numero',$datosFuaArray_056_presencial)
                                   ->GET();

        $procedimientosDatosP = array();
        foreach($datosProcedimientos as $t){
            $procedimientosDatosP[] = $t->IdAtencion;
        }

        $sinProcedimientosD = array_unique($procedimientosDatosP);

        $arraySinProcedimientosD =  array_diff(array_merge($datosFuaArray1_056_presencial, $sinProcedimientosD),array_intersect($datosFuaArray1_056_presencial, $sinProcedimientosD));
        $cruceArray = array_intersect($arraySinMedicamentosD, $arraySinInsumosD, $arraySinProcedimientosD);

        DB::TABLE('INRDIS_II.dbo.CitasHora_Paciente')
        ->WHEREIN('INRDIS_II.dbo.CitasHora_Paciente.FUA_id', $cruceArray)
        ->UPDATE(['INRDIS_II.dbo.CitasHora_Paciente.diag_cod_danoD' => 'D','INRDIS_II.dbo.CitasHora_Paciente.cie_cod_danoD' => 'Z10.8']);
        // FIN VALIDACION DE PROCEDIMIENTOS PARA DIAGNOSTICO Z10

        $datosDiagnostico_056_presencial = DB::TABLE('INRDIS_II.dbo.CitasHora_Paciente')
                            ->WHEREIN('INRDIS_II.dbo.CitasHora_Paciente.FUA_id', $datosFuaArray1_056_presencial)
                            ->GET();

        $datosDiagnostico_056_noPresencial = DB::TABLE('INRDIS_II.dbo.CitasHora_Paciente')
                            ->WHEREIN('INRDIS_II.dbo.CitasHora_Paciente.FUA_id', $datosFuaArray1_056_noPresencial)
                            ->GET();

        $diagnosticosDatos_056_presencial = array();
        $diagnosticosDatos_056_noPresencial = array();

        /*DIAGNOSTICO 056 PRESENCIAL (INICIO)*/
        foreach($datosDiagnostico_056_presencial as $t){

            if($t->diag_cod_dano1 == 'D'){
                $diag_cod_dano1_056_presencial = 1;
            }elseif($t->diag_cod_dano1 == 'P'){
                $diag_cod_dano1_056_presencial = 2;
            }elseif($t->diag_cod_dano1 == 'R'){
                $diag_cod_dano1_056_presencial = 4;
            }else{
                $diag_cod_dano1_056_presencial = '';
            }

            if($t->diag_cod_dano2 == 'D'){
                $diag_cod_dano2_056_presencial = 1;
            }elseif($t->diag_cod_dano2 == 'P'){
                $diag_cod_dano2_056_presencial = 2;
            }elseif($t->diag_cod_dano2 == 'R'){
                $diag_cod_dano2_056_presencial = 4;
            }else{
                $diag_cod_dano2_056_presencial = '';
            }

            if($t->diag_cod_dano3 == 'D'){
                $diag_cod_dano3_056_presencial = 1;
            }elseif($t->diag_cod_dano3 == 'P'){
                $diag_cod_dano3_056_presencial = 2;
            }elseif($t->diag_cod_dano3 == 'R'){
                $diag_cod_dano3_056_presencial = 4;
            }else{
                $diag_cod_dano3_056_presencial = '';
            }

            if($t->diag_cod_dano4 == 'D'){
                $diag_cod_dano4_056_presencial = 1;
            }elseif($t->diag_cod_dano4 == 'P'){
                $diag_cod_dano4_056_presencial = 2;
            }elseif($t->diag_cod_dano4 == 'R'){
                $diag_cod_dano4_056_presencial = 4;
            }else{
                $diag_cod_dano4_056_presencial = '';
            }

            if($t->diag_cod_danoD == 'D'){
                $diag_cod_danoD_056_presencial = 1;
            }elseif($t->diag_cod_danoD == 'P'){
                $diag_cod_danoD_056_presencial = 2;
            }elseif($t->diag_cod_danoD == 'R'){
                $diag_cod_danoD_056_presencial = 4;
            }else{
                $diag_cod_danoD_056_presencial = '';
            }

            if($t->diag_cod_dano1 != ''){
                $cie_cod_dano1_056_presencial = str_replace(".","",$t->cie_cod_dano1);
                $valorInicial1_056_presencial = $t->FUA_id . $separador . substr($cie_cod_dano1_056_presencial,0,4) . $separador . '1' . $separador . 'I' . $separador . $diag_cod_dano1_056_presencial . $saltoLinea;
                $valorCon1_056_presencial = 1;
            }else{
                $valorInicial1_056_presencial = '';
                $valorCon1_056_presencial = 0;
            }

            if($t->diag_cod_dano2 != ""){
                $cie_cod_dano2_056_presencial = str_replace(".","",$t->cie_cod_dano2);
                $valorInicial2_056_presencial = $t->FUA_id . $separador . substr($cie_cod_dano2_056_presencial,0,4) . $separador . '2' . $separador . 'I' . $separador . $diag_cod_dano2_056_presencial . $saltoLinea;
                $valorCon2_056_presencial = 1;
            }else{
                $valorInicial2_056_presencial = '';
                $valorCon2_056_presencial = 0;
            }

            if($t->diag_cod_dano3 != ''){
                $cie_cod_dano3_056_presencial = str_replace(".","",$t->cie_cod_dano3);
                $valorInicial3_056_presencial = $t->FUA_id . $separador . substr($cie_cod_dano3_056_presencial,0,4) . $separador . '3' . $separador . 'I' . $separador . $diag_cod_dano3_056_presencial . $saltoLinea;
                $valorCon3_056_presencial = 1;
            }else{
                $valorInicial3_056_presencial = '';
                $valorCon3_056_presencial = 0;
            }

            if($t->diag_cod_dano4 != ''){
                $cie_cod_dano4_056_presencial = str_replace(".","",$t->cie_cod_dano4);
                $valorInicial4_056_presencial = $t->FUA_id . $separador . substr($cie_cod_dano4_056_presencial,0,4) . $separador . '4' . $separador . 'I' . $separador . $diag_cod_dano4_056_presencial . $saltoLinea;
                $valorCon4_056_presencial = 1;
            }else{
                $valorInicial4_056_presencial = '';
                $valorCon4_056_presencial = 0;
            }

            if($t->diag_cod_danoD != ''){
                $cie_cod_danoD_056_presencial = str_replace(".","",$t->cie_cod_danoD);
                $valorInicialD_056_presencial = $t->FUA_id . $separador . substr($cie_cod_danoD_056_presencial,0,4) . $separador . '5' . $separador . 'I' . $separador . $diag_cod_danoD_056_presencial . $saltoLinea;
                $valorConD_056_presencial = 1;
            }else{
                $valorInicialD_056_presencial = '';
                $valorConD_056_presencial = 0;
            }

            $diagnosticosDatos_056_presencial[] = $valorInicial1_056_presencial.
                                                  $valorInicial2_056_presencial.
                                                  $valorInicial3_056_presencial.
                                                  $valorInicial4_056_presencial.
                                                  $valorInicialD_056_presencial;

            $diagnos_056_presencial[] = $valorCon1_056_presencial + $valorCon2_056_presencial + $valorCon3_056_presencial + $valorCon4_056_presencial + $valorConD_056_presencial;
        }/* DIAGNOSTICO 056 PRESENCIAL (FIN) */

        /*DIAGNOSTICO 056 NO PRESENCIAL (INICIO)*/
        foreach($datosDiagnostico_056_noPresencial as $t){

            if($t->diag_cod_dano1 == 'D'){
                $diag_cod_dano1_056_noPresencial = 1;
            }elseif($t->diag_cod_dano1 == 'P'){
                $diag_cod_dano1_056_noPresencial = 2;
            }elseif($t->diag_cod_dano1 == 'R'){
                $diag_cod_dano1_056_noPresencial = 4;
            }else{
                $diag_cod_dano1_056_noPresencial = '';
            }

            if($t->diag_cod_dano2 == 'D'){
                $diag_cod_dano2_056_noPresencial = 1;
            }elseif($t->diag_cod_dano2 == 'P'){
                $diag_cod_dano2_056_noPresencial = 2;
            }elseif($t->diag_cod_dano2 == 'R'){
                $diag_cod_dano2_056_noPresencial = 4;
            }else{
                $diag_cod_dano2_056_noPresencial = '';
            }

            if($t->diag_cod_dano3 == 'D'){
                $diag_cod_dano3_056_noPresencial = 1;
            }elseif($t->diag_cod_dano3 == 'P'){
                $diag_cod_dano3_056_noPresencial = 2;
            }elseif($t->diag_cod_dano3 == 'R'){
                $diag_cod_dano3_056_noPresencial = 4;
            }else{
                $diag_cod_dano3_056_noPresencial = '';
            }

            if($t->diag_cod_dano4 == 'D'){
                $diag_cod_dano4_056_noPresencial = 1;
            }elseif($t->diag_cod_dano4 == 'P'){
                $diag_cod_dano4_056_noPresencial = 2;
            }elseif($t->diag_cod_dano4 == 'R'){
                $diag_cod_dano4_056_noPresencial = 4;
            }else{
                $diag_cod_dano4_056_noPresencial = '';
            }

            if($t->diag_cod_danoD == 'D'){
                $diag_cod_danoD_056_noPresencial = 1;
            }elseif($t->diag_cod_danoD == 'P'){
                $diag_cod_danoD_056_noPresencial = 2;
            }elseif($t->diag_cod_danoD == 'R'){
                $diag_cod_danoD_056_noPresencial = 4;
            }else{
                $diag_cod_danoD_056_noPresencial = '';
            }

            if($t->diag_cod_dano1 != ''){
                $cie_cod_dano1_056_noPresencial = str_replace(".","",$t->cie_cod_dano1);
                $valorInicial1_056_noPresencial = $t->FUA_id . $separador . substr($cie_cod_dano1_056_noPresencial,0,4) . $separador . '1' . $separador . 'I' . $separador . $diag_cod_dano1_056_noPresencial . $saltoLinea;
                $valorCon1_056_noPresencial = 1;
            }else{
                $valorInicial1_056_noPresencial = '';
                $valorCon1_056_noPresencial = 0;
            }

            if($t->diag_cod_dano2 != ""){
                $cie_cod_dano2_056_noPresencial = str_replace(".","",$t->cie_cod_dano2);
                $valorInicial2_056_noPresencial = $t->FUA_id . $separador . substr($cie_cod_dano2_056_noPresencial,0,4) . $separador . '2' . $separador . 'I' . $separador . $diag_cod_dano2_056_noPresencial . $saltoLinea;
                $valorCon2_056_noPresencial = 1;
            }else{
                $valorInicial2_056_noPresencial = '';
                $valorCon2_056_noPresencial = 0;
            }

            if($t->diag_cod_dano3 != ''){
                $cie_cod_dano3_056_noPresencial = str_replace(".","",$t->cie_cod_dano3);
                $valorInicial3_056_noPresencial = $t->FUA_id . $separador . substr($cie_cod_dano3_056_noPresencial,0,4) . $separador . '3' . $separador . 'I' . $separador . $diag_cod_dano3_056_noPresencial . $saltoLinea;
                $valorCon3_056_noPresencial = 1;
            }else{
                $valorInicial3_056_noPresencial = '';
                $valorCon3_056_noPresencial = 0;
            }

            if($t->diag_cod_dano4 != ''){
                $cie_cod_dano4_056_noPresencial = str_replace(".","",$t->cie_cod_dano4);
                $valorInicial4_056_noPresencial = $t->FUA_id . $separador . substr($cie_cod_dano4_056_noPresencial,0,4) . $separador . '4' . $separador . 'I' . $separador . $diag_cod_dano4_056_noPresencial . $saltoLinea;
                $valorCon4_056_noPresencial = 1;
            }else{
                $valorInicial4_056_noPresencial = '';
                $valorCon4_056_noPresencial = 0;
            }

            if($t->diag_cod_danoD != ''){
                $cie_cod_danoD_056_noPresencial = str_replace(".","",$t->cie_cod_danoD);
                $valorInicialD_056_noPresencial = $t->FUA_id . $separador . substr($cie_cod_danoD_056_noPresencial,0,4) . $separador . '5' . $separador . 'I' . $separador . $diag_cod_danoD_056_noPresencial . $saltoLinea;
                $valorConD_056_noPresencial = 1;
            }else{
                $valorInicialD_056_noPresencial = '';
                $valorConD_056_noPresencial = 0;
            }

            $diagnosticosDatos_056_noPresencial[] = $valorInicial1_056_noPresencial.
                                                    $valorInicial2_056_noPresencial.
                                                    $valorInicial3_056_noPresencial.
                                                    $valorInicial4_056_noPresencial.
                                                    $valorInicialD_056_noPresencial;

            $diagnos_056_noPresencial[] = $valorCon1_056_noPresencial + $valorCon2_056_noPresencial + $valorCon3_056_noPresencial + $valorCon4_056_noPresencial + $valorConD_056_noPresencial;
        }/* DIAGNOSTICO 056 NO PRESENCIAL (FIN) */

        if(empty($diagnos_056_presencial)){
            $suma_diagnos_presencial[] = 0;
        }else{
            $suma_diagnos_presencial = $diagnos_056_presencial;
        }

        if(empty($diagnos_056_noPresencial)){
            $suma_diagnos_noPresencial[] = 0;
        }else{
            $suma_diagnos_noPresencial = $diagnos_056_noPresencial;
        }

        $diagnosticosDatos_completo = array_merge($diagnosticosDatos_056_presencial, $diagnosticosDatos_056_noPresencial);
        $diagnosticosDatos_union = array_merge($suma_diagnos_presencial, $suma_diagnos_noPresencial);

        //recorrer datos de los medicamentos
        $medicamentosDatos = array();
		foreach($datosFarmacia as $t){
			$medicamentosDatos[] = $t->IdAtencion . $separador . $t->catalogo_sismed . $separador . /* $t->diagnostico_estado */'1' . $separador . $t->docf_item_cant . $separador . $t->docf_item_cant . $separador . '' . $separador . '' . $saltoLinea;
		}

        $medicamentosDatos_noPresencial = array();
		foreach($datosFarmacia_noPresencial as $t){
			$medicamentosDatos_noPresencial[] = $t->IdAtencion . $separador . $t->catalogo_sismed . $separador . /* $t->diagnostico_estado */'1' . $separador . $t->docf_item_cant . $separador . $t->docf_item_cant . $separador . '' . $separador . '' . $saltoLinea;
		}

        $medicamentosDatos_completo = array_merge($medicamentosDatos, $medicamentosDatos_noPresencial);

        $medicamentosDatosInsumos = array();
		foreach($datosFarmaciaInsumos as $t){
			$medicamentosDatosInsumos[] = $t->IdAtencion . $separador . $t->catalogo_sismed . $separador . /* $t->diagnostico_estado */'1' . $separador . $t->docf_item_cant . $separador . $t->docf_item_cant . $saltoLinea;
		}

        $medicamentosDatosInsumos_noPresencial = array();
		foreach($datosFarmaciaInsumos_noPresencial as $t){
			$medicamentosDatosInsumos_noPresencial[] = $t->IdAtencion . $separador . $t->catalogo_sismed . $separador . /* $t->diagnostico_estado */'1' . $separador . $t->docf_item_cant . $separador . $t->docf_item_cant . $saltoLinea;
		}

        $medicamentosDatosInsumos_completo = array_merge($medicamentosDatosInsumos, $medicamentosDatosInsumos_noPresencial);
        //fin de medicamentos

        //recorrer los procedimientos 056 presenciales
        $procedimientosDatos = array();
        foreach($datosProcedimientos as $p056){
            $procedimientosDatos[] = $p056->IdAtencion . $separador . '97597' . $separador . '15880' . $separador . $p056->dx . $separador . $p056->ind . $separador . $p056->eje . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $saltoLinea;
        }

        if(!empty($datosFuaArray1_056_noPresencial)){
            foreach($datosFuaArray1_056_noPresencial as $p056){
                $procedimientosDatos_noPresencial[] =  $p056 . $separador . '9949908' . $separador . '99499.08' . $separador . '1' . $separador . '1' . $separador . '1' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $separador . '' . $saltoLinea;
            }
        }else{
            $procedimientosDatos_noPresencial = [];
        }

        $procedimientosDatos_completo = array_merge($procedimientosDatos, $procedimientosDatos_noPresencial);
        //fin de procedimientos 056 presenciales

        $vacio = "";

        /* CON ESTO VALIDAMOS QUE SE AGREGUE UNA UNIDAD AL ID FINAL DEL COMPRIMIDO */
        $idUltimoComprimido = DB::SELECT("SELECT TOP 1 CorrelativoComprimidoZip,NombreComprimidoZip FROM software_ufpa_general.dbo.ComprimidoZip ORDER BY IdComprimidoZip DESC");
        $nuevoValorComprimido = $idUltimoComprimido[0]->CorrelativoComprimidoZip + 1;
        $valorFinalComprimido = str_pad($nuevoValorComprimido, 5, "0", STR_PAD_LEFT);

        $resumenDatos = date("Y") . $saltoLinea . date("m") . $saltoLinea . $valorFinalComprimido . $saltoLinea . '00007734' . date("Y") . date("m") . $valorFinalComprimido . '.ZIP' . $saltoLinea . '0230' . $saltoLinea . count($atencionDatos_completo) . $saltoLinea . '0' . $saltoLinea . array_sum($diagnosticosDatos_union) . $saltoLinea . count($medicamentosDatos_completo) . $saltoLinea . count($medicamentosDatosInsumos_completo) . $saltoLinea . count($procedimientosDatos_completo) . $saltoLinea . '0' . $saltoLinea . '0' . $saltoLinea . 'SOFT.UFPA' . $saltoLinea . 'V1.0' . $saltoLinea . 'V1.0' . $saltoLinea . '1' . $saltoLinea . '15297725' . $saltoLinea;

        // AGREGAMOS EL NOMBRE DE LOS ARCHIVOS
        $filename1 = 'ATENCION.TXT';
        $filename2 = 'ATENCIONDIA.TXT';
        $filename3 = 'ATENCIONINS.TXT';
        $filename4 = 'ATENCIONMED.TXT';
        $filename5 = 'ATENCIONPRO.TXT';
        $filename6 = 'ATENCIONSMI.TXT';
        $filename7 = 'ATENCIONSER.TXT';
        $filename8 = 'ATENCIONRN.TXT';
        $filename9 = 'RESUMEN.TXT';

        //GUARDAMOS LOS ARCHIVOS EN EL STORAGE
        Storage::disk('tramasFua')->put($filename1, $atencionDatos_completo);
        Storage::disk('tramasFua')->put($filename2, $diagnosticosDatos_completo);
        Storage::disk('tramasFua')->put($filename3, $medicamentosDatosInsumos_completo);
        Storage::disk('tramasFua')->put($filename4, $medicamentosDatos_completo);
        Storage::disk('tramasFua')->put($filename5, $procedimientosDatos_completo);
        Storage::disk('tramasFua')->put($filename6, $vacio);
        Storage::disk('tramasFua')->put($filename7, $vacio);
        Storage::disk('tramasFua')->put($filename8, $vacio);
        Storage::disk('tramasFua')->put($filename9, $resumenDatos);

      //COMPRIMIR ARCHIVOS EN ZIP

        /*$obtenerFechaMayor = DB::table('ECONOMIA.dbo.FUA2')->select('FechaHoraAtencion')
                        ->whereIn('IdAtencion', $ids)->where('CodigoPrestacional','=','056')
                        ->take(1)->orderBy('FechaHoraAtencion', 'DESC')
                        ->get();

        $anioFechaMayor = date("Y", strtotime($obtenerFechaMayor[0]->FechaHoraAtencion));
        $mesFechaMayor = date("m", strtotime($obtenerFechaMayor[0]->FechaHoraAtencion));*/

        $zip =  new ZipArchive;
        //$fileName = '00007734'.$anioFechaMayor.$mesFechaMayor.$valorFinalComprimido.'.ZIP';
        $fileName = '00007734'.date("Y").date("m").$valorFinalComprimido.'.ZIP';

        if($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
            {
                $files = File::files(public_path('../storage/app/public/tramasFua'));
                foreach($files as $key => $value){
                    $file = basename($value);
                    $zip->addFile($value,$file);
                }

                $zip->setEncryptionName($filename1, ZipArchive::EM_TRAD_PKWARE, '8885FuaE16010');/* 8885FuaE16010 *//* 8885FuaE16010 */
                $zip->setEncryptionName($filename2, ZipArchive::EM_TRAD_PKWARE, '8885FuaE16010');
                $zip->setEncryptionName($filename3, ZipArchive::EM_TRAD_PKWARE, '8885FuaE16010');
                $zip->setEncryptionName($filename4, ZipArchive::EM_TRAD_PKWARE, '8885FuaE16010');
                $zip->setEncryptionName($filename5, ZipArchive::EM_TRAD_PKWARE, '8885FuaE16010');
                $zip->setEncryptionName($filename6, ZipArchive::EM_TRAD_PKWARE, '8885FuaE16010');
                $zip->setEncryptionName($filename7, ZipArchive::EM_TRAD_PKWARE, '8885FuaE16010');
                $zip->setEncryptionName($filename8, ZipArchive::EM_TRAD_PKWARE, '8885FuaE16010');
                $zip->setEncryptionName($filename9, ZipArchive::EM_TRAD_PKWARE, '8885FuaE16010');
                $zip->close();

                $s = file_get_contents($fileName);
                $s = base64_encode($s);
            }

            $comprimidoZip = new ComprimidoZipModel();
            $comprimidoZip->CorrelativoComprimidoZip = $valorFinalComprimido;
            $comprimidoZip->NombreComprimidoZip = $fileName;

        $url = "https://ws01.sis.gob.pe/cxf/esb/negocio/registroFuaBatch/v2/?wsdl";/* https://ws01.sis.gob.pe/cxf/esb/negocio/registroFuaBatch/v2/?wsdl *//* https://pruebaws01.sis.gob.pe/cxf/esb/negocio/registroFuaBatch/v2/?wsdl */ /* wKjGSzhw */
        $soap_request = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:v2=\"http://sis.gob.pe/esb/negocio/registroFuaBatch/v2/\">
        <soapenv:Header>
           <v2:requestHeader>
              <v2:canal>SOFT.UFPA</v2:canal>
              <v2:usuario>INR</v2:usuario>
              <v2:autorizacion>wKjGSzhw</v2:autorizacion>
           </v2:requestHeader>
        </soapenv:Header>
        <soapenv:Body>
           <v2:registrarFuaRequest>
              <v2:nombreZip>$fileName</v2:nombreZip>
              <v2:dataZip>$s</v2:dataZip>
           </v2:registrarFuaRequest>
        </soapenv:Body>
     </soapenv:Envelope>";

        $header = array(
            "Method: POST",
            "Accept-Encoding: gzip,deflate",
            "Content-Type: text/xml;charset=UTF-8",
            "SOAPAction: \"http://sis.gob.pe/esb/negocio/registroFuaBatch/v2/registrarFuaPublico\"",
            "Content-length: ".strlen($soap_request),
            "Connection: Keep-Alive",
            "User-Agent: Apache-HttpClient/4.5.5 (Java/12.0.1)"
        );

        /* return $header; */

        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_URL,            $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_POST,           true);
        curl_setopt($handle, CURLOPT_POSTFIELDS,     $soap_request);
        curl_setopt($handle, CURLOPT_HTTPHEADER,     $header);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_HEADER, false);
        curl_setopt($handle, CURLOPT_NOBODY, 0);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($handle);
        curl_close($handle);

        $response1_get = str_replace("<soap:Envelope xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\"><soap:Body>","",$response);
        $response2_get = str_replace("</soap:Body></soap:Envelope>","",$response1_get);

        $carga_xml = simplexml_load_string($response2_get);

        if($carga_xml->codigo != 0){
            return array("ERROR-CODIFICACION", $carga_xml->respuesta);
        }else{
            $fechaHoraDigitacion = date("Y-m-d") . 'T' . date("H:i:s.v");
            $idUsuario_digitacion = auth()->user()->id;

            DB::TABLE('ECONOMIA.DBO.FUA2')
            ->WHEREIN('ECONOMIA.DBO.FUA2.IdAtencion', $idAtencionDatos_completo)
            ->UPDATE(['ECONOMIA.DBO.FUA2.digitarFua_estado' => '1','ECONOMIA.DBO.FUA2.FechaHoraDigitacion' => $fechaHoraDigitacion,'ECONOMIA.DBO.FUA2.nombrePaquete_tramas' => $fileName]);

            DB::TABLE('SOFTWARE_UFPA_GENERAL.DBO.FUA')
            ->WHEREIN('SOFTWARE_UFPA_GENERAL.DBO.FUA.NroFua', $idAtencionDatos_completo)
            ->UPDATE(['SOFTWARE_UFPA_GENERAL.DBO.FUA.IdUsuario_digitacion' => $idUsuario_digitacion]);

            $comprimidoZip->save();

            return array("PAQUETE-GENERADO-SATISFACTORIAMENTE", $carga_xml->paqueteNombre, $carga_xml->paqueteId);
        }
    }
}
