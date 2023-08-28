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
use SimpleXMLElement;
use nusoap_client;

class PacientesCitadosController extends Controller
{
    public function index(){

        $connection = DB::connection("sqlsrv2");
        $response=$connection->select(DB::raw("EXEC [dbo].[USP_ObtenerListaGeneral_PacientesCitados]"));

        /* Si existe un requerimiento del tipo AJAX */
        if (request()->ajax()) {
                return datatables()->of($response)->make(true);
        }

        $administradores = AdministradoresModel::on('sqlsrv')->get();
        $estados = DB::select('SELECT * FROM software_ufpa_general.dbo.estado');
        $codigoCie = DB::select('SELECT C.cie_cod,C.cie_desc FROM inrdis_ii.dbo.tblCIE C');
        $codPrestacional = DB::SELECT("SELECT * FROM economia.dbo.sisSERVICIOS WHERE flag = 'A' AND id <> 000");
        $personal = DB::select('SELECT * FROM INRDIS_II.dbo.tbPersonal PE INNER JOIN INRDIS_II.dbo.Persona P ON PE.Persona_id = P.id
                                WHERE PE.tbEstado_id IS NOT NULL ORDER BY PE.apPaterno ASC');
        $personalAtiende = DB::SELECT("SELECT SOP.descripcion AS descripcion,SOP.id
                                       FROM ECONOMIA.dbo.sisORIGENPERSONAL SOP");
        $lugarAtencion = DB::SELECT("SELECT LA.descripcion AS descripcion,LA.id FROM ECONOMIA.dbo.sisLUGARATENCION LA");
        $tipoAtencion = DB::SELECT("SELECT TA.descripcion AS descripcion,id FROM ECONOMIA.dbo.sisTIPOATENCION TA");
        $componente = DB::SELECT("SELECT SC.descripcion AS descripcion,SC.id FROM ECONOMIA.dbo.sisCOMPONENTES SC WHERE id <> 3");
        $concPrestacional = DB::SELECT("SELECT CP.descripcion AS descripcion,id FROM ECONOMIA.dbo.sisMODALIDADATENCION CP");
        $destinoAsegurado =DB::SELECT("SELECT DA.descripcion AS descripcion,id FROM ECONOMIA.dbo.sisDESTINOASEGURADO DA");
        $datosPersonalC = DB::SELECT("SELECT PER.id AS Profesional_id,PER.per_apat+' '+PER.per_amat+', '+PER.per_nomb AS Profesional FROM PERSONAL.dbo.personal PER 
                                      ORDER BY PER.per_apat ASC,PER.per_amat ASC");
        $sisTipoPersonalSalud = DB::SELECT("SELECT STPS.descripcion AS descripcion,STPS.id FROM ECONOMIA.dbo.sisTIPOPERSONALSALUD STPS WHERE id <> 00");
        $sisEgresado = DB::SELECT("SELECT SE.descripcion AS descripcion,SE.id FROM ECONOMIA.dbo.sisEgresado SE");
        $sisEspecialidad = DB::SELECT("SELECT E.descripcion AS descripcion,E.id FROM ECONOMIA.dbo.sisESPECIALIDAD E WHERE id <> 00");

        return view('paginas.pacientesCitados',array("administradores"=>$administradores,"estados"=>$estados,
                                                     "codigoCie"=>$codigoCie,"codPrestacional"=>$codPrestacional,"personal"=>$personal,
                                                     "personalAtiende"=>$personalAtiende,"lugarAtencion"=>$lugarAtencion,
                                                     "tipoAtencion"=>$tipoAtencion,"componente"=>$componente,
                                                     "concPrestacional"=>$concPrestacional,"destinoAsegurado"=>$destinoAsegurado,
                                                     "datosPersonalC"=>$datosPersonalC,"sisTipoPersonalSalud"=>$sisTipoPersonalSalud,
                                                     "sisEgresado"=>$sisEgresado,"sisEspecialidad"=>$sisEspecialidad));
    }

    public function buscarPorMes(Request $request){

        $datos = array("fechaInicio_pacientesCitados"=>$request->input("fechaInicio_pacientesCitados"),
                       "fechaFin_pacientesCitados"=>$request->input("fechaFin_pacientesCitados"));

        $fechaInicio = $datos["fechaInicio_pacientesCitados"];
        $fechaFin = $datos["fechaFin_pacientesCitados"];

        $connection = DB::connection("sqlsrv2");
        $response=$connection->select(DB::raw("EXEC [dbo].[USP_ObtenerListaPorFechas_PacientesCitados] '$fechaInicio','$fechaFin'"));

        /* Si existe un requerimiento del tipo AJAX */
        if(request()->ajax()){
            return datatables()->of($response)->make(true);}
        }

        public function rolCitas(Request $request){
                $datos = array("idCab_pacientesCitados"=>$request->input("idCab_pacientesCitados"));

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
                                 
                                                        WHERE (SPD.ServicioPersonaCab_id = :idCab_pacientesCitados) AND SPD.tbEstado_id <> 11
                                                        ORDER BY SPD.nroSesion",["idCab_pacientesCitados"=>$request->input('idCab_pacientesCitados')]))->make(true);
                }
        }

        public function consultarSis(Request $request){

                if($request->ajax()){

                        $strNroDocumento = $request->documento_pacientesCitados;
                        $strTipoDocumento = $request->tipoDocumento_pacientesCitados;

                        if($strTipoDocumento == 'D.N.I.' || $strTipoDocumento == '1'){
                                $strTipoDocumento = 1;
                        }else{
                                $strTipoDocumento = 3;
                        }

                        $url = "http://app.sis.gob.pe/sisWSAFI/Service.asmx?WSDL";

                        // PRIMER WEB SERVICE ---- INICIO
                        $soap_request_get = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:sis=\"http://sis.gob.pe/\">
                                                <soapenv:Header/>
                                                <soapenv:Body>
                                                   <sis:GetSession>
                                                      <sis:strUsuario>INR</sis:strUsuario>
                                                      <sis:strClave>wKjGSzhw</sis:strClave>
                                                   </sis:GetSession>
                                                </soapenv:Body>
                                             </soapenv:Envelope>";

                        $header_get = array(
                                "Method: POST",
                                "Content-type: text/xml;charset=\"utf-8\"",
                                "SOAPAction: \"http://sis.gob.pe/GetSession\"",
                                "Content-length: ".strlen($soap_request_get),
                                "Connection: Keep-Alive",
                                "User-Agent: Apache-HttpClient/4.5.5 (Java/12.0.1)",
                                "Host: app.sis.gob.pe"
                        );

                        $handle_get = curl_init($url);
                        curl_setopt($handle_get, CURLOPT_URL,            $url);
                        curl_setopt($handle_get, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($handle_get, CURLOPT_POST,           true);
                        curl_setopt($handle_get, CURLOPT_POSTFIELDS,     $soap_request_get);
                        curl_setopt($handle_get, CURLOPT_HTTPHEADER,     $header_get);
                        curl_setopt($handle_get, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($handle_get, CURLOPT_HEADER, false);
                        curl_setopt($handle_get, CURLOPT_NOBODY, 0);
                        curl_setopt($handle_get, CURLOPT_FOLLOWLOCATION, true);-
   
                        $response_get = curl_exec($handle_get);
                        curl_close($handle_get);

                        $response1_get = str_replace("<soap:Envelope xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"><soap:Body><GetSessionResponse xmlns=\"http://sis.gob.pe/\">","",$response_get);
                        $response2_get = str_replace("</GetSessionResponse></soap:Body></soap:Envelope>","",$response1_get);

                        $xml_get = new SimpleXMLElement($response2_get);

                        //SEGUNDO WEB SERVICE --- INICIO
                        $soap_request = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:sis=\"http://sis.gob.pe/\">
                                           <soapenv:Header/>
                                           <soapenv:Body>
                                              <sis:ConsultarAfiliadoFuaE>
                                                 <sis:intOpcion>1</sis:intOpcion>
                                                 <sis:strAutorizacion>$xml_get</sis:strAutorizacion>
                                                 <sis:strDni>41598101</sis:strDni>
                                                 <sis:strTipoDocumento>$strTipoDocumento</sis:strTipoDocumento>
                                                 <sis:strNroDocumento>$strNroDocumento</sis:strNroDocumento>
                                              </sis:ConsultarAfiliadoFuaE>
                                           </soapenv:Body>
                                        </soapenv:Envelope>";

                        $header = array(
                                "Method: POST",
                                "Content-type: text/xml;charset=\"utf-8\"",
                                "SOAPAction: \"http://sis.gob.pe/ConsultarAfiliadoFuaE\"",
                                "Content-length: ".strlen($soap_request),
                                "Connection: Keep-Alive",
                                "User-Agent: Apache-HttpClient/4.5.5 (Java/12.0.1)",
                                "Host: app.sis.gob.pe"
                        );

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

                        $response1 = str_replace("<soap:Envelope xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\"><soap:Body>","",$response);
                        $response2 = str_replace("</soap:Body></soap:Envelope>","",$response1);

                        $xml = new SimpleXMLElement($response2);

                        return json_encode($xml->ConsultarAfiliadoFuaEResult);

/*                         $strNroDocumento = $request->documento_pacientesCitados;
                        $strTipoDocumento = $request->tipoDocumento_pacientesCitados;

                        if($strTipoDocumento == 'D.N.I.' || $strTipoDocumento == '1'){
                                $strTipoDocumento = 1;
                        }else{
                                $strTipoDocumento = 3;
                        }

                        $client = new Client();

                        $response = $client->request('POST','http://pidesalud.minsa.gob.pe:19095/sis/afiliado/rest/v1.0/afiliado',[

                                'headers' => ['Content-Type' => 'application/json;charset=utf-8', 'Accept' => 'application/json'],
                                'body'    => '{"strTipoDocumento":"'.$strTipoDocumento.'","strNroDocumento":"'.$strNroDocumento.'"}'
                
                        ]);

                        $sis_informacion = $response->getBody()->getContents();
                        return $sis_informacion; */
                }
        }

        public function buscarPorHistoria(Request $request){
                if($request->ajax()){
                        $historiaClinica_pacientesCitados = $request->numHistoria;
                        $persona = DB::SELECT("SELECT P.id,P.Sexo_id,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') AS fechaNacimiento,P.TipoDocIdentidad_id,P.nroDocIdentidad,P.apPaterno,P.apMaterno,P.nombres,P.hcl_num,FORMAT(CAST(P.fechaNacimiento AS DATE), 'yyyy/MM/dd') as edad,P.telefono,P.correoElectronico FROM 
                                               inrdis_ii.dbo.Persona P WHERE P.hcl_num = ?",[$request->numHistoria]);

                        return response(json_encode($persona));                  
                }
        }

        public function buscarPorDocumento(Request $request){
                if($request->ajax()){
                        $documentoN_pacientesCitados = $request->numDocumento;
                        $persona = DB::SELECT("SELECT P.id,P.Sexo_id,FORMAT(CAST(P.fechaNacimiento AS DATE),'yyyy-MM-dd') AS fechaNacimiento,P.TipoDocIdentidad_id,P.nroDocIdentidad,P.apPaterno,P.apMaterno,P.nombres,P.hcl_num,FORMAT(CAST(P.fechaNacimiento AS DATE), 'yyyy/MM/dd') as edad,P.telefono,P.correoElectronico FROM 
                                               inrdis_ii.dbo.Persona P WHERE P.nroDocIdentidad = ?",[$request->numDocumento]);

                        return response(json_encode($persona));                  
                }
        }

        public function reportesFUA($IdAtencion_generacionFua,Request $request){

                $datosFua = DB::SELECT("SELECT * FROM [ECONOMIA].[dbo].[FUA2] FU LEFT JOIN ECONOMIA.dbo.sisTIPOPERSONALSALUD TPS ON FU.TipoPersonalSalud = TPS.id WHERE FU.IdAtencion = ?",[$IdAtencion_generacionFua]);
                $datosFuaIdCab = DB::SELECT("SELECT idCab,terapias_prog,codigoTerapia,descripcionTerapia FROM [SOFTWARE_UFPA_GENERAL].[dbo].[FUA] WHERE NroFua = ?",[$IdAtencion_generacionFua]);

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

                $datosTerapiaInicial = $datosFuaIdCab[0]->idCab;

                $datosFarmacia = DB::SELECT("SELECT DFC.fua_nro,C.catalogo_sismed,C.catalogo_desc,C.catalogo_tipo,DFD.docf_item_cant,DFC.docf_flag,DFC.docf_fech_despachado,
                                             DFD.docf_item_total,DFD.docf_item_precio,DFD.diagnostico_estado FROM INR.dbo.DocFCabecera DFC INNER JOIN INR.dbo.DocFDetalles DFD ON DFC.docf_trans = DFD.docf_trans INNER JOIN
                                             INR.dbo.Catalogo C ON DFD.catalogo_cod = C.catalogo_cod WHERE DFC.fua_nro = ? AND cast(YEAR(docf_fech) as varchar(4)) = cast(YEAR(GETDATE()) as varchar(4))",[$datosFua[0]->Numero]);

/*                 $datosLaboratorio = DB::SELECT("SELECT PDL.pdiario_id,PDL.pdiario_id_orden,PDL.asis_cod,PDL.hcl_num,PDL.pdiario_apat,PDL.pdiario_amat,PDL.pdiario_nomb,PDL.asis_cod AS Expre2,
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
                                                INRDIS_II.dbo.tbServicio S ON SUO.tbServicio_id = S.id INNER JOIN
                                                INRDIS_II.dbo.tbCPT CPT ON S.tbCPT_id = CPT.id                                                           
                                                WHERE SPD.ServicioPersonaCab_id = :idCab AND CONVERT(int,F.Numero) = :numeroFua AND F.Lote = '23' AND SPD.tbEstado_id <> 11 
                                                GROUP BY CPT.codigoCPT,S.codigoServicio,S.descripcion",["idCab"=>$datosFuaIdCab[0]->idCab,"numeroFua"=>$datosFua[0]->Numero]);

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
                                                INRDIS_II.dbo.tbCPT CPT ON AE.CPT = CPT.id 
                                                WHERE FU.Numero = :numeroFua AND FU.Lote = '23' GROUP BY CPT.codigoCPT,CPT.descripcion",["numeroFua"=>$datosFua[0]->Numero]);
                /* return $datosTerapiasCitasHora; */

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
                INRDIS_II.dbo.tbCPT CPT ON AE.CPT = CPT.id 
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
                /* view()->share('datosLaboratorio',$datosLaboratorio); */
                view()->share('datosTerapiaInicial',$datosTerapiaInicial);
                view()->share('datosTerapiasInicial',$datosTerapiasInicial);
                view()->share('datosFuaIdCab',$datosFuaIdCab);
                view()->share('datosTerapiasCitasHora',$datosTerapiasCitasHora);
                view()->share('datosPDiarioDetalles',$datosPDiarioDetalles);

                $pdf = PDF::loadView('paginas.reportesFUA',$datosFua);
                return $pdf->setPaper('a4','portrait')->stream('reportesFUA.pdf');
        }

        public function referencias(Request $request) {

                if($request->ajax()){
                        $idPaciente_pacientesCitados = $request->idPaciente;
                        $datosReferencia = DB::SELECT("SELECT TOP 1 RRH.estb2_cod,TE.descripcion,RRH.ref_rec_hoja_nro FROM INRDIS_II.dbo.Referencias_Rec_Hojas RRH 
                                                       INNER JOIN INRDIS_II.dbo.tbEstablecimiento TE ON RRH.estb2_cod = TE.codigoRenaes COLLATE Modern_Spanish_CI_AS 
                                                       WHERE RRH.Persona_id = ? ORDER BY ref_rec_hoja_fech DESC",[$idPaciente_pacientesCitados]);

                        return response(json_encode($datosReferencia));  
                }
        }

        public function personalC(Request $request){
                if($request->ajax()){
                        $idPersonal_pacientesCitados = $request->idPersonal;
                        $datosPersonal = DB::SELECT("SELECT PER.ddi_cod,PER.ddi_nro,PER.per_apat,PER.per_amat,PER.per_nomb,PER.NroColegiatura,PER.NroRNE,TPS.id AS TipoPersonalSalud_id,TPS.descripcion AS TipoPersonalSalud,
                                                     E.id AS Especialidad_id,E.descripcion AS Especialidad,SE.id AS Egresado_id,SE.descripcion AS Egresado 
                                                     FROM PERSONAL.dbo.personal PER LEFT JOIN ECONOMIA.dbo.sisTIPOPERSONALSALUD TPS ON PER.sisTIPOPERSONALSALUD_id = TPS.id
                                                     LEFT JOIN ECONOMIA.dbo.sisESPECIALIDAD E ON PER.sisESPECIALIDAD_id = E.id
                                                     LEFT JOIN ECONOMIA.dbo.sisEgresado SE ON PER.sisEGRESADO_id = SE.id WHERE PER.id = ?",[$idPersonal_pacientesCitados]);

                        return response(json_encode($datosPersonal));
                }
        }

        public function codigoCie(Request $request){
                if($request->ajax()){
                        $codigoCie_pacientesCitados = $request->CodigoCie;
                        $datosCodigoCie = DB::SELECT("SELECT C.cie_cod,C.cie_desc FROM inrdis_ii.dbo.tblCIE C 
                                                      WHERE C.cie_cod = ?",[$codigoCie_pacientesCitados]);
                        return response(json_encode($datosCodigoCie));
                }
        }

        public function generarFua(Request $request){

                if($request->ajax()){
                        $datos =  array("usuario_pacientesCitados"=>$request->usuario_pacientesCitados,
                                        "personalAtiendeF_pacientesCitados"=>$request->personalAtiendeF_pacientesCitados,
                                        "lugarAtencionF_pacientesCitados"=>$request->lugarAtencionF_pacientesCitados,
                                        "tipoAtencionF_pacientesCitados"=>$request->tipoAtencionF_pacientesCitados,
                                        "codigoReferenciaF_pacientesCitados"=>$request->codigoReferenciaF_pacientesCitados,
                                        "descripcionReferenciaF_pacientesCitados"=>$request->descripcionReferenciaF_pacientesCitados,
                                        "numeroReferenciaF_pacientesCitados"=>$request->numeroReferenciaF_pacientesCitados,
                                        "tipoDocumentoF_pacientesCitados"=>$request->tipoDocumentoF_pacientesCitados,
                                        "numeroDocumentoF_pacientesCitados"=>$request->numeroDocumentoF_pacientesCitados,
                                        "componenteF_pacientesCitados"=>$request->componenteF_pacientesCitados,
                                        "codigoAsegurado1F_pacientesCitados"=>$request->codigoAsegurado1F_pacientesCitados,
                                        "codigoAsegurado2F_pacientesCitados"=>$request->codigoAsegurado2F_pacientesCitados,
                                        "codigoAsegurado3F_pacientesCitados"=>$request->codigoAsegurado3F_pacientesCitados,
                                        "apellidoPaternoF_pacientesCitados"=>$request->apellidoPaternoF_pacientesCitados,
                                        "apellidoMaternoF_pacientesCitados"=>$request->apellidoMaternoF_pacientesCitados,
                                        "primerNombreF_pacientesCitados"=>$request->primerNombreF_pacientesCitados,
                                        "otroNombreF_pacientesCitados"=>$request->otroNombreF_pacientesCitados,
                                        "sexoF_pacientesCitados"=>$request->sexoF_pacientesCitados,
                                        "fechaNacimientoF_pacientesCitados"=>$request->fechaNacimientoF_pacientesCitados,
                                        "historiaF_pacientesCitados"=>$request->historiaF_pacientesCitados,
                                        "fechaF_pacientesCitados"=>$request->fechaF_pacientesCitados,
                                        "horaF_pacientesCitados"=>$request->horaF_pacientesCitados,
                                        "codigoPrestacionalF_pacientesCitados"=>$request->codigoPrestacionalF_pacientesCitados,
                                        "conceptoPrestacionalF_pacientesCitados"=>$request->conceptoPrestacionalF_pacientesCitados,
                                        "destinoAseguradoF_pacientesCitados"=>$request->destinoAseguradoF_pacientesCitados,
                                        "diagnosticoF_pacientesCitados"=>$request->diagnosticoF_pacientesCitados,
                                        "codigoCieNF_pacientesCitados"=>$request->codigoCieNF_pacientesCitados,
                                        "tipoDocumentoP_pacientesCitados"=>$request->tipoDocumentoP_pacientesCitados,
                                        "numeroDocumentoP_pacientesCitados"=>$request->numeroDocumentoP_pacientesCitados,
                                        "nombresApellidosP_pacientesCitados"=>$request->nombresApellidosP_pacientesCitados,
                                        "tipoPersonalSaludF_pacientesCitados"=>$request->tipoPersonalSaludF_pacientesCitados,
                                        "egresadoF_pacientesCitados"=>$request->egresadoF_pacientesCitados,
                                        "colegiaturaF_pacientesCitados"=>$request->colegiaturaF_pacientesCitados,
                                        "especialidadF_pacientesCitados"=>$request->especialidadF_pacientesCitados,
                                        "rneF_pacientesCitados"=>$request->rneF_pacientesCitados,
                                        "observacionesF_pacientesCitados"=>$request->observacionesF_pacientesCitados,
                                        "pacienteIdF_pacientesCitados"=>$request->pacienteIdF_pacientesCitados,
                                        "idRegistroF_pacientesCitados"=>$request->idRegistroF_pacientesCitados,
                                        "modeloF_pacientesCitados"=>$request->modeloF_pacientesCitados,
                                        "idCab_pacientesCitadosC"=>$request->idCab_pacientesCitadosC,
                                        "unidadOrganicaId_pacientesCitadosC"=>$request->unidadOrganicaId_pacientesCitadosC,
                                        "correlativoSis_pacientesCitadosC"=>$request->correlativoSis_pacientesCitadosC,
                                        "tipoTablaSis_pacientesCitadosC"=>$request->tipoTablaSis_pacientesCitadosC,
                                        "idNumRegSis_pacientesCitadosC"=>$request->idNumRegSis_pacientesCitadosC,
                                        "idPlanSis_pacientesCitadosC"=>$request->idPlanSis_pacientesCitadosC,
                                        "idUbigeoSis_pacientesCitadosC"=>$request->idUbigeoSis_pacientesCitadosC,
                                        "codigoOficinaF_pacientesCitados"=>$request->codigoOficinaF_pacientesCitados,
                                        "codigoOperacionF_pacientesCitados"=>$request->codigoOperacionF_pacientesCitados);

                                /* return $datos; */

                        /* INICIO DEL SEXO DEL PACIENTE */
                        if($datos["sexoF_pacientesCitados"] == "MASCULINO"){
                                $datos["sexoF_pacientesCitados"] = "1";
                        }else{
                                $datos["sexoF_pacientesCitados"] = "0";
                        }
                        /* FIN DEL SEXO DEL PACIENTE */

                        /* INICIO DEL DOCUMENTO DEL PACIENTE */
                        if($datos["tipoDocumentoF_pacientesCitados"] == "D.N.I."){
                                $datos["tipoDocumentoF_pacientesCitados"] = "1";
                        }else{
                                $datos["tipoDocumentoF_pacientesCitados"] = "3";
                        }
                        /* FIN DEL DOCUMENTOS DEL PACIENTE */

                        /* ES PARA EL DOCUMENTO DEL PERSONAL */
                        if($datos["tipoDocumentoP_pacientesCitados"] == "D.N.I."){
                                $datos["tipoDocumentoP_pacientesCitados"] = "1";
                        }else{
                                $datos["tipoDocumentoP_pacientesCitados"] = "3";
                        }
                        /* FIN DEL DOCUMENTO DEL PERSONAL */

                        /* return $datos; */

                        if(!empty($datos)){
                                $validar = \Validator::make($datos,[
                                        "usuario_pacientesCitados"=>'required',
                                        "personalAtiendeF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "lugarAtencionF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "tipoAtencionF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "codigoReferenciaF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "numeroReferenciaF_pacientesCitados"=>'required|regex:/^[-\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ]+$/i',
                                        "componenteF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "codigoAsegurado2F_pacientesCitados"=>'required|regex:/^[0-9a-zA-ZñÑáéíóúÁÉÍÓÚ]+$/i',
                                        "codigoAsegurado3F_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "tipoDocumentoF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "numeroDocumentoF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "apellidoPaternoF_pacientesCitados"=>"required|regex:/^['\\.\\a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i",
                                        "primerNombreF_pacientesCitados"=>"required|regex:/^['\\a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i",
                                        "sexoF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "fechaNacimientoF_pacientesCitados"=>'required',
                                        "historiaF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "fechaF_pacientesCitados"=>'nullable',
                                        "horaF_pacientesCitados"=>'nullable',
                                        "codigoPrestacionalF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "conceptoPrestacionalF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "destinoAseguradoF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "nombresApellidosP_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "tipoTablaSis_pacientesCitadosC"=>'required',
                                        "idNumRegSis_pacientesCitadosC"=>'required',
                                        "idUbigeoSis_pacientesCitadosC"=>'required'
                                ]);

                                if($validar->fails()){
                                        return "NO-VALIDACION";
                                }else{
                                        /* CON ESTO VALIDAMOS QUE SE AGREGUE UNA UNIDAD AL FUA FINAL */
                                        $idUltimoFua = DB::SELECT("SELECT TOP 1 FUA.Numero,FUA.IdAtencion FROM [ECONOMIA].[dbo].[FUA2] FUA WHERE FUA.Lote <> 'DA' ORDER BY IdAtencion DESC");
                                        $nuevoValorFua = $idUltimoFua[0]->Numero + 1;
                                        $valorFinalFua = str_pad($nuevoValorFua, 8, "0", STR_PAD_LEFT);

                                        $verificamosNumeracion = DB::SELECT("SELECT FUA.Numero FROM [ECONOMIA].[dbo].[FUA2] FUA WHERE FUA.Numero = ? AND FUA.Lote = 23",[$nuevoValorFua]);

                                        if(!empty($verificamosNumeracion)){
                                                return "FUA-DUPLICADO";
                                        }else{
                                                //VALIDACIÓN DE DATOS CON EL NÚMERO DE HISTORIA CLINICA
                                                $verificamosHistoria = DB::SELECT("SELECT apPaterno,apMaterno FROM [INRDIS_II].[dbo].[Persona] P WHERE P.nroDocIdentidad = ?",[$datos["numeroDocumentoF_pacientesCitados"]]);

                                                if((trim($verificamosHistoria[0]->apPaterno) != $datos["apellidoPaternoF_pacientesCitados"]) && (trim($verificamosHistoria[0]->apMaterno) != $datos["apellidoMaternoF_pacientesCitados"])) {
                                                        return "DATOS-ERRONEOS";
                                                }else{
                                                        
                                                $generarFua = new FuaModel();
                                                $generarFua->DISA = "00007734";
                                                $generarFua->Lote = /* date("y"); */23;
                                                $generarFua->Numero = $valorFinalFua;/* HAY QUE MODIFICAR LOS DATOS */
                                                $generarFua->IPRESS = "00007734";
                                                $generarFua->EsReconsiderecion = "N";
                                                $generarFua->DISAReconsideracion = '';
                                                $generarFua->LoteReconsideracion = '';
                                                $generarFua->NumeroReconsideracion = '';
                                                $generarFua->IdConvenio = "0";
                                                $generarFua->Componente = $datos["componenteF_pacientesCitados"];
                                                $generarFua->DISAAsegurado = $datos["codigoAsegurado1F_pacientesCitados"];
                                                $generarFua->LoteAsegurado = $datos["codigoAsegurado2F_pacientesCitados"];
                                                $generarFua->NumeroAsegurado = $datos["codigoAsegurado3F_pacientesCitados"];
                                                $generarFua->TipoDocumentoIdentidad = $datos["tipoDocumentoF_pacientesCitados"];
                                                $generarFua->NroDocumentoIdentidad = $datos["numeroDocumentoF_pacientesCitados"];
                                                $generarFua->ApellidoPaterno = $datos["apellidoPaternoF_pacientesCitados"];
                                                $generarFua->ApellidoMaterno = $datos["apellidoMaternoF_pacientesCitados"];
                                                $generarFua->PrimerNombre = $datos["primerNombreF_pacientesCitados"];
                                                $generarFua->OtrosNombres = $datos["otroNombreF_pacientesCitados"];
                                                $generarFua->FechaNacimiento = $datos["fechaNacimientoF_pacientesCitados"] . 'T' . date("00:00:00.000");
                                                $generarFua->Sexo = $datos["sexoF_pacientesCitados"];
                                                $generarFua->HistoriaClinica = $datos["historiaF_pacientesCitados"];
                                                $generarFua->TipoAtencion = $datos["tipoAtencionF_pacientesCitados"];
                                                $generarFua->SaludMaterna = "0";
                                                $generarFua->ModalidadAtencion = $datos["conceptoPrestacionalF_pacientesCitados"];

                                                if($datos["fechaF_pacientesCitados"] != '' && $datos["horaF_pacientesCitados"] != ''){
                                                        $generarFua->FechaHoraAtencion = $datos["fechaF_pacientesCitados"] . 'T' . $datos["horaF_pacientesCitados"] . ':00.000';
                                                }else{
                                                        $generarFua->FechaHoraAtencion = null;
                                                }

                                                $generarFua->IPRESSRefirio = $datos["codigoReferenciaF_pacientesCitados"];
                                                $generarFua->NroHojaReferencia = $datos["numeroReferenciaF_pacientesCitados"];
                                                $generarFua->CodigoPrestacional = $datos["codigoPrestacionalF_pacientesCitados"];
                                                $generarFua->PersonaAtiende = $datos["personalAtiendeF_pacientesCitados"];
                                                $generarFua->LugarAtencion = $datos["lugarAtencionF_pacientesCitados"];
                                                $generarFua->DestinoAsegurado = $datos["destinoAseguradoF_pacientesCitados"];
                                                $generarFua->IPRESSContrareferencia = '';
                                                $generarFua->NroHojaContrareferencia = '';
                                                $generarFua->TipoDocResponsable = $datos["tipoDocumentoP_pacientesCitados"];
                                                $generarFua->NroDocResponsable = $datos["numeroDocumentoP_pacientesCitados"];
                                                $generarFua->TipoPersonalSalud = $datos["tipoPersonalSaludF_pacientesCitados"];
                                                $generarFua->Especialidad = $datos["especialidadF_pacientesCitados"];
                                                $generarFua->EsEgresado = $datos["egresadoF_pacientesCitados"];
                                                $generarFua->NroColegiatura = $datos["colegiaturaF_pacientesCitados"];
                                                $generarFua->NroRNE = $datos["rneF_pacientesCitados"];
                                                $generarFua->FechaHoraRegistro = date("Y-m-d") . 'T' . date("H:i:s.v");
                                                $generarFua->Observacion = $datos["observacionesF_pacientesCitados"];
                                                $generarFua->VersionAplicativoOrigen = "SOFT.UFPA";
                                                $generarFua->persona_id = $datos["pacienteIdF_pacientesCitados"];
                                                $generarFua->personalAtiende_id = $datos["nombresApellidosP_pacientesCitados"];
                                                $generarFua->personalResponsable_id = $datos["nombresApellidosP_pacientesCitados"];
                                                $generarFua->cie1_cod = $datos["codigoCieNF_pacientesCitados"];
                                                $generarFua->pdr1_cod = $datos["diagnosticoF_pacientesCitados"];
                                                $generarFua->generarFua_estado = '1';
                                                $generarFua->correlativo = $datos["correlativoSis_pacientesCitadosC"];
                                                $generarFua->tablaContrato = $datos["tipoTablaSis_pacientesCitadosC"];
                                                $generarFua->idFormatoContrato = $datos["idNumRegSis_pacientesCitadosC"];
                                                $generarFua->planAsegurado = $datos["idPlanSis_pacientesCitadosC"];
                                                $generarFua->ubigeoAsegurado = $datos["idUbigeoSis_pacientesCitadosC"];
                                                $generarFua->fua_libre = '0';
                                                $generarFua->save();

                                                $insertedId = $generarFua->id;

                                                /* VAMOS A ACTUALIZAR LA FUA CON EL CODIGO FUA (CORRECTO) */

                                                $datos1 = array("FUA_id"=>$insertedId,"id_usuario"=>$request->usuario_pacientesCitados);
                                                $datos2 = array("sustentoPago_id"=>$insertedId,"id_usuario"=>$request->usuario_pacientesCitados);

                                                if($datos["modeloF_pacientesCitados"] == "PDIARIO"){
                                                        if($datos["unidadOrganicaId_pacientesCitadosC"] == 19){
                                                                DB::UPDATE("UPDATE PDD SET PDD.FUA_id = :nroFua,PDD.id_usuario = :idUsuario FROM [INRDIS_II].[dbo].[PDiarioDetalles] PDD INNER JOIN [INRDIS_II].[dbo].[PDiarioCabecera] PDC
                                                                        ON PDD.PDiarioCabecera_id = PDC.id WHERE PDD.Persona_id = :registroId AND PDC.UnidadOrganica_id = 19 AND CAST(PDC.fecha AS DATE) = :fecha",
                                                                        ["nroFua"=>$insertedId,"registroId"=>$datos["pacienteIdF_pacientesCitados"],"fecha"=>$datos["fechaF_pacientesCitados"],"idUsuario"=>$datos["usuario_pacientesCitados"]]);
                                                        }else if($datos["unidadOrganicaId_pacientesCitadosC"] == 41){
                                                                DB::UPDATE("UPDATE PDD SET PDD.FUA_id = :nroFua,PDD.id_usuario = :idUsuario FROM [INRDIS_II].[dbo].[PDiarioDetalles] PDD INNER JOIN [INRDIS_II].[dbo].[PDiarioCabecera] PDC
                                                                        ON PDD.PDiarioCabecera_id = PDC.id WHERE PDD.Persona_id = :registroId AND PDC.UnidadOrganica_id = 41 AND CAST(PDC.fecha AS DATE) = :fecha",
                                                                        ["nroFua"=>$insertedId,"registroId"=>$datos["pacienteIdF_pacientesCitados"],"fecha"=>$datos["fechaF_pacientesCitados"],"idUsuario"=>$datos["usuario_pacientesCitados"]]);
                                                        }else if($datos["unidadOrganicaId_pacientesCitadosC"] == 26){
                                                                DB::UPDATE("UPDATE PDD SET PDD.FUA_id = :nroFua,PDD.id_usuario = :idUsuario FROM [INRDIS_II].[dbo].[PDiarioDetalles] PDD INNER JOIN [INRDIS_II].[dbo].[PDiarioCabecera] PDC
                                                                        ON PDD.PDiarioCabecera_id = PDC.id WHERE PDD.Persona_id = :registroId AND PDC.UnidadOrganica_id = 26 AND CAST(PDC.fecha AS DATE) = :fecha",
                                                                        ["nroFua"=>$insertedId,"registroId"=>$datos["pacienteIdF_pacientesCitados"],"fecha"=>$datos["fechaF_pacientesCitados"],"idUsuario"=>$datos["usuario_pacientesCitados"]]);
                                                        }else{
                                                                $fua1Actualizacion = Fua1ActualizacionModel::where('id',$datos["idRegistroF_pacientesCitados"])->update($datos1);
                                                        }
                                                }

                                                if($datos["modeloF_pacientesCitados"] == "SERVICIODET"){
                                                        $fua2Actualizacion = Fua2ActualizacionModel::where('id',$datos["idRegistroF_pacientesCitados"])->update($datos2);
        
                                                        /* INICIO PARA REPETIR FUAS EN HOSPITALIZACIÓN*/
                                                        $datosHospitalizado = DB::SELECT("SELECT SUM(1) as hospitalizado, SPC.serviciopadre_id FROM INRDIS_II.dbo.ServicioPersonaCab SPC INNER JOIN
                                                                                          INRDIS_II.dbo.ServicioPersonaDet SPD ON SPC.id = SPD.ServicioPersonaCab_id INNER JOIN
                                                                                          INRDIS_II.dbo.ServicioPersonaCab AS ServicioPersonaCab_1 ON SPC.ServicioPadre_id = ServicioPersonaCab_1.ServicioPadre_id
                                                                                          WHERE (SPD.id = :idRegistroF_pacientesCitados) AND (ServicioPersonaCab_1.tbServicio_UOrganica_id = 1980)
                                                                                          GROUP BY SPC.serviciopadre_id",["idRegistroF_pacientesCitados"=>$datos["idRegistroF_pacientesCitados"]]);
                                                        /* INICIO DE REPETIR FUAS EN LABORATORIO*/
                                                        $datosLaboratorio = DB::SELECT("SELECT SUM(1) as laboratorio, SPC.serviciopadre_id FROM INRDIS_II.dbo.ServicioPersonaCab SPC INNER JOIN
                                                                                        INRDIS_II.dbo.ServicioPersonaDet SPD ON SPC.id = SPD.ServicioPersonaCab_id INNER JOIN
                                                                                        INRDIS_II.dbo.ServicioPersonaCab AS ServicioPersonaCab_1 ON SPC.ServicioPadre_id = ServicioPersonaCab_1.ServicioPadre_id
                                                                                        WHERE (SPD.id = :idRegistroF_pacientesCitados) AND (ServicioPersonaCab_1.tbServicio_UOrganica_id = 8307)
                                                                                        GROUP BY SPC.serviciopadre_id",["idRegistroF_pacientesCitados"=>$datos["idRegistroF_pacientesCitados"]]);
                                                        /* FIN DE REPETIR FUAS EN LABORATORIO */
        
                                                        /* INICIO DE REPETIR FUAS EN RAYOS X */
                                                        $datosRayosx = DB::SELECT("SELECT SUM(1) as rayosx, SPC.serviciopadre_id FROM INRDIS_II.dbo.ServicioPersonaCab SPC INNER JOIN
                                                                                   INRDIS_II.dbo.ServicioPersonaDet SPD ON SPC.id = SPD.ServicioPersonaCab_id INNER JOIN
                                                                                   INRDIS_II.dbo.ServicioPersonaCab AS ServicioPersonaCab_1 ON SPC.ServicioPadre_id = ServicioPersonaCab_1.ServicioPadre_id
                                                                                   WHERE (SPD.id = :idRegistroF_pacientesCitados) AND (ServicioPersonaCab_1.tbServicio_UOrganica_id = 8309)
                                                                                   GROUP BY SPC.serviciopadre_id",["idRegistroF_pacientesCitados"=>$datos["idRegistroF_pacientesCitados"]]);
        
                                                        /* return $datosRayosx; */
                                                        /* FIN DE REPETIR FUAS EN RAYOS X */
        
                                                        /* REALIZAMOS LAS CONSULTAS PARA LOS CASOS ANTERIORES */
        
                                                        if(!empty($datosHospitalizado)){
                                                                if($datosHospitalizado[0]->hospitalizado == 1){
                                                                        /* return "hospitalizado"; */
                                                                        DB::UPDATE("UPDATE SPD SET sustentoPago_id=:idFua,id_usuario=:idUsuario
                                                                                    FROM INRDIS_II.dbo.ServicioPersonaCab SPC INNER JOIN
                                                                                    INRDIS_II.dbo.ServicioPersonaDet SPD ON SPC.id = SPD.ServicioPersonaCab_id INNER JOIN
                                                                                    INRDIS_II.dbo.tbServicio_UOrganica SUO ON SPC.tbServicio_UOrganica_id = SUO.id
                                                                                    WHERE (SPC.ServicioPadre_id = :idpadre) AND (SUO.tbGrupoServicio_id = 2) AND (SPC.Persona_id = :PersonaPacienteId)",[
                                                                                    "idFua"=>$insertedId,"idpadre"=>$datosHospitalizado[0]->serviciopadre_id,"PersonaPacienteId"=>$datos["pacienteIdF_pacientesCitados"],"idUsuario"=>$datos["usuario_pacientesCitados"]]);
                                                                }else{
                                                                        /* return "terapias"; */
                                                                        $idCab = DB::SELECT("SELECT SPD.ServicioPersonaCab_id FROM INRDIS_II.dbo.ServicioPersonaDet SPD WHERE (SPD.id = :id)",["id"=>$datos["idRegistroF_pacientesCitados"]]);
                
                                                                        if(!empty($idCab)){
                                                                                $idNuevoG = DB::SELECT("SELECT SUO.tbServicio_id,SUO.tbGrupoProfesional_id FROM INRDIS_II.dbo.tbServicio_UOrganica SUO INNER JOIN
                                                                                                        INRDIS_II.dbo.ServicioPersonaCab SPC ON SUO.id = SPC.tbServicio_UOrganica_id WHERE (SPC.id = :idcab)",["idcab"=>$idCab[0]->ServicioPersonaCab_id]);
                
                                                                                if(!empty($idNuevoG)){
                                                                                        if($idNuevoG[0]->tbServicio_id == 156 || $idNuevoG[0]->tbServicio_id == 115 || $idNuevoG[0]->tbGrupoProfesional_id == 4 || $idNuevoG[0]->tbGrupoProfesional_id == 6){
                                                                                                DB::UPDATE("UPDATE INRDIS_II.dbo.ServicioPersonaDet SET sustentoPago_id = :idFua,id_usuario=:idUsuario WHERE (id=:id)",
                                                                                                            ["idFua"=>$insertedId,"id"=>$datos["idRegistroF_pacientesCitados"],"idUsuario"=>$datos["usuario_pacientesCitados"]]);
                                                                                        }else{
                                                                                                DB::UPDATE("UPDATE INRDIS_II.dbo.ServicioPersonaDet SET sustentoPago_id = :idFua,id_usuario=:idUsuario WHERE ServicioPersonaCab_id=:idcab",
                                                                                                ["idFua"=>$insertedId,"idcab"=>$idCab[0]->ServicioPersonaCab_id,"idUsuario"=>$datos["usuario_pacientesCitados"]]);
                                                                                        }
                                                                                }
                                                                        }
                                                                }
                                                        }elseif(!empty($datosLaboratorio)){
                                                                if($datosLaboratorio[0]->laboratorio == 1 && $datos["nombresApellidosP_pacientesCitados"] == 907){
                                                                        /* return "laboratorio"; */
                                                                        DB::UPDATE("UPDATE SPD SET sustentoPago_id=:idFua,id_usuario=:idUsuario
                                                                                    FROM INRDIS_II.dbo.ServicioPersonaCab SPC INNER JOIN
                                                                                    INRDIS_II.dbo.ServicioPersonaDet SPD ON SPC.id = SPD.ServicioPersonaCab_id INNER JOIN
                                                                                    INRDIS_II.dbo.tbServicio_UOrganica SUO ON SPC.tbServicio_UOrganica_id = SUO.id
                                                                                    WHERE (SPC.ServicioPadre_id = :idpadre) AND (SUO.tbGrupoServicio_id = 4) AND (SPC.Persona_id = :PersonaPacienteId)",[
                                                                                    "idFua"=>$insertedId,"idpadre"=>$datosHospitalizado[0]->serviciopadre_id,"PersonaPacienteId"=>$datos["pacienteIdF_pacientesCitados"],"idUsuario"=>$datos["usuario_pacientesCitados"]]);
                                                                }else{
                                                                        /* return "terapias"; */
                                                                        $idCab = DB::SELECT("SELECT SPD.ServicioPersonaCab_id FROM INRDIS_II.dbo.ServicioPersonaDet SPD WHERE (SPD.id = :id)",["id"=>$datos["idRegistroF_pacientesCitados"]]);
                
                                                                        if(!empty($idCab)){
                                                                                $idNuevoG = DB::SELECT("SELECT SUO.tbServicio_id,SUO.tbGrupoProfesional_id FROM INRDIS_II.dbo.tbServicio_UOrganica SUO INNER JOIN
                                                                                                        INRDIS_II.dbo.ServicioPersonaCab SPC ON SUO.id = SPC.tbServicio_UOrganica_id WHERE (SPC.id = :idcab)",["idcab"=>$idCab[0]->ServicioPersonaCab_id]);
                
                                                                                if(!empty($idNuevoG)){
                                                                                        if($idNuevoG[0]->tbServicio_id == 156 || $idNuevoG[0]->tbServicio_id == 115 || $idNuevoG[0]->tbGrupoProfesional_id == 4 || $idNuevoG[0]->tbGrupoProfesional_id == 6){
                                                                                                DB::UPDATE("UPDATE INRDIS_II.dbo.ServicioPersonaDet SET sustentoPago_id = :idFua,id_usuario=:idUsuario WHERE (id=:id)",
                                                                                                            ["idFua"=>$insertedId,"id"=>$datos["idRegistroF_pacientesCitados"],"idUsuario"=>$datos["usuario_pacientesCitados"]]);
                                                                                        }else{
                                                                                                DB::UPDATE("UPDATE INRDIS_II.dbo.ServicioPersonaDet SET sustentoPago_id = :idFua,id_usuario=:idUsuario WHERE ServicioPersonaCab_id=:idcab",
                                                                                                ["idFua"=>$insertedId,"idcab"=>$idCab[0]->ServicioPersonaCab_id,"idUsuario"=>$datos["usuario_pacientesCitados"]]);
                                                                                        }
                                                                                }
                                                                        }
                                                                }
                                                        }elseif(!empty($datosRayosx)){
                                                                if($datosRayosx[0]->rayosx == 1 && $datos["nombresApellidosP_pacientesCitados"] == 908){
                                                                        /* return "rayosx"; */
                                                                        DB::UPDATE("UPDATE SPD SET sustentoPago_id=:idFua,id_usuario=:idUsuario
                                                                                    FROM INRDIS_II.dbo.ServicioPersonaCab SPC INNER JOIN
                                                                                    INRDIS_II.dbo.ServicioPersonaDet SPD ON SPC.id = SPD.ServicioPersonaCab_id INNER JOIN
                                                                                    INRDIS_II.dbo.tbServicio_UOrganica SUO ON SPC.tbServicio_UOrganica_id = SUO.id
                                                                                    WHERE (SPC.ServicioPadre_id = :idpadre) AND (SUO.tbPrograma_id IN (34, 103)) AND (SPC.Persona_id = :PersonaPacienteId)",[
                                                                                    "idFua"=>$insertedId,"idpadre"=>$datosHospitalizado[0]->serviciopadre_id,"PersonaPacienteId"=>$datos["pacienteIdF_pacientesCitados"],"idUsuario"=>$datos["usuario_pacientesCitados"]]);
                                                                }else{
                                                                        /* return "terapias"; */
                                                                        $idCab = DB::SELECT("SELECT SPD.ServicioPersonaCab_id FROM INRDIS_II.dbo.ServicioPersonaDet SPD WHERE (SPD.id = :id)",["id"=>$datos["idRegistroF_pacientesCitados"]]);
                
                                                                        if(!empty($idCab)){
                                                                                $idNuevoG = DB::SELECT("SELECT SUO.tbServicio_id,SUO.tbGrupoProfesional_id FROM INRDIS_II.dbo.tbServicio_UOrganica SUO INNER JOIN
                                                                                                        INRDIS_II.dbo.ServicioPersonaCab SPC ON SUO.id = SPC.tbServicio_UOrganica_id WHERE (SPC.id = :idcab)",["idcab"=>$idCab[0]->ServicioPersonaCab_id]);
                
                                                                                if(!empty($idNuevoG)){
                                                                                        if($idNuevoG[0]->tbServicio_id == 156 || $idNuevoG[0]->tbServicio_id == 115 || $idNuevoG[0]->tbGrupoProfesional_id == 4 || $idNuevoG[0]->tbGrupoProfesional_id == 6){
                                                                                                DB::UPDATE("UPDATE INRDIS_II.dbo.ServicioPersonaDet SET sustentoPago_id = :idFua,id_usuario=:idUsuario WHERE (id=:id)",
                                                                                                            ["idFua"=>$insertedId,"id"=>$datos["idRegistroF_pacientesCitados"],"idUsuario"=>$datos["usuario_pacientesCitados"]]);
                                                                                        }else{
                                                                                                DB::UPDATE("UPDATE INRDIS_II.dbo.ServicioPersonaDet SET sustentoPago_id = :idFua,id_usuario=:idUsuario WHERE ServicioPersonaCab_id=:idcab",
                                                                                                ["idFua"=>$insertedId,"idcab"=>$idCab[0]->ServicioPersonaCab_id,"idUsuario"=>$datos["usuario_pacientesCitados"]]);
                                                                                        }
                                                                                }
                                                                        }
                                                                }
                                                        }else{
                                                                /* return "terapias"; */
                                                                $idCab = DB::SELECT("SELECT SPD.ServicioPersonaCab_id FROM INRDIS_II.dbo.ServicioPersonaDet SPD WHERE (SPD.id = :id)",["id"=>$datos["idRegistroF_pacientesCitados"]]);
                
                                                                if(!empty($idCab)){
                                                                        $idNuevoG = DB::SELECT("SELECT SUO.tbServicio_id,SUO.tbGrupoProfesional_id FROM INRDIS_II.dbo.tbServicio_UOrganica SUO INNER JOIN
                                                                                                INRDIS_II.dbo.ServicioPersonaCab SPC ON SUO.id = SPC.tbServicio_UOrganica_id WHERE (SPC.id = :idcab)",["idcab"=>$idCab[0]->ServicioPersonaCab_id]);
                                                                        
                                                                        if(!empty($idNuevoG)){
                                                                                if($idNuevoG[0]->tbServicio_id == 156 || $idNuevoG[0]->tbServicio_id == 115 || $idNuevoG[0]->tbGrupoProfesional_id == 4 || $idNuevoG[0]->tbGrupoProfesional_id == 6){
                                                                                        DB::UPDATE("UPDATE INRDIS_II.dbo.ServicioPersonaDet SET sustentoPago_id = :idFua,id_usuario=:idUsuario WHERE (id=:id)",
                                                                                                        ["idFua"=>$insertedId,"id"=>$datos["idRegistroF_pacientesCitados"],"idUsuario"=>$datos["usuario_pacientesCitados"]]);
                                                                                }else{
                                                                                        DB::UPDATE("UPDATE INRDIS_II.dbo.ServicioPersonaDet SET sustentoPago_id = :idFua,id_usuario=:idUsuario WHERE ServicioPersonaCab_id=:idcab",
                                                                                        ["idFua"=>$insertedId,"idcab"=>$idCab[0]->ServicioPersonaCab_id,"idUsuario"=>$datos["usuario_pacientesCitados"]]);
                                                                                }
                                                                        }
                                                                }
                                                        }
        
                                                        /* FIN DE LAS CONSULTAS PARA LOS CASOS ANTERIORES */
                                                }

                                                if($datos["modeloF_pacientesCitados"] == "CITASHORA"){
                                                        $fua3Actualizacion = Fua3ActualizacionModel::where('cext_id',$datos["idRegistroF_pacientesCitados"])->update($datos1);
                                                }

                                                if($datos["idCab_pacientesCitadosC"] != ''){
                                                        $datosTerapiasInicial = DB::SELECT("SELECT COUNT(SPD.id) AS cantidad_citas,CPT.codigoCPT,S.descripcion
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
                                                        WHERE SPD.ServicioPersonaCab_id = :idCab AND CONVERT(int,F.Numero) = :numeroFua AND SPD.tbEstado_id <> 11 
                                                        GROUP BY CPT.codigoCPT,S.descripcion",["idCab"=>$datos["idCab_pacientesCitadosC"],"numeroFua"=>$generarFua->Numero]);
        
                                                        if(!empty($datosTerapiasInicial)){
                                                                $valor1 = $datosTerapiasInicial[0]->cantidad_citas;
                                                                $valor2 = $datosTerapiasInicial[0]->descripcion;
                                                                $valor3 = $datosTerapiasInicial[0]->codigoCPT;
                                                        }else{
                                                                $valor1 = '';
                                                                $valor2 = '';
                                                                $valor3 = '';
                                                        }
                                                }else{
                                                        $valor1 = '';
                                                        $valor2 = '';
                                                        $valor3 = '';
                                                }

                                                $generarFuaN = new FuaNModel();
                                                $generarFuaN->NroFua = $insertedId;
                                                $generarFuaN->TipoAccion = 1; /* SI ES 1 ES PARA GENERACION Y 0 PARA ANULACION */
                                                $generarFuaN->IdUsuario = $datos["usuario_pacientesCitados"];
                                                $generarFuaN->idCab = $datos["idCab_pacientesCitadosC"];
                                                $generarFuaN->terapias_prog = $valor1;
                                                $generarFuaN->codigoTerapia = $valor3;
                                                $generarFuaN->descripcionTerapia = $valor2;
                                                $generarFuaN->codigoOficina = $datos["codigoOficinaF_pacientesCitados"];
                                                $generarFuaN->codigoOperacion = $datos["codigoOperacionF_pacientesCitados"];
                                                $generarFuaN->save();
                                                
                                                return array("GUARDAR-FUA", $insertedId);
                                           }
                                        }
 
                                }
                        }else{
                                return "ERROR";
                        }
                }
        }

        public function generarFuaLibre(Request $request){
                if($request->ajax()){
                        $datos =  array("usuario_pacientesCitados"=>$request->usuarioFLibre_pacientesCitados,
                                        "personalAtiendeF_pacientesCitados"=>$request->personalAtiendeFL_pacientesCitados,
                                        "lugarAtencionF_pacientesCitados"=>$request->lugarAtencionFL_pacientesCitados,
                                        "tipoAtencionF_pacientesCitados"=>$request->tipoAtencionFL_pacientesCitados,
                                        "codigoReferenciaF_pacientesCitados"=>$request->codigoReferenciaFL_pacientesCitados,
                                        "descripcionReferenciaF_pacientesCitados"=>$request->descripcionReferenciaFL_pacientesCitados,
                                        "numeroReferenciaF_pacientesCitados"=>$request->numeroReferenciaFL_pacientesCitados,
                                        "tipoDocumentoF_pacientesCitados"=>$request->tipoDocumentoFL_pacientesCitados,
                                        "numeroDocumentoF_pacientesCitados"=>$request->documentoN_pacientesCitados,
                                        "componenteF_pacientesCitados"=>$request->componenteFL_pacientesCitados,
                                        "codigoAsegurado1FL_pacientesCitados"=>$request->codigoAsegurado1FL_pacientesCitados,
                                        "codigoAsegurado2FL_pacientesCitados"=>$request->codigoAsegurado2FL_pacientesCitados,
                                        "codigoAsegurado3FL_pacientesCitados"=>$request->codigoAsegurado3FL_pacientesCitados,
                                        "apellidoPaternoF_pacientesCitados"=>$request->apellidoPaterno_pacientesCitados,
                                        "apellidoMaternoF_pacientesCitados"=>$request->apellidoMaterno_pacientesCitados,
                                        "primerNombreF_pacientesCitados"=>$request->primerNombreFL_pacientesCitados,
                                        "otroNombreF_pacientesCitados"=>$request->otroNombreFL_pacientesCitados,
                                        "sexoF_pacientesCitados"=>$request->sexoFL_pacientesCitados,
                                        "fechaNacimientoF_pacientesCitados"=>$request->fechaNacimientoFL_pacientesCitados,
                                        "historiaF_pacientesCitados"=>$request->historiaClinica_pacientesCitados,
                                        "fechaF_pacientesCitados"=>$request->fechaAtencion_pacientesCitados,
                                        "horaF_pacientesCitados"=>$request->hora_pacientesCitados,
                                        "codigoPrestacionalF_pacientesCitados"=>$request->codigoPrestacional_pacientesCitados,
                                        "conceptoPrestacionalF_pacientesCitados"=>$request->conceptoPrestacionalFL_pacientesCitados,
                                        "destinoAseguradoF_pacientesCitados"=>$request->destinoAseguradoFL_pacientesCitados,
                                        "fechaIngresoF_pacientesCitados"=>$request->fechaIngresoF_pacientesCitados,
                                        "fechaAltaF_pacientesCitados"=>$request->fechaAltaF_pacientesCitados,
                                        "diagnosticoF_pacientesCitados"=>$request->diagnostico_pacientesCitados,
                                        "codigoCieNF_pacientesCitados"=>$request->codigoCieN_pacientesCitados,
                                        "tipoDocumentoP_pacientesCitados"=>$request->tipoDocumentoPFL_pacientesCitados,
                                        "numeroDocumentoP_pacientesCitados"=>$request->numeroDocumentoPFL_pacientesCitados,
                                        "nombresApellidosP_pacientesCitados"=>$request->personal_pacientesCitados,
                                        "tipoPersonalSaludF_pacientesCitados"=>$request->tipoPersonalSaludFL_pacientesCitados,
                                        "egresadoF_pacientesCitados"=>$request->egresadoFL_pacientesCitados,
                                        "colegiaturaF_pacientesCitados"=>$request->colegiaturaFL_pacientesCitados,
                                        "especialidadF_pacientesCitados"=>$request->especialidadFL_pacientesCitados,
                                        "rneF_pacientesCitados"=>$request->rneFL_pacientesCitados,
                                        "pacienteIdF_pacientesCitados"=>$request->pacienteIdFL_pacientesCitados);

                        /* INICIO DEL SEXO DEL PACIENTE */
                        if($datos["sexoF_pacientesCitados"] == "MASCULINO"){
                                $datos["sexoF_pacientesCitados"] = 1;
                        }else{
                                $datos["sexoF_pacientesCitados"] = 0;
                        }
                        /* FIN DEL SEXO DEL PACIENTE */

                        /* ES PARA EL DOCUMENTO DEL PERSONAL */
                        if($datos["tipoDocumentoP_pacientesCitados"] == "D.N.I."){
                                $datos["tipoDocumentoP_pacientesCitados"] = 1;
                        }else{
                                $datos["tipoDocumentoP_pacientesCitados"] = 3;
                        }
                        /* FIN DEL DOCUMENTO DEL PERSONAL */

                        /* return $datos; */

                        if(!empty($datos)){
                                $validar = \Validator::make($datos,[
                                        "usuario_pacientesCitados"=>'required',
                                        "personalAtiendeF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "lugarAtencionF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "tipoAtencionF_pacientesCitados"=>'nullable|regex:/^[0-9]+$/i',
                                        "codigoReferenciaF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "numeroReferenciaF_pacientesCitados"=>'required|regex:/^[-\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ]+$/i',
                                        "componenteF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "codigoAsegurado2FL_pacientesCitados"=>'required|regex:/^[0-9a-zA-ZñÑáéíóúÁÉÍÓÚ]+$/i',
                                        "codigoAsegurado3FL_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "tipoDocumentoF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "numeroDocumentoF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "apellidoPaternoF_pacientesCitados"=>"required|regex:/^['\\.\\a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i",
                                        "apellidoMaternoF_pacientesCitados"=>"required|regex:/^['\\.\\a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i",
                                        "primerNombreF_pacientesCitados"=>'required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                                        "sexoF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "fechaNacimientoF_pacientesCitados"=>'required',
                                        "historiaF_pacientesCitados"=>'nullable|regex:/^[0-9]+$/i',
                                        "codigoPrestacionalF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "conceptoPrestacionalF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "destinoAseguradoF_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "nombresApellidosP_pacientesCitados"=>'required|regex:/^[0-9]+$/i',
                                        "fechaAltaF_pacientesCitados"=>'nullable|after_or_equal:fechaIngresoF_pacientesCitados'
                                ]);

                                if($validar->fails()){
                                        return "NO-VALIDACION";
                                }else{
                                        /* CON ESTO VALIDAMOS QUE SE AGREGUE UNA UNIDAD AL FUA FINAL */
                                        $idUltimoFua = DB::SELECT("SELECT TOP 1 FUA.Numero FROM [ECONOMIA].[dbo].[FUA2] FUA ORDER BY IdAtencion DESC");
                                        $nuevoValorFua = $idUltimoFua[0]->Numero + 1;
                                        
                                        $valorFinalFua = str_pad($nuevoValorFua, 8, "0", STR_PAD_LEFT);
                                        
                                        /* FIN DEL FUA UNIDAD */
                                        
                                        $generarFua = new FuaModel();
                                        $generarFua->DISA = "00007734";
                                        $generarFua->Lote = date("y");
                                        $generarFua->Numero = $valorFinalFua;
                                        $generarFua->IPRESS = "00007734";
                                        $generarFua->EsReconsiderecion = "N";
                                        $generarFua->DISAReconsideracion = '';
                                        $generarFua->LoteReconsideracion = '';
                                        $generarFua->NumeroReconsideracion = '';
                                        $generarFua->IdConvenio = 0;
                                        $generarFua->Componente = $datos["componenteF_pacientesCitados"];
                                        $generarFua->DISAAsegurado = $datos["codigoAsegurado1FL_pacientesCitados"];
                                        $generarFua->LoteAsegurado = $datos["codigoAsegurado2FL_pacientesCitados"];
                                        $generarFua->NumeroAsegurado = $datos["codigoAsegurado3FL_pacientesCitados"];
                                        $generarFua->TipoDocumentoIdentidad = $datos["tipoDocumentoF_pacientesCitados"];
                                        $generarFua->NroDocumentoIdentidad = $datos["numeroDocumentoF_pacientesCitados"];
                                        $generarFua->ApellidoPaterno = $datos["apellidoPaternoF_pacientesCitados"];
                                        $generarFua->ApellidoMaterno = $datos["apellidoMaternoF_pacientesCitados"];
                                        $generarFua->PrimerNombre = $datos["primerNombreF_pacientesCitados"];
                                        $generarFua->OtrosNombres = $datos["otroNombreF_pacientesCitados"];
                                        $generarFua->FechaNacimiento = $datos["fechaNacimientoF_pacientesCitados"] . 'T' . date("00:00:00.000");
                                        $generarFua->Sexo = $datos["sexoF_pacientesCitados"];
                                        $generarFua->HistoriaClinica = $datos["historiaF_pacientesCitados"];
                                        $generarFua->TipoAtencion = $datos["tipoAtencionF_pacientesCitados"];
                                        $generarFua->SaludMaterna = 0;
                                        $generarFua->ModalidadAtencion = $datos["conceptoPrestacionalF_pacientesCitados"];

                                        if($datos["fechaF_pacientesCitados"] != '' && $datos["horaF_pacientesCitados"] != ''){
                                                $generarFua->FechaHoraAtencion = $datos["fechaF_pacientesCitados"] . 'T' . $datos["horaF_pacientesCitados"] . ':00.000';
                                        }else{
                                                $generarFua->FechaHoraAtencion = null;
                                        }

                                        $generarFua->IPRESSRefirio = $datos["codigoReferenciaF_pacientesCitados"];
                                        $generarFua->NroHojaReferencia = $datos["numeroReferenciaF_pacientesCitados"];
                                        $generarFua->CodigoPrestacional = $datos["codigoPrestacionalF_pacientesCitados"];
                                        $generarFua->PersonaAtiende = $datos["personalAtiendeF_pacientesCitados"];
                                        $generarFua->LugarAtencion = $datos["lugarAtencionF_pacientesCitados"];
                                        $generarFua->DestinoAsegurado = $datos["destinoAseguradoF_pacientesCitados"];

                                        if($datos["fechaIngresoF_pacientesCitados"] == ''){
                                                
                                        }else{
                                                $generarFua->FechaIngreso = $datos["fechaIngresoF_pacientesCitados"] . 'T' . date("00:00:00.000");
                                        }

                                        if($datos["fechaAltaF_pacientesCitados"] == ''){
                                                
                                        }else{
                                                $generarFua->FechaAlta = $datos["fechaF_pacientesCitados"] . 'T' . date("00:00:00.000");
                                        }

                                        $generarFua->IPRESSContrareferencia = '';
                                        $generarFua->NroHojaContrareferencia = '';
                                        $generarFua->TipoDocResponsable = $datos["tipoDocumentoP_pacientesCitados"];
                                        $generarFua->NroDocResponsable = $datos["numeroDocumentoP_pacientesCitados"];
                                        $generarFua->TipoPersonalSalud = $datos["tipoPersonalSaludF_pacientesCitados"];
                                        $generarFua->Especialidad = $datos["especialidadF_pacientesCitados"];
                                        $generarFua->EsEgresado = $datos["egresadoF_pacientesCitados"];
                                        $generarFua->NroColegiatura = $datos["colegiaturaF_pacientesCitados"];
                                        $generarFua->NroRNE = $datos["rneF_pacientesCitados"];
                                        $generarFua->FechaHoraRegistro = date("Y-m-d") . 'T' . date("H:i:s.v");
                                        $generarFua->VersionAplicativoOrigen = "SOFT_UFPA";
                                        $generarFua->persona_id = $datos["pacienteIdF_pacientesCitados"];
                                        $generarFua->personalAtiende_id = $datos["nombresApellidosP_pacientesCitados"];
                                        $generarFua->personalResponsable_id = $datos["nombresApellidosP_pacientesCitados"];
                                        $generarFua->cie1_cod = $datos["codigoCieNF_pacientesCitados"];
                                        $generarFua->pdr1_cod = $datos["diagnosticoF_pacientesCitados"];
                                        $generarFua->generarFua_estado = '1';
                                        $generarFua->fua_libre = '1';
                                        $generarFua->save();

                                        $insertedId = $generarFua->id;

                                        /* GUARDAMOS VALORES EN LA BD SOFTWARE_UFPA (AUDITORIA) */

                                        $generarFuaN = new FuaNModel();
                                        $generarFuaN->NroFua = $insertedId;
                                        $generarFuaN->TipoAccion = 1; /* SI ES 1 ES PARA GENERACION Y 0 PARA ANULACION Y 2 PARA ACTUALIZACION */
                                        $generarFuaN->IdUsuario = $datos["usuario_pacientesCitados"];
                                        $generarFuaN->save();
                                        
                                        /* VAMOS A ACTUALIZAR LA FUA CON EL CODIGO FUA (CORRECTO) */

                                        return array("GUARDAR-FUA", $insertedId);
                                }
                        }else{
                                return "ERROR";
                        }


                }
        }

        public function buscarPorHistoriaBD(Request $request){

            if(request()->ajax()){
                return datatables()->of(DB::SELECT("SELECT TOP (100) PERCENT CHP.fina_cod as Financiador_id,
                CASE WHEN CHP.fina_cod = '02' OR CHP.fina_cod = '2' THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
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
        
                'CITASHORA' AS Modelo,

                CASE WHEN CH.modoap_cod = 1 THEN 'PRESENCIAL'
                     WHEN CH.modoap_cod = 5 THEN 'EXTRA'
                     WHEN CH.modoap_cod = 7 THEN 'NO PRESENCIAL'
                     WHEN CH.modoap_cod = 6 THEN 'H-COMPL.'
                     END AS TipoAtencion,

                NULL AS UnidadOrganica_id,
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
              IN ('2 ','8 ')) AND CH.serv_cod <> 41 AND FU.IdAtencion IS NULL AND (CHP.asis_cod NOT IN ('2','3','4','5','6','D','F','G') OR CHP.asis_cod IS NULL)
        
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
                UO.id AS UnidadOrganica_id,
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
        
        WHERE P.hcl_num = :hcl_num2 AND PDD.Financiador_id IN (2,8) AND FU.IdAtencion IS NULL
                AND (PDD.AsistenciaCita_id NOT IN (2,3,4,5,6,13,15,17,18,19) OR PDD.AsistenciaCita_id IS NULL)
        
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
                CASE WHEN P.hcl_num LIKE '990%' THEN P.nroDocIdentidad ELSE P.hcl_num END AS HistoriaClinica,
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
                
                UO.id AS UnidadOrganica_id,
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
        
        WHERE   P.hcl_num = :hcl_num3 AND SPD.tbFinanciador_id IN (2,8) AND
                (SPD.tbEstado_id <> 8 AND SPD.tbEstado_id <> 11) AND SUO.tbGrupoProfesional_id IN (2,4,6,7,11,13,14,18,21,23)
                AND FU.IdAtencion IS NULL AND (SPD.tbAsistencia_id NOT IN (2,3,4,5,6,13,15,17,18,19,21,22,23) OR SPD.tbAsistencia_id IS NULL)",
                ["hcl_num1"=>$request->numHistoriaBD_pacientesCitados,
                "hcl_num2"=>$request->numHistoriaBD_pacientesCitados,
                "hcl_num3"=>$request->numHistoriaBD_pacientesCitados]))->make(true);
            }
        }

        public function buscarPorDocumentoBD(Request $request){

                if(request()->ajax()){
                    return datatables()->of(DB::SELECT("SELECT TOP (100) PERCENT CHP.fina_cod as Financiador_id,
                    CASE WHEN CHP.fina_cod = '02' OR CHP.fina_cod = '2' THEN 'S.I.S.' ELSE 'SANI-PNP' END as Financiador,
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
            
                    'CITASHORA' AS Modelo,
    
                    CASE WHEN CH.modoap_cod = 1 THEN 'PRESENCIAL'
                         WHEN CH.modoap_cod = 5 THEN 'EXTRA'
                         WHEN CH.modoap_cod = 7 THEN 'NO PRESENCIAL'
                         WHEN CH.modoap_cod = 6 THEN 'H-COMPL.'
                         END AS TipoAtencion,
    
                         NULL AS UnidadOrganica_id,
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
                  IN ('2 ','8 ')) AND CH.serv_cod <> 41 AND FU.IdAtencion IS NULL AND (CHP.asis_cod NOT IN ('2','3','4','5','6','D','F','G') OR CHP.asis_cod IS NULL)
            
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
                    UO.id AS UnidadOrganica_id,
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
            
            WHERE P.nroDocIdentidad = CONVERT(varchar(11), :nroDocIdentidad2) AND PDD.Financiador_id IN (2,8) AND FU.IdAtencion IS NULL
                    AND (PDD.AsistenciaCita_id NOT IN (2,3,4,5,6,13,15,17,18,19) OR PDD.AsistenciaCita_id IS NULL)
            
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
    
                    UO.id AS UnidadOrganica_id,
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
            
            WHERE   P.nroDocIdentidad = CONVERT(varchar(11), :nroDocIdentidad3) AND SPD.tbFinanciador_id IN (2,8) AND
                    (SPD.tbEstado_id <> 8 AND SPD.tbEstado_id <> 11) AND SUO.tbGrupoProfesional_id IN (2,4,6,7,11,13,14,18,21,23)
                    AND FU.IdAtencion IS NULL AND (SPD.tbAsistencia_id NOT IN (2,3,4,5,6,13,15,17,18,19,21,22,23) OR SPD.tbAsistencia_id IS NULL)
            
            ",[ "nroDocIdentidad1"=>$request->numDocumentoBD_pacientesCitados,
                "nroDocIdentidad2"=>$request->numDocumentoBD_pacientesCitados,
                "nroDocIdentidad3"=>$request->numDocumentoBD_pacientesCitados]))->make(true);
                }
            }
}
