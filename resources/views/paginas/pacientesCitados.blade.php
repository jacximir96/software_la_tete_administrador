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
            <h1>Pacientes Citados</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
              <li class="breadcrumb-item active">Pacientes Citados</li>
            </ol>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="input-group">
              <label for="email" class="col-md-2 control-label">Unidad Orgánica:</label>

              <div class="col-md-4" id="lista_unidadOrganica"></div>

              <label for="email" class="col-md-2 control-label">N° Historia:</label>

              <div class="col-md-1">
                <input type="text" class="form-control inputRutaNumero" name="historia_pacientesCitados" id="historia_pacientesCitados"
                style="text-transform: uppercase;text-align:center;font-weight:900;" maxlength="6" readonly="true">
              </div>

              <label for="email" class="col-md-1 control-label">Doc. Id:</label>

              <div class="col-md-1">
                <input type="text" class="form-control" name="tipoDocumento_pacientesCitados" id="tipoDocumento_pacientesCitados"
                style="text-transform: uppercase;text-align:center;font-weight:900;" readonly="true">
              </div>

              <div class="col-md-1">
                <input type="text" class="form-control" name="documento_pacientesCitados" id="documento_pacientesCitados"
                style="text-transform: uppercase;text-align:center;font-weight:900;" readonly="true">
              </div>
            </div>

            <div class="input-group">
              <label for="email" class="col-md-2 control-label">Grupo Profesional:</label>

              <div class="col-md-4" id="lista_grupoProfesional"><!-- Información desde Javascript (pacientesCitados.js) --></div>

              <label for="email" class="col-md-2 control-label">Paciente:</label>

              <div class="col-md-4">
                <input type="text" class="form-control" name="nombres_pacientesCitados" id="nombres_pacientesCitados"
                style="text-transform: uppercase;font-weight:900;" readonly="true">
              </div>
            </div>

            <div class="input-group">
              <label for="email" class="col-md-2 control-label">Profesional:</label>

              <div class="col-md-4" id="lista_profesionales"><!-- Información desde Javascript (pacientesCitados.js) --></div>

              <label for="email" class="col-md-2 control-label">
                <!-- <div id="loading-screen" style="display:none;"> -->
                  <img style="width:25px;display:none;height:18px;" id="loading-screen" src="{{ url('/') }}/img/pacientesCitados/spinning-circles.svg">
                <!-- </div> --> SIS:
              </label>

              <div class="col-md-1">
                <input type="text" class="form-control" name="tipoSeguro_pacientesCitados" id="tipoSeguro_pacientesCitados"
                style="text-transform: uppercase;text-align:left;font-weight:900;" readonly="true">
              </div>

              <div class="col-md-1">
                <input type="text" class="form-control" name="estadoSeguro_pacientesCitados" id="estadoSeguro_pacientesCitados"
                style="text-transform: uppercase;" readonly="true">
              </div>

              <label for="email" class="col-md-1 control-label">Hasta:</label>

              <div class="col-md-1">
                <input type="text" class="form-control" name="fechaCaducidadSeguro_pacientesCitados" id="fechaCaducidadSeguro_pacientesCitados"
                style="text-transform: uppercase;text-align:center;font-weight:900;" readonly="true">
              </div>
            </div>

            <form method="GET" action="{{ url('/') }}/pacientesCitados/buscarPorMes" id="frmFechas">
              @csrf
              <div class="input-group">
                  <label for="email" class="col-md-2 control-label">Fecha de cita / Atención:</label>

                  <div class="col-md-2">
                    <input type="date" class="form-control" name="fechaInicio_pacientesCitados" id="fechaInicio_pacientesCitados"
                    style="text-transform: uppercase;" required>
                  </div>

                  <div class="col-md-2">
                    <input type="date" class="form-control" name="fechaFin_pacientesCitados" id="fechaFin_pacientesCitados"
                    style="text-transform: uppercase;" required>
                  </div>

                  <label for="email" class="col-md-6 control-label" style="text-align:center;color:white;background:red;">Busquedas directas</label>
              </div>

              <div class="input-group">
                <div class="col-md-2">
                  <button style="width:100%;font-weight:400;" id="btnRecargarDatos" type="button" class="btn btn-secondary btn-sm">
							    <i class="fas fa-search"></i> Recargar Tabla</button>
                </div>
                <div class="col-md-4">
                  <button style="width:100%;font-weight:400;" id="btnGuardar" type="submit" class="btn btn-primary btn-sm">
							    <i class="fas fa-search"></i> Consultar</button>
                </div>

                <label for="historiaBD_pacientesCitados" class="col-md-1 control-label" style="">N° Historia:</label>
                <div class="col-md-2">
                    <input type="text" class="form-control inputRutaNumero" name="historiaBD_pacientesCitados" id="historiaBD_pacientesCitados"
                    style="text-transform: uppercase;" maxlength="6">
                </div>

                <label for="documentoBD_pacientesCitados" class="col-md-1 control-label">N° Documento:</label>
                <div class="col-md-2">
                  <input type="text" class="form-control inputRutaNumero" name="documentoBD_pacientesCitados" id="documentoBD_pacientesCitados"
                  style="text-transform: uppercase;" maxlength="9">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Fin Content Header (Page header)  -->




<menu id="html5menu" style="display:none" class="showcase">
  <command label="Copiar N° de FUA" icon="edit" onclick="alert('resize')">
  </menu>
</menu>


    <!-- Inicio Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Default box -->
            <div class="card">
              <div class="card-header">

                <button class="btn btn-warning btn-sm boton-general" data-toggle="modal" data-target="#buscarPaciente" style="float:right; margin-left: 5px;">
                  <i class="fas fa-record-vinyl"></i> Buscar Paciente
                </button>

                <button class="btn btn-info btn-sm boton-general" data-toggle="modal" data-target="#crearFuaLibre" style="float:right; margin-left: 5px;">
                  <i class="fas fa-clone"></i> FUA Libre
                </button>

                <form method="GET" action="" id="frmGenerarFua">
                  @csrf
                  <input type="text" class="form-control" name="idDet_pacientesCitados" id="idDet_pacientesCitados"
                  style="text-transform: uppercase;display:none;" required>

                  <button type="submit" class="btn btn-danger btn-sm boton-general" data-toggle="modal" data-target="#generarFua"
                  style="float:left;display:none;margin-right: 5px;" id="btnGenerarFUA_pacientesCitados">
							    <i class="fas fa-file" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Generar FUA</button>
                </form>

                <form method="GET" action="" id="frmVerRolCitas">
                  @csrf
                  <input type="text" class="form-control" name="idCab_pacientesCitados" id="idCab_pacientesCitados"
                  style="text-transform: uppercase;display:none;" required>

                  <button type="submit" class="btn btn-dark btn-sm boton-general" data-toggle="modal" data-target="#verRolCitas"
                  style="float:left;display:none;margin-right: 5px;" id="btnRolCitas_pacientesCitados">
							    <i class="fas fa-file" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Rol de Citas</button>

<!--                   <button type="button" class="btn btn-dark btn-sm boton-general"
                  style="float:left;display:none;margin-right: 5px;" id="btnActivarGeneracion_pacientesCitados">
							    <i class="fas fa-check" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Activar Generacion!</button> -->
                </form>

<!--                 <form method="GET" action="" id="frmCopiarPegarFua_pacientesCitados">
                  @csrf
                  <input type="text" class="form-control" name="valorCopiar_pacientesCitados" id="valorCopiar_pacientesCitados"
                  style="text-transform: uppercase;">

                  <input type="text" class="form-control" name="valorCopiado_pacientesCitados" id="valorCopiado_pacientesCitados"
                  style="text-transform: uppercase;">

                  <button type="button" class="btn btn-secondary btn-sm boton-general"
                  style="float:left;margin-right: 5px;" id="btnCopiar_pacientesCitados">
							    <i class="fas fa-file" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Copiar N° de FUA</button>

                  <button type="submit" class="btn btn-secondary btn-sm boton-general"
                  style="float:left;display:none;margin-right: 5px;" id="btnPegar_pacientesCitados">
							    <i class="fas fa-file" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Pegar N° de FUA</button>
                </form> -->
              </div>

              <div class="card-body">

                <table class="table table-bordered table-striped display nowrap" width="100%"
                id="tablaPacientesCitados">

                    <thead>
                        <tr style="background:white;" role="row">
                          <th rowspan="2">Financiador</th>
                          <th rowspan="2">Tipo Atención</th>
                          <th rowspan="2">Fecha Cita</th>
                          <th rowspan="2" style="width:350px;">Paciente</th>
                          <th colspan="2"style="text-align:center;">Documento</th>
                          <th rowspan="2">HC</th>
                          <th rowspan="2">FUA/CG</th>
                          <th rowspan="2">Actividad</th>
                          <th rowspan="2">Profesional</th>
                          <th rowspan="2">Estado Cita</th>
                          <th rowspan="2">Unidad Orgánica</th>
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

