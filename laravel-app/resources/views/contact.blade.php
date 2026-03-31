@extends('layouts.app')

@section('content')
<div class="py-4">
    <h2 class="fw-bold mb-4 text-primary">Contact Us</h2>
    
    <div class="row g-4">
      {{-- Contact Info Card --}}
      <div class="col-md-5">
        <div class="card shadow-sm h-100 border-0 bg-white p-2">
          <div class="card-body">
            <h5 class="fw-bold mb-3">Reach us</h5>
            <div class="d-flex mb-3">
                <div class="text-primary me-3"><i class="bi bi-envelope"></i></div>
                <div>
                    <div class="small text-muted">Email</div>
                    <div class="fw-semibold">support@maf.com</div>
                </div>
            </div>
            <div class="d-flex mb-3">
                <div class="text-primary me-3"><i class="bi bi-telephone"></i></div>
                <div>
                    <div class="small text-muted">Phone</div>
                    <div class="fw-semibold">+256 773496048</div>
                </div>
            </div>
            <div class="d-flex">
                <div class="text-primary me-3"><i class="bi bi-geo-alt"></i></div>
                <div>
                    <div class="small text-muted">Location</div>
                    <div class="fw-semibold">Kampala, Uganda</div>
                </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Suggestion Box Form --}}
      <div class="col-md-7">
        <div class="card shadow-sm h-100 border-0 bg-white">
          <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Suggestion box</h5>
            <form method="POST" action="/contact">
              @csrf
              <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Name</label>
                    <input class="form-control" name="name" placeholder="Your Name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Email</label>
                    <input class="form-control" type="email" name="email" placeholder="email@example.com" required>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label small fw-bold">Message</label>
                <textarea class="form-control" rows="4" name="message" placeholder="How can we improve MedFinder?" required></textarea>
              </div>
              <button class="btn btn-primary px-4 fw-bold" type="submit">Send Message</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- FAQs Section --}}
    <h4 class="fw-semibold mt-5 mb-3">Frequently Asked Questions</h4>
    <div class="accordion border-0 shadow-sm" id="faqs">
      <div class="accordion-item border-0 border-bottom">
        <h2 class="accordion-header" id="faq1">
          <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1c">
            Is the price accurate?
          </button>
        </h2>
        <div id="faq1c" class="accordion-collapse collapse show" data-bs-parent="#faqs">
          <div class="accordion-body text-secondary">
            Prices come directly from registered pharmacies. However, we recommend confirming during your visit as prices in Uganda can fluctuate.
          </div>
        </div>
      </div>
      
      <div class="accordion-item border-0">
        <h2 class="accordion-header" id="faq2">
          <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2c">
            Can I reserve medicine?
          </button>
        </h2>
        <div id="faq2c" class="accordion-collapse collapse" data-bs-parent="#faqs">
          <div class="accordion-body text-secondary">
            Yes, if the pharmacy offers reservations through Medicine Availability Finder. Once reserved, you will receive a notification to pick it up within a specific timeframe.
          </div>
        </div>
      </div>
    </div>
</div>
@endsection