@extends('layouts.dashboard')
@section('title', 'Subscription Payments | Medfinder')
@section('dashboard_title', 'Subscription Payments')
@section('dashboard_subtitle', 'Verify mobile-money receipts before activating pharmacy subscriptions.')
@section('dashboard_sidebar')
  @include('partials.admin-sidebar')
@endsection
@section('dashboard_main')
<div class="dashboard-panel"><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Pharmacy</th><th>Reference / phone</th><th>Amount</th><th>Period</th><th>Status</th><th>Review</th></tr></thead><tbody>
@forelse($payments as $payment)
  <tr><td>{{ $payment->pharmacy_name }}</td><td>{{ $payment->reference }}<div class="small text-muted">{{ $payment->phone }}</div></td><td>UGX {{ number_format($payment->amount) }}</td><td>{{ $payment->months }} month(s)</td><td>{{ ucfirst($payment->status) }}</td><td>
    @if($payment->status === 'pending')<div class="d-flex gap-2">
      <form method="POST" action="/admin/subscriptions/{{ $payment->id }}/approve" onsubmit="return confirm('Have you verified this transaction and amount in the mobile-money account?')">@csrf<button class="btn btn-sm btn-success">Verify payment</button></form>
      <form method="POST" action="/admin/subscriptions/{{ $payment->id }}/reject">@csrf<button class="btn btn-sm btn-outline-danger">Reject</button></form>
    </div>@else Reviewed @endif
  </td></tr>
@empty<tr><td colspan="6" class="text-muted py-4">No subscription payments submitted.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
