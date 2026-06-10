@extends('layouts.app')

@section('title', 'About | MedFinder')

@section('content')

<!-- 1. Hero Section -->

<section class="hero-section py-5 mb-5">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6 animate-on-scroll">
                <span class="badge bg-primary bg-opacity-10 text-primary mb-3 d-inline-flex align-items-center">
                    <i class="bi bi-heart-pulse-fill me-2"></i> Trusted healthcare search
                </span>
                <h1 class="display-5 fw-bold mb-4">Finding medicine nearby should be fast, simple, and reliable.</h1>
                <p class="lead text-secondary mb-4">
                    MedFinder helps people across Uganda locate available medicines at nearby pharmacies, compare options, and reserve what they need in minutes.
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
                    <img src="{{ asset('images/image 1.png') }}" class="img-fluid rounded-4 hero-img" alt="MedFinder hero image">
                </div>
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

<style>
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
</style>

<!-- 2. Features / Values Section -->
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

<!-- 3. Mission Section -->
<section id="mission" class="container py-5 text-center">
    <div class="animate-on-scroll">
        <h3 class="fw-bold">Our Mission</h3>
        <p class="text-muted mx-auto" style="max-width: 700px;">
            To improve healthcare access in Uganda by connecting patients with pharmacies through a simple, fast, and reliable digital platform.
        </p>
    </div>

    <div class="mt-5 animate-on-scroll">
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-5 py-3 pulse-btn">
            <i class="bi bi-search me-2"></i>Get Started Today
        </a>
    </div>
</section>

@php
$teamMembers = [
['name' => 'Amina N.', 'role' => 'Founder', 'image' => 'images/team-1.jpeg', 'bio' => 'Guides the MedFinder vision and long-term growth.'],
['name' => 'Brian K.', 'role' => 'Lead Developer', 'image' => 'images/team-2.png', 'bio' => 'Builds and improves the core platform experience.'],
['name' => 'Clara S.', 'role' => 'Pharmacist Advisor', 'image' => 'images/team 3.jpeg', 'bio' => 'Supports medicine information and pharmacy workflows.'],
['name' => 'David O.', 'role' => 'Project Coordinator', 'image' => 'images/team-1.jpeg', 'bio' => 'Keeps the team aligned and project delivery on track.'],
['name' => 'Esther M.', 'role' => 'UI/UX Designer', 'image' => 'images/team-2.png', 'bio' => 'Designs simple and easy-to-use patient journeys.'],
['name' => 'Frank P.', 'role' => 'Support Lead', 'image' => 'images/team 3.jpeg', 'bio' => 'Helps patients and pharmacies use the platform smoothly.'],
];
@endphp

<!-- 4. Team Section -->
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
    /* Cleaned up CSS */
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