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
                    <h1>Fuas Digitados</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
                        <li class="breadcrumb-item active">Fuas Digitados</li>
                    </ol>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <div class="input-group">
                        <label for="lista_pacienteFuasDigitados" class="col-md-2 control-label">Paciente:</label>
                        <div class="col-md-4" id="lista_pacienteFuasDigitados"><!-- Información desde Javascript (FUASMedica.js) --></div>
                        <label for="lista_fuaFuasDigitados" class="col-md-2 control-label">N° FUA:</label>
                        <div class="col-md-4" id="lista_fuaFuasDigitados"><!-- Información desde Javascript (FUASMedica.js) --></div>
                    </div>

                    <div class="input-group">
                        <label for="lista_profesionalesFuasDigitados" class="col-md-2 control-label">Profesional:</label>
                        <div class="col-md-4" id="lista_profesionalesFuasDigitados"><!-- Información desde Javascript (FUASMedica.js) --></div>
                        <label for="lista_profesionalesFuasDigitados" class="col-md-2 control-label">Cod. Prestacional:</label>
                        <div class="col-md-4" id="lista_codigoPrestacionalFuasDigitados"><!-- Información desde Javascript (FUASMedica.js) --></div>
                    </div>

                    <form method="GET" action="{{ url('/') }}/fuasDigitados/buscarPorMes" id="frmFechas_fuasDigitados">
                        @csrf
                        <div class="input-group">
                            <label for="email" class="col-md-2 control-label">Fecha de atención:</label>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="fechaInicio_fuasDigitados" id="fechaInicio_fuasDigitados"
                                style="text-transform: uppercase;" required>
                            </div>

                            <div class="col-md-2">
                                <input type="date" class="form-control" name="fechaFin_fuasDigitados" id="fechaFin_fuasDigitados"
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

                            <label for="historiaBD_fuasDigitados" class="col-md-1 control-label" style="">N° Historia:</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control inputRutaNumero" name="historiaBD_fuasDigitados" id="historiaBD_fuasDigitados"
                                style="text-transform: uppercase;" maxlength="6">
                            </div>

                            <label for="documentoBD_fuasDigitados" class="col-md-1 control-label">N° Documento:</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control inputRutaNumero" name="documentoBD_fuasDigitados" id="documentoBD_fuasDigitados"
                                style="text-transform: uppercase;" maxlength="9">
                            </div>
                            
                            <label for="fuaBD_fuasDigitados" class="col-md-1 control-label">N° FUA:</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control inputRutaNumero" name="fuaBD_fuasDigitados" id="fuaBD_fuasDigitados"
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
                            <button class="btn btn-warning btn-sm boton-general" data-toggle="modal" data-target="#verRecord" style="float:right;display:none; margin-left: 5px;">
                                <i class="fas fa-record-vinyl"></i> Record
                            </button>
                
                            <form method="POST" action="{{ url('/') }}/fuasDigitados/generadorTxt" id="frmDigitarFua_fuasDigitados">
                            @csrf
                                <input type="text" class="form-control" name="idFua_fuasDigitados" id="idFua_fuasDigitados"
                                style="text-transform: uppercase;display:none;">

                                <button type="submit" class="btn btn-danger btn-sm boton-general" data-toggle="modal" data-target="#verFua_fuasDigitados"  
                                style="float:left;display:none;margin-right: 5px;" id="btnVerFUA_fuasDigitados"> 
							    <i class="fas fa-eye" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Digitar FUA</button>
                            </form>

							<form method="POST" action="{{ url('/') }}/fuasDigitados/anularDigitacion" id="frmAnularDigitacionFua_fuasDigitados">
                            @csrf
                                <input type="text" class="form-control" name="idFuaA_fuasDigitados" id="idFuaA_fuasDigitados"
                                style="text-transform: uppercase;display:none;">

                                <button type="submit" class="btn btn-warning btn-sm boton-general" data-toggle="modal" data-target="#verFua_fuasDigitados"  
                                style="float:left;display:none;margin-right: 5px;" id="btnAnularDigitacionFUA_fuasDigitados"> 
							    <i class="fas fa-times" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Anular Digitación</button>
                            </form>

                            <form method="GET" action="" id="frmVerRolCitas_fuasDigitados">
                                @csrf
                                <input type="text" class="form-control" name="idCab_fuasDigitados" id="idCab_fuasDigitados"
                                style="text-transform: uppercase;display:none;" required>

                                <button type="submit" class="btn btn-dark btn-sm boton-general" data-toggle="modal" data-target="#verRolCitas_fuasDigitados"  
                                style="float:left;display:none;margin-right: 5px;" id="btnRolCitas_fuasDigitados"> 
							    <i class="fa fa-tasks" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Rol de Citas</button>
                            </form>
                        </div>

                        <div class="card-body">

                            <table class="table table-bordered table-striped display nowrap" width="100%" id="tablaFuasDigitados">
                                <thead>
                                    <tr style="background:white;" role="row">
									    <th>Id/Atención</th>
                                        <th>FUA</th>
                                        <th>Paciente</th>
                                        <th>Registro/Historia</th>
                                        <th>Fecha/Hora de digitación</th>
                                        <th>Fecha/Hora de atención</th>
                                        <th>Código Prestacional</th>
                                        <th>Profesional</th>
                                        <th>Estado</th>
										<th>Usuario</th>
										<th>Nombre/Paquete</th>
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

