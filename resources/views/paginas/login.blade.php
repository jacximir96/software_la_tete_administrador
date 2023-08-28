<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            La tete restobar
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Iniciar Sesión</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>

                        <input id="email" type="email" class="form-control email_login @error('email') is-invalid
                        @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                        placeholder="Correo Electrónico">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>Estas credenciales no coinciden con nuestros registros.</strong>
                            </span>
                        @enderror
                    </div>{{-- Fin Email --}}

                    {{-- Password --}}
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fas fa-key"></i>
                            </div>
                        </div>

                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password" placeholder="Contraseña">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>Estas credenciales no coinciden con nuestros registros.</strong>
                            </span>
                        @enderror
                    </div>{{-- Fin Password --}}

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">
                            Ingresar
                        </button>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                        @endif
                    </div>

                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <script src="{{ url('/') }}/js/login.js"></script>
</body>
