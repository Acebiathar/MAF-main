<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Medicine Availability Finder</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('static/css/style.css') }}">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light shadow-sm bg-white sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="{{ url('/') }}">MedFinder</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbars" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbars">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="{{ url('/how-it-works') }}">How it works</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">About</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">Contact</a></li>
          @if($currentUser && $currentUser->role === 'pharmacist')
          <li class="nav-item"><a class="nav-link" href="{{ url('/pharmacist') }}">My pharmacy</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/pharmacist/requests') }}">Requests</a></li>
          @elseif($currentUser && $currentUser->role === 'admin')
          <li class="nav-item"><a class="nav-link" href="{{ url('/admin') }}">Admin</a></li>
          @elseif($currentUser && $currentUser->role === 'patient')
          <li class="nav-item"><a class="nav-link" href="{{ url('/requests') }}">My requests</a></li>
          @endif
        </ul>
        <ul class="navbar-nav ms-auto">
          @if($currentUser)
          <li class="nav-item"><span class="navbar-text me-2 text-secondary">Hi, {{ $currentUser->name }}</span></li>
          <li class="nav-item"><a class="btn btn-outline-primary btn-sm" href="{{ url('/logout') }}">Logout</a></li>
          @else
          <li class="nav-item me-2"><a class="btn btn-outline-primary btn-sm" href="{{ url('/login') }}">Login</a></li>
          <li class="nav-item"><a class="btn btn-primary btn-sm" href="{{ url('/register') }}">Sign up</a></li>
          @endif
        </ul>
      </div>
    </div>
  </nav>

  <main class="py-4">
    <div class="container">
      @foreach($alerts as [$category, $message])
      <div class="alert alert-{{ $category }} alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endforeach
      @yield('content')
    </div>
  </main>

  <footer class="bg-dark text-white py-4 mt-5">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
      <div>
        <div class="fw-bold">MedFinder Uganda</div>
        <div class="small text-muted">Making medicine easy to find since 2026.</div>
      </div>
      <div class="small">
        <a class="text-white me-3" href="{{ url('/about') }}">About</a>
        <a class="text-white me-3" href="{{ url('/contact') }}">Contact</a>
        <span class="text-white-50">(c) 2026</span>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('static/js/main.js') }}"></script>
</body>

</html>