<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdministradoresModel;
use App\DepartamentosModel;
use App\DireccionesEjecutivasModel;
use PDF;
use Illuminate\Support\Facades\DB;

class DashBoardController extends Controller
{
    public function index(){
        $administradores = AdministradoresModel::all();
        $cantidad_usuarios = DB::select('SELECT COUNT(id) as cantidad_usuarios FROM users');
        $cantidad_categorias = DB::select('SELECT COUNT(id_categoria) as cantidad_categorias FROM categorias');
        $cantidad_unidadesMedida = DB::select('SELECT COUNT(id_unidadMedida) as cantidad_unidadesMedida FROM unidades_medida');
        $cantidad_productosLimpieza = DB::select('SELECT COUNT(id_productoLimpieza) as cantidad_productosLimpieza FROM productos_limpieza');
/*         $cantidad_pacientesCitados = DB::SELECT("SELECT COUNT(CH.cita_fech) as fecha
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
              IN ('2 ','8 ')) AND CH.serv_cod <> 41 AND FU.IdAtencion IS NULL AND (CHP.asis_cod NOT IN ('2','13','15','16') OR CHP.asis_cod IS NULL)
        
        UNION ALL
        
        SELECT COUNT(PDC.fecha) as Fecha
                
        
        FROM [INRDIS_II].[dbo].[PDiarioDetalles] PDD INNER JOIN
             [INRDIS_II].[dbo].[PDiarioCabecera] PDC ON PDD.PDiarioCabecera_id = PDC.id INNER JOIN 
             [INRDIS_II].[dbo].[TipoActividad] TA ON PDC.tipoActividad_id = TA.id INNER JOIN 
             [INRDIS_II].[dbo].[ActividadEspecifica] AE ON PDD.actividadEspecifica_id = AE.id INNER JOIN
             [INRDIS_II].[dbo].[UnidadOrganica] UO ON PDC.UnidadOrganica_id = UO.id INNER JOIN
             [INRDIS_II].[dbo].[Persona] P ON PDD.Persona_id = P.id LEFT JOIN 
             [INRDIS_II].[dbo].[tbPersonal] PE ON PDC.PersonalAtiende_id = PE.id LEFT JOIN
             [INRDIS_II].[dbo].[AsistenciaCita] AC ON PDD.AsistenciaCita_id = AC.id LEFT JOIN
             [ECONOMIA].[dbo].[FUA2] FU ON PDD.FUA_id= FU.IdAtencion
        
        WHERE CAST(PDC.fecha AS DATE) BETWEEN DATEADD(DD, -1, GETDATE()) AND DATEADD(DD, 1, GETDATE()) AND PDD.Financiador_id IN (2,8) AND FU.IdAtencion IS NULL
        AND (PDD.AsistenciaCita_id NOT IN (2,13,15,17,18,19) OR PDD.AsistenciaCita_id IS NULL)
        
        UNION ALL
        
        SELECT COUNT(SPD.fechaProgramada) as Fecha
        
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
                  AND FU.IdAtencion IS NULL AND (SPD.tbAsistencia_id NOT IN (2,13,15,17,18,19) OR SPD.tbAsistencia_id IS NULL)");

        $sum = 0;

        foreach ($cantidad_pacientesCitados as $key => $valor_cantidad_pacientesCitados) {
            $sum += $valor_cantidad_pacientesCitados->fecha;
        }

                $cantidad_fuasEmitidos = DB::select("SELECT COUNT(CH.cita_fech) as fecha
        
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
                      IN ('2 ','8 ')) AND CH.serv_cod <> 41 AND FU.IdAtencion IS NOT NULL AND AE.id NOT IN (41,42,43,44,45,46,47,48,49,50,51)
                
                UNION ALL
                
                SELECT COUNT(PDC.fecha) as Fecha
                        
                
                FROM [INRDIS_II].[dbo].[PDiarioDetalles] PDD INNER JOIN
                     [INRDIS_II].[dbo].[PDiarioCabecera] PDC ON PDD.PDiarioCabecera_id = PDC.id INNER JOIN 
                     [INRDIS_II].[dbo].[TipoActividad] TA ON PDC.tipoActividad_id = TA.id INNER JOIN 
                     [INRDIS_II].[dbo].[ActividadEspecifica] AE ON PDD.actividadEspecifica_id = AE.id INNER JOIN
                     [INRDIS_II].[dbo].[UnidadOrganica] UO ON PDC.UnidadOrganica_id = UO.id INNER JOIN
                     [INRDIS_II].[dbo].[Persona] P ON PDD.Persona_id = P.id LEFT JOIN 
                     [INRDIS_II].[dbo].[tbPersonal] PE ON PDC.PersonalAtiende_id = PE.id LEFT JOIN
                     [INRDIS_II].[dbo].[AsistenciaCita] AC ON PDD.AsistenciaCita_id = AC.id LEFT JOIN
                     [ECONOMIA].[dbo].[FUA2] FU ON PDD.FUA_id= FU.IdAtencion
                
                WHERE CAST(PDC.fecha AS DATE) BETWEEN DATEADD(DD, -1, GETDATE()) AND DATEADD(DD, 1, GETDATE()) AND PDD.Financiador_id IN (2,8) 
                      AND FU.IdAtencion IS NOT NULL AND AE.id NOT IN (41,42,43,44,45,46,47,48,49,50,51)
                
                UNION ALL
                
                SELECT COUNT(SPD.fechaProgramada) as Fecha
                
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
                          AND FU.IdAtencion IS NOT NULL AND SUO.tbServicio_id NOT IN (6,7,8,9,27,28,115)");

        $sum_fuasEmitidos = 0;

        foreach ($cantidad_fuasEmitidos as $key => $valor_cantidad_fuasEmitidos) {
            $sum_fuasEmitidos += $valor_cantidad_fuasEmitidos->fecha;
        }

        $cantidad_fuasObservados = DB::select("SELECT COUNT(CH.cita_fech) as fecha
            
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
        
        SELECT COUNT(PDC.fecha) as Fecha
                
        
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
        
        SELECT COUNT(SPD.fechaProgramada) as Fecha
        
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
                  AND FU.IdAtencion IS NOT NULL AND SUO.tbServicio_id IN (6,7,8,9,27,28,115)");

        $sum_fuasObservados = 0;

        foreach ($cantidad_fuasObservados as $key => $valor_cantidad_fuasObservados) {
            $sum_fuasObservados += $valor_cantidad_fuasObservados->fecha;
        }

        $cantidad_fuasEmitidosG = DB::SELECT("SELECT COUNT(FU.idAtencion) as idAtencion FROM ECONOMIA.dbo.FUA2 FU 
                                        INNER JOIN [software_ufpa_general].[dbo].[FUA] F ON FU.IdAtencion = F.NroFua INNER JOIN [software_ufpa_general].[dbo].[users] U ON F.IdUsuario = U.id
                                        LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id WHERE 
                                        CAST(FU.FechaHoraRegistro AS DATE) = CONVERT(DATE,GETDATE()) AND (FU.generarFua_estado = '1' OR FU.generarFua_estado = '0')");

        $sum_fuasEmitidosG = 0;

        foreach ($cantidad_fuasEmitidosG as $key => $valor_cantidad_fuasEmitidosG) {
                $sum_fuasEmitidosG += $valor_cantidad_fuasEmitidosG->idAtencion;
        }

        $cantidad_fuasDigitados = DB::SELECT("SELECT COUNT(FU.idAtencion) as idAtencion FROM ECONOMIA.dbo.FUA2 FU INNER JOIN [software_ufpa_general].[dbo].[FUA] F ON FU.IdAtencion = F.NroFua LEFT JOIN software_ufpa_general.dbo.users U ON F.IdUsuario_digitacion = U.id
                                              LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id WHERE 
                                              CAST(FU.FechaHoraRegistro AS DATE) = CONVERT(DATE,GETDATE()) AND generarFua_estado = '1'");

        $sum_fuasDigitados = 0;

        foreach ($cantidad_fuasDigitados as $key => $valor_cantidad_fuasDigitados) {
                $sum_fuasDigitados += $valor_cantidad_fuasDigitados->idAtencion;
        }

        $cantidad_auditoriaEmitidos = DB::SELECT("SELECT COUNT(FU.idAtencion) as idAtencion FROM ECONOMIA.dbo.FUA2 FU LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id WHERE 
                                              CAST(FU.FechaHoraRegistro AS DATE) = CONVERT(DATE,GETDATE()) AND (FU.generarFua_estado = '1' OR FU.generarFua_estado = '0')");

        $sum_auditoriaEmitidos = 0;

        foreach ($cantidad_auditoriaEmitidos as $key => $valor_cantidad_auditoriaEmitidos) {
                $sum_auditoriaEmitidos += $valor_cantidad_auditoriaEmitidos->idAtencion;
        }

        $cantidad_fuasAcervo = DB::SELECT("SELECT COUNT(FU.idAtencion) as idAtencion FROM ECONOMIA.dbo.FUA2 FU INNER JOIN [software_ufpa_general].[dbo].[FUA] F ON FU.IdAtencion = F.NroFua LEFT JOIN software_ufpa_general.dbo.users U ON F.IdUsuario_digitacion = U.id
                                           LEFT JOIN PERSONAL.dbo.PERSONAL P ON FU.personalAtiende_id = P.id WHERE 
                                           CAST(FU.FechaHoraRegistro AS DATE) = CONVERT(DATE,GETDATE()) AND digitarFua_estado = '1'");

        $sum_fuasAcervo = 0;

        foreach ($cantidad_fuasAcervo as $key => $valor_cantidad_fuasAcervo) {
                $sum_fuasAcervo += $valor_cantidad_fuasAcervo->idAtencion;
        }

        $cantidad_mesActual = DB::select("SELECT COUNT(FU.IdAtencion) as cantidad_actual FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                          AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE()) AND MONTH(FU.FechaHoraRegistro) = MONTH(GETDATE())");
        $cantidad_mesPasado = DB::select("SELECT COUNT(FU.IdAtencion) as cantidad_pasado FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                          AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE()) AND MONTH(FU.FechaHoraRegistro) = MONTH(GETDATE())-1"); */

        return view("paginas.dashboard",  array("administradores"=>$administradores,
                                                "cantidad_usuarios"=>$cantidad_usuarios,
                                                "cantidad_categorias"=>$cantidad_categorias,
                                                "cantidad_unidadesMedida"=>$cantidad_unidadesMedida,
                                                "cantidad_productosLimpieza"=>$cantidad_productosLimpieza/* ,
                                                "sum"=>$sum,"sum_fuasEmitidos"=>$sum_fuasEmitidos,
                                                "sum_fuasObservados"=>$sum_fuasObservados,
                                                "sum_fuasEmitidosG"=>$sum_fuasEmitidosG,
                                                "sum_fuasDigitados"=>$sum_fuasDigitados,
                                                "sum_auditoriaEmitidos"=>$sum_auditoriaEmitidos,
                                                "sum_fuasAcervo"=>$sum_fuasAcervo,
                                                "cantidad_mesActual"=>$cantidad_mesActual,
                                                "cantidad_mesPasado"=>$cantidad_mesPasado */));
    }

