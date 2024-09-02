@foreach ($administradores as $element)
@if ($_COOKIE["email_login"] == $element->email)

@extends('plantilla')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6" style="display: inline-block;">
                    <h1>Insumos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
                        <li class="breadcrumb-item active">Insumos</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-success btn-sm boton-general" data-toggle="modal" data-target="#crearInsumo">
                                <i class="fas fa-plus-circle"></i> Agregar nuevo insumo
                            </button>

                            <a href="{{ url('/') }}/reportesProductosLimpieza/productosLimpiezaEXCEL" target="_blank" class="btn btn-warning btn-sm boton-general" style="float:right; margin-left: 5px;">
                                Exportar a EXCEL
                            </a>

                            <div class="btn-group ancho-100" role="group" aria-label="Button group with nested dropdown" style="float:right; margin-left: 5px;">
                                <div class="btn-group ancho-100" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-info btn-sm boton-general dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-print" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Imprimir Reporte
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-right ancho-100" aria-labelledby="btnGroupDrop1">
                                        <a href="{{ url('/') }}/reportesProductosLimpieza/productosLimpiezaPDF" target="_blank" class="dropdown-item boton-general" style="float:right;">
                                            <i class="fas fa-file" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Insumos Activos
                                        </a>

                                        <a href="{{ url('/') }}/reportesProductosLimpiezaAgotados/productosLimpiezaAgotadosPDF" target="_blank" class="dropdown-item boton-general" style="float:right;">
                                            <i class="fas fa-file" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Insumos Agotados
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">

                            <table class="table table-bordered table-striped dt-responsive" width="100%"
                                id="tablaCategorias">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Stock</th>
                                        <th>Unidad de Medida</th>
                                        <th>Agregado</th>
                                        <th>Actualizado</th>
                                        <th>Historial</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($insumos as $key => $valor_insumos)
                                    <tr>
                                        <td style="text-align: center;">{{($key+1)}}</td>
                                        <td style="text-align: center; text-transform: uppercase;">{{$valor_insumos->codigo_insumo}}</td>
                                        <td style="text-align: center; text-transform: uppercase;">{{$valor_insumos->nombre_insumo}}</td>
                                        <td style="text-align: center; text-transform: uppercase;">{{$valor_insumos->descripcion_insumo}}</td>
                                        <td style="text-align: center; text-transform: uppercase;">
                                            @if($valor_insumos->stock_insumo <= 10)
                                                <button class="btn btn-danger">{{$valor_insumos->stock_insumo}}</button>
                                            @elseif($valor_insumos->stock_insumo >= 10 AND $valor_insumos->stock_insumo <=20)
                                                <button class="btn btn-warning">{{$valor_insumos->stock_insumo}}</button>
                                            @else
                                                <button class="btn btn-success">{{$valor_insumos->stock_insumo}}</button>
                                            @endif
                                        </td>
                                        <td style="text-align: center; text-transform: uppercase;">{{$valor_insumos->nombre_unidadMedida}}</td>
                                        <td style="text-align: center; text-transform: uppercase;">{{ \Carbon\Carbon::parse($valor_insumos->created_at)->format('d-m-Y H:i:s')}}</td>
                                        <td style="text-align: center; text-transform: uppercase;">{{ \Carbon\Carbon::parse($valor_insumos->created_at)->format('d-m-Y H:i:s')}}</td>
                                        <td style="text-align: center;">
                                            <div class="btn-group">
                                                <a href="{{ url('/') }}/reportesHistorialLimpieza/productosLimpiezaPDF/{{$valor_insumos->IDInsumo}}" target="_blank" class="btn btn-success btn-sm" style="float:right;">
                                                    Historial
                                                </a>
                                            </div>
                                        </td>
                                        <td style="text-align: center;">
                                            <div class="btn-group">
                                                <a href="{{url('/')}}/insumos/{{$valor_insumos->IDInsumo}}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-pencil-alt text-white"></i>
                                                </a>

                                                <button class="btn btn-danger btn-sm eliminarRegistro" action="{{url('/')}}/insumos/{{$valor_insumos->IDInsumo}}"
                                                    method="DELETE" pagina="insumos" token="{{ csrf_token() }}">
                                                    <i class="fas fa-trash-alt text-white"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="crearInsumo">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ url('/') }}/insumos" enctype="multipart/form-data">
                @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;">Crear insumo</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="input-group mb-3 usuarios_general">
                        <div class="input-group-append input-group-text" style="border-radius:0px;">
                            <i class="fas fa-angle-double-right"></i>
                        </div>

                        @foreach($administradores as $element)
                        @if ($_COOKIE["email_login"] == $element->email)
                        <input type="text" class="form-control" name="usuario_insumo"
                            value="{{ old('id',$element->id) }}" required autofocus
                            style="text-transform: uppercase;border-radius:0px;">
                        @endif
                        @endforeach
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Código:</label>

                        <div class="col-md-9">
                            @foreach($codificacion_insumo as $key => $value)
                                @if($value->codificacion_insumo < 10 AND $value->codificacion_insumo > 0)
                                    <input type="text" class="form-control" name="codigo_insumo"
                                    value="000000{{$value->codificacion_insumo}}" required autofocus
                                    placeholder="Ingresar código" style="text-transform: uppercase;" readonly="true">
                                @endif

                                @if($value->codificacion_insumo >= 10 AND $value->codificacion_insumo <= 99)
                                    <input type="text" class="form-control" name="codigo_insumo"
                                    value="00000{{$value->codificacion_insumo}}" required autofocus
                                    placeholder="Ingresar código" style="text-transform: uppercase;" readonly="true">
                                @endif

                                @if($value->codificacion_insumo >= 100 AND $value->codificacion_insumo <= 999)
                                    <input type="text" class="form-control" name="codigo_insumo"
                                    value="0000{{$value->codificacion_insumo}}" required autofocus
                                    placeholder="Ingresar código" style="text-transform: uppercase;" readonly="true">
                                @endif

                                @if($value->codificacion_insumo >= 1000 AND $value->codificacion_insumo <= 9999)
                                    <input type="text" class="form-control" name="codigo_insumo"
                                    value="000{{$value->codificacion_insumo}}" required autofocus
                                    placeholder="Ingresar código" style="text-transform: uppercase;" readonly="true">
                                @endif

                                @if($value->codificacion_insumo >= 10000 AND $value->codificacion_insumo <= 99999)
                                    <input type="text" class="form-control" name="codigo_insumo"
                                    value="00{{$value->codificacion_insumo}}" required autofocus
                                    placeholder="Ingresar código" style="text-transform: uppercase;" readonly="true">
                                @endif

                                @if($value->codificacion_insumo >= 100000 AND $value->codificacion_insumo <= 999999)
                                    <input type="text" class="form-control" name="codigo_insumo"
                                    value="0{{$value->codificacion_insumo}}" required autofocus
                                    placeholder="Ingresar código" style="text-transform: uppercase;" readonly="true">
                                @endif

                                @if($value->codificacion_insumo >= 1000000 AND $value->codificacion_insumo <= 9999999)
                                    <input type="text" class="form-control" name="codigo_insumo"
                                    value="{{$value->codificacion_insumo}}" required autofocus
                                    placeholder="Ingresar código" style="text-transform: uppercase;" readonly="true">
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Nombre:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="nombre_insumo"
                                value="{{ old("nombre_insumo") }}" required autofocus
                                placeholder="Ingresar nombre" style="text-transform: uppercase;">
                        </div>

                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Descripción:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="descripcion_insumo"
                                value="{{ old("descripcion_insumo") }}" required autofocus
                                placeholder="Ingresar descripción" style="text-transform: uppercase;">
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Stock:</label>

                        <div class="col-md-9">
                            <input type="number" class="form-control" name="stock_insumo"
                                value="{{ old("stock_insumo") }}" required autofocus
                                placeholder="Stock" style="text-transform: uppercase;" min="0">
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">U. de Medida:</label>

                        <div class="col-md-9">
                            <select class="form-control select2" name="unidadMedida_insumo" required>

                                <option value="">
                                    -- Seleccionar la unidad de Medida --
                                </option>

                                @foreach ($unidadesMedida as $key => $value)
                                <option value="{{old('id_unidadMedida',$value->id_unidadMedida)}}">
                                    {{old('nombre_unidadMedida',$value->nombre_unidadMedida)}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex">
                    <div>
                        <button type="button" class="btn btn-default boton-general" data-dismiss="modal">Cancelar</button>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-success boton-general">Guardar datos</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@if (isset($status))

@if ($status == 200)

@foreach ($insumo as $key => $valor_insumo)

<div class="modal" id="editarInsumo">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ url('/') }}/insumos/{{$valor_insumo["IDInsumo"]}}"
                enctype="multipart/form-data">

                @method('PUT')
                @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;">Editar insumo</h4>
                    <a href="{{ url("/") }}/insumos" type="button" class="close">&times;</a>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3 usuarios_general">
                        <label for="email" class="col-md-3 control-label">Usuario:</label>

                        <div class="col-md-9">
                            @foreach($administradores as $element)
                            @if ($_COOKIE["email_login"] == $element->email)
                            <input type="text" class="form-control" name="usuario_insumo"
                                value="{{ $element->id }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Código:</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="codigo_insumo"
                                value="{{old('codigo_insumo',$valor_insumo["codigo_insumo"])}}" required autofocus
                                style="text-transform: uppercase;" readonly="">
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Nombre:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="nombre_insumo"
                                value="{{old('nombre_insumo',$valor_insumo["nombre_insumo"])}}" required autofocus
                                style="text-transform: uppercase;">
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Descripción:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="descripcion_insumo"
                                value="{{old('descripcion_insumo',$valor_insumo["descripcion_insumo"])}}" required autofocus
                                style="text-transform: uppercase;">
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Stock:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="stock_insumo"
                                value="{{old('stock_insumo',$valor_insumo["stock_insumo"])}}" required autofocus
                                style="text-transform: uppercase;" readonly="true">
                        </div>

                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">U. de medida:</label>

                        <div class="col-md-9">
                            <select class="form-control select2" name="unidadMedida_insumo" required>
                                @foreach ($insumo_unidadMedida as $key => $value1)
                                    <option value="{{$value1->id_unidadMedida}}">
                                        {{$value1->nombre_unidadMedida}}
                                    </option>

                                    @foreach ($unidadesMedida as $key => $value2)
                                        @if ($value2->id_unidadMedida != $value1->id_unidadMedida)
                                            <option value="{{old('id_unidadMedida',$value2->id_unidadMedida)}}">
                                                {{old('nombre_unidadMedida',$value2->nombre_unidadMedida)}}
                                            </option>
                                        @endif
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex">
                    <div>
                        <a href="{{ url("/") }}/insumos" type="button" class="btn btn-default boton-general">Cancelar</a>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-success boton-general">Actualizar datos</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

