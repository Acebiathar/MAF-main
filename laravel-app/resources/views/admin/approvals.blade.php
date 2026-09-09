  <div class="dashboard-table-card p-3 p-lg-4" id="pendingApprovals">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
      <div>
        <div class="text-uppercase small text-muted fw-semibold">Requests Table</div>
        <h4 class="fw-bold mb-0">Pending Pharmacy Verification</h4>
      </div>
      <span class="badge bg-warning-subtle text-dark px-3 py-2 rounded-pill">{{ $pendingCount }} pending review</span>
    </div>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr class="small text-uppercase text-muted">
            <th>Pharmacy Details</th>
            <th>Location</th>
            <th>NDA License No.</th>
            <th>Contact</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($pending as $p)
          <tr>
            <td>
              <div class="fw-bold text-dark">{{ $p->name }}</div>
              <div class="small text-muted">ID: #{{ $p->id }}</div>
            </td>
            <td>{{ $p->location }}</td>
            <td><code class="text-primary fw-bold">{{ $p->license_number }}</code></td>
            <td>{{ $p->phone_number ?? 'N/A' }}</td>
            <td class="text-end">
              <div class="d-flex gap-2 justify-content-end">
                <a href="/admin/pharmacies/{{ $p->id }}/approve" class="btn btn-sm btn-success rounded-pill px-3 fw-bold" onclick="return confirm('Approve this pharmacy?')">Approve</a>
                <a href="/admin/pharmacies/{{ $p->id }}/reject" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Reject this application?')">Reject</a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center py-5 text-muted">All pharmacy applications have been processed.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
