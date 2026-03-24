@extends('layouts.app')

@section('content')
<h2 class="fw-bold mb-3">About Us</h2>
<p class="lead">Our mission is to make medicine accessible by helping patients locate drugs quickly and reliably.</p>
<div class="row g-4">
  <div class="col-md-6">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h5>Vision</h5>
        <p>To become the leading digital platform for medicine availability in Africa.</p>
        <h6 class="mt-3">Core values</h6>
        <ul class="small text-muted mb-0">
          <li>Accessibility</li>
          <li>Transparency</li>
          <li>Reliability</li>
          <li>Patient care</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h5>What We Do</h5>
        <ul class="small text-muted mb-0">
          <li>Medicine search across vetted pharmacies</li>
          <li>Price comparison and stock visibility</li>
          <li>Availability notifications and alternatives</li>
          <li>Pharmacy onboarding with admin approval</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<h4 class="fw-semibold mt-4">Team</h4>
<div class="row g-3">
  <div class="col-md-3"><div class="card shadow-sm p-3 h-100"><strong>Founder</strong><div class="small text-muted">Leads product vision.</div></div></div>
  <div class="col-md-3"><div class="card shadow-sm p-3 h-100"><strong>Lead Developer</strong><div class="small text-muted">Builds and ships the platform.</div></div></div>
  <div class="col-md-3"><div class="card shadow-sm p-3 h-100"><strong>Pharmacist Advisor</strong><div class="small text-muted">Validates medicine data and safety.</div></div></div>
  <div class="col-md-3"><div class="card shadow-sm p-3 h-100"><strong>Community Manager</strong><div class="small text-muted">Supports pharmacies and patients.</div></div></div>
</div>
@endsection
