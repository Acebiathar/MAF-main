@extends('layouts.app')

@section('title', 'How it Works | MedFinder')

@section('content')

<style>
  :root {
    --primary-gradient: linear-gradient(135deg, #0b2f5c 0%, #1a4d8c 100%);
    --accent-gradient: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
  }

  .text-navy {
    color: #0b2f5c;
  }

  .bg-navy {
    background-color: #0b2f5c;
  }

  .tracking-widest {
    letter-spacing: 0.08em;
  }

  /* Hero Section */
  .how-hero {
    background: linear-gradient(135deg, rgba(13, 110, 253, 0.08) 0%, rgba(11, 47, 92, 0.05) 100%);
    backdrop-filter: blur(2px);
  }

  /* Step Cards */
  .step-card {
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    cursor: pointer;
  }

  .step-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12) !important;
  }

  .step-card:hover .step-img {
    transform: scale(1.05);
  }

  .step-img {
    transition: transform 0.4s ease;
    height: 220px;
    object-fit: cover;
  }

  .step-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    border-radius: 50%;
    z-index: 1;
  }

  .card-img-wrapper {
    position: relative;
    overflow: hidden;
  }

  /* Accordion Enhancements */
  .accordion-button:not(.collapsed) {
    background: linear-gradient(135deg, rgba(13, 110, 253, 0.08) 0%, rgba(11, 47, 92, 0.04) 100%);
    color: #0b2f5c !important;
    font-weight: 700 !important;
  }

  .accordion-button:focus {
    box-shadow: none;
    border-color: rgba(13, 110, 253, 0.2);
  }

  .accordion-item {
    transition: all 0.3s ease;
  }

  .accordion-item:hover {
    background-color: rgba(13, 110, 253, 0.02);
  }

  /* Badge Styles */
  .badge-step {
    padding: 8px 16px;
    font-weight: 600;
    letter-spacing: 0.5px;
  }

  /* Decorative Elements */
  .floating-decoration {
    position: absolute;
    opacity: 0.05;
    pointer-events: none;
  }

  /* Animation */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .animate-fade-up {
    animation: fadeInUp 0.6s ease forwards;
  }

  .delay-1 {
    animation-delay: 0.1s;
  }

  .delay-2 {
    animation-delay: 0.2s;
  }

  .delay-3 {
    animation-delay: 0.3s;
  }

  .delay-4 {
    animation-delay: 0.4s;
  }
</style>

<!-- Hero Section -->
<section class="how-hero py-5 mb-5 rounded-4 mx-2 mx-md-4 position-relative overflow-hidden">
  <div class="floating-decoration">
    <svg width="200" height="200" viewBox="0 0 200 200" fill="none">
      <circle cx="100" cy="100" r="80" stroke="#0d6efd" stroke-width="2" />
      <circle cx="100" cy="100" r="60" stroke="#0d6efd" stroke-width="1.5" />
      <circle cx="100" cy="100" r="40" stroke="#0d6efd" stroke-width="1" />
    </svg>
  </div>

  <div class="container py-5 text-center">
    <div class="animate-fade-up">
      <span class="badge bg-primary bg-opacity-15 text-primary px-4 py-2 rounded-pill mb-4 fw-semibold text-uppercase tracking-widest fs-7">
        <i class="bi bi-info-circle me-2"></i>Operational Guide
      </span>
      <h1 class="display-4 fw-bold mb-4" style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
        How MedFinder Bridges the Healthcare Gap
      </h1>
      <p class="lead text-secondary mx-auto mb-0" style="max-width: 720px; font-size: 1.2rem;">
        MedFinder matches real-time pharmacy supply records with active patient requests across Uganda.
        Discover how our system ensures you never make a wasted trip.
      </p>
    </div>
  </div>
</section>

