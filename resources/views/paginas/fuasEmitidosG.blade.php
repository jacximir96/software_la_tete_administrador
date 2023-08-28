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
                    <h1>MODIFICACIÓN, IMPRESIÓN Y ANULACIÓN DE FUAS EMITIDOS</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
                        <li class="breadcrumb-item active">MODIFICACIÓN, IMPRESIÓN Y ANULACIÓN DE FUAS EMITIDOS</li>
                    </ol>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <div class="input-group">
                        <label for="lista_pacienteFuasEmitidosG" class="col-md-2 control-label">Paciente:</label>
                        <div class="col-md-4" id="lista_pacienteFuasEmitidosG"><!-- Información desde Javascript (auditoriaMedica.js) --></div>
                        <label for="lista_fuaFuasEmitidosG" class="col-md-2 control-label">N° FUA:</label>
                        <div class="col-md-4" id="lista_fuaFuasEmitidosG"><!-- Información desde Javascript (auditoriaMedica.js) --></div>
                    </div>

                    <div class="input-group">
                        <label for="lista_profesionalesFuasEmitidosG" class="col-md-2 control-label">Profesional:</label>
                        <div class="col-md-4" id="lista_profesionalesFuasEmitidosG"><!-- Información desde Javascript (auditoriaMedica.js) --></div>
                        <label for="lista_profesionalesFuasEmitidosG" class="col-md-2 control-label">Cod. Prestacional:</label>
                        <div class="col-md-4" id="lista_codigoPrestacionalFuasEmitidosG"><!-- Información desde Javascript (auditoriaMedica.js) --></div>
                    </div>

                    <form method="GET" action="{{ url('/') }}/fuasEmitidosG/buscarPorMes" id="frmFechas_fuasEmitidosG">
                        @csrf
                        <div class="input-group">
                            <label for="fechaInicio_fuasEmitidosG" class="col-md-2 control-label">Fecha de generación:</label>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="fechaInicio_fuasEmitidosG" id="fechaInicio_fuasEmitidosG"
                                style="text-transform: uppercase;" required>
                            </div>

                            <div class="col-md-2">
                                <input type="date" class="form-control" name="fechaFin_fuasEmitidosG" id="fechaFin_fuasEmitidosG"
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

                            <label for="historiaBD_fuasEmitidosG" class="col-md-1 control-label" style="">N° Historia:</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control inputRutaNumero" name="historiaBD_fuasEmitidosG" id="historiaBD_fuasEmitidosG"
                                style="text-transform: uppercase;" maxlength="6">
                            </div>

                            <label for="documentoBD_fuasEmitidosG" class="col-md-1 control-label">N° Documento:</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control inputRutaNumero" name="documentoBD_fuasEmitidosG" id="documentoBD_fuasEmitidosG"
                                style="text-transform: uppercase;" maxlength="9">
                            </div>
                            
                            <label for="fuaBD_fuasEmitidosG" class="col-md-1 control-label">N° FUA:</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control inputRutaNumero" name="fuaBD_fuasEmitidosG" id="fuaBD_fuasEmitidosG"
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
                
                            <form method="POST" action="" id="frmVerFua_fuasEmitidosG">
                                @csrf
                                <input type="text" class="form-control" name="idFua_fuasEmitidosG" id="idFua_fuasEmitidosG"
                                style="text-transform: uppercase;display:none;" required>

                                <button type="submit" class="btn btn-danger btn-sm boton-general" data-toggle="modal" data-target="#verFua_fuasEmitidosG"  
                                style="float:left;display:none;margin-right: 5px;" id="btnVerFUA_fuasEmitidosG"> 
							    <i class="fas fa-eye" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Ver FUA</button>
                            </form>

                            <form method="POST" action="{{ url('/') }}/fuasEmitidosG/volverDeAnuladoAGenerado" id="frmPasarAnulacionGeneracionFua_fuasEmitidosG">
                            @csrf
                                <input type="text" class="form-control" name="idFuaA_fuasEmitidosG" id="idFuaA_fuasEmitidosG"
                                style="text-transform: uppercase;display:none;">

                                <button type="submit" class="btn btn-warning btn-sm boton-general"  
                                style="float:left;margin-right: 5px;display:none;" id="btnPasarAnulacionGeneracionFUA_fuasEmitidosG"> 
							    <i class="fas fa-times" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Pasar FUA - Generado</button>
                            </form>

                            <form method="GET" action="" id="frmVerRolCitas_fuasEmitidosG">
                                @csrf
                                <input type="text" class="form-control" name="idCab_fuasEmitidosG" id="idCab_fuasEmitidosG"
                                style="text-transform: uppercase;display:none;" required>

                                <button type="submit" class="btn btn-dark btn-sm boton-general" data-toggle="modal" data-target="#verRolCitas_fuasEmitidosG"  
                                style="float:left;display:none;margin-right: 5px;" id="btnRolCitas_fuasEmitidosG"> 
							    <i class="fa fa-tasks" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Rol de Citas</button>
                            </form>
                        </div>

                        <div class="card-body">

                            <table class="table table-bordered table-striped display nowrap" width="100%" id="tablaFuasEmitidosG">
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
                                        <th>Estado</th>
                                        <th>Usuario</th>
                                        <th>Dias Transcurridos</th>
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