<script type="text/javascript">

$("#historiaBD_fuasDigitados").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        var numHistoriaBD = $("#historiaBD_fuasDigitados").val();
        
        if (numHistoriaBD != ''){

            tablaFuasDigitados.clear().destroy();

            $("#btnVerFUA_fuasDigitados").css("display","none");
            $("#documentoBD_fuasDigitados").val("");
            $("#fuaBD_fuasDigitados").val("");

            $.ajax({
                url: '{{ route('consultar.historiaBD_fuasDigitados') }}',
                data: {numHistoriaBD},
                success: function(respuesta){
                    /* console.log("respuesta",respuesta); */
                },

                error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                }
            });

        /*=============================================
        DataTable de FUAS DIGITADOS
        =============================================*/
        tablaFuasDigitados = $("#tablaFuasDigitados").DataTable({
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
			    "title": 'FUAS DIGITADOS GENERAL',
			    "filename": 'FUAS_DIGITADOS_GENERAL',
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
                "url": '{{ route('consultar.historiaBD_fuasDigitados') }}',
                "data": {'numHistoriaBD' : $("#historiaBD_fuasDigitados").val()}
            },
			"columnDefs":[
		        {
			        className:"position",targets: [8],
			        "render": function ( data, type, row ) {
				        if(data == 1){
					        return '<i class="fas fa-check" style="color:green;text-align:center;"> DIGITADO</i>'
				        }else{ 
				            return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"> NO-DIGITADO</i>'
				        }
			        }
		        }
	        ],
            "order": [[5, "asc"]],
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
				{"data": 'IdAtencion',"name": 'IdAtencion'},
		        {"data": 'FUA',"name": 'FUA'},
				{"data": 'Paciente',"name": 'Paciente'},
		        {"data": 'HistoriaClinica',"name": 'HistoriaClinica'},
		        {"data": 'FechaHoraDigitacion',"name": 'FechaHoraDigitacion'},
		        {"data": 'FechaHoraAtencion',"name": 'FechaHoraAtencion'},
		        {"data": 'CodigoPrestacional',"name": 'CodigoPrestacional'},
		        {"data": 'Profesional',"name": 'Profesional'},
		        {"data": 'digitarFua_estado',"name": 'digitarFua_estado'},
		        {"data": 'name',"name": 'name'},
		        {"data": 'nombrePaquete_tramas',"name": 'nombrePaquete_tramas'}
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
		paciente_fuasDigitados(tablaFuasDigitados);
		profesionales_fuasDigitados(tablaFuasDigitados);
		fua_fuasDigitados(tablaFuasDigitados);
        codigoPrestacional_fuasDigitados(tablaFuasDigitados);
	}
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasDigitados.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */

        }else{
            $('#historiaBD_fuasDigitados').focus();
            tablaFuasDigitados.clear().destroy();
            $("#btnVerFUA_fuasDigitados").css("display","none");

            let valorUrlAjaxHistoria_fuasDigitados = '';

            if($('#fechaInicio_fuasDigitados').val() != '' || $('#fechaFin_fuasDigitados').val() != ''){
                valorUrlAjaxHistoria_fuasDigitados = '{{ route('consultar.fechasFDigitados') }}';
            }else{
                valorUrlAjaxHistoria_fuasDigitados = ruta + '/fuasDigitados';
            }

            /*=============================================
            DataTable de FUAS DIGITADOS
            =============================================*/
            tablaFuasDigitados = $("#tablaFuasDigitados").DataTable({
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
			            "title": 'FUAS DIGITADOS',
			            "filename": 'FUAS_DIGITADOS',
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
                    "url": valorUrlAjaxHistoria_fuasDigitados,
                    "data":{'_token' : $('input[name=_token]').val(),
                            'fechaInicio_fuasDigitados' : $('#fechaInicio_fuasDigitados').val(),
                            'fechaFin_fuasDigitados': $('#fechaFin_fuasDigitados').val()}
                },
				"columnDefs":[
		            {
			            className:"position",targets: [8],
			            "render": function ( data, type, row ) {
				            if(data == 1){
					            return '<i class="fas fa-check" style="color:green;text-align:center;"> DIGITADO</i>'
				            }else{ 
				                return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"> NO-DIGITADO</i>'
				            }
			            }
		            }
	            ],
                "order": [[5, "asc"]],
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
					{"data": 'IdAtencion',"name": 'IdAtencion'},
		            {"data": 'FUA',"name": 'FUA'},
					{"data": 'Paciente',"name": 'Paciente'},
		            {"data": 'HistoriaClinica',"name": 'HistoriaClinica'},
		            {"data": 'FechaHoraDigitacion',"name": 'FechaHoraDigitacion'},
		            {"data": 'FechaHoraAtencion',"name": 'FechaHoraAtencion'},
		            {"data": 'CodigoPrestacional',"name": 'CodigoPrestacional'},
		            {"data": 'Profesional',"name": 'Profesional'},
		            {"data": 'digitarFua_estado',"name": 'digitarFua_estado'},
		            {"data": 'name',"name": 'name'},
		            {"data": 'nombrePaquete_tramas',"name": 'nombrePaquete_tramas'}
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
		paciente_fuasDigitados(tablaFuasDigitados);
		profesionales_fuasDigitados(tablaFuasDigitados);
		fua_fuasDigitados(tablaFuasDigitados);
        codigoPrestacional_fuasDigitados(tablaFuasDigitados);
	}
            });

            /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasDigitados.search(this.value).draw();
	            }
            });
            /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});

