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
  <div class="badge bg-light text-primary px-3 py-2 rounded-pill">Reservations: {{ $reservationCount }} ? Searches: {{ $searchCount }}</div>
@endsection

@section('dashboard_sidebar')
  @include('partials.patient-sidebar')
@endsection

@section('dashboard_main')
  @if($section === 'home')
  <!-- PATIENT HOME SECTION -->
  <div id="section-home" class="section-content">
    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="dashboard-stat h-100">
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
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="dashboard-stat h-100">
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
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="dashboard-stat h-100">
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
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="dashboard-stat h-100">
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
    </div>
  </div>

    @include('partials.patient-activity')
  @endif
  @if($section === 'reservations')
  <!-- MY REQUESTS SECTION (CLEAN SINGLE INSTANCE) -->
  <div id="section-requests" class="section-content">
    <div class="dashboard-table-card p-3 p-lg-4">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
        <div>
          <div class="text-uppercase small text-muted fw-semibold">Reservations</div>
          <h4 class="fw-bold mb-0">Your Reservations</h4>
        </div>
        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">{{ $reservationCount }} requests</span>
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
                <div class="small text-muted"><i class="bi bi-geo-alt"></i> {{ $r->pharmacy_address }}</div>
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
  </div>

  @endif
  @if($section === 'notifications')
  <!-- NOTIFICATIONS SECTION -->
  <div id="section-notifications" class="section-content">
    <div class="dashboard-notice mb-4">
      <div class="text-uppercase small text-muted fw-semibold mb-2">Notifications and Updates</div>
      <h5 class="fw-bold mb-3">Request Status Alerts</h5>
      <div class="row g-3 mb-4">

  <!-- Card 1: Total Requests -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="card border-0 rounded-4 shadow-sm p-3 h-100">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
          <span class="text-uppercase text-muted fw-bold small" style="font-size: 0.75rem;">Total Requests</span>
          <h2 class="fw-bold my-1 text-dark">{{ $reservationCount ?? 0 }}</h2>
        </div>
        <span class="badge rounded-circle bg-primary-subtle text-primary p-2">
          <i class="bi bi-journal-check fs-5"></i>
        </span>
      </div>
      <p class="text-muted small mb-0">Everything you have submitted so far.</p>
    </div>
  </div>

  <!-- Card 2: Pending -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="card border-0 rounded-4 shadow-sm p-3 h-100">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
          <span class="text-uppercase text-muted fw-bold small" style="font-size: 0.75rem;">Pending</span>
          <h2 class="fw-bold my-1 text-dark">{{ $pendingCount ?? 0 }}</h2>
        </div>
        <span class="badge rounded-circle bg-primary-subtle text-primary p-2">
          <i class="bi bi-clock-history fs-5"></i>
        </span>
      </div>
      <p class="text-muted small mb-0">Awaiting pharmacist review.</p>
    </div>
  </div>

  <!-- Card 3: Ready for Pickup -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="card border-0 rounded-4 shadow-sm p-3 h-100">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
          <span class="text-uppercase text-muted fw-bold small" style="font-size: 0.75rem;">Ready For Pickup</span>
          <h2 class="fw-bold my-1 text-dark">{{ $confirmedCount ?? 0 }}</h2>
        </div>
        <span class="badge rounded-circle bg-primary-subtle text-primary p-2">
          <i class="bi bi-bag-check fs-5"></i>
        </span>
      </div>
      <p class="text-muted small mb-0">Confirmed and ready to collect.</p>
    </div>
  </div>

  <!-- Card 4: Unavailable -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="card border-0 rounded-4 shadow-sm p-3 h-100">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
          <span class="text-uppercase text-muted fw-bold small" style="font-size: 0.75rem;">Unavailable</span>
          <h2 class="fw-bold my-1 text-dark">{{ $declinedCount ?? 0 }}</h2>
        </div>
        <span class="badge rounded-circle bg-primary-subtle text-primary p-2">
          <i class="bi bi-x-circle fs-5"></i>
        </span>
      </div>
      <p class="text-muted small mb-0">Requests that need a fresh search.</p>
    </div>
  </div>

</div>

    <div class="dashboard-notice">
      <h5 class="fw-bold mb-3">Quick Tips</h5>
      <div class="dashboard-notice-list">
        <div class="dashboard-notice-item">
          <h6 class="fw-semibold mb-2">Add prescription details</h6>
          <p>Include dosage and specifics when requesting - helps pharmacists respond faster.</p>
        </div>
        <div class="dashboard-notice-item">
          <h6 class="fw-semibold mb-2">Bring your ID</h6>
          <p>Have identification ready when picking up confirmed requests.</p>
        </div>
        <div class="dashboard-notice-item">
          <h6 class="fw-semibold mb-2">Search nearby pharmacies</h6>
          <p>If a request is unavailable, use the search to find alternatives quickly.</p>
        </div>
      </div>
    </div>
  </div>
  @endif
  @if($section === 'history')
    @include('partials.patient-search-history')
  @endif
@endsection

@section('dashboard_notifications')
  <a class="d-block text-decoration-none p-2" href="/requests?section=notifications">View request updates <span class="badge text-bg-primary ms-1">{{ $pendingCount }}</span></a>
@endsection

@section('styles')
<style>
  /* Sidebar notification styling */
  .dashboard-notice-list .dashboard-notice-item {
    border-radius: 10px;
    padding: 0.75rem;
  }

  .dashboard-notice-list .dashboard-notice-item h6 {
    color: inherit;
    margin-bottom: 0.5rem;
  }

  .dashboard-notice-list .dashboard-notice-item p {
    margin-bottom: 0;
    font-size: 0.8rem;
  }

  /* Stats Cards Alignment */
  .dashboard-stat {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .row.g-3 > [class*='col'] {
    display: flex;
  }

  .row.g-3 > [class*='col'] .dashboard-stat {
    width: 100%;
  }

  /* Responsive grid alignment */
  @media (max-width: 575.98px) {
    .row.g-3 {
      row-gap: 1rem;
    }
  }

  @media (min-width: 576px) and (max-width: 991.98px) {
    .row.g-3 {
      row-gap: 1rem;
    }
  }

  /* Active link styling */
  .dashboard-sidebar .nav-link.active {
    background-color: rgba(255, 255, 255, 0.12);
    color: #ffffff;
    border-color: rgba(255, 255, 255, 0.12);
  }

  .dashboard-sidebar .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.08);
  }

  /* Section animation */
  .section-content {
    animation: fadeIn 0.3s ease-in-out;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>

@endsection
