@extends('layouts.app')

@section('title', 'Medfinder | Smart Healthcare Access')

@section('styles')
<link rel="preload" as="image" href="https://images.pexels.com/photos/4386467/pexels-photo-4386467.jpeg?auto=compress&cs=tinysrgb&w=1200">
@endsection

@section('fullwidth')
<div class="landing-page">
    <!-- Spinner Overlay -->
    <div id="spinnerOverlay" class="spinner-overlay">
        <div class="text-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
            <p class="mt-3 fw-bold text-primary">MedFinder Loading...</p>
        </div>
    </div>

    <!-- Hero Section with integrated search -->
    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('https://images.pexels.com/photos/4386467/pexels-photo-4386467.jpeg?auto=compress&cs=tinysrgb&w=1200');">
                <div class="carousel-caption">
                    <h5 class="text-white text-uppercase">Smart Healthcare Access</h5>
                    <h1 class="display-3 fw-bold text-white">Find Medicine<br>Near You. Instantly.</h1>
                    <div class="hero-search-wrapper d-flex align-items-center mt-4">
                        <input type="text" id="heroSearchInput" class="form-control-lg flex-grow-1 bg-transparent" placeholder="Search for a medicine... e.g., Amoxicillin">
                        <button id="heroSearchBtn" class="btn fw-bold">Search Now</button>
                    </div>
                    <p class="text-white-50 mt-3 small"><i class="fas fa-arrow-down me-1"></i> Or add multiple items below</p>
                </div>
            </div>
        </div>
    </div>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold h3 mb-0">Featured <span class="text-primary">Partners & Products</span></h2>
                <div class="carousel-controls">
                    <button class="btn btn-outline-primary btn-sm rounded-circle" type="button" data-bs-target="#adCarousel" data-bs-slide="prev">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="btn btn-outline-primary btn-sm rounded-circle" type="button" data-bs-target="#adCarousel" data-bs-slide="next">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div id="adCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <!-- Slide 1: Hospitals -->
                    <div class="carousel-item active" data-bs-interval="5000">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="ad-card">
                                    <span class="badge bg-danger mb-2">Hospital</span>
                                    <h4>Mulago National Referral</h4>
                                    <p class="text-muted small">Specialized care and 24/7 emergency services.</p>
                                    <img src="{{ asset('images/pharmacy3.jpg') }}" alt="pharmacy3" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ad-card">
                                    <span class="badge bg-danger mb-2">Hospital</span>
                                    <h4>Case Medical Centre</h4>
                                    <p class="text-muted small">Quality healthcare for your entire family.</p>
                                    <img src="{{ asset('images/pharmacy.jpg') }}" alt="pharmacy" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-md-4 d-none d-md-block">
                                <div class="ad-card border-primary border">
                                    <span class="badge bg-primary mb-2">Promoted Drug</span>
                                    <h4>Panadol Extra</h4>
                                    <p class="text-muted small">Fast-acting relief for tough headaches.</p>
                                    <img src="{{ asset('images/drugs.jpg') }}" class="img-fluid rounded-3" alt="drugs">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2: Drugs & Pharmacies -->
                    <div class="carousel-item" data-bs-interval="5000">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="ad-card">
                                    <span class="badge bg-primary mb-2">Featured Drug</span>
                                    <h4>Amoxicillin</h4>
                                    <p class="text-muted small">Wide spectrum antibiotics available at verified stores.</p>
                                    <img src="{{ asset('images/amoxy.jpg') }}" class="img-fluid rounded-3" alt="amoxy">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ad-card">
                                    <span class="badge bg-success mb-2">Pharmacy</span>
                                    <h4>First Pharmacy</h4>
                                    <p class="text-muted small">Your neighborhood partner for genuine medicine.</p>
                                    <img src="https://via.placeholder.com/400x200?text=First+Pharmacy" class="img-fluid rounded-3" alt="Pharmacy">
                                </div>
                            </div>
                            <div class="col-md-4 d-none d-md-block">
                                <div class="ad-card border-warning border">
                                    <span class="badge bg-warning text-dark mb-2">Limited Offer</span>
                                    <h4>Vitamin C Boost</h4>
                                    <p class="text-muted small">20% off at all Kampala-based pharmacies.</p>
                                    <img src="https://via.placeholder.com/400x200?text=Vitamin+C" class="img-fluid rounded-3" alt="Drug">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== NEW SECTION: HOW IT WORKS (CAROUSEL) ========== -->
    <div class="container py-5 my-3">
        <div class="text-center mb-4">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">Simple Process</span>
            <h2 class="fw-bold mt-2 display-6">How MedFinder Works</h2>
            <p class="text-muted">From search to doorstep delivery in three smart steps</p>
        </div>
        <div id="howItWorksCarousel" class="carousel slide howit-carousel" data-bs-ride="carousel" data-bs-interval="4000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row g-4 justify-content-center">
                        <div class="col-md-4">
                            <div class="howit-card">
                                <div class="step-icon"><i class="fas fa-search-location"></i></div>
                                <h4 class="fw-bold">1. Search & Compare</h4>
                                <p class="text-muted">Enter medicine name, view real-time stock & price across 180+ partner pharmacies near you.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="howit-card">
                                <div class="step-icon"><i class="fas fa-hand-holding-heart"></i></div>
                                <h4 class="fw-bold">2. Reserve Instantly</h4>
                                <p class="text-muted">Click reserve to secure your medication. Pay online or at pickup — simple & transparent.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="howit-card">
                                <div class="step-icon"><i class="fas fa-truck-fast"></i></div>
                                <h4 class="fw-bold">3. Fast Delivery/Pickup</h4>
                                <p class="text-muted">Same-day delivery or collect from pharmacy. Real-time tracking for peace of mind.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row g-4 justify-content-center">
                        <div class="col-md-4">
                            <div class="howit-card">
                                <div class="step-icon"><i class="fas fa-clock"></i></div>
                                <h4 class="fw-bold">24/7 Emergency Access</h4>
                                <p class="text-muted">Urgent medications flagged for immediate response — contact emergency hotline anytime.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="howit-card">
                                <div class="step-icon"><i class="fas fa-shield-alt"></i></div>
                                <h4 class="fw-bold">Authenticity Guarantee</h4>
                                <p class="text-muted">All partner pharmacies verified, medicines sourced from licensed distributors.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="howit-card">
                                <div class="step-icon"><i class="fas fa-mobile-alt"></i></div>
                                <h4 class="fw-bold">Smart Reminders</h4>
                                <p class="text-muted">Set refill reminders & get dosage alerts — manage family health seamlessly.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#howItWorksCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#howItWorksCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- ========== MAF QUALITY & HOW WE DELIVER EXCELLENCE ========== -->
    <div class="container py-4">
        <div class="maf-quality row align-items-center g-4">
            <div class="col-md-6">
                <h3 class="fw-bold"><i class="fas fa-award text-primary me-2"></i> Why MAF Ensures Trust & Quality</h3>
                <p class="mt-3">MedFinder Access Foundation (MAF) operates with a rigorous partner verification system, cold-chain support for sensitive meds, and real-time stock monitoring. We partner only with licensed pharmacies and offer transparent pricing.</p>
                <div class="mt-4 d-flex gap-4 flex-wrap">
                    <div><span class="counter-number" id="verifiedPartners">180</span><br><span>Verified Pharmacies</span></div>
                    <div><span class="counter-number" id="monthlyOrders">24.5k</span><br><span>Monthly Orders Fulfilled</span></div>
                    <div><span class="counter-number" id="customerRating">4.9</span><br><span>⭐ Customer Rating</span></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="glass-card p-3">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="quality-badge-icon"><i class="fas fa-thermometer-half fa-2x text-primary"></i></div>
                        <div><strong>Temperature-controlled logistics</strong><br><small>For insulin & biologics</small></div>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="quality-badge-icon"><i class="fas fa-user-md fa-2x text-primary"></i></div>
                        <div><strong>Pharmacist on chat</strong><br><small>Free consultation before purchase</small></div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="quality-badge-icon"><i class="fas fa-credit-card fa-2x text-primary"></i></div>
                        <div><strong>Secure payments & escrow</strong><br><small>Pay after order confirmation</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== TESTIMONIALS SECTION (Carousel + star ratings) ========== -->
    <div class="container py-5">
        <div class="text-center mb-4">
            <span class="badge bg-warning bg-opacity-15 text-dark px-3 py-2 rounded-pill">Real Stories</span>
            <h2 class="fw-bold">Trusted by thousands across Uganda</h2>
            <p class="text-muted">What our community says about MedFinder</p>
        </div>
        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="testimonial-card h-100">
                                <i class="fas fa-quote-right quote-icon"></i>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="avatar-sm">RN</div>
                                    <div><strong>Ruth N.</strong><br><span class="rating-stars">★★★★★</span></div>
                                </div>
                                <p class="fst-italic">“I needed an emergency antibiotic for my child at midnight. MedFinder showed me a 24hr pharmacy 2km away. I reserved in 2 minutes! Lifesaver.”</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="testimonial-card h-100">
                                <i class="fas fa-quote-right quote-icon"></i>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="avatar-sm">JM</div>
                                    <div><strong>John M.</strong><br><span class="rating-stars">★★★★★</span></div>
                                </div>
                                <p class="fst-italic">“Excellent prices compared to local stores. The delivery was super fast, and the medicine was genuine. Highly recommend the reservation system.”</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="testimonial-card h-100">
                                <i class="fas fa-quote-right quote-icon"></i>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="avatar-sm">AK</div>
                                    <div><strong>Alice K.</strong><br><span class="rating-stars">★★★★☆</span></div>
                                </div>
                                <p class="fst-italic">“Easy interface, I like the stock update feature. MAF support helped me find a rare diabetic medicine. Will always use MedFinder!”</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="testimonial-card h-100">
                                <i class="fas fa-quote-right quote-icon"></i>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="avatar-sm">PO</div>
                                    <div><strong>Peter O.</strong><br><span class="rating-stars">★★★★★</span></div>
                                </div>
                                <p class="fst-italic">“The no-cost reservation feature gives confidence. I can reserve and pick up after work without worrying about stock outs. Great innovation!”</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="testimonial-card h-100">
                                <i class="fas fa-quote-right quote-icon"></i>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="avatar-sm">LN</div>
                                    <div><strong>Lilian N.</strong><br><span class="rating-stars">★★★★★</span></div>
                                </div>
                                <p class="fst-italic">“I love the health tips and reminders. MedFinder truly cares about patients, not just selling medicines. 10/10.”</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="testimonial-card h-100">
                                <i class="fas fa-quote-right quote-icon"></i>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="avatar-sm">ES</div>
                                    <div><strong>Edwin S.</strong><br><span class="rating-stars">★★★★★</span></div>
                                </div>
                                <p class="fst-italic">“Fast, reliable, and they even followed up to confirm my prescription. Top tier service for chronic meds.”</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev" style="width:auto; left:-30px;">
                <span class="carousel-control-prev-icon bg-primary rounded-circle p-2" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next" style="width:auto; right:-30px;">
                <span class="carousel-control-next-icon bg-primary rounded-circle p-2" aria-hidden="true"></span>
            </button>
        </div>
    </div>

    <!-- ========== ANIMATED MEDICAL TIPS SECTION ========== -->
    <div class="container py-4 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <div>
                <h2 class="fw-bold"><i class="fas fa-lightbulb text-warning me-2"></i> Daily Wellness Insights</h2>
                <p>Evidence-based medical tips for healthier living</p>
            </div>
            <div><i class="fas fa-sync-alt text-primary" id="refreshTipsBtn" style="cursor:pointer" title="Refresh tips"></i></div>
        </div>
        <div class="row g-4" id="medicalTipsContainer">
            <!-- Tips will be injected dynamically with animation -->
        </div>
    </div>

    <!-- ========== PARTNER PHARMACIES & MEDICINES ADS (keeping as reference but not mandatory) ========== -->
    <div class="container py-4">
        <div class="section-title-ads">
            <h2><i class="fas fa-store me-2 text-primary"></i> Partner Pharmacies</h2><a href="#" class="pill-link">View all →</a>
        </div>
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="ad-card card border-0 h-100"><img src="https://images.pexels.com/photos/3251209/pexels-photo-3251209.jpeg?auto=compress&cs=tinysrgb&w=400" class="ad-img" loading="lazy" alt="Guardian Health Pharmacy">
                    <div class="card-body">
                        <h5 class="fw-bold">Guardian Health</h5>
                        <p class="text-muted small">Kampala, Kololo</p><span class="badge bg-primary">Verified Partner</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ad-card card border-0 h-100"><img src="https://images.pexels.com/photos/3683098/pexels-photo-3683098.jpeg?auto=compress&cs=tinysrgb&w=400" class="ad-img" loading="lazy" alt="MedPlus Uganda Pharmacy">
                    <div class="card-body">
                        <h5 class="fw-bold">MedPlus Uganda</h5>
                        <p class="text-muted small">Ntinda, Kampala</p><span class="badge bg-success">10% off first order</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ad-card card border-0 h-100"><img src="https://images.pexels.com/photos/262211/pexels-photo-262211.jpeg?auto=compress&cs=tinysrgb&w=400" class="ad-img" loading="lazy" alt="City Chemist Pharmacy">
                    <div class="card-body">
                        <h5 class="fw-bold">City Chemist</h5>
                        <p class="text-muted small">Wandegeya, Kampala</p><span class="badge bg-warning text-dark">⭐ 4.8 rating</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Support rib -->
    <div class="container mb-5">
        <div class="bg-danger text-white rounded-4 p-4 d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4 class="fw-bold mb-1"><i class="fas fa-phone-alt me-2"></i> Emergency Support</h4>
                <p class="mb-0 opacity-75">Need urgent medication? Call our 24/7 hotline.</p>
            </div>
            <h2 class="fw-bold mb-0">0800 199 199</h2>
        </div>
    </div>
</div>

<!-- Toast container -->
<div id="toastRoot" class="position-fixed bottom-0 start-0 p-3" style="z-index: 11"></div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/index-home.js') }}" defer></script>
@endsection