$("#documentoBD_fuasDigitados").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        var numDocumentoBD = $("#documentoBD_fuasDigitados").val();
        
        if (numDocumentoBD != ''){

            tablaFuasDigitados.clear().destroy();

            $("#btnVerFUA_fuasDigitados").css("display","none");
            $("#historiaBD_fuasDigitados").val("");
            $("#fuaBD_fuasDigitados").val("");

            $.ajax({
                url: '{{ route('consultar.documentoBD_fuasDigitados') }}',
                data: {numDocumentoBD},
                success: function(respuesta){
                    /* console.log("respuesta",respuesta); */
                },

                error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                }
            });

        /*=============================================
        DataTable de FUAS DIGITADOS
        =============================================*/
        tablaFuasDigitados = $("#tablaFuasDigitados").DataTable({
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
			    "title": 'FUAS DIGITADOS GENERAL',
			    "filename": 'FUAS_DIGITADOS_GENERAL',
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
                "url": '{{ route('consultar.documentoBD_fuasDigitados') }}',
                "data": {'numDocumentoBD' : $("#documentoBD_fuasDigitados").val()}
            },
			"columnDefs":[
		        {
			        className:"position",targets: [8],
			        "render": function ( data, type, row ) {
				        if(data == 1){
					        return '<i class="fas fa-check" style="color:green;text-align:center;"> DIGITADO</i>'
				        }else{ 
				            return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"> NO-DIGITADO</i>'
				        }
			        }
		        }
	        ],
            "order": [[5, "asc"]],
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
				{"data": 'IdAtencion',"name": 'IdAtencion'},
		        {"data": 'FUA',"name": 'FUA'},
				{"data": 'Paciente',"name": 'Paciente'},
		        {"data": 'HistoriaClinica',"name": 'HistoriaClinica'},
		        {"data": 'FechaHoraDigitacion',"name": 'FechaHoraDigitacion'},
		        {"data": 'FechaHoraAtencion',"name": 'FechaHoraAtencion'},
		        {"data": 'CodigoPrestacional',"name": 'CodigoPrestacional'},
		        {"data": 'Profesional',"name": 'Profesional'},
		        {"data": 'digitarFua_estado',"name": 'digitarFua_estado'},
		        {"data": 'name',"name": 'name'},
		        {"data": 'nombrePaquete_tramas',"name": 'nombrePaquete_tramas'}
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
		paciente_fuasDigitados(tablaFuasDigitados);
		profesionales_fuasDigitados(tablaFuasDigitados);
		fua_fuasDigitados(tablaFuasDigitados);
        codigoPrestacional_fuasDigitados(tablaFuasDigitados);
	}
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
        $('div.dataTables_filter input').unbind();
        $("div.dataTables_filter input").keyup( function (e) {
	        if (e.keyCode == 13) {
		        tablaFuasDigitados.search(this.value).draw();
	        }
        });
        /* FIN DE BUSQUEDA POR ENTER */

        }else{
            $('#documentoBD_fuasDigitados').focus();

            tablaFuasDigitados.clear().destroy();
            $("#btnVerFUA_fuasDigitados").css("display","none");

            let valorUrlAjaxDocumento_fuasDigitados = '';

            if($('#fechaInicio_fuasDigitados').val() != '' || $('#fechaFin_fuasDigitados').val() != ''){
                valorUrlAjaxDocumento_fuasDigitados = '{{ route('consultar.fechasFDigitados') }}';
            }else{
                valorUrlAjaxDocumento_fuasDigitados = ruta + '/fuasDigitados';
            }

            /*=============================================
            DataTable de FUAS DIGITADOS
            =============================================*/
            tablaFuasDigitados = $("#tablaFuasDigitados").DataTable({
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
			            "title": 'FUAS DIGITADOS',
			            "filename": 'FUAS_DIGITADOS',
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
                    "url": valorUrlAjaxDocumento_fuasDigitados,
                    "data": { '_token' : $('input[name=_token]').val(),
                            'fechaInicio_fuasDigitados' : $('#fechaInicio_fuasDigitados').val(),
                            'fechaFin_fuasDigitados': $('#fechaFin_fuasDigitados').val()}
                },
				"columnDefs":[
		            {
			            className:"position",targets: [8],
			            "render": function ( data, type, row ) {
				            if(data == 1){
					            return '<i class="fas fa-check" style="color:green;text-align:center;"> DIGITADO</i>'
				            }else{ 
				                return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"> NO-DIGITADO</i>'
				            }
			            }
		            }
	            ],
                "order": [[5, "asc"]],
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
					{"data": 'IdAtencion',"name": 'IdAtencion'},
		            {"data": 'FUA',"name": 'FUA'},
					{"data": 'Paciente',"name": 'Paciente'},
		            {"data": 'HistoriaClinica',"name": 'HistoriaClinica'},
		            {"data": 'FechaHoraDigitacion',"name": 'FechaHoraDigitacion'},
		            {"data": 'FechaHoraAtencion',"name": 'FechaHoraAtencion'},
		            {"data": 'CodigoPrestacional',"name": 'CodigoPrestacional'},
		            {"data": 'Profesional',"name": 'Profesional'},
		            {"data": 'digitarFua_estado',"name": 'digitarFua_estado'},
		            {"data": 'name',"name": 'name'},
		            {"data": 'nombrePaquete_tramas',"name": 'nombrePaquete_tramas'}
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
		paciente_fuasDigitados(tablaFuasDigitados);
		profesionales_fuasDigitados(tablaFuasDigitados);
		fua_fuasDigitados(tablaFuasDigitados);
        codigoPrestacional_fuasDigitados(tablaFuasDigitados);
	}
            });

            /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasDigitados.search(this.value).draw();
	            }
            });
            /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});

