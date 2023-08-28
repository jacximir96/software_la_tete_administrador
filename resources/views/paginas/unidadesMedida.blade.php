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
            <h1>Unidades de Medida</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
              <li class="breadcrumb-item active">Unidades de Medida</li>
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
                @can('crear_unidadesMedida')
                <button class="btn btn-success btn-sm boton-general" data-toggle="modal" data-target="#crearUnidadMedida">
                    <i class="fas fa-plus-circle"></i> Agregar nueva Unidad de Medida
                </button>
                @endcan

                @can('reportesEXCEL_unidadesMedida')
                <a href="{{ url('/') }}/reportesUnidadesMedida/unidadesMedidaEXCEL" target="_blank" class="btn btn-warning btn-sm boton-general" style="float:right; margin-left: 5px;">
                    Exportar a EXCEL
                </a>
                @endcan

                @can('reportesPDF_unidadesMedida')
                <a href="{{ url('/') }}/reportesUnidadesMedida/unidadesMedidaPDF" target="_blank" class="btn btn-danger btn-sm boton-general" style="float:right;">
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
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Agregado</th>
                            <th>Actualizado</th>
                            <th>Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                    @foreach($unidadesMedida as $key => $valor_unidadesMedida)
                        <tr>
                            <td style="text-align: center;">{{($key+1)}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$valor_unidadesMedida->nombre_unidadMedida}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$valor_unidadesMedida->nombre_estado}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{ \Carbon\Carbon::parse($valor_unidadesMedida->created_at)->format('d-m-Y H:i:s')}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{ \Carbon\Carbon::parse($valor_unidadesMedida->updated_at)->format('d-m-Y H:i:s')}}</td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    @can('editar_unidadesMedida')
                                    <a href="{{url('/')}}/unidadesMedida/{{$valor_unidadesMedida->id_unidadMedida}}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-pencil-alt text-white"></i>
                                    </a>
                                    @endcan

                                    @can('eliminar_unidadesMedida')
                                    <button class="btn btn-danger btn-sm eliminarRegistro" action="{{url('/')}}/unidadesMedida/{{$valor_unidadesMedida->id_unidadMedida}}"
                                        method="DELETE" pagina="unidadesMedida" token="{{ csrf_token() }}">
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
            <form method="POST" action="{{ url('/') }}/unidadesMedida">
                @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;">Crear unidad de medida</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="input-group mb-3 usuarios_general" >
                        <div class="input-group-append input-group-text" style="border-radius:0px;">
                            <i class="fas fa-angle-double-right"></i>
                        </div>

                        @foreach($administradores as $element)
                            @if ($_COOKIE["email_login"] == $element->email)
                                <input type="text" class="form-control" name="usuario_unidadMedida"
                                value="{{ $element->id }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                        @endforeach
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Nombre:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="nombre_unidadMedida"
                            value="{{ old("nombre_unidadMedida") }}" required autofocus
                            placeholder="Ingresar nombre" style="text-transform: uppercase;">
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Estado:</label>

                        <div class="col-md-9">
                            <select class="form-control" name="estado_unidadMedida" required>

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

{{-- Editar UNIDAD MEDIDA en modal --}}

@if (isset($status))

@if ($status == 200)

    @foreach ($unidadMedida as $key => $valor_unidadMedida)

    <div class="modal" id="editarUnidadMedida">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ url('/') }}/unidadesMedida/{{$valor_unidadMedida["id_unidadMedida"]}}"
                enctype="multipart/form-data">

                @method('PUT')
                @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;">Editar Unidad de Medida</h4>
                    <a href="{{ url("/") }}/unidadesMedida" type="button" class="close">&times;</a>
                </div>
                <div class="modal-body">
                    <!-- INICIO USUARIO DE UNIDAD DE MEDIDA -->
                    <div class="input-group mb-3 usuarios_general">
                        <label for="email" class="col-md-3 control-label">Usuario:</label>

                        <div class="col-md-9">
                        @foreach($administradores as $element)
                            @if ($_COOKIE["email_login"] == $element->email)
                                <input type="text" class="form-control" name="usuario_unidadMedida"
                                value="{{ $element->id }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                        @endforeach
                        </div>
                    </div><!-- FIN USUARIO DE UNIDAD DE MEDIDA -->

                    <!-- INICIO NOMBRE DE UNIDAD DE MEDIDA -->
                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Nombre:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="nombre_unidadMedida"
                            value="{{$valor_unidadMedida["nombre_unidadMedida"]}}" required autofocus
                            style="text-transform: uppercase;">
                        </div>
                    </div><!-- FIN NOMBRE DE UNIDAD DE MEDIDA -->

                    <!-- INICIO CATEGORIA DE PRODUCTO DE LIMPIEZA -->
                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Estado:</label>

                        <div class="col-md-9">
                            <select class="form-control" name="estado_unidadMedida" required>
                            @foreach ($unidadMedida_estado as $key => $value1)

                                    <option value="{{$value1->estado_unidadMedida}}">
                                        {{$value1->nombre_estado}}
                                    </option>

                                    @foreach ($estado as $key => $value2)

                                        @if ($value2->id_estado != $value1->estado_unidadMedida)
                                            <option value="{{$value2->id_estado}}">
                                                {{$value2->nombre_estado}}
                                            </option>
                                        @endif{{-- Aparece todo menos el que es diferente --}}

                                    @endforeach

                                @endforeach
                            </select>
                        </div>
                    </div><!-- FIN CATEGORIA DE PRODUCTO DE LIMPIEZA -->

                </div>

                <div class="modal-footer d-flex">
                    <div>
                        <a href="{{ url("/") }}/unidadesMedida" type="button" class="btn btn-default boton-general">Cancelar</a>
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
      notie.alert({type:1,text:'!La unidad de medida ha sido creado correctamente', time:10})
  </script>
@endif

@if (Session::has("codigo-existe"))
  <script>
      notie.alert({type:2,text:'!La unidad de medida ya existe en nuestros registros', time:10})
  </script>
@endif

@if (Session::has("no-validacion"))
  <script>
      notie.alert({type:2,text:'!Hay campos no válidos en el formulario', time:10})
  </script>
@endif

@if (Session::has("error"))
  <script>
      notie.alert({type:3,text:'!Error en el gestor de unidades de medida', time:10})
  </script>
@endif

@if (Session::has("ok-editar"))
  <script>
      notie.alert({type:1,text:'!La unidad de medida ha sido actualizado correctamente', time:10})
  </script>
@endif

@if (Session::has("ok-eliminar"))

<script>
    notie.alert({ type: 1, text: '¡La unidad de medida ha sido eliminado correctamente!', time: 10 })
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
