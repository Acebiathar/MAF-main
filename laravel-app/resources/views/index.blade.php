@extends('layouts.app')

@section('title', 'Home | Medfinder')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link rel="preload" as="image" href="https://images.pexels.com/photos/4386467/pexels-photo-4386467.jpeg?auto=compress&cs=tinysrgb&w=1200">

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<style>
    :root {
        --primary: #0b5ed7;
        --primary-dark: #0a58ca;
        --primary-soft: #f0f7ff;
        --secondary: #00b4aa;
        --dark: #0f172a;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --slate-600: #475569;
        --slate-700: #334155;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8fafc;
        min-height: 100vh;
    }

    nav,
    .landing-page nav,
    body nav {
        position: relative !important;
        top: auto !important;
        left: auto !important;
        width: 100% !important;
        z-index: 50 !important;
        background-color: #ffffff !important;
        border-bottom: 1px solid var(--slate-200) !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) !important;
    }

    nav div,
    .landing-page nav div {
        background-color: transparent !important;
    }

    nav a,
    nav span,
    nav text,
    nav ul li a,
    .landing-page nav a,
    .landing-page nav span {
        color: #1e293b !important;
    }

    nav a span,
    nav ul li a[aria-current="page"] {
        color: #0b5ed7 !important;
    }

    nav ul li a:hover,
    .landing-page nav ul li a:hover {
        color: #0a58ca !important;
    }

    nav button,
    nav button i {
        color: #1e293b !important;
    }

    .hero-carousel .carousel-item {
        min-height: 80vh;
        background-size: cover;
        background-position: center;
        position: relative;
        display: flex;
        align-items: center;
    }

    @media (max-width: 768px) {
        .hero-carousel .carousel-item {
            min-height: 60vh;
            padding: 2rem 0;
            background-position: center top;
            background-size: cover;
        }
    }

    .hero-carousel .carousel-item::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.75) 50%, rgba(15, 23, 42, 0.4) 100%);
        z-index: 1;
    }

    .carousel-caption {
        z-index: 2;
        text-align: left;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        padding: 2rem 0;
        display: flex;
        align-items: center;
    }

    @media (max-width: 576px) {
        .carousel-caption {
            padding: 1rem 0;
        }
    }

    .search-card-wrapper {
        background: rgba(30, 41, 59, 0.7);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        max-width: 720px;
        width: 100%;
    }

    .search-input-field:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    .tag-item {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #ffffff;
        padding: 0.4rem 0.8rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition);
    }

    .tag-item:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    .spinner-overlay {
        position: fixed;
        inset: 0;
        background: #ffffff;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.4s ease, visibility 0.4s ease;
    }

    .stat-box {
        background: #ffffff;
        padding: 2rem 1.5rem;
        border-radius: 1.25rem;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05);
        border: 1px solid var(--slate-200);
        text-align: center;
        transition: var(--transition);
        height: 100%;
    }

    .stat-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 30px -10px rgba(15, 23, 42, 0.1);
    }

    .glass-card {
        background: #ffffff;
        border-radius: 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.01);
        border: 1px solid var(--slate-200);
    }

    .badge-stock {
        background-color: rgba(16, 185, 129, 0.1);
        color: #047857;
        padding: 0.4rem 0.8rem;
        border-radius: 50px;
        font-size: 0.825rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .ad-card {
        background: #ffffff;
        border: 1px solid var(--slate-200);
        border-radius: 1.25rem;
        padding: 1.75rem;
        height: 100%;
        transition: var(--transition);
    }

    .ad-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.1);
    }

    .object-cover {
        object-fit: cover;
    }

    .crypto-card {
        background: white;
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid var(--slate-200);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .crypto-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.12);
    }

    .icon-wrapper {
        width: 3.5rem;
        height: 3.5rem;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .icon-wrapper i {
        font-size: 1.5rem;
        color: white;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.75rem;
    }

    .card-description {
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        flex-grow: 1;
    }

    .learn-link {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        color: #2563eb;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }

    .learn-link:hover {
        gap: 0.5rem;
        color: #1d4ed8;
    }

    .gradient-text {
        background: linear-gradient(135deg, #0b5ed7, #00b4aa);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.5rem;
        margin-top: 3rem;
    }

    @media (min-width: 768px) {
        .cards-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }
    }

    .section-container {
        max-width: 1240px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 2rem;
        padding: 4rem 2rem;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--slate-200);
    }

    .trust-badges {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 2rem;
        margin-top: 3.5rem;
        padding-top: 2rem;
        border-top: 1px solid var(--slate-200);
    }

    .badge-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--slate-600);
        font-weight: 500;
    }

    .badge-item i {
        font-size: 1.1rem;
        color: #10b981;
    }

    #toastRoot {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 10000;
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-width: 350px;
        width: 100%;
    }

    .toast-notify {
        background: #ffffff;
        border-radius: 1rem;
        padding: 1rem;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.15);
        border-left: 4px solid var(--primary);
        animation: slideIn 0.3s ease forwards;
    }

    /* CUSTOM STYLING FOR INTERACTIVE MAP MAP ROWS */
    .map-sidebar-row {
        cursor: pointer;
        transition: var(--transition);
        border-left: 3px solid transparent;
    }

    .map-sidebar-row:hover {
        background-color: var(--slate-100);
        border-left-color: var(--primary);
    }

    @keyframes slideIn {
        from {
            transform: translateX(120%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
@endsection

@section('fullwidth')
<div class="landing-page">
    <div id="toastRoot"></div>

    <div id="spinnerOverlay" class="spinner-overlay">
        <div class="text-center">
            <div class="spinner-border text-primary" style="width: 3.5rem; height: 3.5rem; border-width: 3px;"></div>
            <p class="mt-3 fw-bold text-dark tracking-wide">Securing Live Pharmacy Networks...</p>
        </div>
    </div>

    <div id="heroCarousel" class="carousel slide hero-carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('https://images.pexels.com/photos/4386467/pexels-photo-4386467.jpeg?auto=compress&cs=tinysrgb&w=1600');">
                <div class="carousel-caption">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <span class="badge bg-primary px-3 py-2 rounded-pill text-uppercase mb-3 tracking-wider fw-semibold" style="font-size: 0.75rem;">Verified Stocks Only</span>
                                <h1 class="display-4 fw-bold text-white mb-3" style="letter-spacing: -0.02em; line-height: 1.2;">Find Prescriptions Near You, Instantly.</h1>
                                <p class="lead text-white-50 mb-4" style="max-width: 600px;">Unified platform mapping local medical stock configurations directly to real-time consumer and emergency needs across Uganda.</p>

                                <div class="search-card-wrapper position-relative overflow-hidden p-4 p-md-4">
                                    <div class="position-absolute" style="width: 150px; height: 150px; background: radial-gradient(circle, rgba(0, 180, 170, 0.25) 0%, rgba(0,0,0,0) 70%); top: -50px; right: -50px; pointer-events: none;"></div>

                                    <div class="position-relative z-3">
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <div class="d-flex align-items-center justify-content-center bg-info bg-opacity-10 rounded-3" style="width: 44px; height: 44px; border: 1px solid rgba(0, 180, 170, 0.25);">
                                                <i class="bi bi-search text-info fs-5"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-white fw-bold h5 mb-0" style="letter-spacing: -0.01em;">Search For Your Medicine</h3>
                                                <p class="text-white-50 mb-0" style="font-size: 0.8rem;">Type medication criteria name and press Enter to accumulate items into criteria tag matrices.</p>
                                            </div>
                                        </div>


                                        @if($errors->any())
                                            <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                                        @endif
                                        <form id="searchForm" action="{{ url('/') }}" method="GET" class="m-0">
                                            <div class="input-group bg-white rounded-4 p-2 border border-white border-2 shadow">
                                                <span class="input-group-text border-0 bg-transparent ps-3 pe-2">
                                                    <i class="bi bi-capsule" style="font-size: 1.1rem; color: #64748b;"></i>
                                                </span>

                                                <input type="text"
                                                    name="search"
                                                    id="itemInput"
                                                    value="{{ $searchQuery }}"
                                                    class="form-control border-0 bg-transparent text-dark px-2 py-3 search-input-field"
                                                    placeholder="Enter prescription criteria (e.g., Panadol, Amoxicillin)..."
                                                    style="font-size: 0.95rem; font-weight: 500; letter-spacing: -0.01em;">

                                                <button type="submit" id="searchBtn"
                                                    class="btn btn-primary rounded-4 px-4 fw-bold text-uppercase tracking-wider d-flex align-items-center gap-2"
                                                    style="font-size: 0.8rem; background: linear-gradient(135deg, var(--primary) 0%, #0a58ca 100%); border: none;">
                                                    <span>Search</span>
                                                    <i class="bi bi-arrow-right-short" style="font-size: 1.2rem;"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="container" style="margin-top: -30px; position: relative; z-index: 10;">
        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-4">
                <div class="stat-box">
                    <h3 class="display-6 fw-bold text-dark mb-1" id="medCount">0</h3>
                    <p class="text-muted small fw-semibold mb-0">Active Medical Catalogs</p>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-box">
                    <h3 class="display-6 fw-bold text-primary mb-1" id="pharCount">0</h3>
                    <p class="text-muted small fw-semibold mb-0">Verified Pharmacy Nodes</p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="stat-box">
                    <h3 class="display-6 fw-bold text-success mb-1" id="stockCount">0</h3>
                    <p class="text-muted small fw-semibold mb-0">Tracked Physical Stock Units</p>
                </div>
            </div>
        </div>
    </section>

    @if($searchQuery !== '' && $results->isEmpty())
    <div class="container mt-5">
        <div class="alert alert-warning text-center shadow-sm border-0 rounded-4 p-4">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 text-warning align-middle"></i>
            <span class="fw-medium">No medicines matching "{{ $searchQuery }}" were found at approved pharmacies. Try another name or check the spelling.</span>
        </div>
    </div>
    @endif

    {{-- INTERACTIVE LIVE DASHBOARD: INTEGRATED MAP AND LIST SPLIT VIEW --}}
    @if(isset($results) && $results->isNotEmpty())
    <div class="container py-5">
        <div class="glass-card overflow-hidden">
            <div class="p-4 bg-white border-bottom border-light d-flex flex-column flex-sm-row gap-3 align-items-sm-center justify-content-between">
                <h3 class="fw-bold h5 mb-0 text-dark"><i class="bi bi-map-fill me-2 text-primary"></i> Live Availability Records Map</h3>
                <div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-medium" style="font-size: 0.8rem;">Prioritized by Location Proximity</span>
                </div>
            </div>

            <div class="row g-0">
                <div class="col-lg-7 col-md-6 position-relative" style="min-height: 500px;">
                    <div id="map" style="position: absolute; inset: 0; width: 100%; height: 100%; z-index: 10;"></div>
                </div>

                <div class="col-lg-5 col-md-6 bg-white border-start" style="max-height: 500px; overflow-y: auto;">
                    <div class="list-group list-group-flush">
                        @foreach ($results as $pharmacy)
                        @foreach ($pharmacy->medicines as $medicine)
                        <div class="list-group-item p-3 map-sidebar-row"
                            data-lat="{{ $pharmacy->latitude ?? '0.3476' }}"
                            data-lng="{{ $pharmacy->longitude ?? '32.5825' }}"
                            data-pharmacy="{{ $pharmacy->name }}"
                            data-medicine="{{ $medicine->name }}"
                            data-price="{{ number_format($medicine->pivot->price ?? 0, 0) }} UGX"
                            data-quantity="{{ $medicine->pivot->quantity ?? 0 }}">

                            <div class="medicine-result-summary">
                                <h4 class="h6 fw-bold text-dark mb-3">{{ $medicine->name }}</h4>
                                <dl class="row g-2 small mb-0">
                                    <dt class="col-4 text-muted fw-normal">Pharmacy</dt>
                                    <dd class="col-8 fw-semibold text-primary mb-0">{{ $pharmacy->name }}</dd>
                                    <dt class="col-4 text-muted fw-normal">Location</dt>
                                    <dd class="col-8 mb-0">{{ $pharmacy->location ?? $pharmacy->pharmacy_location ?? 'Location not provided' }}</dd>
                                    <dt class="col-4 text-muted fw-normal">Price</dt>
                                    <dd class="col-8 fw-semibold mb-0">UGX {{ number_format($medicine->pivot->price ?? 0, 0) }}</dd>
                                    <dt class="col-4 text-muted fw-normal">Quantity</dt>
                                    <dd class="col-8 mb-0">{{ number_format($medicine->pivot->quantity ?? 0) }} units</dd>
                                    <dt class="col-4 text-muted fw-normal">Availability</dt>
                                    <dd class="col-8 mb-0">
                                        @if(($medicine->pivot->quantity ?? 0) > 0)
                                            <span class="badge bg-success-subtle text-success rounded-pill"><i class="bi bi-check-circle me-1" aria-hidden="true"></i>In Stock</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger rounded-pill"><i class="bi bi-x-circle me-1" aria-hidden="true"></i>Out of Stock</span>
                                        @endif
                                    </dd>
                                </dl>
                            </div>

                            <div class="d-flex justify-content-end align-items-center mt-3 pt-3 border-top border-light">
                                <div>
                                    @if(isset($currentUser) && $currentUser->role === 'patient')
                                    <form action="{{ url('/reserve/' . ($medicine->pivot->id ?? $medicine->id)) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-primary rounded-pill px-3 py-1 shadow-sm fw-bold transition" style="font-size: 0.75rem;" @disabled(($medicine->pivot->quantity ?? 0) <= 0)>
                                            <i class="bi bi-shield-lock-fill me-1"></i>Reserve
                                        </button>
                                    </form>
                                    @elseif(isset($currentUser))
                                    <span class="text-muted small" style="font-size: 0.7rem;"><i class="bi bi-person-x"></i> Provider Acc</span>
                                    @else
                                    <a href="{{ route('login') }}" class="btn btn-xs btn-dark rounded-pill px-3 py-1 fw-bold text-uppercase tracking-wider transition" style="font-size: 0.7rem;">
                                        Login <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>

                        </div>
                        @endforeach
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
    @endif

    <section class="py-5 bg-white mt-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold h4 mb-0">Featured Partner <span class="text-primary">Facilities & Nodes</span></h2>
                <div class="carousel-controls">
                    <button class="btn btn-outline-primary btn-sm rounded-circle p-2 px-3 me-1" type="button" data-bs-target="#adCarousel" data-bs-slide="prev">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="btn btn-outline-primary btn-sm rounded-circle p-2 px-3" type="button" data-bs-target="#adCarousel" data-bs-slide="next">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div id="adCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="5000">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="ad-card d-flex flex-column justify-content-between">
                                    <div class="mb-3">
                                        <span class="badge bg-danger bg-opacity-10 text-danger mb-3 px-2.5 py-1.5 rounded fw-semibold">Hospital Referral Hub</span>
                                        <h4 class="fw-bold h5 mb-2">Mulago National Referral</h4>
                                        <p class="text-muted small mb-0">Specialized care and 24/7 emergency infrastructure networks across central regions.</p>
                                    </div>
                                    <img src="{{ asset('images/pharmacy3.jpg') }}" alt="Mulago Layout" class="img-fluid rounded object-cover" style="height: 160px; width: 100%;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ad-card d-flex flex-column justify-content-between">
                                    <div class="mb-3">
                                        <span class="badge bg-danger bg-opacity-10 text-danger mb-3 px-2.5 py-1.5 rounded fw-semibold">Clinical Infrastructure</span>
                                        <h4 class="fw-bold h5 mb-2">Case Medical Centre</h4>
                                        <p class="text-muted small mb-0">Quality professional healthcare configurations optimized for family networks.</p>
                                    </div>
                                    <img src="{{ asset('images/pharmacy.jpg') }}" alt="Case Layout" class="img-fluid rounded object-cover" style="height: 160px; width: 100%;">
                                </div>
                            </div>
                            <div class="col-md-4 d-none d-md-block">
                                <div class="ad-card border-primary border d-flex flex-column justify-content-between">
                                    <div class="mb-3">
                                        <span class="badge bg-primary mb-3 px-2.5 py-1.5 rounded fw-semibold">Promoted Drug Allocation</span>
                                        <h4 class="fw-bold h5 mb-2">Panadol Extra</h4>
                                        <p class="text-muted small mb-0">Fast-acting advanced chemical composition relief tracking widespread local demands.</p>
                                    </div>
                                    <img src="{{ asset('images/drugs.jpg') }}" class="img-fluid rounded object-cover" style="height: 160px; width: 100%;" alt="Panadol Extra Allocation">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item" data-bs-interval="5000">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="ad-card d-flex flex-column justify-content-between">
                                    <div class="mb-3">
                                        <span class="badge bg-success bg-opacity-10 text-success mb-3 px-2.5 py-1.5 rounded fw-semibold">Network Partner Node</span>
                                        <h4 class="fw-bold h5 mb-2">Green Ridge Clinic</h4>
                                        <p class="text-muted small mb-0">Community care hub with rapid stock matching and patient referral support.</p>
                                    </div>
                                    <img src="{{ asset('images/pharmacy3.jpg') }}" alt="Green Ridge Clinic" class="img-fluid rounded object-cover" style="height: 160px; width: 100%;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ad-card d-flex flex-column justify-content-between">
                                    <div class="mb-3">
                                        <span class="badge bg-info bg-opacity-10 text-info mb-3 px-2.5 py-1.5 rounded fw-semibold">Regional Pharmacy</span>
                                        <h4 class="fw-bold h5 mb-2">Solar Care Pharmacy</h4>
                                        <p class="text-muted small mb-0">Verified pharmaceutical outlet built for secure and fast medication handoff.</p>
                                    </div>
                                    <img src="{{ asset('images/pharmacy.jpg') }}" alt="Solar Care Pharmacy" class="img-fluid rounded object-cover" style="height: 160px; width: 100%;">
                                </div>
                            </div>
                            <div class="col-md-4 d-none d-md-block">
                                <div class="ad-card d-flex flex-column justify-content-between">
                                    <div class="mb-3">
                                        <span class="badge bg-warning bg-opacity-10 text-warning mb-3 px-2.5 py-1.5 rounded fw-semibold">Logistics Node</span>
                                        <h4 class="fw-bold h5 mb-2">Medicortex Hub</h4>
                                        <p class="text-muted small mb-0">Integrated stock and delivery coordination for high-demand pharmaceutical items.</p>
                                    </div>
                                    <img src="{{ asset('images/drugs.jpg') }}" class="img-fluid rounded object-cover" style="height: 160px; width: 100%;" alt="Medicortex Hub">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="section-container">
            <div class="text-center" style="max-width: 700px; margin: 0 auto 3rem;">
                <span style="display: inline-flex; align-items: center; gap: 0.5rem; background-color: rgba(13, 148, 136, 0.1); padding: 6px 16px; border-radius: 50px; color: #0d9488; font-size: 0.8rem; font-weight: 600; text-uppercase: tracking-wider;">
                    <i class="bi bi-capsule-hd-fill"></i> Medicine Availability Finder
                </span>
                <h2 style="font-size: 2.25rem; font-weight: 800; color: #0f172a; margin-top: 1rem; line-height: 1.3;">
                    The most trusted <span class="gradient-text">prescription tracking</span> platform
                </h2>
                <p style="color: #64748b; font-size: 1.05rem; margin-top: 0.75rem;">
                    Discover how MedFinder eliminates unnecessary physical journeys to locate vital healthcare supplies across communities.
                </p>
            </div>

            <div class="cards-grid">
                <div class="crypto-card">
                    <div class="icon-wrapper">
                        <i class="bi bi-search-heart"></i>
                    </div>
                    <h3 class="card-title">Real-time lookup</h3>
                    <p class="card-description">
                        Search individual formulas or system item lists. Our algorithm normalizes entry data and checks against active database registers instantly.
                    </p>
                    <a href="#" class="learn-link feature-detail-btn" data-feature="Dynamic Search Matrix">
                        <span>Learn how stock queries operate</span>
                        <i class="bi bi-arrow-right-short"></i>
                    </a>
                </div>

                <div class="crypto-card">
                    <div class="icon-wrapper">
                        <i class="bi bi-building-check"></i>
                    </div>
                    <h3 class="card-title">Verified pharmacies</h3>
                    <p class="card-description">
                        Every partner pharmacy remains locked in a pending administrative state until credentials and operating certificates are officially approved.
                    </p>
                    <a href="#" class="learn-link feature-detail-btn" data-feature="Real-Time Verification">
                        <span>Learn about licensing reviews</span>
                        <i class="bi bi-arrow-right-short"></i>
                    </a>
                </div>

                <div class="crypto-card">
                    <div class="icon-wrapper">
                        <i class="bi bi-bookmark-check-fill"></i>
                    </div>
                    <h3 class="card-title">Secure ticket holds</h3>
                    <p class="card-description">
                        Reserve matching assets securely. Confirmed tickets execute transactional counters, preventing dual-allocation and stock hoarding.
                    </p>
                    <a href="#" class="learn-link feature-detail-btn" data-feature="Secure Allocation Locks">
                        <span>How reservations safeguard supply</span>
                        <i class="bi bi-arrow-right-short"></i>
                    </a>
                </div>
            </div>

            <div class="trust-badges">
                <div class="badge-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>All Regions of Uganda</span>
                </div>
                <div class="badge-item">
                    <i class="bi bi-building"></i>
                    <span>Licensed Stores Linked</span>
                </div>
                <div class="badge-item">
                    <i class="bi bi-lightning-fill"></i>
                    <span>Live Inventory Metrics</span>
                </div>
                <div class="badge-item">
                    <i class="bi bi-telephone-outbound-fill"></i>
                    <span>24/7 Support Hotline</span>
                </div>
            </div>
        </div>
    </div>

    {{-- BOTTOM CALL TO ACTION BLOCK WITH POPUP CAPABILITIES --}}
    <div class="container py-4">
        <div class="p-5 bg-white border border-light shadow-sm rounded-4 position-relative overflow-hidden text-center">
            <div class="position-absolute top-0 end-0 w-25 h-100 bg-primary opacity-5 rounded-circle blur-3xl" style="transform: translate(30%, -30%);"></div>
            <div class="position-absolute bottom-0 start-0 w-25 h-100 bg-info opacity-5 rounded-circle blur-3xl" style="transform: translate(-30%, 30%);"></div>

            <div class="position-relative z-3" style="max-width: 650px; margin: 0 auto;">
                <div class="d-inline-flex align-items-center gap-1.5 bg-primary bg-opacity-10 text-primary text-xs fw-bold px-3 py-2 rounded-pill mb-4">
                    <i class="bi bi-heart-pulse-fill text-danger"></i> <span>Instant Access • Uganda Nationwide</span>
                </div>
                <h2 class="mb-3 fw-extrabold text-dark tracking-tight h1">Find Your Prescriptions Instantly</h2>
                <p class="mb-4 text-muted">
                    Access verified pharmaceutical inventories across Uganda. Locate, confirm, and secure your essential medication without delay today.
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center align-items-center">
                    <button type="button" id="bottomTriggerPromptModalBtn" class="btn btn-primary px-4 py-3 rounded-3 fw-bold d-inline-flex align-items-center gap-2 w-100 w-sm-auto justify-content-center shadow-sm">
                        <i class="bi bi-search-heart fs-5"></i> Looking for meds?
                    </button>
                    <a href="{{ url('/about#hero') }}" class="btn btn-outline-secondary px-4 py-3 rounded-3 fw-bold d-inline-flex align-items-center gap-2 w-100 w-sm-auto justify-content-center bg-light text-dark border-light shadow-sm">
                        <i class="bi bi-building-add"></i> View our story
                    </a>
                </div>
                <div class="mt-4 d-flex justify-content-center flex-wrap gap-4 text-muted" style="font-size: 0.8rem;">
                    <span class="d-flex align-items-center gap-1"><i class="bi bi-check-circle-fill text-success"></i> Verified Nodes</span>
                    <span class="d-flex align-items-center gap-1"><i class="bi bi-clock-history"></i> 24/7 Live Sync</span>
                    <span class="d-flex align-items-center gap-1"><i class="bi bi-shield-check"></i> Standard Security Secure</span>
                </div>
            </div>
        </div>
    </div>

    {{-- FIXED: INTEGRATED INLINE SEARCH PROMPT MODAL FROM ABOUT TEMPLATE LAYER --}}
    <div id="searchPromptModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 1050; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.25s ease;">
        <div class="bg-white rounded-4 p-4 position-relative shadow-lg border" style="width: 100%; max-width: 460px; margin: 20px;">
            <button id="closePromptBtn" type="button" class="btn-close position-absolute top-0 end-0 m-3 shadow-none bg-transparent text-muted fs-5 border-0" aria-label="Close" style="cursor: pointer; font-family: Arial, sans-serif; line-height: 1;">&times;</button>

            <div class="text-center pt-2">
                <div class="d-flex align-items-center justify-content-center gap-2 text-primary fw-bold mb-3">
                    <i class="bi bi-heart-pulse-fill text-primary fs-4"></i>
                    <span class="fs-5 tracking-tight text-dark" style="font-family: 'Inter', sans-serif;">MedFinder</span>
                </div>
                <h3 class="fw-extrabold text-dark h4 mb-4" style="font-family: 'Inter', sans-serif; color: #0f172a; letter-spacing: -0.02em;">What medication do you need?</h3>
            </div>

            <form action="{{ url('/') }}" method="GET" class="mb-3">
                <div class="position-relative mb-3">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 1.1rem;"></i>
                    <input type="text" id="modalItemInput" name="search" class="form-control py-3 ps-5 border rounded-3 bg-light text-dark shadow-none" placeholder="What med are you looking for?" style="font-size: 0.95rem; font-weight: 500;" required>
                </div>
                <button type="submit" class="btn btn-info w-100 py-3 rounded-3 text-dark fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm" style="background-color: #e2f7f5; border: none; transition: all 0.2s;">
                    <i class="bi bi-geo-alt-fill text-muted"></i> Find Your Meds
                </button>
            </form>

            <div class="text-center mt-3 pt-2 border-top border-light">
                <span class="text-dark small fw-semibold" style="text-decoration: underline;">Excellent</span>
                <span class="text-success ms-1" style="letter-spacing: 1px;">
                    <i class="bi bi-star-fill text-success bg-opacity-10"></i>
                    <i class="bi bi-star-fill text-success"></i>
                    <i class="bi bi-star-fill text-success"></i>
                    <i class="bi bi-star-fill text-success"></i>
                    <i class="bi bi-star-half text-success"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="mt-4 d-flex justify-content-center flex-wrap gap-4 text-muted" style="font-size: 0.8rem;">
        <span class="d-flex align-items-center gap-1"><i class="bi bi-check-circle-fill text-success"></i> Verified Nodes</span>
        <span class="d-flex align-items-center gap-1"><i class="bi bi-clock-history"></i> 24/7 Live Sync</span>
        <span class="d-flex align-items-center gap-1"><i class="bi bi-shield-check"></i> Standard Security Secure</span>
    </div>
</div>
<div class="mt-4 d-flex justify-content-center flex-wrap gap-4 text-muted" style="font-size: 0.8rem;">
    <span class="d-flex align-items-center gap-1"><i class="bi bi-check-circle-fill text-success"></i> Verified Nodes</span>
    <span class="d-flex align-items-center gap-1"><i class="bi bi-clock-history"></i> 24/7 Live Sync</span>
    <span class="d-flex align-items-center gap-1"><i class="bi bi-shield-check"></i> Standard Security Secure</span>
</div>
</div>
</div>
</div>

<section class="py-5 mt-4" style="background-color: #f1f5f9; border-top: 1px solid var(--slate-200);">
    <div class="container text-center">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold mb-2">User Feedback</span>
        <h2 style="font-size: 2rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem;">What Our Users Say</h2>
        <p style="color: #64748b; font-size: 1rem; max-width: 600px; margin: 0 auto 3rem;">Discover how we are connecting patients directly with authentic local pharmacies securely.</p>

        <div class="row g-4 justify-content-center text-start">
            @if(isset($testimonials) && count($testimonials) > 0)
            @foreach($testimonials as $testimonial)
            <div class="col-md-6 col-lg-4">
                <div class="h-100 bg-white p-4 rounded-4 border shadow-sm d-flex flex-column justify-content-between transition" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                    <p class="text-secondary italic mb-4" style="font-size: 0.95rem; line-height: 1.6;">
                        "{{ $testimonial['quote'] ?? $testimonial['body'] }}"
                    </p>
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ $testimonial['avatar'] ?? 'https://ui-avatars.com/api/?name='.urlencode($testimonial['name']) }}" alt="{{ $testimonial['name'] }}" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover;">
                        <div>
                            <h4 class="h6 fw-bold text-dark mb-0">{{ $testimonial['name'] }}</h4>
                            <span class="text-primary fw-medium" style="font-size: 0.85rem;">{{ $testimonial['role'] ?? 'Patient' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div class="col-md-6 col-lg-4">
                <div class="h-100 bg-white p-4 rounded-4 border shadow-sm d-flex flex-column justify-content-between">
                    <p class="text-secondary italic mb-4" style="font-size: 0.95rem; line-height: 1.6; font-style: italic;">"Saved me hours driving through Kampala traffic trying to locate rare insulin variants. Perfect platform."</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="fw-bold" style="width: 44px; height: 44px; border-radius: 50%; background: #0b5ed7; color: white; display:flex; align-items:center; justify-content:center;">NK</div>
                        <div>
                            <h4 class="h6 fw-bold text-dark mb-0">Nsubuga Karim</h4>
                            <span class="text-primary fw-medium" style="font-size: 0.85rem;">Verified Patient</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
</div>

<div id="featureModal" class="modal fade" desert-overlay-target tabindex="-1" style="display: none; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); transition: opacity 0.2s ease;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px; width: 100%;">
        <div class="modal-content border-0 shadow-lg rounded-4 p-4" style="background: #ffffff;">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h3 id="modalTitle" class="h4 fw-bold text-dark mb-0">Feature Details</h3>
                <button id="closeModalBtn" type="button" class="btn-close border-0 bg-transparent shadow-none fs-4 p-0 line-none text-muted" aria-label="Close" style="cursor: pointer;">&times;</button>
            </div>
            <p id="modalDescription" class="text-secondary mb-4" style="font-size: 0.95rem; line-height: 1.6;">Detailed capability insights stream setup info text.</p>
            <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3 text-sm d-flex align-items-start gap-2" style="font-size: 0.85rem;">
                <i class="bi bi-info-circle-fill flex-shrink-0 mt-0.5"></i>
                <span>MedFinder syncs allocations in real-time, matching database locks directly to active vendor registers.</span>
            </div>
            <button id="closeModalBtnSecondary" type="button" class="btn btn-light mt-4 w-100 rounded-3 py-2 fw-semibold text-dark border">Close</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    function showToast(message, type = 'info') {
        const container = document.getElementById('toastRoot');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = 'toast-notify';
        toast.style.borderLeftColor = type === 'error' ? '#dc3545' : (type === 'success' ? '#198754' : '#0b5ed7');
        toast.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-1">
                <strong class="text-dark" style="font-size: 0.9rem;">${type === 'error' ? 'Registry Notice' : type === 'success' ? 'Pipeline Action' : 'System Information'}</strong>
                <button class="btn-close btn-sm shadow-none" type="button" aria-label="Close" style="font-size: 0.75rem;"></button>
            </div>
            <div class="text-muted small fw-medium">${message}</div>
        `;

        toast.querySelector('.btn-close')?.addEventListener('click', () => toast.remove());
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 4200);
    }

    function animateCounterEl(el, target) {
        if (!el) return;
        let current = 0;
        const step = Math.ceil(target / 45);
        const interval = setInterval(() => {
            current += step;
            if (current >= target) {
                el.innerText = target.toLocaleString() + "+";
                clearInterval(interval);
            } else {
                el.innerText = current.toLocaleString();
            }
        }, 20);
    }

    window.addEventListener('load', function() {
        const spinner = document.getElementById('spinnerOverlay');
        if (spinner) {
            spinner.style.opacity = '0';
            setTimeout(() => {
                spinner.style.visibility = 'hidden';
                spinner.style.display = 'none';
            }, 400);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        animateCounterEl(document.getElementById('medCount'), 2480);
        animateCounterEl(document.getElementById('pharCount'), 186);
        animateCounterEl(document.getElementById('stockCount'), 58200);

        // =========================================================================
        // LIVE MAP GEOLOCATION CONTROLLER ENGAGEMENT (SAFEBODA PARITY)
        // =========================================================================
        const mapContainer = document.getElementById('map');
        if (mapContainer) {
            // Default center point: Kampala, Uganda [0.3476, 32.5825]
            const map = L.map('map').setView([0.3476, 32.5825], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            const markers = [];
            const rows = document.querySelectorAll('.map-sidebar-row');

            // Map out every pharmacy entry match found inside blade view collection looping structures
            rows.forEach(row => {
                const lat = parseFloat(row.getAttribute('data-lat'));
                const lng = parseFloat(row.getAttribute('data-lng'));
                if (!isNaN(lat) && !isNaN(lng)) {
                    // Create Pin Marker Instance
                    const marker = L.marker([lat, lng]).addTo(map);

                    // Reuse the escaped listing details so the map shows the same information.
                    const popup = row.querySelector('.medicine-result-summary').cloneNode(true);
                    marker.bindPopup(popup, { minWidth: 260 });

                    markers.push({
                        marker: marker,
                        lat: lat,
                        lng: lng
                    });

                    // Click event listener: When sidebar items are clicked, track map over coordinates like SafeBoda!
                    row.addEventListener('click', () => {
                        map.setView([lat, lng], 15, {
                            animate: true,
                            duration: 1
                        });
                        marker.openPopup();

                        // Scroll smoothly to map viewport on small interfaces
                        if (window.innerWidth < 768) {
                            mapContainer.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    });
                }
            });

            // Adjust viewport boundaries dynamically if markers exist
            if (markers.length > 0) {
                const group = new L.featureGroup(markers.map(m => m.marker));
                map.fitBounds(group.getBounds().pad(0.2));
            }
        }
    });

    // Modal Control Flow Configuration
    (function() {
        const modal = document.getElementById('featureModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalDescription = document.getElementById('modalDescription');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const closeModalBtnSecondary = document.getElementById('closeModalBtnSecondary');

        const featureDetailsMap = {
            "Real-Time Verification": "Real-Time Verification syncs across regional Ugandan pharmacy endpoints using trusted system logs, ensuring extreme accuracy for live inventory tracking counts. Our platform cuts down verification loops cleanly.",
            "Secure Allocation Locks": "Secure Allocation Locks allow patients to hold critical matches safely. Pharmacies receive notification counters right away, avoiding dual allocation issues and optimizing stock distributions.",
            "Dynamic Search Matrix": "Dynamic Search Matrix handles complex medical strings. Form list fields auto-accumulate criteria items perfectly to parse targeted stock nodes simultaneously across cities.",
        };

        const detailBtns = document.querySelectorAll('.feature-detail-btn');

        function openModal(featureName) {
            if (!modal) return;
            modalTitle.innerText = featureName;
            modalDescription.innerText = featureDetailsMap[featureName] || "Advanced capability designed to streamline medical stock discovery with verified pharmacy integration.";

            modal.style.display = 'flex';
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.style.opacity = '1';
                modal.classList.add('show');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            if (!modal) return;
            modal.style.opacity = '0';
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 200);
            document.body.style.overflow = '';
        }

        detailBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const feature = btn.getAttribute('data-feature');
                if (feature) openModal(feature);
            });
        });

        closeModalBtn?.addEventListener('click', closeModal);
        closeModalBtnSecondary?.addEventListener('click', closeModal);
        modal?.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    })();

    document.getElementById('demoSearchCta')?.addEventListener('click', (e) => {
        e.preventDefault();
        const mainSearchInput = document.getElementById('itemInput');
        if (mainSearchInput) {
            mainSearchInput.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            mainSearchInput.focus();
        }
    });

    document.getElementById('demoRegisterCta')?.addEventListener('click', (e) => {
        e.preventDefault();
        showToast("Pharmacy registration panel template: This safely triggers onboarding operations.", "info");
    });

    // ISOLATED ROBUST POPUP SYSTEM FOR MEDFINDER INDEX
    (function() {
        function initSearchPopup() {
            const searchPromptModal = document.getElementById('searchPromptModal');
            const heroTriggerBtn = document.getElementById('triggerPromptModalBtn'); // Yellow top button
            const bottomTriggerBtn = document.getElementById('bottomTriggerPromptModalBtn'); // Blue bottom button
            const closePromptBtn = document.getElementById('closePromptBtn');
            const modalInput = document.getElementById('modalItemInput');

            if (!searchPromptModal) {
                console.error("MedFinder Error: #searchPromptModal element was not found in the HTML.");
                return;
            }

            function openSearchPrompt(e) {
                if (e) e.preventDefault();
                searchPromptModal.style.setProperty('display', 'flex', 'important');
                setTimeout(() => {
                    searchPromptModal.style.opacity = '1';
                }, 20);
                document.body.style.overflow = 'hidden';
                modalInput?.focus();
            }

            function closeSearchPrompt(e) {
                if (e) e.preventDefault();
                searchPromptModal.style.opacity = '0';
                setTimeout(() => {
                    searchPromptModal.style.display = 'none';
                }, 200);
                document.body.style.overflow = '';
            }

            // Attach event listeners safely
            if (heroTriggerBtn) {
                heroTriggerBtn.addEventListener('click', openSearchPrompt);
            }

            if (bottomTriggerBtn) {
                bottomTriggerBtn.addEventListener('click', openSearchPrompt);
            } else {
                console.warn("MedFinder Warning: #bottomTriggerPromptModalBtn not found in layout.");
            }

            closePromptBtn?.addEventListener('click', closeSearchPrompt);

            searchPromptModal.addEventListener('click', function(e) {
                if (e.target === searchPromptModal) {
                    closeSearchPrompt();
                }
            });
        }

        // Run code as soon as DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSearchPopup);
        } else {
            initSearchPopup();
        }
    })();
</script>
@endsection
