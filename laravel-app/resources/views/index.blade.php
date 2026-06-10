@extends('layouts.app')

@section('title', 'Home | Medfinder')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link rel="preload" as="image" href="https://images.pexels.com/photos/4386467/pexels-photo-4386467.jpeg?auto=compress&cs=tinysrgb&w=1200">
<script src="https://cdn.tailwindcss.com"></script>
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
    }

    .landing-page {
        min-height: 100vh;
    }

    /* Hero Section Visual Fixes */
    .hero-carousel .carousel-item {
        min-height: 85vh;
        background-size: cover;
        background-position: center;
        position: relative;
        display: flex;
        align-items: center;
    }

    .hero-carousel .carousel-item::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.6) 50%, rgba(15, 23, 42, 0.3) 100%);
        z-index: 1;
    }

    .carousel-caption {
        z-index: 2;
        text-align: left;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        padding: 4rem 2rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* Modern Glass Card Engine */
    .glass-card {
        background: #ffffff;
        border: 1px solid var(--slate-200);
        border-radius: 1.5rem;
        padding: 2.25rem;
        height: 100%;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.03);
        transition: var(--transition);
    }

    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.08);
    }

    .dark-bg {
        background: linear-gradient(145deg, #1e2f3e 0%, #0f172a 100%);
        border: none;
        color: white;
    }

    /* Medicine Interactive Tag Badges */
    .tag-item {
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 50px;
        padding: 0.4rem 1rem;
        font-size: 0.825rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: var(--transition);
        backdrop-filter: blur(8px);
    }

    .tag-item:hover {
        background: rgba(255, 255, 255, 0.22);
        transform: scale(1.02);
    }

    /* Live Badge Indicators */
    .badge-stock {
        background: #d1fae5;
        color: #065f46;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.825rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .ad-card {
        background: white;
        border: 1px solid var(--slate-200);
        border-radius: 1.25rem;
        padding: 1.5rem;
        height: 100%;
        transition: var(--transition);
    }

    .ad-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.05);
    }

    .spinner-overlay {
        position: fixed;
        inset: 0;
        background: #ffffff;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.4s ease, visibility 0.4s;
    }

    .toast-notify {
        width: min(380px, calc(100vw - 2rem));
        background: #ffffff;
        border-left: 4px solid var(--primary);
        border-radius: 1rem;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
        padding: 1.25rem;
        margin-bottom: 0.75rem;
    }

    .search-container {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 1.5rem;
        padding: 2rem;
        width: 100%;
        max-width: 650px;
    }

    .stat-box {
        background: white;
        border: 1px solid var(--slate-200);
        border-radius: 1.25rem;
        padding: 1.75rem;
        text-align: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }

    * {
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .enhanced-card {
        transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        backdrop-filter: blur(0px);
    }

    .enhanced-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(59, 130, 246, 0.1);
    }



    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #e2e8f0;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: #3b82f6;
        border-radius: 10px;
    }

    .feature-card {
        background: ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.05), 0 2px 4px -2px rgba(15, 23, 42, 0.05);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -8px rgba(15, 23, 42, 0.12);
        border-color: rgba(59, 130, 246, 0.3);
    }

    .feature-card h3 {
        font-size: 1.05rem !important;
        line-height: 1.4;
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    .feature-card p {
        color: #64748b;
        font-size: 0.825rem !important;
        line-height: 1.5;
    }

    .feature-card .feature-detail-btn {
        border-radius: 0.5rem;
        padding: 0.35rem 0.75rem;
        border: 1px solid rgba(59, 130, 246, 0.1);
        background: rgba(59, 130, 246, 0.05);
        color: #2563eb;
        font-size: 0.775rem !important;
    }

    .feature-card .feature-detail-btn:hover {
        background: rgba(59, 130, 246, 0.12);
        border-color: rgba(59, 130, 246, 0.25);
    }

    @media (min-width: 1024px) {
        #featuresGrid {
            display: grid !important;
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
            flex-wrap: wrap !important;
            overflow-x: visible !important;
            padding-bottom: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            gap: 1.5rem !important;
        }

        #featuresGrid .feature-card {
            min-width: 0 !important;
            max-width: none !important;
            flex: none !important;
            margin-left: 0 !important;
        }
    }

    /* Force black color for feature card headings (override dark mode classes) */
    #featuresGrid .feature-card h3 {
        color: #000000 !important;
        -webkit-text-fill-color: #000000 !important;
        background: none !important;
        background-clip: unset !important;
        -webkit-background-clip: unset !important;
    }

    /* Force black color for CTA title in 'Find Your Prescriptions Instantly' */
    .cta-animate h2 {
        color: #000000 !important;
        -webkit-text-fill-color: #000000 !important;
        background: none !important;
        background-clip: unset !important;
        -webkit-background-clip: unset !important;
    }
</style>
@endsection

