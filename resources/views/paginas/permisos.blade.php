@foreach ($administradores as $element)
            @if ($_COOKIE["email_login"] == $element->email)


@extends('plantilla')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Permisos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
              <li class="breadcrumb-item active">Permisos</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Default box -->
            <div class="card">
              <div class="card-header">
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#añadirPermiso">
                    Agregar Permiso
                </button>

                <a style="float:right;margin-left:5px;" href="{{ url("/roles") }}" class="btn btn-success btn-sm">
                    <i></i> Volver
                </a>

                @if (isset($status))
                  @if ($status == 200)

                  @foreach ($rol as $key => $data)

                <button class="btn btn-danger btn-sm eliminarRegistro" style="float:right;" action="{{url('/')}}/permisos/{{$data->id}}"
                  method="DELETE" pagina="permisos/{{$data->id}}" token="{{ csrf_token() }}" value="{{$data->id}}">
                    <i class="fas fa-trash-alt text-white"></i> Eliminar Todos
                </button>

                  @endforeach

                  @else

                  {{$status}}

                  @endif

                @endif
              </div>
              <div class="card-body">

                <table class="table table-bordered table-striped dt-responsive" id="tablaRoles" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Permisos</th>
                            <th>Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                    @if (isset($status))
                        @if ($status == 200)

                    @foreach ($role_has_permissions as $key => $data)
                        <tr>
                            <td style="text-align: center;">{{($key+1)}}</td>
                            <td style="text-align: center;">{{$data->name}}</td>

                            <td style="text-align: center;">
                                <div class="btn-group">
                                    <button class="btn btn-danger btn-sm eliminarRegistroPermisos" action="{{url('/')}}/permisos/{{$data->role_id}}/{{$data->permission_id}}"
                                        method="DELETE" pagina="permisos" token="{{ csrf_token() }}" value="{{$data->role_id}}">
                                        <i class="fas fa-trash-alt text-white"></i>
                                    </button>
                                </div>
                            </td>

                        </tr>
                    @endforeach

                    @else

                    {{$status}}

                    @endif

                    @endif
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
    <!-- /.content -->
  </div>

<div class="modal fade" id="añadirPermiso">
    <div class="modal-dialog">
        <div class="modal-content">
            @if (isset($status))
            @if ($status == 200)
            @foreach ($rol_unidad as $key => $data_rol)

            <form method="POST" action="{{url('/')}}/permisos/{{$data_rol->id}}">

            @endforeach
            @else
                {{$status}}
            @endif
            @endif

                @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;">Permisos</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                <!-- Nombre -->


                <div class="form-group">
							    <div class="row">
								    <div class="col-sm-12">
                      <label for="select_permisos">Permisos:</label>
                      <select class="form-select" style="width:100%;" multiple aria-label="multiple select example" name="select_permisos[]">

                        @if (isset($status))
                            @if ($status == 200)

                                @foreach ($permission as $key => $value_permission)

                                    <option value="{{$value_permission->id}}">{{$value_permission->name}}</option>

                                @endforeach
                            @else
                                {{$status}}

                            @endif
                        @endif

                      </select>
                    </div>
                  </div>
                </div>





                    <div class="input-group mb-3">

                        <div class="col-md-8">

                        @if (isset($status))
                        @if ($status == 200)

                        @foreach ($rol_unidad as $key => $data_rol)

                            <input type="text" class="form-control" name="role_id"
                            value="{{ $data_rol->id }}" autofocus
                            style="text-transform: uppercase;display:none;">

                        @endforeach
                        @else
                            {{$status}}
                        @endif
                        @endif

                        </div>
                    </div><!-- Fin nombre de dirección Ejecutiva -->
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

@if (Session::has("ok-crear"))
  <script>
      notie.alert({type:1,text:'!El permiso ha sido creado correctamente', time:10})
  </script>
@endif

@if (Session::has("no-validacion"))
  <script>
      notie.alert({type:2,text:'!Hay campos no válidos en el formulario', time:10})
  </script>
@endif

@if (Session::has("error"))
  <script>
      notie.alert({type:3,text:'!Error en el gestor de Permisos', time:10})
  </script>
@endif

@if (Session::has("ok-editar"))
  <script>
      notie.alert({type:1,text:'!El permiso ha sido actualizado correctamente', time:10})
  </script>
@endif

@if (Session::has("ok-eliminar"))

<script>
    notie.alert({ type: 1, text: '¡El permiso ha sido eliminado correctamente!', time: 10 })
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
