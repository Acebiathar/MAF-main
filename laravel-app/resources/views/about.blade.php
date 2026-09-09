@extends('layouts.app')

@section('title', 'About | MedFinder')

@section('fullwidth')

<style>
    /* Custom Finder Card Container Profile */
    .finder-card {
        background-color: #0d2847;
        /* Rich deep navy backdrop */
        border-radius: 20px;
    }

    .font-poppins {
        font-family: 'Poppins', sans-serif;
        letter-spacing: -0.5px;
    }

    .max-w-550 {
        max-width: 550px;
    }

    .max-w-600 {
        max-width: 600px;
    }

    .fs-7 {
        font-size: 0.875rem !important;
    }

    /* Input Custom Inner Frame Styling */
    .med-input-wrapper {
        background-color: #ffffff;
        transition: box-shadow 0.2s ease-in-out;
    }

    .med-input-wrapper:focus-within {
        box-shadow: 0 0 0 3px rgba(143, 174, 193, 0.4) !important;
    }

    .med-input-wrapper input:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    .med-input-wrapper input::placeholder {
        color: #adb5bd;
    }

    /* Action Trigger Search Button Styling */
    .search-btn {
        background-color: #8faec1 !important;
        /* Steel-blue / slate matching the wireframes */
        color: #0d2847 !important;
        border: none !important;
        border-radius: 6px !important;
        transition: background-color 0.2s ease;
    }

    .search-btn:hover {
        background-color: #7d9cb0 !important;
        color: #0d2847 !important;
    }

    /* Utility Helpers */
    .text-white-700 {
        color: #e5e7eb;
    }

    .font-medium {
        font-weight: 500;
    }

    .stats-row {
        margin-top: 1.5rem;
    }

    .about-hero {
        padding-bottom: 4rem;
    }

    .mission-card {
        background-color: #ffffff;
        border-radius: 24px;
        padding: 2rem;
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        min-height: 100%;
    }

    .mission-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 55px rgba(15, 23, 42, 0.12);
    }

    .mission-icon {
        width: 58px;
        height: 58px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        background-color: rgba(56, 103, 222, 0.1);
        color: #3867DE;
        font-size: 1.35rem;
        margin-bottom: 1.25rem;
    }
</style>

