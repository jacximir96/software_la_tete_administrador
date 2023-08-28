<?php

Route::get('/', function () {
    return "view('plantilla')";
});

Auth::routes();

/* Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset','Auth\ResetPasswordController@reset')->name('password.update'); */

Route::group(['middleware' => ['permission:ver_usuarios|editar_usuarios|eliminar_usuarios|crear_usuarios']], function () {
    Route::resource('/administradores','AdministradoresController');
});

Route::group(['middleware' => ['permission:ver_roles|editar_roles|eliminar_roles|crear_roles|permisos_roles']], function () {
    Route::resource('/roles','RolesController');
});

Route::group(['middleware' => ['permission:VER_GENERACION']], function () {
    Route::get('/pacientesCitados','PacientesCitadosController@index');
    Route::get('/fuasEmitidos','FuasEmitidosController@index');
    Route::get('/fuasObservados','FuasObservadosController@index');
});

Route::group(['middleware' => ['permission:VER_GENERACION_GENERAL']], function () {
    Route::get('/fuasEmitidosG','FuasEmitidosGController@index');
});

Route::group(['middleware' => ['permission:VER_DIGITACION']], function () {
    Route::get('/fuasDigitados','FuasDigitadosController@index');
});

Route::group(['middleware' => ['permission:VER_AUDITORIAMEDICA']], function () {
    Route::get('/auditoriaEmitidos','AuditoriaEmitidosController@index');
});

Route::group(['middleware' => ['permission:VER_ACERVO']], function () {
    Route::get('/fuasAcervo','FuasAcervocontroller@index');
});

Route::group(['middleware' => ['permission:VER_REPORTES']], function () {
    Route::get('/reportesEntreFechas/fuasGeneradosEXCEL','ReportesEntreFechasController@fuasGeneradosEXCEL');
});

Route::group(['middleware' => ['permission:ver_unidadesMedida|editar_unidadesMedida|eliminar_unidadesMedida|crear_unidadesMedida|reportesPDF_unidadesMedida|reportesEXCEL_unidadesMedida']], function () {
    Route::resource('/unidadesMedida','UnidadesMedidaController');
    //Route::get('/reportesUnidadesMedida/unidadesMedidaPDF','UnidadesMedidaController@createPDF');
    //Route::get('/reportesUnidadesMedida/unidadesMedidaEXCEL','UnidadesMedidaController@createEXCEL');
});

Route::group(['middleware' => ['permission:ver_gastos|editar_gastos|eliminar_gastos|crear_gastos|reportesPDF_gastos|reportesEXCEL_gastos']], function () {
    Route::resource('/gastos','GastosController');
    Route::get('/reportesGastos/gastosPDF','GastosController@createPDF');
    Route::get('/reportesGastos/gastosEXCEL','GastosController@createEXCEL');
});

Route::group(['middleware' => ['permission:ver_categorias|editar_categorias|eliminar_categorias|crear_categorias|reportesPDF_categorias|reportesEXCEL_categorias']], function () {
    Route::resource('/categorias','CategoriasController');
    Route::get('/reportesCategorias/categoriasPDF','CategoriasController@createPDF');
    Route::get('/reportesCategorias/categoriasEXCEL','CategoriasController@createEXCEL');
});

Route::get('/soporteTecnico','SoporteTecnicoController@index');
Route::get('/','DashboardController@index');
Route::get('/dashboard','DashBoardController@index');
Route::resource('/manual','ManualController');

