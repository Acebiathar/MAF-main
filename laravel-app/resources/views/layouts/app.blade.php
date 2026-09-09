<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Medicine Availability Finder')</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">

  @yield('styles')

  <style>
    /* Basic navbar styles to ensure visibility on all pages */
    .site-navbar {
      position: sticky;
      top: 0;
      background-color: #ffffff !important;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      z-index: 1050;
    }

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

    .auth-buttons .btn {
      position: relative;
      border-width: 2px;
      transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease, color 0.25s ease;
      min-width: 110px;
      letter-spacing: 0.02em;
    }

    .auth-buttons .btn-outline-primary {
      color: #2563eb;
      border-color: rgba(37, 99, 235, 0.5);
      background: rgba(37, 99, 235, 0.05);
    }

    .auth-buttons .btn-primary {
      background: linear-gradient(135deg, #2563eb 0%, #2563eb 100%);
      border-color: #2563eb;
      box-shadow: 0 12px 24px rgba(37, 99, 235, 0.18);
    }

    .auth-buttons .btn:hover,
    .auth-buttons .btn.btn-hover,
    .auth-buttons .btn.active {
      transform: translateY(-2px);
      box-shadow: 0 18px 32px rgba(37, 99, 235, 0.22);
    }

    .auth-buttons .btn-register.active {
      background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
      color: #fff;
    }

    .auth-buttons .btn-login.active {
      background: #eff6ff;
      color: #1d4ed8;
    }

    .auth-buttons .btn::after {
      content: attr(data-label);
      position: absolute;
      opacity: 0;
      pointer-events: none;
      left: 50%;
      transform: translateX(-50%) translateY(-140%);
      background: rgba(15, 23, 42, 0.9);
      color: #fff;
      font-size: 0.75rem;
      padding: 0.35rem 0.65rem;
      border-radius: 999px;
      white-space: nowrap;
      transition: opacity 0.2s ease, transform 0.2s ease;
    }

    .auth-buttons .btn:hover::after {
      opacity: 1;
      transform: translateX(-50%) translateY(-160%);
    }

    @media (max-width: 991.98px) {
      .site-navbar .navbar-collapse {
        margin-top: 0.9rem;
        padding: 1rem;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 12px 32px rgba(15, 52, 96, 0.08);
      }

      .auth-buttons {
        width: 100%;
        justify-content: center;
      }
    }
    .flash-toast-container {
      position: fixed;
      top: 1rem;
      right: 1rem;
      width: min(380px, calc(100vw - 2rem));
      max-height: calc(100dvh - 2rem);
      overflow-y: auto;
      z-index: 11000;
      pointer-events: none;
    }

    .flash-toast {
      width: 100%;
      background: #fff;
      color: #10233c;
      border: 1px solid #e2e8f0;
      border-left: 4px solid var(--flash-accent);
      border-radius: 12px;
      box-shadow: 0 8px 28px rgba(16, 35, 60, 0.16);
      pointer-events: auto;
    }

    .flash-toast-icon { color: var(--flash-accent); }
  </style>
</head>

<body class="{{ (Request::is('requests*') || Request::is('dashboard*') || Request::is('search*') || Request::is('pharmacist*') || Request::is('admin*') || Request::is('account*')) ? '' : 'bg-light' }}">

  {{-- Conditionally hide header for dashboard views --}}
  @unless(Request::is('requests*') || Request::is('dashboard*') || Request::is('pharmacist*') || Request::is('admin*') || Request::is('account*'))
    @include('partials.header')
  @endunless

  <main class="{{ (Request::is('requests*') || Request::is('dashboard*') || Request::is('search*') || Request::is('pharmacist*') || Request::is('admin*') || Request::is('account*')) ? '' : 'py-4' }}">

    <div class="toast-container flash-toast-container" aria-live="polite" aria-atomic="false">
      @foreach(session()->pull('alerts', []) as $alert)
        @php
          $category = $alert['category'] ?? 'info';
          [$accent, $icon, $label] = match ($category) {
            'success' => ['#198754', 'bi-check-circle-fill', 'Success'],
            'danger', 'error' => ['#dc3545', 'bi-exclamation-circle-fill', 'Error'],
            'warning' => ['#b77900', 'bi-exclamation-triangle-fill', 'Notice'],
            default => ['#0d6efd', 'bi-info-circle-fill', 'Update'],
          };
        @endphp
        <div class="toast flash-toast" role="status" aria-atomic="true" style="--flash-accent: {{ $accent }}" data-bs-delay="{{ in_array($category, ['danger', 'error', 'warning']) ? 8000 : 5000 }}">
          <div class="toast-body d-flex align-items-start gap-3 p-3">
            <i class="bi {{ $icon }} flash-toast-icon fs-5" aria-hidden="true"></i>
            <div class="flex-grow-1"><div class="fw-semibold mb-1">{{ $label }}</div><div>{{ $alert['message'] ?? '' }}</div></div>
            <button type="button" class="btn-close flex-shrink-0" data-bs-dismiss="toast" aria-label="Dismiss notification"></button>
          </div>
        </div>
      @endforeach
    </div>

    @hasSection('fullwidth')
    @yield('fullwidth')
    @else
    <div class="container">
      @yield('content')
    </div>
    @endif
  </main>

  {{-- Conditionally hide footer for dashboard views --}}
  @unless(Request::is('requests*') || Request::is('dashboard*') || Request::is('pharmacist*') || Request::is('admin*') || Request::is('account*'))
    @include('partials.footer')
  @endunless

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.querySelectorAll('.flash-toast').forEach(element => {
      const toast = bootstrap.Toast.getOrCreateInstance(element, { autohide: true });
      element.addEventListener('hidden.bs.toast', () => {
        toast.dispose();
        element.remove();
      });
      toast.show();
    });
  </script>
  <script>
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
      window.onscroll = () => {
        if (window.scrollY > 300) backToTop.classList.add('show');
        else backToTop.classList.remove('show');
      };
      backToTop.onclick = () => window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    }

    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('[data-password-toggle]').forEach(button => {
        const input = document.getElementById(button.dataset.passwordToggle);
        if (!input) return;
        button.addEventListener('click', () => {
          const reveal = input.type === 'password';
          input.type = reveal ? 'text' : 'password';
          button.setAttribute('aria-pressed', String(reveal));
          button.setAttribute('aria-label', reveal ? 'Hide password' : 'Show password');
          button.title = reveal ? 'Hide password' : 'Show password';
          button.querySelector('i').className = reveal ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
      });

      const authButtons = document.querySelectorAll('.auth-buttons .btn');
      authButtons.forEach(button => {
        button.addEventListener('mouseenter', () => button.classList.add('btn-hover'));
        button.addEventListener('mouseleave', () => button.classList.remove('btn-hover'));
        button.addEventListener('click', () => {
          authButtons.forEach(item => item.classList.remove('active'));
          button.classList.add('active');
        });
      });

      const path = window.location.pathname.toLowerCase();
      if (path.includes('/login')) {
        document.querySelector('.btn-login')?.classList.add('active');
      } else if (path.includes('/register')) {
        document.querySelector('.btn-register')?.classList.add('active');
      }
    });
  </script>
  <!-- Debug: log navbar clicks and guard against accidental '#' navigation -->
  <script>
    (function(){
      if (!window.console) return;
      document.addEventListener('click', function(e){
        const a = e.target.closest && e.target.closest('a');
        if (!a) return;
        console.log('[NAV DEBUG] link click:', a.href, 'text:', a.textContent.trim());
        // Prevent inert anchors from navigating and log them for debugging
        if (a.getAttribute('href') === '#') {
          e.preventDefault();
          console.warn('[NAV DEBUG] prevented inert "#" navigation for', a);
        }
      }, true);

      window.addEventListener('popstate', function(e){
        console.log('[NAV DEBUG] popstate event', e);
      });

      window.addEventListener('beforeunload', function(){
        console.log('[NAV DEBUG] beforeunload fired');
      });
    })();
  </script>
  @yield('scripts')
</body>

</html>
