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
            <h1>Administradores</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
              <li class="breadcrumb-item active">Administradores</li>
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
                @can('crear_usuarios')
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#crearAdministrador">
                    Crear nuevo administrador
                </button>
                @endcan

              </div>
              <div class="card-body">

                <table class="table table-bordered table-striped dt-responsive" width="100%"
                 id="tablaAdministradores">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th width="50px">Foto</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                    @foreach ($administradores as $key => $value)

                            <tr>
                                <td>{{($key+1)}}</td>
                                <td>{{$value->name}}</td>
                                <td>{{$value->email}}</td>

                                @php
                                    if ($value->foto == "") {
                                        echo '<td><img src="'.url('/').'/img/administradores/administrador.png"
                                        class="img-fluid rounded-circle"></td>';
                                    }else{
                                        echo '<td><img src="'.url('/').'/'.$value->foto.'" class="img-fluid
                                        rounded-circle"></td>';
                                    }
                                @endphp

                                <td>{{$value->rol}}</td>
                                <td>
                                    <div class="btn-group">
                                        @can('editar_usuarios')
                                        <a href="{{url('/')}}/administradores/{{$value->id_usuario}}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-pencil-alt text-white"></i>
                                        </a>
                                        @endcan

                                        @can('eliminar_usuarios')
                                        <button class="btn btn-danger btn-sm eliminarRegistro" action="{{url('/')}}/administradores/{{$value->id_usuario}}"
                                        method="DELETE" pagina="administradores" token="{{ csrf_token() }}">
                                            <i class="fas fa-trash-alt"></i>
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

{{-- Crear Administrador en modal --}}
<div class="modal" id="crearAdministrador">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('register') }}">
                @csrf

            <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                <h4 class="modal-tittle" style="color:white;">Crear Administrador</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <div class="row">
						<div class="col-sm-12">
                            <label for="name">Nombre del Usuario</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid
                            @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                            placeholder="No debe contener signos especiales">
                            
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
						<div class="col-sm-12">
                            <label for="email">Correo Electrónico</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid
                            @enderror" name="email" value="{{ old('email') }}" required autocomplete="email"
                            placeholder="Debe contener el @">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>El email ya ha sido registrado.</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
						<div class="col-sm-12">
                            <label for="password">Contraseña</label>
                            <input id="password" type="password" class="form-control @error('password')
                            is-invalid @enderror" name="password" required autocomplete="new-password"
                            placeholder="Mínimo de 8 caracteres">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
						<div class="col-sm-12">
                            <label for="password-confirm">Confirmar Contraseña</label>
                            <input id="password-confirm" type="password" class="form-control"
                            name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
						<div class="col-sm-12">
                            <label for="id_rol">Rol</label>
                            <select class="form-control select-2 select2" name="id_rol" id="id_rol">
                                <option value="">-- Seleccionar el Rol --</option>
                                @foreach ($roles as $key => $value_roles)
                                    <option value="{{$value_roles->id}}" style="text-transform: uppercase;">{{$value_roles->name}}</option>
                                @endforeach
                            </select>
                        </div>
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

</div>{{-- Fin crear administrador --}}


{{-- Editar Administrador en modal --}}

