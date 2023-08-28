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
                <div class="col-sm-6" style="display: inline-block;">
                    <h1>Auditoría Médica</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
                        <li class="breadcrumb-item active">Auditoría Médica</li>
                    </ol>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <div class="input-group">
                        <label for="lista_pacienteAuditoriaEmitidos" class="col-md-2 control-label">Paciente:</label>
                        <div class="col-md-4" id="lista_pacienteAuditoriaEmitidos"><!-- Información desde Javascript (auditoriaMedica.js) --></div>
                        <label for="lista_fuaAuditoriaEmitidos" class="col-md-2 control-label">N° FUA:</label>
                        <div class="col-md-4" id="lista_fuaAuditoriaEmitidos"><!-- Información desde Javascript (auditoriaMedica.js) --></div>
                    </div>

                    <div class="input-group">
                        <label for="lista_profesionalesAuditoriaEmitidos" class="col-md-2 control-label">Profesional:</label>
                        <div class="col-md-4" id="lista_profesionalesAuditoriaEmitidos"><!-- Información desde Javascript (auditoriaMedica.js) --></div>
                        <label for="lista_profesionalesAuditoriaEmitidos" class="col-md-2 control-label">Cod. Prestacional:</label>
                        <div class="col-md-4" id="lista_codigoPrestacionalAuditoriaEmitidos"><!-- Información desde Javascript (auditoriaMedica.js) --></div>
                    </div>

                    <form method="GET" action="{{ url('/') }}/auditoriaEmitidos/buscarPorMes" id="frmFechas_auditoriaEmitidos">
                        @csrf
                        <div class="input-group">
                            <label for="email" class="col-md-2 control-label">Fecha de generación:</label>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="fechaInicio_auditoriaEmitidos" id="fechaInicio_auditoriaEmitidos"
                                style="text-transform: uppercase;" required>
                            </div>

                            <div class="col-md-2">
                                <input type="date" class="form-control" name="fechaFin_auditoriaEmitidos" id="fechaFin_auditoriaEmitidos"
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

                            <label for="historiaBD_auditoriaEmitidos" class="col-md-1 control-label" style="">N° Historia:</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control inputRutaNumero" name="historiaBD_auditoriaEmitidos" id="historiaBD_auditoriaEmitidos"
                                style="text-transform: uppercase;" maxlength="6">
                            </div>

                            <label for="documentoBD_auditoriaEmitidos" class="col-md-1 control-label">N° Documento:</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control inputRutaNumero" name="documentoBD_auditoriaEmitidos" id="documentoBD_auditoriaEmitidos"
                                style="text-transform: uppercase;" maxlength="9">
                            </div>
                            
                            <label for="fuaBD_auditoriaEmitidos" class="col-md-1 control-label">N° FUA:</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control inputRutaNumero" name="fuaBD_auditoriaEmitidos" id="fuaBD_auditoriaEmitidos"
                                style="text-transform: uppercase;" maxlength="8">
                            </div>
                        </div>
                    </form>
                </div>
            <div>
        </div><!-- /.container-fluid -->
    </section>
    <!--=====================================
        FIN - SECTION (CONTENT-HEADER)
    ======================================-->

    <!--=====================================
        INICIO - SECTION (CONTENT-HEADER)
    ======================================-->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-warning btn-sm boton-general" data-toggle="modal" data-target="#verRecord" style="float:right; margin-left: 5px;display:none;">
                                <i class="fas fa-record-vinyl"></i> Record
                            </button>
                
                            <form method="POST" action="" id="frmVerFua_auditoriaEmitidos">
                                @csrf
                                <input type="text" class="form-control" name="idFua_auditoriaEmitidos" id="idFua_auditoriaEmitidos"
                                style="text-transform: uppercase;display:none;" required>

                                <button type="submit" class="btn btn-danger btn-sm boton-general" data-toggle="modal" data-target="#verFua_auditoriaEmitidos"  
                                style="float:left;display:none;margin-right: 5px;" id="btnVerFUA_auditoriaEmitidos"> 
							    <i class="fas fa-eye" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Auditar FUA</button>
                            </form>

                            <form method="GET" action="" id="frmVerRolCitas_auditoriaEmitidos">
                                @csrf
                                <input type="text" class="form-control" name="idCab_auditoriaEmitidos" id="idCab_auditoriaEmitidos"
                                style="text-transform: uppercase;display:none;" required>

                                <button type="submit" class="btn btn-dark btn-sm boton-general" data-toggle="modal" data-target="#verRolCitas_auditoriaEmitidos"  
                                style="float:left;display:none;margin-right: 5px;" id="btnRolCitas_auditoriaEmitidos"> 
							    <i class="fa fa-tasks" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Rol de Citas</button>
                            </form>
                        </div>

                        <div class="card-body">

                            <table class="table table-bordered table-striped display nowrap" width="100%" id="tablaAuditoriaEmitidos">
                                <thead>
                                    <tr style="background:white;" role="row">
                                        <th>FUA</th>
                                        <th>Paciente</th>
                                        <th>Registro/Historia</th>
                                        <th>Fecha/Hora de generación</th>
                                        <th>Fecha/Hora de atención</th>
                                        <th>Código Prestacional</th>
                                        <th>Profesional</th>
                                        <th>CIE-10</th>
                                        <th>Auditado</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <!-- SE DEJA VACIO PORQUE SE TRABAJA EN PARTE SERVIDOR -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=====================================
        FIN - SECTION (CONTENT-HEADER)
    ======================================-->
</div>

