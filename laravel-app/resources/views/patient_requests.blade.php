@extends('layouts.dashboard')

@php
  $reservationCount = $reservations->count();
  $pendingCount = $reservations->where('status', 'pending')->count();
  $confirmedCount = $reservations->where('status', 'confirmed')->count();
  $declinedCount = $reservations->where('status', 'declined')->count();
@endphp

@section('title', 'Patient Dashboard | Medfinder')
@section('dashboard_search_placeholder', 'Search a medicine or pharmacy')
@section('dashboard_notification_badge', (string) $pendingCount)
@section('dashboard_title', 'Patient Dashboard')
@section('dashboard_subtitle', 'Track your medicine requests, check fulfillment progress, and jump back into search without leaving your workspace.')
@section('dashboard_welcome_meta')
  <div class="badge bg-light text-primary px-3 py-2 rounded-pill">Requests this week: {{ $reservationCount }}</div>
@endsection

@section('dashboard_sidebar')
  <a href="/requests" class="nav-link active">
    <span class="dashboard-nav-main">
      <i class="bi bi-speedometer2"></i>
      <span>
        <div class="fw-semibold">Patient Home</div>
        <small>Overview and request history</small>
      </span>
    </span>
    <span class="badge text-bg-light">{{ $reservationCount }}</span>
  </a>
  <a href="/" class="nav-link">
    <span class="dashboard-nav-main">
      <i class="bi bi-search"></i>
      <span>
        <div class="fw-semibold">Search Medicine</div>
        <small>Find availability near you</small>
      </span>
    </span>
  </a>
  <a href="/how-it-works" class="nav-link">
    <span class="dashboard-nav-main">
      <i class="bi bi-compass"></i>
      <span>
        <div class="fw-semibold">How It Works</div>
        <small>Learn the request process</small>
      </span>
    </span>
  </a>
  <a href="/contact" class="nav-link">
    <span class="dashboard-nav-main">
      <i class="bi bi-headset"></i>
      <span>
        <div class="fw-semibold">Support</div>
        <small>Reach the Medfinder team</small>
      </span>
    </span>
  </a>
@endsection

@section('dashboard_stats')
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="small text-muted text-uppercase">Total Requests</div>
          <div class="dashboard-stat-value">{{ $reservationCount }}</div>
        </div>
        <div class="dashboard-stat-icon"><i class="bi bi-journal-check"></i></div>
      </div>
      <div class="small text-muted">Everything you have submitted so far.</div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="small text-muted text-uppercase">Pending</div>
          <div class="dashboard-stat-value">{{ $pendingCount }}</div>
        </div>
        <div class="dashboard-stat-icon"><i class="bi bi-clock-history"></i></div>
      </div>
      <div class="small text-muted">Awaiting pharmacist review.</div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="small text-muted text-uppercase">Ready for Pickup</div>
          <div class="dashboard-stat-value">{{ $confirmedCount }}</div>
        </div>
        <div class="dashboard-stat-icon"><i class="bi bi-bag-check"></i></div>
      </div>
      <div class="small text-muted">Confirmed and ready to collect.</div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="small text-muted text-uppercase">Unavailable</div>
          <div class="dashboard-stat-value">{{ $declinedCount }}</div>
        </div>
        <div class="dashboard-stat-icon"><i class="bi bi-x-octagon"></i></div>
      </div>
      <div class="small text-muted">Requests that need a fresh search.</div>
    </div>
  </div>
@endsection



@section('dashboard_main')
  <div class="dashboard-table-card p-3 p-lg-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
      <div>
        <div class="text-uppercase small text-muted fw-semibold">Requests Table</div>
        <h4 class="fw-bold mb-0">My Medicine Requests</h4>
      </div>
      <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">{{ $reservationCount }} tracked requests</span>
    </div>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr class="small text-uppercase text-muted">
            <th>Medicine</th>
            <th>Pharmacy and Location</th>
            <th>Status</th>
            <th>Note Sent</th>
            <th>Date Placed</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($reservations as $r)
          <tr>
            <td>
              <div class="fw-bold text-primary">{{ $r->medicine_name }}</div>
            </td>
            <td>
              <div class="fw-semibold text-dark">{{ $r->pharmacy_name }}</div>
              <div class="small text-muted"><i class="bi bi-geo-alt"></i> {{ $r->pharmacy_location }}</div>
            </td>
            <td>
              @if ($r->status === 'confirmed')
              <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                <i class="bi bi-check-circle-fill me-1"></i> Ready for Pick-up
              </span>
              @elseif ($r->status === 'declined')
              <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                <i class="bi bi-x-circle-fill me-1"></i> Unavailable
              </span>
              @else
              <span class="badge bg-warning-subtle text-dark px-3 py-2 rounded-pill">
                <i class="bi bi-clock-history me-1"></i> Pending Review
              </span>
              @endif
            </td>
            <td class="small text-muted">{{ $r->note ?? '-' }}</td>
            <td class="small">{{ date('D, M d Y', strtotime($r->created_at)) }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center py-5">
              <div class="text-muted mb-3">You have not placed any medicine requests yet.</div>
              <a href="/" class="btn btn-outline-primary rounded-pill px-4">Search for Medicine</a>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection

@section('dashboard_notifications')
  <div class="dashboard-notice mb-4">
    <div class="text-uppercase small text-muted fw-semibold mb-2">Notifications and Updates Panel</div>
    <h5 class="fw-bold mb-3">Request Alerts</h5>
    <div class="dashboard-notice-list">
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Pending reviews</h6>
        <p>{{ $pendingCount }} request{{ $pendingCount === 1 ? '' : 's' }} still waiting on pharmacy confirmation.</p>
      </div>
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Pickup reminders</h6>
        <p>{{ $confirmedCount }} request{{ $confirmedCount === 1 ? '' : 's' }} are confirmed. Visit the pharmacy within 24 hours when possible.</p>
      </div>
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Smart next step</h6>
        <p>If a request is unavailable, run a fresh search to compare other nearby pharmacies quickly.</p>
      </div>
    </div>
  </div>

  <div class="dashboard-notice">
    <h5 class="fw-bold mb-3">Quick patient tips</h5>
    <div class="dashboard-notice-list">
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Add notes when needed</h6>
        <p>Prescription details and dosage notes help pharmacies respond with fewer delays.</p>
      </div>
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Bring verification</h6>
        <p>Carry your ID and prescription for confirmed requests that require regulated dispensing.</p>
      </div>
    </div>
  </div>
@endsection