<section class="hero-section about-hero py-5 mb-5">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6 animate-on-scroll">
                <span class="badge bg-primary bg-opacity-10 text-primary mb-3 d-inline-flex align-items-center">
                    <i class="bi bi-heart-pulse-fill me-2"></i> About MedFinder UG
                </span>
                <h1 class="display-5 fw-bold mb-4">Finding medicine nearby should be fast, simple, and reliable.</h1>
                <p class="lead text-secondary mb-4">
                    MedFinder is a health-tech platform designed to eliminate the exhausting and risky guesswork of finding prescription medications in Uganda. Born to solve the emotional and financial strain caused by fragmented supply chains, MedFinder connects patients directly with real-time pharmacy inventories. By turning an unpredictable, door-to-door search into a transparent digital process, the platform empowers users to instantly locate, compare, and reserve their essential medicines—saving vital time and bringing peace of mind when it matters most.
                </p>

                <div class="d-flex flex-column flex-sm-row gap-3">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#medSearchModal" class="btn btn-primary btn-lg px-5 py-3">
                        Start Searching
                    </button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#medSearchModal" class="btn btn-outline-primary btn-lg px-5 py-3">
                        Get Started
                    </button>
                </div>
            </div>

            <div class="col-lg-6 animate-on-scroll">
                <div class="hero-card position-relative overflow-hidden rounded-4 shadow-sm">
                    <img src="{{ asset('images/phone.png') }}" class="w-100 vh-50 rounded-4 hero-img" style="object-fit: cover;" alt="MedFinder hero image">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container mt-5 mb-5 py-5">
    <div class="text-center mb-5">
        <h2 class="section-title">Mission, Vision & Core Values</h2>
        <div class="section-divider"></div>
        <p class="text-secondary mx-auto" style="max-width: 600px;">Guiding principles that drive everything we do at MedFinder</p>
    </div>

    <div class="row g-4">
        <!-- Mission -->
        <div class="col-md-6 animate-fade-up delay-1">
            <div class="mission-card">
                <div class="mission-icon">
                    <i class="bi bi-bullseye"></i>
                </div>
                <h3 class="h4 fw-bold text-navy mb-3">Our Mission</h3>
                <p class="text-secondary">
                    To revolutionize healthcare accessibility in Uganda by leveraging intuitive digital solutions that connect patients with trusted pharmacies, ensuring swift, reliable, and stress-free access to life-saving medications.
                </p>
            </div>
        </div>

        <!-- Vision -->
        <div class="col-md-6 animate-fade-up delay-2">
            <div class="mission-card">
                <div class="mission-icon">
                    <i class="bi bi-eye"></i>
                </div>
                <h3 class="h4 fw-bold text-navy mb-3">Our Vision</h3>
                <p class="text-secondary">
                    To become East Africa's most trusted health-tech bridge, creating a seamless ecosystem where no patient ever leaves a clinic wondering if or where they will find their prescribed care.
                </p>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="medSearchModal" tabindex="-1" aria-labelledby="medSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-med-modal border-0 p-4 position-relative">

            <button type="button" class="btn-close custom-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="modal-body text-center p-0 mt-2">
                <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                    <i class="bi bi-heart-pulse-fill text-primary fs-4"></i>
                    <span class="fw-bold text-dark fs-5" style="font-family: 'Poppins', sans-serif; letter-spacing: -0.5px;">MedFinder</span>
                </div>

                <h3 class="fw-bold text-navy mb-4" id="medSearchModalLabel">What medication do you need?</h3>

                <form action="{{ route('index') }}" method="GET">
                    <div class="position-relative mb-4">
                        <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control custom-modal-input ps-5" placeholder="What med are you looking for?" required>
                    </div>

                    <button type="submit" class="btn custom-modal-btn w-100 fw-semibold mb-4 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-geo-alt-fill"></i> Find Your Meds
                    </button>
                </form>

                <div class="d-flex align-items-center justify-content-center gap-2 pt-2">
                    <span class="text-dark fw-semibold text-decoration-underline" style="font-size: 0.95rem;">Excellent</span>
                    <div class="d-flex gap-1 rating-stars">
                        <span class="star-box">★</span>
                        <span class="star-box">★</span>
                        <span class="star-box">★</span>
                        <span class="star-box">★</span>
                        <span class="star-box semi-star">★</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<section class="bg-light py-5 rounded-4">
    <div class="container text-center">
        <h3 class="fw-bold mb-3 animate-on-scroll">Why Choose MedFinder?</h3>
        <p class="text-muted mb-5 animate-on-scroll">
            Designed to improve healthcare access and reduce stress when searching for medicine.
        </p>

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="p-4 shadow-sm rounded bg-white h-100 feature-card animate-on-scroll">
                    <i class="bi bi-search text-primary fs-2 icon-bounce"></i>
                    <h5 class="mt-3">Easy Search</h5>
                    <p class="text-muted">Quickly find medicines available near you without moving around.</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="p-4 shadow-sm rounded bg-white h-100 feature-card animate-on-scroll">
                    <i class="bi bi-geo-alt text-primary fs-2 icon-bounce"></i>
                    <h5 class="mt-3">Location Based</h5>
                    <p class="text-muted">Discover nearby pharmacies based on your location.</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="p-4 shadow-sm rounded bg-white h-100 feature-card animate-on-scroll">
                    <i class="bi bi-clock text-primary fs-2 icon-bounce"></i>
                    <h5 class="mt-3">Save Time</h5>
                    <p class="text-muted">No more walking pharmacy to pharmacy searching for drugs.</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="p-4 shadow-sm rounded bg-white h-100 feature-card animate-on-scroll">
                    <i class="bi bi-shield-check text-primary fs-2 icon-bounce"></i>
                    <h5 class="mt-3">Reliable Info</h5>
                    <p class="text-muted">Get accurate and updated medicine availability.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@php
$teamMembers = [
['name' => 'Abiathar s.', 'role' => 'Project Manager', 'image' => 'images/team 4.jpeg', 'bio' => 'Guides the MedFinder vision and long-term growth.'],
['name' => 'Lamulah N.', 'role' => 'UI/UX Designer', 'image' => 'images/team-2.png', 'bio' => 'Builds and improves the core platform experience.'],
['name' => 'Stellah N.', 'role' => 'lead developer ', 'image' => 'images/team-1.jpeg', 'bio' => 'Supports medicine information and pharmacy workflows.'],
['name' => 'Ruth T .', 'role' => 'Project Coordinator', 'image' => 'images/team 3.jpeg', 'bio' => 'Keeps the team aligned and project delivery on track.'],
['name' => 'Patricia T.', 'role' => 'Project advisor', 'image' => 'images/team 5.jpeg', 'bio' => 'Designs simple and easy-to-use patient journeys.'],
['name' => 'Frank P.', 'role' => 'Support Lead', 'image' => 'images/team 3.jpeg', 'bio' => 'Helps patients and pharmacies use the platform smoothly.'],
];
@endphp

