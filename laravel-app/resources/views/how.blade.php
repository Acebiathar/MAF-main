@extends('layouts.app')

@section('title', 'How it Works | MedFinder')

@section('content')
<div class="container py-5">

  <div class="text-center mb-5 animate-on-scroll">
    <h2 class="display-5 fw-bold mb-3 text-primary">How MedFinder Works</h2>
    <p class="lead text-secondary mx-auto" style="max-width: 700px;">
      Connecting patients with pharmacies across Uganda in four simple steps.
    </p>
  </div>

  {{-- Steps --}}
  @php
  $steps = [
  ['title' => 'Search Medicine', 'desc' => 'Users search by medicine name, category, or dosage with smart suggestions.', 'img' => 'image 1.png'],
  ['title' => 'Compare Prices', 'desc' => 'View live results showing real-time availability and exact pricing in UGX.', 'img' => 'image 2.png'],
  ['title' => 'Find Nearest Pharmacy', 'desc' => 'Locate pharmacies closest to you using integrated GPS mapping.', 'img' => 'image 3.png'],
  ['title' => 'Order & Pickup', 'desc' => 'Confirm your order and get a digital receipt for a fast pickup experience.', 'img' => 'image 4.png'],
  ];
  @endphp

  @foreach($steps as $index => $step)
  <section class="row g-5 align-items-center mb-5 {{ $index % 2 != 0 ? 'flex-lg-row-reverse' : '' }} animate-on-scroll">
    <div class="col-lg-6">
      <div class="public-card shadow-sm rounded-4 p-4 h-100 border bg-white">
        <div class="badge bg-primary mb-3">Step {{ $index + 1 }}</div>
        <h2 class="h3 fw-bold mb-3">{{ $step['title'] }}</h2>
        <p class="text-secondary fs-5 mb-0">{{ $step['desc'] }}</p>
      </div>
    </div>
    <div class="col-lg-6">
      <img src="{{ asset('images/' . $step['img']) }}" class="img-fluid rounded-4 shadow-sm border" alt="{{ $step['title'] }}">
    </div>
  </section>
  @endforeach

  {{-- Stats Section --}}
  <section class="stats-section my-5 shadow-sm">
    <div class="container">
      <div class="row text-center g-4">
        <div class="col-md-4">
          <div class="stat-box h-100">
            <h2 class="stat-number"><span id="timeSaved">0</span></h2>
            <p class="fw-bold mb-1">Minutes Saved</p>
            <small class="text-muted">⏱️ 25–110 min faster</small>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-box h-100">
            <h2 class="stat-number"><span id="costSaved">0</span></h2>
            <p class="fw-bold mb-1">Avg. Savings (%)</p>
            <small class="text-muted">💰 Reduced search costs</small>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-box h-100">
            <h2 class="stat-number"><span id="monthlySaved">0</span>+</h2>
            <p class="fw-bold mb-1">Pharmacies</p>
            <small class="text-muted">📉 Across Uganda</small>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- FAQ Section --}}
  <section class="mt-5 pt-4">
    <div class="bg-white border rounded-5 p-4 p-lg-5 shadow-sm">
      <div class="text-center mb-4">
        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-2">FAQ</span>
        <h2 class="fw-bold">Frequently Asked Questions</h2>
        <p class="text-muted">Quickly find answers by typing below</p>
      </div>

      <div class="mb-4 col-md-8 mx-auto">
        <div class="input-group">
          <span class="input-group-text bg-white border-end-0 rounded-start-4"><i class="bi bi-search"></i></span>
          <input type="text" id="faqSearch" class="form-control form-control-lg border-start-0 rounded-end-4" placeholder="Search questions...">
        </div>
      </div>

      <div class="accordion" id="faqAccordion">
        @php
        $faqs = [
        ["What is MedFinder?", "MedFinder helps users find available medicines in nearby pharmacies and reserve them."],
        ["Do I need an account?", "You can search without an account, but reservations require login."],
        ["How does it work?", "Pharmacies update their stock, and users search and reserve medicines in real-time."],
        ["Is the information accurate?", "It depends on pharmacy updates, but confirmations are made during reservation."],
        ["Can I reserve medicine?", "Yes, you can reserve and pick it up from the selected pharmacy."],
        ];
        @endphp

        @foreach($faqs as $index => $faq)
        <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 faq-item overflow-hidden">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $index }}">
              {{ $faq[0] }}
            </button>
          </h2>
          <div id="faqCollapse{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body text-muted">
              {{ $faq[1] }}
            </div>
          </div>
        </div>
        @endforeach
      </div>
      <p id="noResults" class="text-center text-muted mt-3 d-none">No matching questions found.</p>
    </div>
  </section>
</div>
@endsection