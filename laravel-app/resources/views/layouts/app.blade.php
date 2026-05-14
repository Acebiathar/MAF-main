<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Medicine Availability Finder')</title>

  <!-- Stylesheets -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  <style>
    .footer-gradient {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #0f3460 100%);
      position: relative;
      overflow: hidden;
    }

    .footer-link {
      transition: all 0.3s ease;
      display: inline-block;
    }

    .footer-link:hover {
      color: #fff !important;
      transform: translateX(5px);
    }

    .back-to-top {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s;
      z-index: 1000;
    }

    .back-to-top.show {
      opacity: 1;
      visibility: visible;
    }

    .newsletter-input {
      border-radius: 25px 0 0 25px;
      border: none;
      padding: 12px 20px;
    }

    .newsletter-btn {
      border-radius: 0 25px 25px 0;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
    }

    @media (max-width: 991.98px) {
      .site-navbar .navbar-collapse {
        margin-top: 0.9rem;
        padding: 1rem;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 12px 32px rgba(15, 52, 96, 0.08);
      }
    }
  </style>
</head>

@php
  $isDashboardRoute = Request::is('requests')
    || Request::is('pharmacist')
    || Request::is('pharmacist/*')
    || Request::is('admin')
    || Request::is('admin/*');
@endphp

<body class="bg-light">

  @if(!$isDashboardRoute && !Request::is('register'))
  <nav class="navbar navbar-expand-lg navbar-light shadow-sm bg-white sticky-top site-navbar">
    <div class="container">
      <a class="navbar-brand fw-bold text-primary" href="/">Medfinder Ug</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbars">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbars">
        <ul class="navbar-nav mb-2 mb-lg-0">
          @if(isset($currentUser->role))
          @php
          $roleRoutes = [
          'pharmacist' => ['url' => '/pharmacist', 'label' => 'My Pharmacy'],
          'admin' => ['url' => '/admin', 'label' => 'Admin Dashboard'],
          'patient' => ['url' => '/requests', 'label' => 'My Requests'],
          ];
          $route = $roleRoutes[$currentUser->role] ?? null;
          @endphp

          @if($route)
          <li class="nav-item">
            <a class="nav-link {{ Request::is(trim($route['url'], '/')) ? 'active' : '' }}" href="{{ $route['url'] }}">
              {{ $route['label'] }}
            </a>
          </li>
          @endif
          @endif
        </ul>

        <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
          <li class="nav-item"><a class="nav-link {{ Request::is('how-it-works') ? 'active' : '' }}" href="{{ url('/how-it-works') }}">How it works</a></li>
          <li class="nav-item"><a class="nav-link {{ Request::is('about') ? 'active' : '' }}" href="{{ url('/about') }}">About</a></li>
          <li class="nav-item"><a class="nav-link {{ Request::is('contact') ? 'active' : '' }}" href="{{ url('/contact') }}">Contact</a></li>
        </ul>

        <hr class="d-lg-none my-2 text-muted">

        <ul class="navbar-nav ms-auto align-items-lg-center">
          @if(isset($currentUser))
          <li class="nav-item py-2 py-lg-0">
            <span class="navbar-text me-lg-3 text-dark small">
              Logged in as <strong>{{ $currentUser->name }}</strong>
            </span>
          </li>
          <li class="nav-item"><a class="btn btn-outline-danger btn-sm w-100" href="/logout">Logout</a></li>
          @else
          <li class="nav-item me-lg-2 mb-2 mb-lg-0"><a class="btn btn-outline-primary btn-sm w-100" href="/login">Login</a></li>
          <li class="nav-item"><a class="btn btn-primary btn-sm w-100" href="/register">Sign up</a></li>
          @endif
        </ul>
      </div>
    </div>
  </nav>
  @endif

  <main class="{{ $isDashboardRoute ? 'py-0' : 'py-4' }}">
    <div class="{{ $isDashboardRoute ? 'container-fluid px-0' : 'container' }}">
      @if(session('alerts'))
      @foreach (session('alerts') as $alert)
      <div class="alert alert-{{ $alert['category'] ?? 'info' }} alert-dismissible fade show shadow-sm" role="alert">
        {{ $alert['message'] ?? 'No message content' }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      @endforeach
      @endif

      @yield('content')
    </div>
  </main>

  @if(!$isDashboardRoute)
  <footer class="footer-gradient text-white py-5 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 mb-4">
          <div class="fw-bold fs-4 mb-3"><i class="bi bi-pills text-primary me-2"></i>Medfinder Ug</div>
          <p class="small opacity-75">Connecting patients to pharmacies across Uganda for reliable medicine access[cite: 1].</p>
        </div>

        <div class="col-lg-2 col-md-6 mb-4">
          <h6 class="fw-bold mb-3">Quick Links</h6>
          <ul class="list-unstyled">
            <li><a href="{{ url('/privacy') }}" class="footer-link text-white-50 text-decoration-none">Privacy Policy</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
          <h6 class="fw-bold mb-3">Newsletter</h6>
          <form action="/newsletter" method="POST" class="d-flex">
            @csrf
            <input type="email" name="email" class="newsletter-input form-control form-control-sm" placeholder="Your email" required>
            <button type="submit" class="newsletter-btn btn btn-sm px-4 text-white fw-bold"><i class="bi bi-send"></i></button>
          </form>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
          <h6 class="fw-bold mb-3">Follow Us</h6>
          <div class="fs-5">
            <a href="#" class="text-white-50 me-3"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-white-50 me-3"><i class="bi bi-twitter-x"></i></a>
            <a href="#" class="text-white-50"><i class="bi bi-whatsapp"></i></a>
          </div>
        </div>
      </div>
    </div>
  </footer>
  @endif

  <button id="backToTop" class="back-to-top"><i class="bi bi-arrow-up"></i></button>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const btn = document.getElementById('backToTop');
    window.onscroll = () => {
      if (window.scrollY > 300) btn.classList.add('show');
      else btn.classList.remove('show');
    };
    btn.onclick = () => window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  </script>
  @yield('scripts')
</body>

</html>
