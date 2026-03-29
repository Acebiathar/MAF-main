<?php
/** @var array $pending */
/** @var array $stats */
?>
<h3 class="fw-bold mb-3">Admin Dashboard</h3>
<div class="row g-3 mb-4">
  <div class="col-md-3"><div class="card shadow-sm p-3 h-100"><div class="text-muted small">Users</div><div class="fs-4 fw-bold"><?= (int)$stats['users'] ?></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm p-3 h-100"><div class="text-muted small">Pharmacies</div><div class="fs-4 fw-bold"><?= (int)$stats['pharmacies'] ?></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm p-3 h-100"><div class="text-muted small">Medicines</div><div class="fs-4 fw-bold"><?= (int)$stats['medicines'] ?></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm p-3 h-100"><div class="text-muted small">Reservations</div><div class="fs-4 fw-bold"><?= (int)$stats['reservations'] ?></div></div></div>
</div>

<h5 class="fw-semibold">Pending pharmacies</h5>
<div class="table-responsive card shadow-sm">
  <table class="table align-middle mb-0">
    <thead class="table-light"><tr><th>Name</th><th>Location</th><th>License</th><th>Phone</th><th>Action</th></tr></thead>
    <tbody>
      <?php if ($pending): ?>
        <?php foreach ($pending as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td><?= htmlspecialchars($p['location']) ?></td>
          <td><?= htmlspecialchars($p['license_number']) ?></td>
          <td><?= htmlspecialchars($p['phone']) ?></td>
          <td>
            <a class="btn btn-sm btn-success" href="/admin/pharmacies/<?= (int)$p['id'] ?>/approve">Approve</a>
            <a class="btn btn-sm btn-outline-secondary" href="/admin/pharmacies/<?= (int)$p['id'] ?>/reject">Reject</a>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
      <tr><td colspan="5" class="text-muted">No pending pharmacies.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
