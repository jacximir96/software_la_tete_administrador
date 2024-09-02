@foreach ($administradores as $element)
            @if ($_COOKIE["email_login"] == $element->email)

@extends('plantilla')

@section('content')

<div class="content-wrapper">
    <!-- Inicio Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6" style="display: inline-block;">
            <h1>Kardex de Productos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
              <li class="breadcrumb-item active">Kardex de Productos</li>
            </ol>
          </div>
        </div>

        <div class="row mb-2">
		 <div class="col-sm-6 col-md-8" style="display: inline-block;">
		 	<form method="POST" action="{{ url('/') }}/kardexLimpieza/buscarPorMes" role="form" autocomplete="off" class="form-validate-jquery" id="frmSearch">
                @csrf
				<div class="form-group">
					<div class="row">
						<div class="col-sm-6">
							<label>Mes Inventario</label>
							<div class="input-group">
							<span class="input-group-prepend"><div class="input-group-text"><i class="fa fa-calendar"></i></div></span>
							    <input type="month" id="txtMes" name="txtMes" placeholder=""
							    class="form-control input-sm" style="text-transform:uppercase;"
	                		    onkeyup="javascript:this.value=this.value.toUpperCase();">
	                		</div>
						</div>
						<div class="col-sm-4">
							<button style="margin-top: 32px;" id="btnGuardar" type="submit" class="btn btn-primary btn-sm">
							<i class="fas fa-search"></i> Consultar</button>
						</div>
					</div>
				</div>
			  </form>
	   	  </div>
	  </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- Fin Content Header (Page header)  -->

    <!-- Inicio Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Default box -->
            <div class="card">
              <div class="card-header">

                <label style="margin-top:4px;margin-bottom:4px;">Mes Inventario:
                    @if($mfecha == '')
                        6
                    @elseif($mfecha == 1)
                        ENERO
                    @elseif($mfecha == 2)
                        FEBRERO
                    @elseif($mfecha == 3)
                        MARZO
                    @elseif($mfecha == 4)
                        ABRIL
                    @elseif($mfecha == 5)
                        MAYO
                    @elseif($mfecha == 6)
                        JUNIO
                    @elseif($mfecha == 7)
                        JULIO
                    @elseif($mfecha == 8)
                        AGOSTO
                    @elseif($mfecha == 9)
                        SETIEMBRE
                    @elseif($mfecha == 10)
                        OCTUBRE
                    @elseif($mfecha == 11)
                        NOVIEMBRE
                    @elseif($mfecha == 12)
                        DICIEMBRE
                    @endif

                    de {{$afecha}}</label>

                <div class="btn-group ancho-100" role="group" aria-label="Button group with nested dropdown" style="float:right; margin-left: 5px;">
                    <div class="btn-group ancho-100" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-info btn-sm boton-general dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                            <i class="fa fa-pencil" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Registrar Movimiento
                        </button>

                        <div class="dropdown-menu dropdown-menu-right ancho-100" aria-labelledby="btnGroupDrop1">
                            <a href="{{ url('/') }}/kardexLimpieza" target="_blank" class="dropdown-item boton-general" style="float:right;" data-toggle="modal" data-target="#crearEntradaProducto">
                                <i class="fa fa-arrow-right" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Registrar Entrada
                            </a>

                            <a href="{{ url('/') }}/kardexLimpieza" target="_blank" class="dropdown-item boton-general" style="float:right;" data-toggle="modal" data-target="#crearSalidaProducto">
                                <i class="fa fa-arrow-left" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Registrar Salida
                            </a>
                        </div>
                    </div>
                </div>

                <div class="btn-group ancho-100 top-margin" role="group" aria-label="Button group with nested dropdown" style="float:right; margin-left: 5px; margin-top: 5px;">
                    <div class="btn-group ancho-100" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-danger btn-sm boton-general dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                            <i class="fas fa-print" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Imprimir Reporte
                        </button>

                        <div class="dropdown-menu dropdown-menu-right ancho-100" aria-labelledby="btnGroupDrop1">
                            <a href="{{ url('/') }}/reportesKardexLimpieza/movimientosLimpiezaPDF?mfecha={{$mfecha}}&afecha={{$afecha}}" target="_blank" class="dropdown-item boton-general" style="float:right;">
                                <i class="fas fa-file" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Movimientos
                            </a>

                            <a href="{{ url('/') }}/reportesEntradasLimpieza/entradasLimpiezaPDF?mfecha={{$mfecha}}&afecha={{$afecha}}" target="_blank" class="dropdown-item boton-general" style="float:right;">
                                <i class="fas fa-file" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Entradas del Mes
                            </a>

                            <a href="{{ url('/') }}/reportesSalidasLimpieza/salidasLimpiezaPDF?mfecha={{$mfecha}}&afecha={{$afecha}}" target="_blank" class="dropdown-item boton-general" style="float:right;">
                                <i class="fas fa-file" style="font-size:15px;"></i>&nbsp;&nbsp;&nbsp;Salidas del Mes
                            </a>
                        </div>
                    </div>
                </div>


              </div>
              <div class="card-body">

                <table class="table table-bordered table-striped dt-responsive" width="100%"
                 id="tablaCategorias">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Descripción</th>
                            <th>Entradas</th>
                            <th>Salidas</th>
                            <th>Stock Restante</th>
                            <th>Empleado</th>
                            <th>Fecha de Ingreso / Salida</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($kardexLimpiezaTotal as $key => $value_kardex)
                        <tr>
                            <td style="text-align: center;">{{($key+1)}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$value_kardex->nombre_productoLimpieza}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$value_kardex->descripcion_productoLimpieza}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$value_kardex->entradas_kardexLimpieza}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{$value_kardex->salidas_kardexLimpieza}}</td>
                            <td style="text-align: center; text-transform: uppercase;">
                                <button class="btn btn-success">
                                    {{$value_kardex->restante_kardexLimpieza}}
                                </button>
                            </td>
                            <td style="text-align: center; text-transform: uppercase;">{{$value_kardex->nombres_empleado}} {{$value_kardex->apellidos_empleado}}</td>
                            <td style="text-align: center; text-transform: uppercase;">{{ \Carbon\Carbon::parse($value_kardex->created_at)->format('d-m-Y H:i:s')}}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>

              </div>
              <!-- /.card-body -->

              <!-- /.card-footer-->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- Fin Main content -->
