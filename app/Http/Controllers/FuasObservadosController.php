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
use Illuminate\Support\Facades\Hash;

class FuasObservadosController extends Controller
{
    public function index(){

                $usuario_fuasObservados = auth()->id();

                /* Si existe un requerimiento del tipo AJAX */
                if(request()->ajax()){

                    if(auth()->user()->id_rol == 1){
                         return datatables()->of(DB::SELECT("SELECT TOP (100) PERCENT CHP.fina_cod as Financiador_id,
                         CASE WHEN CHP.fina_cod = '02' THEN 'S.I.S.' WHEN CHP.fina_cod = '2 ' THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                         FORMAT(CAST(CH.cita_fech AS DATE), 'dd-MM-yyyy') + ' ' + LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) as Fecha,
                         FORMAT(CAST(CH.cita_fech AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) AS Hora,
                         P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
                         P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                         CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                         WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
                 
                         /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                         CASE 
                         WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                         WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                         ELSE 'OTRO' END AS TipoDocumento,
                         CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                         /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 
                         /* INICIO DE HISTORIA CLINICA*/
                         CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
                         /* FIN DE HISTORIA CLINICA*/
                 
                         /* INICIO DE FUA */
                         CASE WHEN CHP.fina_cod ='02' or CHP.fina_cod ='08' THEN 	FU.DISA + '-' + FU.Lote + '-' + FU.Numero END AS FUA,
                         FU.IdAtencion as Fua_id,
                         /* FIN DE FUA */
                 
                         TA.description AS TipoActividad,
                         LEFT(AE.description,20) AS ActividadEspecifica,
                         PE.id AS Personal_id,
                         PE.apPaterno +' '+ PE.apMaterno +' '+ PE.nombres AS Profesional, 
                 
                         /* INICIO DE ESTADO CITA */
                         CASE WHEN CHP.asis_cod IS NULL THEN 10 ELSE A.AsistenciaCita_id END AS EstadoCita_id,
                         CASE WHEN AC.abreviado IS NULL THEN 'CITADO'  
                              WHEN AC.id = 19 THEN 'NO ATENDIDO'
                              WHEN AC.id = 18 THEN 'NO ATENDIDO'
                              WHEN AC.id = 17 THEN 'NO ATENDIDO'
                              WHEN AC.id = 13 THEN 'NO ATENDIDO'
                              WHEN AC.id = 2 THEN 'NO ATENDIDO'
                              WHEN AC.id = 15 THEN 'NO ATENDIDO'
                              WHEN AC.id = 3  THEN 'ATENDIDO'
                              WHEN AC.id = 4  THEN 'ATENDIDO'
                              ELSE AC.abreviado END AS EstadoCita,
                    /* FIN DE ESTADO CITA */
                         /* FIN DE ESTADO CITA */
                 
                         'CITASHORA' AS Modelo,
         
                         CASE WHEN CH.modoap_cod = 1 THEN 'PRESENCIAL'
                              WHEN CH.modoap_cod = 5 THEN 'EXTRA'
                              WHEN CH.modoap_cod = 7 THEN 'NO PRESENCIAL'
                              WHEN CH.modoap_cod = 6 THEN 'H-COMPL.'
                              END AS TipoAtencion,
         
                         CASE WHEN UO.uo_desc = 'DPTO. AMPUTADOS Y QUEMADOS' THEN 'DPTO. A. Q. Y T-POSTURALES' ELSE UO.uo_desc END AS UnidadOrganica,
                         CASE WHEN CH.serv_cod = 11 THEN 'MEDICOS' ELSE 'NUTRICIONISTAS' END AS GrupoProfesional,
                         CHP.cext_id AS idRegistro,
                         NULL AS numeroSesion,
                         NULL AS idIdentificador,
                         CHP.cie_cod_dano1 AS CodigoCie,
                         CHP.diag_cod_dano1 AS Diagnostico
                 
                 FROM    [INRDIS_II].[dbo].[CitasHora] CH INNER JOIN 
                         [INRDIS_II].[dbo].[CitasHora_Paciente] CHP ON CH.cita_prof = CHP.cita_prof AND CH.cita_fech = CHP.cita_fech AND CH.cita_extra = CHP.cita_extra AND
                         CH.cita_hora = CHP.cita_hora INNER JOIN [INRDIS_II].[dbo].[tblUO] UO ON CH.uo_cod = UO.uo_cod INNER JOIN
                         [INRDIS_II].[dbo].[tblTipoCita_Citado] TCC ON CHP.tcitaa_cod = TCC.tcitaa_cod INNER JOIN 
                         [INRDIS_II].[dbo].[TipoActividad] TA ON TCC.tipoActividad_id = TA.id INNER JOIN 
                         [INRDIS_II].[dbo].[ActividadEspecifica] AE ON TCC.actividadEspecifica_id = AE.id INNER JOIN
                         [INRDIS_II].[dbo].[Persona] P ON CHP.pers_id = P.id LEFT OUTER JOIN 
                         [INRDIS_II].[dbo].[tblAsistencia] A ON CHP.asis_cod = A.asis_cod LEFT OUTER JOIN
                         [ECONOMIA].[dbo].[FUA2] FU ON CHP.FUA_id = FU.IdAtencion LEFT OUTER JOIN 
                         [INRDIS_II].[dbo].[AsistenciaCita] AC ON A.AsistenciaCita_id = AC.id LEFT JOIN
                         [INRDIS_II].[dbo].[tbPersonal] PE ON CH.cita_prof = PE.id
                 
                          
                 WHERE CAST(CH.cita_fech AS DATE) BETWEEN DATEADD(DD, -1, GETDATE()) AND DATEADD(DD, 1, GETDATE()) AND (CHP.fina_cod IN ('02','08') OR CHP.fina_cod 
                       IN ('2 ','8 ')) AND CH.serv_cod <> 41 AND FU.IdAtencion IS NOT NULL AND AE.id IN (41,42,43,44,45,46,47,48,49,50,51)
                 
                 UNION ALL
                 
                 SELECT TOP (100) PERCENT PDD.Financiador_id as Financiador_id,
                 CASE WHEN PDD.Financiador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                 FORMAT(CAST(PDC.fecha AS DATE),'dd-MM-yyyy') + ' ' + LEFT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) + ':' + 
                 RIGHT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) as Fecha,
                 FORMAT(CAST(PDC.fecha AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(PDC.horaInicia,2) + ':' + RIGHT(PDC.horaInicia,2) AS Hora,
                 P.id AS id_Paciente,P.apPaterno + ' ' + P.apMaterno + ' ' + P.nombres AS Paciente,
                 P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                 CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                 WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
                 
                 /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 CASE 
                 WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                 WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                 ELSE 'OTRO' END AS TipoDocumento,
                 CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                 /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 
                 P.hcl_num as HistoriaClinica,FU.DISA + '-' + FU.Lote + '-' + FU.Numero as FUA,
                 FU.IdAtencion as Fua_id,
                 TA.description AS TipoActividad,
                 LEFT(AE.description,20) as ActividadEspecifica,
                 PE.id AS Personal_id,
                 PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
         
                 /* INICIO DE ESTADO CITA */
                 CASE WHEN PDD.AsistenciaCita_id IS NULL THEN 10 ELSE PDD.AsistenciaCita_id END AS EstadoCita_id,
                 CASE WHEN AC.abreviado IS NULL THEN 'CITADO' 
                      WHEN AC.id = 19 THEN 'NO ATENDIDO'
                      WHEN AC.id = 18 THEN 'NO ATENDIDO'
                      WHEN AC.id = 17 THEN 'NO ATENDIDO'
                      WHEN AC.id = 13 THEN 'NO ATENDIDO'
                      WHEN AC.id = 2 THEN 'NO ATENDIDO'
                      WHEN AC.id = 15 THEN 'NO ATENDIDO'
                      WHEN AC.id = 3  THEN 'ATENDIDO'
                      WHEN AC.id = 4  THEN 'ATENDIDO'
                      ELSE AC.abreviado END AS EstadoCita,
                 /* FIN DE ESTADO CITA */
                 'PDIARIO' AS Modelo, 
         
                 CASE WHEN PDC.ModoAtencionProfesional_id = 1 THEN 'PRESENCIAL'
                 WHEN PDC.ModoAtencionProfesional_id = 5 THEN 'EXTRA'
                 WHEN PDC.ModoAtencionProfesional_id = 7 THEN 'NO PRESENCIAL'
                 WHEN PDC.ModoAtencionProfesional_id = 6 THEN 'H-COMPL.'
                 END AS TipoAtencion,
         
                 UO.description AS UnidadOrganica,
                 'MEDICOS' AS GrupoProfesional,
                 PDD.id AS idRegistro,
                 PDD.nroSesion AS numeroSesion,
                 NULL AS idIdentificador,
                 PDD.Cie1_cod AS CodigoCie,
                 NULL AS Diagnostico
                         
                 
                 FROM [INRDIS_II].[dbo].[PDiarioDetalles] PDD INNER JOIN
                      [INRDIS_II].[dbo].[PDiarioCabecera] PDC ON PDD.PDiarioCabecera_id = PDC.id INNER JOIN 
                      [INRDIS_II].[dbo].[TipoActividad] TA ON PDC.tipoActividad_id = TA.id INNER JOIN 
                      [INRDIS_II].[dbo].[ActividadEspecifica] AE ON PDD.actividadEspecifica_id = AE.id INNER JOIN
                      [INRDIS_II].[dbo].[UnidadOrganica] UO ON PDC.UnidadOrganica_id = UO.id INNER JOIN
                      [INRDIS_II].[dbo].[Persona] P ON PDD.Persona_id = P.id LEFT JOIN 
                      [INRDIS_II].[dbo].[tbPersonal] PE ON PDC.PersonalAtiende_id = PE.id LEFT JOIN
                      [INRDIS_II].[dbo].[AsistenciaCita] AC ON PDD.AsistenciaCita_id = AC.id LEFT JOIN
                      [ECONOMIA].[dbo].[FUA2] FU ON PDD.FUA_id= FU.IdAtencion
                 
                 WHERE CAST(PDC.fecha AS DATE) BETWEEN DATEADD(DD, -1, GETDATE()) AND DATEADD(DD, 1, GETDATE()) AND PDD.Financiador_id IN (2,8) AND FU.IdAtencion IS NOT NULL
                       AND AE.id IN (41,42,43,44,45,46,47,48,49,50,51)
                 
                 UNION ALL
                 
                 SELECT TOP (100) PERCENT CONVERT(smallint, SPD.tbFinanciador_id) as Financiador_id,CASE WHEN SPD.tbFinanciador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                 FORMAT(CAST(SPD.fechaProgramada AS DATE),'dd-MM-yyyy') + ' ' + LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) as Fecha,
                 FORMAT(CAST(SPD.fechaProgramada AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) AS Hora,
                 P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
                 P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                 CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                 WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
         
                 /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 CASE WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                 WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                 ELSE 'OTRO' END AS TipoDocumento,
                 CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                 /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 
                 /* INICIO DE HISTORIA CLINICA*/
                 CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
                 /* FIN DE HISTORIA CLINICA*/
         
                 /* INICIO DE FUA */
                 CASE WHEN SPD.tbFinanciador_id ='02' or SPD.tbFinanciador_id ='08' THEN FU.DISA + '-' + FU.Lote + '-' + FU.Numero END AS FUA,
                 FU.IdAtencion as Fua_id,
                 /* FIN DE FUA */
         
                 'SESION' AS TipoActividad,
                 LEFT(S.descripcion,20) AS ActividadEspecifica,
                 PE.id AS Personal_id,
                 PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
         
                 /* INICIO DE ESTADO CITA */
                 SPD.tbAsistencia_id AS EstadoCita_id,
                 CASE WHEN A.abreviatura IS NULL THEN 'CITADO' 
                      WHEN A.id = 19 THEN 'NO ATENDIDO'
                      WHEN A.id = 18 THEN 'NO ATENDIDO'
                      WHEN A.id = 17 THEN 'NO ATENDIDO'
                      WHEN A.id = 13 THEN 'NO ATENDIDO'
                      WHEN A.id = 2 THEN 'NO ATENDIDO'
                      WHEN A.id = 15 THEN 'NO ATENDIDO'
                      WHEN A.id = 3  THEN 'ATENDIDO'
                      WHEN A.id = 4  THEN 'ATENDIDO'
                      WHEN A.id = 22  THEN 'NO ATENDIDO'
                      ELSE A.abreviatura END COLLATE Modern_Spanish_CI_AS AS EstadoCita,
                 /* FIN DE ESTADO CITA */
         
                 'SERVICIODET' AS Modelo,
         
                 CASE WHEN SPD.tbModoCita_id = 1 THEN 'PRESENCIAL'
                 WHEN SPD.tbModoCita_id = 5 THEN 'EXTRA'
                 WHEN SPD.tbModoCita_id = 7 THEN 'NO PRESENCIAL'
                 WHEN SPD.tbModoCita_id = 6 THEN 'H-COMPL.'
                 END AS TipoAtencion,
         
                 UO.descripcion AS UnidadOrganica,
                 CASE WHEN GP.id = 2 THEN 'TERAPISTAS'
                      WHEN GP.id = 13 THEN 'MEDICOS'
                      WHEN GP.id = 14 THEN 'MEDICOS'
                      WHEN GP.id = 21 THEN 'MEDICOS'
                      ELSE GP.descripcion END AS GrupoProfesional,
                 SPD.id AS idRegistro,
                 SPD.nroSesion AS numeroSesion,
                 SPD.ServicioPersonaCab_id AS idIdentificador,
                 SPC.tbCIE1_codigo AS CodigoCie,
                 NULL AS Diagnostico
                 
                 FROM    [INRDIS_II].[dbo].[tbServicio] S INNER JOIN [INRDIS_II].[dbo].[tbServicio_UOrganica] SUO
                         ON S.id = SUO.tbServicio_id INNER JOIN [INRDIS_II].[dbo].[ServicioPersonaCab] SPC INNER JOIN
                         [INRDIS_II].[dbo].[ServicioPersonaDet] SPD ON SPC.id = SPD.ServicioPersonaCab_id LEFT JOIN
                         [INRDIS_II].[dbo].[tbAsistencia] A ON SPD.tbAsistencia_id = A.id INNER JOIN
                         [INRDIS_II].[dbo].[Persona] P ON SPC.Persona_id = P.id ON SUO.id = SPC.tbServicio_UOrganica_id INNER JOIN
                         [INRDIS_II].[dbo].[tbPersonal] PE ON SPD.tbPersonal_id = PE.id INNER JOIN
                         [INRDIS_II].[dbo].[tbUOrganica] UO ON SUO.tbUOrganica_id = UO.id LEFT OUTER JOIN
                         [ECONOMIA].[dbo].[FUA2] FU ON SPD.sustentoPago_id = FU.IdAtencion LEFT JOIN
                         [INRDIS_II].[dbo].[tbGrupoProfesional] GP ON SUO.tbGrupoProfesional_id = GP.id
                 
                 WHERE CAST(SPD.fechaProgramada AS DATE) BETWEEN DATEADD(DD, -1, GETDATE()) AND DATEADD(DD, 1, GETDATE()) AND SPD.tbFinanciador_id IN (2,8) AND
                           (SPD.tbEstado_id <> 8 AND SPD.tbEstado_id <> 11) AND SUO.tbGrupoProfesional_id IN (2,4,6,7,11,13,14,18,21,23)
                           AND FU.IdAtencion IS NOT NULL AND SUO.tbServicio_id IN (6,7,8,9,27,28,115)"))->make(true);
                    }else{
                         return datatables()->of(DB::SELECT("SELECT TOP (100) PERCENT CHP.fina_cod as Financiador_id,
                         CASE WHEN CHP.fina_cod = '02' THEN 'S.I.S.' WHEN CHP.fina_cod = '2 ' THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                         FORMAT(CAST(CH.cita_fech AS DATE), 'dd-MM-yyyy') + ' ' + LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) as Fecha,
                         FORMAT(CAST(CH.cita_fech AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) AS Hora,
                         P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
                         P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                         CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                         WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
                 
                         /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                         CASE 
                         WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                         WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                         ELSE 'OTRO' END AS TipoDocumento,
                         CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                         /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 
                         /* INICIO DE HISTORIA CLINICA*/
                         CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
                         /* FIN DE HISTORIA CLINICA*/
                 
                         /* INICIO DE FUA */
                         CASE WHEN CHP.fina_cod ='02' or CHP.fina_cod ='08' THEN 	FU.DISA + '-' + FU.Lote + '-' + FU.Numero END AS FUA,
                         FU.IdAtencion as Fua_id,
                         /* FIN DE FUA */
                 
                         TA.description AS TipoActividad,
                         LEFT(AE.description,20) AS ActividadEspecifica,
                         PE.id AS Personal_id,
                         PE.apPaterno +' '+ PE.apMaterno +' '+ PE.nombres AS Profesional, 
                 
                         /* INICIO DE ESTADO CITA */
                         CASE WHEN CHP.asis_cod IS NULL THEN 10 ELSE A.AsistenciaCita_id END AS EstadoCita_id,
                         CASE WHEN AC.abreviado IS NULL THEN 'CITADO'  
                              WHEN AC.id = 19 THEN 'NO ATENDIDO'
                              WHEN AC.id = 18 THEN 'NO ATENDIDO'
                              WHEN AC.id = 17 THEN 'NO ATENDIDO'
                              WHEN AC.id = 13 THEN 'NO ATENDIDO'
                              WHEN AC.id = 2 THEN 'NO ATENDIDO'
                              WHEN AC.id = 15 THEN 'NO ATENDIDO'
                              WHEN AC.id = 3  THEN 'ATENDIDO'
                              WHEN AC.id = 4  THEN 'ATENDIDO'
                              ELSE AC.abreviado END AS EstadoCita,
                    /* FIN DE ESTADO CITA */
                         /* FIN DE ESTADO CITA */
                 
                         'CITASHORA' AS Modelo,
         
                         CASE WHEN CH.modoap_cod = 1 THEN 'PRESENCIAL'
                              WHEN CH.modoap_cod = 5 THEN 'EXTRA'
                              WHEN CH.modoap_cod = 7 THEN 'NO PRESENCIAL'
                              WHEN CH.modoap_cod = 6 THEN 'H-COMPL.'
                              END AS TipoAtencion,
         
                         CASE WHEN UO.uo_desc = 'DPTO. AMPUTADOS Y QUEMADOS' THEN 'DPTO. A. Q. Y T-POSTURALES' ELSE UO.uo_desc END AS UnidadOrganica,
                         CASE WHEN CH.serv_cod = 11 THEN 'MEDICOS' ELSE 'NUTRICIONISTAS' END AS GrupoProfesional,
                         CHP.cext_id AS idRegistro,
                         NULL AS numeroSesion,
                         NULL AS idIdentificador,
                         CHP.cie_cod_dano1 AS CodigoCie,
                         CHP.diag_cod_dano1 AS Diagnostico
                 
                 FROM    [INRDIS_II].[dbo].[CitasHora] CH INNER JOIN 
                         [INRDIS_II].[dbo].[CitasHora_Paciente] CHP ON CH.cita_prof = CHP.cita_prof AND CH.cita_fech = CHP.cita_fech AND CH.cita_extra = CHP.cita_extra AND
                         CH.cita_hora = CHP.cita_hora INNER JOIN [INRDIS_II].[dbo].[tblUO] UO ON CH.uo_cod = UO.uo_cod INNER JOIN
                         [INRDIS_II].[dbo].[tblTipoCita_Citado] TCC ON CHP.tcitaa_cod = TCC.tcitaa_cod INNER JOIN 
                         [INRDIS_II].[dbo].[TipoActividad] TA ON TCC.tipoActividad_id = TA.id INNER JOIN 
                         [INRDIS_II].[dbo].[ActividadEspecifica] AE ON TCC.actividadEspecifica_id = AE.id INNER JOIN
                         [INRDIS_II].[dbo].[Persona] P ON CHP.pers_id = P.id LEFT OUTER JOIN 
                         [INRDIS_II].[dbo].[tblAsistencia] A ON CHP.asis_cod = A.asis_cod LEFT OUTER JOIN
                         [ECONOMIA].[dbo].[FUA2] FU ON CHP.FUA_id = FU.IdAtencion LEFT OUTER JOIN 
                         [INRDIS_II].[dbo].[AsistenciaCita] AC ON A.AsistenciaCita_id = AC.id LEFT JOIN
                         [INRDIS_II].[dbo].[tbPersonal] PE ON CH.cita_prof = PE.id
                 
                          
                 WHERE CAST(CH.cita_fech AS DATE) BETWEEN DATEADD(DD, -1, GETDATE()) AND DATEADD(DD, 1, GETDATE()) AND (CHP.fina_cod IN ('02','08') OR CHP.fina_cod 
                       IN ('2 ','8 ')) AND CH.serv_cod <> 41 AND FU.IdAtencion IS NOT NULL AND AE.id IN (41,42,43,44,45,46,47,48,49,50,51) AND CHP.id_usuario = :IdUsuario1
                 
                 UNION ALL
                 
                 SELECT TOP (100) PERCENT PDD.Financiador_id as Financiador_id,
                 CASE WHEN PDD.Financiador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                 FORMAT(CAST(PDC.fecha AS DATE),'dd-MM-yyyy') + ' ' + LEFT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) + ':' + 
                 RIGHT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) as Fecha,
                 FORMAT(CAST(PDC.fecha AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(PDC.horaInicia,2) + ':' + RIGHT(PDC.horaInicia,2) AS Hora,
                 P.id AS id_Paciente,P.apPaterno + ' ' + P.apMaterno + ' ' + P.nombres AS Paciente,
                 P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                 CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                 WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
                 
                 /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 CASE 
                 WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                 WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                 ELSE 'OTRO' END AS TipoDocumento,
                 CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                 /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 
                 P.hcl_num as HistoriaClinica,FU.DISA + '-' + FU.Lote + '-' + FU.Numero as FUA,
                 FU.IdAtencion as Fua_id,
                 TA.description AS TipoActividad,
                 LEFT(AE.description,20) as ActividadEspecifica,
                 PE.id AS Personal_id,
                 PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
         
                 /* INICIO DE ESTADO CITA */
                 CASE WHEN PDD.AsistenciaCita_id IS NULL THEN 10 ELSE PDD.AsistenciaCita_id END AS EstadoCita_id,
                 CASE WHEN AC.abreviado IS NULL THEN 'CITADO' 
                      WHEN AC.id = 19 THEN 'NO ATENDIDO'
                      WHEN AC.id = 18 THEN 'NO ATENDIDO'
                      WHEN AC.id = 17 THEN 'NO ATENDIDO'
                      WHEN AC.id = 13 THEN 'NO ATENDIDO'
                      WHEN AC.id = 2 THEN 'NO ATENDIDO'
                      WHEN AC.id = 15 THEN 'NO ATENDIDO'
                      WHEN AC.id = 3  THEN 'ATENDIDO'
                      WHEN AC.id = 4  THEN 'ATENDIDO'
                      ELSE AC.abreviado END AS EstadoCita,
                 /* FIN DE ESTADO CITA */
                 'PDIARIO' AS Modelo, 
         
                 CASE WHEN PDC.ModoAtencionProfesional_id = 1 THEN 'PRESENCIAL'
                 WHEN PDC.ModoAtencionProfesional_id = 5 THEN 'EXTRA'
                 WHEN PDC.ModoAtencionProfesional_id = 7 THEN 'NO PRESENCIAL'
                 WHEN PDC.ModoAtencionProfesional_id = 6 THEN 'H-COMPL.'
                 END AS TipoAtencion,
         
                 UO.description AS UnidadOrganica,
                 'MEDICOS' AS GrupoProfesional,
                 PDD.id AS idRegistro,
                 PDD.nroSesion AS numeroSesion,
                 NULL AS idIdentificador,
                 PDD.Cie1_cod AS CodigoCie,
                 NULL AS Diagnostico
                         
                 
                 FROM [INRDIS_II].[dbo].[PDiarioDetalles] PDD INNER JOIN
                      [INRDIS_II].[dbo].[PDiarioCabecera] PDC ON PDD.PDiarioCabecera_id = PDC.id INNER JOIN 
                      [INRDIS_II].[dbo].[TipoActividad] TA ON PDC.tipoActividad_id = TA.id INNER JOIN 
                      [INRDIS_II].[dbo].[ActividadEspecifica] AE ON PDD.actividadEspecifica_id = AE.id INNER JOIN
                      [INRDIS_II].[dbo].[UnidadOrganica] UO ON PDC.UnidadOrganica_id = UO.id INNER JOIN
                      [INRDIS_II].[dbo].[Persona] P ON PDD.Persona_id = P.id LEFT JOIN 
                      [INRDIS_II].[dbo].[tbPersonal] PE ON PDC.PersonalAtiende_id = PE.id LEFT JOIN
                      [INRDIS_II].[dbo].[AsistenciaCita] AC ON PDD.AsistenciaCita_id = AC.id LEFT JOIN
                      [ECONOMIA].[dbo].[FUA2] FU ON PDD.FUA_id= FU.IdAtencion
                 
                 WHERE CAST(PDC.fecha AS DATE) BETWEEN DATEADD(DD, -1, GETDATE()) AND DATEADD(DD, 1, GETDATE()) AND PDD.Financiador_id IN (2,8) AND FU.IdAtencion IS NOT NULL
                       AND AE.id IN (41,42,43,44,45,46,47,48,49,50,51) AND PDD.id_usuario = :IdUsuario2
                 
                 UNION ALL
                 
                 SELECT TOP (100) PERCENT CONVERT(smallint, SPD.tbFinanciador_id) as Financiador_id,CASE WHEN SPD.tbFinanciador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                 FORMAT(CAST(SPD.fechaProgramada AS DATE),'dd-MM-yyyy') + ' ' + LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) as Fecha,
                 FORMAT(CAST(SPD.fechaProgramada AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) AS Hora,
                 P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
                 P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                 CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                 WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
         
                 /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 CASE WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                 WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                 ELSE 'OTRO' END AS TipoDocumento,
                 CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                 /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 
                 /* INICIO DE HISTORIA CLINICA*/
                 CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
                 /* FIN DE HISTORIA CLINICA*/
         
                 /* INICIO DE FUA */
                 CASE WHEN SPD.tbFinanciador_id ='02' or SPD.tbFinanciador_id ='08' THEN FU.DISA + '-' + FU.Lote + '-' + FU.Numero END AS FUA,
                 FU.IdAtencion as Fua_id,
                 /* FIN DE FUA */
         
                 'SESION' AS TipoActividad,
                 LEFT(S.descripcion,20) AS ActividadEspecifica,
                 PE.id AS Personal_id,
                 PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
         
                 /* INICIO DE ESTADO CITA */
                 SPD.tbAsistencia_id AS EstadoCita_id,
                 CASE WHEN A.abreviatura IS NULL THEN 'CITADO' 
                      WHEN A.id = 19 THEN 'NO ATENDIDO'
                      WHEN A.id = 18 THEN 'NO ATENDIDO'
                      WHEN A.id = 17 THEN 'NO ATENDIDO'
                      WHEN A.id = 13 THEN 'NO ATENDIDO'
                      WHEN A.id = 2 THEN 'NO ATENDIDO'
                      WHEN A.id = 15 THEN 'NO ATENDIDO'
                      WHEN A.id = 3  THEN 'ATENDIDO'
                      WHEN A.id = 4  THEN 'ATENDIDO'
                      WHEN A.id = 22  THEN 'NO ATENDIDO'
                      ELSE A.abreviatura END COLLATE Modern_Spanish_CI_AS AS EstadoCita,
                 /* FIN DE ESTADO CITA */
         
                 'SERVICIODET' AS Modelo,
         
                 CASE WHEN SPD.tbModoCita_id = 1 THEN 'PRESENCIAL'
                 WHEN SPD.tbModoCita_id = 5 THEN 'EXTRA'
                 WHEN SPD.tbModoCita_id = 7 THEN 'NO PRESENCIAL'
                 WHEN SPD.tbModoCita_id = 6 THEN 'H-COMPL.'
                 END AS TipoAtencion,
         
                 UO.descripcion AS UnidadOrganica,
                 CASE WHEN GP.id = 2 THEN 'TERAPISTAS'
                      WHEN GP.id = 13 THEN 'MEDICOS'
                      WHEN GP.id = 14 THEN 'MEDICOS'
                      WHEN GP.id = 21 THEN 'MEDICOS'
                      ELSE GP.descripcion END AS GrupoProfesional,
                 SPD.id AS idRegistro,
                 SPD.nroSesion AS numeroSesion,
                 SPD.ServicioPersonaCab_id AS idIdentificador,
                 SPC.tbCIE1_codigo AS CodigoCie,
                 NULL AS Diagnostico
                 
                 FROM    [INRDIS_II].[dbo].[tbServicio] S INNER JOIN [INRDIS_II].[dbo].[tbServicio_UOrganica] SUO
                         ON S.id = SUO.tbServicio_id INNER JOIN [INRDIS_II].[dbo].[ServicioPersonaCab] SPC INNER JOIN
                         [INRDIS_II].[dbo].[ServicioPersonaDet] SPD ON SPC.id = SPD.ServicioPersonaCab_id LEFT JOIN
                         [INRDIS_II].[dbo].[tbAsistencia] A ON SPD.tbAsistencia_id = A.id INNER JOIN
                         [INRDIS_II].[dbo].[Persona] P ON SPC.Persona_id = P.id ON SUO.id = SPC.tbServicio_UOrganica_id INNER JOIN
                         [INRDIS_II].[dbo].[tbPersonal] PE ON SPD.tbPersonal_id = PE.id INNER JOIN
                         [INRDIS_II].[dbo].[tbUOrganica] UO ON SUO.tbUOrganica_id = UO.id LEFT OUTER JOIN
                         [ECONOMIA].[dbo].[FUA2] FU ON SPD.sustentoPago_id = FU.IdAtencion LEFT JOIN
                         [INRDIS_II].[dbo].[tbGrupoProfesional] GP ON SUO.tbGrupoProfesional_id = GP.id
                 
                 WHERE CAST(SPD.fechaProgramada AS DATE) BETWEEN DATEADD(DD, -1, GETDATE()) AND DATEADD(DD, 1, GETDATE()) AND SPD.tbFinanciador_id IN (2,8) AND
                           (SPD.tbEstado_id <> 8 AND SPD.tbEstado_id <> 11) AND SUO.tbGrupoProfesional_id IN (2,4,6,7,11,13,14,18,21,23)
                           AND FU.IdAtencion IS NOT NULL AND SUO.tbServicio_id IN (6,7,8,9,27,28,115) AND SPD.id_usuario = :IdUsuario3",
                           ["IdUsuario1"=>$usuario_fuasObservados,"IdUsuario2"=>$usuario_fuasObservados,"IdUsuario3"=>$usuario_fuasObservados]))->make(true);
                    }





                    
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

        return view('paginas.fuasObservados',array("administradores"=>$administradores,"estados"=>$estados,"concPrestacional"=>$concPrestacional,
                    "destinoAsegurado"=>$destinoAsegurado,"codPrestacional"=>$codPrestacional,
                    "componente"=>$componente,"personalAtiende"=>$personalAtiende,"lugarAtencion"=>$lugarAtencion,
                    "tipoAtencion"=>$tipoAtencion,"datosPersonalC"=>$datosPersonalC,
                    "sisTipoPersonalSalud"=>$sisTipoPersonalSalud,"sisEgresado"=>$sisEgresado,
                    "sisEspecialidad"=>$sisEspecialidad));

    }

     public function buscarPorMes(Request $request){

          $usuario_fuasObservados = auth()->id();

          $datos = array("fechaInicio_pacientesCitados"=>$request->input("fechaInicio_pacientesCitados"),
                         "fechaFin_pacientesCitados"=>$request->input("fechaFin_pacientesCitados"));

               /* Si existe un requerimiento del tipo AJAX */
               if(request()->ajax()){

                    if(auth()->user()->id_rol == 1){
                         return datatables()->of(DB::SELECT("SELECT TOP (100) PERCENT CHP.fina_cod as Financiador_id,
                         CASE WHEN CHP.fina_cod = '02' THEN 'S.I.S.' WHEN CHP.fina_cod = '2 ' THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                         FORMAT(CAST(CH.cita_fech AS DATE), 'dd-MM-yyyy') + ' ' + LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) as Fecha,
                         FORMAT(CAST(CH.cita_fech AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) AS Hora,
                         P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
                         P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                         CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                         WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
                 
                         /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                         CASE 
                         WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                         WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                         ELSE 'OTRO' END AS TipoDocumento,
                         CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                         /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 
                         /* INICIO DE HISTORIA CLINICA*/
                         CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
                         /* FIN DE HISTORIA CLINICA*/
                 
                         /* INICIO DE FUA */
                         CASE WHEN CHP.fina_cod ='02' or CHP.fina_cod ='08' THEN 	FU.DISA + '-' + FU.Lote + '-' + FU.Numero END AS FUA,
                         FU.IdAtencion as Fua_id,
                         /* FIN DE FUA */
                 
                         TA.description AS TipoActividad,
                         LEFT(AE.description,20) AS ActividadEspecifica,
                         PE.id AS Personal_id,
                         PE.apPaterno +' '+ PE.apMaterno +' '+ PE.nombres AS Profesional, 
                 
                         /* INICIO DE ESTADO CITA */
                         CASE WHEN CHP.asis_cod IS NULL THEN 10 ELSE A.AsistenciaCita_id END AS EstadoCita_id,
                         CASE WHEN AC.abreviado IS NULL THEN 'CITADO'  
                              WHEN AC.id = 19 THEN 'NO ATENDIDO'
                              WHEN AC.id = 18 THEN 'NO ATENDIDO'
                              WHEN AC.id = 17 THEN 'NO ATENDIDO'
                              WHEN AC.id = 13 THEN 'NO ATENDIDO'
                              WHEN AC.id = 2 THEN 'NO ATENDIDO'
                              WHEN AC.id = 15 THEN 'NO ATENDIDO'
                              WHEN AC.id = 3  THEN 'ATENDIDO'
                              WHEN AC.id = 4  THEN 'ATENDIDO'
                              ELSE AC.abreviado END AS EstadoCita,
                    /* FIN DE ESTADO CITA */
                         /* FIN DE ESTADO CITA */
                 
                         'CITASHORA' AS Modelo,
         
                         CASE WHEN CH.modoap_cod = 1 THEN 'PRESENCIAL'
                              WHEN CH.modoap_cod = 5 THEN 'EXTRA'
                              WHEN CH.modoap_cod = 7 THEN 'NO PRESENCIAL'
                              WHEN CH.modoap_cod = 6 THEN 'H-COMPL.'
                              END AS TipoAtencion,
         
                         CASE WHEN UO.uo_desc = 'DPTO. AMPUTADOS Y QUEMADOS' THEN 'DPTO. A. Q. Y T-POSTURALES' ELSE UO.uo_desc END AS UnidadOrganica,
                         CASE WHEN CH.serv_cod = 11 THEN 'MEDICOS' ELSE 'NUTRICIONISTAS' END AS GrupoProfesional,
                         CHP.cext_id AS idRegistro,
                         NULL AS numeroSesion,
                         NULL AS idIdentificador,
                         CHP.cie_cod_dano1 AS CodigoCie,
                         CHP.diag_cod_dano1 AS Diagnostico
                 
                 FROM    [INRDIS_II].[dbo].[CitasHora] CH INNER JOIN 
                         [INRDIS_II].[dbo].[CitasHora_Paciente] CHP ON CH.cita_prof = CHP.cita_prof AND CH.cita_fech = CHP.cita_fech AND CH.cita_extra = CHP.cita_extra AND
                         CH.cita_hora = CHP.cita_hora INNER JOIN [INRDIS_II].[dbo].[tblUO] UO ON CH.uo_cod = UO.uo_cod INNER JOIN
                         [INRDIS_II].[dbo].[tblTipoCita_Citado] TCC ON CHP.tcitaa_cod = TCC.tcitaa_cod INNER JOIN 
                         [INRDIS_II].[dbo].[TipoActividad] TA ON TCC.tipoActividad_id = TA.id INNER JOIN 
                         [INRDIS_II].[dbo].[ActividadEspecifica] AE ON TCC.actividadEspecifica_id = AE.id INNER JOIN
                         [INRDIS_II].[dbo].[Persona] P ON CHP.pers_id = P.id LEFT OUTER JOIN 
                         [INRDIS_II].[dbo].[tblAsistencia] A ON CHP.asis_cod = A.asis_cod LEFT OUTER JOIN
                         [ECONOMIA].[dbo].[FUA2] FU ON CHP.FUA_id = FU.IdAtencion LEFT OUTER JOIN 
                         [INRDIS_II].[dbo].[AsistenciaCita] AC ON A.AsistenciaCita_id = AC.id LEFT JOIN
                         [INRDIS_II].[dbo].[tbPersonal] PE ON CH.cita_prof = PE.id
                 
                          
                 WHERE CAST(CH.cita_fech AS DATE) BETWEEN :fechaInicial1 AND :fechaFinal1 AND (CHP.fina_cod IN ('02','08') OR CHP.fina_cod 
                       IN ('2 ','8 ')) AND CH.serv_cod <> 41 AND FU.IdAtencion IS NOT NULL AND AE.id IN (41,42,43,44,45,46,47,48,49,50,51)
                 
                 UNION ALL
                 
                 SELECT TOP (100) PERCENT PDD.Financiador_id as Financiador_id,
                 CASE WHEN PDD.Financiador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                 FORMAT(CAST(PDC.fecha AS DATE),'dd-MM-yyyy') + ' ' + LEFT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) + ':' + 
                 RIGHT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) as Fecha,
                 FORMAT(CAST(PDC.fecha AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(PDC.horaInicia,2) + ':' + RIGHT(PDC.horaInicia,2) AS Hora,
                 P.id AS id_Paciente,P.apPaterno + ' ' + P.apMaterno + ' ' + P.nombres AS Paciente,
                 P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                 CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                 WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
                 
                 /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 CASE 
                 WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                 WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                 ELSE 'OTRO' END AS TipoDocumento,
                 CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                 /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 
                 P.hcl_num as HistoriaClinica,FU.DISA + '-' + FU.Lote + '-' + FU.Numero as FUA,
                 FU.IdAtencion as Fua_id,
                 TA.description AS TipoActividad,
                 LEFT(AE.description,20) as ActividadEspecifica,
                 PE.id AS Personal_id,
                 PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
         
                 /* INICIO DE ESTADO CITA */
                 CASE WHEN PDD.AsistenciaCita_id IS NULL THEN 10 ELSE PDD.AsistenciaCita_id END AS EstadoCita_id,
                 CASE WHEN AC.abreviado IS NULL THEN 'CITADO' 
                      WHEN AC.id = 19 THEN 'NO ATENDIDO'
                      WHEN AC.id = 18 THEN 'NO ATENDIDO'
                      WHEN AC.id = 17 THEN 'NO ATENDIDO'
                      WHEN AC.id = 13 THEN 'NO ATENDIDO'
                      WHEN AC.id = 2 THEN 'NO ATENDIDO'
                      WHEN AC.id = 15 THEN 'NO ATENDIDO'
                      WHEN AC.id = 3  THEN 'ATENDIDO'
                      WHEN AC.id = 4  THEN 'ATENDIDO'
                      ELSE AC.abreviado END AS EstadoCita,
                 /* FIN DE ESTADO CITA */
                 'PDIARIO' AS Modelo, 
         
                 CASE WHEN PDC.ModoAtencionProfesional_id = 1 THEN 'PRESENCIAL'
                 WHEN PDC.ModoAtencionProfesional_id = 5 THEN 'EXTRA'
                 WHEN PDC.ModoAtencionProfesional_id = 7 THEN 'NO PRESENCIAL'
                 WHEN PDC.ModoAtencionProfesional_id = 6 THEN 'H-COMPL.'
                 END AS TipoAtencion,
         
                 UO.description AS UnidadOrganica,
                 'MEDICOS' AS GrupoProfesional,
                 PDD.id AS idRegistro,
                 PDD.nroSesion AS numeroSesion,
                 NULL AS idIdentificador,
                 PDD.Cie1_cod AS CodigoCie,
                 NULL AS Diagnostico
                         
                 
                 FROM [INRDIS_II].[dbo].[PDiarioDetalles] PDD INNER JOIN
                      [INRDIS_II].[dbo].[PDiarioCabecera] PDC ON PDD.PDiarioCabecera_id = PDC.id INNER JOIN 
                      [INRDIS_II].[dbo].[TipoActividad] TA ON PDC.tipoActividad_id = TA.id INNER JOIN 
                      [INRDIS_II].[dbo].[ActividadEspecifica] AE ON PDD.actividadEspecifica_id = AE.id INNER JOIN
                      [INRDIS_II].[dbo].[UnidadOrganica] UO ON PDC.UnidadOrganica_id = UO.id INNER JOIN
                      [INRDIS_II].[dbo].[Persona] P ON PDD.Persona_id = P.id LEFT JOIN 
                      [INRDIS_II].[dbo].[tbPersonal] PE ON PDC.PersonalAtiende_id = PE.id LEFT JOIN
                      [INRDIS_II].[dbo].[AsistenciaCita] AC ON PDD.AsistenciaCita_id = AC.id LEFT JOIN
                      [ECONOMIA].[dbo].[FUA2] FU ON PDD.FUA_id= FU.IdAtencion
                 
                 WHERE CAST(PDC.fecha AS DATE) BETWEEN :fechaInicial2 AND :fechaFinal2 AND PDD.Financiador_id IN (2,8) AND FU.IdAtencion IS NOT NULL
                       AND AE.id IN (41,42,43,44,45,46,47,48,49,50,51)
                 
                 UNION ALL
                 
                 SELECT TOP (100) PERCENT SPD.tbFinanciador_id as Financiador_id,CASE WHEN SPD.tbFinanciador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                 FORMAT(CAST(SPD.fechaProgramada AS DATE),'dd-MM-yyyy') + ' ' + LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) as Fecha,
                 FORMAT(CAST(SPD.fechaProgramada AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) AS Hora,
                 P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
                 P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                 CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                 WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
         
                 /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 CASE WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                 WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                 ELSE 'OTRO' END AS TipoDocumento,
                 CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                 /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 
                 /* INICIO DE HISTORIA CLINICA*/
                 CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
                 /* FIN DE HISTORIA CLINICA*/
         
                 /* INICIO DE FUA */
                 CASE WHEN SPD.tbFinanciador_id ='02' or SPD.tbFinanciador_id ='08' THEN FU.DISA + '-' + FU.Lote + '-' + FU.Numero END AS FUA,
                 FU.IdAtencion as Fua_id,
                 /* FIN DE FUA */
         
                 'SESION' AS TipoActividad,
                 LEFT(S.descripcion,20) AS ActividadEspecifica,
                 PE.id AS Personal_id,
                 PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
         
                 /* INICIO DE ESTADO CITA */
                 SPD.tbAsistencia_id AS EstadoCita_id,
                 CASE WHEN A.abreviatura IS NULL THEN 'CITADO' 
                         WHEN A.id = 19 THEN 'NO ATENDIDO'
                         WHEN A.id = 18 THEN 'NO ATENDIDO'
                         WHEN A.id = 17 THEN 'NO ATENDIDO'
                         WHEN A.id = 13 THEN 'NO ATENDIDO'
                         WHEN A.id = 2 THEN 'NO ATENDIDO'
                         WHEN A.id = 15 THEN 'NO ATENDIDO'
                         WHEN A.id = 3  THEN 'ATENDIDO'
                         WHEN A.id = 4  THEN 'ATENDIDO'
                         WHEN A.id = 22  THEN 'NO ATENDIDO'
                         ELSE A.abreviatura END COLLATE Modern_Spanish_CI_AS AS EstadoCita,
                 /* FIN DE ESTADO CITA */
         
                 'SERVICIODET' AS Modelo,
         
                 CASE WHEN SPD.tbModoCita_id = 1 THEN 'PRESENCIAL'
                 WHEN SPD.tbModoCita_id = 5 THEN 'EXTRA'
                 WHEN SPD.tbModoCita_id = 7 THEN 'NO PRESENCIAL'
                 WHEN SPD.tbModoCita_id = 6 THEN 'H-COMPL.'
                 END AS TipoAtencion,
         
                 UO.descripcion AS UnidadOrganica,
                 CASE WHEN GP.id = 2 THEN 'TERAPISTAS'
                      WHEN GP.id = 13 THEN 'MEDICOS'
                      WHEN GP.id = 14 THEN 'MEDICOS'
                      WHEN GP.id = 21 THEN 'MEDICOS'
                      ELSE GP.descripcion END AS GrupoProfesional,
                 SPD.id AS idRegistro,
                 SPD.nroSesion AS numeroSesion,
                 SPD.ServicioPersonaCab_id AS idIdentificador,
                 SPC.tbCIE1_codigo AS CodigoCie,
                 NULL AS Diagnostico
                 
                 FROM    [INRDIS_II].[dbo].[tbServicio] S INNER JOIN [INRDIS_II].[dbo].[tbServicio_UOrganica] SUO
                         ON S.id = SUO.tbServicio_id INNER JOIN [INRDIS_II].[dbo].[ServicioPersonaCab] SPC INNER JOIN
                         [INRDIS_II].[dbo].[ServicioPersonaDet] SPD ON SPC.id = SPD.ServicioPersonaCab_id LEFT JOIN
                         [INRDIS_II].[dbo].[tbAsistencia] A ON SPD.tbAsistencia_id = A.id INNER JOIN
                         [INRDIS_II].[dbo].[Persona] P ON SPC.Persona_id = P.id ON SUO.id = SPC.tbServicio_UOrganica_id INNER JOIN
                         [INRDIS_II].[dbo].[tbPersonal] PE ON SPD.tbPersonal_id = PE.id INNER JOIN
                         [INRDIS_II].[dbo].[tbUOrganica] UO ON SUO.tbUOrganica_id = UO.id LEFT OUTER JOIN
                         [ECONOMIA].[dbo].[FUA2] FU ON SPD.sustentoPago_id = FU.IdAtencion LEFT JOIN
                         [INRDIS_II].[dbo].[tbGrupoProfesional] GP ON SUO.tbGrupoProfesional_id = GP.id
                 
                 WHERE CAST(SPD.fechaProgramada AS DATE) BETWEEN :fechaInicial3 AND :fechaFinal3 AND SPD.tbFinanciador_id IN (2,8) AND
                           (SPD.tbEstado_id <> 8 AND SPD.tbEstado_id <> 11) AND SUO.tbGrupoProfesional_id IN (2,4,6,7,11,13,14,18,21,23)
                           AND FU.IdAtencion IS NOT NULL AND SUO.tbServicio_id IN (6,7,8,9,27,28,115)
                 
                 ORDER BY Fecha,Hora ASC",["fechaInicial1"=>$request->input('fechaInicio_pacientesCitados'),
                                           "fechaFinal1"=>$request->input('fechaFin_pacientesCitados'),
                                           "fechaInicial2"=>$request->input('fechaInicio_pacientesCitados'),
                                           "fechaFinal2"=>$request->input('fechaFin_pacientesCitados'),
                                           "fechaInicial3"=>$request->input('fechaInicio_pacientesCitados'),
                                           "fechaFinal3"=>$request->input('fechaFin_pacientesCitados')]))->make(true);
                    }else{
                         return datatables()->of(DB::SELECT("SELECT TOP (100) PERCENT CHP.fina_cod as Financiador_id,
                         CASE WHEN CHP.fina_cod = '02' THEN 'S.I.S.' WHEN CHP.fina_cod = '2 ' THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                         FORMAT(CAST(CH.cita_fech AS DATE), 'dd-MM-yyyy') + ' ' + LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) as Fecha,
                         FORMAT(CAST(CH.cita_fech AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) AS Hora,
                         P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
                         P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                         CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                         WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
                 
                         /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                         CASE 
                         WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                         WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                         ELSE 'OTRO' END AS TipoDocumento,
                         CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                         /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 
                         /* INICIO DE HISTORIA CLINICA*/
                         CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
                         /* FIN DE HISTORIA CLINICA*/
                 
                         /* INICIO DE FUA */
                         CASE WHEN CHP.fina_cod ='02' or CHP.fina_cod ='08' THEN 	FU.DISA + '-' + FU.Lote + '-' + FU.Numero END AS FUA,
                         FU.IdAtencion as Fua_id,
                         /* FIN DE FUA */
                 
                         TA.description AS TipoActividad,
                         LEFT(AE.description,20) AS ActividadEspecifica,
                         PE.id AS Personal_id,
                         PE.apPaterno +' '+ PE.apMaterno +' '+ PE.nombres AS Profesional, 
                 
                         /* INICIO DE ESTADO CITA */
                         CASE WHEN CHP.asis_cod IS NULL THEN 10 ELSE A.AsistenciaCita_id END AS EstadoCita_id,
                         CASE WHEN AC.abreviado IS NULL THEN 'CITADO'  
                              WHEN AC.id = 19 THEN 'NO ATENDIDO'
                              WHEN AC.id = 18 THEN 'NO ATENDIDO'
                              WHEN AC.id = 17 THEN 'NO ATENDIDO'
                              WHEN AC.id = 13 THEN 'NO ATENDIDO'
                              WHEN AC.id = 2 THEN 'NO ATENDIDO'
                              WHEN AC.id = 15 THEN 'NO ATENDIDO'
                              WHEN AC.id = 3  THEN 'ATENDIDO'
                              WHEN AC.id = 4  THEN 'ATENDIDO'
                              ELSE AC.abreviado END AS EstadoCita,
                    /* FIN DE ESTADO CITA */
                         /* FIN DE ESTADO CITA */
                 
                         'CITASHORA' AS Modelo,
         
                         CASE WHEN CH.modoap_cod = 1 THEN 'PRESENCIAL'
                              WHEN CH.modoap_cod = 5 THEN 'EXTRA'
                              WHEN CH.modoap_cod = 7 THEN 'NO PRESENCIAL'
                              WHEN CH.modoap_cod = 6 THEN 'H-COMPL.'
                              END AS TipoAtencion,
         
                         CASE WHEN UO.uo_desc = 'DPTO. AMPUTADOS Y QUEMADOS' THEN 'DPTO. A. Q. Y T-POSTURALES' ELSE UO.uo_desc END AS UnidadOrganica,
                         CASE WHEN CH.serv_cod = 11 THEN 'MEDICOS' ELSE 'NUTRICIONISTAS' END AS GrupoProfesional,
                         CHP.cext_id AS idRegistro,
                         NULL AS numeroSesion,
                         NULL AS idIdentificador,
                         CHP.cie_cod_dano1 AS CodigoCie,
                         CHP.diag_cod_dano1 AS Diagnostico
                 
                 FROM    [INRDIS_II].[dbo].[CitasHora] CH INNER JOIN 
                         [INRDIS_II].[dbo].[CitasHora_Paciente] CHP ON CH.cita_prof = CHP.cita_prof AND CH.cita_fech = CHP.cita_fech AND CH.cita_extra = CHP.cita_extra AND
                         CH.cita_hora = CHP.cita_hora INNER JOIN [INRDIS_II].[dbo].[tblUO] UO ON CH.uo_cod = UO.uo_cod INNER JOIN
                         [INRDIS_II].[dbo].[tblTipoCita_Citado] TCC ON CHP.tcitaa_cod = TCC.tcitaa_cod INNER JOIN 
                         [INRDIS_II].[dbo].[TipoActividad] TA ON TCC.tipoActividad_id = TA.id INNER JOIN 
                         [INRDIS_II].[dbo].[ActividadEspecifica] AE ON TCC.actividadEspecifica_id = AE.id INNER JOIN
                         [INRDIS_II].[dbo].[Persona] P ON CHP.pers_id = P.id LEFT OUTER JOIN 
                         [INRDIS_II].[dbo].[tblAsistencia] A ON CHP.asis_cod = A.asis_cod LEFT OUTER JOIN
                         [ECONOMIA].[dbo].[FUA2] FU ON CHP.FUA_id = FU.IdAtencion LEFT OUTER JOIN 
                         [INRDIS_II].[dbo].[AsistenciaCita] AC ON A.AsistenciaCita_id = AC.id LEFT JOIN
                         [INRDIS_II].[dbo].[tbPersonal] PE ON CH.cita_prof = PE.id
                 
                          
                 WHERE CAST(CH.cita_fech AS DATE) BETWEEN :fechaInicial1 AND :fechaFinal1 AND (CHP.fina_cod IN ('02','08') OR CHP.fina_cod 
                       IN ('2 ','8 ')) AND CH.serv_cod <> 41 AND FU.IdAtencion IS NOT NULL AND AE.id IN (41,42,43,44,45,46,47,48,49,50,51)
                       AND CHP.id_usuario = :IdUsuario1
                 
                 UNION ALL
                 
                 SELECT TOP (100) PERCENT PDD.Financiador_id as Financiador_id,
                 CASE WHEN PDD.Financiador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                 FORMAT(CAST(PDC.fecha AS DATE),'dd-MM-yyyy') + ' ' + LEFT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) + ':' + 
                 RIGHT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) as Fecha,
                 FORMAT(CAST(PDC.fecha AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(PDC.horaInicia,2) + ':' + RIGHT(PDC.horaInicia,2) AS Hora,
                 P.id AS id_Paciente,P.apPaterno + ' ' + P.apMaterno + ' ' + P.nombres AS Paciente,
                 P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                 CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                 WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
                 
                 /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 CASE 
                 WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                 WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                 ELSE 'OTRO' END AS TipoDocumento,
                 CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                 /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 
                 P.hcl_num as HistoriaClinica,FU.DISA + '-' + FU.Lote + '-' + FU.Numero as FUA,
                 FU.IdAtencion as Fua_id,
                 TA.description AS TipoActividad,
                 LEFT(AE.description,20) as ActividadEspecifica,
                 PE.id AS Personal_id,
                 PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
         
                 /* INICIO DE ESTADO CITA */
                 CASE WHEN PDD.AsistenciaCita_id IS NULL THEN 10 ELSE PDD.AsistenciaCita_id END AS EstadoCita_id,
                 CASE WHEN AC.abreviado IS NULL THEN 'CITADO' 
                      WHEN AC.id = 19 THEN 'NO ATENDIDO'
                      WHEN AC.id = 18 THEN 'NO ATENDIDO'
                      WHEN AC.id = 17 THEN 'NO ATENDIDO'
                      WHEN AC.id = 13 THEN 'NO ATENDIDO'
                      WHEN AC.id = 2 THEN 'NO ATENDIDO'
                      WHEN AC.id = 15 THEN 'NO ATENDIDO'
                      WHEN AC.id = 3  THEN 'ATENDIDO'
                      WHEN AC.id = 4  THEN 'ATENDIDO'
                      ELSE AC.abreviado END AS EstadoCita,
                 /* FIN DE ESTADO CITA */
                 'PDIARIO' AS Modelo, 
         
                 CASE WHEN PDC.ModoAtencionProfesional_id = 1 THEN 'PRESENCIAL'
                 WHEN PDC.ModoAtencionProfesional_id = 5 THEN 'EXTRA'
                 WHEN PDC.ModoAtencionProfesional_id = 7 THEN 'NO PRESENCIAL'
                 WHEN PDC.ModoAtencionProfesional_id = 6 THEN 'H-COMPL.'
                 END AS TipoAtencion,
         
                 UO.description AS UnidadOrganica,
                 'MEDICOS' AS GrupoProfesional,
                 PDD.id AS idRegistro,
                 PDD.nroSesion AS numeroSesion,
                 NULL AS idIdentificador,
                 PDD.Cie1_cod AS CodigoCie,
                 NULL AS Diagnostico
                         
                 
                 FROM [INRDIS_II].[dbo].[PDiarioDetalles] PDD INNER JOIN
                      [INRDIS_II].[dbo].[PDiarioCabecera] PDC ON PDD.PDiarioCabecera_id = PDC.id INNER JOIN 
                      [INRDIS_II].[dbo].[TipoActividad] TA ON PDC.tipoActividad_id = TA.id INNER JOIN 
                      [INRDIS_II].[dbo].[ActividadEspecifica] AE ON PDD.actividadEspecifica_id = AE.id INNER JOIN
                      [INRDIS_II].[dbo].[UnidadOrganica] UO ON PDC.UnidadOrganica_id = UO.id INNER JOIN
                      [INRDIS_II].[dbo].[Persona] P ON PDD.Persona_id = P.id LEFT JOIN 
                      [INRDIS_II].[dbo].[tbPersonal] PE ON PDC.PersonalAtiende_id = PE.id LEFT JOIN
                      [INRDIS_II].[dbo].[AsistenciaCita] AC ON PDD.AsistenciaCita_id = AC.id LEFT JOIN
                      [ECONOMIA].[dbo].[FUA2] FU ON PDD.FUA_id= FU.IdAtencion
                 
                 WHERE CAST(PDC.fecha AS DATE) BETWEEN :fechaInicial2 AND :fechaFinal2 AND PDD.Financiador_id IN (2,8) AND FU.IdAtencion IS NOT NULL
                       AND AE.id IN (41,42,43,44,45,46,47,48,49,50,51) AND PDD.id_usuario = :IdUsuario2
                 
                 UNION ALL
                 
                 SELECT TOP (100) PERCENT SPD.tbFinanciador_id as Financiador_id,CASE WHEN SPD.tbFinanciador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                 FORMAT(CAST(SPD.fechaProgramada AS DATE),'dd-MM-yyyy') + ' ' + LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) as Fecha,
                 FORMAT(CAST(SPD.fechaProgramada AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) AS Hora,
                 P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
                 P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                 CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                 WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
         
                 /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 CASE WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                 WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                 ELSE 'OTRO' END AS TipoDocumento,
                 CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                 /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                 
                 /* INICIO DE HISTORIA CLINICA*/
                 CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
                 /* FIN DE HISTORIA CLINICA*/
         
                 /* INICIO DE FUA */
                 CASE WHEN SPD.tbFinanciador_id ='02' or SPD.tbFinanciador_id ='08' THEN FU.DISA + '-' + FU.Lote + '-' + FU.Numero END AS FUA,
                 FU.IdAtencion as Fua_id,
                 /* FIN DE FUA */
         
                 'SESION' AS TipoActividad,
                 LEFT(S.descripcion,20) AS ActividadEspecifica,
                 PE.id AS Personal_id,
                 PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
         
                 /* INICIO DE ESTADO CITA */
                 SPD.tbAsistencia_id AS EstadoCita_id,
                 CASE WHEN A.abreviatura IS NULL THEN 'CITADO' 
                         WHEN A.id = 19 THEN 'NO ATENDIDO'
                         WHEN A.id = 18 THEN 'NO ATENDIDO'
                         WHEN A.id = 17 THEN 'NO ATENDIDO'
                         WHEN A.id = 13 THEN 'NO ATENDIDO'
                         WHEN A.id = 2 THEN 'NO ATENDIDO'
                         WHEN A.id = 15 THEN 'NO ATENDIDO'
                         WHEN A.id = 3  THEN 'ATENDIDO'
                         WHEN A.id = 4  THEN 'ATENDIDO'
                         WHEN A.id = 22  THEN 'NO ATENDIDO'
                         ELSE A.abreviatura END COLLATE Modern_Spanish_CI_AS AS EstadoCita,
                 /* FIN DE ESTADO CITA */
         
                 'SERVICIODET' AS Modelo,
         
                 CASE WHEN SPD.tbModoCita_id = 1 THEN 'PRESENCIAL'
                 WHEN SPD.tbModoCita_id = 5 THEN 'EXTRA'
                 WHEN SPD.tbModoCita_id = 7 THEN 'NO PRESENCIAL'
                 WHEN SPD.tbModoCita_id = 6 THEN 'H-COMPL.'
                 END AS TipoAtencion,
         
                 UO.descripcion AS UnidadOrganica,
                 CASE WHEN GP.id = 2 THEN 'TERAPISTAS'
                      WHEN GP.id = 13 THEN 'MEDICOS'
                      WHEN GP.id = 14 THEN 'MEDICOS'
                      WHEN GP.id = 21 THEN 'MEDICOS'
                      ELSE GP.descripcion END AS GrupoProfesional,
                 SPD.id AS idRegistro,
                 SPD.nroSesion AS numeroSesion,
                 SPD.ServicioPersonaCab_id AS idIdentificador,
                 SPC.tbCIE1_codigo AS CodigoCie,
                 NULL AS Diagnostico
                 
                 FROM    [INRDIS_II].[dbo].[tbServicio] S INNER JOIN [INRDIS_II].[dbo].[tbServicio_UOrganica] SUO
                         ON S.id = SUO.tbServicio_id INNER JOIN [INRDIS_II].[dbo].[ServicioPersonaCab] SPC INNER JOIN
                         [INRDIS_II].[dbo].[ServicioPersonaDet] SPD ON SPC.id = SPD.ServicioPersonaCab_id LEFT JOIN
                         [INRDIS_II].[dbo].[tbAsistencia] A ON SPD.tbAsistencia_id = A.id INNER JOIN
                         [INRDIS_II].[dbo].[Persona] P ON SPC.Persona_id = P.id ON SUO.id = SPC.tbServicio_UOrganica_id INNER JOIN
                         [INRDIS_II].[dbo].[tbPersonal] PE ON SPD.tbPersonal_id = PE.id INNER JOIN
                         [INRDIS_II].[dbo].[tbUOrganica] UO ON SUO.tbUOrganica_id = UO.id LEFT OUTER JOIN
                         [ECONOMIA].[dbo].[FUA2] FU ON SPD.sustentoPago_id = FU.IdAtencion LEFT JOIN
                         [INRDIS_II].[dbo].[tbGrupoProfesional] GP ON SUO.tbGrupoProfesional_id = GP.id
                 
                 WHERE CAST(SPD.fechaProgramada AS DATE) BETWEEN :fechaInicial3 AND :fechaFinal3 AND SPD.tbFinanciador_id IN (2,8) AND
                           (SPD.tbEstado_id <> 8 AND SPD.tbEstado_id <> 11) AND SUO.tbGrupoProfesional_id IN (2,4,6,7,11,13,14,18,21,23)
                           AND FU.IdAtencion IS NOT NULL AND SUO.tbServicio_id IN (6,7,8,9,27,28,115) AND SPD.id_usuario = :IdUsuario3
                 
                 ORDER BY Fecha,Hora ASC",["fechaInicial1"=>$request->input('fechaInicio_pacientesCitados'),
                                           "fechaFinal1"=>$request->input('fechaFin_pacientesCitados'),
                                           "fechaInicial2"=>$request->input('fechaInicio_pacientesCitados'),
                                           "fechaFinal2"=>$request->input('fechaFin_pacientesCitados'),
                                           "fechaInicial3"=>$request->input('fechaInicio_pacientesCitados'),
                                           "fechaFinal3"=>$request->input('fechaFin_pacientesCitados'),
                                           "IdUsuario1"=>$usuario_fuasObservados,
                                           "IdUsuario2"=>$usuario_fuasObservados,
                                           "IdUsuario3"=>$usuario_fuasObservados]))->make(true);
                    }

               }
     }

     public function rolCitas(Request $request){
        $datos = array("idCab_fuasObservados"=>$request->input("idCab_fuasObservados"));

        /* Si existe un requerimiento del tipo AJAX */
        if(request()->ajax()){
        return datatables()->of(DB::SELECT("SELECT SPD.id,SPD.tbHorario_id,SPD.nroSesion,FORMAT(CAST(SPD.fechaProgramada AS DATE),'yyyy-MM-dd') AS fecha,LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) AS hora,FORMAT(CAST(SPD.fechaProgramada AS DATE),'dd-MM-yyyy') + ' ' + LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) AS fechaProgramada,
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
                                            CASE WHEN SPD.tbFinanciador_id = 2 THEN F.DISA + '-' + F.Lote + '-' + F.Numero ELSE '' END AS Comprobante,SPD.notas

                                           FROM INRDIS_II.dbo.tbEstado E INNER JOIN
                                                INRDIS_II.dbo.ServicioPersonaCab SPC INNER JOIN
                                                INRDIS_II.dbo.ServicioPersonaDet SPD ON SPC.id = SPD.ServicioPersonaCab_id ON E.id = SPD.tbEstado_id LEFT OUTER JOIN
                                                INRDIS_II.dbo.Persona P INNER JOIN 
                                                INRDIS_II.dbo.tbPersonal PER ON P.id = PER.Persona_id ON SPD.tbPersonal_id = PER.id LEFT OUTER JOIN
                                                INRDIS_II.dbo.tbAsistencia A ON SPD.tbAsistencia_id = A.id LEFT OUTER JOIN
                                                ECONOMIA.dbo.FUA2 F ON SPD.sustentoPago_id = F.IdAtencion LEFT OUTER JOIN
                                                INRDIS_II.dbo.tbHorario H ON SPD.tbHorario_id = H.id
                         
                                           WHERE (SPD.ServicioPersonaCab_id = :idCab_fuasObservados) AND SPD.tbEstado_id <> 11
                                           ORDER BY SPD.nroSesion",["idCab_fuasObservados"=>$request->input('idCab_fuasObservados')]))->make(true);
        }
   }

   public function verFua(Request $request){
        if($request->ajax()){
            $fuaId_fuasObservados = $request->idFua_fuasObservados;

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
                                            WHERE F.IdAtencion = ?",[$fuaId_fuasObservados]);

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
         $datos =  array("usuario_fuasObservados"=>$request->usuario_fuasObservados,
                         "idFuaF_fuasObservados"=>$request->idFuaF_fuasObservados,
                         "personalAtiendeF_fuasObservados"=>$request->personalAtiendeF_fuasObservados,
                         "lugarAtencionF_fuasObservados"=>$request->lugarAtencionF_fuasObservados,
                         "tipoAtencionF_fuasObservados"=>$request->tipoAtencionF_fuasObservados,
                         "codigoReferenciaF_fuasObservados"=>$request->codigoReferenciaF_fuasObservados,
                         "descripcionReferenciaF_fuasObservados"=>$request->descripcionReferenciaF_fuasObservados,
                         "numeroReferenciaF_fuasObservados"=>$request->numeroReferenciaF_fuasObservados,
                         "tipoDocumentoF_fuasObservados"=>$request->tipoDocumentoF_fuasObservados,
                         "numeroDocumentoF_fuasObservados"=>$request->numeroDocumentoF_fuasObservados,
                         "componenteF_fuasObservados"=>$request->componenteF_fuasObservados,
                         "codigoAsegurado1F_fuasObservados"=>$request->codigoAsegurado1F_fuasObservados,
                         "codigoAsegurado2F_fuasObservados"=>$request->codigoAsegurado2F_fuasObservados,
                         "codigoAsegurado3F_fuasObservados"=>$request->codigoAsegurado3F_fuasObservados,
                         "apellidoPaternoF_fuasObservados"=>$request->apellidoPaternoF_fuasObservados,
                         "apellidoMaternoF_fuasObservados"=>$request->apellidoMaternoF_fuasObservados,
                         "primerNombreF_fuasObservados"=>$request->primerNombreF_fuasObservados,
                         "otroNombreF_fuasObservados"=>$request->otroNombreF_fuasObservados,
                         "sexoF_fuasObservados"=>$request->sexoF_fuasObservados,
                         "fechaNacimientoF_fuasObservados"=>$request->fechaNacimientoF_fuasObservados,
                         "historiaF_fuasObservados"=>$request->historiaF_fuasObservados,
                         "fechaF_fuasObservados"=>$request->fechaF_fuasObservados,
                         "horaF_fuasObservados"=>$request->horaF_fuasObservados,
                         "codigoPrestacionalF_fuasObservados"=>$request->codigoPrestacionalF_fuasObservados,
                         "conceptoPrestacionalF_fuasObservados"=>$request->conceptoPrestacionalF_fuasObservados,
                         "destinoAseguradoF_fuasObservados"=>$request->destinoAseguradoF_fuasObservados,
                         "fechaIngresoF_fuasObservados"=>$request->fechaIngresoF_fuasObservados,
                         "fechaAltaF_fuasObservados"=>$request->fechaAltaF_fuasObservados,
                         "diagnosticoF_fuasObservados"=>$request->diagnosticoF_fuasObservados,
                         "codigoCieNF_fuasObservados"=>$request->codigoCieNF_fuasObservados,
                         "tipoDocumentoP_fuasObservados"=>$request->tipoDocumentoP_fuasObservados,
                         "numeroDocumentoP_fuasObservados"=>$request->numeroDocumentoP_fuasObservados,
                         "nombresApellidosP_fuasObservados"=>$request->nombresApellidosP_fuasObservados,
                         "tipoPersonalSaludF_fuasObservados"=>$request->tipoPersonalSaludF_fuasObservados,
                         "egresadoF_fuasObservados"=>$request->egresadoF_fuasObservados,
                         "colegiaturaF_fuasObservados"=>$request->colegiaturaF_fuasObservados,
                         "especialidadF_fuasObservados"=>$request->especialidadF_fuasObservados,
                         "rneF_fuasObservados"=>$request->rneF_fuasObservados,
                         "pacienteIdF_fuasObservados"=>$request->pacienteIdF_fuasObservados);

         /* INICIO DEL SEXO DEL PACIENTE */
         if($datos["sexoF_fuasObservados"] == "MASCULINO"){
              $datos["sexoF_fuasObservados"] = 1;
         }else{
              $datos["sexoF_fuasObservados"] = 0;
         }
         /* FIN DEL SEXO DEL PACIENTE */

         /* INICIO DEL DOCUMENTO DEL PACIENTE */
         if($datos["tipoDocumentoF_fuasObservados"] == "D.N.I."){
              $datos["tipoDocumentoF_fuasObservados"] = 1;
         }else{
              $datos["tipoDocumentoF_fuasObservados"] = 3;
         }
         /* FIN DEL DOCUMENTOS DEL PACIENTE */

         /* ES PARA EL DOCUMENTO DEL PERSONAL */
         if($datos["tipoDocumentoP_fuasObservados"] == "D.N.I."){
              $datos["tipoDocumentoP_fuasObservados"] = 1;
         }else{
              $datos["tipoDocumentoP_fuasObservados"] = 3;
         }
         /* FIN DEL DOCUMENTO DEL PERSONAL */

         if(!empty($datos)){
              $validar = \Validator::make($datos,[
                   "usuario_fuasObservados"=>'required',
                   "idFuaF_fuasObservados"=>'required|regex:/^[0-9]+$/i',
                   "personalAtiendeF_fuasObservados"=>'required|regex:/^[0-9]+$/i',
                   "lugarAtencionF_fuasObservados"=>'required|regex:/^[0-9]+$/i',
                   "tipoAtencionF_fuasObservados"=>'nullable|regex:/^[0-9]+$/i',
                   "codigoReferenciaF_fuasObservados"=>'required|regex:/^[0-9]+$/i',
                   "numeroReferenciaF_fuasObservados"=>'required|regex:/^[-\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ]+$/i',
                   "componenteF_fuasObservados"=>'required|regex:/^[0-9]+$/i',
                   "codigoAsegurado2F_fuasObservados"=>'required|regex:/^[0-9a-zA-ZñÑáéíóúÁÉÍÓÚ]+$/i',
                   "codigoAsegurado3F_fuasObservados"=>'required|regex:/^[0-9]+$/i',
                   "tipoDocumentoF_fuasObservados"=>'required|regex:/^[0-9]+$/i',
                   "numeroDocumentoF_fuasObservados"=>'required|regex:/^[0-9]+$/i',
                   "apellidoPaternoF_fuasObservados"=>'required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                   "apellidoMaternoF_fuasObservados"=>'required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                   "primerNombreF_fuasObservados"=>'required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                   "sexoF_fuasObservados"=>'required|regex:/^[0-9]+$/i',
                   "fechaNacimientoF_fuasObservados"=>'required',
                   "historiaF_fuasObservados"=>'required|regex:/^[0-9]+$/i',
                   "fechaF_fuasObservados"=>'required',/* HAY QUE COMPROBAR OK */
                   "horaF_fuasObservados"=>'required',/* HAY QUE COMPROBAR OK */
                   "codigoPrestacionalF_fuasObservados"=>'required|regex:/^[0-9]+$/i',
                   "conceptoPrestacionalF_fuasObservados"=>'required|regex:/^[0-9]+$/i',
                   "destinoAseguradoF_fuasObservados"=>'required|regex:/^[0-9]+$/i',
                   "nombresApellidosP_fuasObservados"=>'required|regex:/^[0-9]+$/i',
                   "fechaAltaF_fuasObservados"=>'nullable|after_or_equal:fechaIngresoF_fuasObservados'
              ]);

              if($validar->fails()){
                   return "NO-VALIDACION";
              }else{

                   if($datos["fechaIngresoF_fuasObservados"] == ''){
                        $fechaIngreso = null;          
                   }else{
                        $fechaIngreso = $datos["fechaIngresoF_fuasObservados"] . 'T' . date("00:00:00.000");
                   }

                   if($datos["fechaAltaF_fuasObservados"] == ''){
                        $fechaAlta = null;
                   }else{
                        $fechaAlta = $datos["fechaAltaF_fuasObservados"] . 'T' . date("00:00:00.000");
                   }

                   /* VOLVEMOS A TRAER TODOS LOS DATOS */
                   $datos =  array("PersonaAtiende"=>$request->personalAtiendeF_fuasObservados,
                                   "LugarAtencion"=>$request->lugarAtencionF_fuasObservados,
                                   "TipoAtencion"=>$request->tipoAtencionF_fuasObservados,
                                   "IPRESSRefirio"=>$request->codigoReferenciaF_fuasObservados,
                                   "NroHojaReferencia"=>$request->numeroReferenciaF_fuasObservados,
                                   "TipoDocumentoIdentidad"=>$datos["tipoDocumentoF_fuasObservados"],
                                   "NroDocumentoIdentidad"=>$request->numeroDocumentoF_fuasObservados,
                                   "Componente"=>$request->componenteF_fuasObservados,
                                   "DISAAsegurado"=>$request->codigoAsegurado1F_fuasObservados,
                                   "LoteAsegurado"=>$request->codigoAsegurado2F_fuasObservados,
                                   "NumeroAsegurado"=>$request->codigoAsegurado3F_fuasObservados,
                                   "ApellidoPaterno"=>$request->apellidoPaternoF_fuasObservados,
                                   "ApellidoMaterno"=>$request->apellidoMaternoF_fuasObservados,
                                   "PrimerNombre"=>$request->primerNombreF_fuasObservados,
                                   "OtrosNombres"=>$request->otroNombreF_fuasObservados,
                                   "Sexo"=>$datos["sexoF_fuasObservados"],
                                   "FechaNacimiento"=>$datos["fechaNacimientoF_fuasObservados"] . 'T' . date("00:00:00.000"),
                                   "HistoriaClinica"=>$request->historiaF_fuasObservados,
                                   "FechaHoraAtencion"=>$datos["fechaF_fuasObservados"]. 'T' . $datos["horaF_fuasObservados"] . '.000',
                                   "CodigoPrestacional"=>$request->codigoPrestacionalF_fuasObservados,
                                   "ModalidadAtencion"=>$request->conceptoPrestacionalF_fuasObservados,
                                   "DestinoAsegurado"=>$request->destinoAseguradoF_fuasObservados,
                                   "FechaIngreso"=>$fechaIngreso,
                                   "FechaAlta"=>$fechaAlta,
                                   "pdr1_cod"=>$request->diagnosticoF_fuasObservados,
                                   "cie1_cod"=>$request->codigoCieNF_fuasObservados,
                                   "TipoDocResponsable"=>$datos["tipoDocumentoP_fuasObservados"],
                                   "NroDocResponsable"=>$request->numeroDocumentoP_fuasObservados,
                                   "personalAtiende_id"=>$request->nombresApellidosP_fuasObservados,
                                   "personalResponsable_id"=>$request->nombresApellidosP_fuasObservados,
                                   "TipoPersonalSalud"=>$request->tipoPersonalSaludF_fuasObservados,
                                   "EsEgresado"=>$request->egresadoF_fuasObservados,
                                   "NroColegiatura"=>$request->colegiaturaF_fuasObservados,
                                   "Especialidad"=>$request->especialidadF_fuasObservados,
                                   "NroRNE"=>$request->rneF_fuasObservados,
                                   "persona_id"=>$request->pacienteIdF_fuasObservados);

                                   /* return $datos; */

                   $fua = FuaModel::where('IdAtencion',$request->idFuaF_fuasObservados)->update($datos);

                   /* GUARDAMOS VALORES EN LA BD SOFTWARE_UFPA (AUDITORIA) */

                   $generarFuaA = new FuaNModel();
                   $generarFuaA->NroFua = $request->idFuaF_fuasObservados;
                   $generarFuaA->TipoAccion = 2; /* SI ES 1 ES PARA GENERACION Y 0 PARA ANULACION Y 2 PARA ACTUALIZAR */
                   $generarFuaA->IdUsuario = $request->usuario_fuasObservados;
                   $generarFuaA->save();

                   return "ACTUALIZAR-FUA";
                   /* FALTA EXTRAER EL ID DE LA ATENCIÓN */
              }
         }else{
              return "ERROR";
         }
    }
    }

    public function validarPasswordFua(Request $request){
        if($request->ajax()){
             $password_fuasObservados = $request->password;
             $extraemosContraseñaVerdadera = DB::SELECT("SELECT password FROM software_ufpa_general.dbo.users WHERE
                                                        id = ?",[$request->usuarioExtraer]);

             $contraseñaV = $extraemosContraseñaVerdadera[0]->password;

             if(password_verify($password_fuasObservados, $contraseñaV)) {

                  $datos1 = array("FUA_id"=>"");
                  $datos2 = array("sustentoPago_id"=>"");

                  if($request->idModelo == "PDIARIO"){
                       $fua1Anulacion = Fua1ActualizacionModel::where('id',$request->idRegistro)->update($datos1);
                  }

                  if($request->idModelo == "SERVICIODET"){
                       $fua2Anulacion = Fua2ActualizacionModel::where('id',$request->idRegistro)->update($datos2);
                  }

                  if($request->idModelo == "CITASHORA"){
                       $fua3Anulacion = Fua3ActualizacionModel::where('cext_id',$request->idRegistro)->update($datos1);
                  }

                  /* GUARDAMOS VALORES EN LA BD SOFTWARE_UFPA (AUDITORIA) */

                  $generarFuaN = new FuaNModel();
                  $generarFuaN->NroFua = $request->idFua;
                  $generarFuaN->TipoAccion = 0; /* SI ES 1 ES PARA GENERACION Y 0 PARA ANULACION */
                  $generarFuaN->IdUsuario = $request->usuarioExtraer;
                  $generarFuaN->save();

                  DB::UPDATE("UPDATE ECONOMIA.dbo.FUA2 SET generarFua_estado = '0' WHERE IdAtencion = :idAtencion",
                            ["idAtencion"=>$request->idFua]);

                  return "IGUALES";
             }else{
                  return "DIFERENTES";
             }
              
        }
   }

   public function codigoCie(Request $request){
    if($request->ajax()){
            $codigoCie_fuasObservados = $request->CodigoCie;
            $datosCodigoCie = DB::SELECT("SELECT C.cie_cod,C.cie_desc FROM inrdis_ii.dbo.tblCIE C 
                                          WHERE C.cie_cod = ?",[$codigoCie_fuasObservados]);
            return response(json_encode($datosCodigoCie));
    }
}

public function referencias(Request $request) {

    if($request->ajax()){
            $idPaciente_fuasObservados = $request->idPaciente;
            $datosReferencia = DB::SELECT("SELECT TOP 1 RRH.estb2_cod,TE.descripcion,RRH.ref_rec_hoja_nro FROM INRDIS_II.dbo.Referencias_Rec_Hojas RRH 
                                           INNER JOIN INRDIS_II.dbo.tbEstablecimiento TE ON RRH.estb2_cod = TE.codigoRenaes COLLATE Modern_Spanish_CI_AS 
                                           WHERE RRH.Persona_id = ? ORDER BY ref_rec_hoja_fech DESC",[$idPaciente_fuasObservados]);

            return response(json_encode($datosReferencia));  
    }
}

public function personalC(Request $request){
    if($request->ajax()){
            $idPersonal_fuasObservados = $request->idPersonal;
            $datosPersonal = DB::SELECT("SELECT PER.ddi_cod,PER.ddi_nro,PER.per_apat,PER.per_amat,PER.per_nomb,PER.NroColegiatura,PER.NroRNE,TPS.id AS TipoPersonalSalud_id,TPS.descripcion AS TipoPersonalSalud,
                                         E.id AS Especialidad_id,E.descripcion AS Especialidad,SE.id AS Egresado_id,SE.descripcion AS Egresado 
                                         FROM PERSONAL.dbo.personal PER LEFT JOIN ECONOMIA.dbo.sisTIPOPERSONALSALUD TPS ON PER.sisTIPOPERSONALSALUD_id = TPS.id
                                         LEFT JOIN ECONOMIA.dbo.sisESPECIALIDAD E ON PER.sisESPECIALIDAD_id = E.id
                                         LEFT JOIN ECONOMIA.dbo.sisEgresado SE ON PER.sisEGRESADO_id = SE.id WHERE PER.id = ?",[$idPersonal_fuasObservados]);

            return response(json_encode($datosPersonal));
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
                                 DFD.docf_item_total,DFD.docf_item_precio FROM INR.dbo.DocFCabecera DFC INNER JOIN INR.dbo.DocFDetalles DFD ON DFC.docf_trans = DFD.docf_trans INNER JOIN
                                 INR.dbo.Catalogo C ON DFD.catalogo_cod = C.catalogo_cod WHERE DFC.fua_nro = ?",[$datosFua[0]->Numero]);

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

    $pdf = PDF::loadView('paginas.reportesFUA',$datosFua);

    // descargar archivo PDF con método de descarga
    return $pdf->setPaper('a4','portrait')->stream('reportesFUA.pdf');
}

     public function buscarPorHistoriaBD(Request $request){

          $usuario_fuasObservados = auth()->id();

          if(request()->ajax()){

               if(auth()->user()->id_rol == 1){
                    return datatables()->of(DB::SELECT("SELECT TOP (100) PERCENT CHP.fina_cod as Financiador_id,
                    CASE WHEN CHP.fina_cod = '02' THEN 'S.I.S.' WHEN CHP.fina_cod = '2 ' THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                    FORMAT(CAST(CH.cita_fech AS DATE), 'dd-MM-yyyy') + ' ' + LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) as Fecha,
                    FORMAT(CAST(CH.cita_fech AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) AS Hora,
                    P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
                    P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                    CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                    WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
            
                    /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                    CASE 
                    WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                    WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                    ELSE 'OTRO' END AS TipoDocumento,
                    CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                    /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
                    /* INICIO DE HISTORIA CLINICA*/
                    CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
                    /* FIN DE HISTORIA CLINICA*/
            
                    /* INICIO DE FUA */
                    FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,
                    FU.IdAtencion as Fua_id,
                    /* FIN DE FUA */
            
                    TA.description AS TipoActividad,
                    LEFT(AE.description,20) AS ActividadEspecifica,
                    PE.id AS Personal_id,
                    PE.apPaterno +' '+ PE.apMaterno +' '+ PE.nombres AS Profesional, 
            
                    /* INICIO DE ESTADO CITA */
                    CASE WHEN CHP.asis_cod IS NULL THEN 10 ELSE A.AsistenciaCita_id END AS EstadoCita_id,
                    CASE WHEN AC.abreviado IS NULL THEN 'CITADO'  
                         WHEN AC.id = 19 THEN 'NO ATENDIDO'
                         WHEN AC.id = 18 THEN 'NO ATENDIDO'
                         WHEN AC.id = 17 THEN 'NO ATENDIDO'
                         WHEN AC.id = 13 THEN 'NO ATENDIDO'
                         WHEN AC.id = 2 THEN 'NO ATENDIDO'
                         WHEN AC.id = 15 THEN 'NO ATENDIDO'
                         WHEN AC.id = 3  THEN 'ATENDIDO'
                         WHEN AC.id = 4  THEN 'ATENDIDO'
                         ELSE AC.abreviado END AS EstadoCita,
               /* FIN DE ESTADO CITA */
                    /* FIN DE ESTADO CITA */
            
                    'CITASHORA' AS Modelo,
           
                    CASE WHEN CH.modoap_cod = 1 THEN 'PRESENCIAL'
                         WHEN CH.modoap_cod = 5 THEN 'EXTRA'
                         WHEN CH.modoap_cod = 7 THEN 'NO PRESENCIAL'
                         WHEN CH.modoap_cod = 6 THEN 'H-COMPL.'
                         END AS TipoAtencion,
           
                    CASE WHEN UO.uo_desc = 'DPTO. AMPUTADOS Y QUEMADOS' THEN 'DPTO. A. Q. Y T-POSTURALES' ELSE UO.uo_desc END AS UnidadOrganica,
                    CASE WHEN CH.serv_cod = 11 THEN 'MEDICOS' ELSE 'NUTRICIONISTAS' END AS GrupoProfesional,
                    CHP.cext_id AS idRegistro,
                    NULL AS numeroSesion,
                    NULL AS idIdentificador,
                    CHP.cie_cod_dano1 AS CodigoCie,
                    CHP.diag_cod_dano1 AS Diagnostico
            
            FROM    [INRDIS_II].[dbo].[CitasHora] CH INNER JOIN 
                    [INRDIS_II].[dbo].[CitasHora_Paciente] CHP ON CH.cita_prof = CHP.cita_prof AND CH.cita_fech = CHP.cita_fech AND CH.cita_extra = CHP.cita_extra AND
                    CH.cita_hora = CHP.cita_hora INNER JOIN [INRDIS_II].[dbo].[tblUO] UO ON CH.uo_cod = UO.uo_cod INNER JOIN
                    [INRDIS_II].[dbo].[tblTipoCita_Citado] TCC ON CHP.tcitaa_cod = TCC.tcitaa_cod INNER JOIN 
                    [INRDIS_II].[dbo].[TipoActividad] TA ON TCC.tipoActividad_id = TA.id INNER JOIN 
                    [INRDIS_II].[dbo].[ActividadEspecifica] AE ON TCC.actividadEspecifica_id = AE.id INNER JOIN
                    [INRDIS_II].[dbo].[Persona] P ON CHP.pers_id = P.id LEFT OUTER JOIN 
                    [INRDIS_II].[dbo].[tblAsistencia] A ON CHP.asis_cod = A.asis_cod LEFT OUTER JOIN
                    [ECONOMIA].[dbo].[FUA2] FU ON CHP.FUA_id = FU.IdAtencion LEFT OUTER JOIN 
                    [INRDIS_II].[dbo].[AsistenciaCita] AC ON A.AsistenciaCita_id = AC.id LEFT JOIN
                    [INRDIS_II].[dbo].[tbPersonal] PE ON CH.cita_prof = PE.id
            
                     
            WHERE P.hcl_num = :hcl_num1 AND (CHP.fina_cod IN ('02','08') OR CHP.fina_cod 
                  IN ('2 ','8 ')) AND CH.serv_cod <> 41 AND FU.IdAtencion IS NOT NULL AND AE.id NOT IN (41,42,43,44,45,46,47,48,49,50,51)
            
            UNION ALL
            
            SELECT TOP (100) PERCENT PDD.Financiador_id as Financiador_id,
            CASE WHEN PDD.Financiador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
            FORMAT(CAST(PDC.fecha AS DATE),'dd-MM-yyyy') + ' ' + LEFT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) + ':' + 
            RIGHT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) as Fecha,
            FORMAT(CAST(PDC.fecha AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(PDC.horaInicia,2) + ':' + RIGHT(PDC.horaInicia,2) AS Hora,
            P.id AS id_Paciente,P.apPaterno + ' ' + P.apMaterno + ' ' + P.nombres AS Paciente,
            P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
            CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
            WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
            
            /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            CASE 
            WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
            WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
            ELSE 'OTRO' END AS TipoDocumento,
            CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
            /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
            P.hcl_num as HistoriaClinica,FU.DISA + '-' + FU.Lote + '-' + FU.Numero as FUA,
            FU.IdAtencion as Fua_id,
            TA.description AS TipoActividad,
            LEFT(AE.description,20) as ActividadEspecifica,
            PE.id AS Personal_id,
            PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
           
            /* INICIO DE ESTADO CITA */
            CASE WHEN PDD.AsistenciaCita_id IS NULL THEN 10 ELSE PDD.AsistenciaCita_id END AS EstadoCita_id,
            CASE WHEN AC.abreviado IS NULL THEN 'CITADO' 
                 WHEN AC.id = 19 THEN 'NO ATENDIDO'
                 WHEN AC.id = 18 THEN 'NO ATENDIDO'
                 WHEN AC.id = 17 THEN 'NO ATENDIDO'
                 WHEN AC.id = 13 THEN 'NO ATENDIDO'
                 WHEN AC.id = 2 THEN 'NO ATENDIDO'
                 WHEN AC.id = 15 THEN 'NO ATENDIDO'
                 WHEN AC.id = 3  THEN 'ATENDIDO'
                 WHEN AC.id = 4  THEN 'ATENDIDO'
                 ELSE AC.abreviado END AS EstadoCita,
            /* FIN DE ESTADO CITA */
            'PDIARIO' AS Modelo, 
           
            CASE WHEN PDC.ModoAtencionProfesional_id = 1 THEN 'PRESENCIAL'
            WHEN PDC.ModoAtencionProfesional_id = 5 THEN 'EXTRA'
            WHEN PDC.ModoAtencionProfesional_id = 7 THEN 'NO PRESENCIAL'
            WHEN PDC.ModoAtencionProfesional_id = 6 THEN 'H-COMPL.'
            END AS TipoAtencion,
           
            UO.description AS UnidadOrganica,
            'MEDICOS' AS GrupoProfesional,
            PDD.id AS idRegistro,
            PDD.nroSesion AS numeroSesion,
            NULL AS idIdentificador,
            PDD.Cie1_cod AS CodigoCie,
            NULL AS Diagnostico
                    
            
            FROM [INRDIS_II].[dbo].[PDiarioDetalles] PDD INNER JOIN
                 [INRDIS_II].[dbo].[PDiarioCabecera] PDC ON PDD.PDiarioCabecera_id = PDC.id INNER JOIN 
                 [INRDIS_II].[dbo].[TipoActividad] TA ON PDC.tipoActividad_id = TA.id INNER JOIN 
                 [INRDIS_II].[dbo].[ActividadEspecifica] AE ON PDD.actividadEspecifica_id = AE.id INNER JOIN
                 [INRDIS_II].[dbo].[UnidadOrganica] UO ON PDC.UnidadOrganica_id = UO.id INNER JOIN
                 [INRDIS_II].[dbo].[Persona] P ON PDD.Persona_id = P.id LEFT JOIN 
                 [INRDIS_II].[dbo].[tbPersonal] PE ON PDC.PersonalAtiende_id = PE.id LEFT JOIN
                 [INRDIS_II].[dbo].[AsistenciaCita] AC ON PDD.AsistenciaCita_id = AC.id LEFT JOIN
                 [ECONOMIA].[dbo].[FUA2] FU ON PDD.FUA_id= FU.IdAtencion
            
            WHERE P.hcl_num = :hcl_num2 AND PDD.Financiador_id IN (2,8) 
                  AND FU.IdAtencion IS NOT NULL AND AE.id NOT IN (41,42,43,44,45,46,47,48,49,50,51)
            
            UNION ALL
            
            SELECT TOP (100) PERCENT SPD.tbFinanciador_id as Financiador_id,CASE WHEN SPD.tbFinanciador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
            FORMAT(CAST(SPD.fechaProgramada AS DATE),'dd-MM-yyyy') + ' ' + LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) as Fecha,
            FORMAT(CAST(SPD.fechaProgramada AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) AS Hora,
            P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
            P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
            CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
            WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
           
            /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            CASE WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
            WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
            ELSE 'OTRO' END AS TipoDocumento,
            CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
            /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
            /* INICIO DE HISTORIA CLINICA*/
            CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
            /* FIN DE HISTORIA CLINICA*/
           
            /* INICIO DE FUA */
            FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,
            FU.IdAtencion as Fua_id,
            /* FIN DE FUA */
           
            'SESION' AS TipoActividad,
            LEFT(S.descripcion,20) AS ActividadEspecifica,
            PE.id AS Personal_id,
            PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
           
            /* INICIO DE ESTADO CITA */
            SPD.tbAsistencia_id AS EstadoCita_id,
            CASE WHEN A.abreviatura IS NULL THEN 'CITADO' 
                 WHEN A.id = 19 THEN 'NO ATENDIDO'
                 WHEN A.id = 18 THEN 'NO ATENDIDO'
                 WHEN A.id = 17 THEN 'NO ATENDIDO'
                 WHEN A.id = 13 THEN 'NO ATENDIDO'
                 WHEN A.id = 2 THEN 'NO ATENDIDO'
                 WHEN A.id = 15 THEN 'NO ATENDIDO'
                 WHEN A.id = 3  THEN 'ATENDIDO'
                 WHEN A.id = 4  THEN 'ATENDIDO'
                 WHEN A.id = 22  THEN 'NO ATENDIDO'
                 ELSE A.abreviatura END COLLATE Modern_Spanish_CI_AS AS EstadoCita,
            /* FIN DE ESTADO CITA */
           
            'SERVICIODET' AS Modelo,
           
            CASE WHEN SPD.tbModoCita_id = 1 THEN 'PRESENCIAL'
            WHEN SPD.tbModoCita_id = 5 THEN 'EXTRA'
            WHEN SPD.tbModoCita_id = 7 THEN 'NO PRESENCIAL'
            WHEN SPD.tbModoCita_id = 6 THEN 'H-COMPL.'
            END AS TipoAtencion,
           
            UO.descripcion AS UnidadOrganica,
            CASE WHEN GP.id = 2 THEN 'TERAPISTAS'
                 WHEN GP.id = 13 THEN 'MEDICOS'
                 WHEN GP.id = 14 THEN 'MEDICOS'
                 WHEN GP.id = 21 THEN 'MEDICOS'
                 ELSE GP.descripcion END AS GrupoProfesional,
            SPD.id AS idRegistro,
            SPD.nroSesion AS numeroSesion,
            SPD.ServicioPersonaCab_id AS idIdentificador,
            SPC.tbCIE1_codigo AS CodigoCie,
            NULL AS Diagnostico
            
            FROM    [INRDIS_II].[dbo].[tbServicio] S INNER JOIN [INRDIS_II].[dbo].[tbServicio_UOrganica] SUO
                    ON S.id = SUO.tbServicio_id INNER JOIN [INRDIS_II].[dbo].[ServicioPersonaCab] SPC INNER JOIN
                    [INRDIS_II].[dbo].[ServicioPersonaDet] SPD ON SPC.id = SPD.ServicioPersonaCab_id LEFT JOIN
                    [INRDIS_II].[dbo].[tbAsistencia] A ON SPD.tbAsistencia_id = A.id INNER JOIN
                    [INRDIS_II].[dbo].[Persona] P ON SPC.Persona_id = P.id ON SUO.id = SPC.tbServicio_UOrganica_id INNER JOIN
                    [INRDIS_II].[dbo].[tbPersonal] PE ON SPD.tbPersonal_id = PE.id INNER JOIN
                    [INRDIS_II].[dbo].[tbUOrganica] UO ON SUO.tbUOrganica_id = UO.id LEFT OUTER JOIN
                    [ECONOMIA].[dbo].[FUA2] FU ON SPD.sustentoPago_id = FU.IdAtencion LEFT JOIN
                    [INRDIS_II].[dbo].[tbGrupoProfesional] GP ON SUO.tbGrupoProfesional_id = GP.id
            
            WHERE P.hcl_num = :hcl_num3 AND SPD.tbFinanciador_id IN (2,8) AND
                      (SPD.tbEstado_id <> 8 AND SPD.tbEstado_id <> 11) AND SUO.tbGrupoProfesional_id IN (2,4,6,7,11,13,14,18,21,23)
                      AND FU.IdAtencion IS NOT NULL AND SUO.tbServicio_id IN (6,7,8,9,27,28,115)",
                    ["hcl_num1"=>$request->numHistoriaBD_fuasObservados,
                     "hcl_num2"=>$request->numHistoriaBD_fuasObservados,
                     "hcl_num3"=>$request->numHistoriaBD_fuasObservados]))->make(true);
               }else{
                    return datatables()->of(DB::SELECT("SELECT TOP (100) PERCENT CHP.fina_cod as Financiador_id,
                    CASE WHEN CHP.fina_cod = '02' THEN 'S.I.S.' WHEN CHP.fina_cod = '2 ' THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                    FORMAT(CAST(CH.cita_fech AS DATE), 'dd-MM-yyyy') + ' ' + LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) as Fecha,
                    FORMAT(CAST(CH.cita_fech AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) AS Hora,
                    P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
                    P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                    CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                    WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
            
                    /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                    CASE 
                    WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                    WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                    ELSE 'OTRO' END AS TipoDocumento,
                    CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                    /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
                    /* INICIO DE HISTORIA CLINICA*/
                    CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
                    /* FIN DE HISTORIA CLINICA*/
            
                    /* INICIO DE FUA */
                    FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,
                    FU.IdAtencion as Fua_id,
                    /* FIN DE FUA */
            
                    TA.description AS TipoActividad,
                    LEFT(AE.description,20) AS ActividadEspecifica,
                    PE.id AS Personal_id,
                    PE.apPaterno +' '+ PE.apMaterno +' '+ PE.nombres AS Profesional, 
            
                    /* INICIO DE ESTADO CITA */
                    CASE WHEN CHP.asis_cod IS NULL THEN 10 ELSE A.AsistenciaCita_id END AS EstadoCita_id,
                    CASE WHEN AC.abreviado IS NULL THEN 'CITADO'  
                         WHEN AC.id = 19 THEN 'NO ATENDIDO'
                         WHEN AC.id = 18 THEN 'NO ATENDIDO'
                         WHEN AC.id = 17 THEN 'NO ATENDIDO'
                         WHEN AC.id = 13 THEN 'NO ATENDIDO'
                         WHEN AC.id = 2 THEN 'NO ATENDIDO'
                         WHEN AC.id = 15 THEN 'NO ATENDIDO'
                         WHEN AC.id = 3  THEN 'ATENDIDO'
                         WHEN AC.id = 4  THEN 'ATENDIDO'
                         ELSE AC.abreviado END AS EstadoCita,
               /* FIN DE ESTADO CITA */
                    /* FIN DE ESTADO CITA */
            
                    'CITASHORA' AS Modelo,
           
                    CASE WHEN CH.modoap_cod = 1 THEN 'PRESENCIAL'
                         WHEN CH.modoap_cod = 5 THEN 'EXTRA'
                         WHEN CH.modoap_cod = 7 THEN 'NO PRESENCIAL'
                         WHEN CH.modoap_cod = 6 THEN 'H-COMPL.'
                         END AS TipoAtencion,
           
                    CASE WHEN UO.uo_desc = 'DPTO. AMPUTADOS Y QUEMADOS' THEN 'DPTO. A. Q. Y T-POSTURALES' ELSE UO.uo_desc END AS UnidadOrganica,
                    CASE WHEN CH.serv_cod = 11 THEN 'MEDICOS' ELSE 'NUTRICIONISTAS' END AS GrupoProfesional,
                    CHP.cext_id AS idRegistro,
                    NULL AS numeroSesion,
                    NULL AS idIdentificador,
                    CHP.cie_cod_dano1 AS CodigoCie,
                    CHP.diag_cod_dano1 AS Diagnostico
            
            FROM    [INRDIS_II].[dbo].[CitasHora] CH INNER JOIN 
                    [INRDIS_II].[dbo].[CitasHora_Paciente] CHP ON CH.cita_prof = CHP.cita_prof AND CH.cita_fech = CHP.cita_fech AND CH.cita_extra = CHP.cita_extra AND
                    CH.cita_hora = CHP.cita_hora INNER JOIN [INRDIS_II].[dbo].[tblUO] UO ON CH.uo_cod = UO.uo_cod INNER JOIN
                    [INRDIS_II].[dbo].[tblTipoCita_Citado] TCC ON CHP.tcitaa_cod = TCC.tcitaa_cod INNER JOIN 
                    [INRDIS_II].[dbo].[TipoActividad] TA ON TCC.tipoActividad_id = TA.id INNER JOIN 
                    [INRDIS_II].[dbo].[ActividadEspecifica] AE ON TCC.actividadEspecifica_id = AE.id INNER JOIN
                    [INRDIS_II].[dbo].[Persona] P ON CHP.pers_id = P.id LEFT OUTER JOIN 
                    [INRDIS_II].[dbo].[tblAsistencia] A ON CHP.asis_cod = A.asis_cod LEFT OUTER JOIN
                    [ECONOMIA].[dbo].[FUA2] FU ON CHP.FUA_id = FU.IdAtencion LEFT OUTER JOIN 
                    [INRDIS_II].[dbo].[AsistenciaCita] AC ON A.AsistenciaCita_id = AC.id LEFT JOIN
                    [INRDIS_II].[dbo].[tbPersonal] PE ON CH.cita_prof = PE.id
            
                     
            WHERE P.hcl_num = :hcl_num1 AND (CHP.fina_cod IN ('02','08') OR CHP.fina_cod 
                  IN ('2 ','8 ')) AND CH.serv_cod <> 41 AND FU.IdAtencion IS NOT NULL AND AE.id NOT IN (41,42,43,44,45,46,47,48,49,50,51)
                  AND CHP.id_usuario = :IdUsuario1
            
            UNION ALL
            
            SELECT TOP (100) PERCENT PDD.Financiador_id as Financiador_id,
            CASE WHEN PDD.Financiador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
            FORMAT(CAST(PDC.fecha AS DATE),'dd-MM-yyyy') + ' ' + LEFT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) + ':' + 
            RIGHT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) as Fecha,
            FORMAT(CAST(PDC.fecha AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(PDC.horaInicia,2) + ':' + RIGHT(PDC.horaInicia,2) AS Hora,
            P.id AS id_Paciente,P.apPaterno + ' ' + P.apMaterno + ' ' + P.nombres AS Paciente,
            P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
            CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
            WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
            
            /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            CASE 
            WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
            WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
            ELSE 'OTRO' END AS TipoDocumento,
            CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
            /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
            P.hcl_num as HistoriaClinica,FU.DISA + '-' + FU.Lote + '-' + FU.Numero as FUA,
            FU.IdAtencion as Fua_id,
            TA.description AS TipoActividad,
            LEFT(AE.description,20) as ActividadEspecifica,
            PE.id AS Personal_id,
            PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
           
            /* INICIO DE ESTADO CITA */
            CASE WHEN PDD.AsistenciaCita_id IS NULL THEN 10 ELSE PDD.AsistenciaCita_id END AS EstadoCita_id,
            CASE WHEN AC.abreviado IS NULL THEN 'CITADO' 
                 WHEN AC.id = 19 THEN 'NO ATENDIDO'
                 WHEN AC.id = 18 THEN 'NO ATENDIDO'
                 WHEN AC.id = 17 THEN 'NO ATENDIDO'
                 WHEN AC.id = 13 THEN 'NO ATENDIDO'
                 WHEN AC.id = 2 THEN 'NO ATENDIDO'
                 WHEN AC.id = 15 THEN 'NO ATENDIDO'
                 WHEN AC.id = 3  THEN 'ATENDIDO'
                 WHEN AC.id = 4  THEN 'ATENDIDO'
                 ELSE AC.abreviado END AS EstadoCita,
            /* FIN DE ESTADO CITA */
            'PDIARIO' AS Modelo, 
           
            CASE WHEN PDC.ModoAtencionProfesional_id = 1 THEN 'PRESENCIAL'
            WHEN PDC.ModoAtencionProfesional_id = 5 THEN 'EXTRA'
            WHEN PDC.ModoAtencionProfesional_id = 7 THEN 'NO PRESENCIAL'
            WHEN PDC.ModoAtencionProfesional_id = 6 THEN 'H-COMPL.'
            END AS TipoAtencion,
           
            UO.description AS UnidadOrganica,
            'MEDICOS' AS GrupoProfesional,
            PDD.id AS idRegistro,
            PDD.nroSesion AS numeroSesion,
            NULL AS idIdentificador,
            PDD.Cie1_cod AS CodigoCie,
            NULL AS Diagnostico
                    
            
            FROM [INRDIS_II].[dbo].[PDiarioDetalles] PDD INNER JOIN
                 [INRDIS_II].[dbo].[PDiarioCabecera] PDC ON PDD.PDiarioCabecera_id = PDC.id INNER JOIN 
                 [INRDIS_II].[dbo].[TipoActividad] TA ON PDC.tipoActividad_id = TA.id INNER JOIN 
                 [INRDIS_II].[dbo].[ActividadEspecifica] AE ON PDD.actividadEspecifica_id = AE.id INNER JOIN
                 [INRDIS_II].[dbo].[UnidadOrganica] UO ON PDC.UnidadOrganica_id = UO.id INNER JOIN
                 [INRDIS_II].[dbo].[Persona] P ON PDD.Persona_id = P.id LEFT JOIN 
                 [INRDIS_II].[dbo].[tbPersonal] PE ON PDC.PersonalAtiende_id = PE.id LEFT JOIN
                 [INRDIS_II].[dbo].[AsistenciaCita] AC ON PDD.AsistenciaCita_id = AC.id LEFT JOIN
                 [ECONOMIA].[dbo].[FUA2] FU ON PDD.FUA_id= FU.IdAtencion
            
            WHERE P.hcl_num = :hcl_num2 AND PDD.Financiador_id IN (2,8) 
                  AND FU.IdAtencion IS NOT NULL AND AE.id NOT IN (41,42,43,44,45,46,47,48,49,50,51) AND PDD.id_usuario = :IdUsuario2
            
            UNION ALL
            
            SELECT TOP (100) PERCENT SPD.tbFinanciador_id as Financiador_id,CASE WHEN SPD.tbFinanciador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
            FORMAT(CAST(SPD.fechaProgramada AS DATE),'dd-MM-yyyy') + ' ' + LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) as Fecha,
            FORMAT(CAST(SPD.fechaProgramada AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) AS Hora,
            P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
            P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
            CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
            WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
           
            /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            CASE WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
            WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
            ELSE 'OTRO' END AS TipoDocumento,
            CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
            /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
            /* INICIO DE HISTORIA CLINICA*/
            CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
            /* FIN DE HISTORIA CLINICA*/
           
            /* INICIO DE FUA */
            FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,
            FU.IdAtencion as Fua_id,
            /* FIN DE FUA */
           
            'SESION' AS TipoActividad,
            LEFT(S.descripcion,20) AS ActividadEspecifica,
            PE.id AS Personal_id,
            PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
           
            /* INICIO DE ESTADO CITA */
            SPD.tbAsistencia_id AS EstadoCita_id,
            CASE WHEN A.abreviatura IS NULL THEN 'CITADO' 
                 WHEN A.id = 19 THEN 'NO ATENDIDO'
                 WHEN A.id = 18 THEN 'NO ATENDIDO'
                 WHEN A.id = 17 THEN 'NO ATENDIDO'
                 WHEN A.id = 13 THEN 'NO ATENDIDO'
                 WHEN A.id = 2 THEN 'NO ATENDIDO'
                 WHEN A.id = 15 THEN 'NO ATENDIDO'
                 WHEN A.id = 3  THEN 'ATENDIDO'
                 WHEN A.id = 4  THEN 'ATENDIDO'
                 WHEN A.id = 22  THEN 'NO ATENDIDO'
                 ELSE A.abreviatura END COLLATE Modern_Spanish_CI_AS AS EstadoCita,
            /* FIN DE ESTADO CITA */
           
            'SERVICIODET' AS Modelo,
           
            CASE WHEN SPD.tbModoCita_id = 1 THEN 'PRESENCIAL'
            WHEN SPD.tbModoCita_id = 5 THEN 'EXTRA'
            WHEN SPD.tbModoCita_id = 7 THEN 'NO PRESENCIAL'
            WHEN SPD.tbModoCita_id = 6 THEN 'H-COMPL.'
            END AS TipoAtencion,
           
            UO.descripcion AS UnidadOrganica,
            CASE WHEN GP.id = 2 THEN 'TERAPISTAS'
                 WHEN GP.id = 13 THEN 'MEDICOS'
                 WHEN GP.id = 14 THEN 'MEDICOS'
                 WHEN GP.id = 21 THEN 'MEDICOS'
                 ELSE GP.descripcion END AS GrupoProfesional,
            SPD.id AS idRegistro,
            SPD.nroSesion AS numeroSesion,
            SPD.ServicioPersonaCab_id AS idIdentificador,
            SPC.tbCIE1_codigo AS CodigoCie,
            NULL AS Diagnostico
            
            FROM    [INRDIS_II].[dbo].[tbServicio] S INNER JOIN [INRDIS_II].[dbo].[tbServicio_UOrganica] SUO
                    ON S.id = SUO.tbServicio_id INNER JOIN [INRDIS_II].[dbo].[ServicioPersonaCab] SPC INNER JOIN
                    [INRDIS_II].[dbo].[ServicioPersonaDet] SPD ON SPC.id = SPD.ServicioPersonaCab_id LEFT JOIN
                    [INRDIS_II].[dbo].[tbAsistencia] A ON SPD.tbAsistencia_id = A.id INNER JOIN
                    [INRDIS_II].[dbo].[Persona] P ON SPC.Persona_id = P.id ON SUO.id = SPC.tbServicio_UOrganica_id INNER JOIN
                    [INRDIS_II].[dbo].[tbPersonal] PE ON SPD.tbPersonal_id = PE.id INNER JOIN
                    [INRDIS_II].[dbo].[tbUOrganica] UO ON SUO.tbUOrganica_id = UO.id LEFT OUTER JOIN
                    [ECONOMIA].[dbo].[FUA2] FU ON SPD.sustentoPago_id = FU.IdAtencion LEFT JOIN
                    [INRDIS_II].[dbo].[tbGrupoProfesional] GP ON SUO.tbGrupoProfesional_id = GP.id
            
            WHERE P.hcl_num = :hcl_num3 AND SPD.tbFinanciador_id IN (2,8) AND
                      (SPD.tbEstado_id <> 8 AND SPD.tbEstado_id <> 11) AND SUO.tbGrupoProfesional_id IN (2,4,6,7,11,13,14,18,21,23)
                      AND FU.IdAtencion IS NOT NULL AND SUO.tbServicio_id IN (6,7,8,9,27,28,115) AND SPD.id_usuario = :IdUsuario3",
                    ["hcl_num1"=>$request->numHistoriaBD_fuasObservados,
                     "hcl_num2"=>$request->numHistoriaBD_fuasObservados,
                     "hcl_num3"=>$request->numHistoriaBD_fuasObservados,
                     "IdUsuario1"=>$usuario_fuasObservados,
                     "IdUsuario2"=>$usuario_fuasObservados,
                     "IdUsuario3"=>$usuario_fuasObservados]))->make(true);
               }

          }
     }

     public function buscarPorDocumentoBD(Request $request){

          $usuario_fuasObservados = auth()->id();

          if(request()->ajax()){

               if(auth()->user()->id_rol == 1){
                    return datatables()->of(DB::SELECT("SELECT TOP (100) PERCENT CHP.fina_cod as Financiador_id,
                    CASE WHEN CHP.fina_cod = '02' THEN 'S.I.S.' WHEN CHP.fina_cod = '2 ' THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                    FORMAT(CAST(CH.cita_fech AS DATE), 'dd-MM-yyyy') + ' ' + LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) as Fecha,
                    FORMAT(CAST(CH.cita_fech AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) AS Hora,
                    P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
                    P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                    CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                    WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
            
                    /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                    CASE 
                    WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                    WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                    ELSE 'OTRO' END AS TipoDocumento,
                    CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                    /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
                    /* INICIO DE HISTORIA CLINICA*/
                    CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
                    /* FIN DE HISTORIA CLINICA*/
            
                    /* INICIO DE FUA */
                    FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,
                    FU.IdAtencion as Fua_id,
                    /* FIN DE FUA */
            
                    TA.description AS TipoActividad,
                    LEFT(AE.description,20) AS ActividadEspecifica,
                    PE.id AS Personal_id,
                    PE.apPaterno +' '+ PE.apMaterno +' '+ PE.nombres AS Profesional, 
            
                    /* INICIO DE ESTADO CITA */
                    CASE WHEN CHP.asis_cod IS NULL THEN 10 ELSE A.AsistenciaCita_id END AS EstadoCita_id,
                    CASE WHEN AC.abreviado IS NULL THEN 'CITADO'  
                         WHEN AC.id = 19 THEN 'NO ATENDIDO'
                         WHEN AC.id = 18 THEN 'NO ATENDIDO'
                         WHEN AC.id = 17 THEN 'NO ATENDIDO'
                         WHEN AC.id = 13 THEN 'NO ATENDIDO'
                         WHEN AC.id = 2 THEN 'NO ATENDIDO'
                         WHEN AC.id = 15 THEN 'NO ATENDIDO'
                         WHEN AC.id = 3  THEN 'ATENDIDO'
                         WHEN AC.id = 4  THEN 'ATENDIDO'
                         ELSE AC.abreviado END AS EstadoCita,
               /* FIN DE ESTADO CITA */
                    /* FIN DE ESTADO CITA */
            
                    'CITASHORA' AS Modelo,
           
                    CASE WHEN CH.modoap_cod = 1 THEN 'PRESENCIAL'
                         WHEN CH.modoap_cod = 5 THEN 'EXTRA'
                         WHEN CH.modoap_cod = 7 THEN 'NO PRESENCIAL'
                         WHEN CH.modoap_cod = 6 THEN 'H-COMPL.'
                         END AS TipoAtencion,
           
                    CASE WHEN UO.uo_desc = 'DPTO. AMPUTADOS Y QUEMADOS' THEN 'DPTO. A. Q. Y T-POSTURALES' ELSE UO.uo_desc END AS UnidadOrganica,
                    CASE WHEN CH.serv_cod = 11 THEN 'MEDICOS' ELSE 'NUTRICIONISTAS' END AS GrupoProfesional,
                    CHP.cext_id AS idRegistro,
                    NULL AS numeroSesion,
                    NULL AS idIdentificador,
                    CHP.cie_cod_dano1 AS CodigoCie,
                    CHP.diag_cod_dano1 AS Diagnostico
            
            FROM    [INRDIS_II].[dbo].[CitasHora] CH INNER JOIN 
                    [INRDIS_II].[dbo].[CitasHora_Paciente] CHP ON CH.cita_prof = CHP.cita_prof AND CH.cita_fech = CHP.cita_fech AND CH.cita_extra = CHP.cita_extra AND
                    CH.cita_hora = CHP.cita_hora INNER JOIN [INRDIS_II].[dbo].[tblUO] UO ON CH.uo_cod = UO.uo_cod INNER JOIN
                    [INRDIS_II].[dbo].[tblTipoCita_Citado] TCC ON CHP.tcitaa_cod = TCC.tcitaa_cod INNER JOIN 
                    [INRDIS_II].[dbo].[TipoActividad] TA ON TCC.tipoActividad_id = TA.id INNER JOIN 
                    [INRDIS_II].[dbo].[ActividadEspecifica] AE ON TCC.actividadEspecifica_id = AE.id INNER JOIN
                    [INRDIS_II].[dbo].[Persona] P ON CHP.pers_id = P.id LEFT OUTER JOIN 
                    [INRDIS_II].[dbo].[tblAsistencia] A ON CHP.asis_cod = A.asis_cod LEFT OUTER JOIN
                    [ECONOMIA].[dbo].[FUA2] FU ON CHP.FUA_id = FU.IdAtencion LEFT OUTER JOIN 
                    [INRDIS_II].[dbo].[AsistenciaCita] AC ON A.AsistenciaCita_id = AC.id LEFT JOIN
                    [INRDIS_II].[dbo].[tbPersonal] PE ON CH.cita_prof = PE.id
            
                     
            WHERE P.nroDocIdentidad = CONVERT(varchar(11), :nroDocIdentidad1) AND (CHP.fina_cod IN ('02','08') OR CHP.fina_cod 
                  IN ('2 ','8 ')) AND CH.serv_cod <> 41 AND FU.IdAtencion IS NOT NULL AND AE.id NOT IN (41,42,43,44,45,46,47,48,49,50,51)
                  
            UNION ALL
            
            SELECT TOP (100) PERCENT PDD.Financiador_id as Financiador_id,
            CASE WHEN PDD.Financiador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
            FORMAT(CAST(PDC.fecha AS DATE),'dd-MM-yyyy') + ' ' + LEFT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) + ':' + 
            RIGHT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) as Fecha,
            FORMAT(CAST(PDC.fecha AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(PDC.horaInicia,2) + ':' + RIGHT(PDC.horaInicia,2) AS Hora,
            P.id AS id_Paciente,P.apPaterno + ' ' + P.apMaterno + ' ' + P.nombres AS Paciente,
            P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
            CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
            WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
            
            /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            CASE 
            WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
            WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
            ELSE 'OTRO' END AS TipoDocumento,
            CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
            /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
            P.hcl_num as HistoriaClinica,FU.DISA + '-' + FU.Lote + '-' + FU.Numero as FUA,
            FU.IdAtencion as Fua_id,
            TA.description AS TipoActividad,
            LEFT(AE.description,20) as ActividadEspecifica,
            PE.id AS Personal_id,
            PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
           
            /* INICIO DE ESTADO CITA */
            CASE WHEN PDD.AsistenciaCita_id IS NULL THEN 10 ELSE PDD.AsistenciaCita_id END AS EstadoCita_id,
            CASE WHEN AC.abreviado IS NULL THEN 'CITADO' 
                 WHEN AC.id = 19 THEN 'NO ATENDIDO'
                 WHEN AC.id = 18 THEN 'NO ATENDIDO'
                 WHEN AC.id = 17 THEN 'NO ATENDIDO'
                 WHEN AC.id = 13 THEN 'NO ATENDIDO'
                 WHEN AC.id = 2 THEN 'NO ATENDIDO'
                 WHEN AC.id = 15 THEN 'NO ATENDIDO'
                 WHEN AC.id = 3  THEN 'ATENDIDO'
                 WHEN AC.id = 4  THEN 'ATENDIDO'
                 ELSE AC.abreviado END AS EstadoCita,
            /* FIN DE ESTADO CITA */
            'PDIARIO' AS Modelo, 
           
            CASE WHEN PDC.ModoAtencionProfesional_id = 1 THEN 'PRESENCIAL'
            WHEN PDC.ModoAtencionProfesional_id = 5 THEN 'EXTRA'
            WHEN PDC.ModoAtencionProfesional_id = 7 THEN 'NO PRESENCIAL'
            WHEN PDC.ModoAtencionProfesional_id = 6 THEN 'H-COMPL.'
            END AS TipoAtencion,
           
            UO.description AS UnidadOrganica,
            'MEDICOS' AS GrupoProfesional,
            PDD.id AS idRegistro,
            PDD.nroSesion AS numeroSesion,
            NULL AS idIdentificador,
            PDD.Cie1_cod AS CodigoCie,
            NULL AS Diagnostico
                    
            
            FROM [INRDIS_II].[dbo].[PDiarioDetalles] PDD INNER JOIN
                 [INRDIS_II].[dbo].[PDiarioCabecera] PDC ON PDD.PDiarioCabecera_id = PDC.id INNER JOIN 
                 [INRDIS_II].[dbo].[TipoActividad] TA ON PDC.tipoActividad_id = TA.id INNER JOIN 
                 [INRDIS_II].[dbo].[ActividadEspecifica] AE ON PDD.actividadEspecifica_id = AE.id INNER JOIN
                 [INRDIS_II].[dbo].[UnidadOrganica] UO ON PDC.UnidadOrganica_id = UO.id INNER JOIN
                 [INRDIS_II].[dbo].[Persona] P ON PDD.Persona_id = P.id LEFT JOIN 
                 [INRDIS_II].[dbo].[tbPersonal] PE ON PDC.PersonalAtiende_id = PE.id LEFT JOIN
                 [INRDIS_II].[dbo].[AsistenciaCita] AC ON PDD.AsistenciaCita_id = AC.id LEFT JOIN
                 [ECONOMIA].[dbo].[FUA2] FU ON PDD.FUA_id= FU.IdAtencion
            
            WHERE P.nroDocIdentidad = CONVERT(varchar(11), :nroDocIdentidad2) AND PDD.Financiador_id IN (2,8) 
                  AND FU.IdAtencion IS NOT NULL AND AE.id NOT IN (41,42,43,44,45,46,47,48,49,50,51)
            
            UNION ALL
            
            SELECT TOP (100) PERCENT SPD.tbFinanciador_id as Financiador_id,CASE WHEN SPD.tbFinanciador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
            FORMAT(CAST(SPD.fechaProgramada AS DATE),'dd-MM-yyyy') + ' ' + LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) as Fecha,
            FORMAT(CAST(SPD.fechaProgramada AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) AS Hora,
            P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
            P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
            CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
            WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
           
            /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            CASE WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
            WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
            ELSE 'OTRO' END AS TipoDocumento,
            CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
            /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
            /* INICIO DE HISTORIA CLINICA*/
            CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
            /* FIN DE HISTORIA CLINICA*/
           
            /* INICIO DE FUA */
            FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,
            FU.IdAtencion as Fua_id,
            /* FIN DE FUA */
           
            'SESION' AS TipoActividad,
            LEFT(S.descripcion,20) AS ActividadEspecifica,
            PE.id AS Personal_id,
            PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
           
            /* INICIO DE ESTADO CITA */
            SPD.tbAsistencia_id AS EstadoCita_id,
            CASE WHEN A.abreviatura IS NULL THEN 'CITADO' 
                 WHEN A.id = 19 THEN 'NO ATENDIDO'
                 WHEN A.id = 18 THEN 'NO ATENDIDO'
                 WHEN A.id = 17 THEN 'NO ATENDIDO'
                 WHEN A.id = 13 THEN 'NO ATENDIDO'
                 WHEN A.id = 2 THEN 'NO ATENDIDO'
                 WHEN A.id = 15 THEN 'NO ATENDIDO'
                 WHEN A.id = 3  THEN 'ATENDIDO'
                 WHEN A.id = 4  THEN 'ATENDIDO'
                 WHEN A.id = 22  THEN 'NO ATENDIDO'
                 ELSE A.abreviatura END COLLATE Modern_Spanish_CI_AS AS EstadoCita,
            /* FIN DE ESTADO CITA */
           
            'SERVICIODET' AS Modelo,
           
            CASE WHEN SPD.tbModoCita_id = 1 THEN 'PRESENCIAL'
            WHEN SPD.tbModoCita_id = 5 THEN 'EXTRA'
            WHEN SPD.tbModoCita_id = 7 THEN 'NO PRESENCIAL'
            WHEN SPD.tbModoCita_id = 6 THEN 'H-COMPL.'
            END AS TipoAtencion,
           
            UO.descripcion AS UnidadOrganica,
            CASE WHEN GP.id = 2 THEN 'TERAPISTAS'
                 WHEN GP.id = 13 THEN 'MEDICOS'
                 WHEN GP.id = 14 THEN 'MEDICOS'
                 WHEN GP.id = 21 THEN 'MEDICOS'
                 ELSE GP.descripcion END AS GrupoProfesional,
            SPD.id AS idRegistro,
            SPD.nroSesion AS numeroSesion,
            SPD.ServicioPersonaCab_id AS idIdentificador,
            SPC.tbCIE1_codigo AS CodigoCie,
            NULL AS Diagnostico
            
            FROM    [INRDIS_II].[dbo].[tbServicio] S INNER JOIN [INRDIS_II].[dbo].[tbServicio_UOrganica] SUO
                    ON S.id = SUO.tbServicio_id INNER JOIN [INRDIS_II].[dbo].[ServicioPersonaCab] SPC INNER JOIN
                    [INRDIS_II].[dbo].[ServicioPersonaDet] SPD ON SPC.id = SPD.ServicioPersonaCab_id LEFT JOIN
                    [INRDIS_II].[dbo].[tbAsistencia] A ON SPD.tbAsistencia_id = A.id INNER JOIN
                    [INRDIS_II].[dbo].[Persona] P ON SPC.Persona_id = P.id ON SUO.id = SPC.tbServicio_UOrganica_id INNER JOIN
                    [INRDIS_II].[dbo].[tbPersonal] PE ON SPD.tbPersonal_id = PE.id INNER JOIN
                    [INRDIS_II].[dbo].[tbUOrganica] UO ON SUO.tbUOrganica_id = UO.id LEFT OUTER JOIN
                    [ECONOMIA].[dbo].[FUA2] FU ON SPD.sustentoPago_id = FU.IdAtencion LEFT JOIN
                    [INRDIS_II].[dbo].[tbGrupoProfesional] GP ON SUO.tbGrupoProfesional_id = GP.id
            
            WHERE P.nroDocIdentidad = CONVERT(varchar(11), :nroDocIdentidad3) AND SPD.tbFinanciador_id IN (2,8) AND
                      (SPD.tbEstado_id <> 8 AND SPD.tbEstado_id <> 11) AND SUO.tbGrupoProfesional_id IN (2,4,6,7,11,13,14,18,21,23)
                      AND FU.IdAtencion IS NOT NULL AND SUO.tbServicio_id IN (6,7,8,9,27,28,115)",
                    ["nroDocIdentidad1"=>$request->numDocumentoBD_fuasObservados,
                     "nroDocIdentidad2"=>$request->numDocumentoBD_fuasObservados,
                     "nroDocIdentidad3"=>$request->numDocumentoBD_fuasObservados]))->make(true);
               }else{
                    return datatables()->of(DB::SELECT("SELECT TOP (100) PERCENT CHP.fina_cod as Financiador_id,
                    CASE WHEN CHP.fina_cod = '02' THEN 'S.I.S.' WHEN CHP.fina_cod = '2 ' THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                    FORMAT(CAST(CH.cita_fech AS DATE), 'dd-MM-yyyy') + ' ' + LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) as Fecha,
                    FORMAT(CAST(CH.cita_fech AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) AS Hora,
                    P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
                    P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                    CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                    WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
            
                    /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                    CASE 
                    WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                    WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                    ELSE 'OTRO' END AS TipoDocumento,
                    CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                    /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
                    /* INICIO DE HISTORIA CLINICA*/
                    CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
                    /* FIN DE HISTORIA CLINICA*/
            
                    /* INICIO DE FUA */
                    FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,
                    FU.IdAtencion as Fua_id,
                    /* FIN DE FUA */
            
                    TA.description AS TipoActividad,
                    LEFT(AE.description,20) AS ActividadEspecifica,
                    PE.id AS Personal_id,
                    PE.apPaterno +' '+ PE.apMaterno +' '+ PE.nombres AS Profesional, 
            
                    /* INICIO DE ESTADO CITA */
                    CASE WHEN CHP.asis_cod IS NULL THEN 10 ELSE A.AsistenciaCita_id END AS EstadoCita_id,
                    CASE WHEN AC.abreviado IS NULL THEN 'CITADO'  
                         WHEN AC.id = 19 THEN 'NO ATENDIDO'
                         WHEN AC.id = 18 THEN 'NO ATENDIDO'
                         WHEN AC.id = 17 THEN 'NO ATENDIDO'
                         WHEN AC.id = 13 THEN 'NO ATENDIDO'
                         WHEN AC.id = 2 THEN 'NO ATENDIDO'
                         WHEN AC.id = 15 THEN 'NO ATENDIDO'
                         WHEN AC.id = 3  THEN 'ATENDIDO'
                         WHEN AC.id = 4  THEN 'ATENDIDO'
                         ELSE AC.abreviado END AS EstadoCita,
               /* FIN DE ESTADO CITA */
                    /* FIN DE ESTADO CITA */
            
                    'CITASHORA' AS Modelo,
           
                    CASE WHEN CH.modoap_cod = 1 THEN 'PRESENCIAL'
                         WHEN CH.modoap_cod = 5 THEN 'EXTRA'
                         WHEN CH.modoap_cod = 7 THEN 'NO PRESENCIAL'
                         WHEN CH.modoap_cod = 6 THEN 'H-COMPL.'
                         END AS TipoAtencion,
           
                    CASE WHEN UO.uo_desc = 'DPTO. AMPUTADOS Y QUEMADOS' THEN 'DPTO. A. Q. Y T-POSTURALES' ELSE UO.uo_desc END AS UnidadOrganica,
                    CASE WHEN CH.serv_cod = 11 THEN 'MEDICOS' ELSE 'NUTRICIONISTAS' END AS GrupoProfesional,
                    CHP.cext_id AS idRegistro,
                    NULL AS numeroSesion,
                    NULL AS idIdentificador,
                    CHP.cie_cod_dano1 AS CodigoCie,
                    CHP.diag_cod_dano1 AS Diagnostico
            
            FROM    [INRDIS_II].[dbo].[CitasHora] CH INNER JOIN 
                    [INRDIS_II].[dbo].[CitasHora_Paciente] CHP ON CH.cita_prof = CHP.cita_prof AND CH.cita_fech = CHP.cita_fech AND CH.cita_extra = CHP.cita_extra AND
                    CH.cita_hora = CHP.cita_hora INNER JOIN [INRDIS_II].[dbo].[tblUO] UO ON CH.uo_cod = UO.uo_cod INNER JOIN
                    [INRDIS_II].[dbo].[tblTipoCita_Citado] TCC ON CHP.tcitaa_cod = TCC.tcitaa_cod INNER JOIN 
                    [INRDIS_II].[dbo].[TipoActividad] TA ON TCC.tipoActividad_id = TA.id INNER JOIN 
                    [INRDIS_II].[dbo].[ActividadEspecifica] AE ON TCC.actividadEspecifica_id = AE.id INNER JOIN
                    [INRDIS_II].[dbo].[Persona] P ON CHP.pers_id = P.id LEFT OUTER JOIN 
                    [INRDIS_II].[dbo].[tblAsistencia] A ON CHP.asis_cod = A.asis_cod LEFT OUTER JOIN
                    [ECONOMIA].[dbo].[FUA2] FU ON CHP.FUA_id = FU.IdAtencion LEFT OUTER JOIN 
                    [INRDIS_II].[dbo].[AsistenciaCita] AC ON A.AsistenciaCita_id = AC.id LEFT JOIN
                    [INRDIS_II].[dbo].[tbPersonal] PE ON CH.cita_prof = PE.id
            
                     
            WHERE P.nroDocIdentidad = CONVERT(varchar(11), :nroDocIdentidad1) AND (CHP.fina_cod IN ('02','08') OR CHP.fina_cod 
                  IN ('2 ','8 ')) AND CH.serv_cod <> 41 AND FU.IdAtencion IS NOT NULL AND AE.id NOT IN (41,42,43,44,45,46,47,48,49,50,51)
                  AND CHP.id_usuario = :IdUsuario1
                  
            
            UNION ALL
            
            SELECT TOP (100) PERCENT PDD.Financiador_id as Financiador_id,
            CASE WHEN PDD.Financiador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
            FORMAT(CAST(PDC.fecha AS DATE),'dd-MM-yyyy') + ' ' + LEFT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) + ':' + 
            RIGHT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) as Fecha,
            FORMAT(CAST(PDC.fecha AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(PDC.horaInicia,2) + ':' + RIGHT(PDC.horaInicia,2) AS Hora,
            P.id AS id_Paciente,P.apPaterno + ' ' + P.apMaterno + ' ' + P.nombres AS Paciente,
            P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
            CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
            WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
            
            /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            CASE 
            WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
            WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
            ELSE 'OTRO' END AS TipoDocumento,
            CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
            /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
            P.hcl_num as HistoriaClinica,FU.DISA + '-' + FU.Lote + '-' + FU.Numero as FUA,
            FU.IdAtencion as Fua_id,
            TA.description AS TipoActividad,
            LEFT(AE.description,20) as ActividadEspecifica,
            PE.id AS Personal_id,
            PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
           
            /* INICIO DE ESTADO CITA */
            CASE WHEN PDD.AsistenciaCita_id IS NULL THEN 10 ELSE PDD.AsistenciaCita_id END AS EstadoCita_id,
            CASE WHEN AC.abreviado IS NULL THEN 'CITADO' 
                 WHEN AC.id = 19 THEN 'NO ATENDIDO'
                 WHEN AC.id = 18 THEN 'NO ATENDIDO'
                 WHEN AC.id = 17 THEN 'NO ATENDIDO'
                 WHEN AC.id = 13 THEN 'NO ATENDIDO'
                 WHEN AC.id = 2 THEN 'NO ATENDIDO'
                 WHEN AC.id = 15 THEN 'NO ATENDIDO'
                 WHEN AC.id = 3  THEN 'ATENDIDO'
                 WHEN AC.id = 4  THEN 'ATENDIDO'
                 ELSE AC.abreviado END AS EstadoCita,
            /* FIN DE ESTADO CITA */
            'PDIARIO' AS Modelo, 
           
            CASE WHEN PDC.ModoAtencionProfesional_id = 1 THEN 'PRESENCIAL'
            WHEN PDC.ModoAtencionProfesional_id = 5 THEN 'EXTRA'
            WHEN PDC.ModoAtencionProfesional_id = 7 THEN 'NO PRESENCIAL'
            WHEN PDC.ModoAtencionProfesional_id = 6 THEN 'H-COMPL.'
            END AS TipoAtencion,
           
            UO.description AS UnidadOrganica,
            'MEDICOS' AS GrupoProfesional,
            PDD.id AS idRegistro,
            PDD.nroSesion AS numeroSesion,
            NULL AS idIdentificador,
            PDD.Cie1_cod AS CodigoCie,
            NULL AS Diagnostico
                    
            
            FROM [INRDIS_II].[dbo].[PDiarioDetalles] PDD INNER JOIN
                 [INRDIS_II].[dbo].[PDiarioCabecera] PDC ON PDD.PDiarioCabecera_id = PDC.id INNER JOIN 
                 [INRDIS_II].[dbo].[TipoActividad] TA ON PDC.tipoActividad_id = TA.id INNER JOIN 
                 [INRDIS_II].[dbo].[ActividadEspecifica] AE ON PDD.actividadEspecifica_id = AE.id INNER JOIN
                 [INRDIS_II].[dbo].[UnidadOrganica] UO ON PDC.UnidadOrganica_id = UO.id INNER JOIN
                 [INRDIS_II].[dbo].[Persona] P ON PDD.Persona_id = P.id LEFT JOIN 
                 [INRDIS_II].[dbo].[tbPersonal] PE ON PDC.PersonalAtiende_id = PE.id LEFT JOIN
                 [INRDIS_II].[dbo].[AsistenciaCita] AC ON PDD.AsistenciaCita_id = AC.id LEFT JOIN
                 [ECONOMIA].[dbo].[FUA2] FU ON PDD.FUA_id= FU.IdAtencion
            
            WHERE P.nroDocIdentidad = CONVERT(varchar(11), :nroDocIdentidad2) AND PDD.Financiador_id IN (2,8) 
                  AND FU.IdAtencion IS NOT NULL AND AE.id NOT IN (41,42,43,44,45,46,47,48,49,50,51)
                  AND PDD.id_usuario = :IdUsuario2
            
            UNION ALL
            
            SELECT TOP (100) PERCENT SPD.tbFinanciador_id as Financiador_id,CASE WHEN SPD.tbFinanciador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
            FORMAT(CAST(SPD.fechaProgramada AS DATE),'dd-MM-yyyy') + ' ' + LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) as Fecha,
            FORMAT(CAST(SPD.fechaProgramada AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) AS Hora,
            P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
            P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
            CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
            WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
           
            /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            CASE WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
            WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
            ELSE 'OTRO' END AS TipoDocumento,
            CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
            /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
            /* INICIO DE HISTORIA CLINICA*/
            CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
            /* FIN DE HISTORIA CLINICA*/
           
            /* INICIO DE FUA */
            FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,
            FU.IdAtencion as Fua_id,
            /* FIN DE FUA */
           
            'SESION' AS TipoActividad,
            LEFT(S.descripcion,20) AS ActividadEspecifica,
            PE.id AS Personal_id,
            PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
           
            /* INICIO DE ESTADO CITA */
            SPD.tbAsistencia_id AS EstadoCita_id,
            CASE WHEN A.abreviatura IS NULL THEN 'CITADO' 
                 WHEN A.id = 19 THEN 'NO ATENDIDO'
                 WHEN A.id = 18 THEN 'NO ATENDIDO'
                 WHEN A.id = 17 THEN 'NO ATENDIDO'
                 WHEN A.id = 13 THEN 'NO ATENDIDO'
                 WHEN A.id = 2 THEN 'NO ATENDIDO'
                 WHEN A.id = 15 THEN 'NO ATENDIDO'
                 WHEN A.id = 3  THEN 'ATENDIDO'
                 WHEN A.id = 4  THEN 'ATENDIDO'
                 WHEN A.id = 22  THEN 'NO ATENDIDO'
                 ELSE A.abreviatura END COLLATE Modern_Spanish_CI_AS AS EstadoCita,
            /* FIN DE ESTADO CITA */
           
            'SERVICIODET' AS Modelo,
           
            CASE WHEN SPD.tbModoCita_id = 1 THEN 'PRESENCIAL'
            WHEN SPD.tbModoCita_id = 5 THEN 'EXTRA'
            WHEN SPD.tbModoCita_id = 7 THEN 'NO PRESENCIAL'
            WHEN SPD.tbModoCita_id = 6 THEN 'H-COMPL.'
            END AS TipoAtencion,
           
            UO.descripcion AS UnidadOrganica,
            CASE WHEN GP.id = 2 THEN 'TERAPISTAS'
                 WHEN GP.id = 13 THEN 'MEDICOS'
                 WHEN GP.id = 14 THEN 'MEDICOS'
                 WHEN GP.id = 21 THEN 'MEDICOS'
                 ELSE GP.descripcion END AS GrupoProfesional,
            SPD.id AS idRegistro,
            SPD.nroSesion AS numeroSesion,
            SPD.ServicioPersonaCab_id AS idIdentificador,
            SPC.tbCIE1_codigo AS CodigoCie,
            NULL AS Diagnostico
            
            FROM    [INRDIS_II].[dbo].[tbServicio] S INNER JOIN [INRDIS_II].[dbo].[tbServicio_UOrganica] SUO
                    ON S.id = SUO.tbServicio_id INNER JOIN [INRDIS_II].[dbo].[ServicioPersonaCab] SPC INNER JOIN
                    [INRDIS_II].[dbo].[ServicioPersonaDet] SPD ON SPC.id = SPD.ServicioPersonaCab_id LEFT JOIN
                    [INRDIS_II].[dbo].[tbAsistencia] A ON SPD.tbAsistencia_id = A.id INNER JOIN
                    [INRDIS_II].[dbo].[Persona] P ON SPC.Persona_id = P.id ON SUO.id = SPC.tbServicio_UOrganica_id INNER JOIN
                    [INRDIS_II].[dbo].[tbPersonal] PE ON SPD.tbPersonal_id = PE.id INNER JOIN
                    [INRDIS_II].[dbo].[tbUOrganica] UO ON SUO.tbUOrganica_id = UO.id LEFT OUTER JOIN
                    [ECONOMIA].[dbo].[FUA2] FU ON SPD.sustentoPago_id = FU.IdAtencion LEFT JOIN
                    [INRDIS_II].[dbo].[tbGrupoProfesional] GP ON SUO.tbGrupoProfesional_id = GP.id
            
            WHERE P.nroDocIdentidad = CONVERT(varchar(11), :nroDocIdentidad3) AND SPD.tbFinanciador_id IN (2,8) AND
                      (SPD.tbEstado_id <> 8 AND SPD.tbEstado_id <> 11) AND SUO.tbGrupoProfesional_id IN (2,4,6,7,11,13,14,18,21,23)
                      AND FU.IdAtencion IS NOT NULL AND SUO.tbServicio_id IN (6,7,8,9,27,28,115) AND SPD.id_usuario = :IdUsuario3",
                    ["nroDocIdentidad1"=>$request->numDocumentoBD_fuasObservados,
                     "nroDocIdentidad2"=>$request->numDocumentoBD_fuasObservados,
                     "nroDocIdentidad3"=>$request->numDocumentoBD_fuasObservados,
                     "IdUsuario1"=>$usuario_fuasObservados,
                     "IdUsuario2"=>$usuario_fuasObservados,
                     "IdUsuario3"=>$usuario_fuasObservados]))->make(true);
               }
          }
     }

     public function buscarPorFuaBD(Request $request){

          $usuario_fuasObservados = auth()->id();

          if(request()->ajax()){

               if(auth()->user()->id_rol == 1){
                    return datatables()->of(DB::SELECT("SELECT TOP (100) PERCENT CHP.fina_cod as Financiador_id,
                    CASE WHEN CHP.fina_cod = '02' THEN 'S.I.S.' WHEN CHP.fina_cod = '2 ' THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                    FORMAT(CAST(CH.cita_fech AS DATE), 'dd-MM-yyyy') + ' ' + LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) as Fecha,
                    FORMAT(CAST(CH.cita_fech AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) AS Hora,
                    P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
                    P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                    CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                    WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
            
                    /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                    CASE 
                    WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                    WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                    ELSE 'OTRO' END AS TipoDocumento,
                    CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                    /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
                    /* INICIO DE HISTORIA CLINICA*/
                    CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
                    /* FIN DE HISTORIA CLINICA*/
            
                    /* INICIO DE FUA */
                    FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,
                    FU.IdAtencion as Fua_id,
                    /* FIN DE FUA */
            
                    TA.description AS TipoActividad,
                    LEFT(AE.description,20) AS ActividadEspecifica,
                    PE.id AS Personal_id,
                    PE.apPaterno +' '+ PE.apMaterno +' '+ PE.nombres AS Profesional, 
            
                    /* INICIO DE ESTADO CITA */
                    CASE WHEN CHP.asis_cod IS NULL THEN 10 ELSE A.AsistenciaCita_id END AS EstadoCita_id,
                    CASE WHEN AC.abreviado IS NULL THEN 'CITADO'  
                         WHEN AC.id = 19 THEN 'NO ATENDIDO'
                         WHEN AC.id = 18 THEN 'NO ATENDIDO'
                         WHEN AC.id = 17 THEN 'NO ATENDIDO'
                         WHEN AC.id = 13 THEN 'NO ATENDIDO'
                         WHEN AC.id = 2 THEN 'NO ATENDIDO'
                         WHEN AC.id = 15 THEN 'NO ATENDIDO'
                         WHEN AC.id = 3  THEN 'ATENDIDO'
                         WHEN AC.id = 4  THEN 'ATENDIDO'
                         ELSE AC.abreviado END AS EstadoCita,
               /* FIN DE ESTADO CITA */
                    /* FIN DE ESTADO CITA */
            
                    'CITASHORA' AS Modelo,
           
                    CASE WHEN CH.modoap_cod = 1 THEN 'PRESENCIAL'
                         WHEN CH.modoap_cod = 5 THEN 'EXTRA'
                         WHEN CH.modoap_cod = 7 THEN 'NO PRESENCIAL'
                         WHEN CH.modoap_cod = 6 THEN 'H-COMPL.'
                         END AS TipoAtencion,
           
                    CASE WHEN UO.uo_desc = 'DPTO. AMPUTADOS Y QUEMADOS' THEN 'DPTO. A. Q. Y T-POSTURALES' ELSE UO.uo_desc END AS UnidadOrganica,
                    CASE WHEN CH.serv_cod = 11 THEN 'MEDICOS' ELSE 'NUTRICIONISTAS' END AS GrupoProfesional,
                    CHP.cext_id AS idRegistro,
                    NULL AS numeroSesion,
                    NULL AS idIdentificador,
                    CHP.cie_cod_dano1 AS CodigoCie,
                    CHP.diag_cod_dano1 AS Diagnostico
            
            FROM    [INRDIS_II].[dbo].[CitasHora] CH INNER JOIN 
                    [INRDIS_II].[dbo].[CitasHora_Paciente] CHP ON CH.cita_prof = CHP.cita_prof AND CH.cita_fech = CHP.cita_fech AND CH.cita_extra = CHP.cita_extra AND
                    CH.cita_hora = CHP.cita_hora INNER JOIN [INRDIS_II].[dbo].[tblUO] UO ON CH.uo_cod = UO.uo_cod INNER JOIN
                    [INRDIS_II].[dbo].[tblTipoCita_Citado] TCC ON CHP.tcitaa_cod = TCC.tcitaa_cod INNER JOIN 
                    [INRDIS_II].[dbo].[TipoActividad] TA ON TCC.tipoActividad_id = TA.id INNER JOIN 
                    [INRDIS_II].[dbo].[ActividadEspecifica] AE ON TCC.actividadEspecifica_id = AE.id INNER JOIN
                    [INRDIS_II].[dbo].[Persona] P ON CHP.pers_id = P.id LEFT OUTER JOIN 
                    [INRDIS_II].[dbo].[tblAsistencia] A ON CHP.asis_cod = A.asis_cod LEFT OUTER JOIN
                    [ECONOMIA].[dbo].[FUA2] FU ON CHP.FUA_id = FU.IdAtencion LEFT OUTER JOIN 
                    [INRDIS_II].[dbo].[AsistenciaCita] AC ON A.AsistenciaCita_id = AC.id LEFT JOIN
                    [INRDIS_II].[dbo].[tbPersonal] PE ON CH.cita_prof = PE.id
            
                     
            WHERE CONVERT(int,FU.Numero) = :nroFua1 AND (CHP.fina_cod IN ('02','08') OR CHP.fina_cod 
                  IN ('2 ','8 ')) AND CH.serv_cod <> 41 AND FU.IdAtencion IS NOT NULL AND AE.id NOT IN (41,42,43,44,45,46,47,48,49,50,51)
            
            UNION ALL
            
            SELECT TOP (100) PERCENT PDD.Financiador_id as Financiador_id,
            CASE WHEN PDD.Financiador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
            FORMAT(CAST(PDC.fecha AS DATE),'dd-MM-yyyy') + ' ' + LEFT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) + ':' + 
            RIGHT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) as Fecha,
            FORMAT(CAST(PDC.fecha AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(PDC.horaInicia,2) + ':' + RIGHT(PDC.horaInicia,2) AS Hora,
            P.id AS id_Paciente,P.apPaterno + ' ' + P.apMaterno + ' ' + P.nombres AS Paciente,
            P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
            CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
            WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
            
            /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            CASE 
            WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
            WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
            ELSE 'OTRO' END AS TipoDocumento,
            CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
            /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
            P.hcl_num as HistoriaClinica,FU.DISA + '-' + FU.Lote + '-' + FU.Numero as FUA,
            FU.IdAtencion as Fua_id,
            TA.description AS TipoActividad,
            LEFT(AE.description,20) as ActividadEspecifica,
            PE.id AS Personal_id,
            PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
           
            /* INICIO DE ESTADO CITA */
            CASE WHEN PDD.AsistenciaCita_id IS NULL THEN 10 ELSE PDD.AsistenciaCita_id END AS EstadoCita_id,
            CASE WHEN AC.abreviado IS NULL THEN 'CITADO' 
                 WHEN AC.id = 19 THEN 'NO ATENDIDO'
                 WHEN AC.id = 18 THEN 'NO ATENDIDO'
                 WHEN AC.id = 17 THEN 'NO ATENDIDO'
                 WHEN AC.id = 13 THEN 'NO ATENDIDO'
                 WHEN AC.id = 2 THEN 'NO ATENDIDO'
                 WHEN AC.id = 15 THEN 'NO ATENDIDO'
                 WHEN AC.id = 3  THEN 'ATENDIDO'
                 WHEN AC.id = 4  THEN 'ATENDIDO'
                 ELSE AC.abreviado END AS EstadoCita,
            /* FIN DE ESTADO CITA */
            'PDIARIO' AS Modelo, 
           
            CASE WHEN PDC.ModoAtencionProfesional_id = 1 THEN 'PRESENCIAL'
            WHEN PDC.ModoAtencionProfesional_id = 5 THEN 'EXTRA'
            WHEN PDC.ModoAtencionProfesional_id = 7 THEN 'NO PRESENCIAL'
            WHEN PDC.ModoAtencionProfesional_id = 6 THEN 'H-COMPL.'
            END AS TipoAtencion,
           
            UO.description AS UnidadOrganica,
            'MEDICOS' AS GrupoProfesional,
            PDD.id AS idRegistro,
            PDD.nroSesion AS numeroSesion,
            NULL AS idIdentificador,
            PDD.Cie1_cod AS CodigoCie,
            NULL AS Diagnostico
                    
            
            FROM [INRDIS_II].[dbo].[PDiarioDetalles] PDD INNER JOIN
                 [INRDIS_II].[dbo].[PDiarioCabecera] PDC ON PDD.PDiarioCabecera_id = PDC.id INNER JOIN 
                 [INRDIS_II].[dbo].[TipoActividad] TA ON PDC.tipoActividad_id = TA.id INNER JOIN 
                 [INRDIS_II].[dbo].[ActividadEspecifica] AE ON PDD.actividadEspecifica_id = AE.id INNER JOIN
                 [INRDIS_II].[dbo].[UnidadOrganica] UO ON PDC.UnidadOrganica_id = UO.id INNER JOIN
                 [INRDIS_II].[dbo].[Persona] P ON PDD.Persona_id = P.id LEFT JOIN 
                 [INRDIS_II].[dbo].[tbPersonal] PE ON PDC.PersonalAtiende_id = PE.id LEFT JOIN
                 [INRDIS_II].[dbo].[AsistenciaCita] AC ON PDD.AsistenciaCita_id = AC.id LEFT JOIN
                 [ECONOMIA].[dbo].[FUA2] FU ON PDD.FUA_id= FU.IdAtencion
            
            WHERE CONVERT(int,FU.Numero) = :nroFua2 AND PDD.Financiador_id IN (2,8) 
                  AND FU.IdAtencion IS NOT NULL AND AE.id NOT IN (41,42,43,44,45,46,47,48,49,50,51)
            
            UNION ALL
            
            SELECT TOP (100) PERCENT SPD.tbFinanciador_id as Financiador_id,CASE WHEN SPD.tbFinanciador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
            FORMAT(CAST(SPD.fechaProgramada AS DATE),'dd-MM-yyyy') + ' ' + LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) as Fecha,
            FORMAT(CAST(SPD.fechaProgramada AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) AS Hora,
            P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
            P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
            CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
            WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
           
            /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            CASE WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
            WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
            ELSE 'OTRO' END AS TipoDocumento,
            CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
            /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
            /* INICIO DE HISTORIA CLINICA*/
            CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
            /* FIN DE HISTORIA CLINICA*/
           
            /* INICIO DE FUA */
            FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,
            FU.IdAtencion as Fua_id,
            /* FIN DE FUA */
           
            'SESION' AS TipoActividad,
            LEFT(S.descripcion,20) AS ActividadEspecifica,
            PE.id AS Personal_id,
            PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
           
            /* INICIO DE ESTADO CITA */
            SPD.tbAsistencia_id AS EstadoCita_id,
            CASE WHEN A.abreviatura IS NULL THEN 'CITADO' 
                 WHEN A.id = 19 THEN 'NO ATENDIDO'
                 WHEN A.id = 18 THEN 'NO ATENDIDO'
                 WHEN A.id = 17 THEN 'NO ATENDIDO'
                 WHEN A.id = 13 THEN 'NO ATENDIDO'
                 WHEN A.id = 2 THEN 'NO ATENDIDO'
                 WHEN A.id = 15 THEN 'NO ATENDIDO'
                 WHEN A.id = 3  THEN 'ATENDIDO'
                 WHEN A.id = 4  THEN 'ATENDIDO'
                 WHEN A.id = 22  THEN 'NO ATENDIDO'
                 ELSE A.abreviatura END COLLATE Modern_Spanish_CI_AS AS EstadoCita,
            /* FIN DE ESTADO CITA */
           
            'SERVICIODET' AS Modelo,
           
            CASE WHEN SPD.tbModoCita_id = 1 THEN 'PRESENCIAL'
            WHEN SPD.tbModoCita_id = 5 THEN 'EXTRA'
            WHEN SPD.tbModoCita_id = 7 THEN 'NO PRESENCIAL'
            WHEN SPD.tbModoCita_id = 6 THEN 'H-COMPL.'
            END AS TipoAtencion,
           
            UO.descripcion AS UnidadOrganica,
            CASE WHEN GP.id = 2 THEN 'TERAPISTAS'
                 WHEN GP.id = 13 THEN 'MEDICOS'
                 WHEN GP.id = 14 THEN 'MEDICOS'
                 WHEN GP.id = 21 THEN 'MEDICOS'
                 ELSE GP.descripcion END AS GrupoProfesional,
            SPD.id AS idRegistro,
            SPD.nroSesion AS numeroSesion,
            SPD.ServicioPersonaCab_id AS idIdentificador,
            SPC.tbCIE1_codigo AS CodigoCie,
            NULL AS Diagnostico
            
            FROM    [INRDIS_II].[dbo].[tbServicio] S INNER JOIN [INRDIS_II].[dbo].[tbServicio_UOrganica] SUO
                    ON S.id = SUO.tbServicio_id INNER JOIN [INRDIS_II].[dbo].[ServicioPersonaCab] SPC INNER JOIN
                    [INRDIS_II].[dbo].[ServicioPersonaDet] SPD ON SPC.id = SPD.ServicioPersonaCab_id LEFT JOIN
                    [INRDIS_II].[dbo].[tbAsistencia] A ON SPD.tbAsistencia_id = A.id INNER JOIN
                    [INRDIS_II].[dbo].[Persona] P ON SPC.Persona_id = P.id ON SUO.id = SPC.tbServicio_UOrganica_id INNER JOIN
                    [INRDIS_II].[dbo].[tbPersonal] PE ON SPD.tbPersonal_id = PE.id INNER JOIN
                    [INRDIS_II].[dbo].[tbUOrganica] UO ON SUO.tbUOrganica_id = UO.id LEFT OUTER JOIN
                    [ECONOMIA].[dbo].[FUA2] FU ON SPD.sustentoPago_id = FU.IdAtencion LEFT JOIN
                    [INRDIS_II].[dbo].[tbGrupoProfesional] GP ON SUO.tbGrupoProfesional_id = GP.id
            
            WHERE CONVERT(int,FU.Numero) = :nroFua3 AND SPD.tbFinanciador_id IN (2,8) AND
                      (SPD.tbEstado_id <> 8 AND SPD.tbEstado_id <> 11) AND SUO.tbGrupoProfesional_id IN (2,4,6,7,11,13,14,18,21,23)
                      AND FU.IdAtencion IS NOT NULL AND SUO.tbServicio_id IN (6,7,8,9,27,28,115)",
                    ["nroFua1"=>$request->numFuaBD_fuasObservados,
                     "nroFua2"=>$request->numFuaBD_fuasObservados,
                     "nroFua3"=>$request->numFuaBD_fuasObservados]))->make(true);
               }else{
                    return datatables()->of(DB::SELECT("SELECT TOP (100) PERCENT CHP.fina_cod as Financiador_id,
                    CASE WHEN CHP.fina_cod = '02' THEN 'S.I.S.' WHEN CHP.fina_cod = '2 ' THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
                    FORMAT(CAST(CH.cita_fech AS DATE), 'dd-MM-yyyy') + ' ' + LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) as Fecha,
                    FORMAT(CAST(CH.cita_fech AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(CH.cita_hora,2) + ':' + RIGHT(CH.cita_hora,2) AS Hora,
                    P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
                    P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
                    CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
                    WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
            
                    /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
                    CASE 
                    WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
                    WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
                    ELSE 'OTRO' END AS TipoDocumento,
                    CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
                    /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
                    /* INICIO DE HISTORIA CLINICA*/
                    CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
                    /* FIN DE HISTORIA CLINICA*/
            
                    /* INICIO DE FUA */
                    FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,
                    FU.IdAtencion as Fua_id,
                    /* FIN DE FUA */
            
                    TA.description AS TipoActividad,
                    LEFT(AE.description,20) AS ActividadEspecifica,
                    PE.id AS Personal_id,
                    PE.apPaterno +' '+ PE.apMaterno +' '+ PE.nombres AS Profesional, 
            
                    /* INICIO DE ESTADO CITA */
                    CASE WHEN CHP.asis_cod IS NULL THEN 10 ELSE A.AsistenciaCita_id END AS EstadoCita_id,
                    CASE WHEN AC.abreviado IS NULL THEN 'CITADO'  
                         WHEN AC.id = 19 THEN 'NO ATENDIDO'
                         WHEN AC.id = 18 THEN 'NO ATENDIDO'
                         WHEN AC.id = 17 THEN 'NO ATENDIDO'
                         WHEN AC.id = 13 THEN 'NO ATENDIDO'
                         WHEN AC.id = 2 THEN 'NO ATENDIDO'
                         WHEN AC.id = 15 THEN 'NO ATENDIDO'
                         WHEN AC.id = 3  THEN 'ATENDIDO'
                         WHEN AC.id = 4  THEN 'ATENDIDO'
                         ELSE AC.abreviado END AS EstadoCita,
               /* FIN DE ESTADO CITA */
                    /* FIN DE ESTADO CITA */
            
                    'CITASHORA' AS Modelo,
           
                    CASE WHEN CH.modoap_cod = 1 THEN 'PRESENCIAL'
                         WHEN CH.modoap_cod = 5 THEN 'EXTRA'
                         WHEN CH.modoap_cod = 7 THEN 'NO PRESENCIAL'
                         WHEN CH.modoap_cod = 6 THEN 'H-COMPL.'
                         END AS TipoAtencion,
           
                    CASE WHEN UO.uo_desc = 'DPTO. AMPUTADOS Y QUEMADOS' THEN 'DPTO. A. Q. Y T-POSTURALES' ELSE UO.uo_desc END AS UnidadOrganica,
                    CASE WHEN CH.serv_cod = 11 THEN 'MEDICOS' ELSE 'NUTRICIONISTAS' END AS GrupoProfesional,
                    CHP.cext_id AS idRegistro,
                    NULL AS numeroSesion,
                    NULL AS idIdentificador,
                    CHP.cie_cod_dano1 AS CodigoCie,
                    CHP.diag_cod_dano1 AS Diagnostico
            
            FROM    [INRDIS_II].[dbo].[CitasHora] CH INNER JOIN 
                    [INRDIS_II].[dbo].[CitasHora_Paciente] CHP ON CH.cita_prof = CHP.cita_prof AND CH.cita_fech = CHP.cita_fech AND CH.cita_extra = CHP.cita_extra AND
                    CH.cita_hora = CHP.cita_hora INNER JOIN [INRDIS_II].[dbo].[tblUO] UO ON CH.uo_cod = UO.uo_cod INNER JOIN
                    [INRDIS_II].[dbo].[tblTipoCita_Citado] TCC ON CHP.tcitaa_cod = TCC.tcitaa_cod INNER JOIN 
                    [INRDIS_II].[dbo].[TipoActividad] TA ON TCC.tipoActividad_id = TA.id INNER JOIN 
                    [INRDIS_II].[dbo].[ActividadEspecifica] AE ON TCC.actividadEspecifica_id = AE.id INNER JOIN
                    [INRDIS_II].[dbo].[Persona] P ON CHP.pers_id = P.id LEFT OUTER JOIN 
                    [INRDIS_II].[dbo].[tblAsistencia] A ON CHP.asis_cod = A.asis_cod LEFT OUTER JOIN
                    [ECONOMIA].[dbo].[FUA2] FU ON CHP.FUA_id = FU.IdAtencion LEFT OUTER JOIN 
                    [INRDIS_II].[dbo].[AsistenciaCita] AC ON A.AsistenciaCita_id = AC.id LEFT JOIN
                    [INRDIS_II].[dbo].[tbPersonal] PE ON CH.cita_prof = PE.id
            
                     
            WHERE CONVERT(int,FU.Numero) = :nroFua1 AND (CHP.fina_cod IN ('02','08') OR CHP.fina_cod 
                  IN ('2 ','8 ')) AND CH.serv_cod <> 41 AND FU.IdAtencion IS NOT NULL AND AE.id NOT IN (41,42,43,44,45,46,47,48,49,50,51)
                  AND CHP.id_usuario = :IdUsuario1
            
            UNION ALL
            
            SELECT TOP (100) PERCENT PDD.Financiador_id as Financiador_id,
            CASE WHEN PDD.Financiador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
            FORMAT(CAST(PDC.fecha AS DATE),'dd-MM-yyyy') + ' ' + LEFT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) + ':' + 
            RIGHT(CASE WHEN PDC.horaInicia IS NULL THEN '0000' ELSE PDC.horaInicia END,2) as Fecha,
            FORMAT(CAST(PDC.fecha AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(PDC.horaInicia,2) + ':' + RIGHT(PDC.horaInicia,2) AS Hora,
            P.id AS id_Paciente,P.apPaterno + ' ' + P.apMaterno + ' ' + P.nombres AS Paciente,
            P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
            CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
            WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
            
            /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            CASE 
            WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
            WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
            ELSE 'OTRO' END AS TipoDocumento,
            CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
            /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
            P.hcl_num as HistoriaClinica,FU.DISA + '-' + FU.Lote + '-' + FU.Numero as FUA,
            FU.IdAtencion as Fua_id,
            TA.description AS TipoActividad,
            LEFT(AE.description,20) as ActividadEspecifica,
            PE.id AS Personal_id,
            PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
           
            /* INICIO DE ESTADO CITA */
            CASE WHEN PDD.AsistenciaCita_id IS NULL THEN 10 ELSE PDD.AsistenciaCita_id END AS EstadoCita_id,
            CASE WHEN AC.abreviado IS NULL THEN 'CITADO' 
                 WHEN AC.id = 19 THEN 'NO ATENDIDO'
                 WHEN AC.id = 18 THEN 'NO ATENDIDO'
                 WHEN AC.id = 17 THEN 'NO ATENDIDO'
                 WHEN AC.id = 13 THEN 'NO ATENDIDO'
                 WHEN AC.id = 2 THEN 'NO ATENDIDO'
                 WHEN AC.id = 15 THEN 'NO ATENDIDO'
                 WHEN AC.id = 3  THEN 'ATENDIDO'
                 WHEN AC.id = 4  THEN 'ATENDIDO'
                 ELSE AC.abreviado END AS EstadoCita,
            /* FIN DE ESTADO CITA */
            'PDIARIO' AS Modelo, 
           
            CASE WHEN PDC.ModoAtencionProfesional_id = 1 THEN 'PRESENCIAL'
            WHEN PDC.ModoAtencionProfesional_id = 5 THEN 'EXTRA'
            WHEN PDC.ModoAtencionProfesional_id = 7 THEN 'NO PRESENCIAL'
            WHEN PDC.ModoAtencionProfesional_id = 6 THEN 'H-COMPL.'
            END AS TipoAtencion,
           
            UO.description AS UnidadOrganica,
            'MEDICOS' AS GrupoProfesional,
            PDD.id AS idRegistro,
            PDD.nroSesion AS numeroSesion,
            NULL AS idIdentificador,
            PDD.Cie1_cod AS CodigoCie,
            NULL AS Diagnostico
                    
            
            FROM [INRDIS_II].[dbo].[PDiarioDetalles] PDD INNER JOIN
                 [INRDIS_II].[dbo].[PDiarioCabecera] PDC ON PDD.PDiarioCabecera_id = PDC.id INNER JOIN 
                 [INRDIS_II].[dbo].[TipoActividad] TA ON PDC.tipoActividad_id = TA.id INNER JOIN 
                 [INRDIS_II].[dbo].[ActividadEspecifica] AE ON PDD.actividadEspecifica_id = AE.id INNER JOIN
                 [INRDIS_II].[dbo].[UnidadOrganica] UO ON PDC.UnidadOrganica_id = UO.id INNER JOIN
                 [INRDIS_II].[dbo].[Persona] P ON PDD.Persona_id = P.id LEFT JOIN 
                 [INRDIS_II].[dbo].[tbPersonal] PE ON PDC.PersonalAtiende_id = PE.id LEFT JOIN
                 [INRDIS_II].[dbo].[AsistenciaCita] AC ON PDD.AsistenciaCita_id = AC.id LEFT JOIN
                 [ECONOMIA].[dbo].[FUA2] FU ON PDD.FUA_id= FU.IdAtencion
            
            WHERE CONVERT(int,FU.Numero) = :nroFua2 AND PDD.Financiador_id IN (2,8) 
                  AND FU.IdAtencion IS NOT NULL AND AE.id NOT IN (41,42,43,44,45,46,47,48,49,50,51)
                  AND PDD.id_usuario = :IdUsuario2
            
            UNION ALL
            
            SELECT TOP (100) PERCENT SPD.tbFinanciador_id as Financiador_id,CASE WHEN SPD.tbFinanciador_id = 2 THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
            FORMAT(CAST(SPD.fechaProgramada AS DATE),'dd-MM-yyyy') + ' ' + LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) as Fecha,
            FORMAT(CAST(SPD.fechaProgramada AS DATE), 'yyyy-MM-dd') as Fecha1,LEFT(SPD.horaProgramada,2) + ':' + RIGHT(SPD.horaProgramada,2) AS Hora,
            P.id AS id_Paciente,P.apPaterno +' '+ P.apMaterno +' '+ P.nombres AS Paciente,
            P.apPaterno AS ApellidoPaterno,P.apMaterno AS ApellidoMaterno,P.nombres AS Nombres,
            CASE WHEN P.Sexo_id = 'M' THEN 'MASCULINO'
            WHEN P.Sexo_id = 'F' THEN 'FEMENINO' END as Sexo,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') as FechaNacimiento,
           
            /*(INICIO) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            CASE WHEN P.TipoDocIdentidad_id = 1 THEN 'D.N.I.' 
            WHEN P.TipoDocIdentidad_id = 2 THEN 'C.E.'
            ELSE 'OTRO' END AS TipoDocumento,
            CASE WHEN P.nroDocIdentidad = '' THEN 'S/N' ELSE P.nroDocIdentidad END AS NumeroDocumento,
            /*(FIN) HACEMOS UN IF A LA SENTENCIA DEL DOCUMENTO*/
            
            /* INICIO DE HISTORIA CLINICA*/
            CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE CONVERT(VARCHAR, P.hcl_num) END AS HistoriaClinica,
            /* FIN DE HISTORIA CLINICA*/
           
            /* INICIO DE FUA */
            FU.DISA + '-' + FU.Lote + '-' + FU.Numero AS FUA,
            FU.IdAtencion as Fua_id,
            /* FIN DE FUA */
           
            'SESION' AS TipoActividad,
            LEFT(S.descripcion,20) AS ActividadEspecifica,
            PE.id AS Personal_id,
            PE.apPaterno + ' ' + PE.apMaterno + ' ' + PE.nombres AS Profesional,
           
            /* INICIO DE ESTADO CITA */
            SPD.tbAsistencia_id AS EstadoCita_id,
            CASE WHEN A.abreviatura IS NULL THEN 'CITADO' 
                 WHEN A.id = 19 THEN 'NO ATENDIDO'
                 WHEN A.id = 18 THEN 'NO ATENDIDO'
                 WHEN A.id = 17 THEN 'NO ATENDIDO'
                 WHEN A.id = 13 THEN 'NO ATENDIDO'
                 WHEN A.id = 2 THEN 'NO ATENDIDO'
                 WHEN A.id = 15 THEN 'NO ATENDIDO'
                 WHEN A.id = 3  THEN 'ATENDIDO'
                 WHEN A.id = 4  THEN 'ATENDIDO'
                 WHEN A.id = 22  THEN 'NO ATENDIDO'
                 ELSE A.abreviatura END COLLATE Modern_Spanish_CI_AS AS EstadoCita,
            /* FIN DE ESTADO CITA */
           
            'SERVICIODET' AS Modelo,
           
            CASE WHEN SPD.tbModoCita_id = 1 THEN 'PRESENCIAL'
            WHEN SPD.tbModoCita_id = 5 THEN 'EXTRA'
            WHEN SPD.tbModoCita_id = 7 THEN 'NO PRESENCIAL'
            WHEN SPD.tbModoCita_id = 6 THEN 'H-COMPL.'
            END AS TipoAtencion,
           
            UO.descripcion AS UnidadOrganica,
            CASE WHEN GP.id = 2 THEN 'TERAPISTAS'
                 WHEN GP.id = 13 THEN 'MEDICOS'
                 WHEN GP.id = 14 THEN 'MEDICOS'
                 WHEN GP.id = 21 THEN 'MEDICOS'
                 ELSE GP.descripcion END AS GrupoProfesional,
            SPD.id AS idRegistro,
            SPD.nroSesion AS numeroSesion,
            SPD.ServicioPersonaCab_id AS idIdentificador,
            SPC.tbCIE1_codigo AS CodigoCie,
            NULL AS Diagnostico
            
            FROM    [INRDIS_II].[dbo].[tbServicio] S INNER JOIN [INRDIS_II].[dbo].[tbServicio_UOrganica] SUO
                    ON S.id = SUO.tbServicio_id INNER JOIN [INRDIS_II].[dbo].[ServicioPersonaCab] SPC INNER JOIN
                    [INRDIS_II].[dbo].[ServicioPersonaDet] SPD ON SPC.id = SPD.ServicioPersonaCab_id LEFT JOIN
                    [INRDIS_II].[dbo].[tbAsistencia] A ON SPD.tbAsistencia_id = A.id INNER JOIN
                    [INRDIS_II].[dbo].[Persona] P ON SPC.Persona_id = P.id ON SUO.id = SPC.tbServicio_UOrganica_id INNER JOIN
                    [INRDIS_II].[dbo].[tbPersonal] PE ON SPD.tbPersonal_id = PE.id INNER JOIN
                    [INRDIS_II].[dbo].[tbUOrganica] UO ON SUO.tbUOrganica_id = UO.id LEFT OUTER JOIN
                    [ECONOMIA].[dbo].[FUA2] FU ON SPD.sustentoPago_id = FU.IdAtencion LEFT JOIN
                    [INRDIS_II].[dbo].[tbGrupoProfesional] GP ON SUO.tbGrupoProfesional_id = GP.id
            
            WHERE CONVERT(int,FU.Numero) = :nroFua3 AND SPD.tbFinanciador_id IN (2,8) AND
                      (SPD.tbEstado_id <> 8 AND SPD.tbEstado_id <> 11) AND SUO.tbGrupoProfesional_id IN (2,4,6,7,11,13,14,18,21,23)
                      AND FU.IdAtencion IS NOT NULL AND SUO.tbServicio_id IN (6,7,8,9,27,28,115) AND SPD.id_usuario = :IdUsuario3",
                    ["nroFua1"=>$request->numFuaBD_fuasObservados,
                     "nroFua2"=>$request->numFuaBD_fuasObservados,
                     "nroFua3"=>$request->numFuaBD_fuasObservados,
                     "IdUsuario1"=>$usuario_fuasObservados,
                     "IdUsuario2"=>$usuario_fuasObservados,
                     "IdUsuario3"=>$usuario_fuasObservados]))->make(true);
               }

          }
     }


}
