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
@section('dashboard_title', 'Reservations for ' . $pharmacy->name)
@section('dashboard_subtitle', 'Review incoming reservations, respond fast to patient needs, and keep fulfillment moving smoothly.')
@section('dashboard_welcome_meta')
  <a href="/pharmacist" class="btn btn-light text-primary rounded-pill px-4 fw-semibold">Back to Dashboard</a>
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
              @include('pharmacy.reservation-actions', ['reservation' => $r])
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