<div class="modal fade bd-example-modal-lg" id="verRolCitas_auditoriaEmitidos">
    <div class="modal-dialog modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                <h4 class="modal-tittle" style="color:white;"><i class="fa fa-pencil"></i> &nbsp; <span class="title-form">Rol de Citas</span></h4>
                <button type="button" id="botonCerrar_auditoriaEmitidos" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body" id="modal-container">
                <table class="table table-bordered table-striped display nowrap" width="100%" id="tablaRolCitas_auditoriaEmitidos">

                    <thead>
                        <tr style="background:white;">
                            <th>N° SESIÓN</th>
                            <th>Fecha Programada</th>
                            <th>Estado Cita</th>
                            <th>Profesional</th>
                            <th>FUA</th>
                            <th>CIE-10</th>
                            <th>Notas</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- TODO SE TRABAJÓ EN LA PARTE SERVIDOR -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fullscreen-modal fade" id="verFua_auditoriaEmitidos" data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel"> 
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ url('/') }}/auditoriaEmitidos/actualizarFua" enctype="multipart/form-data" id="actualizarFuaF_auditoriaEmitidos"
            pagina="auditoriaEmitidos">
            @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;"><i class="fa fa-pencil"></i> &nbsp; <span class="title-form">FUA</span></h4>
                    <button type="button" id="botonCerrarVerFua_auditoriaEmitidos" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body" id="modal-container">
                    <div class="row"><!-- FILA PRINCIPAL MODAL BODY -->
                    <div class="col-sm-6"><!-- INICIO PRIMERA COLUMNA (50%) -->

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-info alert-styled-left text-blue-800 content-group">
				                <span class="text-semibold">Estimado usuario</span> 
				                Los campos remarcados con <span class="text-danger"> * </span> son necesarios.
				                <button type="button" class="close" data-dismiss="alert">×</button>
	                            <input type="hidden" id="txtProceso" name="txtProceso" class="form-control" value="Entrada">
				            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3 usuarios_general">
                        <div class="input-group-append input-group-text" style="border-radius:0px;">
                            <i class="fas fa-angle-double-right"></i>
                        </div>

                        @foreach($administradores as $element)
                            @if ($_COOKIE["email_login"] == $element->email)
                                <input type="text" class="form-control" name="usuario_auditoriaEmitidos" id="usuario_auditoriaEmitidos"
                                value="{{ old('id',$element->id) }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                        @endforeach
                    </div>


                    <div class="form-group">
                        <div class="row" style="display:none;">
                            <div class="col-sm-12">
                                <label for="idFuaF_auditoriaEmitidos">Id del Fua</label>
                                <input type="text" class="form-control" name="idFuaF_auditoriaEmitidos" id="idFuaF_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="disaF_auditoriaEmitidos">N° del formato</label>
                            </div>

                            <div class="col-sm-6">
                                <label for="costoTotalF_auditoriaEmitidos">Costo Total (FUA)</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-1">
                                <input type="text" class="form-control" name="disaF_auditoriaEmitidos" id="disaF_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-1">
                                <input type="text" class="form-control" name="loteF_auditoriaEmitidos" id="loteF_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="numeroF_auditoriaEmitidos" id="numeroF_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="costoTotalF_auditoriaEmitidos" id="costoTotalF_auditoriaEmitidos"
                                style="text-transform: uppercase;background:red;color:white;font-weight: 900;" readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
						<div class="row">
							<div class="col-sm-4">
								<label for="personalAtiendeF_auditoriaEmitidos">Personal que atiende <span class="text-danger"> * </span></label>
                                <select class="form-control select-2 select2 disabled-select" name="personalAtiendeF_auditoriaEmitidos" id="personalAtiendeF_auditoriaEmitidos">
                                    <option value="">-- Seleccionar el Personal --</option>
                                    @foreach($personalAtiende as $key => $value_personalAtiende)
                                    <option value="{{$value_personalAtiende->id}}">{{$value_personalAtiende->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
								<label for="lugarAtencionF_auditoriaEmitidos">Lugar de Atención <span class="text-danger"> * </span></label>
                                <select class="form-control select-2 select2 disabled-select" name="lugarAtencionF_auditoriaEmitidos" id="lugarAtencionF_auditoriaEmitidos">
                                    <option value="">-- Seleccionar el Lugar de Atención --</option>
                                    @foreach($lugarAtencion as $key => $value_lugarAtencion)
                                    <option value="{{$value_lugarAtencion->id}}">{{$value_lugarAtencion->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
								<label for="tipoAtencionF_auditoriaEmitidos">Tipo de Atención</label>
                                <select class="form-control select-2 select2 disabled-select" name="tipoAtencionF_auditoriaEmitidos" id="tipoAtencionF_auditoriaEmitidos">
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
								<label for="historiaClinica_auditoriaEmitidos">Referencia realizada por <span class="text-danger"> * </span></label>
                            </div>

                            <div class="col-sm-4">
								<label for="historiaClinica_auditoriaEmitidos">N° Hoja de Referencia <span class="text-danger"> * </span></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="codigoReferenciaF_auditoriaEmitidos" id="codigoReferenciaF_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="descripcionReferenciaF_auditoriaEmitidos" id="descripcionReferenciaF_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="numeroReferenciaF_auditoriaEmitidos" id="numeroReferenciaF_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="tipoDocumentoF_auditoriaEmitidos">Identidad del Asegurado <span class="text-danger"> * </span></label>
                            </div>

                            <div class="col-sm-4">
                                <label for="componenteF_auditoriaEmitidos">Componente <span class="text-danger"> * </span></label>
                            </div>

                            <div class="col-sm-4">
                                <label for="codigoAsegurado1F_auditoriaEmitidos">Código del Asegurado <span class="text-danger"> * </span></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="tipoDocumentoF_auditoriaEmitidos" id="tipoDocumentoF_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="numeroDocumentoF_auditoriaEmitidos" id="numeroDocumentoF_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-4">
                                <select class="form-control select-2 select2" name="componenteF_auditoriaEmitidos" id="componenteF_auditoriaEmitidos">
                                    <option value="">-- Seleccionar el Componente --</option>
                                    @foreach($componente as $key => $value_componente)
                                    <option value="{{$value_componente->id}}">{{$value_componente->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-1">
                                <input type="text" class="form-control" name="codigoAsegurado1F_auditoriaEmitidos" id="codigoAsegurado1F_auditoriaEmitidos"
                                style="text-transform: uppercase;" readonly="true">
                            </div>

                            <div class="col-sm-1">
                                <input type="text" class="form-control" name="codigoAsegurado2F_auditoriaEmitidos" id="codigoAsegurado2F_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="codigoAsegurado3F_auditoriaEmitidos" id="codigoAsegurado3F_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="apellidoPaternoF_auditoriaEmitidos">Apellido Paterno <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="apellidoPaternoF_auditoriaEmitidos" id="apellidoPaternoF_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-6">
                                <label for="apellidoMaternoF_auditoriaEmitidos">Apellido Materno <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="apellidoMaternoF_auditoriaEmitidos" id="apellidoMaternoF_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="primerNombreF_auditoriaEmitidos">Primer Nombre <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="primerNombreF_auditoriaEmitidos" id="primerNombreF_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-6">
                                <label for="otroNombreF_auditoriaEmitidos">Otros Nombres</label>
                                <input type="text" class="form-control" name="otroNombreF_auditoriaEmitidos" id="otroNombreF_auditoriaEmitidos"
                                style="text-transform: uppercase;" readonly="true">
                            </div>

                                <!-- PARA SELECCIONAR EL ID DEL PACIENTE -->
                                <input type="text" class="form-control" name="pacienteIdF_auditoriaEmitidos" id="pacienteIdF_auditoriaEmitidos"
                                style="text-transform: uppercase;display:none;" readonly="true">
                                <!-- FIN PARA SELECCIONAR EL ID DEL PACIENTE -->

                                <!-- PARA SELECCIONAR EL ID DEL FUA -->
                                <input type="text" class="form-control" name="atencionIdF_auditoriaEmitidos" id="atencionIdF_auditoriaEmitidos"
                                style="text-transform: uppercase;display:none;" readonly="true">
                                <!-- FIN PARA SELECCIONAR EL ID DEL FUA -->
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="sexoF_auditoriaEmitidos">Sexo <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="sexoF_auditoriaEmitidos" id="sexoF_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-4">
                                <label for="fechaNacimientoF_auditoriaEmitidos">Fecha de Nacimiento <span class="text-danger"> * </span></label>
                                <input type="date" class="form-control" name="fechaNacimientoF_auditoriaEmitidos" id="fechaNacimientoF_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-4">
                                <label for="historiaF_auditoriaEmitidos">Historia <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="historiaF_auditoriaEmitidos" id="historiaF_auditoriaEmitidos"
                                style="text-transform: uppercase;" readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="fechaF_auditoriaEmitidos">Fecha de Atención <span class="text-danger"> * </span></label>
                                <input type="date" class="form-control" name="fechaF_auditoriaEmitidos" id="fechaF_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-2">
                                <label for="horaF_auditoriaEmitidos">Hora de Atención <span class="text-danger"> * </span></label>
                                <input type="time" class="form-control" name="horaF_auditoriaEmitidos" id="horaF_auditoriaEmitidos"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-7">
                                <label for="codigoPrestacionalF_auditoriaEmitidos">Código Prestacional <span class="text-danger"> * </span></label>
                                <select class="form-control select2" name="codigoPrestacionalF_auditoriaEmitidos" id="codigoPrestacionalF_auditoriaEmitidos" data-placeholder="Seleccionar código prestacional">
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
                                <label for="conceptoPrestacionalF_auditoriaEmitidos">Concepto Prestacional <span class="text-danger"> * </span></label>
                                <select class="form-control select2" name="conceptoPrestacionalF_auditoriaEmitidos" id="conceptoPrestacionalF_auditoriaEmitidos">
                                    <option value="">-- Seleccionar concepto prestacional --</option>
                                    @foreach($concPrestacional as $key => $value_concPrestacional)
                                    <option value="{{$value_concPrestacional->id}}">{{$value_concPrestacional->id}} - {{$value_concPrestacional->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label for="destinoAseguradoF_auditoriaEmitidos">Destino del Asegurado <span class="text-danger"> * </span></label>
                                <select class="form-control select-2 select2" name="destinoAseguradoF_auditoriaEmitidos" id="destinoAseguradoF_auditoriaEmitidos">
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
                                <label for="fechaIngresoF_auditoriaEmitidos">Fecha de Ingreso <span id="span_fechaIngresoF_auditoriaEmitidos" class="text-danger"> * </span></label>
                                <input type="date" class="form-control" name="fechaIngresoF_auditoriaEmitidos" id="fechaIngresoF_auditoriaEmitidos"
                                style="text-transform: uppercase;" readonly="readonly">
                            </div>

                            <div class="col-sm-6">
                                <label for="fechaAltaF_auditoriaEmitidos">Fecha de Alta</label>
                                <input type="date" class="form-control" name="fechaAltaF_auditoriaEmitidos" id="fechaAltaF_auditoriaEmitidos"
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
								<label for="diagnosticoF_auditoriaEmitidos">Tipo</label>
                            </div>

                            <div class="col-sm-2">
								<label for="codigoCieNF_auditoriaEmitidos">CIE - 10</label>
                            </div>

                            <div class="col-sm-6">
								<label for="codigoCieF_auditoriaEmitidos">Descripción</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <select class="form-control select-2 select2" name="diagnosticoF_auditoriaEmitidos" id="diagnosticoF_auditoriaEmitidos">
                                    <option value="">-- Seleccionar el tipo --</option>
                                    <option value="P">PRESUNTIVO</option>
                                    <option value="D">DEFINITIVO</option>
                                    <option value="R">REPETIDO</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="codigoCieNF_auditoriaEmitidos" id="codigoCieNF_auditoriaEmitidos"
                                style="text-transform: uppercase;" readonly="readonly">
                            </div>

                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="codigoCieF_auditoriaEmitidos" id="codigoCieF_auditoriaEmitidos"
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
                                <label for="tipoDocumentoP_auditoriaEmitidos">Identidad del Profesional</label>
                            </div>

                            <div class="col-sm-8">
                                <label for="nombresApellidosP_auditoriaEmitidos">Nombres y Apellidos <span class="text-danger"> * </span></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="tipoDocumentoP_auditoriaEmitidos" id="tipoDocumentoP_auditoriaEmitidos"
                                style="text-transform: uppercase;" readonly="true">
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="numeroDocumentoP_auditoriaEmitidos" id="numeroDocumentoP_auditoriaEmitidos"
                                style="text-transform: uppercase;" readonly="true">
                            </div>

                            <div class="col-sm-8">
                                <select class="form-control select2" name="nombresApellidosP_auditoriaEmitidos" id="nombresApellidosP_auditoriaEmitidos" data-placeholder="Seleccionar el profesional">
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
                                <label for="tipoPersonalSaludF_auditoriaEmitidos">Tipo de Personal de Salud</label>
                            </div>

                            <div class="col-sm-4">
                                <label for="colegiaturaF_auditoriaEmitidos">Colegiatura</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <select class="form-control select-2 select2 disabled-select" name="tipoPersonalSaludF_auditoriaEmitidos" id="tipoPersonalSaludF_auditoriaEmitidos">
                                    <option value="">-- Seleccionar el Tipo de Personal de Salud --</option>
                                    @foreach($sisTipoPersonalSalud as $key => $value_sisTipoPersonalSalud)
                                    <option value="{{$value_sisTipoPersonalSalud->id}}">{{$value_sisTipoPersonalSalud->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <select class="form-control select-2 select2 disabled-select" name="egresadoF_auditoriaEmitidos" id="egresadoF_auditoriaEmitidos">
                                    <option value="">-- Seleccionar si es Egresado --</option>
                                    @foreach($sisEgresado as $key => $value_sisEgresado)
                                    <option value="{{$value_sisEgresado->id}}">{{$value_sisEgresado->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="colegiaturaF_auditoriaEmitidos" id="colegiaturaF_auditoriaEmitidos"
                                style="text-transform: uppercase;" readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-8">
                                <label for="especialidadF_auditoriaEmitidos">Especialidad</label>
                            </div>

                            <div class="col-sm-4">
                                <label for="rneF_auditoriaEmitidos">RNE</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-8">
                                <select class="form-control select-2 select2 disabled-select" name="especialidadF_auditoriaEmitidos" id="especialidadF_auditoriaEmitidos">
                                    <option value="">-- Seleccionar la Especialidad --</option>
                                    @foreach($sisEspecialidad as $key => $value_sisEspecialidad)
                                    <option value="{{$value_sisEspecialidad->id}}">{{$value_sisEspecialidad->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="rneF_auditoriaEmitidos" id="rneF_auditoriaEmitidos"
                                style="text-transform: uppercase;" readonly="true">
                            </div>
                        </div>
                    </div>

                    </div><!-- FIN PRIMERA COLUMNA (50%) -->

                    <div class="col-sm-6"><!-- INICIO SEGUNDA COLUMNA (50%) -->

                        <div class="form-group" id="medicamentosFua_auditoriaEmitidos">
                            <div class="row">
                                <div class="col-sm-12">
                                    <a href="#" class="btn btn-secondary btn-sm boton-general" 
                                    style="text-align:center;padding:1px;font-size:16px;width:100%;margin:0px;" target="_blank">
                                        <i class="fas fa-arrow-right"></i> Medicamentos
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="medicamentosFuaActivos_datosFarmacia">
                            <div class="row">
                                <div class="col-sm-2">
                                    <label>Código Sismed <span class='text-danger'> * </span></label>
                                </div>

                                <div class="col-sm-3">
                                    <label>Nombre <span class='text-danger'> * </span></label>
                                </div>

                                <div class="col-sm-2">
                                    <label>FF <span class='text-danger'> * </span></label>
                                </div>

                                <div class="col-sm-1">
                                    <label>PRESS <span class='text-danger'> * </span></label>
                                </div>

                                <div class="col-sm-1">
                                    <label>ENTR</label>
                                </div>

                                <div class="col-sm-1">
                                    <label>DX</label>
                                </div>

                                <div class="col-sm-1">
                                    <label style='color:red;'>CORREC.</label>
                                </div>

                                <div class="col-sm-1">
                                    <label>EDITAR</label>
                                </div>
                            </div>
                        </div>

<!--                         <div class="form-group" id="biomecanicaFua_auditoriaEmitidos">
                            <div class="row">
                                <div class="col-sm-12">
                                    <a href="#" class="btn btn-secondary btn-sm boton-general" 
                                    style="text-align:center;padding:1px;font-size:16px;width:100%;margin:0px;" target="_blank">
                                        <i class="fas fa-arrow-right"></i> Biomecanica
                                    </a>
                                </div>
                            </div>
                        </div> -->

                        <div class="form-group" id="laboratorioFua_auditoriaEmitidos">
                            <div class="row">
                                <div class="col-sm-12">
                                    <a href="{{ url('/') }}/auditoriaEmitidos/mostrarPdfLaboratorio/378761" class="btn btn-secondary btn-sm boton-general" 
                                    style="text-align:center;padding:1px;font-size:16px;width:100%;margin:0px;" target="_blank">
                                        <i class="fas fa-arrow-right"></i> Laboratorio
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="laboratorioFuaActivos_datosLaboratorio">
                            <div class="row">
                                <div class="col-sm-2">
                                    <label>Código <span class='text-danger'> * </span></label>
                                </div>

                                <div class="col-sm-4">
                                    <label>Nombre <span class='text-danger'> * </span></label>
                                </div>

                                <div class="col-sm-2">
                                    <label>Precio <span class='text-danger'> * </span></label>
                                </div>

                                <div class="col-sm-1">
                                    <label>IND <span class='text-danger'> * </span></label>
                                </div>

                                <div class="col-sm-1">
                                    <label>EJE <span class='text-danger'> * </span></label>
                                </div>

                                <div class="col-sm-1">
                                    <label>DX</label>
                                </div>

                                <div class="col-sm-1">
                                    <label>EDITAR</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="radiologiaFua_auditoriaEmitidos">
                            <div class="row">
                                <div class="col-sm-12">
                                    <a href="http://192.168.6.11:9090/oviyam2/index2.html" class="btn btn-secondary btn-sm boton-general" 
                                    style="text-align:center;padding:1px;font-size:16px;width:100%;margin:0px;" target="_blank">
                                        <i class="fas fa-arrow-right"></i> Radiología
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div><!-- FIN SEGUNDA COLUMNA (50%) -->
                    </div><!-- FIN FILA PRINCIPAL MODAL BODY -->
                </div>

                <div class="modal-footer d-flex">
                    <div id="cerrar_actualizarFuaF_auditoriaEmitidos">
                        <button type="button" class="btn btn-default boton-general" data-dismiss="modal" id="salir_actualizarFuaF_auditoriaEmitidos">
                            <i class="fa fa-undo" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Salir
                        </button>
                    </div>

                    <div id="imprimir_actualizarFuaF_auditoriaEmitidos">
                        <button type="button" class="btn btn-dark boton-general" id="imprimir_verFuaF_auditoriaEmitidos">
                            <i class="fas fa-print" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Imprimir
                        </button>
                    </div>

                    <div id="actualizar_actualizarFuaF_auditoriaEmitidos">
                        <button type="button" class="btn btn-info boton-general" id="actualizar_verFuaF_auditoriaEmitidos">
                            <i class="fa fa-pencil" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Actualizar
                        </button>
                    </div>

                    <div id="auditar_actualizarFuaF_auditoriaEmitidos">
                        <a href="{{ url('/') }}/auditoriaEmitidos/auditarFua" class="btn btn-danger boton-general" id="auditar_verFuaF_auditoriaEmitidos">
                            <i class="fa fa-pencil" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Auditar Fua
                        </a>
                    </div>

                    <div id="registrar_actualizarFuaF_auditoriaEmitidos" style="display:none;">
                        <button type="submit" class="btn btn-success boton-general" id="registrar_actualizarFuaF1_auditoriaEmitidos">
                            <i class="fas fa-save" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Guardar datos
                        </button>
                    </div>

                    <div id="cancelar_actualizarFuaF_auditoriaEmitidos" style="display:none;">
                        <button type="button" class="btn btn-danger boton-general">
                            <i class="fa fa-times" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Cancelar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRadiologia_auditoriaEmitidos" data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel"> 
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="height:600px;">
            <div class="modal-header" style="border-radius:0px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="embed-responsive embed-responsive-16by9" style="height:600px;">
                <iframe class="embed-responsive-item" src="http://192.168.6.11:9090/oviyam2/index2.html"></iframe>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="editarDatosMedicamentos" data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel"> 
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="" enctype="multipart/form-data" id="actualizarMedicamentoM_auditoriaEmitidos"
            pagina="auditoriaEmitidos">
            @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;"><i class="fa fa-pencil"></i> &nbsp; <span class="title-form">Editar datos del medicamento</span></h4>
                    <button type="button" id="botonCerrarMedicamentosM_auditoriaEmitidos" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body" id="modal-container">

                <div class="form-group">

                    <div class="row" style="display:none;">
                        <div class="col-sm-6">
                            <label for="idMedicamentoM_auditoriaEmitidos">ID</label>
                            <input type="text" class="form-control" name="idMedicamentoM_auditoriaEmitidos" id="idMedicamentoM_auditoriaEmitidos"
                            style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-6">
                            <label for="idFuaM_auditoriaEmitidos">ID</label>
                            <input type="text" class="form-control" name="idFuaM_auditoriaEmitidos" id="idFuaM_auditoriaEmitidos"
                            style="text-transform: uppercase;" required readonly="true">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <label for="codigoSismedM_auditoriaEmitidos">Código Sismed</label>
                            <input type="text" class="form-control" name="codigoSismedM_auditoriaEmitidos" id="codigoSismedM_auditoriaEmitidos"
                            style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-8">
                            <label for="nombreM_auditoriaEmitidos">Nombre</label>
                            <input type="text" class="form-control" name="nombreM_auditoriaEmitidos" id="nombreM_auditoriaEmitidos"
                            style="text-transform: uppercase;" required readonly="true">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">

                        <div class="col-sm-4">
                            <label for="tipoM_auditoriaEmitidos">Tipo</label>
                            <input type="text" class="form-control" name="tipoM_auditoriaEmitidos" id="tipoM_auditoriaEmitidos"
                            style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-2">
                            <label for="prescritoM_auditoriaEmitidos">Prescrito</label>
                            <input type="text" class="form-control" name="prescritoM_auditoriaEmitidos" id="prescritoM_auditoriaEmitidos"
                            style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-2">
                            <label for="entregadoM_auditoriaEmitidos">Entregado</label>
                            <input type="text" class="form-control" name="entregadoM_auditoriaEmitidos" id="entregadoM_auditoriaEmitidos"
                            style="text-transform: uppercase;" readonly="true">
                        </div>

                        <div class="col-sm-2">
                            <label for="diagnosticoM_auditoriaEmitidos">Diagnostico</label>
                            <input type="number" class="form-control" name="diagnosticoM_auditoriaEmitidos" id="diagnosticoM_auditoriaEmitidos"
                            style="text-transform: uppercase;" min="1">
                        </div>

                        <div class="col-sm-2">
                            <label for="observacionM_auditoriaEmitidos">Observación</label>
                            <input type="number" class="form-control" name="observacionM_auditoriaEmitidos" id="observacionM_auditoriaEmitidos"
                            style="text-transform: uppercase;" min="1">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">

                    <div class="col-sm-8"></div>

                        <div class="col-sm-2">
                            <label for="precioUnitarioM_auditoriaEmitidos">Precio</label>
                            <input type="text" class="form-control" name="precioUnitarioM_auditoriaEmitidos" id="precioUnitarioM_auditoriaEmitidos"
                            style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-2">
                            <label for="precioTotalM_auditoriaEmitidos">Precio</label>
                            <input type="text" class="form-control" name="precioTotalM_auditoriaEmitidos" id="precioTotalM_auditoriaEmitidos"
                            style="text-transform: uppercase;" required readonly="true">
                        </div>

                    </div>
                </div>

                </div>

                <div class="modal-footer d-flex">
                    <div id="salir_MedicamentosM_auditoriaEmitidos">
                        <button type="button" class="btn btn-default boton-general" data-dismiss="modal" id="salir_MedicamentosM_auditoriaEmitidos">
                            <i class="fa fa-undo" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Salir
                        </button>
                    </div>

                    <div id="registrar_MedicamentosM_auditoriaEmitidos">
                        <button type="submit" class="btn btn-success boton-general" id="registrar_MedicamentosM_auditoriaEmitidos">
                            <i class="fas fa-save" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Actualizar datos
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editarDatosLaboratorio" data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel"> 
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="" enctype="multipart/form-data" id="actualizarLaboratorioM_auditoriaEmitidos"
            pagina="auditoriaEmitidos">
            @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;"><i class="fa fa-pencil"></i> &nbsp; <span class="title-form">Editar datos del laboratorio</span></h4>
                    <button type="button" id="botonCerrarLaboratorioM_auditoriaEmitidos" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body" id="modal-container">

                <div class="form-group">

                    <div class="row" style="display:none;">
                        <div class="col-sm-6">
                            <label for="idLaboratorioM_auditoriaEmitidos">ID</label>
                            <input type="text" class="form-control" name="idLaboratorioM_auditoriaEmitidos" id="idLaboratorioM_auditoriaEmitidos"
                            style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-6">
                            <label for="idFuaL_auditoriaEmitidos">ID</label>
                            <input type="text" class="form-control" name="idFuaL_auditoriaEmitidos" id="idFuaL_auditoriaEmitidos"
                            style="text-transform: uppercase;" required readonly="true">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <label for="codigoCpmsM_auditoriaEmitidos">Código CPMS</label>
                            <input type="text" class="form-control" name="codigoCpmsM_auditoriaEmitidos" id="codigoCpmsM_auditoriaEmitidos"
                            style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-8">
                            <label for="nombreL_auditoriaEmitidos">Nombre</label>
                            <input type="text" class="form-control" name="nombreL_auditoriaEmitidos" id="nombreL_auditoriaEmitidos"
                            style="text-transform: uppercase;" required readonly="true">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">

                        <div class="col-sm-4"></div>

                        <div class="col-sm-2">
                            <label for="indicadoL_auditoriaEmitidos">Indicado</label>
                            <input type="text" class="form-control" name="indicadoL_auditoriaEmitidos" id="indicadoL_auditoriaEmitidos"
                            style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-2">
                            <label for="ejecutadoL_auditoriaEmitidos">Ejecutado</label>
                            <input type="text" class="form-control" name="ejecutadoL_auditoriaEmitidos" id="ejecutadoL_auditoriaEmitidos"
                            style="text-transform: uppercase;" readonly="true">
                        </div>

                        <div class="col-sm-2">
                            <label for="diagnosticoL_auditoriaEmitidos">Diagnostico</label>
                            <input type="number" class="form-control" name="diagnosticoL_auditoriaEmitidos" id="diagnosticoL_auditoriaEmitidos"
                            style="text-transform: uppercase;" min="1">
                        </div>

                        <div class="col-sm-2">
                            <label for="precioTotalL_auditoriaEmitidos">Precio</label>
                            <input type="text" class="form-control" name="precioTotalL_auditoriaEmitidos" id="precioTotalL_auditoriaEmitidos"
                            style="text-transform: uppercase;" required readonly="true">
                        </div>
                    </div>
                </div>

                </div>

                <div class="modal-footer d-flex">
                    <div id="salir_laboratorioL_auditoriaEmitidos">
                        <button type="button" class="btn btn-default boton-general" data-dismiss="modal" id="salir_laboratorioL_auditoriaEmitidos">
                            <i class="fa fa-undo" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Salir
                        </button>
                    </div>

                    <div id="registrar_laboratorioL_auditoriaEmitidos">
                        <button type="submit" class="btn btn-success boton-general" id="registrar_laboratorioL_auditoriaEmitidos">
                            <i class="fas fa-save" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Actualizar datos
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

$("#historiaBD_auditoriaEmitidos").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        var numHistoriaBD = $("#historiaBD_auditoriaEmitidos").val();
        
        if (numHistoriaBD != ''){

            tablaAuditoriaEmitidos.clear().destroy();

            $("#btnVerFUA_auditoriaEmitidos").css("display","none");
            $("#documentoBD_auditoriaEmitidos").val("");
            $("#fuaBD_auditoriaEmitidos").val("");

            $.ajax({
                url: '{{ route('consultar.historiaBD') }}',
                data: {numHistoriaBD},
                success: function(respuesta){
                    /* console.log("respuesta",respuesta); */
                },

                error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                }
            });

        /*=============================================
        DataTable de Auditoria Emitidos
        =============================================*/
        tablaAuditoriaEmitidos = $("#tablaAuditoriaEmitidos").DataTable({
            /* processing: true, */
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
	        /* dom: 'Bfrtip', */
	        "buttons": [{
			    "extend": 'excel',
			    "footer": false,
			    "title": 'AUDITORIA EMITIDOS',
			    "filename": 'AUDITORIA_EMITIDOS',
			    "text": "<button class='btn btn-success btn-sm boton-general' style='float:left;'><i class='fas fa-file-excel'></i> Exportar a Excel</button>",
                action: function ( e, dt, node, config ) {
			        var myButton = this;
			        dt.one( 'draw', function () {
				        $.fn.dataTable.ext.buttons.excelHtml5.action.call(myButton, e, dt, node, config);
			        });
			        dt.page.len(-1).draw();
		        }
		    }],
            ajax: {
                url: '{{ route('consultar.historiaBD') }}',
                data: {'numHistoriaBD' : $("#historiaBD_auditoriaEmitidos").val()}
            },

            "columnDefs":[
		        {
		        "searchable": true,
		        "orderable" : true,
		        "targets" : 0
	            },
		{
			className:"position",targets: [8],
			"render": function ( data, type, row ) {
				if(data == 1){
					return '<i class="fas fa-check" style="color:green;text-align:center;"></i>'
				}else{
					return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"></i>'
				}
			}
		}
            ],

            "order": [[3, "asc"]],

            /* Creamos columnas para visualizar */
            columns: [
		    {
                data: 'FUA',
                name: 'FUA'
            },
		    {
                data: 'Paciente',
                name: 'Paciente'
            },
		    {
                data: 'HistoriaClinica',
                name: 'HistoriaClinica'
            },
		    {
                data: 'FechaHoraRegistro',
                name: 'FechaHoraRegistro'
            },
		    {
                data: 'FechaHoraAtencion',
                name: 'FechaHoraAtencion'
            },
		    {
                data: 'CodigoPrestacional',
                name: 'CodigoPrestacional'
            },
		    {
                data: 'Profesional',
                name: 'Profesional'
            },
            {
                data: 'cie1_cod',
                name: 'cie1_cod'
            },
		{
            data: 'auditarFua_estado',
            name: 'auditarFua_estado'
        }
            
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
  	        } ,
	
	        deferRender:true,
            scroller:{
		        loadingIndicastor: true
	        },
	        scrollCollapse:true,
	        paging:true,
	        scrollY:"350PX",
	        scrollX:true,
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaAuditoriaEmitidos.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */

        tablaAuditoriaEmitidos.draw();

        }else{
            $('#historiaBD_auditoriaEmitidos').focus();

            tablaAuditoriaEmitidos.clear().destroy();
            $("#btnVerFUA_auditoriaEmitidos").css("display","none");

            let valorUrlAjax3 = '';

            if($('#fechaInicio_auditoriaEmitidos').val() != '' || $('#fechaFin_auditoriaEmitidos').val() != ''){
                valorUrlAjax3 = '{{ route('consultar.fechasAEmitidos') }}';
            }else{
                valorUrlAjax3 = ruta + '/auditoriaEmitidos';
            }

            /*=============================================
            DataTable de Auditoria Emitidos
            =============================================*/
            tablaAuditoriaEmitidos = $("#tablaAuditoriaEmitidos").DataTable({
                /* processing: true, */
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
	            /* dom: 'Bfrtip', */
                "buttons": [
                    {
			            "extend": 'excel',
			            "footer": false,
			            "title": 'AUDITORIA EMITIDOS',
			            "filename": 'AUDITORIA_EMITIDOS',
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
                ajax: {
                    url: valorUrlAjax3,
                    data: { '_token' : $('input[name=_token]').val(),
                            'fechaInicio_auditoriaEmitidos' : $('#fechaInicio_auditoriaEmitidos').val(),
                            'fechaFin_auditoriaEmitidos': $('#fechaFin_auditoriaEmitidos').val()}
                },
                "columnDefs":[
		            {
		                "searchable": true,
		                "orderable" : true,
		                "targets" : 0
	                },
		            {
			            className:"position",targets: [8],
			            "render": function (data,type,row){
				            if(data == 1){
					            return '<i class="fas fa-check" style="color:green;text-align:center;"></i>'
				            }else{
					            return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"></i>'
				            }
			            }
		            }
                ],
                "order": [[3, "asc"]],
                columns: [
		            {data: 'FUA',name: 'FUA'},
		            {data: 'Paciente',name: 'Paciente'},
		            {data: 'HistoriaClinica',name: 'HistoriaClinica'},
		            {data: 'FechaHoraRegistro',name: 'FechaHoraRegistro'},
		            {data: 'FechaHoraAtencion',name: 'FechaHoraAtencion'},
		            {data: 'CodigoPrestacional',name: 'CodigoPrestacional'},
		            {data: 'Profesional',name: 'Profesional'},
                    {data: 'cie1_cod',name: 'cie1_cod'},
		            {data: 'auditarFua_estado',name: 'auditarFua_estado'}
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
	            deferRender:true,
                "scroller": {
	                "loadingIndicator": true
	            },
	            scroller:true,
	            scrollCollapse:true,
	            paging:true,
	            scrollY:"405PX",
	            scrollX:true
            });

            /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaAuditoriaEmitidos.search(this.value).draw();
	            }
            });
            /* FIN DE BUSQUEDA POR ENTER */

            tablaAuditoriaEmitidos.draw();
        }
    }
});

$("#documentoBD_auditoriaEmitidos").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        var numDocumentoBD = $("#documentoBD_auditoriaEmitidos").val();
        
        if (numDocumentoBD != ''){

            tablaAuditoriaEmitidos.clear().destroy();

            $("#btnVerFUA_auditoriaEmitidos").css("display","none");
            $("#historiaBD_auditoriaEmitidos").val("");
            $("#fuaBD_auditoriaEmitidos").val("");

            $.ajax({
                url: '{{ route('consultar.documentoBD') }}',
                data: {numDocumentoBD},
                success: function(respuesta){
                    /* console.log("respuesta",respuesta); */
                },

                error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                }
            });

        /*=============================================
        DataTable de Auditoria Emitidos
        =============================================*/
        tablaAuditoriaEmitidos = $("#tablaAuditoriaEmitidos").DataTable({
            /* processing: true, */
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
	        /* dom: 'Bfrtip', */
            "buttons": [
                {
			        "extend": 'excel',
			        "footer": false,
			        "title": 'AUDITORIA EMITIDOS',
			        "filename": 'AUDITORIA_EMITIDOS',
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
            ajax: {
                url: '{{ route('consultar.documentoBD') }}',
                data: {'numDocumentoBD' : $("#documentoBD_auditoriaEmitidos").val()}
            },

            "columnDefs":[
		        {
		        "searchable": true,
		        "orderable" : true,
		        "targets" : 0
	            },
		{
			className:"position",targets: [8],
			"render": function ( data, type, row ) {
				if(data == 1){
					return '<i class="fas fa-check" style="color:green;text-align:center;"></i>'
				}else{
					return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"></i>'
				}
			}
		}
            ],

            "order": [[3, "asc"]],

            /* Creamos columnas para visualizar */
            columns: [
		    {
                data: 'FUA',
                name: 'FUA'
            },
		    {
                data: 'Paciente',
                name: 'Paciente'
            },
		    {
                data: 'HistoriaClinica',
                name: 'HistoriaClinica'
            },
		    {
                data: 'FechaHoraRegistro',
                name: 'FechaHoraRegistro'
            },
		    {
                data: 'FechaHoraAtencion',
                name: 'FechaHoraAtencion'
            },
		    {
                data: 'CodigoPrestacional',
                name: 'CodigoPrestacional'
            },
		    {
                data: 'Profesional',
                name: 'Profesional'
            },
            {
                data: 'cie1_cod',
                name: 'cie1_cod'
            },
		{
            data: 'auditarFua_estado',
            name: 'auditarFua_estado'
        }
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
  	        } ,
	
	        deferRender:true,
            scroller:{
		        loadingIndicastor: true
	        },
	        scrollCollapse:true,
	        paging:true,
	        scrollY:"350PX",
	        scrollX:true,
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaAuditoriaEmitidos.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */

        tablaAuditoriaEmitidos.draw();

        }else{
            $('#documentoBD_auditoriaEmitidos').focus();

            tablaAuditoriaEmitidos.clear().destroy();
            $("#btnVerFUA_auditoriaEmitidos").css("display","none");

            let valorUrlAjax4 = '';

            if($('#fechaInicio_auditoriaEmitidos').val() != '' || $('#fechaFin_auditoriaEmitidos').val() != ''){
                valorUrlAjax4 = '{{ route('consultar.fechasAEmitidos') }}';
            }else{
                valorUrlAjax4 = ruta + '/auditoriaEmitidos';
            }

            /*=============================================
            DataTable de Auditoria Emitidos
            =============================================*/
            tablaAuditoriaEmitidos = $("#tablaAuditoriaEmitidos").DataTable({
                /* processing: true, */
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
	            /* dom: 'Bfrtip', */
                "buttons": [
                    {
			            "extend": 'excel',
			            "footer": false,
			            "title": 'AUDITORIA EMITIDOS',
			            "filename": 'AUDITORIA_EMITIDOS',
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
                ajax: {
                    url: valorUrlAjax4,
                    data: { '_token' : $('input[name=_token]').val(),
                            'fechaInicio_auditoriaEmitidos' : $('#fechaInicio_auditoriaEmitidos').val(),
                            'fechaFin_auditoriaEmitidos': $('#fechaFin_auditoriaEmitidos').val()}
                },
                "columnDefs":[
		            {
		                "searchable": true,
		                "orderable" : true,
		                "targets" : 0
	                },
		            {
			            className:"position",targets: [8],
			            "render": function (data,type,row){
				            if(data == 1){
					            return '<i class="fas fa-check" style="color:green;text-align:center;"></i>'
				            }else{
					            return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"></i>'
				            }
			            }
		            }
                ],
                "order": [[3, "asc"]],
                columns: [
		            {data: 'FUA',name: 'FUA'},
		            {data: 'Paciente',name: 'Paciente'},
		            {data: 'HistoriaClinica',name: 'HistoriaClinica'},
		            {data: 'FechaHoraRegistro',name: 'FechaHoraRegistro'},
		            {data: 'FechaHoraAtencion',name: 'FechaHoraAtencion'},
		            {data: 'CodigoPrestacional',name: 'CodigoPrestacional'},
		            {data: 'Profesional',name: 'Profesional'},
                    {data: 'cie1_cod',name: 'cie1_cod'},
		            {data: 'auditarFua_estado',name: 'auditarFua_estado'}
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
	            deferRender:true,
                "scroller": {
	                "loadingIndicator": true
	            },
	            scroller:true,
	            scrollCollapse:true,
	            paging:true,
	            scrollY:"405PX",
	            scrollX:true
            });

            /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaAuditoriaEmitidos.search(this.value).draw();
	            }
            });
            /* FIN DE BUSQUEDA POR ENTER */

            tablaAuditoriaEmitidos.draw();
        }
    }
});

$("#fuaBD_auditoriaEmitidos").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        var numFuaBD = $("#fuaBD_auditoriaEmitidos").val();
        
        if (numFuaBD != ''){

            tablaAuditoriaEmitidos.clear().destroy();

            $("#btnVerFUA_auditoriaEmitidos").css("display","none");
            $("#historiaBD_auditoriaEmitidos").val("");
            $("#documentoBD_auditoriaEmitidos").val("");

            $.ajax({
                url: '{{ route('consultar.fuaBD') }}',
                data: {numFuaBD},
                success: function(respuesta){
                    /* console.log("respuesta",respuesta); */
                },

                error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                }
            });

        /*=============================================
        DataTable de Auditoria Emitidos
        =============================================*/
        tablaAuditoriaEmitidos = $("#tablaAuditoriaEmitidos").DataTable({
            /* processing: true, */
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
	        /* dom: 'Bfrtip', */
            "buttons": [
                {
			        "extend": 'excel',
			        "footer": false,
			        "title": 'AUDITORIA EMITIDOS',
			        "filename": 'AUDITORIA_EMITIDOS',
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
            ajax: {
                url: '{{ route('consultar.fuaBD') }}',
                data: {'numFuaBD' : $("#fuaBD_auditoriaEmitidos").val()}
            },

            "columnDefs":[
		        {
		        "searchable": true,
		        "orderable" : true,
		        "targets" : 0
	            },
		{
			className:"position",targets: [8],
			"render": function ( data, type, row ) {
				if(data == 1){
					return '<i class="fas fa-check" style="color:green;text-align:center;"></i>'
				}else{
					return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"></i>'
				}
			}
		}
            ],

            "order": [[3, "asc"]],

            /* Creamos columnas para visualizar */
            columns: [
		    {
                data: 'FUA',
                name: 'FUA'
            },
		    {
                data: 'Paciente',
                name: 'Paciente'
            },
		    {
                data: 'HistoriaClinica',
                name: 'HistoriaClinica'
            },
		    {
                data: 'FechaHoraRegistro',
                name: 'FechaHoraRegistro'
            },
		    {
                data: 'FechaHoraAtencion',
                name: 'FechaHoraAtencion'
            },
		    {
                data: 'CodigoPrestacional',
                name: 'CodigoPrestacional'
            },
		    {
                data: 'Profesional',
                name: 'Profesional'
            },
            {
                data: 'cie1_cod',
                name: 'cie1_cod'
            },
		{
            data: 'auditarFua_estado',
            name: 'auditarFua_estado'
        }
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
  	        } ,
	
	        deferRender:true,
            scroller:{
		        loadingIndicastor: true
	        },
	        scrollCollapse:true,
	        paging:true,
	        scrollY:"350PX",
	        scrollX:true,
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaAuditoriaEmitidos.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */

        tablaAuditoriaEmitidos.draw();

        }else{
            $('#fuaBD_auditoriaEmitidos').focus();

            tablaAuditoriaEmitidos.clear().destroy();
            $("#btnVerFUA_auditoriaEmitidos").css("display","none");

            let valorUrlAjax5 = '';

            if($('#fechaInicio_auditoriaEmitidos').val() != '' || $('#fechaFin_auditoriaEmitidos').val() != ''){
                valorUrlAjax5 = '{{ route('consultar.fechasAEmitidos') }}';
            }else{
                valorUrlAjax5 = ruta + '/auditoriaEmitidos';
            }

            /*=============================================
            DataTable de Auditoria Emitidos
            =============================================*/
            tablaAuditoriaEmitidos = $("#tablaAuditoriaEmitidos").DataTable({
                /* processing: true, */
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
	            /* dom: 'Bfrtip', */
                "buttons": [
                    {
			            "extend": 'excel',
			            "footer": false,
			            "title": 'AUDITORIA EMITIDOS',
			            "filename": 'AUDITORIA_EMITIDOS',
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
                ajax: {
                    url: valorUrlAjax5,
                    data: { '_token' : $('input[name=_token]').val(),
                            'fechaInicio_auditoriaEmitidos' : $('#fechaInicio_auditoriaEmitidos').val(),
                            'fechaFin_auditoriaEmitidos': $('#fechaFin_auditoriaEmitidos').val()}
                },
                "columnDefs":[
		            {
		                "searchable": true,
		                "orderable" : true,
		                "targets" : 0
	                },
		            {
			            className:"position",targets: [8],
			            "render": function (data,type,row){
				            if(data == 1){
					            return '<i class="fas fa-check" style="color:green;text-align:center;"></i>'
				            }else{
					            return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"></i>'
				            }
			            }
		            }
                ],
                "order": [[3, "asc"]],
                columns: [
		            {data: 'FUA',name: 'FUA'},
		            {data: 'Paciente',name: 'Paciente'},
		            {data: 'HistoriaClinica',name: 'HistoriaClinica'},
		            {data: 'FechaHoraRegistro',name: 'FechaHoraRegistro'},
		            {data: 'FechaHoraAtencion',name: 'FechaHoraAtencion'},
		            {data: 'CodigoPrestacional',name: 'CodigoPrestacional'},
		            {data: 'Profesional',name: 'Profesional'},
                    {data: 'cie1_cod',name: 'cie1_cod'},
		            {data: 'auditarFua_estado',name: 'auditarFua_estado'}
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
	            deferRender:true,
                "scroller": {
	                "loadingIndicator": true
	            },
	            scroller:true,
	            scrollCollapse:true,
	            paging:true,
	            scrollY:"405PX",
	            scrollX:true
            });

            /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaAuditoriaEmitidos.search(this.value).draw();
	            }
            });
            /* FIN DE BUSQUEDA POR ENTER */

            tablaAuditoriaEmitidos.draw();
        }
    }
});

$('#frmFechas_auditoriaEmitidos').submit(function(e){
          
          e.preventDefault();

          tablaAuditoriaEmitidos.clear().destroy();

          $("#btnVerFUA_auditoriaEmitidos").css("display","none");
          $("#historiaBD_auditoriaEmitidos").val("");
          $("#documentoBD_auditoriaEmitidos").val("");
          $("#fuaBD_auditoriaEmitidos").val("");

          /* Petición AJAX */
          $.ajax({
            url: '{{ route('consultar.fechasAEmitidos') }}',
            data: $("#frmFechas_auditoriaEmitidos").serialize(),
            success: function(respuesta){
                /* console.log("respuesta",respuesta); */
            },

            error: function(jqXHR,textStatus,errorThrown){
                console.error(textStatus + " " + errorThrown);
            }
          });
        /* Fin de Petición AJAX */

        /*=============================================
        DataTable de Auditoria Emitidos
        =============================================*/
        tablaAuditoriaEmitidos = $("#tablaAuditoriaEmitidos").DataTable({
            /* processing: true, */
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
	        /* dom: 'Bfrtip', */
	        "buttons": [{
			    "extend": 'excel',
			    "footer": false,
			    "title": 'AUDITORIA EMITIDOS',
			    "filename": 'AUDITORIA_EMITIDOS',
			    "text": "<button class='btn btn-success btn-sm boton-general' style='float:left;'><i class='fas fa-file-excel'></i> Exportar a Excel</button>",
                action: function ( e, dt, node, config ) {
			        var myButton = this;
			        dt.one( 'draw', function () {
				        $.fn.dataTable.ext.buttons.excelHtml5.action.call(myButton, e, dt, node, config);
			        });
			        dt.page.len(-1).draw();
		        }
		    }],
            "ajax": {
                "url": '{{ route('consultar.fechasAEmitidos') }}',
                "data": {'_token' : $('input[name=_token]').val(),
                       'fechaInicio_auditoriaEmitidos' : $('#fechaInicio_auditoriaEmitidos').val(),
                       'fechaFin_auditoriaEmitidos': $('#fechaFin_auditoriaEmitidos').val()}
            },
            "columnDefs":[
		        {
			        className:"position",targets: [8],
			        "render": function ( data, type, row ) {
				        if(data == 1){
					        return '<i class="fas fa-check" style="color:green;text-align:center;"></i>'
				        }else{
					        return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"></i>'
				        }
			        }
		        }
            ],
            "order": [[3, "asc"]],
            "colReorder": true,
            "deferRender":true,
            "scroller":{
		        "loadingIndicator": true
	        },
	        "scrollCollapse":true,
	        "paging":true,
	        "scrollY":400,
	        "scrollX":true,
            "columns": [
		        {"data": 'FUA',"name": 'FUA'},
		        {"data": 'Paciente',"name": 'Paciente'},
		        {"data": 'HistoriaClinica',"name": 'HistoriaClinica'},
		        {"data": 'FechaHoraRegistro',"name": 'FechaHoraRegistro'},
		        {"data": 'FechaHoraAtencion',"name": 'FechaHoraAtencion'},
		        {"data": 'CodigoPrestacional',"name": 'CodigoPrestacional'},
		        {"data": 'Profesional',"name": 'Profesional'},
                {"data": 'cie1_cod',"name": 'cie1_cod'},
		        {"data": 'auditarFua_estado',"name": 'auditarFua_estado'}
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
  	        }
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
        $('div.dataTables_filter input').unbind();
        $("div.dataTables_filter input").keyup( function (e) {
	        if (e.keyCode == 13) {
		        tablaAuditoriaEmitidos.search(this.value).draw();
	        }
        });
        /* FIN DE BUSQUEDA POR ENTER */
});
</script>

<script type="text/javascript">
$('#frmVerRolCitas_auditoriaEmitidos').submit(function(e){
    /* console.log($('#idCab_auditoriaEmitidos').val()); */
    e.preventDefault();

    /* Boton cerrar del modal */
    $('#botonCerrar_auditoriaEmitidos').click(function(){
        var tablaRolCitas_auditoriaEmitidos = $("#tablaRolCitas_auditoriaEmitidos").DataTable();
        tablaRolCitas_auditoriaEmitidos.clear().destroy();
    });
    /* Fin boton cerrar del modal */

    /* Petición AJAX */
    $.ajax({
        url: '{{ route('consultar.rolAuditoriaEmitidos') }}',
        data: $("#frmVerRolCitas_auditoriaEmitidos").serialize(),
        success: function(respuesta){
            /* console.log("respuesta",respuesta); */
        },

        error: function(jqXHR,textStatus,errorThrown){
            console.error(textStatus + " " + errorThrown);
        }
    });
    /* Fin de Petición AJAX */

    tablaRolCitas_auditoriaEmitidos = $("#tablaRolCitas_auditoriaEmitidos").DataTable({
        /* processing: true, */
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
	    /* dom: 'Bfrtip', */
	    buttons: [
        {
			//Botón para Excel
			extend: 'excel',
			footer: false,
			title: 'ROL DE CITAS',
			filename: 'ROL_CITAS',
	
			//Aquí es donde generas el botón personalizado
			text: "<button class='btn btn-success btn-sm boton-general' style='float:left;'><i class='fas fa-file-excel'></i> Exportar a Excel</button>"
		}],
        ajax: {
            url: '{{ route('consultar.rolAuditoriaEmitidos') }}',
            data: {'_token' : $('input[name=_token]').val(),
                   'idCab_auditoriaEmitidos' : $('#idCab_auditoriaEmitidos').val()}
        },

        "columnDefs":[
        {
            "searchable": true,
            "orderable" : true,
            "targets" : 0
        }],

        "order": [[3, "asc"]],

        /* Creamos columnas para visualizar */
        columns: [
		    {
                data: 'nroSesion',
                name: 'nroSesion'
            },
            {
                data:'fechaProgramada',
                name:'fechaProgramada'
            },
            {
                data:'atendido',
                name:'atendido'
            },
            {
                data:'Personal',
                name:'Personal'
            },
            {
                data:'Comprobante',
                name:'Comprobante'
            },
            {
                data:'cie1_cod',
                name:'cie1_cod'
            },
            {
                data:'notas',
                name:'notas'
            }
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
	
	    deferRender:true,
	    scroller:true,
	    scrollCollapse:true,
	    paging:true,
	    scrollY:"350PX",
	    scrollX:true,

        rowCallback: function(row, data, index){
            if(data["atendido_id"] != 1 && data["atendido_id"] != 3 && data["atendido_id"] != 4){
            $(row).css('background-color', '#F39B9B');
            }
        }
    });

    tablaRolCitas_auditoriaEmitidos.draw();

});
</script>

<script type="text/javascript">

$(function(){

$('#tablaAuditoriaEmitidos tbody').on('click', 'tr', function (e) {
  e.preventDefault();

  if($(this).hasClass('selected')){
    $(this).removeClass('selected');
      $("#btnVerFUA_auditoriaEmitidos").css("display","none");
      $("#btnRolCitas_auditoriaEmitidos").css("display","none");
  }else{
    tablaAuditoriaEmitidos.$('tr.selected').removeClass('selected');
    $(this).addClass('selected');
    /* console.log(tablaAuditoriaEmitidos.row($(this)).data()); */

    //=====================================================================INICIO
            //EXTRAER INFORMACIÓN DEL FUA EXISTENTE
    //===========================================================================
    $("#idFua_auditoriaEmitidos").val(tablaAuditoriaEmitidos.row($(this)).data()["Fua_id"]);
    $("#idFuaF_auditoriaEmitidos").val(tablaAuditoriaEmitidos.row($(this)).data()["Fua_id"]);
    $("#idFuaM_auditoriaEmitidos").val(tablaAuditoriaEmitidos.row($(this)).data()["Fua_id"]);
    $("#idFuaL_auditoriaEmitidos").val(tablaAuditoriaEmitidos.row($(this)).data()["Fua_id"]);

    /* INICIO DE LA CONSULTA PARA VER EL ROL DE LAS CITAS */
/*     if(tablaAuditoriaEmitidos.row($(this)).data()["idIdentificador"])
    {
        $("#btnRolCitas_auditoriaEmitidos").css("display","block");
        $("#frmVerRolCitas_auditoriaEmitidos").attr('action',$(location).attr('href')+"/"+tablaAuditoriaEmitidos.row($(this)).data()["idIdentificador"]);
        $("#idCab_auditoriaEmitidos").val(tablaAuditoriaEmitidos.row($(this)).data()["idIdentificador"]);
    }
    else
    {
        $("#btnRolCitas_auditoriaEmitidos").css("display","none");
    } */
    /* FIN DE LA CONSULTA PARA VER EL ROL DE LAS CITAS */

    $('#btnVerFUA_auditoriaEmitidos').unbind('click').on('click',function(e){
      e.preventDefault();

      $.ajax({
      url: '{{ route('consultar.verFuaAuditoriaEmitidos') }}',
      method: "POST",
      data: $("#frmVerFua_auditoriaEmitidos").serialize(),
      success: function(respuesta){
          /* console.log(respuesta); */

          $("#costoTotalF_auditoriaEmitidos").val('S/ ' + respuesta["costoTotalFua"]);

        if(jQuery.isEmptyObject(respuesta["datosFarmacia"])){
            $('.rowDatosMedicamentos').remove();
            $("#medicamentosFua_auditoriaEmitidos").css("display","none");
            $("#medicamentosFuaActivos_datosFarmacia").css("display","none");
        }else{

            $("#medicamentosFuaActivos_datosFarmacia").css("display","block");

            var arregloMedicamentosFua = respuesta["datosFarmacia"];

            /* console.log(arregloMedicamentosFua); */

            for(var x=0;x<arregloMedicamentosFua.length;x++){

                $("#medicamentosFua_auditoriaEmitidos").css("display","block");

                let EntregaMedicamentos_auditoriaEmitidos = '';

                if(arregloMedicamentosFua[x].docf_flag == 'O'){
                    EntregaMedicamentos_auditoriaEmitidos = arregloMedicamentosFua[x].docf_item_cant;
                }else if(arregloMedicamentosFua[x].docf_flag == 'X'){
                    EntregaMedicamentos_auditoriaEmitidos = '';
                }else{
                    EntregaMedicamentos_auditoriaEmitidos = '';
                }

                if(arregloMedicamentosFua[x].diagnostico_estado == null){
                    arregloMedicamentosFua[x].diagnostico_estado = '';
                }

                if(arregloMedicamentosFua[x].cambio_cantidad == null){
                    arregloMedicamentosFua[x].cambio_cantidad = '';
                }

                var valoresMedicamentos =   "<div class='row rowDatosMedicamentos' style='margin-bottom:5px;'>"+
                                                "<div class='col-sm-2'>"+
                                                    "<input type='text' value='"+arregloMedicamentosFua[x].catalogo_sismed+"' class='form-control' style='text-transform:uppercase;' readonly='readonly'>"+
                                                "</div>"+
                                                "<div class='col-sm-3'>"+
                                                    "<input type='text' value='"+arregloMedicamentosFua[x].catalogo_desc+"' class='form-control' style='text-transform:uppercase;' readonly='readonly'>"+
                                                "</div>"+
                                                "<div class='col-sm-2'>"+
                                                    "<input type='text' value='"+arregloMedicamentosFua[x].catalogo_medida+"' class='form-control' style='text-transform:uppercase;' readonly='readonly'>"+
                                                "</div>"+
                                                "<div class='col-sm-1'>"+
                                                    "<input type='text' value='"+arregloMedicamentosFua[x].docf_item_cant+"' class='form-control' style='text-transform:uppercase;' readonly='readonly'>"+
                                                "</div>"+
                                                "<div class='col-sm-1'>"+
                                                    "<input type='text' value='"+EntregaMedicamentos_auditoriaEmitidos+"' class='form-control' style='text-transform:uppercase;' readonly='readonly'>"+
                                                "</div>"+
                                                "<div class='col-sm-1'>"+
                                                    "<input type='text' value='"+arregloMedicamentosFua[x].diagnostico_estado+"' class='form-control' style='text-transform:uppercase;' readonly='readonly'>"+
                                                "</div>"+
                                                "<div class='col-sm-1'>"+
                                                    "<input type='text' value='"+arregloMedicamentosFua[x].cambio_cantidad+"' class='form-control' style='text-transform:uppercase;' readonly='readonly'>"+
                                                "</div>"+
                                                    "<a class='btn btn-warning btn-sm' href='#' onclick='Mostrar("+arregloMedicamentosFua[x].catalogo_cod+")' data-toggle='modal' data-target='#editarDatosMedicamentos'><i class='fas fa-pencil-alt text-white'></i></a>"+
                                                "</div>"+
                                            "</div>";

                $("#medicamentosFuaActivos_datosFarmacia").append(valoresMedicamentos);
            }
        }

        if(jQuery.isEmptyObject(respuesta["datosLaboratorio"])){
            $('.rowDatosLaboratorio').remove();
            $("#laboratorioFua_auditoriaEmitidos").css("display","block");
            $("#laboratorioFuaActivos_datosLaboratorio").css("display","none");
        }else{
            $("#laboratorioFuaActivos_datosLaboratorio").css("display","block");
            var arregloLaboratorioFua = respuesta["datosLaboratorio"];
            /* console.log(arregloLaboratorioFua); */

            for(var x=0;x<arregloLaboratorioFua.length;x++){
                $("#laboratorioFua_auditoriaEmitidos").css("display","block");

                var valoresLaboratorio =   "<div class='row rowDatosLaboratorio' style='margin-bottom:5px;'>"+
                                                "<div class='col-sm-2'>"+
                                                    "<input type='text' value='"+arregloLaboratorioFua[x].codigoCPMS+"' class='form-control' style='text-transform:uppercase;' readonly='readonly'>"+
                                                "</div>"+
                                                "<div class='col-sm-4'>"+
                                                    "<input type='text' value='"+arregloLaboratorioFua[x].denominacionCorta+"' class='form-control' style='text-transform:uppercase;' readonly='readonly'>"+
                                                "</div>"+
                                                "<div class='col-sm-2'>"+
                                                    "<input type='text' value='S/ "+arregloLaboratorioFua[x].exa_lab_precio_1+"' class='form-control' style='text-transform:uppercase;' readonly='readonly'>"+
                                                "</div>"+
                                                "<div class='col-sm-1'>"+
                                                    "<input type='text' value='1' class='form-control' style='text-transform:uppercase;' readonly='readonly'>"+
                                                "</div>"+
                                                "<div class='col-sm-1'>"+
                                                    "<input type='text' value='1' class='form-control' style='text-transform:uppercase;' readonly='readonly'>"+
                                                "</div>"+
                                                "<div class='col-sm-1'>"+
                                                    "<input type='text' value='' class='form-control' style='text-transform:uppercase;' readonly='readonly'>"+
                                                "</div>"+
                                                    "<a class='btn btn-warning btn-sm' href='#' onclick='Mostrar1("+arregloLaboratorioFua[x].codigoCPMS+")' data-toggle='modal' data-target='#editarDatosLaboratorio'><i class='fas fa-pencil-alt text-white'></i></a>"+
                                                "</div>"+
                                            "</div>";

                $("#laboratorioFuaActivos_datosLaboratorio").append(valoresLaboratorio);
            }

        }

        //boton salir

        $("#salir_actualizarFuaF_auditoriaEmitidos").on('click',function(){
            $('.rowDatosMedicamentos').remove();
            $('.rowDatosLaboratorio').remove();
        });

        $("#botonCerrarVerFua_auditoriaEmitidos").on('click',function(){
            $('.rowDatosMedicamentos').remove();
            $('.rowDatosLaboratorio').remove();
        });

        var arregloFua = respuesta["datosFuaGeneral"];
        for(var x=0;x<arregloFua.length;x++){

        $("#personalAtiendeF_auditoriaEmitidos").val(arregloFua[x].PersonaAtiende).trigger('change.select2');
        $('#personalAtiendeF_auditoriaEmitidos').attr('readonly','readonly');
        $("#lugarAtencionF_auditoriaEmitidos").val(arregloFua[x].LugarAtencion).trigger('change.select2');
        $('#lugarAtencionF_auditoriaEmitidos').attr('readonly','readonly');
        $("#tipoAtencionF_auditoriaEmitidos").val(arregloFua[x].TipoAtencion).trigger('change.select2');
        $('#tipoAtencionF_auditoriaEmitidos').attr('readonly','readonly');
        $("#disaF_auditoriaEmitidos").val(arregloFua[x].DISA);
        $("#loteF_auditoriaEmitidos").val(arregloFua[x].Lote);
        $("#numeroF_auditoriaEmitidos").val(arregloFua[x].Numero);
        $("#apellidoPaternoF_auditoriaEmitidos").val(arregloFua[x].ApellidoPaterno);
        $("#apellidoMaternoF_auditoriaEmitidos").val(arregloFua[x].ApellidoMaterno);
        $("#primerNombreF_auditoriaEmitidos").val(arregloFua[x].PrimerNombre);
        $("#otroNombreF_auditoriaEmitidos").val(arregloFua[x].OtrosNombres);

        /* INICIO DEL SEXO DEL PACIENTE */
        if(arregloFua[x].Sexo == 1){
          $("#sexoF_auditoriaEmitidos").val("MASCULINO");
        }else if(arregloFua[x].Sexo == 0){
          $("#sexoF_auditoriaEmitidos").val("FEMENINO");
        }else{
          $("#sexoF_auditoriaEmitidos").val("");
        }
        /* FIN DEL SEXO DEL PACIENTE */

        $("#fechaNacimientoF_auditoriaEmitidos").val(arregloFua[x].FechaNacimiento);
        $("#historiaF_auditoriaEmitidos").val(arregloFua[x].HistoriaClinica);

        /* INICIO DEL TIPO DEL DOCUMENTO DEL PACIENTE */
        if(arregloFua[x].TipoDocumentoIdentidad == 1){
          $("#tipoDocumentoF_auditoriaEmitidos").val("D.N.I.");
        }else if(arregloFua[x].TipoDocumentoIdentidad == 3){
          $("#tipoDocumentoF_auditoriaEmitidos").val("C.E.");
        }else{
          $("#tipoDocumentoF_auditoriaEmitidos").val("");
        }
        /* FIN DEL TIPO DEL DOCUMENTO DEL PACIENTE */

        $("#numeroDocumentoF_auditoriaEmitidos").val(arregloFua[x].NroDocumentoIdentidad);
        $("#componenteF_auditoriaEmitidos").val(arregloFua[x].Componente).trigger('change.select2');
        $('#componenteF_auditoriaEmitidos').attr('readonly','readonly');
        $("#codigoAsegurado1F_auditoriaEmitidos").val(arregloFua[x].DISAAsegurado);
        $("#codigoAsegurado2F_auditoriaEmitidos").val(arregloFua[x].LoteAsegurado);
        $("#codigoAsegurado3F_auditoriaEmitidos").val(arregloFua[x].NumeroAsegurado);
        $("#fechaF_auditoriaEmitidos").val(arregloFua[x].FechaAtencion);
        $("#horaF_auditoriaEmitidos").val(arregloFua[x].HoraAtencion);
        $("#codigoPrestacionalF_auditoriaEmitidos").val(arregloFua[x].CodigoPrestacional).trigger('change.select2');
        $('#codigoPrestacionalF_auditoriaEmitidos').attr('readonly','readonly');
        $("#conceptoPrestacionalF_auditoriaEmitidos").val(arregloFua[x].ModalidadAtencion).trigger('change.select2');
        $('#conceptoPrestacionalF_auditoriaEmitidos').attr('readonly','readonly');
        $("#destinoAseguradoF_auditoriaEmitidos").val(arregloFua[x].DestinoAsegurado).trigger('change.select2');
        $('#destinoAseguradoF_auditoriaEmitidos').attr('readonly','readonly');
        $("#diagnosticoF_auditoriaEmitidos").val(arregloFua[x].pdr1_cod).trigger('change.select2');
        $('#diagnosticoF_auditoriaEmitidos').attr('readonly','readonly');
        $("#codigoCieNF_auditoriaEmitidos").val(arregloFua[x].cie1_cod);

        if(arregloFua[x].CodigoPrestacional == '065'){
          $(".hospitalizacion_oculto").css("display","block");
          $("#fechaIngresoF_auditoriaEmitidos").val(arregloFua[x].FechaIngreso);
          $("#fechaAltaF_auditoriaEmitidos").val(arregloFua[x].FechaAlta);
          $("#fechaIngresoF_auditoriaEmitidos").attr('required',true);

          /* REALIZAMOS UN AJAX PARA ACTUALIZAR LA FECHA DE ATENCIÓN */
            if($("#fechaAltaF_auditoriaEmitidos").val() != ''){
              let fechaAltaVerFua = $("#fechaAltaF_auditoriaEmitidos").val();
              let idFuaVerFua = $("#idFua_auditoriaEmitidos").val();

              /* ACTUALIZAR FECHA DE ATENCIÓN */
              $.ajax({
                url: '{{ route('consultar.fechaAltaVerFuaAuditoriaEmitidos') }}',
                data: {fechaAltaVerFua,idFuaVerFua},
                success: function(respuesta){
                  /* console.log(respuesta); */
                  if(respuesta[0] == "FECHA_ATENCION_ACTUALIZADO"){
                    console.log("SE ACTUALIZÓ DE MANERA CORRECTA LA FECHA DE ATENCIÓN");
                    $("#fechaF_auditoriaEmitidos").val(respuesta[1][0]["FechaHoraAtencion"]);
                    $("#horaF_auditoriaEmitidos").val("00:00:00");
                  } 
                },

                error: function(jqXHR,textStatus,errorThrown){
                  console.error(textStatus + " " + errorThrown);
                }
              });
              /* FIN DE ACTUALIZAR FECHA DE ATENCIÓN */
            }else{

              let fechaAltaVerFua = $("#fechaAltaF_auditoriaEmitidos").val();
              let idFuaVerFua = $("#idFua_auditoriaEmitidos").val();

              $.ajax({
                url: '{{ route('consultar.fechaAltaVerFuaAuditoriaEmitidos') }}',
                data: {fechaAltaVerFua,idFuaVerFua},
                success: function(respuesta){
                  /* console.log(respuesta); */
                  if(respuesta[0] == "FECHA_ATENCION_ACTUALIZADO"){
                    console.log("SE ACTUALIZÓ DE MANERA CORRECTA LA FECHA DE ATENCIÓN");
                    $("#fechaF_auditoriaEmitidos").val(respuesta[1][0]["FechaHoraAtencion"]);
                    $("#horaF_auditoriaEmitidos").val("");
                  } 
                },

                error: function(jqXHR,textStatus,errorThrown){
                  console.error(textStatus + " " + errorThrown);
                }
              });
            }
          /* FIN DE AJAX PARA ACTUALIZAR LA FECHA DE ATENCIÓN */
        }else{
          $(".hospitalizacion_oculto").css("display","none");
          $("#fechaIngresoF_auditoriaEmitidos").val("");
          $("#fechaAltaF_auditoriaEmitidos").val("");
          $("#span_fechaIngresoF_auditoriaEmitidos").css("display","none");
          $("#fechaIngresoF_auditoriaEmitidos").removeAttr("required");
        }

        /* TIPO DE DOCUMENTO DEL RESPONSABLE */
        if(arregloFua[x].TipoDocResponsable == 1){
          $("#tipoDocumentoP_auditoriaEmitidos").val("D.N.I.");
        }else if(arregloFua[x].TipoDocResponsable == 3){
          $("#tipoDocumentoP_auditoriaEmitidos").val("C.E.");
        }else{
          $("#tipoDocumentoP_auditoriaEmitidos").val("");
        }
        /* FIN DEL TIPO DE DOCUMENTO DEL RESPONSABLE */

        $("#numeroDocumentoP_auditoriaEmitidos").val(arregloFua[x].NroDocResponsable);
        $("#nombresApellidosP_auditoriaEmitidos").val(arregloFua[x].personalAtiende_id).trigger('change.select2');
        $('#nombresApellidosP_auditoriaEmitidos').attr('readonly','readonly');
        $("#tipoPersonalSaludF_auditoriaEmitidos").val(arregloFua[x].TipoPersonalSalud).trigger('change.select2');
        $('#tipoPersonalSaludF_auditoriaEmitidos').attr('readonly','readonly');
        $("#egresadoF_auditoriaEmitidos").val(arregloFua[x].EsEgresado).trigger('change.select2');
        $('#egresadoF_auditoriaEmitidos').attr('readonly','readonly');
        $("#colegiaturaF_auditoriaEmitidos").val(arregloFua[x].NroColegiatura);
        $("#especialidadF_auditoriaEmitidos").val(arregloFua[x].Especialidad).trigger('change.select2');
        $('#especialidadF_auditoriaEmitidos').attr('readonly','readonly');
        $("#rneF_auditoriaEmitidos").val(arregloFua[x].NroRNE);
        $("#codigoReferenciaF_auditoriaEmitidos").val(arregloFua[x].IPRESSRefirio);
        $("#pacienteIdF_auditoriaEmitidos").val(arregloFua[x].persona_id);
        $("#numeroReferenciaF_auditoriaEmitidos").val(arregloFua[x].NroHojaReferencia);
        $("#atencionIdF_auditoriaEmitidos").val(arregloFua[x].IdAtencion);
        } 

        var CodigoCie = $("#codigoCieNF_auditoriaEmitidos").val();

        if(CodigoCie != ''){
        /* INICIO CODIGO CIE BUSQUEDA CONSULTAS */
        $.ajax({
          url: '{{ route('consultar.codigoCieAuditoriaEmitidos') }}',
          data: {CodigoCie},
          success: function(respuesta){
            /* console.log("respuesta",respuesta);*/

            if (CodigoCie == null){   
            $("#codigoCieNF_auditoriaEmitidos").val('');
            $('#codigoCieNF_auditoriaEmitidos').attr('readonly','readonly');
            $('#codigoCieF_auditoriaEmitidos').val('');
            $('#codigoCieF_auditoriaEmitidos').attr('readonly','readonly');
            }else{
            var arregloCodigoCie = JSON.parse(respuesta);
            for(var x=0;x<arregloCodigoCie.length;x++){
              $("#codigoCieNF_auditoriaEmitidos").val(arregloCodigoCie[x].cie_cod);
              $('#codigoCieNF_auditoriaEmitidos').attr('readonly','readonly');
              $("#codigoCieF_auditoriaEmitidos").val(arregloCodigoCie[x].cie_desc);
              $('#codigoCieF_auditoriaEmitidos').attr('readonly','readonly');
            }
            }
          },

          error: function(jqXHR,textStatus,errorThrown){
            console.error(textStatus + " " + errorThrown);
          }
        });
        /* FIN DE AJAX PARA EXTRAER VALORES DE CODIGO CIE */
        }else{
        $("#codigoCieF_auditoriaEmitidos").val("");
        }

        if($("#pacienteIdF_auditoriaEmitidos").val() != '' && $("#codigoReferenciaF_auditoriaEmitidos").val() != ''){
        var idPaciente = $("#pacienteIdF_auditoriaEmitidos").val();
        /* console.log(idPaciente); */

        /* INICIO REFERENCIAS CONSULTAS */
          $.ajax({
          url: '{{ route('consultar.referenciasAuditoriaEmitidos') }}',
          data: {idPaciente},
          success: function(respuesta){
            /* console.log("respuesta",respuesta); */

            var arregloReferencia = JSON.parse(respuesta);
            for(var x=0;x<arregloReferencia.length;x++){
            $("#descripcionReferenciaF_auditoriaEmitidos").val(arregloReferencia[x].descripcion);
            }
          },

          error: function(jqXHR,textStatus,errorThrown){
            console.error(textStatus + " " + errorThrown);
          }
          });
        /* FIN DE AJAX PARA EXTRAER VALORES DE REFERENCIA */
        }else{
        $("#descripcionReferenciaF_auditoriaEmitidos").val("");
        }

      },

      error: function(jqXHR,textStatus,errorThrown){
        console.error(textStatus + " " + errorThrown);
      }

      });
    });
    //========================================================================FIN

    $("#btnVerFUA_auditoriaEmitidos").css("display","block");
    $("#frmVerFua_auditoriaEmitidos").attr('action',$(location).attr('href')+"/"+tablaAuditoriaEmitidos.row($(this)).data()["Fua_id"]);

  }
  });
});

</script>

<script type="text/javascript">

    let Mostrar = function(id)
        {

        let NroFuaM = $("#idFua_auditoriaEmitidos").val();

        $.ajax({
            url: ruta+'/auditoriaEmitidos/showMedicamentos/'+id,
            data:{NroFuaM},
            success: function(respuesta){
                var arreglo = JSON.parse(respuesta);
                
                if(arreglo == ""){
                    alert("El medicamento no tiene información!");
                    $("#codigoSismedM_auditoriaEmitidos").val("");
                    $("#nombreM_auditoriaEmitidos").val("");
                    $("#tipoM_auditoriaEmitidos").val("");
                    $("#prescritoM_auditoriaEmitidos").val("");
                    $("#entregadoM_auditoriaEmitidos").val("");
                    $("#diagnosticoM_auditoriaEmitidos").val("");
                    $("#observacionM_auditoriaEmitidos").val("");
                    $("#precioUnitarioM_auditoriaEmitidos").val("");
                    $("#precioTotalM_auditoriaEmitidos").val("");
                }else{
                    for(var x=0;x<arreglo.length;x++){

                        let EntregaMedicamentosM_auditoriaEmitidos = '';

                        if(arreglo[x].docf_flag == 'O'){
                            EntregaMedicamentosM_auditoriaEmitidos = arreglo[x].docf_item_cant;
                        }else if(arreglo[x].docf_flag == 'X'){
                            EntregaMedicamentosM_auditoriaEmitidos = '';
                        }else{
                            EntregaMedicamentosM_auditoriaEmitidos = '';
                        }

                        $("#codigoSismedM_auditoriaEmitidos").val(arreglo[x].catalogo_sismed);
                        $("#nombreM_auditoriaEmitidos").val(arreglo[x].catalogo_desc);
                        $("#tipoM_auditoriaEmitidos").val(arreglo[x].catalogo_medida);
                        $("#prescritoM_auditoriaEmitidos").val(arreglo[x].docf_item_cant);
                        $("#entregadoM_auditoriaEmitidos").val(EntregaMedicamentosM_auditoriaEmitidos);
                        $("#diagnosticoM_auditoriaEmitidos").val(arreglo[x].diagnostico_estado);
                        $("#observacionM_auditoriaEmitidos").val(arreglo[x].cambio_cantidad);
                        $("#idMedicamentoM_auditoriaEmitidos").val(arreglo[x].catalogo_cod);
                        $("#precioUnitarioM_auditoriaEmitidos").val('S/ ' + arreglo[x].docf_item_precio);
                        $("#precioTotalM_auditoriaEmitidos").val('S/ ' + arreglo[x].docf_item_total);
                        $("#actualizarMedicamentoM_auditoriaEmitidos").attr('action',$(location).attr('href')+"/"+"updateMedicamentos/"+id);
                    }
                }
            },

            error: function(jqXHR,textStatus,errorThrown){
                console.error(textStatus + " " + errorThrown);
            }
        });
        }

        let Mostrar1 = function(id)
        {

        let NroFuaM = $("#idFua_auditoriaEmitidos").val();

        $.ajax({
            url: ruta+'/auditoriaEmitidos/showLaboratorio/'+id,
            data:{NroFuaM},
            success: function(respuesta){
                /* console.log(respuesta); */
                var arreglo = JSON.parse(respuesta);
                
                if(arreglo == ""){
                    alert("Datos del laboratorio no tiene información!");
                    $("#codigoCpmsM_auditoriaEmitidos").val("");
                    $("#nombreL_auditoriaEmitidos").val("");
                    $("#indicadoL_auditoriaEmitidos").val("");
                    $("#ejecutadoL_auditoriaEmitidos").val("");
                    $("#diagnosticoL_auditoriaEmitidos").val("");
                    $("#precioTotalL_auditoriaEmitidos").val("");
                }else{
                    for(var x=0;x<arreglo.length;x++){
                        $("#codigoCpmsM_auditoriaEmitidos").val(arreglo[x].codigoCPMS);
                        $("#nombreL_auditoriaEmitidos").val(arreglo[x].denominacionCorta);
                        $("#indicadoL_auditoriaEmitidos").val(1);
                        $("#ejecutadoL_auditoriaEmitidos").val(1);
                        $("#diagnosticoL_auditoriaEmitidos").val("");
                        $("#idLaboratorioM_auditoriaEmitidos").val(arreglo[x].codigoCPMS);
                        $("#precioTotalL_auditoriaEmitidos").val('S/ ' + arreglo[x].exa_lab_precio_1);
                        $("#actualizarLaboratorioM_auditoriaEmitidos").attr('action',$(location).attr('href')+"/"+"updateLaboratorio/"+id);
                    }
                }
            },

            error: function(jqXHR,textStatus,errorThrown){
                console.error(textStatus + " " + errorThrown);
            }
        });
        }

        $("#actualizarLaboratorioM_auditoriaEmitidos").submit(function(e){
            e.preventDefault();

            let idLaboratorio = $("#idLaboratorioM_auditoriaEmitidos").val();
            
            swal({
                            title: '¿Está seguro de actualizar los datos?',
                            text: "¡Si no lo está puede cancelar la acción!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Si, actualizar datos!'
                          }).then(function(result){
                            if(result.value){
                              $.ajax({
                                url: ruta+'/auditoriaEmitidos/updateLaboratorio/'+idLaboratorio,
                                method: "POST",
                                data: $("#actualizarLaboratorioM_auditoriaEmitidos").serialize(),
                                  success:function(respuesta){
                                    console.log("respuesta",respuesta);

                                    if(respuesta == 'NO-VALIDACION'){
                                      swal("Existen valores incorrectos");
                                    }

                                    if(respuesta == "ACTUALIZAR-LABORATORIO"){
                                      swal({
                                        type:"success",
                                        title: "¡La atención ha sido actualizado correctamente!",
                                        showConfirmButton: true,
                                        confirmButtonText: "Cerrar"
                                      }).then(function(result){
                                         $('#editarDatosLaboratorio').modal('hide');
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

        $("#actualizarMedicamentoM_auditoriaEmitidos").submit(function(e){
            e.preventDefault();

            let idMedicamento = $("#idMedicamentoM_auditoriaEmitidos").val();
            
            swal({
                            title: '¿Está seguro de actualizar los datos del Medicamento?',
                            text: "¡Si no lo está puede cancelar la acción!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Si, actualizar Medicamento!'
                          }).then(function(result){
                            if(result.value){
                              $.ajax({
                                url: ruta+'/auditoriaEmitidos/updateMedicamentos/'+idMedicamento,
                                method: "POST",
                                data: $("#actualizarMedicamentoM_auditoriaEmitidos").serialize(),
                                  success:function(respuesta){
                                    /* console.log("respuesta",respuesta); */

                                    if(respuesta == 'NO-VALIDACION'){
                                      swal("Existen valores incorrectos");
                                    }

                                    if(respuesta == "ACTUALIZAR-MEDICAMENTO"){
                                      swal({
                                        type:"success",
                                        title: "¡El medicamento ha sido actualizado correctamente!",
                                        showConfirmButton: true,
                                        confirmButtonText: "Cerrar"
                                      }).then(function(result){
                                         $('#editarDatosMedicamentos').modal('hide');
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

        $("#auditar_verFuaF_auditoriaEmitidos").click(function(e){
            e.preventDefault();

            swal({
                title: '¿Está seguro de auditar el FUA?',
                text: "¡Si no lo está puede cancelar la acción!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, auditar FUA!'
            }).then(function(result){

            if(result.value){
            
            let idFuaAuditarFua = $("#idFua_auditoriaEmitidos").val();

            $.ajax({
                url: ruta+'/auditoriaEmitidos/auditarFua',
                data: {idFuaAuditarFua},
                success:function(respuesta){
                    /* console.log("respuesta",respuesta); */

/*                     if(respuesta == 'NO-VALIDACION'){
                        swal("Existen valores incorrectos");
                    } */

                    if(respuesta == "AUDITAR-FUA"){
                        swal({
                            type:"success",
                            title: "¡El FUA ha sido auditado correctamente!",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                        }).then(function(result){
                            $('#verFua_auditoriaEmitidos').modal('hide');

                            tablaAuditoriaEmitidos.clear().destroy();
                            $("#btnVerFUA_auditoriaEmitidos").css("display","none");

                            let valorUrlAjax2 = '';

                            if($('#fechaInicio_auditoriaEmitidos').val() != '' || $('#fechaFin_auditoriaEmitidos').val() != ''){
                                valorUrlAjax2 = '{{ route('consultar.fechasAEmitidos') }}';
                            }else{
                                valorUrlAjax2 = ruta + '/auditoriaEmitidos';
                            }

                            /*=============================================
                            DataTable de Auditoria Emitidos
                            =============================================*/
                            tablaAuditoriaEmitidos = $("#tablaAuditoriaEmitidos").DataTable({
                                /* processing: true, */
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
	                            /* dom: 'Bfrtip', */
                                "buttons": [
                                    {
			                            "extend": 'excel',
			                            "footer": false,
			                            "title": 'AUDITORIA EMITIDOS',
			                            "filename": 'AUDITORIA_EMITIDOS',
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
                                ajax: {
                                    url: valorUrlAjax2,
                                    data: {'_token' : $('input[name=_token]').val(),
                                          'fechaInicio_auditoriaEmitidos' : $('#fechaInicio_auditoriaEmitidos').val(),
                                          'fechaFin_auditoriaEmitidos': $('#fechaFin_auditoriaEmitidos').val()}
                                },

                                "columnDefs":[
		        {
		        "searchable": true,
		        "orderable" : true,
		        "targets" : 0
	            },
		{
			className:"position",targets: [8],
			"render": function ( data, type, row ) {
				if(data == 1){
					return '<i class="fas fa-check" style="color:green;text-align:center;"></i>'
				}else{
					return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"></i>'
				}
			}
		}
            ],

                                "order": [[3, "asc"]],

                                columns: [
		    {
                data: 'FUA',
                name: 'FUA'
            },
		    {
                data: 'Paciente',
                name: 'Paciente'
            },
		    {
                data: 'HistoriaClinica',
                name: 'HistoriaClinica'
            },
		    {
                data: 'FechaHoraRegistro',
                name: 'FechaHoraRegistro'
            },
		    {
                data: 'FechaHoraAtencion',
                name: 'FechaHoraAtencion'
            },
		    {
                data: 'CodigoPrestacional',
                name: 'CodigoPrestacional'
            },
		    {
                data: 'Profesional',
                name: 'Profesional'
            },
            {
                data: 'cie1_cod',
                name: 'cie1_cod'
            },
		{
            data: 'auditarFua_estado',
            name: 'auditarFua_estado'
        }
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

  	                            } ,
	
	                              deferRender:true,
                                "scroller": {
	                                "loadingIndicator": true
	                              },
	                              scroller:true,
	                              scrollCollapse:true,
	                              paging:true,
	                              scrollY:"405PX",
	                              scrollX:true
                            });

                                    /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
        $('div.dataTables_filter input').unbind();
        $("div.dataTables_filter input").keyup( function (e) {
	        if (e.keyCode == 13) {
		        tablaAuditoriaEmitidos.search(this.value).draw();
	        }
        });
        /* FIN DE BUSQUEDA POR ENTER */

                            tablaAuditoriaEmitidos.draw();
                            
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
</script>

<script type="text/javascript">
                  $("#codigoCieNF_auditoriaEmitidos").keypress(function(e) {
                      if(e.which == 13) {
                        e.preventDefault();

                        var CodigoCie = $("#codigoCieNF_auditoriaEmitidos").val();

                        if (CodigoCie != '') {
                          /* Petición AJAX */
                          $.ajax({
                            url: '{{ route('consultar.codigoCieAuditoriaEmitidos') }}',
                            data: {CodigoCie},
                            success: function(respuesta){
                              /* console.log(respuesta); */
                              var arreglo = JSON.parse(respuesta);

                              if(arreglo == ""){
                                alert("El Código CIE-10 no existe");
                                $("#codigoCieNF_auditoriaEmitidos").val("");
                                $("#codigoCieF_auditoriaEmitidos").val("");
                              }else{
                                for(var x=0;x<arreglo.length;x++){
                                    $("#codigoCieNF_auditoriaEmitidos").val(arreglo[x].cie_cod);
                                    $("#codigoCieF_auditoriaEmitidos").val(arreglo[x].cie_desc);
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
                          $('#codigoCieNF_auditoriaEmitidos').focus();
                          $("#codigoCieNF_auditoriaEmitidos").val("");
                        }
                      }
                    });

                    $("#actualizar_verFuaF_auditoriaEmitidos").on('click',function(){

                      /* PONEMOS TODOS LOS VALORES QUE SE VAN A PODER EDITAR */
                      $("#cerrar_actualizarFuaF_auditoriaEmitidos").css("display","none");
                      $("#imprimir_actualizarFuaF_auditoriaEmitidos").css("display","none");
                      $("#actualizar_actualizarFuaF_auditoriaEmitidos").css("display","none");
                      $("#auditar_verFuaF_auditoriaEmitidos").css("display","none");
                      $("#registrar_actualizarFuaF_auditoriaEmitidos").css("display","block");
                      $("#cancelar_actualizarFuaF_auditoriaEmitidos").css("display","block");
                      $("#nombresApellidosP_auditoriaEmitidos").removeAttr("readonly");

                      var idCodigoPrestacional = $("#codigoPrestacionalF_auditoriaEmitidos").val();

                      if(idCodigoPrestacional == '065'){
                        $("#tipoAtencionF_auditoriaEmitidos").attr('readonly','readonly');
                      }else{
                        $("#tipoAtencionF_auditoriaEmitidos").removeAttr("readonly");
                      }

                      $("#codigoPrestacionalF_auditoriaEmitidos").removeAttr("readonly");
                      $("#personalAtiendeF_auditoriaEmitidos").removeAttr("readonly");
                      $("#lugarAtencionF_auditoriaEmitidos").removeAttr("readonly");
                      $("#historiaF_auditoriaEmitidos").removeAttr("readonly");
                      $("#fechaF_auditoriaEmitidos").removeAttr("readonly");
                      $("#horaF_auditoriaEmitidos").removeAttr("readonly");
                      $("#conceptoPrestacionalF_auditoriaEmitidos").removeAttr("readonly");
                      $("#destinoAseguradoF_auditoriaEmitidos").removeAttr("readonly");
                      $("#diagnosticoF_auditoriaEmitidos").removeAttr("readonly");
                      $("#codigoCieNF_auditoriaEmitidos").removeAttr("readonly");
                      $("#botonCerrarVerFua_auditoriaEmitidos").css("display","none");
                      
                      $("#cancelar_actualizarFuaF_auditoriaEmitidos").on('click',function(){

                        $("#actualizarFuaF_auditoriaEmitidos").validate().resetForm();
                        $("#cerrar_actualizarFuaF_auditoriaEmitidos").css("display","block");
                        $("#imprimir_actualizarFuaF_auditoriaEmitidos").css("display","block");
                        $("#actualizar_actualizarFuaF_auditoriaEmitidos").css("display","block");
                        $("#auditar_verFuaF_auditoriaEmitidos").css("display","block");
                        $("#registrar_actualizarFuaF_auditoriaEmitidos").css("display","none");
                        $("#cancelar_actualizarFuaF_auditoriaEmitidos").css("display","none");
                        $("#nombresApellidosP_auditoriaEmitidos").attr('readonly','readonly');
                        $("#tipoAtencionF_auditoriaEmitidos").attr('readonly','readonly');
                        $("#codigoPrestacionalF_auditoriaEmitidos").attr('readonly','readonly');
                        $("#botonCerrarVerFua_auditoriaEmitidos").css("display","block");
                        $("#personalAtiendeF_auditoriaEmitidos").attr('readonly','readonly');
                        $("#lugarAtencionF_auditoriaEmitidos").attr('readonly','readonly');
                        $("#historiaF_auditoriaEmitidos").attr('readonly','readonly');
                        $("#fechaF_auditoriaEmitidos").attr('readonly','readonly');
                        $("#horaF_auditoriaEmitidos").attr('readonly','readonly');
                        $("#conceptoPrestacionalF_auditoriaEmitidos").attr('readonly','readonly');
                        $("#destinoAseguradoF_auditoriaEmitidos").attr('readonly','readonly');
                        $("#diagnosticoF_auditoriaEmitidos").attr('readonly','readonly');
                        $("#codigoCieNF_auditoriaEmitidos").attr('readonly','readonly');
                      });

                      $("#registrar_actualizarFuaF1_auditoriaEmitidos").on('click',function(){

                        if($("#actualizarFuaF_auditoriaEmitidos").valid() == false){
		                      return;
	                      }

                          let personalAtiendeF_auditoriaEmitidos = $("#personalAtiendeF_auditoriaEmitidos").val();
	                      let lugarAtencionF_auditoriaEmitidos = $("#lugarAtencionF_auditoriaEmitidos").val();
	                      /* let tipoAtencionF_fuasEmitidos = $("#tipoAtencionF_fuasEmitidos").val(); */
	                      let codigoReferenciaF_auditoriaEmitidos = $("#codigoReferenciaF_auditoriaEmitidos").val();
	                      let descripcionReferenciaF_auditoriaEmitidos = $("#descripcionReferenciaF_auditoriaEmitidos").val();
	                      let numeroReferenciaF_auditoriaEmitidos = $("#numeroReferenciaF_auditoriaEmitidos").val();
	                      let tipoDocumentoF_auditoriaEmitidos = $("#tipoDocumentoF_auditoriaEmitidos").val();
	                      let numeroDocumentoF_auditoriaEmitidos = $("#numeroDocumentoF_auditoriaEmitidos").val();
	                      let componenteF_auditoriaEmitidos = $("#componenteF_auditoriaEmitidos").val();
	                      let codigoAsegurado2F_auditoriaEmitidos = $("#codigoAsegurado2F_auditoriaEmitidos").val();
	                      let codigoAsegurado3F_auditoriaEmitidos = $("#codigoAsegurado3F_auditoriaEmitidos").val();
	                      let apellidoPaternoF_auditoriaEmitidos = $("#apellidoPaternoF_auditoriaEmitidos").val();
	                      let apellidoMaternoF_auditoriaEmitidos = $("#apellidoMaternoF_auditoriaEmitidos").val();
	                      let primerNombreF_auditoriaEmitidos = $("#primerNombreF_auditoriaEmitidos").val();
	                      let sexoF_auditoriaEmitidos = $("#sexoF_auditoriaEmitidos").val();
	                      let fechaNacimientoF_auditoriaEmitidos = $("#fechaNacimientoF_auditoriaEmitidos").val();
	                      let fechaF_auditoriaEmitidos = $("#fechaF_auditoriaEmitidos").val();
	                      let horaF_auditoriaEmitidos = $("#horaF_auditoriaEmitidos").val();
	                      let codigoPrestacionalF_auditoriaEmitidos = $("#codigoPrestacionalF_auditoriaEmitidos").val();
	                      let conceptoPrestacionalF_auditoriaEmitidos = $("#conceptoPrestacionalF_auditoriaEmitidos").val();
	                      let destinoAseguradoF_auditoriaEmitidos = $("#destinoAseguradoF_auditoriaEmitidos").val();
                          let historiaF_auditoriaEmitidos = $("#historiaF_auditoriaEmitidos").val();

                        $('#actualizarFuaF_auditoriaEmitidos').submit(function(e){

                          if($("#actualizarFuaF_auditoriaEmitidos").valid() == false){
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
                                url: '{{ route('consultar.actualizarFuaAuditoriaEmitidos') }}',
                                method: "POST",
                                data: $("#actualizarFuaF_auditoriaEmitidos").serialize(),
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
                                        $("#cerrar_actualizarFuaF_auditoriaEmitidos").css("display","block");
                                        $("#imprimir_actualizarFuaF_auditoriaEmitidos").css("display","block");
                                        $("#actualizar_actualizarFuaF_auditoriaEmitidos").css("display","block");
                                        $("#auditar_verFuaF_auditoriaEmitidos").css("display","block");
                                        $("#registrar_actualizarFuaF_auditoriaEmitidos").css("display","none");
                                        $("#cancelar_actualizarFuaF_auditoriaEmitidos").css("display","none");

                                        $('#nombresApellidosP_auditoriaEmitidos').attr('readonly','readonly');
                                        $("#tipoAtencionF_auditoriaEmitidos").attr('readonly','readonly');
                                        $("#codigoPrestacionalF_auditoriaEmitidos").attr('readonly','readonly');
                                        $("#botonCerrarVerFua_auditoriaEmitidos").css("display","block");
                                        $("#personalAtiendeF_auditoriaEmitidos").attr('readonly','readonly');
                                        $("#lugarAtencionF_auditoriaEmitidos").attr('readonly','readonly');
                                        $("#historiaF_auditoriaEmitidos").attr('readonly','readonly');
                                        $("#fechaF_auditoriaEmitidos").attr('readonly','readonly');
                                        $("#horaF_auditoriaEmitidos").attr('readonly','readonly');
                                        $("#conceptoPrestacionalF_auditoriaEmitidos").attr('readonly','readonly');
                                        $("#destinoAseguradoF_auditoriaEmitidos").attr('readonly','readonly');
                                        $("#diagnosticoF_auditoriaEmitidos").attr('readonly','readonly');
                                        $("#codigoCieNF_auditoriaEmitidos").attr('readonly','readonly');
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

                      $('select[name=nombresApellidosP_auditoriaEmitidos]').change(function(){
                        var idPersonal = $("#nombresApellidosP_auditoriaEmitidos").val();
                        /* console.log(idPersonal); */

                        if(idPersonal != ''){
                          $.ajax({
                            url: '{{ route('consultar.personalCAuditoriaEmitidos') }}',
                            data: {idPersonal},
                            success: function(respuesta){
                              /* console.log("respuesta",respuesta); */
                              var arregloPersonalC = JSON.parse(respuesta);
                              for(var x=0;x<arregloPersonalC.length;x++){
                                if(arregloPersonalC[x].ddi_cod == 1){
                                  $("#tipoDocumentoP_auditoriaEmitidos").val('D.N.I.');
                                }else{
                                  $("#numeroDocumentoP_auditoriaEmitidos").val('');
                                }
  
                                $("#numeroDocumentoP_auditoriaEmitidos").val(arregloPersonalC[x].ddi_nro);
                                $("#tipoPersonalSaludF_auditoriaEmitidos").val(arregloPersonalC[x].TipoPersonalSalud_id).trigger('change.select2');/* CORREGIR */
                                $("#egresadoF_auditoriaEmitidos").val(arregloPersonalC[x].Egresado_id).trigger('change.select2');/* CORREGIR */
                                $("#especialidadF_auditoriaEmitidos").val(arregloPersonalC[x].Especialidad_id).trigger('change.select2');/* CORREGIR */
                                $("#colegiaturaF_auditoriaEmitidos").val(arregloPersonalC[x].NroColegiatura);
                                $("#rneF_auditoriaEmitidos").val(arregloPersonalC[x].NroRNE);
                              }
                            },

                            error: function(jqXHR,textStatus,errorThrown){
                              console.error(textStatus + " " + errorThrown);
                            }
                          });
                        }else{
                          $("#tipoDocumentoP_auditoriaEmitidos").val('');
                          $("#numeroDocumentoP_auditoriaEmitidos").val('');
                          $("#tipoPersonalSaludF_auditoriaEmitidos").val('').trigger('change.select2');/* CORREGIR */
                          $("#egresadoF_auditoriaEmitidos").val('').trigger('change.select2');/* CORREGIR */
                          $("#especialidadF_auditoriaEmitidos").val('').trigger('change.select2');/* CORREGIR */
                          $("#colegiaturaF_auditoriaEmitidos").val('');
                          $("#rneF_auditoriaEmitidos").val('');
                        }
                      /* FIN DE PERSONAL DATOS GENERALES */
                      });

                      $('select[name=codigoPrestacionalF_auditoriaEmitidos]').change(function(){
                        
                        var idCodigoPrestacional = $("#codigoPrestacionalF_auditoriaEmitidos").val();

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
                                  $("#fechaAltaF_auditoriaEmitidos").val($("#fechaF_fuasEmitidos").val());
                                  $("#tipoAtencionF_auditoriaEmitidos").val("").trigger("change");/* CORREGIR */
                                  $("#tipoAtencionF_auditoriaEmitidos").attr('readonly','readonly');
                                }else{
                                  $(".hospitalizacion_oculto").css("display","none");
                                  $("#fechaAltaF_auditoriaEmitidos").val("");
                                  $("#tipoAtencionF_auditoriaEmitidos").val(1).trigger("change");/* CORREGIR */
                                }
                              }
                            });
                          }else{
                            $(".hospitalizacion_oculto").css("display","none");
                            $("#fechaAltaF_auditoriaEmitidos").val("");
                            $("#tipoAtencionF_auditoriaEmitidos").val(1).trigger("change");/* CORREGIR */
                            $("#tipoAtencionF_auditoriaEmitidos").removeAttr("readonly");
                          }
                      });
                    });

                    /* FIN DE IMPRIMIR FUA CON EL ID */

                    /* IMPRIMIR FUA CON EL ID DE ATENCIÓN */
                    $('#imprimir_verFuaF_auditoriaEmitidos').click(function(){

                      var IdAtencion = $("#atencionIdF_auditoriaEmitidos").val();
                      console.log(IdAtencion);

                      printJS({printable:ruta+'/'+'auditoriaEmitidos/reportesFUA/'+IdAtencion, type:'pdf', showModal:true});
                    });
                    /* FIN DE AJAX PARA EXTRAER VALORES DEL FUA */
 
</script>

@endsection

@section('javascript')
    <script src="{{ url('/') }}/js/auditoriaEmitidos.js"></script>
@endsection

@endif

@endforeach