$.ajax({
    url: ruta + '/fuasDigitados',
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
var tablaFuasDigitados = $("#tablaFuasDigitados").DataTable({
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
        "url": ruta + '/fuasDigitados'
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
	}
});

/* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
/* $('div.dataTables_filter input').unbind();
$("div.dataTables_filter input").keyup( function (e) {
	if (e.keyCode == 13) {
		tablaFuasDigitados.search(this.value).draw();
	}
} ); */
/* FIN DE BUSQUEDA POR ENTER */

function fua_fuasDigitados(tablaFuasDigitados) {
	tablaFuasDigitados.columns(1).every(function() {
		var column = tablaFuasDigitados.column(this, {
			search: 'applied'
		});
		var select = $('<select class="form-control select2" name="fua_fuasDigitados" id="fua_fuasDigitados"><option value="">-- SELECCIONAR EL FUA --</option></select>')
			.appendTo($('#lista_fuaFuasDigitados').empty())
			.on('change', function() {
				var val = $.fn.dataTable.util.escapeRegex(
					$(this).val()
				);

				column
					.search(val ? '^' + val + '$' : '', true, false)
					.draw();
			});

			column.cells('', column[0]).render('display').sort().unique().each( function ( d, j ) {
				select.append( '<option value="'+d+'">'+d+'</option>' )
			} );

		var currSearch = column.search();
		
		if (currSearch) {
			select.val(currSearch.substring(1, currSearch.length - 1));
		}

		$('.select2').select2();
	});
}

function profesionales_fuasDigitados(tablaFuasDigitados) {
	tablaFuasDigitados.columns(7).every(function() {
		var column = tablaFuasDigitados.column(this, {
			search: 'applied'
		});
		var select = $('<select class="form-control select2 select-2" name="profesional_fuasDigitados" id="profesional_fuasDigitados"><option value="">-- SELECCIONAR EL PROFESIONAL --</option></select>')
			.appendTo($('#lista_profesionalesFuasDigitados').empty())
			.on('change', function() {
				var val = $.fn.dataTable.util.escapeRegex(
					$(this).val()
				);

				column
					.search(val ? '^' + val + '$' : '', true, false)
					.draw();
			});

			column.cells('', column[0]).render('display').sort().unique().each( function ( d, j ) {
				select.append( '<option value="'+d+'">'+d+'</option>' )
			} );

		var currSearch = column.search();

		if (currSearch) {
			select.val(currSearch.substring(1, currSearch.length - 1));
		}

		$('.select2').select2();
	});
}

function paciente_fuasDigitados(tablaFuasDigitados) {
	tablaFuasDigitados.columns(2).every(function() {
		var column = tablaFuasDigitados.column(this, {
			search: 'applied'
		});
		
		var select = $('<select class="form-control select2 select-2" name="paciente_fuasDigitados" id="paciente_fuasDigitados"><option value="">-- SELECCIONAR EL PACIENTE --</option></select>')
			.appendTo($('#lista_pacienteFuasDigitados').empty())
			.on('change', function() {
				var val = $.fn.dataTable.util.escapeRegex(
					$(this).val()
				);

				column
					.search(val ? '^' + val + '$' : '', true, false)
					.draw();
			});

			column.cells('', column[0]).render('display').sort().unique().each( function ( d, j ){
				select.append( '<option value="'+d+'">'+d+'</option>' )
			});

		var currSearch = column.search();

		if (currSearch) {
			select.val(currSearch.substring(1, currSearch.length - 1));
		}

		$('.select2').select2();
	});
}

function codigoPrestacional_fuasDigitados(tablaFuasDigitados) {
	tablaFuasDigitados.columns(6).every(function() {
		var column = tablaFuasDigitados.column(this, {
			search: 'applied'
		});
		
		var select = $('<select class="form-control select2 select-2" name="codigoPrestacional_fuasDigitados" id="codigoPrestacional_fuasDigitados"><option value="">-- SELECCIONAR EL CÓDIGO PRESTACIONAL --</option></select>')
			.appendTo($('#lista_codigoPrestacionalFuasDigitados').empty())
			.on('change', function() {
				var val = $.fn.dataTable.util.escapeRegex(
					$(this).val()
				);

				column
					.search(val ? '^' + val + '$' : '', true, false)
					.draw();
			});

			column.cells('', column[0]).render('display').sort().unique().each( function ( d, j ){
				select.append( '<option value="'+d+'">'+d+'</option>' )
			});

		var currSearch = column.search();

		if (currSearch) {
			select.val(currSearch.substring(1, currSearch.length - 1));
		}

		$('.select2').select2();
	});
}