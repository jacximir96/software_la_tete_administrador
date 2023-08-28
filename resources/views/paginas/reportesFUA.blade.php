<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FUA</title>
</head>
<body>
    <table class="table table-sm borderless" id="tabla_general">
    @foreach($datosFua as $key => $valor_datosFua)
        <tr>
            <th colspan="40" style="text-align:center;font-size:0.6rem;height:30px;"><img style="width:250px;height:30px;float:left;" src="./img/reportesFua/logo-minsa.jpg"><img style="width:50px;height:30px;float:right;" src="./img/reportesFua/logo-inr.jpg"></th>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:0px;">FORMATO ÚNICO DE ATENCIÓN - FUA</th>
        </tr>

        <tr>
            <th colspan="14" rowspan="2"></th>
            <th colspan="12" style="background:WHITE;text-align:center;font-size:0.5rem;padding:0px;">NÚMERO DE FORMATO</th>
            @if($valor_datosFua->FechaIngreso != '' || $valor_datosFua->FechaAlta != '')
            <th colspan="14" rowspan="2" style="padding:0px 70px;"></th>
            @elseif($valor_datosFua->FechaHoraAtencion == '')
            <th colspan="14" rowspan="2" style="padding:0px 75px;"></th>
            @else
            <th colspan="14" rowspan="2" style="padding:0px 75px;"></th>
            @endif
        </tr>

        <tr>
            <td colspan="3" style="text-align:center;font-size:1rem;font-weight: 900;">{{$valor_datosFua->DISA}}</td>
            <td colspan="3" style="text-align:center;font-size:1rem;font-weight: 900;">{{$valor_datosFua->Lote}}</td>
            <td colspan="6" style="text-align:center;font-size:1rem;font-weight: 900;">{{$valor_datosFua->Numero}}</td>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:0px;">DE LA INSTITUCIÓN PRESTADORA DE SERVICIOS DE SALUD</th>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="10" style="text-align:center;font-size:0.5rem;padding:1px;max-width:30px;">CÓDIGO RENAES DE LA IPRESS</th>
            <th colspan="30" style="text-align:center;font-size:0.5rem;padding:1px;">NOMBRE DE LA IPRESS QUE REALIZA LA ATENCIÓN</th>
        </tr>

        <tr>
            <th colspan="10" style="text-align:center;font-size:1.2rem;padding:0px;">070102A102</th>
            <th colspan="30" style="text-align:center;font-size:1rem;padding:0px 30px;">LA TETE RESTOBAR</th>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="9" style="text-align:center;font-size:0.5rem;padding:1px;">PERSONAL QUE ATIENDE</th>
            <th colspan="6" style="text-align:center;font-size:0.5rem;padding:1px;">LUGAR DE ATENCIÓN</th>
            <th colspan="6" style="text-align:center;font-size:0.5rem;padding:1px;">ATENCIÓN</th>
            <th colspan="19" style="text-align:center;font-size:0.5rem;padding:1px;">REFERENCIA REALIZADA POR</th>
        </tr>

        <tr>
            <td colspan="4" style="text-align:center;font-size:0.4rem;background:WHITE;">DE LA IPRESS</td>
            @if($valor_datosFua->PersonaAtiende == 1)
            <td colspan="1" style="text-align:center;">X</td>
            @else
            <td colspan="1" style="text-align:center;"></td>
            @endif
            <td colspan="4" style="text-align:center;font-size:0.4rem;background:WHITE;width:30px;">CÓDIGO DE LA OFERTA FLEXIBLE</td>

            <td colspan="5" style="text-align:center;font-size:0.4rem;background:WHITE;">INTRAMURAL</td>
            @if($valor_datosFua->LugarAtencion == 1)
            <td colspan="1" style="text-align:center;">X</td>
            @else
            <td colspan="1" style="text-align:center;"></td>
            @endif
            <td colspan="5" style="text-align:center;font-size:0.4rem;background:WHITE;">AMBULATORIO</td>
            @if($valor_datosFua->TipoAtencion == 1)
            <td colspan="1" style="text-align:center;">X</td>
            @else
            <td colspan="1" style="text-align:center;"></td>
            @endif

            <td colspan="5" style="text-align:center;font-size:0.4rem;background:WHITE;">COD RENAES</td>
            <td colspan="11" style="text-align:center;font-size:0.4rem;background:WHITE;">NOMBRE DE LA IPRESS U OFERTA FLEXIBLE</td>
            <td colspan="3" style="text-align:center;font-size:0.4rem;background:WHITE;">N° HOJA DE REFERENCIA</td>
        </tr>

        <tr>
            <td colspan="4" style="text-align:center;font-size:0.4rem;background:WHITE;padding:5px;width:70px;">ITINERANTE</td>
            @if($valor_datosFua->PersonaAtiende == 2)
            <td colspan="1" style="text-align:center;">X</td>
            @else
            <td colspan="1" style="text-align:center;"></td>
            @endif

            @if($valor_datosFua->LugarAtencion == 2)
            <td colspan="4" rowspan="2" style="text-align:center;">TELESALUD</td>
            @else
            <td colspan="4" rowspan="2" style="text-align:center;"></td>
            @endif


            <td colspan="5" style="text-align:center;font-size:0.4rem;background:WHITE;">EXTRAMURAL</td>
            @if($valor_datosFua->LugarAtencion == 2)
            <td colspan="1" style="text-align:center;">X</td>
            @else
            <td colspan="1" style="text-align:center;"></td>
            @endif
            <td colspan="5" style="text-align:center;font-size:0.4rem;background:WHITE;">REFERENCIA</td>
            @if($valor_datosFua->TipoAtencion == 2)
            <td colspan="1" style="text-align:center;">X</td>
            @else
            <td colspan="1" style="text-align:center;"></td>
            @endif

            <!-- FALTA AGREGAR EN EL FUA DINAMICO -->
            @if($valor_datosFua->IPRESSRefirio != '')
            <td colspan="5" rowspan="2" style="text-align:center;">{{$valor_datosFua->IPRESSRefirio}}</td>
            <td colspan="11" rowspan="2" style="text-align:center;">{{$establecimientoDatosFua1}}</td>
            @else
            <td colspan="5" rowspan="2"></td>
            <td colspan="11" rowspan="2"></td>
            @endif

            @if($valor_datosFua->NroHojaReferencia != '')
            <td colspan="3" rowspan="2" style="text-align:center;">{{$valor_datosFua->NroHojaReferencia}}</td>
            @else
            <td colspan="3" rowspan="2"></td>
            @endif
            <!-- FIN DE FALTA AGREGAR EN EL FUA DINAMICO -->
        </tr>

        <tr>
            <td colspan="4" style="text-align:center;font-size:0.4rem;background:WHITE;padding:3px;">OFERTA FLEXIBLE</td>
            @if($valor_datosFua->PersonaAtiende == 4)
            <td colspan="1" style="text-align:center;">X</td>
            @else
            <td colspan="1" style="text-align:center;"></td>
            @endif

            <td colspan="6"></td>

            <td colspan="5" style="text-align:center;font-size:0.4rem;background:WHITE;">EMERGENCIA</td>
            @if($valor_datosFua->TipoAtencion == 3)
            <td colspan="1" style="text-align:center;">X</td>
            @else
            <td colspan="1" style="text-align:center;"></td>
            @endif
        </tr>

        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:1px;">DEL ASEGURADO / USUARIO</th>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="9" style="text-align:center;font-size:0.5rem;padding:1px;">IDENTIFICACIÓN</th>
            <th colspan="11" style="text-align:center;font-size:0.5rem;padding:1px;">CÓDIGO DEL ASEGURADO SIS</th>
            <th colspan="20" style="text-align:center;font-size:0.5rem;padding:1px;">ASEGURADO DE OTRA IAFAS</th>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;">TDI</td>
            <td colspan="7" style="text-align:center;font-size:0.4rem;background:WHITE;max-width:10px !important;">N° DOCUMENTO DE IDENTIDAD</td>

            <td colspan="4" style="text-align:center;font-size:0.4rem;background:WHITE;">DIRESA / OTROS</td>
            <td colspan="7" style="text-align:center;font-size:0.4rem;background:WHITE;">NÚMERO</td>

            <td colspan="4" style="text-align:center;font-size:0.4rem;background:WHITE;">INSTITUCIÓN</td>
            <td colspan="16"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;width:50px !important;">{{$valor_datosFua->TipoDocumentoIdentidad}}</td>
            <td colspan="7" style="text-align:center;font-size:1rem;font-weight: 900;">{{$valor_datosFua->NroDocumentoIdentidad}}</td>

            <td colspan="4" style="text-align:center;">{{$valor_datosFua->DISAAsegurado}}</td>
            <td colspan="1" style="text-align:center;">{{$valor_datosFua->LoteAsegurado}}</td>
            <td colspan="6" style="text-align:center;font-size:1rem;font-weight: 900;">{{$valor_datosFua->NumeroAsegurado}}</td>

            <td colspan="4" style="text-align:center;font-size:0.4rem;background:WHITE;padding:1px;">COD. SEGURO</td>
            <td colspan="16"></td>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="20" style="text-align:center;font-size:0.5rem;padding:1px;">APELLIDO PATERNO</th>
            <th colspan="20" style="text-align:center;font-size:0.5rem;padding:1px;">APELLIDO MATERNO</th>
        </tr>

        <tr>
            <td colspan="20" style="padding:2px;text-align:center;font-size:1rem;font-weight: 900;">{{$valor_datosFua->ApellidoPaterno}}</td>
            <td colspan="20" style="padding:2px;text-align:center;font-size:1rem;font-weight: 900;">{{$valor_datosFua->ApellidoMaterno}}</td>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="20" style="text-align:center;font-size:0.5rem;padding:1px;">PRIMER NOMBRE</th>
            <th colspan="20" style="text-align:center;font-size:0.5rem;padding:1px;">OTROS NOMBRES</th>
        </tr>

        <tr>
            <td colspan="20" style="padding:2px;text-align:center;font-size:1rem;font-weight: 900;">{{$valor_datosFua->PrimerNombre}}</td>
            <td colspan="20" style="padding:2px;text-align:center;font-size:1rem;font-weight: 900;">{{$valor_datosFua->OtrosNombres}}</td>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="5" style="text-align:center;font-size:0.5rem;padding:1px;">SEXO</th>
            <th colspan="7" style="text-align:center;font-size:0.5rem;padding:1px;">FECHA</th>
            <th colspan="2" style="text-align:center;font-size:0.5rem;padding:1px;">DÍA</th>
            <th colspan="2" style="text-align:center;font-size:0.5rem;padding:1px;">MES</th>
            <th colspan="4" style="text-align:center;font-size:0.5rem;padding:1px;">AÑO</th>
            <th colspan="12" style="text-align:center;font-size:0.5rem;padding:1px;">N° DE HISTORIA CLINICA</th>
            <th colspan="8" style="text-align:center;font-size:0.5rem;padding:1px;">ETNIA</th>
        </tr>

        <tr>
            <td colspan="4" style="text-align:center;font-size:0.4rem;background:WHITE;padding:5px">MASCULINO</td>
            @if($valor_datosFua->Sexo == 1)
            <td colspan="1" style="text-align:center;">X</td>
            @else
            <td colspan="1"></td>
            @endif
            <td colspan="7" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;">FECHA PROBABLE DE PARTO / FECHA DE PARTO</td>
            <td colspan="1" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="12" rowspan="2" style="text-align:center;font-size:1.2rem;font-weight: 900;">{{$valor_datosFua->HistoriaClinica}}</td>
            <td colspan="8" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="4" style="text-align:center;font-size:0.4rem;background:WHITE;padding:5px;">FEMENINO</td>
            @if($valor_datosFua->Sexo == 0)
            <td colspan="1" style="text-align:center;">X</td>
            @else
            <td colspan="1"></td>
            @endif
        </tr>

        <tr>
            <th colspan="5" style="text-align:center;font-size:0.5rem;background:WHITE;">SALUD MATERNA</th>
            <td colspan="7" style="text-align:center;font-size:0.4rem;background:WHITE;">FECHA DE NACIMIENTO</td>
            <td colspan="1" style="text-align:center;">{{$primerCaracterFechaNacimiento}}</td>
            <td colspan="1" style="text-align:center;">{{$segundoCaracterFechaNacimiento}}</td>
            <td colspan="1" style="text-align:center;">{{$tercerCaracterFechaNacimiento}}</td>
            <td colspan="1" style="text-align:center;">{{$cuartoCaracterFechaNacimiento}}</td>
            <td colspan="1" style="text-align:center;">{{$quintoCaracterFechaNacimiento}}</td>
            <td colspan="1" style="text-align:center;">{{$sextoCaracterFechaNacimiento}}</td>
            <td colspan="1" style="text-align:center;">{{$septimoCaracterFechaNacimiento}}</td>
            <td colspan="1" style="text-align:center;">{{$octavoCaracterFechaNacimiento}}</td>
            <td colspan="12" style="text-align:center;font-size:0.4rem;background:WHITE">DNI / CNV / AFILIACIÓN DEL RN 1</td>
            <td colspan="8"></td>
        </tr>

        <tr>
            <td colspan="4" style="text-align:center;font-size:0.4rem;background:WHITE;padding:5px;">GESTANTE</td>
            <td colspan="1"></td>
            <td colspan="7" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;">FECHA DE FALLECIMIENTO</td>
            <td colspan="1" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" rowspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="12" style="text-align:center;font-size:0.4rem;background:WHITE">DNI / CNV / AFILIACIÓN DEL RN 2</td>
            <td colspan="8"></td>
        </tr>

        <tr>
            <td colspan="4" style="text-align:center;font-size:0.4rem;background:WHITE;padding:5px;">PUERPERA</td>
            <td colspan="1"></td>
            <td colspan="12" style="text-align:center;font-size:0.4rem;background:WHITE">DNI / CNV / AFILIACIÓN DEL RN 3</td>
            <td colspan="8"></td>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:0px;">DE LA ATENCIÓN</th>
        </tr>

        <tr>
            <th colspan="8" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">FECHA DE ATENCIÓN</th>
            <th colspan="6" rowspan="2" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">HORA</th>
            <th colspan="6" rowspan="2" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">UPS</th>

            <th colspan="6" rowspan="2" style="text-align:center;font-size:0.5rem;background:WHITE;">CÓD. PRESTA.</th>

            @if($valor_datosFua->FechaIngreso != '' || $valor_datosFua->FechaAlta != '')
            <th colspan="2" rowspan="5" class="rotate1" style="font-size:0.5rem;"><div>HOSPITALIZACIÓN</div></th>
            @else
            <th colspan="2" rowspan="5" class="rotate" style="font-size:0.5rem;"><div>HOSPITALIZACIÓN</div></th>
            @endif

            <th colspan="4" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">FECHA</th>
            <th colspan="2" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">DIA</th>
            <th colspan="2" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">MES</th>
            <th colspan="4" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">AÑO</th>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;padding:0px;background:WHITE;">DIA</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;padding:0px;background:WHITE;">MES</td>
            <td colspan="4" style="text-align:center;font-size:0.4rem;padding:0px;background:WHITE;">AÑO</td>

            <td colspan="4" style="text-align:center;font-size:0.4rem;padding:0px;background:WHITE;">DE INGRESO</td>
            <td colspan="1" style="text-align:center;">{{$primerCDiaFechaIngreso}}</td>
            <td colspan="1" style="text-align:center;">{{$segundoCDiaFechaIngreso}}</td>
            <td colspan="1" style="text-align:center;">{{$primerCMesFechaIngreso}}</td>
            <td colspan="1" style="text-align:center;">{{$segundoCMesFechaIngreso}}</td>
            <td colspan="1" style="text-align:center;">{{$primerCAñoFechaIngreso}}</td>
            <td colspan="1" style="text-align:center;">{{$segundoCAñoFechaIngreso}}</td>
            <td colspan="1" style="text-align:center;">{{$tercerCAñoFechaIngreso}}</td>
            <td colspan="1" style="text-align:center;">{{$cuartoCAñoFechaIngreso}}</td>
        </tr>

        <tr>
            <td colspan="1" style="text-align:center;font-size:1rem;font-weight: 900;">{{$primerCDiaFechaAtencion}}</td>
            <td colspan="1" style="text-align:center;font-size:1rem;font-weight: 900;">{{$segundoCDiaFechaAtencion}}</td>
            <td colspan="1" style="text-align:center;font-size:1rem;font-weight: 900;">{{$primerCMesFechaAtencion}}</td>
            <td colspan="1" style="text-align:center;font-size:1rem;font-weight: 900;">{{$segundoCMesFechaAtencion}}</td>
            <td colspan="1" style="text-align:center;font-size:1rem;font-weight: 900;">{{$primerCAñoFechaAtencion}}</td>
            <td colspan="1" style="text-align:center;font-size:1rem;font-weight: 900;">{{$segundoCAñoFechaAtencion}}</td>
            <td colspan="1" style="text-align:center;font-size:1rem;font-weight: 900;">{{$tercerCAñoFechaAtencion}}</td>
            <td colspan="1" style="text-align:center;font-size:1rem;font-weight: 900;">{{$cuartoCAñoFechaAtencion}}</td>

            <td colspan="2" style="text-align:center;font-size:1rem;font-weight: 900;">{{$primerCHoraFechaAtencion}}</td>
            <td colspan="2" style="text-align:center;">:</td>
            <td colspan="2" style="text-align:center;font-size:1rem;font-weight: 900;">{{$primerCMinutosFechaAtencion}}</td>

            <td colspan="6"></td>

            <td colspan="6" style="text-align:center;font-size:1rem;font-weight: 900;">{{$valor_datosFua->CodigoPrestacional}}</td>

            <td colspan="4" style="text-align:center;font-size:0.4rem;padding:5px;background:WHITE;">DE ALTA</td>
            <td colspan="1" style="text-align:center;">{{$primerCDiaFechaAlta}}</td>
            <td colspan="1" style="text-align:center;">{{$segundoCDiaFechaAlta}}</td>
            <td colspan="1" style="text-align:center;">{{$primerCMesFechaAlta}}</td>
            <td colspan="1" style="text-align:center;">{{$segundoCMesFechaAlta}}</td>
            <td colspan="1" style="text-align:center;">{{$primerCAñoFechaAlta}}</td>
            <td colspan="1" style="text-align:center;">{{$segundoCAñoFechaAlta}}</td>
            <td colspan="1" style="text-align:center;">{{$tercerCAñoFechaAlta}}</td>
            <td colspan="1" style="text-align:center;">{{$cuartoCAñoFechaAlta}}</td>
        </tr>

        <tr>
            <th colspan="7" rowspan="2" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">REPORTE VINCULADO</th>
            <th colspan="7" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">COD. AUTORIZACIÓN</th>
            <th colspan="12" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">N° FUA VINCULAR</th>

            <td colspan="4" rowspan="2" style="text-align:center;font-size:0.4rem;padding:0px;background:WHITE;">DE CORTE ADMINIST.</td>
            <td colspan="1" rowspan="2"></td>
            <td colspan="1" rowspan="2"></td>
            <td colspan="1" rowspan="2"></td>
            <td colspan="1" rowspan="2"></td>
            <td colspan="1" rowspan="2"></td>
            <td colspan="1" rowspan="2"></td>
            <td colspan="1" rowspan="2"></td>
            <td colspan="1" rowspan="2"></td>
        </tr>

        <tr>
            <td colspan="7"></td>
            <td colspan="12"></td>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:0px;">CONCEPTO PRESTACIONAL</th>
        </tr>

        <tr>
            <th colspan="3" rowspan="3" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">ATENCIÓN DIRECTA</th>
            @if($valor_datosFua->ModalidadAtencion == 1)
            <th colspan="2" rowspan="3" style="text-align:center;">X</th>
            @else
            <th colspan="2" rowspan="3"></th>
            @endif
            <th colspan="9" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">COB. EXTRAORDINARIA</th>
            <th colspan="6" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">CARTA DE GARANTÍA</th>
            <th colspan="4" rowspan="3" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">TRASLADO</th>
            <th colspan="2" rowspan="3"></th>
            <th colspan="14" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">SEPELIO</th>
        </tr>

        <tr>
            <th colspan="6" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">N° AUTORIZACIÓN</th>
            <th colspan="3"></th>
            <th colspan="4" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">N° AUTORIZACIÓN</th>
            <th colspan="2"></th>

            <th colspan="4" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">NATIMUERTO</th>
            <th colspan="2" rowspan="2"></th>
            <th colspan="2" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">OBITO</th>
            <th colspan="2" rowspan="2"></th>
            <th colspan="2" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">OTRO</th>
            <th colspan="2" rowspan="2"></th>
        </tr>

        <tr>
            <th colspan="6" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">MONTO S/</th>
            <th colspan="3"></th>
            <th colspan="4" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">MONTO S/</th>
            <th colspan="2"></th>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:0px;">DEL DESTINO DEL ASEGURADO / USUARIO</th>
        </tr>

        <tr>
            <td colspan="2" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">ALTA</td>
            @if($valor_datosFua->DestinoAsegurado == 1)
            <td colspan="1" rowspan="2" style="text-align:center;">X</td>
            @else
            <td colspan="1" rowspan="2"></td>
            @endif
            <td colspan="2" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">CITA</td>
            @if($valor_datosFua->DestinoAsegurado == 2)
            <td colspan="1" rowspan="2" style="text-align:center;">X</td>
            @else
            <td colspan="1" rowspan="2"></td>
            @endif

            <td colspan="6" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">HOSPITALIZACIÓN</td>
            @if($valor_datosFua->DestinoAsegurado == 8)
            <td colspan="1" rowspan="2" style="text-align:center;">X</td>
            @else
            <td colspan="1" rowspan="2"></td>
            @endif

            <th colspan="13" style="text-align:center;font-size:0.5rem;background:WHITE;">REFERIDO</th>

            <td colspan="4" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">CONTRA REFERIDO</td>
            <td colspan="2" rowspan="2"></td>
            <td colspan="3" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">FALLECIDO</td>
            <td colspan="1" rowspan="2"></td>
            <td colspan="2" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">CORTE ADMINIS.</td>
            <td colspan="2" rowspan="2"></td>
        </tr>

        <tr>
            <td colspan="3" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px 5px;">EMERGENCIA</td>
            <td colspan="1"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">CONSULTA EXTERNA</td>
            <td colspan="1"></td>
            <td colspan="5" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">APOYO AL DIAGNOSTICO</td>
            <td colspan="1"></td>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:0px;">SE REFIERE / CONTRARREFIERE A:</th>
        </tr>

        <tr>
            <th colspan="10" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">CÓDIGO RENAES DE LA IPRESS</th>
            <th colspan="20" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">NOMBRE DE LA IPRESS A LA QUE SE REFIERE / CONTRARREFIERE</th>
            <th colspan="10" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">N° HOJA DE REFER / CONTRARR.</th>
        </tr>

        <tr>
            <td colspan="10" style="text-align:center;font-size:0.4rem;padding:5px;"></td>
            <td colspan="20" style="text-align:center;font-size:0.4rem;padding:5px;"></td>
            <td colspan="10" style="text-align:center;font-size:0.4rem;padding:5px;"></td>
        </tr>

        <tr>
            <th colspan="26" style="text-align:center;font-size:0.5rem;background:WHITE;">OTRAS ACTIVIDADES</th>
            <th colspan="14" rowspan="3"></th>
        </tr>

        <tr>
            <th colspan="5" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">PESO (kg)</th>
            <th colspan="1" rowspan="2"></th>
            <th colspan="5" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">TALLA (cm)</th>
            <th colspan="1" rowspan="2"></th>
            <th colspan="5" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">P.A. (mmHg)</th>
            <th colspan="1" rowspan="2"></th>
            <th colspan="5" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">TAMIZAJE DE SALUD MENTAL</th>
            <td colspan="3" style="text-align:center;font-size:0.4rem;padding:0px;">PAT.</td>
        </tr>

        <tr>
            <td colspan="3" style="text-align:center;font-size:0.4rem;padding:0px;">NOR.</td>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:0px;">DIAGNÓSTICOS:</th>
        </tr>

        <tr>
            <th colspan="2" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;">N°</th>
            <th colspan="18" rowspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;">DESCRIPCIÓN</th>

            <th colspan="11" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">INGRESO</th>
            <th colspan="9" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">EGRESO</th>
        </tr>

        <tr>
            <th colspan="6" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">TIPO DE DX</th>
            <th colspan="5" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">CIE - 10</th>
            <th colspan="4" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">TIPO DE DX</th>
            <th colspan="5" style="text-align:center;font-size:0.4rem;background:WHITE;padding:0px;">CIE - 10</th>
        </tr>

        <tr>
            <th colspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;">1</th>
            <td colspan="18" style="text-align:left;font-size:0.4rem;">{{$descripcionCodigoCie1}}</td>

            
            <th colspan="2" style="text-align:center;font-size:0.4rem;position:relative;">P @if($valor_datosFua->pdr1_cod == 'P') <div style="position:absolute;top:1px;left:11px;font-size:1rem;color:red;opacity:0.6;background-color:transparent;z-index:1;">X</div>@endif </th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;position:relative;">D @if($valor_datosFua->pdr1_cod == 'D') <div style="position:absolute;top:1px;left:11px;font-size:1rem;color:red;opacity:0.6;background-color:transparent;z-index:1;">X</div>@endif</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;position:relative;">R @if($valor_datosFua->pdr1_cod == 'R') <div style="position:absolute;top:1px;left:11px;font-size:1rem;color:red;opacity:0.6;background-color:transparent;z-index:1;">X</div>@endif</th>

            <td colspan="5" style="text-align:center;">{{$valor_datosFua->cie1_cod}}</td>

            <th colspan="2" style="text-align:center;font-size:0.4rem;">D</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">R</th>

            <th colspan="5" style="text-align:center;font-size:0.4rem;"></th>
        </tr>

        <tr>
            <th colspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;">2</th>
            <th colspan="18" style="text-align:center;font-size:0.4rem;"></th>

            <th colspan="2" style="text-align:center;font-size:0.4rem;">P</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">D</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">R</th>

            <th colspan="5" style="text-align:center;font-size:0.4rem;"></th>

            <th colspan="2" style="text-align:center;font-size:0.4rem;">D</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">R</th>

            <th colspan="5" style="text-align:center;font-size:0.4rem;"></th>
        </tr>

        <tr>
            <th colspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;">3</th>
            <th colspan="18" style="text-align:center;font-size:0.4rem;"></th>

            <th colspan="2" style="text-align:center;font-size:0.4rem;">P</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">D</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">R</th>

            <th colspan="5" style="text-align:center;font-size:0.4rem;"></th>

            <th colspan="2" style="text-align:center;font-size:0.4rem;">D</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">R</th>

            <th colspan="5" style="text-align:center;font-size:0.4rem;"></th>
        </tr>

        <tr>
            <th colspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;">4</th>
            <th colspan="18" style="text-align:center;font-size:0.4rem;"></th>

            <th colspan="2" style="text-align:center;font-size:0.4rem;">P</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">D</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">R</th>

            <th colspan="5" style="text-align:center;font-size:0.4rem;"></th>

            <th colspan="2" style="text-align:center;font-size:0.4rem;">D</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">R</th>

            <th colspan="5" style="text-align:center;font-size:0.4rem;"></th>
        </tr>

        <tr>
            <th colspan="8" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">N° DE DNI</th>
            <th colspan="24" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">NOMBRE DEL RESPONSABLE DE LA ATENCIÓN</th>
            <th colspan="8" style="text-align:center;font-size:0.5rem;background:WHITE;padding:1px;">N° DE COLEGIATURA</th>
        </tr>

        <tr>
            <td colspan="8" style="text-align:center;padding:2px;font-size:1rem;font-weight: 900;">{{$valor_datosFua->NroDocResponsable}}</td>
            @if($valor_datosFua->personalAtiende_id != '')
                @foreach($nombreResponsable as $key => $valor_nombreResponsable)
                    <td colspan="24" style="text-align:center;padding:2px;">{{$valor_nombreResponsable->Profesional}}</td>
                @endforeach
            @else
                <td colspan="24" style="text-align:center;padding:2px;"></td>
            @endif
            <td colspan="8" style="text-align:center;padding:2px;">{{$valor_datosFua->NroColegiatura}}</td>
        </tr>

        <tr>
            <td colspan="8" style="text-align:center;font-size:0.4rem;background:WHITE;padding:1px;">RESPONSABLE DE LA ATENCIÓN</td>
            <td colspan="1" style="text-align:center;">{{$valor_datosFua->idEnFormato}}</td>
            <td colspan="4" style="text-align:center;font-size:0.4rem;background:WHITE;padding:1px;">ESPECIALIDAD</td>

            @if($valor_datosFua->Especialidad != '')
                @foreach($datosEspecialidad as $key => $valor_datosEspecialidad)
                        <td colspan="15" style="text-align:left;">{{$valor_datosEspecialidad->descripcion}}</td>
                @endforeach
            @else
                <td colspan="15" style="text-align:left;"></td>
            @endif


            <td colspan="2" style="text-align:center;font-size:0.4rem;background:WHITE;padding:1px;">N° RNE</td>
            <td colspan="2" style="text-align:center;padding:2px;">{{$valor_datosFua->NroRNE}}</td>

            <td colspan="7" style="text-align:center;font-size:0.4rem;background:WHITE;padding:1px;">EGRESADO</td>
            @if($valor_datosFua->EsEgresado == '0')
            <td colspan="1" style="text-align:center;padding:2px;">X</td>
            @else
            <td colspan="1" style="text-align:center;padding:2px;"></td>
            @endif
        </tr>

        
        <tr>
            
            <td colspan="40" style="font-size:0.4rem;text-align:justify;border-bottom:1px solid white">
