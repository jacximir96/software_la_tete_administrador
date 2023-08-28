<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdministradoresModel;
use App\FuaPrincipalModel;
use App\PacientesCitadosModel;
use App\FuaModel;
use App\FuaNModel;
use App\Fua1ActualizacionModel;
use App\Fua2ActualizacionModel;
use App\Fua3ActualizacionModel;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class FuasEmitidosGController extends Controller
{
    public function index(){

        $usuario_fuasEmitidosG = auth()->id();

        /* Si existe un requerimiento del tipo AJAX */
        if(request()->ajax()){
                return datatables()->of(DB::SELECT("SELECT FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ FU.PrimerNombre +' '+ CASE WHEN FU.OtrosNombres IS NULL THEN '' ELSE FU.OtrosNombres END AS Paciente,
                FU.HistoriaClinica,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy HH:mm:ss') as FechaHoraAtencion,
                FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy HH:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                FU.cie1_cod,generarFua_estado,FU.IdAtencion as Fua_id,FU.generarFua_estado,DATEDIFF(DAY,CAST(FU.FechaHoraRegistro AS DATETIME),GETDATE()) as DiasTranscurridos,U.name FROM ECONOMIA.dbo.FUA2 FU 
                INNER JOIN [software_ufpa_general].[dbo].[FUA] F ON FU.IdAtencion = F.NroFua INNER JOIN [software_ufpa_general].[dbo].[users] U ON F.IdUsuario = U.id
                LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id WHERE 
                CAST(FU.FechaHoraRegistro AS DATE) = CONVERT(DATE,GETDATE()) AND (FU.generarFua_estado = '1' OR FU.generarFua_estado = '0')"))->make(true);
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

        return view('paginas.fuasEmitidosG',  array("administradores"=>$administradores,"estados"=>$estados,"concPrestacional"=>$concPrestacional,
                                                    "destinoAsegurado"=>$destinoAsegurado,"codPrestacional"=>$codPrestacional,
                                                    "componente"=>$componente,"personalAtiende"=>$personalAtiende,"lugarAtencion"=>$lugarAtencion,
                                                    "tipoAtencion"=>$tipoAtencion,"datosPersonalC"=>$datosPersonalC,
                                                    "sisTipoPersonalSalud"=>$sisTipoPersonalSalud,"sisEgresado"=>$sisEgresado,
                                                    "sisEspecialidad"=>$sisEspecialidad));
    }

    public function buscarPorMes(Request $request){

        $datos = array("fechaInicio_fuasEmitidosG"=>$request->input("fechaInicio_fuasEmitidosG"),
                       "fechaFin_fuasEmitidosG"=>$request->input("fechaFin_fuasEmitidosG"));
   
        /* Si existe un requerimiento del tipo AJAX */
        if(request()->ajax()){
                return datatables()->of(DB::SELECT("SELECT FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ FU.PrimerNombre +' '+ CASE WHEN FU.OtrosNombres IS NULL THEN '' ELSE FU.OtrosNombres END AS Paciente,
                FU.HistoriaClinica,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy HH:mm:ss') as FechaHoraAtencion,
                FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy HH:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                FU.cie1_cod,generarFua_estado,FU.IdAtencion as Fua_id,FU.generarFua_estado,DATEDIFF(DAY,CAST(FU.FechaHoraRegistro AS DATETIME),GETDATE()) as DiasTranscurridos,U.name FROM ECONOMIA.dbo.FUA2 FU 
                INNER JOIN [software_ufpa_general].[dbo].[FUA] F ON FU.IdAtencion = F.NroFua INNER JOIN [software_ufpa_general].[dbo].[users] U ON F.IdUsuario = U.id
                LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id WHERE CAST(FU.FechaHoraRegistro AS DATE) 
                BETWEEN :fechaInicial AND :fechaFinal AND (FU.generarFua_estado = '1' OR FU.generarFua_estado = '0')",["fechaInicial"=>$request->input('fechaInicio_fuasEmitidosG'),
                                                                                    "fechaFinal"=>$request->input('fechaFin_fuasEmitidosG')]))->make(true);
        }
    }

    public function buscarPorHistoriaBD(Request $request){
          if(request()->ajax()){
                return datatables()->of(DB::SELECT("SELECT FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ FU.PrimerNombre +' '+ CASE WHEN FU.OtrosNombres IS NULL THEN '' ELSE FU.OtrosNombres END AS Paciente,
                FU.HistoriaClinica,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy HH:mm:ss') as FechaHoraAtencion,
                FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy HH:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                FU.cie1_cod,generarFua_estado,FU.IdAtencion as Fua_id,FU.generarFua_estado,DATEDIFF(DAY,CAST(FU.FechaHoraRegistro AS DATETIME),GETDATE()) as DiasTranscurridos,U.name FROM ECONOMIA.dbo.FUA2 FU 
                INNER JOIN [software_ufpa_general].[dbo].[FUA] F ON FU.IdAtencion = F.NroFua INNER JOIN [software_ufpa_general].[dbo].[users] U ON F.IdUsuario = U.id
                LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id 
                WHERE FU.HistoriaClinica = :hClinica AND (FU.generarFua_estado = '1' OR FU.generarFua_estado = '0')",["hClinica"=>$request->numHistoriaBD]))->make(true);
          }
     }

     public function buscarPorDocumentoBD(Request $request){
          if(request()->ajax()){
                return datatables()->of(DB::SELECT("SELECT FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ FU.PrimerNombre +' '+ CASE WHEN FU.OtrosNombres IS NULL THEN '' ELSE FU.OtrosNombres END AS Paciente,
                FU.HistoriaClinica,FU.NroDocumentoIdentidad,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy HH:mm:ss') as FechaHoraAtencion,
                FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy HH:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                FU.cie1_cod,generarFua_estado,FU.IdAtencion as Fua_id,FU.generarFua_estado,DATEDIFF(DAY,CAST(FU.FechaHoraRegistro AS DATETIME),GETDATE()) as DiasTranscurridos,U.name FROM ECONOMIA.dbo.FUA2 FU 
                INNER JOIN [software_ufpa_general].[dbo].[FUA] F ON FU.IdAtencion = F.NroFua INNER JOIN [software_ufpa_general].[dbo].[users] U ON F.IdUsuario = U.id
                LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id 
                WHERE FU.NroDocumentoIdentidad = :nroDoc AND (FU.generarFua_estado = '1' OR FU.generarFua_estado = '0')",["nroDoc"=>$request->numDocumentoBD]))->make(true);
          }
     }

     public function buscarPorFuaBD(Request $request){
          if(request()->ajax()){
                return datatables()->of(DB::SELECT("SELECT FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ FU.PrimerNombre +' '+ CASE WHEN FU.OtrosNombres IS NULL THEN '' ELSE FU.OtrosNombres END AS Paciente,
                FU.HistoriaClinica,FU.NroDocumentoIdentidad,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy HH:mm:ss') as FechaHoraAtencion,
                FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy HH:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                FU.cie1_cod,generarFua_estado,FU.IdAtencion as Fua_id,FU.generarFua_estado,DATEDIFF(DAY,CAST(FU.FechaHoraRegistro AS DATETIME),GETDATE()) as DiasTranscurridos,U.name FROM ECONOMIA.dbo.FUA2 FU 
                INNER JOIN [software_ufpa_general].[dbo].[FUA] F ON FU.IdAtencion = F.NroFua INNER JOIN [software_ufpa_general].[dbo].[users] U ON F.IdUsuario = U.id
                LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id 
                WHERE CONVERT(int,FU.Numero) = :nroFua AND (FU.generarFua_estado = '1' OR FU.generarFua_estado = '0')",["nroFua"=>$request->numFuaBD]))->make(true);
          }
     }

    public function verFua(Request $request){
        if($request->ajax()){
            $fuaId_fuasEmitidosG = $request->idFua_fuasEmitidosG;

            $datosFuaGeneral = DB::SELECT("SELECT F.DISA,F.Lote,F.Numero,F.ApellidoPaterno,F.ApellidoMaterno,F.PrimerNombre,F.OtrosNombres,F.Sexo,F.HistoriaClinica,
                                            FORMAT(CAST(F.FechaNacimiento AS DATE),'yyyy-MM-dd') AS FechaNacimiento,F.TipoDocumentoIdentidad,
                                            F.NroDocumentoIdentidad,F.DISAAsegurado,F.LoteAsegurado,F.NumeroAsegurado,
                                            FORMAT(CAST(F.FechaHoraAtencion AS DATE),'yyyy-MM-dd') AS FechaAtencion,
                                            FORMAT (F.FechaHoraAtencion, 'HH:mm:ss') as HoraAtencion,F.ModalidadAtencion,
                                            F.DestinoAsegurado,F.CodigoPrestacional,F.Componente,F.PersonaAtiende,
                                            F.LugarAtencion,F.TipoAtencion,F.pdr1_cod,F.cie1_cod,
                                            FORMAT(CAST(F.FechaIngreso AS DATE),'yyyy-MM-dd') AS FechaIngreso,
                                            FORMAT(CAST(F.FechaAlta AS DATE),'yyyy-MM-dd') AS FechaAlta,
                                            F.TipoDocResponsable,F.NroDocResponsable,F.personalAtiende_id,
                                            F.TipoPersonalSalud,F.EsEgresado,F.NroColegiatura,F.Especialidad,F.NroRNE,
                                            F.IPRESSRefirio,F.NroHojaReferencia,F.persona_id,F.IdAtencion,FU.codigoOficina,FU.codigoOperacion
                                            FROM ECONOMIA.dbo.FUA2 F INNER JOIN [software_ufpa_general].[dbo].[FUA] FU ON F.IdAtencion = FU.NroFua 
                                            WHERE F.IdAtencion = ?",[$fuaId_fuasEmitidosG]);

            return response(json_encode($datosFuaGeneral));
        }
    }

    public function fechaAltaVerFua(Request $request){
        if($request->ajax()){

             if($request->fechaAltaVerFua != ''){
                  $fechaAltaVerFua = $request->fechaAltaVerFua . 'T' . date("00:00:00.000");
             }else{
                  $fechaAltaVerFua = null;
             }

             $idFuaVerFua = $request->idFuaVerFua;

             DB::UPDATE("UPDATE ECONOMIA.dbo.FUA2 SET FechaHoraAtencion = :fechaAltaVerFua WHERE IdAtencion = :idFuaVerFua",["fechaAltaVerFua"=>$fechaAltaVerFua,"idFuaVerFua"=>$idFuaVerFua]);
             $actualizarVerFua = DB::SELECT("SELECT FORMAT(CAST(FechaHoraAtencion AS DATE), 'yyyy-MM-dd') as FechaHoraAtencion FROM ECONOMIA.dbo.FUA2 WHERE IdAtencion = :idFuaVerFua",["idFuaVerFua"=>$idFuaVerFua]);

             return array("FECHA_ATENCION_ACTUALIZADO", $actualizarVerFua);
        }
    }

    public function actualizarFua(Request $request){
        if($request->ajax()){
            $datos =  array("usuario_fuasEmitidosG"=>$request->usuario_fuasEmitidosG,
                            "idFuaF_fuasEmitidosG"=>$request->idFuaF_fuasEmitidosG,
                            "personalAtiendeF_fuasEmitidosG"=>$request->personalAtiendeF_fuasEmitidosG,
                            "lugarAtencionF_fuasEmitidosG"=>$request->lugarAtencionF_fuasEmitidosG,
                            "tipoAtencionF_fuasEmitidosG"=>$request->tipoAtencionF_fuasEmitidosG,
                            "codigoReferenciaF_fuasEmitidosG"=>$request->codigoReferenciaF_fuasEmitidosG,
                            "descripcionReferenciaF_fuasEmitidosG"=>$request->descripcionReferenciaF_fuasEmitidosG,
                            "numeroReferenciaF_fuasEmitidosG"=>$request->numeroReferenciaF_fuasEmitidosG,
                            "tipoDocumentoF_fuasEmitidosG"=>$request->tipoDocumentoF_fuasEmitidosG,
                            "numeroDocumentoF_fuasEmitidosG"=>$request->numeroDocumentoF_fuasEmitidosG,
                            "componenteF_fuasEmitidosG"=>$request->componenteF_fuasEmitidosG,
                            "codigoAsegurado1F_fuasEmitidosG"=>$request->codigoAsegurado1F_fuasEmitidosG,
                            "codigoAsegurado2F_fuasEmitidosG"=>$request->codigoAsegurado2F_fuasEmitidosG,
                            "codigoAsegurado3F_fuasEmitidosG"=>$request->codigoAsegurado3F_fuasEmitidosG,
                            "apellidoPaternoF_fuasEmitidosG"=>$request->apellidoPaternoF_fuasEmitidosG,
                            "apellidoMaternoF_fuasEmitidosG"=>$request->apellidoMaternoF_fuasEmitidosG,
                            "primerNombreF_fuasEmitidosG"=>$request->primerNombreF_fuasEmitidosG,
                            "otroNombreF_fuasEmitidosG"=>$request->otroNombreF_fuasEmitidosG,
                            "sexoF_fuasEmitidosG"=>$request->sexoF_fuasEmitidosG,
                            "fechaNacimientoF_fuasEmitidosG"=>$request->fechaNacimientoF_fuasEmitidosG,
                            "historiaF_fuasEmitidosG"=>$request->historiaF_fuasEmitidosG,
                            "fechaF_fuasEmitidosG"=>$request->fechaF_fuasEmitidosG,
                            "horaF_fuasEmitidosG"=>$request->horaF_fuasEmitidosG,
                            "codigoPrestacionalF_fuasEmitidosG"=>$request->codigoPrestacionalF_fuasEmitidosG,
                            "conceptoPrestacionalF_fuasEmitidosG"=>$request->conceptoPrestacionalF_fuasEmitidosG,
                            "destinoAseguradoF_fuasEmitidosG"=>$request->destinoAseguradoF_fuasEmitidosG,
                            "fechaIngresoF_fuasEmitidosG"=>$request->fechaIngresoF_fuasEmitidosG,
                            "fechaAltaF_fuasEmitidosG"=>$request->fechaAltaF_fuasEmitidosG,
                            "diagnosticoF_fuasEmitidosG"=>$request->diagnosticoF_fuasEmitidosG,
                            "codigoCieNF_fuasEmitidosG"=>$request->codigoCieNF_fuasEmitidosG,
                            "tipoDocumentoP_fuasEmitidosG"=>$request->tipoDocumentoP_fuasEmitidosG,
                            "numeroDocumentoP_fuasEmitidosG"=>$request->numeroDocumentoP_fuasEmitidosG,
                            "nombresApellidosP_fuasEmitidosG"=>$request->nombresApellidosP_fuasEmitidosG,
                            "tipoPersonalSaludF_fuasEmitidosG"=>$request->tipoPersonalSaludF_fuasEmitidosG,
                            "egresadoF_fuasEmitidosG"=>$request->egresadoF_fuasEmitidosG,
                            "colegiaturaF_fuasEmitidosG"=>$request->colegiaturaF_fuasEmitidosG,
                            "especialidadF_fuasEmitidosG"=>$request->especialidadF_fuasEmitidosG,
                            "rneF_fuasEmitidosG"=>$request->rneF_fuasEmitidosG,
                            "pacienteIdF_fuasEmitidosG"=>$request->pacienteIdF_fuasEmitidosG);

            $datosDiagnosticos_056 = array("diagnosticoF_0_fuasEmitidosG"=>$request->diagnosticoF_0_fuasEmitidosG,
                                           "diagnosticoF_1_fuasEmitidosG"=>$request->diagnosticoF_1_fuasEmitidosG,
                                           "diagnosticoF_2_fuasEmitidosG"=>$request->diagnosticoF_2_fuasEmitidosG,
                                           "diagnosticoF_3_fuasEmitidosG"=>$request->diagnosticoF_3_fuasEmitidosG,
                                           "codigoCieNF_0_fuasEmitidosG"=>$request->codigoCieNF_0_fuasEmitidosG,
                                           "codigoCieNF_1_fuasEmitidosG"=>$request->codigoCieNF_1_fuasEmitidosG,
                                           "codigoCieNF_2_fuasEmitidosG"=>$request->codigoCieNF_2_fuasEmitidosG,
                                           "codigoCieNF_3_fuasEmitidosG"=>$request->codigoCieNF_3_fuasEmitidosG);

            /* return $datosDiagnosticos_056; */
    
            /* INICIO DEL SEXO DEL PACIENTE */
            if($datos["sexoF_fuasEmitidosG"] == "MASCULINO"){
                $datos["sexoF_fuasEmitidosG"] = 1;
            }else{
                $datos["sexoF_fuasEmitidosG"] = 0;
            }
            /* FIN DEL SEXO DEL PACIENTE */
    
             /* INICIO DEL DOCUMENTO DEL PACIENTE */
             if($datos["tipoDocumentoF_fuasEmitidosG"] == "D.N.I."){
                  $datos["tipoDocumentoF_fuasEmitidosG"] = 1;
             }else{
                  $datos["tipoDocumentoF_fuasEmitidosG"] = 3;
             }
             /* FIN DEL DOCUMENTOS DEL PACIENTE */
    
             /* ES PARA EL DOCUMENTO DEL PERSONAL */
             if($datos["tipoDocumentoP_fuasEmitidosG"] == "D.N.I."){
                  $datos["tipoDocumentoP_fuasEmitidosG"] = 1;
             }else{
                  $datos["tipoDocumentoP_fuasEmitidosG"] = 3;
             }
             /* FIN DEL DOCUMENTO DEL PERSONAL */
    
            if(!empty($datos)){
                $validar = \Validator::make($datos,[
                    "usuario_fuasEmitidosG"=>'required',
                    "idFuaF_fuasEmitidosG"=>'required|regex:/^[0-9]+$/i',
                    "personalAtiendeF_fuasEmitidosG"=>'required|regex:/^[0-9]+$/i',
                    "lugarAtencionF_fuasEmitidosG"=>'required|regex:/^[0-9]+$/i',
                    "tipoAtencionF_fuasEmitidosG"=>'nullable|regex:/^[0-9]+$/i',
                    "codigoReferenciaF_fuasEmitidosG"=>'required|regex:/^[0-9]+$/i',
                    "numeroReferenciaF_fuasEmitidosG"=>'required|regex:/^[-\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ]+$/i',
                    "componenteF_fuasEmitidosG"=>'required|regex:/^[0-9]+$/i',
                    "codigoAsegurado2F_fuasEmitidosG"=>'required|regex:/^[0-9a-zA-ZñÑáéíóúÁÉÍÓÚ]+$/i',
                    "codigoAsegurado3F_fuasEmitidosG"=>'required|regex:/^[0-9]+$/i',
                    "tipoDocumentoF_fuasEmitidosG"=>'required|regex:/^[0-9]+$/i',
                    "numeroDocumentoF_fuasEmitidosG"=>'required|regex:/^[0-9]+$/i',
                    "apellidoPaternoF_fuasEmitidosG"=>'required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                    "apellidoMaternoF_fuasEmitidosG"=>'required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                    "primerNombreF_fuasEmitidosG"=>'required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                    "sexoF_fuasEmitidosG"=>'required|regex:/^[0-9]+$/i',
                    "fechaNacimientoF_fuasEmitidosG"=>'required',
                    "historiaF_fuasEmitidosG"=>'required|regex:/^[0-9]+$/i',
                    "fechaF_fuasEmitidosG"=>'nullable',/* HAY QUE COMPROBAR OK */
                    "horaF_fuasEmitidosG"=>'nullable',/* HAY QUE COMPROBAR OK */
                    "codigoPrestacionalF_fuasEmitidosG"=>'required|regex:/^[0-9]+$/i',
                    "conceptoPrestacionalF_fuasEmitidosG"=>'required|regex:/^[0-9]+$/i',
                    "destinoAseguradoF_fuasEmitidosG"=>'required|regex:/^[0-9]+$/i',
                    "nombresApellidosP_fuasEmitidosG"=>'required|regex:/^[0-9]+$/i',
                    "fechaAltaF_fuasEmitidosG"=>'nullable|after_or_equal:fechaIngresoF_fuasEmitidosG'
                ]);
    
                if($validar->fails()){
                    return "NO-VALIDACION";
                }else{
    
                    if($datos["fechaIngresoF_fuasEmitidosG"] == ''){
                        $fechaIngreso = null;          
                    }else{
                         $fechaIngreso = $datos["fechaIngresoF_fuasEmitidosG"] . 'T' . date("00:00:00.000");
                    }
    
                    if($datos["fechaAltaF_fuasEmitidosG"] == ''){
                         $fechaAlta = null;
                    }else{
                         $fechaAlta = $datos["fechaAltaF_fuasEmitidosG"] . 'T' . date("00:00:00.000");
                    }

                    if($datos["fechaF_fuasEmitidosG"] != '' && $datos["horaF_fuasEmitidosG"] != ''){
                         $valorFechaHora = $datos["fechaF_fuasEmitidosG"] . 'T' . $datos["horaF_fuasEmitidosG"] . '.000';
                    }else{
                         $valorFechaHora = null;
                    }
    
                       /* VOLVEMOS A TRAER TODOS LOS DATOS */
                       $datos =  array("PersonaAtiende"=>$request->personalAtiendeF_fuasEmitidosG,
                                       "LugarAtencion"=>$request->lugarAtencionF_fuasEmitidosG,
                                       "TipoAtencion"=>$request->tipoAtencionF_fuasEmitidosG,
                                       "IPRESSRefirio"=>$request->codigoReferenciaF_fuasEmitidosG,
                                       "NroHojaReferencia"=>$request->numeroReferenciaF_fuasEmitidosG,
                                       "TipoDocumentoIdentidad"=>$datos["tipoDocumentoF_fuasEmitidosG"],
                                       "NroDocumentoIdentidad"=>$request->numeroDocumentoF_fuasEmitidosG,
                                       "Componente"=>$request->componenteF_fuasEmitidosG,
                                       "DISAAsegurado"=>$request->codigoAsegurado1F_fuasEmitidosG,
                                       "LoteAsegurado"=>$request->codigoAsegurado2F_fuasEmitidosG,
                                       "NumeroAsegurado"=>$request->codigoAsegurado3F_fuasEmitidosG,
                                       "ApellidoPaterno"=>$request->apellidoPaternoF_fuasEmitidosG,
                                       "ApellidoMaterno"=>$request->apellidoMaternoF_fuasEmitidosG,
                                       "PrimerNombre"=>$request->primerNombreF_fuasEmitidosG,
                                       "OtrosNombres"=>$request->otroNombreF_fuasEmitidosG,
                                       "Sexo"=>$datos["sexoF_fuasEmitidosG"],
                                       "FechaNacimiento"=>$datos["fechaNacimientoF_fuasEmitidosG"] . 'T' . date("00:00:00.000"),
                                       "HistoriaClinica"=>$request->historiaF_fuasEmitidosG,
                                       "FechaHoraAtencion"=>$valorFechaHora,
                                       "CodigoPrestacional"=>$request->codigoPrestacionalF_fuasEmitidosG,
                                       "ModalidadAtencion"=>$request->conceptoPrestacionalF_fuasEmitidosG,
                                       "DestinoAsegurado"=>$request->destinoAseguradoF_fuasEmitidosG,
                                       "FechaIngreso"=>$fechaIngreso,
                                       "FechaAlta"=>$fechaAlta,
                                       "pdr1_cod"=>$request->diagnosticoF_fuasEmitidosG,
                                       "cie1_cod"=>$request->codigoCieNF_fuasEmitidosG,
                                       "TipoDocResponsable"=>$datos["tipoDocumentoP_fuasEmitidosG"],
                                       "NroDocResponsable"=>$request->numeroDocumentoP_fuasEmitidosG,
                                       "personalAtiende_id"=>$request->nombresApellidosP_fuasEmitidosG,
                                       "personalResponsable_id"=>$request->nombresApellidosP_fuasEmitidosG,
                                       "TipoPersonalSalud"=>$request->tipoPersonalSaludF_fuasEmitidosG,
                                       "EsEgresado"=>$request->egresadoF_fuasEmitidosG,
                                       "NroColegiatura"=>$request->colegiaturaF_fuasEmitidosG,
                                       "Especialidad"=>$request->especialidadF_fuasEmitidosG,
                                       "NroRNE"=>$request->rneF_fuasEmitidosG,
                                       "persona_id"=>$request->pacienteIdF_fuasEmitidosG);

                        $datosDiagnosticos_056 = array("diag_cod_dano1"=>$request->diagnosticoF_0_fuasEmitidosG,
                                                       "diag_cod_dano2"=>$request->diagnosticoF_1_fuasEmitidosG,
                                                       "diag_cod_dano3"=>$request->diagnosticoF_2_fuasEmitidosG,
                                                       "diag_cod_dano4"=>$request->diagnosticoF_3_fuasEmitidosG,
                                                       "cie_cod_dano1"=>$request->codigoCieNF_0_fuasEmitidosG,
                                                       "cie_cod_dano2"=>$request->codigoCieNF_1_fuasEmitidosG,
                                                       "cie_cod_dano3"=>$request->codigoCieNF_2_fuasEmitidosG,
                                                       "cie_cod_dano4"=>$request->codigoCieNF_3_fuasEmitidosG);
    
                        /* return $datosDiagnosticos_056; */
    
                       $fua = FuaModel::where('IdAtencion',$request->idFuaF_fuasEmitidosG)->update($datos);
                       $diagActualizar = Fua3ActualizacionModel::where('FUA_id',$request->idFuaF_fuasEmitidosG)->update($datosDiagnosticos_056);
    
                       /* GUARDAMOS VALORES EN LA BD SOFTWARE_UFPA (AUDITORIA) */
    
/*                        $generarFuaA = new FuaNModel();
                       $generarFuaA->NroFua = $request->idFuaF_fuasEmitidosG;
                       $generarFuaA->TipoAccion = 2;
                       $generarFuaA->IdUsuario = $request->usuario_fuasEmitidosG;
                       $generarFuaA->save(); */
    
                       return "ACTUALIZAR-FUA";
                       /* FALTA EXTRAER EL ID DE LA ATENCIÓN */
                  }
             }else{
                  return "ERROR";
             }
        }
    }

    public function volverDeAnuladoAGenerado(Request $request){
        if(request()->ajax()){
            DB::TABLE('ECONOMIA.DBO.FUA2')
            ->WHERE('ECONOMIA.DBO.FUA2.IdAtencion', $request->idFuaA_fuasEmitidosG)
            ->UPDATE(['ECONOMIA.DBO.FUA2.generarFua_estado' => 1]);

            return "volver-correcto";
        }
    }

    public function validarPasswordFua(Request $request){
        if($request->ajax()){
             $password_fuasEmitidosG = $request->password;
             $extraemosContraseñaVerdadera = DB::SELECT("SELECT password FROM software_ufpa_general.dbo.users WHERE
                                                        id = ?",[$request->usuarioExtraer]);

             $contraseñaV = $extraemosContraseñaVerdadera[0]->password;

             if(password_verify($password_fuasEmitidosG, $contraseñaV)) {


                  $datos1 =  array("TipoAccion"=>0);
                  $datos2 =  array("generarFua_estado"=>'0');

                  $fua1 = FuaNModel::where('NroFua',$request->idFua)->update($datos1);
                  $fua2 = FuaModel::where('IdAtencion',$request->idFua)->update($datos2);

                  DB::UPDATE("UPDATE INRDIS_II.dbo.PDiarioDetalles SET FUA_id = '' WHERE FUA_id = ?",[$request->idFua]);
                  DB::UPDATE("UPDATE INRDIS_II.dbo.ServicioPersonaDet SET sustentoPago_id = '' WHERE sustentoPago_id = ?",[$request->idFua]);
                  DB::UPDATE("UPDATE INRDIS_II.dbo.CitasHora_Paciente SET FUA_id = '' WHERE FUA_id = ?",[$request->idFua]);

                  return "IGUALES";
             }else{
                  return "DIFERENTES";
             }
        }
   }

   public function codigoCie(Request $request){
        if($request->ajax()){
            $codigoCie_fuasEmitidosG = $request->CodigoCie;
            $datosCodigoCie = DB::SELECT("SELECT C.cie_cod,C.cie_desc FROM inrdis_ii.dbo.tblCIE C 
                                          WHERE C.cie_cod = ?",[$codigoCie_fuasEmitidosG]);
            return response(json_encode($datosCodigoCie));
        }
    }

    public function referencias(Request $request) {

        if($request->ajax()){
            $idPaciente_fuasEmitidosG = $request->idPaciente;
            $datosReferencia = DB::SELECT("SELECT TOP 1 RRH.estb2_cod,TE.descripcion,RRH.ref_rec_hoja_nro FROM INRDIS_II.dbo.Referencias_Rec_Hojas RRH 
                                           INNER JOIN INRDIS_II.dbo.tbEstablecimiento TE ON RRH.estb2_cod = TE.codigoRenaes COLLATE Modern_Spanish_CI_AS 
                                           WHERE RRH.Persona_id = ? ORDER BY ref_rec_hoja_fech DESC",[$idPaciente_fuasEmitidosG]);

            return response(json_encode($datosReferencia));  
        }
    }

    public function personalC(Request $request){
        if($request->ajax()){
                $idPersonal_fuasEmitidosG = $request->idPersonal;
                $datosPersonal = DB::SELECT("SELECT PER.ddi_cod,PER.ddi_nro,PER.per_apat,PER.per_amat,PER.per_nomb,PER.NroColegiatura,PER.NroRNE,TPS.id AS TipoPersonalSalud_id,TPS.descripcion AS TipoPersonalSalud,
                                             E.id AS Especialidad_id,E.descripcion AS Especialidad,SE.id AS Egresado_id,SE.descripcion AS Egresado 
                                             FROM PERSONAL.dbo.personal PER LEFT JOIN ECONOMIA.dbo.sisTIPOPERSONALSALUD TPS ON PER.sisTIPOPERSONALSALUD_id = TPS.id
                                             LEFT JOIN ECONOMIA.dbo.sisESPECIALIDAD E ON PER.sisESPECIALIDAD_id = E.id
                                             LEFT JOIN ECONOMIA.dbo.sisEgresado SE ON PER.sisEGRESADO_id = SE.id WHERE PER.id = ?",[$idPersonal_fuasEmitidosG]);
    
                return response(json_encode($datosPersonal));
        }
    }

    public function diagnosticos056(Request $request){
        if($request->ajax()){
            $idFua_fuasEmitidosG = $request->idFuaVerFua;
            $datosDiagnosticos_056 = DB::SELECT("SELECT diag_cod_dano1 AS diag,cie_cod_dano1 AS cie,C.cie_desc AS descrip FROM INRDIS_II.dbo.CitasHora_Paciente CHP LEFT JOIN INRDIS_II.dbo.tblCIE C ON CHP.cie_cod_dano1 = C.cie_cod WHERE CHP.FUA_id = :idFua1 /*AND CHP.diag_cod_dano1 IS NOT NULL AND CHP.cie_cod_dano1 IS NOT NULL*/
            UNION ALL
            SELECT diag_cod_dano2 AS diag,cie_cod_dano2 AS cie,C.cie_desc AS descrip FROM INRDIS_II.dbo.CitasHora_Paciente CHP LEFT JOIN INRDIS_II.dbo.tblCIE C ON CHP.cie_cod_dano2 = C.cie_cod WHERE CHP.FUA_id = :idFua2 /*AND CHP.diag_cod_dano2 IS NOT NULL AND CHP.cie_cod_dano2 IS NOT NULL*/
            UNION ALL
            SELECT diag_cod_dano3 AS diag,cie_cod_dano3 AS cie,C.cie_desc AS descrip FROM INRDIS_II.dbo.CitasHora_Paciente CHP LEFT JOIN INRDIS_II.dbo.tblCIE C ON CHP.cie_cod_dano3 = C.cie_cod WHERE CHP.FUA_id = :idFua3 /*AND CHP.diag_cod_dano3 IS NOT NULL AND CHP.cie_cod_dano3 IS NOT NULL*/
            UNION ALL
            SELECT diag_cod_dano4 AS diag,cie_cod_dano4 AS cie,C.cie_desc AS descrip FROM INRDIS_II.dbo.CitasHora_Paciente CHP LEFT JOIN INRDIS_II.dbo.tblCIE C ON CHP.cie_cod_dano4 = C.cie_cod WHERE CHP.FUA_id = :idFua4 /*AND CHP.diag_cod_dano4 IS NOT NULL AND CHP.cie_cod_dano4 IS NOT NULL*/",["idFua1"=>$idFua_fuasEmitidosG,"idFua2"=>$idFua_fuasEmitidosG,"idFua3"=>$idFua_fuasEmitidosG,"idFua4"=>$idFua_fuasEmitidosG]);

            return response(json_encode($datosDiagnosticos_056));
        }
    }

    public function reportesFUA($IdAtencion,Request $request){

        $datosFua = DB::SELECT("SELECT * FROM [ECONOMIA].[dbo].[FUA2] FU LEFT JOIN ECONOMIA.dbo.sisTIPOPERSONALSALUD TPS ON FU.TipoPersonalSalud = TPS.id WHERE FU.IdAtencion = ?",[$IdAtencion]);
        $datosFuaIdCab = DB::SELECT("SELECT * FROM [SOFTWARE_UFPA_GENERAL].[dbo].[FUA] WHERE NroFua = ?",[$IdAtencion]);

        if($datosFua[0]->IPRESSRefirio != ''){
            $establecimientoDatosFua = DB::SELECT("SELECT descripcion FROM INRDIS_II.dbo.tbEstablecimiento WHERE codigoRenaes = ?",[$datosFua[0]->IPRESSRefirio]);
            $establecimientoDatosFua1 = $establecimientoDatosFua[0]->descripcion;
        }else{
            $establecimientoDatosFua = "";
            $establecimientoDatosFua1 = "";
        }
    
        if($datosFua[0]->cie1_cod != ''){
            $descripcionCodigoCie = DB::SELECT("SELECT C.cie_desc FROM INRDIS_II.dbo.tblCIE C WHERE C.cie_cod = ?",[$datosFua[0]->cie1_cod]);
            $descripcionCodigoCie1 = $descripcionCodigoCie[0]->cie_desc;
    
        }else{
            $descripcionCodigoCie1 = "";
            $descripcionCodigoCie = "";
        }
    
        $datosFarmacia = DB::SELECT("SELECT DFC.fua_nro,C.catalogo_sismed,C.catalogo_desc,C.catalogo_tipo,DFD.docf_item_cant,DFC.docf_flag,DFC.docf_fech_despachado,
                                    DFD.docf_item_total,DFD.docf_item_precio,DFD.diagnostico_estado FROM INR.dbo.DocFCabecera DFC INNER JOIN INR.dbo.DocFDetalles DFD ON DFC.docf_trans = DFD.docf_trans INNER JOIN
                                    INR.dbo.Catalogo C ON DFD.catalogo_cod = C.catalogo_cod WHERE DFC.fua_nro = ? AND cast(YEAR(docf_fech) as varchar(4)) = cast(YEAR(GETDATE()) as varchar(4))",[$datosFua[0]->Numero]);

/*         $datosLaboratorio = DB::SELECT("SELECT PDL.pdiario_id,PDL.pdiario_id_orden,PDL.asis_cod,PDL.hcl_num,PDL.pdiario_apat,PDL.pdiario_amat,PDL.pdiario_nomb,PDL.asis_cod AS Expre2,
                                        PDL.edad_cod,PDL.sproc_cod,PDL.pdiario_obs,CL.exa_lab_desc,CL.exa_lab_cod,PDL.fua_nro,PD.pdiario_fech,CAST(CL.exa_lab_precio_1 as decimal(12,2)) AS exa_lab_precio_1
                                        FROM INRDIS_II.dbo.PDiario_Lab PDL INNER JOIN INRDIS_II.dbo.PDiario_Lab_Examenes PDLE ON PDL.pdiario_id = PDLE.pdiario_id
                                        AND PDL.pdiario_id_orden = PDLE.pdiario_id_orden INNER JOIN INRDIS_II.dbo.tblCatalogo_Laboratorio CL ON PDLE.exa_lab_cod = CL.exa_lab_cod INNER JOIN
                                        INRDIS_II.dbo.PDiario PD ON PDL.pdiario_id = PD.pdiario_id WHERE PDL.fua_nro = ?",[$datosFua[0]->Numero]); */

        $datosTerapiasInicial = DB::SELECT("SELECT COUNT(SPD.id) AS cantidad_citas,S.codigoServicio,CPT.codigoCPT,S.descripcion
                                            FROM INRDIS_II.dbo.tbEstado E INNER JOIN
                                            INRDIS_II.dbo.ServicioPersonaCab SPC INNER JOIN
                                            INRDIS_II.dbo.ServicioPersonaDet SPD ON SPC.id = SPD.ServicioPersonaCab_id ON E.id = SPD.tbEstado_id LEFT OUTER JOIN
                                            INRDIS_II.dbo.Persona P INNER JOIN 
                                            INRDIS_II.dbo.tbPersonal PER ON P.id = PER.Persona_id ON SPD.tbPersonal_id = PER.id LEFT OUTER JOIN
                                            INRDIS_II.dbo.tbAsistencia A ON SPD.tbAsistencia_id = A.id LEFT OUTER JOIN
                                            ECONOMIA.dbo.FUA2 F ON SPD.sustentoPago_id = F.IdAtencion LEFT OUTER JOIN
                                            INRDIS_II.dbo.tbHorario H ON SPD.tbHorario_id = H.id INNER JOIN
                                            INRDIS_II.dbo.tbServicio_UOrganica SUO ON SPC.tbServicio_UOrganica_id = SUO.id INNER JOIN
                                            INRDIS_II.dbo.tbServicio S ON SUO.tbServicio_id = S.id LEFT JOIN
                                            INRDIS_II.dbo.tbCPT CPT ON S.tbCPT_id = CPT.id                                                           
                                            WHERE SPD.ServicioPersonaCab_id = :idCab AND CONVERT(int,F.Numero) = :numeroFua AND F.Lote = '23' AND SPD.tbEstado_id <> 11 
                                            GROUP BY S.codigoServicio,CPT.codigoCPT,S.descripcion",["idCab"=>$datosFuaIdCab[0]->idCab,"numeroFua"=>$datosFua[0]->Numero]);

        $datosTerapiasCitasHora = DB::SELECT("SELECT COUNT(CH.id) AS cantidad_citas,CPT.codigoCPT,CPT.descripcion
                                            FROM [INRDIS_II].[dbo].[CitasHora] CH INNER JOIN 
                                            [INRDIS_II].[dbo].[CitasHora_Paciente] CHP ON CH.cita_prof = CHP.cita_prof AND CH.cita_fech = CHP.cita_fech AND CH.cita_extra = CHP.cita_extra AND
                                            CH.cita_hora = CHP.cita_hora INNER JOIN [INRDIS_II].[dbo].[tblUO] UO ON CH.uo_cod = UO.uo_cod INNER JOIN
                                            [INRDIS_II].[dbo].[tblTipoCita_Citado] TCC ON CHP.tcitaa_cod = TCC.tcitaa_cod INNER JOIN 
                                            [INRDIS_II].[dbo].[TipoActividad] TA ON TCC.tipoActividad_id = TA.id INNER JOIN 
                                            [INRDIS_II].[dbo].[ActividadEspecifica] AE ON TCC.actividadEspecifica_id = AE.id INNER JOIN
                                            [INRDIS_II].[dbo].[Persona] P ON CHP.pers_id = P.id LEFT OUTER JOIN 
                                            [INRDIS_II].[dbo].[tblAsistencia] A ON CHP.asis_cod = A.asis_cod LEFT OUTER JOIN
                                            [ECONOMIA].[dbo].[FUA2] FU ON CHP.FUA_id = FU.IdAtencion LEFT OUTER JOIN 
                                            [INRDIS_II].[dbo].[AsistenciaCita] AC ON A.AsistenciaCita_id = AC.id LEFT JOIN
                                            [INRDIS_II].[dbo].[tbPersonal] PE ON CH.cita_prof = PE.id INNER JOIN
                                            INRDIS_II.dbo.tbCPT CPT ON AE.CPT = CPT.codigoCPT COLLATE Modern_Spanish_CI_AS
                                            WHERE FU.Numero = :numeroFua AND FU.Lote = '23' GROUP BY CPT.codigoCPT,CPT.descripcion",["numeroFua"=>$datosFua[0]->Numero]);

        $datosPDiarioDetalles = DB::SELECT("SELECT COUNT(PDD.id) AS cantidad_citas,CPT.codigoCPT,CPT.descripcion
                                            FROM [INRDIS_II].[dbo].[PDiarioDetalles] PDD INNER JOIN
                                            [INRDIS_II].[dbo].[PDiarioCabecera] PDC ON PDD.PDiarioCabecera_id = PDC.id INNER JOIN 
                                            [INRDIS_II].[dbo].[TipoActividad] TA ON PDC.tipoActividad_id = TA.id INNER JOIN 
                                            [INRDIS_II].[dbo].[ActividadEspecifica] AE ON PDD.actividadEspecifica_id = AE.id INNER JOIN
                                            [INRDIS_II].[dbo].[UnidadOrganica] UO ON PDC.UnidadOrganica_id = UO.id INNER JOIN
                                            [INRDIS_II].[dbo].[Persona] P ON PDD.Persona_id = P.id LEFT JOIN 
                                            [INRDIS_II].[dbo].[tbPersonal] PE ON PDC.PersonalAtiende_id = PE.id LEFT JOIN
                                            [INRDIS_II].[dbo].[AsistenciaCita] AC ON PDD.AsistenciaCita_id = AC.id LEFT JOIN
                                            [ECONOMIA].[dbo].[FUA2] FU ON PDD.FUA_id= FU.IdAtencion INNER JOIN
                                            INRDIS_II.dbo.tbCPT CPT ON AE.CPT = CPT.codigoCPT COLLATE Modern_Spanish_CI_AS
                                            WHERE FU.Numero = :numeroFua AND FU.Lote = '23'
                                            GROUP BY CPT.codigoCPT,CPT.descripcion",["numeroFua"=>$datosFua[0]->Numero]);
    
        $nombreResponsable = DB::SELECT("SELECT PER.id AS Profesional_id,PER.per_apat+' '+PER.per_amat+', '+PER.per_nomb AS Profesional FROM PERSONAL.dbo.personal PER  WHERE PER.id = ?
                                         ORDER BY PER.per_apat ASC,PER.per_amat ASC",[$datosFua[0]->personalAtiende_id]);
    
        $datosTipoPersonalSalud = DB::SELECT("SELECT STPS.descripcion AS descripcion,STPS.id,STPS.idEnFormato FROM ECONOMIA.dbo.sisTIPOPERSONALSALUD STPS WHERE id <> 00");
    
        $datosEspecialidad = DB::SELECT("SELECT E.descripcion AS descripcion,E.id FROM ECONOMIA.dbo.sisESPECIALIDAD E WHERE id <> 00 AND id = ?",[$datosFua[0]->Especialidad]);
    
        /* TRABAJANDO LA FECHA DE NACIMIENTO */
    
        $primerCaracterFechaNacimiento = substr(Carbon::parse($datosFua[0]->FechaNacimiento)->format('d'),0,1);
        $segundoCaracterFechaNacimiento = substr(Carbon::parse($datosFua[0]->FechaNacimiento)->format('d'),1,1);
        $tercerCaracterFechaNacimiento = substr(Carbon::parse($datosFua[0]->FechaNacimiento)->format('m'),0,1);
        $cuartoCaracterFechaNacimiento = substr(Carbon::parse($datosFua[0]->FechaNacimiento)->format('m'),1,1);
        $quintoCaracterFechaNacimiento = substr(Carbon::parse($datosFua[0]->FechaNacimiento)->format('Y'),0,1);
        $sextoCaracterFechaNacimiento = substr(Carbon::parse($datosFua[0]->FechaNacimiento)->format('Y'),1,1);
        $septimoCaracterFechaNacimiento = substr(Carbon::parse($datosFua[0]->FechaNacimiento)->format('Y'),2,1);
        $octavoCaracterFechaNacimiento = substr(Carbon::parse($datosFua[0]->FechaNacimiento)->format('Y'),3,1);
    
        /* FIN DE LA FECHA DE NACIMIENTO */
    
        /* TRABAJANDO LA FECHA Y HORA DE ATENCION */
        if($datosFua[0]->FechaHoraAtencion != ''){
            $primerCDiaFechaAtencion = substr(Carbon::parse($datosFua[0]->FechaHoraAtencion)->format('d'),0,1);
            $segundoCDiaFechaAtencion = substr(Carbon::parse($datosFua[0]->FechaHoraAtencion)->format('d'),1,1);
            $primerCMesFechaAtencion = substr(Carbon::parse($datosFua[0]->FechaHoraAtencion)->format('m'),0,1);
            $segundoCMesFechaAtencion = substr(Carbon::parse($datosFua[0]->FechaHoraAtencion)->format('m'),1,1);
            $primerCAñoFechaAtencion = substr(Carbon::parse($datosFua[0]->FechaHoraAtencion)->format('Y'),0,1);
            $segundoCAñoFechaAtencion = substr(Carbon::parse($datosFua[0]->FechaHoraAtencion)->format('Y'),1,1);
            $tercerCAñoFechaAtencion = substr(Carbon::parse($datosFua[0]->FechaHoraAtencion)->format('Y'),2,1);
            $cuartoCAñoFechaAtencion = substr(Carbon::parse($datosFua[0]->FechaHoraAtencion)->format('Y'),3,1);
            $primerCHoraFechaAtencion = Carbon::parse($datosFua[0]->FechaHoraAtencion)->format('H');
            $primerCMinutosFechaAtencion = Carbon::parse($datosFua[0]->FechaHoraAtencion)->format('i');
        }else{
            $primerCDiaFechaAtencion = '';
            $segundoCDiaFechaAtencion = '';
            $primerCMesFechaAtencion = '';
            $segundoCMesFechaAtencion = '';
            $primerCAñoFechaAtencion = '';
            $segundoCAñoFechaAtencion = '';
            $tercerCAñoFechaAtencion = '';
            $cuartoCAñoFechaAtencion = '';
            $primerCHoraFechaAtencion = '';
            $primerCMinutosFechaAtencion = '';
        }
        /* FIN DE LA FECHA Y HORA DE ATENCION */
    
        /* TRABAJANDO LA FECHA DE INGRESO (HOSPITALIZACION) */
        if($datosFua[0]->FechaIngreso != ''){
            $primerCDiaFechaIngreso = substr(Carbon::parse($datosFua[0]->FechaIngreso)->format('d'),0,1);
            $segundoCDiaFechaIngreso = substr(Carbon::parse($datosFua[0]->FechaIngreso)->format('d'),1,1);
            $primerCMesFechaIngreso = substr(Carbon::parse($datosFua[0]->FechaIngreso)->format('m'),0,1);
            $segundoCMesFechaIngreso = substr(Carbon::parse($datosFua[0]->FechaIngreso)->format('m'),1,1);
            $primerCAñoFechaIngreso = substr(Carbon::parse($datosFua[0]->FechaIngreso)->format('Y'),0,1);
            $segundoCAñoFechaIngreso = substr(Carbon::parse($datosFua[0]->FechaIngreso)->format('Y'),1,1);
            $tercerCAñoFechaIngreso = substr(Carbon::parse($datosFua[0]->FechaIngreso)->format('Y'),2,1);
            $cuartoCAñoFechaIngreso = substr(Carbon::parse($datosFua[0]->FechaIngreso)->format('Y'),3,1);
        }else{
            $primerCDiaFechaIngreso = '';
            $segundoCDiaFechaIngreso = '';
            $primerCMesFechaIngreso = '';
            $segundoCMesFechaIngreso = '';
            $primerCAñoFechaIngreso = '';
            $segundoCAñoFechaIngreso = '';
            $tercerCAñoFechaIngreso = '';
            $cuartoCAñoFechaIngreso = '';
        }
        /* FIN DE FECHA DE INGRESO */
    
        /* TRABAJANDO LA FECHA DE ALTA (HOSPITALIZACION) */
        if($datosFua[0]->FechaAlta != ''){
            $primerCDiaFechaAlta = substr(Carbon::parse($datosFua[0]->FechaAlta)->format('d'),0,1);
            $segundoCDiaFechaAlta = substr(Carbon::parse($datosFua[0]->FechaAlta)->format('d'),1,1);
            $primerCMesFechaAlta = substr(Carbon::parse($datosFua[0]->FechaAlta)->format('m'),0,1);
            $segundoCMesFechaAlta = substr(Carbon::parse($datosFua[0]->FechaAlta)->format('m'),1,1);
            $primerCAñoFechaAlta = substr(Carbon::parse($datosFua[0]->FechaAlta)->format('Y'),0,1);
            $segundoCAñoFechaAlta = substr(Carbon::parse($datosFua[0]->FechaAlta)->format('Y'),1,1);
            $tercerCAñoFechaAlta = substr(Carbon::parse($datosFua[0]->FechaAlta)->format('Y'),2,1);
            $cuartoCAñoFechaAlta = substr(Carbon::parse($datosFua[0]->FechaAlta)->format('Y'),3,1);
        }else{
            $primerCDiaFechaAlta = '';
            $segundoCDiaFechaAlta = '';
            $primerCMesFechaAlta = '';
            $segundoCMesFechaAlta = '';
            $primerCAñoFechaAlta = '';
            $segundoCAñoFechaAlta = '';
            $tercerCAñoFechaAlta = '';
            $cuartoCAñoFechaAlta = '';
        }
        /* FIN DE LA FECHA DE ALTA */

        // compartir datos para ver
        view()->share('datosFua',$datosFua);
        view()->share('primerCaracterFechaNacimiento',$primerCaracterFechaNacimiento);
        view()->share('segundoCaracterFechaNacimiento',$segundoCaracterFechaNacimiento);
        view()->share('tercerCaracterFechaNacimiento',$tercerCaracterFechaNacimiento);
        view()->share('cuartoCaracterFechaNacimiento',$cuartoCaracterFechaNacimiento);
        view()->share('quintoCaracterFechaNacimiento',$quintoCaracterFechaNacimiento);
        view()->share('sextoCaracterFechaNacimiento',$sextoCaracterFechaNacimiento);
        view()->share('septimoCaracterFechaNacimiento',$septimoCaracterFechaNacimiento);
        view()->share('octavoCaracterFechaNacimiento',$octavoCaracterFechaNacimiento);
        view()->share('primerCDiaFechaAtencion',$primerCDiaFechaAtencion);
        view()->share('segundoCDiaFechaAtencion',$segundoCDiaFechaAtencion);
        view()->share('primerCMesFechaAtencion',$primerCMesFechaAtencion);
        view()->share('segundoCMesFechaAtencion',$segundoCMesFechaAtencion);
        view()->share('primerCAñoFechaAtencion',$primerCAñoFechaAtencion);
        view()->share('segundoCAñoFechaAtencion',$segundoCAñoFechaAtencion);
        view()->share('tercerCAñoFechaAtencion',$tercerCAñoFechaAtencion);
        view()->share('cuartoCAñoFechaAtencion',$cuartoCAñoFechaAtencion);
        view()->share('primerCHoraFechaAtencion',$primerCHoraFechaAtencion);
        view()->share('primerCMinutosFechaAtencion',$primerCMinutosFechaAtencion);
        view()->share('primerCDiaFechaIngreso',$primerCDiaFechaIngreso);
        view()->share('segundoCDiaFechaIngreso',$segundoCDiaFechaIngreso);
        view()->share('primerCMesFechaIngreso',$primerCMesFechaIngreso);
        view()->share('segundoCMesFechaIngreso',$segundoCMesFechaIngreso);
        view()->share('primerCAñoFechaIngreso',$primerCAñoFechaIngreso);
        view()->share('segundoCAñoFechaIngreso',$segundoCAñoFechaIngreso);
        view()->share('tercerCAñoFechaIngreso',$tercerCAñoFechaIngreso);
        view()->share('cuartoCAñoFechaIngreso',$cuartoCAñoFechaIngreso);
        view()->share('primerCDiaFechaAlta',$primerCDiaFechaAlta);
        view()->share('segundoCDiaFechaAlta',$segundoCDiaFechaAlta);
        view()->share('primerCMesFechaAlta',$primerCMesFechaAlta);
        view()->share('segundoCMesFechaAlta',$segundoCMesFechaAlta);
        view()->share('primerCAñoFechaAlta',$primerCAñoFechaAlta);
        view()->share('segundoCAñoFechaAlta',$segundoCAñoFechaAlta);
        view()->share('tercerCAñoFechaAlta',$tercerCAñoFechaAlta);
        view()->share('cuartoCAñoFechaAlta',$cuartoCAñoFechaAlta);
        view()->share('nombreResponsable',$nombreResponsable);
        view()->share('datosEspecialidad',$datosEspecialidad);
        view()->share('datosTipoPersonalSalud',$datosTipoPersonalSalud);
        view()->share('establecimientoDatosFua',$establecimientoDatosFua);
        view()->share('establecimientoDatosFua1',$establecimientoDatosFua1);
        view()->share('descripcionCodigoCie',$descripcionCodigoCie);
        view()->share('descripcionCodigoCie1',$descripcionCodigoCie1);
        view()->share('datosFarmacia',$datosFarmacia);
/*         view()->share('datosLaboratorio',$datosLaboratorio); */
        view()->share('datosTerapiasInicial',$datosTerapiasInicial);
        view()->share('datosFuaIdCab',$datosFuaIdCab);
        view()->share('datosTerapiasCitasHora',$datosTerapiasCitasHora);
        view()->share('datosPDiarioDetalles',$datosPDiarioDetalles);
        
        $pdf = \PDF::loadView('paginas.reportesFUA',$datosFua);
        return $pdf->setPaper('a4','portrait')->stream('reportesFUA.pdf');
    }
}
