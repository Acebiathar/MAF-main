@extends('layouts.app')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0 text-dark">Requests for {{ $pharmacy->name }}</h3>
        <a href="/pharmacist" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Inventory
        </a>
    </div>

    <div class="table-responsive card shadow-sm border-0">
        <table class="table align-middle mb-0">
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
                    <td>
                        <div class="fw-bold text-primary">{{ $r->medicine_name }}</div>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $r->user_name }}</div>
                        <div class="small text-muted">{{ $r->user_email }}</div>
                    </td>
                    <td>
                        @if ($r->status === 'confirmed')
                            <span class="badge bg-success-subtle text-success px-3">Confirmed</span>
                        @elseif ($r->status === 'declined')
                            <span class="badge bg-secondary-subtle text-secondary px-3">Declined</span>
                        @else
                            <span class="badge bg-warning-subtle text-dark px-3">Pending</span>
                        @endif
                    </td>
                    <td class="small text-wrap" style="max-width: 200px;">
                        {{ $r->note ?? '-' }}
                    </td>
                    <td class="small text-muted">
                        {{ date('M d, H:i', strtotime($r->created_at)) }}
                    </td>
                    <td class="text-end">
                        @if($r->status === 'pending')
                            <div class="d-flex gap-2 justify-content-end">
                                <form method="POST" action="/pharmacist/requests/{{ $r->id }}/confirm">
                                    @csrf
                                    <button class="btn btn-sm btn-success px-3 shadow-sm" type="submit">Confirm</button>
                                </form>
                                <form method="POST" action="/pharmacist/requests/{{ $r->id }}/decline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Decline</button>
                                </form>
                            </div>
                        @else
                            <span class="text-muted small italic">Processed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <div class="fs-2 mb-2">📩</div>
                        No medicine requests have been placed yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection