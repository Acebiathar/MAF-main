<?php
/** @var string $query */
/** @var array $results */
/** @var array $alternatives */
/** @var array $pharmacies */
?>
<section class="hero rounded-4 p-4 p-md-5 mb-4">
  <div class="row align-items-center">
    <div class="col-lg-7">
      <p class="badge bg-primary-subtle text-primary mb-2">Medicine Availability Finder</p>
      <h1 class="fw-bold mb-3">Find medicine near you faster.</h1>
      <p class="lead text-secondary">Search trusted pharmacies, compare prices, see availability, and get alternatives instantly.</p>
      <form class="d-flex gap-2 mt-3" method="get" action="/">
        <input class="form-control form-control-lg" type="search" name="q" placeholder="Search for a medicine..." value="<?= htmlspecialchars($query) ?>" required>
        <button class="btn btn-lg btn-primary" type="submit">Search</button>
      </form>
      <div class="small text-muted mt-2">Try Panadol, Paracetamol, Insulin…</div>
    </div>
    <div class="col-lg-5 mt-4 mt-lg-0">
      <div id="tips" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner rounded-3 shadow-sm">
          <div class="carousel-item active p-4 bg-white">
            <h5>Stay hydrated</h5><p class="mb-0">Drink enough water daily to help your medicine work effectively.</p>
          </div>
          <div class="carousel-item p-4 bg-white">
            <h5>Finish the dose</h5><p class="mb-0">Always finish prescribed medication even if you feel better.</p>
          </div>
          <div class="carousel-item p-4 bg-white">
            <h5>Store safely</h5><p class="mb-0">Keep medicines away from heat and out of reach of children.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if ($query !== ''): ?>
<section class="mb-4">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h4 class="fw-semibold mb-0">Results for "<?= htmlspecialchars($query) ?>"</h4>
  </div>
  <?php if ($results): ?>
    <div class="table-responsive card shadow-sm">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Medicine</th>
            <th>Pharmacy</th>
            <th>Price (UGX)</th>
            <th>Status</th>
            <th>Quantity</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($results as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['medicine_name']) ?></td>
            <td><?= htmlspecialchars($item['pharmacy_name']) ?><div class="small text-muted"><?= htmlspecialchars($item['pharmacy_location']) ?></div></td>
            <td><?= number_format((float)($item['price'] ?? 0), 0) ?></td>
            <td>
              <?php if (($item['stock_status'] ?? '') === 'in_stock'): ?>
                <span class="badge bg-success">In stock</span>
              <?php else: ?>
                <span class="badge bg-secondary">Out of stock</span>
              <?php endif; ?>
            </td>
            <td><?= (int)$item['quantity'] ?></td>
            <td>
              <form method="post" action="/reserve/<?= (int)$item['id'] ?>" class="d-flex gap-1">
                <input type="hidden" name="note" value="Request from search">
                <button class="btn btn-sm btn-primary" type="submit">Reserve</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info">No exact matches yet. Try an alternative below or ask a pharmacist.</div>
  <?php endif; ?>
</section>
<?php endif; ?>

<section class="mb-5">
  <div class="row g-3 align-items-center mb-2">
    <div class="col"><h5 class="fw-semibold mb-0">Alternatives</h5></div>
    <div class="col-auto text-muted small">Same category suggestions</div>
  </div>
  <div class="row g-3">
    <?php if ($alternatives): ?>
      <?php foreach ($alternatives as $med): ?>
      <div class="col-md-3">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <div class="fw-bold"><?= htmlspecialchars($med['name']) ?></div>
            <div class="text-muted small"><?= htmlspecialchars($med['category'] ?? 'General') ?></div>
            <p class="small mt-2"><?= htmlspecialchars($med['description'] ?? '') ?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12 text-muted">Enter a medicine to see suggested alternatives.</div>
    <?php endif; ?>
  </div>
</section>

<section class="mb-5">
  <div class="row g-3">
    <div class="col-md-3">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <div class="icon-circle bg-primary text-white mb-3">1</div>
          <h6 class="fw-semibold">Search medicine</h6>
          <p class="small text-muted mb-0">Type the drug name and we scan approved pharmacies.</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <div class="icon-circle bg-primary text-white mb-3">2</div>
          <h6 class="fw-semibold">Compare prices</h6>
          <p class="small text-muted mb-0">See prices and stock status instantly.</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <div class="icon-circle bg-primary text-white mb-3">3</div>
          <h6 class="fw-semibold">Pick pharmacy</h6>
          <p class="small text-muted mb-0">Choose the nearest or best-rated option.</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <div class="icon-circle bg-primary text-white mb-3">4</div>
          <h6 class="fw-semibold">Get notified</h6>
          <p class="small text-muted mb-0">Subscribe to alerts for restocks or price drops.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="mb-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-semibold mb-0">Nearby pharmacies</h5>
    <div class="small text-muted">Sample data for now</div>
  </div>
  <div class="row g-3">
    <?php foreach ($pharmacies as $pharmacy): ?>
    <div class="col-md-3">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <div class="fw-semibold"><?= htmlspecialchars($pharmacy['name']) ?></div>
          <div class="small text-muted"><?= htmlspecialchars($pharmacy['location']) ?></div>
          <div class="small mt-2">Phone: <?= htmlspecialchars($pharmacy['phone']) ?></div>
          <span class="badge bg-success mt-3">Approved</span>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>
