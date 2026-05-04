@extends('layouts.app')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .landing-page {
        font-family: 'Inter', sans-serif;
        background: #f6f9fe;
        color: #1b2e3c;
        scroll-behavior: smooth;
        overflow-x: hidden;
        min-height: 100vh;
    }

    :root {
        --primary: #0b5ed7;
        --primary-dark: #0a58ca;
        --primary-soft: #eef2ff;
        --secondary: #00b4aa;
        --accent: #ff8744;
        --dark: #1e2f3e;
        --light: #ffffff;
        --shadow-sm: 0 8px 20px rgba(0, 20, 50, 0.05);
        --shadow-md: 0 20px 35px -8px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 25px 45px -12px rgba(0, 0, 0, 0.15);
        --transition: all 0.25s ease;
    }

    /* navbar refinements */
    .site-navbar.navbar {
        backdrop-filter: blur(14px);
        background: rgba(255, 255, 255, 0.94) !important;
        box-shadow: 0 2px 18px rgba(0, 0, 0, 0.02);
        padding: 0.7rem 0;
    }

    .site-navbar .navbar-brand {
        font-weight: 800;
        font-size: 1.8rem;
        background: linear-gradient(135deg, #0b5ed7, #3b82f6);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .btn-primary {
        background: var(--primary);
        border-radius: 60px;
        padding: 0.6rem 1.6rem;
        font-weight: 600;
        transition: var(--transition);
        box-shadow: 0 4px 10px rgba(11, 94, 215, 0.2);
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    /* carousel */
    .hero-carousel .carousel-item {
        height: 88vh;
        min-height: 550px;
        background-size: cover;
        background-position: center 30%;
        position: relative;
    }

    .hero-carousel .carousel-item::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(125deg, rgba(0, 0, 0, 0.55), rgba(0, 20, 40, 0.4));
    }

    .carousel-caption {
        z-index: 2;
        bottom: 18%;
        text-align: left;
        left: 8%;
        right: auto;
    }

    .display-1-custom {
        font-size: 4rem;
        font-weight: 800;
        letter-spacing: -1px;
    }

    .hero-search-bar .input-group {
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
        border-radius: 80px;
        overflow: hidden;
    }

    /* glass cards */
    .glass-card {
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(4px);
        border-radius: 2rem;
        padding: 2rem;
        transition: var(--transition);
        height: 100%;
        box-shadow: var(--shadow-sm);
    }

    .glass-card.primary-bg {
        background: linear-gradient(135deg, #0b5ed7, #0d47a1);
        color: white;
    }

    .glass-card.dark-bg {
        background: linear-gradient(145deg, #1e2f3e, #15262e);
        color: white;
    }

    .glass-card.accent-bg {
        background: linear-gradient(125deg, #00b4aa, #00897b);
        color: white;
    }

    .tag-item {
        background: rgba(13, 110, 253, 0.12);
        color: var(--primary);
        border-radius: 40px;
        padding: 0.45rem 1rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .hero-tag-item {
        background: black(255, 255, 255, 0.92);
        backdrop-filter: blur(4px);
        border-radius: 60px;
        padding: 0.45rem 1rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* result table */
    .modern-table-card {
        background: blue;
        border-radius: 1.8rem;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .badge-stock {
        background: #e0f7e8;
        color: #1e7b48;
        font-weight: 600;
        padding: 0.35rem 0.9rem;
        border-radius: 50px;
    }

    .pharmacy-pill-modern {
        background: white;
        border-radius: 80px;
        padding: 0.8rem 0.5rem;
        transition: all 0.2s;
        border: 1px solid #eef2f6;
    }

    .pharmacy-pill-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 24px -8px rgba(0, 0, 0, 0.08);
    }

    .counter {
        font-size: 2.4rem;
        font-weight: 800;
        line-height: 1;
    }

    /* ad cards */
    .ad-card {
        background: white;
        border-radius: 1.5rem;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
        border: 1px solid rgba(0, 0, 0, 0.03);
        height: 100%;
    }

    .ad-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .ad-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        color: white;
        font-size: 0.7rem;
        padding: 0.2rem 0.7rem;
        border-radius: 30px;
        font-weight: 500;
        z-index: 2;
    }

    .pharmacy-ad-card {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-left: 6px solid var(--primary);
        transition: var(--transition);
    }

    .pharmacy-ad-card:hover {
        background: white;
        transform: translateX(5px);
    }

    .discount-chip {
        background: #ffedd5;
        color: #b45309;
        border-radius: 50px;
        padding: 0.2rem 0.8rem;
        font-size: 0.7rem;
        font-weight: 700;
    }

    /* back to top */
    .back-to-top {
        position: fixed;
        bottom: 28px;
        right: 28px;
        width: 48px;
        height: 48px;
        background: var(--primary);
        border-radius: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        opacity: 0;
        visibility: hidden;
        transition: var(--transition);
        z-index: 99;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    .back-to-top.show {
        opacity: 1;
        visibility: visible;
    }

    /* toast */
    .toast-container-custom {
        position: fixed;
        bottom: 30px;
        left: 30px;
        z-index: 1100;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .toast-notify {
        background: white;
        border-left: 5px solid var(--primary);
        padding: 0.9rem 1.2rem;
        border-radius: 1rem;
        box-shadow: var(--shadow-lg);
        font-weight: 500;
        animation: slideUp 0.28s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .spinner-overlay {
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(14px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .display-1-custom {
            font-size: 2.3rem;
        }

        .carousel-caption {
            left: 5%;
            bottom: 12%;
            text-align: center;
        }

        .hero-search-bar .input-group {
            flex-direction: column;
            border-radius: 40px;
        }
    }
</style>

<div class="landing-page">
    <!-- Spinner -->
    <div id="spinnerOverlay" class="spinner-overlay">
        <div class="text-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
            <p class="mt-3 fw-semibold text-primary">MedFinder <br>loading smart pharmacy network...</p>
        </div>
    </div>

    <!-- Hero Carousel -->
    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: linear-gradient(0deg, rgba(0,0,0,0.3), rgba(0,0,0,0.2)), url('https://images.pexels.com/photos/4386467/pexels-photo-4386467.jpeg?auto=compress&cs=tinysrgb&w=1600'); background-size: cover;">
                <div class="carousel-caption">
                    <h5 class="text-white text-uppercase fw-light">Smart Healthcare Access</h5>
                    <h1 class="display-1-custom text-white fw-bold">Find Medicine<br>Near You. Instantly.</h1>
                    <div class="hero-search-bar">
                        <div class="text-center">
                            <div class="input-group input-group-lg mb-3" style="max-width: 500px; margin: 0 auto;">
                                <input type="text" id="heroItemInput" class="form-control rounded-pill-start border-0 bg-white bg-opacity-95" placeholder="e.g., Amoxicillin, Paracetamol..." onkeypress="if(event.key === 'Enter') addHeroItem()">
                                <button class="btn btn-primary rounded-pill-end px-4" onclick="addHeroItem()"><i class="fas fa-plus-circle me-1"></i> Add</button>
                            </div>
                            <div id="heroEditableItemList" class="d-flex flex-wrap justify-content-center gap-2 mb-3" style="min-height: 48px;"></div>
                            <a href="#searchSection" class="btn btn-light btn-lg rounded-pill px-5 shadow-lg fw-semibold" id="heroSearchBtn" style="display: none;"><i class="fas fa-map-marker-alt me-2 text-primary"></i> Find Availability</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="background-image: linear-gradient(0deg, rgba(0,0,0,0.3), rgba(0,0,0,0.2)), url('https://images.pexels.com/photos/4386466/pexels-photo-4386466.jpeg?auto=compress&cs=tinysrgb&w=1600');">
                <div class="carousel-caption">
                    <h5 class="text-white text-uppercase fw-light">Verified Pharmacy Network</h5>
                    <h1 class="display-1-custom text-white fw-bold">Quality & Transparency</h1>
                    <a href="#about" class="btn btn-outline-light btn-lg rounded-pill px-5 mt-4">Discover More</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark rounded-circle p-3 bg-opacity-50"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark rounded-circle p-3 bg-opacity-50"></span>
        </button>
    </div>

    <!-- Smart Search + Info Cards -->
    <div class="container py-5 mt-3" id="searchSection">
        <div class="row g-4">
            <div class="col-lg-4 wow fadeInUp">
                <div class="glass-card primary-bg h-100">
                    <h3 class="mb-3 fw-bold"><i class="fas fa-microscope me-2"></i> Smart Medicine Search</h3>
                    <div class="input-group input-group-clean bg-white rounded-pill mb-3">
                        <input type="text" id="itemInput" class="form-control border-0 bg-transparent" placeholder="e.g., Amoxicillin, Paracetamol..." onkeypress="if(event.key === 'Enter') addItem()">
                        <button class="btn btn-dark rounded-pill px-4" onclick="addItem()"><i class="fas fa-plus"></i> Add</button>
                    </div>
                    <div id="editableItemList" class="d-flex flex-wrap gap-2 mb-3" style="min-height: 80px;"></div>
                    <form id="searchForm" action="/" method="GET">
                        <button type="submit" id="searchBtn" class="btn btn-light w-100 rounded-pill fw-semibold py-2" style="display: none;"><i class="fas fa-map-pin me-2"></i> Find Availability</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-4 wow fadeInUp">
                <div class="glass-card dark-bg h-100">
                    <h3 class="fw-bold"><i class="fas fa-chart-simple me-2"></i> Live Uganda Stats</h3>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between border-bottom border-white-20 pb-2 mb-3">
                            <span>Medicines catalog</span>
                            <span class="counter fw-bold" id="medCount">2,480</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom border-white-20 pb-2 mb-3">
                            <span>Active Pharmacies</span>
                            <span class="counter fw-bold" id="pharCount">186</span>
                        </div>
                        <div class="d-flex justify-content-between pb-2">
                            <span>Total stock units</span>
                            <span class="counter fw-bold" id="stockCount">58.2k</span>
                        </div>
                    </div>
                    <a href="#" class="btn btn-light rounded-pill w-100 mt-4 fw-semibold">Partner with us <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
            <div class="col-lg-4 wow fadeInUp">
                <div class="glass-card accent-bg h-100">
                    <h3 class="fw-bold"><i class="fas fa-phone-alt me-2"></i> Emergency Support</h3>
                    <p class="mt-3 opacity-90">Urgent medicine? We'll connect you to emergency-ready pharmacies.</p>
                    <h2 class="text-white mt-2 fw-bold" style="font-size: 2rem;">112 / 0800 199 199</h2>
                    <small>24/7 Health Hotline</small>
                    <hr class="bg-white mt-3 opacity-25">
                    <p class="small mb-0">Same‑day delivery options available in Kampala & Wakiso.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== ADVERTISEMENT SECTION: DRUG PROMOS & PHARMACY ADS ========== -->
    <div class="container py-4">
        <div class="row g-4 align-items-stretch">
            <!-- Left side: drug advertisements -->
            <div class="col-lg-7">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge bg-primary-soft text-primary py-2 px-3 rounded-pill"><i class="fas fa-tag me-1"></i> Sponsored</span>
                    <small class="text-muted">health partner offers</small>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="ad-card position-relative h-100">
                            <div class="ad-badge"><i class="fas fa-ad me-1"></i> PROMO</div>
                            <img src="https://images.pexels.com/photos/208512/pexels-photo-208512.jpeg?auto=compress&cs=tinysrgb&w=400" class="w-100" style="height: 160px; object-fit: cover;" alt="medicine promo">
                            <div class="p-3">
                                <h5 class="fw-bold mb-1"><i class="fas fa-capsules text-primary me-1"></i> Amoxicillin 500mg</h5>
                                <p class="small text-muted">Trusted antibiotic, WHO prequalified.</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="discount-chip">-25% OFF</span>
                                    <span class="fw-bold text-primary">from 8,500 UGX</span>
                                </div>
                                <button class="btn btn-sm btn-outline-primary rounded-pill mt-2 w-100" onclick="showToast('Special offer applied at checkout', 'success')"><i class="fas fa-shopping-cart"></i> Claim deal</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="ad-card position-relative h-100">
                            <div class="ad-badge bg-danger">LIMITED</div>
                            <img src="https://images.pexels.com/photos/4041392/pexels-photo-4041392.jpeg?auto=compress&cs=tinysrgb&w=400" class="w-100" style="height: 160px; object-fit: cover;" alt="pain relief">
                            <div class="p-3">
                                <h5 class="fw-bold mb-1"><i class="fas fa-tablets text-primary me-1"></i> Ibuprofen + Paracetamol</h5>
                                <p class="small text-muted">Dual action pain relief, 20 tablets.</p>
                                <div class="d-flex justify-content-between">
                                    <span class="discount-chip">BOGO 50%</span>
                                    <span class="fw-bold text-success">new low price</span>
                                </div>
                                <button class="btn btn-sm btn-primary rounded-pill mt-2 w-100" onclick="showToast('Ibuprofen combo added to your reservation list', 'info')"><i class="fas fa-hand-holding-heart"></i> Reserve now</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="bg-white rounded-4 p-3 d-flex align-items-center justify-content-between flex-wrap shadow-sm border">
                            <div><i class="fas fa-chart-line fa-2x text-primary me-3"></i><span class="fw-semibold">Malaria treatment week: Coartem & Artesunate at <span class="text-danger">−15%</span></span></div>
                            <button class="btn btn-sm btn-light rounded-pill" onclick="showToast('Malaria meds discounted in all partner pharmacies', 'success')">View offer →</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right side: pharmacy spotlight ads -->
            <div class="col-lg-5">
                <div class="mb-3"><span class="badge bg-primary-soft text-primary py-2 px-3 rounded-pill"><i class="fas fa-store me-1"></i> Partner Spotlight</span></div>
                <div class="pharmacy-ad-card rounded-4 p-4 mb-3 shadow-sm">
                    <div class="d-flex justify-content-between">
                        <img src="https://via.placeholder.com/60x60?text=Guardian+Pharmacy" class="rounded-circle bg-light p-2" style="width: 60px; height: 60px; object-fit: contain;">
                        <span class="discount-chip bg-success text-white">⭐ 4.9 Verified</span>
                    </div>
                    <h5 class="fw-bold mt-2">Guardian Health Pharmacy</h5>
                    <p class="small text-secondary"><i class="fas fa-location-dot me-1"></i> Kampala Road + Free delivery</p>
                    <div class="d-flex justify-content-between border-top pt-2 mt-2">
                        <span><i class="fas fa-tag text-primary"></i> 20% off on diabetes meds</span>
                        <a href="#" onclick="showToast('Guardian Pharmacy promo activated', 'success')" class="text-decoration-none">Grab <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
                <div class="pharmacy-ad-card rounded-4 p-4 shadow-sm">
                    <div class="d-flex justify-content-between">
                        <i class="fas fa-truck-fast fa-2x text-primary"></i>
                        <span class="discount-chip">Flash sale</span>
                    </div>
                    <h5 class="fw-bold mt-2"><i class="fas fa-bolt text-warning"></i> MedPlus Express</h5>
                    <p class="small">Order before 2PM and get same-day delivery within Kampala. Free for first order.</p>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: 68%;"></div>
                    </div>
                    <small class="text-muted">68% of stock ready for instant dispatch</small>
                    <button class="btn btn-outline-primary rounded-pill w-100 mt-3 btn-sm" onclick="showToast('MedPlus Express: free delivery applied on checkout', 'success')">Order now <i class="fas fa-arrow-right ms-1"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner ad row (pharmacy of the week) -->
    <div class="container my-2">
        <div class="bg-gradient rounded-4 p-3 px-4 d-flex flex-wrap align-items-center justify-content-between" style="background: linear-gradient(115deg, #eef2ff, #ffffff); border: 1px solid #dee5ed;">
            <div><i class="fas fa-prescription-bottle-alt fa-2x text-primary me-2"></i> <strong class="fs-5">Pharmacy of the week: City Care Chemist</strong> – 10% off on prescription meds & free mask</div>
            <button class="btn btn-sm btn-primary rounded-pill mt-2 mt-sm-0" onclick="showToast('City Care discount active until Sunday', 'info')">Redeem &rarr;</button>
        </div>
    </div>

    <!-- Search Results (if any query from backend) -->
    @if (!empty($query) && isset($results))
    <div class="container py-5">
        <div class="section-title mb-4 text-center">
            <span class="badge bg-primary-soft text-primary px-4 py-2 rounded-pill"><i class="fas fa-search me-1"></i> Search Results</span>
            <h2 class="display-6 fw-bold mt-2">Results for “{{ $query }}”</h2>
        </div>
        <div class="modern-table-card">
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Pharmacy</th>
                            <th>Price (UGX)</th>
                            <th>Stock</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($results) > 0)
                        @foreach ($results->groupBy('pharmacy_name') as $pharmacyName => $meds)
                        <tr class="bg-light-50">
                            <td colspan="5" class="fw-bold text-primary pt-3"><i class="fas fa-store me-2"></i>{{ $pharmacyName }} • {{ $meds->first()->pharmacy_location ?? 'Kampala' }}</td>
                        </tr>
                        @foreach ($meds as $item)
                        <tr>
                            <td><span class="fw-semibold">{{ $item->medicine_name }}</span></td>
                            <td>{{ $pharmacyName }}</td>
                            <td>{{ number_format((float)($item->price ?? 0), 0) }} UGX</td>
                            <td>@if((int)($item->quantity) > 0) <span class="badge-stock"><i class="fas fa-check-circle"></i> {{ $item->quantity }} packs</span> @else <span class="badge bg-light text-danger">Out of stock</span> @endif</td>
                            <td>
                                <form method="post" action="/reserve/{{ $item->id }}">
                                    @csrf
                                    <button class="btn btn-sm btn-primary rounded-pill px-3"><i class="fas fa-hand-holding-heart"></i> Reserve</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted"><i class="fas fa-database me-2"></i> No exact matches found. Try adding alternative medicine names.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Trusted Pharmacies Gallery -->
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold"><i class="fas fa-hand-holding-medical text-primary me-2"></i>Trusted Pharmacies Near You</h2>
            <p class="text-secondary">Verified partners across Uganda — realtime stock</p>
        </div>
        <div class="row g-3 justify-content-center" id="pharmacyList">
            @php
            $fakePharmacies = [
            ['name'=>'Guardian Health Pharmacy','location'=>'Kampala Road'],
            ['name'=>'MedPlus Pharmacy','location'=>'Wandegeya'],
            ['name'=>'City Care Chemist','location'=>'Najjanankumbi'],
            ['name'=>'Royal Drug Centre','location'=>'Ntinda'],
            ['name'=>'Jubilee Pharmacy','location'=>'Muyenga'],
            ['name'=>'St. Francis Medicals','location'=>'Entebbe'],
            ['name'=>'Lake Victoria Chemist','location'=>'Jinja']
            ];
            @endphp
            @foreach ($fakePharmacies as $pharmacy)
            <div class="col-md-2 col-6">
                <div class="pharmacy-pill-modern text-center p-3">
                    <i class="fas fa-shield-alt fs-2 d-block mb-2 text-primary"></i>
                    <div class="fw-semibold">{{ $pharmacy['name'] }}</div>
                    <small class="text-muted"><i class="fas fa-location-dot me-1"></i>{{ $pharmacy['location'] }}</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- About + Features section -->
    <div class="container py-5" id="about">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 wow fadeInLeft">
                <img src="https://images.pexels.com/photos/40568/medical-appointment-doctor-healthcare-40568.jpeg?auto=compress&cs=tinysrgb&w=800" class="img-fluid rounded-4 shadow-lg" style="object-fit: cover; height: 450px; width: 100%;" alt="pharmacy care">
            </div>
            <div class="col-lg-6 wow fadeInRight">
                <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill mb-2">Why MedFinder?</span>
                <h2 class="display-6 fw-bold mt-2">Your health, simplified.</h2>
                <p class="lead text-secondary">Real-time stock visibility, fair pricing, and seamless reservation system.</p>
                <div class="row mt-4">
                    <div class="col-6 mb-3"><i class="fas fa-check-circle text-primary me-2"></i> <strong>Live availability</strong><br><small class="text-muted">Updated every 2h</small></div>
                    <div class="col-6 mb-3"><i class="fas fa-clock text-primary me-2"></i> <strong>Fast reservation</strong><br><small class="text-muted">Hold 4h, pay at pickup</small></div>
                    <div class="col-6 mb-3"><i class="fas fa-shield-alt text-primary me-2"></i> <strong>Verified network</strong><br><small class="text-muted">Licensed providers</small></div>
                    <div class="col-6 mb-3"><i class="fas fa-chart-line text-primary me-2"></i> <strong>Price transparency</strong><br><small class="text-muted">Compare before you go</small></div>
                </div>
                <a href="#" class="btn btn-primary rounded-pill px-5 py-2 mt-2">Explore How It Works →</a>
            </div>
        </div>
    </div>

    <!-- Quick Search Modal -->
    <div class="modal fade" id="quickSearchModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-body p-4">
                    <input type="text" id="quickGlobalSearch" class="form-control form-control-lg rounded-pill" placeholder="Search for any medicine...">
                    <button class="btn btn-primary rounded-pill w-100 mt-3" id="quickSearchExec"><i class="fas fa-search me-2"></i>Search</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast container -->
    <div class="toast-container-custom" id="toastRoot"></div>
</div>

<!-- Back to top button -->
<a href="#" id="backToTop" class="back-to-top"><i class="fas fa-arrow-up"></i></a>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
<script>
    // Spinner hide on load
    window.addEventListener('load', function() {
        let spinner = document.getElementById('spinnerOverlay');
        if (spinner) {
            spinner.style.opacity = '0';
            setTimeout(() => {
                if (spinner) spinner.style.display = 'none';
            }, 400);
        }
        new WOW().init();
    });

    // Toast notification system
    function showToast(message, type = 'info') {
        const container = document.getElementById('toastRoot');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = `toast-notify`;
        toast.style.borderLeftColor = type === 'error' ? '#dc3545' : (type === 'success' ? '#198754' : '#0b5ed7');
        toast.innerHTML = `<div class="d-flex justify-content-between align-items-center">
            <strong>${type === 'error' ? '⚠️' : '✨'} ${type.toUpperCase()}</strong>
            <button class="btn-close btn-sm" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
        <div class="mt-1">${message}</div>`;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 4200);
    }

    // Escape HTML
    function escapeHtml(str) {
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    // Hero section item management
    function addHeroItem() {
        const input = document.getElementById('heroItemInput');
        let val = input.value.trim();
        if (!val) {
            showToast('Please enter a medicine name', 'error');
            input.focus();
            return;
        }
        const listDiv = document.getElementById('heroEditableItemList');
        const wrap = document.createElement('div');
        wrap.className = 'hero-tag-item';
        wrap.innerHTML = `<span>${escapeHtml(val)}</span><i class="fas fa-times-circle text-danger ms-1" style="cursor:pointer;" onclick="this.parentElement.remove(); updateHeroSearchBtn()"></i><input type="hidden" name="hero_item_names[]" value="${escapeHtml(val)}">`;
        listDiv.appendChild(wrap);
        input.value = '';
        updateHeroSearchBtn();
        showToast(`➕ ${val} added`, 'success');
    }

    function updateHeroSearchBtn() {
        const list = document.getElementById('heroEditableItemList');
        const btn = document.getElementById('heroSearchBtn');
        if (btn) btn.style.display = (list.children.length > 0) ? 'inline-block' : 'none';
    }

    // Main search item management
    function addItem() {
        const input = document.getElementById('itemInput');
        let val = input.value.trim();
        if (!val) {
            showToast('Please enter medicine name', 'error');
            return;
        }
        const mainList = document.getElementById('editableItemList');
        const wrap = document.createElement('div');
        wrap.className = 'tag-item';
        wrap.innerHTML = `<span>${escapeHtml(val)}</span><i class="fas fa-times-circle text-danger" style="cursor:pointer;" onclick="this.parentElement.remove(); updateSearchBtn()"></i><input type="hidden" name="item_names[]" value="${escapeHtml(val)}">`;
        mainList.appendChild(wrap);
        input.value = '';
        updateSearchBtn();
        showToast(`✓ ${val} added to search list`, 'success');
    }

    function updateSearchBtn() {
        const list = document.getElementById('editableItemList');
        const btn = document.getElementById('searchBtn');
        if (btn) btn.style.display = (list.children.length > 0) ? 'flex' : 'none';
        const form = document.getElementById('searchForm');
        if (form && list.children.length) {
            let existHidden = form.querySelectorAll('input[name="item_names[]"]');
            existHidden.forEach(el => el.remove());
            let items = list.querySelectorAll('input[type="hidden"]');
            items.forEach(inp => form.appendChild(inp.cloneNode(true)));
        }
    }

    // Counter animation
    function animateCounterEl(el, target) {
        let current = 0;
        let step = Math.ceil(target / 45);
        let interval = setInterval(() => {
            current += step;
            if (current >= target) {
                el.innerText = target.toLocaleString();
                clearInterval(interval);
            } else el.innerText = current.toLocaleString();
        }, 20);
    }

    // DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        // Animate counters
        if (document.getElementById('medCount')) animateCounterEl(document.getElementById('medCount'), 2480);
        if (document.getElementById('pharCount')) animateCounterEl(document.getElementById('pharCount'), 186);
        if (document.getElementById('stockCount')) animateCounterEl(document.getElementById('stockCount'), 58200);

        // Back to top button
        const backBtn = document.getElementById('backToTop');
        if (backBtn) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 400) backBtn.classList.add('show');
                else backBtn.classList.remove('show');
            });
            backBtn.addEventListener('click', (e) => {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Quick search modal
        const quickSearchBtn = document.getElementById('quickSearchExec');
        if (quickSearchBtn) quickSearchBtn.addEventListener('click', () => {
            let val = document.getElementById('quickGlobalSearch')?.value;
            if (val) showToast(`Searching for "${val}"…`, 'info');
        });

        updateSearchBtn();
        updateHeroSearchBtn();

        // Hero search button - transfer items to main form
        const heroSearchBtn = document.getElementById('heroSearchBtn');
        if (heroSearchBtn) {
            heroSearchBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const heroList = document.getElementById('heroEditableItemList');
                const mainList = document.getElementById('editableItemList');
                const heroItems = heroList.querySelectorAll('input[type="hidden"]');
                heroItems.forEach(inp => {
                    let value = inp.value;
                    const wrap = document.createElement('div');
                    wrap.className = 'tag-item';
                    wrap.innerHTML = `<span>${escapeHtml(value)}</span><i class="fas fa-times-circle text-danger" style="cursor:pointer;" onclick="this.parentElement.remove(); updateSearchBtn()"></i><input type="hidden" name="item_names[]" value="${escapeHtml(value)}">`;
                    mainList.appendChild(wrap);
                });
                heroList.innerHTML = '';
                updateHeroSearchBtn();
                document.getElementById('searchSection').scrollIntoView({
                    behavior: 'smooth'
                });
                updateSearchBtn();
                showToast('Items transferred to search panel below', 'success');
            });
        }
    });
</script>
@endsection