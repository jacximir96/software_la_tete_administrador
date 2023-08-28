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
                    <h1>Fuas Acervo</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
                        <li class="breadcrumb-item active">Fuas Acervo</li>
                    </ol>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <div class="input-group">
                        <label for="lista_pacienteFuasAcervo" class="col-md-2 control-label">Paciente:</label>
                        <div class="col-md-4" id="lista_pacienteFuasAcervo"><!-- Información desde Javascript (FUASMedica.js) --></div>
                        <label for="lista_fuaFuasAcervo" class="col-md-2 control-label">N° FUA:</label>
                        <div class="col-md-4" id="lista_fuaFuasAcervo"><!-- Información desde Javascript (FUASMedica.js) --></div>
                    </div>

                    <div class="input-group">
                        <label for="lista_profesionalesFuasAcervo" class="col-md-2 control-label">Profesional:</label>
                        <div class="col-md-4" id="lista_profesionalesFuasAcervo"><!-- Información desde Javascript (FUASMedica.js) --></div>
                        <label for="lista_codigoPrestacionalFuasAcervo" class="col-md-2 control-label">Cod. Prestacional:</label>
                        <div class="col-md-4" id="lista_codigoPrestacionalFuasAcervo"><!-- Información desde Javascript (FUASMedica.js) --></div>
                    </div>

                    <form method="GET" action="{{ url('/') }}/fuasAcervo/buscarPorMes" id="frmFechas_fuasAcervo">
                        @csrf
                        <div class="input-group">
                            <label for="email" class="col-md-2 control-label">Fecha de atención:</label>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="fechaInicio_fuasAcervo" id="fechaInicio_fuasAcervo"
                                style="text-transform: uppercase;" required>
                            </div>

                            <div class="col-md-2">
                                <input type="date" class="form-control" name="fechaFin_fuasAcervo" id="fechaFin_fuasAcervo"
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

                            <label for="historiaBD_fuasAcervo" class="col-md-1 control-label" style="">N° Historia:</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control inputRutaNumero" name="historiaBD_fuasAcervo" id="historiaBD_fuasAcervo"
                                style="text-transform: uppercase;" maxlength="6">
                            </div>

                            <label for="documentoBD_fuasAcervo" class="col-md-1 control-label">N° Documento:</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control inputRutaNumero" name="documentoBD_fuasAcervo" id="documentoBD_fuasAcervo"
                                style="text-transform: uppercase;" maxlength="9">
                            </div>
                            
                            <label for="fuaBD_fuasAcervo" class="col-md-1 control-label">N° FUA:</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control inputRutaNumero" name="fuaBD_fuasAcervo" id="fuaBD_fuasAcervo"
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
                
                            <form method="POST" action="{{ url('/') }}/fuasAcervo/generadorTxt" id="frmDigitarFua_fuasAcervo">
                            @csrf
                                <input type="text" class="form-control" name="idFua_fuasAcervo" id="idFua_fuasAcervo"
                                style="text-transform: uppercase;display:none;">

                                <button type="submit" class="btn btn-danger btn-sm boton-general" data-toggle="modal" data-target="#verFua_fuasAcervo"  
                                style="float:left;display:none;margin-right: 5px;" id="btnVerFUA_fuasAcervo"> 
							    <i class="fas fa-eye" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Acervo FUA</button>
                            </form>

							<form method="POST" action="{{ url('/') }}/fuasAcervo/anularDigitacion" id="frmAnularDigitacionFua_fuasAcervo">
                            @csrf
                                <input type="text" class="form-control" name="idFuaA_fuasAcervo" id="idFuaA_fuasAcervo"
                                style="text-transform: uppercase;display:none;">

                                <button type="submit" class="btn btn-warning btn-sm boton-general" data-toggle="modal" data-target="#verFua_fuasAcervo"  
                                style="float:left;display:none;margin-right: 5px;" id="btnAnularDigitacionFUA_fuasAcervo"> 
							    <i class="fas fa-times" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Anular Digitación</button>
                            </form>

                            <form method="GET" action="" id="frmVerRolCitas_fuasAcervo">
                                @csrf
                                <input type="text" class="form-control" name="idCab_fuasAcervo" id="idCab_fuasAcervo"
                                style="text-transform: uppercase;display:none;" required>

                                <button type="submit" class="btn btn-dark btn-sm boton-general" data-toggle="modal" data-target="#verRolCitas_fuasAcervo"  
                                style="float:left;display:none;margin-right: 5px;" id="btnRolCitas_fuasAcervo"> 
							    <i class="fa fa-tasks" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Rol de Citas</button>
                            </form>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered table-striped display nowrap" width="100%" id="tablaFuasAcervo">
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

