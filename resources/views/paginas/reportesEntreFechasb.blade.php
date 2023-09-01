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

                            <form method="GET" action="{{ url('/') }}/reportesEntreFechas/fuasGeneradosEXCEL" id="frm_reportesEntreFechas">
                            @csrf
                                <h4>Fuas Generados por Personal</h4></br>

                                <div class="form-group">
							        <div class="row">
                                        <div class="col-sm-6">
                                            <label for="fecha_inicial_reportes">Fecha Inicial</label>
                                            <input type="text" class="form-control" name="fechaInicial_reportesEntreFechas" id="fechaInicial_reportesEntreFechas"
                                            required autofocus onfocus="(this.type='date')">
                                        </div>
    
                                        <div class="col-sm-6">
                                            <label for="fecha_final_reportes">Fecha Final</label>
                                            <input type="text" class="form-control" name="fechaFinal_reportesEntreFechas" id="fechaFinal_reportesEntreFechas"
                                            required autofocus onfocus="(this.type='date')">
                                        </div>
							        </div>
				                </div>

                                <div class="form-group">
							        <div class="row">
                                        <div class="col-sm-6">
                                            <label for="empleado_reportes">Personal (UFPA)</label>
                                            <select class="form-control select-2 select2" name="usuario_reportesEntreFechas" id="usuario_reportesEntreFechas" required>
                                                <option value="">-- Seleccionar el Personal (UFPA) --</option>
                                                @foreach($usuarios as $key => $valor_usuarios)
                                                <option value="{{$valor_usuarios->id}}">{{$valor_usuarios->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
				                </div>

                                <button style="float:right;" class="btn btn-success btn-sm" type="submit">GENERAR REPORTE (EXCEL)</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection

@endif

@endforeach