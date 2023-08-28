$.ajax({
    url: ruta + '/fuasAcervo',
    success: function(respuesta){
        console.log("respuesta",respuesta);
    },

    error: function(jqXHR,textStatus,errorThrown){
        console.error(textStatus + " " + errorThrown);
    }
});

/*=============================================
DataTable de FUAS DIGITADOS
=============================================*/
var tablaFuasAcervo = $("#tablaFuasAcervo").DataTable({
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
		"style":'multi'
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
        "url": ruta + '/fuasAcervo'
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
	}
});

/* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
$('div.dataTables_filter input').unbind();
$("div.dataTables_filter input").keyup( function (e) {
	if (e.keyCode == 13) {
		tablaFuasAcervo.search(this.value).draw();
	}
} );
/* FIN DE BUSQUEDA POR ENTER */

function fua_fuasAcervo(tablaFuasAcervo) {
	tablaFuasAcervo.columns(1).every(function() {
		var column = tablaFuasAcervo.column(this, {
			search: 'applied'
		});
		var select = $('<select class="form-control select2" name="fua_fuasAcervo" id="fua_fuasAcervo"><option value="">-- SELECCIONAR EL FUA --</option></select>')
			.appendTo($('#lista_fuaFuasAcervo').empty())
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

function profesionales_fuasAcervo(tablaFuasAcervo) {
	tablaFuasAcervo.columns(7).every(function() {
		var column = tablaFuasAcervo.column(this, {
			search: 'applied'
		});
		var select = $('<select class="form-control select2 select-2" name="profesional_fuasAcervo" id="profesional_fuasAcervo"><option value="">-- SELECCIONAR EL PROFESIONAL --</option></select>')
			.appendTo($('#lista_profesionalesFuasAcervo').empty())
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

function paciente_fuasAcervo(tablaFuasAcervo) {
	tablaFuasAcervo.columns(2).every(function() {
		var column = tablaFuasAcervo.column(this, {
			search: 'applied'
		});
		
		var select = $('<select class="form-control select2 select-2" name="paciente_fuasAcervo" id="paciente_fuasAcervo"><option value="">-- SELECCIONAR EL PACIENTE --</option></select>')
			.appendTo($('#lista_pacienteFuasAcervo').empty())
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

function codigoPrestacional_fuasAcervo(tablaFuasAcervo) {
	tablaFuasAcervo.columns(6).every(function() {
		var column = tablaFuasAcervo.column(this, {
			search: 'applied'
		});
		
		var select = $('<select class="form-control select2 select-2" name="codigoPrestacional_fuasAcervo" id="codigoPrestacional_fuasAcervo"><option value="">-- SELECCIONAR EL CÓDIGO PRESTACIONAL --</option></select>')
			.appendTo($('#lista_codigoPrestacionalFuasAcervo').empty())
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