@if (isset($status))

    @if ($status == 200)

        @foreach ($administrador as $key => $value)

        <div class="modal" id="editarAdministrador">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ url('/') }}/administradores/{{ $value["id"] }}"
                    enctype="multipart/form-data">

                    @method('PUT')
                    @csrf

                    <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                        <h4 class="modal-tittle" style="color:white;">Editar Administrador</h4>
                        <a href="{{ url("/") }}/administradores" type="button" class="close">&times;</a>
                    </div>
                    <div class="modal-body">
                        {{-- Nombre --}}
                        <div class="input-group mb-3">
                            <div class="input-group-append input-group-text">
                                <i class="fas fa-user"></i>
                            </div>

                            <input id="name" type="text" class="form-control @error('name') is-invalid
                            @enderror" name="name" value="{{ $value["name"] }}" required autocomplete="name" autofocus
                            placeholder="Nombre">

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="input-group mb-3">
                            <div class="input-group-append input-group-text">
                                <i class="fas fa-envelope"></i>
                            </div>

                            <input id="email" type="email" class="form-control @error('email') is-invalid
                            @enderror" name="email" value="{{ $value["email"] }}" required autocomplete="email"
                            placeholder="Correo Electrónico">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="input-group mb-3">
                            <div class="input-group-append input-group-text">
                                <i class="fas fa-key"></i>
                            </div>

                            <input id="password" type="password" class="form-control @error('password')
                            is-invalid @enderror" name="password" autocomplete="new-password"
                            placeholder="Contraseña, mínimo de 8 caracteres">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <input type="hidden" name="password_actual" value="{{$value["password"]}}">

                        {{-- Rol --}}
                        <div class="input-group mb-3">
                            <div class="input-group-append input-group-text">
                                <i class="fas fa-list-ul"></i>
                            </div>

                            <select class="form-control" name="id_rol" required>
                            @foreach ($administrador_rol as $key => $value1)

                                <option value="{{$value1->id_rol}}" style="text-transform: lowercase;">
                                    {{$value1->name}}
                                </option>

                                @foreach ($roles as $key => $value2)

                                    @if ($value2->id != $value1->id_rol)
                                        <option value="{{$value2->id}}" style="text-transform: uppercase;">
                                            {{$value2->name}}
                                        </option>
                                    @endif{{-- Aparece todo menos el que es diferente --}}

                                @endforeach

                            @endforeach
                            </select>
                        </div>

                        {{-- Foto --}}
                        <hr class="pb-2">
                            <div class="form-group my-2 text-center">
                                <div class="btn btn-default btn-file">
                                    <i class="fas fa-paperclip"></i> Adjuntar Foto
                                    <input type="file" name="foto" id="foto_administrador">
                                </div><br>

                                @if ($value["foto"] == "")
                                    <img src="{{url('/')}}/img/administradores/administrador.png"
                                    class="previsualizarImg_foto img-fluid py-2 w-25 rounded-circle">
                                @else
                                    <img src="{{url('/')}}/{{$value["foto"]}}" class="previsualizarImg_foto
                                    img-fluid py-2 w-25 rounded-circle">
                                @endif

                                <input type="hidden" name="imagen_actual" value="{{$value["foto"]}}">
                                <p class="help-block small">Dimensiones: 200px * 200px | Peso Max. 2MB |
                                    Formato: JPG o PNG</p>
                            </div>

                    </div>

                    <div class="modal-footer d-flex">
                        <div>
                            <a href="{{ url("/") }}/administradores" type="button" class="btn btn-default boton-general">Cancelar</a>
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
            $("#editarAdministrador").modal();
        </script>

      @else

      {{$status}}

    @endif

@endif

@if (Session::has("ok-crear"))
  <script>
      notie.alert({type:1,text:'!El usuario ha sido creado correctamente', time:10})
  </script>
@endif

@if (Session::has("no-validacion"))
    <script>
        notie.alert({type:2,text:'!Hay campos no válidos en el formulario', time:10})
    </script>
@endif

@if (Session::has("error"))
    <script>
        notie.alert({type:3,text:'!Error en el gestor de administradores', time:10})
    </script>
@endif

@if (Session::has("ok-editar"))
    <script>
        notie.alert({type:1,text:'!El administrador ha sido actualizado correctamente', time:10})
    </script>
@endif

@if (Session::has("ok-eliminar"))

  <script>
      notie.alert({ type: 1, text: '¡El administrador ha sido eliminado correctamente!', time: 10 })
 </script>

@endif

@if (Session::has("no-borrar"))

  <script>
      notie.alert({ type: 2, text: '¡Este administrador no se puede borrar!', time: 10 })
 </script>

@endif

@endsection

@section('javascript')
    <script src="{{ url('/') }}/js/administradores.js"></script>
@endsection

@endif

@endforeach
