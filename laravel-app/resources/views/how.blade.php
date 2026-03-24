@extends('layouts.app')

@section('content')
<h2 class="fw-bold mb-4">How It Works</h2>
<div class="row g-4">
  <div class="col-md-6">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h5>Step 1 — Search</h5>
        <p>Enter the medicine name. We instantly search approved pharmacies.</p>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h5>Step 2 — Compare</h5>
        <p>See availability, price, and location in one table.</p>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h5>Step 3 — Reserve or visit</h5>
        <p>Pick the pharmacy that suits you and call or visit.</p>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h5>Step 4 — Notifications</h5>
        <p>Subscribe to get alerts when your medicine restocks or prices change.</p>
      </div>
    </div>
  </div>
</div>
@endsection
