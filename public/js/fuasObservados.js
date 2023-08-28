/*=============================================
DataTable Servidor de Direcciones Ejecutivas
=============================================*/

/* Petición AJAX */
$.ajax({
    url: ruta + '/fuasObservados',
    success: function(respuesta){
        console.log("respuesta",respuesta);
    },

    error: function(jqXHR,textStatus,errorThrown){
        console.error(textStatus + " " + errorThrown);
    }
})
/* Fin de Petición AJAX */

/*=============================================
DataTable de Fuas Observados
=============================================*/
var tablaFuasObservados = $("#tablaFuasObservados").DataTable({
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
			"title": 'FUA-SIS CICLOS',
			"filename": 'FUAS_CICLOS',
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
        "url": ruta + '/fuasObservados'
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

function grupoProfesionales_fuasObservados(tablaFuasObservados) {
	tablaFuasObservados.columns(14).every(function() {
		var column = tablaFuasObservados.column(this, {
			search: 'applied'
		});
		var select = $('<select class="form-control select2" name="grupoProfesional_fuasObservados" id="grupoProfesional_fuasObservados"><option value="">-- SELECCIONAR EL GRUPO PROFESIONAL --</option></select>')
			.appendTo($('#lista_grupoProfesional2').empty())
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

function pacientes_fuasObservados(tablaFuasObservados) {
	tablaFuasObservados.columns(5).every(function() {
		var column = tablaFuasObservados.column(this, {
			search: 'applied'
		});
		var select = $('<select class="form-control select2" name="paciente_fuasObservados" id="paciente_fuasObservados"><option value="">-- SELECCIONAR EL PACIENTE --</option></select>')
			.appendTo($('#lista_pacientes2').empty())
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

function profesionales_fuasObservados(tablaFuasObservados) {
	tablaFuasObservados.columns(11).every(function() {
		var column = tablaFuasObservados.column(this, {
			search: 'applied'
		});
		var select = $('<select class="form-control select2 select-2" name="profesional_fuasObservados" id="profesional_fuasObservados"><option value="">-- SELECCIONAR EL PROFESIONAL --</option></select>')
			.appendTo($('#lista_profesionales2').empty())
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

function unidadOrganica_fuasObservados(tablaFuasObservados) {
	tablaFuasObservados.columns(13).every(function() {
		var column = tablaFuasObservados.column(this, {
			search: 'applied'
		});
		
		var select = $('<select class="form-control select2 select-2" name="unidadOrganica_fuasObservados" id="unidadOrganica_fuasObservados"><option value="">-- SELECCIONAR LA UNIDAD ORGÁNICA --</option></select>')
			.appendTo($('#lista_unidadOrganica2').empty())
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

