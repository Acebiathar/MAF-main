@extends('layouts.app')

@section('title', 'Home | Medfinder')

@section('styles')
<!-- Inter Font & Bootstrap Icons -->
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
    <!-- Network Loader Animation overlay -->
    <div id="spinnerOverlay" class="spinner-overlay">
        <div class="text-center">
            <div class="spinner-border text-primary" style="width: 3.5rem; height: 3.5rem; stroke-width: 3;"></div>
            <p class="mt-3 fw-bold text-slate-700 tracking-wide">Securing Live Pharmacy Networks...</p>
        </div>
    </div>

    <!-- Hero Framework Section -->
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
                                
                                <!-- Core Medicine Input Engine -->
                                <div class="search-container">
                                    <h3 class="mb-3 text-white fw-semibold h5"><i class="bi bi-search me-2 text-info"></i> Search Medication</h3>
                                    
                                    <form id="searchForm" action="{{ url()->current() }}" method="GET">
                                        <div class="input-group bg-white rounded-pill p-1.5 mb-3 shadow-sm">
                                            <input type="text" id="itemInput" class="form-control border-0 bg-transparent ps-3 text-dark" placeholder="Type medicine name (e.g., Amoxicillin)..." style="outline: none; box-shadow: none;">
                                            <button type="button" class="btn btn-primary rounded-pill px-4 fw-semibold transition" onclick="addItem()">Add to List</button>
                                        </div>
                                        
                                        <!-- Container dynamically managed via JavaScript tagging architecture -->
                                        <div id="editableItemList" class="d-flex flex-wrap gap-2 mb-3" style="min-height: 40px;"></div>
                                        
                                        <!-- Submission button triggered when items are queued -->
                                        <button type="submit" id="searchBtn" class="btn btn-info text-white w-100 rounded-pill py-2.5 fw-bold transition shadow-sm" style="display: none;">
                                            <i class="bi bi-patch-check-fill me-2"></i>Scan Verified Registries
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Performance Network Cluster Stats -->
    <section class="container mt-n5 position-relative" style="z-index: 10; margin-top: -4rem;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="glass-card dark-bg p-4 px-md-5">
                    <div class="row text-center align-items-center">
                        <div class="col-4">
                            <h2 class="display-6 fw-bold mb-1 tracking-tight text-white" id="medCount">0</h2>
                            <span class="text-white-50 small text-uppercase tracking-wider fw-semibold">Tracked Meds</span>
                        </div>
                        <div class="col-4 border-start border-end border-white border-opacity-10">
                            <h2 class="display-6 fw-bold mb-1 tracking-tight text-white" id="pharCount">0</h2>
                            <span class="text-white-50 small text-uppercase tracking-wider fw-semibold">Pharmacies</span>
                        </div>
                        <div class="col-4">
                            <h2 class="display-6 fw-bold mb-1 tracking-tight text-white" id="stockCount">0</h2>
                            <span class="text-white-50 small text-uppercase tracking-wider fw-semibold">Stock Units</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Results Rendering Grid Infrastructure -->
    @if(isset($results) && $results->isNotEmpty())
    <div class="container py-5">
        <div class="glass-card p-0 overflow-hidden border-0 shadow-lg">
            <div class="p-4 bg-white border-bottom border-light d-flex align-items-center justify-content-between">
                <h3 class="fw-bold h5 mb-0 text-dark"><i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i> Live Availability Records</h3>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-medium">Cluster Refreshed Live</span>
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
                        @foreach ($results as $item)
                        <tr class="transition">
                            <td class="ps-4 py-3.5">
                                <span class="d-block fw-bold text-dark h6 mb-0">{{ $item->medicine_name }}</span>
                                <small class="text-muted text-xs">ID: {{ 1000 + $item->id }}</small>
                            </td>
                            <td class="py-3.5">
                                <div class="fw-bold text-primary mb-0.5"><i class="bi bi-patch-check-fill me-1 text-info"></i>{{ $item->pharmacy_name }}</div>
                                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $item->pharmacy_location }}</small>
                            </td>
                            <td class="py-3.5 font-medium text-dark fw-bold">
                                {{ number_format($item->price, 0) }} <span class="text-xs text-muted" style="font-size:0.75rem;">UGX</span>
                            </td>
                            <td class="py-3.5">
                                @if($item->quantity > 0)
                                <span class="badge-stock"><i class="bi bi-check2-circle"></i> In Stock ({{ $item->quantity }} units)</span>
                                @else
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-full fw-semibold" style="font-size: 0.825rem;">Out of Stock</span>
                                @endif
                            </td>
                            <td class="text-end pe-4 py-3.5">
                                @if(isset($currentUser) && $currentUser->role === 'patient')
                                <form action="/reserve/{{ $item->id }}" method="POST" class="m-0">
                                    @csrf
                                    <button class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm fw-bold transition">Reserve Allocation</button>
                                </form>
                                @elseif(isset($currentUser))
                                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" type="button" disabled style="font-size: 0.8rem;">
                                    Patients Only
                                </button>
                                @else
                                <a href="/login" class="btn btn-sm btn-outline-primary rounded-pill px-4 fw-semibold transition">Login to Secure Allocation</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Promotional Partner Slider Sections -->
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

    <!-- Operational System Overview Steps -->
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

    <!-- Testimonials Layout Section Component -->
    <section style="background-color: #f1f5f9; padding: 5rem 1.5rem; border-top: 1px solid var(--slate-200);">
        <div style="max-width: 1200px; margin: 0 auto; text-align: center;">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold mb-2">User Feedback</span>
            <h2 style="font-size: 2rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem;">What Our Users Say</h2>
            <p style="color: #64748b; font-size: 1rem; max-width: 600px; margin: 0 auto 4rem;">Discover how we are connecting patients directly with authentic local pharmacies securely.</p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
                @foreach($testimonials as $testimonial)
                    <div style="background: #ffffff; padding: 2.25rem; border-radius: 1.25rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); border: 1px solid var(--slate-200); text-align: left; display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                        <!-- Security Patch: Escaped output via double braces prevents arbitrary XSS injection attacks -->
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

    <!-- Dynamic System Notification Mount Node -->
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

        // Toggle search visibility depending on tag list allocation depth
        btn.style.display = list.children.length > 0 ? 'block' : 'none';
        
        // Clean old variable strings inside form scope mapping rules
        form.querySelectorAll('input[name="item_names[]"]').forEach((el) => el.remove());
        
        // Append synchronized deep copies down to processing execution level
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