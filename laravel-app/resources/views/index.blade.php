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
        --transition: all 0.25s ease;
    }

    .landing-page {
        font-family: 'Inter', sans-serif;
        background: #f6f9fe;
        color: #1b2e3c;
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
        background: linear-gradient(to right, rgba(0, 0, 0, 0.7), transparent);
    }

    .carousel-caption {
        z-index: 2;
        text-align: left;
        left: 10%;
        top: 30%;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(8px);
        border-radius: 1.5rem;
        padding: 2rem;
        height: 100%;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        transition: var(--transition);
    }

    .glass-card:hover {
        transform: translateY(-5px);
    }

    .primary-bg {
        background: linear-gradient(135deg, var(--primary), #0d47a1);
        color: white;
    }

    .dark-bg {
        background: linear-gradient(145deg, #1e2f3e, #15262e);
        color: white;
    }

    .tag-item {
        background: rgba(13, 110, 253, 0.12);
        color: var(--primary);
        border-radius: 40px;
        padding: 0.4rem 1rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 5px;
    }

    .badge-stock {
        background: #e0f7e8;
        color: #1e7b48;
        padding: 0.35rem 0.9rem;
        border-radius: 50px;
        font-weight: 600;
    }

    .spinner-overlay {
        position: fixed;
        inset: 0;
        background: white;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.5s;
    }

    .toast-notify {
        width: min(360px, calc(100vw - 2rem));
        background: #fff;
        border-left: 4px solid var(--primary);
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        padding: 1rem;
        margin-bottom: 0.75rem;
    }
</style>

<div class="landing-page">
    <div id="spinnerOverlay" class="spinner-overlay">
        <div class="text-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
            <p class="mt-3 fw-bold text-primary">MedFinder Loading...</p>
        </div>
    </div>

    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('https://images.pexels.com/photos/4386467/pexels-photo-4386467.jpeg?auto=compress&cs=tinysrgb&w=1600');">
                <div class="carousel-caption">
                    <h5 class="text-white text-uppercase">Smart Healthcare Access</h5>
                    <h1 class="display-3 fw-bold text-white">Find Medicine<br>Near You. Instantly.</h1>
                    <a href="#searchSection" class="btn btn-primary btn-lg rounded-pill mt-3 px-5">Start Searching</a>
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
            </div>
        </div>
    </div>

    <!-- Search & Statistics Section -->
    <div class="container py-5" id="searchSection">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="glass-card primary-bg">
                    <h3 class="mb-4 fw-bold"><i class="bi bi-search me-2"></i> Medicine Search</h3>
                    <div class="input-group bg-white rounded-pill p-1 mb-3">
                        <input type="text" id="itemInput" class="form-control border-0 bg-transparent ps-3" placeholder="e.g., Amoxicillin...">
                        <button type="button" class="btn btn-dark rounded-pill px-4" onclick="addItem()">Add</button>
                    </div>
                    <div id="editableItemList" class="d-flex flex-wrap gap-2 mb-3" style="min-height: 50px;">
                    </div>
                    <form id="searchForm" action="/" method="GET">
                        <button type="submit" id="searchBtn" class="btn btn-light w-100 rounded-pill fw-bold py-2" style="display: none;">
                            Find Availability
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="glass-card dark-bg">
                    <h3 class="fw-bold mb-4"><i class="bi bi-graph-up-arrow me-2"></i> Live Uganda Network</h3>
                    <div class="row text-center mt-4">
                        <div class="col-4">
                            <h2 class="fw-bold mb-0" id="medCount">0</h2>
                            <small class="opacity-75">Medicines</small>
                        </div>
                        <div class="col-4 border-start border-end border-white-20">
                            <h2 class="fw-bold mb-0" id="pharCount">0</h2>
                            <small class="opacity-75">Pharmacies</small>
                        </div>
                        <div class="col-4">
                            <h2 class="fw-bold mb-0" id="stockCount">0</h2>
                            <small class="opacity-75">Stock Units</small>
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-white bg-opacity-10 rounded-4">
                        <p class="small mb-0 text-center"><i class="bi bi-info-circle me-1"></i> Data syncs with pharmacies every 2 hours.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @if($results->isNotEmpty())
    <div class="container py-5">
        <div class="glass-card">
            <h3 class="fw-bold mb-4"><i class="bi bi-table me-2"></i> Search Results</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Medicine</th>
                            <th>Pharmacy</th>
                            <th>Price</th>
                            <th>Stock</th>

                            <!-- ========== MEDICINE RESULTS TABLE SECTION ========== -->
                            @if(isset($results))
                            <div class="container pb-5">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                    <div class="table-responsive">
                                        <table class="table align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-4">Medicine Name</th>
                                                    <th>Pharmacy Details</th>
                                                    <th>Price</th>
                                                    <th>Availability Status</th>
                                                    >>>>>>> 465936978b381bee5ce01252723434e57e26c4ea
                                                    <th class="text-end pe-4">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($results as $item)
                                                <tr>
                                                    <td class="ps-4"><strong>{{ $item->medicine_name }}</strong></td>
                                                    <td>
                                                        <div class="fw-semibold text-primary">{{ $item->pharmacy_name }}</div>
                                                        <small class="text-muted">{{ $item->pharmacy_location }}</small>
                                                    </td>
                                                    <td>{{ number_format($item->price, 0) }} UGX</td>
                                                    <td>
                                                        @if($item->quantity > 0)
                                                        <span class="badge-stock">In Stock ({{ $item->quantity }})</span>
                                                        @else
                                                        <span class="badge bg-light text-danger">Out of Stock</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end pe-4">
                                                        @if(isset($currentUser) && $currentUser->role === 'patient')
                                                        <form action="/reserve/{{ $item->id }}" method="POST">
                                                            @csrf
                                                            <button class="btn btn-sm btn-primary rounded-pill px-3">Reserve</button>
                                                        </form>
                                                        @elseif(isset($currentUser))
                                                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" type="button" disabled>
                                                            Patients can reserve
                                                        </button>
                                                        @else
                                                        <a href="/login" class="btn btn-sm btn-primary rounded-pill px-3">Login to Reserve</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5 text-muted">No medicines found. Try another search.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <<<<<<< HEAD

                                <div class="container mb-5">
                                <div class="bg-danger text-white rounded-4 p-4 d-flex justify-content-between align-items-center flex-wrap">
                                    <div>
                                        <h4 class="fw-bold mb-1"><i class="bi bi-telephone-fill me-2"></i> Emergency Support</h4>
                                        <p class="mb-0 opacity-75">Need urgent medication? Call our 24/7 hotline.</p>
                                    </div>
                                    <h2 class="fw-bold mb-0">0800 199 199</h2>
                                    =======
                                    <div class="container mb-5">
                                        <div class="bg-danger text-white rounded-4 p-4 d-flex justify-content-between align-items-center flex-wrap">
                                            <div>
                                                <h4 class="fw-bold mb-1"><i class="bi bi-telephone-fill me-2"></i> Emergency Support</h4>
                                                <p class="mb-0 opacity-75">Need urgent medication? Call our 24/7 hotline.</p>
                                                >>>>>>> 465936978b381bee5ce01252723434e57e26c4ea
                                            </div>
                                        </div>
                                    </div>

                                    <div id="toastRoot" class="position-fixed bottom-0 start-0 p-3" style="z-index: 11"></div>
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
            <div class="d-flex justify-content-between align-items-center">
                <strong>${type === 'error' ? 'Warning' : type === 'success' ? 'Success' : 'Info'}</strong>
                <button class="btn-close btn-sm" type="button" aria-label="Close"></button>
            </div>
            <div class="mt-1">${message}</div>
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
            <button class="btn btn-sm p-0 border-0 bg-transparent text-danger" type="button" aria-label="Remove medicine">
                <i class="bi bi-x-circle-fill"></i>
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
                                                showToast('Please enter a medicine name', 'error');
                                                input.focus();
                                                return;
                                            }

                                            mainList.appendChild(createTag(value));
                                            input.value = '';
                                            updateSearchBtn();
                                            showToast(`Added "${value}" to the search list`, 'success');
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