/*=============================================
DataTable Servidor de Direcciones Ejecutivas
=============================================*/

/* Petición AJAX */
$.ajax({
    url: ruta + '/fuasEmitidos',
    success: function(respuesta){
        console.log("respuesta",respuesta);
    },

    error: function(jqXHR,textStatus,errorThrown){
        console.error(textStatus + " " + errorThrown);
    }
})
/* Fin de Petición AJAX */

/*=============================================
DataTable de Pacientes Citados
=============================================*/
var tablaFuasEmitidos = $("#tablaFuasEmitidos").DataTable({
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
        "url": ruta + '/fuasEmitidos'
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

function grupoProfesionales(tablaFuasEmitidos) {
	tablaFuasEmitidos.columns(14).every(function() {
		var column = tablaFuasEmitidos.column(this, {
			search: 'applied'
		});
		var select = $('<select class="form-control select2" name="grupoProfesional_fuasEmitidos" id="grupoProfesional_fuasEmitidos"><option value="">-- SELECCIONAR EL GRUPO PROFESIONAL --</option></select>')
			.appendTo($('#lista_grupoProfesional1').empty())
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

function pacientes(tablaFuasEmitidos) {
	tablaFuasEmitidos.columns(5).every(function() {
		var column = tablaFuasEmitidos.column(this, {
			search: 'applied'
		});
		var select = $('<select class="form-control select2" name="paciente_fuasEmitidos" id="paciente_fuasEmitidos"><option value="">-- SELECCIONAR EL PACIENTE --</option></select>')
			.appendTo($('#lista_paciente1').empty())
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

function profesionales(tablaFuasEmitidos) {
	tablaFuasEmitidos.columns(11).every(function() {
		var column = tablaFuasEmitidos.column(this, {
			search: 'applied'
		});
		var select = $('<select class="form-control select2 select-2" name="profesional_fuasEmitidos" id="profesional_fuasEmitidos"><option value="">-- SELECCIONAR EL PROFESIONAL --</option></select>')
			.appendTo($('#lista_profesionales1').empty())
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

function unidadOrganica(tablaFuasEmitidos) {
	tablaFuasEmitidos.columns(13).every(function() {
		var column = tablaFuasEmitidos.column(this, {
			search: 'applied'
		});
		
		var select = $('<select class="form-control select2 select-2" name="unidadOrganica_fuasEmitidos" id="unidadOrganica_fuasEmitidos"><option value="">-- SELECCIONAR LA UNIDAD ORGÁNICA --</option></select>')
			.appendTo($('#lista_unidadOrganica1').empty())
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