<!--                 @foreach($datosTipoPersonalSalud as $valor_datosTipoPersonalSalud)
                    {{$valor_datosTipoPersonalSalud->id}}. {{$valor_datosTipoPersonalSalud->descripcion}}
                @endforeach -->
                01. MÉDICO 02. FARMACÉUTICO 03. CIRUJANO DENTISTA 04. BIÓLOGO 05. OBSTETRIZ 06. ENFERMERA 07. TRABAJADORA SOCIAL 08. PSICÓLOGA 09. TECNÓLOGO MÉDICO 10. NUTRICIÓN 11. TÉCNICO ENFERMERÍA 12. AUXILIAR DE ENFERMERÍA 13. OTRO
            </td>            
        </tr>

        <tr>
            <td colspan="1" rowspan="6" style="border-right:1px solid white;"></td>
            <td colspan="13" rowspan="5" style="border-right:1px solid white;"></td>
            <td colspan="1" rowspan="6" style="border-left:1px solid white;border-right:1px solid white;"></td>
            <th colspan="3" style="text-align:left;font-size:0.4rem;padding:2px;border-bottom:1px solid white;">ASEGURADO</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:2px;border-top:1.1px solid black;"></th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:2px;border-top:1.1px solid white;border-bottom:1.1px solid white;"></th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:2px;border-top:1.1px solid white;border-left:1.1px solid white;border-right:1.1px solid white;"></th>
            <td colspan="11" rowspan="3" style="border-right:white;border-left:white;"></td>

            <td colspan="1" rowspan="6" style="border-right:1px solid white;border-left:1px solid white;"></td>
            <td colspan="5" rowspan="5" style="border:1.1px solid black"></td>
            <td colspan="1" rowspan="6" style="border-left:1px solid white;"></td>
        </tr>

        <tr>
            <th colspan="3" style="text-align:left;font-size:0.4rem;padding:2px;border-bottom:1px solid white;">APODERADO</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:2px;border-top:1.1px solid black;"></th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:2px;border-top:1.1px solid white;border-bottom:1.1px solid white;"></th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:2px;border-top:1.1px solid white;border-left:1.1px solid white;border-right:1.1px solid white;border-bottom:1.1px solid white;"></th>
        </tr>

        <tr>
            <th colspan="7" style="text-align:left;font-size:0.4rem;padding:2px;border-bottom:1px solid white;">FIRMA</th>
        </tr>

        <tr>
            <th colspan="7" style="text-align:left;font-size:0.4rem;padding:2px;border-bottom:1px solid white;border-right:white;">APODERADO: NOMBRES Y APELLIDOS</th>
            <td colspan="11" style="padding:5px;"></td>
        </tr>

        <tr>
            <th colspan="7" style="text-align:left;font-size:0.4rem;padding:2px;border-bottom:1px solid white;border-right:white;">DNI O CE DEL APODERADO</th>
            <td colspan="11"></td>
        </tr>

        <tr>
            <td colspan="13" style="text-align:center;font-size:0.4rem;padding:2px;">FIRMA Y SELLO DEL RESPONSABLE DE LA ATENCIÓN</td>
            <td colspan="18"></td>
            <td colspan="5" style="text-align:center;font-size:0.3rem;padding:2px;border-top:1.1px solid black;">HUELLA DIGITAL DEL ASEGURADO O DEL APODERADO</td>
        </tr>

        <tr>
            <td colspan="40" style="border-right:1px solid white;border-bottom:1px solid white;border-left:1px solid white;"></td>
        </tr>
    </table>

    <div style="page-break-after:always;"></div>
    
    <table class="table table-sm borderless" id="tabla_general" style="table-layout: fixed;width: 100%;">

    @if($valor_datosFua->fua_libre == 0 || $valor_datosFua->fua_libre == null)

    @if($valor_datosFua->TipoPersonalSalud != "01")
    @if(!empty($datosFarmacia))
        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:2px;">MEDICAMENTOS</th>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="4" style="text-align:center;font-size:0.4rem;">CÓDIGO SISMED</th>
            <th colspan="28" style="text-align:center;font-size:0.4rem;">NOMBRE</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">FF</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">PRES</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">ENTR</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">DX</th>
        </tr>

        @foreach($datosFarmacia as $key => $valor_datosFarmacia)
        <tr>
            <td colspan="4" style="text-align:center;font-size:0.4rem;">{{$valor_datosFarmacia->catalogo_sismed}}</td>
            <td colspan="28" style="text-align:left;font-size:0.4rem;">{{$valor_datosFarmacia->catalogo_desc}}</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">{{$valor_datosFarmacia->catalogo_tipo}}</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">{{$valor_datosFarmacia->docf_item_cant}}</td>

            @if($valor_datosFarmacia->docf_flag == 'O')
            <td colspan="2" style="text-align:center;font-size:0.4rem;">{{$valor_datosFarmacia->docf_item_cant}}</td>
            @elseif($valor_datosFarmacia->docf_flag == 'X')
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            @else
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            @endif

            <td colspan="2" style="text-align:center;font-size:0.4rem;">{{$valor_datosFarmacia->diagnostico_estado}}</td>
        </tr>
        @endforeach
    @endif

    @if(!empty($datosTerapiasInicial))
        @foreach($datosTerapiasInicial as $key => $valor_datosTerapiasInicial)
            <tr style="background:WHITE;">
                <th colspan="40" style="text-align:center;font-size:0.6rem;padding:2px;">TERAPIAS</th>
            </tr>

            <tr style="background:WHITE;">
                <th colspan="4" style="text-align:center;font-size:0.4rem;">CÓDIGO</th>
                <th colspan="30" style="text-align:center;font-size:0.4rem;">NOMBRE</th>
                <th colspan="2" style="text-align:center;font-size:0.4rem;">PRES</th>
                <th colspan="2" style="text-align:center;font-size:0.4rem;">REALIZ</th>
                <th colspan="2" style="text-align:center;font-size:0.4rem;">DX</th>
            </tr>

            @if($valor_datosTerapiasInicial->codigoServicio == 'D0470' || $valor_datosTerapiasInicial->codigoServicio == 'D8220' || $valor_datosTerapiasInicial->codigoCPT == 'D0160' || $valor_datosTerapiasInicial->codigoCPT == 'D0160.1' || $valor_datosTerapiasInicial->codigoCPT == 'D0160.2' || $valor_datosTerapiasInicial->codigoCPT == 'D0160.3')
                <tr>
                    <td colspan="4" style="text-align:center;font-size:0.4rem;">D0160</td>
                    <td colspan="30" style="text-align:left;font-size:0.4rem;">CONSULTA ESTOMATOLÓGICA ESPECIALIZADA</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;">{{$valor_datosTerapiasInicial->cantidad_citas}}</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                </tr>
            @elseif($valor_datosTerapiasInicial->codigoCPT == '97006')
                <tr>
                    <td colspan="4" style="text-align:center;font-size:0.4rem;">97010</td>
                    <td colspan="30" style="text-align:left;font-size:0.4rem;">{{$valor_datosTerapiasInicial->descripcion}}</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;">{{$valor_datosTerapiasInicial->cantidad_citas}}</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                </tr>
            @elseif($valor_datosTerapiasInicial->codigoCPT == '97150')
                <tr>
                    <td colspan="4" style="text-align:center;font-size:0.4rem;">97799</td>
                    <td colspan="30" style="text-align:left;font-size:0.4rem;">{{$valor_datosTerapiasInicial->descripcion}}</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;">{{$valor_datosTerapiasInicial->cantidad_citas}}</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                </tr>
            @elseif($valor_datosTerapiasInicial->codigoCPT == '97036')
                <tr>
                    <td colspan="4" style="text-align:center;font-size:0.4rem;">97113</td>
                    <td colspan="30" style="text-align:left;font-size:0.4rem;">{{$valor_datosTerapiasInicial->descripcion}}</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;">{{$valor_datosTerapiasInicial->cantidad_citas}}</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                </tr>
            @elseif($valor_datosTerapiasInicial->codigoCPT == 'D2390')
                <tr>
                    <td colspan="4" style="text-align:center;font-size:0.4rem;">E2395</td>
                    <td colspan="30" style="text-align:left;font-size:0.4rem;">{{$valor_datosTerapiasInicial->descripcion}}</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;">{{$valor_datosTerapiasInicial->cantidad_citas}}</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                </tr>
            @else
                <tr>
                    <td colspan="4" style="text-align:center;font-size:0.4rem;">{{$valor_datosTerapiasInicial->codigoCPT}}</td>
                    <td colspan="30" style="text-align:left;font-size:0.4rem;">{{$valor_datosTerapiasInicial->descripcion}}</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;">{{$valor_datosTerapiasInicial->cantidad_citas}}</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                </tr>