<!-- Step-by-Step Process -->
<section class="container mb-5 py-3">
  <div class="text-center mb-5 animate-fade-up">
    <h2 class="fw-bold display-6 text-navy mb-3">The Step-by-Step Process</h2>
    <div class="mx-auto" style="width: 60px; height: 3px; background: var(--accent-gradient); border-radius: 3px;"></div>
    <p class="text-secondary mt-3">From entering a prescription search string to confirming your pickup hold</p>
  </div>

  <div class="row g-4 justify-content-center">
    <!-- Step 1 -->
    <div class="col-md-6 col-lg-3 animate-fade-up delay-1">
      <div class="card h-100 shadow-sm border-0 rounded-4 step-card overflow-hidden">
        <div class="card-img-wrapper position-relative">
          <img src="{{ asset('images/image 1.png') }}" class="card-img-top step-img w-100" alt="Search Medicine">
          <div class="step-badge bg-primary text-white shadow-sm">
            01
          </div>
        </div>
        <div class="card-body p-4">
          <div class="mb-3">
            <i class="bi bi-search-heart fs-1 text-primary opacity-75"></i>
          </div>
          <h5 class="fw-bold mb-3 text-navy">Search Medicine</h5>
          <p class="text-muted mb-0">
            Enter the medicine name or prescription details and begin your search across hundreds of pharmacies.
          </p>
        </div>
      </div>
    </div>

    <!-- Step 2 -->
    <div class="col-md-6 col-lg-3 animate-fade-up delay-2">
      <div class="card h-100 shadow-sm border-0 rounded-4 step-card overflow-hidden">
        <div class="card-img-wrapper position-relative">
          <img src="{{ asset('images/image 2.png') }}" class="card-img-top step-img w-100" alt="Compare Results">
          <div class="step-badge bg-success text-white shadow-sm">
            02
          </div>
        </div>
        <div class="card-body p-4">
          <div class="mb-3">
            <i class="bi bi-graph-up-arrow fs-1 text-success opacity-75"></i>
          </div>
          <h5 class="fw-bold mb-3 text-navy">Compare Results</h5>
          <p class="text-muted mb-0">
            View pharmacies with available stock, compare nearby options, and find the best deal.
          </p>
        </div>
      </div>
    </div>

    <!-- Step 3 -->
    <div class="col-md-6 col-lg-3 animate-fade-up delay-3">
      <div class="card h-100 shadow-sm border-0 rounded-4 step-card overflow-hidden">
        <div class="card-img-wrapper position-relative">
          <img src="{{ asset('images/image 3.png') }}" class="card-img-top step-img w-100" alt="Login or Sign Up">
          <div class="step-badge bg-info text-white shadow-sm">
            03
          </div>
        </div>
        <div class="card-body p-4">
          <div class="mb-3">
            <i class="bi bi-person-plus fs-1 text-info opacity-75"></i>
          </div>
          <h5 class="fw-bold mb-3 text-navy">Login or Sign Up</h5>
          <p class="text-muted mb-0">
            Create an account or log in to reserve medicines and track all your requests in one place.
          </p>
        </div>
      </div>
    </div>

    <!-- Step 4 -->
    <div class="col-md-6 col-lg-3 animate-fade-up delay-4">
      <div class="card h-100 shadow-sm border-0 rounded-4 step-card overflow-hidden">
        <div class="card-img-wrapper position-relative">
          <img src="{{ asset('images/image 4.png') }}" class="card-img-top step-img w-100" alt="Reserve & Manage">
          <div class="step-badge bg-warning text-dark shadow-sm">
            04
          </div>
        </div>
        <div class="card-body p-4">
          <div class="mb-3">
            <i class="bi bi-calendar-check fs-1 text-warning opacity-75"></i>
          </div>
          <h5 class="fw-bold mb-3 text-navy">Reserve & Manage</h5>
          <p class="text-muted mb-0">
            Reserve your medicine and receive instant confirmation from the pharmacy for pickup.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FAQ Section -->
