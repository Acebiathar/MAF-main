@extends('layouts.dashboard')

@php
  $inventoryCount = $inventory->count();
  $lowStockCount = $inventory->where('quantity', '<=', 10)->count();
  $requestCount = $requestCollection->count();
  $pendingRequestCount = $requestCollection->where('status', 'pending')->count();
  $confirmedRequestCount = $requestCollection->where('status', 'confirmed')->count();
@endphp

@section('title', 'Pharmacy Dashboard | Medfinder')
@section('dashboard_search_placeholder', 'Search medicine names across the network')
@section('dashboard_notification_badge', (string) $pendingRequestCount)
@section('dashboard_title', $pharmacy->name . ' Dashboard')
@section('dashboard_subtitle', 'Manage inventory, watch incoming requests, and keep your pharmacy profile ready for patient demand.')
@section('dashboard_welcome_meta')
  <div class="d-flex flex-wrap justify-content-xl-end gap-2">
    <span class="badge {{ $isActive ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2 rounded-pill">
      {{ $isActive ? 'Approved and Visible' : 'Pending Approval' }}
    </span>
    <span class="badge bg-light text-primary px-3 py-2 rounded-pill">{{ $pharmacy->location }}</span>
  </div>
@endsection

@section('dashboard_sidebar')
  <a href="/pharmacist" class="nav-link active">
    <span class="dashboard-nav-main">
      <i class="bi bi-shop-window"></i>
      <span>
        <div class="fw-semibold">Pharmacy Home</div>
        <small>Inventory and performance overview</small>
      </span>
    </span>
  </a>
  <a href="/pharmacist/requests" class="nav-link">
    <span class="dashboard-nav-main">
      <i class="bi bi-inboxes"></i>
      <span>
        <div class="fw-semibold">Requests</div>
        <small>Review pending patient orders</small>
      </span>
    </span>
    <span class="badge text-bg-light">{{ $pendingRequestCount }}</span>
  </a>
  <a href="/" class="nav-link">
    <span class="dashboard-nav-main">
      <i class="bi bi-search"></i>
      <span>
        <div class="fw-semibold">Search Marketplace</div>
        <small>See what patients see</small>
      </span>
    </span>
  </a>
@endsection

@section('dashboard_stats')
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="small text-muted text-uppercase">Inventory Lines</div>
          <div class="dashboard-stat-value">{{ $inventoryCount }}</div>
        </div>
        <div class="dashboard-stat-icon"><i class="bi bi-capsule-pill"></i></div>
      </div>
      <div class="small text-muted">Active stock records in your dashboard.</div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="small text-muted text-uppercase">Pending Requests</div>
          <div class="dashboard-stat-value">{{ $pendingRequestCount }}</div>
        </div>
        <div class="dashboard-stat-icon"><i class="bi bi-bell"></i></div>
      </div>
      <div class="small text-muted">Orders that still need your response.</div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="small text-muted text-uppercase">Low Stock</div>
          <div class="dashboard-stat-value">{{ $lowStockCount }}</div>
        </div>
        <div class="dashboard-stat-icon"><i class="bi bi-exclamation-diamond"></i></div>
      </div>
      <div class="small text-muted">Items with quantity at or below 10 units.</div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xxl-3">
    <div class="dashboard-stat">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <div class="small text-muted text-uppercase">Confirmed Orders</div>
          <div class="dashboard-stat-value">{{ $confirmedRequestCount }}</div>
        </div>
        <div class="dashboard-stat-icon"><i class="bi bi-check2-circle"></i></div>
      </div>
      <div class="small text-muted">Requests you have already approved.</div>
    </div>
  </div>
@endsection

@section('dashboard_actions')
  <div class="col-12 col-md-6 col-xl-4">
    <div class="dashboard-action-card">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div class="dashboard-action-icon"><i class="bi bi-pencil-square"></i></div>
        <div>
          <h5 class="fw-bold mb-1">Update Inventory</h5>
          <p class="small text-muted mb-0">Add a new medicine line or refresh stock levels.</p>
        </div>
      </div>
      <a href="#stockForm" class="btn btn-primary px-4">Edit Stock</a>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-4">
    <div class="dashboard-action-card">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div class="dashboard-action-icon"><i class="bi bi-inboxes"></i></div>
        <div>
          <h5 class="fw-bold mb-1">Review Requests</h5>
          <p class="small text-muted mb-0">Process pending patient reservations quickly.</p>
        </div>
      </div>
      <a href="/pharmacist/requests" class="btn btn-outline-primary px-4">Open Requests</a>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-4">
    <div class="dashboard-action-card">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div class="dashboard-action-icon"><i class="bi bi-arrow-clockwise"></i></div>
        <div>
          <h5 class="fw-bold mb-1">Refresh Dashboard</h5>
          <p class="small text-muted mb-0">Reload your latest inventory and request signals.</p>
        </div>
      </div>
      <a href="/pharmacist" class="btn btn-outline-dark px-4">Refresh Now</a>
    </div>
  </div>
