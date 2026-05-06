@extends('layouts.app')

@section('title', 'Home | Medfinder')

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

    /* Hero Section */
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

    /* Glass Cards */
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

    /* Tags & Badges */
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
</style>

<div class="landing-page">
    <!-- Loading Spinner -->
    <div id="spinnerOverlay" class="spinner-overlay">
        <div class="text-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
            <p class="mt-3 fw-bold text-primary">MedFinder Loading...</p>
        </div>
    </div>

    <!-- Hero Section -->
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

    <!-- Search & Stats Section -->
    <div class="container py-5" id="searchSection">
        <div class="row g-4">
            <!-- Search Card -->
            <div class="col-lg-5">
                <div class="glass-card primary-bg">
                    <h3 class="mb-4 fw-bold"><i class="fas fa-search me-2"></i> Medicine Search</h3>
                    <div class="input-group bg-white rounded-pill p-1 mb-3">
                        <input type="text" id="itemInput" class="form-control border-0 bg-transparent ps-3" placeholder="e.g., Amoxicillin...">
                        <button class="btn btn-dark rounded-pill px-4" onclick="addItem()">Add</button>
                    </div>
                    <div id="editableItemList" class="d-flex flex-wrap gap-2 mb-3" style="min-height: 50px;">
                        <!-- Tags appear here -->
                    </div>
                    <form id="searchForm" action="/" method="GET">
                        <!-- Hidden inputs will be injected here by JS -->
                        <button type="submit" id="searchBtn" class="btn btn-light w-100 rounded-pill fw-bold py-2" style="display: none;">
                            Find Availability
                        </button>
                    </form>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="col-lg-7">
                <div class="glass-card dark-bg">
                    <h3 class="fw-bold mb-4"><i class="fas fa-chart-line me-2"></i> Live Uganda Network</h3>
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
                        <p class="small mb-0 text-center"><i class="fas fa-info-circle me-1"></i> Data syncs with pharmacies every 2 hours.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results -->
    @if (!empty($query) && isset($results))
    <div class="container pb-5">
        <div class="section-title mb-4">
            <span class="badge bg-primary text-white px-3 py-2 rounded-pill">Search Results</span>
            <h2 class="fw-bold mt-2">Availability for "{{ $query }}"</h2>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Medicine</th>
                            <th>Pharmacy</th>
                            <th>Price (UGX)</th>
                            <th>Stock</th>
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
                                <form action="/reserve/{{ $item->id }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-primary rounded-pill px-3">Reserve</button>
                                </form>
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

    <!-- Emergency Support -->
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

<!-- Toast Container -->
<div id="toastRoot" class="position-fixed bottom-0 start-0 p-3" style="z-index: 11"></div>

@endsection

@section('scripts')
<script>
    // Remove spinner on load
    window.addEventListener('load', () => {
        const spinner = document.getElementById('spinnerOverlay');
        spinner.style.opacity = '0';
        setTimeout(() => spinner.remove(), 500);

        // Initial stats animation
        animateCounter('medCount', 2480);
        animateCounter('pharCount', 186);
        animateCounter('stockCount', 58200);
    });

    function animateCounter(id, target) {
        let current = 0;
        const el = document.getElementById(id);
        const step = target / 50;
        const interval = setInterval(() => {
            current += step;
            if (current >= target) {
                el.innerText = Math.round(target).toLocaleString();
                clearInterval(interval);
            } else {
                el.innerText = Math.round(current).toLocaleString();
            }
        }, 30);
    }

    function addItem() {
        const input = document.getElementById('itemInput');
        const list = document.getElementById('editableItemList');
        const val = input.value.trim();

        if (!val) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'tag-item';
        wrapper.innerHTML = `
            <span>${val}</span>
            <i class="fas fa-times-circle text-danger ms-2" style="cursor:pointer" onclick="this.parentElement.remove(); updateSearchBtn();"></i>
            <input type="hidden" name="item_names[]" value="${val}">
        `;

        list.appendChild(wrapper);
        input.value = '';
        updateSearchBtn();
    }

    function updateSearchBtn() {
        const list = document.getElementById('editableItemList');
        const btn = document.getElementById('searchBtn');
        const form = document.getElementById('searchForm');

        const count = list.children.length;
        btn.style.display = count > 0 ? 'block' : 'none';
        btn.innerHTML = `<i class="fas fa-search me-2"></i>Search ${count} Item${count > 1 ? 's' : ''}`;

        // Sync hidden inputs to the form
        form.querySelectorAll('input[type="hidden"]').forEach(el => el.remove());
        list.querySelectorAll('input[type="hidden"]').forEach(inp => form.appendChild(inp.cloneNode(true)));
    }
</script>
@endsection