<div class="dashboard-panel mb-4">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3"><h4 class="h5 fw-bold mb-0">Recent Searches</h4><a href="/requests?section=history">View search history</a></div>
  <div class="list-group list-group-flush">
    @forelse($recentSearches as $search)
      <div class="list-group-item px-0 py-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="patient-search-details"><div class="fw-semibold text-break">{{ $search->query }}</div><div class="small text-muted">{{ \Illuminate\Support\Carbon::parse($search->searched_at)->format('d M Y, H:i') }} · {{ $search->result_count }} listings when searched</div></div>
        <a href="{{ route('index', ['search' => $search->query]) }}" class="btn btn-sm btn-outline-primary">Search again</a>
      </div>
    @empty
      <p class="text-muted mb-0">No searches yet. <a href="/">Search for a medicine</a> to get started.</p>
    @endforelse
  </div>
</div>
<div class="dashboard-panel">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3"><h4 class="h5 fw-bold mb-0">Recent Reservations</h4><a href="/requests?section=reservations">View reservations</a></div>
  @forelse($reservations->take(5) as $reservation)
    <div class="border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div class="patient-search-details"><div class="fw-semibold">{{ $reservation->medicine_name }}</div><div class="small text-muted">{{ $reservation->pharmacy_name }} · {{ $reservation->pharmacy_address }}</div><div class="small text-muted">{{ \Illuminate\Support\Carbon::parse($reservation->created_at)->format('d M Y, H:i') }}</div></div>
      <span class="badge rounded-pill {{ $reservation->status === 'confirmed' ? 'text-bg-success' : ($reservation->status === 'pending' ? 'text-bg-warning' : 'text-bg-secondary') }}">{{ $reservation->status === 'confirmed' ? 'Ready for Pickup' : ucfirst($reservation->status) }}</span>
    </div>
  @empty
    <p class="text-muted mb-0">No reservations yet. Choose Reserve on an available medicine in search results.</p>
  @endforelse
</div>