@section('fullwidth')
<div class="landing-page">
    <div id="spinnerOverlay" class="spinner-overlay">
        <div class="text-center">
            <div class="spinner-border text-primary" style="width: 3.5rem; height: 3.5rem; stroke-width: 3;"></div>
            <p class="mt-3 fw-bold text-slate-700 tracking-wide">Securing Live Pharmacy Networks...</p>
        </div>
    </div>

    <div id="heroCarousel" class="carousel slide hero-carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('https://images.pexels.com/photos/4386467/pexels-photo-4386467.jpeg?auto=compress&cs=tinysrgb&w=1600');">
                <div class="carousel-caption">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-7">
                                <span class="badge bg-primary px-3 py-2 rounded-pill text-uppercase mb-3 tracking-wider fw-semibold" style="font-size: 0.75rem;">Verified Stocks Only</span>
                                <h1 class="display-4 fw-bold text-white mb-3">Find Prescriptions Near You, Instantly.</h1>
                                <p class="lead text-white-50 mb-4">Unified platform mapping local medical stock configurations directly to real-time consumer and emergency needs.</p>

                                <div class="search-container position-relative overflow-hidden p-4 p-md-5" style="background: rgba(15, 23, 42, 0.45); backdrop-filter: blur(24px); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 2rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); max-width: 700px;">
                                    <div class="position-absolute" style="width: 150px; height: 150px; background: radial-gradient(circle, rgba(0, 180, 170, 0.25) 0%, rgba(0,0,0,0) 70%); top: -50px; right: -50px; pointer-events: none;"></div>

                                    <div class="position-relative z-3">
                                        <div class="d-flex align-items-center gap-2 mb-4">
                                            <div class="d-flex align-items-center justify-content-center bg-info bg-opacity-10 rounded-3" style="width: 36px; height: 36px; border: 1px solid rgba(0, 180, 170, 0.25);">
                                                <i class="bi bi-search text-info" style="font-size: 1rem;"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-white fw-bold h5 mb-0" style="letter-spacing: -0.02em;">Search For Your Medicine</h3>
                                                <p class="text-white-50 mb-0" style="font-size: 0.775rem;">Type your medicine here. Press Enter to append criteria items.</p>
                                            </div>
                                        </div>

                                        <div id="editableItemList" class="d-flex flex-wrap gap-2 mb-3"></div>

                                        <form id="searchForm" action="{{ url('/') }}" method="GET" class="m-0">
                                            <div class="input-group bg-white rounded-4 shadow-lg p-2 border border-white border-2" style="transition: var(--transition);">
                                                <span class="input-group-text border-0 bg-transparent ps-3 pe-2 text-slate-600">
                                                    <i class="bi bi-capsule" style="font-size: 1.1rem; color: #64748b;"></i>
                                                </span>

                                                <input type="text"
                                                    name="search"
                                                    id="itemInput"
                                                    value="{{ request('search') }}"
                                                    class="form-control border-0 bg-transparent text-dark px-2 py-3"
                                                    placeholder="Enter prescription criteria (e.g., Panadol, Amoxicillin)..."
                                                    style="outline: none; box-shadow: none; font-size: 0.95rem; font-weight: 500; letter-spacing: -0.01em;">

                                                <button type="submit" id="searchBtn"
                                                    class="btn btn-dark rounded-4 px-4 fw-bold text-uppercase tracking-wider transition d-flex align-items-center gap-2"
                                                    style="font-size: 0.8rem; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 1px solid rgba(255,255,255,0.05); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);">
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

    <section class="container" style="margin-top: -40px; position: relative; z-index: 10;">
        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-4">
                <div class="stat-box">
                    <h3 class="display-6 fw-bold text-dark mb-1" id="medCount">0</h3>
                    <p class="text-muted small fw-medium mb-0">Active Medical Catalogs</p>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-box">
                    <h3 class="display-6 fw-bold text-primary mb-1" id="pharCount">0</h3>
                    <p class="text-muted small fw-medium mb-0">Verified Pharmacy Nodes</p>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="stat-box">
                    <h3 class="display-6 fw-bold text-success mb-1" id="stockCount">0</h3>
                    <p class="text-muted small fw-medium mb-0">Tracked Physical Stock Units</p>
                </div>
            </div>
        </div>
    </section>

    @if(request()->has('search') && isset($results) && $results->isEmpty())
    <div class="container mt-5">
        <div class="alert alert-warning text-center shadow-sm border-0 rounded-3">
            <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>
            No approved pharmacies currently have "{{ request('search') }}" in stock. Please try a different medicine.
        </div>
    </div>
    @endif

    @if(isset($results) && $results->isNotEmpty())
    <div class="container py-5">
        <div class="glass-card p-0 overflow-hidden border-0 shadow-lg">
            <div class="p-4 bg-white border-bottom border-light d-flex align-items-center justify-content-between">
                <h3 class="fw-bold h5 mb-0 text-dark"><i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i> Live Availability Records</h3>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-medium">Prioritized by Stock Matches</span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="table-light text-uppercase tracking-wider text-muted" style="font-size: 0.75rem;">
                        <tr>
                            <th class="ps-4 py-3">Medicine Name</th>
                            <th class="py-3">Pharmacy Location</th>
                            <th class="py-3">Price Status</th>
                            <th class="py-3">Availability Status</th>
                            <th class="text-end pe-4 py-3">Distribution Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $pharmacy)
                        @foreach ($pharmacy->medicines as $medicine)
                        <tr class="transition">
                            <td class="ps-4 py-3.5">
                                <span class="d-block fw-bold text-dark h6 mb-0 text-capitalize">{{ $medicine->name }}</span>
                                <small class="text-muted text-xs">ID: {{ 1000 + $medicine->id }}</small>
                            </td>
                            <td class="py-3.5">
                                <div class="fw-bold text-primary mb-0.5">
                                    <i class="bi bi-patch-check-fill me-1 text-info"></i>{{ $pharmacy->name }}
                                    <span class="badge bg-primary-subtle text-primary rounded-pill text-xs ms-1" style="font-size: 0.7rem;">
                                        {{ $pharmacy->available_items_count ?? 1 }} Matches
                                    </span>
                                </div>
                                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $pharmacy->location ?? $pharmacy->pharmacy_location }}</small>
                            </td>
                            <td class="py-3.5 font-medium text-dark fw-bold">
                                {{ number_format($medicine->pivot->price ?? 0, 0) }} <span class="text-xs text-muted" style="font-size:0.75rem;">UGX</span>
                            </td>
                            <td class="py-3.5">
                                @if(($medicine->pivot->quantity ?? 0) == 0)
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-full fw-semibold" style="font-size: 0.825rem;">Out of Stock</span>
                                @elseif(($medicine->pivot->quantity ?? 0) <= 5)
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-full fw-semibold" style="font-size: 0.825rem; color: #b58105 !important;"><i class="bi bi-exclamation-triangle"></i> Limited Stock ({{ $medicine->pivot->quantity }} units)</span>
                                    @else
                                    <span class="badge-stock"><i class="bi bi-check2-circle"></i> In Stock ({{ $medicine->pivot->quantity }} units)</span>
                                    @endif
                            </td>
                            <td class="text-end pe-4 py-3.5">
                                @if(isset($currentUser) && $currentUser->role === 'patient')
                                <form action="{{ url('/reserve/' . ($medicine->pivot->id ?? $medicine->id)) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm fw-bold transition d-inline-flex align-items-center gap-1.5" style="font-size: 0.8rem; letter-spacing: -0.01em;">
                                        <i class="bi bi-shield-lock-fill"></i>
                                        <span>Reserve Allocation</span>
                                    </button>
                                </form>
                                @elseif(isset($currentUser))
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill fw-medium" style="font-size: 0.775rem;">
                                    <i class="bi bi-person-x me-1"></i> Patient Account Required
                                </span>
                                @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-dark rounded-pill px-4 fw-bold text-uppercase tracking-wider transition d-inline-flex align-items-center gap-1.5" style="font-size: 0.75rem; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 1px solid rgba(255,255,255,0.05);">
                                    <span>Login to Secure</span>
                                    <i class="bi bi-arrow-right-short" style="font-size: 1rem;"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
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
                                    <div>
                                        <span class="badge bg-danger bg-opacity-10 text-danger mb-3 px-2.5 py-1.5 rounded fw-semibold">Hospital Referral Hub</span>
                                        <h4 class="fw-bold h5 mb-2">Mulago National Referral</h4>
                                        <p class="text-muted small mb-4">Specialized care and 24/7 emergency infrastructure networks across central regions.</p>
                                    </div>
                                    <img src="{{ asset('images/pharmacy3.jpg') }}" alt="Mulago Layout" class="img-fluid rounded object-cover" style="height: 160px; width: 100%;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ad-card d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-danger bg-opacity-10 text-danger mb-3 px-2.5 py-1.5 rounded fw-semibold">Clinical Infrastructure</span>
                                        <h4 class="fw-bold h5 mb-2">Case Medical Centre</h4>
                                        <p class="text-muted small mb-4">Quality professional healthcare configurations optimized for family networks.</p>
                                    </div>
                                    <img src="{{ asset('images/pharmacy.jpg') }}" alt="Case Layout" class="img-fluid rounded object-cover" style="height: 160px; width: 100%;">
                                </div>
                            </div>
                            <div class="col-md-4 d-none d-md-block">
                                <div class="ad-card border-primary border d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-primary mb-3 px-2.5 py-1.5 rounded fw-semibold">Promoted Drug Allocation</span>
                                        <h4 class="fw-bold h5 mb-2">Panadol Extra</h4>
                                        <p class="text-muted small mb-4">Fast-acting advanced chemical composition relief tracking widespread local demands.</p>
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
                                    <div>
                                        <span class="badge bg-success bg-opacity-10 text-success mb-3 px-2.5 py-1.5 rounded fw-semibold">Network Partner Node</span>
                                        <h4 class="fw-bold h5 mb-2">Green Ridge Clinic</h4>
                                        <p class="text-muted small mb-4">Community care hub with rapid stock matching and patient referral support.</p>
                                    </div>
                                    <img src="{{ asset('images/pharmacy3.jpg') }}" alt="Green Ridge Clinic" class="img-fluid rounded object-cover" style="height: 160px; width: 100%;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ad-card d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-info bg-opacity-10 text-info mb-3 px-2.5 py-1.5 rounded fw-semibold">Regional Pharmacy</span>
                                        <h4 class="fw-bold h5 mb-2">Solar Care Pharmacy</h4>
                                        <p class="text-muted small mb-4">Verified pharmaceutical outlet built for secure and fast medication handoff.</p>
                                    </div>
                                    <img src="{{ asset('images/pharmacy.jpg') }}" alt="Solar Care Pharmacy" class="img-fluid rounded object-cover" style="height: 160px; width: 100%;">
                                </div>
                            </div>
                            <div class="col-md-4 d-none d-md-block">
                                <div class="ad-card d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-warning bg-opacity-10 text-warning mb-3 px-2.5 py-1.5 rounded fw-semibold">Logistics Node</span>
                                        <h4 class="fw-bold h5 mb-2">Medicortex Hub</h4>
                                        <p class="text-muted small mb-4">Integrated stock and delivery coordination for high-demand pharmaceutical items.</p>
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

    <!-- MAIN SECTION: original medical stock discovery (enhanced) -->
    <section class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-sm py-12 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6 rounded-3xl my-6 shadow-2xl border border-white/40 transition-all duration-300">
        <div class="mx-auto max-w-screen-xl">
            <div class="max-w-screen-md mb-8 lg:mb-16">
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1.5 rounded-full dark:bg-blue-900 dark:text-blue-300 uppercase tracking-wider mb-4 inline-flex items-center gap-1.5">
                    <i class="bi bi-cpu text-sm"></i> System Capabilities
                </span>
                <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white bg-gradient-to-r from-gray-900 to-blue-900 bg-clip-text text-transparent dark:from-white dark:to-blue-300">Intelligent Medical Stock Discovery</h2>
                <p class="text-gray-500 sm:text-xl dark:text-gray-400">MedFinder connects patients directly to verified pharmaceutical inventories, bypassing physical depletion constraints with unified programmatic tracking.</p>
            </div>

            <!-- Feature Grid: each card gets interactive hover & modals -->
            <div class="space-y-8 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-12 md:space-y-0" id="featuresGrid">
                <!-- Card 1: Real-Time Verification -->
                <div class="feature-card enhanced-card bg-white dark:bg-gray-800/70 rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 group">
                    <div class="flex justify-start items-center mb-5 w-12 h-12 rounded-xl bg-blue-100 lg:h-14 lg:w-14 dark:bg-blue-900/70 group-hover:scale-105 transition-transform">
                        <i class="bi bi-patch-check-fill text-blue-600 text-2xl lg:text-2xl mx-auto dark:text-blue-300"></i>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">Real-Time Verification <span class="live-dot text-xs bg-green-500 w-2 h-2 rounded-full inline-block"></span></h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-3">Directly syncs with active pharmacy catalogs across the region, pulling authentic live inventory balances with exact timestamp matching.</p>
                    <button class="feature-detail-btn text-blue-600 dark:text-blue-400 text-sm font-medium flex items-center gap-1 mt-2 hover:gap-2 transition-all" data-feature="Real-Time Verification">
                        Learn more <i class="bi bi-arrow-right-short text-lg"></i>
                    </button>
                </div>

                <!-- Card 2: Secure Allocation Locks -->
                <div class="feature-card enhanced-card bg-white dark:bg-gray-800/70 rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 group">
                    <div class="flex justify-start items-center mb-5 w-12 h-12 rounded-xl bg-blue-100 lg:h-14 lg:w-14 dark:bg-blue-900/70 group-hover:scale-105 transition-transform">
                        <i class="bi bi-shield-lock-fill text-blue-600 text-2xl lg:text-2xl mx-auto dark:text-blue-300"></i>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Secure Allocation Locks</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-3">Patients can trigger instantaneous reservation holds directly on pharmacy counters, protecting critical prescriptions from localized depletion.</p>
                    <button class="feature-detail-btn text-blue-600 dark:text-blue-400 text-sm font-medium flex items-center gap-1 mt-2 hover:gap-2 transition-all" data-feature="Secure Allocation Locks">
                        Learn more <i class="bi bi-arrow-right-short text-lg"></i>
                    </button>
                </div>

                <!-- Card 3: Dynamic Search Matrix -->
                <div class="feature-card enhanced-card bg-white dark:bg-gray-800/70 rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 group">
                    <div class="flex justify-start items-center mb-5 w-12 h-12 rounded-xl bg-blue-100 lg:h-14 lg:w-14 dark:bg-blue-900/70 group-hover:scale-105 transition-transform">
                        <i class="bi bi-capsule text-blue-600 text-2xl lg:text-2xl mx-auto dark:text-blue-300"></i>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Dynamic Search Matrix</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-3">Type and append multiple medical nomenclature strings into custom parameter tag clouds to filter matches across multiple facilities simultaneously.</p>
                    <button class="feature-detail-btn text-blue-600 dark:text-blue-400 text-sm font-medium flex items-center gap-1 mt-2 hover:gap-2 transition-all" data-feature="Dynamic Search Matrix">
                        Learn more <i class="bi bi-arrow-right-short text-lg"></i>
                    </button>
                </div>

                <!-- Card 4: Geolocated Nodes -->
                <div class="feature-card enhanced-card bg-white dark:bg-gray-800/70 rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 group">
                    <div class="flex justify-start items-center mb-5 w-12 h-12 rounded-xl bg-blue-100 lg:h-14 lg:w-14 dark:bg-blue-900/70 group-hover:scale-105 transition-transform">
                        <i class="bi bi-geo-alt-fill text-blue-600 text-2xl lg:text-2xl mx-auto dark:text-blue-300"></i>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Geolocated Nodes</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-3">Sort live results automatically based on physical vicinity, directing you cleanly to the closest emergency care or retail medicine distributor.</p>
                    <button class="feature-detail-btn text-blue-600 dark:text-blue-400 text-sm font-medium flex items-center gap-1 mt-2 hover:gap-2 transition-all" data-feature="Geolocated Nodes">
                        Learn more <i class="bi bi-arrow-right-short text-lg"></i>
                    </button>
                </div>

                <!-- Card 5: Transparent Price Ledger -->
                <div class="feature-card enhanced-card bg-white dark:bg-gray-800/70 rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 group">
                    <div class="flex justify-start items-center mb-5 w-12 h-12 rounded-xl bg-blue-100 lg:h-14 lg:w-14 dark:bg-blue-900/70 group-hover:scale-105 transition-transform">
                        <i class="bi bi-currency-exchange text-blue-600 text-2xl lg:text-2xl mx-auto dark:text-blue-300"></i>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Transparent Price Ledger</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-3">Compare standardized unit retail costs across verified partner nodes immediately to avoid unexpected variance when procuring rare formulations.</p>
                    <button class="feature-detail-btn text-blue-600 dark:text-blue-400 text-sm font-medium flex items-center gap-1 mt-2 hover:gap-2 transition-all" data-feature="Transparent Price Ledger">
                        Learn more <i class="bi bi-arrow-right-short text-lg"></i>
                    </button>
                </div>

                <!-- Card 6: Resilient Infrastructure -->
                <div class="feature-card enhanced-card bg-white dark:bg-gray-800/70 rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 group">
                    <div class="flex justify-start items-center mb-5 w-12 h-12 rounded-xl bg-blue-100 lg:h-14 lg:w-14 dark:bg-blue-900/70 group-hover:scale-105 transition-transform">
                        <i class="bi bi-activity text-blue-600 text-2xl lg:text-2xl mx-auto dark:text-blue-300"></i>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Resilient Infrastructure</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-3">Engineered with high-availability systems to maintain secure digital handoffs, logging handovers seamlessly between patient accounts and pharmacy nodes.</p>
                    <button class="feature-detail-btn text-blue-600 dark:text-blue-400 text-sm font-medium flex items-center gap-1 mt-2 hover:gap-2 transition-all" data-feature="Resilient Infrastructure">
                        Learn more <i class="bi bi-arrow-right-short text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Extra dynamic area: Live Search Simulation -->
            <div class="mt-14 rounded-2xl bg-gradient-to-r from-blue-50/50 to-indigo-50/50 dark:from-gray-800/40 dark:to-gray-800/20 p-5 md:p-6 border border-blue-100 dark:border-gray-700">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h4 class="font-bold text-gray-800 dark:text-white flex items-center gap-2"><i class="bi bi-search-heart text-blue-600"></i> Live Demo: Smart Stock Discovery</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Try searching for a medication — simulate real-time pharmacy inventory lookup</p>
                    </div>
                    <div class="flex flex-wrap gap-3 w-full md:w-auto">
                        <div class="relative flex-1 md:min-w-[240px]">
                            <input type="text" id="medSearchInput" placeholder="e.g., Insulin, Paracetamol, Azithromycin..." class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-400 outline-none transition">
                            <i class="bi bi-search absolute right-3 top-3 text-gray-400"></i>
                        </div>
                        <button id="simulateSearchBtn" class="bg-blue-700 hover:bg-blue-800 text-white px-5 py-2 rounded-xl shadow-md transition flex items-center gap-2 font-medium">Search Inventory <i class="bi bi-box-arrow-in-right"></i></button>
                    </div>
                </div>
                <div id="searchResultsArea" class="mt-5 hidden transition-all duration-300">
                    <div class="bg-white dark:bg-gray-800/90 rounded-xl p-4 shadow-inner border-l-4 border-blue-400">
                        <p id="resultMessage" class="text-gray-700 dark:text-gray-300 text-sm flex items-center gap-2"><i class="bi bi-check-circle-fill text-green-500"></i> <span>Ready to display stock matches.</span></p>
                        <div id="dynamicStockList" class="mt-2 text-xs text-gray-500 dark:text-gray-400"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================= -->
    <!-- NEW CTA SECTION: Find Your Prescriptions Instantly (enhanced CSS) -->
    <!-- ========================================================= -->
    <section class="bg-white dark:bg-gray-900 my-12 sm:my-16 rounded-3xl shadow-xl overflow-hidden transition-all duration-500 cta-animate">
        <div class="py-12 px-4 mx-auto max-w-screen-xl sm:py-20 lg:px-6 border-t border-gray-100 dark:border-gray-800 relative">
            <!-- decorative background accent -->
            <div class="absolute top-0 right-0 w-72 h-72 bg-blue-500/5 dark:bg-blue-400/5 rounded-full blur-3xl -z-0"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-500/5 dark:bg-indigo-400/5 rounded-full blur-3xl -z-0"></div>

            <div class="mx-auto max-w-screen-md text-center relative z-10">
                <div class="inline-flex items-center gap-1 bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-200 text-xs font-semibold px-3 py-1.5 rounded-full mb-5 backdrop-blur-sm">
                    <i class="bi bi-heart-pulse-fill text-red-500"></i> <span>Instant access • Uganda nationwide</span>
                </div>
                <h2 class="mb-5 text-4xl tracking-tight font-extrabold leading-tight text-gray-900 dark:text-white sm:text-5xl bg-gradient-to-r from-gray-800 to-blue-800 dark:from-white dark:to-blue-300 bg-clip-text text-transparent">
                    Find Your Prescriptions Instantly
                </h2>
                <p class="mb-8 font-normal text-gray-500 dark:text-gray-400 md:text-lg max-w-xl mx-auto">
                    Access real-time pharmaceutical inventories across Uganda. Locate, verify, and secure your essential medicine today.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="#" id="demoSearchCta" class="w-full sm:w-auto text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-7 py-3.5 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2 btn-cta-primary dark:bg-blue-600 dark:hover:bg-blue-700">
                        <i class="bi bi-search-heart text-lg"></i> Search Live Inventory
                    </a>
                    <a href="#" id="demoRegisterCta" class="w-full sm:w-auto text-gray-700 bg-gray-50 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-xl text-sm px-7 py-3.5 transition-all duration-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 flex items-center justify-center gap-2 btn-cta-secondary shadow-sm">
                        <i class="bi bi-building-add"></i> Register Your Pharmacy
                    </a>
                </div>
                <!-- small trust badge added -->
                <div class="mt-8 flex justify-center gap-5 text-xs text-gray-400 dark:text-gray-500">
                    <span class="flex items-center gap-1"><i class="bi bi-check-circle-fill text-green-500 text-xs"></i> Verified nodes</span>
                    <span class="flex items-center gap-1"><i class="bi bi-clock-history"></i> 24/7 live sync</span>
                    <span class="flex items-center gap-1"><i class="bi bi-shield-check"></i> HIPAA-ready</span>
                </div>
            </div>
        </div>
    </section>