<!--                 <tr>
                    <td colspan="4" style="text-align:center;font-size:0.4rem;">D0160</td>
                    <td colspan="30" style="text-align:left;font-size:0.4rem;">CONSULTA ESTOMATOLÓGICA ESPECIALIZADA</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;">{{$valor_datosTerapiasInicial->cantidad_citas}}</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                </tr> -->
            @endif
        @endforeach
    @endif

    @if(!empty($datosTerapiasCitasHora))

        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:2px;">TERAPIAS</th>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="4" style="text-align:center;font-size:0.4rem;">CÓDIGO</th>
            <th colspan="30" style="text-align:center;font-size:0.4rem;">NOMBRE</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">PRES</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">REALIZ</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">DX</th>
        </tr>

        @foreach($datosTerapiasCitasHora as $key => $valor_datosTerapiasCitasHora)
                <tr>
                    <td colspan="4" style="text-align:center;font-size:0.4rem;">{{$valor_datosTerapiasCitasHora->codigoCPT}}</td>
                    <td colspan="30" style="text-align:left;font-size:0.4rem;">{{$valor_datosTerapiasCitasHora->descripcion}}</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;">{{$valor_datosTerapiasCitasHora->cantidad_citas}}</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                </tr>
        @endforeach
    @endif

    @if(!empty($datosPDiarioDetalles))

        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:2px;">PROCEDIMIENTOS ESPECIALIZADOS</th>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="4" style="text-align:center;font-size:0.4rem;">CÓDIGO</th>
            <th colspan="30" style="text-align:center;font-size:0.4rem;">NOMBRE</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">PRES</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">REALIZ</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;">DX</th>
        </tr>

        @foreach($datosPDiarioDetalles as $key => $valor_datosPDiarioDetalles)
                <tr>
                    <td colspan="4" style="text-align:center;font-size:0.4rem;">{{$valor_datosPDiarioDetalles->codigoCPT}}</td>
                    <td colspan="30" style="text-align:left;font-size:0.4rem;">{{$valor_datosPDiarioDetalles->descripcion}}</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;">{{$valor_datosPDiarioDetalles->cantidad_citas}}</td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                    <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
                </tr>
        @endforeach
    @endif
