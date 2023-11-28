<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */
/* Route::group(['middleware' => ['cors']], function () {
    Route::get('listarMesas', 'UnidadesMedidaController@listarMesas');
    Route::post('iniciarSesion', 'UnidadesMedidaController@iniciarSesion');
}); */

Route::group(['prefix' => 'auth','middleware' => ['cors']], function () {
    Route::get('listarMesas', 'UnidadesMedidaController@listarMesas');
    Route::get('listarCategorias', 'UnidadesMedidaController@listarCategorias');
    Route::get('listarProductoPorCategoria', 'UnidadesMedidaController@listarProductoPorCategoria');
    Route::get('listarFormaPago', 'UnidadesMedidaController@listarFormaPago');
    Route::get('getProductoById', 'UnidadesMedidaController@getProductoById');
    Route::get('createOrdenPedido', 'UnidadesMedidaController@createOrdenPedido');
    Route::get('cambiarStatusMesa', 'UnidadesMedidaController@cambiarStatusMesa');
    Route::get('getDatosCabeceraOrdenPedido', 'UnidadesMedidaController@getDatosCabeceraOrdenPedido');
    Route::get('cobrarPedidoMesaSeleccionada', 'UnidadesMedidaController@cobrarPedidoMesaSeleccionada');
    Route::get('totalRegistrosCierreCaja', 'UnidadesMedidaController@totalRegistrosCierreCaja');
    Route::get('actualizarTotalRegistrosCierreCaja', 'UnidadesMedidaController@actualizarTotalRegistrosCierreCaja');
    Route::get('iniciarSesion', 'UnidadesMedidaController@iniciarSesion');
    Route::get('verificarPeriodoActivo', 'UnidadesMedidaController@verificarPeriodoActivo');
    Route::get('abrirPeriodoVenta', 'UnidadesMedidaController@abrirPeriodoVenta');
});