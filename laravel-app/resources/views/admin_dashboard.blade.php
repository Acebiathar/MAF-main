@extends('layouts.app')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-dark">Admin Control Center</h3>
            <p class="text-muted small mb-0">System Overview & Pharmacy Moderation</p>
        </div>
        <span class="badge bg-dark px-3 py-2 shadow-sm">System Administrator</span>
    </div>
    
    {{-- Statistics Row --}}
    <div class="row g-3 mb-5">
      <div class="col-md-3">
        <div class="card shadow-sm p-3 h-100 border-0 bg-primary text-white">
          <div class="small opacity-75">Total Users</div>
          {{-- Accessing as array since it's a manual compact() array in index.php --}}
          <div class="fs-3 fw-bold">{{ number_format($stats['users'] ?? 0) }}</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm p-3 h-100 border-0 bg-white">
          <div class="text-muted small">Active Pharmacies</div>
          <div class="fs-3 fw-bold text-primary">{{ number_format($stats['pharmacies'] ?? 0) }}</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm p-3 h-100 border-0 bg-white">
          <div class="text-muted small">Medicines Catalog</div>
          <div class="fs-3 fw-bold text-success">{{ number_format($stats['medicines'] ?? 0) }}</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm p-3 h-100 border-0 bg-white">
          <div class="text-muted small">Total Reservations</div>
          <div class="fs-3 fw-bold text-info">{{ number_format($stats['reservations'] ?? 0) }}</div>
        </div>
      </div>
    </div>

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
                    {{-- Approval Action --}}
                    {{-- Note: We use GET in the route regex in index.php for simplicity --}}
                    <a href="/admin/pharmacies/{{ $p->id }}/approve" 
                       class="btn btn-sm btn-success px-3 shadow-sm fw-bold"
                       onclick="return confirm('Approve this pharmacy?')">Approve</a>
                    
                    {{-- Rejection Action --}}
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
                <p class="mb-0">All pharmacy applications have been processed.</p>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
</div>
@endsection