$("#historiaBD_fuasAcervo").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        var numHistoriaBD = $("#historiaBD_fuasAcervo").val();
        
        if (numHistoriaBD != ''){

            tablaFuasAcervo.clear().destroy();

            $("#btnVerFUA_fuasAcervo").css("display","none");
            $("#documentoBD_fuasAcervo").val("");
            $("#fuaBD_fuasAcervo").val("");

            $.ajax({
                url: '{{ route('consultar.historiaBD_fuasAcervo') }}',
                data: {numHistoriaBD},
                success: function(respuesta){
                    /* console.log("respuesta",respuesta); */
                },

                error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                }
            });

        /*=============================================
        DataTable de FUAS Acervo
        =============================================*/
        tablaFuasAcervo = $("#tablaFuasAcervo").DataTable({
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
			    "title": 'FUAS ACERVO GENERAL',
			    "filename": 'FUAS_ACERVO_GENERAL',
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
                "url": '{{ route('consultar.historiaBD_fuasAcervo') }}',
                "data": {'numHistoriaBD' : $("#historiaBD_fuasAcervo").val()}
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
		paciente_fuasAcervo(tablaFuasAcervo);
		profesionales_fuasAcervo(tablaFuasAcervo);
		fua_fuasAcervo(tablaFuasAcervo);
        codigoPrestacional_fuasAcervo(tablaFuasAcervo);
	}
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasAcervo.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */

        }else{
            $('#historiaBD_fuasAcervo').focus();
            tablaFuasAcervo.clear().destroy();
            $("#btnVerFUA_fuasAcervo").css("display","none");

            let valorUrlAjaxHistoria_fuasAcervo = '';

            if($('#fechaInicio_fuasAcervo').val() != '' || $('#fechaFin_fuasAcervo').val() != ''){
                valorUrlAjaxHistoria_fuasAcervo = '{{ route('consultar.fechasFAcervo') }}';
            }else{
                valorUrlAjaxHistoria_fuasAcervo = ruta + '/fuasAcervo';
            }

            /*=============================================
            DataTable de FUAS Acervo
            =============================================*/
            tablaFuasAcervo = $("#tablaFuasAcervo").DataTable({
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
			            "title": 'FUAS ACERVO',
			            "filename": 'FUAS_ACERVO',
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
                    "url": valorUrlAjaxHistoria_fuasAcervo,
                    "data":{'_token' : $('input[name=_token]').val(),
                            'fechaInicio_fuasAcervo' : $('#fechaInicio_fuasAcervo').val(),
                            'fechaFin_fuasAcervo': $('#fechaFin_fuasAcervo').val()}
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
		paciente_fuasAcervo(tablaFuasAcervo);
		profesionales_fuasAcervo(tablaFuasAcervo);
		fua_fuasAcervo(tablaFuasAcervo);
        codigoPrestacional_fuasAcervo(tablaFuasAcervo);
	}
            });

            /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasAcervo.search(this.value).draw();
	            }
            });
            /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});

