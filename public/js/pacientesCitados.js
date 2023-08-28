/*=============================================
DataTable Servidor de Direcciones Ejecutivas
=============================================*/

/* Petición AJAX */
$.ajax({
    url: ruta + '/pacientesCitados',
    success: function(respuesta){
        console.log("respuesta",respuesta);
    },

    error: function(jqXHR,textStatus,errorThrown){
        console.error(textStatus + " " + errorThrown);
    }
});
/* Fin de Petición AJAX */

/*=============================================
DataTable de Pacientes Citados
=============================================*/
let tablaPacientesCitados = $("#tablaPacientesCitados").DataTable({
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
        "url": ruta + '/pacientesCitados'
    },
    "order" : [[2, "asc"]],
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
		{"data": 'UnidadOrganica',"name": 'UnidadOrganica'/*,"targets": [ 11 ],"visible": false*/},
		{"data": 'GrupoProfesional',"name": 'GrupoProfesional',"targets": [ 12 ],"visible": false},
    ],
    "language" : {
	    "sProcessing": "<div class='spinner'></div>",
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
} );
/* FIN DE BUSQUEDA POR ENTER */

function grupoProfesionales_pacientesCitados(tablaPacientesCitados) {
	tablaPacientesCitados.columns(12).every(function() {
		var column = tablaPacientesCitados.column(this, {
			search: 'applied'
		});
		var select = $('<select class="form-control select2" name="grupoProfesional_pacientesCitados" id="grupoProfesional_pacientesCitados"><option value="">-- SELECCIONAR EL GRUPO PROFESIONAL --</option></select>')
			.appendTo($('#lista_grupoProfesional').empty())
			.on('change', function() {
				let val = $.fn.dataTable.util.escapeRegex(
					$(this).val()
				);

				column
					.search(val ? '^' + val + '$' : '', true, false)
					.draw();
			});

			column.cells('', column[0]).render('display').sort().unique().each( function ( d, j ) {
				select.append( '<option value="'+d+'">'+d+'</option>' )
			} );

		let currSearch = column.search();

		if (currSearch) {
			select.val(currSearch.substring(1, currSearch.length - 1));
		}

		$('.select2').select2();
	});
}

function profesionales_pacientesCitados(tablaPacientesCitados) {
	tablaPacientesCitados.columns(9).every(function() {
		let column = tablaPacientesCitados.column(this, {
			search: 'applied'
		});
		let select = $('<select class="form-control select2 select-2" name="profesional_pacientesCitados" id="profesional_pacientesCitados"><option value="">-- SELECCIONAR EL PROFESIONAL --</option></select>')
			.appendTo($('#lista_profesionales').empty())
			.on('change', function() {
				let val = $.fn.dataTable.util.escapeRegex(
					$(this).val()
				);

				column
					.search(val ? '^' + val + '$' : '', true, false)
					.draw();
			});

			column.cells('', column[0]).render('display').sort().unique().each( function ( d, j ) {
				select.append( '<option value="'+d+'">'+d+'</option>' )
			} );

		let currSearch = column.search();

		if (currSearch) {
			select.val(currSearch.substring(1, currSearch.length - 1));
		}

		$('.select2').select2();
	});
}

function unidadOrganica_pacientesCitados(tablaPacientesCitados) {
	tablaPacientesCitados.columns(11).every(function() {
		let column = tablaPacientesCitados.column(this, {
			search: 'applied'
		});

		let select = $('<select class="form-control select2 select-2" name="unidadOrganica_pacientesCitados" id="unidadOrganica_pacientesCitados"><option value="">-- SELECCIONAR LA UNIDAD ORGÁNICA --</option></select>')
			.appendTo($('#lista_unidadOrganica').empty())
			.on('change', function() {
				let val = $.fn.dataTable.util.escapeRegex(
					$(this).val()
				);

				column
					.search(val ? '^' + val + '$' : '', true, false)
					.draw();
			});

			column.cells('', column[0]).render('display').sort().unique().each( function ( d, j ){
				select.append( '<option value="'+d+'">'+d+'</option>' )
			});

		let currSearch = column.search();

		if (currSearch) {
			select.val(currSearch.substring(1, currSearch.length - 1));
		}

		$('.select2').select2();
	});
}

/* tablaPacientesCitados.draw(); */

/* document.oncontextmenu = function() {return false;}; */
