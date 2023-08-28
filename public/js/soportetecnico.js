/*=============================================
DataTable Servidor de Direcciones Ejecutivas
=============================================*/

/* Petición AJAX */
$.ajax({
    url: ruta + '/soporteTecnico',
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
let tablaSoporteTecnico = $("#tablaSoporteTecnico").DataTable({
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
/* 	lengthMenu: [
		[ 10, 25, 50, -1 ],
		[ '10 rows', '25 rows', '50 rows', 'Show all' ]
	], */
	"dom": 'Bfrtip',
	"buttons": [{
		"extend": 'excel',
		"footer": false,
		"title": 'SOPORTE TECNICO',
		"filename": 'SOPORTE_TECNICO',
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
        "url": ruta + '/soporteTecnico'
    },
/* 	"columnDefs":[
		{
			className:"position",targets: [8],
			"render": function ( data, type, row ) {
				if(data == 1){
					return '<i class="fas fa-check" style="color:green;text-align:center;"> GENERADO</i>'
				}else if (data == 0){
					return '<i class="fa fa-times" aria-hidden="true" style="color:red;text-align:center;"> ANULADO</i>'
				}else{
					return ''
				}
			}
		}
	], */
    "order": [[0, "asc"]],
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
        {"data": 'IdSoporteTecnico',"name": 'IdSoporteTecnico'},
		{"data": 'Descripcion',"name": 'Descripcion'},
		{"data": 'Prioridad',"name": 'Prioridad'},
		{"data": 'FechaCreado',"name": 'FechaCreado'},
		{"data": 'FechaResuelto',"name": 'FechaResuelto'},
		{"data": 'Estado',"name": 'Estado'},
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
		paciente_soporteTecnico(tablaSoporteTecnico);
		profesionales_soporteTecnico(tablaSoporteTecnico);
		fua_soporteTecnico(tablaSoporteTecnico);
        codigoPrestacional_soporteTecnico(tablaSoporteTecnico);
	}
});

/* MEDIANTE ESTA FUNCIÓN SOLO SE EXIGE EL ENTER PARA LA BUSQUEDA */
$('div.dataTables_filter input').unbind();
$("div.dataTables_filter input").keyup( function (e) {
	if (e.keyCode == 13) {
		tablaFuasEmitidosG.search(this.value).draw();
	}
} );
/* FIN DE BUSQUEDA POR ENTER */

function fua_soporteTecnico(tablaSoporteTecnico) {
	tablaSoporteTecnico.columns(0).every(function() {
		var column = tablaSoporteTecnico.column(this, {
			search: 'applied'
		});
		var select = $('<select class="form-control select2 select-2" name="fua_soporteTecnico" id="fua_soporteTecnico""><option value="">-- SELECCIONAR EL FUA --</option></select>')
			.appendTo($('#lista_idSoporteTecnico').empty())
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

function profesionales_soporteTecnico(tablaSoporteTecnico) {
	tablaSoporteTecnico.columns(2).every(function() {
		var column = tablaSoporteTecnico.column(this, {
			search: 'applied'
		});
		var select = $('<select class="form-control select2 select-2" name="profesional_soporteTecnico" id="profesional_soporteTecnico"><option value="">-- SELECCIONAR EL PROFESIONAL --</option></select>')
			.appendTo($('#lista_prioridadSoporteTecnico').empty())
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

function paciente_soporteTecnico(tablaSoporteTecnico) {
	tablaSoporteTecnico.columns(3).every(function() {
		var column = tablaSoporteTecnico.column(this, {
			search: 'applied'
		});
		
		var select = $('<select class="form-control select2 select-2" name="paciente_soporteTecnico" id="paciente_soporteTecnico"><option value="">-- SELECCIONAR EL PACIENTE --</option></select>')
			.appendTo($('#lista_fechaCreacionSoporteTecnico').empty())
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

function codigoPrestacional_soporteTecnico(tablaSoporteTecnico) {
	tablaSoporteTecnico.columns(4).every(function() {
		var column = tablaSoporteTecnico.column(this, {
			search: 'applied'
		});
		
		var select = $('<select class="form-control select2" name="codigoPrestacional_soporteTecnico" id="codigoPrestacional_soporteTecnico"><option value="">-- SELECCIONAR EL CÓDIGO PRESTACIONAL --</option></select>')
			.appendTo($('#lista_fechaResolucionSoporteTecnico').empty())
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

		$('.select2').select2({
			language: {
		  
			  noResults: function() {
		  
				return "No hay resultado";        
			  },
			  searching: function() {
		  
				return "Buscando..";
			  }
			}
		});
	});
}

