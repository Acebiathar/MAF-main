<?php
/** @var array $pharmacy */
/** @var array $medicines */
/** @var array $inventory */
?>
<h3 class="fw-bold mb-3"><?= htmlspecialchars($pharmacy['name']) ?> dashboard</h3>
<div class="d-flex align-items-center gap-2 mb-3">
  <div class="alert alert-info mb-0">Status: <?= htmlspecialchars(ucfirst($pharmacy['status'])) ?>. Phone <?= htmlspecialchars($pharmacy['phone']) ?> | Location <?= htmlspecialchars($pharmacy['location']) ?></div>
  <a class="btn btn-sm btn-outline-primary" href="/pharmacist/requests">View requests</a>
</div>
<div class="row g-4">
  <div class="col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="fw-semibold">Add / update stock</h5>
        <form method="post" action="/pharmacist/add">
          <div class="mb-3">
            <label class="form-label">Medicine</label>
            <select class="form-select" name="medicine_id" required>
              <?php foreach ($medicines as $med): ?>
              <option value="<?= (int)$med['id'] ?>"><?= htmlspecialchars($med['name']) ?> (<?= htmlspecialchars($med['category']) ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Price (UGX)</label>
            <input class="form-control" type="number" name="price" min="0" step="100" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input class="form-control" type="number" name="quantity" min="0" step="1" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="stock_status">
              <option value="in_stock">In stock</option>
              <option value="out_of_stock">Out of stock</option>
            </select>
          </div>
          <button class="btn btn-primary w-100" type="submit">Save</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-lg-7">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="fw-semibold mb-3">Inventory</h5>
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="table-light">
              <tr><th>Medicine</th><th>Price</th><th>Status</th><th>Qty</th></tr>
            </thead>
            <tbody>
              <?php foreach ($inventory as $item): ?>
              <tr>
                <td><?= htmlspecialchars($item['medicine_name']) ?></td>
                <td><?= number_format((float)($item['price'] ?? 0), 0) ?></td>
                <td><?= $item['stock_status'] === 'in_stock' ? '<span class="badge bg-success">In stock</span>' : '<span class="badge bg-secondary">Out</span>' ?></td>
                <td><?= (int)$item['quantity'] ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
