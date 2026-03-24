@extends('layouts.app')

@section('content')
<h3 class="fw-bold mb-3">Requests for {{ $pharmacy->name }}</h3>
<div class="table-responsive card shadow-sm">
  <table class="table align-middle mb-0">
    <thead class="table-light"><tr><th>Medicine</th><th>Patient</th><th>Contact</th><th>Status</th><th>Note</th><th>Placed</th><th></th></tr></thead>
    <tbody>
      @if($reservations->count())
        @foreach($reservations as $r)
        <tr>
          <td>{{ $r->medicine_name }}</td>
          <td>{{ $r->user_name }}</td>
          <td class="small text-muted">{{ $r->user_email }}</td>
          <td>
            @if($r->status === 'confirmed')<span class="badge bg-success">Confirmed
            @elseif($r->status === 'declined')<span class="badge bg-secondary">Declined
            @else<span class="badge bg-warning text-dark">Pending
            @endif</span>
          </td>
          <td class="small">{{ $r->note ?? '-' }}</td>
          <td class="small text-muted">{{ $r->created_at }}</td>
          <td class="text-end">
            <form method="post" action="{{ url('/pharmacist/requests/'.$r->id.'/confirm') }}" class="d-inline">
              @csrf
              <button class="btn btn-sm btn-success" type="submit">Confirm</button>
            </form>
            <form method="post" action="{{ url('/pharmacist/requests/'.$r->id.'/decline') }}" class="d-inline ms-1">
              @csrf
              <button class="btn btn-sm btn-outline-secondary" type="submit">Decline</button>
            </form>
          </td>
        </tr>
        @endforeach
      @else
      <tr><td colspan="7" class="text-muted">No requests yet.</td></tr>
      @endif
    </tbody>
  </table>
</div>
@endsection
