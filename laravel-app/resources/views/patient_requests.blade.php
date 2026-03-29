@extends('layouts.app')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0 text-dark">My Medicine Requests</h3>
        <a href="/" class="btn btn-primary shadow-sm">
            <i class="bi bi-search me-1"></i> New Search
        </a>
    </div>

    <div class="table-responsive card shadow-sm border-0">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-uppercase text-muted">
                    <th>Medicine</th>
                    <th>Pharmacy & Location</th>
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
                            <span class="badge bg-success-subtle text-success px-3">
                                <i class="bi bi-check-circle-fill me-1"></i> Ready for Pick-up
                            </span>
                        @elseif ($r->status === 'declined')
                            <span class="badge bg-danger-subtle text-danger px-3">
                                <i class="bi bi-x-circle-fill me-1"></i> Unavailable
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-dark px-3">
                                <i class="bi bi-clock-history me-1"></i> Pending Review
                            </span>
                        @endif
                    </td>
                    <td class="small text-muted">
                        {{ $r->note ?? '-' }}
                    </td>
                    <td class="small">
                        {{ date('D, M d Y', strtotime($r->created_at)) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="text-muted mb-3">You haven't placed any medicine requests yet.</div>
                        <a href="/" class="btn btn-outline-primary">Search for Medicine</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 p-3 bg-light rounded shadow-sm border-start border-primary border-4">
        <p class="small text-muted mb-0">
            <strong>Note:</strong> If your request is <strong>Confirmed</strong>, please visit the pharmacy within 24 hours to collect your medicine. Bring your ID and the prescription if required.
        </p>
    </div>
</div>
@endsection