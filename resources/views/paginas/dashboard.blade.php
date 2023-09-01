@extends('plantilla')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Panel de Control</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
                        <li class="breadcrumb-item active">Panel de Control</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @canany(['ver_usuarios','crear_usuarios','editar_usuarios','eliminar_usuarios'])
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>
                                @foreach($cantidad_usuarios as $key => $value_usuarios)
                                {{$value_usuarios->cantidad_usuarios}}
                                @endforeach
                            </h3>

                            <p style="margin-bottom: -0.5rem;">Usuarios Registrados</p>
                        </div>

                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>

                        <a href="{{url("/")}}/administradores" class="small-box-footer">Más Información <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endcan

                @canany(['ver_categorias','crear_categorias','editar_categorias','eliminar_categorias','reportesPDF_categorias','reportesEXCEL_categorias'])
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>
                                @foreach($cantidad_categorias as $key => $value_categorias)
                                    {{$value_categorias->cantidad_categorias}}
                                @endforeach
                            </h3>

                            <p style="margin-bottom: -0.5rem;">Categorías</p>
                        </div>

                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>

                        <a href="{{url("/")}}/categorias" class="small-box-footer">Más Información <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endcan

                @canany(['ver_unidadesMedida','crear_unidadesMedida','editar_unidadesMedida','eliminar_unidadesMedida','reportesPDF_unidadesMedida','reportesEXCEL_unidadesMedida'])
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>
                                @foreach($cantidad_unidadesMedida as $key => $value_unidadesMedida)
                                    {{$value_unidadesMedida->cantidad_unidadesMedida}}
                                @endforeach
                            </h3>

                            <p style="margin-bottom: -0.5rem;">Unidades de Medida</p>
                        </div>

                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>

                        <a href="{{url("/")}}/unidadesMedida" class="small-box-footer">Más Información <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endcan

                @canany(['ver_productosLimpieza','crear_productosLimpieza','editar_productosLimpieza','eliminar_productosLimpieza','reportesPDF_productosLimpieza','reportesEXCEL_productosLimpieza'])
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>
                                @foreach($cantidad_productosLimpieza as $key => $value_productosLimpieza)
                                    {{$value_productosLimpieza->cantidad_productosLimpieza}}
                                @endforeach
                            </h3>

                            <p style="margin-bottom: -0.5rem;">Productos</p>
                        </div>

                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>

                        <a href="{{url("/")}}/productosLimpieza" class="small-box-footer">Más Información <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

@if (Session::has("ok-crear"))
<script>
    notie.alert({
        type: 1,
        text: '!El ambiente ha sido creado correctamente',
        time: 10
    })
</script>
@endif

@if (Session::has("no-validacion"))
<script>
    notie.alert({
        type: 2,
        text: '!Hay campos no válidos en el formulario',
        time: 10
    })
</script>
@endif

@if (Session::has("error"))
<script>
    notie.alert({
        type: 3,
        text: '!Error en el gestor de Ambientes',
        time: 10
    })
</script>
@endif

@if (Session::has("ok-editar"))
<script>
    notie.alert({
        type: 1,
        text: '!El ambiente ha sido actualizado correctamente',
        time: 10
    })
</script>
@endif

@if (Session::has("ok-eliminar"))

<script>
    notie.alert({
        type: 1,
        text: '¡El ambiente ha sido eliminado correctamente!',
        time: 10
    })
</script>

@endif

@if (Session::has("no-borrar"))

<script>
    notie.alert({
        type: 2,
        text: '¡Este administrador no se puede borrar!',
        time: 10
    })
</script>

@endif

@endsection