$("#fuaBD_fuasDigitados").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        var numFuaBD = $("#fuaBD_fuasDigitados").val();
        
        if (numFuaBD != ''){

            tablaFuasDigitados.clear().destroy();

            $("#btnVerFUA_fuasDigitados").css("display","none");
            $("#historiaBD_fuasDigitados").val("");
            $("#documentoBD_fuasDigitados").val("");

            $.ajax({
                url: '{{ route('consultar.fuaBD_fuasDigitados') }}',
                data: {numFuaBD},
                success: function(respuesta){
                    /* console.log("respuesta",respuesta); */
                },

                error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                }
            });

        /*=============================================
        DataTable de FUAS DIGITADOS
        =============================================*/
        tablaFuasDigitados = $("#tablaFuasDigitados").DataTable({
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
			    "title": 'FUAS DIGITADOS GENERAL',
			    "filename": 'FUAS_DIGITADOS_GENERAL',
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
                "url": '{{ route('consultar.fuaBD_fuasDigitados') }}',
                "data": {'numFuaBD' : $("#fuaBD_fuasDigitados").val()}
            },
			"columnDefs":[
		        {
			        className:"position",targets: [8],
			        "render": function ( data, type, row ) {
				        if(data == 1){
					        return '<i class="fas fa-check" style="color:green;text-align:center;"> DIGITADO</i>'
				        }else{ 
				            return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"> NO-DIGITADO</i>'
				        }
			        }
		        }
	        ],
            "order": [[5, "asc"]],
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
				{"data": 'IdAtencion',"name": 'IdAtencion'},
		        {"data": 'FUA',"name": 'FUA'},
				{"data": 'Paciente',"name": 'Paciente'},
		        {"data": 'HistoriaClinica',"name": 'HistoriaClinica'},
		        {"data": 'FechaHoraDigitacion',"name": 'FechaHoraDigitacion'},
		        {"data": 'FechaHoraAtencion',"name": 'FechaHoraAtencion'},
		        {"data": 'CodigoPrestacional',"name": 'CodigoPrestacional'},
		        {"data": 'Profesional',"name": 'Profesional'},
		        {"data": 'digitarFua_estado',"name": 'digitarFua_estado'},
		        {"data": 'name',"name": 'name'},
		        {"data": 'nombrePaquete_tramas',"name": 'nombrePaquete_tramas'}
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
		paciente_fuasDigitados(tablaFuasDigitados);
		profesionales_fuasDigitados(tablaFuasDigitados);
		fua_fuasDigitados(tablaFuasDigitados);
        codigoPrestacional_fuasDigitados(tablaFuasDigitados);
	}
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasDigitados.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */

        }else{
            $('#fuaBD_fuasDigitados').focus();
            tablaFuasDigitados.clear().destroy();
            $("#btnVerFUA_fuasDigitados").css("display","none");

            let valorUrlAjaxFua_fuasDigitados = '';

            if($('#fechaInicio_fuasDigitados').val() != '' || $('#fechaFin_fuasDigitados').val() != ''){
                valorUrlAjaxFua_fuasDigitados = '{{ route('consultar.fechasFDigitados') }}';
            }else{
                valorUrlAjaxFua_fuasDigitados = ruta + '/fuasDigitados';
            }

            /*=============================================
            DataTable de FUAS DIGITADOS
            =============================================*/
            tablaFuasDigitados = $("#tablaFuasDigitados").DataTable({
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
			            "title": 'FUAS DIGITADOS',
			            "filename": 'FUAS_DIGITADOS',
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
                    url: valorUrlAjaxFua_fuasDigitados,
                    data: { '_token' : $('input[name=_token]').val(),
                            'fechaInicio_fuasDigitados' : $('#fechaInicio_fuasDigitados').val(),
                            'fechaFin_fuasDigitados': $('#fechaFin_fuasDigitados').val()}
                },
				"columnDefs":[
		            {
			            className:"position",targets: [8],
			            "render": function ( data, type, row ) {
				            if(data == 1){
					            return '<i class="fas fa-check" style="color:green;text-align:center;"> DIGITADO</i>'
				            }else{ 
				                return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"> NO-DIGITADO</i>'
				            }
			            }
		            }
	            ],
                "order": [[5, "asc"]],
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
					{"data": 'IdAtencion',"name": 'IdAtencion'},
		            {"data": 'FUA',"name": 'FUA'},
					{"data": 'Paciente',"name": 'Paciente'},
		            {"data": 'HistoriaClinica',"name": 'HistoriaClinica'},
		            {"data": 'FechaHoraDigitacion',"name": 'FechaHoraDigitacion'},
		            {"data": 'FechaHoraAtencion',"name": 'FechaHoraAtencion'},
		            {"data": 'CodigoPrestacional',"name": 'CodigoPrestacional'},
		            {"data": 'Profesional',"name": 'Profesional'},
		            {"data": 'digitarFua_estado',"name": 'digitarFua_estado'},
		            {"data": 'name',"name": 'name'},
		            {"data": 'nombrePaquete_tramas',"name": 'nombrePaquete_tramas'}
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
		paciente_fuasDigitados(tablaFuasDigitados);
		profesionales_fuasDigitados(tablaFuasDigitados);
		fua_fuasDigitados(tablaFuasDigitados);
        codigoPrestacional_fuasDigitados(tablaFuasDigitados);
	}
            });

            /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasDigitados.search(this.value).draw();
	            }
            });
            /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});

