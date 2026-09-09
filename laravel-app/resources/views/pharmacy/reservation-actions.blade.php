@if($reservation->status === 'pending')
  @php $availableQuantity = (int) ($inventory->firstWhere('medicine_id', $reservation->medicine_id)->quantity ?? 0); @endphp
  <div class="d-flex flex-wrap gap-2">
    <form method="POST" action="/pharmacist/requests/{{ $reservation->id }}/confirm">
      @csrf
      <button class="btn btn-sm btn-success rounded-pill px-3" type="submit" @disabled(!$isActive || $availableQuantity < 1) aria-label="Approve reservation for {{ $reservation->user_name }}: {{ $reservation->medicine_name }}"><i class="bi bi-check2 me-1" aria-hidden="true"></i>Approve</button>
    </form>
    <form method="POST" action="/pharmacist/requests/{{ $reservation->id }}/decline">
      @csrf
      <button class="btn btn-sm btn-outline-danger rounded-pill px-3" type="submit" @disabled(!$isActive) aria-label="Decline reservation for {{ $reservation->user_name }}: {{ $reservation->medicine_name }}">Decline</button>
    </form>
  </div>
  @if(!$isActive)
    <div class="small text-muted mt-2">Your pharmacy must be approved before processing reservations.</div>
  @elseif($availableQuantity < 1)
    <a class="small d-inline-block mt-2" href="/pharmacist/inventory">Restock this medicine to approve.</a>
  @else
    <div class="small text-muted mt-2">{{ $availableQuantity }} units available · Approval reserves 1 unit.</div>
  @endif
@else
  <span class="small {{ $reservation->status === 'confirmed' ? 'text-success' : 'text-muted' }}">{{ $reservation->status === 'confirmed' ? 'Approved for pickup' : 'Declined' }}</span>
@endif
