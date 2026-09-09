@extends('layouts.dashboard')
@php
  $pendingCount = $pending->count();
  $selectedViewLabel = $viewType ? ucfirst($viewType) : 'Overview';
  $pageTitle = $viewType ? $selectedViewLabel : match ($mode) { 'reports' => 'System Reports', 'activity' => 'Recent Activity', 'settings' => 'Account Settings', default => 'Admin Dashboard' };
@endphp
@section('title', $pageTitle.' | MedFinder')
@section('dashboard_title', $pageTitle)
@section('dashboard_subtitle', 'Monitor system activities and manage users, pharmacies and medicines.')
@section('dashboard_notification_badge', (string) ($pendingCount + $pendingPayments))
@section('dashboard_sidebar')
  @include('partials.admin-sidebar')
@endsection
@section('dashboard_stats')
  @if($mode !== 'settings')
    @foreach([['Total Users', 'users', 'bi-people-fill', 'blue'], ['Total Pharmacies', 'pharmacies', 'bi-shop', 'green'], ['Total Medicines', 'medicines', 'bi-capsule', 'purple'], ['Total Reservations', 'reservations', 'bi-calendar-check', 'orange']] as [$label, $key, $icon, $tone])
      <div class="col-12"><a class="dashboard-stat admin-summary admin-tone-{{ $tone }}" href="/admin/view/{{ $key }}"><span class="admin-summary-icon"><i class="bi {{ $icon }}" aria-hidden="true"></i></span><span class="admin-summary-label">{{ $label }}</span><strong class="dashboard-stat-value">{{ number_format($stats[$key]) }}</strong></a></div>
    @endforeach
  @endif
@endsection
@section('dashboard_main')
  @if(in_array($mode, ['dashboard', 'reports']))
    @include('admin/overview')
  @endif
  @if($viewType)
    @include('admin/records')
    @if($viewType === 'pharmacies') @include('admin/approvals') @endif
  @elseif($mode === 'settings')
    @include('admin/settings')
  @elseif($mode === 'activity')
    <section class="admin-card"><div class="admin-card-heading"><h2>Recent Activity</h2><a href="/admin">Back to overview</a></div><p class="admin-muted">Latest recorded account, inventory and reservation updates.</p>@include('admin/activity')</section>
  @elseif($mode === 'reports')
    <section class="admin-card"><div class="admin-card-heading"><h2>Yearly Report</h2><a class="btn btn-sm btn-outline-primary" href="/admin/reports/export"><i class="bi bi-download me-1" aria-hidden="true"></i>Download CSV</a></div><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Month</th><th>New users</th><th>New pharmacies</th><th>Reservations</th></tr></thead><tbody>@foreach($chartData['year']['dates'] as $i => $month)<tr><td>{{ $month }}</td><td>{{ $chartData['year']['series']['users'][$i] }}</td><td>{{ $chartData['year']['series']['pharmacies'][$i] }}</td><td>{{ $chartData['year']['series']['reservations'][$i] }}</td></tr>@endforeach</tbody></table></div></section>
  @else
    <div class="admin-panels">
      <section class="admin-card"><div class="admin-card-heading"><h2>Recent Users</h2><a href="/admin/view/users">View all</a></div>
        @forelse($recentUsers as $user)
          <a href="/admin/view/users" class="admin-feed-row"><span class="admin-feed-icon admin-tone-blue"><i class="bi {{ in_array($user->role, ['pharmacist', 'pharmacy']) ? 'bi-shop' : 'bi-person' }}" aria-hidden="true"></i></span><span class="admin-feed-copy"><strong>{{ $user->name }}</strong><span>{{ ucfirst($user->role) }}</span><time>{{ $user->created_at ? \Illuminate\Support\Carbon::parse($user->created_at)->format('d M Y') : 'Join date unavailable' }}</time></span></a>
        @empty<p class="admin-empty">No registered users yet.</p>@endforelse
      </section>
      <section class="admin-card"><div class="admin-card-heading"><h2>Recent Activity</h2><a href="/admin/activity">View all</a></div>@include('admin/activity')</section>
    </div>
    @if($pendingCount > 0)
      <details class="admin-card admin-review"><summary>{{ $pendingCount }} pharmacies awaiting verification</summary><div class="mt-3">@include('admin/approvals')</div></details>
    @endif
    <details class="admin-health"><summary><span class="admin-health-icon"><i class="bi bi-shield-check" aria-hidden="true"></i></span><span><strong>System Health</strong><small>Database connected ? {{ $pendingCount + $pendingPayments }} items awaiting review</small></span><i class="bi bi-chevron-right ms-auto" aria-hidden="true"></i></summary><div class="admin-health-details"><p class="mb-2">Dashboard data loaded successfully from the database. {{ $approvedPharmacies }} pharmacies are approved.</p><a href="/admin/view/pharmacies">Review pharmacies ({{ $pendingCount }})</a><a href="/admin/subscriptions">Review payments ({{ $pendingPayments }})</a></div></details>
  @endif
@endsection
@section('dashboard_notifications')
  <a class="d-block p-2 text-decoration-none" href="/admin/view/pharmacies">{{ $pendingCount }} pharmacies awaiting verification</a>
  <a class="d-block p-2 text-decoration-none" href="/admin/subscriptions">{{ $pendingPayments }} subscription payments awaiting review</a>
@endsection
@section('scripts')
  @if(in_array($mode, ['dashboard', 'reports']))
    <script id="admin-chart-data" type="application/json">{!! json_encode($chartData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    <script src="{{ asset('js/admin-dashboard.js') }}" defer></script>
  @endif
@endsection