$("#documentoBD_fuasAcervo").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        var numDocumentoBD = $("#documentoBD_fuasAcervo").val();
        
        if (numDocumentoBD != ''){

            tablaFuasAcervo.clear().destroy();

            $("#btnVerFUA_fuasAcervo").css("display","none");
            $("#historiaBD_fuasAcervo").val("");
            $("#fuaBD_fuasAcervo").val("");

            $.ajax({
                url: '{{ route('consultar.documentoBD_fuasAcervo') }}',
                data: {numDocumentoBD},
                success: function(respuesta){
                    /* console.log("respuesta",respuesta); */
                },

                error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                }
            });

        /*=============================================
        DataTable de FUAS Acervo
        =============================================*/
        tablaFuasAcervo = $("#tablaFuasAcervo").DataTable({
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
			    "title": 'FUAS Acervo GENERAL',
			    "filename": 'FUAS_Acervo_GENERAL',
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
                "url": '{{ route('consultar.documentoBD_fuasAcervo') }}',
                "data": {'numDocumentoBD' : $("#documentoBD_fuasAcervo").val()}
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
		paciente_fuasAcervo(tablaFuasAcervo);
		profesionales_fuasAcervo(tablaFuasAcervo);
		fua_fuasAcervo(tablaFuasAcervo);
        codigoPrestacional_fuasAcervo(tablaFuasAcervo);
	}
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
        $('div.dataTables_filter input').unbind();
        $("div.dataTables_filter input").keyup( function (e) {
	        if (e.keyCode == 13) {
		        tablaFuasAcervo.search(this.value).draw();
	        }
        });
        /* FIN DE BUSQUEDA POR ENTER */

        }else{
            $('#documentoBD_fuasAcervo').focus();

            tablaFuasAcervo.clear().destroy();
            $("#btnVerFUA_fuasAcervo").css("display","none");

            let valorUrlAjaxDocumento_fuasAcervo = '';

            if($('#fechaInicio_fuasAcervo').val() != '' || $('#fechaFin_fuasAcervo').val() != ''){
                valorUrlAjaxDocumento_fuasAcervo = '{{ route('consultar.fechasFAcervo') }}';
            }else{
                valorUrlAjaxDocumento_fuasAcervo = ruta + '/fuasAcervo';
            }

            /*=============================================
            DataTable de FUAS Acervo
            =============================================*/
            tablaFuasAcervo = $("#tablaFuasAcervo").DataTable({
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
			            "title": 'FUAS Acervo',
			            "filename": 'FUAS_Acervo',
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
                    "url": valorUrlAjaxDocumento_fuasAcervo,
                    "data": { '_token' : $('input[name=_token]').val(),
                            'fechaInicio_fuasAcervo' : $('#fechaInicio_fuasAcervo').val(),
                            'fechaFin_fuasAcervo': $('#fechaFin_fuasAcervo').val()}
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
		paciente_fuasAcervo(tablaFuasAcervo);
		profesionales_fuasAcervo(tablaFuasAcervo);
		fua_fuasAcervo(tablaFuasAcervo);
        codigoPrestacional_fuasAcervo(tablaFuasAcervo);
	}
            });

            /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasAcervo.search(this.value).draw();
	            }
            });
            /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});

