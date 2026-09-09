@foreach([
  ['Patient Home', '/requests', 'bi-house-door', 'home'],
  ['Search Medicine', '/', 'bi-search', null],
  ['Search History', '/requests?section=history', 'bi-clock-history', 'history'],
  ['Reservations', '/requests?section=reservations', 'bi-calendar-check', 'reservations'],
  ['Notifications', '/requests?section=notifications', 'bi-bell', 'notifications'],
] as [$label, $url, $icon, $key])
  <a href="{{ $url }}" class="nav-link {{ $section === $key ? 'active' : '' }}" @if($section === $key) aria-current="page" @endif>
    <span class="dashboard-nav-main"><i class="bi {{ $icon }}" aria-hidden="true"></i><span class="fw-semibold">{{ $label }}</span></span>
    @if($key === 'reservations' && $reservationCount > 0)<span class="badge rounded-pill text-bg-light">{{ $reservationCount }}</span>@endif
    @if($key === 'notifications' && $pendingCount > 0)<span class="badge rounded-pill text-bg-warning">{{ $pendingCount }}</span>@endif
  </a>
@endforeach
