
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
            <th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th>Account status</th><th>Actions</th>
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
            <td><span class="badge {{ $item->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $item->is_active ? 'Active' : 'Deactivated' }}</span></td>
            <td>
              @if($item->role === 'admin')
                <span class="small text-muted">Protected administrator</span>
              @else
                <details class="admin-account-action">
                  <summary class="{{ $item->is_active ? 'text-danger' : 'text-primary' }}">{{ $item->is_active ? 'Deactivate' : 'Reactivate' }} account<span class="visually-hidden"> for {{ $item->name }}</span></summary>
                  <form method="POST" action="/admin/users/{{ $item->id }}/status" class="mt-3">
                    @csrf
                    <input type="hidden" name="is_active" value="{{ $item->is_active ? '0' : '1' }}">
                    <p class="small">{{ $item->is_active ? 'This blocks sign-in and existing sessions. Account records are kept.' : 'This restores access. The user must sign in again.' }}</p>
                    <label for="account-reason-{{ $item->id }}" class="form-label">Reason</label>
                    <textarea id="account-reason-{{ $item->id }}" name="reason" class="form-control mb-3" rows="2" required minlength="5" maxlength="500"></textarea>
                    <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="confirmed" value="1" id="account-confirm-{{ $item->id }}" required><label class="form-check-label small" for="account-confirm-{{ $item->id }}">I confirm this change for {{ $item->name }}.</label></div>
                    <button class="btn btn-sm {{ $item->is_active ? 'btn-outline-danger' : 'btn-outline-primary' }}" type="submit">Confirm {{ $item->is_active ? 'deactivation' : 'reactivation' }}</button>
                  </form>
                </details>
              @endif
            </td>
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