$("#fuaBD_fuasAcervo").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();

        var numFuaBD = $("#fuaBD_fuasAcervo").val();
        
        if (numFuaBD != ''){

            tablaFuasAcervo.clear().destroy();

            $("#btnVerFUA_fuasAcervo").css("display","none");
            $("#historiaBD_fuasAcervo").val("");
            $("#documentoBD_fuasAcervo").val("");

            $.ajax({
                url: '{{ route('consultar.fuaBD_fuasAcervo') }}',
                data: {numFuaBD},
                success: function(respuesta){
                    /* console.log("respuesta",respuesta); */
                },

                error: function(jqXHR,textStatus,errorThrown){
                    console.error(textStatus + " " + errorThrown);
                }
            });

        /*=============================================
        DataTable de FUAS Acervo
        =============================================*/
        tablaFuasAcervo = $("#tablaFuasAcervo").DataTable({
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
			    "title": 'FUAS Acervo GENERAL',
			    "filename": 'FUAS_Acervo_GENERAL',
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
                "url": '{{ route('consultar.fuaBD_fuasAcervo') }}',
                "data": {'numFuaBD' : $("#fuaBD_fuasAcervo").val()}
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
		paciente_fuasAcervo(tablaFuasAcervo);
		profesionales_fuasAcervo(tablaFuasAcervo);
		fua_fuasAcervo(tablaFuasAcervo);
        codigoPrestacional_fuasAcervo(tablaFuasAcervo);
	}
        });

        /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasAcervo.search(this.value).draw();
	            }
            });
        /* FIN DE BUSQUEDA POR ENTER */

        }else{
            $('#fuaBD_fuasAcervo').focus();
            tablaFuasAcervo.clear().destroy();
            $("#btnVerFUA_fuasAcervo").css("display","none");

            let valorUrlAjaxFua_fuasAcervo = '';

            if($('#fechaInicio_fuasAcervo').val() != '' || $('#fechaFin_fuasAcervo').val() != ''){
                valorUrlAjaxFua_fuasAcervo = '{{ route('consultar.fechasFAcervo') }}';
            }else{
                valorUrlAjaxFua_fuasAcervo = ruta + '/fuasAcervo';
            }

            /*=============================================
            DataTable de FUAS Acervo
            =============================================*/
            tablaFuasAcervo = $("#tablaFuasAcervo").DataTable({
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
			            "title": 'FUAS Acervo',
			            "filename": 'FUAS_Acervo',
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
                    url: valorUrlAjaxFua_fuasAcervo,
                    data: { '_token' : $('input[name=_token]').val(),
                            'fechaInicio_fuasAcervo' : $('#fechaInicio_fuasAcervo').val(),
                            'fechaFin_fuasAcervo': $('#fechaFin_fuasAcervo').val()}
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
		paciente_fuasAcervo(tablaFuasAcervo);
		profesionales_fuasAcervo(tablaFuasAcervo);
		fua_fuasAcervo(tablaFuasAcervo);
        codigoPrestacional_fuasAcervo(tablaFuasAcervo);
	}
            });

            /* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
            $('div.dataTables_filter input').unbind();
            $("div.dataTables_filter input").keyup( function (e) {
	            if (e.keyCode == 13) {
		            tablaFuasAcervo.search(this.value).draw();
	            }
            });
            /* FIN DE BUSQUEDA POR ENTER */
        }
    }
});

$('#frmFechas_fuasAcervo').submit(function(e){
          
          e.preventDefault();

          tablaFuasAcervo.clear().destroy();

          $("#btnVerFUA_fuasAcervo").css("display","none");

          /* Petición AJAX */
          $.ajax({
            url: '{{ route('consultar.fechasFAcervo') }}',
            data: $("#frmFechas_fuasAcervo").serialize(),
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

        tablaFuasAcervo = $("#tablaFuasAcervo").DataTable({
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
			    "title": 'FUAS Acervo',
			    "filename": 'FUAS_Acervo',
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
                "url": '{{ route('consultar.fechasFAcervo') }}',
                "data": {'_token' : $('input[name=_token]').val(),
                       'fechaInicio_fuasAcervo' : $('#fechaInicio_fuasAcervo').val(),
                       'fechaFin_fuasAcervo': $('#fechaFin_fuasAcervo').val()}
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
		        paciente_fuasAcervo(tablaFuasAcervo);
		        profesionales_fuasAcervo(tablaFuasAcervo);
		        fua_fuasAcervo(tablaFuasAcervo);
                codigoPrestacional_fuasAcervo(tablaFuasAcervo);
	        }/* ,
			rowCallback: function(row,data) {
            if ($.inArray(data.DT_RowId,selected) !== -1 ) {
                $(row).addClass('selected');
            }
        } */
        });


/* 		$('#tablaFuasAcervo tbody').on('click', 'tr', function () {
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
		        tablaFuasAcervo.search(this.value).draw();
	        }
        }); */
        /* FIN DE BUSQUEDA POR ENTER */
});
</script>

@endsection

@section('javascript')
    <script src="{{ url('/') }}/js/fuasAcervo.js"></script>
@endsection

@endif

@endforeach