    public function listarActual(){
/*         $cronogramas_enero =  DB::select("SELECT COUNT(FU.IdAtencion) as enero FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                          AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE()) AND MONTH(FU.FechaHoraRegistro) = 1");
        $cronogramas_febrero =  DB::select("SELECT COUNT(FU.IdAtencion) as febrero FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                            AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE()) AND MONTH(FU.FechaHoraRegistro) = 2");
        $cronogramas_marzo =  DB::select("SELECT COUNT(FU.IdAtencion) as marzo FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                          AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE()) AND MONTH(FU.FechaHoraRegistro) = 3");
        $cronogramas_abril =  DB::select("SELECT COUNT(FU.IdAtencion) as abril FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                          AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE()) AND MONTH(FU.FechaHoraRegistro) = 4");
        $cronogramas_mayo =  DB::select("SELECT COUNT(FU.IdAtencion) as mayo FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                         AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE()) AND MONTH(FU.FechaHoraRegistro) = 5");
        $cronogramas_junio =  DB::select("SELECT COUNT(FU.IdAtencion) as junio FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                          AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE()) AND MONTH(FU.FechaHoraRegistro) = 6");
        $cronogramas_julio =  DB::select("SELECT COUNT(FU.IdAtencion) as julio FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                          AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE()) AND MONTH(FU.FechaHoraRegistro) = 7");
        $cronogramas_agosto =  DB::select("SELECT COUNT(FU.IdAtencion) as agosto FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                           AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE()) AND MONTH(FU.FechaHoraRegistro) = 8");
        $cronogramas_setiembre =  DB::select("SELECT COUNT(FU.IdAtencion) as setiembre FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                              AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE()) AND MONTH(FU.FechaHoraRegistro) = 9");
        $cronogramas_octubre =  DB::select("SELECT COUNT(FU.IdAtencion) as octubre FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                            AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE()) AND MONTH(FU.FechaHoraRegistro) = 10");
        $cronogramas_noviembre =  DB::select("SELECT COUNT(FU.IdAtencion) as noviembre FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                              AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE()) AND MONTH(FU.FechaHoraRegistro) = 11");
        $cronogramas_diciembre =  DB::select("SELECT COUNT(FU.IdAtencion) as diciembre FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                              AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE()) AND MONTH(FU.FechaHoraRegistro) = 12");

        $cronogramas_enero_pasado =  DB::select("SELECT COUNT(FU.IdAtencion) as enero FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                                 AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE())-1 AND MONTH(FU.FechaHoraRegistro) = 1");
        $cronogramas_febrero_pasado =  DB::select("SELECT COUNT(FU.IdAtencion) as febrero FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                                   AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE())-1 AND MONTH(FU.FechaHoraRegistro) = 2");
        $cronogramas_marzo_pasado =  DB::select("SELECT COUNT(FU.IdAtencion) as marzo FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                                   AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE())-1 AND MONTH(FU.FechaHoraRegistro) = 3");
        $cronogramas_abril_pasado =  DB::select("SELECT COUNT(FU.IdAtencion) as abril FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                                   AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE())-1 AND MONTH(FU.FechaHoraRegistro) = 4");
        $cronogramas_mayo_pasado =  DB::select("SELECT COUNT(FU.IdAtencion) as mayo FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                                   AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE())-1 AND MONTH(FU.FechaHoraRegistro) = 5");
        $cronogramas_junio_pasado =  DB::select("SELECT COUNT(FU.IdAtencion) as junio FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                                   AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE())-1 AND MONTH(FU.FechaHoraRegistro) = 6");
        $cronogramas_julio_pasado =  DB::select("SELECT COUNT(FU.IdAtencion) as julio FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                                   AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE())-1 AND MONTH(FU.FechaHoraRegistro) = 7");
        $cronogramas_agosto_pasado =  DB::select("SELECT COUNT(FU.IdAtencion) as agosto FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                                   AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE())-1 AND MONTH(FU.FechaHoraRegistro) = 8");
        $cronogramas_setiembre_pasado =  DB::select("SELECT COUNT(FU.IdAtencion) as setiembre FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                                   AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE())-1 AND MONTH(FU.FechaHoraRegistro) = 9");
        $cronogramas_octubre_pasado =  DB::select("SELECT COUNT(FU.IdAtencion) as octubre FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                                   AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE())-1 AND MONTH(FU.FechaHoraRegistro) = 10");
        $cronogramas_noviembre_pasado =  DB::select("SELECT COUNT(FU.IdAtencion) as noviembre FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                                   AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE())-1 AND MONTH(FU.FechaHoraRegistro) = 11");
        $cronogramas_diciembre_pasado =  DB::select("SELECT COUNT(FU.IdAtencion) as diciembre FROM ECONOMIA.dbo.FUA2 FU WHERE FU.generarFua_estado = '1' 
                                                   AND YEAR(FU.FechaHoraRegistro) = YEAR(GETDATE())-1 AND MONTH(FU.FechaHoraRegistro) = 12");

        $nuevo_cronograma = [];
        $nuevo_cronograma1 = [];

            $nuevo_cronograma[] = [
                $cronogramas_enero,
                $cronogramas_febrero,
                $cronogramas_marzo,
                $cronogramas_abril,
                $cronogramas_mayo,
                $cronogramas_junio,
                $cronogramas_julio,
                $cronogramas_agosto,
                $cronogramas_setiembre,
                $cronogramas_octubre,
                $cronogramas_noviembre,
                $cronogramas_diciembre
            ];

            $nuevo_cronograma1[] = [
                $cronogramas_enero_pasado,
                $cronogramas_febrero_pasado,
                $cronogramas_marzo_pasado,
                $cronogramas_abril_pasado,
                $cronogramas_mayo_pasado,
                $cronogramas_junio_pasado,
                $cronogramas_julio_pasado,
                $cronogramas_agosto_pasado,
                $cronogramas_setiembre_pasado,
                $cronogramas_octubre_pasado,
                $cronogramas_noviembre_pasado,
                $cronogramas_diciembre_pasado
            ];

        return response()->json([$nuevo_cronograma,$nuevo_cronograma1]); */
    }
}