</div>

<!-- Modal overlay for feature details -->
<div id="featureModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 opacity-0 invisible transition-all duration-300 px-4">
    <div class="bg-white dark:bg-gray-850 rounded-2xl max-w-lg w-full p-6 shadow-2xl transform scale-95 transition-transform duration-300 border border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-start mb-3">
            <h3 id="modalTitle" class="text-2xl font-bold text-gray-900 dark:text-white">Feature</h3>
            <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600 text-2xl leading-5">&times;</button>
        </div>
        <p id="modalDescription" class="text-gray-600 dark:text-gray-300 mb-4">Detailed insights about this capability.</p>
        <div class="bg-blue-50 dark:bg-blue-900/30 p-3 rounded-xl text-sm text-blue-800 dark:text-blue-200 flex items-start gap-2">
            <i class="bi bi-info-circle-fill mt-0.5"></i>
            <span>MedFinder uses real-time indexing, protecting stock integrity with secure handshakes.</span>
        </div>
        <button class="mt-5 w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-white py-2 rounded-xl transition">Close</button>
    </div>
</div>




<!-- Modal overlay for feature details -->
<!-- Testimonials Section -->
<section style="background-color: #f1f5f9; padding: 3.5rem 1.5rem; border-top: 1px solid var(--slate-200);">
    <div style="max-width: 1200px; margin: 0 auto; text-align: center;">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold mb-2">User Feedback</span>
        <h2 style="font-size: 2rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem;">What Our Users Say</h2>
        <p style="color: #64748b; font-size: 1rem; max-width: 600px; margin: 0 auto 3rem;">Discover how we are connecting patients directly with authentic local pharmacies securely.</p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
            @if(isset($testimonials) && count($testimonials) > 0)
            @foreach($testimonials as $testimonial)
            <div style="background: #ffffff; padding: 2.25rem; border-radius: 1.25rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); border: 1px solid var(--slate-200); text-align: left; display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                <p style="color: #475569; font-size: 0.95rem; line-height: 1.6; font-style: italic; margin-bottom: 2rem;">
                    "{{ $testimonial['quote'] ?? $testimonial['body'] }}"
                </p>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <img src="{{ $testimonial['avatar'] ?? 'https://ui-avatars.com/api/?name='.urlencode($testimonial['name']) }}" alt="{{ $testimonial['name'] }}" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; background-color: var(--slate-100);">
                    <div>
                        <h4 style="font-size: 0.95rem; font-weight: 600; color: #0f172a; margin: 0;">{{ $testimonial['name'] }}</h4>
                        <span style="font-size: 0.85rem; color: var(--primary); font-weight: 500;">{{ $testimonial['role'] ?? 'Patient' }}</span>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div style="background: #ffffff; padding: 2.25rem; border-radius: 1.25rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); border: 1px solid var(--slate-200); text-align: left;">
                <p style="color: #475569; font-size: 0.95rem; line-height: 1.6; font-style: italic; margin-bottom: 2rem;">"Saved me hours driving through Kampala traffic trying to locate rare insulin variants. Perfect platform."</p>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div class="fw-bold" style="width: 44px; height: 44px; border-radius: 50%; background: #0b5ed7; color: white; display:flex; align-items:center; justify-content:center;">NK</div>
                    <div>
                        <h4 style="font-size: 0.95rem; font-weight: 600; color: #0f172a; margin: 0;">Nsubuga Karim</h4>
                        <span style="font-size: 0.85rem; color: var(--primary); font-weight: 500;">Verified Patient</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<section class="bg-white dark:bg-gray-900 my-12 sm:my-16">
    <div class="py-12 px-4 mx-auto max-w-screen-xl sm:py-20 lg:px-6 border-t border-gray-100 dark:border-gray-800">
        <div class="mx-auto max-w-screen-md text-center">
            <h2 class="mb-4 text-3xl tracking-tight font-extrabold leading-tight text-gray-900 dark:text-white sm:text-4xl">
                Find Your Prescriptions Instantly
            </h2>
            <p class="mb-8 font-normal text-gray-500 dark:text-gray-400 md:text-lg max-w-xl mx-auto">
                Access real-time pharmaceutical inventories across Uganda. Locate, verify, and secure your essential medicine today.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                <a href="/search" class="w-full sm:w-auto text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-6 py-3 transition-colors dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 shadow-sm">
                    Search Live Inventory
                </a>
                <a href="/pharmacy/register" class="w-full sm:w-auto text-gray-700 bg-gray-50 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-xl text-sm px-6 py-3 transition-colors dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700">
                    Register Your Pharmacy
                </a>
            </div>
        </div>
    </div>
