<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Medicine Availability Finder</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-light shadow-sm bg-white sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold text-primary" href="/">MedFinder</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbars">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbars">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="/how-it-works">How it works</a></li>
          <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
          <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
          
          @if ($currentUser)
              @if ($currentUser->role === 'pharmacist')
                <li class="nav-item"><a class="nav-link" href="/pharmacist">My pharmacy</a></li>
                <li class="nav-item"><a class="nav-link" href="/pharmacist/requests">Requests</a></li>
              @elseif ($currentUser->role === 'admin')
                <li class="nav-item"><a class="nav-link" href="/admin">Admin Dashboard</a></li>
              @elseif ($currentUser->role === 'patient')
                <li class="nav-item"><a class="nav-link" href="/requests">My requests</a></li>
              @endif
          @endif
        </ul>

        <ul class="navbar-nav ms-auto">
          @if ($currentUser)
            <li class="nav-item d-flex align-items-center">
                <span class="navbar-text me-3 text-secondary">Hi, <strong>{{ $currentUser->name }}</strong></span>
            </li>
            <li class="nav-item"><a class="btn btn-outline-danger btn-sm" href="/logout">Logout</a></li>
          @else
            <li class="nav-item me-2"><a class="btn btn-outline-primary btn-sm" href="/login">Login</a></li>
            <li class="nav-item"><a class="btn btn-primary btn-sm" href="/register">Sign up</a></li>
          @endif
        </ul>
      </div>
    </div>
  </nav>

  <main class="py-4">
    <div class="container">
      {{-- Alerts Section --}}
      @if(session('alerts'))
        @foreach (session('alerts') as $alert)
          <div class="alert alert-{{ $alert['category'] }} alert-dismissible fade show shadow-sm" role="alert">
            {{ $alert['message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endforeach
      @endif

      {{-- THIS IS THE MOST IMPORTANT LINE --}}
      @yield('content')
      
    </div>
  </main>

  <footer class="bg-dark text-white py-5 mt-5">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
      <div>
        <div class="fw-bold fs-5">MedFinder Uganda</div>
        <div class="small text-muted">Connecting patients to pharmacies.</div>
      </div>
      <div class="small">
        <a class="text-white-50 text-decoration-none me-3" href="/about">About</a>
        <a class="text-white-50 text-decoration-none me-3" href="/contact">Contact</a>
        <span class="text-white-50">&copy; 2026</span>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>