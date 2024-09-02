<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{ url('/') }}/vistas/img/logo.jpg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">La tete restobar</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            @foreach ($administradores as $element)
                @if ($_COOKIE["email_login"] == $element->email)
                   @if ($element->foto == "")
                    <img src="{{ url('/') }}/vistas/img/admin.png" class="img-circle elevation-2" alt="User Image">
                   @else
                   <img src="{{ url('/') }}/{{$element->foto}}" class="img-circle elevation-2" alt="User Image">
                   @endif
                @endif
            @endforeach
        </div>
        <div class="info">
          <a href="#" class="d-block">
            {{-- Verificar el usuario que ingresó al sistema --}}
            @foreach ($administradores as $element)
                @if ($_COOKIE["email_login"] == $element->email)
                    {{$element->name}}
                @endif
            @endforeach
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                {{-- Verificar el usuario que ingresó al sistema --}}
                    @foreach ($administradores as $element)
                        @if ($_COOKIE["email_login"] == $element->email)

                                <!--=====================================
                                Botón Dashboard
                                ======================================-->
                                <li class="nav-item">
                                    <a href="{{ url("/dashboard") }}" class="nav-link">
                                    <i class="nav-icon fa fa-tachometer"></i>
                                    <p>Inicio</p>
                                    </a>
                                </li>

                                <!--=====================================
                                Botón Manual
                                ======================================-->
                                <li class="nav-item">
                                    <a href="{{ url("/manual") }}" class="nav-link">
                                    <i class="nav-icon fas fa-book-open"></i>
                                    <p>Manual</p>
                                    </a>
                                </li>

                                <!--=====================================
                                Botón Unidades de Medida
                                ======================================-->
                                @canany(['ver_unidadesMedida','crear_unidadesMedida','editar_unidadesMedida','eliminar_unidadesMedida','reportesPDF_unidadesMedida','reportesEXCEL_unidadesMedida'])
                                <li class="nav-item">
                                    <a href="{{ url("/unidadesMedida") }}" class="nav-link">
                                    <i class="fas fa-balance-scale nav-icon"></i>
                                    <p>Unidades de Medida</p>
                                    </a>
                                </li>
                                @endcan

                                <!--=====================================
                                Botón Unidades de Medida
                                ======================================-->
                                @canany(['ver_gastos','crear_gastos','editar_gastos','eliminar_gastos','reportesPDF_gastos','reportesEXCEL_gastos'])
                                <li class="nav-item">
                                    <a href="{{ url("/gastos") }}" class="nav-link">
                                    <i class="fas fa-balance-scale nav-icon"></i>
                                    <p>Gastos</p>
                                    </a>
                                </li>
                                @endcan

                                <!--=====================================
                                Botón Categorías
                                ======================================-->
                                @canany(['ver_categorias','crear_categorias','editar_categorias','eliminar_categorias','reportesPDF_categorias','reportesEXCEL_categorias'])
                                <li class="nav-item">
                                    <a href="{{ url("/categorias") }}" class="nav-link">
                                    <i class="fas fa-list-alt nav-icon"></i>
                                    <p>Categorías</p>
                                    </a>
                                </li>
                                @endcan

                                <!--=====================================
                                Botón Fuas Generados
                                ======================================-->
                                @can('VER_GENERACION')
                                <li class="nav-item has-treeview">
                                    <a href="#" class="nav-link">
                                    <i class="nav-icon fa fa-plus-square" aria-hidden="true"></i>
                                    <p>
                                        Generación
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ url("/pacientesCitados") }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Pacientes Citados</p>
                                            </a>
                                        </li>
                                        
                                        <li class="nav-item">
                                            <a href="{{ url("/fuasEmitidos") }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>FUAS/CG Emitidos</p>
                                            </a>
                                        </li>
                                        
                                        <li class="nav-item">
                                            <a href="{{ url("/fuasObservados") }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>FUAS Observados</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                @endcan

                                
                                <!--=====================================
                                Botón Fuas Generados
                                ======================================-->
                                @can('VER_GENERACION_GENERAL')
                                    <li class="nav-item">
                                        <a href="{{ url("/fuasEmitidosG") }}" class="nav-link">
                                        <i class="nav-icon fa fa-check-square"></i>
                                        <p>FUAS Emitidos General</p>
                                        </a>
                                    </li>
                                @endcan

                                <!--=====================================
                                Botón Fuas Generados
                                ======================================-->
                                @can('VER_DIGITACION')
                                <li class="nav-item has-treeview">
                                    <a href="#" class="nav-link">
                                    <i class="nav-icon fa fa-paper-plane" aria-hidden="true"></i>
                                    <p>
                                        Digitación
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ url("/fuasDigitados") }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Digitación</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                @endcan

                                <!--=====================================
                                Botón Fuas Generados
                                ======================================-->
                                @can('VER_AUDITORIAMEDICA')
                                <li class="nav-item has-treeview">
                                    <a href="#" class="nav-link">
                                    <i class="nav-icon fa fa-industry" aria-hidden="true"></i>
                                    <p>
                                        Autoría Médica
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ url("/auditoriaEmitidos") }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Auditoría</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                @endcan

                                <!--=====================================
                                Botón Fuas Generados
                                ======================================-->
                                @can('VER_ACERVO')
                                <li class="nav-item has-treeview">
                                    <a href="#" class="nav-link">
                                    <i class="nav-icon fa fa-tasks" aria-hidden="true"></i>
                                    <p>
                                        Acervo
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ url("/fuasAcervo") }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Acervo</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                @endcan

                                <!--=====================================
                                Botón de Productos
                                ======================================-->
                                <li class="nav-item has-treeview">
                                    <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-eraser"></i>
                                    <p>
                                        Gestión de Productos
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                    </a>

                                    <ul class="nav nav-treeview">

                                        <li class="nav-item has-treeview">
                                            <a href="#" class="nav-link">
                                            <i class="nav-icon fas fa-chart-pie"></i>
                                            <p>
                                                Almacén
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                            </a>

                                            <ul class="nav nav-treeview">
                                                @canany(['ver_productosLimpieza','crear_productosLimpieza','editar_productosLimpieza','eliminar_productosLimpieza','reportesEXCEL_productosLimpieza','reportesActivosPDF_productosLimpieza','reportesAgotadosPDF_productosLimpieza'])
                                                <li class="nav-item">
                                                    <a href="{{ url("/productosLimpieza") }}" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Productos</p>
                                                    </a>
                                                </li>
                                                @endcan
                                                <li class="nav-item">
                                                    <a href="{{ url("/insumos") }}" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Insumos</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>

                                    
                                    <!-- <li class="nav-item">
                                        <a href="{{ url("/recepcionLimpieza") }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Recepción de Materiales</p>
                                        </a>
                                    </li> -->

                                    <!-- <li class="nav-item">
                                        <a href="{{ url("/formatoLimpieza") }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Formato</p>
                                        </a>
                                    </li> -->

                                    <li class="nav-item">
                                        <a href="{{ url("/kardexLimpieza") }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Kardex de Productos</p>
                                        </a>
                                    </li>

                                    </ul>
                                </li>

                                <!--=====================================
                                Botón Fuas Generados
                                ======================================-->
                                @can('VER_REPORTES')
                                <li class="nav-item has-treeview">
                                    <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-file" aria-hidden="true"></i>
                                    <p>
                                        Reportes
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ url("/reportesEntreFechas") }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Entre Fechas</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                @endcan

                                <!--=====================================
                                Botón Gestión de Usuarios
                                ======================================-->
                                @can('ver_usuarios')
                                <li class="nav-item has-treeview">
                                    <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-chart-pie" aria-hidden="true"></i>
                                    <p>
                                        Gestión de Usuarios
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                    </a>

                                    <ul class="nav nav-treeview">
                                   
                                    <li class="nav-item">
                                        <a href="{{ url("/administradores") }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Usuarios</p>
                                        </a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a href="{{ url("/roles") }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Roles</p>
                                        </a>
                                    </li>
    
                                    </ul>
                                </li>
                                @endcan

                                <!--=====================================
                                Botón Soporte Técnico
                                ======================================-->
                                <!-- @can('VER_GENERACION_GENERAL') -->
                                    <li class="nav-item">
                                        <a href="{{ url("/soporteTecnico") }}" class="nav-link">
                                        <i class="nav-icon fa fa-cog"></i>
                                        <p>Soporte Técnico</p>
                                        </a>
                                    </li>
                                <!-- @endcan -->

                                <!--=====================================
                                Botón Reportes
                                ======================================-->
                                <li class="nav-item has-treeview">
                                    <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        Reportes
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                    </a>

                                    <ul class="nav nav-treeview">

                                        <li class="nav-item">
                                            <a href="{{ url("/reportesEntreFechas") }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Entre Fechas</p>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                        @endif
                    @endforeach
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
