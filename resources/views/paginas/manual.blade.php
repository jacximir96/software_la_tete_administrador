@extends('plantilla')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Bienvenidos al Software del Ufpa</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url("/") }}">Inicio</a></li>
              <li class="breadcrumb-item active">Manual de Usuario</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Default box -->
            <div class="card">

              <div class="card-body">
                <p>Bienvenidos al Software del Ufpa, para cualquier información pueden revisar el siguiente manual del usuario.</p>

                </br>

<!--                 <iframe src="https://docs.google.com/viewer?srcid=1KSOTCiebSgge0QkaAxqamlKUd_UjHzDC&pid=explorer&efh=false&a=v&chrome=false&embedded=true"
                width="100%" height="680px"></iframe> -->
              </div>
              <!-- /.card-body -->

              <!-- /.card-footer-->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
@endsection
