gi@extends('layouts.app')

@section('content')
<h3 class="fw-bold mb-3">My reservations</h3>
<div class="table-responsive card shadow-sm">
  <table class="table align-middle mb-0">
    <thead class="table-light">
      <gtr>
        <th>Medicine</th>
        <th>Pharmacy</th>
        <th>Status</th>
        <th>Note</th>
        <th>Placed</th>
        </tr>
    </thead>
    <tbody>
      @if($reservations->count())
      @foreach($reservations as $r)
      <tr>
        <td>{{ $r->medicine_name }}</td>
        <td>{{ $r->pharmacy_name }}
          <div class="small text-muted">{{ $r->pharmacy_location }}</div>
        </td>
        <td>
          @if($r->status === 'confirmed')<span class="badge bg-success">Confirmed
            @elseif($r->status === 'declined')<span class="badge bg-secondary">Declined
              @else<span class="badge bg-warning text-dark">Pending
                @endif</span>
        </td>
        <td class="small">{{ $r->note ?? '-' }}</td>
        <td class="small text-muted">{{ $r->created_at }}</td>
      </tr>
      @endforeach
      @else
      <tr>
        <td colspan="5" class="text-muted">No reservations yet.</td>
      </tr>
      @endif
    </tbody>
  </table>
</div>
@endsection