<div class="modal fade" id="buscarPaciente" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
        <h4 class="modal-tittle" style="color:white;"><i class="fa fa-pencil"></i> &nbsp; <span class="title-form">Buscar Paciente</span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body" id="modal-container">
        <div class="form-group">
					<div class="row">
						<div class="col-sm-6">
							<label for="historiaClinicaBP_pacientesCitados">Historia Clinica</label>
              <input type="text" class="form-control inputRutaNumero" name="historiaClinicaBP_pacientesCitados" id="historiaClinicaBP_pacientesCitados" maxlength="6">
            </div>

            <div class="col-sm-2" style="display:none;">
              <input type="text" class="form-control" name="tipoDocumentoBP_pacientesCitados" id="tipoDocumentoBP_pacientesCitados"
              style="text-transform: uppercase;" readonly="true">
            </div>

            <div class="col-sm-6">
							<label for="documentoBP_pacientesCitados">Doc. Identidad / Carné de Extranj.</label>
              <input type="text" class="form-control inputRutaNumero" name="documentoBP_pacientesCitados" id="documentoBP_pacientesCitados" maxlength="9">
            </div>
					</div>
				</div>

        <hr>

        <div class="form-group">
          <div class="row">
            <div class="col-sm-6">
              <label for="apellidoPaternoBP_pacientesCitados">Apellido Paterno <span class="text-danger"> * </span></label>
              <input type="text" class="form-control" name="apellidoPaternoBP_pacientesCitados" id="apellidoPaternoBP_pacientesCitados"
              style="text-transform: uppercase;" required readonly="true">
            </div>

            <div class="col-sm-6">
              <label for="apellidoMaternoBP_pacientesCitados">Apellido Materno <span class="text-danger"> * </span></label>
              <input type="text" class="form-control" name="apellidoMaternoBP_pacientesCitados" id="apellidoMaternoBP_pacientesCitados"
              style="text-transform: uppercase;" required readonly="true">
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="row">
            <div class="col-sm-6">
              <label for="nombresBP_pacientesCitados">Nombres <span class="text-danger"> * </span></label>
              <input type="text" class="form-control" name="nombresBP_pacientesCitados" id="nombresBP_pacientesCitados"
              style="text-transform: uppercase;" required readonly="true">
            </div>

            <div class="col-sm-3">
              <label for="sexoBP_pacientesCitados">Sexo <span class="text-danger"> * </span></label>
              <input type="text" class="form-control" name="sexoBP_pacientesCitados" id="sexoBP_pacientesCitados"
              style="text-transform: uppercase;" required readonly="true">
            </div>

            <div class="col-sm-3">
              <label for="fechaNacimientoBP_pacientesCitados">Fecha Nacimiento <span class="text-danger"> * </span></label>
              <input type="date" class="form-control" name="fechaNacimientoBP_pacientesCitados" id="fechaNacimientoBP_pacientesCitados"
              style="text-transform: uppercase;" required readonly="true">
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="row">
            <div class="col-sm-6">
              <label for="telefonoBP_pacientesCitados">Teléfono <span class="text-danger"> * </span></label>
              <input type="text" class="form-control" name="telefonoBP_pacientesCitados" id="telefonoBP_pacientesCitados"
              style="text-transform: uppercase;" required readonly="true">
            </div>

            <div class="col-sm-6">
              <label for="correoElectronicoBP_pacientesCitados">Correo Electrónico <span class="text-danger"> * </span></label>
              <input type="text" class="form-control" name="correoElectronicoBP_pacientesCitados" id="correoElectronicoBP_pacientesCitados"
              style="text-transform: uppercase;" required readonly="true">
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="crearFuaLibre" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ url('/') }}/productosLimpieza/generarFuaLibre" enctype="multipart/form-data" id="generarFuaLibre_pacientesCitados"
            pagina="pacientesCitados">
                @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;"><i class="fa fa-pencil"></i> &nbsp; <span class="title-form">Generar FUA-SIS Libre</span></h4>
                    <button type="button" id="botonCerrarGenerarFuaLibre_pacientesCitados" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body" id="modal-container">

                    <div class="alert alert-info alert-styled-left text-blue-800 content-group">
				              <span class="text-semibold">Estimado usuario</span>
				                Los campos remarcados con <span class="text-danger"> * </span> son necesarios.
				              <button type="button" class="close" data-dismiss="alert">×</button>
	                    <input type="hidden" id="txtProceso" name="txtProceso" class="form-control" value="Entrada">
				            </div>

                    <div class="input-group mb-1 usuarios_general" >
                        <div class="input-group-append input-group-text" style="border-radius:0px;">
                            <i class="fas fa-angle-double-right"></i>
                        </div>

                        @foreach($administradores as $element)
                            @if ($_COOKIE["email_login"] == $element->email)
                                <input type="text" class="form-control" name="usuarioFLibre_pacientesCitados"
                                value="{{ old('id',$element->id) }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                        @endforeach
                    </div>

                    <div class="form-group">
							        <div class="row">
								        <div class="col-sm-6">
									        <label for="historiaClinica_pacientesCitados">Historia Clinica</label>
                          <input type="text" class="form-control inputRutaNumero" name="historiaClinica_pacientesCitados" id="historiaClinica_pacientesCitados" maxlength="6">
                        </div>

                        <div class="col-sm-2" style="display:none;">
                          <input type="text" class="form-control" name="tipoDocumentoFL_pacientesCitados" id="tipoDocumentoFL_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>

                        <div class="col-sm-6">
									        <label for="documentoN_pacientesCitados">Doc. Identidad / Carné de Extranj.</label>
                          <input type="text" class="form-control inputRutaNumero" name="documentoN_pacientesCitados" id="documentoN_pacientesCitados" maxlength="9">
                        </div>
							        </div>
					          </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-6">
                          <input type="text" class="form-control" name="tipoSeguroFL_pacientesCitados" id="tipoSeguroFL_pacientesCitados"
                          style="text-transform: uppercase;text-align:center;font-weight:900;" readonly="true">
                        </div>

                        <div class="col-md-6">
                          <input type="text" class="form-control" name="estadoSeguroFL_pacientesCitados" id="estadoSeguroFL_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>
                      </div>
                    </div>

                    <hr>

                    <div class="form-group">
							        <div class="row">
								        <div class="col-sm-4">
									        <label for="historiaClinica_pacientesCitados">Personal que atiende <span class="text-danger"> * </span></label>
                          <select class="form-control select-2 select2" name="personalAtiendeFL_pacientesCitados" id="personalAtiendeFL_pacientesCitados">
                              <option value="">-- Seleccionar el Personal --</option>
                              @foreach($personalAtiende as $key => $value_personalAtiende)
                                  <option value="{{$value_personalAtiende->id}}">{{$value_personalAtiende->descripcion}}</option>
                              @endforeach
                          </select>
                        </div>

                        <div class="col-sm-4">
									        <label for="historiaClinica_pacientesCitados">Lugar de Atención <span class="text-danger"> * </span></label>
                          <select class="form-control select-2 select2" name="lugarAtencionFL_pacientesCitados" id="lugarAtencionFL_pacientesCitados">
                              <option value="">-- Seleccionar el Lugar de Atención --</option>
                              @foreach($lugarAtencion as $key => $value_lugarAtencion)
                                  <option value="{{$value_lugarAtencion->id}}">{{$value_lugarAtencion->descripcion}}</option>
                              @endforeach
                          </select>
                        </div>

                        <div class="col-sm-4">
									        <label for="historiaClinica_pacientesCitados">Tipo de Atención <span class="text-danger"> * </span></label>
                          <select class="form-control select-2 select2" name="tipoAtencionFL_pacientesCitados" id="tipoAtencionFL_pacientesCitados">
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
									        <label for="codigoReferenciaFL_pacientesCitados">Referencia realizada por <span class="text-danger"> * </span></label>
                        </div>

                        <div class="col-sm-4">
									        <label for="numeroReferenciaFL_pacientesCitados">N° Hoja de Referencia <span class="text-danger"> * </span></label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="codigoReferenciaFL_pacientesCitados" id="codigoReferenciaFL_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-6">
                          <input type="text" class="form-control" name="descripcionReferenciaFL_pacientesCitados" id="descripcionReferenciaFL_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-4">
                          <input type="text" class="form-control" name="numeroReferenciaFL_pacientesCitados" id="numeroReferenciaFL_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-4">
                          <label for="tipoDocumentoFL1_pacientesCitados">Identidad del Asegurado <span class="text-danger"> * </span></label>
                        </div>

                        <div class="col-sm-4">
                          <label for="componenteFL_pacientesCitados">Componente <span class="text-danger"> * </span></label>
                        </div>

                        <div class="col-sm-4">
                          <label for="codigoAsegurado1FL_pacientesCitados">Código del Asegurado <span class="text-danger"> * </span></label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="tipoDocumentoFL1_pacientesCitados" id="tipoDocumentoFL1_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="documentoN1_pacientesCitados" id="documentoN1_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-4">
                          <select class="form-control select-2 select2" name="componenteFL_pacientesCitados" id="componenteFL_pacientesCitados">
                            <option value="">-- Seleccionar el Componente --</option>
                            @foreach($componente as $key => $value_componente)
                              <option value="{{$value_componente->id}}">{{$value_componente->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-sm-1">
                          <input type="text" class="form-control" name="codigoAsegurado1FL_pacientesCitados" id="codigoAsegurado1FL_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>

                        <div class="col-sm-1">
                          <input type="text" class="form-control" name="codigoAsegurado2FL_pacientesCitados" id="codigoAsegurado2FL_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="codigoAsegurado3FL_pacientesCitados" id="codigoAsegurado3FL_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-6">
                          <label for="apellidoPaterno_pacientesCitados">Apellido Paterno <span class="text-danger"> * </span></label>
                          <input type="text" class="form-control" name="apellidoPaterno_pacientesCitados" id="apellidoPaterno_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-6">
                          <label for="apellidoMaterno_pacientesCitados">Apellido Materno <span class="text-danger"> * </span></label>
                          <input type="text" class="form-control" name="apellidoMaterno_pacientesCitados" id="apellidoMaterno_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-6">
                          <label for="primerNombreFL_pacientesCitados">Primer Nombre <span class="text-danger"> * </span></label>
                          <input type="text" class="form-control" name="primerNombreFL_pacientesCitados" id="primerNombreFL_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-6">
                          <label for="otroNombreFL_pacientesCitados">Otros Nombres</label>
                          <input type="text" class="form-control" name="otroNombreFL_pacientesCitados" id="otroNombreFL_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-4">
                          <label for="sexoFL_pacientesCitados">Sexo <span class="text-danger"> * </span></label>
                          <input type="text" class="form-control" name="sexoFL_pacientesCitados" id="sexoFL_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-4">
                          <label for="fechaNacimientoFL_pacientesCitados">Fecha de Nacimiento <span class="text-danger"> * </span></label>
                          <input type="date" class="form-control" name="fechaNacimientoFL_pacientesCitados" id="fechaNacimientoFL_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-4">
                          <label for="historiaFL_pacientesCitados">Historia <span class="text-danger"> * </span></label>
                          <input type="text" class="form-control" name="historiaFL_pacientesCitados" id="historiaFL_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>

                        <div class="col-sm-4" style="display:none;">
                          <label for="edad_pacientesCitados">Edad</label>
                          <input type="text" class="form-control" name="edad_pacientesCitados" id="edad_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-3">
                          <label for="fechaAtencion_pacientesCitados">Fecha de Atención</label>
                          <input type="date" class="form-control" name="fechaAtencion_pacientesCitados" id="fechaAtencion_pacientesCitados"
                          style="text-transform: uppercase;">
                        </div>

                        <div class="col-sm-2">
                          <label for="hora_pacientesCitados">Hora de Atención</label>
                          <input type="time" class="form-control" name="hora_pacientesCitados" id="hora_pacientesCitados"
                          style="text-transform: uppercase;">
                        </div>

                        <div class="col-sm-7">
                          <label for="codigoPrestacional_pacientesCitados">Código Prestacional <span class="text-danger"> * </span></label>
                          <select class="form-control select-2 select2" name="codigoPrestacional_pacientesCitados" id="codigoPrestacional_pacientesCitados" required>
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
                          <label for="conceptoPrestacionalFL_pacientesCitados">Concepto Prestacional <span class="text-danger"> * </span></label>
                          <select class="form-control select-2 select2" name="conceptoPrestacionalFL_pacientesCitados" id="conceptoPrestacionalFL_pacientesCitados">
                            <option value="">-- Seleccionar concepto prestacional --</option>
                            @foreach($concPrestacional as $key => $value_concPrestacional)
                              <option value="{{$value_concPrestacional->id}}">{{$value_concPrestacional->id}} - {{$value_concPrestacional->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-sm-6">
                          <label for="destinoAseguradoFL_pacientesCitados">Destino del Asegurado <span class="text-danger"> * </span></label>
                          <select class="form-control select-2 select2" name="destinoAseguradoFL_pacientesCitados" id="destinoAseguradoFL_pacientesCitados">
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
                          <label for="fechaIngresoF_pacientesCitados">Fecha de Ingreso <span id="span_fechaIngresoF_pacientesCitados" class="text-danger"> * </span></label>
                          <input type="date" class="form-control" name="fechaIngresoF_pacientesCitados" id="fechaIngresoF_pacientesCitados"
                          style="text-transform: uppercase;">
                        </div>

                        <div class="col-sm-6">
                          <label for="fechaAltaF_pacientesCitados">Fecha de Alta</label>
                          <input type="date" class="form-control" name="fechaAltaF_pacientesCitados" id="fechaAltaF_pacientesCitados"
                          style="text-transform: uppercase;">
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
									        <label for="diagnostico_pacientesCitados">Tipo</label>
                        </div>

                        <div class="col-sm-2">
									        <label for="codigoCieN_pacientesCitados">CIE - 10</label>
                        </div>

                        <div class="col-sm-6">
									        <label for="codigoCie_pacientesCitados">Descripción</label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-4">
                          <select class="form-control select-2 select2" name="diagnostico_pacientesCitados" id="diagnostico_pacientesCitados">
                            <option value="">-- Seleccionar el tipo --</option>
                            <option value="P">PRESUNTIVO</option>
                            <option value="D">DEFINITIVO</option>
                            <option value="R">REPETIDO</option>
                          </select>
                        </div>

                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="codigoCieN_pacientesCitados" id="codigoCieN_pacientesCitados"
                          style="text-transform: uppercase;">
                        </div>

                        <div class="col-sm-6">
                          <input type="text" class="form-control" name="codigoCie_pacientesCitados" id="codigoCie_pacientesCitados"
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
                          <label for="tipoDocumentoPFL_pacientesCitados">Identidad del Profesional</label>
                        </div>

                        <div class="col-sm-8">
                          <label for="personal_pacientesCitados">Nombres y Apellidos <span class="text-danger"> * </span></label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="tipoDocumentoPFL_pacientesCitados" id="tipoDocumentoPFL_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>

                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="numeroDocumentoPFL_pacientesCitados" id="numeroDocumentoPFL_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>

                        <div class="col-sm-8">
                          <select class="form-control select-2 select2" name="personal_pacientesCitados" id="personal_pacientesCitados" required>
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
                          <label for="tipoPersonalSaludFL_pacientesCitados">Tipo de Personal de Salud</label>
                        </div>

                        <div class="col-sm-4">
                          <label for="colegiaturaFL_pacientesCitados">Colegiatura</label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-6">
                          <select class="form-control select-2 select2" name="tipoPersonalSaludFL_pacientesCitados" id="tipoPersonalSaludFL_pacientesCitados">
                            <option value="">-- Seleccionar el Tipo de Personal de Salud --</option>
                            @foreach($sisTipoPersonalSalud as $key => $value_sisTipoPersonalSalud)
                              <option value="{{$value_sisTipoPersonalSalud->id}}">{{$value_sisTipoPersonalSalud->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-sm-2">
                          <select class="form-control select-2 select2" name="egresadoFL_pacientesCitados" id="egresadoFL_pacientesCitados">
                            <option value="">-- Seleccionar si es Egresado --</option>
                            @foreach($sisEgresado as $key => $value_sisEgresado)
                              <option value="{{$value_sisEgresado->id}}">{{$value_sisEgresado->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-sm-4">
                          <input type="text" class="form-control" name="colegiaturaFL_pacientesCitados" id="colegiaturaFL_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-8">
                          <label for="especialidadFL_pacientesCitados">Especialidad</label>
                        </div>

                        <div class="col-sm-4">
                          <label for="rneFL_pacientesCitados">RNE</label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-8">
                          <select class="form-control select-2 select2" name="especialidadFL_pacientesCitados" id="especialidadFL_pacientesCitados">
                            <option value="">-- Seleccionar la Especialidad --</option>
                            @foreach($sisEspecialidad as $key => $value_sisEspecialidad)
                              <option value="{{$value_sisEspecialidad->id}}">{{$value_sisEspecialidad->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-sm-4">
                          <input type="text" class="form-control" name="rneFL_pacientesCitados" id="rneFL_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>
                      </div>
                    </div>

                    <!-- PARA SELECCIONAR EL ID DEL PACIENTE -->
                    <input type="text" class="form-control" name="pacienteIdFL_pacientesCitados" id="pacienteIdFL_pacientesCitados"
                    style="text-transform: uppercase;display:none;" readonly="true">
                    <!-- FIN PARA SELECCIONAR EL ID DEL PACIENTE -->

                </div>

                <div class="modal-footer d-flex">

                    <div id="cancelar_generarFuaLibreF">
                      <button type="button" class="btn btn-default boton-general" data-dismiss="modal">
                        <i class="fa fa-undo" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Salir
                      </button>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-success boton-general" id="guardarFuaLibre_pacientesCitados">Generar FUA Libre</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="verRolCitas">
    <div class="modal-dialog modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-content">
          <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
            <h4 class="modal-tittle" style="color:white;"><i class="fa fa-pencil"></i> &nbsp; <span class="title-form">Rol de Citas</span></h4>
            <button type="button" id="botonCerrar" class="close" data-dismiss="modal">&times;</button>
          </div>

          <div class="modal-body" id="modal-container">
            <table class="table table-bordered table-striped display nowrap" width="100%"
                   id="tablaRolCitas">

              <thead>
                  <tr style="background:white;">
                    <th>N° SESIÓN</th>
                    <th>Fecha Programada</th>
                    <th>Estado</th>
                    <th>Estado Cita</th>
                    <th>Profesional</th>
                    <th>FUA</th>
                    <th>Notas</th>
                  </tr>
              </thead>

              <tbody>

              </tbody>
            </table>
          </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="generarFua" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-content">

            <form method="POST" action="{{ url('/') }}/pacientesCitados/generarFua" enctype="multipart/form-data" id="generarFuaF_pacientesCitados"
            pagina="pacientesCitados">
                @csrf
                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;"><i class="fa fa-pencil"></i> &nbsp; <span class="title-form">FUA</span></h4>
                    <button type="button" id="botonCerrarGenerarFua_pacientesCitados" class="close" data-dismiss="modal">&times;</button>
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
                                <input type="text" class="form-control" name="usuario_pacientesCitados"
                                value="{{ old('id',$element->id) }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                        @endforeach
                    </div>

                    <div class="form-group">
							        <div class="row">
								        <div class="col-sm-4">
									        <label for="historiaClinica_pacientesCitados">Personal que atiende <span class="text-danger"> * </span></label>
                          <select class="form-control select-2 select2" name="personalAtiendeF_pacientesCitados" id="personalAtiendeF_pacientesCitados">
                              <option value="">-- Seleccionar el Personal --</option>
                              @foreach($personalAtiende as $key => $value_personalAtiende)
                                  <option value="{{$value_personalAtiende->id}}">{{$value_personalAtiende->descripcion}}</option>
                              @endforeach
                          </select>
                        </div>

                        <div class="col-sm-4">
									        <label for="historiaClinica_pacientesCitados">Lugar de Atención <span class="text-danger"> * </span></label>
                          <select class="form-control select-2 select2" name="lugarAtencionF_pacientesCitados" id="lugarAtencionF_pacientesCitados">
                              <option value="">-- Seleccionar el Lugar de Atención --</option>
                              @foreach($lugarAtencion as $key => $value_lugarAtencion)
                                  <option value="{{$value_lugarAtencion->id}}">{{$value_lugarAtencion->descripcion}}</option>
                              @endforeach
                          </select>
                        </div>

                        <div class="col-sm-4">
									        <label for="historiaClinica_pacientesCitados">Tipo de Atención <span class="text-danger"> * </span></label>
                          <select class="form-control select-2 select2" name="tipoAtencionF_pacientesCitados" id="tipoAtencionF_pacientesCitados">
                              <option value="">-- Seleccionar el Tipo de Atención --</option>
                              @foreach($tipoAtencion as $key => $value_tipoAtencion)
                                  <option value="{{$value_tipoAtencion->id}}">{{$value_tipoAtencion->descripcion}}</option>
                              @endforeach
                          </select>
                        </div>
							        </div>
					          </div>

                    <div class="form-group" style="display:none;">
                      <div class="row">
                        <div class="col-sm-6">
                          <input type="text" class="form-control" name="idCab_pacientesCitadosC" id="idCab_pacientesCitadosC"
                          style="text-transform: uppercase;" readonly="true">
                        </div>

                        <div class="col-sm-6">
                          <input type="text" class="form-control" name="unidadOrganicaId_pacientesCitadosC" id="unidadOrganicaId_pacientesCitadosC"
                          style="text-transform: uppercase;" readonly="true">
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-3">
                          <input type="text" class="form-control" name="correlativoSis_pacientesCitadosC" id="correlativoSis_pacientesCitadosC"
                          style="text-transform: uppercase;" readonly="true">
                        </div>

                        <div class="col-sm-3">
                          <input type="text" class="form-control" name="tipoTablaSis_pacientesCitadosC" id="tipoTablaSis_pacientesCitadosC"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-3">
                          <input type="text" class="form-control" name="idNumRegSis_pacientesCitadosC" id="idNumRegSis_pacientesCitadosC"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-1">
                          <input type="text" class="form-control" name="idPlanSis_pacientesCitadosC" id="idPlanSis_pacientesCitadosC"
                          style="text-transform: uppercase;" readonly="true">
                        </div>

                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="idUbigeoSis_pacientesCitadosC" id="idUbigeoSis_pacientesCitadosC"
                          style="text-transform: uppercase;" readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
							        <div class="row">
                        <div class="col-sm-8">
									        <label for="historiaClinica_pacientesCitados">Referencia realizada por <span class="text-danger"> * </span></label>
                        </div>

                        <div class="col-sm-4">
									        <label for="historiaClinica_pacientesCitados">N° Hoja de Referencia <span class="text-danger"> * </span></label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="codigoReferenciaF_pacientesCitados" id="codigoReferenciaF_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-6">
                          <input type="text" class="form-control" name="descripcionReferenciaF_pacientesCitados" id="descripcionReferenciaF_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-4">
                          <input type="text" class="form-control" name="numeroReferenciaF_pacientesCitados" id="numeroReferenciaF_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-4">
                          <label for="tipoDocumentoF_pacientesCitados">Identidad del Asegurado <span class="text-danger"> * </span></label>
                        </div>

                        <div class="col-sm-4">
                          <label for="componenteF_pacientesCitados">Componente <span class="text-danger"> * </span></label>
                        </div>

                        <div class="col-sm-4">
                          <label for="codigoAsegurado1F_pacientesCitados">Código del Asegurado <span class="text-danger"> * </span></label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="tipoDocumentoF_pacientesCitados" id="tipoDocumentoF_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="numeroDocumentoF_pacientesCitados" id="numeroDocumentoF_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-4">
                          <select class="form-control select-2 select2" name="componenteF_pacientesCitados" id="componenteF_pacientesCitados">
                            <option value="">-- Seleccionar el Componente --</option>
                            @foreach($componente as $key => $value_componente)
                              <option value="{{$value_componente->id}}">{{$value_componente->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-sm-1">
                          <input type="text" class="form-control" name="codigoAsegurado1F_pacientesCitados" id="codigoAsegurado1F_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>

                        <div class="col-sm-1">
                          <input type="text" class="form-control" name="codigoAsegurado2F_pacientesCitados" id="codigoAsegurado2F_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="codigoAsegurado3F_pacientesCitados" id="codigoAsegurado3F_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-6">
                          <label for="apellidoPaternoF_pacientesCitados">Apellido Paterno <span class="text-danger"> * </span></label>
                          <input type="text" class="form-control" name="apellidoPaternoF_pacientesCitados" id="apellidoPaternoF_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-6">
                          <label for="apellidoMaternoF_pacientesCitados">Apellido Materno</label>
                          <input type="text" class="form-control" name="apellidoMaternoF_pacientesCitados" id="apellidoMaternoF_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-6">
                          <label for="primerNombreF_pacientesCitados">Primer Nombre <span class="text-danger"> * </span></label>
                          <input type="text" class="form-control" name="primerNombreF_pacientesCitados" id="primerNombreF_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-6">
                          <label for="otroNombreF_pacientesCitados">Otros Nombres</label>
                          <input type="text" class="form-control" name="otroNombreF_pacientesCitados" id="otroNombreF_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>

                        <!-- PARA SELECCIONAR EL ID DEL PACIENTE -->
                        <input type="text" class="form-control" name="pacienteIdF_pacientesCitados" id="pacienteIdF_pacientesCitados"
                        style="text-transform: uppercase;display:none;" readonly="true">
                        <!-- FIN PARA SELECCIONAR EL ID DEL PACIENTE -->

                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-4">
                          <label for="sexoF_pacientesCitados">Sexo <span class="text-danger"> * </span></label>
                          <input type="text" class="form-control" name="sexoF_pacientesCitados" id="sexoF_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-4">
                          <label for="fechaNacimientoF_pacientesCitados">Fecha de Nacimiento <span class="text-danger"> * </span></label>
                          <input type="date" class="form-control" name="fechaNacimientoF_pacientesCitados" id="fechaNacimientoF_pacientesCitados"
                          style="text-transform: uppercase;" required readonly="true">
                        </div>

                        <div class="col-sm-4">
                          <label for="historiaF_pacientesCitados">Historia <span class="text-danger"> * </span></label>
                          <input type="text" class="form-control" name="historiaF_pacientesCitados" id="historiaF_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-3">
                          <label for="fechaF_pacientesCitados">Fecha de Atención<!--  <span class="text-danger"> * </span> --></label>
                          <input type="date" class="form-control" name="fechaF_pacientesCitados" id="fechaF_pacientesCitados"
                          style="text-transform: uppercase;">
                        </div>

                        <div class="col-sm-2">
                          <label for="horaF_pacientesCitados">Hora de Atención<!--  <span class="text-danger"> * </span> --></label>
                          <input type="time" class="form-control" name="horaF_pacientesCitados" id="horaF_pacientesCitados"
                          style="text-transform: uppercase;">
                        </div>

                        <div class="col-sm-7">
                          <label for="codigoPrestacionalF_pacientesCitados">Código Prestacional <span class="text-danger"> * </span></label>
                          <select class="form-control select-2 select2" name="codigoPrestacionalF_pacientesCitados" id="codigoPrestacionalF_pacientesCitados">
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
                          <label for="conceptoPrestacionalF_pacientesCitados">Concepto Prestacional <span class="text-danger"> * </span></label>
                          <select class="form-control select-2 select2" name="conceptoPrestacionalF_pacientesCitados" id="conceptoPrestacionalF_pacientesCitados">
                            <option value="">-- Seleccionar concepto prestacional --</option>
                            @foreach($concPrestacional as $key => $value_concPrestacional)
                              <option value="{{$value_concPrestacional->id}}">{{$value_concPrestacional->id}} - {{$value_concPrestacional->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-sm-6">
                          <label for="destinoAseguradoF_pacientesCitados">Destino del Asegurado <span class="text-danger"> * </span></label>
                          <select class="form-control select-2 select2" name="destinoAseguradoF_pacientesCitados" id="destinoAseguradoF_pacientesCitados">
                            <option value="">-- Seleccionar destino asegurado --</option>
                            @foreach($destinoAsegurado as $key => $value_destinoAsegurado)
                              <option value="{{$value_destinoAsegurado->id}}">{{$value_destinoAsegurado->id}} - {{$value_destinoAsegurado->descripcion}}</option>
                            @endforeach
                          </select>
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
									        <label for="diagnosticoF_pacientesCitados">Tipo</label>
                        </div>

                        <div class="col-sm-2">
									        <label for="codigoCieNF_pacientesCitados">CIE - 10</label>
                        </div>

                        <div class="col-sm-6">
									        <label for="codigoCieF_pacientesCitados">Descripción</label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-4">
                          <select class="form-control select-2 select2" name="diagnosticoF_pacientesCitados" id="diagnosticoF_pacientesCitados">
                            <option value="">-- Seleccionar el tipo --</option>
                            <option value="P">PRESUNTIVO</option>
                            <option value="D">DEFINITIVO</option>
                            <option value="R">REPETIDO</option>
                          </select>
                        </div>

                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="codigoCieNF_pacientesCitados" id="codigoCieNF_pacientesCitados"
                          style="text-transform: uppercase;">
                        </div>

                        <div class="col-sm-6">
                          <input type="text" class="form-control" name="codigoCieF_pacientesCitados" id="codigoCieF_pacientesCitados"
                          style="text-transform: uppercase;">
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
                          <label for="tipoDocumentoP_pacientesCitados">Identidad del Profesional</label>
                        </div>

                        <div class="col-sm-8">
                          <label for="nombresApellidosP_pacientesCitados">Nombres y Apellidos <span class="text-danger"> * </span></label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="tipoDocumentoP_pacientesCitados" id="tipoDocumentoP_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>

                        <div class="col-sm-2">
                          <input type="text" class="form-control" name="numeroDocumentoP_pacientesCitados" id="numeroDocumentoP_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>

                        <div class="col-sm-8">
                          <select class="form-control select-2 select2" name="nombresApellidosP_pacientesCitados" id="nombresApellidosP_pacientesCitados">
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
                          <label for="tipoPersonalSaludF_pacientesCitados">Tipo de Personal de Salud</label>
                        </div>

                        <div class="col-sm-4">
                          <label for="colegiaturaF_pacientesCitados">Colegiatura</label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-6">
                          <select class="form-control select-2 select2" name="tipoPersonalSaludF_pacientesCitados" id="tipoPersonalSaludF_pacientesCitados">
                            <option value="">-- Seleccionar el Tipo de Personal de Salud --</option>
                            @foreach($sisTipoPersonalSalud as $key => $value_sisTipoPersonalSalud)
                              <option value="{{$value_sisTipoPersonalSalud->id}}">{{$value_sisTipoPersonalSalud->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-sm-2">
                          <select class="form-control select-2 select2" name="egresadoF_pacientesCitados" id="egresadoF_pacientesCitados">
                            <option value="">-- Seleccionar si es Egresado --</option>
                            @foreach($sisEgresado as $key => $value_sisEgresado)
                              <option value="{{$value_sisEgresado->id}}">{{$value_sisEgresado->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-sm-4">
                          <input type="text" class="form-control" name="colegiaturaF_pacientesCitados" id="colegiaturaF_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-8">
                          <label for="especialidadF_pacientesCitados">Especialidad</label>
                        </div>

                        <div class="col-sm-4">
                          <label for="rneF_pacientesCitados">RNE</label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-8">
                          <select class="form-control select-2 select2" name="especialidadF_pacientesCitados" id="especialidadF_pacientesCitados">
                            <option value="">-- Seleccionar la Especialidad --</option>
                            @foreach($sisEspecialidad as $key => $value_sisEspecialidad)
                              <option value="{{$value_sisEspecialidad->id}}">{{$value_sisEspecialidad->descripcion}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="col-sm-4">
                          <input type="text" class="form-control" name="rneF_pacientesCitados" id="rneF_pacientesCitados"
                          style="text-transform: uppercase;" readonly="true">
                        </div>
                      </div>
                    </div>

                    <div class="form-group sisInactivo_pacientesCitados" style="display:none">
                      <div class="row">
                        <div class="col-sm-12">
                          <label style="text-align:center;background:black;color:white;padding:1px;font-size:16px;width:100%;margin:0px;">Generar Fua - SIS Inactivo</label>
                        </div>
                      </div>
                    </div>

                    <div class="form-group sisInactivo_pacientesCitados" style="display:none">
                      <div class="row">
                        <div class="col-sm-6">
                          <label for="codigoOficinaF_pacientesCitados">Código de Oficina (4 dígitos)<span class="text-danger"> * </span></label>
                          <input type="text" class="form-control inputRutaNumero" name="codigoOficinaF_pacientesCitados" id="codigoOficinaF_pacientesCitados"
                          style="text-transform: uppercase;" maxlength="4">
                        </div>

                        <div class="col-sm-6">
                          <label for="codigoOperacionF_pacientesCitados">Código de Operación (7 dígitos) <span class="text-danger"> * </span></label>
                          <input type="text" class="form-control inputRutaNumero" name="codigoOperacionF_pacientesCitados" id="codigoOperacionF_pacientesCitados"
                          style="text-transform: uppercase;" maxlength="7">
                        </div>
                      </div>
                    </div>

                    <div class="form-group" style="display:none;">
                      <div class="row">
                        <div class="col-sm-12">
                          <label style="text-align:center;background:black;color:white;padding:1px;font-size:16px;width:100%;margin:0px;">Observaciones</label>
                        </div>
                      </div>
                    </div>

                    <div class="form-group" style="display:none;">
                      <div class="row">
                        <div class="col-sm-12">
                          <label for="observacionesF_pacientesCitados">Observaciones</label>
                          <textarea name="observacionesF_pacientesCitados" id="observacionesF_pacientesCitados" class="form-control"
                          style="text-transform: uppercase;width:100%;height: 50px !important;"></textarea>
                        </div>

                        <div class="col-sm-1" style="display:none;">
                            <input type="text" name="idRegistroF_pacientesCitados" id="idRegistroF_pacientesCitados">
                            <input type="text" name="modeloF_pacientesCitados" id="modeloF_pacientesCitados">
                        </div>
                      </div>
                    </div>
                </div>

                <div class="modal-footer d-flex">

                    <div id="cancelar_generarFuaF">
                      <button type="button" class="btn btn-default boton-general" data-dismiss="modal">
                        <i class="fa fa-undo" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Salir
                      </button>
                    </div>

                    <div>
                      <button type="submit" class="btn btn-success boton-general" id="guardar_generarFUAF">Generar FUA</button>
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

   $("#fechaInicio_pacientesCitados").val(currentDate);
   $("#fechaFin_pacientesCitados").val(currentDate);
</script> -->

<script type="text/javascript">
/*                 $.contextMenu({
                    selector: '#tablaPacientesCitados tbody tr',
                    callback: function(key, options) {
                        var m = "clicked: " + key;
                        window.console && console.log(m) || alert(m);
                    },
                    items: $.contextMenu.fromMenu($('#html5menu'))
                }); */

/*       $('#tablaPacientesCitados tbody').on('mousedown', 'tr', function (e) {
        if(e.which == 3)
        		{
              if ($(this).hasClass('selected')) {
		            $(this).removeClass('selected');
	            }else{
                tablaPacientesCitados.$('tr.selected').removeClass('selected');
		            $(this).addClass('selected');
		            console.log(tablaPacientesCitados.row($(this)).data());
              }
        		}
      }); */
</script>

<script type="text/javascript">
    $("#btnRecargarDatos").click(function(){
        tablaPacientesCitados.ajax.reload(null,false);
        $("#btnGenerarFUA_pacientesCitados").css("display","none");
        $("#btnRolCitas_pacientesCitados").css("display","none");
        $("#historia_pacientesCitados").val('');
        $("#tipoDocumento_pacientesCitados").val('');
        $("#documento_pacientesCitados").val('');
        $("#nombres_pacientesCitados").val('');
        $("#tipoSeguro_pacientesCitados").val('');
        $("#estadoSeguro_pacientesCitados").val('');
        $("#estadoSeguro_pacientesCitados").css({"background":"#e9ecef","color":"white","text-align":"center","font-weight":"900"});
        $("#fechaCaducidadSeguro_pacientesCitados").val('');
    });
</script>

<script type="text/javascript">
$("#historiaBD_pacientesCitados").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        let numHistoriaBD_pacientesCitados = $("#historiaBD_pacientesCitados").val();

        if (numHistoriaBD_pacientesCitados != ''){

            tablaPacientesCitados.clear().destroy();

            $("#btnGenerarFUA_pacientesCitados").css("display","none");
            $("#btnRolCitas_pacientesCitados").css("display","none");
            $("#documentoBD_pacientesCitados").val("");
            $("#tipoSeguro_pacientesCitados").val("");
            $("#estadoSeguro_pacientesCitados").val("");
            $("#fechaCaducidadSeguro_pacientesCitados").val("");
            $("#nombres_pacientesCitados").val("");
            $("#historia_pacientesCitados").val("");
            $("#tipoDocumento_pacientesCitados").val("");
            $("#documento_pacientesCitados").val("");
            $("#estadoSeguro_pacientesCitados").css({"background":"#e9ecef","color":"white","text-align":"center","font-weight":"900"});

            $.ajax({
                url: '{{ route('consultar.historiaBD_pacientesCitados') }}',
                data: {numHistoriaBD_pacientesCitados},
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
        tablaPacientesCitados = $("#tablaPacientesCitados").DataTable({
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
			        "title": 'PROGRAMACIÓN FUA-SIS DESDE CITADOS',
			        "filename": 'PROGRAMACION_FUAS',
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
                "url": '{{ route('consultar.historiaBD_pacientesCitados') }}',
                "data": {'numHistoriaBD_pacientesCitados' : $("#historiaBD_pacientesCitados").val()}
            },
            "order": [[2, "asc"]],
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
		        {"data": 'UnidadOrganica',"name": 'UnidadOrganica'},
		        {"data": 'GrupoProfesional',"name": 'GrupoProfesional',"targets": [ 12 ],"visible": false},
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
		        unidadOrganica_pacientesCitados(tablaPacientesCitados);
		        profesionales_pacientesCitados(tablaPacientesCitados);
		        grupoProfesionales_pacientesCitados(tablaPacientesCitados);
	        }
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
        $('div.dataTables_filter input').unbind();
        $("div.dataTables_filter input").keyup( function (e) {
	        if (e.keyCode == 13) {
		        tablaPacientesCitados.search(this.value).draw();
	        }
        });
        /* FIN DE BUSQUEDA POR ENTER */

        }else{
            $('#historiaBD_pacientesCitados').focus();

            tablaPacientesCitados.clear().destroy();
            $("#btnGenerarFUA_pacientesCitados").css("display","none");
            $("#btnRolCitas_pacientesCitados").css("display","none");
            $("#tipoSeguro_pacientesCitados").val("");
            $("#estadoSeguro_pacientesCitados").val("");
            $("#fechaCaducidadSeguro_pacientesCitados").val("");
            $("#nombres_pacientesCitados").val("");
            $("#historia_pacientesCitados").val("");
            $("#tipoDocumento_pacientesCitados").val("");
            $("#documento_pacientesCitados").val("");
            $("#estadoSeguro_pacientesCitados").css({"background":"#e9ecef","color":"white","text-align":"center","font-weight":"900"});

            let valorUrlAjaxHistoriaBD_pacientesCitados = '';

            if($('#fechaInicio_pacientesCitados').val() != '' || $('#fechaFin_pacientesCitados').val() != ''){
                valorUrlAjaxHistoriaBD_pacientesCitados = '{{ route('consultar.fechas') }}';
            }else{
                valorUrlAjaxHistoriaBD_pacientesCitados = ruta + '/pacientesCitados';
            }

        /*=============================================
        DataTable de Auditoria Emitidos
        =============================================*/
        tablaPacientesCitados = $("#tablaPacientesCitados").DataTable({
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
			        "title": 'PROGRAMACIÓN FUA-SIS DESDE CITADOS',
			        "filename": 'PROGRAMACION_FUAS',
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
                "url": valorUrlAjaxHistoriaBD_pacientesCitados,
                "data": { '_token' : $('input[name=_token]').val(),
                        'fechaInicio_pacientesCitados' : $('#fechaInicio_pacientesCitados').val(),
                        'fechaFin_pacientesCitados': $('#fechaFin_pacientesCitados').val()}
            },
            "order": [[2, "asc"]],
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
		        {"data": 'UnidadOrganica',"name": 'UnidadOrganica'},
		        {"data": 'GrupoProfesional',"name": 'GrupoProfesional',"targets": [ 12 ],"visible": false},
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
		        unidadOrganica_pacientesCitados(tablaPacientesCitados);
		        profesionales_pacientesCitados(tablaPacientesCitados);
		        grupoProfesionales_pacientesCitados(tablaPacientesCitados);
	        }
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaPacientesCitados.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});

$("#documentoBD_pacientesCitados").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        let numDocumentoBD_pacientesCitados = $("#documentoBD_pacientesCitados").val();

        if (numDocumentoBD_pacientesCitados != ''){

            tablaPacientesCitados.clear().destroy();

            $("#btnGenerarFUA_pacientesCitados").css("display","none");
            $("#btnRolCitas_pacientesCitados").css("display","none");
            $("#historiaBD_pacientesCitados").val("");
            $("#tipoSeguro_pacientesCitados").val("");
            $("#estadoSeguro_pacientesCitados").val("");
            $("#fechaCaducidadSeguro_pacientesCitados").val("");
            $("#nombres_pacientesCitados").val("");
            $("#historia_pacientesCitados").val("");
            $("#tipoDocumento_pacientesCitados").val("");
            $("#documento_pacientesCitados").val("");
            $("#estadoSeguro_pacientesCitados").css({"background":"#e9ecef","color":"white","text-align":"center","font-weight":"900"});

            $.ajax({
                url: '{{ route('consultar.documentoBD_pacientesCitados') }}',
                data: {numDocumentoBD_pacientesCitados},
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
        tablaPacientesCitados = $("#tablaPacientesCitados").DataTable({
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
			        "title": 'PROGRAMACIÓN FUA-SIS DESDE CITADOS',
			        "filename": 'PROGRAMACION_FUAS',
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
                "url": '{{ route('consultar.documentoBD_pacientesCitados') }}',
                "data": {'numDocumentoBD_pacientesCitados' : $("#documentoBD_pacientesCitados").val()}
            },
            "order": [[2, "asc"]],
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
		        {"data": 'UnidadOrganica',"name": 'UnidadOrganica'},
		        {"data": 'GrupoProfesional',"name": 'GrupoProfesional',"targets": [ 12 ],"visible": false},
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
		        unidadOrganica_pacientesCitados(tablaPacientesCitados);
		        profesionales_pacientesCitados(tablaPacientesCitados);
		        grupoProfesionales_pacientesCitados(tablaPacientesCitados);
	        }
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
        $('div.dataTables_filter input').unbind();
        $("div.dataTables_filter input").keyup( function (e) {
	        if (e.keyCode == 13) {
		        tablaPacientesCitados.search(this.value).draw();
	        }
        });
        /* FIN DE BUSQUEDA POR ENTER */
        }else{
            $('#documentoBD_pacientesCitados').focus();

            tablaPacientesCitados.clear().destroy();
            $("#btnGenerarFUA_pacientesCitados").css("display","none");
            $("#btnRolCitas_pacientesCitados").css("display","none");
            $("#tipoSeguro_pacientesCitados").val("");
            $("#estadoSeguro_pacientesCitados").val("");
            $("#fechaCaducidadSeguro_pacientesCitados").val("");
            $("#nombres_pacientesCitados").val("");
            $("#historia_pacientesCitados").val("");
            $("#tipoDocumento_pacientesCitados").val("");
            $("#documento_pacientesCitados").val("");
            $("#estadoSeguro_pacientesCitados").css({"background":"#e9ecef","color":"white","text-align":"center","font-weight":"900"});

            let valorUrlAjaxDocumentoBD_pacientesCitados = '';

            if($('#fechaInicio_pacientesCitados').val() != '' || $('#fechaFin_pacientesCitados').val() != ''){
                valorUrlAjaxDocumentoBD_pacientesCitados = '{{ route('consultar.fechas') }}';
            }else{
                valorUrlAjaxDocumentoBD_pacientesCitados = ruta + '/pacientesCitados';
            }

        /*=============================================
        DataTable de Auditoria Emitidos
        =============================================*/
        tablaPacientesCitados = $("#tablaPacientesCitados").DataTable({
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
			        "title": 'PROGRAMACIÓN FUA-SIS DESDE CITADOS',
			        "filename": 'PROGRAMACION_FUAS',
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
                "url": valorUrlAjaxDocumentoBD_pacientesCitados,
                "data": { '_token' : $('input[name=_token]').val(),
                        'fechaInicio_pacientesCitados' : $('#fechaInicio_pacientesCitados').val(),
                        'fechaFin_pacientesCitados': $('#fechaFin_pacientesCitados').val()}
            },
            "order": [[2, "asc"]],
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
		        {"data": 'UnidadOrganica',"name": 'UnidadOrganica'},
		        {"data": 'GrupoProfesional',"name": 'GrupoProfesional',"targets": [ 12 ],"visible": false},
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
		        unidadOrganica_pacientesCitados(tablaPacientesCitados);
		        profesionales_pacientesCitados(tablaPacientesCitados);
		        grupoProfesionales_pacientesCitados(tablaPacientesCitados);
	        }
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaPacientesCitados.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});
</script>

<script type="text/javascript">
    $("#historiaClinica_pacientesCitados").keypress(function(e) {
      if(e.which == 13) {
        e.preventDefault();

        var numHistoria = $("#historiaClinica_pacientesCitados").val();

        if (numHistoria!='') {
          /* Petición AJAX */
          $.ajax({
            url: '{{ route('consultar.historia') }}',
            data: {numHistoria},
            success: function(respuesta){
              var arreglo = JSON.parse(respuesta);

              if(arreglo == ""){
                alert("El N° de Historia Clinica no existe");
                $("#historiaClinica_pacientesCitados").val("");
                $("#apellidoPaterno_pacientesCitados").val("");
                $("#apellidoMaterno_pacientesCitados").val("");
                $("#documentoN_pacientesCitados").val("");
                $("#tipoDocumentoFL_pacientesCitados").val("");
                $("#historiaFL_pacientesCitados").val("");
                $("#sexoFL_pacientesCitados").val("");
                $("#fechaNacimientoFL_pacientesCitados").val("");
                $("#tipoSeguroFL_pacientesCitados").val("");
                $("#codigoAsegurado1FL_pacientesCitados").val("");
                $("#codigoAsegurado2FL_pacientesCitados").val("");
                $("#codigoAsegurado3FL_pacientesCitados").val("");
                $("#estadoSeguroFL_pacientesCitados").val("");
                $("#pacienteIdFL_pacientesCitados").val("");
                $("#primerNombreFL_pacientesCitados").val("");
                $("#otroNombreFL_pacientesCitados").val("");
                $("#fechaAtencion_pacientesCitados").val("");
                $("#hora_pacientesCitados").val("");
                $("#tipoDocumentoFL1_pacientesCitados").val("");
                $("#documentoN1_pacientesCitados").val("");
                $("#codigoReferenciaFL_pacientesCitados").val("");
                $("#descripcionReferenciaFL_pacientesCitados").val("");
                $("#numeroReferenciaFL_pacientesCitados").val("");
                $("#codigoPrestacional_pacientesCitados").val("").trigger('change.select2');
                $("#personal_pacientesCitados").val("").trigger('change.select2');
                $("#historiaFL_pacientesCitados").val("");
              }else{

                $("#fechaAtencion_pacientesCitados").val("");
                $("#hora_pacientesCitados").val("");
                $("#personal_pacientesCitados").val("").trigger('change.select2');
                $("#codigoPrestacional_pacientesCitados").val("").trigger('change.select2');
                $("#diagnostico_pacientesCitados").val("").trigger('change.select2');
                $("#codigoCieN_pacientesCitados").val("");
                $("#codigoCie_pacientesCitados").val("");

                for(var x=0;x<arreglo.length;x++){
                  $("#documentoN_pacientesCitados").val(arreglo[x].nroDocIdentidad);
                  $("#tipoDocumentoFL_pacientesCitados").val(arreglo[x].TipoDocIdentidad_id);
                  $("#historiaFL_pacientesCitados").val(arreglo[x].hcl_num);

/*                   if(arreglo[x].Sexo_id == 'M'){
                    $("#sexoFL_pacientesCitados").val("MASCULINO");
                  }else{
                    $("#sexoFL_pacientesCitados").val("FEMENINO");
                  } */

                  /* $("#fechaNacimientoFL_pacientesCitados").val(arreglo[x].fechaNacimiento); */
                  $("#pacienteIdFL_pacientesCitados").val(arreglo[x].id);

                  var fechaNacimiento = new Date(arreglo[x].edad);
                  var fechaActual = new Date();
                  var diferencia = fechaActual-fechaNacimiento;
                  var edad = Math.floor(diferencia/31557600000);

                  $("#edad_pacientesCitados").val(edad + " AÑOS");

                      /* Petición AJAX */
                      $.ajax({
                        url: '{{ route('consultar.sisF') }}',
                        dataType: 'json',
                        data: {
                                  tipoDocumento_pacientesCitados: $("#tipoDocumentoFL_pacientesCitados").val(),
                                  documento_pacientesCitados: $("#documentoN_pacientesCitados").val()
                              },
                        success: function(respuesta){
                            /* console.log(respuesta); */

                            let cadena = respuesta.FecNacimiento;
                            let extraida_1 = cadena.substring(0, 4);
                            let extraida_2 = cadena.substring(4, 6);
                            let extraida_3 = cadena.substring(6, 8);

                            $("#fechaNacimientoFL_pacientesCitados").val(extraida_1 + "-" + extraida_2 + "-" + extraida_3);
                            $("#tipoSeguroFL_pacientesCitados").val(respuesta.DescTipoSeguro);
                            $("#componenteFL_pacientesCitados").val(respuesta.Regimen).trigger('change.select2');

                            if(respuesta.TipoDocumento == 1){
                              $("#tipoDocumentoFL1_pacientesCitados").val('D.N.I.');
                            }else if(respuesta.TipoDocumento == 3){
                              $("#tipoDocumentoFL1_pacientesCitados").val('C.E.');
                            }else{
                              $("#tipoDocumentoFL1_pacientesCitados").val("");
                            }

                            $("#documentoN1_pacientesCitados").val(respuesta.NroDocumento);

                            if(respuesta.Estado == 'ACTIVO'){

/*                               let valorDividirContrato3 = respuesta.Contrato;

                              let pruebaNumeracion3 = valorDividirContrato3.trim().replace(/\s+/gi,'-').split('-').length;

                              if(pruebaNumeracion3 == 2){
                                var valoresDivididos3 = respuesta.Contrato.split('-', 2);

                                var valor1_3 = "";
                                var valor2_3 = valoresDivididos3[0];
                                var valor3_3 = valoresDivididos3[1];
                              } else if(pruebaNumeracion3 == 3){
                                var valoresDivididos3 = respuesta.Contrato.split('-', 3)

                                var valor1_3 = valoresDivididos3[0];
                                var valor2_3 = valoresDivididos3[1];
                                var valor3_3 = valoresDivididos3[2];
                              }else{
                                var valor1_3 = "";
                                var valor2_3 = "";
                                var valor3_3 = "";
                              } */

                              $("#sexoFL_pacientesCitados").val(respuesta.Genero);

                              if(respuesta.Genero == 1){
                                $("#sexoFL_pacientesCitados").val("MASCULINO");
                              }else if(respuesta.Genero == 0){
                                $("#sexoFL_pacientesCitados").val("FEMENINO");
                              }else{
                                $("#sexoFL_pacientesCitados").val("");
                              }

                                $("#codigoAsegurado1FL_pacientesCitados").val(respuesta.Disa);
                                $("#codigoAsegurado2FL_pacientesCitados").val(respuesta.TipoFormato);
                                $("#codigoAsegurado3FL_pacientesCitados").val(respuesta.NroContrato);
                                $("#apellidoPaterno_pacientesCitados").val(respuesta.ApePaterno);
                                $("#apellidoMaterno_pacientesCitados").val(respuesta.ApeMaterno);

                                /* CON ESTO ESTAMOS SEPARANDO LOS NOMBRES */
                                var valores_nombresFL =(respuesta.Nombres).split(" ");
                                $("#primerNombreFL_pacientesCitados").val(valores_nombresFL[0]);
                                let elementoEliminadoFL = valores_nombresFL.splice(0, 1);
                                $("#otroNombreFL_pacientesCitados").val(valores_nombresFL.join(" "));
                                /* FIN DE SEPARACIÓN DE LOS NOMBRES */
                                $("#estadoSeguroFL_pacientesCitados").css({"background":"green","color":"white","text-align":"center","font-weight":"900"});
                                $("#estadoSeguroFL_pacientesCitados").val(respuesta.Estado);
                            }else if(respuesta.Estado == 'INACTIVO'){

                              let valorDividirContrato4 = respuesta.Contrato;

                              let pruebaNumeracion4 = valorDividirContrato4.trim().replace(/\s+/gi,'-').split('-').length;

                              if(pruebaNumeracion4 == 2){
                                var valoresDivididos4 = respuesta.Contrato.split('-', 2);

                                var valor1_4 = "";
                                var valor2_4 = valoresDivididos4[0];
                                var valor3_4 = valoresDivididos4[1];
                              } else if(pruebaNumeracion4 == 3){
                                var valoresDivididos3 = respuesta.Contrato.split('-', 3)

                                var valor1_4 = valoresDivididos4[0];
                                var valor2_4 = valoresDivididos4[1];
                                var valor3_4 = valoresDivididos4[2];
                              }else{
                                var valor1_4 = "";
                                var valor2_4 = "";
                                var valor3_4 = "";
                              }

                              $("#codigoAsegurado1FL_pacientesCitados").val(valor1_4);
                              $("#codigoAsegurado2FL_pacientesCitados").val(valor2_4);
                              $("#codigoAsegurado3FL_pacientesCitados").val(valor3_4);
                              $("#apellidoPaterno_pacientesCitados").val(respuesta.ApePaterno);
                              $("#apellidoMaterno_pacientesCitados").val(respuesta.ApeMaterno);

                              /* CON ESTO ESTAMOS SEPARANDO LOS NOMBRES */
                              var valores_nombresFL =(respuesta.Nombres).split(" ");
                              $("#primerNombreFL_pacientesCitados").val(valores_nombresFL[0]);
                              let elementoEliminadoFL = valores_nombresFL.splice(0, 1);
                              $("#otroNombreFL_pacientesCitados").val(valores_nombresFL.join(" "));
                              /* FIN DE SEPARACIÓN DE LOS NOMBRES */

                              $("#estadoSeguroFL_pacientesCitados").css({"background":"red","color":"white","text-align":"center","font-weight":"900"});
                              $("#estadoSeguroFL_pacientesCitados").val(respuesta.Estado);
                            }else{
                                $("#codigoAsegurado1FL_pacientesCitados").val("");
                                $("#codigoAsegurado2FL_pacientesCitados").val("");
                                $("#codigoAsegurado3FL_pacientesCitados").val("");
                                $("#apellidoPaterno_pacientesCitados").val("");
                                $("#apellidoMaterno_pacientesCitados").val("");
                                $("#primerNombreFL_pacientesCitados").val("");
                                $("#otroNombreFL_pacientesCitados").val("");
                                $("#estadoSeguroFL_pacientesCitados").val("");
                              $("#estadoSeguroFL_pacientesCitados").css({"background":"red","color":"white","text-align":"center","font-weight":"900"});
                            }

                            /* $("#estadoSeguroFL_pacientesCitados").val(respuesta.estado); */
                        },

                        error: function(jqXHR,textStatus,errorThrown){
                            console.error(textStatus + " " + errorThrown);
                        }
                      });
                      /* Fin de Petición AJAX */
                }

                var idPaciente = $("#pacienteIdFL_pacientesCitados").val();
                /* console.log(idPaciente); */

                /* INICIO REFERENCIAS CONSULTAS */
                    $.ajax({
                      url: '{{ route('consultar.referencias') }}',
                      data: {idPaciente},
                      success: function(respuesta){
                        /* console.log("respuesta",respuesta); */

                        var arregloReferencia = JSON.parse(respuesta);
                        for(var x=0;x<arregloReferencia.length;x++){
                          $("#codigoReferenciaFL_pacientesCitados").val(arregloReferencia[x].estb2_cod);
                          $("#descripcionReferenciaFL_pacientesCitados").val(arregloReferencia[x].descripcion);
                          $("#numeroReferenciaFL_pacientesCitados").val(arregloReferencia[x].ref_rec_hoja_nro);
                        }
                      },

                      error: function(jqXHR,textStatus,errorThrown){
                        console.error(textStatus + " " + errorThrown);
                      }
                    });
                /* FIN DE AJAX PARA EXTRAER VALORES DE REFERENCIA */
              }
            },

            error: function(jqXHR,textStatus,errorThrown){
                console.error(textStatus + " " + errorThrown);
            }
          });
        /* Fin de Petición AJAX */
        }else{
          alert('Escriba el N° de Historia.!');
          $('#historiaClinica_pacientesCitados').focus();
          $("#historiaClinica_pacientesCitados").val("");
          $("#apellidoPaterno_pacientesCitados").val("");
          $("#apellidoMaterno_pacientesCitados").val("");
          $("#documentoN_pacientesCitados").val("");
          $("#tipoDocumentoFL_pacientesCitados").val("");
          $("#historiaFL_pacientesCitados").val("");
          $("#sexoFL_pacientesCitados").val("");
          $("#fechaNacimientoFL_pacientesCitados").val("");
          $("#tipoSeguroFL_pacientesCitados").val("");
          $("#codigoAsegurado1FL_pacientesCitados").val("");
          $("#codigoAsegurado2FL_pacientesCitados").val("");
          $("#codigoAsegurado3FL_pacientesCitados").val("");
          $("#estadoSeguroFL_pacientesCitados").val("");
          $("#pacienteIdFL_pacientesCitados").val("");
          $("#primerNombreFL_pacientesCitados").val("");
          $("#otroNombreFL_pacientesCitados").val("");
          $("#fechaAtencion_pacientesCitados").val("");
          $("#hora_pacientesCitados").val("");
          $("#tipoDocumentoFL1_pacientesCitados").val("");
          $("#documentoN1_pacientesCitados").val("");
          $("#codigoReferenciaFL_pacientesCitados").val("");
          $("#descripcionReferenciaFL_pacientesCitados").val("");
          $("#numeroReferenciaFL_pacientesCitados").val("");
          $("#codigoPrestacional_pacientesCitados").val("").trigger('change.select2');
          $("#personal_pacientesCitados").val("").trigger('change.select2');
          $("#historiaFL_pacientesCitados").val("");
        }
      }
    });

    $("#documentoN_pacientesCitados").keypress(function(e) {
      if(e.which == 13) {
        e.preventDefault();
        var numDocumento = $("#documentoN_pacientesCitados").val();

        if (numDocumento!='') {
          /* Petición AJAX */
          $.ajax({
            url: '{{ route('consultar.documento') }}',
            data: {numDocumento},
            success: function(respuesta){
              /* console.log(respuesta); */
              var arreglo = JSON.parse(respuesta);

              if(arreglo == ""){
                alert("El N° de Documento no existe");
                $("#historiaClinica_pacientesCitados").val("");
                $("#apellidoPaterno_pacientesCitados").val("");
                $("#apellidoMaterno_pacientesCitados").val("");
                $("#documentoN_pacientesCitados").val("");
                $("#tipoDocumentoFL_pacientesCitados").val("");
                $("#historiaFL_pacientesCitados").val("");
                $("#sexoFL_pacientesCitados").val("");
                $("#fechaNacimientoFL_pacientesCitados").val("");
                $("#tipoSeguroFL_pacientesCitados").val("");
                $("#codigoAsegurado1FL_pacientesCitados").val("");
                $("#codigoAsegurado2FL_pacientesCitados").val("");
                $("#codigoAsegurado3FL_pacientesCitados").val("");
                $("#estadoSeguroFL_pacientesCitados").val("");
                $("#pacienteIdFL_pacientesCitados").val("");
                $("#primerNombreFL_pacientesCitados").val("");
                $("#otroNombreFL_pacientesCitados").val("");
                $("#fechaAtencion_pacientesCitados").val("");
                $("#hora_pacientesCitados").val("");
                $("#tipoDocumentoFL1_pacientesCitados").val("");
                $("#documentoN1_pacientesCitados").val("");
                $("#codigoReferenciaFL_pacientesCitados").val("");
                $("#descripcionReferenciaFL_pacientesCitados").val("");
                $("#numeroReferenciaFL_pacientesCitados").val("");
                $("#codigoPrestacional_pacientesCitados").val("").trigger('change.select2');
                $("#personal_pacientesCitados").val("").trigger('change.select2');
                $("#historiaFL_pacientesCitados").val("");
              }else{

                $("#fechaAtencion_pacientesCitados").val("");
                $("#hora_pacientesCitados").val("");
                $("#personal_pacientesCitados").val("").trigger('change.select2');
                $("#codigoPrestacional_pacientesCitados").val("").trigger('change.select2');
                $("#diagnostico_pacientesCitados").val("").trigger('change.select2');
                $("#codigoCieN_pacientesCitados").val("");
                $("#codigoCie_pacientesCitados").val("");

                for(var x=0;x<arreglo.length;x++){
                  $("#historiaClinica_pacientesCitados").val(arreglo[x].hcl_num);
                  $("#tipoDocumentoFL_pacientesCitados").val(arreglo[x].TipoDocIdentidad_id);
                  $("#historiaFL_pacientesCitados").val(arreglo[x].hcl_num);

/*                   if(arreglo[x].Sexo_id == 'M'){
                    $("#sexoFL_pacientesCitados").val("MASCULINO");
                  }else{
                    $("#sexoFL_pacientesCitados").val("FEMENINO");
                  } */

                  /* $("#fechaNacimientoFL_pacientesCitados").val(arreglo[x].fechaNacimiento); */
                  $("#pacienteIdFL_pacientesCitados").val(arreglo[x].id);

                  var fechaNacimiento = new Date(arreglo[x].edad);
                  var fechaActual = new Date();
                  var diferencia = fechaActual-fechaNacimiento;
                  var edad = Math.floor(diferencia/31557600000);

                  $("#edad_pacientesCitados").val(edad + " AÑOS");

                      /* Petición AJAX */
                      $.ajax({
                        url: '{{ route('consultar.sisF') }}', //Hacia donde nos queremos dirigir
                        dataType: 'json',
                        data: {
                                  tipoDocumento_pacientesCitados: $("#tipoDocumentoFL_pacientesCitados").val(),
                                  documento_pacientesCitados: $("#documentoN_pacientesCitados").val()
                              },
                        success: function(respuesta){
                            /* console.log(respuesta); */

                            let cadena = respuesta.FecNacimiento;
                            let extraida_1 = cadena.substring(0, 4);
                            let extraida_2 = cadena.substring(4, 6);
                            let extraida_3 = cadena.substring(6, 8);

                            $("#fechaNacimientoFL_pacientesCitados").val(extraida_1 + "-" + extraida_2 + "-" + extraida_3);
                            $("#tipoSeguroFL_pacientesCitados").val(respuesta.DescTipoSeguro);
                            $("#componenteFL_pacientesCitados").val(respuesta.Regimen).trigger('change.select2');

                            if(respuesta.TipoDocumento == 1){
                              $("#tipoDocumentoFL1_pacientesCitados").val('D.N.I.');
                            }else if(respuesta.TipoDocumento == 3){
                              $("#tipoDocumentoFL1_pacientesCitados").val('C.E.');
                            }else{
                              $("#tipoDocumentoFL1_pacientesCitados").val("");
                            }

                            $("#documentoN1_pacientesCitados").val(respuesta.NroDocumento);

                            if(respuesta.Estado == 'ACTIVO'){

/*                               let valorDividirContrato5 = respuesta.Contrato;

                              let pruebaNumeracion5 = valorDividirContrato5.trim().replace(/\s+/gi,'-').split('-').length;

                              if(pruebaNumeracion5 == 2){
                                var valoresDivididos5 = respuesta.Contrato.split('-', 2);

                                var valor1_5 = "";
                                var valor2_5 = valoresDivididos5[0];
                                var valor3_5 = valoresDivididos5[1];
                              } else if(pruebaNumeracion5 == 3){
                                var valoresDivididos3 = respuesta.Contrato.split('-', 3)

                                var valor1_5 = valoresDivididos5[0];
                                var valor2_5 = valoresDivididos5[1];
                                var valor3_5 = valoresDivididos5[2];
                              }else{
                                var valor1_5 = "";
                                var valor2_5 = "";
                                var valor3_5 = "";
                              } */

                              $("#sexoFL_pacientesCitados").val(respuesta.Genero);

                              if(respuesta.Genero == 1){
                                $("#sexoFL_pacientesCitados").val("MASCULINO");
                              }else if(respuesta.Genero == 0){
                                $("#sexoFL_pacientesCitados").val("FEMENINO");
                              }else{
                                $("#sexoFL_pacientesCitados").val("");
                              }

                              $("#codigoAsegurado1FL_pacientesCitados").val(respuesta.Disa);
                              $("#codigoAsegurado2FL_pacientesCitados").val(respuesta.TipoFormato);
                              $("#codigoAsegurado3FL_pacientesCitados").val(respuesta.NroContrato);
                              $("#apellidoPaterno_pacientesCitados").val(respuesta.ApePaterno);
                              $("#apellidoMaterno_pacientesCitados").val(respuesta.ApeMaterno);

                              /* CON ESTO ESTAMOS SEPARANDO LOS NOMBRES */
                              var valores_nombresFL =(respuesta.Nombres).split(" ");
                              $("#primerNombreFL_pacientesCitados").val(valores_nombresFL[0]);
                              let elementoEliminadoFL = valores_nombresFL.splice(0, 1);
                              $("#otroNombreFL_pacientesCitados").val(valores_nombresFL.join(" "));
                              /* FIN DE SEPARACIÓN DE LOS NOMBRES */

                              $("#estadoSeguroFL_pacientesCitados").css({"background":"green","color":"white","text-align":"center","font-weight":"900"});
                              $("#estadoSeguroFL_pacientesCitados").val(respuesta.Estado);
                            }else if(respuesta.Estado == 'INACTIVO'){

                              let valorDividirContrato6 = respuesta.Contrato;

                              let pruebaNumeracion6 = valorDividirContrato6.trim().replace(/\s+/gi,'-').split('-').length;

                              if(pruebaNumeracion6 == 2){
                                var valoresDivididos6 = respuesta.Contrato.split('-', 2);

                                var valor1_6 = "";
                                var valor2_6 = valoresDivididos6[0];
                                var valor3_6 = valoresDivididos6[1];
                              } else if(pruebaNumeracion6 == 3){
                                var valoresDivididos3 = respuesta.Contrato.split('-', 3)

                                var valor1_6 = valoresDivididos6[0];
                                var valor2_6 = valoresDivididos6[1];
                                var valor3_6 = valoresDivididos6[2];
                              }else{
                                var valor1_6 = "";
                                var valor2_6 = "";
                                var valor3_6 = "";
                              }
                              $("#codigoAsegurado1FL_pacientesCitados").val(valor1_6);
                              $("#codigoAsegurado2FL_pacientesCitados").val(valor2_6);
                              $("#codigoAsegurado3FL_pacientesCitados").val(valor3_6);
                              $("#apellidoPaterno_pacientesCitados").val(respuesta.ApePaterno);
                              $("#apellidoMaterno_pacientesCitados").val(respuesta.ApeMaterno);

                              /* CON ESTO ESTAMOS SEPARANDO LOS NOMBRES */
                              var valores_nombresFL =(respuesta.Nombres).split(" ");
                              $("#primerNombreFL_pacientesCitados").val(valores_nombresFL[0]);
                              let elementoEliminadoFL = valores_nombresFL.splice(0, 1);
                              $("#otroNombreFL_pacientesCitados").val(valores_nombresFL.join(" "));
                              /* FIN DE SEPARACIÓN DE LOS NOMBRES */

                              $("#estadoSeguroFL_pacientesCitados").css({"background":"red","color":"white","text-align":"center","font-weight":"900"});
                              $("#estadoSeguroFL_pacientesCitados").val(respuesta.Estado);
                            }else{
                              $("#codigoAsegurado1FL_pacientesCitados").val("");
                              $("#codigoAsegurado2FL_pacientesCitados").val("");
                              $("#codigoAsegurado3FL_pacientesCitados").val("");
                              $("#apellidoPaterno_pacientesCitados").val("");
                              $("#apellidoMaterno_pacientesCitados").val("");
                              $("#primerNombreFL_pacientesCitados").val("");
                              $("#otroNombreFL_pacientesCitados").val("");
                              $("#estadoSeguroFL_pacientesCitados").val("");
                              $("#estadoSeguroFL_pacientesCitados").css({"background":"red","color":"white","text-align":"center","font-weight":"900"});
                            }

                            /* $("#estadoSeguroFL_pacientesCitados").val(respuesta.estado); */
                        },

                        error: function(jqXHR,textStatus,errorThrown){
                            console.error(textStatus + " " + errorThrown);
                        }
                      });
                      /* Fin de Petición AJAX */
                }

                var idPaciente = $("#pacienteIdFL_pacientesCitados").val();
                /* console.log(idPaciente); */

                /* INICIO REFERENCIAS CONSULTAS */
                    $.ajax({
                      url: '{{ route('consultar.referencias') }}',
                      data: {idPaciente},
                      success: function(respuesta){
                        /* console.log("respuesta",respuesta); */

                        var arregloReferencia = JSON.parse(respuesta);
                        for(var x=0;x<arregloReferencia.length;x++){
                          $("#codigoReferenciaFL_pacientesCitados").val(arregloReferencia[x].estb2_cod);
                          $("#descripcionReferenciaFL_pacientesCitados").val(arregloReferencia[x].descripcion);
                          $("#numeroReferenciaFL_pacientesCitados").val(arregloReferencia[x].ref_rec_hoja_nro);
                        }
                      },

                      error: function(jqXHR,textStatus,errorThrown){
                        console.error(textStatus + " " + errorThrown);
                      }
                    });
                /* FIN DE AJAX PARA EXTRAER VALORES DE REFERENCIA */
              }
            },

            error: function(jqXHR,textStatus,errorThrown){
                console.error(textStatus + " " + errorThrown);
            }
          })
        /* Fin de Petición AJAX */
        }else{
          alert('Escriba el N° de Documento.!');
          $('#documentoN_pacientesCitados').focus();
          $("#historiaClinica_pacientesCitados").val("");
          $("#apellidoPaterno_pacientesCitados").val("");
          $("#apellidoMaterno_pacientesCitados").val("");
          $("#documentoN_pacientesCitados").val("");
          $("#tipoDocumentoFL_pacientesCitados").val("");
          $("#historiaFL_pacientesCitados").val("");
          $("#sexoFL_pacientesCitados").val("");
          $("#fechaNacimientoFL_pacientesCitados").val("");
          $("#tipoSeguroFL_pacientesCitados").val("");
          $("#codigoAsegurado1FL_pacientesCitados").val("");
          $("#codigoAsegurado2FL_pacientesCitados").val("");
          $("#codigoAsegurado3FL_pacientesCitados").val("");
          $("#estadoSeguroFL_pacientesCitados").val("");
          $("#pacienteIdFL_pacientesCitados").val("");
          $("#primerNombreFL_pacientesCitados").val("");
          $("#otroNombreFL_pacientesCitados").val("");
          $("#fechaAtencion_pacientesCitados").val("");
          $("#hora_pacientesCitados").val("");
          $("#tipoDocumentoFL1_pacientesCitados").val("");
          $("#documentoN1_pacientesCitados").val("");
          $("#codigoReferenciaFL_pacientesCitados").val("");
          $("#descripcionReferenciaFL_pacientesCitados").val("");
          $("#numeroReferenciaFL_pacientesCitados").val("");
          $("#codigoPrestacional_pacientesCitados").val("").trigger('change.select2');
          $("#personal_pacientesCitados").val("").trigger('change.select2');
          $("#historiaFL_pacientesCitados").val("");
        }
      }
    });

    $("#codigoCieNF_pacientesCitados").keypress(function(e) {
      if(e.which == 13) {
        e.preventDefault();

        var CodigoCie = $("#codigoCieNF_pacientesCitados").val();

        if (CodigoCie != '') {
          /* Petición AJAX */
          $.ajax({
            url: '{{ route('consultar.codigoCie') }}',
            data: {CodigoCie},
            success: function(respuesta){

              var arreglo = JSON.parse(respuesta);

              if(arreglo == ""){
                alert("El Código CIE-10 no existe");
                $("#codigoCieNF_pacientesCitados").val("");
                $("#codigoCieF_pacientesCitados").val("");
              }else{
                for(var x=0;x<arreglo.length;x++){
                    $("#codigoCieNF_pacientesCitados").val(arreglo[x].cie_cod);
                    $("#codigoCieF_pacientesCitados").val(arreglo[x].cie_desc);
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
          $('#codigoCieNF_pacientesCitados').focus();
          $("#codigoCieNF_pacientesCitados").val("");
          $("#codigoCieF_pacientesCitados").val("");
        }
      }
    });

    $("#codigoCieN_pacientesCitados").keypress(function(e) {
      if(e.which == 13) {
        e.preventDefault();

        var CodigoCie = $("#codigoCieN_pacientesCitados").val();

        if (CodigoCie != '') {
          /* Petición AJAX */
          $.ajax({
            url: '{{ route('consultar.codigoCie') }}',
            data: {CodigoCie},
            success: function(respuesta){

              var arreglo = JSON.parse(respuesta);

              if(arreglo == ""){
                alert("El Código CIE-10 no existe");
                $("#codigoCieN_pacientesCitados").val("");
                $("#codigoCie_pacientesCitados").val("");
              }else{
                for(var x=0;x<arreglo.length;x++){
                    $("#codigoCieN_pacientesCitados").val(arreglo[x].cie_cod);
                    $("#codigoCie_pacientesCitados").val(arreglo[x].cie_desc);
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
          $('#codigoCieN_pacientesCitados').focus();
          $("#codigoCieN_pacientesCitados").val("");
          $("#codigoCie_pacientesCitados").val("");
        }
      }
    });
</script>

<script type="text/javascript">
  $('#frmVerRolCitas').submit(function(e){
    e.preventDefault();

    console.log($("#idCab_pacientesCitados").val());

    /* Boton cerrar del modal */
    $('#botonCerrar').click(function(){
      var tablaRolCitas = $("#tablaRolCitas").DataTable();
      tablaRolCitas.clear().destroy();
    });
    /* Fin boton cerrar del modal */

    /* Petición AJAX */
    $.ajax({
      url: '{{ route('consultar.rol') }}',
      data: $("#frmVerRolCitas").serialize(),
      success: function(respuesta){
        /* console.log("respuesta",respuesta); */
      },

      error: function(jqXHR,textStatus,errorThrown){
        console.error(textStatus + " " + errorThrown);
      }
    });
    /* Fin de Petición AJAX */

    tablaRolCitas = $("#tablaRolCitas").DataTable({
            retrieve: true,
            processing: true,
            serverSide: true,
	          dom: 'Bfrtip',
	          buttons: [
              {
			          //Botón para Excel
			          extend: 'excel',
			          footer: false,
			          title: 'ROL DE CITAS',
			          filename: 'ROL_CITAS',

			          //Aquí es donde generas el botón personalizado
			          text: "<button class='btn btn-success btn-sm boton-general' style='float:left;'><i class='fas fa-file-excel'></i> Exportar a Excel</button>"
		          }
	          ],
            ajax: {
                url: '{{ route('consultar.rol') }}',
                data: {'_token' : $('input[name=_token]').val(),
                       'idCab_pacientesCitados' : $('#idCab_pacientesCitados').val()}
            },

            "columnDefs":[
              {
                "searchable": true,
                "orderable" : true,
                "targets" : 0
              }
              ],

            "order": [[0, "asc"]],

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
                  data:'abreviatura',
                  name:'abreviatura',
                  render: function (item) {
				            return item.toUpperCase();
			            }
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

  	        } ,

	          deferRender:true,
	          scroller:true,
	          scrollCollapse:true,
	          paging:true,
	          scrollY:"405PX",
	          scrollX:"100%"
    });

    /* tablaRolCitas.draw(); */

  });

</script>

<script type="text/javascript">

$('#frmFechas').submit(function(e){

          e.preventDefault();

          tablaPacientesCitados.clear().destroy();

          /* BOTONES Y LA CONSULTA DEL SIS (PANTALLA) */
          $("#btnGenerarFUA_pacientesCitados").css("display","none");
          $("#btnRolCitas_pacientesCitados").css("display","none");
          $("#tipoSeguro_pacientesCitados").val("");
          $("#estadoSeguro_pacientesCitados").val("");
          $("#fechaCaducidadSeguro_pacientesCitados").val("");
          $("#nombres_pacientesCitados").val("");
          $("#historia_pacientesCitados").val("");
          $("#tipoDocumento_pacientesCitados").val("");
          $("#documento_pacientesCitados").val("");
          $("#estadoSeguro_pacientesCitados").css({"background":"#e9ecef","color":"white","text-align":"center","font-weight":"900"});

          /* Petición AJAX */
          $.ajax({
            url: '{{ route('consultar.fechas') }}',
            data: $("#frmFechas").serialize(),
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
        tablaPacientesCitados = $("#tablaPacientesCitados").DataTable({
            "processing": false,
            "serverSide": false,
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
			        "title": 'PROGRAMACIÓN FUA-SIS DESDE CITADOS',
			        "filename": 'PROGRAMACION_FUAS',
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
                "url": '{{ route('consultar.fechas') }}',
                "data":{'_token' : $('input[name=_token]').val(),
                        'fechaInicio_pacientesCitados' : $('#fechaInicio_pacientesCitados').val(),
                        'fechaFin_pacientesCitados': $('#fechaFin_pacientesCitados').val()}
            },

            "order": [[2, "asc"]],

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
		        {"data": 'UnidadOrganica',"name": 'UnidadOrganica'},
		        {"data": 'GrupoProfesional',"name": 'GrupoProfesional',"targets": [ 12 ],"visible": false},
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
		        unidadOrganica_pacientesCitados(tablaPacientesCitados);
		        profesionales_pacientesCitados(tablaPacientesCitados);
		        grupoProfesionales_pacientesCitados(tablaPacientesCitados);
	        }
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
        $('div.dataTables_filter input').unbind();
        $("div.dataTables_filter input").keyup( function (e) {
	        if (e.keyCode == 13) {
		        tablaPacientesCitados.search(this.value).draw();
	        }
        });
        /* FIN DE BUSQUEDA POR ENTER */
});

</script>

<script>
//Seleccionar
$('#tablaPacientesCitados tbody').on('click', 'tr', function () {
	if ($(this).hasClass('selected')) {
		$(this).removeClass('selected');
    $("#btnGenerarFUA_pacientesCitados").css("display","none");
    $("#btnRolCitas_pacientesCitados").css("display","none");
    $("#tipoSeguro_pacientesCitados").val("");
    $("#estadoSeguro_pacientesCitados").val("");
    $("#fechaCaducidadSeguro_pacientesCitados").val("");
    $("#nombres_pacientesCitados").val("");
    $("#historia_pacientesCitados").val("");
    $("#tipoDocumento_pacientesCitados").val("");
    $("#documento_pacientesCitados").val("");
    $("#estadoSeguro_pacientesCitados").css({"background":"#e9ecef","color":"white","text-align":"center","font-weight":"900"});
	}
	else {
    var screen = $('#loading-screen');
    configureLoadingScreen(screen);

		tablaPacientesCitados.$('tr.selected').removeClass('selected');
		$(this).addClass('selected');
		console.log(tablaPacientesCitados.row($(this)).data());

    //Inicio Limpieza valores pegados
    $("#btnGenerarFUA_pacientesCitados").css("display","none");
    $("#tipoSeguro_pacientesCitados").val('');
    $("#estadoSeguro_pacientesCitados").val('');
    $("#estadoSeguro_pacientesCitados").css({"background":"#e9ecef","color":"white","text-align":"center","font-weight":"900"});
    $("#fechaCaducidadSeguro_pacientesCitados").val('');
    $("#nombres_pacientesCitados").val('');
    $("#historia_pacientesCitados").val('');
    $("#tipoDocumento_pacientesCitados").val('');
    $("#documento_pacientesCitados").val('');
    $("#apellidoPaternoF_pacientesCitados").val('');
    $("#apellidoMaternoF_pacientesCitados").val('');
    $("#primerNombreF_pacientesCitados").val('');
    $("#otroNombreF_pacientesCitados").val('');
    $("#sexoF_pacientesCitados").val('');
    $("#fechaNacimientoF_pacientesCitados").val('');
    $("#codigoAsegurado1F_pacientesCitados").val('');
    $("#codigoAsegurado2F_pacientesCitados").val('');
    $("#codigoAsegurado3F_pacientesCitados").val('');
    $("#tipoTablaSis_pacientesCitadosC").val('');
    $("#idNumRegSis_pacientesCitadosC").val('');
    $("#idPlanSis_pacientesCitadosC").val('');
    $("#idUbigeoSis_pacientesCitadosC").val('');
    $("#correlativoSis_pacientesCitadosC").val('');
    $("#componenteF_pacientesCitados").val('').trigger('change.select2');
    //fin Limpieza de valores

    $.ajax({
      url: '{{ route('consultar.sisF') }}', //Hacia donde nos queremos dirigir
      dataType: 'json',
      data: {
                tipoDocumento_pacientesCitados: tablaPacientesCitados.row($(this)).data()["TipoDocumento"],
                documento_pacientesCitados: tablaPacientesCitados.row($(this)).data()["NumeroDocumento"]
            },
      success: function(respuesta){
                  console.log(respuesta);
                  $("#tipoSeguro_pacientesCitados").val(respuesta.DescTipoSeguro);
                  $("#componenteF_pacientesCitados").val(respuesta.Regimen).trigger('change.select2');

                  if(respuesta.Estado == 'ACTIVO'){
                      let cadena = respuesta.FecNacimiento;
                      let extraida_1 = cadena.substring(0, 4);
                      let extraida_2 = cadena.substring(4, 6);
                      let extraida_3 = cadena.substring(6, 8);

                      $("#fechaNacimientoF_pacientesCitados").val(extraida_1 + "-" + extraida_2 + "-" + extraida_3);
                      $("#sexoF_pacientesCitados").val(respuesta.Genero);

                      if(respuesta.Genero == 1){
                        $("#sexoF_pacientesCitados").val("MASCULINO");
                      }else if(respuesta.Genero == 0){
                        $("#sexoF_pacientesCitados").val("FEMENINO");
                      }else{
                        $("#sexoF_pacientesCitados").val("");
                      }

                      $("#codigoAsegurado1F_pacientesCitados").val(respuesta.Disa);
                      $("#codigoAsegurado2F_pacientesCitados").val(respuesta.TipoFormato);
                      $("#codigoAsegurado3F_pacientesCitados").val(respuesta.NroContrato);

                      if($.isEmptyObject(respuesta.Correlativo) != true){
                        $("#correlativoSis_pacientesCitadosC").val(respuesta.Correlativo);
                      }else{
                        $("#correlativoSis_pacientesCitadosC").val("");
                      }

                      if($.isEmptyObject(respuesta.ApeMaterno) != true){
                        $("#apellidoMaternoF_pacientesCitados").val(respuesta.ApeMaterno);
                        $("#nombres_pacientesCitados").val(respuesta.Nombres + ' ' + respuesta.ApePaterno + ' ' + respuesta.ApeMaterno);
                      }else{
                        $("#apellidoMaternoF_pacientesCitados").val("");
                        $("#nombres_pacientesCitados").val(respuesta.Nombres + ' ' + respuesta.ApePaterno);
                      }

                      $("#tipoTablaSis_pacientesCitadosC").val(respuesta.Tabla);
                      $("#idNumRegSis_pacientesCitadosC").val(respuesta.IdNumReg);
                      $("#idPlanSis_pacientesCitadosC").val(respuesta.IdPlan);
                      $("#idUbigeoSis_pacientesCitadosC").val(respuesta.IdUbigeo);
                      $("#apellidoPaternoF_pacientesCitados").val(respuesta.ApePaterno);

                      /* CON ESTO ESTAMOS SEPARANDO LOS NOMBRES */
                      var valores_nombresF =(respuesta.Nombres).split(" ");
                      $("#primerNombreF_pacientesCitados").val(valores_nombresF[0]);
                      let elementoEliminado = valores_nombresF.splice(0, 1);
                      $("#otroNombreF_pacientesCitados").val(valores_nombresF.join(" "));
                      /* FIN DE SEPARACIÓN DE LOS NOMBRES */

                      $("#estadoSeguro_pacientesCitados").css({"background":"green","color":"white","text-align":"center","font-weight":"900"});
                      $("#estadoSeguro_pacientesCitados").val(respuesta.Estado);

                      var formattedDate = moment(respuesta.FecCaducidad).format('DD-MM-YYYY');

                      if($.isEmptyObject(respuesta.FecCaducidad) != true){
                        $("#fechaCaducidadSeguro_pacientesCitados").val(formattedDate);
                        $("#fechaCaducidadSeguro_pacientesCitados").css({"background":"green","color":"white","text-align":"center","font-weight":"900"});
                      }else{
                        $("#fechaCaducidadSeguro_pacientesCitados").val("");
                      }

                      $(".sisInactivo_pacientesCitados").css("display","none");
                      $("#btnGenerarFUA_pacientesCitados").css("display","block");

                  }else if(respuesta.Estado == 'INACTIVO'){

                    let cadena = respuesta.FecNacimiento;
                    let extraida_1 = cadena.substring(0, 4);
                    let extraida_2 = cadena.substring(4, 6);
                    let extraida_3 = cadena.substring(6, 8);

                    $("#fechaNacimientoF_pacientesCitados").val(extraida_1 + "-" + extraida_2 + "-" + extraida_3);
                    $("#btnGenerarFUA_pacientesCitados").css("display","block");
                    $("#codigoOficinaF_pacientesCitados").prop('required',true);
                    $("#codigoOperacionF_pacientesCitados").prop('required',true);
                    $(".sisInactivo_pacientesCitados").css("display","block");

                    let valorDividirContrato2 = respuesta.Contrato;

                    let pruebaNumeracion2 = valorDividirContrato2.trim().replace(/\s+/gi,'-').split('-').length;

                    if(pruebaNumeracion2 == 2){
                      var valoresDivididos2 = respuesta.Contrato.split('-', 2);

                      var valor1_2 = "";
                      var valor2_2 = valoresDivididos2[0];
                      var valor3_2 = valoresDivididos2[1];
                    } else if(pruebaNumeracion2 == 3){
                      var valoresDivididos2 = respuesta.Contrato.split('-', 3)

                      var valor1_2 = valoresDivididos2[0];
                      var valor2_2 = valoresDivididos2[1];
                      var valor3_2 = valoresDivididos2[2];
                    }else{
                      var valor1_2 = "";
                      var valor2_2 = "";
                      var valor3_2 = "";
                    }

                    $("#codigoAsegurado1F_pacientesCitados").val(valor1_2);
                    $("#codigoAsegurado2F_pacientesCitados").val(valor2_2);
                    $("#codigoAsegurado3F_pacientesCitados").val(valor3_2);

                    if($.isEmptyObject(respuesta.Correlativo) != true){
                        $("#correlativoSis_pacientesCitadosC").val(respuesta.Correlativo);
                    }else{
                        $("#correlativoSis_pacientesCitadosC").val("");
                    }

                    if($.isEmptyObject(respuesta.ApeMaterno) != true){
                        $("#apellidoMaternoF_pacientesCitados").val(respuesta.ApeMaterno);
                        $("#nombres_pacientesCitados").val(respuesta.Nombres + ' ' + respuesta.ApePaterno + ' ' + respuesta.ApeMaterno);
                    }else{
                        $("#apellidoMaternoF_pacientesCitados").val("");
                        $("#nombres_pacientesCitados").val(respuesta.Nombres + ' ' + respuesta.ApePaterno);
                    }

                    $("#tipoTablaSis_pacientesCitadosC").val(respuesta.Tabla);
                    $("#idNumRegSis_pacientesCitadosC").val(respuesta.IdNumReg);
                    $("#idPlanSis_pacientesCitadosC").val(respuesta.IdPlan);
                    $("#idUbigeoSis_pacientesCitadosC").val(respuesta.IdUbigeo);
                    $("#apellidoPaternoF_pacientesCitados").val(respuesta.ApePaterno);

                    /* CON ESTO ESTAMOS SEPARANDO LOS NOMBRES */
                    var valores_nombresF =(respuesta.Nombres).split(" ");
                    $("#primerNombreF_pacientesCitados").val(valores_nombresF[0]);
                    let elementoEliminado = valores_nombresF.splice(0, 1); /* CON ESTO BORRAMOS UN ELEMENTO QUE NO QUEREMOS MOSTRAR */
                    $("#otroNombreF_pacientesCitados").val(valores_nombresF.join(" "));
                    /* FIN DE SEPARACIÓN DE LOS NOMBRES */

                    $("#estadoSeguro_pacientesCitados").css({"background":"red","color":"white","text-align":"center","font-weight":"900"});
                    $("#estadoSeguro_pacientesCitados").val(respuesta.Estado);

                    var formattedDate = moment(respuesta.FecCaducidad).format('DD-MM-YYYY');

                    if(respuesta.fecCaducidad != ''){
                      $("#fechaCaducidadSeguro_pacientesCitados").val(formattedDate);
                      $("#fechaCaducidadSeguro_pacientesCitados").css({"background":"green","color":"white","text-align":"center","font-weight":"900"});
                    }else{
                      $("#fechaCaducidadSeguro_pacientesCitados").val("");
                    }

                  }
                  else{
                  $("#codigoAsegurado1F_pacientesCitados").val("");
                  $("#codigoAsegurado2F_pacientesCitados").val("");
                  $("#codigoAsegurado3F_pacientesCitados").val("");
                  $("#correlativoSis_pacientesCitadosC").val("");
                  $("#tipoTablaSis_pacientesCitadosC").val("");
                  $("#idNumRegSis_pacientesCitadosC").val("");
                  $("#idPlanSis_pacientesCitadosC").val("");
                  $("#idUbigeoSis_pacientesCitadosC").val("");
                  $("#apellidoPaternoF_pacientesCitados").val("");
                  $("#apellidoMaternoF_pacientesCitados").val("");
                  $("#primerNombreF_pacientesCitados").val("");
                  $("#otroNombreF_pacientesCitados").val("");
                  $("#nombres_pacientesCitados").val("");
                  $("#historia_pacientesCitados").val("");
                  $("#tipoDocumento_pacientesCitados").val("");
                  $("#documento_pacientesCitados").val("");
                  $("#tipoSeguro_pacientesCitados").val("");
                  $("#estadoSeguro_pacientesCitados").val("");
                  $("#fechaCaducidadSeguro_pacientesCitados").val("");
                  $("#estadoSeguro_pacientesCitados").css({"background":"#e9ecef","color":"white","text-align":"center","font-weight":"900"});
                  $("#fechaCaducidadSeguro_pacientesCitados").css({"background":"#e9ecef","color":"white","text-align":"center","font-weight":"900"});
                  $(".sisInactivo_pacientesCitados").css("display","none");
                  $("#btnGenerarFUA_pacientesCitados").css("display","none");
                  $("#fechaNacimientoF_pacientesCitados").val("");
                  }
      },

      error: function(jqXHR,textStatus,errorThrown){
          console.error(textStatus + " " + errorThrown);
      }
    });

    /* PARA QUE EL VALOR SIEMPRE SE MANTENGA EN LA POSICIÓN CUANDO SE REALICE UN CAMBIO */
    $("#componenteF_pacientesCitados").val(1).trigger('change.select2');
    $('#componenteF_pacientesCitados').attr('readonly','readonly');
    $("#conceptoPrestacionalF_pacientesCitados").val(1).trigger('change.select2');
    $('#conceptoPrestacionalF_pacientesCitados').attr('readonly','readonly');
    $("#destinoAseguradoF_pacientesCitados").val(2).trigger('change.select2');
    $('#destinoAseguradoF_pacientesCitados').attr('readonly','readonly');
    $("#fechaIngresoF_pacientesCitados").val('').trigger('change.select2');
    $("#fechaAltaF_pacientesCitados").val('').trigger('change.select2');
    $("#observacionesF_pacientesCitados").val('').trigger('change.select2');

    /* FIN PARA QUE EL VALOR SIEMPRE SE MANTENGA EN LA POSICIÓN */

    $("#valorCopiar_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["TipoAtencion"]);

    $("#btnCopiar_pacientesCitados").click(function(){
      /* console.log("probando copiar"); */
      $("#btnCopiar_pacientesCitados").css("display","none");
      $("#btnPegar_pacientesCitados").css("display","block");
      /* console.log(tablaPacientesCitados.row($(this)).data()["TipoAtencion"]); */
      $("#valorCopiado_pacientesCitados").val($("#valorCopiar_pacientesCitados").val());
    });

    $('#btnPegar_pacientesCitados').submit(function(e){

    });

    /* LUGAR DE ATENCIÓN */
    var idTipoAtencion = tablaPacientesCitados.row($(this)).data()["TipoAtencion"];

    if(idTipoAtencion == 'PRESENCIAL'){
      $("#lugarAtencionF_pacientesCitados").val(1).trigger('change.select2');
    }else if(idTipoAtencion == 'NO PRESENCIAL'){
      $("#lugarAtencionF_pacientesCitados").val(2).trigger('change.select2');
    }else{
      $("#lugarAtencionF_pacientesCitados").val(1).trigger('change.select2');
    }
    /* FIN DE LUGAR DE ATENCIÓN */

    /* AJAX PARA EXTRAER VALORES DE REFERENCIA */

    var idPaciente = tablaPacientesCitados.row($(this)).data()["id_Paciente"];

    /* INICIO REFERENCIAS CONSULTAS */
        $.ajax({
          url: '{{ route('consultar.referencias') }}',
          data: {idPaciente},
          success: function(respuesta){
            /* console.log("respuesta",respuesta); */

            var arregloReferencia = JSON.parse(respuesta);
            for(var x=0;x<arregloReferencia.length;x++){
              $("#codigoReferenciaF_pacientesCitados").val(arregloReferencia[x].estb2_cod);
              $("#descripcionReferenciaF_pacientesCitados").val(arregloReferencia[x].descripcion);
              $("#numeroReferenciaF_pacientesCitados").val(arregloReferencia[x].ref_rec_hoja_nro);
            }
          },

          error: function(jqXHR,textStatus,errorThrown){
            console.error(textStatus + " " + errorThrown);
          }
        });
    /* FIN DE AJAX PARA EXTRAER VALORES DE REFERENCIA */

    var CodigoCie = tablaPacientesCitados.row($(this)).data()["CodigoCie"];

    /* INICIO CODIGO CIE BUSQUEDA CONSULTAS */
        $.ajax({
          url: '{{ route('consultar.codigoCie') }}',
          data: {CodigoCie},
          success: function(respuesta){
            /* console.log("respuesta",respuesta); */

            if (CodigoCie == null){
              $("#codigoCieNF_pacientesCitados").val('');
              /* $('#codigoCieNF_pacientesCitados').attr('readonly','readonly'); */
              $('#codigoCieF_pacientesCitados').val('');
              $('#codigoCieF_pacientesCitados').attr('readonly','readonly');
            }else{
              var arregloCodigoCie = JSON.parse(respuesta);
              for(var x=0;x<arregloCodigoCie.length;x++){
                $("#codigoCieNF_pacientesCitados").val(arregloCodigoCie[x].cie_cod);
                /* $('#codigoCieNF_pacientesCitados').attr('readonly','readonly'); */
                $("#codigoCieF_pacientesCitados").val(arregloCodigoCie[x].cie_desc);
                $('#codigoCieF_pacientesCitados').attr('readonly','readonly');
              }
            }
          },

          error: function(jqXHR,textStatus,errorThrown){
            console.error(textStatus + " " + errorThrown);
          }
        });
    /* FIN DE AJAX PARA EXTRAER VALORES DE CODIGO CIE */

    /* INICIO DE PERSONAL DATOS GENERALES */
        var idPersonal = tablaPacientesCitados.row($(this)).data()["Personal_id"];
        /* console.log(idPersonal); */

        $.ajax({
          url: '{{ route('consultar.personalC') }}',
          data: {idPersonal},
          success: function(respuesta){
            /* console.log("respuesta",respuesta); */
            var arregloPersonalC = JSON.parse(respuesta);
            for(var x=0;x<arregloPersonalC.length;x++){
              if(arregloPersonalC[x].ddi_cod == 1){
                $("#tipoDocumentoP_pacientesCitados").val('D.N.I.');
              }else{
                $("#tipoDocumentoP_pacientesCitados").val('');
              }

              $("#numeroDocumentoP_pacientesCitados").val(arregloPersonalC[x].ddi_nro);

              $("#tipoPersonalSaludF_pacientesCitados").val(arregloPersonalC[x].TipoPersonalSalud_id).trigger('change.select2');
              $('#tipoPersonalSaludF_pacientesCitados').attr('readonly','readonly');

              $("#egresadoF_pacientesCitados").val(arregloPersonalC[x].Egresado_id).trigger('change.select2');
              $('#egresadoF_pacientesCitados').attr('readonly','readonly');

              $("#especialidadF_pacientesCitados").val(arregloPersonalC[x].Especialidad_id).trigger('change.select2');
              $('#especialidadF_pacientesCitados').attr('readonly','readonly');

              $("#colegiaturaF_pacientesCitados").val(arregloPersonalC[x].NroColegiatura);
              $("#rneF_pacientesCitados").val(arregloPersonalC[x].NroRNE);
            }
          },

          error: function(jqXHR,textStatus,errorThrown){
            console.error(textStatus + " " + errorThrown);
          }
        });
    /* FIN DE PERSONAL DATOS GENERALES */

    if(tablaPacientesCitados.row($(this)).data()["numeroSesion"] != null)
    {
        $("#btnRolCitas_pacientesCitados").css("display","block");
        $("#frmVerRolCitas").attr('action',$(location).attr('href')+"/"+tablaPacientesCitados.row($(this)).data()["idIdentificador"]);
        $("#idCab_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["idIdentificador"]);
        $("#idCab_pacientesCitadosC").val(tablaPacientesCitados.row($(this)).data()["idIdentificador"]);
    }
    else
    {
        $("#btnRolCitas_pacientesCitados").css("display","none");
        $("#idCab_pacientesCitadosC").val("");
    }

    $("#frmGenerarFua").attr('action',$(location).attr('href')+"/"+tablaPacientesCitados.row($(this)).data()["idRegistro"]);
		$("#historia_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["HistoriaClinica"]);
		/* $("#nombres_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["Paciente"]); */
		$("#documento_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["NumeroDocumento"]);
		$("#tipoDocumento_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["TipoDocumento"]);
    /* $("#apellidoPaternoF_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["ApellidoPaterno"].replace('&#039;', "'")); */
    /* $("#apellidoMaternoF_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["ApellidoMaterno"].replace('&#039;', "'")); */
    $("#unidadOrganicaId_pacientesCitadosC").val(tablaPacientesCitados.row($(this)).data()["UnidadOrganica_id"]);

/*     var valores_nombresF =(tablaPacientesCitados.row($(this)).data()["Nombres"]).split(" ");
    $("#primerNombreF_pacientesCitados").val(valores_nombresF[0]);
    let elementoEliminado = valores_nombresF.splice(0, 1);
    $("#otroNombreF_pacientesCitados").val(valores_nombresF.join(" ")); */

    /* MODAL DE GENERACIÓN DE FUA */
    $("#tipoDocumentoF_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["TipoDocumento"]);
    $("#numeroDocumentoF_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["NumeroDocumento"]);
    $("#pacienteIdF_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["id_Paciente"]);
    /* $("#sexoF_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["Sexo"]); */
    /* $("#fechaNacimientoF_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["FechaNacimiento"]); */

    if(tablaPacientesCitados.row($(this)).data()["HistoriaClinica"] == null){
        $("#historiaF_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["NumeroDocumento"]);
    }else{
        $("#historiaF_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["HistoriaClinica"]);
    }

    $("#fechaF_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["Fecha1"]);
    $("#horaF_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["Hora"]);

    /* CON ESTO REALIZAMOS EL ALGORITMO PARA IDENTIFICAR EL CODIGO PRESTACIONAL */
        if((tablaPacientesCitados.row($(this)).data()["GrupoProfesional"] == 'MEDICOS' && tablaPacientesCitados.row($(this)).data()["TipoActividad"] == 'ESTUDIO MEDICO') ||
           (tablaPacientesCitados.row($(this)).data()["GrupoProfesional"] == 'MEDICOS' && tablaPacientesCitados.row($(this)).data()["TipoActividad"] == 'EVOLUCION MEDICO') ||
           (tablaPacientesCitados.row($(this)).data()["GrupoProfesional"] == 'MEDICOS' && tablaPacientesCitados.row($(this)).data()["TipoActividad"] == 'REEVALUACION MEDICO') ||
           (tablaPacientesCitados.row($(this)).data()["GrupoProfesional"] == 'MEDICOS' && tablaPacientesCitados.row($(this)).data()["TipoActividad"] == 'INTERCONSULTA MEDICO')) {
            $('#codigoPrestacionalF_pacientesCitados').val('056').trigger('change.select2');
        }else if((tablaPacientesCitados.row($(this)).data()["GrupoProfesional"] == 'NUTRICIONISTAS' && tablaPacientesCitados.row($(this)).data()["TipoActividad"] == 'ESTUDIO NUTRICIONAL') ||
                 (tablaPacientesCitados.row($(this)).data()["GrupoProfesional"] == 'NUTRICIONISTAS' && tablaPacientesCitados.row($(this)).data()["TipoActividad"] == 'EVOLUCION NUTRICIONAL') ||
                 (tablaPacientesCitados.row($(this)).data()["GrupoProfesional"] == 'NUTRICIONISTAS' && tablaPacientesCitados.row($(this)).data()["TipoActividad"] == 'REEVALUACION NUTRICIONAL') ||
                 (tablaPacientesCitados.row($(this)).data()["GrupoProfesional"] == 'PSICOLOGOS')){
                    $('#codigoPrestacionalF_pacientesCitados').val('906').trigger('change.select2');
        }else if(tablaPacientesCitados.row($(this)).data()["GrupoProfesional"] == 'ODONTOLOGOS'){
            $('#codigoPrestacionalF_pacientesCitados').val('070').trigger('change.select2');
        }else if((tablaPacientesCitados.row($(this)).data()["GrupoProfesional"] == 'TERAPISTAS' && tablaPacientesCitados.row($(this)).data()["TipoActividad"] == 'SESION'))
            $('#codigoPrestacionalF_pacientesCitados').val('901').trigger('change.select2');
        else{
          $('#codigoPrestacionalF_pacientesCitados').val('').trigger('change.select2');
        }
    /* FIN DEL CODIGO PRESTACIONAL */

    /* CON ESTO REALIZAMOS EL ALGORITMO PARA EL DIAGNOSTICO Y CODIGO CIE*/
        if(tablaPacientesCitados.row($(this)).data()["Diagnostico"] == ''){
          $('#diagnosticoF_pacientesCitados').val('').trigger('change.select2');
          $('#diagnosticoF_pacientesCitados').attr('readonly','readonly');
        }else{
          $('#diagnosticoF_pacientesCitados').val(tablaPacientesCitados.row($(this)).data()["Diagnostico"]).trigger('change.select2');
          $('#diagnosticoF_pacientesCitados').attr('readonly','readonly');
        }
    /* FIN DEL CODIGO DEL DIAGNOSTICO */

    $("#nombresApellidosP_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["Personal_id"]).trigger('change.select2');
    $("#idRegistroF_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["idRegistro"]);
    $("#modeloF_pacientesCitados").val(tablaPacientesCitados.row($(this)).data()["Modelo"]);
    /* FIN DE MODAL DE GENERACIÓN DE FUA */
	}
});

function configureLoadingScreen(screen){
    $(document).ajaxStart(function(){
      screen.fadeIn();
    }).ajaxStop(function(){
      screen.fadeOut();
    });
  }
</script>

<script type="text/javascript">

/* INICIO DE CIERRE DE MODAL DE GENERAR FUA CITADOS Y LIBRE */
$("#cancelar_generarFuaF").on('click',function(){
  $("#generarFuaF_pacientesCitados").validate().resetForm();
});

$("#cancelar_generarFuaLibreF").on('click',function(){
  $("#generarFuaLibre_pacientesCitados").validate().resetForm();
});

$("#botonCerrarGenerarFua_pacientesCitados").on('click',function(){
  $("#generarFuaF_pacientesCitados").validate().resetForm();
});

$("#botonCerrarGenerarFuaLibre_pacientesCitados").on('click',function(){
  $("#generarFuaLibre_pacientesCitados").validate().resetForm();
});
/* FIN DE CIERRE DE MODAL DE GENERAR FUA CITADOS Y LIBRE */

$("#guardar_generarFUAF").click(function(){
	if($("#generarFuaF_pacientesCitados").valid() == false){
		return;
	}

	let personalAtiendeF_pacientesCitados = $("#personalAtiendeF_pacientesCitados").val();
	let lugarAtencionF_pacientesCitados = $("#lugarAtencionF_pacientesCitados").val();
	let tipoAtencionF_pacientesCitados = $("#tipoAtencionF_pacientesCitados").val();
	let codigoReferenciaF_pacientesCitados = $("#codigoReferenciaF_pacientesCitados").val();
	let descripcionReferenciaF_pacientesCitados = $("#descripcionReferenciaF_pacientesCitados").val();
	let numeroReferenciaF_pacientesCitados = $("#numeroReferenciaF_pacientesCitados").val();
	let tipoDocumentoF_pacientesCitados = $("#tipoDocumentoF_pacientesCitados").val();
	let numeroDocumentoF_pacientesCitados = $("#numeroDocumentoF_pacientesCitados").val();
	let componenteF_pacientesCitados = $("#componenteF_pacientesCitados").val();
	let codigoAsegurado2F_pacientesCitados = $("#codigoAsegurado2F_pacientesCitados").val();
	let codigoAsegurado3F_pacientesCitados = $("#codigoAsegurado3F_pacientesCitados").val();
	let apellidoPaternoF_pacientesCitados = $("#apellidoPaternoF_pacientesCitados").val();
	/* let apellidoMaternoF_pacientesCitados = $("#apellidoMaternoF_pacientesCitados").val(); */
	let primerNombreF_pacientesCitados = $("#primerNombreF_pacientesCitados").val();
	let sexoF_pacientesCitados = $("#sexoF_pacientesCitados").val();
	let fechaNacimientoF_pacientesCitados = $("#fechaNacimientoF_pacientesCitados").val();
/* 	let fechaF_pacientesCitados = $("#fechaF_pacientesCitados").val();
	let horaF_pacientesCitados = $("#horaF_pacientesCitados").val(); */
	let codigoPrestacionalF_pacientesCitados = $("#codigoPrestacionalF_pacientesCitados").val();
	let conceptoPrestacionalF_pacientesCitados = $("#conceptoPrestacionalF_pacientesCitados").val();
	let destinoAseguradoF_pacientesCitados = $("#destinoAseguradoF_pacientesCitados").val();
  let historiaF_pacientesCitados = $("#historiaF_pacientesCitados").val();

  $('#generarFuaF_pacientesCitados').submit(function(e){

    if($("#generarFuaF_pacientesCitados").valid() == false){
		  return;
	  }

    e.preventDefault();

    swal({
      title: '¿Está seguro de generar el FUA?',
      text: "¡Si no lo está puede cancelar la acción!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, generar FUA!'
    }).then(function(result){

        if(result.value){

          $.ajax({

            url: '{{ route('consultar.generarFua') }}',
            method: "POST",
            data: $("#generarFuaF_pacientesCitados").serialize(),
              success:function(respuesta){
                console.log("respuesta",respuesta);
                let IdAtencion_generacionFua = respuesta[1];

                if(respuesta == 'NO-VALIDACION'){
                  swal("Existen valores incorrectos");
                }

                if(respuesta == 'FUA-DUPLICADO'){
                  swal({
  		               title: 'El número de Fua existe en nuestros registros',
  		               type: 'warning'
  	              });
                }

                if(respuesta == 'DATOS-ERRONEOS'){
                  swal({
  		               title: 'Los datos del paciente son erroneos, por favor volver a intentarlo',
  		               type: 'warning'
  	              });

                  tablaPacientesCitados.ajax.reload(null,false);
                  $('#generarFua').modal('hide');
                  $("#btnGenerarFUA_pacientesCitados").css("display","none");
                  $("#btnRolCitas_pacientesCitados").css("display","none");
                  $("#tipoSeguro_pacientesCitados").val("");
                  $("#estadoSeguro_pacientesCitados").val("");
                  $("#fechaCaducidadSeguro_pacientesCitados").val("");
                  $("#nombres_pacientesCitados").val("");
                  $("#historia_pacientesCitados").val("");
                  $("#tipoDocumento_pacientesCitados").val("");
                  $("#documento_pacientesCitados").val("");
                  $("#estadoSeguro_pacientesCitados").css({"background":"#e9ecef","color":"white","text-align":"center","font-weight":"900"});
                  $("#fechaCaducidadSeguro_pacientesCitados").css({"background":"#e9ecef","color":"white","text-align":"center","font-weight":"900"});
                }

                if(respuesta[0] == "GUARDAR-FUA"){
                swal({
                          type:"success",
                          title: "¡El FUA ha sido generado correctamente!",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"
                }).then(function(result){

                    if(result.value){
                        tablaPacientesCitados.ajax.reload(null,false);
                    }

                    $('#generarFua').modal('hide');
                    $("#btnGenerarFUA_pacientesCitados").css("display","none");
                    $("#btnRolCitas_pacientesCitados").css("display","none");
                    $("#tipoSeguro_pacientesCitados").val("");
                    $("#estadoSeguro_pacientesCitados").val("");
                    $("#fechaCaducidadSeguro_pacientesCitados").val("");
                    $("#nombres_pacientesCitados").val("");
                    $("#historia_pacientesCitados").val("");
                    $("#tipoDocumento_pacientesCitados").val("");
                    $("#documento_pacientesCitados").val("");
                    $("#estadoSeguro_pacientesCitados").css({"background":"#e9ecef","color":"white","text-align":"center","font-weight":"900"});
                    $("#fechaCaducidadSeguro_pacientesCitados").css({"background":"#e9ecef","color":"white","text-align":"center","font-weight":"900"});

                    printJS({printable:ruta+'/'+'pacientesCitados/reportesFUA/'+IdAtencion_generacionFua, type:'pdf', showModal:true});
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

$("#guardarFuaLibre_pacientesCitados").click(function(){
	if($("#generarFuaLibre_pacientesCitados").valid() == false) {
		return;
	}

  let personalAtiendeFL_pacientesCitados = $("#personalAtiendeFL_pacientesCitados").val();
  let lugarAtencionFL_pacientesCitados = $("#lugarAtencionFL_pacientesCitados").val();
  let codigoReferenciaFL_pacientesCitados = $("#codigoReferenciaFL_pacientesCitados").val();
  let descripcionReferenciaFL_pacientesCitados = $("#descripcionReferenciaFL_pacientesCitados").val();
  let numeroReferenciaFL_pacientesCitados = $("#numeroReferenciaFL_pacientesCitados").val();
  let tipoDocumentoFL_pacientesCitados = $("#tipoDocumentoFL1_pacientesCitados").val();
  let numeroDocumentoFL_pacientesCitados = $("#documentoN1_pacientesCitados").val();
  let componenteFL_pacientesCitados = $("#componenteFL_pacientesCitados").val();
  let codigoAsegurado2FL_pacientesCitados = $("#codigoAsegurado2FL_pacientesCitados").val();
  let codigoAsegurado3FL_pacientesCitados = $("#codigoAsegurado3FL_pacientesCitados").val();
  let apellidoPaternoFL_pacientesCitados = $("#apellidoPaterno_pacientesCitados").val();
  let apellidoMaternoFL_pacientesCitados = $("#apellidoMaterno_pacientesCitados").val();
  let primerNombreFL_pacientesCitados = $("#primerNombreFL_pacientesCitados").val();
  let sexoFL_pacientesCitados = $("#sexoFL_pacientesCitados").val();
  let fechaNacimientoFL_pacientesCitados = $("#fechaNacimientoFL_pacientesCitados").val();
  let historiaFL_pacientesCitados = $("#historiaFL_pacientesCitados").val();
  let codigoPrestacionalFL_pacientesCitados = $("#codigoPrestacional_pacientesCitados").val();
  let conceptoPrestacionalFL_pacientesCitados = $("#conceptoPrestacionalFL_pacientesCitados").val();
  let destinoAseguradoFL_pacientesCitados = $("#destinoAseguradoFL_pacientesCitados").val();
  let personalFL_pacientesCitados = $("#personal_pacientesCitados").val();

  $('#generarFuaLibre_pacientesCitados').submit(function(e){
    if($("#generarFuaLibre_pacientesCitados").valid() == false){
		  return;
	  }

    e.preventDefault();

    swal({
      title: '¿Está seguro de generar el FUA Libre?',
      text: "¡Si no lo está puede cancelar la acción!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, generar FUA!'
    }).then(function(result){
      if(result.value){
        $.ajax({
          url: '{{ route('consultar.generarFuaLibre') }}',
          method: "POST",
          data: $("#generarFuaLibre_pacientesCitados").serialize(),
            success:function(respuesta){

                let IdAtencion_generacionFua = respuesta[1];

                if(respuesta == 'NO-VALIDACION'){
                  swal("Existen valores incorrectos");
                }

                if(respuesta[0] == "GUARDAR-FUA"){
                  swal({
                          type:"success",
                          title: "¡El FUA Libre ha sido generado correctamente!",
                          showConfirmButton: true,
                          confirmButtonText: "Cerrar"

                    }).then(function(result){
                      tablaPacientesCitados.ajax.reload(null,false);
                      $('#crearFuaLibre').modal('hide');
                        /* CERRAMOS EL MODAL ABIERTO Y NOS MUESTRA LA INFORMACIÓN DEL FUA EN OTRA PÁGINA */
                        /* $('#crearFuaLibre').modal('hide'); */
                        /* tablaPacientesCitados.clear().destroy(); */

/*                         let valorUrlAjax1 = '';

                        if($('#fechaInicio_pacientesCitados').val() != '' || $('#fechaFin_pacientesCitados').val() != ''){
                          valorUrlAjax1 = '{{ route('consultar.fechas') }}';
                        }else{
                          valorUrlAjax1 = 'http://192.168.6.113/software_ufpa/public/pacientesCitados';
                        } */


                        $("#historiaClinica_pacientesCitados").val("");
                        $("#apellidoPaterno_pacientesCitados").val("");
                        $("#apellidoMaterno_pacientesCitados").val("");
                        $("#documentoN_pacientesCitados").val("");
                        $("#tipoDocumentoFL_pacientesCitados").val("");
                        $("#historiaFL_pacientesCitados").val("");
                        $("#sexoFL_pacientesCitados").val("");
                        $("#fechaNacimientoFL_pacientesCitados").val("");
                        $("#tipoSeguroFL_pacientesCitados").val("");
                        $("#codigoAsegurado1FL_pacientesCitados").val("");
                        $("#codigoAsegurado2FL_pacientesCitados").val("");
                        $("#codigoAsegurado3FL_pacientesCitados").val("");
                        $("#estadoSeguroFL_pacientesCitados").val("");
                        $("#pacienteIdFL_pacientesCitados").val("");
                        $("#primerNombreFL_pacientesCitados").val("");
                        $("#otroNombreFL_pacientesCitados").val("");
                        $("#fechaAtencion_pacientesCitados").val("");
                        $("#hora_pacientesCitados").val("");
                        $("#personal_pacientesCitados").val("").trigger('change.select2');
                        $("#codigoPrestacional_pacientesCitados").val("").trigger('change.select2');
                        $("#diagnostico_pacientesCitados").val("").trigger('change.select2');
                        $("#codigoCieN_pacientesCitados").val("");
                        $("#codigoCie_pacientesCitados").val("");
                        $("#codigoReferenciaFL_pacientesCitados").val("");
                        $("#descripcionReferenciaFL_pacientesCitados").val("");
                        $("#numeroReferenciaFL_pacientesCitados").val("");
                        $("#tipoDocumentoFL1_pacientesCitados").val("");
                        $("#documentoN1_pacientesCitados").val("");
                        $("#historiaFL_pacientesCitados").val("");
                        $("#fechaIngresoF_pacientesCitados").val("");
                        $("#fechaAltaF_pacientesCitados").val("");
                        $("#tipoDocumentoPFL_pacientesCitados").val("");
                        $("#numeroDocumentoPFL_pacientesCitados").val("");
                        $("#colegiaturaFL_pacientesCitados").val("");
                        $("#rneFL_pacientesCitados").val("");
                        $("#tipoPersonalSaludFL_pacientesCitados").val("").trigger('change.select2');
                        $("#egresadoFL_pacientesCitados").val("").trigger('change.select2');
                        $("#especialidadFL_pacientesCitados").val("").trigger('change.select2');

                        printJS({printable:ruta+'/'+'pacientesCitados/reportesFUA/'+IdAtencion_generacionFua, type:'pdf', showModal:true});

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

$('select[name=personal_pacientesCitados]').change(function(){
  var idPersonal = $("#personal_pacientesCitados").val();

  if(idPersonal != ''){
    $.ajax({
      url: '{{ route('consultar.personalC') }}',
      data: {idPersonal},
      success: function(respuesta){
        /* console.log("respuesta",respuesta); */
        var arregloPersonalC = JSON.parse(respuesta);
        for(var x=0;x<arregloPersonalC.length;x++){
          if(arregloPersonalC[x].ddi_cod == 1){
            $("#tipoDocumentoPFL_pacientesCitados").val('D.N.I.');
          }else{
            $("#tipoDocumentoPFL_pacientesCitados").val('');
          }

          $("#numeroDocumentoPFL_pacientesCitados").val(arregloPersonalC[x].ddi_nro);
          $("#tipoPersonalSaludFL_pacientesCitados").val(arregloPersonalC[x].TipoPersonalSalud_id).trigger('change.select2');
          $("#egresadoFL_pacientesCitados").val(arregloPersonalC[x].Egresado_id).trigger('change.select2');
          $("#especialidadFL_pacientesCitados").val(arregloPersonalC[x].Especialidad_id).trigger('change.select2');
          $("#colegiaturaFL_pacientesCitados").val(arregloPersonalC[x].NroColegiatura);
          $("#rneFL_pacientesCitados").val(arregloPersonalC[x].NroRNE);
        }
      },

      error: function(jqXHR,textStatus,errorThrown){
        console.error(textStatus + " " + errorThrown);
      }
    });
  }else{
    $("#tipoDocumentoPFL_pacientesCitados").val('');
    $("#numeroDocumentoPFL_pacientesCitados").val('');
    $("#tipoPersonalSaludFL_pacientesCitados").val('').trigger('change.select2');
    $("#egresadoFL_pacientesCitados").val('').trigger('change.select2');
    $("#especialidadFL_pacientesCitados").val('').trigger('change.select2');
    $("#colegiaturaFL_pacientesCitados").val('');
    $("#rneFL_pacientesCitados").val('');
  }
/* FIN DE PERSONAL DATOS GENERALES */
});

$('select[name=nombresApellidosP_pacientesCitados]').change(function(){
  var idPersonal = $("#nombresApellidosP_pacientesCitados").val();
  /* console.log(idPersonal); */

  if(idPersonal != ''){
    $.ajax({
      url: '{{ route('consultar.personalC') }}',
      data: {idPersonal},
      success: function(respuesta){
        /* console.log("respuesta",respuesta); */
        var arregloPersonalC = JSON.parse(respuesta);
        for(var x=0;x<arregloPersonalC.length;x++){
          if(arregloPersonalC[x].ddi_cod == 1){
            $("#tipoDocumentoP_pacientesCitados").val('D.N.I.');
          }else{
            $("#tipoDocumentoP_pacientesCitados").val('');
          }

          $("#numeroDocumentoP_pacientesCitados").val(arregloPersonalC[x].ddi_nro);
          $("#tipoPersonalSaludF_pacientesCitados").val(arregloPersonalC[x].TipoPersonalSalud_id).trigger('change.select2');
          $("#egresadoF_pacientesCitados").val(arregloPersonalC[x].Egresado_id).trigger('change.select2');
          $("#especialidadF_pacientesCitados").val(arregloPersonalC[x].Especialidad_id).trigger('change.select2');
          $("#colegiaturaF_pacientesCitados").val(arregloPersonalC[x].NroColegiatura);
          $("#rneF_pacientesCitados").val(arregloPersonalC[x].NroRNE);
        }
      },

      error: function(jqXHR,textStatus,errorThrown){
        console.error(textStatus + " " + errorThrown);
      }
    });
  }else{
    $("#tipoDocumentoPFL_pacientesCitados").val('');
    $("#numeroDocumentoPFL_pacientesCitados").val('');
    $("#tipoPersonalSaludFL_pacientesCitados").val('').trigger('change.select2');
    $("#egresadoFL_pacientesCitados").val('').trigger('change.select2');
    $("#especialidadFL_pacientesCitados").val('').trigger('change.select2');
    $("#colegiaturaFL_pacientesCitados").val('');
    $("#rneFL_pacientesCitados").val('');
  }
/* FIN DE PERSONAL DATOS GENERALES */
});

$('select[name=codigoPrestacional_pacientesCitados]').change(function(){
  var idCodigoPrestacional = $("#codigoPrestacional_pacientesCitados").val();

  if(idCodigoPrestacional == '065'){
    $(".hospitalizacion_oculto").css("display","block");
    $("#tipoAtencionFL_pacientesCitados").val("").trigger("change");
    $("#fechaAltaF_pacientesCitados").attr('readonly','readonly');
    $("#fechaAtencion_pacientesCitados").attr('readonly','readonly');
    $("#fechaIngresoF_pacientesCitados").attr('required',true);
    $('#span_fechaIngresoF_pacientesCitados').removeAttr('style');
  }else{
    $(".hospitalizacion_oculto").css("display","none");
    $("#tipoAtencionFL_pacientesCitados").val(1).trigger("change");
    $("#fechaAtencion_pacientesCitados").removeAttr("readonly");
    $("#span_fechaIngresoF_pacientesCitados").css("display","none");
    $("#fechaIngresoF_pacientesCitados").removeAttr("required");
  }
});

$("#fechaAltaF_pacientesCitados").keyup(function () {
  var value = $(this).val();
  $("#fechaAtencion_pacientesCitados").val(value);
});
</script>

<script type="text/javascript">

$("#historiaClinicaBP_pacientesCitados").keypress(function(e) {
  if(e.which == 13) {
    e.preventDefault();

    let numHistoria = $("#historiaClinicaBP_pacientesCitados").val();

    if (numHistoria != '') {
          /* Petición AJAX */
          $.ajax({
            url: '{{ route('consultar.historia') }}',
            data: {numHistoria},
            success: function(respuesta){
              /* console.log(respuesta); */
              var arreglo = JSON.parse(respuesta);

              if(arreglo == ""){
                alert("El N° de Historia Clinica no existe");
                $('#historiaClinicaBP_pacientesCitados').val("");
                $("#documentoBP_pacientesCitados").val("");
                $("#apellidoPaternoBP_pacientesCitados").val("");
                $("#apellidoMaternoBP_pacientesCitados").val("");
                $("#nombresBP_pacientesCitados").val("");
                $("#sexoBP_pacientesCitados").val("");
                $("#fechaNacimientoBP_pacientesCitados").val("");
                $("#telefonoBP_pacientesCitados").val("");
                $("#correoElectronicoBP_pacientesCitados").val("");
              }else{
                for(var x=0;x<arreglo.length;x++){
                  $("#documentoBP_pacientesCitados").val(arreglo[x].nroDocIdentidad);
                  $("#historiaClinicaBP_pacientesCitados").val(arreglo[x].hcl_num);

                  if(arreglo[x].Sexo_id == 'M'){
                    $("#sexoBP_pacientesCitados").val("MASCULINO");
                  }else{
                    $("#sexoBP_pacientesCitados").val("FEMENINO");
                  }

                  $("#apellidoPaternoBP_pacientesCitados").val(arreglo[x].apPaterno);
                  $("#apellidoMaternoBP_pacientesCitados").val(arreglo[x].apMaterno);
                  $("#nombresBP_pacientesCitados").val(arreglo[x].nombres);
                  $("#fechaNacimientoBP_pacientesCitados").val(arreglo[x].fechaNacimiento);
                  $("#telefonoBP_pacientesCitados").val(arreglo[x].telefono);
                  $("#correoElectronicoBP_pacientesCitados").val(arreglo[x].correoElectronico);
                }
              }
            },
            error: function(jqXHR,textStatus,errorThrown){
                console.error(textStatus + " " + errorThrown);
            }
          });
    }else{
      alert('Escriba el N° de Historia.!');
      $("#historiaClinicaBP_pacientesCitados").focus();
      $("#documentoBP_pacientesCitados").val("");
      $("#apellidoPaternoBP_pacientesCitados").val("");
      $("#apellidoMaternoBP_pacientesCitados").val("");
      $("#nombresBP_pacientesCitados").val("");
      $("#sexoBP_pacientesCitados").val("");
      $("#fechaNacimientoBP_pacientesCitados").val("");
      $("#telefonoBP_pacientesCitados").val("");
      $("#correoElectronicoBP_pacientesCitados").val("");
    }
  }
});

$("#documentoBP_pacientesCitados").keypress(function(e) {
  if(e.which == 13) {
    e.preventDefault();

    let numDocumento = $("#documentoBP_pacientesCitados").val();

    if (numDocumento != '') {
          /* Petición AJAX */
          $.ajax({
            url: '{{ route('consultar.documento') }}',
            data: {numDocumento},
            success: function(respuesta){
              /* console.log(respuesta); */
              var arreglo = JSON.parse(respuesta);

              if(arreglo == ""){
                alert("El N° de Documento de Identidad no existe");
                $('#historiaClinicaBP_pacientesCitados').val("");
                $("#documentoBP_pacientesCitados").val("");
                $("#apellidoPaternoBP_pacientesCitados").val("");
                $("#apellidoMaternoBP_pacientesCitados").val("");
                $("#nombresBP_pacientesCitados").val("");
                $("#sexoBP_pacientesCitados").val("");
                $("#fechaNacimientoBP_pacientesCitados").val("");
                $("#telefonoBP_pacientesCitados").val("");
                $("#correoElectronicoBP_pacientesCitados").val("");
              }else{
                for(var x=0;x<arreglo.length;x++){
                  $("#documentoBP_pacientesCitados").val(arreglo[x].nroDocIdentidad);
                  $("#historiaClinicaBP_pacientesCitados").val(arreglo[x].hcl_num);

                  if(arreglo[x].Sexo_id == 'M'){
                    $("#sexoBP_pacientesCitados").val("MASCULINO");
                  }else{
                    $("#sexoBP_pacientesCitados").val("FEMENINO");
                  }

                  $("#apellidoPaternoBP_pacientesCitados").val(arreglo[x].apPaterno);
                  $("#apellidoMaternoBP_pacientesCitados").val(arreglo[x].apMaterno);
                  $("#nombresBP_pacientesCitados").val(arreglo[x].nombres);
                  $("#fechaNacimientoBP_pacientesCitados").val(arreglo[x].fechaNacimiento);
                  $("#telefonoBP_pacientesCitados").val(arreglo[x].telefono);
                  $("#correoElectronicoBP_pacientesCitados").val(arreglo[x].correoElectronico);
                }
              }
            },
            error: function(jqXHR,textStatus,errorThrown){
                console.error(textStatus + " " + errorThrown);
            }
          });
    }else{
      alert('Escriba el N° de Documento de Identidad.!');
      $("#documentoBP_pacientesCitados").focus();
      $("#historiaClinicaBP_pacientesCitados").val("");
      $("#apellidoPaternoBP_pacientesCitados").val("");
      $("#apellidoMaternoBP_pacientesCitados").val("");
      $("#nombresBP_pacientesCitados").val("");
      $("#sexoBP_pacientesCitados").val("");
      $("#fechaNacimientoBP_pacientesCitados").val("");
      $("#telefonoBP_pacientesCitados").val("");
      $("#correoElectronicoBP_pacientesCitados").val("");
    }
  }
});

</script>

@endsection

@section('javascript')
    <script src="{{ url('/') }}/js/pacientesCitados.js"></script>
@endsection

@endif

@endforeach
