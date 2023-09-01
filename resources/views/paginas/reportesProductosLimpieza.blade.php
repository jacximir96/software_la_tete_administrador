<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Lista de Productos de Limpieza - {{date("Y")}}</title>
    {{-- BOOTSTRAP 4 --}}
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> -->
</head>
<body>
    <table class="table table-sm borderless" id="tabla_titulo">

        <thead>
            <tr>
                <th colspan="4" style="text-align:center;">Lista de Productos de Limpieza</th>
            </tr>

            <tr>
                <th colspan="4" style="text-align:center;">Actualizado al {{date("d-m-Y")}}</th>
            </tr>
        </thead>
    </table>

    <hr>

    <table class="table table-bordered table-sm" id="tabla_cronogramaGeneral" style="text-align:center;">

        <thead class="thead-light">
            <tr style="background:#B2CFB6;">
                <th style="vertical-align : middle;text-align:center;">#</th>
                <th style="vertical-align : middle;text-align:center;">Nombre</th>
                <th style="width:80px;">Código</th>
                <th style="vertical-align : middle;text-align:center;">Descripción</th>
                <th style="vertical-align : middle;text-align:center;width:100px;">Categoría</th>
                <th style="vertical-align : middle;text-align:center;">Stock</th>
                <th style="width:90px;">Agregado</th>
                <th style="width:90px;">Actualizado</th>
                <th>Usuario que realizó la última acción</th>
            </tr>
        </thead>

        <tbody>
        @foreach($productosLimpieza as $key => $valor_productosLimpieza)
            <tr>
                <td style="vertical-align : middle;text-align:center;">{{($key+1)}}</td>
                <td style="vertical-align : middle;text-align:center;">{{$valor_productosLimpieza->nombre_productoLimpieza}}</td>
                <td style="vertical-align : middle;text-align:center;">{{$valor_productosLimpieza->codigo_productoLimpieza}}</td>
                <td style="vertical-align : middle;text-align:center;">{{$valor_productosLimpieza->descripcion_productoLimpieza}}</td>
                <td style="vertical-align : middle;text-align:center;">{{$valor_productosLimpieza->nombre_categoria}}</td>
                <td style="vertical-align : middle;text-align:center;">{{$valor_productosLimpieza->stock_productoLimpieza}} ({{$valor_productosLimpieza->nombre_unidadMedida}})</td>
                <td style="vertical-align : middle;text-align:center;">{{ \Carbon\Carbon::parse($valor_productosLimpieza->created_at)->format('d-m-Y H:i:s')}}</td>
                <td style="vertical-align : middle;text-align:center;">{{ \Carbon\Carbon::parse($valor_productosLimpieza->updated_at)->format('d-m-Y H:i:s')}}</td>
                <td style="vertical-align : middle;text-align:center;">{{$valor_productosLimpieza->name}}</td>
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
