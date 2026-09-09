<div class="dashboard-panel">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
    <div><h4 class="fw-bold mb-1">Search History</h4><p class="small text-muted mb-0">Revisit medicines you searched for while signed in.</p></div>
    @if($searchHistory->total() > 0)
      <form method="POST" action="/requests/search-history" onsubmit="return confirm('Clear your search history?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Clear history</button></form>
    @endif
  </div>
  <div class="list-group list-group-flush">
    @forelse($searchHistory as $search)
      <div class="list-group-item px-0 py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="patient-search-details"><div class="fw-semibold text-break">{{ $search->query }}</div><div class="small text-muted">{{ \Illuminate\Support\Carbon::parse($search->searched_at)->format('d M Y, H:i') }} · {{ $search->result_count }} listings when searched</div></div>
        <a class="btn btn-sm btn-outline-primary" href="{{ route('index', ['search' => $search->query]) }}">Search again</a>
      </div>
    @empty
      <div class="text-center py-5"><i class="bi bi-clock-history fs-1 text-primary" aria-hidden="true"></i><p class="text-muted mt-3">No searches yet. Your medicine searches will appear here.</p><a class="btn btn-primary" href="/">Search for medicine</a></div>
    @endforelse
  </div>
  @if($searchHistory->hasPages())<div class="mt-3">{{ $searchHistory->links('pagination::bootstrap-5') }}</div>@endif
</div>
