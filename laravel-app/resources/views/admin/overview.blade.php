<section class="admin-card admin-overview" aria-labelledby="overview-heading">
  <div class="admin-card-heading admin-chart-heading">
    <h2 id="overview-heading">System Overview</h2>
    <div class="admin-periods" role="group" aria-label="Chart period">
      <button class="active" type="button" data-admin-period="week" aria-pressed="true">This Week</button>
      <button type="button" data-admin-period="month" aria-pressed="false">This Month</button>
      <button type="button" data-admin-period="year" aria-pressed="false">This Year</button>
    </div>
  </div>
  <div class="admin-chart-meta"><label for="admin-chart-metric" class="visually-hidden">Activity to display</label><select id="admin-chart-metric" class="form-select form-select-sm"><option value="reservations">Reservations</option><option value="users">New users</option><option value="pharmacies">New pharmacies</option></select><span id="admin-chart-total" class="admin-muted">{{ array_sum($chartData['week']['series']['reservations']) }} reservations this week</span></div>
  <div class="admin-chart-wrap"><svg id="admin-overview-chart" viewBox="0 0 760 265" role="img" aria-label="Reservations recorded this week"></svg></div>
  <p id="admin-chart-readout" class="admin-chart-readout" aria-live="polite">Hover over or focus a point to see its count.</p>
  <details class="admin-chart-table"><summary>View chart data</summary><div class="table-responsive"><table class="table table-sm mt-2"><thead><tr><th>Period</th><th id="admin-chart-table-heading">Reservations</th></tr></thead><tbody id="admin-chart-table-body">@foreach($chartData['week']['dates'] as $i => $date)<tr><td>{{ $date }}</td><td>{{ $chartData['week']['series']['reservations'][$i] }}</td></tr>@endforeach</tbody></table></div></details>
  <noscript><p class="admin-muted">Open “View chart data” for this week’s totals, or Reports for the yearly summary.</p></noscript>
</section>
