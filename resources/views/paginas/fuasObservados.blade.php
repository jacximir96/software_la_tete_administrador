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
            <h1>FUAS Observados</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
              <li class="breadcrumb-item active">FUAS Observados</li>
            </ol>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-12">
            <div class="input-group">
              <label for="email" class="col-md-2 control-label">Unidad Orgánica:</label>

              <div class="col-md-4" id="lista_unidadOrganica2"><!-- Información desde Javascript (pacientesCitados.js) --></div>

              <label for="email" class="col-md-2 control-label">Grupo Profesional:</label>

              <div class="col-md-4" id="lista_grupoProfesional2"><!-- Información desde Javascript (pacientesCitados.js) --></div>
            </div>

            <div class="input-group">
              <label for="email" class="col-md-2 control-label">Profesional:</label>

              <div class="col-md-4" id="lista_profesionales2"><!-- Información desde Javascript (pacientesCitados.js) --></div>

              <label for="email" class="col-md-2 control-label">Paciente:</label>

              <div class="col-md-4" id="lista_pacientes2"><!-- Información desde Javascript (pacientesCitados.js) --></div>
            </div>

            <form method="GET" action="{{ url('/') }}/fuasObservados/buscarPorMes" id="frmFechas_fuasObservados">
              @csrf
                <div class="input-group">
                  <label for="email" class="col-md-2 control-label">Fecha de cita / Atención:</label>

                  <div class="col-md-2">
                    <input type="date" class="form-control" name="fechaInicio_fuasObservados" id="fechaInicio_fuasObservados"
                    style="text-transform: uppercase;" required>
                  </div>

                  <div class="col-md-2">
                    <input type="date" class="form-control" name="fechaFin_fuasObservados" id="fechaFin_fuasObservados"
                    style="text-transform: uppercase;" required>
                  </div>

                  <label for="email" class="col-md-6 control-label" style="text-align:center;color:white;background:red;">Busquedas directas</label>
                </div>

                <div class="input-group">
                    <div class="col-md-2"></div>
                    <div class="col-md-4">  
                        <button style="width:100%;font-weight:400;" id="btnGuardar" type="submit" class="btn btn-primary btn-sm"> 
							<i class="fas fa-search"></i> Consultar</button>
                    </div>

                    <label for="historiaBD_fuasObservados" class="col-md-1 control-label" style="">N° Historia:</label>
                    <div class="col-md-1">
                        <input type="text" class="form-control inputRutaNumero" name="historiaBD_fuasObservados" id="historiaBD_fuasObservados"
                        style="text-transform: uppercase;" maxlength="6">
                    </div>

                    <label for="documentoBD_fuasObservados" class="col-md-1 control-label">N° Documento:</label>
                    <div class="col-md-1">
                        <input type="text" class="form-control inputRutaNumero" name="documentoBD_fuasObservados" id="documentoBD_fuasObservados"
                        style="text-transform: uppercase;" maxlength="9">
                    </div>

                    <label for="fuaBD_fuasObservados" class="col-md-1 control-label">N° FUA:</label>
                    <div class="col-md-1">
                        <input type="text" class="form-control inputRutaNumero" name="fuaBD_fuasObservados" id="fuaBD_fuasObservados"
                        style="text-transform: uppercase;" maxlength="8">
                    </div>
                </div>
            </form>
          </div>
        <div>
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
                <button class="btn btn-warning btn-sm boton-general" data-toggle="modal" data-target="#verRecord" style="float:right; margin-left: 5px;display:none;">
                  <i class="fas fa-record-vinyl"></i> Record
                </button>
                
<!--                 <form method="POST" action="" id="frmVerFua_fuasObservados">
                  @csrf
                  <input type="text" class="form-control" name="idFua_fuasObservados" id="idFua_fuasObservados"
                  style="text-transform: uppercase;display:none;" required>

                  <button type="submit" class="btn btn-danger btn-sm boton-general" data-toggle="modal" data-target="#verFua_fuasObservados"  
                  style="float:left;display:none;margin-right: 5px;" id="btnVerFUA_fuasObservados"> 
				  <i class="fas fa-eye" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Ver FUA</button>
                </form> -->

                <div id="anular_anularFuaF">
                    <a href="" class="btn btn-danger btn-sm boton-general" id="anular_verFuaF" style="float:left;display:none;margin-right: 5px;">
                        <i class="fa fa-times" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Liberar N° de Fua
                    </a>
                </div>

                <form method="GET" action="" id="frmVerRolCitas_fuasObservados">
                  @csrf
                  <input type="text" class="form-control" name="idCab_fuasObservados" id="idCab_fuasObservados"
                  style="text-transform: uppercase;display:none;" required>

                  <button type="submit" class="btn btn-dark btn-sm boton-general" data-toggle="modal" data-target="#verRolCitas_fuasObservados"  
                  style="float:left;display:none;margin-right: 5px;" id="btnRolCitas_fuasObservados"> 
				  <i class="fa fa-tasks" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Rol de Citas</button>
                </form>
              </div>

              <div class="card-body">

                <table class="table table-bordered table-striped display nowrap" width="100%"
                 id="tablaFuasObservados">

                    <thead>
                        <tr style="background:white;" role="row">
                          <th rowspan="2">Financiador</th>
                          <th rowspan="2">Tipo Atención</th>
                          <th rowspan="2">Fecha Cita</th>
                          <th rowspan="2" style="display:none;">Fecha1</th>
                          <th rowspan="2" style="display:none;">Hora</th>
                          <th rowspan="2" style="width:350px;">Paciente</th>
                          <th colspan="2" style="text-align:center;">Documento</th>
                          <th rowspan="2">HC</th>
                          <th rowspan="2">FUA/CG</th>
                          <th rowspan="2">Actividad</th>
                          <th rowspan="2">Profesional</th>
                          <th rowspan="2">Estado Cita</th>
                          <th rowspan="2" style="display:none;">Unidad Orgánica</th>
                          <th rowspan="2" style="display:none;">Grupo Profesional</th>
                        </tr>

                        <tr role="row">
                            <th>Tipo</th>
                            <th style="border-right: 1px solid #dee2e6;">N°</th>
                        </tr>

                    </thead>

                    <tbody>

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

