<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Medicine Availability Finder')</title>

  <!-- Stylesheets -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  @yield('styles')

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

<body class="bg-light">

  @include('partials.header')

  <main class="py-4">
    @if(session('alerts'))
    @foreach (session('alerts') as $alert)
    <div class="container">
      <div class="alert alert-{{ $alert['category'] ?? 'info' }} alert-dismissible fade show shadow-sm" role="alert">
        {{ $alert['message'] ?? 'No message content' }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
    @endforeach
    @endif

    @hasSection('fullwidth')
    @yield('fullwidth')
    @else
    <div class="container">
      @yield('content')
    </div>
    @endif
  </main>

  @include('partials.footer')

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