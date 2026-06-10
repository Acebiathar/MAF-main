@extends('layouts.app')

@section('title', 'How it Works | MedFinder')

@section('content')

<section class="how-hero py-5 mb-5 rounded-4 bg-primary bg-opacity-10 mx-2 mx-md-4">
  <div class="container py-4 text-center">
    <span class="badge bg-primary text-primary px-3 py-2 rounded-pill mb-3 fw-semibold text-uppercase tracking-wider">Operational Guide</span>
    <h1 class="display-5 fw-bold text-dark mb-3">How MedFinder Bridges the Healthcare Gap</h1>
    <p class="lead text-secondary mx-auto mb-0" style="max-width: 720px;">
      MedFinder matches real-time pharmacy supply records with active patient requests across Uganda. Discover how our system ensures you never make a wasted trip.
    </p>
  </div>
</section>

<section class="container mb-5">
  <div class="text-center mb-5">
    <h2 class="fw-bold text-navy">The Step-by-Step Process</h2>
    <p class="text-secondary">From entering a prescription search string to confirming your pickup hold.</p>
  </div>

  <div class="row g-4">
    <div class="col-md-6 col-lg-3">
      <div class="card h-100 border-0 shadow-sm rounded-4 p-3 custom-hover-card">
        <div class="card-body">
          <div class="step-badge-icon bg-primary text-white rounded-3 mb-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
            <i class="bi bi-search fs-4"></i>
          </div>
          <span class="text-primary fw-bold text-uppercase small tracking-widest d-block mb-1">Step 01</span>
          <h5 class="card-title fw-bold text-dark mb-2">Search Medicine</h5>
          <p class="card-text text-secondary small mb-0">
            Patients input targeted medication strings. You can use commas to lookup an entire prescription bundle simultaneously (e.g., <em>"Amoxicillin, Zinc"</em>).
          </p>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-lg-3">
      <div class="card h-100 border-0 shadow-sm rounded-4 p-3 custom-hover-card">
        <div class="card-body">
          <div class="step-badge-icon bg-success text-white rounded-3 mb-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
            <i class="bi bi-shuffle fs-4"></i>
          </div>
          <span class="text-success fw-bold text-uppercase small tracking-widest d-block mb-1">Step 02</span>
          <h5 class="card-title fw-bold text-dark mb-2">Algorithmic Match</h5>
          <p class="card-text text-secondary small mb-0">
            The engine queries active stock registers, prioritizing and ranking approved pharmacies based on how many requested items they hold in stock.
          </p>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-lg-3">
      <div class="card h-100 border-0 shadow-sm rounded-4 p-3 custom-hover-card">
        <div class="card-body">
          <div class="step-badge-icon bg-info text-white rounded-3 mb-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
            <i class="bi bi-cart-plus-fill fs-4"></i>
          </div>
          <span class="text-info fw-bold text-uppercase small tracking-widest d-block mb-1">Step 03</span>
          <h5 class="card-title fw-bold text-dark mb-2">Lock Reservation</h5>
          <p class="card-text text-secondary small mb-0">
            The patient picks a preferred store location, logs an optional instruction note, and routes an immediate pending request ticket over to the pharmacy.
          </p>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-lg-3">
      <div class="card h-100 border-0 shadow-sm rounded-4 p-3 custom-hover-card">
        <div class="card-body">
          <div class="step-badge-icon bg-warning text-dark rounded-3 mb-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
            <i class="bi bi-check-all fs-3"></i>
          </div>
          <span class="text-warning fw-bold text-uppercase small tracking-widest d-block mb-1">Step 04</span>
          <h5 class="card-title fw-bold text-dark mb-2">Stock Sync Action</h5>
          <p class="card-text text-secondary small mb-0">
            Pharmacists check physical inventory and click confirm. This securely decrements the item count from stock tables, preventing outdated listings.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container mb-5 py-4">
  <div class="bg-dark text-white p-4 p-lg-5 rounded-4 shadow-lg">
    <div class="row align-items-center g-4">
      <div class="col-lg-5">
        <span class="badge bg-secondary mb-3 px-3 py-2">System Blueprint</span>
        <h2 class="fw-bold mb-3 text-white">How Our Database Safeguards Accuracy</h2>
        <p class="text-light opacity-75 small mb-4">
          Behind our fast search experience lies a relational database that links users, licensed premises, standardized catalogs, and relational inventory counts smoothly.
        </p>
        <div class="d-flex flex-wrap gap-2 mb-2">
          <div class="bg-secondary bg-opacity-20 rounded-3 p-2 d-flex align-items-center gap-2 border border-secondary border-opacity-20">
            <i class="bi bi-shield-fill-check text-success"></i> <span class="small">Secure Sessions</span>
          </div>
          <div class="bg-secondary bg-opacity-20 rounded-3 p-2 d-flex align-items-center gap-2 border border-secondary border-opacity-20">
            <i class="bi bi-lightning-charge-fill text-warning"></i> <span class="small">Real-time Updates</span>
          </div>
        </div>
        <a href="#" data-bs-toggle="modal" data-bs-target="#medSearchModal" class="btn btn-primary px-4 py-3 rounded-3 fw-semibold mt-3">
          <i class="bi bi-search me-2"></i> Open Finder Popup
        </a>
      </div>

      <div class="col-lg-7">
        <div class="table-responsive rounded-3 bg-secondary bg-opacity-10 p-2 border border-secondary border-opacity-20">
          <table class="table table-dark table-borderless table-striped mb-0 align-middle small">
            <thead>
              <tr class="text-muted border-bottom border-secondary">
                <th scope="col" class="py-3">Module</th>
                <th scope="col" class="py-3">Operational Purpose</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="fw-bold text-primary py-3"><i class="bi bi-people-fill me-2"></i> Users</td>
                <td class="text-light opacity-75">Handles access groups for Patients, Pharmacists, and System Admins.</td>
              </tr>
              <tr>
                <td class="fw-bold text-success py-3"><i class="bi bi-building-check me-2"></i> Pharmacies</td>
                <td class="text-light opacity-75">Manages store registration profiles, locations, and verification status.</td>
              </tr>
              <tr>
                <td class="fw-bold text-info py-3"><i class="bi bi-capsule me-2"></i> Medicines</td>
                <td class="text-light opacity-75">A dictionary of medication names and medical classifications.</td>
              </tr>
              <tr>
                <td class="fw-bold text-warning py-3"><i class="bi bi-node-plus-fill me-2"></i> Stock Pivot</td>
                <td class="text-light opacity-75">Binds drugs to stores, tracking prices, stock volumes, and status tags.</td>
              </tr>
              <tr>
                <td class="fw-bold text-danger py-3"><i class="bi bi-bookmark-heart-fill me-2"></i> Holds</td>
                <td class="text-light opacity-75">Tracks reservation states from <code>pending</code> to <code>confirmed</code>.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container mb-5 py-3">
  <div class="row align-items-center g-5">
    <div class="col-md-6">
      <div class="position-relative">
        <div class="bg-primary bg-opacity-10 rounded-4 p-4 text-center border border-primary border-opacity-20">
          <i class="bi bi-building-lock text-primary display-2 mb-3 d-block"></i>
          <h3 class="fw-bold text-dark">NDA Verification Check</h3>
          <p class="text-secondary small max-w-sm mx-auto" style="max-width: 320px;">
            Every partner pharmacy undergoes evaluation before accessing inventory distribution pipelines.
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-3 fw-semibold">Trust & Security</span>
      <h2 class="fw-bold text-navy mb-4">Patient Safety is Our Core Focus</h2>

      <div class="d-flex gap-3 mb-4">
        <div class="flex-shrink-0 text-success fs-4"><i class="bi bi-patch-check-fill"></i></div>
        <div>
          <h5 class="fw-bold text-dark mb-1">Strict License Review</h5>
          <p class="text-secondary small mb-0">Pharmacists must provide clear verification details during sign-up. Accounts stay in a pending state until verified by an administrator.</p>
        </div>
      </div>

      <div class="d-flex gap-3 mb-4">
        <div class="flex-shrink-0 text-success fs-4"><i class="bi bi-shield-fill-exclamation"></i></div>
        <div>
          <h5 class="fw-bold text-dark mb-1">Anti-Hoarding Safeguards</h5>
          <p class="text-secondary small mb-0">Reservations have strict quantity limits and expiry windows to prevent medication hoarding and preserve local stock balances.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="container mb-5 py-4">
  <div class="text-center mb-5" style="max-width: 600px; margin: 0 auto;">
    <h2 class="fw-bold text-navy">Frequently Asked Questions</h2>
    <p class="text-secondary">Have questions about reserving or managing items on MedFinder? Here are the quick answers.</p>
  </div>

  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="accordion accordion-flush shadow-sm rounded-4 overflow-hidden border" id="howFaqAccordion">

        <div class="accordion-item border-bottom">
          <h2 class="accordion-header" id="faq-headingOne">
            <button class="accordion-button fw-semibold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseOne" aria-expanded="true" aria-controls="faq-collapseOne">
              Is there a cost to use MedFinder to search and reserve?
            </button>
          </h2>
          <div id="faq-collapseOne" class="accordion-collapse collapse show" aria-labelledby="faq-headingOne" data-bs-parent="#howFaqAccordion">
            <div class="accordion-body text-secondary small">
              No, searching for medication and placing a reservation hold is entirely free for patients. You only pay for the medication itself directly at the pharmacy counter during pickup.
            </div>
          </div>
        </div>

        <div class="accordion-item border-bottom">
          <h2 class="accordion-header" id="faq-headingTwo">
            <button class="accordion-button collapsed fw-semibold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseTwo" aria-expanded="false" aria-controls="faq-collapseTwo">
              How long will a pharmacy hold my reserved medicine?
            </button>
          </h2>
          <div id="faq-collapseTwo" class="accordion-collapse collapse" aria-labelledby="faq-headingTwo" data-bs-parent="#howFaqAccordion">
            <div class="accordion-body text-secondary small">
              Typically, pharmacies hold confirmed reservations for up to 24 hours. If you need extra time, you can add pickup details inside the optional comment text box when submitting your hold.
            </div>
          </div>
        </div>

        <div class="accordion-item border-bottom">
          <h2 class="accordion-header" id="faq-headingThree">
            <button class="accordion-button collapsed fw-semibold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseThree" aria-expanded="false" aria-controls="faq-collapseThree">
              Can I look up multiple medications at the same time?
            </button>
          </h2>
          <div id="faq-collapseThree" class="accordion-collapse collapse" aria-labelledby="faq-headingThree" data-bs-parent="#howFaqAccordion">
            <div class="accordion-body text-secondary small">
              Yes! Simply separate individual item variants with commas inside the search text boxes (e.g., <em>"Panadol, Amoxicillin"</em>). The ranking algorithm evaluates multi-item bundles and matches stores that have the highest stock count for your list.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="faq-headingFour">
            <button class="accordion-button collapsed fw-semibold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseFour" aria-expanded="false" aria-controls="faq-collapseFour">
              How can a pharmacy register its store listing?
            </button>
          </h2>
          <div id="faq-collapseFour" class="accordion-collapse collapse" aria-labelledby="faq-headingFour" data-bs-parent="#howFaqAccordion">
            <div class="accordion-body text-secondary small">
              Pharmacists can click the register option, select the <strong>Pharmacist</strong> role profile, and provide their pharmacy license number, operational address, and store phone contact details. The profile activates as soon as an administrator confirms their documentation.
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

@endsection

<style>
  .text-navy {
    color: #0b2f5c;
  }

  .tracking-widest {
    letter-spacing: 0.08em;
  }

  .custom-hover-card {
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
  }

  .custom-hover-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08) !important;
  }

  .accordion-button:not(.collapsed) {
    background-color: rgba(13, 110, 253, 0.05) !important;
    color: #0d6efd !important;
    box-shadow: none !important;
  }

  .accordion-button:focus {
    box-shadow: none !important;
    border-color: rgba(0, 0, 0, .125) !important;
  }
</style>