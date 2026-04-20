@extends('layouts.app')

@section('title', 'About | MedFinder')

@section('content')

<!-- Page Header -->
<div class="container py-5 text-center">
    <h1 class="fw-bold text-primary animate-on-scroll">About MedFinder</h1>
    <p class="text-muted animate-on-scroll">
        Helping people in Uganda easily find available medicine from nearby pharmacies.
    </p>
</div>

<!-- About Section -->
<section class="container mb-5">
    <div class="row align-items-center">

        <!-- Text -->
        <div class="col-lg-6 mb-4 animate-on-scroll">
            <h2 class="fw-bold">Making Medicine Access Simple</h2>
            <p class="lead text-secondary">
                MedFinder (MAF) is a web-based platform designed to connect patients with pharmacies that have the medicine they need.
            </p>

            <p>
                Many people waste time moving from one pharmacy to another searching for medicine.
                MedFinder solves this by allowing users to quickly check availability, compare options, and save time.
            </p>

            <!-- Stats -->
            <div class="row text-center mt-4">
                <div class="col-4">
                    <h4 class="fw-bold text-primary counter" data-target="100">0</h4>
                    <small>Pharmacies</small>
                </div>
                <div class="col-4">
                    <h4 class="fw-bold text-primary counter" data-target="1000">0</h4>
                    <small>Users</small>
                </div>
                <div class="col-4">
                    <h4 class="fw-bold text-primary">24/7</h4>
                    <small>Access</small>
                </div>
            </div>
        </div>

        <!-- Image -->
        <div class="col-lg-6 animate-on-scroll">
            <img src="{{ asset('images/image 1.png') }}" class="img-fluid rounded shadow hover-lift" alt="Pharmacy">
        </div>

    </div>
</section>

<!-- Features / Values -->
<section class="bg-light py-5">
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
                    <p class="text-muted">
                        Quickly find medicines available near you without moving around.
                    </p>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="p-4 shadow-sm rounded bg-white h-100 feature-card animate-on-scroll">
                    <i class="bi bi-geo-alt text-primary fs-2 icon-bounce"></i>
                    <h5 class="mt-3">Location Based</h5>
                    <p class="text-muted">
                        Discover nearby pharmacies based on your location.
                    </p>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="p-4 shadow-sm rounded bg-white h-100 feature-card animate-on-scroll">
                    <i class="bi bi-clock text-primary fs-2 icon-bounce"></i>
                    <h5 class="mt-3">Save Time</h5>
                    <p class="text-muted">
                        No more walking pharmacy to pharmacy searching for drugs.
                    </p>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="p-4 shadow-sm rounded bg-white h-100 feature-card animate-on-scroll">
                    <i class="bi bi-shield-check text-primary fs-2 icon-bounce"></i>
                    <h5 class="mt-3">Reliable Info</h5>
                    <p class="text-muted">
                        Get accurate and updated medicine availability.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="container py-5 text-center">
    <div class="animate-on-scroll">
        <h3 class="fw-bold">Our Mission</h3>
        <p class="text-muted mx-auto" style="max-width: 700px;">
            To improve healthcare access in Uganda by connecting patients with pharmacies through a simple, fast, and reliable digital platform.
        </p>
    </div>

    <!-- Interactive CTA -->
    <div class="mt-5 animate-on-scroll">
        <a href="/?focus=search" class="btn btn-primary btn-lg px-5 py-3 pulse-btn">
            <i class="bi bi-search me-2"></i>Get Started Today
        </a>
    </div>
</section>

