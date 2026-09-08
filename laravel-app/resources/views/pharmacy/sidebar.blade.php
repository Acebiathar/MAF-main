@php
  $navigation = [
    ['Dashboard', '/pharmacist', 'bi-house-door'],
    ['Manage Medicines', '/pharmacist/medicines', 'bi-bag-plus'],
    ['Stock & Inventory', '/pharmacist/inventory', 'bi-box-seam'],
    ['Prices', '/pharmacist/prices', 'bi-currency-exchange'],
    ['Reservations', '/pharmacist/requests', 'bi-calendar-check'],
    ['Subscription', '/pharmacist/subscription', 'bi-credit-card'],
    ['Profile', '/pharmacist/profile', 'bi-person'],
    ['Settings', '/pharmacist/settings', 'bi-gear'],
  ];
@endphp
@foreach($navigation as [$label, $url, $icon])
  <a href="{{ $url }}" class="nav-link {{ request()->getPathInfo() === $url ? 'active' : '' }}" @if(request()->getPathInfo() === $url) aria-current="page" @endif>
    <span class="dashboard-nav-main"><i class="bi {{ $icon }}" aria-hidden="true"></i><span class="fw-semibold">{{ $label }}</span></span>
    @if($label === 'Reservations' && ($pendingCount ?? 0) > 0)<span class="badge rounded-pill text-bg-warning">{{ $pendingCount }}</span>@endif
  </a>
@endforeach
