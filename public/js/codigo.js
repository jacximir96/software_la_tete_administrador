
/*  Capturar ruta de mi servidor */
var ruta = $("#ruta").val();

/*=====================================
Ocultar usuarios
======================================*/
$(".usuarios_general").css("display","none");
/*=====================================*/

/* Validamos el formulario de agregar movimiento */
$("#generarFuaLibre_pacientesCitados").validate({
	rules:{
		personalAtiendeFL_pacientesCitados:{
			required:true
		},

		lugarAtencionFL_pacientesCitados:{
			required:true
		},

		codigoReferenciaFL_pacientesCitados:{
			required:true
		},

		descripcionReferenciaFL_pacientesCitados:{
			required:true
		},

		numeroReferenciaFL_pacientesCitados:{
			required:true
		},

		tipoDocumentoFL_pacientesCitados:{
			required:true
		},

		numeroDocumentoFL_pacientesCitados:{
			required:true
		},

		componenteFL_pacientesCitados:{
			required:true
		},

		codigoAsegurado2FL_pacientesCitados:{
			required:true
		},

		codigoAsegurado3FL_pacientesCitados:{
			required:true
		},

		apellidoPaternoFL_pacientesCitados:{
			required:true
		},

		apellidoMaternoFL_pacientesCitados:{
			required:true
		},

		primerNombreFL_pacientesCitados:{
			required:true
		},

		sexoFL_pacientesCitados:{
			required:true
		},

		fechaNacimientoFL_pacientesCitados:{
			required:true
		},

		historiaFL_pacientesCitados:{
			required:true
		},

		codigoPrestacionalFL_pacientesCitados:{
			required:true
		},

		conceptoPrestacionalFL_pacientesCitados:{
			required:true
		},

		destinoAseguradoFL_pacientesCitados:{
			required:true
		},

		personalFL_pacientesCitados:{
			required:true
		}
	},
	highlight: function (element, errorClass, validClass) {
        $(element).parents('.form-control').removeClass('has-success').addClass('has-error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parents('.form-control').removeClass('has-error').addClass('has-success');
    },
    errorPlacement: function (error, element) {
    		if(element.hasClass('select2') && element.next('.select2-container').length) {
        	error.insertAfter(element.next('.select2-container'));
        } else if (element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        }
        else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
            error.insertAfter(element.parent().parent());
        }
        else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
            error.appendTo(element.parent().parent());
        }
        else {
            error.insertAfter(element);
        }
    }
});

$("#generarFuaF_pacientesCitados").validate({
	rules:{
		personalAtiendeF_pacientesCitados:{
			required:true
		},

		lugarAtencionF_pacientesCitados:{
			required:true
		},

		tipoAtencionF_pacientesCitados:{
			required:true
		},

		codigoReferenciaF_pacientesCitados:{
			required:true
		},

		descripcionReferenciaF_pacientesCitados:{
			required:true
		},

		numeroReferenciaF_pacientesCitados:{
			required:true
		},

		tipoDocumentoF_pacientesCitados:{
			required:true
		},

		numeroDocumentoF_pacientesCitados:{
			required:true
		},

		componenteF_pacientesCitados:{
			required:true
		},

		codigoAsegurado2F_pacientesCitados:{
			required:true
		},

		codigoAsegurado3F_pacientesCitados:{
			required:true
		},

		apellidoPaternoF_pacientesCitados:{
			required:true
		},

/* 		apellidoMaternoF_pacientesCitados:{
			required:true
		}, */

		primerNombreF_pacientesCitados:{
			required:true
		},

		sexoF_pacientesCitados:{
			required:true
		},

		fechaNacimientoF_pacientesCitados:{
			required:true
		},

/* 		fechaF_pacientesCitados:{
			required:true
		},

		horaF_pacientesCitados:{
			required:true
		}, */

		codigoPrestacionalF_pacientesCitados:{
			required:true
		},

		conceptoPrestacionalF_pacientesCitados:{
			required:true
		},

		destinoAseguradoF_pacientesCitados:{
			required:true
		},

		historiaF_pacientesCitados:{
			required:true
		}
	},
	highlight: function (element, errorClass, validClass) {
        $(element).parents('.form-control').removeClass('has-success').addClass('has-error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parents('.form-control').removeClass('has-error').addClass('has-success');
    },
    errorPlacement: function (error, element) {
    		if(element.hasClass('select2') && element.next('.select2-container').length) {
        	error.insertAfter(element.next('.select2-container'));
        } else if (element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        }
        else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
            error.insertAfter(element.parent().parent());
        }
        else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
            error.appendTo(element.parent().parent());
        }
        else {
            error.insertAfter(element);
        }
    }
});