<!-- Team Section -->
<section class="bg-light py-5">
    <div class="container text-center">

        <h3 class="fw-bold mb-3 animate-on-scroll">Meet Our Team</h3>
        <p class="text-muted mb-5 animate-on-scroll">
            The people behind MedFinder working to improve access to medicine in Uganda.
        </p>

        <div class="row justify-content-center">

            <!-- Team Member 1 -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100 team-card animate-on-scroll">
                    <img src="{{ asset('images/team1.jpg') }}" class="card-img-top team-img" alt="Team Member">
                    <div class="card-body">
                        <h5 class="fw-bold mb-1">Your Name</h5>
                        <p class="text-primary mb-2">System Developer</p>
                        <p class="text-muted small">
                            Responsible for designing and developing the MedFinder system.
                        </p>
                        <div class="social-links mt-3">
                            <a href="#" class="text-primary me-3"><i class="bi bi-linkedin fs-5"></i></a>
                            <a href="#" class="text-primary me-3"><i class="bi bi-github fs-5"></i></a>
                            <a href="#" class="text-primary"><i class="bi bi-envelope fs-5"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Member 2 -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100 team-card animate-on-scroll">
                    <img src="{{ asset('images/team2.jpg') }}" class="card-img-top team-img" alt="Team Member">
                    <div class="card-body">
                        <h5 class="fw-bold mb-1">Member Name</h5>
                        <p class="text-primary mb-2">UI/UX Designer</p>
                        <p class="text-muted small">
                            Designed the user interface to ensure the system is easy to use.
                        </p>
                        <div class="social-links mt-3">
                            <a href="#" class="text-primary me-3"><i class="bi bi-linkedin fs-5"></i></a>
                            <a href="#" class="text-primary me-3"><i class="bi bi-dribbble fs-5"></i></a>
                            <a href="#" class="text-primary"><i class="bi bi-envelope fs-5"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Member 3 -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100 team-card animate-on-scroll">
                    <img src="{{ asset('images/team3.jpg') }}" class="card-img-top team-img" alt="Team Member">
                    <div class="card-body">
                        <h5 class="fw-bold mb-1">Member Name</h5>
                        <p class="text-primary mb-2">Project Coordinator</p>
                        <p class="text-muted small">
                            Managed project planning and ensured smooth development progress.
                        </p>
                        <div class="social-links mt-3">
                            <a href="#" class="text-primary me-3"><i class="bi bi-linkedin fs-5"></i></a>
                            <a href="#" class="text-primary me-3"><i class="bi bi-twitter fs-5"></i></a>
                            <a href="#" class="text-primary"><i class="bi bi-envelope fs-5"></i></a>
                        </div>
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
        // Counter Animation
        function animateCounters() {
            const counters = document.querySelectorAll('.counter');
            const speed = 200;

            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const increment = target / speed;

                function updateCount() {
                    const count = +counter.innerText;
                    if (count < target) {
                        counter.innerText = Math.ceil(count + increment);
                        setTimeout(updateCount, 1);
                    } else {
                        counter.innerText = target + '+';
                    }
                }

                updateCount();
            });
        }

        // Scroll Animation Observer
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all animate-on-scroll elements
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Trigger counter animation when stats section is visible
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    statsObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.5
        });

        const statsSection = document.querySelector('.row.text-center.mt-4');
        if (statsSection) {
            statsObserver.observe(statsSection);
        }

        // Feature card hover effects
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
                this.style.boxShadow = '0 15px 35px rgba(0,0,0,0.1)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 6px rgba(0,0,0,0.07)';
            });
        });

        // Team card hover effects
        document.querySelectorAll('.team-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-15px)';
                this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.15)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 6px rgba(0,0,0,0.07)';
            });
        });

        // Pulse button effect
        const pulseBtn = document.querySelector('.pulse-btn');
        if (pulseBtn) {
            setInterval(() => {
                pulseBtn.classList.add('pulse-animation');
                setTimeout(() => {
                    pulseBtn.classList.remove('pulse-animation');
                }, 1000);
            }, 3000);
        }
    });
</script>

<style>
    /* Scroll Animation Styles */
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease-out;
    }

    .animate-on-scroll.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Hover Effects */
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
    }

    .feature-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .icon-bounce {
        transition: transform 0.3s ease;
    }

    .feature-card:hover .icon-bounce {
        transform: scale(1.1) rotate(5deg);
    }

    /* Team Card Styles */
    .team-card {
        transition: all 0.3s ease;
        cursor: pointer;
        overflow: hidden;
    }

    .team-img {
        height: 250px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .team-card:hover .team-img {
        transform: scale(1.05);
    }

    .social-links a {
        transition: all 0.3s ease;
        display: inline-block;
    }

    .social-links a:hover {
        transform: translateY(-3px);
        color: #0d6efd !important;
    }

    /* Pulse Button */
    .pulse-btn {
        position: relative;
        overflow: hidden;
    }

    .pulse-animation {
        animation: pulse 1s ease-in-out;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        }

        50% {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.5);
        }

        100% {
            transform: scale(1);
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        }
    }

    /* Staggered animation delays */
    .animate-on-scroll:nth-child(1) {
        transition-delay: 0.1s;
    }

    .animate-on-scroll:nth-child(2) {
        transition-delay: 0.2s;
    }

    .animate-on-scroll:nth-child(3) {
        transition-delay: 0.3s;
    }

    .animate-on-scroll:nth-child(4) {
        transition-delay: 0.4s;
    }

    .animate-on-scroll:nth-child(5) {
        transition-delay: 0.5s;
    }
</style>
@endsection