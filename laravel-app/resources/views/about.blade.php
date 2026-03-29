@extends('layouts.app')

@section('content')
<div class="py-5">
    <h2 class="fw-bold mb-3 text-primary">About Us</h2>
    <p class="lead text-secondary">Our mission is to make medicine accessible by helping patients locate drugs quickly and reliably.</p>
    
    <div class="row g-4 mt-2">
      <div class="col-md-6">
        <div class="card shadow-sm h-100 border-0">
          <div class="card-body">
            <h5 class="fw-bold">Vision</h5>
            <p class="text-muted">To become the leading digital platform for medicine availability in Africa.</p>
            <h6 class="mt-4 fw-bold">Core values</h6>
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
        <div class="card shadow-sm h-100 border-0">
          <div class="card-body">
            <h5 class="fw-bold">What We Do</h5>
            <ul class="small text-muted mb-0 mt-3" style="line-height: 2;">
              <li>Medicine search across vetted pharmacies</li>
              <li>Price comparison and stock visibility</li>
              <li>Availability notifications and alternatives</li>
              <li>Pharmacy onboarding with admin approval</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <h4 class="fw-semibold mt-5 mb-3">The Team</h4>
    <div class="row g-3">
      <div class="col-md-3">
        <div class="card shadow-sm p-3 h-100 border-0">
            <div class="text-primary fw-bold">Founder</div>
            <div class="small text-muted">Leads product vision.</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm p-3 h-100 border-0">
            <div class="text-primary fw-bold">Lead Developer</div>
            <div class="small text-muted">Builds and ships the platform.</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm p-3 h-100 border-0">
            <div class="text-primary fw-bold">Pharmacist Advisor</div>
            <div class="small text-muted">Validates medicine data and safety.</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm p-3 h-100 border-0">
            <div class="text-primary fw-bold">Community Manager</div>
            <div class="small text-muted">Supports pharmacies and patients.</div>
        </div>
      </div>
    </div>
</div>
@endsection