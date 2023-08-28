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
            <h1>FUAS/CG Emitidos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
              <li class="breadcrumb-item active">FUAS-CG Emitidos</li>
            </ol>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-12">
            <div class="input-group">
              <label for="email" class="col-md-2 control-label">Unidad Orgánica:</label>

              <div class="col-md-4" id="lista_unidadOrganica1"><!-- Información desde Javascript (pacientesCitados.js) --></div>

              <label for="email" class="col-md-2 control-label">Grupo Profesional:</label>

              <div class="col-md-4" id="lista_grupoProfesional1"><!-- Información desde Javascript (pacientesCitados.js) --></div>
            </div>

            <div class="input-group">
              <label for="email" class="col-md-2 control-label">Profesional:</label>

              <div class="col-md-4" id="lista_profesionales1"><!-- Información desde Javascript (pacientesCitados.js) --></div>

              <label for="email" class="col-md-2 control-label">Paciente:</label>

              <div class="col-md-4" id="lista_paciente1"><!-- Información desde Javascript (pacientesCitados.js) --></div>
            </div>

            <form method="GET" action="{{ url('/') }}/fuasEmitidos/buscarPorMes" id="frmFechas_fuasEmitidos">
              @csrf
                <div class="input-group">
                  <label for="email" class="col-md-2 control-label">Fecha de cita / Atención:</label>

                  <div class="col-md-2">
                    <input type="date" class="form-control" name="fechaInicio_fuasEmitidos" id="fechaInicio_fuasEmitidos"
                    style="text-transform: uppercase;" required>
                  </div>

                  <div class="col-md-2">
                    <input type="date" class="form-control" name="fechaFin_fuasEmitidos" id="fechaFin_fuasEmitidos"
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

                    <label for="historiaBD_fuasEmitidos" class="col-md-1 control-label" style="">N° Historia:</label>
                    <div class="col-md-1">
                        <input type="text" class="form-control inputRutaNumero" name="historiaBD_fuasEmitidos" id="historiaBD_fuasEmitidos"
                        style="text-transform: uppercase;" maxlength="6">
                    </div>

                    <label for="documentoBD_fuasEmitidos" class="col-md-1 control-label">N° Documento:</label>
                    <div class="col-md-1">
                        <input type="text" class="form-control inputRutaNumero" name="documentoBD_fuasEmitidos" id="documentoBD_fuasEmitidos"
                        style="text-transform: uppercase;" maxlength="9">
                    </div>

                    <label for="fuaBD_fuasEmitidos" class="col-md-1 control-label">N° FUA:</label>
                    <div class="col-md-1">
                        <input type="text" class="form-control inputRutaNumero" name="fuaBD_fuasEmitidos" id="fuaBD_fuasEmitidos"
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
                
                <div id="anular_anularFuaF">
                    <a href="" class="btn btn-danger btn-sm boton-general" id="anular_verFuaF" style="float:left;display:none;margin-right: 5px;">
                        <i class="fa fa-times" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Liberar N° de Fua
                    </a>
                </div>

                <form method="GET" action="" id="frmVerRolCitas_fuasEmitidos">
                  @csrf
                  <input type="text" class="form-control" name="idCab_fuasEmitidos" id="idCab_fuasEmitidos"
                  style="text-transform: uppercase;display:none;" required>

                  <button type="submit" class="btn btn-dark btn-sm boton-general" data-toggle="modal" data-target="#verRolCitas"  
                  style="float:left;display:none;margin-right: 5px;" id="btnRolCitas_fuasEmitidos"> 
							    <i class="fa fa-tasks" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Rol de Citas</button>
                </form>
              </div>

              <div class="card-body">

                <table class="table table-bordered table-striped display nowrap" width="100%"
                 id="tablaFuasEmitidos">

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

<div class="modal fade" id="verRolCitas" data-keyboard="false" data-backdrop="static"> 
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

          <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
              <h4 class="modal-tittle" style="color:white;"><i class="fa fa-pencil"></i> &nbsp; <span class="title-form">Rol de Citas</span></h4>
              <button type="button" id="botonCerrar_fuasEmitidos" class="close" data-dismiss="modal">&times;</button>
          </div>

          <div class="modal-body" id="modal-container">
            <table class="table table-bordered table-striped display nowrap" width="100%"
                   id="tablaRolCitas_fuasEmitidos">

              <thead>
                  <tr style="background:white;" role="row">
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

