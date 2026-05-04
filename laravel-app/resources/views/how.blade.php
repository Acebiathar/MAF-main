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

  {{-- FAQ Section --}}
  <section class="mt-5">
    <div class="public-card rounded-5 p-4 p-lg-5">
      <div class="section-kicker mb-2">FAQ</div>
      <h2 class="fw-bold mb-3">Frequently Asked Questions</h2>
      <p class="text-muted mb-4">Quickly find answers by typing below</p>

      {{-- 🔍 Search Bar --}}
      <div class="mb-4">
        <input type="text" id="faqSearch" class="form-control form-control-lg rounded-4"
          placeholder="Search questions...">
      </div>

      <div class="accordion" id="faqAccordion">

        @php
        $faqs = [
        ["What is MedFinder?", "MedFinder helps users find available medicines in nearby pharmacies and reserve them."],
        ["Do I need an account?", "You can search without an account, but reservations require login."],
        ["How does it work?", "Pharmacies update their stock, and users search and reserve medicines in real-time."],
        ["Is the information accurate?", "It depends on pharmacy updates, but confirmations are made during reservation."],
        ["Can I reserve medicine?", "Yes, you can reserve and pick it up from the selected pharmacy."],
        ["Is MedFinder free?", "Yes, the system is free to use."],
        ["How do I find nearby pharmacies?", "The system uses your location to show closest pharmacies."],
        ["Can I cancel a reservation?", "Yes, before the pickup time."],
        ["Does MedFinder deliver medicines?", "No, pickup is done at the pharmacy."],
        ["What if the medicine is unavailable?", "You will be notified and can search for alternatives."]
        ];
        @endphp

        @foreach($faqs as $index => $faq)
        <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 faq-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed rounded-4 fw-semibold faq-question"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#faqCollapse{{ $index }}">
              {{ $faq[0] }}
            </button>
          </h2>
          <div id="faqCollapse{{ $index }}"
            class="accordion-collapse collapse"
            data-bs-parent="#faqAccordion">
            <div class="accordion-body text-muted faq-answer">
              {{ $faq[1] }}
            </div>
          </div>
        </div>
        @endforeach

      </div>

      {{-- ❌ No Results Message --}}
      <p id="noResults" class="text-muted mt-3 d-none">No matching questions found.</p>
    </div>
  </section>

</div>
@endsection

@section('scripts')
<style>
  .faq-search-active {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
  }

  footer.footer-enhanced {
    background: linear-gradient(180deg, #0b2e21, #132524);
  }

  #backToTop {
    position: fixed;
    right: 24px;
    bottom: 24px;
    z-index: 1050;
    border: none;
    background: #198754;
    color: #fff;
    padding: 0.85rem 1rem;
    border-radius: 50px;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.18);
    display: none;
    cursor: pointer;
  }

  #backToTop:hover {
    background: #1ea86c;
  }

  .accordion-button {
    background-color: #fff;
    color: #333;
    transition: 0.3s;
  }

  .accordion-button:not(.collapsed) {
    background-color: #198754;
    color: #fff;
  }

  .accordion-body {
    background-color: #f8f9fa;
    border-radius: 0 0 1rem 1rem;
  }
</style>

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

  document.addEventListener('DOMContentLoaded', function() {
    const faqSearch = document.getElementById('faqSearch');
    const footer = document.querySelector('footer');
    const backToTop = document.createElement('button');

    if (faqSearch) {
      faqSearch.addEventListener('focus', () => faqSearch.classList.add('faq-search-active'));
      faqSearch.addEventListener('blur', () => faqSearch.classList.remove('faq-search-active'));
    }

    if (footer) {
      footer.classList.add('footer-enhanced');
    }

    backToTop.id = 'backToTop';
    backToTop.type = 'button';
    backToTop.innerHTML = '<i class="bi bi-arrow-up-short me-1"></i>Top';
    document.body.appendChild(backToTop);

    backToTop.addEventListener('click', function() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });

    window.addEventListener('scroll', function() {
      backToTop.style.display = window.scrollY > 300 ? 'block' : 'none';
    });

    const faqInput = document.getElementById('faqSearch');
    if (faqInput) {
      faqInput.addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let items = document.querySelectorAll('.faq-item');
        let visibleCount = 0;

        items.forEach(item => {
          let question = item.querySelector('.faq-question').innerText.toLowerCase();
          let answer = item.querySelector('.faq-answer').innerText.toLowerCase();

          if (question.includes(filter) || answer.includes(filter)) {
            item.style.display = "";
            visibleCount++;
          } else {
            item.style.display = "none";
          }
        });

        document.getElementById('noResults').classList.toggle('d-none', visibleCount > 0);
      });
    }
  });
</script>
@endsection