</div>

<div class="modal fade" id="crearEntradaProducto">
    <div class="modal-dialog modal-lg" style="max-width:600px;">
        <div class="modal-content">
            <form method="POST" action="{{ url('/') }}/kardexLimpieza/entradaProductos" id="formulario_crearEntradaProducto">
                @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;"><i class="fa fa-pencil"></i> &nbsp; <span class="title-form">Registrar Entrada de Producto</span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body" id="modal-container">

                    <div class="alert alert-info alert-styled-left text-blue-800 content-group">
				                <span class="text-semibold">Estimado usuario</span>
				                Los campos remarcados con <span class="text-danger"> * </span> son necesarios.
				                <button type="button" class="close" data-dismiss="alert">×</button>
	                          	<input type="hidden" id="txtProceso" name="txtProceso" class="form-control" value="Entrada">
				    </div>

                    <div class="input-group mb-3 usuarios_general" >
                        <div class="input-group-append input-group-text" style="border-radius:0px;">
                            <i class="fas fa-angle-double-right"></i>
                        </div>

                        @foreach($administradores as $element)
                            @if ($_COOKIE["email_login"] == $element->email)
                                <input type="text" class="form-control" name="usuario_entradaProducto"
                                value="{{ $element->id }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                        @endforeach
                    </div>

                    <div class="form-group">
							<div class="row">
								<div class="col-sm-12">
									<label for="seleccionar_producto1">Producto <span class="text-danger"> * </span></label>
                                    <select class="form-control select-2 select2" name="seleccionar_producto" id="seleccionar_producto1">
                                        <option value="">-- Seleccionar el Producto --</option>
                                        @foreach($productosLimpiezaEntradas as $key => $value_productosLimpieza)
                                        <option value="{{$value_productosLimpieza->id_productoLimpieza}}" 
                                                data-stock="{{$value_productosLimpieza->stock_productoLimpieza}}"
                                                data-tipo="{{$value_productosLimpieza->tipo}}"
                                            >Nombre: {{$value_productosLimpieza->nombre_productoLimpieza}}, 
                                            Descripción: {{$value_productosLimpieza->descripcion_productoLimpieza}}, 
                                            Código: {{$value_productosLimpieza->codigo_productoLimpieza}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" class="form-control" name="verificar_tipo_producto_insumo" id="verificar_tipo_producto_insumo" value="" required>
                                </div>
							</div>
					</div>

                    <div class="form-group">
							<div class="row">
                                <div class="col-sm-6 epp_margin">
									<label for="seleccionar_stock1">Stock Actual <span class="text-danger"> * </span></label>
                                    <input type="text" min="0" class="form-control" name="seleccionar_stock" id="seleccionar_stock1"
                                    readonly="true">
                                </div>

								<div class="col-sm-6">
									<label for="seleccionar_motivo1">Motivo <span class="text-danger"> * </span></label>
                                    <select class="form-control select-2 select2" name="seleccionar_motivo" id="seleccionar_motivo1">
                                        <option value="">-- Seleccionar Motivo --</option>
                                        <option value="POR INVENTARIO INICIAL">POR INVENTARIO INICIAL</option>
                                        <option value="POR AJUSTE DE INVENTARIO">POR AJUSTE DE INVENTARIO</option>
                                    </select>
                                </div>
							</div>
					</div>

                    <div class="form-group">
							<div class="row">
                                <div class="col-sm-6">
									<label for="seleccionar_cantidad1">Cantidad <span class="text-danger"> * </span></label>
                                    <input type="number" min="0" class="form-control" name="seleccionar_cantidad" id="seleccionar_cantidad1"
                                    placeholder="0">
                                </div>
                            </div>
					</div>
                </div>

                <div class="modal-footer d-flex">
                    <div>
                        <button type="button" class="btn btn-default boton-general" data-dismiss="modal">Cancelar</button>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-success boton-general" id="guardar_kardexLimpieza">Guardar datos</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="crearSalidaProducto">
    <div class="modal-dialog modal-lg" style="max-width:600px;">
        <div class="modal-content">
            <form method="POST" action="{{ url('/') }}/kardexLimpieza/salidaProductos" id="formulario_crearSalidaProducto">
                @csrf

                <div class="modal-header" style="background:#2B7D73;border-radius:0px;">
                    <h4 class="modal-tittle" style="color:white;"><i class="fa fa-pencil"></i> &nbsp; <span class="title-form">Registrar Salida de Producto</span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body" id="modal-container">

                    <div class="alert alert-info alert-styled-left text-blue-800 content-group">
				                <span class="text-semibold">Estimado usuario</span>
				                Los campos remarcados con <span class="text-danger"> * </span> son necesarios.
				                <button type="button" class="close" data-dismiss="alert">×</button>
	                          	<input type="hidden" id="txtProceso" name="txtProceso" class="form-control" value="Entrada">
				    </div>

                    <div class="input-group mb-3 usuarios_general" >
                        <div class="input-group-append input-group-text" style="border-radius:0px;">
                            <i class="fas fa-angle-double-right"></i>
                        </div>

                        @foreach($administradores as $element)
                            @if ($_COOKIE["email_login"] == $element->email)
                                <input type="text" class="form-control" name="usuario_entradaProducto"
                                value="{{ $element->id }}" required autofocus
                                style="text-transform: uppercase;border-radius:0px;">
                            @endif
                        @endforeach
                    </div>

                    <div class="form-group">
							<div class="row">
								<div class="col-sm-12">
									<label for="seleccionar_producto">Producto <span class="text-danger"> * </span></label>
                                    <select class="form-control select-2 select2" name="seleccionar_producto" id="seleccionar_producto">
                                        <option value="">-- Seleccionar el Producto --</option>
                                        @foreach($productosLimpieza as $key => $value_productosLimpieza)
                                        <option value="{{$value_productosLimpieza->id_productoLimpieza}}" 
                                                data-stock="{{$value_productosLimpieza->stock_productoLimpieza}}"
                                                data-tipo="{{$value_productosLimpieza->tipo}}"
                                            >Nombre: {{$value_productosLimpieza->nombre_productoLimpieza}}, 
                                            Descripción: {{$value_productosLimpieza->descripcion_productoLimpieza}}, 
                                            Código: {{$value_productosLimpieza->codigo_productoLimpieza}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" class="form-control" name="verificar_tipo_producto_insumo_salida" id="verificar_tipo_producto_insumo_salida" value="" required>
                                </div>
							</div>
					</div>

                    <div class="form-group">
							<div class="row">
                                <div class="col-sm-6 epp_margin">
									<label for="seleccionar_stock">Stock Actual <span class="text-danger"> * </span></label>
                                    <input type="text" min="0" class="form-control" name="seleccionar_stock" id="seleccionar_stock"
                                    readonly="true">
                                </div>

								<div class="col-sm-6">
									<label for="seleccionar_motivo">Motivo <span class="text-danger"> * </span></label>
                                    <select class="form-control select-2 select2" name="seleccionar_motivo" id="seleccionar_motivo">
                                        <option value="">-- Seleccionar Motivo --</option>
                                        <option value="POR AJUSTE DE INVENTARIO">POR AJUSTE DE INVENTARIO</option>
                                    </select>
                                </div>
							</div>
					</div>

                    <div class="form-group">
							<div class="row">
                                <div class="col-sm-6">
									<label for="seleccionar_cantidad">Cantidad <span class="text-danger"> * </span></label>
                                    <input type="number" min="0" class="form-control" name="seleccionar_cantidad" id="seleccionar_cantidad"
                                    placeholder="0">
                                </div>
                            </div>
					</div>

                    <div class="form-group">
							<div class="row">
								<div class="col-sm-12">
									<label for="seleccionar_empleado">Empleado <span class="text-danger"> * </span></label>
                                    <select class="form-control select-2 select2" name="seleccionar_empleado" id="seleccionar_empleado">
                                        <option value="">-- Seleccionar Empleado --</option>
                                        @foreach($empleados as $key => $value_empleados)
                                        <option value="{{$value_empleados->id_empleado}}">{{$value_empleados->nombres_empleado}} {{$value_empleados->apellidos_empleado}}</option>
                                        @endforeach
                                    </select>
                                </div>
							</div>
					</div>
                </div>

                <div class="modal-footer d-flex">
                    <div>
                        <button type="button" class="btn btn-default boton-general" data-dismiss="modal">Cancelar</button>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-success boton-general" id="guardar_kardexLimpiezaSalida">Guardar datos</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#seleccionar_producto1').on('change', function() {
        var tipo = $(this).find('option:selected').data('tipo');
        $('#verificar_tipo_producto_insumo').val(tipo);
        console.log(tipo);
    });

    $('#seleccionar_producto').on('change', function() {
        var tipo = $(this).find('option:selected').data('tipo');
        $('#verificar_tipo_producto_insumo_salida').val(tipo);
        console.log(tipo);
    });
});
</script>