@endsection

@section('dashboard_main')
  @if(!$isActive)
  <div class="dashboard-notice mb-4">
    <div class="d-flex align-items-start gap-3">
      <div class="dashboard-action-icon flex-shrink-0"><i class="bi bi-shield-exclamation"></i></div>
      <div>
        <h5 class="fw-bold mb-2">Verification Pending</h5>
        <p class="text-muted mb-0">Your pharmacy is still under admin review. Patients will not see your inventory until approval is complete, and stock tools remain visible for preparation only.</p>
      </div>
    </div>
  </div>
  @endif

  <div class="dashboard-panel mb-4" id="stockForm">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <div class="text-uppercase small text-muted fw-semibold">Quick Actions</div>
        <h4 class="fw-bold mb-0">Add or Update Stock</h4>
      </div>
      <span class="badge bg-light text-primary px-3 py-2 rounded-pill">Phone: {{ $pharmacy->phone_number ?? 'N/A' }}</span>
    </div>

    @if($isActive)
    <form method="POST" action="/pharmacist/add">
      @csrf
      <div class="mb-3">
        <label for="medicine_name" class="form-label">Medicine Name</label>
        <input class="form-control" list="medicineOptions" name="medicine_name" id="medicine_name" placeholder="Type to search or add new..." required>
        <datalist id="medicineOptions">
          @foreach($all_medicines as $med)
          <option value="{{ $med->name }}">
          @endforeach
        </datalist>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Price (UGX)</label>
          <input type="number" name="price" class="form-control" placeholder="e.g. 5000" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Quantity</label>
          <input type="number" name="quantity" class="form-control" placeholder="e.g. 50" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Stock Status</label>
        <select name="stock_status" class="form-select">
          <option value="in_stock">Available (In Stock)</option>
          <option value="out_of_stock">Out of Stock</option>
          <option value="low_stock">Limited Stock</option>
        </select>
      </div>

      <button class="btn btn-primary rounded-pill px-4" type="submit">Save Stock Update</button>
    </form>
    @else
    <div class="text-center py-4">
      <i class="bi bi-lock fs-1 text-muted"></i>
      <h6 class="mt-3 fw-bold">Inventory Management Locked</h6>
      <p class="text-muted small mb-0">Please wait for admin approval before publishing or changing stock.</p>
    </div>
    @endif
  </div>

  <div class="dashboard-table-card p-3 p-lg-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
      <div>
        <div class="text-uppercase small text-muted fw-semibold">Requests Table</div>
        <h4 class="fw-bold mb-0">Live Inventory</h4>
      </div>
      <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">{{ $inventoryCount }} tracked stock lines</span>
    </div>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th>Medicine</th>
            <th>Price (UGX)</th>
            <th>Qty</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($inventory as $item)
          <tr>
            <td class="fw-bold">{{ $item->medicine_name }}</td>
            <td>{{ number_format($item->price) }}</td>
            <td>{{ $item->quantity }}</td>
            <td>
              <span class="badge {{ $item->stock_status === 'in_stock' ? 'bg-success' : ($item->stock_status === 'low_stock' ? 'bg-warning text-dark' : 'bg-danger') }} px-3 py-2 rounded-pill">
                {{ str_replace('_', ' ', $item->stock_status) }}
              </span>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="text-center text-muted py-5">No medicines in inventory yet.</td>
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
    <h5 class="fw-bold mb-3">Operations Feed</h5>
    <div class="dashboard-notice-list">
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Pending patient requests</h6>
        <p>{{ $pendingRequestCount }} request{{ $pendingRequestCount === 1 ? '' : 's' }} still need your action.</p>
      </div>
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Low stock watch</h6>
        <p>{{ $lowStockCount }} item{{ $lowStockCount === 1 ? '' : 's' }} should be restocked soon to avoid missed reservations.</p>
      </div>
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Profile visibility</h6>
        <p>{{ $isActive ? 'Your pharmacy is visible in patient search results.' : 'Approval is still pending, so patient search visibility is currently off.' }}</p>
      </div>
    </div>
  </div>

  <div class="dashboard-notice">
    <h5 class="fw-bold mb-3">Pharmacy snapshot</h5>
    <div class="dashboard-notice-list">
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Location</h6>
        <p>{{ $pharmacy->location }}</p>
      </div>
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Contact line</h6>
        <p>{{ $pharmacy->phone_number ?? 'No phone number on file' }}</p>
      </div>
      <div class="dashboard-notice-item">
        <h6 class="fw-semibold mb-2">Request throughput</h6>
        <p>{{ $requestCount }} total reservation{{ $requestCount === 1 ? '' : 's' }} with {{ $confirmedRequestCount }} already confirmed.</p>
      </div>
    </div>
  </div>
@endsection
