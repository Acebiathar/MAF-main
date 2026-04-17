<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Medicine Availability Finder</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bg-light">
  @if(Request::path() !== 'register')
  <nav class="navbar navbar-expand-lg navbar-light shadow-sm bg-white sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold text-primary" href="/">MAF</a>
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
          <li class="nav-item"><a class="nav-link fw-bold text-primary" href="/pharmacist">My Pharmacy</a></li>
          @elseif ($currentUser->role === 'admin')
          <li class="nav-item"><a class="nav-link fw-bold text-primary" href="/admin">Admin Dashboard</a></li>
          @elseif ($currentUser->role === 'patient')
          <li class="nav-item"><a class="nav-link fw-bold text-primary" href="/requests">My Requests</a></li>
          @endif
          @endif
        </ul>

        {{-- Mobile Divider for better spacing --}}
        <hr class="d-lg-none my-2 text-muted">

        <ul class="navbar-nav ms-auto align-items-lg-center">
          @if ($currentUser)
          {{-- On Mobile: Compact Profile | On Desktop: Normal Text --}}
          <li class="nav-item py-2 py-lg-0">
            <div class="d-flex align-items-center">
              <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 d-lg-none" style="width: 30px; height: 30px; font-size: 12px;">
                {{ substr($currentUser->name, 0, 1) }}
              </div>
              <span class="navbar-text me-lg-3 text-dark small">Logged in as <strong>{{ $currentUser->name }}</strong></span>
            </div>
          </li>
          <li class="nav-item mt-2 mt-lg-0">
            <a class="btn btn-outline-danger btn-sm w-100 w-lg-auto" href="/logout">Logout</a>
          </li>
          @else
          <li class="nav-item me-lg-2 mb-2 mb-lg-0"><a class="btn btn-outline-primary btn-sm w-100 w-lg-auto" href="/login">Login</a></li>
          <li class="nav-item"><a class="btn btn-primary btn-sm w-100 w-lg-auto" href="/register">Sign up</a></li>
          @endif
        </ul>
      </div>
  </nav>
  @endif

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

  <footer class="bg-dark text-white py-2 mt-2">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
      <div>
        <div class="fw-bold fs-5">MAF Uganda</div>
        <div class="small">Connecting patients to pharmacies.</div>
      </div>
      <div class="small">
        <a class="text-white-40 text-decoration-none me-3" href="/about">About</a>
        <a class="text-white-40 text-decoration-none me-3" href="/contact">Contact</a>
        <span class="text-white-40">&copy; 2026</span>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/main.js') }}"></script>
  @yield('scripts')
</body>

</html>