$('#frmFechas_fuasDigitados').submit(function(e){
          
          e.preventDefault();

          tablaFuasDigitados.clear().destroy();

          $("#btnVerFUA_fuasDigitados").css("display","none");

          /* Petición AJAX */
          $.ajax({
            url: '{{ route('consultar.fechasFDigitados') }}',
            data: $("#frmFechas_fuasDigitados").serialize(),
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

		var selected = [];

        tablaFuasDigitados = $("#tablaFuasDigitados").DataTable({
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
		        "style":'multi'
	        },
	        "dom": 'Bfrtip',
            "buttons": [
		    {
			    "extend": 'excel',
			    "footer": false,
			    "title": 'FUAS DIGITADOS',
			    "filename": 'FUAS_DIGITADOS',
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
                "url": '{{ route('consultar.fechasFDigitados') }}',
                "data": {'_token' : $('input[name=_token]').val(),
                       'fechaInicio_fuasDigitados' : $('#fechaInicio_fuasDigitados').val(),
                       'fechaFin_fuasDigitados': $('#fechaFin_fuasDigitados').val()}
            },
			"columnDefs":[
		        {
			        className:"position",targets: [8],
			        "render": function ( data, type, row ) {
				        if(data == 1){
					        return '<i class="fas fa-check" style="color:green;text-align:center;"> DIGITADO</i>'
				        }else{ 
				            return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"> NO-DIGITADO</i>'
				        }
			        }
		        }
	        ],
            "order" : [[5, "asc"]],
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
				{"data": 'IdAtencion',"name": 'IdAtencion'},
		        {"data": 'FUA',"name": 'FUA'},
				{"data": 'Paciente',"name": 'Paciente'},
		        {"data": 'HistoriaClinica',"name": 'HistoriaClinica'},
		        {"data": 'FechaHoraDigitacion',"name": 'FechaHoraDigitacion'},
		        {"data": 'FechaHoraAtencion',"name": 'FechaHoraAtencion'},
		        {"data": 'CodigoPrestacional',"name": 'CodigoPrestacional'},
		        {"data": 'Profesional',"name": 'Profesional'},
		        {"data": 'digitarFua_estado',"name": 'digitarFua_estado'},
		        {"data": 'name',"name": 'name'},
		        {"data": 'nombrePaquete_tramas',"name": 'nombrePaquete_tramas'}
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
                        _:"%d filas seleccionadas",
				        0:"Haga click en una fila para seleccionarla.",
				        1:"Solo 1 fila seleccionada"
			        }
		        }
  	        },
	        initComplete: function () {
		        paciente_fuasDigitados(tablaFuasDigitados);
		        profesionales_fuasDigitados(tablaFuasDigitados);
		        fua_fuasDigitados(tablaFuasDigitados);
                codigoPrestacional_fuasDigitados(tablaFuasDigitados);
	        }/* ,
			rowCallback: function(row,data) {
            if ($.inArray(data.DT_RowId,selected) !== -1 ) {
                $(row).addClass('selected');
            }
        } */
        });


/* 		$('#tablaFuasDigitados tbody').on('click', 'tr', function () {
        var id = this[1];
		console.log(id);
        var index = $.inArray(id, selected);
 
        if ( index === -1 ) {
            selected.push(id);
        } else {
            selected.splice(index, 1 );
        }
 
        $(this).toggleClass('selected');
    } ); */

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
/*         $('div.dataTables_filter input').unbind();
        $("div.dataTables_filter input").keyup( function (e) {
	        if (e.keyCode == 13) {
		        tablaFuasDigitados.search(this.value).draw();
	        }
        }); */
        /* FIN DE BUSQUEDA POR ENTER */
});
</script>

