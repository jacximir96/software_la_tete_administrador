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
            <h1>Gastos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
              <li class="breadcrumb-item active">Gastos</li>
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
                @can('crear_gastos')
                <button class="btn btn-success btn-sm boton-general" data-toggle="modal" data-target="#crearUnidadMedida">
                    <i class="fas fa-plus-circle"></i> Agregar nuevo gasto
                </button>
                @endcan

                @can('reportesEXCEL_gastos')
                <a href="{{ url('/') }}/reportesGastos/gastosEXCEL" target="_blank" class="btn btn-warning btn-sm boton-general" style="float:right; margin-left: 5px;">
                    Exportar a EXCEL
                </a>
                @endcan

                @can('reportesPDF_gastos')
                <a href="{{ url('/') }}/reportesGastos/gastosPDF" target="_blank" class="btn btn-danger btn-sm boton-general" style="float:right;">
                    Exportar a PDF
                </a>
                @endcan
              </div>
              <div class="card-body">

                <table class="table table-bordered table-striped dt-responsive" width="100%"
                 id="tablaCategorias">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Precio Total</th>
                            <th>Unidad Medida</th>
                            <th>Agregado</th>
                            <th>Actualizado</th>
                            <th>Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                    @foreach($gastos as $key => $valor_gastos)
                        <tr>
                            <td style="text-align: center;">{{($key+1)}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$valor_gastos->descripcion_gasto}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$valor_gastos->cantidad_gasto}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$valor_gastos->precio_gasto}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$valor_gastos->preciot_gasto}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$valor_gastos->nombre_unidadMedida}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{ \Carbon\Carbon::parse($valor_gastos->created_at)->format('d-m-Y H:i:s')}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{ \Carbon\Carbon::parse($valor_gastos->updated_at)->format('d-m-Y H:i:s')}}</td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    @can('editar_gastos')
                                    <a href="{{url('/')}}/gastos/{{$valor_gastos->id_gasto}}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-pencil-alt text-white"></i>
                                    </a>
                                    @endcan

                                    @can('eliminar_gastos')
                                    <button class="btn btn-danger btn-sm eliminarRegistro" action="{{url('/')}}/gastos/{{$valor_gastos->id_gasto}}"
                                        method="DELETE" pagina="gastos" token="{{ csrf_token() }}">
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

<div class="modal fade" id="crearUnidadMedida">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ url('/') }}/gastos">
                @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;">Crear gasto</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="input-group mb-3 usuarios_general" >
                        <div class="input-group-append input-group-text" style="border-radius:0px;">
                            <i class="fas fa-angle-double-right"></i>
                        </div>

                        @foreach($administradores as $element)
                            @if ($_COOKIE["email_login"] == $element->email)
                                <input type="text" class="form-control" name="usuario_gasto"
                                value="{{ $element->id }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                        @endforeach
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Descripción:</label>

                        <div class="col-md-9">
                            <!-- <input type="text" class="form-control" name="descripcion_gasto"
                            value="{{ old("descripcion_gasto") }}" required autofocus
                            placeholder="Ingresar descripción" style="text-transform: uppercase;"> -->
                            <textarea class="form-control" name="descripcion_gasto"
                            required autofocus style="text-transform: uppercase;width:100%;height: 50px !important;"
                            rows="4"></textarea>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Cantidad:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="cantidad_gasto" id="cantidad_gasto_store"
                            value="{{ old("cantidad_gasto") }}" required autofocus
                            placeholder="Ingresar cantidad" style="text-transform: uppercase;">
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Precio unitario:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="precio_gasto" id="precio_gasto_store"
                            value="{{ old("precio_gasto") }}" required autofocus
                            placeholder="Ingresar precio" style="text-transform: uppercase;">
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Precio total:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="preciot_gasto" id="preciot_gasto_store"
                            value="{{ old("preciot_gasto") }}" required autofocus
                            style="text-transform: uppercase;" readonly="true">
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">U. de Medida:</label>

                        <div class="col-md-9">
                            <select class="form-control select2" name="unidadMedida_gasto" required>

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

