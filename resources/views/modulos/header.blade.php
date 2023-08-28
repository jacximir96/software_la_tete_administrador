<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">

      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" style="display:none;">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">

          </span>
        </a>

        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">

          </span>

                    <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <p></p>
                            <p># Cod. Patrimonial: </p>
                        </a>

          <div class="dropdown-divider"></div>
          <!-- <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a> -->
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" id="nombre_usuario_header">

            @foreach ($administradores as $element)
                @if ($_COOKIE["email_login"] == $element->email)
                    Hola, {{$element->name}}
                @endif
            @endforeach

        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('logout')}}" onclick="event.preventDefault();
                                                                 document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt"></i>
        </a>

        <form action="{{ route('logout')}}" id="logout-form" method="POST" style="display: none;">
            @csrf
        </form>
      </li>

    </ul>
  </nav>
