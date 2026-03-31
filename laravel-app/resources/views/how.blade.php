@extends('layouts.app')

@section('content')
<div class="py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-3 text-primary">How Medicine Availability Finder Works</h2>
        <p class="lead text-secondary mx-auto" style="max-width: 700px;">
            Connecting patients with pharmacies across Uganda in four simple steps.
        </p>
    </div>

    <div class="row g-4">
      {{-- Step 1 --}}
      <div class="col-md-6">
        <div class="card shadow-sm h-100 border-0 p-3">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">1</div>
                <h5 class="fw-bold mb-0">Search</h5>
            </div>
            <p class="text-muted">Enter the medicine name in our search bar. We instantly scan our database of vetted and approved pharmacies near your location.</p>
          </div>
        </div>
      </div>

      {{-- Step 2 --}}
      <div class="col-md-6">
        <div class="card shadow-sm h-100 border-0 p-3">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">2</div>
                <h5 class="fw-bold mb-0">Compare</h5>
            </div>
            <p class="text-muted">View a live list of results showing real-time availability, exact pricing in UGX, and the pharmacy's specific location.</p>
          </div>
        </div>
      </div>

      {{-- Step 3 --}}
      <div class="col-md-6">
        <div class="card shadow-sm h-100 border-0 p-3">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">3</div>
                <h5 class="fw-bold mb-0">Reserve or Visit</h5>
            </div>
            <p class="text-muted">Pick the pharmacy that suits your budget and distance. Use our "Reserve" feature to hold your medicine or get the contact details to visit immediately.</p>
          </div>
        </div>
      </div>

      {{-- Step 4 --}}
      <div class="col-md-6">
        <div class="card shadow-sm h-100 border-0 p-3">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">4</div>
                <h5 class="fw-bold mb-0">Notifications</h5>
            </div>
            <p class="text-muted">Can't find what you need? Subscribe to specific medicine alerts to get notified the moment a pharmacy restocks or if a better price becomes available.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center mt-5">
        <a href="/" class="btn btn-primary btn-lg px-5 shadow-sm fw-bold">Start Searching Now</a>
    </div>
</div>
@endsection