<section class="bg-light py-5 mb-5 rounded-4">
    <div class="container text-center">
        <h3 class="fw-bold mb-3 animate-on-scroll">Meet Our Team</h3>
        <p class="text-muted mb-5 animate-on-scroll">
            The people behind MedFinder working to improve access to medicine in Uganda.
        </p>

        <div class="row g-4 justify-content-center">
            @foreach ($teamMembers as $member)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 team-card animate-on-scroll">
                    <img src="{{ asset($member['image']) }}" class="card-img-top team-img team-img-pos-{{ $loop->iteration }}" alt="{{ $member['name'] }}">
                    <div class="card-body">
                        <h5 class="fw-bold mb-1">{{ $member['name'] }}</h5>
                        <p class="text-primary mb-2">{{ $member['role'] }}</p>
                        <p class="text-muted small">{{ $member['bio'] }}</p>
                        <div class="social-links mt-3">
                            <a href="#" class="text-primary me-3"><i class="bi bi-linkedin fs-5"></i></a>
                            <a href="#" class="text-primary me-3"><i class="bi bi-envelope fs-5"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 animate-on-scroll">
            <div class="finder-card p-5 text-center text-white position-relative shadow">

                <div class="hero-header mb-4">
                    <h2 class="fw-bold mb-2 display-6 font-poppins">Ready to find a pharmacy?</h2>
                    <p class="subhead text-white-50 mx-auto max-w-550 fs-6">
                        Start your search today and we'll find a pharmacy with your medication in stock.
                    </p>
                </div>

                <div class="search-section mx-auto max-w-600">
                    <form id="searchForm" action="{{ url('/') }}" method="GET" class="m-0">
                        <div class="input-group bg-white rounded-4 p-2 border border-white border-2 shadow">
                            <span class="input-group-text border-0 bg-transparent ps-3 pe-2">
                                <i class="bi bi-capsule" style="font-size: 1.1rem; color: #64748b;"></i>
                            </span>

                            <input type="text"
                                name="search"
                                id="itemInput"
                                value="{{ request('search') }}"
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

                    <div class="stats-row d-flex flex-wrap justify-content-center gap-4 mb-4 text-white fs-7 font-medium">
                        <div class="stat-item d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span class="stat-text">99% success rate</span>
                        </div>
                        <div class="stat-item d-flex align-items-center gap-2">
                            <i class="bi bi-lightning-charge-fill text-warning"></i>
                            <span class="stat-text">Fast turn up</span>
                        </div>
                        <div class="stat-item d-flex align-items-center gap-2">
                            <i class="bi bi-telephone-x-fill text-danger"></i>
                            <span class="stat-text">Never call another pharmacy</span>
                        </div>
                    </div>

                    <div id="resultPanel" class="result-area mb-3"></div>

                    <div class="footnote text-white-50 small border-top border-white border-opacity-10 pt-3">
                        <i class="bi bi-star-fill text-warning me-1"></i> Real-time stock check · Verified network pharmacies · No wait
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll Animation Observer
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
    });
</script>

<style>
    /* Component & Base Utility Classes */
    .text-navy {
        color: #0b2f5c;
    }

    .custom-med-modal {
        border-radius: 20px !important;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15) !important;
        background-color: #ffffff;
        max-width: 440px;
        margin: 0 auto;
    }

    .custom-modal-close {
        position: absolute;
        top: 24px;
        right: 24px;
        opacity: 0.6;
        z-index: 10;
    }

    .custom-modal-input {
        border: 2px solid #1a1a1a !important;
        border-radius: 14px !important;
        padding: 14px 16px !important;
        font-size: 1rem;
    }

    .custom-modal-input:focus {
        box-shadow: none !important;
        border-color: #0b2f5c !important;
    }

    .custom-modal-btn {
        background-color: #e2f7f5 !important;
        color: #4a7a77 !important;
        border: none !important;
        border-radius: 12px !important;
        padding: 14px !important;
        font-size: 1rem;
        transition: all 0.2s ease-in-out;
    }

    .custom-modal-btn:hover {
        background-color: #d1f2ef !important;
        color: #355c59 !important;
    }

    .rating-stars .star-box {
        background-color: #00b67a;
        color: white;
        padding: 2px 5px;
        font-size: 0.75rem;
        border-radius: 3px;
    }

    .rating-stars .semi-star {
        position: relative;
        background: linear-gradient(90deg, #00b67a 70%, #cccccc 70%);
    }

    /* Layout & Animation Styles */
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease-out;
    }

    .animate-on-scroll.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .feature-card {
        transition: all 0.3s ease;
        border: none;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .team-card {
        border-radius: 20px;
        overflow: hidden;
        transition: 0.3s;
    }

    .team-img {
        height: 300px;
        object-fit: cover;
    }

    .hero-section {
        background: linear-gradient(135deg, #f4f8ff 0%, #e7efff 100%);
        border-radius: 32px;
    }

    .about-hero {
        min-height: calc(100vh - 100px);
        display: flex;
        align-items: center;
    }

    @media (max-width: 991.98px) {
        .about-hero {
            min-height: auto;
            padding-top: 4rem;
            padding-bottom: 4rem;
        }
    }

    .hero-card-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(13, 110, 253, 0.85);
        color: white;
    }
</style>
@endsection