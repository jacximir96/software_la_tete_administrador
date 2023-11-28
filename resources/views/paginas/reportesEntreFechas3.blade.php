<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Ventas</title>
    {{-- BOOTSTRAP 4 --}}
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> -->
</head>
<body>
    <table class="table table-sm borderless" id="tabla_titulo">

        <thead>
            <tr>
                <th style="text-align:center;font-size:0.5rem" colspan="2"><u>Reporte de ventas del periodo {{ \Carbon\Carbon::parse($datos["fecha_inicial"])->format('d-m-Y')}}</u></br>
                <p style="font-size:0.4rem;">Fecha de Reporte: {{date("d-m-Y")}}</p></th>
            </tr>
        </thead>

    </table>

    <table class="table table-bordered table-sm" id="tabla_cronogramaGeneral" style="text-align:center;">
        <thead class="thead-light">
            <tr style="background:#B2CFB6;">
                <th colspan="7" style="vertical-align : middle;text-align:center;" scope="col">VENTAS</th>
            </tr>

            <tr style="background:#B2CFB6;">
                <th style="vertical-align : middle;text-align:center;">#</th>
                <th style="vertical-align : middle;text-align:center;">Correlativo</th>
                <th style="vertical-align : middle;text-align:center;">Ordenes de pedido</th>
                <th style="vertical-align : middle;text-align:center;">Monto total</th>
                <th style="vertical-align : middle;text-align:center;">Periodo</th>
                <th style="vertical-align : middle;text-align:center;">Tipo de pago</th>
                <th style="vertical-align : middle;text-align:center;">Productos</th>
            </tr>
        </thead>

        <tbody>
        @foreach($responseGeneral as $key =>$valor_ventasPorFechas)
            <tr>
                <td><strong>{{($key+1)}}</strong></td>
                <td>{{$valor_ventasPorFechas->cfac_correlativo}}</td>
                <td>{{$valor_ventasPorFechas->cfac_ordenes_pedido}}</td>
                <td>S/ {{$valor_ventasPorFechas->cfac_monto_total}}</td>
                <td>{{\Carbon\Carbon::parse($valor_ventasPorFechas->prd_fechaapertura)->format('d-m-Y')}}</td>
                <td>{{$valor_ventasPorFechas->fp_descripcion}}</td>
                <td style="text-align:justify">
                @foreach($valor_ventasPorFechas->productosCabeceraOrdenPedido as $key1 => $valor_productos)
                    <p style="text-align:justify"><strong>{{$key1+1}} => </strong><strong>Nombre:</strong> {{$valor_productos->nombre_productoLimpieza}}, <strong>Cantidad:</strong> {{$valor_productos->dop_cantidad}}, <strong>Precio unitario:</strong> {{$valor_productos->dop_precio}}, <strong>Precio total:</strong><span style="color:red;">{{$valor_productos->dop_total}}</span></p></br>
                @endforeach
                </td>
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