@endforeach

<script>
    $("#editarInsumo").modal();
</script>

@else

{{$status}}

@endif

@endif

@if (Session::has("ok-crear"))
<script>
    notie.alert({
        type: 1,
        text: '!El insumo ha sido creado correctamente',
        time: 10
    })
</script>
@endif

@if (Session::has("codigo-existe"))
<script>
    notie.alert({
        type: 2,
        text: '!El insumo ya existe en nuestros registros',
        time: 10
    })
</script>
@endif

@if (Session::has("no-validacion"))
<script>
    notie.alert({
        type: 2,
        text: '!Hay campos no válidos en el formulario',
        time: 10
    })
</script>
@endif

@if (Session::has("error"))
<script>
    notie.alert({
        type: 3,
        text: '!Error en el gestor de insumos',
        time: 10
    })
</script>
@endif

@if (Session::has("ok-editar"))
<script>
    notie.alert({
        type: 1,
        text: '!El insumo ha sido actualizado correctamente',
        time: 10
    })
</script>
@endif

@if (Session::has("ok-eliminar"))

<script>
    notie.alert({
        type: 1,
        text: '¡El insumo ha sido eliminado correctamente!',
        time: 10
    })
</script>

@endif

@if (Session::has("no-borrar"))

<script>
    notie.alert({
        type: 2,
        text: '¡Este administrador no se puede borrar!',
        time: 10
    })
</script>

@endif

@endsection

@endif

@endforeach