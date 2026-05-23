@extends('layouts.app')

@section('title', 'Home | Medfinder')

@section('styles')
<link rel="preload" as="image" href="https://images.pexels.com/photos/4386467/pexels-photo-4386467.jpeg?auto=compress&cs=tinysrgb&w=1200">
@endsection

@section('content')
<style>
    :root {
        --primary: #0b5ed7;
        --primary-dark: #0a58ca;
        --primary-soft: #eef2ff;
        --secondary: #00b4aa;
        --dark: #1e2f3e;
        --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .landing-page {
        font-family: 'Inter', sans-serif;
        background: #f8fafc;
        color: #0f172a;
        min-height: 100vh;
    }

    .hero-carousel .carousel-item {
        height: 70vh;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .hero-carousel .carousel-item::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.3));
    }

    .carousel-caption {
        z-index: 2;
        text-align: left;
        left: 8%;
        top: 32%;
    }

    .glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.5rem;
        padding: 2.25rem;
        height: 100%;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);
        transition: var(--transition);
    }

    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px -4px rgba(15, 23, 42, 0.08);
    }

    .primary-bg {
        background: linear-gradient(135deg, #0b5ed7 0%, #0346a7 100%);
        border: none;
        color: white;
    }

    .dark-bg {
        background: linear-gradient(145deg, #1e2f3e 0%, #111c24 100%);
        border: none;
        color: white;
    }

    .tag-item {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 50px;
        padding: 0.45rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
        backdrop-filter: blur(4px);
    }

    .tag-item:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    .badge-stock {
        background: #ecfdf5;
        color: #065f46;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .ad-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 1.5rem;
        height: 100%;
        transition: var(--transition);
    }

    .ad-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
    }

    .howit-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 2rem;
        text-align: center;
        height: 100%;
        transition: var(--transition);
    }

    .step-icon {
        width: 60px;
        height: 60px;
        background: var(--primary-soft);
        color: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        font-size: 1.5rem;
    }

    .spinner-overlay {
        position: fixed;
        inset: 0;
        background: #ffffff;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.4s ease;
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
</style>

<div class="landing-page">
    <div id="spinnerOverlay" class="spinner-overlay">
        <div class="text-center">
            <div class="spinner-border text-primary" style="width: 3.5rem; height: 3.5rem; stroke-width: 3;"></div>
            <p class="mt-3 fw-bold text-slate-700 tracking-wide">Securing Network Connections...</p>
        </div>
    </div>

    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('https://images.pexels.com/photos/4386467/pexels-photo-4386467.jpeg?auto=compress&cs=tinysrgb&w=1600');">
                <div class="carousel-caption">
                    <span class="badge bg-primary px-3 py-2 rounded-pill text-uppercase mb-3 tracking-wider font-semibold" style="font-size: 0.75rem;">Smart Healthcare Access</span>
                    <h1 class="display-3 fw-bold text-white mb-3">Find Medicine<br>Near You. Instantly.</h1>
                    <p class="lead text-white-50 mb-4 max-w-xl">Unified platform connecting local stock intelligence to emergency health demands across Uganda network hubs.</p>
                    <a href="#searchSection" class="btn btn-primary btn-lg rounded-pill px-5 py-3 font-semibold shadow-lg">Start Searching</a>
                </div>
            </div>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold h3 mb-0">Featured <span class="text-primary">Partners & Products</span></h2>
                <div class="carousel-controls">
                    <button class="btn btn-outline-primary btn-sm rounded-circle p-2 px-3 me-1" type="button" data-bs-target="#adCarousel" data-bs-slide="prev">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="btn btn-outline-primary btn-sm rounded-circle p-2 px-3" type="button" data-bs-target="#adCarousel" data-bs-slide="next">
                        <i class="fas fa-chevron-right"></i>
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
                                        <span class="badge bg-danger bg-opacity-10 text-danger mb-3 px-2.5 py-1.5 rounded-md font-semibold">Hospital</span>
                                        <h4 class="fw-bold h5 mb-2">Mulago National Referral</h4>
                                        <p class="text-muted small mb-4">Specialized care and 24/7 emergency infrastructure networks.</p>
                                    </div>
                                    <img src="{{ asset('images/pharmacy3.jpg') }}" alt="pharmacy3" class="img-fluid rounded-3 object-cover" style="height: 160px; w-100;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ad-card d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-danger bg-opacity-10 text-danger mb-3 px-2.5 py-1.5 rounded-md font-semibold">Hospital</span>
                                        <h4 class="fw-bold h5 mb-2">Case Medical Centre</h4>
                                        <p class="text-muted small mb-4">Quality professional healthcare configurations for your entire family.</p>
                                    </div>
                                    <img src="{{ asset('images/pharmacy.jpg') }}" alt="pharmacy" class="img-fluid rounded-3 object-cover" style="height: 160px; w-100;">
                                </div>
                            </div>
                            <div class="col-md-4 d-none d-md-block">
                                <div class="ad-card border-primary border d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-primary mb-3 px-2.5 py-1.5 rounded-md font-semibold">Promoted Drug</span>
                                        <h4 class="fw-bold h5 mb-2">Panadol Extra</h4>
                                        <p class="text-muted small mb-4">Fast-acting advanced chemical composition relief for tough headaches.</p>
                                    </div>
                                    <img src="{{ asset('images/drugs.jpg') }}" class="img-fluid rounded-3 object-cover" style="height: 160px; w-100;" alt="drugs">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item" data-bs-interval="5000">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="ad-card d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-primary mb-3 px-2.5 py-1.5 rounded-md font-semibold">Featured Drug</span>
                                        <h4 class="fw-bold h5 mb-2">Amoxicillin</h4>
                                        <p class="text-muted small mb-4">Wide spectrum antibiotics ready for distribution at checked nodes.</p>
                                    </div>
                                    <img src="{{ asset('images/amoxy.jpg') }}" class="img-fluid rounded-3 object-cover" style="height: 160px; w-100;" alt="amoxy">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ad-card d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-success bg-opacity-10 text-success mb-3 px-2.5 py-1.5 rounded-md font-semibold">Pharmacy</span>
                                        <h4 class="fw-bold h5 mb-2">First Pharmacy</h4>
                                        <p class="text-muted small mb-4">Your neighborhood partner for genuine medicine stocks.</p>
                                    </div>
                                    <img src="https://via.placeholder.com/400x200?text=First+Pharmacy" class="img-fluid rounded-3 object-cover" style="height: 160px; w-100;" alt="Pharmacy">
                                </div>
                            </div>
                            <div class="col-md-4 d-none d-md-block">
                                <div class="ad-card border-warning border d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="badge bg-warning text-dark mb-3 px-2.5 py-1.5 rounded-md font-semibold">Limited Offer</span>
                                        <h4 class="fw-bold h5 mb-2">Vitamin C Boost</h4>
                                        <p class="text-muted small mb-4">Special 20% system offset at all verified Kampala outlets.</p>
                                    </div>
                                    <img src="https://via.placeholder.com/400x200?text=Vitamin+C" class="img-fluid rounded-3 object-cover" style="height: 160px; w-100;" alt="Drug">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5 my-2">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill font-semibold">Simple Process</span>
            <h2 class="fw-bold mt-2 display-6">How MedFinder Works</h2>
            <p class="text-muted">From search analytics to client handoff in three clear execution steps</p>
        </div>
        <div id="howItWorksCarousel" class="carousel slide howit-carousel" data-bs-ride="carousel" data-bs-interval="4000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row g-4 justify-content-center">
                        <div class="col-md-4">
                            <div class="howit-card">
                                <div class="step-icon"><i class="fas fa-search-location"></i></div>
                                <h4 class="fw-bold h5 mb-2">1. Search & Compare</h4>
                                <p class="text-muted small mb-0">Query medical names, parse live stocks & compare prices instantly across 180+ verified pharmacies near you.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="howit-card">
                                <div class="step-icon"><i class="fas fa-hand-holding-heart"></i></div>
                                <h4 class="fw-bold h5 mb-2">2. Reserve Instantly</h4>
                                <p class="text-muted small mb-0">Trigger hold requests to lock down allocation buffers. Complete transactions online or during physical pick-up phases.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="howit-card">
                                <div class="step-icon"><i class="fas fa-truck-fast"></i></div>
                                <h4 class="fw-bold h5 mb-2">3. Fast Delivery/Pickup</h4>
                                <p class="text-muted small mb-0">Deploy same-day dispatch routing or gather item items onsite with full transit logging layers.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5" id="searchSection">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-5">
                <div class="glass-card primary-bg d-flex flex-column justify-content-between p-4 px-md-5">
                    <div>
                        <h3 class="mb-4 fw-bold tracking-tight h4"><i class="bi bi-search me-2"></i> Medicine Search</h3>
                        <div class="input-group bg-white rounded-pill p-1.5 mb-4 shadow-sm border border-white border-opacity-20">
                            <input type="text" id="itemInput" class="form-control border-0 bg-transparent ps-3 text-dark font-medium" placeholder="e.g., Amoxicillin..." style="outline: none; box-shadow: none;">
                            <button type="button" class="btn btn-dark rounded-pill px-4 font-semibold shadow-sm transition hover:bg-slate-800" onclick="addItem()">Add</button>
                        </div>
                        <div id="editableItemList" class="d-flex flex-wrap gap-2 mb-4" style="min-height: 60px;">
                        </div>
                    </div>
                    <form id="searchForm" action="/" method="GET" class="w-100">
                        <button type="submit" id="searchBtn" class="btn btn-light w-100 rounded-pill fw-bold py-3 text-primary shadow-md hover:bg-slate-50 transition" style="display: none;">
                            Find Availability
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="glass-card dark-bg d-flex flex-column justify-content-between p-4 px-md-5">
                    <div>
                        <h3 class="fw-bold mb-4 tracking-tight h4"><i class="bi bi-graph-up-arrow me-2"></i> Live Uganda Network</h3>
                        <div class="row text-center my-auto py-3">
                            <div class="col-4">
                                <h2 class="display-5 fw-bold mb-1 tracking-tight text-white" id="medCount">0</h2>
                                <span class="text-white-50 small tracking-wider uppercase font-semibold">Medicines</span>
                            </div>
                            <div class="col-4 border-start border-end border-white border-opacity-10">
                                <h2 class="display-5 fw-bold mb-1 tracking-tight text-white" id="pharCount">0</h2>
                                <span class="text-white-50 small tracking-wider uppercase font-semibold">Pharmacies</span>
                            </div>
                            <div class="col-4">
                                <h2 class="display-5 fw-bold mb-1 tracking-tight text-white" id="stockCount">0</h2>
                                <span class="text-white-50 small tracking-wider uppercase font-semibold">Stock Units</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-white bg-opacity-5 rounded-3 border border-white border-opacity-5">
                        <p class="small mb-0 text-center text-white-50"><i class="bi bi-info-circle me-1 text-info"></i> Cluster synchronized endpoints refresh every 2 hours natively.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($results->isNotEmpty())
    <div class="container py-4">
        <div class="glass-card p-0 overflow-hidden border-0 shadow-sm">
            <div class="p-4 bg-white border-b border-slate-100 flex items-center">
                <h3 class="fw-bold h4 mb-0 text-slate-800"><i class="bi bi-table me-2 text-primary"></i> Search Results</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Medicine</th>
                            <th>Pharmacy</th>
                            <th>Price</th>
                            <th>Stock</th>

                            @if(isset($results))
                            <div class="container-fluid p-0">
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead class="table-light">
                                            <tr class="text-uppercase tracking-wider text-muted" style="font-size: 0.75rem;">
                                                <th class="ps-4 py-3">Medicine Name</th>
                                                <th class="py-3">Pharmacy Details</th>
                                                <th class="py-3">Price</th>
                                                <th class="py-3">Availability Status</th>
                                                <th class="text-end pe-4 py-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($results as $item)
                                            <tr class="transition hover:bg-slate-50">
                                                <td class="ps-4 py-3.5"><strong class="text-slate-900 font-semibold">{{ $item->medicine_name }}</strong></td>
                                                <td class="py-3.5">
                                                    <div class="fw-semibold text-primary font-semibold mb-0.5">{{ $item->pharmacy_name }}</div>
                                                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $item->pharmacy_location }}</small>
                                                </td>
                                                <td class="py-3.5 font-medium text-slate-700">{{ number_format($item->price, 0) }} UGX</td>
                                                <td class="py-3.5">
                                                    @if($item->quantity > 0)
                                                    <span class="badge-stock">In Stock ({{ $item->quantity }})</span>
                                                    @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-full font-semibold" style="font-size: 0.825rem;">Out of Stock</span>
                                                    @endif
                                                </td>
                                                <td class="text-end pe-4 py-3.5">
                                                    @if(isset($currentUser) && $currentUser->role === 'patient')
                                                    <form action="/reserve/{{ $item->id }}" method="POST">
                                                        @csrf
                                                        <button class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm font-semibold transition hover:bg-primary-dark">Reserve</button>
                                                    </form>
                                                    @elseif(isset($currentUser))
                                                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" type="button" disabled style="font-size: 0.8rem;">
                                                        Patients can reserve
                                                    </button>
                                                    @else
                                                    <a href="/login" class="btn btn-sm btn-outline-primary rounded-pill px-4 font-semibold transition">Login to Reserve</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5 text-muted font-medium">No identical medicines resolved. Try another system string array input query.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @endif

    <div class="container mb-5 py-3">
        <div class="bg-danger text-white rounded-4 p-4 px-md-5 d-flex justify-content-between align-items-center flex-wrap gap-3 shadow-lg border-0" style="background: linear-gradient(135deg, #dc3545 0%, #b01a2b 100%) !important;">
            <div>
                <h4 class="fw-bold mb-1 tracking-tight"><i class="bi bi-telephone-fill me-2"></i> Emergency Support Network</h4>
                <p class="mb-0 opacity-75 small font-medium">Critical medication drop failures resolved through active local cell dispatch lines.</p>
            </div>
            <h2 class="display-6 fw-bold mb-0 tracking-tight">0800 199 199</h2>
        </div>
    </div>

    <div id="toastRoot" class="position-fixed bottom-0 start-0 p-3" style="z-index: 11"></div>