$("#actualizarFuaF_fuasEmitidosG").validate({
	rules:{
		personalAtiendeF_fuasEmitidosG:{
			required:true
		},

		lugarAtencionF_fuasEmitidosG:{
			required:true
		},

/* 		tipoAtencionF_fuasEmitidosG:{
			required:true
		}, */

		codigoReferenciaF_fuasEmitidosG:{
			required:true
		},

		descripcionReferenciaF_fuasEmitidosG:{
			required:true
		},

		numeroReferenciaF_fuasEmitidosG:{
			required:true
		},

		tipoDocumentoF_fuasEmitidosG:{
			required:true
		},

		numeroDocumentoF_fuasEmitidosG:{
			required:true
		},

		componenteF_fuasEmitidosG:{
			required:true
		},

		codigoAsegurado2F_fuasEmitidosG:{
			required:true
		},

		codigoAsegurado3F_fuasEmitidosG:{
			required:true
		},

		apellidoPaternoF_fuasEmitidosG:{
			required:true
		},

		apellidoMaternoF_fuasEmitidosG:{
			required:true
		},

		primerNombreF_fuasEmitidosG:{
			required:true
		},

		sexoF_fuasEmitidosG:{
			required:true
		},

		fechaNacimientoF_fuasEmitidosG:{
			required:true
		},

		fechaF_fuasEmitidosG:{
			required:true
		},

		horaF_fuasEmitidosG:{
			required:true
		},

		codigoPrestacionalF_fuasEmitidosG:{
			required:true
		},

		conceptoPrestacionalF_fuasEmitidosG:{
			required:true
		},

		destinoAseguradoF_fuasEmitidosG:{
			required:true
		},

		historiaF_fuasEmitidosG:{
			required:true
		}
	},
	highlight: function (element, errorClass, validClass) {
        $(element).parents('.form-control').removeClass('has-success').addClass('has-error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parents('.form-control').removeClass('has-error').addClass('has-success');
    },
    errorPlacement: function (error, element) {
    		if(element.hasClass('select2') && element.next('.select2-container').length) {
        	error.insertAfter(element.next('.select2-container'));
        } else if (element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        }
        else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
            error.insertAfter(element.parent().parent());
        }
        else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
            error.appendTo(element.parent().parent());
        }
        else {
            error.insertAfter(element);
        }
    }
});

/* Finalizamos el formulario */

var campo = $("#id_personal_externo").val();

if(campo != ''){
    $('#nombres_profesional_texto').remove();
    $('#fecha_actual_epp').remove();
    $('#horario_jornada_profesional').remove();
    $('#id_jornada_profesional').remove();
    $('#id_calendario_epp').remove();
}
else{
    $('#nombres_personal_texto').remove();
    $('#fecha_actual_personal').remove();
    $('#horario_jornada_personal').remove();
    $('#id_jornada_personal').remove();
    $('#id_calendario_personal').remove();
}

$("#tabla_ocultar").hide();
$("#cambio_formatoAutomovil").css("display","none");

/*Ocultar Fecha de HTML*/
$("#fecha_actual_epp").prop('disabled', true);
$("#fecha_actual_personal").prop('disabled', true);
$("#customSwitch2").prop('disabled', true);
$("#customSwitch5").prop('disabled', true);
/* $("#fecha_actual_calendario").prop('disabled', true); */
$('#boton_epp').on("click",function(){
    $("#fecha_actual_epp").prop('disabled', false);
    $("#fecha_actual_personal").prop('disabled', false);
    $("#boton_registro").attr('href','#');
})

