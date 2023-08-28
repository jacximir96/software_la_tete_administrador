@foreach ($administradores as $element)
            @if ($_COOKIE["email_login"] == $element->email)

@extends('plantilla')

@section('content')

<div class="content-wrapper">
    <!--=====================================
        INICIO - SECTION (CONTENT-HEADER)
    ======================================-->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Soporte Técnico</h1>
                </div>
                
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
                        <li class="breadcrumb-item active">Soporte Técnico</li>
                    </ol>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <div class="input-group">
                        <label for="lista_idSoporteTecnico" class="col-md-2 control-label">Id:</label>
                        <div class="col-md-4" id="lista_idSoporteTecnico"><!-- Información desde Javascript (FUASMedica.js) --></div>
                        <label for="lista_fechaCreacionSoporteTecnico" class="col-md-2 control-label">Fecha Creado:</label>
                        <div class="col-md-4" id="lista_fechaCreacionSoporteTecnico"><!-- Información desde Javascript (FUASMedica.js) --></div>
                    </div>

                    <div class="input-group">
                        <label for="lista_prioridadSoporteTecnico" class="col-md-2 control-label">Prioridad:</label>
                        <div class="col-md-4" id="lista_prioridadSoporteTecnico"><!-- Información desde Javascript (FUASMedica.js) --></div>
                        <label for="lista_fechaResolucionSoporteTecnico" class="col-md-2 control-label">Fecha Resuelto:</label>
                        <div class="col-md-4" id="lista_fechaResolucionSoporteTecnico"><!-- Información desde Javascript (FUASMedica.js) --></div>
                    </div>

                    <form method="GET" action="{{ url('/') }}/soporteTecnico/buscarPorMes" id="frmFechas_SoporteTecnico">
                        @csrf
                        <div class="input-group">
                            <label for="email" class="col-md-2 control-label">Fecha de atención:</label>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="fechaInicio_SoporteTecnico" id="fechaInicio_SoporteTecnico"
                                style="text-transform: uppercase;" required>
                            </div>

                            <div class="col-md-2">
                                <input type="date" class="form-control" name="fechaFin_SoporteTecnico" id="fechaFin_SoporteTecnico"
                                style="text-transform: uppercase;" required>
                            </div>

                            <label for="email" class="col-md-6 control-label" style="text-align:center;color:white;background:red;">Busquedas directas</label>
                        </div>

                        <div class="input-group">
                            <div class="col-md-2"></div> 
                            <div class="col-md-4">  
                                <button style="width:100%;" id="btnGuardar" type="submit" class="btn btn-primary btn-sm"> 
                                <i class="fas fa-search"></i> Consultar</button>
                            </div>

<!--                             <label for="historiaBD_soporteTecnico" class="col-md-1 control-label" style="">N° Historia:</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control inputRutaNumero" name="historiaBD_fuasDigitados" id="historiaBD_fuasDigitados"
                                style="text-transform: uppercase;" maxlength="6">
                            </div> -->

<!--                             <label for="documentoBD_fuasDigitados" class="col-md-1 control-label">N° Documento:</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control inputRutaNumero" name="documentoBD_fuasDigitados" id="documentoBD_fuasDigitados"
                                style="text-transform: uppercase;" maxlength="9">
                            </div> -->
                            
<!--                             <label for="fuaBD_fuasDigitados" class="col-md-1 control-label">N° FUA:</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control inputRutaNumero" name="fuaBD_fuasDigitados" id="fuaBD_fuasDigitados"
                                style="text-transform: uppercase;" maxlength="8">
                            </div> -->
                        </div>
                    </form>
                </div>
            <div>
        </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
              </div>
              <div class="card-body">

                <table class="table table-bordered table-striped display nowrap" width="100%" id="tablaSoporteTecnico">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Descripción (Error)</th>
                            <th>Prioridad (1-5)</th>
                            <th>Fecha - Creado</th>
                            <th>Fecha - Resuelto</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>
                    </tbody>
                </table>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>

<!-- <div class="modal" id="crearRol">
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
  </div> -->

<!-- {{-- Editar departamento en modal --}}

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

@endif -->



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
    <script src="{{ url('/') }}/js/soportetecnico.js"></script>
@endsection

@endif

@endforeach