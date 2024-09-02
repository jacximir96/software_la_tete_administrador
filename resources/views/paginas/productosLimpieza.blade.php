@foreach ($administradores as $element)
            @if ($_COOKIE["email_login"] == $element->email)

@extends('plantilla')

@section('content')

<div class="content-wrapper">
    <!-- Inicio Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6" style="display: inline-block;">
            <h1>Productos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
              <li class="breadcrumb-item active">Productos</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Fin Content Header (Page header)  -->

    <!-- Inicio Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Default box -->
            <div class="card">
              <div class="card-header">
                @can('crear_productosLimpieza')
                <button class="btn btn-success btn-sm boton-general" data-toggle="modal" data-target="#crearProductoLimpieza">
                    <i class="fas fa-plus-circle"></i> Agregar nuevo producto
                </button>
                @endcan

                @can('reportesEXCEL_productosLimpieza')
                <a href="{{ url('/') }}/reportesProductosLimpieza/productosLimpiezaEXCEL" target="_blank" class="btn btn-warning btn-sm boton-general" style="float:right; margin-left: 5px;">
                    Exportar a EXCEL
                </a>
                @endcan

                <div class="btn-group ancho-100" role="group" aria-label="Button group with nested dropdown" style="float:right; margin-left: 5px;">
                    <div class="btn-group ancho-100" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-info btn-sm boton-general dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                            <i class="fas fa-print" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Imprimir Reporte
                        </button>

                        <div class="dropdown-menu dropdown-menu-right ancho-100" aria-labelledby="btnGroupDrop1">
                            @can('reportesActivosPDF_productosLimpieza')
                            <a href="{{ url('/') }}/reportesProductosLimpieza/productosLimpiezaPDF" target="_blank" class="dropdown-item boton-general" style="float:right;">
                                <i class="fas fa-file" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Productos Activos
                            </a>
                            @endcan

                            @can('reportesAgotadosPDF_productosLimpieza')
                            <a href="{{ url('/') }}/reportesProductosLimpiezaAgotados/productosLimpiezaAgotadosPDF" target="_blank" class="dropdown-item boton-general" style="float:right;">
                                <i class="fas fa-file" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Productos Agotados
                            </a>
                            @endcan
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
                            <th>Imagen</th>
                            <th>Producto</th>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Categoria</th>
                            <th>Stock</th>
                            <th>Precio</th>
                            <th>Unidad de Medida</th>
                            <th>Insumos</th>
                            <th>Agregado</th>
                            <th>Actualizado</th>
                            <th>Historial</th>
                            <th>Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                    @foreach($productosLimpieza as $key => $valor_productosLimpieza)
                        <tr>
                            <td style="text-align: center;">{{($key+1)}}</td>
                            <td style="text-align: center; text-transform: uppercase;">
                                <a href="{{$valor_productosLimpieza->imagen_productoLimpieza}}" target="_blank">
                                    <img src="{{$valor_productosLimpieza->imagen_productoLimpieza}}" style="width:60px; height:60px;" class="img-fluid"></img>
                                </a>
                            </td>
                            <td style="text-align: center; text-transform: uppercase;">{{$valor_productosLimpieza->nombre_productoLimpieza}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$valor_productosLimpieza->codigo_productoLimpieza}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$valor_productosLimpieza->descripcion_productoLimpieza}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$valor_productosLimpieza->nombre_categoria}}</td>
                            <td style="text-align: center; text-transform: uppercase;">
                                @if($valor_productosLimpieza->stock_productoLimpieza <= 10)
                                    <button class="btn btn-danger">
                                        {{$valor_productosLimpieza->stock_productoLimpieza}}
                                    </button>
                                @elseif($valor_productosLimpieza->stock_productoLimpieza >= 10 AND $valor_productosLimpieza->stock_productoLimpieza <=20)
                                    <button class="btn btn-warning">
                                        {{$valor_productosLimpieza->stock_productoLimpieza}}
                                    </button>
                                @else
                                    <button class="btn btn-success">
                                        {{$valor_productosLimpieza->stock_productoLimpieza}}
                                    </button>
                                @endif
                            </td>
                            <td style="text-align: center; text-transform: uppercase;">S/ {{$valor_productosLimpieza->precio_unitario}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$valor_productosLimpieza->nombre_unidadMedida}}</td>
                            <td style="text-align: center; text-transform: uppercase; width: auto; min-width: 200px;">
                                <ul style="list-style-type: disc; padding-left: 20px; margin-top: 0; margin-bottom: 0;">
                                    @foreach(explode(',', $valor_productosLimpieza->insumos_asociados) as $insumo)
                                        @if(trim($insumo) !== '') <!-- Verifica que el insumo no esté vacío -->
                                            <li style="font-weight: bold; margin-bottom: 5px;">{{ $insumo }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>
                            <td style="text-align: center; text-transform: uppercase;">{{ \Carbon\Carbon::parse($valor_productosLimpieza->created_at)->format('d-m-Y H:i:s')}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{ \Carbon\Carbon::parse($valor_productosLimpieza->created_at)->format('d-m-Y H:i:s')}}</td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    <a href="{{ url('/') }}/reportesHistorialLimpieza/productosLimpiezaPDF/{{$valor_productosLimpieza->id_productoLimpieza}}" target="_blank" class="btn btn-success btn-sm" style="float:right;">
                                        Historial
                                    </a>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    @can('editar_productosLimpieza')
                                    <a href="{{url('/')}}/productosLimpieza/{{$valor_productosLimpieza->id_productoLimpieza}}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-pencil-alt text-white"></i>
                                    </a>
                                    @endcan

                                    @can('eliminar_productosLimpieza')
                                    <button class="btn btn-danger btn-sm eliminarRegistro" action="{{url('/')}}/productosLimpieza/{{$valor_productosLimpieza->id_productoLimpieza}}"
                                        method="DELETE" pagina="productosLimpieza" token="{{ csrf_token() }}">
                                        <i class="fas fa-trash-alt text-white"></i>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

              </div>
              <!-- /.card-body -->

              <!-- /.card-footer-->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- Fin Main content -->
</div>

<div class="modal fade" id="crearProductoLimpieza">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ url('/') }}/productosLimpieza" enctype="multipart/form-data">
                @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;">Crear producto</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="input-group mb-3 usuarios_general" >
                        <div class="input-group-append input-group-text" style="border-radius:0px;">
                            <i class="fas fa-angle-double-right"></i>
                        </div>

                        @foreach($administradores as $element)
                            @if ($_COOKIE["email_login"] == $element->email)
                                <input type="text" class="form-control" name="usuario_productoLimpieza"
                                value="{{ old('id',$element->id) }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                        @endforeach
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Código:</label>

                        <div class="col-md-9">
                        @foreach($codificacion_productoLimpieza as $key => $value)
                            @if($value->codificacion_productoLimpieza < 10 AND $value->codificacion_productoLimpieza > 0)
                                <input type="text" class="form-control" name="codigo_productoLimpieza"
                                value="000000{{$value->codificacion_productoLimpieza}}" required autofocus
                                placeholder="Ingresar código" style="text-transform: uppercase;" readonly="true">
                            @endif

                            @if($value->codificacion_productoLimpieza >= 10 AND $value->codificacion_productoLimpieza <= 99)
                                <input type="text" class="form-control" name="codigo_productoLimpieza"
                                value="00000{{$value->codificacion_productoLimpieza}}" required autofocus
                                placeholder="Ingresar código" style="text-transform: uppercase;" readonly="true">
                            @endif

                            @if($value->codificacion_productoLimpieza >= 100 AND $value->codificacion_productoLimpieza <= 999)
                                <input type="text" class="form-control" name="codigo_productoLimpieza"
                                value="0000{{$value->codificacion_productoLimpieza}}" required autofocus
                                placeholder="Ingresar código" style="text-transform: uppercase;" readonly="true">
                            @endif

                            @if($value->codificacion_productoLimpieza >= 1000 AND $value->codificacion_productoLimpieza <= 9999)
                                <input type="text" class="form-control" name="codigo_productoLimpieza"
                                value="000{{$value->codificacion_productoLimpieza}}" required autofocus
                                placeholder="Ingresar código" style="text-transform: uppercase;" readonly="true">
                            @endif

                            @if($value->codificacion_productoLimpieza >= 10000 AND $value->codificacion_productoLimpieza <= 99999)
                                <input type="text" class="form-control" name="codigo_productoLimpieza"
                                value="00{{$value->codificacion_productoLimpieza}}" required autofocus
                                placeholder="Ingresar código" style="text-transform: uppercase;" readonly="true">
                            @endif

                            @if($value->codificacion_productoLimpieza >= 100000 AND $value->codificacion_productoLimpieza <= 999999)
                                <input type="text" class="form-control" name="codigo_productoLimpieza"
                                value="0{{$value->codificacion_productoLimpieza}}" required autofocus
                                placeholder="Ingresar código" style="text-transform: uppercase;" readonly="true">
                            @endif

                            @if($value->codificacion_productoLimpieza >= 1000000 AND $value->codificacion_productoLimpieza <= 9999999)
                                <input type="text" class="form-control" name="codigo_productoLimpieza"
                                value="{{$value->codificacion_productoLimpieza}}" required autofocus
                                placeholder="Ingresar código" style="text-transform: uppercase;" readonly="true">
                            @endif
                        @endforeach
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Categoría:</label>

                        <div class="col-md-9">
                            <select class="form-control select2" name="categoria_productoLimpieza" required>
                                <option value="">
                                        -- Seleccionar la categoría --
                                </option>
                                @foreach ($categorias as $key => $value)
                                    <option value="{{old('id_categoria',$value->id_categoria)}}">
                                        {{old('nombre_categoria',$value->nombre_categoria)}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Nombre:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="nombre_productoLimpieza"
                            value="{{ old("nombre_productoLimpieza") }}" required autofocus
                            placeholder="Ingresar producto" style="text-transform: uppercase;">
                        </div>

                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Descripción:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="descripcion_productoLimpieza"
                            value="{{ old("descripcion_productoLimpieza") }}" required autofocus
                            placeholder="Ingresar descripción" style="text-transform: uppercase;">
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Stock:</label>

                        <div class="col-md-9">
                            <input type="number" class="form-control" name="stock_productoLimpieza"
                            value="{{ old("stock_productoLimpieza") }}" required autofocus
                            placeholder="Stock" style="text-transform: uppercase;" min="0">
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Precio:</label>

                        <div class="col-md-9">
                            <input type="number" class="form-control" name="precio_productoLimpieza"
                            value="{{ old("precio_productoLimpieza") }}" required autofocus
                            placeholder="Precio" style="text-transform: uppercase;" min="0">
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">U. de Medida:</label>

                        <div class="col-md-9">
                            <select class="form-control select2" name="unidadMedida_productoLimpieza" required>

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

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Insumos:</label>

                        <div class="col-md-9">
                            <select class="form-control select2" id="insumos" name="insumos[]" multiple="multiple">
                                @foreach($insumos as $insumo)
                                    <option value="{{ $insumo->IDInsumo }}">{{ $insumo->nombre_insumo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr class="pb-2">

                    <div class="form-group my-2 text-center">
                        <div class="btn btn-default btn-file">
                            <i class="fas fa-paperclip" aria-hidden="true"></i> Adjuntar Foto
                            <input type="file" name="foto" id="imagen_productoLimpieza">
                        </div><br>

                        <img src="" class="previsualizarImg_foto
                                img-fluid py-2 w-25">

                        <input type="hidden" name="imagen_actual" value="">
                        <p class="help-block small">Dimensiones: 200px * 200px | Peso Max. 2MB |
                                                    Formato: JPG o PNG</p>
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

{{-- Editar CATEGORIA en modal --}}

@if (isset($status))

@if ($status == 200)

    @foreach ($productoLimpieza as $key => $valor_productoLimpieza)

    <div class="modal" id="editarProductoLimpieza">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ url('/') }}/productosLimpieza/{{$valor_productoLimpieza["id_productoLimpieza"]}}"
                enctype="multipart/form-data">

                @method('PUT')
                @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;">Editar Producto</h4>
                    <a href="{{ url("/") }}/productosLimpieza" type="button" class="close">&times;</a>
                </div>
                <div class="modal-body">
                    <!-- INICIO USUARIO DE PRODUCTO DE LIMPIEZA -->
                    <div class="input-group mb-3 usuarios_general">
                        <label for="email" class="col-md-3 control-label">Usuario:</label>

                        <div class="col-md-9">
                        @foreach($administradores as $element)
                            @if ($_COOKIE["email_login"] == $element->email)
                                <input type="text" class="form-control" name="usuario_productoLimpieza"
                                value="{{ $element->id }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                        @endforeach
                        </div>
                    </div><!-- FIN USUARIO DE PRODUCTO DE LIMPIEZA -->

                    <!-- INICIO NOMBRE DE PRODUCTO DE LIMPIEZA -->
                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Nombre:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="nombre_productoLimpieza"
                            value="{{old('nombre_productoLimpieza',$valor_productoLimpieza["nombre_productoLimpieza"])}}" required autofocus
                            style="text-transform: uppercase;">
                        </div>
                    </div><!-- FIN NOMBRE DE PRODUCTO DE LIMPIEZA -->

                    <!-- INICIO CODIGO DE PRODUCTO DE LIMPIEZA -->
                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Código:</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="codigo_productoLimpieza"
                            value="{{old('codigo_productoLimpieza',$valor_productoLimpieza["codigo_productoLimpieza"])}}" required autofocus
                            style="text-transform: uppercase;" readonly="">
                        </div>
                    </div><!-- FIN CODIGO DE PRODUCTO DE LIMPIEZA -->

                    <!-- INICIO DESCRIPCION DE PRODUCTO DE LIMPIEZA -->
                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Descripción:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="descripcion_productoLimpieza"
                            value="{{old('descripcion_productoLimpieza',$valor_productoLimpieza["descripcion_productoLimpieza"])}}" required autofocus
                            style="text-transform: uppercase;">
                        </div>
                    </div><!-- FIN DESCRIPCION DE PRODUCTO DE LIMPIEZA -->

                    <!-- INICIO CATEGORIA DE PRODUCTO DE LIMPIEZA -->
                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Categoría:</label>

                        <div class="col-md-9">
                            <select class="form-control" name="categoria_productoLimpieza" required readonly="">
                            @foreach ($productoLimpieza_categoria as $key => $value1)

                                <option value="{{$value1->id_categoria}}">
                                    {{$value1->nombre_categoria}}
                                </option>

                            @endforeach
                            </select>
                        </div>
                    </div><!-- FIN CATEGORIA DE PRODUCTO DE LIMPIEZA -->

                    <!-- INICIO STOCK DE PRODUCTO DE LIMPIEZA -->
                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Stock:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="stock_productoLimpieza"
                            value="{{old('stock_productoLimpieza',$valor_productoLimpieza["stock_productoLimpieza"])}}" required autofocus
                            style="text-transform: uppercase;" readonly="true">
                        </div>

                    </div><!-- FIN STOCK DE PRODUCTO DE LIMPIEZA -->

                    <!-- INICIO PRECIO DE PRODUCTO DE LIMPIEZA -->
                    <div class="input-group mb-3">
                        <label for="precio" class="col-md-3 control-label">Precio:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="precio_productoLimpieza"
                            value="{{old('precio_unitario',$valor_productoLimpieza["precio_unitario"])}}" required autofocus
                            style="text-transform: uppercase;">
                        </div>

                    </div><!-- FIN PRECIO DE PRODUCTO DE LIMPIEZA -->

                    <!-- INICIO UNIDADES DE MEDIDA DE PRODUCTO DE LIMPIEZA -->
                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">U. de medida:</label>

                        <div class="col-md-9">
                            <select class="form-control select2" name="unidadMedida_productoLimpieza" required>
                            @foreach ($productoLimpieza_unidadMedida as $key => $value1)

                                    <option value="{{$value1->id_unidadMedida}}">
                                        {{$value1->nombre_unidadMedida}}
                                    </option>

                                    @foreach ($unidadesMedida as $key => $value2)

                                        @if ($value2->id_unidadMedida != $value1->id_unidadMedida)
                                            <option value="{{old('id_unidadMedida',$value2->id_unidadMedida)}}">
                                                {{old('nombre_unidadMedida',$value2->nombre_unidadMedida)}}
                                            </option>
                                        @endif{{-- Aparece todo menos el que es diferente --}}

                                    @endforeach

                                @endforeach
                            </select>
                        </div>
                    </div><!-- FIN UNIDADES DE MEDIDA DE PRODUCTO DE LIMPIEZA -->

                    <div class="input-group mb-3">
                        <label for="insumos" class="col-md-3 control-label">Insumos:</label>

                        <div class="col-md-9">
                            <select class="form-control select2" id="insumos" name="insumos[]" multiple="multiple">
                                @foreach($insumos as $insumo)
                                    <option value="{{ $insumo->IDInsumo }}" 
                                        @if(in_array($insumo->IDInsumo, explode(',', $valor_productoLimpieza->insumos_asociados))) selected @endif>
                                        {{ $insumo->nombre_insumo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- INICIO IMAGEN DE PRODUCTO DE LIMPIEZA -->
                    <hr class="pb-2">

                    <div class="form-group my-2 text-center">
                        <div class="btn btn-default btn-file">
                                <i class="fas fa-paperclip"></i> Adjuntar Foto
                                <input type="file" name="foto" id="imagen_productoLimpieza_editar">
                        </div><br>

                        @if($valor_productoLimpieza->imagen_productoLimpieza == "")
                            <img src="{{ url('/') }}/img/productosLimpieza/sinImagen.jpg" class="previsualizarImg_foto
                            img-fluid py-2 w-25">
                        @else
                            <img src="{{ url('/') }}/{{$valor_productoLimpieza->imagen_productoLimpieza}}" class="previsualizarImg_foto
                            img-fluid py-2 w-25">
                        @endif

                        <input type="hidden" name="imagen_actual" value="{{$valor_productoLimpieza->imagen_productoLimpieza}}">
                        <p class="help-block small">Dimensiones: 200px * 200px | Peso Max. 2MB |
                        Formato: JPG o PNG</p>
                    </div><!-- FIN IMAGEN DE PRODUCTO DE LIMPIEZA -->

                </div>

                <div class="modal-footer d-flex">
                    <div>
                        <a href="{{ url("/") }}/productosLimpieza" type="button" class="btn btn-default boton-general">Cancelar</a>
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
        $("#editarProductoLimpieza").modal();
    </script>

  @else

  {{$status}}

@endif

@endif

@if (Session::has("ok-crear"))
  <script>
      notie.alert({type:1,text:'!El Producto ha sido creado correctamente', time:10})
  </script>
@endif

@if (Session::has("codigo-existe"))
  <script>
      notie.alert({type:2,text:'!El Producto ya existe en nuestros registros', time:10})
  </script>
@endif

@if (Session::has("no-validacion"))
  <script>
      notie.alert({type:2,text:'!Hay campos no válidos en el formulario', time:10})
  </script>
@endif

@if (Session::has("error"))
  <script>
      notie.alert({type:3,text:'!Error en el gestor de productos', time:10})
  </script>
@endif

@if (Session::has("ok-editar"))
  <script>
      notie.alert({type:1,text:'!El Producto ha sido actualizado correctamente', time:10})
  </script>
@endif

@if (Session::has("ok-eliminar"))

<script>
    notie.alert({ type: 1, text: '¡El Producto ha sido eliminado correctamente!', time: 10 })
</script>

@endif

@if (Session::has("no-borrar"))

<script>
    notie.alert({ type: 2, text: '¡Este administrador no se puede borrar!', time: 10 })
</script>

@endif

@endsection

@endif

@endforeach