@if (Session::has("entrada-producto"))
  <script>
      notie.alert({type:1,text:'!La cantidad ha sido añadido correctamente', time:10})
  </script>
@endif

@if (Session::has("salida-producto"))
  <script>
      notie.alert({type:1,text:'!La cantidad ha sido disminuida correctamente', time:10})
  </script>
@endif

@if (Session::has("stock-negativo"))
  <script>
      notie.alert({type:2,text:'!La cantidad supera el stock del producto', time:10})
  </script>
@endif

@if (Session::has("error"))
  <script>
      notie.alert({type:3,text:'!El stock del producto está agotado', time:10})
  </script>
@endif

@if (Session::has("no-validacion"))
  <script>
      notie.alert({type:2,text:'!Hay campos no válidos en el formulario', time:10})
  </script>
@endif

@if (Session::has("error"))
  <script>
      notie.alert({type:3,text:'!Error en el gestor del kardex', time:10})
  </script>
@endif

@if (Session::has("ok-editar"))
  <script>
      notie.alert({type:1,text:'!La cantidad ha sido actualizado correctamente', time:10})
  </script>
@endif

@if (Session::has("ok-eliminar"))

<script>
    notie.alert({ type: 1, text: '¡La cantidad ha sido eliminado correctamente!', time: 10 })
</script>

@endif

@if (Session::has("no-borrar"))

<script>
    notie.alert({ type: 2, text: '¡Este administrador no se puede borrar!', time: 10 })
</script>

@endif

@endsection

@endif

@endforeach