</div>
@endsection

@section('scripts')
<script>
    function showToast(message, type = 'info') {
        const container = document.getElementById('toastRoot');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = 'toast-notify animate__animated animate__fadeInUp';
        toast.style.borderLeftColor = type === 'error' ? '#dc3545' : (type === 'success' ? '#198754' : '#0b5ed7');
        toast.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-1">
                <strong class="text-slate-800" style="font-size: 0.9rem;">${type === 'error' ? 'Warning Alert' : type === 'success' ? 'Success Transaction' : 'System Notice'}</strong>
                <button class="btn-close btn-sm shadow-none" type="button" aria-label="Close" style="font-size: 0.75rem;"></button>
            </div>
            <div class="text-slate-600 small font-medium">${message}</div>
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
            <button class="btn btn-sm p-0 border-0 bg-transparent text-white opacity-75 hover:opacity-100 ms-1 d-flex align-items-center" type="button" aria-label="Remove medicine">
                <i class="bi bi-x-circle-fill" style="font-size: 0.95rem;"></i>
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
            showToast('Please specify a valid medicine identifier payload string', 'error');
            input.focus();
            return;
        }

        mainList.appendChild(createTag(value));
        input.value = '';
        updateSearchBtn();
        showToast(`Added cluster item: "${value}" to execution queue stack`, 'success');
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

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('focus') === 'search' && input) {
            setTimeout(() => {
                input.focus();
                input.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 500);
        }

        updateSearchBtn();
    });
</script>
@endsection