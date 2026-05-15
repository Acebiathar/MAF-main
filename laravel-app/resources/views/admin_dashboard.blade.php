@extends('layouts.dashboard')

@php
  $pendingCount = count($pending);
  $selectedViewLabel = $viewType ? ucfirst($viewType) : 'Overview';
@endphp

@section('title', 'Admin Dashboard | Medfinder')
@section('dashboard_search_placeholder', 'Search medicines, users, or pharmacies')
@section('dashboard_notification_badge', (string) $pendingCount)
@section('dashboard_title', 'Admin Control Center')
@section('dashboard_subtitle', 'Monitor growth, review pharmacy applications, and keep the marketplace healthy from one unified operations desk.')
@section('dashboard_welcome_meta')
  <div class="d-flex flex-wrap justify-content-xl-end gap-2">
    <span class="badge bg-dark px-3 py-2 rounded-pill">System Administrator</span>
    <span class="badge bg-light text-primary px-3 py-2 rounded-pill">Pending reviews: {{ $pendingCount }}</span>
  </div>
@endsection

@section('dashboard_sidebar')
  <a href="/admin" class="nav-link {{ Request::is('admin') ? 'active' : '' }}">
    <span class="dashboard-nav-main">
      <i class="bi bi-speedometer2"></i>
      <span>
        <div class="fw-semibold">Admin Home</div>
        <small>Oversight and platform health</small>
      </span>
    </span>
  </a>
  <a href="/admin/view/users" class="nav-link {{ Request::is('admin/view/users') ? 'active' : '' }}">
    <span class="dashboard-nav-main">
      <i class="bi bi-people"></i>
      <span>
        <div class="fw-semibold">Users</div>
        <small>Inspect member growth</small>
      </span>
    </span>
    <span class="badge text-bg-light">{{ $stats['users'] ?? 0 }}</span>
  </a>
  <a href="/admin/view/pharmacies" class="nav-link {{ Request::is('admin/view/pharmacies') ? 'active' : '' }}">
    <span class="dashboard-nav-main">
      <i class="bi bi-shop"></i>
      <span>
        <div class="fw-semibold">Pharmacies</div>
        <small>Track verified providers</small>
      </span>
    </span>
    <span class="badge text-bg-light">{{ $stats['pharmacies'] ?? 0 }}</span>
  </a>
  <a href="/admin/view/reservations" class="nav-link {{ Request::is('admin/view/reservations') ? 'active' : '' }}">
    <span class="dashboard-nav-main">
      <i class="bi bi-clipboard-data"></i>
      <span>
        <div class="fw-semibold">Reservations</div>
        <small>Review transaction flow</small>
      </span>
    </span>
    <span class="badge text-bg-light">{{ $stats['reservations'] ?? 0 }}</span>
  </a>
@endsection

@section('dashboard_stats')
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="small text-muted text-uppercase">Total Users</div>
          <div class="dashboard-stat-value">{{ number_format($stats['users'] ?? 0) }}</div>
        </div>
        <div class="dashboard-stat-icon"><i class="bi bi-people-fill"></i></div>
      </div>
      <div class="small text-muted">All registered accounts in the platform.</div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="small text-muted text-uppercase">Active Pharmacies</div>
          <div class="dashboard-stat-value">{{ number_format($stats['pharmacies'] ?? 0) }}</div>
        </div>
        <div class="dashboard-stat-icon"><i class="bi bi-hospital"></i></div>
      </div>
      <div class="small text-muted">Approved providers currently visible to patients.</div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="small text-muted text-uppercase">Medicines Catalog</div>
          <div class="dashboard-stat-value">{{ number_format($stats['medicines'] ?? 0) }}</div>
        </div>
        <div class="dashboard-stat-icon"><i class="bi bi-capsule"></i></div>
      </div>
      <div class="small text-muted">Catalog entries across the marketplace.</div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="small text-muted text-uppercase">Reservations</div>
          <div class="dashboard-stat-value">{{ number_format($stats['reservations'] ?? 0) }}</div>
        </div>
        <div class="dashboard-stat-icon"><i class="bi bi-journal-text"></i></div>
      </div>
      <div class="small text-muted">Total medicine requests flowing through the system.</div>
    </div>
  </div>
@endsection

@section('dashboard_actions')
  <div class="col-12 col-md-6 col-xl-4">
    <div class="dashboard-action-card">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div class="dashboard-action-icon"><i class="bi bi-person-lines-fill"></i></div>
        <div>
          <h5 class="fw-bold mb-1">Review Users</h5>
          <p class="small text-muted mb-0">Open the full user list for quick inspection.</p>
        </div>
      </div>
      <a href="/admin/view/users" class="btn btn-primary px-4">Open Users</a>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-4">
    <div class="dashboard-action-card">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div class="dashboard-action-icon"><i class="bi bi-patch-check"></i></div>
        <div>
          <h5 class="fw-bold mb-1">Moderate Pharmacies</h5>
          <p class="small text-muted mb-0">Jump straight to provider verification.</p>
        </div>
      </div>
      <a href="#pendingApprovals" class="btn btn-outline-primary px-4">Open Queue</a>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-4">
    <div class="dashboard-action-card">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div class="dashboard-action-icon"><i class="bi bi-arrow-repeat"></i></div>
        <div>
          <h5 class="fw-bold mb-1">Refresh Overview</h5>
          <p class="small text-muted mb-0">Reload the latest counts and review state.</p>
        </div>
      </div>
      <a href="/admin" class="btn btn-outline-dark px-4">Refresh Dashboard</a>
    </div>
  </div>
