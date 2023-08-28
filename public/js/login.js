/*=============================================
Función para crear cookies
=============================================*/

function crearCookie(nombre, valor, diasExpiracion){
    var hoy = new Date();
    hoy.setTime(hoy.getTime() + (diasExpiracion*24*60*60*1000));
    var fechaExpiracion = "expires=" +hoy.toUTCString();
    document.cookie = nombre + "=" +valor+"; "+fechaExpiracion;
}

/*=============================================
Capturar email login para convertirlo en cookie
=============================================*/

$(document).on("change", ".email_login", function(){
    crearCookie("email_login", $(this).val(), 1);
})
