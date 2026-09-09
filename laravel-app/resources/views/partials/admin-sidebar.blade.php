@foreach([
  ['Dashboard', '/admin', request()->is('admin', 'admin/dashboard')],
  ['Users', '/admin/view/users', request()->is('admin/view/users')],
  ['Pharmacies', '/admin/view/pharmacies', request()->is('admin/view/pharmacies')],
  ['Subscription', '/admin/subscriptions', request()->is('admin/subscriptions*')],
  ['Medicines', '/admin/view/medicines', request()->is('admin/view/medicines')],
  ['Reservations', '/admin/view/reservations', request()->is('admin/view/reservations')],
  ['Reports', '/admin/reports', request()->is('admin/reports*', 'admin/activity')],
  ['Settings', '/admin/settings', request()->is('admin/settings')],
] as [$label, $url, $active])
  <a href="{{ $url }}" class="nav-link {{ $active ? 'active' : '' }}" @if($active) aria-current="page" @endif><span class="dashboard-nav-main"><span class="fw-semibold">{{ $label }}</span></span></a>
@endforeach
