<x-home-css />

<nav id="main-navbar" class="mb-1 navbar navbar-expand-lg navbar-dark bg-dark fixed-top scrolling-navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('app.index') }}">
            <img src="{{ asset('img/logo1.png') }}" class="img-fluidr" height="40px" width="80px" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="app-nav"
            aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse ml-3" id="app-nav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link font-weight-bold @if (Route::is('app.index')) active @endif"
                        href="{{ route('app.index') }}">
                        <span>
                            <i class="fa fa-home"></i> Accueil
                            <span class="sr-only">(current)</span>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold @if (Route::is('app.login')) active @endif"
                        href="{{ route('app.login') }}">
                        <span><i class="fa fa-user-alt"></i> Connexion</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
