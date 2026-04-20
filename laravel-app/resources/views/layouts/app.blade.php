<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Medicine Availability Finder</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<<<<<<< Updated upstream
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
=======

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

>>>>>>> Stashed changes
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    .footer-gradient {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #0f3460 100%);
      position: relative;
      overflow: hidden;
    }

    .footer-gradient::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
      animation: float 6s ease-in-out infinite;
      pointer-events: none;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0) rotate(0deg);
      }

      50% {
        transform: translateY(-10px) rotate(1deg);
      }
    }

    .footer-link {
      transition: all 0.3s ease;
      position: relative;
      z-index: 1;
      display: inline-block;
      pointer-events: auto;
    }

    .footer-link:hover {
      color: #fff !important;
      transform: translateX(5px);
      text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    .social-icon {
      transition: all 0.3s ease;
      transform: scale(1);
    }

    .social-icon:hover {
      transform: scale(1.2) rotate(10deg);
      color: #fff !important;
      text-shadow: 0 0 15px currentColor;
    }

    .btn-footer {
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      transition: all 0.3s;
      border: none !important;
    }

    .btn-footer:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
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
      font-size: 20px;
      box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s;
      z-index: 1000;
    }

    .back-to-top.show {
      opacity: 1;
      visibility: visible;
    }

    .brand-glow {
      text-shadow: 0 0 20px rgba(13, 110, 253, 0.5);
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
  </style>
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

        @if (auth()->check())
    @if (auth()->user()->role === 'pharmacist')
          <li class="nav-item"><a class="nav-link fw-bold text-primary" href="/pharmacist">My Pharmacy</a></li>
          @elseif ($currentUser->role === 'admin')
          <li class="nav-item"><a class="nav-link fw-bold text-primary" href="/admin">Admin Dashboard</a></li>
          @elseif ($currentUser->role === 'patient')
          <li class="nav-item"><a class="nav-link fw-bold text-primary" href="/requests">My Requests</a></li>
          @endif
          @endif
        </ul>

        <!-- Mobile Divider for better spacing -->
        <hr class="d-lg-none my-2 text-muted">

        <ul class="navbar-nav ms-auto align-items-lg-center">
          @if ($currentUser)
          <!-- On Mobile: Compact Profile | On Desktop: Normal Text -->
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
      <!-- Alerts Section -->
      @if(session('alerts'))
      @foreach (session('alerts') as $alert)
 <div class="alert alert-{{ $alertData['category'] ?? 'info' }} alert-dismissible fade show shadow-sm" role="alert">
            {{ $alertData['message'] ?? 'No message content' }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endforeach
      @endif

      <!-- THIS IS THE MOST IMPORTANT LINE -->
      @yield('content')

    </div>
  </main>

  <footer class="footer-gradient text-white py-5 mt-5">
    <div class="container">
      <div class="row">
        <!-- Brand Column -->
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="fw-bold fs-4 mb-3 brand-glow"><i class="bi bi-pills text-primary me-2"></i>MAF Uganda</div>
          <p class="small opacity-75 mb-0">Connecting patients to pharmacies. Making medicine accessible by helping patients locate drugs quickly and reliably.</p>
        </div>
        <!-- Links Column -->
        <div class="col-lg-2 col-md-6 mb-4">
          <h6 class="fw-bold mb-3">Quick Links</h6>
          <ul class="list-unstyled">
            <li><a href="{{ url('/') }}" class="footer-link text-white-50 text-decoration-none"><i class="bi bi-house-door me-1"></i>Home</a></li>
            <li><a href="{{ url('/how-it-works') }}" class="footer-link text-white-50 text-decoration-none"><i class="bi bi-play-circle me-1"></i>How it works</a></li>
            <li><a href="{{ url('/about') }}" class="footer-link text-white-50 text-decoration-none"><i class="bi bi-info-circle me-1"></i>About</a></li>
            <li><a href="{{ url('/contact') }}" class="footer-link text-white-50 text-decoration-none"><i class="bi bi-envelope me-1"></i>Contact</a></li>
            <li><a href="{{ url('/privacy') }}" class="footer-link text-white-50 text-decoration-none"><i class="bi bi-shield-lock-fill me-1"></i>Privacy Policy</a></li>
          </ul>
        </div>
        <!-- Contact/Suggestion Column -->
        <div class="col-lg-3 col-md-6 mb-4">
          <h6 class="fw-bold mb-3">Suggestion Box</h6>
          <p class="small opacity-75 mb-3">Have feedback? Share with us!</p>
          <a href="/contact" class="btn-footer btn btn-outline-light btn-sm"><i class="bi bi-chat-square-text me-1"></i>Send Message</a>
          <div class="mt-3">
            <h6 class="fw-bold mb-3">Newsletter</h6>
            <form class="d-flex">
              <input type="email" class="newsletter-input form-control form-control-sm me-2" placeholder="Your email">
              <button type="submit" class="newsletter-btn btn btn-sm px-4 text-white fw-bold"><i class="bi bi-send"></i></button>
            </form>
            <small class="opacity-75 mt-2 d-block">Get medicine alerts &amp; tips</small>
          </div>
        </div>
        <!-- Social Column -->
        <div class="col-lg-3 col-md-6 mb-4">
          <h6 class="fw-bold mb-3">Follow Us</h6>
          <div>
            <a href="#" class="social-icon text-white-50 me-3 fs-5"><i class="bi bi-facebook"></i></a>
            <a href="#" class="social-icon text-white-50 me-3 fs-5"><i class="bi bi-twitter-x"></i></a>
            <a href="#" class="social-icon text-white-50 me-3 fs-5"><i class="bi bi-whatsapp"></i></a>
            <a href="#" class="social-icon text-white-50 me-3 fs-5"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
      </div>
      <hr class="my-4 opacity-25">
      <div class="row align-items-center">
        <div class="col-md-6">
          <small class="text-white-50">&amp;copy; 2026 MAF Uganda. All rights reserved. | <a href="{{ url('/privacy') }}" class="text-white-50 text-decoration-none">Privacy Policy</a></small>
        </div>
        <div class="col-md-6 text-md-end">
          <small class="text-white-50">Made with <i class="bi bi-heart-fill text-danger"></i> for better healthcare</small>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/main.js') }}"></script>
  <button id="backToTop" class="back-to-top" title="Back to top"><i class="bi bi-arrow-up"></i></button>
  <script>
    // Back to top functionality
    window.onscroll = function() {
      const btn = document.getElementById('backToTop');
      if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        btn.classList.add('show');
      } else {
        btn.classList.remove('show');
      }
    };
    document.getElementById('backToTop').onclick = function() {
      document.body.scrollTop = 0;
      document.documentElement.scrollTop = 0;
    };
    // Newsletter placeholder
    document.querySelector('.newsletter-btn').onclick = function(e) {
      e.preventDefault();
      alert('Thanks for subscribing! (Demo)');
    };
  </script>
  @yield('scripts')
</body>

</html>