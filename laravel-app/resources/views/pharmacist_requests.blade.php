@extends('layouts.dashboard')

@php
  $requestCount = $reservations->count();
  $pendingCount = $reservations->where('status', 'pending')->count();
  $confirmedCount = $reservations->where('status', 'confirmed')->count();
  $declinedCount = $reservations->where('status', 'declined')->count();
@endphp

@section('title', 'Pharmacy Requests | Medfinder')
@section('dashboard_search_placeholder', 'Search medicines or request context')
@section('dashboard_notification_badge', (string) $pendingCount)
@section('dashboard_title', 'Requests for ' . $pharmacy->name)
@section('dashboard_subtitle', 'Review incoming reservations, respond fast to patient needs, and keep fulfillment moving smoothly.')
@section('dashboard_welcome_meta')
  <a href="/pharmacist" class="btn btn-light text-primary rounded-pill px-4 fw-semibold">Back to Inventory</a>
@endsection

@section('dashboard_sidebar')
  <a href="/pharmacist" class="nav-link">
    <span class="dashboard-nav-main">
      <i class="bi bi-shop-window"></i>
      <span>
        <div class="fw-semibold">Pharmacy Home</div>
        <small>Inventory and summary</small>
      </span>
    </span>
  </a>
  <a href="/pharmacist/requests" class="nav-link active">
    <span class="dashboard-nav-main">
      <i class="bi bi-inboxes"></i>
      <span>
        <div class="fw-semibold">Request Queue</div>
        <small>Respond to patients</small>
      </span>
    </span>
    <span class="badge text-bg-light">{{ $pendingCount }}</span>
  </a>
  <a href="/" class="nav-link">
    <span class="dashboard-nav-main">
      <i class="bi bi-search"></i>
      <span>
        <div class="fw-semibold">Marketplace Search</div>
        <small>Check patient-facing availability</small>
      </span>
    </span>
  </a>
@endsection

@section('dashboard_stats')
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="small text-muted text-uppercase">All Requests</div>
          <div class="dashboard-stat-value">{{ $requestCount }}</div>
        </div>
        <div class="dashboard-stat-icon"><i class="bi bi-journals"></i></div>
      </div>
      <div class="small text-muted">Total reservation activity for this pharmacy.</div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="small text-muted text-uppercase">Pending</div>
          <div class="dashboard-stat-value">{{ $pendingCount }}</div>
        </div>
        <div class="dashboard-stat-icon"><i class="bi bi-hourglass-split"></i></div>
      </div>
      <div class="small text-muted">Still waiting for your decision.</div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="small text-muted text-uppercase">Confirmed</div>
          <div class="dashboard-stat-value">{{ $confirmedCount }}</div>
        </div>
        <div class="dashboard-stat-icon"><i class="bi bi-check2-square"></i></div>
      </div>
      <div class="small text-muted">Requests approved for pickup.</div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="small text-muted text-uppercase">Declined</div>
          <div class="dashboard-stat-value">{{ $declinedCount }}</div>
        </div>
        <div class="dashboard-stat-icon"><i class="bi bi-slash-circle"></i></div>
      </div>
      <div class="small text-muted">Requests closed without fulfillment.</div>
    </div>
  </div>
@endsection

@section('dashboard_actions')
  <div class="col-12 col-md-6 col-xl-4">
    <div class="dashboard-action-card">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div class="dashboard-action-icon"><i class="bi bi-lightning-charge"></i></div>
        <div>
          <h5 class="fw-bold mb-1">Process Queue</h5>
          <p class="small text-muted mb-0">Start with pending requests to improve response time.</p>
        </div>
      </div>
      <a href="#requestsTable" class="btn btn-primary px-4">Open Queue</a>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-4">
    <div class="dashboard-action-card">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div class="dashboard-action-icon"><i class="bi bi-box-seam"></i></div>
        <div>
          <h5 class="fw-bold mb-1">Check Inventory</h5>
          <p class="small text-muted mb-0">Return to stock controls and medicine listings.</p>
        </div>
      </div>
      <a href="/pharmacist" class="btn btn-outline-primary px-4">Inventory</a>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-4">
    <div class="dashboard-action-card">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div class="dashboard-action-icon"><i class="bi bi-arrow-clockwise"></i></div>
        <div>
          <h5 class="fw-bold mb-1">Refresh Requests</h5>
          <p class="small text-muted mb-0">Reload the latest reservation decisions.</p>
        </div>
      </div>
      <a href="/pharmacist/requests" class="btn btn-outline-dark px-4">Refresh</a>
    </div>
  </div>
@endsection

@section('dashboard_main')
  <div class="dashboard-table-card p-3 p-lg-4" id="requestsTable">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
      <div>
        <div class="text-uppercase small text-muted fw-semibold">Requests Table</div>
        <h4 class="fw-bold mb-0">Patient Reservation Queue</h4>
      </div>
      <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">{{ $pendingCount }} pending now</span>
    </div>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr class="small text-uppercase text-muted">
            <th>Medicine</th>
            <th>Patient Details</th>
            <th>Status</th>
            <th>Note</th>
            <th>Placed On</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($reservations as $r)
          <tr>
            <td><div class="fw-bold text-primary">{{ $r->medicine_name }}</div></td>
            <td>
              <div class="fw-semibold">{{ $r->user_name }}</div>
              <div class="small text-muted">{{ $r->user_email }}</div>
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
              <div class="d-flex gap-2 justify-content-end">
                <form method="POST" action="/pharmacist/requests/{{ $r->id }}/confirm">
                  @csrf
                  <button class="btn btn-sm btn-success rounded-pill px-3 shadow-sm" type="submit">Confirm</button>
                </form>
                <form method="POST" action="/pharmacist/requests/{{ $r->id }}/decline">
                  @csrf
                  <button class="btn btn-sm btn-outline-danger rounded-pill px-3" type="submit">Decline</button>
                </form>
              </div>
              @else
              <span class="text-muted small">Processed</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center py-5 text-muted">No medicine requests have been placed yet.</td>
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
    <h5 class="fw-bold mb-3">Queue Highlights</h5>
    <div class="dashboard-notice-list">
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Pending action</h6>
        <p>{{ $pendingCount }} request{{ $pendingCount === 1 ? '' : 's' }} need a confirm or decline decision.</p>
      </div>
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Patient readiness</h6>
        <p>{{ $confirmedCount }} request{{ $confirmedCount === 1 ? '' : 's' }} are ready for pickup coordination.</p>
      </div>
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Communication quality</h6>
        <p>Check patient notes before approving to reduce back-and-forth on collection details.</p>
      </div>
    </div>
  </div>

  <div class="dashboard-notice">
    <h5 class="fw-bold mb-3">Operating tips</h5>
    <div class="dashboard-notice-list">
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Confirm quickly</h6>
        <p>Fast responses improve trust and help patients collect medicine before stock changes.</p>
      </div>
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Decline clearly</h6>
        <p>If you cannot fulfill a request, decline it promptly so the patient can search elsewhere.</p>
      </div>
    </div>
  </div>
@endsection
