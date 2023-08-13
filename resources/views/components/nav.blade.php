<nav id="main-navbar" class="mb-1 navbar navbar-expand-lg navbar-light bg-white fixed-top">
    <div class="container-fluid">
        <button id="toggler" class="navbar-toggler" type="button" data-toggle="sidenav" data-target="#app-sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <a class="navbar-brand" href="{{ route('app.index') }}">
            <img src="{{ asset('img/logo1.png') }}" class="img-fluidr" height="40px" width="80px" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav"
            aria-controls="app-nav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse ml-3" id="app-nav">
            <ul class="navbar-nav ml-auto nav-flex-icons text-right">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink-333"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user"></i> {{ auth()->user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-default" aria-labelledby="">
                        @if (auth()->user()->user_role == 'marchand')
                            <a class="dropdown-item" href="{{ route('marchand.web.compte') }}">Mon compte</a>
                        @endif
                        <a class="dropdown-item" href="{{ route('logout.web') }}">
                            <i class="fas fa-exit"></i> Se deconnecter
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
