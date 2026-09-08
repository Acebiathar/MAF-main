@php
  $activePayment = $payments->where('status', 'verified')->filter(fn ($p) => $p->expires_at && \Illuminate\Support\Carbon::parse($p->expires_at)->isFuture())->sortByDesc('expires_at')->first();
  $pendingPayment = $payments->firstWhere('status', 'pending');
@endphp
<div class="dashboard-panel mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3"><h5 class="fw-bold mb-0">Pharmacy Subscription</h5><span class="badge {{ $activePayment ? 'text-bg-success' : 'text-bg-warning' }}">{{ $activePayment ? 'Active' : ($pendingPayment ? 'Awaiting verification' : 'Not active') }}</span></div>
  <p class="fs-2 fw-bold mb-1">UGX {{ number_format(config('subscription.amount')) }} <span class="fs-6 fw-normal text-muted">/ {{ config('subscription.months') == 1 ? 'month' : config('subscription.months').' months' }}</span></p>
  @if($activePayment)<p class="text-success">Paid through {{ \Illuminate\Support\Carbon::parse($activePayment->expires_at)->format('d M Y') }}.</p>@endif
  <p class="text-muted">Renewals extend your existing paid period. Pharmacy approval is managed separately.</p>
  <hr>
  <h6 class="fw-bold">Pay with mobile money</h6>
  <ol class="ps-3"><li class="mb-2">Send UGX {{ number_format(config('subscription.amount')) }} to <strong>{{ config('subscription.payment_number') }}</strong> using your mobile-money service.</li><li class="mb-2">Enter the transaction reference from your payment receipt below.</li><li>An administrator checks the payment and activates your subscription.</li></ol>
  @if($pendingPayment)
    <div class="alert alert-info mb-0">Reference {{ $pendingPayment->reference }} is awaiting verification. You do not need to submit it again.</div>
  @else
    <form action="/pharmacist/subscription" method="POST">@csrf
      <div class="row g-3"><div class="col-md-6"><label for="payment_phone" class="form-label">Phone number used to pay</label><input class="form-control" id="payment_phone" name="phone" type="tel" value="{{ old('phone', $pharmacy->phone_number) }}" maxlength="30" required></div>
      <div class="col-md-6"><label for="payment_reference" class="form-label">Transaction reference</label><input class="form-control" id="payment_reference" name="reference" value="{{ old('reference') }}" maxlength="100" pattern="[A-Za-z0-9-]+" required></div>
      <div class="col-12"><button class="btn btn-primary">Submit payment for verification</button></div></div>
    </form>
  @endif
</div>
<div class="dashboard-panel"><h5 class="fw-bold mb-3">Payment History</h5><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Date</th><th>Reference</th><th>Amount</th><th>Status</th><th>Paid through</th></tr></thead><tbody>
  @forelse($payments as $payment)<tr><td>{{ \Illuminate\Support\Carbon::parse($payment->created_at)->format('d M Y') }}</td><td>{{ $payment->reference }}</td><td>UGX {{ number_format($payment->amount) }}</td><td>{{ ucfirst($payment->status) }}</td><td>{{ $payment->expires_at ? \Illuminate\Support\Carbon::parse($payment->expires_at)->format('d M Y') : '—' }}</td></tr>
  @empty<tr><td colspan="5" class="text-muted py-4">No payments submitted yet.</td></tr>@endforelse
</tbody></table></div></div>
