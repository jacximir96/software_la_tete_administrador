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
use Illuminate\Support\Facades\DB;/* Agregar conbinaciones de tablas en la base de datos */
use PDF;/* Apuntamos al modelo que existe por defecto para obtener información en PDF */
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AuditoriaEmitidosController extends Controller
{
     public function index(){

          /* Si existe un requerimiento del tipo AJAX */
          if(request()->ajax()){
               return datatables()->of(DB::SELECT("SELECT FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ CASE WHEN FU.OtrosNombres IS NULL THEN '' ELSE FU.OtrosNombres END AS Paciente,
                                                  FU.HistoriaClinica,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraAtencion,
                                                  FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                                                  FU.cie1_cod,generarFua_estado,FU.IdAtencion as Fua_id,FU.auditarFua_estado FROM ECONOMIA.dbo.FUA2 FU LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id WHERE 
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

        return view('paginas.auditoriaEmitidos',array("administradores"=>$administradores,"estados"=>$estados,"concPrestacional"=>$concPrestacional,
                                                      "destinoAsegurado"=>$destinoAsegurado,"codPrestacional"=>$codPrestacional,
                                                      "componente"=>$componente,"personalAtiende"=>$personalAtiende,"lugarAtencion"=>$lugarAtencion,
                                                      "tipoAtencion"=>$tipoAtencion,"datosPersonalC"=>$datosPersonalC,
                                                      "sisTipoPersonalSalud"=>$sisTipoPersonalSalud,"sisEgresado"=>$sisEgresado,
                                                      "sisEspecialidad"=>$sisEspecialidad));
    }

    public function buscarPorMes(Request $request){

     $datos = array("fechaInicio_auditoriaEmitidos"=>$request->input("fechaInicio_auditoriaEmitidos"),
                    "fechaFin_auditoriaEmitidos"=>$request->input("fechaFin_auditoriaEmitidos"));

          /* Si existe un requerimiento del tipo AJAX */
          if(request()->ajax()){
               return datatables()->of(DB::SELECT("SELECT FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ FU.PrimerNombre +' '+ CASE WHEN FU.OtrosNombres IS NULL THEN '' ELSE FU.OtrosNombres END AS Paciente,
                                                  FU.HistoriaClinica,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraAtencion,
                                                  FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                                                  FU.cie1_cod,generarFua_estado,FU.IdAtencion as Fua_id,FU.auditarFua_estado FROM ECONOMIA.dbo.FUA2 FU LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id WHERE CAST(FU.FechaHoraRegistro AS DATE) 
                                                  BETWEEN :fechaInicial AND :fechaFinal AND (FU.generarFua_estado = '1' OR FU.generarFua_estado = '0')",["fechaInicial"=>$request->input('fechaInicio_auditoriaEmitidos'),
                                                                                                                        "fechaFinal"=>$request->input('fechaFin_auditoriaEmitidos')]))->make(true);
          }
     }

     public function buscarPorHistoriaBD(Request $request){
          
          if(request()->ajax()){
               return datatables()->of(DB::SELECT("SELECT FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ FU.PrimerNombre +' '+ FU.OtrosNombres AS Paciente,
                                                   FU.HistoriaClinica,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraAtencion,
                                                   FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                                                   FU.cie1_cod,generarFua_estado,FU.IdAtencion as Fua_id,FU.auditarFua_estado FROM ECONOMIA.dbo.FUA2 FU LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id 
                                                   WHERE FU.HistoriaClinica = ?  AND (FU.generarFua_estado = '1' OR FU.generarFua_estado = '0')",[$request->numHistoriaBD]))->make(true);
          }
     }

     public function buscarPorDocumentoBD(Request $request){
          if(request()->ajax()){
               return datatables()->of(DB::SELECT("SELECT FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ FU.PrimerNombre +' '+ FU.OtrosNombres AS Paciente,
                                                   FU.HistoriaClinica,FU.NroDocumentoIdentidad,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraAtencion,
                                                   FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                                                   FU.cie1_cod,generarFua_estado,FU.IdAtencion as Fua_id,FU.auditarFua_estado FROM ECONOMIA.dbo.FUA2 FU LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id 
                                                   WHERE FU.NroDocumentoIdentidad = ? AND (FU.generarFua_estado = '1' OR FU.generarFua_estado = '0')",[$request->numDocumentoBD]))->make(true);
          }
     }

     public function buscarPorFuaBD(Request $request){
          if(request()->ajax()){
               return datatables()->of(DB::SELECT("SELECT FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,FU.ApellidoPaterno +' '+ FU.ApellidoMaterno +' '+ FU.PrimerNombre +' '+ FU.OtrosNombres AS Paciente,
                                                   FU.HistoriaClinica,FU.NroDocumentoIdentidad,FORMAT(CAST(FU.FechaHoraAtencion AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraAtencion,
                                                   FORMAT(CAST(FU.FechaHoraRegistro AS DATETIME), 'dd-MM-yyyy hh:mm:ss') as FechaHoraRegistro,CodigoPrestacional,P.per_apat+' '+P.per_amat+' '+P.per_nomb as Profesional,
                                                   FU.cie1_cod,generarFua_estado,FU.IdAtencion as Fua_id,FU.auditarFua_estado FROM ECONOMIA.dbo.FUA2 FU LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id 
                                                   WHERE CONVERT(int,FU.Numero) = ? AND (FU.generarFua_estado = '1' OR FU.generarFua_estado = '0')",[$request->numFuaBD]))->make(true);
          }
     }

     public function rolCitas(Request $request){
          $datos = array("idCab_auditoriaEmitidos"=>$request->input("idCab_auditoriaEmitidos"));

          /* Si existe un requerimiento del tipo AJAX */
          if(request()->ajax()){
          return datatables()->of(DB::SELECT("SELECT SPD.id,SPD.tbHorario_id,SPD.nroSesion,FORMAT(CAST(SPD.fechaProgramada AS DATE),'dd-MM-yyyy') + ' ' + LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) AS fechaProgramada,
                                                     SPD.horaProgramada,0 AS seleccion,
                                              CASE WHEN A.id = 19 THEN 'NO ATENDIDO'
                                                   WHEN A.id = 18 THEN 'NO ATENDIDO'
                                                   WHEN A.id = 17 THEN 'NO ATENDIDO'
                                                   WHEN A.id = 13 THEN 'NO ATENDIDO'
                                                   WHEN A.id = 10 THEN 'CITADO'
                                                   ELSE A.abreviatura END AS atendido,
                                                   A.id as atendido_id,

                                              P.apPaterno + ' ' + P.apMaterno + ', ' + P.nombres AS Personal,
                                              SPD.tbFinanciador_id,SPD.sustentoPago_id,E.abreviatura,SPD.tbEstado_id,SPD.tbAsistencia_id,
                                              CASE WHEN SPD.tbFinanciador_id = 2 THEN F.DISA + '-' + F.Lote + '-' + F.Numero ELSE '' END AS Comprobante,SPD.notas,
                                              F.cie1_cod

                                             FROM INRDIS_II.dbo.tbEstado E INNER JOIN
                                                  INRDIS_II.dbo.ServicioPersonaCab SPC INNER JOIN
                                                  INRDIS_II.dbo.ServicioPersonaDet SPD ON SPC.id = SPD.ServicioPersonaCab_id ON E.id = SPD.tbEstado_id LEFT OUTER JOIN
                                                  INRDIS_II.dbo.Persona P INNER JOIN 
                                                  INRDIS_II.dbo.tbPersonal PER ON P.id = PER.Persona_id ON SPD.tbPersonal_id = PER.id LEFT OUTER JOIN
                                                  INRDIS_II.dbo.tbAsistencia A ON SPD.tbAsistencia_id = A.id LEFT OUTER JOIN
                                                  ECONOMIA.dbo.FUA2 F ON SPD.sustentoPago_id = F.IdAtencion LEFT OUTER JOIN
                                                  INRDIS_II.dbo.tbHorario H ON SPD.tbHorario_id = H.id
                           
                                             WHERE (SPD.ServicioPersonaCab_id = :idCab_auditoriaEmitidos) AND SPD.tbEstado_id <> 11
                                             ORDER BY SPD.nroSesion",["idCab_auditoriaEmitidos"=>$request->input('idCab_auditoriaEmitidos')]))->make(true);
          }
     }

     public function verFua(Request $request){
          if($request->ajax()){
               $fuaId_auditoriaEmitidos = $request->idFua_auditoriaEmitidos;

               $datosFuaGeneral = DB::SELECT("SELECT F.DISA,F.Lote,F.Numero,F.ApellidoPaterno,F.ApellidoMaterno,F.PrimerNombre,F.OtrosNombres,F.Sexo,F.HistoriaClinica,
                                              FORMAT(CAST(F.FechaNacimiento AS DATE),'yyyy-MM-dd') AS FechaNacimiento,F.TipoDocumentoIdentidad,
                                              F.NroDocumentoIdentidad,F.DISAAsegurado,F.LoteAsegurado,F.NumeroAsegurado,
                                              FORMAT(CAST(F.FechaHoraAtencion AS DATE),'yyyy-MM-dd') AS FechaAtencion,
                                              FORMAT (F.FechaHoraAtencion, 'hh:mm:ss') as HoraAtencion,F.ModalidadAtencion,
                                              F.DestinoAsegurado,F.CodigoPrestacional,F.Componente,F.PersonaAtiende,
                                              F.LugarAtencion,F.TipoAtencion,F.pdr1_cod,F.cie1_cod,
                                              FORMAT(CAST(F.FechaIngreso AS DATE),'yyyy-MM-dd') AS FechaIngreso,
                                              FORMAT(CAST(F.FechaAlta AS DATE),'yyyy-MM-dd') AS FechaAlta,
                                              F.TipoDocResponsable,F.NroDocResponsable,F.personalAtiende_id,
                                              F.TipoPersonalSalud,F.EsEgresado,F.NroColegiatura,F.Especialidad,F.NroRNE,
                                              F.IPRESSRefirio,F.NroHojaReferencia,F.persona_id,F.IdAtencion
                                              FROM ECONOMIA.dbo.FUA2 F 
                                              WHERE F.IdAtencion = ?",[$fuaId_auditoriaEmitidos]);

               $datosFarmacia = DB::SELECT("SELECT DFC.fua_nro,DFC.tdoc_cod,C.catalogo_sismed,RTRIM(DFD.catalogo_cod) AS catalogo_cod,CONVERT(int,DFC.fina_cod) AS fina_cod,C.catalogo_desc,C.catalogo_medida,DFD.docf_item_cant,DFC.docf_flag,DFC.docf_fech_despachado,DFC.docf_fech,
                                            DFC.tdoc_cod,DFC.docf_nro,CAST(DFD.docf_item_total as decimal(12,2)) AS docf_item_total,CAST(DFD.docf_item_precio as decimal(12,2)) AS docf_item_precio,DFD.diagnostico_estado,DFD.cambio_cantidad FROM INR.dbo.DocFCabecera DFC INNER JOIN INR.dbo.DocFDetalles DFD ON DFC.docf_trans = DFD.docf_trans INNER JOIN
                                            INR.dbo.Catalogo C ON DFD.catalogo_cod = C.catalogo_cod WHERE DFC.fua_nro = ? AND DFC.tdoc_cod = 204",[$datosFuaGeneral[0]->Numero]);

               $datosLaboratorio = DB::SELECT("SELECT PDL.pdiario_id,PDL.pdiario_id_orden,PDL.asis_cod,PDL.hcl_num,PDL.pdiario_apat,PDL.pdiario_amat,PDL.pdiario_nomb,PDL.asis_cod AS Expre2,
                                               PDL.edad_cod,PDL.sproc_cod,PDL.pdiario_obs,CL.exa_lab_desc,CL.exa_lab_cod,PDL.fua_nro,PD.pdiario_fech,CAST(CL.exa_lab_precio_1 as decimal(12,2)) AS exa_lab_precio_1,
                                               CPMS.codigoCPMS,CPMS.denominacionCorta
                                               FROM INRDIS_II.dbo.PDiario_Lab PDL INNER JOIN INRDIS_II.dbo.PDiario_Lab_Examenes PDLE ON PDL.pdiario_id = PDLE.pdiario_id
                                               AND PDL.pdiario_id_orden = PDLE.pdiario_id_orden INNER JOIN INRDIS_II.dbo.tblCatalogo_Laboratorio CL ON PDLE.exa_lab_cod = CL.exa_lab_cod INNER JOIN
                                               INRDIS_II.dbo.PDiario PD ON PDL.pdiario_id = PD.pdiario_id INNER JOIN INRDIS_II.dbo.tbCPMS CPMS ON CL.tbCPMS_id = CPMS.id 
                                               WHERE PDL.fua_nro = ?",[$datosFuaGeneral[0]->Numero]);

               /* SUMAMOS TODOS LOS PROCEDIMIENTOS DEL FUA */
               $sumaMedicamentos = DB::SELECT("SELECT SUM(CAST(DFD.docf_item_total as decimal(12,2))) AS suma FROM INR.dbo.DocFCabecera DFC INNER JOIN INR.dbo.DocFDetalles DFD ON DFC.docf_trans = DFD.docf_trans INNER JOIN
                                               INR.dbo.Catalogo C ON DFD.catalogo_cod = C.catalogo_cod WHERE DFC.fua_nro = ? AND DFC.tdoc_cod = 204",[$datosFuaGeneral[0]->Numero]);

               $sumaLaboratorio = DB::SELECT("SELECT SUM(CAST(CL.exa_lab_precio_1 as decimal(12,2))) AS suma
                                              FROM INRDIS_II.dbo.PDiario_Lab PDL INNER JOIN INRDIS_II.dbo.PDiario_Lab_Examenes PDLE ON PDL.pdiario_id = PDLE.pdiario_id
                                              AND PDL.pdiario_id_orden = PDLE.pdiario_id_orden INNER JOIN INRDIS_II.dbo.tblCatalogo_Laboratorio CL ON PDLE.exa_lab_cod = CL.exa_lab_cod INNER JOIN
                                              INRDIS_II.dbo.PDiario PD ON PDL.pdiario_id = PD.pdiario_id WHERE PDL.fua_nro = ?",[$datosFuaGeneral[0]->Numero]);

               if($sumaMedicamentos[0]->suma == NULL) {
                    $sumaMedicamentos[0]->suma = 0;
               }

               if($sumaLaboratorio[0]->suma == NULL) {
                    $sumaLaboratorio[0]->suma = 0;
               }

               $costoTotalFua = $sumaMedicamentos[0]->suma + $sumaLaboratorio[0]->suma;

               return response()->json(['datosFuaGeneral' => $datosFuaGeneral, 'datosFarmacia' => $datosFarmacia,
                                        'datosLaboratorio' => $datosLaboratorio, 'costoTotalFua' => $costoTotalFua]);
          }
     }

     public function showMedicamentos(Request $request,$catalogo_cod){
          if($request->ajax()){

               $fuaId_auditoriaEmitidos = $request->NroFuaM;
               $catalogoCod_auditoriaEmitidos = $request->catalogo_cod;

               $datosFuaGeneral = DB::SELECT("SELECT F.Numero FROM ECONOMIA.dbo.FUA2 F WHERE F.IdAtencion = ?",[$fuaId_auditoriaEmitidos]);

               $datosMedicamento = DB::SELECT("SELECT DFC.fua_nro,DFC.tdoc_cod,C.catalogo_sismed,RTRIM(DFD.catalogo_cod) AS catalogo_cod,CONVERT(int,DFC.fina_cod) AS fina_cod,C.catalogo_desc,C.catalogo_medida,DFD.docf_item_cant,DFC.docf_flag,DFC.docf_fech_despachado,DFC.docf_fech,
                                               DFC.tdoc_cod,DFC.docf_nro,CAST(DFD.docf_item_total as decimal(12,2)) AS docf_item_total,CAST(DFD.docf_item_precio as decimal(12,2)) AS docf_item_precio,DFD.diagnostico_estado,DFD.cambio_cantidad FROM INR.dbo.DocFCabecera DFC INNER JOIN INR.dbo.DocFDetalles DFD ON DFC.docf_trans = DFD.docf_trans INNER JOIN
                                               INR.dbo.Catalogo C ON DFD.catalogo_cod = C.catalogo_cod WHERE DFC.fua_nro = :numero AND DFC.tdoc_cod = 204 AND DFD.catalogo_cod = :catalogo_cod",["numero"=>$datosFuaGeneral[0]->Numero,"catalogo_cod"=>$catalogoCod_auditoriaEmitidos]);

               return response(json_encode($datosMedicamento)); 

          }
     }

     public function showLaboratorio(Request $request,$catalogo_cod){
          if($request->ajax()){

               $fuaId_auditoriaEmitidos = $request->NroFuaM;
               $catalogoCod_auditoriaEmitidos = $request->catalogo_cod;

               $datosFuaGeneral = DB::SELECT("SELECT F.Numero FROM ECONOMIA.dbo.FUA2 F WHERE F.IdAtencion = ?",[$fuaId_auditoriaEmitidos]);

               $datosLaboratorio = DB::SELECT("SELECT CPMS.codigoCPMS,CPMS.denominacionCorta,CL.exa_lab_precio_1,CAST(CL.exa_lab_precio_1 as decimal(12,2)) AS exa_lab_precio_1
                                               FROM INRDIS_II.dbo.PDiario_Lab PDL INNER JOIN INRDIS_II.dbo.PDiario_Lab_Examenes PDLE ON PDL.pdiario_id = PDLE.pdiario_id
                                               AND PDL.pdiario_id_orden = PDLE.pdiario_id_orden INNER JOIN INRDIS_II.dbo.tblCatalogo_Laboratorio CL ON PDLE.exa_lab_cod = CL.exa_lab_cod INNER JOIN
                                               INRDIS_II.dbo.PDiario PD ON PDL.pdiario_id = PD.pdiario_id INNER JOIN INRDIS_II.dbo.tbCPMS CPMS ON CL.tbCPMS_id = CPMS.id WHERE PDL.fua_nro = :numero
                                               ",["numero"=>$datosFuaGeneral[0]->Numero]);

               return response(json_encode($datosLaboratorio)); 

          }
     }

     public function updateMedicamentos(Request $request){
          if($request->ajax()){

               $fuaId_auditoriaEmitidos = $request->idFuaM_auditoriaEmitidos;

               $datosFuaGeneral = DB::SELECT("SELECT F.Numero FROM ECONOMIA.dbo.FUA2 F WHERE F.IdAtencion = ?",[$fuaId_auditoriaEmitidos]);
               
               $datos =  array("diagnosticoM_auditoriaEmitidos"=>$request->diagnosticoM_auditoriaEmitidos,
                               "observacionM_auditoriaEmitidos"=>$request->observacionM_auditoriaEmitidos,
                               "idMedicamentoM_auditoriaEmitidos"=>$request->idMedicamentoM_auditoriaEmitidos,
                               "numeroFuaM_auditoriaEmitidos"=>$datosFuaGeneral[0]->Numero);

               if(!empty($datos)){

                    $validar = \Validator::make($datos,[
                         "idMedicamentoM_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "numeroFuaM_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "diagnosticoM_auditoriaEmitidos"=>'nullable|regex:/^[0-9]+$/i',
                         "observacionM_auditoriaEmitidos"=>'nullable|regex:/^[-\\0-9]+$/i'
                    ]);

                    if($validar->fails()){
                         return "NO-VALIDACION";
                    }else{

                         $datos =  array("diagnosticoM_auditoriaEmitidos"=>$request->diagnosticoM_auditoriaEmitidos,
                                         "observacionM_auditoriaEmitidos"=>$request->observacionM_auditoriaEmitidos,
                                         "idMedicamentoM_auditoriaEmitidos"=>$request->idMedicamentoM_auditoriaEmitidos,
                                         "numeroFuaM_auditoriaEmitidos"=>$datosFuaGeneral[0]->Numero);

                         DB::UPDATE("UPDATE A SET A.diagnostico_estado = :diagnosticoM_auditoriaEmitidos, A.cambio_cantidad = :observacionM_auditoriaEmitidos FROM INR.dbo.DocFDetalles A
                                     INNER JOIN INR.dbo.DocFCabecera B ON A.docf_trans = B.docf_trans WHERE B.fua_nro = :numeroFuaM_auditoriaEmitidos AND A.catalogo_cod = :idMedicamentoM_auditoriaEmitidos",
                                     ["diagnosticoM_auditoriaEmitidos"=>$datos["diagnosticoM_auditoriaEmitidos"],"observacionM_auditoriaEmitidos"=>$datos["observacionM_auditoriaEmitidos"],
                                      "idMedicamentoM_auditoriaEmitidos"=>$datos["idMedicamentoM_auditoriaEmitidos"],"numeroFuaM_auditoriaEmitidos"=>$datos["numeroFuaM_auditoriaEmitidos"]]);

                         return "ACTUALIZAR-MEDICAMENTO";
                    }

               }else{
                    return "ERROR";
               }
        
          }
     }

     public function updateLaboratorio(Request $request){
          if($request->ajax()){

               $fuaId_auditoriaEmitidos = $request->idFuaM_auditoriaEmitidos;

               $datosFuaGeneral = DB::SELECT("SELECT F.Numero FROM ECONOMIA.dbo.FUA2 F WHERE F.IdAtencion = ?",[$fuaId_auditoriaEmitidos]);
               
               $datos =  array("diagnosticoM_auditoriaEmitidos"=>$request->diagnosticoM_auditoriaEmitidos,
                               "observacionM_auditoriaEmitidos"=>$request->observacionM_auditoriaEmitidos,
                               "idMedicamentoM_auditoriaEmitidos"=>$request->idMedicamentoM_auditoriaEmitidos,
                               "numeroFuaM_auditoriaEmitidos"=>$datosFuaGeneral[0]->Numero);

               if(!empty($datos)){

                    $validar = \Validator::make($datos,[
                         "idMedicamentoM_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "numeroFuaM_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "diagnosticoM_auditoriaEmitidos"=>'nullable|regex:/^[0-9]+$/i',
                         "observacionM_auditoriaEmitidos"=>'nullable|regex:/^[-\\0-9]+$/i'
                    ]);

                    if($validar->fails()){
                         return "NO-VALIDACION";
                    }else{

                         $datos =  array("diagnosticoM_auditoriaEmitidos"=>$request->diagnosticoM_auditoriaEmitidos,
                                         "observacionM_auditoriaEmitidos"=>$request->observacionM_auditoriaEmitidos,
                                         "idMedicamentoM_auditoriaEmitidos"=>$request->idMedicamentoM_auditoriaEmitidos,
                                         "numeroFuaM_auditoriaEmitidos"=>$datosFuaGeneral[0]->Numero);

                         DB::UPDATE("UPDATE A SET A.diagnostico_estado = :diagnosticoM_auditoriaEmitidos, A.cambio_cantidad = :observacionM_auditoriaEmitidos FROM INR.dbo.DocFDetalles A
                                     INNER JOIN INR.dbo.DocFCabecera B ON A.docf_trans = B.docf_trans WHERE B.fua_nro = :numeroFuaM_auditoriaEmitidos AND A.catalogo_cod = :idMedicamentoM_auditoriaEmitidos",
                                     ["diagnosticoM_auditoriaEmitidos"=>$datos["diagnosticoM_auditoriaEmitidos"],"observacionM_auditoriaEmitidos"=>$datos["observacionM_auditoriaEmitidos"],
                                      "idMedicamentoM_auditoriaEmitidos"=>$datos["idMedicamentoM_auditoriaEmitidos"],"numeroFuaM_auditoriaEmitidos"=>$datos["numeroFuaM_auditoriaEmitidos"]]);

                         return "ACTUALIZAR-LABORATORIO";
                    }

               }else{
                    return "ERROR";
               }
        
          }
     }

     public function verMedicamentos(Request $request){
          if($request->ajax()){
               $numeroFuaId_auditoriaEmitidos = $request->NumeroFuaId;

               $datosFarmacia = DB::SELECT("SELECT DFC.fua_nro,DFC.tdoc_cod,C.catalogo_sismed,DFD.catalogo_cod,CONVERT(int,DFC.fina_cod) AS fina_cod,C.catalogo_desc,C.catalogo_medida,DFD.docf_item_cant,DFC.docf_flag,DFC.docf_fech_despachado,DFC.docf_fech,
                                            DFC.tdoc_cod,DFC.docf_nro,DFD.docf_item_total,DFD.docf_item_precio FROM INR.dbo.DocFCabecera DFC INNER JOIN INR.dbo.DocFDetalles DFD ON DFC.docf_trans = DFD.docf_trans INNER JOIN
                                            INR.dbo.Catalogo C ON DFD.catalogo_cod = C.catalogo_cod WHERE DFC.fua_nro = ? AND DFC.tdoc_cod = 204",[$numeroFuaId_auditoriaEmitidos]);

               return response(json_encode($datosFarmacia));
               
          }
     }

     public function mostrarPdfLaboratorio($persona_id){

          return $persona_id;


          header('Content-type: application/pdf');
          readfile('\\\\192.168.6.105\\lab_resultados$\\01_0031385.pdf');
     }

     public function auditarFua(Request $request){
          if($request->ajax()){

               DB::UPDATE("UPDATE [ECONOMIA].[dbo].[FUA2] SET auditarFua_estado = '1' WHERE IdAtencion = ?",[$request->idFuaAuditarFua]);

               return "AUDITAR-FUA";
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

     public function codigoCie(Request $request){
          if($request->ajax()){
                  $codigoCie_auditoriaEmitidos = $request->CodigoCie;
                  $datosCodigoCie = DB::SELECT("SELECT C.cie_cod,C.cie_desc FROM inrdis_ii.dbo.tblCIE C 
                                                WHERE C.cie_cod = ?",[$codigoCie_auditoriaEmitidos]);
                  return response(json_encode($datosCodigoCie));
          }
     }

     public function referencias(Request $request) {

          if($request->ajax()){
                  $idPaciente_auditoriaEmitidos = $request->idPaciente;
                  $datosReferencia = DB::SELECT("SELECT TOP 1 RRH.estb2_cod,TE.descripcion,RRH.ref_rec_hoja_nro FROM INRDIS_II.dbo.Referencias_Rec_Hojas RRH 
                                                 INNER JOIN INRDIS_II.dbo.tbEstablecimiento TE ON RRH.estb2_cod = TE.codigoRenaes COLLATE Modern_Spanish_CI_AS 
                                                 WHERE RRH.Persona_id = ? ORDER BY ref_rec_hoja_fech DESC",[$idPaciente_auditoriaEmitidos]);

                  return response(json_encode($datosReferencia));  
          }
     }

     public function personalC(Request $request){
          if($request->ajax()){
                  $idPersonal_auditoriaEmitidos = $request->idPersonal;
                  $datosPersonal = DB::SELECT("SELECT PER.ddi_cod,PER.ddi_nro,PER.per_apat,PER.per_amat,PER.per_nomb,PER.NroColegiatura,PER.NroRNE,TPS.id AS TipoPersonalSalud_id,TPS.descripcion AS TipoPersonalSalud,
                                               E.id AS Especialidad_id,E.descripcion AS Especialidad,SE.id AS Egresado_id,SE.descripcion AS Egresado 
                                               FROM PERSONAL.dbo.personal PER LEFT JOIN ECONOMIA.dbo.sisTIPOPERSONALSALUD TPS ON PER.sisTIPOPERSONALSALUD_id = TPS.id
                                               LEFT JOIN ECONOMIA.dbo.sisESPECIALIDAD E ON PER.sisESPECIALIDAD_id = E.id
                                               LEFT JOIN ECONOMIA.dbo.sisEgresado SE ON PER.sisEGRESADO_id = SE.id WHERE PER.id = ?",[$idPersonal_auditoriaEmitidos]);

                  return response(json_encode($datosPersonal));
          }
     }

     public function actualizarFua(Request $request){
          if($request->ajax()){
               $datos =  array("usuario_auditoriaEmitidos"=>$request->usuario_auditoriaEmitidos,
                               "idFuaF_auditoriaEmitidos"=>$request->idFuaF_auditoriaEmitidos,
                               "personalAtiendeF_auditoriaEmitidos"=>$request->personalAtiendeF_auditoriaEmitidos,
                               "lugarAtencionF_auditoriaEmitidos"=>$request->lugarAtencionF_auditoriaEmitidos,
                               "tipoAtencionF_auditoriaEmitidos"=>$request->tipoAtencionF_auditoriaEmitidos,
                               "codigoReferenciaF_auditoriaEmitidos"=>$request->codigoReferenciaF_auditoriaEmitidos,
                               "descripcionReferenciaF_auditoriaEmitidos"=>$request->descripcionReferenciaF_auditoriaEmitidos,
                               "numeroReferenciaF_auditoriaEmitidos"=>$request->numeroReferenciaF_auditoriaEmitidos,
                               "tipoDocumentoF_auditoriaEmitidos"=>$request->tipoDocumentoF_auditoriaEmitidos,
                               "numeroDocumentoF_auditoriaEmitidos"=>$request->numeroDocumentoF_auditoriaEmitidos,
                               "componenteF_auditoriaEmitidos"=>$request->componenteF_auditoriaEmitidos,
                               "codigoAsegurado1F_auditoriaEmitidos"=>$request->codigoAsegurado1F_auditoriaEmitidos,
                               "codigoAsegurado2F_auditoriaEmitidos"=>$request->codigoAsegurado2F_auditoriaEmitidos,
                               "codigoAsegurado3F_auditoriaEmitidos"=>$request->codigoAsegurado3F_auditoriaEmitidos,
                               "apellidoPaternoF_auditoriaEmitidos"=>$request->apellidoPaternoF_auditoriaEmitidos,
                               "apellidoMaternoF_auditoriaEmitidos"=>$request->apellidoMaternoF_auditoriaEmitidos,
                               "primerNombreF_auditoriaEmitidos"=>$request->primerNombreF_auditoriaEmitidos,
                               "otroNombreF_auditoriaEmitidos"=>$request->otroNombreF_auditoriaEmitidos,
                               "sexoF_auditoriaEmitidos"=>$request->sexoF_auditoriaEmitidos,
                               "fechaNacimientoF_auditoriaEmitidos"=>$request->fechaNacimientoF_auditoriaEmitidos,
                               "historiaF_auditoriaEmitidos"=>$request->historiaF_auditoriaEmitidos,
                               "fechaF_auditoriaEmitidos"=>$request->fechaF_auditoriaEmitidos,
                               "horaF_auditoriaEmitidos"=>Carbon::parse($request->horaF_auditoriaEmitidos)->format('H:i:s'),
                               "codigoPrestacionalF_auditoriaEmitidos"=>$request->codigoPrestacionalF_auditoriaEmitidos,
                               "conceptoPrestacionalF_auditoriaEmitidos"=>$request->conceptoPrestacionalF_auditoriaEmitidos,
                               "destinoAseguradoF_auditoriaEmitidos"=>$request->destinoAseguradoF_auditoriaEmitidos,
                               "fechaIngresoF_auditoriaEmitidos"=>$request->fechaIngresoF_auditoriaEmitidos,
                               "fechaAltaF_auditoriaEmitidos"=>$request->fechaAltaF_auditoriaEmitidos,
                               "diagnosticoF_auditoriaEmitidos"=>$request->diagnosticoF_auditoriaEmitidos,
                               "codigoCieNF_auditoriaEmitidos"=>$request->codigoCieNF_auditoriaEmitidos,
                               "tipoDocumentoP_auditoriaEmitidos"=>$request->tipoDocumentoP_auditoriaEmitidos,
                               "numeroDocumentoP_auditoriaEmitidos"=>$request->numeroDocumentoP_auditoriaEmitidos,
                               "nombresApellidosP_auditoriaEmitidos"=>$request->nombresApellidosP_auditoriaEmitidos,
                               "tipoPersonalSaludF_auditoriaEmitidos"=>$request->tipoPersonalSaludF_auditoriaEmitidos,
                               "egresadoF_auditoriaEmitidos"=>$request->egresadoF_auditoriaEmitidos,
                               "colegiaturaF_auditoriaEmitidos"=>$request->colegiaturaF_auditoriaEmitidos,
                               "especialidadF_auditoriaEmitidos"=>$request->especialidadF_auditoriaEmitidos,
                               "rneF_auditoriaEmitidos"=>$request->rneF_auditoriaEmitidos,
                               "pacienteIdF_auditoriaEmitidos"=>$request->pacienteIdF_auditoriaEmitidos);
                               
               /* INICIO DEL SEXO DEL PACIENTE */
               if($datos["sexoF_auditoriaEmitidos"] == "MASCULINO"){
                    $datos["sexoF_auditoriaEmitidos"] = 1;
               }else{
                    $datos["sexoF_auditoriaEmitidos"] = 0;
               }
               /* FIN DEL SEXO DEL PACIENTE */

               /* INICIO DEL DOCUMENTO DEL PACIENTE */
               if($datos["tipoDocumentoF_auditoriaEmitidos"] == "D.N.I."){
                    $datos["tipoDocumentoF_auditoriaEmitidos"] = 1;
               }else{
                    $datos["tipoDocumentoF_auditoriaEmitidos"] = 3;
               }
               /* FIN DEL DOCUMENTOS DEL PACIENTE */

               /* ES PARA EL DOCUMENTO DEL PERSONAL */
               if($datos["tipoDocumentoP_auditoriaEmitidos"] == "D.N.I."){
                    $datos["tipoDocumentoP_auditoriaEmitidos"] = 1;
               }else{
                    $datos["tipoDocumentoP_auditoriaEmitidos"] = 3;
               }
               /* FIN DEL DOCUMENTO DEL PERSONAL */

               if(!empty($datos)){
                    $validar = \Validator::make($datos,[
                         "usuario_auditoriaEmitidos"=>'required',
                         "idFuaF_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "personalAtiendeF_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "lugarAtencionF_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "tipoAtencionF_auditoriaEmitidos"=>'nullable|regex:/^[0-9]+$/i',
                         "codigoReferenciaF_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "numeroReferenciaF_auditoriaEmitidos"=>'required|regex:/^[-\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ]+$/i',
                         "componenteF_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "codigoAsegurado2F_auditoriaEmitidos"=>'required|regex:/^[0-9a-zA-ZñÑáéíóúÁÉÍÓÚ]+$/i',
                         "codigoAsegurado3F_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "tipoDocumentoF_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "numeroDocumentoF_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "apellidoPaternoF_auditoriaEmitidos"=>'required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                         "apellidoMaternoF_auditoriaEmitidos"=>'required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                         "primerNombreF_auditoriaEmitidos"=>'required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                         "sexoF_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "fechaNacimientoF_auditoriaEmitidos"=>'required',
                         "historiaF_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "fechaF_auditoriaEmitidos"=>'required',
                         "horaF_auditoriaEmitidos"=>'required',
                         "codigoPrestacionalF_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "conceptoPrestacionalF_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "destinoAseguradoF_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "nombresApellidosP_auditoriaEmitidos"=>'required|regex:/^[0-9]+$/i',
                         "fechaAltaF_auditoriaEmitidos"=>'nullable|after_or_equal:fechaIngresoF_auditoriaEmitidos'
                    ]);

                    if($validar->fails()){
                         return "NO-VALIDACION";
                    }else{

                         if($datos["fechaIngresoF_auditoriaEmitidos"] == ''){
                              $fechaIngreso = null;          
                         }else{
                              $fechaIngreso = $datos["fechaIngresoF_auditoriaEmitidos"] . 'T' . date("00:00:00.000");
                         }

                         if($datos["fechaAltaF_auditoriaEmitidos"] == ''){
                              $fechaAlta = null;
                         }else{
                              $fechaAlta = $datos["fechaAltaF_auditoriaEmitidos"] . 'T' . date("00:00:00.000");
                         }

                         /* VOLVEMOS A TRAER TODOS LOS DATOS */
                         $datos =  array("PersonaAtiende"=>$request->personalAtiendeF_auditoriaEmitidos,
                                         "LugarAtencion"=>$request->lugarAtencionF_auditoriaEmitidos,
                                         "TipoAtencion"=>$request->tipoAtencionF_auditoriaEmitidos,
                                         "IPRESSRefirio"=>$request->codigoReferenciaF_auditoriaEmitidos,
                                         "NroHojaReferencia"=>$request->numeroReferenciaF_auditoriaEmitidos,
                                         "TipoDocumentoIdentidad"=>$datos["tipoDocumentoF_auditoriaEmitidos"],
                                         "NroDocumentoIdentidad"=>$request->numeroDocumentoF_auditoriaEmitidos,
                                         "Componente"=>$request->componenteF_auditoriaEmitidos,
                                         "DISAAsegurado"=>$request->codigoAsegurado1F_auditoriaEmitidos,
                                         "LoteAsegurado"=>$request->codigoAsegurado2F_auditoriaEmitidos,
                                         "NumeroAsegurado"=>$request->codigoAsegurado3F_auditoriaEmitidos,
                                         "ApellidoPaterno"=>$request->apellidoPaternoF_auditoriaEmitidos,
                                         "ApellidoMaterno"=>$request->apellidoMaternoF_auditoriaEmitidos,
                                         "PrimerNombre"=>$request->primerNombreF_auditoriaEmitidos,
                                         "OtrosNombres"=>$request->otroNombreF_auditoriaEmitidos,
                                         "Sexo"=>$datos["sexoF_auditoriaEmitidos"],
                                         "FechaNacimiento"=>$datos["fechaNacimientoF_auditoriaEmitidos"] . 'T' . date("00:00:00.000"),
                                         "HistoriaClinica"=>$request->historiaF_auditoriaEmitidos,
                                         "FechaHoraAtencion"=>$datos["fechaF_auditoriaEmitidos"]. 'T' . $datos["horaF_auditoriaEmitidos"],
                                         "CodigoPrestacional"=>$request->codigoPrestacionalF_auditoriaEmitidos,
                                         "ModalidadAtencion"=>$request->conceptoPrestacionalF_auditoriaEmitidos,
                                         "DestinoAsegurado"=>$request->destinoAseguradoF_auditoriaEmitidos,
                                         "FechaIngreso"=>$fechaIngreso,
                                         "FechaAlta"=>$fechaAlta,
                                         "pdr1_cod"=>$request->diagnosticoF_auditoriaEmitidos,
                                         "cie1_cod"=>$request->codigoCieNF_auditoriaEmitidos,
                                         "TipoDocResponsable"=>$datos["tipoDocumentoP_auditoriaEmitidos"],
                                         "NroDocResponsable"=>$request->numeroDocumentoP_auditoriaEmitidos,
                                         "personalAtiende_id"=>$request->nombresApellidosP_auditoriaEmitidos,
                                         "personalResponsable_id"=>$request->nombresApellidosP_auditoriaEmitidos,
                                         "TipoPersonalSalud"=>$request->tipoPersonalSaludF_auditoriaEmitidos,
                                         "EsEgresado"=>$request->egresadoF_auditoriaEmitidos,
                                         "NroColegiatura"=>$request->colegiaturaF_auditoriaEmitidos,
                                         "Especialidad"=>$request->especialidadF_auditoriaEmitidos,
                                         "NroRNE"=>$request->rneF_auditoriaEmitidos,
                                         "persona_id"=>$request->pacienteIdF_auditoriaEmitidos);

                                         /* return $datos; */

                         $fua = FuaModel::where('IdAtencion',$request->idFuaF_auditoriaEmitidos)->update($datos);

                         /* GUARDAMOS VALORES EN LA BD SOFTWARE_UFPA (AUDITORIA) */

                         $generarFuaA = new FuaNModel();
                         $generarFuaA->NroFua = $request->idFuaF_auditoriaEmitidos;
                         $generarFuaA->TipoAccion = 2; /* SI ES 1 ES PARA GENERACION Y 0 PARA ANULACION Y 2 PARA ACTUALIZAR */
                         $generarFuaA->IdUsuario = $request->usuario_auditoriaEmitidos;
                         $generarFuaA->save();

                         return "ACTUALIZAR-FUA";
                         /* FALTA EXTRAER EL ID DE LA ATENCIÓN */
                    }
               }else{
                    return "ERROR";
               }
          }
     }

     public function reportesFUA($IdAtencion,Request $request){
          
          $datosFua = DB::SELECT("SELECT * FROM [ECONOMIA].[dbo].[FUA2] WHERE IdAtencion = ?",[$IdAtencion]);

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
                                       DFD.docf_item_total,DFD.docf_item_precio,DFD.diagnostico_estado,DFD.cambio_cantidad FROM INR.dbo.DocFCabecera DFC INNER JOIN INR.dbo.DocFDetalles DFD ON DFC.docf_trans = DFD.docf_trans INNER JOIN
                                       INR.dbo.Catalogo C ON DFD.catalogo_cod = C.catalogo_cod WHERE DFC.fua_nro = ?",[$datosFua[0]->Numero]);

          $datosLaboratorio = DB::SELECT("SELECT PDL.pdiario_id,PDL.pdiario_id_orden,PDL.asis_cod,PDL.hcl_num,PDL.pdiario_apat,PDL.pdiario_amat,PDL.pdiario_nomb,PDL.asis_cod AS Expre2,
                                             PDL.edad_cod,PDL.sproc_cod,PDL.pdiario_obs,CL.exa_lab_desc,CL.exa_lab_cod,PDL.fua_nro,PD.pdiario_fech,CAST(CL.exa_lab_precio_1 as decimal(12,2)) AS exa_lab_precio_1
                                             FROM INRDIS_II.dbo.PDiario_Lab PDL INNER JOIN INRDIS_II.dbo.PDiario_Lab_Examenes PDLE ON PDL.pdiario_id = PDLE.pdiario_id
                                             AND PDL.pdiario_id_orden = PDLE.pdiario_id_orden INNER JOIN INRDIS_II.dbo.tblCatalogo_Laboratorio CL ON PDLE.exa_lab_cod = CL.exa_lab_cod INNER JOIN
                                             INRDIS_II.dbo.PDiario PD ON PDL.pdiario_id = PD.pdiario_id WHERE PDL.fua_nro = ?",[$datosFua[0]->Numero]);

          $nombreResponsable = DB::SELECT("SELECT PER.id AS Profesional_id,PER.per_apat+' '+PER.per_amat+', '+PER.per_nomb AS Profesional FROM PERSONAL.dbo.personal PER  WHERE PER.id = ?
                                           ORDER BY PER.per_apat ASC,PER.per_amat ASC",[$datosFua[0]->personalAtiende_id]);

          $datosTipoPersonalSalud = DB::SELECT("SELECT STPS.descripcion AS descripcion,STPS.id FROM ECONOMIA.dbo.sisTIPOPERSONALSALUD STPS WHERE id <> 00");

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
          view()->share('datosLaboratorio',$datosLaboratorio);

          $pdf = PDF::loadView('paginas.reportesFUA',$datosFua);

          // descargar archivo PDF con método de descarga
          return $pdf->setPaper('a4','portrait')->stream('reportesFUA.pdf');
     }
}
