@foreach([
  ['Patient Home', '/requests', 'home'],
  ['Search Medicine', '/', null],
  ['Search History', '/requests?section=history', 'history'],
  ['Reservations', '/requests?section=reservations', 'reservations'],
  ['Notifications', '/requests?section=notifications', 'notifications'],
] as [$label, $url, $key])
  <a href="{{ $url }}" class="nav-link {{ $section === $key ? 'active' : '' }}" @if($section === $key) aria-current="page" @endif>
    <span class="dashboard-nav-main"><span class="fw-semibold">{{ $label }}</span></span>
    @if($key === 'reservations' && $reservationCount > 0)<span class="badge rounded-pill text-bg-light">{{ $reservationCount }}</span>@endif
    @if($key === 'notifications' && $pendingCount > 0)<span class="badge rounded-pill text-bg-warning">{{ $pendingCount }}</span>@endif
  </a>
@endforeach
