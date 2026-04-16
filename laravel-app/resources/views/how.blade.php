@extends('layouts.app') {{-- Changed from 'public' to 'app' --}}

@section('content')
<div class="container py-5">

  <div class="text-center mb-5">
    <h2 class="fw-bold mb-3 text-primary">How Medicine Availability Finder Works</h2>
    <p class="lead text-secondary mx-auto" style="max-width: 700px;">
      Connecting patients with pharmacies across Uganda in four simple steps.
    </p>
  </div>

  {{-- Step 1 --}}
  <section class="row g-5 align-items-center mb-5">
    <div class="col-lg-6">
      <div class="public-card shadow-sm rounded-4 p-4 h-100 border">
        <div class="badge bg-primary mb-2">Step 1</div>
        <h2 class="h3 fw-bold mb-3">Search medicine</h2>
        <p class="text-secondary mb-0">Users search by medicine name, category, or dosage with smart suggestions.</p>
      </div>
    </div>
    <div class="col-lg-6">
      <img src="{{ asset('images/image 1.png') }}" class="img-fluid rounded-4 shadow" alt="Search">
    </div>
  </section>

  {{-- Step 2 --}}
  <section class="row g-5 align-items-center mb-5 flex-lg-row-reverse">
    <div class="col-lg-6">
      <div class="public-card shadow-sm rounded-4 p-4 h-100 border">
        <div class="badge bg-primary mb-2">Step 2</div>
        <h2 class="h3 fw-bold mb-3">Compare Prices</h2>
        <p class="text-secondary mb-0">View live results showing real-time availability and exact pricing in UGX.</p>
      </div>
    </div>
    <div class="col-lg-6">
      <img src="{{ asset('images/image 2.png') }}" class="img-fluid rounded-4 shadow" alt="Compare">
    </div>
  </section>

  {{-- Add Step 3 and 4 following the same pattern --}}
  {{-- Step 3 --}}
  <section class="row g-5 align-items-center mb-5">
    <div class="col-lg-6">
      <div class="public-card shadow-sm rounded-4 p-4 h-100 border">
        <div class="badge bg-primary mb-2">Step 3</div>
        <h2 class="h3 fw-bold mb-3">Find Nearest Pharmacy</h2>
        <p class="text-secondary mb-0">Locate pharmacies closest to you using integrated GPS mapping.</p>
      </div>
    </div>
    <div class="col-lg-6">
      {{-- Notice the hyphen instead of a space --}}
      <img src="{{ asset('images/image 3.png') }}" class="img-fluid rounded-4 shadow" alt="Location Map">
    </div>
  </section>

  {{-- Step 4 --}}
  <section class="row g-5 align-items-center mb-5 flex-lg-row-reverse">
    <div class="col-lg-6">
      <div class="public-card shadow-sm rounded-4 p-4 h-100 border">
        <div class="badge bg-primary mb-2">Step 4</div>
        <h2 class="h3 fw-bold mb-3">Order & Pickup</h2>
        <p class="text-secondary mb-0">Confirm your order and get a digital receipt for a fast pickup experience.</p>
      </div>
    </div>
    <div class="col-lg-6">
      <img src="{{ asset('images/image 4.png') }}" class="img-fluid rounded-4 shadow" alt="Pickup Confirmation">
    </div>
  </section>

  <section class="stats-section">
    <div class="container">
      <div class="row text-center">
        <div class="col-md-4">
          <div class="stat-box">
            <h2 class="stat-number" id="timeSaved">0</h2>
            <p>Minutes Saved <br> per Search</p>
            <small>⏱️ 25–110 min faster</small>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-box">
            <h2 class="stat-number" id="costSaved">0</h2>
            <p>Dollars Saved <br> per Prescription</p>
            <small>💰 $15–$75 less</small>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-box">
            <h2 class="stat-number" id="monthlySaved">0</h2>
            <p>Monthly Savings <br> per Patient</p>
            <small>📉 $50–$250 / month</small>
          </div>
        </div>
      </div>
    </div>
  </section>

  <style>
    .stats-section {
      background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
      padding: 40px 0;
      border-radius: 20px;
      margin: 30px 0;
    }

    .stat-box {
      background: white;
      padding: 22px 16px;
      border-radius: 18px;
      box-shadow: 0 8px 18px rgba(0, 0, 0, 0.06);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      margin-bottom: 18px;
    }

    .stat-box:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }

    .stat-number {
      font-size: 2.4rem;
      font-weight: bold;
      color: #2e7d32;
      margin-bottom: 8px;
    }

    .highlight {
      background: #2e7d32;
      color: white;
    }

    .highlight .stat-number {
      color: #ffeb3b;
    }

    .highlight small {
      color: #ddd;
    }
  </style>

  @section('scripts')
  <script>
    function animateCounter(elementId, start, end, duration) {
      let element = document.getElementById(elementId);
      let startTime = null;

      function updateCounter(currentTime) {
        if (startTime === null) startTime = currentTime;
        let progress = (currentTime - startTime) / duration;
        progress = Math.min(progress, 1);
        let value = Math.floor(start + progress * (end - start));
        element.innerText = value;
        if (progress < 1) {
          requestAnimationFrame(updateCounter);
        } else {
          element.innerText = end;
        }
      }
      requestAnimationFrame(updateCounter);
    }

    const targets = {
      timeSaved: 68,
      costSaved: 45,
      monthlySaved: 150,
      annualSaved: 1800
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animateCounter('timeSaved', 0, targets.timeSaved, 2000);
          animateCounter('costSaved', 0, targets.costSaved, 2000);
          animateCounter('monthlySaved', 0, targets.monthlySaved, 2000);
          animateCounter('annualSaved', 0, targets.annualSaved, 2000);
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.3
    });

    observer.observe(document.querySelector('.stats-section'));
  </script>
  @endsection

</div>
@endsection