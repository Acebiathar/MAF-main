@extends('layouts.app')

@section('content')
<div class="py-4">
    <h3 class="fw-bold mb-4 text-dark">Admin Dashboard</h3>
    
    {{-- Statistics Row --}}
    <div class="row g-3 mb-5">
      <div class="col-md-3">
        <div class="card shadow-sm p-3 h-100 border-0 bg-primary text-white">
          <div class="small opacity-75">Total Users</div>
          <div class="fs-3 fw-bold">{{ number_format($stats['users'] ?? 0) }}</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm p-3 h-100 border-0 bg-white">
          <div class="text-muted small">Registered Pharmacies</div>
          <div class="fs-3 fw-bold text-primary">{{ number_format($stats['pharmacies'] ?? 0) }}</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm p-3 h-100 border-0 bg-white">
          <div class="text-muted small">Medicines Listed</div>
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
        <h5 class="fw-semibold mb-0">Pending Pharmacy Onboarding</h5>
        <span class="badge bg-warning text-dark">{{ count($pending) }} Pending</span>
    </div>

    <div class="table-responsive card shadow-sm border-0">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Pharmacy Name</th>
            <th>Location</th>
            <th>License No.</th>
            <th>Contact Phone</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($pending as $p)
          <tr>
            <td><strong>{{ $p->name }}</strong></td>
            <td>{{ $p->location }}</td>
            <td><code>{{ $p->license_number }}</code></td>
            <td>{{ $p->phone }}</td>
            <td class="text-end">
                <div class="d-flex gap-2 justify-content-end">
                    {{-- Approval Action --}}
                    <form action="/admin/pharmacies/{{ $p->id }}/approve" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success px-3">Approve</button>
                    </form>
                    
                    {{-- Rejection Action --}}
                    <form action="/admin/pharmacies/{{ $p->id }}/reject" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                    </form>
                </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center py-4 text-muted">
                <div class="mb-2">🎉</div>
                No pending pharmacy applications at the moment.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
</div>
@endsection