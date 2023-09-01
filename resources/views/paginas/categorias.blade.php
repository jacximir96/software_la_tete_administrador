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
            <h1>Categorías</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
              <li class="breadcrumb-item active">Categorías</li>
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
                @can('crear_categorias')
                <button class="btn btn-success btn-sm boton-general" data-toggle="modal" data-target="#crearCategoria">
                    <i class="fas fa-plus-circle"></i> Agregar nueva Categoría
                </button>
                @endcan

                @can('reportesEXCEL_categorias')
                <a href="{{ url('/') }}/reportesCategorias/categoriasEXCEL" target="_blank" class="btn btn-warning btn-sm boton-general" style="float:right; margin-left: 5px;">
                    Exportar a EXCEL
                </a>
                @endcan

                @can('reportesPDF_categorias')
                <a href="{{ url('/') }}/reportesCategorias/categoriasPDF" target="_blank" class="btn btn-danger btn-sm boton-general" style="float:right;">
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
                            <th>Categoría</th>
                            <th>Descripción</th>
                            <th>Agregado</th>
                            <th>Actualizado</th>
                            <th>Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($categorias as $key => $valor_categorias)
                        <tr>
                            <td style="text-align: center;">{{($key+1)}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$valor_categorias->nombre_categoria}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$valor_categorias->descripcion_categoria}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{ \Carbon\Carbon::parse($valor_categorias->created_at)->format('d-m-Y H:i:s')}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{ \Carbon\Carbon::parse($valor_categorias->updated_at)->format('d-m-Y H:i:s')}}</td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    @can('editar_categorias')
                                    <a href="{{url('/')}}/categorias/{{$valor_categorias->id_categoria}}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-pencil-alt text-white"></i>
                                    </a>
                                    @endcan

                                    @can('eliminar_categorias')
                                    <button class="btn btn-danger btn-sm eliminarRegistro" action="{{url('/')}}/categorias/{{$valor_categorias->id_categoria}}"
                                        method="DELETE" pagina="categorias" token="{{ csrf_token() }}">
                                        <!-- @csrf -->
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

<div class="modal fade" id="crearCategoria">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ url('/') }}/categorias">
                @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;">Crear categoría</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="input-group mb-3 usuarios_general" >
                        <div class="input-group-append input-group-text" style="border-radius:0px;">
                            <i class="fas fa-angle-double-right"></i>
                        </div>

                        @foreach($administradores as $element)
                            @if ($_COOKIE["email_login"] == $element->email)
                                <input type="text" class="form-control" name="usuario_categoria"
                                value="{{ $element->id }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                        @endforeach
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-append input-group-text" style="border-radius:0px;">
                            <i class="fas fa-angle-double-right"></i>
                        </div>

                        <input type="text" class="form-control" name="nombre_categoria"
                        value="{{ old("nombre_categoria") }}" required autofocus
                        placeholder="Ingresar nombre" style="text-transform: uppercase;border-radius:0px;">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-append input-group-text" style="border-radius:0px;">
                            <i class="fas fa-angle-double-right"></i>
                        </div>

                        <input type="text" class="form-control" name="descripcion_categoria"
                        value="{{ old("descripcion_categorias") }}" required autofocus
                        placeholder="Ingresar descripción" style="text-transform: uppercase;border-radius:0px;">
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

    @foreach ($categoria as $key => $valor_categoria)

    <div class="modal" id="editarCategoria">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ url('/') }}/categorias/{{$valor_categoria["id_categoria"]}}"
                enctype="multipart/form-data">

                @method('PUT')
                @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;">Editar Categoría</h4>
                    <a href="{{ url("/") }}/categorias" type="button" class="close">&times;</a>
                </div>
                <div class="modal-body">
                    <!-- INICIO USUARIO DE CATEGORÍA -->
                    <div class="input-group mb-3 usuarios_general">
                        <label for="email" class="col-md-3 control-label">Usuario:</label>

                        <div class="col-md-9">
                        @foreach($administradores as $element)
                            @if ($_COOKIE["email_login"] == $element->email)
                                <input type="text" class="form-control" name="usuario_categoria"
                                value="{{ $element->id }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                        @endforeach
                        </div>
                    </div><!-- FIN USUARIO DE CATEGORÍA -->

                    <!-- INICIO NOMBRE DE CATEGORÍA -->
                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Nombre:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="nombre_categoria"
                            value="{{$valor_categoria["nombre_categoria"]}}" required autofocus
                            style="text-transform: uppercase;">
                        </div>
                    </div><!-- FIN NOMBRE DE CATEGORÍA -->

                    <!-- INICIO DESCRIPCION DE CATEGORÍA -->
                    <div class="input-group mb-3">
                        <label for="email" class="col-md-3 control-label">Descripción:</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control" name="descripcion_categoria"
                            value="{{$valor_categoria["descripcion_categoria"]}}" required autofocus
                            style="text-transform: uppercase;">
                        </div>
                    </div><!-- FIN DESCRIPCION DE CATEGORÍA -->

                </div>

                <div class="modal-footer d-flex">
                    <div>
                        <a href="{{ url("/") }}/categorias" type="button" class="btn btn-default boton-general">Cancelar</a>
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
        $("#editarCategoria").modal();
    </script>

  @else

  {{$status}}

@endif

@endif

@if (Session::has("ok-crear"))
  <script>
      notie.alert({type:1,text:'!La categoría ha sido creado correctamente', time:10})
  </script>
@endif

@if (Session::has("codigo-existe"))
  <script>
      notie.alert({type:2,text:'!La categoría ya existe en nuestros registros', time:10})
  </script>
@endif

@if (Session::has("no-validacion"))
  <script>
      notie.alert({type:2,text:'!Hay campos no válidos en el formulario', time:10})
  </script>
@endif

@if (Session::has("error"))
  <script>
      notie.alert({type:3,text:'!Error en el gestor de categorías', time:10})
  </script>
@endif

@if (Session::has("ok-editar"))
  <script>
      notie.alert({type:1,text:'!La categoría ha sido actualizado correctamente', time:10})
  </script>
@endif

@if (Session::has("ok-eliminar"))

<script>
    notie.alert({ type: 1, text: '¡La categoría ha sido eliminado correctamente!', time: 10 })
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
