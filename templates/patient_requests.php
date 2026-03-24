<?php /** @var array $reservations */ ?>
<h3 class="fw-bold mb-3">My reservations</h3>
<div class="table-responsive card shadow-sm">
  <table class="table align-middle mb-0">
    <thead class="table-light"><tr><th>Medicine</th><th>Pharmacy</th><th>Status</th><th>Note</th><th>Placed</th></tr></thead>
    <tbody>
      <?php if ($reservations): ?>
        <?php foreach ($reservations as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['medicine_name']) ?></td>
          <td><?= htmlspecialchars($r['pharmacy_name']) ?><div class="small text-muted"><?= htmlspecialchars($r['pharmacy_location']) ?></div></td>
          <td>
            <?php if ($r['status'] === 'confirmed'): ?><span class="badge bg-success">Confirmed<?php elseif ($r['status'] === 'declined'): ?><span class="badge bg-secondary">Declined<?php else: ?><span class="badge bg-warning text-dark">Pending<?php endif; ?></span>
          </td>
          <td class="small"><?= htmlspecialchars($r['note'] ?? '-') ?></td>
          <td class="small text-muted"><?= htmlspecialchars($r['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
      <tr><td colspan="5" class="text-muted">No reservations yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
