<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La tete restobar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- =============================================================
    PLUGINS DE CSS
    ==============================================================  -->
    <!-- ICCOMONT ICONS -->
    <link rel="stylesheet" href="{{ url('/') }}/css/icomoon/style.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css"> -->
    
	<!-- BOOTSTRAP 4 -->
	<link rel="stylesheet" href="{{ url('/') }}/css/plugins/bootstrap.min.css">

    <!-- OverlayScrollbars.min.css -->
	<link rel="stylesheet" href="{{ url('/') }}/css/plugins/OverlayScrollbars.min.css">

	<!-- TAGS INPUT -->
	<link rel="stylesheet" href="{{ url('/') }}/css/plugins/tagsinput.css">

	<!-- SUMMERNOTE -->
	<link rel="stylesheet" href="{{ url('/') }}/css/plugins/summernote.css">

	<!-- NOTIE -->
    <!-- <link rel="stylesheet" href="{{ url('/') }}/css/plugins/notie.css"> -->

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('/') }}/css/plugins/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/css/plugins/scroller.dataTables.min.css">
	<link rel="stylesheet" href="{{ url('/') }}/css/plugins/responsive.bootstrap.min.css"> 
    <link rel="stylesheet" href="{{ url('/') }}/css/plugins/select.dataTables.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/css/plugins/buttons.dataTables.min.css"> 

	<!-- CSS AdminLTE -->
    <link rel="stylesheet" href="{{ url('/') }}/css/plugins/adminlte.min.css">

	<!-- google fonts -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ url('/') }}/css/plugins/select2.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/css/plugins/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/css/plugins/util.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- PRINT CSS -->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/css/plugins/print.min.css">

    <!-- =============================================================
    PLUGINS DE JS
    ==============================================================  -->

    <!-- FontAwesome  -->
    <script src="https://kit.fontawesome.com/2776b79eec.js" crossorigin="anonymous"></script>

	<!-- jQuery library -->
    <script src="{{ url('/') }}/js/plugins/jquery-3.5.1.js"></script>
    <script src="{{ url('/') }}/js/plugins/jquery.validate.min.js"></script>
    <script src="{{ url('/') }}/js/plugins/traducciones.js"></script>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.ui.position.js"></script> -->

	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="{{ url('/') }}/js/plugins/bootstrap.min.js"></script>

	<!-- jquery.overlayScrollbars.min.js -->
	<!-- <script src="{{ url('/') }}/js/plugins/jquery.overlayScrollbars.min.js"></script> -->

    <!--TAGS INPUT -->
	<script src="{{ url('/') }}/js/plugins/print.min.js"></script>

    <!-- SUMMERNOTE -->
	<script src="{{ url('/') }}/js/plugins/summernote.js"></script>

    <!-- NOTIE -->
    <!-- <script src="{{ url('/') }}/js/plugins/notie.js"></script> -->

    <!--Moment JS -->
    <script src="{{ url('/') }}/js/moment.min.js"></script>

    <!-- https://sweetalert2.github.io/ -->
    <script src="{{ url('/') }}/js/plugins/sweetalert.js"></script>

    <!-- fullcalendar -->
    <!-- <script src="{{ url('/') }}/lib/main.min.js"></script> -->
    <!-- <script src="{{ url('/') }}/lib/locales/es.js"></script> -->

	<!-- JS AdminLTE -->
    <script src="{{ url('/') }}/js/plugins/adminlte.min.js"></script>

    <!-- JS Select2 -->
    <script src="{{ url('/') }}/js/select2.full.min.js"></script>

    <!-- DASHBOARD3 -->
    <!-- <script src="{{ url('/') }}/js/pages/dashboard3.js"></script> -->

    <!-- CHART -->
    <script src="{{ url('/') }}/js/plugins/chart.min.js"></script>
    <script src="{{ url('/') }}/js/plugins/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/js/plugins/dataTables.bootstrap4.min.js"></script>
	<script src="{{ url('/') }}/js/plugins/dataTables.responsive.min.js"></script>
    <script src="{{ url('/') }}/js/plugins/responsive.bootstrap.min.js"></script>
    <script src="{{ url('/') }}/js/plugins/dataTables.scroller.min.js"></script>
    <script src="{{ url('/') }}/js/plugins/dataTables.buttons.min.js"></script>
    <script src="{{ url('/') }}/js/plugins/jszip.min.js"></script>
    <script src="{{ url('/') }}/js/plugins/pdfmake.min.js"></script>
    <script src="{{ url('/') }}/js/plugins/vfs_fonts.js"></script>
    <script src="{{ url('/') }}/js/plugins/buttons.html5.min.js"></script>
    <script src="{{ url('/') }}/js/plugins/buttons.print.min.js"></script>
    <script src="{{ url('/') }}/js/plugins/datetime.js" charset="utf8"></script>
    <script src="{{ url('/') }}/js/plugins/dataTables.select.min.js"></script> 
</head>

@if (Route::has('login'))

    @auth

    <body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
        <div class="wrapper">
            @include('modulos.header')
            @include('modulos.sidebar')
            @yield('content')
            @include('modulos.footer')
        </div>

        <input type="hidden" id="ruta" value="{{url('/')}}">

        <script src="{{ url('/') }}/js/codigo.js"></script>
        <script src="{{ url('/') }}/js/categorias.js"></script>
        @yield('javascript')
    </body>

@else
    @include('paginas.login')
    @endauth
@endif

</html>