</section>

<div id="toastRoot" class="position-fixed bottom-0 start-0 p-3" style="z-index: 11000"></div>
</div> <!-- Fixed missing landing page structural close wrapper -->
@endsection

@section('scripts')
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

    function escapeHtml(str) {
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    function updateSearchBtn() {
        const list = document.getElementById('editableItemList');
        const form = document.getElementById('searchForm');
        if (!list || !form) return;

        form.querySelectorAll('input[name="item_names[]"]').forEach((el) => el.remove());
        list.querySelectorAll('input[type="hidden"]').forEach((inp) => form.appendChild(inp.cloneNode(true)));
    }

    function createTag(value) {
        const wrap = document.createElement('div');
        wrap.className = 'tag-item';
        wrap.innerHTML = `
            <span>${escapeHtml(value)}</span>
            <button class="btn btn-sm p-0 border-0 bg-transparent text-white" type="button" aria-label="Remove element">
                <i class="bi bi-x-circle-fill ms-1" style="font-size: 0.9rem; opacity:0.8;"></i>
            </button>
            <input type="hidden" name="item_names[]" value="${escapeHtml(value)}">
        `;

        wrap.querySelector('button')?.addEventListener('click', () => {
            wrap.remove();
            updateSearchBtn();
        });

        return wrap;
    }

    function addItem() {
        const input = document.getElementById('itemInput');
        const mainList = document.getElementById('editableItemList');
        if (!input || !mainList) return;

        const value = input.value.trim();
        if (!value) {
            showToast('Please specify an authentic medical system nomenclature string.', 'error');
            input.focus();
            return;
        }

        mainList.appendChild(createTag(value));
        input.value = '';
        updateSearchBtn();
        showToast(`Added prescription target: "${value}"`, 'success');
        input.focus();
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
        const input = document.getElementById('itemInput');
        const form = document.getElementById('searchForm');

        animateCounterEl(document.getElementById('medCount'), 2480);
        animateCounterEl(document.getElementById('pharCount'), 186);
        animateCounterEl(document.getElementById('stockCount'), 58200);

        if (input) {
            input.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    addItem();
                }
            });
        }

        if (form && input) {
            form.addEventListener('submit', function(e) {
                const remainder = input.value.trim();
                if (remainder) {
                    const mainList = document.getElementById('editableItemList');
                    if (mainList) {
                        mainList.appendChild(createTag(remainder));
                        input.value = '';
                        updateSearchBtn();
                    }
                }
            });
        }

        updateSearchBtn();
    });

    (function() {
        const modal = document.getElementById('featureModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalDescription = document.getElementById('modalDescription');
        const closeModalBtn = document.getElementById('closeModalBtn');

        const featureDetailsMap = {
            "Real-Time Verification": "Real-Time Verification syncs across +200 regional pharmacy endpoints using cryptographic timestamping, ensuring millisecond-level accuracy for inventory counts. Our system eliminates outdated shelf checks.",
            "Secure Allocation Locks": "Secure Allocation Locks allow patients to place a binding digital hold on critical medications for up to 48 hours. Pharmacies receive instant notifications, reducing stockouts and false reservations.",
            "Dynamic Search Matrix": "Dynamic Search Matrix supports SNOMED, RxNorm, and custom drug nomenclature. Create multidimensional search tags to cross-reference availability across hospital chains, retail pharmacies and speciality depots.",
            "Geolocated Nodes": "Geolocated Nodes rank results using real-time geofencing, walking/driving ETA, and integrates with Google Maps / OpenStreetMap to give turn-by-turn directions to the nearest verified distributor.",
            "Transparent Price Ledger": "Transparent Price Ledger shows historical price trends, manufacturer-level costs, and insurance copay estimates. Avoid price gouging through real-time comparative analytics from partner nodes.",
            "Resilient Infrastructure": "Resilient Infrastructure leverages multi-region failover, end-to-end encryption, and auditable handover logs. Service level agreements guarantee 99.95% uptime for critical prescription handoffs."
        };

        const detailBtns = document.querySelectorAll('.feature-detail-btn');

        function openModal(featureName) {
            modalTitle.innerText = featureName;
            modalDescription.innerText = featureDetailsMap[featureName] || "Advanced capability designed to streamline medical stock discovery with verified pharmacy integration.";
            modal.classList.remove('opacity-0', 'invisible');
            modal.classList.add('opacity-100', 'visible');
            document.body.style.overflow = 'hidden';
            const modalContent = modal.querySelector('.bg-white');
            if (modalContent) modalContent.classList.remove('scale-95');
            void modalContent.offsetWidth;
            modalContent.classList.add('scale-100');
        }

        function closeModal() {
            modal.classList.add('opacity-0', 'invisible');
            modal.classList.remove('opacity-100', 'visible');
            document.body.style.overflow = '';
            const modalContent = modal.querySelector('.bg-white');
            if (modalContent) modalContent.classList.remove('scale-100');
        }

        detailBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const feature = btn.getAttribute('data-feature');
                if (feature) openModal(feature);
            });
        });

        closeModalBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    })();

    (function() {
        const medSearchInput = document.getElementById('medSearchInput');
        const simulateSearchBtn = document.getElementById('simulateSearchBtn');
        const searchResultsArea = document.getElementById('searchResultsArea');
        const resultMessageSpan = document.querySelector('#resultMessage span');
        const dynamicStockListDiv = document.getElementById('dynamicStockList');
        const ctaSearchBtn = document.getElementById('demoSearchCta');
        const ctaRegisterBtn = document.getElementById('demoRegisterCta');

        const mockInventoryDatabase = [{
                drug: "insulin",
                pharmacy: "CityMed Pharmacy",
                stock: 24,
                price: "$28.50",
                distance: "0.3 mi"
            },
            {
                drug: "paracetamol",
                pharmacy: "HealthPlus Central",
                stock: 112,
                price: "$4.99",
                distance: "0.7 mi"
            },
            {
                drug: "azithromycin",
                pharmacy: "Apollo Hub",
                stock: 15,
                price: "$12.20",
                distance: "1.2 mi"
            },
            {
                drug: "metformin",
                pharmacy: "CarePoint RX",
                stock: 48,
                price: "$9.75",
                distance: "0.9 mi"
            },
            {
                drug: "amoxicillin",
                pharmacy: "CityMed Pharmacy",
                stock: 62,
                price: "$7.30",
                distance: "0.3 mi"
            },
            {
                drug: "atorvastatin",
                pharmacy: "HealthPlus Central",
                stock: 33,
                price: "$14.60",
                distance: "0.7 mi"
            },
            {
                drug: "ibuprofen",
                pharmacy: "Apollo Hub",
                stock: 200,
                price: "$5.49",
                distance: "1.2 mi"
            }
        ];

        function performSearch(query) {
            if (!resultMessageSpan || !dynamicStockListDiv) return;
            if (!query.trim()) {
                resultMessageSpan.innerHTML = "💡 Please enter a medication name (e.g., Insulin, Paracetamol) to see real-time stock availability.";
                dynamicStockListDiv.innerHTML = '';
                return;
            }

            const lowerQuery = query.toLowerCase();
            const matches = mockInventoryDatabase.filter(item => item.drug.toLowerCase().includes(lowerQuery));

            if (matches.length === 0) {
                resultMessageSpan.innerHTML = `🔍 No direct match for "${query}". Try synonyms or broaden search.`;
                dynamicStockListDiv.innerHTML = `<div class="text-amber-600 text-xs">No immediate stock found, but allocation alerts are ready.</div>`;
                return;
            }

            resultMessageSpan.innerHTML = `✅ Found ${matches.length} active stock location(s) for "${query}".`;
            let html = `<ul class="mt-3 space-y-2 text-sm">`;
            matches.forEach(m => {
                html += `<li class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-2"><span><i class="bi bi-shop text-blue-500 mr-2"></i>${m.pharmacy}</span><span class="font-mono text-xs">${m.stock} units • ${m.price} • <i class="bi bi-geo-alt"></i> ${m.distance}</span></li>`;
            });
            html += `</ul><div class="mt-3 text-[11px] text-blue-500 flex gap-3"><i class="bi bi-shield-check"></i> Real-time lock available on selection</div>`;
            dynamicStockListDiv.innerHTML = html;
        }

        function handleSearchSubmit() {
            if (!medSearchInput) return;
            const searchTerm = medSearchInput.value.trim();
            if (searchResultsArea?.classList.contains('hidden')) searchResultsArea.classList.remove('hidden');
            performSearch(searchTerm);
        }

        if (simulateSearchBtn) {
            simulateSearchBtn.addEventListener('click', handleSearchSubmit);
        }

        if (medSearchInput) {
            medSearchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') handleSearchSubmit();
            });

            medSearchInput.addEventListener('focus', () => {
                if (searchResultsArea?.classList.contains('hidden')) searchResultsArea.classList.remove('hidden');
                if (resultMessageSpan) resultMessageSpan.innerHTML = "✨ Real-time search ready: type a medication and hit Search.";
                if (dynamicStockListDiv) dynamicStockListDiv.innerHTML = "";
            });
        }

        setTimeout(() => {
            if (searchResultsArea?.classList.contains('hidden')) searchResultsArea.classList.remove('hidden');
            if (resultMessageSpan) resultMessageSpan.innerHTML = "👋 Welcome to MedFinder demo — try searching 'insulin' or 'paracetamol' above.";
            if (dynamicStockListDiv) dynamicStockListDiv.innerHTML = `<div class="flex gap-2 flex-wrap"><span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">⚡ 6 active pharmacy nodes</span><span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">🏥 Geolocation enabled</span></div>`;
        }, 500);

        if (ctaSearchBtn && medSearchInput) {
            ctaSearchBtn.addEventListener('click', (e) => {
                e.preventDefault();
                medSearchInput.focus();
                medSearchInput.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                if (searchResultsArea?.classList.contains('hidden')) searchResultsArea.classList.remove('hidden');
                if (resultMessageSpan) resultMessageSpan.innerHTML = "🔍 Start your search above! Enter a medication to see live inventory.";
            });
        }

        if (ctaRegisterBtn) {
            ctaRegisterBtn.addEventListener('click', (e) => {
                e.preventDefault();
                alert("📋 Pharmacy registration portal demo: This would open a secure onboarding form to list your stock on MedFinder network.");
            });
        }
    })();
</script>
@endsection