$('#boton_equipo').on("click",function(){
    $("#customSwitch2").prop('disabled', false);
    $("#customSwitch5").prop('disabled', false);
})

/*=============================================
PREVISUALIZAR IMÁGENES TEMPORALES
=============================================*/
$("#foto_administrador").change(function(){

	var imagen = this.files[0];
	var tipo = $(this).attr("name");

	/*=============================================
    VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
    =============================================*/

    if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){

    	$("input[type='file']").val("");

    	notie.alert({

		    type: 3,
		    text: '¡La imagen debe estar en formato JPG o PNG!',
		    time: 7

		 })

    }else if(imagen["size"] > 2000000){

    	$("input[type='file']").val("");

    	notie.alert({

		    type: 3,
		    text: '¡La imagen no debe pesar más de 2MB!',
		    time: 7

		 })

    }else{

    	var datosImagen = new FileReader;
    	datosImagen.readAsDataURL(imagen);

    	$(datosImagen).on("load", function(event){

    		var rutaImagen = event.target.result;

    		$(".previsualizarImg_"+tipo).attr("src", rutaImagen);

    	})

    }

});

/*=============================================
Preguntar antes de Eliminar Registro
=============================================*/

$(document).on("click", ".eliminarRegistro", function(){

	var action = $(this).attr("action");
  	var method = $(this).attr("method");
  	var pagina = $(this).attr("pagina");
    var token = $(this).attr("token");

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

  			var datos = new FormData();
  			datos.append("_method", method);
  			datos.append("_token", token);

  			$.ajax({

  				url: action,
  				method: "POST",
  				data: datos,
  				cache: false,
  				contentType: false,
        		processData: false,
        		success:function(respuesta){

        			 if(respuesta == "ok"){

    			 		swal({
		                    type:"success",
		                    title: "¡El registro ha sido eliminado!",
		                    showConfirmButton: true,
		                    confirmButtonText: "Cerrar"

			             }).then(function(result){

			             	if(result.value){
                                 window.location = ruta+'/'+pagina;
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

/*=============================================
Preguntar antes de Eliminar Registro de Permisos
=============================================*/

$(document).on("click", ".eliminarRegistroPermisos", function(){

	var action = $(this).attr("action");
  	var method = $(this).attr("method");
  	var pagina = $(this).attr("pagina");
    var token = $(this).attr("token");
    var value = $(this).attr("value");

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

  			var datos = new FormData();
  			datos.append("_method", method);
  			datos.append("_token", token);

  			$.ajax({

  				url: action,
  				method: "POST",
  				data: datos,
  				cache: false,
  				contentType: false,
        		processData: false,
        		success:function(respuesta){

        			 if(respuesta == "ok"){

    			 		swal({
		                    type:"success",
		                    title: "¡El registro ha sido eliminado!",
		                    showConfirmButton: true,
		                    confirmButtonText: "Cerrar"

			             }).then(function(result){

			             	if(result.value){
                                 window.location = ruta+'/'+pagina+'/'+value;
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

/* SummerNote */
$(".summernote").summernote();

function limpiarUrl(texto){
    var texto = texto.toLowerCase();
    texto = texto.replace(/[-\\´\\,\\.\\;\\:\\-\\_\\<\\>\\+\\*\\/\\¿\\?\\=\\(\\)\\&\\%\\$\\#\\"\\!\\°\\|\\{\\}\\[\\!\\¡\\'\\a-zA-ZñÑáéíóúÁÉÍÓÚ ]/g,'');
    return texto;
}

function ocultarCaracteres(texto){
    var texto = texto.toLowerCase();
    texto = texto.replace(/[-\\´\\,\\.\\;\\:\\-\\_\\<\\>\\+\\*\\/\\¿\\?\\=\\(\\)\\&\\%\\$\\#\\"\\!\\°\\|\\{\\}\\[\\!\\¡\\'\\a-zA-ZñÑáéíóúÁÉÍÓÚ ]/g,'*');
    return texto;
}

function limpiarUrlMonto(texto){
    var texto = texto.toLowerCase();
    texto = texto.replace(/[-\\´\\,\\;\\:\\-\\_\\<\\>\\+\\*\\/\\¿\\?\\=\\(\\)\\&\\%\\$\\#\\"\\!\\°\\|\\{\\}\\[\\!\\¡\\'\\a-zA-ZñÑáéíóúÁÉÍÓÚ ]/g,'');
    return texto;
}

function limpiarUrlNumero(texto){
    var texto = texto.toLowerCase();
    texto = texto.replace(/[-\\.\\´\\,\\;\\:\\-\\_\\<\\>\\+\\*\\/\\¿\\?\\=\\(\\)\\&\\%\\$\\#\\"\\!\\°\\|\\{\\}\\[\\!\\¡\\'\\a-zA-ZñÑáéíóúÁÉÍÓÚ ]/g,'');
    return texto;
}

$(document).on("keyup",".inputRuta",function(){
    $(this).val(
        limpiarUrl($(this).val())
    )
})

$(document).on("keyup",".inputCaracteres",function(){
    $(this).val(
        ocultarCaracteres($(this).val())
    )
})

$(document).on("keyup",".inputRutaMonto",function(){
    $(this).val(
        limpiarUrlMonto($(this).val())
    )
})

$(document).on("keyup",".inputRutaNumero",function(){
    $(this).val(
        limpiarUrlNumero($(this).val())
    )
})

$(document).on("click",".nav-link",function(){
        $(this).addClass("active");
})

/* FUA PROGRAMACIÓN DE PACIENTES */

$('#personalAtiendeF_pacientesCitados option[value="1"]').attr("selected", true);
$('#personalAtiendeF_pacientesCitados').attr('readonly','readonly');

$('#personalAtiendeFL_pacientesCitados option[value="1"]').attr("selected", true);
$('#personalAtiendeFL_pacientesCitados').attr('readonly','readonly');

/* $('#lugarAtencionF_pacientesCitados option[value="1"]').attr("selected", true); */
$('#lugarAtencionF_pacientesCitados').attr('readonly','readonly');

$('#lugarAtencionFL_pacientesCitados option[value="1"]').attr("selected", true);
$('#lugarAtencionFL_pacientesCitados').attr('readonly','readonly');

$('#tipoAtencionF_pacientesCitados option[value="1"]').attr("selected", true);
$('#tipoAtencionF_pacientesCitados').attr('readonly','readonly');

$('#tipoAtencionFL_pacientesCitados option[value="1"]').attr("selected", true);
$('#tipoAtencionFL_pacientesCitados').attr('readonly','readonly');

$('#componenteFL_pacientesCitados option[value="1"]').attr("selected", true);
$('#componenteFL_pacientesCitados').attr('readonly','readonly');

$('#conceptoPrestacionalFL_pacientesCitados option[value="1"]').attr("selected", true);
$('#conceptoPrestacionalFL_pacientesCitados').attr('readonly','readonly');

$('#destinoAseguradoFL_pacientesCitados option[value="2"]').attr("selected", true);
$('#destinoAseguradoFL_pacientesCitados').attr('readonly','readonly');

/* $('#nombresApellidosP_pacientesCitados').attr('readonly','readonly'); */

$('#tipoPersonalSaludFL_pacientesCitados').attr('readonly','readonly');
$('#egresadoFL_pacientesCitados').attr('readonly','readonly');
$('#especialidadFL_pacientesCitados').attr('readonly','readonly');
$('#diagnostico_pacientesCitados option[value=""]').attr("selected", true);
$('#diagnostico_pacientesCitados').attr('readonly','readonly');

/* FIN DE FUA - PROGRAMACIÓN DE PACIENTES */