<script type="text/javascript">
    $('#frmDigitarFua_fuasDigitados').submit(function(e){
        e.preventDefault();

        var data = tablaFuasDigitados.rows({selected:true}).data();
        var newarray=[];        
            for (var i=0; i < data.length ;i++){
                newarray.push(data[i]["Fua_id"]);
            }
 
        var sData = newarray.join();
		
        $("#idFua_fuasDigitados").val(newarray);

        swal({
            title: '¿Está seguro de digitar el FUA?',
            text: "¡Si no lo está puede cancelar la acción!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Si, digitar FUA!'
        }).then(function(result){
            if(result.value){
                $.ajax({
                    url: '{{ route('consultar.generadorTxt') }}',
                    method: "POST",
                    data: $("#frmDigitarFua_fuasDigitados").serialize(),
                    success:function(respuesta){
                        /* console.log("respuesta",respuesta); */

                        if(respuesta[0] == "PAQUETE-GENERADO-SATISFACTORIAMENTE"){
                            swal({
                                type:"success",
                                title: "¡El FUA ha sido digitado correctamente!",
                                text: "Nombre-Paquete: " + respuesta[1][0] + "," + "Id-Paquete: " + respuesta[2][0],
                                showConfirmButton: true,
                                confirmButtonText: "Cerrar"
                            }).then(function(result){
								if(result.value){
                                    tablaFuasDigitados.ajax.reload(null,false);
                                }
                            });
                        }

                        if(respuesta[0] == "ERROR-CODIFICACION"){
                            swal({
                                type:"error",
                                title: "¡Error al digitar los FUAS seleccionados!",
                                text: "Mensaje-Error: " + respuesta[1][0],
                                showConfirmButton: true,
                                confirmButtonText: "Cerrar"
                            })
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error(textStatus + " " + errorThrown);
                    }
                });
            }
        });
    });

	$('#frmAnularDigitacionFua_fuasDigitados').submit(function(e){
        e.preventDefault();

/*         var data = tablaFuasDigitados.rows({selected:true}).data();
        var newarray=[];        
            for (var i=0; i < data.length ;i++){
                newarray.push(data[i]["Fua_id"]);
            }
 
        var sData = newarray.join();
		
        $("#idFua_fuasDigitados").val(newarray); */

        swal({
            title: '¿Está seguro de eliminar la digitación?',
            text: "¡Si no lo está puede cancelar la acción!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Si, eliminar digitación!'
        }).then(function(result){
            if(result.value){
                $.ajax({
                    url: '{{ route('consultar.anularDigitacion') }}',
                    method: "POST",
                    data: $("#frmAnularDigitacionFua_fuasDigitados").serialize(),
                    success:function(respuesta){
                        /* console.log("respuesta",respuesta); */

                        if(respuesta == "anular-correcto"){
                            swal({
                                type:"success",
                                title: "¡El FUA ha sido anulado correctamente!",
                                /* text: "Nombre-Paquete: " + respuesta[1][0] + "," + "Id-Paquete: " + respuesta[2][0], */
                                showConfirmButton: true,
                                confirmButtonText: "Cerrar"
                            }).then(function(result){
								if(result.value){
									$("#btnAnularDigitacionFUA_fuasDigitados").css("display","none");
                                    tablaFuasDigitados.ajax.reload(null,false);
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

<script type="text/javascript">

$(function(){
    $('#tablaFuasDigitados tbody').on('click', 'tr', function (e) {
        e.preventDefault();

        if($(this).hasClass('selected')){
            /* $(this).removeClass('selected'); */
            /* $("#btnVerFUA_fuasDigitados").css("display","none"); */
			$("#idFuaA_fuasDigitados").val("");
			$("#btnAnularDigitacionFUA_fuasDigitados").css("display","none");

        }else{
            /* tablaFuasDigitados.$('tr.selected').removeClass('selected'); */
            $(this).addClass('selected');
            /* console.log(tablaFuasDigitados.row($(this)).data()["digitarFua_estado"]); */

            $("#idFuaA_fuasDigitados").val(tablaFuasDigitados.row($(this)).data()["Fua_id"]);

			if(tablaFuasDigitados.row($(this)).data()["digitarFua_estado"] == 1){
                $("#btnAnularDigitacionFUA_fuasDigitados").css("display","block");
			}else{
				$("#btnAnularDigitacionFUA_fuasDigitados").css("display","none");
			}

            $("#btnVerFUA_fuasDigitados").css("display","block");

/*             $('#btnVerFUA_fuasDigitados').unbind('click').on('click',function(e){

                e.preventDefault();

                $.ajax({
                url: ruta+'/fuasDigitados/generadorTxt',
                data: $("#frmDigitarFua_fuasDigitados").serialize(),
                success: function(respuesta){
                  console.log(respuesta);
                },

                error: function(jqXHR,textStatus,errorThrown){
                  console.error(textStatus + " " + errorThrown);
                }
              });

            }); */
        }
    });
});

</script>

@endsection

@section('javascript')
    <script src="{{ url('/') }}/js/fuasDigitados.js"></script>
@endsection

@endif

@endforeach