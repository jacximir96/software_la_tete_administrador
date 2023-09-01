<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reporte de cierre de caja</title>
    {{-- BOOTSTRAP 4 --}}
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> -->
</head>
<body>
    <table class="table table-sm borderless" id="tabla_titulo">

        <thead>
            <tr>
                <th style="text-align:center;font-size:0.5rem" colspan="2"><u>Reporte de cierre decaja entre fechas Del {{ \Carbon\Carbon::parse($datos["fecha_inicial"])->format('d-m-Y')}} al {{ \Carbon\Carbon::parse($datos["fecha_final"])->format('d-m-Y')}}</u></br>
                <p style="font-size:0.4rem;">Fecha de Reporte: {{date("d-m-Y")}}</p></th>
            </tr>
        </thead>

    </table>

    <table class="table table-bordered table-sm" id="tabla_cronogramaGeneral" style="text-align:center;">
        <thead class="thead-light">
            <tr style="background:#B2CFB6;">
                <th colspan="3" style="vertical-align : middle;text-align:center;" scope="col">CIERRE CAJA</th>
            </tr>

            <tr style="background:#B2CFB6;">
                <th style="vertical-align : middle;text-align:center;">#</th>
                <th style="vertical-align : middle;text-align:center;">Fecha</th>
                <th style="vertical-align : middle;text-align:center;">Monto</th>
            </tr>
        </thead>

        <tbody>
        @foreach($cierreCajaPorFechas as $key =>$valor_cierreCajaPorFechas)
            <tr>
                <td>{{($key+1)}}</td>
                <td>{{\Carbon\Carbon::parse($valor_cierreCajaPorFechas->created_at)->format('d-m-Y')}}</td>
                <td>S/ {{$valor_cierreCajaPorFechas->cc_monto}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>

<style>
    body{
        font-size:10px;
        text-transform: uppercase;
        font-family: "Gill Sans Extrabold", Helvetica, sans-serif;
    }

    .borderless td, .borderless th {
        border: none;
    }

    th {
    padding: 5px;
    }

    #tabla_cronogramaGeneral{
        border-collapse: collapse;
        width: 100%;
    }

    #tabla_cronogramaGeneral, td, th {
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
        margin-top: -5em;
        margin-bottom: 10em;
}

</style>
</html>
