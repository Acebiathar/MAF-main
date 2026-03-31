@extends('layouts.app')

@section('content')
<div class="py-4 px-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-dark">Admin Control Center</h3>
            <p class="text-muted small mb-0">System Overview & Pharmacy Moderation</p>
        </div>
        <span class="badge bg-dark px-3 py-2 shadow-sm">System Administrator</span>
    </div>
    
    {{-- Clickable Statistics Row --}}
    <div class="row g-3 mb-5">
      <div class="col-md-3">
        <a href="/admin/view/users" class="text-decoration-none">
            <div class="card shadow-sm p-3 h-100 border-0 transition-hover {{ ($viewType ?? '') == 'users' ? 'bg-primary text-white' : 'bg-white text-dark' }}">
              <div class="small {{ ($viewType ?? '') == 'users' ? 'opacity-75' : 'text-muted' }}">Total Users</div>
              <div class="fs-3 fw-bold">{{ number_format($stats['users'] ?? 0) }}</div>
            </div>
        </a>
      </div>

      <div class="col-md-3">
        <a href="/admin/view/pharmacies" class="text-decoration-none">
            <div class="card shadow-sm p-3 h-100 border-0 transition-hover {{ ($viewType ?? '') == 'pharmacies' ? 'bg-primary text-white' : 'bg-white text-dark' }}">
              <div class="small {{ ($viewType ?? '') == 'pharmacies' ? 'opacity-75' : 'text-muted' }}">Active Pharmacies</div>
              <div class="fs-3 fw-bold {{ ($viewType ?? '') == 'pharmacies' ? 'text-white' : 'text-primary' }}">{{ number_format($stats['pharmacies'] ?? 0) }}</div>
            </div>
        </a>
      </div>

      <div class="col-md-3">
        <a href="/admin/view/medicines" class="text-decoration-none">
            <div class="card shadow-sm p-3 h-100 border-0 transition-hover {{ ($viewType ?? '') == 'medicines' ? 'bg-primary text-white' : 'bg-white text-dark' }}">
              <div class="small {{ ($viewType ?? '') == 'medicines' ? 'opacity-75' : 'text-muted' }}">Medicines Catalog</div>
              <div class="fs-3 fw-bold {{ ($viewType ?? '') == 'medicines' ? 'text-white' : 'text-success' }}">{{ number_format($stats['medicines'] ?? 0) }}</div>
            </div>
        </a>
      </div>

      <div class="col-md-3">
        <a href="/admin/view/reservations" class="text-decoration-none">
            <div class="card shadow-sm p-3 h-100 border-0 transition-hover {{ ($viewType ?? '') == 'reservations' ? 'bg-primary text-white' : 'bg-white text-dark' }}">
              <div class="small {{ ($viewType ?? '') == 'reservations' ? 'opacity-75' : 'text-muted' }}">Total Reservations</div>
              <div class="fs-3 fw-bold {{ ($viewType ?? '') == 'reservations' ? 'text-white' : 'text-info' }}">{{ number_format($stats['reservations'] ?? 0) }}</div>
            </div>
        </a>
      </div>
    </div>

    {{-- Dynamic Data Table: Shows only when a card is clicked --}}
    @if(isset($viewList) && $viewList !== null)
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0 text-primary">Detailed View: {{ ucfirst($viewType) }}</h5>
            <a href="/admin" class="btn btn-sm btn-outline-secondary">Close List</a>
        </div>
        <div class="table-responsive card shadow-sm border-0">
            <table class="table align-middle mb-0">
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
                            <td>{{ $item->phone }}</td>
                        @elseif($viewType == 'medicines')
                            <td class="fw-bold">{{ $item->name }}</td>
                            <td class="text-muted small">{{ date('M d, Y', strtotime($item->created_at ?? now())) }}</td>
                        @elseif($viewType == 'reservations')
                            <td class="fw-bold">{{ $item->patient_name ?? 'User #'.$item->user_id }}</td>
                            <td><span class="badge bg-info">{{ $item->status }}</span></td>
                            <td class="text-muted small">{{ date('M d, Y', strtotime($item->created_at ?? now())) }}</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Pending Approvals Table --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-semibold mb-0">Pending Pharmacy Verification</h5>
        <span class="badge bg-warning text-dark px-3">{{ count($pending) }} Pending Review</span>
    </div>

    <div class="table-responsive card shadow-sm border-0">
      <table class="table align-middle mb-0">
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
            <td>{{ $p->phone }}</td>
            <td class="text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="/admin/pharmacies/{{ $p->id }}/approve" 
                       class="btn btn-sm btn-success px-3 shadow-sm fw-bold"
                       onclick="return confirm('Approve this pharmacy?')">Approve</a>
                    
                    <a href="/admin/pharmacies/{{ $p->id }}/reject" 
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Reject this application?')">Reject</a>
                </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center py-5 text-muted">
                <div class="fs-1 mb-2">✅</div>
                <p class="mb-0 text-muted">All pharmacy applications have been processed.</p>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
</div>

<style>
    .transition-hover {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        cursor: pointer;
    }
    .transition-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important;
    }
    code { font-size: 0.9em; }
</style>
@endsection