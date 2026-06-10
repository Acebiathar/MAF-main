<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm site-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">MedFinder</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item center-nav-item"><a class="nav-link" href="{{ route('how') }}">How it Works</a></li>
                <li class="nav-item center-nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                <li class="nav-item center-nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>

                <li class="nav-item auth-buttons d-flex gap-2 align-items-center">
                    @if(currentUser())
                    <a href="{{ route('logout') }}" class="btn btn-outline-danger px-4 rounded-pill">Logout</a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-login px-4 rounded-pill" data-label="Login">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-register text-white px-4 rounded-pill" data-label="Register">Register</a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</nav>