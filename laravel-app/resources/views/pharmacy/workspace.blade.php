@extends('layouts.dashboard')
@php
  $titles = ['dashboard' => 'Welcome, '.$pharmacy->name, 'medicines' => 'Manage Medicines', 'inventory' => 'Stock & Inventory', 'prices' => 'Medicine Prices', 'subscription' => 'Subscription', 'profile' => 'Pharmacy Profile', 'settings' => 'Account Settings'];
  $lowStock = $inventory->where('quantity', '>', 0)->where('quantity', '<=', 10)->count();
@endphp
@section('title', $titles[$section].' | Medfinder')
@section('dashboard_title', $titles[$section])
@section('dashboard_subtitle', 'Keep your stock updated and serve your customers better.')
@section('dashboard_notification_badge', (string) ($pendingCount + $lowStock))
@section('dashboard_stats')
  @if($section === 'dashboard')
    @foreach([['Total Medicines', $inventory->count(), 'bi-capsule'], ['In Stock', $inventory->where('quantity', '>', 10)->count(), 'bi-box-seam'], ['Low Stock', $lowStock, 'bi-exclamation-triangle'], ['Out of Stock', $inventory->where('quantity', 0)->count(), 'bi-x-circle']] as [$label, $value, $icon])
      <div class="col-12"><a href="/pharmacist/inventory" class="dashboard-stat d-block text-decoration-none"><div class="dashboard-stat-icon"><i class="bi {{ $icon }}"></i></div><div class="small text-muted mt-3">{{ $label }}</div><div class="dashboard-stat-value">{{ $value }}</div></a></div>
    @endforeach
  @endif