<div class="modal fade bd-example-modal-lg" id="verFua" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-content">
            <form method="POST" action="{{ url('/') }}/fuasEmitidos/actualizarFua" enctype="multipart/form-data" id="actualizarFuaF_fuasEmitidos"
            pagina="fuasEmitidos">
            @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;"><i class="fa fa-pencil"></i> &nbsp; <span class="title-form">FUA</span></h4>
                    <button type="button" id="botonCerrarVerFua_fuasEmitidos" class="close" data-dismiss="modal">&times;</button>
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
                                <input type="text" class="form-control" name="usuario_fuasEmitidos" id="usuario_fuasEmitidos"
                                value="{{ old('id',$element->id) }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                        @endforeach
                    </div>

                    <div class="form-group">
                      <div class="row" style="display:none;">
                        <div class="col-sm-12">
                          <label for="idFuaF_fuasEmitidos">Id del Fua</label>
                          <input type="text" class="form-control" name="idFuaF_fuasEmitidos" id="idFuaF_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-4">
                          <label for="disaF_fuasEmitidos">N° del formato</label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-1">
                        <input type="text" class="form-control" name="disaF_fuasEmitidos" id="disaF_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-1">
                        <input type="text" class="form-control" name="loteF_fuasEmitidos" id="loteF_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-2">
                        <input type="text" class="form-control" name="numeroF_fuasEmitidos" id="numeroF_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>
                      </div>
                    </div>

                    <hr>

                    <div class="form-group">
							        <div class="row">
								        <div class="col-sm-4">
									        <label for="personalAtiendeF_fuasEmitidos">Personal que atiende <span class="text-danger"> * </span></label>
                          <select class="form-control select-2 select2" name="personalAtiendeF_fuasEmitidos" id="personalAtiendeF_fuasEmitidos">
                              <option value="">-- Seleccionar el Personal --</option>
                              @foreach($personalAtiende as $key => $value_personalAtiende)
                                  <option value="{{$value_personalAtiende->id}}">{{$value_personalAtiende->descripcion}}</option>
                              @endforeach
                          </select>
                        </div>

                        <div class="col-sm-4">
									        <label for="lugarAtencionF_fuasEmitidos">Lugar de Atención <span class="text-danger"> * </span></label>
                          <select class="form-control select-2 select2" name="lugarAtencionF_fuasEmitidos" id="lugarAtencionF_fuasEmitidos">
                              <option value="">-- Seleccionar el Lugar de Atención --</option>
                              @foreach($lugarAtencion as $key => $value_lugarAtencion)
                                  <option value="{{$value_lugarAtencion->id}}">{{$value_lugarAtencion->descripcion}}</option>
                              @endforeach
                          </select>
                        </div>

                        <div class="col-sm-4">
									        <label for="tipoAtencionF_fuasEmitidos">Tipo de Atención</label>
                          <select class="form-control select-2 select2" name="tipoAtencionF_fuasEmitidos" id="tipoAtencionF_fuasEmitidos">
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
									        <label for="historiaClinica_fuasEmitidos">Referencia realizada por <span class="text-danger"> * </span></label>
                        </div>

                        <div class="col-sm-4">
									        <label for="historiaClinica_fuasEmitidos">N° Hoja de Referencia <span class="text-danger"> * </span></label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="codigoReferenciaF_fuasEmitidos" id="codigoReferenciaF_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-6">
                          <input type="text" class="form-control" name="descripcionReferenciaF_fuasEmitidos" id="descripcionReferenciaF_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-4">
                          <input type="text" class="form-control" name="numeroReferenciaF_fuasEmitidos" id="numeroReferenciaF_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-4">
                          <label for="tipoDocumentoF_fuasEmitidos">Identidad del Asegurado <span class="text-danger"> * </span></label>
                        </div>

                        <div class="col-sm-4">
                          <label for="componenteF_fuasEmitidos">Componente <span class="text-danger"> * </span></label>
                        </div>

                        <div class="col-sm-4">
                          <label for="codigoAsegurado1F_fuasEmitidos">Código del Asegurado <span class="text-danger"> * </span></label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="tipoDocumentoF_fuasEmitidos" id="tipoDocumentoF_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="numeroDocumentoF_fuasEmitidos" id="numeroDocumentoF_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-4">
                          <select class="form-control select-2 select2" name="componenteF_fuasEmitidos" id="componenteF_fuasEmitidos">
                            <option value="">-- Seleccionar el Componente --</option>
                            @foreach($componente as $key => $value_componente)
                              <option value="{{$value_componente->id}}">{{$value_componente->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-sm-1">
                          <input type="text" class="form-control" name="codigoAsegurado1F_fuasEmitidos" id="codigoAsegurado1F_fuasEmitidos"
                          style="text-transform: uppercase;" readonly="true">
                        </div>

                        <div class="col-sm-1">
                          <input type="text" class="form-control" name="codigoAsegurado2F_fuasEmitidos" id="codigoAsegurado2F_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="codigoAsegurado3F_fuasEmitidos" id="codigoAsegurado3F_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-6">
                          <label for="apellidoPaternoF_fuasEmitidos">Apellido Paterno <span class="text-danger"> * </span></label>
                          <input type="text" class="form-control" name="apellidoPaternoF_fuasEmitidos" id="apellidoPaternoF_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-6">
                          <label for="apellidoMaternoF_fuasEmitidos">Apellido Materno <span class="text-danger"> * </span></label>
                          <input type="text" class="form-control" name="apellidoMaternoF_fuasEmitidos" id="apellidoMaternoF_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-6">
                          <label for="primerNombreF_fuasEmitidos">Primer Nombre <span class="text-danger"> * </span></label>
                          <input type="text" class="form-control" name="primerNombreF_fuasEmitidos" id="primerNombreF_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-6">
                          <label for="otroNombreF_fuasEmitidos">Otros Nombres</label>
                          <input type="text" class="form-control" name="otroNombreF_fuasEmitidos" id="otroNombreF_fuasEmitidos"
                          style="text-transform: uppercase;" readonly="true">
                        </div>

                        <!-- PARA SELECCIONAR EL ID DEL PACIENTE -->
                        <input type="text" class="form-control" name="pacienteIdF_fuasEmitidos" id="pacienteIdF_fuasEmitidos"
                        style="text-transform: uppercase;display:none;" readonly="true">
                        <!-- FIN PARA SELECCIONAR EL ID DEL PACIENTE -->

                        <!-- PARA SELECCIONAR EL ID DEL FUA -->
                        <input type="text" class="form-control" name="atencionIdF_fuasEmitidos" id="atencionIdF_fuasEmitidos"
                        style="text-transform: uppercase;display:none;" readonly="true">
                        <!-- FIN PARA SELECCIONAR EL ID DEL FUA -->
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-4">
                          <label for="sexoF_fuasEmitidos">Sexo <span class="text-danger"> * </span></label>
                          <input type="text" class="form-control" name="sexoF_fuasEmitidos" id="sexoF_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-4">
                          <label for="fechaNacimientoF_fuasEmitidos">Fecha de Nacimiento <span class="text-danger"> * </span></label>
                          <input type="date" class="form-control" name="fechaNacimientoF_fuasEmitidos" id="fechaNacimientoF_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-4">
                          <label for="historiaF_fuasEmitidos">Historia <span class="text-danger"> * </span></label>
                          <input type="text" class="form-control" name="historiaF_fuasEmitidos" id="historiaF_fuasEmitidos"
                          style="text-transform: uppercase;" readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-3">
                          <label for="fechaF_fuasEmitidos">Fecha de Atención <span class="text-danger"> * </span></label>
                          <input type="date" class="form-control" name="fechaF_fuasEmitidos" id="fechaF_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-2">
                          <label for="horaF_fuasEmitidos">Hora de Atención <span class="text-danger"> * </span></label>
                          <input type="time" class="form-control" name="horaF_fuasEmitidos" id="horaF_fuasEmitidos"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-7">
                          <label for="codigoPrestacionalF_fuasEmitidos">Código Prestacional <span class="text-danger"> * </span></label>
                          <select class="form-control select2" name="codigoPrestacionalF_fuasEmitidos" id="codigoPrestacionalF_fuasEmitidos" data-placeholder="Seleccionar código prestacional">
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
                          <label for="conceptoPrestacionalF_fuasEmitidos">Concepto Prestacional <span class="text-danger"> * </span></label>
                          <select class="form-control select2" name="conceptoPrestacionalF_fuasEmitidos" id="conceptoPrestacionalF_fuasEmitidos">
                            <option value="">-- Seleccionar concepto prestacional --</option>
                            @foreach($concPrestacional as $key => $value_concPrestacional)
                              <option value="{{$value_concPrestacional->id}}">{{$value_concPrestacional->id}} - {{$value_concPrestacional->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-sm-6">
                          <label for="destinoAseguradoF_fuasEmitidos">Destino del Asegurado <span class="text-danger"> * </span></label>
                          <select class="form-control select-2 select2" name="destinoAseguradoF_fuasEmitidos" id="destinoAseguradoF_fuasEmitidos">
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
                          <label for="fechaIngresoF_fuasEmitidos">Fecha de Ingreso <span id="span_fechaIngresoF_fuasEmitidos" class="text-danger"> * </span></label>
                          <input type="date" class="form-control" name="fechaIngresoF_fuasEmitidos" id="fechaIngresoF_fuasEmitidos"
                          style="text-transform: uppercase;" readonly="readonly">
                        </div>

                        <div class="col-sm-6">
                          <label for="fechaAltaF_fuasEmitidos">Fecha de Alta</label>
                          <input type="date" class="form-control" name="fechaAltaF_fuasEmitidos" id="fechaAltaF_fuasEmitidos"
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
									        <label for="diagnosticoF_fuasEmitidos">Tipo</label>
                        </div>

                        <div class="col-sm-2">
									        <label for="codigoCieNF_fuasEmitidos">CIE - 10</label>
                        </div>

                        <div class="col-sm-6">
									        <label for="codigoCieF_fuasEmitidos">Descripción</label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-4">
                          <select class="form-control select-2 select2" name="diagnosticoF_fuasEmitidos" id="diagnosticoF_fuasEmitidos">
                            <option value="">-- Seleccionar el tipo --</option>
                            <option value="P">PRESUNTIVO</option>
                            <option value="D">DEFINITIVO</option>
                            <option value="R">REPETIDO</option>
                          </select>
                        </div>

                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="codigoCieNF_fuasEmitidos" id="codigoCieNF_fuasEmitidos"
                          style="text-transform: uppercase;" readonly="readonly">
                        </div>

                        <div class="col-sm-6">
                          <input type="text" class="form-control" name="codigoCieF_fuasEmitidos" id="codigoCieF_fuasEmitidos"
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
                          <label for="tipoDocumentoP_fuasEmitidos">Identidad del Profesional</label>
                        </div>

                        <div class="col-sm-8">
                          <label for="nombresApellidosP_fuasEmitidos">Nombres y Apellidos <span class="text-danger"> * </span></label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="tipoDocumentoP_fuasEmitidos" id="tipoDocumentoP_fuasEmitidos"
                          style="text-transform: uppercase;" readonly="true">
                        </div>

                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="numeroDocumentoP_fuasEmitidos" id="numeroDocumentoP_fuasEmitidos"
                          style="text-transform: uppercase;" readonly="true">
                        </div>

                        <div class="col-sm-8">
                          <select class="form-control select2" name="nombresApellidosP_fuasEmitidos" id="nombresApellidosP_fuasEmitidos" data-placeholder="Seleccionar el profesional">
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
                          <label for="tipoPersonalSaludF_fuasEmitidos">Tipo de Personal de Salud</label>
                        </div>

                        <div class="col-sm-4">
                          <label for="colegiaturaF_fuasEmitidos">Colegiatura</label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-6">
                          <select class="form-control select-2 select2" name="tipoPersonalSaludF_fuasEmitidos" id="tipoPersonalSaludF_fuasEmitidos">
                            <option value="">-- Seleccionar el Tipo de Personal de Salud --</option>
                            @foreach($sisTipoPersonalSalud as $key => $value_sisTipoPersonalSalud)
                              <option value="{{$value_sisTipoPersonalSalud->id}}">{{$value_sisTipoPersonalSalud->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-sm-2">
                          <select class="form-control select-2 select2" name="egresadoF_fuasEmitidos" id="egresadoF_fuasEmitidos">
                            <option value="">-- Seleccionar si es Egresado --</option>
                            @foreach($sisEgresado as $key => $value_sisEgresado)
                              <option value="{{$value_sisEgresado->id}}">{{$value_sisEgresado->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-sm-4">
                          <input type="text" class="form-control" name="colegiaturaF_fuasEmitidos" id="colegiaturaF_fuasEmitidos"
                          style="text-transform: uppercase;" readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-8">
                          <label for="especialidadF_fuasEmitidos">Especialidad</label>
                        </div>

                        <div class="col-sm-4">
                          <label for="rneF_fuasEmitidos">RNE</label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-8">
                          <select class="form-control select-2 select2" name="especialidadF_fuasEmitidos" id="especialidadF_fuasEmitidos">
                            <option value="">-- Seleccionar la Especialidad --</option>
                            @foreach($sisEspecialidad as $key => $value_sisEspecialidad)
                              <option value="{{$value_sisEspecialidad->id}}">{{$value_sisEspecialidad->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-sm-4">
                          <input type="text" class="form-control" name="rneF_fuasEmitidos" id="rneF_fuasEmitidos"
                          style="text-transform: uppercase;" readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group" style="display:none;">
                      <div class="row">
                        <div class="col-sm-12">
                          <input type="text" name="idRegistroF_fuasEmitidos" id="idRegistroF_fuasEmitidos">
                          <input type="text" name="modeloF_fuasEmitidos" id="modeloF_fuasEmitidos">
                        </div>
                      </div>
                    </div>

                    
                </div>

                <div class="modal-footer d-flex">
                    <div id="cerrar_actualizarFuaF">
                      <button type="button" class="btn btn-default boton-general" data-dismiss="modal">
                        <i class="fa fa-undo" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Salir
                      </button>
                    </div>

                    <div id="imprimir_actualizarFuaF">
                      <button type="button" class="btn btn-dark boton-general" id="imprimir_verFuaF">
                        <i class="fas fa-print" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Imprimir
                      </button>
                    </div>

                    <div id="actualizar_actualizarFuaF">
                      <button type="button" class="btn btn-info boton-general" id="actualizar_verFuaF">
                        <i class="fa fa-pencil" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Actualizar
                      </button>
                    </div>

                    <div id="registrar_actualizarFuaF" style="display:none;">
                      <button type="submit" class="btn btn-success boton-general" id="registrar_actualizarFuaF1">
                        <i class="fas fa-save" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Guardar datos
                      </button>
                    </div>

                    <div id="cancelar_actualizarFuaF" style="display:none;">
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

   $("#fechaInicio_fuasEmitidos").val(currentDate);
   $("#fechaFin_fuasEmitidos").val(currentDate);
</script> -->

<script type="text/javascript">
$("#historiaBD_fuasEmitidos").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        let numHistoriaBD_fuasEmitidos = $("#historiaBD_fuasEmitidos").val();
        
        if (numHistoriaBD_fuasEmitidos != ''){

            tablaFuasEmitidos.clear().destroy();

            $("#anular_verFuaF").css("display","none");
            $("#btnRolCitas_fuasEmitidos").css("display","none");
            $("#documentoBD_fuasEmitidos").val("");

            $.ajax({
                url: '{{ route('consultar.historiaBD_fuasEmitidos') }}',
                data: {numHistoriaBD_fuasEmitidos},
                success: function(respuesta){
                    console.log("respuesta",respuesta);
                },

                error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                }
            });

        /*=============================================
        DataTable de Fuas Emitidos
        =============================================*/
        tablaFuasEmitidos = $("#tablaFuasEmitidos").DataTable({
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
			        "title": 'PROGRAMACIÓN FUA-SIS EMITIDOS',
			        "filename": 'PROGRAMACION_FUAS_EMITIDOS',
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
                "url": '{{ route('consultar.historiaBD_fuasEmitidos') }}',
                "data": {'numHistoriaBD_fuasEmitidos' : $("#historiaBD_fuasEmitidos").val()}
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

		          unidadOrganica(tablaFuasEmitidos);
		          profesionales(tablaFuasEmitidos);
		          grupoProfesionales(tablaFuasEmitidos);
		          pacientes(tablaFuasEmitidos);
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
		        tablaFuasEmitidos.search(this.value).draw();
	        }
        });
        /* FIN DE BUSQUEDA POR ENTER */

        }else{
            $('#historiaBD_fuasEmitidos').focus();

            tablaFuasEmitidos.clear().destroy();
            $("#anular_verFuaF").css("display","none");
            $("#btnRolCitas_fuasEmitidos").css("display","none");

            let valorUrlAjaxHistoriaBD_fuasEmitidos = '';

            if($('#fechaInicio_fuasEmitidos').val() != '' || $('#fechaFin_fuasEmitidos').val() != ''){
                valorUrlAjaxHistoriaBD_fuasEmitidos = '{{ route('consultar.fechasFEmitidos') }}';
            }else{
                valorUrlAjaxHistoriaBD_fuasEmitidos = ruta + '/fuasEmitidos';
            }

        /*=============================================
        DataTable de Auditoria Emitidos
        =============================================*/
        tablaFuasEmitidos = $("#tablaFuasEmitidos").DataTable({
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
			        "title": 'PROGRAMACIÓN FUA-SIS EMITIDOS',
			        "filename": 'PROGRAMACION_FUAS_EMITIDOS',
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
                "url": valorUrlAjaxHistoriaBD_fuasEmitidos,
                "data": { '_token' : $('input[name=_token]').val(),
                        'fechaInicio_pacientesCitados' : $('#fechaInicio_fuasEmitidos').val(),
                        'fechaFin_pacientesCitados': $('#fechaFin_fuasEmitidos').val()}
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

		unidadOrganica(tablaFuasEmitidos);
		profesionales(tablaFuasEmitidos);
		grupoProfesionales(tablaFuasEmitidos);
		pacientes(tablaFuasEmitidos);
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
		            tablaFuasEmitidos.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});

$("#documentoBD_fuasEmitidos").keypress(function(e){
    if(e.which == 13) {
        e.preventDefault();

        let numDocumentoBD_fuasEmitidos = $("#documentoBD_fuasEmitidos").val();
        
        if (numDocumentoBD_fuasEmitidos != ''){

            tablaFuasEmitidos.clear().destroy();

            $("#anular_verFuaF").css("display","none");
            $("#btnRolCitas_fuasEmitidos").css("display","none");
            $("#historiaBD_fuasEmitidos").val("");

            $.ajax({
                url: '{{ route('consultar.documentoBD_fuasEmitidos') }}',
                data: {numDocumentoBD_fuasEmitidos},
                success: function(respuesta){
                    console.log("respuesta",respuesta);
                },

                error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                }
            });

        /*=============================================
        DataTable de Auditoria Emitidos
        =============================================*/
        tablaFuasEmitidos = $("#tablaFuasEmitidos").DataTable({
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
			        "title": 'PROGRAMACIÓN FUA-SIS EMITIDOS',
			        "filename": 'PROGRAMACION_FUAS_EMITIDOS',
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
                "url": '{{ route('consultar.documentoBD_fuasEmitidos') }}',
                "data": {'numDocumentoBD_fuasEmitidos' : $("#documentoBD_fuasEmitidos").val()}
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

		unidadOrganica(tablaFuasEmitidos);
		profesionales(tablaFuasEmitidos);
		grupoProfesionales(tablaFuasEmitidos);
		pacientes(tablaFuasEmitidos);
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
		        tablaFuasEmitidos.search(this.value).draw();
	        }
        });
        /* FIN DE BUSQUEDA POR ENTER */
        }else{
            $('#documentoBD_fuasEmitidos').focus();

            tablaFuasEmitidos.clear().destroy();
            $("#anular_verFuaF").css("display","none");
            $("#btnRolCitas_fuasEmitidos").css("display","none");

            let valorUrlAjaxDocumentoBD_fuasEmitidos = '';

            if($('#fechaInicio_fuasEmitidos').val() != '' || $('#fechaFin_fuasEmitidos').val() != ''){
                valorUrlAjaxDocumentoBD_fuasEmitidos = '{{ route('consultar.fechasFEmitidos') }}';
            }else{
                valorUrlAjaxDocumentoBD_fuasEmitidos = ruta + '/fuasEmitidos';
            }

        /*=============================================
        DataTable de Auditoria Emitidos
        =============================================*/
        tablaFuasEmitidos = $("#tablaFuasEmitidos").DataTable({
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
			        "title": 'PROGRAMACIÓN FUA-SIS EMITIDOS',
			        "filename": 'PROGRAMACION_FUAS_EMITIDOS',
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
                "url": valorUrlAjaxDocumentoBD_fuasEmitidos,
                "data": { '_token' : $('input[name=_token]').val(),
                        'fechaInicio_pacientesCitados' : $('#fechaInicio_fuasEmitidos').val(),
                        'fechaFin_pacientesCitados': $('#fechaFin_fuasEmitidos').val()}
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

		unidadOrganica(tablaFuasEmitidos);
		profesionales(tablaFuasEmitidos);
		grupoProfesionales(tablaFuasEmitidos);
		pacientes(tablaFuasEmitidos);
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
		            tablaFuasEmitidos.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});

$("#fuaBD_fuasEmitidos").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        let numFuaBD_fuasEmitidos = $("#fuaBD_fuasEmitidos").val();
        
        if (numFuaBD_fuasEmitidos != ''){

            tablaFuasEmitidos.clear().destroy();

            $("#anular_verFuaF").css("display","none");
            $("#btnRolCitas_fuasEmitidos").css("display","none");
            $("#documentoBD_fuasEmitidos").val("");

            $.ajax({
                url: '{{ route('consultar.fuaBD_fuasEmitidos') }}',
                data: {numFuaBD_fuasEmitidos},
                success: function(respuesta){
                    console.log("respuesta",respuesta);
                },

                error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                }
            });

        /*=============================================
        DataTable de Fuas Emitidos
        =============================================*/
        tablaFuasEmitidos = $("#tablaFuasEmitidos").DataTable({
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
			        "title": 'PROGRAMACIÓN FUA-SIS EMITIDOS',
			        "filename": 'PROGRAMACION_FUAS_EMITIDOS',
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
                "url": '{{ route('consultar.fuaBD_fuasEmitidos') }}',
                "data": {'numFuaBD_fuasEmitidos' : $("#fuaBD_fuasEmitidos").val()}
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

		unidadOrganica(tablaFuasEmitidos);
		profesionales(tablaFuasEmitidos);
		grupoProfesionales(tablaFuasEmitidos);
		pacientes(tablaFuasEmitidos);
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
		        tablaFuasEmitidos.search(this.value).draw();
	        }
        });
        /* FIN DE BUSQUEDA POR ENTER */

        }else{
            $('#fuaBD_fuasEmitidos').focus();

            tablaFuasEmitidos.clear().destroy();
            $("#anular_verFuaF").css("display","none");
            $("#btnRolCitas_fuasEmitidos").css("display","none");

            let valorUrlAjaxFuaBD_fuasEmitidos = '';

            if($('#fechaInicio_fuasEmitidos').val() != '' || $('#fechaFin_fuasEmitidos').val() != ''){
                valorUrlAjaxFuaBD_fuasEmitidos = '{{ route('consultar.fechasFEmitidos') }}';
            }else{
                valorUrlAjaxFuaBD_fuasEmitidos = ruta + '/fuasEmitidos';
            }

        /*=============================================
        DataTable de Auditoria Emitidos
        =============================================*/
        tablaFuasEmitidos = $("#tablaFuasEmitidos").DataTable({
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
			        "title": 'PROGRAMACIÓN FUA-SIS EMITIDOS',
			        "filename": 'PROGRAMACION_FUAS_EMITIDOS',
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
                "url": valorUrlAjaxFuaBD_fuasEmitidos,
                "data": { '_token' : $('input[name=_token]').val(),
                        'fechaInicio_pacientesCitados' : $('#fechaInicio_fuasEmitidos').val(),
                        'fechaFin_pacientesCitados': $('#fechaFin_fuasEmitidos').val()}
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

		unidadOrganica(tablaFuasEmitidos);
		profesionales(tablaFuasEmitidos);
		grupoProfesionales(tablaFuasEmitidos);
		pacientes(tablaFuasEmitidos);
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
		            tablaFuasEmitidos.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});
</script>

<script type="text/javascript">

$('#frmFechas_fuasEmitidos').submit(function(e){
    e.preventDefault();

    tablaFuasEmitidos.clear().destroy();
    $("#anular_verFuaF").css("display","none");
    $("#btnRolCitas_fuasEmitidos").css("display","none");
  
    /* Petición AJAX */
    $.ajax({
        url: '{{ route('consultar.fechasFEmitidos') }}',
        data: $("#frmFechas_fuasEmitidos").serialize(),
        success: function(respuesta){
            /* console.log("respuesta",respuesta); */
        },

        error: function(jqXHR,textStatus,errorThrown){
            console.error(textStatus + " " + errorThrown);
        }
    });
    /* Fin de Petición AJAX */

    /*=============================================
    DataTable de Fuas Emitidos
    =============================================*/

    


    tablaFuasEmitidos = $("#tablaFuasEmitidos").DataTable({
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
            "url": '{{ route('consultar.fechasFEmitidos') }}',
            "data": { '_token' : $('input[name=_token]').val(),
                    'fechaInicio_pacientesCitados' : $('#fechaInicio_fuasEmitidos').val(),
                    'fechaFin_pacientesCitados': $('#fechaFin_fuasEmitidos').val()}
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

		unidadOrganica(tablaFuasEmitidos);
		profesionales(tablaFuasEmitidos);
		grupoProfesionales(tablaFuasEmitidos);
		pacientes(tablaFuasEmitidos);
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
    $('#frmVerRolCitas_fuasEmitidos').submit(function(e){
        console.log($('#idCab_fuasEmitidos').val());
        e.preventDefault();

        /* Boton cerrar del modal */
        $('#botonCerrar_fuasEmitidos').click(function(){
            let tablaRolCitas_fuasEmitidos = $("#tablaRolCitas_fuasEmitidos").DataTable();
            tablaRolCitas_fuasEmitidos.clear().destroy();
        });
        /* Fin boton cerrar del modal */

        /* Petición AJAX */
        $.ajax({
            url: '{{ route('consultar.rolEmitidos') }}',
            data: $("#frmVerRolCitas_fuasEmitidos").serialize(),
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
/*         console.log(strDate);
        console.log(strHour); */

        tablaRolCitas_fuasEmitidos = $("#tablaRolCitas_fuasEmitidos").DataTable({
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
                "url": '{{ route('consultar.rolEmitidos') }}',
                "data": {'_token' : $('input[name=_token]').val(),
                       'idCab_fuasEmitidos' : $('#idCab_fuasEmitidos').val()}
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

$('#tablaFuasEmitidos tbody').on('click', 'tr', function (e) {
    e.preventDefault();

    if($(this).hasClass('selected')){
        $(this).removeClass('selected');
        $("#anular_verFuaF").css("display","none");
        $("#btnRolCitas_fuasEmitidos").css("display","none");
    }else{
        tablaFuasEmitidos.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
        console.log(tablaFuasEmitidos.row($(this)).data());

        //=====================================================================INICIO
                //EXTRAER INFORMACIÓN DEL FUA EXISTENTE
        //===========================================================================
        $("#idFua_fuasEmitidos").val(tablaFuasEmitidos.row($(this)).data()["Fua_id"]);
        $("#idFuaF_fuasEmitidos").val(tablaFuasEmitidos.row($(this)).data()["Fua_id"]);
        $("#anular_verFuaF").attr('href',$(location).attr('href')+"/"+tablaFuasEmitidos.row($(this)).data()["idRegistro"]);
        $("#idRegistroF_fuasEmitidos").val(tablaFuasEmitidos.row($(this)).data()["idRegistro"]);
        $("#modeloF_fuasEmitidos").val(tablaFuasEmitidos.row($(this)).data()["Modelo"]);

        /* INICIO DE LA CONSULTA PARA VER EL ROL DE LAS CITAS */
        if(tablaFuasEmitidos.row($(this)).data()["numeroSesion"] != null && tablaFuasEmitidos.row($(this)).data()["idIdentificador"])
        {
            $("#btnRolCitas_fuasEmitidos").css("display","block");
            $("#frmVerRolCitas_fuasEmitidos").attr('action',$(location).attr('href')+"/"+tablaFuasEmitidos.row($(this)).data()["idIdentificador"]);
            $("#idCab_fuasEmitidos").val(tablaFuasEmitidos.row($(this)).data()["idIdentificador"]);
        }
        else
        {
            $("#btnRolCitas_fuasEmitidos").css("display","none");
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
                    /* html: "<textarea type='text' id='observacionA_fuasEmitidos' class='form-control' style='text-transform: uppercase;width:100%;height: 50px !important;'placeholder='Añadir motivo de anulación del FUA'></textarea>", */
                    showCancelButton: true,
                    confirmButtonText: "Confirmar",
                    cancelButtonText: "Cancelar"}).then(resultado => {
                        if (resultado.value) {
                            let password = resultado.value;
                            let observacion = $("#observacionA_fuasEmitidos").val();
                            let usuarioExtraer = $("#usuario_fuasEmitidos").val();
                            let idRegistro = $("#idRegistroF_fuasEmitidos").val();
                            let idModelo = $("#modeloF_fuasEmitidos").val();
                            let idFua = $("#idFua_fuasEmitidos").val();
                
                            /* INICIO VALIDACION DE CONTRASEÑA */
                            $.ajax({
                            url: '{{ route('consultar.validarPasswordFuaEmitidos') }}',
                            data: {password,observacion,usuarioExtraer,idRegistro,idModelo,idFua},
                            success: function(respuesta){
                                console.log("respuesta",respuesta);

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
                                          tablaFuasEmitidos.ajax.reload(null,false);
                                          $('#verFua').modal('hide');
                                          $("#anular_verFuaF").css("display","none");
                                          $("#btnRolCitas_fuasEmitidos").css("display","none");
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
    $("#frmVerFua_fuasEmitidos").attr('action',$(location).attr('href')+"/"+tablaFuasEmitidos.row($(this)).data()["Fua_id"]);

  }
  });
});

</script>

<script type="text/javascript">
    $("#codigoCieNF_fuasEmitidos").keypress(function(e) {
        if(e.which == 13) {
                        e.preventDefault();

                        var CodigoCie = $("#codigoCieNF_fuasEmitidos").val();

                        if (CodigoCie != '') {
                          /* Petición AJAX */
                          $.ajax({
                            url: '{{ route('consultar.codigoCieEmitidos') }}',
                            data: {CodigoCie},
                            success: function(respuesta){
                              /* console.log(respuesta); */
                              var arreglo = JSON.parse(respuesta);

                              if(arreglo == ""){
                                alert("El Código CIE-10 no existe");
                                $("#codigoCieNF_fuasEmitidos").val("");
                                $("#codigoCieF_fuasEmitidos").val("");
                              }else{
                                for(var x=0;x<arreglo.length;x++){
                                    $("#codigoCieNF_fuasEmitidos").val(arreglo[x].cie_cod);
                                    $("#codigoCieF_fuasEmitidos").val(arreglo[x].cie_desc);
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
                          $('#codigoCieNF_fuasEmitidos').focus();
                          $("#codigoCieNF_fuasEmitidos").val("");
                        }
        }
    });

                    $("#actualizar_verFuaF").on('click',function(){

                      /* PONEMOS TODOS LOS VALORES QUE SE VAN A PODER EDITAR */
                      $("#cerrar_actualizarFuaF").css("display","none");
                      $("#imprimir_actualizarFuaF").css("display","none");
                      $("#actualizar_actualizarFuaF").css("display","none");
                      $("#anular_anularFuaF").css("display","none");
                      $("#registrar_actualizarFuaF").css("display","block");
                      $("#cancelar_actualizarFuaF").css("display","block");
                      $("#nombresApellidosP_fuasEmitidos").removeAttr("readonly");

                      var idCodigoPrestacional = $("#codigoPrestacionalF_fuasEmitidos").val();

                      if(idCodigoPrestacional == '065'){
                        $("#tipoAtencionF_fuasEmitidos").attr('readonly','readonly');
                      }else{
                        $("#tipoAtencionF_fuasEmitidos").removeAttr("readonly");
                      }

                      $("#codigoPrestacionalF_fuasEmitidos").removeAttr("readonly");
                      $("#botonCerrarVerFua_fuasEmitidos").css("display","none");
                      
                      $("#cancelar_actualizarFuaF").on('click',function(){

                        $("#actualizarFuaF_fuasEmitidos").validate().resetForm();
                        $("#cerrar_actualizarFuaF").css("display","block");
                        $("#imprimir_actualizarFuaF").css("display","block");
                        $("#actualizar_actualizarFuaF").css("display","block");
                        $("#anular_anularFuaF").css("display","block");
                        $("#registrar_actualizarFuaF").css("display","none");
                        $("#cancelar_actualizarFuaF").css("display","none");
                        $("#nombresApellidosP_fuasEmitidos").attr('readonly','readonly');
                        $("#tipoAtencionF_fuasEmitidos").attr('readonly','readonly');
                        $("#codigoPrestacionalF_fuasEmitidos").attr('readonly','readonly');
                        $("#botonCerrarVerFua_fuasEmitidos").css("display","block");
                      });

                      $("#registrar_actualizarFuaF1").on('click',function(){

                        if($("#actualizarFuaF_fuasEmitidos").valid() == false){
		                      return;
	                      }

                        let personalAtiendeF_fuasEmitidos = $("#personalAtiendeF_fuasEmitidos").val();
	                      let lugarAtencionF_fuasEmitidos = $("#lugarAtencionF_fuasEmitidos").val();
	                      /* let tipoAtencionF_fuasEmitidos = $("#tipoAtencionF_fuasEmitidos").val(); */
	                      let codigoReferenciaF_fuasEmitidos = $("#codigoReferenciaF_fuasEmitidos").val();
	                      let descripcionReferenciaF_fuasEmitidos = $("#descripcionReferenciaF_fuasEmitidos").val();
	                      let numeroReferenciaF_fuasEmitidos = $("#numeroReferenciaF_fuasEmitidos").val();
	                      let tipoDocumentoF_fuasEmitidos = $("#tipoDocumentoF_fuasEmitidos").val();
	                      let numeroDocumentoF_fuasEmitidos = $("#numeroDocumentoF_fuasEmitidos").val();
	                      let componenteF_fuasEmitidos = $("#componenteF_fuasEmitidos").val();
	                      let codigoAsegurado2F_fuasEmitidos = $("#codigoAsegurado2F_fuasEmitidos").val();
	                      let codigoAsegurado3F_fuasEmitidos = $("#codigoAsegurado3F_fuasEmitidos").val();
	                      let apellidoPaternoF_fuasEmitidos = $("#apellidoPaternoF_fuasEmitidos").val();
	                      let apellidoMaternoF_fuasEmitidos = $("#apellidoMaternoF_fuasEmitidos").val();
	                      let primerNombreF_fuasEmitidos = $("#primerNombreF_fuasEmitidos").val();
	                      let sexoF_fuasEmitidos = $("#sexoF_fuasEmitidos").val();
	                      let fechaNacimientoF_fuasEmitidos = $("#fechaNacimientoF_fuasEmitidos").val();
	                      let fechaF_fuasEmitidos = $("#fechaF_fuasEmitidos").val();
	                      let horaF_fuasEmitidos = $("#horaF_fuasEmitidos").val();
	                      let codigoPrestacionalF_fuasEmitidos = $("#codigoPrestacionalF_fuasEmitidos").val();
	                      let conceptoPrestacionalF_fuasEmitidos = $("#conceptoPrestacionalF_fuasEmitidos").val();
	                      let destinoAseguradoF_fuasEmitidos = $("#destinoAseguradoF_fuasEmitidos").val();
                        let historiaF_fuasEmitidos = $("#historiaF_fuasEmitidos").val();

                        $('#actualizarFuaF_fuasEmitidos').submit(function(e){

                          if($("#actualizarFuaF_fuasEmitidos").valid() == false){
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
                                url: '{{ route('consultar.actualizarFuaEmitidos') }}',
                                method: "POST",
                                data: $("#actualizarFuaF_fuasEmitidos").serialize(),
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
                                        $("#cerrar_actualizarFuaF").css("display","block");
                                        $("#imprimir_actualizarFuaF").css("display","block");
                                        $("#actualizar_actualizarFuaF").css("display","block");
                                        $("#anular_anularFuaF").css("display","block");
                                        $("#registrar_actualizarFuaF").css("display","none");
                                        $("#cancelar_actualizarFuaF").css("display","none");

                                        $('#nombresApellidosP_fuasEmitidos').attr('readonly','readonly');
                                        $("#tipoAtencionF_fuasEmitidos").attr('readonly','readonly');
                                        $("#codigoPrestacionalF_fuasEmitidos").attr('readonly','readonly');
                                        $("#botonCerrarVerFua_fuasEmitidos").css("display","block");
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

                      $('select[name=nombresApellidosP_fuasEmitidos]').change(function(){
                        var idPersonal = $("#nombresApellidosP_fuasEmitidos").val();
                        /* console.log(idPersonal); */

                        if(idPersonal != ''){
                          $.ajax({
                            url: '{{ route('consultar.personalCEmitidos') }}',
                            data: {idPersonal},
                            success: function(respuesta){
                              /* console.log("respuesta",respuesta); */
                              var arregloPersonalC = JSON.parse(respuesta);
                              for(var x=0;x<arregloPersonalC.length;x++){
                                if(arregloPersonalC[x].ddi_cod == 1){
                                  $("#tipoDocumentoP_fuasEmitidos").val('D.N.I.');
                                }else{
                                  $("#numeroDocumentoP_fuasEmitidos").val('');
                                }
  
                                $("#numeroDocumentoP_fuasEmitidos").val(arregloPersonalC[x].ddi_nro);
                                $("#tipoPersonalSaludF_fuasEmitidos").val(arregloPersonalC[x].TipoPersonalSalud_id).trigger('change.select2');/* CORREGIR */
                                $("#egresadoF_fuasEmitidos").val(arregloPersonalC[x].Egresado_id).trigger('change.select2');/* CORREGIR */
                                $("#especialidadF_fuasEmitidos").val(arregloPersonalC[x].Especialidad_id).trigger('change.select2');/* CORREGIR */
                                $("#colegiaturaF_fuasEmitidos").val(arregloPersonalC[x].NroColegiatura);
                                $("#rneF_fuasEmitidos").val(arregloPersonalC[x].NroRNE);
                              }
                            },

                            error: function(jqXHR,textStatus,errorThrown){
                              console.error(textStatus + " " + errorThrown);
                            }
                          });
                        }else{
                          $("#tipoDocumentoP_fuasEmitidos").val('');
                          $("#numeroDocumentoP_fuasEmitidos").val('');
                          $("#tipoPersonalSaludF_fuasEmitidos").val('').trigger('change.select2');/* CORREGIR */
                          $("#egresadoF_fuasEmitidos").val('').trigger('change.select2');/* CORREGIR */
                          $("#especialidadF_fuasEmitidos").val('').trigger('change.select2');/* CORREGIR */
                          $("#colegiaturaF_fuasEmitidos").val('');
                          $("#rneF_fuasEmitidos").val('');
                        }
                      /* FIN DE PERSONAL DATOS GENERALES */
                      });

                      $('select[name=codigoPrestacionalF_fuasEmitidos]').change(function(){
                        
                        var idCodigoPrestacional = $("#codigoPrestacionalF_fuasEmitidos").val();

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
                                  $("#fechaAltaF_fuasEmitidos").val($("#fechaF_fuasEmitidos").val());
                                  $("#tipoAtencionF_fuasEmitidos").val("").trigger("change");/* CORREGIR */
                                  $("#tipoAtencionF_fuasEmitidos").attr('readonly','readonly');
                                }else{
                                  $(".hospitalizacion_oculto").css("display","none");
                                  $("#fechaAltaF_fuasEmitidos").val("");
                                  $("#tipoAtencionF_fuasEmitidos").val(1).trigger("change");/* CORREGIR */
                                }
                              }
                            });
                          }else{
                            $(".hospitalizacion_oculto").css("display","none");
                            $("#fechaAltaF_fuasEmitidos").val("");
                            $("#tipoAtencionF_fuasEmitidos").val(1).trigger("change");/* CORREGIR */
                            $("#tipoAtencionF_fuasEmitidos").removeAttr("readonly");
                          }
                      });
                    });

/*                     $("#fechaF_fuasEmitidos").keyup(function () {
                      var value = $(this).val();
                      $("#fechaAltaF_fuasEmitidos").val(value);
                    }); */
                    /* FIN DE IMPRIMIR FUA CON EL ID */

                    /* IMPRIMIR FUA CON EL ID DE ATENCIÓN */
                    $('#imprimir_verFuaF').click(function(){

                      var IdAtencion = $("#atencionIdF_fuasEmitidos").val();
                      /* console.log(IdAtencion); */

                      printJS({printable:ruta+'/'+'fuasEmitidos/reportesFUA/'+IdAtencion, type:'pdf', showModal:true});
                    });
                    /* FIN DE AJAX PARA EXTRAER VALORES DEL FUA */
 
</script>

@endsection

@section('javascript')
    <script src="{{ url('/') }}/js/fuasEmitidos.js"></script>
@endsection

@endif

@endforeach