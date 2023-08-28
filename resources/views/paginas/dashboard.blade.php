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