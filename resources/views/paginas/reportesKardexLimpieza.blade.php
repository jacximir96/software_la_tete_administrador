<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Movimientos del Entradas y Salidas general - {{date("Y")}}</title>
    {{-- BOOTSTRAP 4 --}}
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> -->
</head>
<body>
    <table class="table table-sm borderless" id="tabla_titulo">

        <thead>
            <tr>
                <th colspan="4" style="text-align:center;">Lista de Movimientos del Kardex de Productos de Limpieza del Mes de
                @if($mfecha == 1)
                        ENERO
                    @elseif($mfecha == 2)
                        FEBRERO
                    @elseif($mfecha == 3)
                        MARZO
                    @elseif($mfecha == 4)
                        ABRIL
                    @elseif($mfecha == 5)
                        MAYO
                    @elseif($mfecha == 6)
                        JUNIO
                    @elseif($mfecha == 7)
                        JULIO
                    @elseif($mfecha == 8)
                        AGOSTO
                    @elseif($mfecha == 9)
                        SETIEMBRE
                    @elseif($mfecha == 10)
                        OCTUBRE
                    @elseif($mfecha == 11)
                        NOVIEMBRE
                    @elseif($mfecha == 12)
                        DICIEMBRE
                    @endif
                    del {{$afecha}}
                </th>
            </tr>

            <tr>
                <th colspan="4" style="text-align:center;">Actualizado al {{date("d-m-Y")}}</th>
            </tr>
        </thead>

        <tbody>

        </tbody>

    </table>

        <hr>

    <table class="table table-bordered table-sm" id="tabla_cronogramaGeneral" style="text-align:center;">

        <thead class="thead-light">
            <tr style="background:#B2CFB6;">
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Código</th>
                <th>Stock Actual</th>
                <th>Entradas</th>
                <th>Salidas</th>
            </tr>
        </thead>

        <tbody>
            @foreach($kardexLimpiezaTotal as $key => $valor_kardexLimpiezaTotal)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$valor_kardexLimpiezaTotal->nombre_productoLimpieza}}</td>
                <td>{{$valor_kardexLimpiezaTotal->descripcion_productoLimpieza}}</td>
                <td>{{$valor_kardexLimpiezaTotal->codigo_productoLimpieza}}</td>
                <td>{{$valor_kardexLimpiezaTotal->stock_productoLimpieza}}</td>
                <td>{{$valor_kardexLimpiezaTotal->suma_entradas}}</td>
                <td>{{$valor_kardexLimpiezaTotal->suma_salidas}}</td>
            </tr>
            @endforeach

            @foreach($sumaEntradasSalidasTotal as $key => $valor_sumaEntradasSalidasTotal)    
            <tr>
                <td colspan="5">Total</td>
                <td>{{$valor_sumaEntradasSalidasTotal->suma_entradasTotal}}</td>
                <td>{{$valor_sumaEntradasSalidasTotal->suma_salidasTotal}}</td>
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