<div class="modal fade bd-example-modal-lg" id="verFua_fuasEmitidosG" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-content">
            <form method="POST" action="{{ url('/') }}/fuasEmitidosG/actualizarFua" enctype="multipart/form-data" id="actualizarFuaF_fuasEmitidosG"
            pagina="fuasEmitidosG">
            @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;"><i class="fa fa-pencil"></i> &nbsp; <span class="title-form">FUA</span></h4>
                    <button type="button" id="botonCerrarVerFua_fuasEmitidosG" class="close" data-dismiss="modal">&times;</button>
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
                                <input type="text" class="form-control" name="usuario_fuasEmitidosG" id="usuario_fuasEmitidosG"
                                value="{{ old('id',$element->id) }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                        @endforeach
                    </div>

                    <div class="form-group">
                        <div class="row" style="display:none;">
                            <div class="col-sm-12">
                                <label for="idFuaF_fuasEmitidosG">Id del Fua</label>
                                <input type="text" class="form-control" name="idFuaF_fuasEmitidosG" id="idFuaF_fuasEmitidosG"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <label for="disaF_fuasEmitidosG">N° del formato</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="disaF_fuasEmitidosG" id="disaF_fuasEmitidosG"
                                style="text-transform: uppercase;text-align:center;font-weight:900;background:green;color:white;" required readonly="true">
                            </div>

                            <div class="col-sm-1">
                                <input type="text" class="form-control" name="loteF_fuasEmitidosG" id="loteF_fuasEmitidosG"
                                style="text-transform: uppercase;text-align:center;font-weight:900;background:green;color:white;" required readonly="true">
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="numeroF_fuasEmitidosG" id="numeroF_fuasEmitidosG"
                                style="text-transform: uppercase;text-align:center;font-weight:900;background:green;color:white;" required readonly="true">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
						<div class="row">
							<div class="col-sm-4">
								<label for="personalAtiendeF_fuasEmitidosG">Personal que atiende <span class="text-danger"> * </span></label>
                                <select class="form-control select-2 select2" name="personalAtiendeF_fuasEmitidosG" id="personalAtiendeF_fuasEmitidosG">
                                    <option value="">-- Seleccionar el Personal --</option>
                                    @foreach($personalAtiende as $key => $value_personalAtiende)
                                    <option value="{{$value_personalAtiende->id}}">{{$value_personalAtiende->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
							    <label for="lugarAtencionF_fuasEmitidosG">Lugar de Atención <span class="text-danger"> * </span></label>
                                <select class="form-control select-2 select2" name="lugarAtencionF_fuasEmitidosG" id="lugarAtencionF_fuasEmitidosG">
                                    <option value="">-- Seleccionar el Lugar de Atención --</option>
                                    @foreach($lugarAtencion as $key => $value_lugarAtencion)
                                    <option value="{{$value_lugarAtencion->id}}">{{$value_lugarAtencion->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
							    <label for="tipoAtencionF_fuasEmitidosG">Tipo de Atención</label>
                                <select class="form-control select-2 select2" name="tipoAtencionF_fuasEmitidosG" id="tipoAtencionF_fuasEmitidosG">
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
							    <label for="historiaClinica_fuasEmitidosG">Referencia realizada por <span class="text-danger"> * </span></label>
                            </div>

                            <div class="col-sm-4">
							    <label for="historiaClinica_fuasEmitidosG">N° Hoja de Referencia <span class="text-danger"> * </span></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="codigoReferenciaF_fuasEmitidosG" id="codigoReferenciaF_fuasEmitidosG"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="descripcionReferenciaF_fuasEmitidosG" id="descripcionReferenciaF_fuasEmitidosG"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="numeroReferenciaF_fuasEmitidosG" id="numeroReferenciaF_fuasEmitidosG"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="tipoDocumentoF_fuasEmitidosG">Identidad del Asegurado <span class="text-danger"> * </span></label>
                            </div>

                            <div class="col-sm-4">
                                <label for="componenteF_fuasEmitidosG">Componente <span class="text-danger"> * </span></label>
                            </div>

                            <div class="col-sm-4">
                                <label for="codigoAsegurado1F_fuasEmitidosG">Código del Asegurado <span class="text-danger"> * </span></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="tipoDocumentoF_fuasEmitidosG" id="tipoDocumentoF_fuasEmitidosG"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="numeroDocumentoF_fuasEmitidosG" id="numeroDocumentoF_fuasEmitidosG"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-4">
                                <select class="form-control select-2 select2" name="componenteF_fuasEmitidosG" id="componenteF_fuasEmitidosG">
                                    <option value="">-- Seleccionar el Componente --</option>
                                    @foreach($componente as $key => $value_componente)
                                    <option value="{{$value_componente->id}}">{{$value_componente->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-1">
                                <input type="text" class="form-control" name="codigoAsegurado1F_fuasEmitidosG" id="codigoAsegurado1F_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="true">
                            </div>

                            <div class="col-sm-1">
                                <input type="text" class="form-control" name="codigoAsegurado2F_fuasEmitidosG" id="codigoAsegurado2F_fuasEmitidosG"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="codigoAsegurado3F_fuasEmitidosG" id="codigoAsegurado3F_fuasEmitidosG"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="apellidoPaternoF_fuasEmitidosG">Apellido Paterno <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="apellidoPaternoF_fuasEmitidosG" id="apellidoPaternoF_fuasEmitidosG"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-6">
                                <label for="apellidoMaternoF_fuasEmitidosG">Apellido Materno <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="apellidoMaternoF_fuasEmitidosG" id="apellidoMaternoF_fuasEmitidosG"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="primerNombreF_fuasEmitidosG">Primer Nombre <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="primerNombreF_fuasEmitidosG" id="primerNombreF_fuasEmitidosG"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-6">
                                <label for="otroNombreF_fuasEmitidosG">Otros Nombres</label>
                                <input type="text" class="form-control" name="otroNombreF_fuasEmitidosG" id="otroNombreF_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="true">
                            </div>

                            <!-- PARA SELECCIONAR EL ID DEL PACIENTE -->
                            <input type="text" class="form-control" name="pacienteIdF_fuasEmitidosG" id="pacienteIdF_fuasEmitidosG"
                            style="text-transform: uppercase;display:none;" readonly="true">
                            <!-- FIN PARA SELECCIONAR EL ID DEL PACIENTE -->

                            <!-- PARA SELECCIONAR EL ID DEL FUA -->
                            <input type="text" class="form-control" name="atencionIdF_fuasEmitidosG" id="atencionIdF_fuasEmitidosG"
                            style="text-transform: uppercase;display:none;" readonly="true">
                            <!-- FIN PARA SELECCIONAR EL ID DEL FUA -->
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="sexoF_fuasEmitidosG">Sexo <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="sexoF_fuasEmitidosG" id="sexoF_fuasEmitidosG"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-4">
                                <label for="fechaNacimientoF_fuasEmitidosG">Fecha de Nacimiento <span class="text-danger"> * </span></label>
                                <input type="date" class="form-control" name="fechaNacimientoF_fuasEmitidosG" id="fechaNacimientoF_fuasEmitidosG"
                                style="text-transform: uppercase;" required readonly="true">
                            </div>

                            <div class="col-sm-4">
                                <label for="historiaF_fuasEmitidosG">Historia <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="historiaF_fuasEmitidosG" id="historiaF_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="fechaF_fuasEmitidosG">Fecha de Atención<!--  <span class="text-danger"> * </span> --></label>
                                <input type="date" class="form-control" name="fechaF_fuasEmitidosG" id="fechaF_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="true">
                            </div>

                            <div class="col-sm-2">
                                <label for="horaF_fuasEmitidosG">Hora de Atención<!--  <span class="text-danger"> * </span> --></label>
                                <input type="time" class="form-control" name="horaF_fuasEmitidosG" id="horaF_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="true">
                            </div>

                            <div class="col-sm-7">
                                <label for="codigoPrestacionalF_fuasEmitidosG">Código Prestacional <span class="text-danger"> * </span></label>
                                <select class="form-control select2" name="codigoPrestacionalF_fuasEmitidosG" id="codigoPrestacionalF_fuasEmitidosG" data-placeholder="Seleccionar código prestacional">
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
                                <label for="conceptoPrestacionalF_fuasEmitidosG">Concepto Prestacional <span class="text-danger"> * </span></label>
                                <select class="form-control select2" name="conceptoPrestacionalF_fuasEmitidosG" id="conceptoPrestacionalF_fuasEmitidosG">
                                    <option value="">-- Seleccionar concepto prestacional --</option>
                                    @foreach($concPrestacional as $key => $value_concPrestacional)
                                    <option value="{{$value_concPrestacional->id}}">{{$value_concPrestacional->id}} - {{$value_concPrestacional->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label for="destinoAseguradoF_fuasEmitidosG">Destino del Asegurado <span class="text-danger"> * </span></label>
                                <select class="form-control select-2 select2" name="destinoAseguradoF_fuasEmitidosG" id="destinoAseguradoF_fuasEmitidosG">
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
                                <label for="fechaIngresoF_fuasEmitidosG">Fecha de Ingreso <span id="span_fechaIngresoF_fuasEmitidosG" class="text-danger"> * </span></label>
                                <input type="date" class="form-control" name="fechaIngresoF_fuasEmitidosG" id="fechaIngresoF_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="readonly">
                            </div>

                            <div class="col-sm-6">
                                <label for="fechaAltaF_fuasEmitidosG">Fecha de Alta</label>
                                <input type="date" class="form-control" name="fechaAltaF_fuasEmitidosG" id="fechaAltaF_fuasEmitidosG"
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

                    <div class="form-group" id="rowDiagnosticosGeneral">
                        <div class="row">
                            <div class="col-sm-3">
							    <label for="diagnosticoF_fuasEmitidosG">Tipo</label>
                            </div>

                            <div class="col-sm-2">
							    <label for="codigoCieNF_fuasEmitidosG">CIE - 10</label>
                            </div>

                            <div class="col-sm-7">
							    <label for="codigoCieF_fuasEmitidosG">Descripción</label>
                            </div>
                        </div>

                        <div class="row" id="rowDiagnosticosDiferente">
                            <div class="col-sm-3">
                                <select class="form-control select-2 select2" name="diagnosticoF_fuasEmitidosG" id="diagnosticoF_fuasEmitidosG">
                                    <option value="">-- Seleccionar el tipo --</option>
                                    <option value="P">PRESUNTIVO</option>
                                    <option value="D">DEFINITIVO</option>
                                    <option value="R">REPETIDO</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="codigoCieNF_fuasEmitidosG" id="codigoCieNF_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="readonly">
                            </div>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="codigoCieF_fuasEmitidosG" id="codigoCieF_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="readonly">
                            </div>
                        </div>

                        <div class="row" id="rowDatosDiagnosticos056" style="margin-bottom: 0.3rem;">
                            <div class="col-sm-3">
                                <select class="form-control select-2 select2" name="diagnosticoF_0_fuasEmitidosG" id="diagnosticoF_0_fuasEmitidosG">
                                    <option value="">-- Seleccionar el tipo --</option>
                                    <option value="P">PRESUNTIVO</option>
                                    <option value="D">DEFINITIVO</option>
                                    <option value="R">REPETIDO</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="codigoCieNF_0_fuasEmitidosG" id="codigoCieNF_0_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="readonly">
                            </div>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="codigoCieF_0_fuasEmitidosG" id="codigoCieF_0_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="readonly">
                            </div>
                        </div>

                        <div class="row" id="rowDatosDiagnosticos056_1" style="margin-bottom: 0.3rem;">
                            <div class="col-sm-3">
                                <select class="form-control select-2 select2" name="diagnosticoF_1_fuasEmitidosG" id="diagnosticoF_1_fuasEmitidosG">
                                    <option value="">-- Seleccionar el tipo --</option>
                                    <option value="P">PRESUNTIVO</option>
                                    <option value="D">DEFINITIVO</option>
                                    <option value="R">REPETIDO</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="codigoCieNF_1_fuasEmitidosG" id="codigoCieNF_1_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="readonly">
                            </div>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="codigoCieF_1_fuasEmitidosG" id="codigoCieF_1_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="readonly">
                            </div>
                        </div>

                        <div class="row" id="rowDatosDiagnosticos056_2" style="margin-bottom: 0.3rem;">
                            <div class="col-sm-3">
                                <select class="form-control select-2 select2" name="diagnosticoF_2_fuasEmitidosG" id="diagnosticoF_2_fuasEmitidosG">
                                    <option value="">-- Seleccionar el tipo --</option>
                                    <option value="P">PRESUNTIVO</option>
                                    <option value="D">DEFINITIVO</option>
                                    <option value="R">REPETIDO</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="codigoCieNF_2_fuasEmitidosG" id="codigoCieNF_2_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="readonly">
                            </div>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="codigoCieF_2_fuasEmitidosG" id="codigoCieF_2_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="readonly">
                            </div>
                        </div>

                        <div class="row" id="rowDatosDiagnosticos056_3" style="margin-bottom: 0.3rem;">
                            <div class="col-sm-3">
                                <select class="form-control select-2 select2" name="diagnosticoF_3_fuasEmitidosG" id="diagnosticoF_3_fuasEmitidosG">
                                    <option value="">-- Seleccionar el tipo --</option>
                                    <option value="P">PRESUNTIVO</option>
                                    <option value="D">DEFINITIVO</option>
                                    <option value="R">REPETIDO</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="codigoCieNF_3_fuasEmitidosG" id="codigoCieNF_3_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="readonly">
                            </div>

                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="codigoCieF_3_fuasEmitidosG" id="codigoCieF_3_fuasEmitidosG"
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
                                <label for="tipoDocumentoP_fuasEmitidosG">Identidad del Profesional</label>
                            </div>

                            <div class="col-sm-8">
                                <label for="nombresApellidosP_fuasEmitidosG">Nombres y Apellidos <span class="text-danger"> * </span></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="tipoDocumentoP_fuasEmitidosG" id="tipoDocumentoP_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="true">
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="numeroDocumentoP_fuasEmitidosG" id="numeroDocumentoP_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="true">
                            </div>

                            <div class="col-sm-8">
                                <select class="form-control select2" name="nombresApellidosP_fuasEmitidosG" id="nombresApellidosP_fuasEmitidosG" data-placeholder="Seleccionar el profesional">
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
                                <label for="tipoPersonalSaludF_fuasEmitidosG">Tipo de Personal de Salud</label>
                            </div>

                            <div class="col-sm-4">
                                <label for="colegiaturaF_fuasEmitidosG">Colegiatura</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <select class="form-control select-2 select2" name="tipoPersonalSaludF_fuasEmitidosG" id="tipoPersonalSaludF_fuasEmitidosG">
                                    <option value="">-- Seleccionar el Tipo de Personal de Salud --</option>
                                    @foreach($sisTipoPersonalSalud as $key => $value_sisTipoPersonalSalud)
                                    <option value="{{$value_sisTipoPersonalSalud->id}}">{{$value_sisTipoPersonalSalud->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <select class="form-control select-2 select2" name="egresadoF_fuasEmitidosG" id="egresadoF_fuasEmitidosG">
                                    <option value="">-- Seleccionar si es Egresado --</option>
                                    @foreach($sisEgresado as $key => $value_sisEgresado)
                                    <option value="{{$value_sisEgresado->id}}">{{$value_sisEgresado->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="colegiaturaF_fuasEmitidosG" id="colegiaturaF_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-8">
                                <label for="especialidadF_fuasEmitidosG">Especialidad</label>
                            </div>

                            <div class="col-sm-4">
                                <label for="rneF_fuasEmitidosG">RNE</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-8">
                                <select class="form-control select-2 select2" name="especialidadF_fuasEmitidosG" id="especialidadF_fuasEmitidosG">
                                    <option value="">-- Seleccionar la Especialidad --</option>
                                    @foreach($sisEspecialidad as $key => $value_sisEspecialidad)
                                    <option value="{{$value_sisEspecialidad->id}}">{{$value_sisEspecialidad->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="rneF_fuasEmitidosG" id="rneF_fuasEmitidosG"
                                style="text-transform: uppercase;" readonly="true">
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="display:none;">
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="text" name="idRegistroF_fuasEmitidosG" id="idRegistroF_fuasEmitidosG">
                                <input type="text" name="modeloF_fuasEmitidosG" id="modeloF_fuasEmitidosG">
                            </div>
                        </div>
                    </div>

                    <div class="form-group sisInactivo_fuasEmitidosG" style="display:none">
                      <div class="row">
                        <div class="col-sm-12">
                          <label style="text-align:center;background:black;color:white;padding:1px;font-size:16px;width:100%;margin:0px;">Generar Fua - SIS Inactivo</label>
                        </div>
                      </div>
                    </div>

                    <div class="form-group sisInactivo_fuasEmitidosG" style="display:none">
                      <div class="row">
                        <div class="col-sm-6">
                          <label for="codigoOficinaF_fuasEmitidosG">Código de Oficina (4 dígitos)<span class="text-danger"> * </span></label>
                          <input type="text" class="form-control inputRutaNumero" name="codigoOficinaF_fuasEmitidosG" id="codigoOficinaF_fuasEmitidosG"
                          style="text-transform: uppercase;" maxlength="4" readonly="true">
                        </div>

                        <div class="col-sm-6">
                          <label for="codigoOperacionF_fuasEmitidosG">Código de Operación (7 dígitos) <span class="text-danger"> * </span></label>
                          <input type="text" class="form-control inputRutaNumero" name="codigoOperacionF_fuasEmitidosG" id="codigoOperacionF_fuasEmitidosG"
                          style="text-transform: uppercase;" maxlength="7" readonly="true">
                        </div>
                      </div>
                    </div>
                </div>

                <div class="modal-footer d-flex">
                    <div id="cerrar_actualizarFuaF_fuasEmitidosG">
                        <button type="button" class="btn btn-default boton-general" data-dismiss="modal" id="salir_actualizarFuaF_fuasEmitidosG">
                            <i class="fa fa-undo" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Salir
                        </button>
                    </div>

                    <div id="imprimir_actualizarFuaF_fuasEmitidosG">
                        <button type="button" class="btn btn-dark boton-general" id="imprimir_verFuaF_fuasEmitidosG">
                            <i class="fas fa-print" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Imprimir
                        </button>
                    </div>

                    <div id="actualizar_actualizarFuaF_fuasEmitidosG">
                        <button type="button" class="btn btn-info boton-general" id="actualizar_verFuaF_fuasEmitidosG">
                            <i class="fa fa-pencil" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Actualizar
                        </button>
                    </div>

                    <div id="anular_anularFuaF_fuasEmitidosG">
                        <a href="" class="btn btn-danger boton-general" id="anular_verFuaF_fuasEmitidosG">
                            <i class="fa fa-times" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Anular
                        </a>
                    </div>

                    <div id="registrar_actualizarFuaF_fuasEmitidosG" style="display:none;">
                        <button type="submit" class="btn btn-success boton-general" id="registrar_actualizarFuaF1_fuasEmitidosG">
                            <i class="fas fa-save" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Guardar datos
                        </button>
                    </div>

                    <div id="cancelar_actualizarFuaF_fuasEmitidosG" style="display:none;">
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

   $("#fechaInicio_fuasEmitidosG").val(currentDate);
   $("#fechaFin_fuasEmitidosG").val(currentDate);
</script> -->

<script type="text/javascript">

$("#historiaBD_fuasEmitidosG").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        var numHistoriaBD = $("#historiaBD_fuasEmitidosG").val();
        
        if (numHistoriaBD != ''){

            tablaFuasEmitidosG.clear().destroy();

            $("#btnVerFUA_fuasEmitidosG").css("display","none");
            $("#documentoBD_fuasEmitidosG").val("");
            $("#fuaBD_fuasEmitidosG").val("");

            $.ajax({
                url: '{{ route('consultar.historiaBD_fuasEmitidosG') }}',
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
        tablaFuasEmitidosG = $("#tablaFuasEmitidosG").DataTable({
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
	        "dom": 'Bfrtip',
	        "buttons": [{
			    "extend": 'excel',
			    "footer": false,
			    "title": 'FUAS EMITIDOS GENERAL',
			    "filename": 'FUAS_EMITIDOS_GENERAL',
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
                "url": '{{ route('consultar.historiaBD_fuasEmitidosG') }}',
                "data": {'numHistoriaBD' : $("#historiaBD_fuasEmitidosG").val()}
            },
            "columnDefs":[
		        {
			        className:"position",targets: [8],
			        "render": function ( data, type, row ) {
				        if(data == 1){
					        return '<i class="fas fa-check" style="color:green;text-align:center;"> GENERADO</i>'
				        }else if(data == 0){
					        return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"> ANULADO</i>'
				        }else{
                            return ''
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
                {"data": 'generarFua_estado',"name": 'generarFua_estado'},
                {"data": 'name',"name": 'name'},
                {"data": 'DiasTranscurridos',"name": 'DiasTranscurridos'}
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
  	        },	initComplete: function () {
		paciente_fuasEmitidosG(tablaFuasEmitidosG);
		profesionales_fuasEmitidosG(tablaFuasEmitidosG);
		fua_fuasEmitidosG(tablaFuasEmitidosG);
        codigoPrestacional_fuasEmitidosG(tablaFuasEmitidosG);
	}
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasEmitidosG.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */

        }else{
            $('#historiaBD_fuasEmitidosG').focus();
            tablaFuasEmitidosG.clear().destroy();
            $("#btnVerFUA_fuasEmitidosG").css("display","none");

            let valorUrlAjaxHistoria_fuasEmitidosG = '';

            if($('#fechaInicio_fuasEmitidosG').val() != '' || $('#fechaFin_fuasEmitidosG').val() != ''){
                valorUrlAjaxHistoria_fuasEmitidosG = '{{ route('consultar.fechasFEmitidosG') }}';
            }else{
                valorUrlAjaxHistoria_fuasEmitidosG = ruta + '/fuasEmitidosG';
            }

            /*=============================================
            DataTable de Auditoria Emitidos
            =============================================*/
            tablaFuasEmitidosG = $("#tablaFuasEmitidosG").DataTable({
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
	            "dom": 'Bfrtip',
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
                "ajax": {
                    "url": valorUrlAjaxHistoria_fuasEmitidosG,
                    "data":{'_token' : $('input[name=_token]').val(),
                            'fechaInicio_fuasEmitidosG' : $('#fechaInicio_fuasEmitidosG').val(),
                            'fechaFin_fuasEmitidosG': $('#fechaFin_fuasEmitidosG').val()}
                },
                "columnDefs":[
		            {
			            className:"position",targets: [8],
			            "render": function ( data, type, row ) {
				            if(data == 1){
					            return '<i class="fas fa-check" style="color:green;text-align:center;"> GENERADO</i>'
				            }else if(data == 0){
					            return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"> ANULADO</i>'
				            }else{
                                return ''
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
                    {"data": 'generarFua_estado',"name": 'generarFua_estado'},
                    {"data": 'name',"name": 'name'},
                    {"data": 'DiasTranscurridos',"name": 'DiasTranscurridos'}
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
  	            },	initComplete: function () {
		paciente_fuasEmitidosG(tablaFuasEmitidosG);
		profesionales_fuasEmitidosG(tablaFuasEmitidosG);
		fua_fuasEmitidosG(tablaFuasEmitidosG);
        codigoPrestacional_fuasEmitidosG(tablaFuasEmitidosG);
	}
            });

            /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasEmitidosG.search(this.value).draw();
	            }
            });
            /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});

$("#documentoBD_fuasEmitidosG").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        var numDocumentoBD = $("#documentoBD_fuasEmitidosG").val();
        
        if (numDocumentoBD != ''){

            tablaFuasEmitidosG.clear().destroy();

            $("#btnVerFUA_fuasEmitidosG").css("display","none");
            $("#historiaBD_fuasEmitidosG").val("");
            $("#fuaBD_fuasEmitidosG").val("");

            $.ajax({
                url: '{{ route('consultar.documentoBD_fuasEmitidosG') }}',
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
        tablaFuasEmitidosG = $("#tablaFuasEmitidosG").DataTable({
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
	        "dom": 'Bfrtip',
	        "buttons": [{
			    "extend": 'excel',
			    "footer": false,
			    "title": 'FUAS EMITIDOS GENERAL',
			    "filename": 'FUAS_EMITIDOS_GENERAL',
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
                "url": '{{ route('consultar.documentoBD_fuasEmitidosG') }}',
                "data": {'numDocumentoBD' : $("#documentoBD_auditoriaEmitidos").val()}
            },
            "columnDefs":[
		        {
			        className:"position",targets: [8],
			        "render": function ( data, type, row ) {
				        if(data == 1){
					        return '<i class="fas fa-check" style="color:green;text-align:center;"> GENERADO</i>'
				        }else if(data == 0){
					        return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"> ANULADO</i>'
				        }else{
                            return ''
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
                {"data": 'generarFua_estado',"name": 'generarFua_estado'},
                {"data": 'name',"name": 'name'},
                {"data": 'DiasTranscurridos',"name": 'DiasTranscurridos'}
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
              initComplete: function () {
		paciente_fuasEmitidosG(tablaFuasEmitidosG);
		profesionales_fuasEmitidosG(tablaFuasEmitidosG);
		fua_fuasEmitidosG(tablaFuasEmitidosG);
        codigoPrestacional_fuasEmitidosG(tablaFuasEmitidosG);
	}
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
        $('div.dataTables_filter input').unbind();
        $("div.dataTables_filter input").keyup( function (e) {
	        if (e.keyCode == 13) {
		        tablaFuasEmitidosG.search(this.value).draw();
	        }
        });
        /* FIN DE BUSQUEDA POR ENTER */

        }else{
            $('#documentoBD_fuasEmitidosG').focus();

            tablaFuasEmitidosG.clear().destroy();
            $("#btnVerFUA_fuasEmitidosG").css("display","none");

            let valorUrlAjaxDocumento_fuasEmitidosG = '';

            if($('#fechaInicio_fuasEmitidosG').val() != '' || $('#fechaFin_fuasEmitidosG').val() != ''){
                valorUrlAjaxDocumento_fuasEmitidosG = '{{ route('consultar.fechasFEmitidosG') }}';
            }else{
                valorUrlAjaxDocumento_fuasEmitidosG = ruta + '/fuasEmitidosG';
            }

            /*=============================================
            DataTable de Auditoria Emitidos
            =============================================*/
            tablaFuasEmitidosG = $("#tablaFuasEmitidosG").DataTable({
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
	            "dom": 'Bfrtip',
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
                "ajax": {
                    "url": valorUrlAjaxDocumento_fuasEmitidosG,
                    "data": { '_token' : $('input[name=_token]').val(),
                            'fechaInicio_fuasEmitidosG' : $('#fechaInicio_fuasEmitidosG').val(),
                            'fechaFin_fuasEmitidosG': $('#fechaFin_fuasEmitidosG').val()}
                },
                "columnDefs":[
		            {
			            className:"position",targets: [8],
			            "render": function ( data, type, row ) {
				            if(data == 1){
					            return '<i class="fas fa-check" style="color:green;text-align:center;"> GENERADO</i>'
				            }else if(data == 0){
					            return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"> ANULADO</i>'
				            }else{
                                return ''
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
                    {"data": 'generarFua_estado',"name": 'generarFua_estado'},
                    {"data": 'name',"name": 'name'},
                    {"data": 'DiasTranscurridos',"name": 'DiasTranscurridos'}
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
                  initComplete: function () {
		paciente_fuasEmitidosG(tablaFuasEmitidosG);
		profesionales_fuasEmitidosG(tablaFuasEmitidosG);
		fua_fuasEmitidosG(tablaFuasEmitidosG);
        codigoPrestacional_fuasEmitidosG(tablaFuasEmitidosG);
	}
            });

            /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasEmitidosG.search(this.value).draw();
	            }
            });
            /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});

$("#fuaBD_fuasEmitidosG").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        var numFuaBD = $("#fuaBD_fuasEmitidosG").val();
        
        if (numFuaBD != ''){

            tablaFuasEmitidosG.clear().destroy();

            $("#btnVerFUA_fuasEmitidosG").css("display","none");
            $("#historiaBD_fuasEmitidosG").val("");
            $("#documentoBD_fuasEmitidosG").val("");

            $.ajax({
                url: '{{ route('consultar.fuaBD_fuasEmitidosG') }}',
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
        tablaFuasEmitidosG = $("#tablaFuasEmitidosG").DataTable({
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
	        "dom": 'Bfrtip',
	        "buttons": [{
			    "extend": 'excel',
			    "footer": false,
			    "title": 'FUAS EMITIDOS GENERAL',
			    "filename": 'FUAS_EMITIDOS_GENERAL',
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
                "url": '{{ route('consultar.fuaBD_fuasEmitidosG') }}',
                "data": {'numFuaBD' : $("#fuaBD_fuasEmitidosG").val()}
            },
            "columnDefs":[
		        {
			        className:"position",targets: [8],
			        "render": function ( data, type, row ) {
				        if(data == 1){
					        return '<i class="fas fa-check" style="color:green;text-align:center;"> GENERADO</i>'
				        }else if(data == 0){
					        return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"> ANULADO</i>'
				        }else{
                            return ''
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
                {"data": 'generarFua_estado',"name": 'generarFua_estado'},
                {"data": 'name',"name": 'name'},
                {"data": 'DiasTranscurridos',"name": 'DiasTranscurridos'}
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
              initComplete: function () {
		paciente_fuasEmitidosG(tablaFuasEmitidosG);
		profesionales_fuasEmitidosG(tablaFuasEmitidosG);
		fua_fuasEmitidosG(tablaFuasEmitidosG);
        codigoPrestacional_fuasEmitidosG(tablaFuasEmitidosG);
	}
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasEmitidosG.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */

        }else{
            $('#fuaBD_fuasEmitidosG').focus();
            tablaFuasEmitidosG.clear().destroy();
            $("#btnVerFUA_fuasEmitidosG").css("display","none");

            let valorUrlAjaxFua_fuasEmitidosG = '';

            if($('#fechaInicio_fuasEmitidosG').val() != '' || $('#fechaFin_fuasEmitidosG').val() != ''){
                valorUrlAjaxFua_fuasEmitidosG = '{{ route('consultar.fechasFEmitidosG') }}';
            }else{
                valorUrlAjaxFua_fuasEmitidosG = ruta + '/fuasEmitidosG';
            }

            /*=============================================
            DataTable de Auditoria Emitidos
            =============================================*/
            tablaFuasEmitidosG = $("#tablaFuasEmitidosG").DataTable({
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
	            "dom": 'Bfrtip',
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
                    url: valorUrlAjaxFua_fuasEmitidosG,
                    data: { '_token' : $('input[name=_token]').val(),
                            'fechaInicio_fuasEmitidosG' : $('#fechaInicio_fuasEmitidosG').val(),
                            'fechaFin_fuasEmitidosG': $('#fechaFin_fuasEmitidosG').val()}
                },
                "columnDefs":[
		            {
			            className:"position",targets: [8],
			            "render": function ( data, type, row ) {
				            if(data == 1){
					            return '<i class="fas fa-check" style="color:green;text-align:center;"> GENERADO</i>'
				            }else if(data == 0){
					            return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"> ANULADO</i>'
				            }else{
                                return ''
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
                    {"data": 'generarFua_estado',"name": 'generarFua_estado'},
                    {"data": 'name',"name": 'name'},
                    {"data": 'DiasTranscurridos',"name": 'DiasTranscurridos'}
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
                  initComplete: function () {
		paciente_fuasEmitidosG(tablaFuasEmitidosG);
		profesionales_fuasEmitidosG(tablaFuasEmitidosG);
		fua_fuasEmitidosG(tablaFuasEmitidosG);
        codigoPrestacional_fuasEmitidosG(tablaFuasEmitidosG);
	}
            });

            /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasEmitidosG.search(this.value).draw();
	            }
            });
            /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});

$('#frmFechas_fuasEmitidosG').submit(function(e){
    e.preventDefault();

    tablaFuasEmitidosG.clear().destroy();

    $("#btnVerFUA_fuasEmitidosG").css("display","none");
    $("#btnRolCitas_fuasEmitidosG").css("display","none");
    $("#historiaBD_fuasEmitidosG").val("");
    $("#documentoBD_fuasEmitidosG").val("");
    $("#fuaBD_fuasEmitidosG").val("");

    /* Petición AJAX */
    $.ajax({
        url: '{{ route('consultar.fechasFEmitidosG') }}',
        data: $("#frmFechas_fuasEmitidosG").serialize(),
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
    tablaFuasEmitidosG = $("#tablaFuasEmitidosG").DataTable({
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
	    "dom": 'Bfrtip',
        "buttons": [
            {
		        "extend": 'excel',
		        "footer": false,
		        "title": 'FUAS EMITIDOS GENERAL',
		        "filename": 'FUAS_EMITIDOS_GENERAL',
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
            "url": '{{ route('consultar.fechasFEmitidosG') }}',
            "data": { '_token' : $('input[name=_token]').val(),
                    'fechaInicio_fuasEmitidosG' : $('#fechaInicio_fuasEmitidosG').val(),
                    'fechaFin_fuasEmitidosG': $('#fechaFin_fuasEmitidosG').val()}
        },
        "columnDefs":[
		    {
			    className:"position",targets: [8],
			    "render": function ( data, type, row ) {
				    if(data == 1){
					    return '<i class="fas fa-check" style="color:green;text-align:center;"> GENERADO</i>'
				    }else if(data == 0){
					    return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"> ANULADO</i>'
				    }else{
                        return ''
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
            {"data": 'generarFua_estado',"name": 'generarFua_estado'},
            {"data": 'name',"name": 'name'},
            {"data": 'DiasTranscurridos',"name": 'DiasTranscurridos'}
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
          initComplete: function () {
		paciente_fuasEmitidosG(tablaFuasEmitidosG);
		profesionales_fuasEmitidosG(tablaFuasEmitidosG);
		fua_fuasEmitidosG(tablaFuasEmitidosG);
        codigoPrestacional_fuasEmitidosG(tablaFuasEmitidosG);
	}
    });
});
</script>

<script type="text/javascript">

$(function(){

$('#tablaFuasEmitidosG tbody').on('click', 'tr', function (e) {
    e.preventDefault();

    if($(this).hasClass('selected')){
        $(this).removeClass('selected');
        $("#btnVerFUA_fuasEmitidosG").css("display","none");
        $("#btnRolCitas_fuasEmitidosG").css("display","none");
        $("#idFuaA_fuasEmitidosG").val("");
		$("#btnPasarAnulacionGeneracionFUA_fuasEmitidosG").css("display","none");
    }else{
        tablaFuasEmitidosG.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
        /* console.log(tablaFuasObservados.row($(this)).data()); */

        $("#idFuaA_fuasEmitidosG").val(tablaFuasEmitidosG.row($(this)).data()["Fua_id"]);

        if(tablaFuasEmitidosG.row($(this)).data()["generarFua_estado"] == 0){
            $("#btnPasarAnulacionGeneracionFUA_fuasEmitidosG").css("display","block");
		}else{
			$("#btnPasarAnulacionGeneracionFUA_fuasEmitidosG").css("display","none");
		}

        if(tablaFuasEmitidosG.row($(this)).data()["generarFua_estado"] == 0){
            $("#anular_anularFuaF_fuasEmitidosG").css("display","none");
        }else{
            $("#anular_anularFuaF_fuasEmitidosG").css("display","block");
        }

        //==========================================================================
                //EXTRAER INFORMACIÓN DEL FUA EXISTENTE
        //===========================================================================
        $("#idFua_fuasEmitidosG").val(tablaFuasEmitidosG.row($(this)).data()["Fua_id"]);
        $("#idFuaF_fuasEmitidosG").val(tablaFuasEmitidosG.row($(this)).data()["Fua_id"]);
        $("#anular_verFuaF_fuasEmitidosG").attr('href',$(location).attr('href')+"/"+tablaFuasEmitidosG.row($(this)).data()["idRegistro"]);
        $("#idRegistroF_fuasEmitidosG").val(tablaFuasEmitidosG.row($(this)).data()["idRegistro"]);
        $("#modeloF_fuasEmitidosG").val(tablaFuasEmitidosG.row($(this)).data()["Modelo"]);

        /* INICIO DE LA CONSULTA PARA VER EL ROL DE LAS CITAS */
        if(tablaFuasEmitidosG.row($(this)).data()["numeroSesion"] != null && tablaFuasEmitidosG.row($(this)).data()["idIdentificador"] )
        {
            $("#btnRolCitas_fuasEmitidosG").css("display","block");
            $("#frmVerRolCitas_fuasEmitidosG").attr('action',$(location).attr('href')+"/"+tablaFuasEmitidosG.row($(this)).data()["idIdentificador"]);
            $("#idCab_fuasEmitidosG").val(tablaFuasEmitidosG.row($(this)).data()["idIdentificador"]);
        }
        else
        {
            $("#btnRolCitas_fuasEmitidosG").css("display","none");
        }
        /* FIN DE LA CONSULTA PARA VER EL ROL DE LAS CITAS */

        $("#anular_verFuaF_fuasEmitidosG").unbind('click').on('click',function(e){
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
                            let usuarioExtraer = $("#usuario_fuasEmitidosG").val();
                            let idRegistro = $("#idRegistroF_fuasEmitidosG").val();
                            let idModelo = $("#modeloF_fuasEmitidosG").val();
                            let idFua = $("#idFua_fuasEmitidosG").val();
                
                            /* INICIO VALIDACION DE CONTRASEÑA */
                            $.ajax({
                                url: '{{ route('consultar.validarPasswordFuaEmitidosG') }}',
                                data: {password,usuarioExtraer,idRegistro,idModelo,idFua},
                                success: function(respuesta){
                                    /* console.log("respuesta",respuesta); */

                                if(respuesta == 'DIFERENTES'){
                                    swal("La contraseña es incorrecta");
                                }

                                if(respuesta == 'IGUALES'){

                                swal({
                                    type:"success",
                                    title: "¡El FUA ha sido anulado correctamente!",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar"

                                }).then(function(result){
                                    if(result.value){
                                    /* CERRAMOS EL MODAL ABIERTO Y NOS MUESTRA LA INFORMACIÓN DEL FUA EN OTRA PÁGINA */
                                    $('#verFua_fuasEmitidosG').modal('hide');
                                    tablaFuasEmitidosG.clear().destroy();
                                    $("#btnVerFUA_fuasEmitidosG").css("display","none");
                                    $("#btnRolCitas_fuasEmitidosG").css("display","none");

                                    let valorUrlAjax1_fuasEmitidosG = '';

                                    if($('#fechaInicio_fuasEmitidosG').val() != '' || $('#fechaFin_fuasEmitidosG').val() != ''){
                                        valorUrlAjax1_fuasEmitidosG = '{{ route('consultar.fechasFEmitidosG') }}';
                                    }else{
                                        valorUrlAjax1_fuasEmitidosG = ruta + '/fuasEmitidosG';
                                    }

                                    /*=============================================
                                    DataTable de Pacientes Citados
                                    =============================================*/
                                    tablaFuasEmitidosG = $("#tablaFuasEmitidosG").DataTable({
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
	                                    "dom": 'Bfrtip',
                                        "buttons": [{
		                                    "extend": 'excel',
		                                    "footer": false,
		                                    "title": 'FUAS EMITIDOS GENERAL',
		                                    "filename": 'FUAS_EMITIDOS_GENERAL',
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
                                            "url": valorUrlAjax1_fuasEmitidosG,
                                            "data": {'_token' : $('input[name=_token]').val(),
                                                    'fechaInicio_fuasEmitidosG' : $('#fechaInicio_fuasEmitidosG').val(),
                                                    'fechaFin_fuasEmitidosG': $('#fechaFin_fuasEmitidosG').val()}
                                        },
                                        "columnDefs":[
		                                    {
			                                    className:"position",targets: [8],
			                                    "render": function ( data, type, row ) {
				                                    if(data == 1){
					                                    return '<i class="fas fa-check" style="color:green;text-align:center;"> GENERADO</i>'
				                                    }else if(data == 0){
					                                    return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"> ANULADO</i>'
				                                    }else{
                                                        return ''
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
                                            {"data": 'generarFua_estado',"name": 'generarFua_estado'},
                                            {"data": 'name',"name": 'name'},
                                            {"data": 'DiasTranscurridos',"name": 'DiasTranscurridos'}
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
                                        initComplete: function () {
		paciente_fuasEmitidosG(tablaFuasEmitidosG);
		profesionales_fuasEmitidosG(tablaFuasEmitidosG);
		fua_fuasEmitidosG(tablaFuasEmitidosG);
        codigoPrestacional_fuasEmitidosG(tablaFuasEmitidosG);
	}
                                    });
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

    $('#btnVerFUA_fuasEmitidosG').unbind('click').on('click',function(e){
      e.preventDefault();

      $.ajax({
      url: '{{ route('consultar.verFuaEmitidosG') }}',
      method: "POST",
      data: $("#frmVerFua_fuasEmitidosG").serialize(),
      success: function(respuesta){
        /* console.log(respuesta); */
        var arregloFua = JSON.parse(respuesta);
        for(var x=0;x<arregloFua.length;x++){

        $("#personalAtiendeF_fuasEmitidosG").val(arregloFua[x].PersonaAtiende).trigger('change.select2');
        $('#personalAtiendeF_fuasEmitidosG').attr('readonly','readonly');
        $("#lugarAtencionF_fuasEmitidosG").val(arregloFua[x].LugarAtencion).trigger('change.select2');
        $('#lugarAtencionF_fuasEmitidosG').attr('readonly','readonly');
        $("#tipoAtencionF_fuasEmitidosG").val(arregloFua[x].TipoAtencion).trigger('change.select2');
        $('#tipoAtencionF_fuasEmitidosG').attr('readonly','readonly');
        $("#disaF_fuasEmitidosG").val(arregloFua[x].DISA);
        $("#loteF_fuasEmitidosG").val(arregloFua[x].Lote);
        $("#numeroF_fuasEmitidosG").val(arregloFua[x].Numero);
        $("#apellidoPaternoF_fuasEmitidosG").val(arregloFua[x].ApellidoPaterno);
        $("#apellidoMaternoF_fuasEmitidosG").val(arregloFua[x].ApellidoMaterno);
        $("#primerNombreF_fuasEmitidosG").val(arregloFua[x].PrimerNombre);
        $("#otroNombreF_fuasEmitidosG").val(arregloFua[x].OtrosNombres);

        if(arregloFua[x].codigoOficina != null){
            $("#codigoOficinaF_fuasEmitidosG").val(arregloFua[x].codigoOficina);
            $("#codigoOperacionF_fuasEmitidosG").val(arregloFua[x].codigoOperacion);

            $(".sisInactivo_fuasEmitidosG").css("display","block");
        }else{
            $("#codigoOficinaF_fuasEmitidosG").val("");
            $("#codigoOperacionF_fuasEmitidosG").val("");

            $(".sisInactivo_fuasEmitidosG").css("display","none");
        }
        
        /* INICIO DEL SEXO DEL PACIENTE */
        if(arregloFua[x].Sexo == 1){
          $("#sexoF_fuasEmitidosG").val("MASCULINO");
        }else if(arregloFua[x].Sexo == 0){
          $("#sexoF_fuasEmitidosG").val("FEMENINO");
        }else{
          $("#sexoF_fuasEmitidosG").val("");
        }
        /* FIN DEL SEXO DEL PACIENTE */

        $("#fechaNacimientoF_fuasEmitidosG").val(arregloFua[x].FechaNacimiento);
        $("#historiaF_fuasEmitidosG").val(arregloFua[x].HistoriaClinica);

        /* INICIO DEL TIPO DEL DOCUMENTO DEL PACIENTE */
        if(arregloFua[x].TipoDocumentoIdentidad == 1){
          $("#tipoDocumentoF_fuasEmitidosG").val("D.N.I.");
        }else if(arregloFua[x].TipoDocumentoIdentidad == 3){
          $("#tipoDocumentoF_fuasEmitidosG").val("C.E.");
        }else{
          $("#tipoDocumentoF_fuasEmitidosG").val("");
        }
        /* FIN DEL TIPO DEL DOCUMENTO DEL PACIENTE */

        $("#numeroDocumentoF_fuasEmitidosG").val(arregloFua[x].NroDocumentoIdentidad);
        $("#componenteF_fuasEmitidosG").val(arregloFua[x].Componente).trigger('change.select2');
        $('#componenteF_fuasEmitidosG').attr('readonly','readonly');
        $("#codigoAsegurado1F_fuasEmitidosG").val(arregloFua[x].DISAAsegurado);
        $("#codigoAsegurado2F_fuasEmitidosG").val(arregloFua[x].LoteAsegurado);
        $("#codigoAsegurado3F_fuasEmitidosG").val(arregloFua[x].NumeroAsegurado);
        $("#fechaF_fuasEmitidosG").val(arregloFua[x].FechaAtencion);
        $("#horaF_fuasEmitidosG").val(arregloFua[x].HoraAtencion);
        $("#codigoPrestacionalF_fuasEmitidosG").val(arregloFua[x].CodigoPrestacional).trigger('change.select2');
        $('#codigoPrestacionalF_fuasEmitidosG').attr('readonly','readonly');
        $("#conceptoPrestacionalF_fuasEmitidosG").val(arregloFua[x].ModalidadAtencion).trigger('change.select2');
        $('#conceptoPrestacionalF_fuasEmitidosG').attr('readonly','readonly');
        $("#destinoAseguradoF_fuasEmitidosG").val(arregloFua[x].DestinoAsegurado).trigger('change.select2');
        $('#destinoAseguradoF_fuasEmitidosG').attr('readonly','readonly');
        $("#diagnosticoF_fuasEmitidosG").val(arregloFua[x].pdr1_cod).trigger('change.select2');
        $('#diagnosticoF_fuasEmitidosG').attr('readonly','readonly');
        $("#codigoCieNF_fuasEmitidosG").val(arregloFua[x].cie1_cod);

        if(arregloFua[x].CodigoPrestacional == '065'){
          $(".hospitalizacion_oculto").css("display","block");
          $("#fechaIngresoF_fuasEmitidosG").val(arregloFua[x].FechaIngreso);
          $("#fechaAltaF_fuasEmitidosG").val(arregloFua[x].FechaAlta);
          $("#fechaIngresoF_fuasEmitidosG").attr('required',true);

          /* REALIZAMOS UN AJAX PARA ACTUALIZAR LA FECHA DE ATENCIÓN */
            if($("#fechaAltaF_fuasEmitidosG").val() != ''){
              let fechaAltaVerFua = $("#fechaAltaF_fuasEmitidosG").val();
              let idFuaVerFua = $("#idFua_fuasEmitidosG").val();

              /* ACTUALIZAR FECHA DE ATENCIÓN */
              $.ajax({
                url: '{{ route('consultar.fechaAltaVerFuaEmitidosG') }}',
                data: {fechaAltaVerFua,idFuaVerFua},
                success: function(respuesta){
                  /* console.log(respuesta); */
                  if(respuesta[0] == "FECHA_ATENCION_ACTUALIZADO"){
                    /* console.log("SE ACTUALIZÓ DE MANERA CORRECTA LA FECHA DE ATENCIÓN"); */
                    $("#fechaF_fuasEmitidosG").val(respuesta[1][0]["FechaHoraAtencion"]);
                    $("#horaF_fuasEmitidosG").val("00:00:00");
                  } 
                },

                error: function(jqXHR,textStatus,errorThrown){
                  console.error(textStatus + " " + errorThrown);
                }
              });
              /* FIN DE ACTUALIZAR FECHA DE ATENCIÓN */
            }else{

              let fechaAltaVerFua = $("#fechaAltaF_fuasEmitidosG").val();
              let idFuaVerFua = $("#idFua_fuasEmitidosG").val();

              $.ajax({
                url: '{{ route('consultar.fechaAltaVerFuaEmitidosG') }}',
                data: {fechaAltaVerFua,idFuaVerFua},
                success: function(respuesta){
                  /* console.log(respuesta); */
                  if(respuesta[0] == "FECHA_ATENCION_ACTUALIZADO"){
                    /* console.log("SE ACTUALIZÓ DE MANERA CORRECTA LA FECHA DE ATENCIÓN"); */
                    $("#fechaF_fuasEmitidosG").val(respuesta[1][0]["FechaHoraAtencion"]);
                    $("#horaF_fuasEmitidosG").val("");
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
          $("#fechaIngresoF_fuasEmitidosG").val("");
          $("#fechaAltaF_fuasEmitidosG").val("");
          $("#span_fechaIngresoF_fuasEmitidosG").css("display","none");
          $("#fechaIngresoF_fuasEmitidosG").removeAttr("required");
        }

        /* INICIO CODIGO PRESTACIONAL 056 DIAGNOSTICOS */

        if(arregloFua[x].CodigoPrestacional == '056'){
            $("#rowDiagnosticosDiferente").css("display","none");
            $("#rowDatosDiagnosticos056").css("display","");
            $("#rowDatosDiagnosticos056_1").css("display","");
            $("#rowDatosDiagnosticos056_2").css("display","");
            $("#rowDatosDiagnosticos056_3").css("display","");

            let idFuaVerFua = $("#idFua_fuasEmitidosG").val();

            $.ajax({
                url: '{{ route('consultar.diagnosticos056_fuasEmitidosG') }}',
                data: {idFuaVerFua},
                success: function(respuesta){
                    /* console.log(respuesta); */

                    var arregloDiagnosticos056 = JSON.parse(respuesta);
                    /* console.log(arregloDiagnosticos056); */

                    if(arregloDiagnosticos056 != ""){
                        $("#diagnosticoF_0_fuasEmitidosG").val(arregloDiagnosticos056[0].diag).trigger('change.select2');
                        $("#codigoCieNF_0_fuasEmitidosG").val(arregloDiagnosticos056[0].cie);
                        $("#codigoCieF_0_fuasEmitidosG").val(arregloDiagnosticos056[0].descrip);
                        $('#diagnosticoF_0_fuasEmitidosG').attr('readonly','readonly');

                        $("#diagnosticoF_1_fuasEmitidosG").val(arregloDiagnosticos056[1].diag).trigger('change.select2');
                        $("#codigoCieNF_1_fuasEmitidosG").val(arregloDiagnosticos056[1].cie);
                        $("#codigoCieF_1_fuasEmitidosG").val(arregloDiagnosticos056[1].descrip);
                        $('#diagnosticoF_1_fuasEmitidosG').attr('readonly','readonly');

                        $("#diagnosticoF_2_fuasEmitidosG").val(arregloDiagnosticos056[2].diag).trigger('change.select2');
                        $("#codigoCieNF_2_fuasEmitidosG").val(arregloDiagnosticos056[2].cie);
                        $("#codigoCieF_2_fuasEmitidosG").val(arregloDiagnosticos056[2].descrip);
                        $('#diagnosticoF_2_fuasEmitidosG').attr('readonly','readonly');

                        $("#diagnosticoF_3_fuasEmitidosG").val(arregloDiagnosticos056[3].diag).trigger('change.select2');
                        $("#codigoCieNF_3_fuasEmitidosG").val(arregloDiagnosticos056[3].cie);
                        $("#codigoCieF_3_fuasEmitidosG").val(arregloDiagnosticos056[3].descrip);
                        $('#diagnosticoF_3_fuasEmitidosG').attr('readonly','readonly');
                    }else{
                        $("#diagnosticoF_0_fuasEmitidosG").val("").trigger('change.select2');
                        $("#codigoCieNF_0_fuasEmitidosG").val("");
                        $("#codigoCieF_0_fuasEmitidosG").val("");

                        $("#diagnosticoF_1_fuasEmitidosG").val("").trigger('change.select2');
                        $("#codigoCieNF_1_fuasEmitidosG").val("");
                        $("#codigoCieF_1_fuasEmitidosG").val("");

                        $("#diagnosticoF_2_fuasEmitidosG").val("").trigger('change.select2');
                        $("#codigoCieNF_2_fuasEmitidosG").val("");
                        $("#codigoCieF_2_fuasEmitidosG").val("");

                        $("#diagnosticoF_3_fuasEmitidosG").val("").trigger('change.select2');
                        $("#codigoCieNF_3_fuasEmitidosG").val("");
                        $("#codigoCieF_3_fuasEmitidosG").val("");
                    }
                    /* } */
                },

                error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                }
            });
        }else{
            $("#rowDiagnosticosDiferente").css("display","");
            $("#rowDatosDiagnosticos056").css("display","none");
            $("#rowDatosDiagnosticos056_1").css("display","none");
            $("#rowDatosDiagnosticos056_2").css("display","none");
            $("#rowDatosDiagnosticos056_3").css("display","none");
        }

        $("#salir_actualizarFuaF_fuasEmitidosG").on('click',function(){
            $('.rowDatosDiagnosticos056').remove();
            $('.rowDatosDiagnosticos056_1').remove();
            $('.rowDatosDiagnosticos056_2').remove();
            $('.rowDatosDiagnosticos056_3').remove();
        });

        $("#botonCerrarVerFua_fuasEmitidosG").on('click',function(){
            $('.rowDatosDiagnosticos056').remove();
            $('.rowDatosDiagnosticos056_1').remove();
            $('.rowDatosDiagnosticos056_2').remove();
            $('.rowDatosDiagnosticos056_3').remove();
        });

        /* FIN CODIGO PRESTACIONAL 056 DIAGNOSTICOS */

        /* TIPO DE DOCUMENTO DEL RESPONSABLE */
        if(arregloFua[x].TipoDocResponsable == 1){
          $("#tipoDocumentoP_fuasEmitidosG").val("D.N.I.");
        }else if(arregloFua[x].TipoDocResponsable == 3){
          $("#tipoDocumentoP_fuasEmitidosG").val("C.E.");
        }else{
          $("#tipoDocumentoP_fuasEmitidosG").val("");
        }
        /* FIN DEL TIPO DE DOCUMENTO DEL RESPONSABLE */

        $("#numeroDocumentoP_fuasEmitidosG").val(arregloFua[x].NroDocResponsable);
        $("#nombresApellidosP_fuasEmitidosG").val(arregloFua[x].personalAtiende_id).trigger('change.select2');
        $('#nombresApellidosP_fuasEmitidosG').attr('readonly','readonly');
        $("#tipoPersonalSaludF_fuasEmitidosG").val(arregloFua[x].TipoPersonalSalud).trigger('change.select2');
        $('#tipoPersonalSaludF_fuasEmitidosG').attr('readonly','readonly');
        $("#egresadoF_fuasEmitidosG").val(arregloFua[x].EsEgresado).trigger('change.select2');
        $('#egresadoF_fuasEmitidosG').attr('readonly','readonly');
        $("#colegiaturaF_fuasEmitidosG").val(arregloFua[x].NroColegiatura);
        $("#especialidadF_fuasEmitidosG").val(arregloFua[x].Especialidad).trigger('change.select2');
        $('#especialidadF_fuasEmitidosG').attr('readonly','readonly');
        $("#rneF_fuasEmitidosG").val(arregloFua[x].NroRNE);
        $("#codigoReferenciaF_fuasEmitidosG").val(arregloFua[x].IPRESSRefirio);
        $("#pacienteIdF_fuasEmitidosG").val(arregloFua[x].persona_id);
        $("#numeroReferenciaF_fuasEmitidosG").val(arregloFua[x].NroHojaReferencia);
        $("#atencionIdF_fuasEmitidosG").val(arregloFua[x].IdAtencion);
        }

        var CodigoCie = $("#codigoCieNF_fuasEmitidosG").val();

        if(CodigoCie != ''){
        /* INICIO CODIGO CIE BUSQUEDA CONSULTAS */
        $.ajax({
          url: '{{ route('consultar.codigoCieEmitidosG') }}',
          data: {CodigoCie},
          success: function(respuesta){
            /* console.log("respuesta",respuesta); */

            if (CodigoCie == null){   
            $("#codigoCieNF_fuasEmitidosG").val('');
            $('#codigoCieNF_fuasEmitidosG').attr('readonly','readonly');
            $('#codigoCieF_fuasEmitidosG').val('');
            $('#codigoCieF_fuasEmitidosG').attr('readonly','readonly');
            }else{
            var arregloCodigoCie = JSON.parse(respuesta);
            for(var x=0;x<arregloCodigoCie.length;x++){
              $("#codigoCieNF_fuasEmitidosG").val(arregloCodigoCie[x].cie_cod);
              $('#codigoCieNF_fuasEmitidosG').attr('readonly','readonly');
              $("#codigoCieF_fuasEmitidosG").val(arregloCodigoCie[x].cie_desc);
              $('#codigoCieF_fuasEmitidosG').attr('readonly','readonly');
            }
            }
          },

          error: function(jqXHR,textStatus,errorThrown){
            console.error(textStatus + " " + errorThrown);
          }
          });
        /* FIN DE AJAX PARA EXTRAER VALORES DE CODIGO CIE */
        }else{
        $("#codigoCieF_fuasEmitidosG").val("");
        }

        if($("#pacienteIdF_fuasEmitidosG").val() != '' && $("#codigoReferenciaF_fuasEmitidosG").val() != ''){
        var idPaciente = $("#pacienteIdF_fuasEmitidosG").val();
        /* console.log(idPaciente); */

        /* INICIO REFERENCIAS CONSULTAS */
          $.ajax({
          url: '{{ route('consultar.referenciasEmitidosG') }}',
          data: {idPaciente},
          success: function(respuesta){
            /* console.log("respuesta",respuesta); */

            var arregloReferencia = JSON.parse(respuesta);
            for(var x=0;x<arregloReferencia.length;x++){
            $("#descripcionReferenciaF_fuasEmitidosG").val(arregloReferencia[x].descripcion);
            }
          },

          error: function(jqXHR,textStatus,errorThrown){
            console.error(textStatus + " " + errorThrown);
          }
          });
        /* FIN DE AJAX PARA EXTRAER VALORES DE REFERENCIA */
        }else{
        $("#descripcionReferenciaF_fuasEmitidosG").val("");
        }
      },

      error: function(jqXHR,textStatus,errorThrown){
        console.error(textStatus + " " + errorThrown);
      }
      });
    });
    //========================================================================FIN

    $("#btnVerFUA_fuasEmitidosG").css("display","block");
    $("#frmVerFua_fuasEmitidosG").attr('action',$(location).attr('href')+"/"+tablaFuasEmitidosG.row($(this)).data()["Fua_id"]);

  }
  });
});
</script>

<script type="text/javascript">
                  $("#codigoCieNF_fuasEmitidosG").keypress(function(e) {
                      if(e.which == 13) {
                        e.preventDefault();

                        var CodigoCie = $("#codigoCieNF_fuasEmitidosG").val();

                        if (CodigoCie != '') {
                          
                          $.ajax({
                            url: '{{ route('consultar.codigoCieEmitidosG') }}',
                            data: {CodigoCie},
                            success: function(respuesta){
                              console.log(respuesta);
                              var arreglo = JSON.parse(respuesta);

                              if(arreglo == ""){
                                alert("El Código CIE-10 no existe");
                                $("#codigoCieNF_fuasEmitidosG").val("");
                                $("#codigoCieF_fuasEmitidosG").val("");
                              }else{
                                for(var x=0;x<arreglo.length;x++){
                                    $("#codigoCieNF_fuasEmitidosG").val(arreglo[x].cie_cod);
                                    $("#codigoCieF_fuasEmitidosG").val(arreglo[x].cie_desc);
                                }
                              }
                            },

                            error: function(jqXHR,textStatus,errorThrown){
                                console.error(textStatus + " " + errorThrown);
                            }
                          })
                        
                        }else{
                          alert('Escriba el Código CIE-10.!');
                          $('#codigoCieNF_fuasEmitidosG').focus();
                          $("#codigoCieNF_fuasEmitidosG").val("");
                        }
                      }
                    });

                    $("#codigoCieNF_0_fuasEmitidosG").keypress(function(e) {
                      if(e.which == 13) {

                        e.preventDefault();

                        var CodigoCie = $("#codigoCieNF_0_fuasEmitidosG").val();

                        if (CodigoCie != '') {
                          
                          $.ajax({
                            url: '{{ route('consultar.codigoCieEmitidosG') }}',
                            data: {CodigoCie},
                            success: function(respuesta){
                              console.log(respuesta);
                              var arreglo = JSON.parse(respuesta);

                              if(arreglo == ""){
                                alert("El Código CIE-10 no existe");
                                $("#codigoCieNF_0_fuasEmitidosG").val("");
                                $("#codigoCieF_0_fuasEmitidosG").val("");
                              }else{
                                for(var x=0;x<arreglo.length;x++){
                                    $("#codigoCieNF_0_fuasEmitidosG").val(arreglo[x].cie_cod);
                                    $("#codigoCieF_0_fuasEmitidosG").val(arreglo[x].cie_desc);
                                }
                              }
                            },

                            error: function(jqXHR,textStatus,errorThrown){
                                console.error(textStatus + " " + errorThrown);
                            }
                          })
                        
                        }else{
                          alert('Escriba el Código CIE-10.!');
                          $('#codigoCieNF_0_fuasEmitidosG').focus();
                          $("#codigoCieF_0_fuasEmitidosG").val("");
                        }
                      }
                    });

                    $("#codigoCieNF_1_fuasEmitidosG").keypress(function(e) {
                      if(e.which == 13) {

                        e.preventDefault();

                        var CodigoCie = $("#codigoCieNF_1_fuasEmitidosG").val();

                        if (CodigoCie != '') {
                          
                          $.ajax({
                            url: '{{ route('consultar.codigoCieEmitidosG') }}',
                            data: {CodigoCie},
                            success: function(respuesta){
                              console.log(respuesta);
                              var arreglo = JSON.parse(respuesta);

                              if(arreglo == ""){
                                alert("El Código CIE-10 no existe");
                                $("#codigoCieNF_1_fuasEmitidosG").val("");
                                $("#codigoCieF_1_fuasEmitidosG").val("");
                              }else{
                                for(var x=0;x<arreglo.length;x++){
                                    $("#codigoCieNF_1_fuasEmitidosG").val(arreglo[x].cie_cod);
                                    $("#codigoCieF_1_fuasEmitidosG").val(arreglo[x].cie_desc);
                                }
                              }
                            },

                            error: function(jqXHR,textStatus,errorThrown){
                                console.error(textStatus + " " + errorThrown);
                            }
                          })
                        
                        }else{
                          alert('Escriba el Código CIE-10.!');
                          $('#codigoCieNF_1_fuasEmitidosG').focus();
                          $("#codigoCieF_1_fuasEmitidosG").val("");
                        }
                      }
                    });

                    $("#codigoCieNF_2_fuasEmitidosG").keypress(function(e) {
                      if(e.which == 13) {

                        e.preventDefault();

                        var CodigoCie = $("#codigoCieNF_2_fuasEmitidosG").val();

                        if (CodigoCie != '') {
                          
                          $.ajax({
                            url: '{{ route('consultar.codigoCieEmitidosG') }}',
                            data: {CodigoCie},
                            success: function(respuesta){
                              console.log(respuesta);
                              var arreglo = JSON.parse(respuesta);

                              if(arreglo == ""){
                                alert("El Código CIE-10 no existe");
                                $("#codigoCieNF_2_fuasEmitidosG").val("");
                                $("#codigoCieF_2_fuasEmitidosG").val("");
                              }else{
                                for(var x=0;x<arreglo.length;x++){
                                    $("#codigoCieNF_2_fuasEmitidosG").val(arreglo[x].cie_cod);
                                    $("#codigoCieF_2_fuasEmitidosG").val(arreglo[x].cie_desc);
                                }
                              }
                            },

                            error: function(jqXHR,textStatus,errorThrown){
                                console.error(textStatus + " " + errorThrown);
                            }
                          })
                        
                        }else{
                          alert('Escriba el Código CIE-10.!');
                          $('#codigoCieNF_2_fuasEmitidosG').focus();
                          $("#codigoCieF_2_fuasEmitidosG").val("");
                        }
                      }
                    });

                    $("#codigoCieNF_3_fuasEmitidosG").keypress(function(e) {
                      if(e.which == 13) {

                        e.preventDefault();

                        var CodigoCie = $("#codigoCieNF_3_fuasEmitidosG").val();

                        if (CodigoCie != '') {
                          
                          $.ajax({
                            url: '{{ route('consultar.codigoCieEmitidosG') }}',
                            data: {CodigoCie},
                            success: function(respuesta){
                              console.log(respuesta);
                              var arreglo = JSON.parse(respuesta);

                              if(arreglo == ""){
                                alert("El Código CIE-10 no existe");
                                $("#codigoCieNF_3_fuasEmitidosG").val("");
                                $("#codigoCieF_3_fuasEmitidosG").val("");
                              }else{
                                for(var x=0;x<arreglo.length;x++){
                                    $("#codigoCieNF_3_fuasEmitidosG").val(arreglo[x].cie_cod);
                                    $("#codigoCieF_3_fuasEmitidosG").val(arreglo[x].cie_desc);
                                }
                              }
                            },

                            error: function(jqXHR,textStatus,errorThrown){
                                console.error(textStatus + " " + errorThrown);
                            }
                          })
                        
                        }else{
                          alert('Escriba el Código CIE-10.!');
                          $('#codigoCieNF_3_fuasEmitidosG').focus();
                          $("#codigoCieF_3_fuasEmitidosG").val("");
                        }
                      }
                    });

                    $("#actualizar_verFuaF_fuasEmitidosG").click(function(){

                      /* PONEMOS TODOS LOS VALORES QUE SE VAN A PODER EDITAR */
                      $("#cerrar_actualizarFuaF_fuasEmitidosG").css("display","none");
                      $("#imprimir_actualizarFuaF_fuasEmitidosG").css("display","none");
                      $("#actualizar_actualizarFuaF_fuasEmitidosG").css("display","none");
                      $("#anular_anularFuaF_fuasEmitidosG").css("display","none");
                      $("#registrar_actualizarFuaF_fuasEmitidosG").css("display","block");
                      $("#cancelar_actualizarFuaF_fuasEmitidosG").css("display","block");
                      $("#nombresApellidosP_fuasEmitidosG").removeAttr("readonly");
                      $("#codigoCieNF_fuasEmitidosG").removeAttr("readonly");
                      $("#fechaF_fuasEmitidosG").removeAttr("readonly");
                      $("#horaF_fuasEmitidosG").removeAttr("readonly");
                      $("#codigoCieNF_0_fuasEmitidosG").removeAttr("readonly");
                      $("#codigoCieNF_1_fuasEmitidosG").removeAttr("readonly");
                      $("#codigoCieNF_2_fuasEmitidosG").removeAttr("readonly");
                      $("#codigoCieNF_3_fuasEmitidosG").removeAttr("readonly");
                      $("#diagnosticoF_0_fuasEmitidosG").removeAttr("readonly");
                      $("#diagnosticoF_1_fuasEmitidosG").removeAttr("readonly");
                      $("#diagnosticoF_2_fuasEmitidosG").removeAttr("readonly");
                      $("#diagnosticoF_3_fuasEmitidosG").removeAttr("readonly");
                      
                      var idCodigoPrestacional = $("#codigoPrestacionalF_fuasEmitidosG").val();

                      if(idCodigoPrestacional == '065'){
                        $("#tipoAtencionF_fuasEmitidosG").attr('readonly','readonly');
                      }else{
                        $("#tipoAtencionF_fuasEmitidosG").removeAttr("readonly");
                      }

                      $("#codigoPrestacionalF_fuasEmitidosG").removeAttr("readonly");
                      $("#botonCerrarVerFua_fuasEmitidosG").css("display","none");
                      
                      $("#cancelar_actualizarFuaF_fuasEmitidosG").on('click',function(){

                        $("#actualizarFuaF_fuasEmitidosG").validate().resetForm();
                        $("#cerrar_actualizarFuaF_fuasEmitidosG").css("display","block");
                        $("#imprimir_actualizarFuaF_fuasEmitidosG").css("display","block");
                        $("#actualizar_actualizarFuaF_fuasEmitidosG").css("display","block");
                        $("#anular_anularFuaF_fuasEmitidosG").css("display","block");
                        $("#registrar_actualizarFuaF_fuasEmitidosG").css("display","none");
                        $("#cancelar_actualizarFuaF_fuasEmitidosG").css("display","none");
                        $("#nombresApellidosP_fuasEmitidosG").attr('readonly','readonly');
                        $("#codigoCieNF_fuasEmitidosG").attr('readonly','readonly');
                        $("#tipoAtencionF_fuasEmitidosG").attr('readonly','readonly');
                        $("#codigoPrestacionalF_fuasEmitidosG").attr('readonly','readonly');
                        $("#fechaF_fuasEmitidosG").attr('readonly','readonly');
                        $("#horaF_fuasEmitidosG").attr('readonly','readonly');
                        $("#botonCerrarVerFua_fuasEmitidosG").css("display","block");
                        $("#codigoCieNF_0_fuasEmitidosG").attr('readonly','readonly');
                        $("#codigoCieNF_1_fuasEmitidosG").attr('readonly','readonly');
                        $("#codigoCieNF_2_fuasEmitidosG").attr('readonly','readonly');
                        $("#codigoCieNF_3_fuasEmitidosG").attr('readonly','readonly');
                        $("#diagnosticoF_0_fuasEmitidosG").attr('readonly','readonly');
                        $("#diagnosticoF_1_fuasEmitidosG").attr('readonly','readonly');
                        $("#diagnosticoF_2_fuasEmitidosG").attr('readonly','readonly');
                        $("#diagnosticoF_3_fuasEmitidosG").attr('readonly','readonly');
                      });

                      $("#registrar_actualizarFuaF1_fuasEmitidosG").click(function(){

                        if($("#codigoCieNF_2_fuasEmitidosG").val() != ''){
                            $("#diagnosticoF_2_fuasEmitidosG").prop('required', true);
                        }else{
                            $("#diagnosticoF_2_fuasEmitidosG").prop('required', false);
                        }

                        if($("#diagnosticoF_2_fuasEmitidosG").val() != ''){
                            $("#codigoCieNF_2_fuasEmitidosG").prop('required', true);
                        }else{
                            $("#codigoCieNF_2_fuasEmitidosG").prop('required', false);
                        }

                        if($("#actualizarFuaF_fuasEmitidosG").valid() == false){
		                      return;
	                    }

                        let personalAtiendeF_fuasEmitidosG = $("#personalAtiendeF_fuasEmitidosG").val();
	                    let lugarAtencionF_fuasEmitidosG = $("#lugarAtencionF_fuasEmitidosG").val();
	                    /* let tipoAtencionF_fuasEmitidosG = $("#tipoAtencionF_fuasEmitidosG").val(); */
	                    let codigoReferenciaF_fuasEmitidosG = $("#codigoReferenciaF_fuasEmitidosG").val();
	                    let descripcionReferenciaF_fuasEmitidosG = $("#descripcionReferenciaF_fuasEmitidosG").val();
	                    let numeroReferenciaF_fuasEmitidosG = $("#numeroReferenciaF_fuasEmitidosG").val();
	                    let tipoDocumentoF_fuasEmitidosG = $("#tipoDocumentoF_fuasEmitidosG").val();
	                    let numeroDocumentoF_fuasEmitidosG = $("#numeroDocumentoF_fuasEmitidosG").val();
	                    let componenteF_fuasEmitidosG = $("#componenteF_fuasEmitidosG").val();
	                    let codigoAsegurado2F_fuasEmitidosG = $("#codigoAsegurado2F_fuasEmitidosG").val();
	                    let codigoAsegurado3F_fuasEmitidosG = $("#codigoAsegurado3F_fuasEmitidosG").val();
	                    let apellidoPaternoF_fuasEmitidosG = $("#apellidoPaternoF_fuasEmitidosG").val();
	                    let apellidoMaternoF_fuasEmitidosG = $("#apellidoMaternoF_fuasEmitidosG").val();
	                    let primerNombreF_fuasEmitidosG = $("#primerNombreF_fuasEmitidosG").val();
	                    let sexoF_fuasEmitidosG = $("#sexoF_fuasEmitidosG").val();
	                    let fechaNacimientoF_fuasEmitidosG = $("#fechaNacimientoF_fuasEmitidosG").val();
/* 	                    let fechaF_fuasEmitidosG = $("#fechaF_fuasEmitidosG").val();
	                    let horaF_fuasEmitidosG = $("#horaF_fuasEmitidosG").val(); */
	                    let codigoPrestacionalF_fuasEmitidosG = $("#codigoPrestacionalF_fuasEmitidosG").val();
	                    let conceptoPrestacionalF_fuasEmitidosG = $("#conceptoPrestacionalF_fuasEmitidosG").val();
	                    let destinoAseguradoF_fuasEmitidosG = $("#destinoAseguradoF_fuasEmitidosG").val();
                        let historiaF_fuasEmitidosG = $("#historiaF_fuasEmitidosG").val();

                        $('#actualizarFuaF_fuasEmitidosG').submit(function(e){

                            if($("#actualizarFuaF_fuasEmitidosG").valid() == false){
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
                                url: '{{ route('consultar.actualizarFuaEmitidosG') }}',
                                method: "POST",
                                data: $("#actualizarFuaF_fuasEmitidosG").serialize(),
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
                                        $("#cerrar_actualizarFuaF_fuasEmitidosG").css("display","block");
                                        $("#imprimir_actualizarFuaF_fuasEmitidosG").css("display","block");
                                        $("#actualizar_actualizarFuaF_fuasEmitidosG").css("display","block");
                                        $("#anular_anularFuaF_fuasEmitidosG").css("display","block");
                                        $("#registrar_actualizarFuaF_fuasEmitidosG").css("display","none");
                                        $("#cancelar_actualizarFuaF_fuasEmitidosG").css("display","none");
                                        $('#nombresApellidosP_fuasEmitidosG').attr('readonly','readonly');
                                        $('#codigoCieNF_fuasEmitidosG').attr('readonly','readonly');
                                        $("#fechaF_fuasEmitidosG").attr('readonly','readonly');
                                        $("#horaF_fuasEmitidosG").attr('readonly','readonly');
                                        $("#tipoAtencionF_fuasEmitidosG").attr('readonly','readonly');
                                        $("#codigoPrestacionalF_fuasEmitidosG").attr('readonly','readonly');
                                        $("#botonCerrarVerFua_fuasEmitidosG").css("display","block");
                                        $("#codigoCieNF_0_fuasEmitidosG").attr('readonly','readonly');
                                        $("#codigoCieNF_1_fuasEmitidosG").attr('readonly','readonly');
                                        $("#codigoCieNF_2_fuasEmitidosG").attr('readonly','readonly');
                                        $("#codigoCieNF_3_fuasEmitidosG").attr('readonly','readonly');
                                        $("#diagnosticoF_0_fuasEmitidosG").attr('readonly','readonly');
                                        $("#diagnosticoF_1_fuasEmitidosG").attr('readonly','readonly');
                                        $("#diagnosticoF_2_fuasEmitidosG").attr('readonly','readonly');
                                        $("#diagnosticoF_3_fuasEmitidosG").attr('readonly','readonly');
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

                      $('select[name=nombresApellidosP_fuasEmitidosG]').change(function(){
                        var idPersonal = $("#nombresApellidosP_fuasEmitidosG").val();
                        /* console.log(idPersonal); */

                        if(idPersonal != ''){
                          $.ajax({
                            url: '{{ route('consultar.personalCEmitidosG') }}',
                            data: {idPersonal},
                            success: function(respuesta){
                              /* console.log("respuesta",respuesta); */
                              var arregloPersonalC = JSON.parse(respuesta);
                              for(var x=0;x<arregloPersonalC.length;x++){
                                if(arregloPersonalC[x].ddi_cod == 1){
                                  $("#tipoDocumentoP_fuasEmitidosG").val('D.N.I.');
                                }else{
                                  $("#numeroDocumentoP_fuasEmitidosG").val('');
                                }
  
                                $("#numeroDocumentoP_fuasEmitidosG").val(arregloPersonalC[x].ddi_nro);
                                $("#tipoPersonalSaludF_fuasEmitidosG").val(arregloPersonalC[x].TipoPersonalSalud_id).trigger('change.select2');
                                $("#egresadoF_fuasEmitidosG").val(arregloPersonalC[x].Egresado_id).trigger('change.select2');
                                $("#especialidadF_fuasEmitidosG").val(arregloPersonalC[x].Especialidad_id).trigger('change.select2');
                                $("#colegiaturaF_fuasEmitidosG").val(arregloPersonalC[x].NroColegiatura);
                                $("#rneF_fuasEmitidosG").val(arregloPersonalC[x].NroRNE);
                              }
                            },

                            error: function(jqXHR,textStatus,errorThrown){
                              console.error(textStatus + " " + errorThrown);
                            }
                          });
                        }else{
                          $("#tipoDocumentoP_fuasEmitidosG").val('');
                          $("#numeroDocumentoP_fuasEmitidosG").val('');
                          $("#tipoPersonalSaludF_fuasEmitidosG").val('').trigger('change.select2');
                          $("#egresadoF_fuasEmitidosG").val('').trigger('change.select2');
                          $("#especialidadF_fuasEmitidosG").val('').trigger('change.select2');
                          $("#colegiaturaF_fuasEmitidosG").val('');
                          $("#rneF_fuasEmitidosG").val('');
                        }
                      /* FIN DE PERSONAL DATOS GENERALES */
                      });

                      $('select[name=codigoPrestacionalF_fuasEmitidosG]').change(function(){
                        
                        var idCodigoPrestacional = $("#codigoPrestacionalF_fuasEmitidosG").val();

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
                                  $("#fechaAltaF_fuasEmitidosG").val($("#fechaF_fuasEmitidosG").val());
                                  $("#tipoAtencionF_fuasEmitidosG").val("").trigger("change");
                                  $("#tipoAtencionF_fuasEmitidosG").attr('readonly','readonly');
                                }else{
                                  $(".hospitalizacion_oculto").css("display","none");
                                  $("#fechaAltaF_fuasEmitidosG").val("");
                                  $("#tipoAtencionF_fuasEmitidosG").val(1).trigger("change");
                                }
                              }
                            });
                          }else{
                            $(".hospitalizacion_oculto").css("display","none");
                            $("#fechaAltaF_fuasEmitidosG").val("");
                            $("#tipoAtencionF_fuasEmitidosG").val(1).trigger("change");/* CORREGIR */
                            $("#tipoAtencionF_fuasEmitidosG").removeAttr("readonly");
                          }
                      });
                    });

                    /* IMPRIMIR FUA CON EL ID DE ATENCIÓN */
                    $('#imprimir_verFuaF_fuasEmitidosG').click(function(){

                      var IdAtencion = $("#atencionIdF_fuasEmitidosG").val();
                      /* console.log(ruta+'/'+'fuasEmitidosG/reportesFUA/'+IdAtencion); */

                      printJS({printable:ruta+'/'+'fuasEmitidosG/reportesFUA/'+IdAtencion, type:'pdf', showModal:true});
                    });
                    /* FIN DE AJAX PARA EXTRAER VALORES DEL FUA */
 
</script>

<script type="text/javascript">
	$('#frmPasarAnulacionGeneracionFua_fuasEmitidosG').submit(function(e){
        e.preventDefault();

        swal({
            title: '¿Está seguro de volver de ANULADO a GENERADO?',
            text: "¡Si no lo está puede cancelar la acción!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Si, pasar a GENERADO!'
        }).then(function(result){
            if(result.value){
                $.ajax({
                    url: '{{ route('consultar.volverDeAnuladoAGenerado') }}',
                    method: "POST",
                    data: $("#frmPasarAnulacionGeneracionFua_fuasEmitidosG").serialize(),
                    success:function(respuesta){
                        console.log("respuesta",respuesta);

                        if(respuesta == "volver-correcto"){
                            swal({
                                type:"success",
                                title: "¡El FUA volvió a 'GENERADO' correctamente!",
                                /* text: "Nombre-Paquete: " + respuesta[1][0] + "," + "Id-Paquete: " + respuesta[2][0], */
                                showConfirmButton: true,
                                confirmButtonText: "Cerrar"
                            }).then(function(result){
								if(result.value){
									$("#btnPasarAnulacionGeneracionFUA_fuasEmitidosG").css("display","none");
                                    tablaFuasEmitidosG.ajax.reload(null,false);
                                }
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

@endsection

@section('javascript')
    <script src="{{ url('/') }}/js/fuasEmitidosG.js"></script>
@endsection

@endif

@endforeach