<?php
/** @var array $reservations */
/** @var array $pharmacy */
?>
<h3 class="fw-bold mb-3">Requests for <?= htmlspecialchars($pharmacy['name']) ?></h3>
<div class="table-responsive card shadow-sm">
  <table class="table align-middle mb-0">
    <thead class="table-light"><tr><th>Medicine</th><th>Patient</th><th>Contact</th><th>Status</th><th>Note</th><th>Placed</th><th></th></tr></thead>
    <tbody>
      <?php if ($reservations): ?>
        <?php foreach ($reservations as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['medicine_name']) ?></td>
          <td><?= htmlspecialchars($r['user_name']) ?></td>
          <td class="small text-muted"><?= htmlspecialchars($r['user_email']) ?></td>
          <td>
            <?php if ($r['status'] === 'confirmed'): ?><span class="badge bg-success">Confirmed<?php elseif ($r['status'] === 'declined'): ?><span class="badge bg-secondary">Declined<?php else: ?><span class="badge bg-warning text-dark">Pending<?php endif; ?></span>
          </td>
          <td class="small"><?= htmlspecialchars($r['note'] ?? '-') ?></td>
          <td class="small text-muted"><?= htmlspecialchars($r['created_at']) ?></td>
          <td class="text-end">
            <form method="post" action="/pharmacist/requests/<?= (int)$r['id'] ?>/confirm" class="d-inline">
              <button class="btn btn-sm btn-success" type="submit">Confirm</button>
            </form>
            <form method="post" action="/pharmacist/requests/<?= (int)$r['id'] ?>/decline" class="d-inline ms-1">
              <button class="btn btn-sm btn-outline-secondary" type="submit">Decline</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
      <tr><td colspan="7" class="text-muted">No requests yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
