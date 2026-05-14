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
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-5 py-3">
                        Start Searching
                    </a>
                    <a href="#mission" class="btn btn-outline-primary btn-lg px-5 py-3">
                        Learn More
                    </a>
                </div>
            </div>

            <div class="col-lg-6 animate-on-scroll">
                <div class="hero-card position-relative overflow-hidden rounded-4 shadow-sm">
                    <img src="{{ asset('images/image 1.png') }}" class="img-fluid rounded-4 hero-img" alt="MedFinder hero image">
                    <div class="hero-card-overlay p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="text-white mb-0">200+ pharmacies</h6>
                                <small class="text-white-50">Connected in your area</small>
                            </div>
                            <i class="bi bi-check-circle-fill fs-2 text-white"></i>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-white mb-0">24/7 access</h6>
                                <small class="text-white-50">Search anytime</small>
                            </div>
                            <div class="badge bg-white text-primary py-2 px-3 rounded-pill">Fast results</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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