<section class="container mb-5 py-4">
  <div class="text-center mb-5 animate-fade-up">
    <h2 class="fw-bold display-6 text-navy mb-3">Frequently Asked Questions</h2>
    <div class="mx-auto" style="width: 60px; height: 3px; background: var(--accent-gradient); border-radius: 3px;"></div>
    <p class="text-secondary mt-3">Have questions about reserving or managing items on MedFinder? Here are the quick answers.</p>
  </div>

  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="accordion shadow-lg rounded-4 overflow-hidden border-0" id="howFaqAccordion">
        <!-- FAQ 1 -->
        <div class="accordion-item border-0 border-bottom">
          <h2 class="accordion-header" id="faq-headingOne">
            <button class="accordion-button fw-semibold text-dark py-4 px-4 fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseOne" aria-expanded="true" aria-controls="faq-collapseOne">
              <i class="bi bi-currency-dollar text-primary me-3"></i>
              Is there a cost to use MedFinder to search and reserve?
            </button>
          </h2>
          <div id="faq-collapseOne" class="accordion-collapse collapse show" aria-labelledby="faq-headingOne" data-bs-parent="#howFaqAccordion">
            <div class="accordion-body text-secondary px-4 pb-4">
              <div class="ps-4 border-start border-3 border-primary">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                No, searching for medication and placing a reservation hold is <strong>entirely free</strong> for patients.
                You only pay for the medication itself directly at the pharmacy counter during pickup.
              </div>
            </div>
          </div>
        </div>

        <!-- FAQ 2 -->
        <div class="accordion-item border-0 border-bottom">
          <h2 class="accordion-header" id="faq-headingTwo">
            <button class="accordion-button collapsed fw-semibold text-dark py-4 px-4 fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseTwo" aria-expanded="false" aria-controls="faq-collapseTwo">
              <i class="bi bi-clock-history text-primary me-3"></i>
              How long will a pharmacy hold my reserved medicine?
            </button>
          </h2>
          <div id="faq-collapseTwo" class="accordion-collapse collapse" aria-labelledby="faq-headingTwo" data-bs-parent="#howFaqAccordion">
            <div class="accordion-body text-secondary px-4 pb-4">
              <div class="ps-4 border-start border-3 border-success">
                Typically, pharmacies hold confirmed reservations for <strong>up to 24 hours</strong>.
                If you need extra time, you can add pickup details inside the optional comment text box when submitting your hold.
              </div>
            </div>
          </div>
        </div>

        <!-- FAQ 3 -->
        <div class="accordion-item border-0 border-bottom">
          <h2 class="accordion-header" id="faq-headingThree">
            <button class="accordion-button collapsed fw-semibold text-dark py-4 px-4 fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseThree" aria-expanded="false" aria-controls="faq-collapseThree">
              <i class="bi bi-capsule text-primary me-3"></i>
              Can I look up multiple medications at the same time?
            </button>
          </h2>
          <div id="faq-collapseThree" class="accordion-collapse collapse" aria-labelledby="faq-headingThree" data-bs-parent="#howFaqAccordion">
            <div class="accordion-body text-secondary px-4 pb-4">
              <div class="ps-4 border-start border-3 border-info">
                <strong>Yes!</strong> Simply separate individual item variants with commas inside the search text boxes
                (e.g., <code class="bg-light px-2 py-1 rounded">"Panadol, Amoxicillin"</code>). The ranking algorithm evaluates
                multi-item bundles and matches stores that have the highest stock count for your list.
              </div>
            </div>
          </div>
        </div>

        <!-- FAQ 4 -->
        <div class="accordion-item border-0">
          <h2 class="accordion-header" id="faq-headingFour">
            <button class="accordion-button collapsed fw-semibold text-dark py-4 px-4 fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseFour" aria-expanded="false" aria-controls="faq-collapseFour">
              <i class="bi bi-building text-primary me-3"></i>
              How can a pharmacy register its store listing?
            </button>
          </h2>
          <div id="faq-collapseFour" class="accordion-collapse collapse" aria-labelledby="faq-headingFour" data-bs-parent="#howFaqAccordion">
            <div class="accordion-body text-secondary px-4 pb-4">
              <div class="ps-4 border-start border-3 border-warning">
                Pharmacists can click the register option, select the <strong class="text-primary">Pharmacist</strong> role profile,
                and provide their pharmacy license number, operational address, and store phone contact details.
                The profile activates as soon as an administrator confirms their documentation.
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Still have questions CTA -->
      <div class="text-center mt-5 p-4 bg-primary bg-opacity-5 rounded-4">
        <i class="bi bi-question-circle fs-1 text-primary mb-2 d-block"></i>
        <h5 class="text-navy mb-2">Still have questions?</h5>
        <p class="text-secondary mb-3">We're here to help you with any concerns.</p>
        <a href="#" class="btn btn-primary rounded-pill px-4">
          <i class="bi bi-chat-dots me-2"></i>Contact Support
        </a>
      </div>
    </div>
  </div>
</section>

@endsection