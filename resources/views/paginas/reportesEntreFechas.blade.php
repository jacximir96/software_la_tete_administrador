@foreach ($administradores as $element)
@if ($_COOKIE["email_login"] == $element->email)

@extends('plantilla')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Reportes entre Fechas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
                        <li class="breadcrumb-item active">Reportes entre Fechas</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ url('/') }}/reportesEntreFechas/entreFechasPDF" method="get" target="_blank" id="formulario_reporteFechasLimpieza">
                                <h4>Ingresos y egresos</h4>
                                </br>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="fecha_inicial_reportes">Fecha Inicial</label>
                                            <input type="date" class="form-control" name="fecha_inicial_reportes" id="fecha_inicial_reportes" required autofocus onfocus="(this.type='date')">
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="fecha_final_reportes">Fecha Final</label>
                                            <input type="date" class="form-control" name="fecha_final_reportes" id="fecha_final_reportes" required autofocus onfocus="(this.type='date')">
                                        </div>

                                    </div>
                                </div>

                                <button style="float:right;" class="btn btn-primary btn-sm" type="submit" id="guardar_reporteFechasLimpieza">GENERAR REPORTE (PDF)</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ url('/') }}/reportesEntreFechas1/entreFechasPDF" method="get" target="_blank" id="formulario_reporteFechasGeneral">

                                <h4>Cierres de caja</h4>
                                </br>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="fecha_inicial_reportes">Fecha Inicial</label>
                                            <input type="date" class="form-control" name="fecha_inicial_reportes" id="fecha_inicial_reportes" required autofocus onfocus="(this.type='date')">
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="fecha_final_reportes">Fecha Final</label>
                                            <input type="date" class="form-control" name="fecha_final_reportes" id="fecha_final_reportes" required autofocus onfocus="(this.type='date')">
                                        </div>
                                    </div>
                                </div>

                                <button style="float:right;" class="btn btn-primary btn-sm" type="submit" id="guardar_reporteFechasGeneral">GENERAR REPORTE (PDF)</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ url('/') }}/reportesEntreFechas3/entreFechasPDF" method="get" target="_blank" id="formulario_reporteFechasVentas">

                                <h4>Ventas</h4>
                                </br>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="fecha_inicial_reportes">Fecha Inicial</label>
                                            <input type="date" class="form-control" name="fecha_inicial_reportes" id="fecha_inicial_reportes" required autofocus onfocus="(this.type='date')">
                                        </div>

                                        <div class="col-sm-6">
                                            <label for="fecha_final_reportes">Fecha Final</label>
                                            <input type="date" class="form-control" name="fecha_final_reportes" id="fecha_final_reportes" autofocus onfocus="(this.type='date')" disabled>
                                        </div>
                                    </div>
                                </div>

                                <button style="float:right;" class="btn btn-primary btn-sm" type="submit" id="guardar_reporteFechasGeneral">GENERAR REPORTE (PDF)</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@if (Session::has("ok-crear"))
<script>
    notie.alert({
        type: 1,
        text: '!El Rol ha sido creado correctamente',
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
        text: '!Error en el gestor de roles',
        time: 10
    })
</script>
@endif

@if (Session::has("ok-editar"))
<script>
    notie.alert({
        type: 1,
        text: '!El Rol ha sido actualizado correctamente',
        time: 10
    })
</script>
@endif

@if (Session::has("ok-eliminar"))

<script>
    notie.alert({
        type: 1,
        text: '¡El Rol ha sido eliminado correctamente!',
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

@endif

@endforeach