<!--                     <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Estado:</label>

                        <div class="col-md-9">
                            <select class="form-control" name="estado_gasto" required>

                                <option value="">
                                    -- Seleccionar el Estado --
                                </option>

                                @foreach ($estado as $key => $value)
                                    <option value="{{$value->id_estado}}">
                                        {{$value->nombre_estado}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div> -->

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

{{-- Editar UNIDAD MEDIDA en modal --}}

@if (isset($status))

@if ($status == 200)

    @foreach ($gasto as $key => $valor_gasto)

    <div class="modal" id="editarUnidadMedida">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ url('/') }}/gastos/{{$valor_gasto["id_gasto"]}}"
                enctype="multipart/form-data">

                @method('PUT')
                @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;">Editar Gasto</h4>
                    <a href="{{ url("/") }}/gastos" type="button" class="close">&times;</a>
                </div>
                <div class="modal-body">
                    <!-- INICIO USUARIO DE GASTO -->
                    <div class="input-group mb-3 usuarios_general">
                        <label for="email" class="col-md-3 control-label">Usuario:</label>

                        <div class="col-md-9">
                        @foreach($administradores as $element)
                            @if ($_COOKIE["email_login"] == $element->email)
                                <input type="text" class="form-control" name="usuario_gasto"
                                value="{{ $element->id }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                        @endforeach
                        </div>
                    </div><!-- FIN USUARIO DE GASTO -->

                    <!-- INICIO NOMBRE DE GASTO -->
                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Descripción:</label>

                        <div class="col-md-9">
                            <!-- <input type="text" class="form-control" name="descripcion_gasto"
                            value="{{$valor_gasto["descripcion_gasto"]}}" required autofocus
                            style="text-transform: uppercase;"> -->
                            <textarea class="form-control" name="descripcion_gasto"
                            required autofocus style="text-transform: uppercase;width:100%;height: 50px !important;"
                            rows="4">{{$valor_gasto["descripcion_gasto"]}}</textarea>
                        </div>
                    </div><!-- FIN NOMBRE DE GASTO -->

                    <!-- INICIO CANTIDAD DE GASTO -->
                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Cantidad:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="cantidad_gasto" id="cantidad_gasto_update"
                            value="{{$valor_gasto["cantidad_gasto"]}}" required autofocus
                            style="text-transform: uppercase;">
                        </div>
                    </div><!-- FIN CANTIDAD DE GASTO -->

                    <!-- INICIO NOMBRE DE GASTO -->
                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Precio unitario:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="precio_gasto" id="precio_gasto_update"
                            value="{{$valor_gasto["precio_gasto"]}}" required autofocus
                            style="text-transform: uppercase;">
                        </div>
                    </div><!-- FIN NOMBRE DE GASTO -->

                    <!-- INICIO NOMBRE DE GASTO -->
                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Precio total:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="preciot_gasto" id="preciot_gasto_update"
                            value="{{$valor_gasto["preciot_gasto"]}}" required autofocus
                            style="text-transform: uppercase;" readonly="true">
                        </div>
                    </div><!-- FIN NOMBRE DE GASTO -->

                    <!-- INICIO UNIDADES DE MEDIDA DE GASTOS -->
                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">U. de medida:</label>

                        <div class="col-md-9">
                            <select class="form-control select2" name="unidadMedida_gasto" required>
                            @foreach ($gasto_unidadMedida as $key => $value1)

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
                    </div><!-- FIN UNIDADES DE MEDIDA DE GASTOS -->

                </div>

                <div class="modal-footer d-flex">
                    <div>
                        <a href="{{ url("/") }}/gastos" type="button" class="btn btn-default boton-general">Cancelar</a>
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
        $("#editarUnidadMedida").modal();
    </script>

  @else

  {{$status}}

@endif

@endif

@if (Session::has("ok-crear"))
  <script>
      notie.alert({type:1,text:'!El gasto ha sido creado correctamente', time:10})
  </script>
@endif

@if (Session::has("codigo-existe"))
  <script>
      notie.alert({type:2,text:'!El gasto ya existe en nuestros registros', time:10})
  </script>
@endif

@if (Session::has("no-validacion"))
  <script>
      notie.alert({type:2,text:'!Hay campos no válidos en el formulario', time:10})
  </script>
@endif

@if (Session::has("error"))
  <script>
      notie.alert({type:3,text:'!Error en el gestor de gastos', time:10})
  </script>
@endif

@if (Session::has("ok-editar"))
  <script>
      notie.alert({type:1,text:'!El gasto ha sido actualizado correctamente', time:10})
  </script>
@endif

@if (Session::has("ok-eliminar"))

<script>
    notie.alert({ type: 1, text: '¡El gasto ha sido eliminado correctamente!', time: 10 })
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
