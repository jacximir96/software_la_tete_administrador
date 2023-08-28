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
            <h1>Roles y Permisos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
              <li class="breadcrumb-item active">Roles y Permisos</li>
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
                @can('crear_roles')
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#crearRol">
                    Crear nuevo rol
                </button>
                @endcan
              </div>
              <div class="card-body">

                <table class="table table-bordered table-striped dt-responsive" width="100%"
                 id="tablaRoles">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Permisos</th>
                            <th>Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                    @foreach ($roles as $key => $data)
                        <tr>
                            <td style="text-align: center;">{{($key+1)}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$data->name}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$data->nombre_estado}}</td>
                            <td>
                                @foreach($role_has_permissions as $key1 => $value_role_has_permissions)
                                    @if($value_role_has_permissions->role_id == $data->id)
                                        {{$value_role_has_permissions->name}},
                                    @endif
                                @endforeach

                                @can('permisos_roles')
                                <a  style="float:right;" href="{{url('/')}}/permisos/{{$data->id}}" class="btn btn-success btn-sm">
                                    <i class="fas fa-lock text-white"></i> Permisos
                                </a>
                                @endcan
                            </td>
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    @can('editar_roles')
                                    <a href="{{url('/')}}/roles/{{$data->id}}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-pencil-alt text-white"></i>
                                    </a>
                                    @endcan

                                    @can('eliminar_roles')
                                    <button class="btn btn-danger btn-sm eliminarRegistro" action="{{url('/')}}/roles/{{$data->id}}"
                                        method="DELETE" pagina="roles" token="{{ csrf_token() }}">
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
    <!-- /.content -->
</div>

<div class="modal" id="crearRol">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ url('/') }}/roles">
                @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;">Crear Rol</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="input-group mb-3">
                        <div class="input-group-append input-group-text">
                            <i class="fas fa-angle-double-right"></i>
                        </div>

                        <input type="text" class="form-control" name="nombre_rol"
                        value="{{ old("name_rol") }}" required autofocus
                        placeholder="Ingrese el Rol" style="text-transform: uppercase;">
                    </div>{{-- fin nombre de rol --}}

                    <div class="input-group mb-3">
                        <div class="input-group-append input-group-text">
                            <i class="fas fa-angle-double-right"></i>
                        </div>

                        <select class="form-control" name="estado_rol" required>
                            <option value="">
                                -- Seleccionar el Estado (Activo o Inactivo) --
                            </option>
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
                        </select>
                    </div>{{-- fin estado de departamento --}}
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

{{-- Editar departamento en modal --}}

@if (isset($status))

@if ($status == 200)

    @foreach ($rol as $key => $value)

    <div class="modal" id="editarRol">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ url('/') }}/roles/{{ $value["id"] }}"
                enctype="multipart/form-data">

                @method('PUT')
                @csrf

                <div class="modal-header border_titulo">
                    <h4 class="modal-title" style="color:white;">Editar Rol</h4>
                    <a href="{{ url("/") }}/roles" type="button" class="close">&times;</a>
                </div>
                <div class="modal-body">
                    {{-- Nombre --}}
                    <div class="input-group mb-3">
                        <div class="input-group-append input-group-text">
                            <i class="fas fa-angle-double-right"></i>
                        </div>

                        <input type="text" class="form-control" name="nombre_rol"
                        value="{{$value["name"]}}" required autofocus
                        placeholder="Ingrese el Rol" style="text-transform: uppercase;">
                    </div>{{-- fin nombre de rol --}}

                    <div class="input-group mb-3">
                        <div class="input-group-append input-group-text">
                            <i class="fas fa-angle-double-right"></i>
                        </div>

                        <select class="form-control" name="estado_rol" required>
                                @if ($value["estado_rol"] == 1)
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                @else
                                    <option value="2">Inactivo</option>
                                    <option value="1">Activo</option>
                                @endif
                            </option>
                            {{-- <option value="2">Inactivo</option> --}}
                        </select>
                    </div>{{-- fin estado del rol --}}
                </div>

                <div class="modal-footer d-flex">
                    <div>
                        <a href="{{ url("/") }}/roles" type="button" class="btn btn-default boton-general">Cancelar</a>
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
        $("#editarRol").modal();
    </script>

  @else

  {{$status}}

@endif

@endif



@if (Session::has("ok-crear"))
  <script>
      notie.alert({type:1,text:'!El Rol ha sido creado correctamente', time:10})
  </script>
@endif

@if (Session::has("no-validacion"))
  <script>
      notie.alert({type:2,text:'!Hay campos no válidos en el formulario', time:10})
  </script>
@endif

@if (Session::has("error"))
  <script>
      notie.alert({type:3,text:'!Error en el gestor de roles', time:10})
  </script>
@endif

@if (Session::has("ok-editar"))
  <script>
      notie.alert({type:1,text:'!El Rol ha sido actualizado correctamente', time:10})
  </script>
@endif

@if (Session::has("ok-eliminar"))

<script>
    notie.alert({ type: 1, text: '¡El Rol ha sido eliminado correctamente!', time: 10 })
</script>

@endif

@if (Session::has("no-borrar"))

<script>
    notie.alert({ type: 2, text: '¡Este administrador no se puede borrar!', time: 10 })
</script>

@endif

@endsection

@section('javascript')
    <script src="{{ url('/') }}/js/roles.js"></script>
@endsection

@endif

@endforeach