@endif

@if($valor_datosFua->TipoPersonalSalud == "01")
        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:1px;">TERAPIAS</th>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">CÓDIGO</th>
            <th colspan="12" style="text-align:center;font-size:0.4rem;padding:1px;">NOMBRE</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">PRES</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">REALIZ</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">DX</th>

            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">CÓDIGO</th>
            <th colspan="12" style="text-align:center;font-size:0.4rem;padding:1px;">NOMBRE</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">PRES</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">REALIZ</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">DX</th>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">99210</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">ATENCIÓN DE SERVICIO SOCIAL</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">97799</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">TERAPIA FÍSICA GRUPAL</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">99401</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">CONSEJERÍA INTEGRAL</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">97535</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">TERAPIA OCUPACIONAL</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">99403</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">CONSEJERÍA NUTRICIONAL</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">97799</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">TERAPIA OCUPACIONAL GRUPAL</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">92507</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">TERAPIA DE LENGUAJE</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">90806</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">PSICOTERAPIA INDIVIDUAL</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">97799</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">TERAPIA DEL LENGUAJE GRUPAL</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">96100.11</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">PSICOTERAPIA GRUPAL PSICODINÁMICA</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">97113</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">SESIÓN: HIDROTERAPIA: TANQUE DE WHIRLPOOL + PISCINA</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">97770</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">TERAPIA DE APRENDIZAJE INDIVIDUAL</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">97799</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">TANQUE HUBBARD</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">97799</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">TERAPIA DE APRENDIZAJE GRUPAL</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">97010</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">TERAPIA FÍSICA A UNA O MÁS ÁREAS; FRÍO O CALOR LOCAL</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">97799</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">REHABILITACIÓN PROFESIONAL</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:1px;">PROCEDIMIENTOS ESPECIALIZADOS</th>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">CÓDIGO</th>
            <th colspan="14" style="text-align:center;font-size:0.4rem;padding:1px;">NOMBRE</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">IND</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">EJE</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">DX</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">RES</th>

            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">CÓDIGO</th>
            <th colspan="14" style="text-align:center;font-size:0.4rem;padding:1px;">NOMBRE</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">IND</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">EJE</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">DX</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">RES</th>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">29450</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">APLICACIÓN DE YESO PARA PIE ZAMBO, LARGO O CORTO</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">15880</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">CURACIÓN DE HERIDAS</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">92557</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">AUDIOMETRÍA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">51702</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">COLOCACIÓN DE SONDA FOLEY (CATETERISMO INTERMITENTE)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">92567</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">TIMPANOMETRÍA (PRUEBA DE IMPEDANCIA)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">51736</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">UROFLUJIMETRÍA (UFM) SIMPLE (ESTUDIO URODINÁMICO)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">92585</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">TONO ESTABLE (POTENCIALES EVOCADOS AUDITIVOS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">97799</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">VIBROESTIMULACIÓN PENEANA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">97799</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">OTOEMISIONES ACUSTICAS</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">97799</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">PROLOTERAPIA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">97799</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">LOGOAUDIOMETRÍA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">97799</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ESCOLIOMETRIA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">97799</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">CONDICIONAMIENTO AUDITIVO</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">99206</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ATENCIÓN DE ENFERMERÍA EN II,III NIVEL DE ATENCIÓN</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">97799</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">PRUEBA DE REFLEJO ACUSTICO</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">99231</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ATENCIÓN PACIENTE + DÍA HOSPITALIZACIÓN (**)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">97810</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">SESIÓN: ACUPUNTURA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">99367</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">JUNTA MÉDICA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">97810 + 97799</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ELECTROACUPUNTURA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">93307</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ECOCARDIOGRAFÍA TRANSTORÁCICA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">97799</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">LASERTERAPIA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">93000</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ELECTROCARDIOGRAMA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">20600</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">BLOQUEO PARAVERTEBRAL</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">93784</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">MONITOREO AMBULATORIO DE LA PRESIÓN ARTERIAL (MAPA)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">20600</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ARTROCENTESIS Y/O INFILTRACIÓN</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">93224</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">MONITOREO HOLTER</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">97799</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ELECTROTERAPIA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">93015</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">PRUEBA DE ESFUERZO</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">94010</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ESPIROMETRÍA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">90782</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">INYECCIÓN PROFILÁCTICA,SUBCUTÁNEA O INTRAMUSCULAR</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">94375</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">MEDICIÓN DE BUCLE DE FLUJO - VOLUMEN RESPIRATORIO</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">99499.01</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">TELECONSULTA EN LINEA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">94620</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">PRUEBA DE ESFUERZO PULMONAR</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">99499.08</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">TELEORIENTACIÓN SINCRONICA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">97799</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ANALISIS LABORATORIO DE LA MARCHA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">99499.11</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">TELEINTERCONSULTA SINCRÓNICA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:1px;">ODONTOLOGÍA</th>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">CÓDIGO</th>
            <th colspan="12" style="text-align:center;font-size:0.4rem;padding:1px;">NOMBRE</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">PRESS</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">REALIZ</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">DX</th>

            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">CÓDIGO</th>
            <th colspan="12" style="text-align:center;font-size:0.4rem;padding:1px;">NOMBRE</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">PRESS</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">REALIZ</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">DX</th>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">D0160</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">CONSULTA ESTOMATOLÓGICA ESPECIALIZADA</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">D2331</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">RESTAURACIÓN DE DIENTE CON RESINA, DOS SUPERFICIES</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
        </tr>     
        
        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">D1110</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">PROFILAXIS</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">E2395</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">RESTAURACIÓN DE UNA SUPERFICIE CON IONÓMERO DE VIDRIO</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
        </tr>    

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">D7176</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">EXTRACCIÓN DENTAL SIMPLE</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">D4341</td>
            <td colspan="12" style="text-align:left;font-size:0.4rem;">DESTARTRAJE</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
        </tr>
        
        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:1px;">DIAGNOSTICO POR IMÁGENES / LABORATORIO</th>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">CÓDIGO</th>
            <th colspan="14" style="text-align:center;font-size:0.4rem;padding:1px;">NOMBRE</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">IND</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">EJE</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">DX</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">RES</th>

            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">CÓDIGO</th>
            <th colspan="14" style="text-align:center;font-size:0.4rem;padding:1px;">NOMBRE</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">IND</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">EJE</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">DX</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">RES</th>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">72040</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX COLUMNA CERVICAL, 2 O 3 INCIDENCIAS</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">86000</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">AGLUTINACIONES</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">72070</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX COLUMNA VERTEBRAL TORÁCICA (2 PLAZAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">85018</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">DOSAJE DE HEMOGLOBINA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">72080</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX COLUMNA DORSOLUMBAR (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">85027</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">HEMOGRAMA COMPLETO</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">72100</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX COLUMNA LUMBOSACRA (2 A 3 VISTAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">85651</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">VELOCIDAD DE SEDIMENTACIÓN GLOBULAR</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">72170</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX PELVIS; UNA O DOS INCIDENCIAS</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">86140</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">PROTEÍNA C REACTIVA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">73510</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX CADERA, DE DOS VISTAS (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">82947</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">GLUCOSA CUANTITATIVA EN SANGRE</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">73030</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX HOMBRO, 2 INCIDENCIAS (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">84478</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">TRIGLICÉRIDOS</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">73120</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX MANO, MÍNIMO DOS INCIDENCIAS (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">82465</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">COLESTEROL TOTAL, EN SUERO O SANGRE TOTAL</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">73550</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX FÉMUR, DOS INCIDENCIAS (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">83718</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">HDL COLESTEROL</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">73560</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX RODILLA, DOS INCIDENCIAS (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">83719</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">VLDL COLESTEROL</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">73590</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX TIBIA Y PERONÉ, DOS INCIDENCIAS (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">83721</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">LDL COLESTEROL</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">73600</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX TOBILLO, 2 INCIDENCIAS (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">84155</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">PROTEÍNAS TOTALES</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">73620</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX PIE COMPLETO, MÍNIMO DOS INCIDENCIAS (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">82040</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">MEDICIÓN DE ALBÚMINA SÉRICA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">76700</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ECOGRAFÍA ABDOMINAL COMPLETA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">82247</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">BILIRRUBINA TOTAL</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">76770</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ECOGRAFÍA RENAL</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">82248</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">BILIRRUBINA DIRECTA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">76775</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ECOGRAFÍA DE VÍAS URINARIAS</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">84075</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">FOSFATASA ALCALINA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">76880</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ECOGRAFÍA NO VASCULAR DE EXTREMIDAD POR RASTREO</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">84450</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">TGP TRANSAMINASA GLUTÁMICO OXALACÉTICO</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">77080</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">DENSITOMETRÍA ÓSEA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">84460</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">TGP TRANSAMINASA GLUTÁMICO PIRÚVICA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">95860</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ELECTROMIOGRAFÍA UNA EXTREMIDAD</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">82565</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">CREATININA EN SANGRE</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">84526</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">UREA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">82575</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">DEPURACIÓN DE CREATININA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">81000</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">EXAMEN DE ORINA CON TIRA REACTIVA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">87087</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">UROCULTIVO Y ANTIBIOGRAMA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">83615</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">LACTATO DESHIDROGENASA (LDH)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">86592</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">PRUEBA DE SÍFILIS CUALITATIVA (VDRL,RPR)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">87220</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">KOH - PIEL, CABELLO O UÑAS</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">87177</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">EX. PARÁSITOS Y HUEVOS POR FROTIS DIRECTO X3</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:1px;">SUB COMPONENTE PRESTACIONAL (PROTESIS / ORTETICOS)</th>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">CÓDIGO</th>
            <th colspan="13" style="text-align:center;font-size:0.4rem;padding:1px;">NOMBRE</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">IND/PRES.</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">EJE/ENTR.</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">DX</th>

            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">CÓDIGO</th>
            <th colspan="13" style="text-align:center;font-size:0.4rem;padding:1px;">NOMBRE</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">IND/PRES.</th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">EJE/ENTR.</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">DX</th>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">30234</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">P.M.I-(AK)-ENDOESQUELÉTICA/SOCKET RESINA/CINTURÓN SILECIADO</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">30363</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">BOTIN DE CUERO</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">30235</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">P.M.I-(AK)-ENDOESQ./SOCKETPOLIPROPILENO/CINTURÓN SILECIADO</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">30364</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">BOTIN DE CUERO CON PUNTA DE ACERO TALLA 36</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">30281</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">P.M.I-(BK)-ENDOESQUELÉTICA/SOCKET RESINA</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">30365</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">BOTIN DE CUERO CON PUNTA DE ACERO TALLA 39</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">30282</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">P.M.I-(BK)-ENDOESQUELÉTICA/SOCKET POLIPROPILENO</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">38203</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">PLANTILLA ORTOPEDICA TALLA S (25 AL 28)</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">30256</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">ARTIC. DE RODILLA MODULAR ENDOESQ. MONOCENTRICA DE PROTESIS</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">38202</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">PLANTILLA ORTOPEDICA TALLA M (29 AL 37)</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">30257</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">ARTICULACIÓN DE RODILLA MODELO 3R-17</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">38201</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">PLANTILLA ORTOPEDICA TALLA L (37 AL 44)</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">30258</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">ARTICULACIÓN DE RODILLA MODELO 3R-20</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">30316</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">EXTENSOR DE CODO SEMIRIGIDO ADULTO</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">30251</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">PIE SACH DE PROTESIS DE MIEMBRO INFERIOR</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">30345</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">MANOPLA DORSAL CORTA DE PVC</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">30421</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">F.M.I EXTENSOR DE RODILLA SEMIRIGIDO NIÑO MAYOR DE 2 AÑOS</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">31637</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">BASTON TIPO CANADIENSE P/ADULTO MANGO DE POLIPROPILENO</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">30301</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">F.M.I ALMOHADILLA DE FREGJKA</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">30423</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">CORSE SEMIRIGIDO LUMBO SACRO PARA ADULTO</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">30269</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">ORTETICO-TOBILLO-PIE (OTP) POSTULAR PARA NIÑO</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">30273</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">ORTETICO-TOBILLO-PIE (OTP) POSTULAR PARA ADULTO</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">30302</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">TWISTER ELASTICO</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">30303</td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;">TWISTER METALICO</td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="13" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>
        @endif

    @else
        
        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:1px;">DIAGNOSTICO POR IMÁGENES / LABORATORIO</th>
        </tr>

        <tr style="background:WHITE;">
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">CÓDIGO</th>
            <th colspan="14" style="text-align:center;font-size:0.4rem;padding:1px;">NOMBRE</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">IND</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">EJE</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">DX</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">RES</th>

            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:1px;">CÓDIGO</th>
            <th colspan="14" style="text-align:center;font-size:0.4rem;padding:1px;">NOMBRE</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">IND</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">EJE</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">DX</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:1px;">RES</th>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">72040</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX COLUMNA CERVICAL, 2 O 3 INCIDENCIAS</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">86000</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">AGLUTINACIONES</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">72070</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX COLUMNA VERTEBRAL TORÁCICA (2 PLAZAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">85018</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">DOSAJE DE HEMOGLOBINA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">72080</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX COLUMNA DORSOLUMBAR (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">85027</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">HEMOGRAMA COMPLETO</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">72100</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX COLUMNA LUMBOSACRA (2 A 3 VISTAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">85651</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">VELOCIDAD DE SEDIMENTACIÓN GLOBULAR</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">72170</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX PELVIS; UNA O DOS INCIDENCIAS</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">86140</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">PROTEÍNA C REACTIVA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">73510</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX CADERA, DE DOS VISTAS (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">82947</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">GLUCOSA CUANTITATIVA EN SANGRE</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">73030</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX HOMBRO, 2 INCIDENCIAS (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">84478</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">TRIGLICÉRIDOS</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">73120</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX MANO, MÍNIMO DOS INCIDENCIAS (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">82465</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">COLESTEROL TOTAL, EN SUERO O SANGRE TOTAL</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">73550</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX FÉMUR, DOS INCIDENCIAS (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">83718</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">HDL COLESTEROL</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">73560</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX RODILLA, DOS INCIDENCIAS (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">83719</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">VLDL COLESTEROL</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">73590</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX TIBIA Y PERONÉ, DOS INCIDENCIAS (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">83721</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">LDL COLESTEROL</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">73600</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX TOBILLO, 2 INCIDENCIAS (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">84155</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">PROTEÍNAS TOTALES</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">73620</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">RX PIE COMPLETO, MÍNIMO DOS INCIDENCIAS (2 PLACAS)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">82040</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">MEDICIÓN DE ALBÚMINA SÉRICA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">76700</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ECOGRAFÍA ABDOMINAL COMPLETA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">82247</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">BILIRRUBINA TOTAL</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">76770</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ECOGRAFÍA RENAL</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">82248</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">BILIRRUBINA DIRECTA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">76775</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ECOGRAFÍA DE VÍAS URINARIAS</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">84075</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">FOSFATASA ALCALINA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">76880</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ECOGRAFÍA NO VASCULAR DE EXTREMIDAD POR RASTREO</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">84450</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">TGP TRANSAMINASA GLUTÁMICO OXALACÉTICO</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">77080</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">DENSITOMETRÍA ÓSEA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">84460</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">TGP TRANSAMINASA GLUTÁMICO PIRÚVICA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;">95860</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">ELECTROMIOGRAFÍA UNA EXTREMIDAD</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">82565</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">CREATININA EN SANGRE</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">84526</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">UREA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">82575</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">DEPURACIÓN DE CREATININA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">81000</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">EXAMEN DE ORINA CON TIRA REACTIVA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">87087</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">UROCULTIVO Y ANTIBIOGRAMA</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">83615</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">LACTATO DESHIDROGENASA (LDH)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">86592</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">PRUEBA DE SÍFILIS CUALITATIVA (VDRL,RPR)</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">87220</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">KOH - PIEL, CABELLO O UÑAS</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>

            <td colspan="2" style="text-align:center;font-size:0.4rem;">87177</td>
            <td colspan="14" style="text-align:left;font-size:0.4rem;">EX. PARÁSITOS Y HUEVOS POR FROTIS DIRECTO X3</td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
            <td colspan="1" style="text-align:center;font-size:0.4rem;"></td>
        </tr>
    @endif

        <tr style="background:WHITE;">
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:2px;">OBSERVACIONES</th>
        </tr>

<!--         <tr>
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:5px;"></th>
        </tr> -->

<!--         <tr>
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:5px;"></th>
        </tr> -->

        <tr>
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:5px;"></th>
        </tr>

        <tr>
            <th colspan="40" style="text-align:center;font-size:0.6rem;padding:5px;"></th>
        </tr>

        <tr>
            <td colspan="40" style="font-size:0.4rem;text-align:justify;border-bottom:1px solid white;padding:10px;"></td>
        </tr>

        <tr>
            <td colspan="1" rowspan="6" style="border-right:1px solid white;"></td>
            <td colspan="13" rowspan="5" style="border-right:1px solid white;"></td>
            <td colspan="1" rowspan="6" style="border-left:1px solid white;border-right:1px solid white;"></td>
            <th colspan="3" style="text-align:left;font-size:0.4rem;padding:2px;border-bottom:1px solid white;">ASEGURADO</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:2px;border-top:1.1px solid black;"></th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:2px;border-top:1.1px solid white;border-bottom:1.1px solid white;"></th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:2px;border-top:1.1px solid white;border-left:1.1px solid white;border-right:1.1px solid white;"></th>
            <td colspan="11" rowspan="3" style="border-right:white;border-left:white;"></td>

            <td colspan="1" rowspan="6" style="border-right:1px solid white;border-left:1px solid white;"></td>
            <td colspan="5" rowspan="5" style="border:1.1px solid black"></td>
            <td colspan="1" rowspan="6" style="border-left:1px solid white;"></td>
        </tr>

        <tr>
            <th colspan="3" style="text-align:left;font-size:0.4rem;padding:2px;border-bottom:1px solid white;">APODERADO</th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:2px;border-top:1.1px solid black;"></th>
            <th colspan="1" style="text-align:center;font-size:0.4rem;padding:2px;border-top:1.1px solid white;border-bottom:1.1px solid white;"></th>
            <th colspan="2" style="text-align:center;font-size:0.4rem;padding:2px;border-top:1.1px solid white;border-left:1.1px solid white;border-right:1.1px solid white;border-bottom:1.1px solid white;"></th>
        </tr>

        <tr>
            <th colspan="7" style="text-align:left;font-size:0.4rem;padding:2px;border-bottom:1px solid white;">FIRMA</th>
        </tr>

        <tr>
            <th colspan="7" style="text-align:left;font-size:0.4rem;padding:2px;border-bottom:1px solid white;border-right:white;">APODERADO: NOMBRES Y APELLIDOS</th>
            <td colspan="11" style="padding:15px;"></td>
        </tr>

        <tr>
            <th colspan="7" style="text-align:left;font-size:0.4rem;padding:2px;border-bottom:1px solid white;border-right:white;">DNI O CE DEL APODERADO</th>
            <td colspan="11"></td>
        </tr>

         <tr>
            <td colspan="13" style="text-align:center;font-size:0.4rem;padding:2px;">FIRMA Y SELLO DEL RESPONSABLE DE PROCEDIMIENTO Y/O FARMACIA</br> Y/O LABORATORIO</td>
            <td colspan="18" style="text-align:center;"></td>
            <td colspan="5" style="text-align:center;font-size:0.3rem;padding:2px;border-top:1.1px solid black;">HUELLA DIGITAL DEL ASEGURADO</br> O DEL APODERADO </td>
        </tr>

        <tr>
            <td colspan="40" style="border-right:1px solid white;border-bottom:1px solid white;border-left:1px solid white;"></td>
        </tr>
        
    @endforeach
    </table>
</body>

<style>
    html {
        margin: 0;
    }

    body{
        font-size:10px;
        text-transform: uppercase;
        font-family: "Gill Sans Extrabold", Helvetica, sans-serif;
        margin: 8mm 8mm 8mm 8mm;
    }

/*     .borderless td, .borderless th {
        border: none;
    } */

    th {
    padding: 5px;
    }

    #tabla_general{
        border-collapse: collapse;
        width: 100%;
    }

    #tabla_general, td, th {
        border: 1px solid black;
    }

    #tabla_titulo{
        width:100%;
    }

    .rotate {
        text-align: left;
        white-space: nowrap;
        vertical-align: middle;
        width: 1.5em;
    }

    .rotate1 {
        text-align: left;
        white-space: nowrap;
        vertical-align: middle;
        width: 1.5em;
    }

    hr {
        margin-top:0px;
        height: 1px;
        background-color: #B2CFB6;
    }

    .rotate div {
        -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
        -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
        -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
        filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
        -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
        margin-left: -10em;
        margin-right: -10em;
        margin-top: -7.4em;
        margin-bottom: 5em;
    }

    .rotate1 div {
        -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
        -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
        -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
        filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
        -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
        margin-left: -10em;
        margin-right: -10em;
        margin-top: -7.1em;
        margin-bottom: 5em;
    }

</style>
</html>