@endsection
@section('dashboard_main')
  @if(!$isActive)
    <div class="alert alert-warning">Your pharmacy is awaiting approval. Stock changes and reservation decisions become available after approval.</div>
  @endif

  @if($section === 'dashboard')
    <div class="dashboard-panel mb-4">
      <div class="d-flex justify-content-between mb-3"><h5 class="fw-bold">Recent Reservations</h5><a href="/pharmacist/requests">View all</a></div>
      <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Patient</th><th>Medicine</th><th>Status</th><th>Date</th></tr></thead><tbody>
        @forelse($reservations->take(5) as $reservation)
          <tr><td>{{ $reservation->user_name }}</td><td>{{ $reservation->medicine_name }}</td><td><span class="badge rounded-pill {{ $reservation->status === 'pending' ? 'text-bg-warning' : ($reservation->status === 'confirmed' ? 'text-bg-success' : 'text-bg-secondary') }}">{{ ucfirst($reservation->status) }}</span></td><td>{{ \Illuminate\Support\Carbon::parse($reservation->created_at)->format('Y-m-d') }}</td></tr>
        @empty<tr><td colspan="4" class="text-muted py-4">No reservations yet.</td></tr>@endforelse
      </tbody></table></div>
    </div>
    <div class="dashboard-panel"><div class="d-flex justify-content-between mb-3"><h5 class="fw-bold">Stock Overview</h5><a href="/pharmacist/inventory">View all</a></div>
      @forelse($inventory->take(5) as $item)
        <div class="row align-items-center border-bottom py-3"><div class="col-sm-6">{{ $item->medicine_name }}</div><div class="col-sm-6"><div class="small mb-2">{{ $item->quantity }} units</div><div class="progress" style="height: 6px" role="progressbar" aria-label="{{ $item->medicine_name }} stock" aria-valuenow="{{ $item->quantity }}" aria-valuemin="0" aria-valuemax="{{ max(100, $inventory->max('quantity')) }}"><div class="progress-bar {{ $item->quantity <= 10 ? 'bg-warning' : 'bg-success' }}" style="width: {{ $item->quantity / max(100, $inventory->max('quantity')) * 100 }}%"></div></div></div></div>
      @empty<p class="text-muted">Your inventory is empty. <a href="/pharmacist/medicines">Add your first medicine</a>.</p>@endforelse
    </div>
  @elseif(in_array($section, ['medicines', 'inventory', 'prices']))
    @if($section === 'medicines')
      <div class="dashboard-panel mb-4"><h5 class="fw-bold mb-3">Add or Update Medicine</h5><p class="text-muted small">For an existing medicine, saving replaces its current quantity and price.</p>
        <form method="POST" action="/pharmacist/add">@csrf
          <fieldset @disabled(!$isActive)><div class="row g-3">
            <div class="col-md-6"><label for="medicine_name" class="form-label">Medicine name</label><input id="medicine_name" name="medicine_name" class="form-control" list="medicineOptions" required maxlength="255" value="{{ old('medicine_name') }}"><datalist id="medicineOptions">@foreach($all_medicines as $medicine)<option value="{{ $medicine->name }}">@endforeach</datalist></div>
            <div class="col-md-3"><label for="price" class="form-label">Price (UGX)</label><input id="price" name="price" type="number" min="0" max="99999999.99" step="0.01" class="form-control" required value="{{ old('price') }}"></div>
            <div class="col-md-3"><label for="quantity" class="form-label">Quantity</label><input id="quantity" name="quantity" type="number" min="0" max="2147483647" class="form-control" required value="{{ old('quantity') }}"></div>
            <div class="col-12"><button class="btn btn-primary">Save medicine</button></div>
          </div></fieldset>
        </form>
      </div>
    @endif
    <div class="dashboard-panel"><div class="d-flex justify-content-between mb-3"><h5 class="fw-bold">{{ $section === 'prices' ? 'Update Prices' : 'Your Inventory' }}</h5>@if($section !== 'medicines')<a href="/pharmacist/medicines" class="btn btn-sm btn-primary">Add medicine</a>@endif</div>
      <p class="small text-muted">Set quantity to zero to mark a medicine out of stock. Remove deletes only your pharmacy’s stock listing.</p>
      <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Medicine</th><th>Price (UGX)</th><th>Quantity</th><th>Status</th><th>Actions</th></tr></thead><tbody>
        @forelse($inventory as $item)
          <tr><td>{{ $item->medicine_name }}</td>
            <td><input form="stock-{{ $item->id }}" aria-label="Price for {{ $item->medicine_name }}" name="price" type="number" min="0" max="99999999.99" step="0.01" value="{{ $item->price }}" class="form-control" style="min-width: 110px" required @disabled(!$isActive)></td>
            <td><input form="stock-{{ $item->id }}" aria-label="Quantity for {{ $item->medicine_name }}" name="quantity" type="number" min="0" max="2147483647" value="{{ $item->quantity }}" class="form-control" style="min-width: 85px" required @disabled(!$isActive)></td>
            <td><span class="badge {{ $item->quantity === 0 ? 'text-bg-danger' : ($item->quantity <= 10 ? 'text-bg-warning' : 'text-bg-success') }}">{{ str_replace('_', ' ', $item->stock_status) }}</span></td>
            <td><div class="d-flex gap-2"><form id="stock-{{ $item->id }}" method="POST" action="/pharmacist/inventory/{{ $item->id }}">@csrf @method('PUT')<button class="btn btn-sm btn-outline-primary" @disabled(!$isActive)>Save</button></form><form method="POST" action="/pharmacist/inventory/{{ $item->id }}" onsubmit="return confirm('Remove this medicine from your inventory?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" @disabled(!$isActive)>Remove</button></form></div></td>
          </tr>
        @empty<tr><td colspan="5" class="text-muted py-4">No medicines in your inventory.</td></tr>@endforelse
      </tbody></table></div>
    </div>
  @elseif($section === 'profile')
    <div class="dashboard-panel"><h5 class="fw-bold mb-3">Pharmacy Details</h5><form method="POST" action="/pharmacist/profile">@csrf
      @foreach(['name' => 'Pharmacy name', 'location' => 'Location', 'phone_number' => 'Phone number'] as $field => $label)
        <div class="mb-3"><label for="{{ $field }}" class="form-label">{{ $label }}</label><input id="{{ $field }}" name="{{ $field }}" class="form-control" value="{{ old($field, $pharmacy->$field ?? '') }}" required></div>
      @endforeach
      <p class="text-muted small">License: {{ $pharmacy->license_number }} · Approval: {{ ucfirst($pharmacy->status) }}</p><button class="btn btn-primary">Save profile</button>
    </form></div>
  @elseif($section === 'settings')
    <div class="dashboard-panel"><h5 class="fw-bold mb-3">Account & Security</h5><form method="POST" action="/pharmacist/settings">@csrf
      @foreach(['name' => ['Full name', 'text'], 'email' => ['Email', 'email'], 'current_password' => ['Current password', 'password'], 'password' => ['New password (optional)', 'password'], 'password_confirmation' => ['Confirm new password', 'password']] as $field => [$label, $type])
        <div class="mb-3"><label for="{{ $field }}" class="form-label">{{ $label }}</label><input id="{{ $field }}" name="{{ $field }}" type="{{ $type }}" class="form-control" @if($type !== 'password') value="{{ old($field, $currentUser->$field) }}" @endif @if(in_array($field, ['name', 'email', 'current_password'])) required @endif autocomplete="{{ $type === 'password' ? ($field === 'current_password' ? 'current-password' : 'new-password') : 'on' }}"></div>
      @endforeach
      <button class="btn btn-primary">Save settings</button>
    </form></div>
  @elseif($section === 'subscription')
    @include('pharmacy.subscription')
  @endif
@endsection
@section('dashboard_notifications')
  @if($pendingCount)<a class="d-block text-decoration-none border-bottom py-3" href="/pharmacist/requests"><strong>{{ $pendingCount }} pending reservations</strong><div class="small text-muted">Review and approve patient requests.</div></a>@endif
  @if($lowStock)<a class="d-block text-decoration-none border-bottom py-3" href="/pharmacist/inventory"><strong>{{ $lowStock }} medicines low on stock</strong><div class="small text-muted">Review quantities and restock.</div></a>@endif
  @if(!$isActive)<p class="small text-muted mt-3">Your pharmacy is awaiting admin approval.</p>@endif
  @if(!$pendingCount && !$lowStock && $isActive)<p class="small text-muted mb-0">You’re all caught up.</p>@endif
@endsection