<div class="modal fade" id="verRolCitas_fuasObservados" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
            <h4 class="modal-tittle" style="color:white;"><i class="fa fa-pencil"></i> &nbsp; <span class="title-form">Rol de Citas</span></h4>
            <button type="button" id="botonCerrar_fuasObservados" class="close" data-dismiss="modal">&times;</button>
          </div>

          <div class="modal-body" id="modal-container">
            <table class="table table-bordered table-striped display nowrap" width="100%"
                   id="tablaRolCitas_fuasObservados">

              <thead>
                  <tr style="background:white;">
                    <th>N° SESIÓN</th>
                    <th>Fecha Programada</th>
                    <th>Estado</th>
                    <th>Estado Cita</th>
                    <th>Profesional</th>
                    <th>FUA</th>
                    <th>Notas</th>
                    <th style="display:none;">Fecha</th>
                    <th style="display:none;">Hora</th>
                  </tr>
              </thead>

              <tbody>

              </tbody>
            </table>
          </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="verFua_fuasObservados" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-content">
            <form method="POST" action="{{ url('/') }}/fuasObservados/actualizarFua" enctype="multipart/form-data" id="actualizarFuaF_fuasObservados"
            pagina="fuasObservados">
            @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;"><i class="fa fa-pencil"></i> &nbsp; <span class="title-form">FUA</span></h4>
                    <button type="button" id="botonCerrarVerFua_fuasObservados" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body" id="modal-container">
                    <div class="alert alert-info alert-styled-left text-blue-800 content-group">
				        <span class="text-semibold">Estimado usuario</span> 
				        Los campos remarcados con <span class="text-danger"> * </span> son necesarios.
				        <button type="button" class="close" data-dismiss="alert">×</button>
	                    <input type="hidden" id="txtProceso" name="txtProceso" class="form-control" value="Entrada">
				    </div>

                    <div class="input-group mb-3 usuarios_general">
                        <div class="input-group-append input-group-text" style="border-radius:0px;">
                            <i class="fas fa-angle-double-right"></i>
                        </div>

                        @foreach($administradores as $element)
                            @if ($_COOKIE["email_login"] == $element->email)
                                <input type="text" class="form-control" name="usuario_fuasObservados" id="usuario_fuasObservados"
                                value="{{ old('id',$element->id) }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                        @endforeach
                    </div>

                    <div class="form-group">
                      <div class="row" style="display:none;">
                        <div class="col-sm-12">
                          <label for="idFuaF_fuasObservados">Id del Fua</label>
                          <input type="text" class="form-control" name="idFuaF_fuasObservados" id="idFuaF_fuasObservados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-4">
                          <label for="disaF_fuasObservados">N° del formato</label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-1">
                        <input type="text" class="form-control" name="disaF_fuasObservados" id="disaF_fuasObservados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-1">
                        <input type="text" class="form-control" name="loteF_fuasObservados" id="loteF_fuasObservados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-2">
                        <input type="text" class="form-control" name="numeroF_fuasObservados" id="numeroF_fuasObservados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>
                      </div>
                    </div>

                    <hr>

                    <div class="form-group">
						<div class="row">
							<div class="col-sm-4">
								<label for="personalAtiendeF_fuasObservados">Personal que atiende <span class="text-danger"> * </span></label>
                                <select class="form-control select-2 select2" name="personalAtiendeF_fuasObservados" id="personalAtiendeF_fuasObservados">
                                    <option value="">-- Seleccionar el Personal --</option>
                                    @foreach($personalAtiende as $key => $value_personalAtiende)
                                    <option value="{{$value_personalAtiende->id}}">{{$value_personalAtiende->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
							    <label for="lugarAtencionF_fuasObservados">Lugar de Atención <span class="text-danger"> * </span></label>
                                <select class="form-control select-2 select2" name="lugarAtencionF_fuasObservados" id="lugarAtencionF_fuasObservados">
                                    <option value="">-- Seleccionar el Lugar de Atención --</option>
                                    @foreach($lugarAtencion as $key => $value_lugarAtencion)
                                    <option value="{{$value_lugarAtencion->id}}">{{$value_lugarAtencion->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
							    <label for="tipoAtencionF_fuasObservados">Tipo de Atención</label>
                                <select class="form-control select-2 select2" name="tipoAtencionF_fuasObservados" id="tipoAtencionF_fuasObservados">
                                    <option value="">-- Seleccionar el Tipo de Atención --</option>
                                    @foreach($tipoAtencion as $key => $value_tipoAtencion)
                                    <option value="{{$value_tipoAtencion->id}}">{{$value_tipoAtencion->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
						</div>
					</div>

                    <div class="form-group">
						<div class="row">
                            <div class="col-sm-8">
							    <label for="historiaClinica_fuasObservados">Referencia realizada por <span class="text-danger"> * </span></label>
                            </div>

                            <div class="col-sm-4">
							    <label for="historiaClinica_fuasObservados">N° Hoja de Referencia <span class="text-danger"> * </span></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="codigoReferenciaF_fuasObservados" id="codigoReferenciaF_fuasObservados"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="descripcionReferenciaF_fuasObservados" id="descripcionReferenciaF_fuasObservados"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="numeroReferenciaF_fuasObservados" id="numeroReferenciaF_fuasObservados"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="tipoDocumentoF_fuasObservados">Identidad del Asegurado <span class="text-danger"> * </span></label>
                            </div>

                            <div class="col-sm-4">
                                <label for="componenteF_fuasObservados">Componente <span class="text-danger"> * </span></label>
                            </div>

                            <div class="col-sm-4">
                                <label for="codigoAsegurado1F_fuasObservados">Código del Asegurado <span class="text-danger"> * </span></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="tipoDocumentoF_fuasObservados" id="tipoDocumentoF_fuasObservados"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="numeroDocumentoF_fuasObservados" id="numeroDocumentoF_fuasObservados"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-4">
                                <select class="form-control select-2 select2" name="componenteF_fuasObservados" id="componenteF_fuasObservados">
                                    <option value="">-- Seleccionar el Componente --</option>
                                    @foreach($componente as $key => $value_componente)
                                    <option value="{{$value_componente->id}}">{{$value_componente->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-1">
                                <input type="text" class="form-control" name="codigoAsegurado1F_fuasObservados" id="codigoAsegurado1F_fuasObservados"
                                style="text-transform: uppercase;" readonly="true">
                            </div>

                            <div class="col-sm-1">
                                <input type="text" class="form-control" name="codigoAsegurado2F_fuasObservados" id="codigoAsegurado2F_fuasObservados"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="codigoAsegurado3F_fuasObservados" id="codigoAsegurado3F_fuasObservados"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="apellidoPaternoF_fuasObservados">Apellido Paterno <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="apellidoPaternoF_fuasObservados" id="apellidoPaternoF_fuasObservados"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-6">
                                <label for="apellidoMaternoF_fuasObservados">Apellido Materno <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="apellidoMaternoF_fuasObservados" id="apellidoMaternoF_fuasObservados"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="primerNombreF_fuasObservados">Primer Nombre <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="primerNombreF_fuasObservados" id="primerNombreF_fuasObservados"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-6">
                                <label for="otroNombreF_fuasObservados">Otros Nombres</label>
                                <input type="text" class="form-control" name="otroNombreF_fuasObservados" id="otroNombreF_fuasObservados"
                                style="text-transform: uppercase;" readonly="true">
                            </div>

                            <!-- PARA SELECCIONAR EL ID DEL PACIENTE -->
                            <input type="text" class="form-control" name="pacienteIdF_fuasObservados" id="pacienteIdF_fuasObservados"
                            style="text-transform: uppercase;display:none;" readonly="true">
                            <!-- FIN PARA SELECCIONAR EL ID DEL PACIENTE -->

                            <!-- PARA SELECCIONAR EL ID DEL FUA -->
                            <input type="text" class="form-control" name="atencionIdF_fuasObservados" id="atencionIdF_fuasObservados"
                            style="text-transform: uppercase;display:none;" readonly="true">
                            <!-- FIN PARA SELECCIONAR EL ID DEL FUA -->
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="sexoF_fuasObservados">Sexo <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="sexoF_fuasObservados" id="sexoF_fuasObservados"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-4">
                                <label for="fechaNacimientoF_fuasObservados">Fecha de Nacimiento <span class="text-danger"> * </span></label>
                                <input type="date" class="form-control" name="fechaNacimientoF_fuasObservados" id="fechaNacimientoF_fuasObservados"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-4">
                                <label for="historiaF_fuasObservados">Historia <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="historiaF_fuasObservados" id="historiaF_fuasObservados"
                                style="text-transform: uppercase;" readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="fechaF_fuasObservados">Fecha de Atención <span class="text-danger"> * </span></label>
                                <input type="date" class="form-control" name="fechaF_fuasObservados" id="fechaF_fuasObservados"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-2">
                                <label for="horaF_fuasObservados">Hora de Atención <span class="text-danger"> * </span></label>
                                <input type="time" class="form-control" name="horaF_fuasObservados" id="horaF_fuasObservados"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-7">
                                <label for="codigoPrestacionalF_fuasObservados">Código Prestacional <span class="text-danger"> * </span></label>
                                <select class="form-control select2" name="codigoPrestacionalF_fuasObservados" id="codigoPrestacionalF_fuasObservados" data-placeholder="Seleccionar código prestacional">
                                    <option value="">-- Seleccionar código prestacional --</option>
                                    @foreach($codPrestacional as $key => $value_codPrestacional)
                                    <option value="{{$value_codPrestacional->id}}">{{$value_codPrestacional->id}} - {{$value_codPrestacional->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="conceptoPrestacionalF_fuasObservados">Concepto Prestacional <span class="text-danger"> * </span></label>
                                <select class="form-control select2" name="conceptoPrestacionalF_fuasObservados" id="conceptoPrestacionalF_fuasObservados">
                                    <option value="">-- Seleccionar concepto prestacional --</option>
                                    @foreach($concPrestacional as $key => $value_concPrestacional)
                                    <option value="{{$value_concPrestacional->id}}">{{$value_concPrestacional->id}} - {{$value_concPrestacional->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label for="destinoAseguradoF_fuasObservados">Destino del Asegurado <span class="text-danger"> * </span></label>
                                <select class="form-control select-2 select2" name="destinoAseguradoF_fuasObservados" id="destinoAseguradoF_fuasObservados">
                                    <option value="">-- Seleccionar destino asegurado --</option>
                                    @foreach($destinoAsegurado as $key => $value_destinoAsegurado)
                                    <option value="{{$value_destinoAsegurado->id}}">{{$value_destinoAsegurado->id}} - {{$value_destinoAsegurado->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group hospitalizacion_oculto" style="display:none;">
                        <div class="row">
                            <div class="col-sm-12">
                                <label style="text-align:center;background:black;color:white;padding:1px;font-size:16px;width:100%;margin:0px;">Hospitalización</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group hospitalizacion_oculto" style="display:none;">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="fechaIngresoF_fuasObservados">Fecha de Ingreso <span id="span_fechaIngresoF_fuasObservados" class="text-danger"> * </span></label>
                                <input type="date" class="form-control" name="fechaIngresoF_fuasObservados" id="fechaIngresoF_fuasObservados"
                                style="text-transform: uppercase;" readonly="readonly">
                            </div>

                            <div class="col-sm-6">
                                <label for="fechaAltaF_fuasObservados">Fecha de Alta</label>
                                <input type="date" class="form-control" name="fechaAltaF_fuasObservados" id="fechaAltaF_fuasObservados"
                                style="text-transform: uppercase;" readonly="readonly">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label style="text-align:center;background:black;color:white;padding:1px;font-size:16px;width:100%;margin:0px;">Diagnósticos</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
							    <label for="diagnosticoF_fuasObservados">Tipo</label>
                            </div>

                            <div class="col-sm-2">
							    <label for="codigoCieNF_fuasObservados">CIE - 10</label>
                            </div>

                            <div class="col-sm-6">
							    <label for="codigoCieF_fuasObservados">Descripción</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <select class="form-control select-2 select2" name="diagnosticoF_fuasObservados" id="diagnosticoF_fuasObservados">
                                    <option value="">-- Seleccionar el tipo --</option>
                                    <option value="P">PRESUNTIVO</option>
                                    <option value="D">DEFINITIVO</option>
                                    <option value="R">REPETIDO</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="codigoCieNF_fuasObservados" id="codigoCieNF_fuasObservados"
                                style="text-transform: uppercase;" readonly="readonly">
                            </div>

                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="codigoCieF_fuasObservados" id="codigoCieF_fuasObservados"
                                style="text-transform: uppercase;" readonly="readonly">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label style="text-align:center;background:black;color:white;padding:1px;font-size:16px;width:100%;margin:0px;">Datos del Profesional</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="tipoDocumentoP_fuasObservados">Identidad del Profesional</label>
                            </div>

                            <div class="col-sm-8">
                                <label for="nombresApellidosP_fuasObservados">Nombres y Apellidos <span class="text-danger"> * </span></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="tipoDocumentoP_fuasObservados" id="tipoDocumentoP_fuasObservados"
                                style="text-transform: uppercase;" readonly="true">
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="numeroDocumentoP_fuasObservados" id="numeroDocumentoP_fuasObservados"
                                style="text-transform: uppercase;" readonly="true">
                            </div>

                            <div class="col-sm-8">
                                <select class="form-control select2" name="nombresApellidosP_fuasObservados" id="nombresApellidosP_fuasObservados" data-placeholder="Seleccionar el profesional">
                                    <option value="">-- Seleccionar el Profesional --</option>
                                    @foreach($datosPersonalC as $key => $value_datosPersonalC)
                                    <option value="{{$value_datosPersonalC->Profesional_id}}">{{$value_datosPersonalC->Profesional}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-8">
                                <label for="tipoPersonalSaludF_fuasObservados">Tipo de Personal de Salud</label>
                            </div>

                            <div class="col-sm-4">
                                <label for="colegiaturaF_fuasObservados">Colegiatura</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <select class="form-control select-2 select2" name="tipoPersonalSaludF_fuasObservados" id="tipoPersonalSaludF_fuasObservados">
                                    <option value="">-- Seleccionar el Tipo de Personal de Salud --</option>
                                    @foreach($sisTipoPersonalSalud as $key => $value_sisTipoPersonalSalud)
                                    <option value="{{$value_sisTipoPersonalSalud->id}}">{{$value_sisTipoPersonalSalud->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <select class="form-control select-2 select2" name="egresadoF_fuasObservados" id="egresadoF_fuasObservados">
                                    <option value="">-- Seleccionar si es Egresado --</option>
                                    @foreach($sisEgresado as $key => $value_sisEgresado)
                                    <option value="{{$value_sisEgresado->id}}">{{$value_sisEgresado->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="colegiaturaF_fuasObservados" id="colegiaturaF_fuasObservados"
                                style="text-transform: uppercase;" readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-8">
                                <label for="especialidadF_fuasObservados">Especialidad</label>
                            </div>

                            <div class="col-sm-4">
                                <label for="rneF_fuasObservados">RNE</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-8">
                                <select class="form-control select-2 select2" name="especialidadF_fuasObservados" id="especialidadF_fuasObservados">
                                    <option value="">-- Seleccionar la Especialidad --</option>
                                    @foreach($sisEspecialidad as $key => $value_sisEspecialidad)
                                    <option value="{{$value_sisEspecialidad->id}}">{{$value_sisEspecialidad->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="rneF_fuasObservados" id="rneF_fuasObservados"
                                style="text-transform: uppercase;" readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="display:none;">
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="text" name="idRegistroF_fuasObservados" id="idRegistroF_fuasObservados">
                                <input type="text" name="modeloF_fuasObservados" id="modeloF_fuasObservados">
                            </div>
                        </div>
                    </div>

                    
                </div>

                <div class="modal-footer d-flex">
                    <div id="cerrar_actualizarFuaF_fuasObservados">
                        <button type="button" class="btn btn-default boton-general" data-dismiss="modal">
                            <i class="fa fa-undo" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Salir
                        </button>
                    </div>

                    <div id="imprimir_actualizarFuaF_fuasObservados">
                        <button type="button" class="btn btn-dark boton-general" id="imprimir_verFuaF_fuasObservados">
                            <i class="fas fa-print" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Imprimir
                        </button>
                    </div>

                    <div id="actualizar_actualizarFuaF_fuasObservados">
                        <button type="button" class="btn btn-info boton-general" id="actualizar_verFuaF_fuasObservados">
                            <i class="fa fa-pencil" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Actualizar
                        </button>
                    </div>

                    <div id="registrar_actualizarFuaF_fuasObservados" style="display:none;">
                        <button type="submit" class="btn btn-success boton-general" id="registrar_actualizarFuaF1_fuasObservados">
                            <i class="fas fa-save" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Guardar datos
                        </button>
                    </div>

                    <div id="cancelar_actualizarFuaF_fuasObservados" style="display:none;">
                        <button type="button" class="btn btn-danger boton-general">
                            <i class="fa fa-times" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Cancelar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <script type="text/javascript">
   var tdate = new Date();
   var dd = tdate.getDate();
   var MM = tdate.getMonth();
   var yyyy = tdate.getFullYear();
   
    if(MM < 10){
        var valorMes = "0";
    }else{
        valorMes = "";
    }

    if(dd < 10){
        var valorDia = "0";
    }else{
        valorDia = "";
    }

   var currentDate = yyyy + "-" + valorMes + ( +MM+1) + "-" + valorDia + dd

   $("#fechaInicio_fuasObservados").val(currentDate);
   $("#fechaFin_fuasObservados").val(currentDate);
</script> -->

<script type="text/javascript">
$("#historiaBD_fuasObservados").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        let numHistoriaBD_fuasObservados = $("#historiaBD_fuasObservados").val();
        
        if (numHistoriaBD_fuasObservados != ''){

            tablaFuasObservados.clear().destroy();

            $("#anular_verFuaF").css("display","none");
            $("#btnRolCitas_fuasObservados").css("display","none");
            $("#documentoBD_fuasObservados").val("");
            $("#fuaBD_fuasObservados").val("");

            $.ajax({
                url: '{{ route('consultar.historiaBD_fuasObservados') }}',
                data: {numHistoriaBD_fuasObservados},
                success: function(respuesta){
                    console.log("respuesta",respuesta);
                },

                error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                }
            });

        /*=============================================
        DataTable de Fuas Observados
        =============================================*/
        tablaFuasObservados = $("#tablaFuasObservados").DataTable({
            "processing": true,
  	        "serverSide": true,
	        "search": {
		        return: true
	        },
	        "ordering": true,
	        "searching": true,
	        "retrieve": true,
	        "select":true,
	        "select":{
		        "style":'single'
	        },
	        "dom": 'Qfrtip',
            "buttons": [
		        {
			        "extend": 'excel',
			        "footer": false,
			        "title": 'FUAS POR CICLOS',
			        "filename": 'FUAS POR CICLOS',
			        "text": "<button class='btn btn-success btn-sm boton-general' style='float:left;'><i class='fas fa-file-excel'></i> Exportar a Excel</button>",
            action: function ( e, dt, node, config ) {
			        var myButton = this;
			        dt.one( 'draw', function () {
				        $.fn.dataTable.ext.buttons.excelHtml5.action.call(myButton, e, dt, node, config);
			        });
			        dt.page.len(-1).draw();
		        }
		        }
	        ],
            "ajax": {
                "url": '{{ route('consultar.historiaBD_fuasObservados') }}',
                "data": {'numHistoriaBD_fuasObservados' : $("#historiaBD_fuasObservados").val()}
            },
            "order": [[ 12, "desc" ]],
            "colReorder": true,
	        "deferRender":true,
	        "scroller": {
	            "loadingIndicator": true
	        },
	        "scrollCollapse":true,
	        "paging":true,
	        "scrollY":400,
	        "scrollX":true,
            "columns": [
		        {"data": 'Financiador',"name": 'Financiador'},
		        {"data": 'TipoAtencion',"name": 'TipoAtencion'},
		        {"data": 'Fecha',"name": 'Fecha'},
            {"data": 'Fecha1',"name": 'Fecha1',"targets": [ 3 ],"visible": false},
		        {"data": 'Hora',"name": 'Hora',"targets": [ 4 ],"visible": false},
		        {"data": 'Paciente',"name": 'Paciente'},
		        {"data": 'TipoDocumento',"name": 'TipoDocumento'},
		        {"data": 'NumeroDocumento',"name": 'NumeroDocumento'},
		        {"data": 'HistoriaClinica',"name": 'HistoriaClinica'},
		        {"data": 'FUA',"name": 'FUA'},
		        {"data": 'ActividadEspecifica',"name": 'ActividadEspecifica'},
		        {"data": 'Profesional',"name": 'Profesional'},
		        {"data": 'EstadoCita',"name": 'EstadoCita',
			        render: function (item) {
				        return item.toUpperCase();
			        }
                },
		        {"data": 'UnidadOrganica',"name": 'UnidadOrganica',"targets": [ 13 ],"visible": false},
		        {"data": 'GrupoProfesional',"name": 'GrupoProfesional',"targets": [ 14 ],"visible": false},
            ],
            "language": {
	            "sProcessing": "Procesando...",
	            "sLengthMenu": "Mostrar _MENU_ registros",
	            "sZeroRecords": "No se encontraron resultados",
	            "sEmptyTable": "Ningún dato disponible en esta tabla",
	            "sInfo": "Mostrando registros del _START_ al _END_",
	            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
	            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
	            "sInfoPostFix": "",
	            "sSearch": "Buscar:",
	            "sUrl": "",
	            "sInfoThousands": ",",
	            "sLoadingRecords": "Cargando...",
	            "oPaginate": {
	              "sFirst": "Primero",
	              "sLast": "Último",
	              "sNext": "Siguiente",
	              "sPrevious": "Anterior"
	            },
	            "oAria": {
	              "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
	              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
	            },
		        "buttons": {
			          "copy": 'Copiar',
			          "csv": 'CSV',
			          "print": 'Imprimir',
			          "copySuccess": {
				          "1": "1 fila copiada al portapapeles",
				          "_": "%d filas copiadas al portapapeles"
			          },
			          "copyTitle": "Copiar al portapapeles",
		        },
		        "select":{
			        "rows":{
				        0:"Haga click en una fila para seleccionarla.",
				        1:"Solo 1 fila seleccionada"
			        }
		        }
  	        },
	          "initComplete": function () {

		          unidadOrganica(tablaFuasObservados);
		          profesionales(tablaFuasObservados);
		          grupoProfesionales(tablaFuasObservados);
		          pacientes(tablaFuasObservados);
	          },
            "rowCallback": function(row, data, index){
                if(data["EstadoCita_id"] != 1 && data["EstadoCita_id"] != 3 && data["EstadoCita_id"] != 4 && data["EstadoCita_id"] != 10){
                    $(row).css('background-color', '#F39B9B');
                }
            }
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
        $('div.dataTables_filter input').unbind();
        $("div.dataTables_filter input").keyup( function (e) {
	        if (e.keyCode == 13) {
		        tablaFuasObservados.search(this.value).draw();
	        }
        });
        /* FIN DE BUSQUEDA POR ENTER */

        }else{
            $('#historiaBD_fuasObservados').focus();

            tablaFuasObservados.clear().destroy();
            $("#anular_verFuaF").css("display","none");
            $("#btnRolCitas_fuasObservados").css("display","none");

            let valorUrlAjaxHistoriaBD_fuasObservados = '';

            if($('#fechaInicio_fuasObservados').val() != '' || $('#fechaFin_fuasObservados').val() != ''){
                valorUrlAjaxHistoriaBD_fuasObservados = '{{ route('consultar.fechasFObservados') }}';
            }else{
                valorUrlAjaxHistoriaBD_fuasObservados = ruta + '/fuasObservados';
            }

        /*=============================================
        DataTable de Auditoria Observados
        =============================================*/
        tablaFuasObservados = $("#tablaFuasObservados").DataTable({
            "processing": true,
  	        "serverSide": true,
	        "search": {
		        return: true
	        },
	        "ordering": true,
	        "searching": true,
	        "retrieve": true,
	        "select":true,
	        "select":{
		        "style":'single'
	        },
	        /* "dom": 'Qfrtip', */
            "buttons": [
		        {
			        "extend": 'excel',
			        "footer": false,
			        "title": 'FUAS POR CICLOS',
			        "filename": 'FUAS POR CICLOS',
			        "text": "<button class='btn btn-success btn-sm boton-general' style='float:left;'><i class='fas fa-file-excel'></i> Exportar a Excel</button>",
                    action: function ( e, dt, node, config ) {
			            var myButton = this;
			            dt.one( 'draw', function () {
				            $.fn.dataTable.ext.buttons.excelHtml5.action.call(myButton, e, dt, node, config);
			            });
			            dt.page.len(-1).draw();
		            }
		        }
	        ],
            "ajax": {
                "url": valorUrlAjaxHistoriaBD_fuasObservados,
                "data": { '_token' : $('input[name=_token]').val(),
                        'fechaInicio_pacientesCitados' : $('#fechaInicio_fuasObservados').val(),
                        'fechaFin_pacientesCitados': $('#fechaFin_fuasObservados').val()}
            },
            "order": [[ 12, "desc" ]],
            "colReorder": true,
	        "deferRender":true,
	        "scroller": {
	            "loadingIndicator": true
	        },
	        "scrollCollapse":true,
	        "paging":true,
	        "scrollY":400,
	        "scrollX":true,
            "columns": [
		        {"data": 'Financiador',"name": 'Financiador'},
		        {"data": 'TipoAtencion',"name": 'TipoAtencion'},
		        {"data": 'Fecha',"name": 'Fecha'},
            {"data": 'Fecha1',"name": 'Fecha1',"targets": [ 3 ],"visible": false},
		        {"data": 'Hora',"name": 'Hora',"targets": [ 4 ],"visible": false},
		        {"data": 'Paciente',"name": 'Paciente'},
		        {"data": 'TipoDocumento',"name": 'TipoDocumento'},
		        {"data": 'NumeroDocumento',"name": 'NumeroDocumento'},
		        {"data": 'HistoriaClinica',"name": 'HistoriaClinica'},
		        {"data": 'FUA',"name": 'FUA'},
		        {"data": 'ActividadEspecifica',"name": 'ActividadEspecifica'},
		        {"data": 'Profesional',"name": 'Profesional'},
		        {"data": 'EstadoCita',"name": 'EstadoCita',
			        render: function (item) {
				        return item.toUpperCase();
			        }
                },
		        {"data": 'UnidadOrganica',"name": 'UnidadOrganica',"targets": [ 13 ],"visible": false},
		        {"data": 'GrupoProfesional',"name": 'GrupoProfesional',"targets": [ 14 ],"visible": false},
            ],
            "language": {
	            "sProcessing": "Procesando...",
	            "sLengthMenu": "Mostrar _MENU_ registros",
	            "sZeroRecords": "No se encontraron resultados",
	            "sEmptyTable": "Ningún dato disponible en esta tabla",
	            "sInfo": "Mostrando registros del _START_ al _END_",
	            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
	            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
	            "sInfoPostFix": "",
	            "sSearch": "Buscar:",
	            "sUrl": "",
	            "sInfoThousands": ",",
	            "sLoadingRecords": "Cargando...",
	            "oPaginate": {
	              "sFirst": "Primero",
	              "sLast": "Último",
	              "sNext": "Siguiente",
	              "sPrevious": "Anterior"
	            },
	            "oAria": {
	              "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
	              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
	            },
		        "buttons": {
			          "copy": 'Copiar',
			          "csv": 'CSV',
			          "print": 'Imprimir',
			          "copySuccess": {
				          "1": "1 fila copiada al portapapeles",
				          "_": "%d filas copiadas al portapapeles"
			          },
			          "copyTitle": "Copiar al portapapeles",
		        },
		        "select":{
			        "rows":{
				        0:"Haga click en una fila para seleccionarla.",
				        1:"Solo 1 fila seleccionada"
			        }
		        }
  	        },
	"initComplete": function () {

		unidadOrganica_fuasObservados(tablaFuasObservados);
		profesionales_fuasObservados(tablaFuasObservados);
		grupoProfesionales_fuasObservados(tablaFuasObservados);
		pacientes_fuasObservados(tablaFuasObservados);
	},
            "rowCallback": function(row, data, index){
                if(data["EstadoCita_id"] != 1 && data["EstadoCita_id"] != 3 && data["EstadoCita_id"] != 4 && data["EstadoCita_id"] != 10){
                    $(row).css('background-color', '#F39B9B');
                }
            }
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasObservados.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});

$("#documentoBD_fuasObservados").keypress(function(e){
    if(e.which == 13) {
        e.preventDefault();

        let numDocumentoBD_fuasObservados = $("#documentoBD_fuasObservados").val();
        
        if (numDocumentoBD_fuasObservados != ''){

            tablaFuasObservados.clear().destroy();

            $("#anular_verFuaF").css("display","none");
            $("#btnRolCitas_fuasObservados").css("display","none");
            $("#historiaBD_fuasObservados").val("");

            $.ajax({
                url: '{{ route('consultar.documentoBD_fuasObservados') }}',
                data: {numDocumentoBD_fuasObservados},
                success: function(respuesta){
                    console.log("respuesta",respuesta);
                },

                error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                }
            });

        /*=============================================
        DataTable de FUAS OBSERVADOS
        =============================================*/
        tablaFuasObservados = $("#tablaFuasObservados").DataTable({
            "processing": true,
  	        "serverSide": true,
	        "search": {
		        return: true
	        },
	        "ordering": true,
	        "searching": true,
	        "retrieve": true,
	        "select":true,
	        "select":{
		        "style":'single'
	        },
	        "dom": 'Qfrtip',
            "buttons": [
		        {
			        "extend": 'excel',
			        "footer": false,
			        "title": 'FUAS OBSERVADOS',
			        "filename": 'FUAS_OBSERVADOS',
			        "text": "<button class='btn btn-success btn-sm boton-general' style='float:left;'><i class='fas fa-file-excel'></i> Exportar a Excel</button>",
                    action: function ( e, dt, node, config ) {
			            var myButton = this;
			            dt.one( 'draw', function () {
				            $.fn.dataTable.ext.buttons.excelHtml5.action.call(myButton, e, dt, node, config);
			            });
			            dt.page.len(-1).draw();
		            }
		        }
	        ],
            "ajax": {
                "url": '{{ route('consultar.documentoBD_fuasObservados') }}',
                "data": {'numDocumentoBD_fuasObservados' : $("#documentoBD_fuasObservados").val()}
            },
            "order": [[ 12, "desc" ]],
            "colReorder": true,
	        "deferRender":true,
	        "scroller": {
	            "loadingIndicator": true
	        },
	        "scrollCollapse":true,
	        "paging":true,
	        "scrollY":400,
	        "scrollX":true,
            "columns": [
		        {"data": 'Financiador',"name": 'Financiador'},
		        {"data": 'TipoAtencion',"name": 'TipoAtencion'},
		        {"data": 'Fecha',"name": 'Fecha'},
            {"data": 'Fecha1',"name": 'Fecha1',"targets": [ 3 ],"visible": false},
		        {"data": 'Hora',"name": 'Hora',"targets": [ 4 ],"visible": false},
		        {"data": 'Paciente',"name": 'Paciente'},
		        {"data": 'TipoDocumento',"name": 'TipoDocumento'},
		        {"data": 'NumeroDocumento',"name": 'NumeroDocumento'},
		        {"data": 'HistoriaClinica',"name": 'HistoriaClinica'},
		        {"data": 'FUA',"name": 'FUA'},
		        {"data": 'ActividadEspecifica',"name": 'ActividadEspecifica'},
		        {"data": 'Profesional',"name": 'Profesional'},
		        {"data": 'EstadoCita',"name": 'EstadoCita',
			        render: function (item) {
				        return item.toUpperCase();
			        }
                },
		        {"data": 'UnidadOrganica',"name": 'UnidadOrganica',"targets": [ 13 ],"visible": false},
		        {"data": 'GrupoProfesional',"name": 'GrupoProfesional',"targets": [ 14 ],"visible": false},
            ],
            "language": {
	            "sProcessing": "Procesando...",
	            "sLengthMenu": "Mostrar _MENU_ registros",
	            "sZeroRecords": "No se encontraron resultados",
	            "sEmptyTable": "Ningún dato disponible en esta tabla",
	            "sInfo": "Mostrando registros del _START_ al _END_",
	            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
	            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
	            "sInfoPostFix": "",
	            "sSearch": "Buscar:",
	            "sUrl": "",
	            "sInfoThousands": ",",
	            "sLoadingRecords": "Cargando...",
	            "oPaginate": {
	              "sFirst": "Primero",
	              "sLast": "Último",
	              "sNext": "Siguiente",
	              "sPrevious": "Anterior"
	            },
	            "oAria": {
	              "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
	              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
	            },
		        "buttons": {
			          "copy": 'Copiar',
			          "csv": 'CSV',
			          "print": 'Imprimir',
			          "copySuccess": {
				          "1": "1 fila copiada al portapapeles",
				          "_": "%d filas copiadas al portapapeles"
			          },
			          "copyTitle": "Copiar al portapapeles",
		        },
		        "select":{
			        "rows":{
				        0:"Haga click en una fila para seleccionarla.",
				        1:"Solo 1 fila seleccionada"
			        }
		        }
  	        },
	        "initComplete": function () {
		        unidadOrganica_fuasObservados(tablaFuasObservados);
		        profesionales_fuasObservados(tablaFuasObservados);
		        grupoProfesionales_fuasObservados(tablaFuasObservados);
		        pacientes_fuasObservados(tablaFuasObservados);
	        },
            "rowCallback": function(row, data, index){
                if(data["EstadoCita_id"] != 1 && data["EstadoCita_id"] != 3 && data["EstadoCita_id"] != 4 && data["EstadoCita_id"] != 10){
                    $(row).css('background-color', '#F39B9B');
                }
            }
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
        $('div.dataTables_filter input').unbind();
        $("div.dataTables_filter input").keyup( function (e) {
	        if (e.keyCode == 13) {
		        tablaFuasObservados.search(this.value).draw();
	        }
        });
        /* FIN DE BUSQUEDA POR ENTER */
        }else{
            $('#documentoBD_fuasObservados').focus();

            tablaFuasObservados.clear().destroy();
            $("#anular_verFuaF").css("display","none");
            $("#btnRolCitas_fuasObservados").css("display","none");

            let valorUrlAjaxDocumentoBD_fuasObservados = '';

            if($('#fechaInicio_fuasObservados').val() != '' || $('#fechaFin_fuasObservados').val() != ''){
                valorUrlAjaxDocumentoBD_fuasObservados = '{{ route('consultar.fechasFObservados') }}';
            }else{
                valorUrlAjaxDocumentoBD_fuasObservados = ruta + '/fuasObservados';
            }

        /*=============================================
        DataTable de FUAS OBSERVADOS
        =============================================*/
        tablaFuasObservados = $("#tablaFuasObservados").DataTable({
            "processing": true,
  	        "serverSide": true,
	        "search": {
		        return: true
	        },
	        "ordering": true,
	        "searching": true,
	        "retrieve": true,
	        "select":true,
	        "select":{
		        "style":'single'
	        },
	        "dom": 'Qfrtip',
            "buttons": [
		        {
			        "extend": 'excel',
			        "footer": false,
			        "title": 'FUAS OBSERVADOS',
			        "filename": 'FUAS_OBSERVADOS',
			        "text": "<button class='btn btn-success btn-sm boton-general' style='float:left;'><i class='fas fa-file-excel'></i> Exportar a Excel</button>",
                    action: function ( e, dt, node, config ) {
			            var myButton = this;
			            dt.one( 'draw', function () {
				            $.fn.dataTable.ext.buttons.excelHtml5.action.call(myButton, e, dt, node, config);
			            });
			            dt.page.len(-1).draw();
		            }
		        }
	        ],
            "ajax": {
                "url": valorUrlAjaxDocumentoBD_fuasObservados,
                "data": { '_token' : $('input[name=_token]').val(),
                        'fechaInicio_pacientesCitados' : $('#fechaInicio_fuasObservados').val(),
                        'fechaFin_pacientesCitados': $('#fechaFin_fuasObservados').val()}
            },
            "order": [[ 12, "desc" ]],
            "colReorder": true,
	        "deferRender":true,
	        "scroller": {
	            "loadingIndicator": true
	        },
	        "scrollCollapse":true,
	        "paging":true,
	        "scrollY":400,
	        "scrollX":true,
            "columns": [
		        {"data": 'Financiador',"name": 'Financiador'},
		        {"data": 'TipoAtencion',"name": 'TipoAtencion'},
		        {"data": 'Fecha',"name": 'Fecha'},
            {"data": 'Fecha1',"name": 'Fecha1',"targets": [ 3 ],"visible": false},
		        {"data": 'Hora',"name": 'Hora',"targets": [ 4 ],"visible": false},
		        {"data": 'Paciente',"name": 'Paciente'},
		        {"data": 'TipoDocumento',"name": 'TipoDocumento'},
		        {"data": 'NumeroDocumento',"name": 'NumeroDocumento'},
		        {"data": 'HistoriaClinica',"name": 'HistoriaClinica'},
		        {"data": 'FUA',"name": 'FUA'},
		        {"data": 'ActividadEspecifica',"name": 'ActividadEspecifica'},
		        {"data": 'Profesional',"name": 'Profesional'},
		        {"data": 'EstadoCita',"name": 'EstadoCita',
			        render: function (item) {
				        return item.toUpperCase();
			        }
                },
		        {"data": 'UnidadOrganica',"name": 'UnidadOrganica',"targets": [ 13 ],"visible": false},
		        {"data": 'GrupoProfesional',"name": 'GrupoProfesional',"targets": [ 14 ],"visible": false},
            ],
            "language": {
	            "sProcessing": "Procesando...",
	            "sLengthMenu": "Mostrar _MENU_ registros",
	            "sZeroRecords": "No se encontraron resultados",
	            "sEmptyTable": "Ningún dato disponible en esta tabla",
	            "sInfo": "Mostrando registros del _START_ al _END_",
	            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
	            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
	            "sInfoPostFix": "",
	            "sSearch": "Buscar:",
	            "sUrl": "",
	            "sInfoThousands": ",",
	            "sLoadingRecords": "Cargando...",
	            "oPaginate": {
	              "sFirst": "Primero",
	              "sLast": "Último",
	              "sNext": "Siguiente",
	              "sPrevious": "Anterior"
	            },
	            "oAria": {
	              "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
	              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
	            },
		        "buttons": {
			          "copy": 'Copiar',
			          "csv": 'CSV',
			          "print": 'Imprimir',
			          "copySuccess": {
				          "1": "1 fila copiada al portapapeles",
				          "_": "%d filas copiadas al portapapeles"
			          },
			          "copyTitle": "Copiar al portapapeles",
		        },
		        "select":{
			        "rows":{
				        0:"Haga click en una fila para seleccionarla.",
				        1:"Solo 1 fila seleccionada"
			        }
		        }
  	        },
	        "initComplete": function () {
		        unidadOrganica_fuasObservados(tablaFuasObservados);
		        profesionales_fuasObservados(tablaFuasObservados);
		        grupoProfesionales_fuasObservados(tablaFuasObservados);
		        pacientes_fuasObservados(tablaFuasObservados);
	        },
            "rowCallback": function(row, data, index){
                if(data["EstadoCita_id"] != 1 && data["EstadoCita_id"] != 3 && data["EstadoCita_id"] != 4 && data["EstadoCita_id"] != 10){
                    $(row).css('background-color', '#F39B9B');
                }
            }
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasObservados.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});

$("#fuaBD_fuasObservados").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        let numFuaBD_fuasObservados = $("#fuaBD_fuasObservados").val();
        
        if (numFuaBD_fuasObservados != ''){

            tablaFuasObservados.clear().destroy();

            $("#anular_verFuaF").css("display","none");
            $("#btnRolCitas_fuasObservados").css("display","none");
            $("#documentoBD_fuasObservados").val("");

            $.ajax({
                url: '{{ route('consultar.fuaBD_fuasObservados') }}',
                data: {numFuaBD_fuasObservados},
                success: function(respuesta){
                    console.log("respuesta",respuesta);
                },

                error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                }
            });

        /*=============================================
        DataTable de Fuas Observados
        =============================================*/
        tablaFuasObservados = $("#tablaFuasObservados").DataTable({
            "processing": true,
  	        "serverSide": true,
	        "search": {
		        return: true
	        },
	        "ordering": true,
	        "searching": true,
	        "retrieve": true,
	        "select":true,
	        "select":{
		        "style":'single'
	        },
	        "dom": 'Qfrtip',
            "buttons": [
		        {
			        "extend": 'excel',
			        "footer": false,
			        "title": 'FUAS OBSERVADOS',
			        "filename": 'FUAS_OBSERVADOS',
			        "text": "<button class='btn btn-success btn-sm boton-general' style='float:left;'><i class='fas fa-file-excel'></i> Exportar a Excel</button>",
                    action: function ( e, dt, node, config ) {
			            var myButton = this;
			            dt.one( 'draw', function () {
				            $.fn.dataTable.ext.buttons.excelHtml5.action.call(myButton, e, dt, node, config);
			            });
			            dt.page.len(-1).draw();
		            }
		        }
	        ],
            "ajax": {
                "url": '{{ route('consultar.fuaBD_fuasObservados') }}',
                "data": {'numFuaBD_fuasObservados' : $("#fuaBD_fuasObservados").val()}
            },
            "order": [[ 12, "desc" ]],
            "colReorder": true,
	        "deferRender":true,
	        "scroller": {
	            "loadingIndicator": true
	        },
	        "scrollCollapse":true,
	        "paging":true,
	        "scrollY":400,
	        "scrollX":true,
            "columns": [
		        {"data": 'Financiador',"name": 'Financiador'},
		        {"data": 'TipoAtencion',"name": 'TipoAtencion'},
		        {"data": 'Fecha',"name": 'Fecha'},
            {"data": 'Fecha1',"name": 'Fecha1',"targets": [ 3 ],"visible": false},
		        {"data": 'Hora',"name": 'Hora',"targets": [ 4 ],"visible": false},
		        {"data": 'Paciente',"name": 'Paciente'},
		        {"data": 'TipoDocumento',"name": 'TipoDocumento'},
		        {"data": 'NumeroDocumento',"name": 'NumeroDocumento'},
		        {"data": 'HistoriaClinica',"name": 'HistoriaClinica'},
		        {"data": 'FUA',"name": 'FUA'},
		        {"data": 'ActividadEspecifica',"name": 'ActividadEspecifica'},
		        {"data": 'Profesional',"name": 'Profesional'},
		        {"data": 'EstadoCita',"name": 'EstadoCita',
			        render: function (item) {
				        return item.toUpperCase();
			        }
                },
		        {"data": 'UnidadOrganica',"name": 'UnidadOrganica',"targets": [ 13 ],"visible": false},
		        {"data": 'GrupoProfesional',"name": 'GrupoProfesional',"targets": [ 14 ],"visible": false},
            ],
            "language": {
	            "sProcessing": "Procesando...",
	            "sLengthMenu": "Mostrar _MENU_ registros",
	            "sZeroRecords": "No se encontraron resultados",
	            "sEmptyTable": "Ningún dato disponible en esta tabla",
	            "sInfo": "Mostrando registros del _START_ al _END_",
	            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
	            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
	            "sInfoPostFix": "",
	            "sSearch": "Buscar:",
	            "sUrl": "",
	            "sInfoThousands": ",",
	            "sLoadingRecords": "Cargando...",
	            "oPaginate": {
	              "sFirst": "Primero",
	              "sLast": "Último",
	              "sNext": "Siguiente",
	              "sPrevious": "Anterior"
	            },
	            "oAria": {
	              "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
	              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
	            },
		        "buttons": {
			          "copy": 'Copiar',
			          "csv": 'CSV',
			          "print": 'Imprimir',
			          "copySuccess": {
				          "1": "1 fila copiada al portapapeles",
				          "_": "%d filas copiadas al portapapeles"
			          },
			          "copyTitle": "Copiar al portapapeles",
		        },
		        "select":{
			        "rows":{
				        0:"Haga click en una fila para seleccionarla.",
				        1:"Solo 1 fila seleccionada"
			        }
		        }
  	        },
	        "initComplete": function () {
		        unidadOrganica_fuasObservados(tablaFuasObservados);
		        profesionales_fuasObservados(tablaFuasObservados);
		        grupoProfesionales_fuasObservados(tablaFuasObservados);
		        pacientes_fuasObservados(tablaFuasObservados);
	        },
            "rowCallback": function(row, data, index){
                if(data["EstadoCita_id"] != 1 && data["EstadoCita_id"] != 3 && data["EstadoCita_id"] != 4 && data["EstadoCita_id"] != 10){
                    $(row).css('background-color', '#F39B9B');
                }
            }
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
        $('div.dataTables_filter input').unbind();
        $("div.dataTables_filter input").keyup( function (e) {
	        if (e.keyCode == 13) {
		        tablaFuasObservados.search(this.value).draw();
	        }
        });
        /* FIN DE BUSQUEDA POR ENTER */

        }else{
            $('#fuaBD_fuasObservados').focus();

            tablaFuasObservados.clear().destroy();
            $("#anular_verFuaF").css("display","none");
            $("#btnRolCitas_fuasObservados").css("display","none");
            $("#documentoBD_fuasObservados").val("");
            $("#historiaBD_fuasObservados").val("");

            let valorUrlAjaxFuaBD_fuasObservados = '';

            if($('#fechaInicio_fuasObservados').val() != '' || $('#fechaFin_fuasObservados').val() != ''){
                valorUrlAjaxFuaBD_fuasObservados = '{{ route('consultar.fechasFObservados') }}';
            }else{
                valorUrlAjaxFuaBD_fuasObservados = ruta + '/fuasObservados';
            }

        /*=============================================
        DataTable de FUAS OBSERVADOS
        =============================================*/
        tablaFuasObservados = $("#tablaFuasObservados").DataTable({
            "processing": true,
  	        "serverSide": true,
	        "search": {
		        return: true
	        },
	        "ordering": true,
	        "searching": true,
	        "retrieve": true,
	        "select":true,
	        "select":{
		        "style":'single'
	        },
	        "dom": 'Qfrtip',
            "buttons": [
		        {
			        "extend": 'excel',
			        "footer": false,
			        "title": 'FUAS OBSERVADOS',
			        "filename": 'FUAS_OBSERVADOS',
			        "text": "<button class='btn btn-success btn-sm boton-general' style='float:left;'><i class='fas fa-file-excel'></i> Exportar a Excel</button>",
                    action: function ( e, dt, node, config ) {
			            var myButton = this;
			            dt.one( 'draw', function () {
				            $.fn.dataTable.ext.buttons.excelHtml5.action.call(myButton, e, dt, node, config);
			            });
			            dt.page.len(-1).draw();
		            }
		        }
	        ],
            "ajax": {
                "url": valorUrlAjaxFuaBD_fuasObservados,
                "data": { '_token' : $('input[name=_token]').val(),
                        'fechaInicio_pacientesCitados' : $('#fechaInicio_fuasObservados').val(),
                        'fechaFin_pacientesCitados': $('#fechaFin_fuasObservados').val()}
            },
            "order": [[ 12, "desc" ]],
            "colReorder": true,
	        "deferRender":true,
	        "scroller": {
	            "loadingIndicator": true
	        },
	        "scrollCollapse":true,
	        "paging":true,
	        "scrollY":400,
	        "scrollX":true,
            "columns": [
		        {"data": 'Financiador',"name": 'Financiador'},
		        {"data": 'TipoAtencion',"name": 'TipoAtencion'},
		        {"data": 'Fecha',"name": 'Fecha'},
            {"data": 'Fecha1',"name": 'Fecha1',"targets": [ 3 ],"visible": false},
		        {"data": 'Hora',"name": 'Hora',"targets": [ 4 ],"visible": false},
		        {"data": 'Paciente',"name": 'Paciente'},
		        {"data": 'TipoDocumento',"name": 'TipoDocumento'},
		        {"data": 'NumeroDocumento',"name": 'NumeroDocumento'},
		        {"data": 'HistoriaClinica',"name": 'HistoriaClinica'},
		        {"data": 'FUA',"name": 'FUA'},
		        {"data": 'ActividadEspecifica',"name": 'ActividadEspecifica'},
		        {"data": 'Profesional',"name": 'Profesional'},
		        {"data": 'EstadoCita',"name": 'EstadoCita',
			        render: function (item) {
				        return item.toUpperCase();
			        }
                },
		        {"data": 'UnidadOrganica',"name": 'UnidadOrganica',"targets": [ 13 ],"visible": false},
		        {"data": 'GrupoProfesional',"name": 'GrupoProfesional',"targets": [ 14 ],"visible": false},
            ],
            "language": {
	            "sProcessing": "Procesando...",
	            "sLengthMenu": "Mostrar _MENU_ registros",
	            "sZeroRecords": "No se encontraron resultados",
	            "sEmptyTable": "Ningún dato disponible en esta tabla",
	            "sInfo": "Mostrando registros del _START_ al _END_",
	            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
	            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
	            "sInfoPostFix": "",
	            "sSearch": "Buscar:",
	            "sUrl": "",
	            "sInfoThousands": ",",
	            "sLoadingRecords": "Cargando...",
	            "oPaginate": {
	              "sFirst": "Primero",
	              "sLast": "Último",
	              "sNext": "Siguiente",
	              "sPrevious": "Anterior"
	            },
	            "oAria": {
	              "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
	              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
	            },
		        "buttons": {
			          "copy": 'Copiar',
			          "csv": 'CSV',
			          "print": 'Imprimir',
			          "copySuccess": {
				          "1": "1 fila copiada al portapapeles",
				          "_": "%d filas copiadas al portapapeles"
			          },
			          "copyTitle": "Copiar al portapapeles",
		        },
		        "select":{
			        "rows":{
				        0:"Haga click en una fila para seleccionarla.",
				        1:"Solo 1 fila seleccionada"
			        }
		        }
  	        },
	        "initComplete": function () {
		        unidadOrganica_fuasObservados(tablaFuasObservados);
		        profesionales_fuasObservados(tablaFuasObservados);
		        grupoProfesionales_fuasObservados(tablaFuasObservados);
		        pacientes_fuasObservados(tablaFuasObservados);
	        },
            "rowCallback": function(row, data, index){
                if(data["EstadoCita_id"] != 1 && data["EstadoCita_id"] != 3 && data["EstadoCita_id"] != 4 && data["EstadoCita_id"] != 10){
                    $(row).css('background-color', '#F39B9B');
                }
            }
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasObservados.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});
</script>

<script type="text/javascript">

$('#frmFechas_fuasObservados').submit(function(e){
          
          e.preventDefault();

          tablaFuasObservados.clear().destroy();
          $("#anular_verFuaF").css("display","none");
          $("#btnRolCitas_fuasObservados").css("display","none");
  
          /* Petición AJAX */
          $.ajax({
            url: '{{ route('consultar.fechasFObservados') }}',
            data: $("#frmFechas_fuasObservados").serialize(),
            success: function(respuesta){
                /* console.log("respuesta",respuesta); */
            },

            error: function(jqXHR,textStatus,errorThrown){
                console.error(textStatus + " " + errorThrown);
            }
          });
        /* Fin de Petición AJAX */

        /*=============================================
        DataTable de Pacientes Citados
        =============================================*/
        tablaFuasObservados = $("#tablaFuasObservados").DataTable({
            "processing": true,
	        "serverSide": true,
	        "search": {
		        return: true
	        },
	        "ordering": true,
	        "searching": true,
	        "retrieve": true,
	        "select":true,
	        "select":{
		        "style":'single'
	        },
	        "dom": 'Qfrtip',
            "buttons": [
		        {
			        "extend": 'excel',
			        "footer": false,
			        "title": 'FUA-SIS EMITIDOS',
			        "filename": 'FUAS_EMITIDOS',
			        "text": "<button class='btn btn-success btn-sm boton-general' style='float:left;'><i class='fas fa-file-excel'></i> Exportar a Excel</button>",
                    action: function ( e, dt, node, config ) {
			            var myButton = this;
			            dt.one( 'draw', function () {
				            $.fn.dataTable.ext.buttons.excelHtml5.action.call(myButton, e, dt, node, config);
			            });
			            dt.page.len(-1).draw();
		            }
		        }
	        ],
            "ajax": {
                "url": '{{ route('consultar.fechasFObservados') }}',
                "data": {'_token' : $('input[name=_token]').val(),
                       'fechaInicio_pacientesCitados' : $('#fechaInicio_fuasObservados').val(),
                       'fechaFin_pacientesCitados': $('#fechaFin_fuasObservados').val()}
            },
            "order": [[ 12, "desc" ]],
	        "colReorder": true,
	        "deferRender":true,
	        "scroller": {
		        "loadingIndicator": true
	        },
	        "scrollCollapse":true,
	        "paging":true,
	        "scrollY":400,
	        "scrollX":true,
            "columns": [
		        {"data": 'Financiador',"name": 'Financiador'},
		        {"data": 'TipoAtencion',"name": 'TipoAtencion'},
		        {"data": 'Fecha',"name": 'Fecha'},
            {"data": 'Fecha1',"name": 'Fecha1',"targets": [ 3 ],"visible": false},
		        {"data": 'Hora',"name": 'Hora',"targets": [ 4 ],"visible": false},
		        {"data": 'Paciente',"name": 'Paciente'},
		        {"data": 'TipoDocumento',"name": 'TipoDocumento'},
		        {"data": 'NumeroDocumento',"name": 'NumeroDocumento'},
		        {"data": 'HistoriaClinica',"name": 'HistoriaClinica'},
		        {"data": 'FUA',"name": 'FUA'},
		        {"data": 'ActividadEspecifica',"name": 'ActividadEspecifica'},
		        {"data": 'Profesional',"name": 'Profesional'},
		        {"data": 'EstadoCita',"name": 'EstadoCita',
			        render: function (item) {
				        return item.toUpperCase();
			        }
                },
		        {"data": 'UnidadOrganica',"name": 'UnidadOrganica',"targets": [ 13 ],"visible": false},
		        {"data": 'GrupoProfesional',"name": 'GrupoProfesional',"targets": [ 14 ],"visible": false},
            ],
            "language": {
	            "sProcessing": "Procesando...",
	            "sLengthMenu": "Mostrar _MENU_ registros",
	            "sZeroRecords": "No se encontraron resultados",
	            "sEmptyTable": "Ningún dato disponible en esta tabla",
	            "sInfo": "Mostrando registros del _START_ al _END_",
	            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
	            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
	            "sInfoPostFix": "",
	            "sSearch": "Buscar:",
	            "sUrl": "",
	            "sInfoThousands": ",",
	            "sLoadingRecords": "Cargando...",
	            "oPaginate": {
	              "sFirst": "Primero",
	              "sLast": "Último",
	              "sNext": "Siguiente",
	              "sPrevious": "Anterior"
	            },
	            "oAria": {
	              "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
	              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
	            },
		        "buttons": {
			        "copy": 'Copiar',
			        "csv": 'CSV',
			        "print": 'Imprimir',
			        "copySuccess": {
				        "1": "1 fila copiada al portapapeles",
				        "_": "%d filas copiadas al portapapeles"
			        },
			          "copyTitle": "Copiar al portapapeles",
		        }
  	        },
            "initComplete": function () {
                unidadOrganica_fuasObservados(tablaFuasObservados);
                profesionales_fuasObservados(tablaFuasObservados);
                grupoProfesionales_fuasObservados(tablaFuasObservados);
                pacientes_fuasObservados(tablaFuasObservados);
            },
            "rowCallback": function(row, data, index){
                if(data["EstadoCita_id"] != 1 && data["EstadoCita_id"] != 3 && data["EstadoCita_id"] != 4 && data["EstadoCita_id"] != 10){
                    $(row).css('background-color', '#F39B9B');
                }
            }
        });
});
</script>

<script type="text/javascript">
  $('#frmVerRolCitas_fuasObservados').submit(function(e){
    e.preventDefault();

    /* Boton cerrar del modal */
    $('#botonCerrar_fuasObservados').click(function(){
      var tablaRolCitas_fuasObservados = $("#tablaRolCitas_fuasObservados").DataTable();
      tablaRolCitas_fuasObservados.clear().destroy();
    });
    /* Fin boton cerrar del modal */

    /* Petición AJAX */
    $.ajax({
      url: '{{ route('consultar.rolObservados') }}',
      data: $("#frmVerRolCitas_fuasObservados").serialize(),
      success: function(respuesta){
        /* console.log("respuesta",respuesta); */
      },

      error: function(jqXHR,textStatus,errorThrown){
        console.error(textStatus + " " + errorThrown);
      }
    });
    /* Fin de Petición AJAX */

    var f = new Date();

if(f.getMonth() < 10){
    var añadir_0 = '0';
}else{
    var añadir_0 = '';
}

if(f.getDate() < 10){
    var añadir_1 = '0';
}else{
    var añadir_1 = '';
}

var strDate = f.getFullYear() + "-" + añadir_0 + (f.getMonth()+1) + "-" + añadir_1 + f.getDate();
var strHour = f.getHours() + ':' + f.getMinutes();

    tablaRolCitas_fuasObservados = $("#tablaRolCitas_fuasObservados").DataTable({
        "processing": true,
	        "serverSide": true,
          "ordering": true,
	        "searching": true,
	        "retrieve": true,
	        "dom": 'Bfrtip',
	        "buttons": [
                {
			        "extend": 'excel',
			        "footer": false,
			        "title": 'ROL DE CITAS',
			        "filename": 'ROL_CITAS',
			        "text": "<button class='btn btn-success btn-sm boton-general' style='float:left;'><i class='fas fa-file-excel'></i> Exportar a Excel</button>",
                    action: function ( e, dt, node, config ) {
			            var myButton = this;
			            dt.one( 'draw', function () {
				            $.fn.dataTable.ext.buttons.excelHtml5.action.call(myButton, e, dt, node, config);
			            });
			            dt.page.len(-1).draw();
		            }
		        }
	        ],
            "ajax": {
                "url": '{{ route('consultar.rolObservados') }}',
                "data": {'_token' : $('input[name=_token]').val(),
                       'idCab_fuasObservados' : $('#idCab_fuasObservados').val()}
            },
            "order": [[0, "asc"]],
            "colReorder": true,
	        "deferRender":true,
	        "scroller": {
		        "loadingIndicator": true
	        },
	        "scrollCollapse":true,
	        "paging":true,
	        "scrollY":400,
	        "scrollX":true,
            "columns": [
		        {"data": 'nroSesion',"name": 'nroSesion'},
                {"data":'fechaProgramada',"name":'fechaProgramada'},             
                {"data":'abreviatura',"name":'abreviatura',
                  render: function (item) {
				            return item.toUpperCase();
			            }
                },
                {"data":'atendido',"name":'atendido'},
                {"data":'Personal',"name":'Personal'},
                {"data":'Comprobante',"name":'Comprobante'},
                {"data":'notas',"name":'notas'},
                {"data":'fecha',"name":'fecha',"targets": [ 7 ],"visible": false},
                {"data":'hora',"name":'hora',"targets": [ 8 ],"visible": false}
            ],
            "language": {
	            "sProcessing": "Procesando...",
	            "sLengthMenu": "Mostrar _MENU_ registros",
	            "sZeroRecords": "No se encontraron resultados",
	            "sEmptyTable": "Ningún dato disponible en esta tabla",
	            "sInfo": "Mostrando registros del _START_ al _END_",
	            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
	            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
	            "sInfoPostFix": "",
	            "sSearch": "Buscar:",
	            "sUrl": "",
	            "sInfoThousands": ",",
	            "sLoadingRecords": "Cargando...",
	            "oPaginate": {
	              "sFirst": "Primero",
	              "sLast": "Último",
	              "sNext": "Siguiente",
	              "sPrevious": "Anterior"
	            },
	            "oAria": {
	              "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
	              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
	            },
		          "buttons": {
			          "copy": 'Copiar',
			          "csv": 'CSV',
			          "print": 'Imprimir',
			          "copySuccess": {
				          "1": "1 fila copiada al portapapeles",
				          "_": "%d filas copiadas al portapapeles"
			          },
			          "copyTitle": "Copiar al portapapeles",
		          },
            "select":{
			"rows":{
				0:"Haga click en una fila para seleccionarla.",
				1:"Solo 1 fila seleccionada"
			}
		}

  	        },

            "rowCallback": function(row, data, index){

if(data["fecha"] == strDate && data["hora"] >= '23:59' && data["atendido_id"] == 10) {
    $(row).css('background-color', '#F39B9B');
}else if(data["fecha"] < strDate && data["atendido_id"] == 10){
    $(row).css('background-color', '#F39B9B');
}else if(data["atendido_id"] == 2){
    $(row).css('background-color', '#F39B9B');
}
}
    });
  });
</script>

<script type="text/javascript">

$(function(){

$('#tablaFuasObservados tbody').on('click', 'tr', function (e) {
  e.preventDefault();

  if($(this).hasClass('selected')){
    $(this).removeClass('selected');
    $("#anular_verFuaF").css("display","none");
    $("#btnRolCitas_fuasObservados").css("display","none");
  }else{
    tablaFuasObservados.$('tr.selected').removeClass('selected');
    $(this).addClass('selected');
    /* console.log(tablaFuasObservados.row($(this)).data()); */

    //=====================================================================INICIO
            //EXTRAER INFORMACIÓN DEL FUA EXISTENTE
    //===========================================================================
    $("#idFua_fuasObservados").val(tablaFuasObservados.row($(this)).data()["Fua_id"]);
    $("#idFuaF_fuasObservados").val(tablaFuasObservados.row($(this)).data()["Fua_id"]);
    $("#anular_verFuaF").attr('href',$(location).attr('href')+"/"+tablaFuasObservados.row($(this)).data()["idRegistro"]);
    $("#idRegistroF_fuasObservados").val(tablaFuasObservados.row($(this)).data()["idRegistro"]);
    $("#modeloF_fuasObservados").val(tablaFuasObservados.row($(this)).data()["Modelo"]);

    /* INICIO DE LA CONSULTA PARA VER EL ROL DE LAS CITAS */
    if(tablaFuasObservados.row($(this)).data()["numeroSesion"] != null && tablaFuasObservados.row($(this)).data()["idIdentificador"] )
    {
        $("#btnRolCitas_fuasObservados").css("display","block");
        $("#frmVerRolCitas_fuasObservados").attr('action',$(location).attr('href')+"/"+tablaFuasObservados.row($(this)).data()["idIdentificador"]);
        $("#idCab_fuasObservados").val(tablaFuasObservados.row($(this)).data()["idIdentificador"]);
    }
    else
    {
        $("#btnRolCitas_fuasObservados").css("display","none");
    }
    /* FIN DE LA CONSULTA PARA VER EL ROL DE LAS CITAS */

    $("#anular_verFuaF").unbind('click').on('click',function(e){
      e.preventDefault();

      swal({
  		 title: '¿Está seguro de eliminar este registro?',
  		 text: "¡Si no lo está puede cancelar la acción!",
  		 type: 'warning',
  		 showCancelButton: true,
  		 confirmButtonColor: '#3085d6',
  		 cancelButtonColor: '#d33',
  		 cancelButtonText: 'Cancelar',
  		 confirmButtonText: 'Si, eliminar registro!'
  	  }).then(function(result){

        if(result.value){
          swal({
            title: "Ingresar contraseña",
            input: "password",
            inputAttributes: {
              'data-lpignore': true,
              'autocomplete' : 'new-password'
            },
            showCancelButton: true,
            confirmButtonText: "Confirmar",
            cancelButtonText: "Cancelar"}).then(resultado => {
              if (resultado.value) {
                let password = resultado.value;
                let usuarioExtraer = $("#usuario_fuasObservados").val();
                let idRegistro = $("#idRegistroF_fuasObservados").val();
                let idModelo = $("#modeloF_fuasObservados").val();
                let idFua = $("#idFua_fuasObservados").val();
                
                /* INICIO VALIDACION DE CONTRASEÑA */
                  $.ajax({
                  url: '{{ route('consultar.validarPasswordFuaObservados') }}',
                  data: {password,usuarioExtraer,idRegistro,idModelo,idFua},
                  success: function(respuesta){
                    /* console.log("respuesta",respuesta); */

                    if(respuesta == 'DIFERENTES'){
                      swal("La contraseña es incorrecta");
                    }

                    if(respuesta == 'IGUALES'){

                      swal({
                          type:"success",
                          title: "¡El FUA ha sido retirado de la programación correctamente!",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"

                      }).then(function(result){
                            if(result.value){
                                tablaFuasObservados.ajax.reload(null,false);
                                $('#verFua').modal('hide');
                                $("#anular_verFuaF").css("display","none");
                                $("#btnRolCitas_fuasObservados").css("display","none");
                            }
                      });
                    }
                  },

                  error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                  }
                  });
              }
          });
        }
      });                
    });

    $("#anular_verFuaF").css("display","block");
    $("#frmVerFua_fuasObservados").attr('action',$(location).attr('href')+"/"+tablaFuasObservados.row($(this)).data()["Fua_id"]);

  }
  });
});

</script>

<script type="text/javascript">
                  $("#codigoCieNF_fuasObservados").keypress(function(e) {
                      if(e.which == 13) {
                        e.preventDefault();

                        var CodigoCie = $("#codigoCieNF_fuasObservados").val();

                        if (CodigoCie != '') {
                          /* Petición AJAX */
                          $.ajax({
                            url: '{{ route('consultar.codigoCieObservados') }}',
                            data: {CodigoCie},
                            success: function(respuesta){
                              /* console.log(respuesta); */
                              var arreglo = JSON.parse(respuesta);

                              if(arreglo == ""){
                                alert("El Código CIE-10 no existe");
                                $("#codigoCieNF_fuasObservados").val("");
                                $("#codigoCieF_fuasObservados").val("");
                              }else{
                                for(var x=0;x<arreglo.length;x++){
                                    $("#codigoCieNF_fuasObservados").val(arreglo[x].cie_cod);
                                    $("#codigoCieF_fuasObservados").val(arreglo[x].cie_desc);
                                }
                              }
                            },

                            error: function(jqXHR,textStatus,errorThrown){
                                console.error(textStatus + " " + errorThrown);
                            }
                          })
                        /* Fin de Petición AJAX */
                        }else{
                          alert('Escriba el Código CIE-10.!');
                          $('#codigoCieNF_fuasObservados').focus();
                          $("#codigoCieNF_fuasObservados").val("");
                        }
                      }
                    });

                    $("#actualizar_verFuaF_fuasObservados").on('click',function(){

                      /* PONEMOS TODOS LOS VALORES QUE SE VAN A PODER EDITAR */
                      $("#cerrar_actualizarFuaF_fuasObservados").css("display","none");
                      $("#imprimir_actualizarFuaF_fuasObservados").css("display","none");
                      $("#actualizar_actualizarFuaF_fuasObservados").css("display","none");
                      $("#anular_anularFuaF").css("display","none");
                      $("#registrar_actualizarFuaF_fuasObservados").css("display","block");
                      $("#cancelar_actualizarFuaF_fuasObservados").css("display","block");
                      $("#nombresApellidosP_fuasObservados").removeAttr("readonly");

                      var idCodigoPrestacional = $("#codigoPrestacionalF_fuasObservados").val();

                      if(idCodigoPrestacional == '065'){
                        $("#tipoAtencionF_fuasObservados").attr('readonly','readonly');
                      }else{
                        $("#tipoAtencionF_fuasObservados").removeAttr("readonly");
                      }

                      $("#codigoPrestacionalF_fuasObservados").removeAttr("readonly");
                      $("#botonCerrarVerFua_fuasObservados").css("display","none");
                      
                      $("#cancelar_actualizarFuaF_fuasObservados").on('click',function(){

                        $("#actualizarFuaF_fuasObservados").validate().resetForm();
                        $("#cerrar_actualizarFuaF_fuasObservados").css("display","block");
                        $("#imprimir_actualizarFuaF_fuasObservados").css("display","block");
                        $("#actualizar_actualizarFuaF_fuasObservados").css("display","block");
                        $("#anular_anularFuaF").css("display","block");
                        $("#registrar_actualizarFuaF_fuasObservados").css("display","none");
                        $("#cancelar_actualizarFuaF_fuasObservados").css("display","none");
                        $("#nombresApellidosP_fuasObservados").attr('readonly','readonly');
                        $("#tipoAtencionF_fuasObservados").attr('readonly','readonly');
                        $("#codigoPrestacionalF_fuasObservados").attr('readonly','readonly');
                        $("#botonCerrarVerFua_fuasObservados").css("display","block");
                      });

                      $("#registrar_actualizarFuaF1_fuasObservados").on('click',function(){

                        if($("#actualizarFuaF_fuasObservados").valid() == false){
		                      return;
	                      }

                        let personalAtiendeF_fuasObservados = $("#personalAtiendeF_fuasObservados").val();
	                      let lugarAtencionF_fuasObservados = $("#lugarAtencionF_fuasObservados").val();
	                      /* let tipoAtencionF_fuasObservados = $("#tipoAtencionF_fuasObservados").val(); */
	                      let codigoReferenciaF_fuasObservados = $("#codigoReferenciaF_fuasObservados").val();
	                      let descripcionReferenciaF_fuasObservados = $("#descripcionReferenciaF_fuasObservados").val();
	                      let numeroReferenciaF_fuasObservados = $("#numeroReferenciaF_fuasObservados").val();
	                      let tipoDocumentoF_fuasObservados = $("#tipoDocumentoF_fuasObservados").val();
	                      let numeroDocumentoF_fuasObservados = $("#numeroDocumentoF_fuasObservados").val();
	                      let componenteF_fuasObservados = $("#componenteF_fuasObservados").val();
	                      let codigoAsegurado2F_fuasObservados = $("#codigoAsegurado2F_fuasObservados").val();
	                      let codigoAsegurado3F_fuasObservados = $("#codigoAsegurado3F_fuasObservados").val();
	                      let apellidoPaternoF_fuasObservados = $("#apellidoPaternoF_fuasObservados").val();
	                      let apellidoMaternoF_fuasObservados = $("#apellidoMaternoF_fuasObservados").val();
	                      let primerNombreF_fuasObservados = $("#primerNombreF_fuasObservados").val();
	                      let sexoF_fuasObservados = $("#sexoF_fuasObservados").val();
	                      let fechaNacimientoF_fuasObservados = $("#fechaNacimientoF_fuasObservados").val();
	                      let fechaF_fuasObservados = $("#fechaF_fuasObservados").val();
	                      let horaF_fuasObservados = $("#horaF_fuasObservados").val();
	                      let codigoPrestacionalF_fuasObservados = $("#codigoPrestacionalF_fuasObservados").val();
	                      let conceptoPrestacionalF_fuasObservados = $("#conceptoPrestacionalF_fuasObservados").val();
	                      let destinoAseguradoF_fuasObservados = $("#destinoAseguradoF_fuasObservados").val();
                        let historiaF_fuasObservados = $("#historiaF_fuasObservados").val();

                        $('#actualizarFuaF_fuasObservados').submit(function(e){

                          if($("#actualizarFuaF_fuasObservados").valid() == false){
		                        return;
	                        }
          
                          e.preventDefault();

                          swal({
                            title: '¿Está seguro de actualizar los datos del FUA?',
                            text: "¡Si no lo está puede cancelar la acción!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Si, actualizar FUA!'
                          }).then(function(result){
                            if(result.value){
                              $.ajax({
                                url: '{{ route('consultar.actualizarFuaObservados') }}',
                                method: "POST",
                                data: $("#actualizarFuaF_fuasObservados").serialize(),
                                  success:function(respuesta){
                                    /* console.log("respuesta",respuesta); *//* HASTA ESTE PUNTO TENEMOS QUE PROBAR LA ACTUALIZACIÓN */

                                    if(respuesta == 'NO-VALIDACION'){
                                      swal("Existen valores incorrectos");
                                    }

                                    if(respuesta == "ACTUALIZAR-FUA"){
                                      swal({
                                        type:"success",
                                        title: "¡El FUA ha sido actualizado correctamente!",
                                        showConfirmButton: true,
                                        confirmButtonText: "Cerrar"
                                      }).then(function(result){
                                        $("#cerrar_actualizarFuaF_fuasObservados").css("display","block");
                                        $("#imprimir_actualizarFuaF_fuasObservados").css("display","block");
                                        $("#actualizar_actualizarFuaF_fuasObservados").css("display","block");
                                        $("#anular_anularFuaF").css("display","block");
                                        $("#registrar_actualizarFuaF_fuasObservados").css("display","none");
                                        $("#cancelar_actualizarFuaF_fuasObservados").css("display","none");

                                        $('#nombresApellidosP_fuasObservados').attr('readonly','readonly');
                                        $("#tipoAtencionF_fuasObservados").attr('readonly','readonly');
                                        $("#codigoPrestacionalF_fuasObservados").attr('readonly','readonly');
                                        $("#botonCerrarVerFua_fuasObservados").css("display","block");
                                      });
                                    }
                                  },
                                  error: function (jqXHR, textStatus, errorThrown) {
                                      console.error(textStatus + " " + errorThrown);
                                  }
                              });
                            }
                          });

                        });
                      });
                      /* FIN DE LOS VALORES QUE SE VAN A EDITAR */

                      $('select[name=nombresApellidosP_fuasObservados]').change(function(){
                        var idPersonal = $("#nombresApellidosP_fuasObservados").val();
                        /* console.log(idPersonal); */

                        if(idPersonal != ''){
                          $.ajax({
                            url: '{{ route('consultar.personalCObservados') }}',
                            data: {idPersonal},
                            success: function(respuesta){
                              /* console.log("respuesta",respuesta); */
                              var arregloPersonalC = JSON.parse(respuesta);
                              for(var x=0;x<arregloPersonalC.length;x++){
                                if(arregloPersonalC[x].ddi_cod == 1){
                                  $("#tipoDocumentoP_fuasObservados").val('D.N.I.');
                                }else{
                                  $("#numeroDocumentoP_fuasObservados").val('');
                                }
  
                                $("#numeroDocumentoP_fuasObservados").val(arregloPersonalC[x].ddi_nro);
                                $("#tipoPersonalSaludF_fuasObservados").val(arregloPersonalC[x].TipoPersonalSalud_id).trigger('change.select2');/* CORREGIR */
                                $("#egresadoF_fuasObservados").val(arregloPersonalC[x].Egresado_id).trigger('change.select2');/* CORREGIR */
                                $("#especialidadF_fuasObservados").val(arregloPersonalC[x].Especialidad_id).trigger('change.select2');/* CORREGIR */
                                $("#colegiaturaF_fuasObservados").val(arregloPersonalC[x].NroColegiatura);
                                $("#rneF_fuasObservados").val(arregloPersonalC[x].NroRNE);
                              }
                            },

                            error: function(jqXHR,textStatus,errorThrown){
                              console.error(textStatus + " " + errorThrown);
                            }
                          });
                        }else{
                          $("#tipoDocumentoP_fuasObservados").val('');
                          $("#numeroDocumentoP_fuasObservados").val('');
                          $("#tipoPersonalSaludF_fuasObservados").val('').trigger('change.select2');/* CORREGIR */
                          $("#egresadoF_fuasObservados").val('').trigger('change.select2');/* CORREGIR */
                          $("#especialidadF_fuasObservados").val('').trigger('change.select2');/* CORREGIR */
                          $("#colegiaturaF_fuasObservados").val('');
                          $("#rneF_fuasObservados").val('');
                        }
                      /* FIN DE PERSONAL DATOS GENERALES */
                      });

                      $('select[name=codigoPrestacionalF_fuasObservados]').change(function(){
                        
                        var idCodigoPrestacional = $("#codigoPrestacionalF_fuasObservados").val();

                        if(idCodigoPrestacional == '065'){
                          swal({
                              title: '¿Se generarán cambios en el "Tipo de atención"?',
                              text: "¡Si no lo está puede cancelar la acción!",
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              cancelButtonText: 'Cancelar',
                              confirmButtonText: 'Si, seleccionar 065!'
                            }).then(function(result){
                              if(result.value){
                                if(idCodigoPrestacional == '065'){
                                  $(".hospitalizacion_oculto").css("display","block");
                                  $("#fechaAltaF_fuasObservados").val($("#fechaF_fuasObservados").val());
                                  $("#tipoAtencionF_fuasObservados").val("").trigger("change");/* CORREGIR */
                                  $("#tipoAtencionF_fuasObservados").attr('readonly','readonly');
                                }else{
                                  $(".hospitalizacion_oculto").css("display","none");
                                  $("#fechaAltaF_fuasObservados").val("");
                                  $("#tipoAtencionF_fuasObservados").val(1).trigger("change");/* CORREGIR */
                                }
                              }
                            });
                          }else{
                            $(".hospitalizacion_oculto").css("display","none");
                            $("#fechaAltaF_fuasObservados").val("");
                            $("#tipoAtencionF_fuasObservados").val(1).trigger("change");/* CORREGIR */
                            $("#tipoAtencionF_fuasObservados").removeAttr("readonly");
                          }
                      });
                    });

                    /* IMPRIMIR FUA CON EL ID DE ATENCIÓN */
                    $('#imprimir_verFuaF_fuasObservados').click(function(){

                      var IdAtencion = $("#atencionIdF_fuasObservados").val();
                      /* console.log(IdAtencion); */

                      printJS({printable:ruta+'/'+'fuasObservados/reportesFUA/'+IdAtencion, type:'pdf', showModal:true});
                    });
                    /* FIN DE AJAX PARA EXTRAER VALORES DEL FUA */
 
</script>

@endsection

@section('javascript')
    <script src="{{ url('/') }}/js/fuasObservados.js"></script>
@endsection

@endif

@endforeach