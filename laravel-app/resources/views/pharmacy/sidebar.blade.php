@php
  $navigation = [
    ['Dashboard', '/pharmacist'],
    ['Manage Medicines', '/pharmacist/medicines'],
    ['Stock & Inventory', '/pharmacist/inventory'],
    ['Prices', '/pharmacist/prices'],
    ['Reservations', '/pharmacist/requests'],
    ['Subscription', '/pharmacist/subscription'],
    ['Profile', '/pharmacist/profile'],
    ['Settings', '/pharmacist/settings'],
  ];
@endphp
@foreach($navigation as [$label, $url])
  <a href="{{ $url }}" class="nav-link {{ request()->getPathInfo() === $url ? 'active' : '' }}" @if(request()->getPathInfo() === $url) aria-current="page" @endif>
    <span class="dashboard-nav-main"><span class="fw-semibold">{{ $label }}</span></span>
    @if($label === 'Reservations' && ($pendingCount ?? 0) > 0)<span class="badge rounded-pill text-bg-warning">{{ $pendingCount }}</span>@endif
  </a>
@endforeach