Route::get('/reportesEntreFechas','ReportesEntreFechasController@index');
Route::get('/pacientesCitados/grupoProfesionales','PacientesCitadosController@getGrupoProfesionales');
Route::get('/pacientesCitados/profesionales','PacientesCitadosController@getProfesionales');
Route::get('/pacientesCitados/profesionalesGeneral','PacientesCitadosController@getProfesionalesGeneral');
Route::get('/pacientesCitados/profesionales1','PacientesCitadosController@getProfesionales1');
Route::get('/pacientesCitados/buscarPorMes','PacientesCitadosController@buscarPorMes')->name('consultar.fechas');
Route::get('/pacientesCitados/rolCitas','PacientesCitadosController@rolCitas')->name('consultar.rol');
Route::get('/fuasEmitidos/rolCitas','FuasEmitidosController@rolCitas')->name('consultar.rolEmitidos');
Route::get('/auditoriaEmitidos/rolCitas','AuditoriaEmitidosController@rolCitas')->name('consultar.rolAuditoriaEmitidos');
Route::get('/fuasObservados/rolCitas','FuasObservadosController@rolCitas')->name('consultar.rolObservados');
Route::get('/pacientesCitados/referencias','PacientesCitadosController@referencias')->name('consultar.referencias');
Route::get('/fuasEmitidos/referencias','FuasEmitidosController@referencias')->name('consultar.referenciasEmitidos');
Route::get('/auditoriaEmitidos/referencias','AuditoriaEmitidosController@referencias')->name('consultar.referenciasAuditoriaEmitidos');
Route::get('/fuasObservados/referencias','FuasObservadosController@referencias')->name('consultar.referenciasObservados');
Route::get('/fuasEmitidosG/referencias','FuasEmitidosGController@referencias')->name('consultar.referenciasEmitidosG');
Route::get('/pacientesCitados/codigoCie','PacientesCitadosController@codigoCie')->name('consultar.codigoCie');
Route::get('/fuasEmitidos/codigoCie','FuasEmitidosController@codigoCie')->name('consultar.codigoCieEmitidos');
Route::get('/auditoriaEmitidos/codigoCie','AuditoriaEmitidosController@codigoCie')->name('consultar.codigoCieAuditoriaEmitidos');
Route::get('/fuasObservados/codigoCie','FuasObservadosController@codigoCie')->name('consultar.codigoCieObservados');
Route::get('/fuasEmitidosG/codigoCie','FuasEmitidosGController@codigoCie')->name('consultar.codigoCieEmitidosG');
Route::get('/pacientesCitados/personalC','PacientesCitadosController@personalC')->name('consultar.personalC');
Route::get('/fuasEmitidos/personalC','FuasEmitidosController@personalC')->name('consultar.personalCEmitidos');
Route::get('/auditoriaEmitidos/personalC','AuditoriaEmitidosController@personalC')->name('consultar.personalCAuditoriaEmitidos');
Route::get('/fuasObservados/personalC','FuasObservadosController@personalC')->name('consultar.personalCObservados');
Route::get('/fuasEmitidosG/personalC','FuasEmitidosGController@personalC')->name('consultar.personalCEmitidosG');
Route::post('/pacientesCitados/generarFua','PacientesCitadosController@generarFua')->name('consultar.generarFua');
Route::post('/fuasEmitidos/verFua','FuasEmitidosController@verFua')->name('consultar.verFuaEmitidos');
Route::post('/fuasObservados/verFua','FuasObservadosController@verFua')->name('consultar.verFuaObservados');
Route::post('/fuasEmitidosG/verFua','FuasEmitidosGController@verFua')->name('consultar.verFuaEmitidosG');
Route::post('/auditoriaEmitidos/verFua','AuditoriaEmitidosController@verFua')->name('consultar.verFuaAuditoriaEmitidos');
Route::post('/fuasEmitidos/actualizarFua','FuasEmitidosController@actualizarFua')->name('consultar.actualizarFuaEmitidos');
Route::post('/auditoriaEmitidos/actualizarFua','AuditoriaEmitidosController@actualizarFua')->name('consultar.actualizarFuaAuditoriaEmitidos');
Route::post('/fuasObservados/actualizarFua','FuasObservadosController@actualizarFua')->name('consultar.actualizarFuaObservados');
Route::post('/fuasEmitidosG/actualizarFua','FuasEmitidosGController@actualizarFua')->name('consultar.actualizarFuaEmitidosG');
Route::get('/fuasEmitidos/validarPasswordFua','FuasEmitidosController@validarPasswordFua')->name('consultar.validarPasswordFuaEmitidos');
Route::get('/fuasObservados/validarPasswordFua','FuasObservadosController@validarPasswordFua')->name('consultar.validarPasswordFuaObservados');
Route::get('/fuasEmitidosG/validarPasswordFua','FuasEmitidosGController@validarPasswordFua')->name('consultar.validarPasswordFuaEmitidosG');
Route::post('/pacientesCitados/generarFuaLibre','PacientesCitadosController@generarFuaLibre')->name('consultar.generarFuaLibre');
Route::get('/pacientesCitados/buscarPorHistoria','PacientesCitadosController@buscarPorHistoria')->name('consultar.historia');
Route::get('/pacientesCitados/buscarPorDocumento','PacientesCitadosController@buscarPorDocumento')->name('consultar.documento');
Route::get('/pacientesCitados/reportesFUA/{IdAtencion_generacionFua}','PacientesCitadosController@reportesFUA');
Route::get('/fuasEmitidos/reportesFUA/{IdAtencion}','FuasEmitidosController@reportesFUA');
Route::get('/fuasObservados/reportesFUA/{IdAtencion}','FuasObservadosController@reportesFUA');
Route::get('/fuasEmitidosG/reportesFUA/{IdAtencion}','FuasEmitidosGController@reportesFUA');
Route::get('/auditoriaEmitidos/reportesFUA/{IdAtencion}','AuditoriaEmitidosController@reportesFUA');
Route::get('/fuasEmitidos/buscarPorMes','FuasEmitidosController@buscarPorMes')->name('consultar.fechasFEmitidos');
Route::get('/fuasDigitados/buscarPorMes','FuasDigitadosController@buscarPorMes')->name('consultar.fechasFDigitados');
Route::get('/auditoriaEmitidos/buscarPorMes','AuditoriaEmitidosController@buscarPorMes')->name('consultar.fechasAEmitidos');
Route::get('/fuasObservados/buscarPorMes','FuasObservadosController@buscarPorMes')->name('consultar.fechasFObservados');
Route::get('/fuasEmitidosG/buscarPorMes','FuasEmitidosGController@buscarPorMes')->name('consultar.fechasFEmitidosG');
Route::get('/fuasAcervo/buscarPorMes','FuasAcervoController@buscarPorMes')->name('consultar.fechasFAcervo');
Route::get('/fuasEmitidos/fechaAltaVerFua','FuasEmitidosController@fechaAltaVerFua')->name('consultar.fechaAltaVerFuaEmitidos');
Route::get('/auditoriaEmitidos/fechaAltaVerFua','AuditoriaEmitidosController@fechaAltaVerFua')->name('consultar.fechaAltaVerFuaAuditoriaEmitidos');
Route::get('/fuasObservados/fechaAltaVerFua','FuasObservadosController@fechaAltaVerFua')->name('consultar.fechaAltaVerFuaObservados');
Route::get('/fuasEmitidosG/fechaAltaVerFua','FuasEmitidosGController@fechaAltaVerFua')->name('consultar.fechaAltaVerFuaEmitidosG');
Route::get('/auditoriaEmitidos/verMedicamentos','AuditoriaEmitidosController@verMedicamentos')->name('consultar.numeroFuaAuditoriaEmitidos');
Route::get('/pacientesCitados/consultarSis','PacientesCitadosController@consultarSis')->name('consultar.sisF');
Route::delete('/permisos/{role_id}/{permission_id}','PermisosRolesController@destroy');
Route::delete('/permisos/{role_id}','PermisosRolesController@destroy1');
Route::get('/permisos/{role_id}','PermisosRolesController@show');
Route::post('/permisos/{role_id}','PermisosRolesController@store');
Route::get('/pacientesCitados/buscarPorHistoriaFua','PacientesCitadosController@buscarPorHistoriaFua')->name('consultar.historiaFua');
Route::post('/fuasDigitados/generadorTxt','FuasDigitadosController@generadorTxt')->name('consultar.generadorTxt');
Route::get('/auditoriaEmitidos/buscarPorHistoriaBD','AuditoriaEmitidosController@buscarPorHistoriaBD')->name('consultar.historiaBD');
Route::get('/pacientesCitados/buscarPorHistoriaBD','PacientesCitadosController@buscarPorHistoriaBD')->name('consultar.historiaBD_pacientesCitados');
Route::get('/fuasEmitidos/buscarPorHistoriaBD','FuasEmitidosController@buscarPorHistoriaBD')->name('consultar.historiaBD_fuasEmitidos');
Route::get('/fuasObservados/buscarPorHistoriaBD','FuasObservadosController@buscarPorHistoriaBD')->name('consultar.historiaBD_fuasObservados');
Route::get('/fuasEmitidosG/buscarPorHistoriaBD','FuasEmitidosGController@buscarPorHistoriaBD')->name('consultar.historiaBD_fuasEmitidosG');
Route::get('/fuasDigitados/buscarPorHistoriaBD','FuasDigitadosController@buscarPorHistoriaBD')->name('consultar.historiaBD_fuasDigitados');
Route::get('/fuasAcervo/buscarPorHistoriaBD','FuasAcervoController@buscarPorHistoriaBD')->name('consultar.historiaBD_fuasAcervo');
Route::get('/pacientesCitados/buscarPorDocumentoBD','PacientesCitadosController@buscarPorDocumentoBD')->name('consultar.documentoBD_pacientesCitados');
Route::get('/fuasEmitidos/buscarPorDocumentoBD','FuasEmitidosController@buscarPorDocumentoBD')->name('consultar.documentoBD_fuasEmitidos');
Route::get('/fuasObservados/buscarPorDocumentoBD','FuasObservadosController@buscarPorDocumentoBD')->name('consultar.documentoBD_fuasObservados');
Route::get('/fuasEmitidosG/buscarPorDocumentoBD','FuasEmitidosGController@buscarPorDocumentoBD')->name('consultar.documentoBD_fuasEmitidosG');
Route::get('/auditoriaEmitidos/buscarPorDocumentoBD','AuditoriaEmitidosController@buscarPorDocumentoBD')->name('consultar.documentoBD');
Route::get('/fuasDigitados/buscarPorDocumentoBD','FuasDigitadosController@buscarPorDocumentoBD')->name('consultar.documentoBD_fuasDigitados');
Route::get('/fuasAcervo/buscarPorDocumentoBD','FuasAcervoController@buscarPorDocumentoBD')->name('consultar.documentoBD_fuasAcervo');
Route::get('/auditoriaEmitidos/buscarPorFuaBD','AuditoriaEmitidosController@buscarPorFuaBD')->name('consultar.fuaBD');
Route::get('/fuasEmitidos/buscarPorFuaBD','FuasEmitidosController@buscarPorFuaBD')->name('consultar.fuaBD_fuasEmitidos');
Route::get('/fuasObservados/buscarPorFuaBD','FuasObservadosController@buscarPorFuaBD')->name('consultar.fuaBD_fuasObservados');
Route::get('/fuasEmitidosG/buscarPorFuaBD','FuasEmitidosGController@buscarPorFuaBD')->name('consultar.fuaBD_fuasEmitidosG');
Route::get('/fuasDigitados/buscarPorFuaBD','FuasDigitadosController@buscarPorFuaBD')->name('consultar.fuaBD_fuasDigitados');
Route::get('/fuasAcervo/buscarPorFuaBD','FuasAcervoController@buscarPorFuaBD')->name('consultar.fuaBD_fuasAcervo');
Route::get('/auditoriaEmitidos/showMedicamentos/{catalogo_cod}','AuditoriaEmitidosController@showMedicamentos');
Route::get('/auditoriaEmitidos/showLaboratorio/{catalogo_cod}','AuditoriaEmitidosController@showLaboratorio');
Route::post('/auditoriaEmitidos/updateMedicamentos/{catalogo_cod}','AuditoriaEmitidosController@updateMedicamentos');
Route::post('/auditoriaEmitidos/updateLaboratorio/{catalogo_cod}','AuditoriaEmitidosController@updateLaboratorio');
Route::get('/auditoriaEmitidos/auditarFua','AuditoriaEmitidosController@auditarFua');
Route::get('/auditoriaEmitidos/mostrarPdfLaboratorio/{persona_id}','AuditoriaEmitidosController@mostrarPdfLaboratorio');
Route::get('/fuasEmitidosG/diagnosticos056','FuasEmitidosGController@diagnosticos056')->name('consultar.diagnosticos056_fuasEmitidosG');
Route::post('/fuasDigitados/anularDigitacion','FuasDigitadosController@anularDigitacion')->name('consultar.anularDigitacion');
Route::post('/fuasEmitidosG/volverDeAnuladoAGenerado','FuasEmitidosGController@volverDeAnuladoAGenerado')->name('consultar.volverDeAnuladoAGenerado');
Route::get('/dashboard/datosActual/listarActual','DashboardController@listarActual');







