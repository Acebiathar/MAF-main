@extends('layouts.app')

@section('title', 'Home | Medfinder')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link rel="preload" as="image" href="https://images.pexels.com/photos/4386467/pexels-photo-4386467.jpeg?auto=compress&cs=tinysrgb&w=1200">

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
        min-height: 80vh;
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
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 50px;
        padding: 0.5rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
        backdrop-filter: blur(8px);
    }

    .tag-item:hover {
        background: rgba(255, 255, 255, 0.25);
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
        box-shadow: 0 12px 20px -5px rgba(0,0,0,0.05);
    }

    .howit-card {
        background: white;
        border: 1px solid var(--slate-200);
        border-radius: 1.25rem;
        padding: 2.5rem 2rem;
        text-align: center;
        height: 100%;
        transition: var(--transition);
    }

    .step-icon {
        width: 70px;
        height: 70px;
        background: var(--primary-soft);
        color: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 1.75rem;
        box-shadow: inset 0 0 0 2px rgba(11, 94, 215, 0.05);
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
</style>
@endsection

@section('content')
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
                        <div class="row">
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
                <p class="text-white-50 mb-0" style="font-size: 0.775rem;">Type your medicine here. Seperate items with a comma.</p>
            </div>
        </div>
        
        <form id="searchForm" action="{{ url('/') }}" method="GET" class="m-0">
            <div class="input-group bg-white rounded-4 shadow-lg p-2 border border-white border-2" style="transition: var(--transition);">
                
                <span class="input-group-text border-0 bg-transparent ps-3 pe-2 text-slate-600">
                    <i class="bi bi-capsule-capsule" style="font-size: 1.1rem; color: #64748b;"></i>
                </span>
                
                <input type="text" 
                       name="search" 
                       id="itemInput" 
                       value="{{ request('search') }}" 
                       class="form-control border-0 bg-transparent text-dark px-2 py-3" 
                       placeholder="Enter prescription criteria (e.g., Panadol, Amoxicillin)..." 
                       style="outline: none; box-shadow: none; font-size: 0.95rem; font-weight: 500; letter-spacing: -0.01em;">
                
                <button type="submit" 
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

    {{-- Fallback validation alert notice check structure --}}
@if(request()->has('search') && isset($results) && $results->isEmpty())
<div class="container mt-5">
    <div class="alert alert-warning text-center shadow-sm border-0 rounded-3">
        <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>
        No approved pharmacies currently have "{{ request('search') }}" in stock. Please try a different medicine.
    </div>
</div>
@endif

{{-- Results Table --}}
@if(isset($results) && $results->isNotEmpty())
<div class="container py-5">
...
@endif
    {{-- Clean structure looping through prioritized pharmacies and nested items inside your table layout --}}
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
                                            {{ $pharmacy->available_items_count }} Matches
                                        </span>
                                    </div>
                                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $pharmacy->location ?? $pharmacy->pharmacy_location }}</small>
                                </td>
                                <td class="py-3.5 font-medium text-dark fw-bold">
                                    {{ number_format($medicine->pivot->price, 0) }} <span class="text-xs text-muted" style="font-size:0.75rem;">UGX</span>
                                </td>
                                <td class="py-3.5">
                                    @if($medicine->pivot->quantity == 0)
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-full fw-semibold" style="font-size: 0.825rem;">Out of Stock</span>
                                    @elseif($medicine->pivot->quantity <= 5)
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
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5 my-4">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold">System Pipeline</span>
            <h2 class="fw-bold mt-2 h3">How MedFinder Simplifies Discovery</h2>
            <p class="text-muted max-w-md mx-auto">Real-time matching matrices converting programmatic medication stock records into verified fulfillment pipelines.</p>
        </div>
        
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="howit-card">
                    <div class="step-icon"><i class="bi bi-search"></i></div>
                    <h4 class="fw-bold h5 mb-2">1. Query Real-Time Networks</h4>
                    <p class="text-muted small mb-0">Input medicine names to parse active stock registers across 180+ localized and verified partner pharmacies instantly.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="howit-card">
                    <div class="step-icon"><i class="bi bi-shield-check"></i></div>
                    <h4 class="fw-bold h5 mb-2">2. Secure Stock Assurances</h4>
                    <p class="text-muted small mb-0">Trigger reservation locks directly onto verified pharmacy counters to shield inventory items from physical depletion parameters.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="howit-card">
                    <div class="step-icon"><i class="bi bi-box-seam"></i></div>
                    <h4 class="fw-bold h5 mb-2">3. Direct Handoff Fulfillment</h4>
                    <p class="text-muted small mb-0">Deploy swift courier routing integrations or pick up items securely in-person backed by synchronized system logging identifiers.</p>
                </div>
            </div>
        </div>
    </section>

    <section style="background-color: #f1f5f9; padding: 5rem 1.5rem; border-top: 1px solid var(--slate-200);">
        <div style="max-width: 1200px; margin: 0 auto; text-align: center;">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold mb-2">User Feedback</span>
            <h2 style="font-size: 2rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem;">What Our Users Say</h2>
            <p style="color: #64748b; font-size: 1rem; max-width: 600px; margin: 0 auto 4rem;">Discover how we are connecting patients directly with authentic local pharmacies securely.</p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
                @foreach($testimonials as $testimonial)
                    <div style="background: #ffffff; padding: 2.25rem; border-radius: 1.25rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); border: 1px solid var(--slate-200); text-align: left; display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                        <p style="color: #475569; font-size: 0.95rem; line-height: 1.6; font-style: italic; margin-bottom: 2rem;">
                            "{{ $testimonial['quote'] }}"
                        </p>

                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; background-color: var(--slate-100);">
                            <div>
                                <h4 style="font-size: 0.95rem; font-weight: 600; color: #0f172a; margin: 0;">{{ $testimonial['name'] }}</h4>
                                <span style="font-size: 0.85rem; color: var(--primary); font-weight: 500;">{{ $testimonial['role'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <div id="toastRoot" class="position-fixed bottom-0 start-0 p-3" style="z-index: 11000"></div>
</div>
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
        const btn = document.getElementById('searchBtn');
        const form = document.getElementById('searchForm');
        if (!list || !btn || !form) return;

        btn.style.display = list.children.length > 0 ? 'block' : 'none';
        
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
        let current = 0;
        const step = Math.ceil(target / 45);
        const interval = setInterval(() => {
            current += step;
            if (current >= target) {
                el.innerText = target.toLocaleString();
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

        if (document.getElementById('medCount')) animateCounterEl(document.getElementById('medCount'), 2480);
        if (document.getElementById('pharCount')) animateCounterEl(document.getElementById('pharCount'), 186);
        if (document.getElementById('stockCount')) animateCounterEl(document.getElementById('stockCount'), 58200);

        if (input) {
            input.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    addItem();
                }
            });
        }

        updateSearchBtn();
    });
</script>
@endsection