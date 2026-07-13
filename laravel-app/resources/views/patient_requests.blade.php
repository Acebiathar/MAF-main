@extends('layouts.dashboard')

@php
  $requestCount = $reservations->count();
  $pendingCount = $reservations->where('status', 'pending')->count();
  $confirmedCount = $reservations->where('status', 'confirmed')->count();
  $declinedCount = $reservations->where('status', 'declined')->count();
@endphp

@section('title', 'My Reservations | Medfinder')
@section('dashboard_search_placeholder', 'Search my reservations...')
@section('dashboard_search_action', url('/requests'))
@section('dashboard_notification_badge', (string) $pendingCount)
@section('dashboard_title', 'My Medicine Reservations')
@section('dashboard_subtitle', 'Track your pending reservations and pickup status across local pharmacies.')

@section('dashboard_welcome_meta')
  <a href="/" class="btn btn-light text-primary rounded-pill px-4 fw-semibold"><i class="bi bi-search me-2"></i>Find Medicine</a>
@endsection

@section('dashboard_sidebar')
<div class="nav flex-column w-100 gap-2">
  <a href="/" class="nav-link">
    <span class="dashboard-nav-main">
      <i class="bi bi-search"></i>
      <span>
        <div class="fw-semibold">Marketplace</div>
        <small>Search medicines</small>
      </span>
    </span>
  </a>
  <a href="/requests" class="nav-link active">
    <span class="dashboard-nav-main">
      <i class="bi bi-journals"></i>
      <span>
        <div class="fw-semibold">My Reservations</div>
        <small>Track current status</small>
      </span>
    </span>
    <span class="badge bg-primary text-white ms-auto">{{ $pendingCount }}</span>
  </a>
  <!-- Search History Card -->
  <div class="dashboard-notice mb-4 p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <span class="text-uppercase small text-muted fw-semibold">Search History</span>
      @if(count($recentSearches ?? []) > 0)
        <button onclick="clearSearchHistory()" class="clear-history-btn btn btn-link p-0 text-secondary" style="font-size: 0.75rem;">Clear</button>
      @endif
    </div>

    <ul id="sidebar-history-list" class="list-unstyled mb-0">
      @forelse($recentSearches ?? [] as $search)
        <li class="mb-2">
          <a href="{{ url('/requests?search=' . rawurlencode($search->query)) }}" onclick="submitSearchHistory(event, {{ json_encode($search->query) }})" class="d-flex align-items-center text-secondary text-decoration-none small" style="gap: 0.5rem;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #5c728a;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span class="text-truncate">{{ $search->query }}</span>
          </a>
        </li>
      @empty
        <li class="small text-muted fst-italic">No recent searches</li>
      @endforelse
    </ul>
  </div>

<!-- AJAX Script to Clear History -->
<script>
function clearSearchHistory() {
    fetch("{{ route('search.history.clear') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            document.getElementById('sidebar-history-list').innerHTML = '<li style="font-size: 12px; color: #5c728a; font-style: italic;">No recent searches</li>';
            document.querySelector('.clear-history-btn').remove();
        }
    });
}

function submitSearchHistory(event, query) {
    event.preventDefault();
    const form = document.getElementById('dashboard-search-form');
    const input = document.getElementById('dashboard-search-input');
    if (!form || !input) {
        return;
    }
    input.value = query;
    form.submit();
}
</script>
</div>
@endsection

@section('dashboard_stats')
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div><div class="small text-muted text-uppercase">All Reservations</div><div class="dashboard-stat-value">{{ $requestCount }}</div></div>
        <div class="dashboard-stat-icon"><i class="bi bi-journals"></i></div>
      </div>
      <div class="small text-muted">Total history.</div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div><div class="small text-muted text-uppercase">Pending</div><div class="dashboard-stat-value">{{ $pendingCount }}</div></div>
        <div class="dashboard-stat-icon"><i class="bi bi-hourglass-split"></i></div>
      </div>
      <div class="small text-muted">Awaiting pharmacy review.</div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div><div class="small text-muted text-uppercase">Confirmed</div><div class="dashboard-stat-value">{{ $confirmedCount }}</div></div>
        <div class="dashboard-stat-icon"><i class="bi bi-check2-square"></i></div>
      </div>
      <div class="small text-muted">Ready for pickup.</div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div><div class="small text-muted text-uppercase">Declined</div><div class="dashboard-stat-value">{{ $declinedCount }}</div></div>
        <div class="dashboard-stat-icon"><i class="bi bi-slash-circle"></i></div>
      </div>
      <div class="small text-muted">Closed requests.</div>
    </div>
  </div>
@endsection

@section('dashboard_main')
  <div class="dashboard-table-card p-3 p-lg-4" id="requestsTable">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
      <div>
        <div class="text-uppercase small text-muted fw-semibold">Reservation History</div>
        <h4 class="fw-bold mb-0">Recent Activity</h4>
      </div>
      <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">{{ $pendingCount }} pending now</span>
    </div>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr class="small text-uppercase text-muted">
            <th>Medicine</th>
            <th>Pharmacy</th>
            <th>Status</th>
            <th>Note</th>
            <th>Placed On</th>
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($reservations as $r)
          <tr>
            <td><div class="fw-bold text-primary">{{ $r->medicine_name }}</div></td>
            <td>
              <div class="fw-semibold text-dark">{{ $r->pharmacy_name }}</div>
              <div class="small text-muted"><i class="bi bi-geo-alt-fill me-1"></i>{{ $r->pharmacy_address ?? 'No address provided' }}</div>
            </td>
            <td>
              @if ($r->status === 'confirmed')
                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Confirmed</span>
              @elseif ($r->status === 'declined')
                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">Declined</span>
              @else
                <span class="badge bg-warning-subtle text-dark px-3 py-2 rounded-pill">Pending</span>
              @endif
            </td>
            <td class="small text-wrap" style="max-width: 200px;">{{ $r->note ?? '-' }}</td>
            <td class="small text-muted">{{ date('M d, H:i', strtotime($r->created_at)) }}</td>
            <td class="text-end">
              @if($r->status === 'pending')
                <span class="text-muted small">Awaiting review</span>
              @else
                <span class="text-muted small">Closed</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-5 text-muted">No medicine reservations have been placed yet.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="dashboard-notice mt-4">
    <div class="text-uppercase small text-muted fw-semibold mb-2">Tips</div>
    <h5 class="fw-bold mb-3">Reservation Guide</h5>
    <div class="dashboard-notice-list">
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Check status often</h6>
        <p>Pharmacies usually review requests within a few hours during business days.</p>
      </div>
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Pickup requirements</h6>
        <p>Bring a valid ID and your reservation details when visiting the pharmacy.</p>
      </div>
    </div>
  </div>
@endsection