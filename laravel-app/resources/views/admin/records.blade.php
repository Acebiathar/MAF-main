
  @if(isset($viewList) && $viewList !== null)
  <div class="dashboard-table-card p-3 p-lg-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
      <div>
        <div class="text-uppercase small text-muted fw-semibold">Detailed View</div>
        <h4 class="fw-bold mb-0">{{ $selectedViewLabel }}</h4>
      </div>
      <a href="/admin" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Close List</a>
    </div>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr class="small text-uppercase text-muted">
            @if($viewType == 'users')
            <th>Name</th><th>Email</th><th>Role</th><th>Joined</th>
            @elseif($viewType == 'pharmacies')
            <th>Name</th><th>Location</th><th>License</th><th>Contact</th>
            @elseif($viewType == 'medicines')
            <th>Medicine Name</th><th>Added On</th>
            @elseif($viewType == 'reservations')
            <th>Patient</th><th>Status</th><th>Date</th>
            @endif
          </tr>
        </thead>
        <tbody>
          @foreach ($viewList as $item)
          <tr>
            @if($viewType == 'users')
            <td class="fw-bold">{{ $item->name }}</td>
            <td>{{ $item->email }}</td>
            <td><span class="badge bg-light text-dark border">{{ $item->role }}</span></td>
            <td class="text-muted small">{{ date('M d, Y', strtotime($item->created_at ?? now())) }}</td>
            @elseif($viewType == 'pharmacies')
            <td class="fw-bold">{{ $item->name }}</td>
            <td>{{ $item->location }}</td>
            <td><code>{{ $item->license_number }}</code></td>
            <td>{{ $item->phone_number ?? 'N/A' }}</td>
            @elseif($viewType == 'medicines')
            <td class="fw-bold">{{ $item->name }}</td>
            <td class="text-muted small">{{ date('M d, Y', strtotime($item->created_at ?? now())) }}</td>
            @elseif($viewType == 'reservations')
            <td class="fw-bold">{{ $item->patient_name ?? 'User #' . $item->user_id }}</td>
            <td><span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">{{ $item->status }}</span></td>
            <td class="text-muted small">{{ date('M d, Y', strtotime($item->created_at ?? now())) }}</td>
            @endif
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif

