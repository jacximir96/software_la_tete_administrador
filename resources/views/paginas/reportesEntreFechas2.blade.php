<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>
        @if($datos["id_categoria"] == 1)
            Historial de Productos de Limpieza
        @endif
    </title>
    {{-- BOOTSTRAP 4 --}}
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> -->
</head>
<body>

    <table class="table table-sm borderless" id="tabla_titulo">
    <tr>
                <th style="text-align:center;font-size:0.5rem" colspan="2"><u>@if($datos["id_categoria"] == 1) Historial de Salidas de Productos de Limpieza @endif @if($datos["id_categoria"] == 2) Historial de Salidas de Materiales y Herramientas @endif @if($datos["id_categoria"] == 3) Historial de Salidas de Productos de EPP @endif entre fechas Del {{ \Carbon\Carbon::parse($datos["fecha_inicial"])->format('d-m-Y')}} al {{ \Carbon\Carbon::parse($datos["fecha_final"])->format('d-m-Y')}}</u></br>
                <p style="font-size:0.4rem;">Fecha de Reporte: {{date("d-m-Y")}}</p></th>
            </tr>

            <tr>
                @if($datos["id_categoria"] == 1)

                @endif

                @if($datos["id_categoria"] == 2)

                @endif

                @if($datos["id_categoria"] == 3)

                @endif
            </tr>
    </table>

    <table class="table table-bordered table-sm" id="tabla_cronogramaGeneral" style="text-align:center;">
        <thead class="thead-light">
            <tr style="background: #453C21;color:white;">
                @foreach($equipos as $key => $valor2)
                <th colspan="6" style="text-align:center;font-size:0.7rem">{{$valor2->nombre_equipo}}</th>
                @endforeach
            </tr>

            <tr style="background:#B2CFB6;">
                <th style="width:15px;">#</th>
                <th>Empleado</th>
                <th>Cargo</th>
                <th style="width:60px;">Código (Producto)</th>
                <th>Nombre del Producto</th>
                <th style="width:50px;">Cantidad Recibido</th>
            </tr>
        </thead>

        <tbody>
            @if($datos["id_categoria"] == 1)
                @foreach($empleados_equiposCargosLimpieza as $key => $valor8)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$valor8->nombres_empleado}} {{$valor8->apellidos_empleado}}</td>
                        <td>{{$valor8->nombre_cargo}}</td>
                        <td>{{$valor8->codigo_productoLimpieza}}</td>
                        <td>{{$valor8->nombre_productoLimpieza}} {{$valor8->descripcion_productoLimpieza}}</td>
                        <td>{{$valor8->suma_productos}}</td>
                    </tr>
                @endforeach
            @endif

            @if($datos["id_categoria"] == 2)
                @foreach($empleados_equiposCargosHerramienta as $key => $valor7)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$valor7->nombres_empleado}} {{$valor7->apellidos_empleado}}</td>
                        <td>{{$valor7->nombre_cargo}}</td>
                        <td>{{$valor7->codigo_productoHerramienta}}</td>
                        <td>{{$valor7->nombre_productoHerramienta}} {{$valor7->descripcion_productoHerramienta}}</td>
                        <td>{{$valor7->suma_productos}}</td>
                    </tr>
                @endforeach
            @endif

            @if($datos["id_categoria"] == 3)
                @foreach($empleados_equiposCargos as $key => $valor3)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$valor3->nombres_empleado}} {{$valor3->apellidos_empleado}}</td>
                        <td>{{$valor3->nombre_cargo}}</td>
                        <td>{{$valor3->codigo_productoEPP}}</td>
                        <td>{{$valor3->nombre_productoEPP}} {{$valor3->descripcion_productoEPP}}</td>
                        <td>{{$valor3->suma_productos}}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

</br></br></br>

    <table class="table table-bordered table-sm" id="tabla_cronogramaGeneral" style="text-align:center;margin-top:20px;">
        <thead class="thead-light">
            <tr style="background:#D6EAF8">
                <th colspan="4">Detalles del Total de Productos</th>
            </tr>

            <tr style="background:#D6EAF8">
                <th>#</th>
                <th>Nombre del Producto</th>
                <th>Código del Producto</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
        <tbody>
            @if($datos["id_categoria"] == 1)
                @foreach($empleados_equiposCargosLimpieza1 as $key => $valor6)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$valor6->nombre_productoLimpieza}} {{$valor6->descripcion_productoLimpieza}}</td>
                        <td>{{$valor6->codigo_productoLimpieza}}</td>
                        <td>{{$valor6->suma_productos}}</td>
                    </tr>
                @endforeach
            @endif

            @if($datos["id_categoria"] == 2)
                @foreach($empleados_equiposCargosHerramienta1 as $key => $valor5)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$valor5->nombre_productoHerramienta}} {{$valor5->descripcion_productoHerramienta}}</td>
                        <td>{{$valor5->codigo_productoHerramienta}}</td>
                        <td>{{$valor5->suma_productos}}</td>
                    </tr>
                @endforeach
            @endif

            @if($datos["id_categoria"] == 3)
                @foreach($empleados_equiposCargos1 as $key => $valor4)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$valor4->nombre_productoEPP}} {{$valor4->descripcion_productoEPP}}</td>
                        <td>{{$valor4->codigo_productoEPP}}</td>
                        <td>{{$valor4->suma_productos}}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>

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
    margin-left: -20em;
    margin-right: -20em;
    margin-top: 2em;
    margin-bottom: 22em;
    }

    .rotate1 div {
    -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
    -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
    -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
    filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
    -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
    margin-left: -20em;
    margin-right: -20em;
    margin-top: -18em;
    margin-bottom: 10em;
    }

</style>
</html>