@endsection

@section('dashboard_main')
  @if(isset($viewList) && $viewList !== null)
  <div class="dashboard-table-card p-3 p-lg-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
      <div>
        <div class="text-uppercase small text-muted fw-semibold">Detailed View</div>
        <h4 class="fw-bold mb-0">{{ $selectedViewLabel }}</h4>
      </div>
      <a href="/admin" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Close List</a>
    </div>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr class="small text-uppercase text-muted">
            @if($viewType == 'users')
            <th>Name</th><th>Email</th><th>Role</th><th>Joined</th>
            @elseif($viewType == 'pharmacies')
            <th>Name</th><th>Location</th><th>License</th><th>Contact</th>
            @elseif($viewType == 'medicines')
            <th>Medicine Name</th><th>Added On</th>
            @elseif($viewType == 'reservations')
            <th>Patient</th><th>Status</th><th>Date</th>
            @endif
          </tr>
        </thead>
        <tbody>
          @foreach ($viewList as $item)
          <tr>
            @if($viewType == 'users')
            <td class="fw-bold">{{ $item->name }}</td>
            <td>{{ $item->email }}</td>
            <td><span class="badge bg-light text-dark border">{{ $item->role }}</span></td>
            <td class="text-muted small">{{ date('M d, Y', strtotime($item->created_at ?? now())) }}</td>
            @elseif($viewType == 'pharmacies')
            <td class="fw-bold">{{ $item->name }}</td>
            <td>{{ $item->location }}</td>
            <td><code>{{ $item->license_number }}</code></td>
            <td>{{ $item->phone_number ?? 'N/A' }}</td>
            @elseif($viewType == 'medicines')
            <td class="fw-bold">{{ $item->name }}</td>
            <td class="text-muted small">{{ date('M d, Y', strtotime($item->created_at ?? now())) }}</td>
            @elseif($viewType == 'reservations')
            <td class="fw-bold">{{ $item->patient_name ?? 'User #' . $item->user_id }}</td>
            <td><span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">{{ $item->status }}</span></td>
            <td class="text-muted small">{{ date('M d, Y', strtotime($item->created_at ?? now())) }}</td>
            @endif
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif

  <div class="dashboard-table-card p-3 p-lg-4" id="pendingApprovals">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
      <div>
        <div class="text-uppercase small text-muted fw-semibold">Requests Table</div>
        <h4 class="fw-bold mb-0">Pending Pharmacy Verification</h4>
      </div>
      <span class="badge bg-warning-subtle text-dark px-3 py-2 rounded-pill">{{ $pendingCount }} pending review</span>
    </div>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr class="small text-uppercase text-muted">
            <th>Pharmacy Details</th>
            <th>Location</th>
            <th>NDA License No.</th>
            <th>Contact</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($pending as $p)
          <tr>
            <td>
              <div class="fw-bold text-dark">{{ $p->name }}</div>
              <div class="small text-muted">ID: #{{ $p->id }}</div>
            </td>
            <td>{{ $p->location }}</td>
            <td><code class="text-primary fw-bold">{{ $p->license_number }}</code></td>
            <td>{{ $p->phone_number ?? 'N/A' }}</td>
            <td class="text-end">
              <div class="d-flex gap-2 justify-content-end">
                <a href="/admin/pharmacies/{{ $p->id }}/approve" class="btn btn-sm btn-success rounded-pill px-3 fw-bold" onclick="return confirm('Approve this pharmacy?')">Approve</a>
                <a href="/admin/pharmacies/{{ $p->id }}/reject" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Reject this application?')">Reject</a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center py-5 text-muted">All pharmacy applications have been processed.</td>
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
    <h5 class="fw-bold mb-3">Admin Alerts</h5>
    <div class="dashboard-notice-list">
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Verification queue</h6>
        <p>{{ $pendingCount }} pharmacy application{{ $pendingCount === 1 ? '' : 's' }} are waiting for review.</p>
      </div>
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Current focus</h6>
        <p>You are currently viewing the {{ strtolower($selectedViewLabel) }} section.</p>
      </div>
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Marketplace activity</h6>
        <p>{{ number_format($stats['reservations'] ?? 0) }} reservations have passed through the platform so far.</p>
      </div>
    </div>
  </div>

  <div class="dashboard-notice">
    <h5 class="fw-bold mb-3">Decision guide</h5>
    <div class="dashboard-notice-list">
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Approve when verified</h6>
        <p>Use the NDA license and contact details to confirm legitimacy before activating visibility.</p>
      </div>
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Reject clearly</h6>
        <p>Reject incomplete or invalid applications so the queue stays clean for legitimate providers.</p>
      </div>
    </div>
  </div>
@endsection
