@extends('layouts.app')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .landing-page {
        font-family: 'Inter', sans-serif;
        background: #f6f9fe;
        color: #1b2e3c;
        scroll-behavior: smooth;
        overflow-x: hidden;
        min-height: 100vh;
    }

    :root {
        --primary: #0b5ed7;
        --primary-dark: #0a58ca;
        --primary-soft: #eef2ff;
        --secondary: #00b4aa;
        --accent: #ff8744;
        --dark: #1e2f3e;
        --light: #ffffff;
        --shadow-sm: 0 8px 20px rgba(0, 20, 50, 0.05);
        --shadow-md: 0 20px 35px -8px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 25px 45px -12px rgba(0, 0, 0, 0.15);
        --transition: all 0.25s ease;
    }

    /* navbar refinements */
    .site-navbar.navbar {
        backdrop-filter: blur(14px);
        background: rgba(255, 255, 255, 0.94) !important;
        box-shadow: 0 2px 18px rgba(0, 0, 0, 0.02);
        padding: 0.7rem 0;
    }

    .site-navbar .navbar-brand {
        font-weight: 800;
        font-size: 1.8rem;
        background: linear-gradient(135deg, #0b5ed7, #3b82f6);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .btn-primary {
        background: var(--primary);
        border-radius: 60px;
        padding: 0.6rem 1.6rem;
        font-weight: 600;
        transition: var(--transition);
        box-shadow: 0 4px 10px rgba(11, 94, 215, 0.2);
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    /* carousel */
    .hero-carousel .carousel-item {
        height: 88vh;
        min-height: 550px;
        background-size: cover;
        background-position: center 30%;
        position: relative;
    }

    .hero-carousel .carousel-item::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(125deg, rgba(0, 0, 0, 0.55), rgba(0, 20, 40, 0.4));
    }

    .carousel-caption {
        z-index: 2;
        bottom: 18%;
        text-align: left;
        left: 8%;
        right: auto;
    }

    .display-1-custom {
        font-size: 4rem;
        font-weight: 800;
        letter-spacing: -1px;
    }

    .hero-search-bar .input-group {
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
        border-radius: 80px;
        overflow: hidden;
    }

    /* glass cards */
    .glass-card {
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(4px);
        border-radius: 2rem;
        padding: 2rem;
        transition: var(--transition);
        height: 100%;
        box-shadow: var(--shadow-sm);
    }

    .glass-card.primary-bg {
        background: linear-gradient(135deg, #0b5ed7, #0d47a1);
        color: white;
    }

    .glass-card.dark-bg {
        background: linear-gradient(145deg, #1e2f3e, #15262e);
        color: white;
    }

    .glass-card.accent-bg {
        background: linear-gradient(125deg, #00b4aa, #00897b);
        color: white;
    }

    .tag-item {
        background: rgba(13, 110, 253, 0.12);
        color: var(--primary);
        border-radius: 40px;
        padding: 0.45rem 1rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .hero-tag-item {
        background: black(255, 255, 255, 0.92);
        backdrop-filter: blur(4px);
        border-radius: 60px;
        padding: 0.45rem 1rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* result table */
    .modern-table-card {
        background: blue;
        border-radius: 1.8rem;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .badge-stock {
        background: #e0f7e8;
        color: #1e7b48;
        font-weight: 600;
        padding: 0.35rem 0.9rem;
        border-radius: 50px;
    }

    .pharmacy-pill-modern {
        background: white;
        border-radius: 80px;
        padding: 0.8rem 0.5rem;
        transition: all 0.2s;
        border: 1px solid #eef2f6;
    }

    .pharmacy-pill-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 24px -8px rgba(0, 0, 0, 0.08);
    }

    .counter {
        font-size: 2.4rem;
        font-weight: 800;
        line-height: 1;
    }

    /* ad cards */
    .ad-card {
        background: white;
        border-radius: 1.5rem;
        overflow: hidden;
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
        border: 1px solid rgba(0, 0, 0, 0.03);
        height: 100%;
    }

    .ad-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .ad-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        color: white;
        font-size: 0.7rem;
        padding: 0.2rem 0.7rem;
        border-radius: 30px;
        font-weight: 500;
        z-index: 2;
    }

    .pharmacy-ad-card {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-left: 6px solid var(--primary);
        transition: var(--transition);
    }

    .pharmacy-ad-card:hover {
        background: white;
        transform: translateX(5px);
    }

    .discount-chip {
        background: #ffedd5;
        color: #b45309;
        border-radius: 50px;
        padding: 0.2rem 0.8rem;
        font-size: 0.7rem;
        font-weight: 700;
    }

    /* back to top */
    .back-to-top {
        position: fixed;
        bottom: 28px;
        right: 28px;
        width: 48px;
        height: 48px;
        background: var(--primary);
        border-radius: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        opacity: 0;
        visibility: hidden;
        transition: var(--transition);
        z-index: 99;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    .back-to-top.show {
        opacity: 1;
        visibility: visible;
    }

    /* toast */
    .toast-container-custom {
        position: fixed;
        bottom: 30px;
        left: 30px;
        z-index: 1100;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .toast-notify {
        background: white;
        border-left: 5px solid var(--primary);
        padding: 0.9rem 1.2rem;
        border-radius: 1rem;
        box-shadow: var(--shadow-lg);
        font-weight: 500;
        animation: slideUp 0.28s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .spinner-overlay {
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(14px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .display-1-custom {
            font-size: 2.3rem;
        }

        .carousel-caption {
            left: 5%;
            bottom: 12%;
            text-align: center;
        }

        .hero-search-bar .input-group {
            flex-direction: column;
            border-radius: 40px;
        }
    }
</style>

<div class="landing-page">
    <!-- Spinner -->
    <div id="spinnerOverlay" class="spinner-overlay">
        <div class="text-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
            <p class="mt-3 fw-semibold text-primary">MedFinder <br>loading smart pharmacy network...</p>
        </div>
    </div>

    <!-- Hero Carousel -->
    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: linear-gradient(0deg, rgba(0,0,0,0.3), rgba(0,0,0,0.2)), url('https://images.pexels.com/photos/4386467/pexels-photo-4386467.jpeg?auto=compress&cs=tinysrgb&w=1600'); background-size: cover;">
                <div class="carousel-caption">
                    <h5 class="text-white text-uppercase fw-light">Smart Healthcare Access</h5>
                    <h1 class="display-1-custom text-white fw-bold">Find Medicine<br>Near You. Instantly.</h1>
                    <div class="hero-search-bar">
                        <div class="text-center">
                            <div class="input-group input-group-lg mb-3" style="max-width: 500px; margin: 0 auto;">
                                <input type="text" id="heroItemInput" class="form-control rounded-pill-start border-0 bg-white bg-opacity-95" placeholder="e.g., Amoxicillin, Paracetamol..." onkeypress="if(event.key === 'Enter') addHeroItem()">
                                <button class="btn btn-primary rounded-pill-end px-4" onclick="addHeroItem()"><i class="fas fa-plus-circle me-1"></i> Add</button>
                            </div>
                            <div id="heroEditableItemList" class="d-flex flex-wrap justify-content-center gap-2 mb-3" style="min-height: 48px;"></div>
                            <a href="#searchSection" class="btn btn-light btn-lg rounded-pill px-5 shadow-lg fw-semibold" id="heroSearchBtn" style="display: none;"><i class="fas fa-map-marker-alt me-2 text-primary"></i> Find Availability</a>
                            <!DOCTYPE html>
                            <html lang="en">

                            <head>
                                <meta charset="utf-8">
                                <title>MedFinder - Medicine Availability Finder</title>
                                <meta content="width=device-width, initial-scale=1.0" name="viewport">
                                <meta content="Medicine Availability Finder, Find medicine near you" name="keywords">
                                <meta content="Find medicine availability at pharmacies near you in Uganda" name="description">

                                <!-- Favicon -->
                                <link href="img/favicon.ico" rel="icon">

                                <!-- Google Web Fonts -->
                                <link rel="preconnect" href="https://fonts.gstatic.com">
                                <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

                                <!-- Icon Font Stylesheet -->
                                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
                                <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

                                <!-- Libraries Stylesheet -->
                                <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
                                <link href="lib/animate/animate.min.css" rel="stylesheet">
                                <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
                                <link href="lib/twentytwenty/twentytwenty.css" rel="stylesheet" />

                                <!-- Customized Bootstrap Stylesheet -->
                                <link href="css/bootstrap.min.css" rel="stylesheet">

                                <!-- Template Stylesheet -->
                                <link href="css/style.css" rel="stylesheet">
                            </head>

                            <body>
                                <!-- Spinner Start -->
                                <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
                                    <div class="spinner-grow text-primary m-1" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-dark m-1" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-secondary m-1" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <!-- Spinner End -->

                                <!-- Topbar Start -->
                                <div class="container-fluid bg-light ps-5 pe-0 d-none d-lg-block">
                                    <div class="row gx-0">
                                        <div class="col-md-6 text-center text-lg-start mb-2 mb-lg-0">
                                            <div class="d-inline-flex align-items-center">
                                                <small class="py-2"><i class="far fa-clock text-primary me-2"></i>Opening Hours: Mon - Fri : 8.00am - 8.00pm, Sunday Closed </small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-center text-lg-end">
                                            <div class="position-relative d-inline-flex align-items-center bg-primary text-white top-shape px-5">
                                                <div class="me-3 pe-3 border-end py-2">
                                                    <p class="m-0"><i class="fa fa-envelope-open me-2"></i>info@medfinder.com</p>
                                                </div>
                                                <div class="py-2">
                                                    <p class="m-0"><i class="fa fa-phone-alt me-2"></i>+256 414 123 456</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Topbar End -->

                                <!-- Navbar Start -->
                                <nav class="navbar navbar-expand-lg bg-white navbar-light shadow-sm px-5 py-3 py-lg-0">
                                    <a href="/" class="navbar-brand p-0">
                                        <h1 class="m-0 text-primary"><i class="fa fa-pills me-2"></i>MedFinder</h1>
                                    </a>
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                    <div class="collapse navbar-collapse" id="navbarCollapse">
                                        <div class="navbar-nav ms-auto py-0">
                                            <a href="/" class="nav-item nav-link active">Home</a>
                                            <a href="/about" class="nav-item nav-link">About</a>
                                            <a href="/how-it-works" class="nav-item nav-link">How It Works</a>
                                            <div class="nav-item dropdown">
                                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Services</a>
                                                <div class="dropdown-menu m-0">
                                                    <a href="/pharmacies" class="dropdown-item">Find Pharmacies</a>
                                                    <a href="/medicines" class="dropdown-item">Browse Medicines</a>
                                                    <a href="/reservations" class="dropdown-item">My Reservations</a>
                                                </div>
                                            </div>
                                            <a href="/contact" class="nav-item nav-link">Contact</a>
                                        </div>
                                        <button type="button" class="btn text-dark" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fa fa-search"></i></button>
                                        @if(currentUser())
                                        <div class="dropdown">
                                            <button class="btn btn-outline-primary dropdown-toggle ms-3" type="button" data-bs-toggle="dropdown">
                                                {{ currentUser()->name ?? 'User' }}
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                                                <li><a class="dropdown-item" href="/logout">Logout</a></li>
                                            </ul>
                                        </div>
                                        @else
                                        <a href="/login" class="btn btn-primary py-2 px-4 ms-3">Login</a>
                                        @endif
                                    </div>
                                </nav>
                                <!-- Navbar End -->

                                <!-- Full Screen Search Start -->
                                <div class="modal fade" id="searchModal" tabindex="-1">
                                    <div class="modal-dialog modal-fullscreen">
                                        <div class="modal-content" style="background: rgba(9, 30, 62, .7);">
                                            <div class="modal-header border-0">
                                                <button type="button" class="btn bg-white btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body d-flex align-items-center justify-content-center">
                                                <div class="input-group" style="max-width: 600px;">
                                                    <input type="text" id="quickSearchInput" class="form-control bg-transparent border-primary p-3" placeholder="Search for medicine...">
                                                    <button class="btn btn-primary px-4"><i class="bi bi-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Full Screen Search End -->

                                <!-- Carousel Start -->
                                <div class="container-fluid p-0">
                                    <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img class="w-100" src="img/carousel-1.jpg" alt="Medicine Search">
                                                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                                    <div class="p-3" style="max-width: 900px;">
                                                        <h5 class="text-white text-uppercase mb-3 animated slideInDown">Find Medicine Near You</h5>
                                                        <h1 class="display-1 text-white mb-md-4 animated zoomIn">Access Healthcare Made Easy</h1>
                                                        <a href="#search" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Start Searching</a>
                                                        <a href="/about" class="btn btn-secondary py-md-3 px-md-5 animated slideInRight">Learn More</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="carousel-item">
                                                <img class="w-100" src="img/carousel-2.jpg" alt="Pharmacy Network">
                                                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                                    <div class="p-3" style="max-width: 900px;">
                                                        <h5 class="text-white text-uppercase mb-3 animated slideInDown">Trusted Pharmacy Network</h5>
                                                        <h1 class="display-1 text-white mb-md-4 animated zoomIn">Quality Medicine, Verified Pharmacies</h1>
                                                        <a href="#search" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Find Pharmacies</a>
                                                        <a href="/contact" class="btn btn-secondary py-md-3 px-md-5 animated slideInRight">Get in Touch</a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="carousel-item" style="background-image: linear-gradient(0deg, rgba(0,0,0,0.3), rgba(0,0,0,0.2)), url('https://images.pexels.com/photos/4386466/pexels-photo-4386466.jpeg?auto=compress&cs=tinysrgb&w=1600');">
                                            <div class="carousel-caption">
                                                <h5 class="text-white text-uppercase fw-light">Verified Pharmacy Network</h5>
                                                <h1 class="display-1-custom text-white fw-bold">Quality & Transparency</h1>
                                                <a href="#about" class="btn btn-outline-light btn-lg rounded-pill px-5 mt-4">Discover More</a>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon bg-dark rounded-circle p-3 bg-opacity-50"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon bg-dark rounded-circle p-3 bg-opacity-50"></span>
                                    </button>
                                </div>

                                <!-- Smart Search + Info Cards -->
                                <div class="container py-5 mt-3" id="searchSection">
                                    <div class="row g-4">
                                        <div class="col-lg-4 wow fadeInUp">
                                            <div class="glass-card primary-bg h-100">
                                                <h3 class="mb-3 fw-bold"><i class="fas fa-microscope me-2"></i> Smart Medicine Search</h3>
                                                <div class="input-group input-group-clean bg-white rounded-pill mb-3">
                                                    <input type="text" id="itemInput" class="form-control border-0 bg-transparent" placeholder="e.g., Amoxicillin, Paracetamol..." onkeypress="if(event.key === 'Enter') addItem()">
                                                    <button class="btn btn-dark rounded-pill px-4" onclick="addItem()"><i class="fas fa-plus"></i> Add</button>
                                                </div>
                                                <div id="editableItemList" class="d-flex flex-wrap gap-2 mb-3" style="min-height: 80px;"></div>
                                                <form id="searchForm" action="/" method="GET">
                                                    <button type="submit" id="searchBtn" class="btn btn-light w-100 rounded-pill fw-semibold py-2" style="display: none;"><i class="fas fa-map-pin me-2"></i> Find Availability</button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 wow fadeInUp">
                                            <div class="glass-card dark-bg h-100">
                                                <h3 class="fw-bold"><i class="fas fa-chart-simple me-2"></i> Live Uganda Stats</h3>
                                                <div class="mt-4">
                                                    <div class="d-flex justify-content-between border-bottom border-white-20 pb-2 mb-3">
                                                        <span>Medicines catalog</span>
                                                        <span class="counter fw-bold" id="medCount">2,480</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between border-bottom border-white-20 pb-2 mb-3">
                                                        <span>Active Pharmacies</span>
                                                        <span class="counter fw-bold" id="pharCount">186</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between pb-2">
                                                        <span>Total stock units</span>
                                                        <span class="counter fw-bold" id="stockCount">58.2k</span>
                                                    </div>
                                                </div>
                                                <a href="#" class="btn btn-light rounded-pill w-100 mt-4 fw-semibold">Partner with us <i class="fas fa-arrow-right ms-1"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 wow fadeInUp">
                                            <div class="glass-card accent-bg h-100">
                                                <h3 class="fw-bold"><i class="fas fa-phone-alt me-2"></i> Emergency Support</h3>
                                                <p class="mt-3 opacity-90">Urgent medicine? We'll connect you to emergency-ready pharmacies.</p>
                                                <h2 class="text-white mt-2 fw-bold" style="font-size: 2rem;">112 / 0800 199 199</h2>
                                                <small>24/7 Health Hotline</small>
                                                <hr class="bg-white mt-3 opacity-25">
                                                <p class="small mb-0">Same‑day delivery options available in Kampala & Wakiso.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ========== ADVERTISEMENT SECTION: DRUG PROMOS & PHARMACY ADS ========== -->
                                <div class="container py-4">
                                    <div class="row g-4 align-items-stretch">
                                        <!-- Left side: drug advertisements -->
                                        <div class="col-lg-7">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="badge bg-primary-soft text-primary py-2 px-3 rounded-pill"><i class="fas fa-tag me-1"></i> Sponsored</span>
                                                <small class="text-muted">health partner offers</small>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="ad-card position-relative h-100">
                                                        <div class="ad-badge"><i class="fas fa-ad me-1"></i> PROMO</div>
                                                        <img src="https://images.pexels.com/photos/208512/pexels-photo-208512.jpeg?auto=compress&cs=tinysrgb&w=400" class="w-100" style="height: 160px; object-fit: cover;" alt="medicine promo">
                                                        <div class="p-3">
                                                            <h5 class="fw-bold mb-1"><i class="fas fa-capsules text-primary me-1"></i> Amoxicillin 500mg</h5>
                                                            <p class="small text-muted">Trusted antibiotic, WHO prequalified.</p>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="discount-chip">-25% OFF</span>
                                                                <span class="fw-bold text-primary">from 8,500 UGX</span>
                                                            </div>
                                                            <button class="btn btn-sm btn-outline-primary rounded-pill mt-2 w-100" onclick="showToast('Special offer applied at checkout', 'success')"><i class="fas fa-shopping-cart"></i> Claim deal</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="ad-card position-relative h-100">
                                                        <div class="ad-badge bg-danger">LIMITED</div>
                                                        <img src="https://images.pexels.com/photos/4041392/pexels-photo-4041392.jpeg?auto=compress&cs=tinysrgb&w=400" class="w-100" style="height: 160px; object-fit: cover;" alt="pain relief">
                                                        <div class="p-3">
                                                            <h5 class="fw-bold mb-1"><i class="fas fa-tablets text-primary me-1"></i> Ibuprofen + Paracetamol</h5>
                                                            <p class="small text-muted">Dual action pain relief, 20 tablets.</p>
                                                            <div class="d-flex justify-content-between">
                                                                <span class="discount-chip">BOGO 50%</span>
                                                                <span class="fw-bold text-success">new low price</span>
                                                            </div>
                                                            <button class="btn btn-sm btn-primary rounded-pill mt-2 w-100" onclick="showToast('Ibuprofen combo added to your reservation list', 'info')"><i class="fas fa-hand-holding-heart"></i> Reserve now</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <div class="bg-white rounded-4 p-3 d-flex align-items-center justify-content-between flex-wrap shadow-sm border">
                                                        <div><i class="fas fa-chart-line fa-2x text-primary me-3"></i><span class="fw-semibold">Malaria treatment week: Coartem & Artesunate at <span class="text-danger">−15%</span></span></div>
                                                        <button class="btn btn-sm btn-light rounded-pill" onclick="showToast('Malaria meds discounted in all partner pharmacies', 'success')">View offer →</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Right side: pharmacy spotlight ads -->
                                        <div class="col-lg-5">
                                            <div class="mb-3"><span class="badge bg-primary-soft text-primary py-2 px-3 rounded-pill"><i class="fas fa-store me-1"></i> Partner Spotlight</span></div>
                                            <div class="pharmacy-ad-card rounded-4 p-4 mb-3 shadow-sm">
                                                <div class="d-flex justify-content-between">
                                                    <img src="https://via.placeholder.com/60x60?text=Guardian+Pharmacy" class="rounded-circle bg-light p-2" style="width: 60px; height: 60px; object-fit: contain;">
                                                    <span class="discount-chip bg-success text-white">⭐ 4.9 Verified</span>
                                                </div>
                                                <h5 class="fw-bold mt-2">Guardian Health Pharmacy</h5>
                                                <p class="small text-secondary"><i class="fas fa-location-dot me-1"></i> Kampala Road + Free delivery</p>
                                                <div class="d-flex justify-content-between border-top pt-2 mt-2">
                                                    <span><i class="fas fa-tag text-primary"></i> 20% off on diabetes meds</span>
                                                    <a href="#" onclick="showToast('Guardian Pharmacy promo activated', 'success')" class="text-decoration-none">Grab <i class="fas fa-chevron-right"></i></a>
                                                </div>
                                            </div>
                                            <div class="pharmacy-ad-card rounded-4 p-4 shadow-sm">
                                                <div class="d-flex justify-content-between">
                                                    <i class="fas fa-truck-fast fa-2x text-primary"></i>
                                                    <span class="discount-chip">Flash sale</span>
                                                </div>
                                                <h5 class="fw-bold mt-2"><i class="fas fa-bolt text-warning"></i> MedPlus Express</h5>
                                                <p class="small">Order before 2PM and get same-day delivery within Kampala. Free for first order.</p>
                                                <div class="progress mb-2" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" style="width: 68%;"></div>
                                                </div>
                                                <small class="text-muted">68% of stock ready for instant dispatch</small>
                                                <button class="btn btn-outline-primary rounded-pill w-100 mt-3 btn-sm" onclick="showToast('MedPlus Express: free delivery applied on checkout', 'success')">Order now <i class="fas fa-arrow-right ms-1"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Banner ad row (pharmacy of the week) -->
                                <div class="container my-2">
                                    <div class="bg-gradient rounded-4 p-3 px-4 d-flex flex-wrap align-items-center justify-content-between" style="background: linear-gradient(115deg, #eef2ff, #ffffff); border: 1px solid #dee5ed;">
                                        <div><i class="fas fa-prescription-bottle-alt fa-2x text-primary me-2"></i> <strong class="fs-5">Pharmacy of the week: City Care Chemist</strong> – 10% off on prescription meds & free mask</div>
                                        <button class="btn btn-sm btn-primary rounded-pill mt-2 mt-sm-0" onclick="showToast('City Care discount active until Sunday', 'info')">Redeem &rarr;</button>
                                    </div>
                                </div>

                                <!-- Search Results (if any query from backend) -->
                                @if (!empty($query) && isset($results))
                                <div class="container py-5">
                                    <div class="section-title mb-4 text-center">
                                        <span class="badge bg-primary-soft text-primary px-4 py-2 rounded-pill"><i class="fas fa-search me-1"></i> Search Results</span>
                                        <h2 class="display-6 fw-bold mt-2">Results for “{{ $query }}”</h2>
                                    </div>
                                    <div class="modern-table-card">
                                        <div class="table-responsive">
                                            <table class="table table-modern align-middle mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Medicine</th>
                                                        <th>Pharmacy</th>
                                                        <th>Price (UGX)</th>
                                                        <th>Stock</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($results) > 0)
                                                    @foreach ($results->groupBy('pharmacy_name') as $pharmacyName => $meds)
                                                    <tr class="bg-light-50">
                                                        <td colspan="5" class="fw-bold text-primary pt-3"><i class="fas fa-store me-2"></i>{{ $pharmacyName }} • {{ $meds->first()->pharmacy_location ?? 'Kampala' }}</td>
                                                    </tr>
                                                    @foreach ($meds as $item)
                                                    <tr>
                                                        <td><span class="fw-semibold">{{ $item->medicine_name }}</span></td>
                                                        <td>{{ $pharmacyName }}</td>
                                                        <td>{{ number_format((float)($item->price ?? 0), 0) }} UGX</td>
                                                        <td>@if((int)($item->quantity) > 0) <span class="badge-stock"><i class="fas fa-check-circle"></i> {{ $item->quantity }} packs</span> @else <span class="badge bg-light text-danger">Out of stock</span> @endif</td>
                                                        <td>
                                                            <form method="post" action="/reserve/{{ $item->id }}">
                                                                @csrf
                                                                <button class="btn btn-sm btn-primary rounded-pill px-3"><i class="fas fa-hand-holding-heart"></i> Reserve</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="5" class="text-center py-4 text-muted"><i class="fas fa-database me-2"></i> No exact matches found. Try adding alternative medicine names.</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Trusted Pharmacies Gallery -->
                                <div class="container py-5">
                                    <div class="text-center mb-5">
                                        <h2 class="fw-bold"><i class="fas fa-hand-holding-medical text-primary me-2"></i>Trusted Pharmacies Near You</h2>
                                        <p class="text-secondary">Verified partners across Uganda — realtime stock</p>
                                    </div>
                                    <div class="row g-3 justify-content-center" id="pharmacyList">
                                        @php
                                        $fakePharmacies = [
                                        ['name'=>'Guardian Health Pharmacy','location'=>'Kampala Road'],
                                        ['name'=>'MedPlus Pharmacy','location'=>'Wandegeya'],
                                        ['name'=>'City Care Chemist','location'=>'Najjanankumbi'],
                                        ['name'=>'Royal Drug Centre','location'=>'Ntinda'],
                                        ['name'=>'Jubilee Pharmacy','location'=>'Muyenga'],
                                        ['name'=>'St. Francis Medicals','location'=>'Entebbe'],
                                        ['name'=>'Lake Victoria Chemist','location'=>'Jinja']
                                        ];
                                        @endphp
                                        @foreach ($fakePharmacies as $pharmacy)
                                        <div class="col-md-2 col-6">
                                            <div class="pharmacy-pill-modern text-center p-3">
                                                <i class="fas fa-shield-alt fs-2 d-block mb-2 text-primary"></i>
                                                <div class="fw-semibold">{{ $pharmacy['name'] }}</div>
                                                <small class="text-muted"><i class="fas fa-location-dot me-1"></i>{{ $pharmacy['location'] }}</small>

                                                <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Previous</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Next</span>
                                                </button>
                                            </div>
                                            <!-- Banner Start -->
                                            <div class="container-fluid banner mb-5" id="search">
                                                <div class="container">
                                                    <div class="row gx-0">
                                                        <div class="col-lg-4 wow zoomIn" data-wow-delay="0.1s">
                                                            <div class="bg-primary d-flex flex-column p-5" style="height: 300px;">
                                                                <h3 class="text-white mb-3">Search Medicines</h3>
                                                                <div class="input-group mb-3">
                                                                    <input type="text" id="itemInput" class="form-control bg-light border-0" placeholder="Add medicine..." onkeypress="if(event.key === 'Enter') addItem()">
                                                                    <button class="btn btn-dark" type="button" onclick="addItem()">Add</button>
                                                                </div>
                                                                <form action="/" method="GET" id="searchForm">
                                                                    <div id="editableItemList" class="mb-3"></div>
                                                                    <button type="submit" class="btn btn-light w-100" id="searchBtn" style="display: none;">
                                                                        <i class="bi bi-search me-2"></i>Search All Items
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 wow zoomIn" data-wow-delay="0.3s">
                                                            <div class="bg-dark d-flex flex-column p-5" style="height: 300px;">
                                                                <h3 class="text-white mb-3">Quick Stats</h3>
                                                                <div class="d-flex justify-content-between text-white mb-3">
                                                                    <h6 class="text-white mb-0">Medicines Available</h6>
                                                                    <p class="mb-0">{{ \DB::table('medicines')->count() }}</p>
                                                                </div>
                                                                <div class="d-flex justify-content-between text-white mb-3">
                                                                    <h6 class="text-white mb-0">Active Pharmacies</h6>
                                                                    <p class="mb-0">{{ \DB::table('pharmacies')->where('status', 'approved')->count() }}</p>
                                                                </div>
                                                                <div class="d-flex justify-content-between text-white mb-3">
                                                                    <h6 class="text-white mb-0">Total Stock</h6>
                                                                    <p class="mb-0">{{ \DB::table('pharmacy_medicine')->sum('quantity') }}</p>
                                                                </div>
                                                                <a class="btn btn-light" href="/about">Learn More</a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 wow zoomIn" data-wow-delay="0.6s">
                                                            <div class="bg-secondary d-flex flex-column p-5" style="height: 300px;">
                                                                <h3 class="text-white mb-3">Emergency Help</h3>
                                                                <p class="text-white">Need urgent medical assistance? Contact emergency services or find the nearest pharmacy with emergency stock.</p>
                                                                <h2 class="text-white mb-0">112</h2>
                                                                <small class="text-white">Emergency Hotline</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Banner End -->

                                            <!-- About Start -->
                                            <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
                                                <div class="container">
                                                    <div class="row g-5">
                                                        <div class="col-lg-7">
                                                            <div class="section-title mb-4">
                                                                <h5 class="position-relative d-inline-block text-primary text-uppercase">About MedFinder</h5>
                                                                <h1 class="display-5 mb-0">Your Trusted Medicine Availability Platform</h1>
                                                            </div>
                                                            <h4 class="text-body fst-italic mb-4">Find medicines at pharmacies near you with real-time availability and pricing.</h4>
                                                            <p class="mb-4">MedFinder connects patients with pharmacies across Uganda, making it easy to find essential medicines when you need them. Our platform provides real-time stock information, competitive pricing, and direct pharmacy connections.</p>
                                                            <div class="row g-3">
                                                                <div class="col-sm-6 wow zoomIn" data-wow-delay="0.3s">
                                                                    <h5 class="mb-3"><i class="fa fa-check-circle text-primary me-3"></i>Real-time Updates</h5>
                                                                    <h5 class="mb-3"><i class="fa fa-check-circle text-primary me-3"></i>Verified Pharmacies</h5>
                                                                </div>
                                                                <div class="col-sm-6 wow zoomIn" data-wow-delay="0.6s">
                                                                    <h5 class="mb-3"><i class="fa fa-check-circle text-primary me-3"></i>24/7 Availability</h5>
                                                                    <h5 class="mb-3"><i class="fa fa-check-circle text-primary me-3"></i>Easy Reservations</h5>
                                                                </div>
                                                            </div>
                                                            <a href="/how-it-works" class="btn btn-primary py-3 px-5 mt-4 wow zoomIn" data-wow-delay="0.6s">How It Works</a>
                                                        </div>
                                                        <div class="col-lg-5" style="min-height: 500px;">
                                                            <div class="position-relative h-100">
                                                                <img class="position-absolute w-100 h-100 rounded wow zoomIn" data-wow-delay="0.9s" src="img/about.jpg" style="object-fit: cover;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- About End -->

                                            @if ($query !== '')
                                            <!-- Search Results Start -->
                                            <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
                                                <div class="container">
                                                    <div class="row g-5 mb-5">
                                                        <div class="col-lg-5 wow zoomIn" data-wow-delay="0.3s" style="min-height: 400px;">
                                                            <div class="twentytwenty-container position-relative h-100 rounded overflow-hidden">
                                                                <img class="position-absolute w-100 h-100" src="img/before.jpg" style="object-fit: cover;">
                                                                <img class="position-absolute w-100 h-100" src="img/after.jpg" style="object-fit: cover;">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-7">
                                                            <div class="section-title mb-5">
                                                                <h5 class="position-relative d-inline-block text-primary text-uppercase">Search Results</h5>
                                                                <h1 class="display-5 mb-0">Results for "{{ $query }}"</h1>
                                                            </div>
                                                            @if(count($results) > 0)
                                                            <div class="table-responsive card shadow-sm border-0">
                                                                <table class="table align-middle mb-0">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>Medicine</th>
                                                                            <th>Price (UGX)</th>
                                                                            <th>Status</th>
                                                                            <th>Quantity</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($results->groupBy('pharmacy_name') as $pharmacyName => $meds)
                                                                        <tr class="table-secondary">
                                                                            <td colspan="5" class="py-2 px-3">
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <div>
                                                                                        <i class="bi bi-shop text-primary me-2"></i>
                                                                                        <strong class="text-dark">{{ $pharmacyName }}</strong>
                                                                                        <span class="small text-muted ms-2">({{ $meds->first()->pharmacy_location }})</span>
                                                                                    </div>
                                                                                    <span class="badge bg-primary rounded-pill">
                                                                                        {{ $meds->count() }} items available
                                                                                    </span>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        @foreach ($meds as $item)
                                                                        <tr>
                                                                            <td class="ps-4">
                                                                                <strong>{{ $item->medicine_name }}</strong>
                                                                            </td>
                                                                            <td>{{ number_format((float)($item->price ?? 0), 0) }}</td>
                                                                            <td>
                                                                                @if ((int)$item->quantity > 0)
                                                                                <span class="badge bg-success">In stock</span>
                                                                                @else
                                                                                <span class="badge bg-secondary">Out of stock</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>{{ (int)$item->quantity }}</td>
                                                                            <td>
                                                                                <form method="post" action="/reserve/{{ $item->id }}" class="d-flex gap-1">
                                                                                    @csrf
                                                                                    <input type="hidden" name="note" value="Request from search">
                                                                                    <button class="btn btn-sm btn-primary" type="submit">Reserve</button>
                                                                                </form>
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            @else
                                                            <div class="alert alert-info border-0 shadow-sm">
                                                                <h5>No exact matches found</h5>
                                                                <p>Try an alternative medicine from the suggestions below.</p>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Search Results End -->
                                            @endif

                                            <!-- Service Start -->
                                            <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
                                                <div class="container">
                                                    <div class="row g-5 mb-5">
                                                        <div class="col-lg-7">
                                                            <div class="row g-5">
                                                                <div class="col-md-6 service-item wow zoomIn" data-wow-delay="0.3s">
                                                                    <div class="rounded-top overflow-hidden">
                                                                        <img class="img-fluid" src="img/service-1.jpg" alt="">
                                                                    </div>
                                                                    <div class="position-relative bg-light rounded-bottom text-center p-4">
                                                                        <h5 class="m-0">Find Medicines</h5>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 service-item wow zoomIn" data-wow-delay="0.6s">
                                                                    <div class="rounded-top overflow-hidden">
                                                                        <img class="img-fluid" src="img/service-2.jpg" alt="">
                                                                    </div>
                                                                    <div class="position-relative bg-light rounded-bottom text-center p-4">
                                                                        <h5 class="m-0">Compare Prices</h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-5 service-item wow zoomIn" data-wow-delay="0.9s">
                                                            <div class="position-relative bg-primary rounded h-100 d-flex flex-column align-items-center justify-content-center text-center p-4">
                                                                <h3 class="text-white mb-3">Make Reservation</h3>
                                                                <p class="text-white mb-3">Reserve medicines at your preferred pharmacy instantly.</p>
                                                                <h2 class="text-white mb-0">+256 414 123 456</h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Service End -->

                                            <!-- Alternatives Start -->
                                            <div class="container-fluid bg-offer my-5 py-5 wow fadeInUp" data-wow-delay="0.1s">
                                                <div class="container py-5">
                                                    <div class="row justify-content-center">
                                                        <div class="col-lg-7 wow zoomIn" data-wow-delay="0.6s">
                                                            <div class="offer-text text-center rounded p-5">
                                                                <h1 class="display-5 text-white">Alternative Medicines</h1>
                                                                <p class="text-white mb-4">Same category suggestions when your preferred medicine isn't available.</p>
                                                                <div class="row g-3">
                                                                    @forelse ($alternatives as $med)
                                                                    <div class="col-md-4">
                                                                        <div class="card bg-white rounded p-3">
                                                                            <div class="fw-bold text-primary">{{ $med->name }}</div>
                                                                            <div class="text-muted small">{{ $med->category ?? 'General' }}</div>
                                                                        </div>
                                                                    </div>
                                                                    @empty
                                                                    <div class="col-12">
                                                                        <p class="text-white">Enter a medicine name to see suggested alternatives.</p>
                                                                    </div>
                                                                    @endforelse
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Alternatives End -->

                                            <!-- Team Start -->
                                            <div class="container-fluid py-5">
                                                <div class="container">
                                                    <div class="row g-5">
                                                        <div class="col-lg-4 wow slideInUp" data-wow-delay="0.1s">
                                                            <div class="section-title bg-light rounded h-100 p-5">
                                                                <h5 class="position-relative d-inline-block text-primary text-uppercase">Nearby Pharmacies</h5>
                                                                <h1 class="display-6 mb-4">Find Pharmacies Near You</h1>
                                                                <a href="/pharmacies" class="btn btn-primary py-3 px-5">View All Pharmacies</a>
                                                            </div>
                                                        </div>
                                                        @foreach ($pharmacies as $pharmacy)
                                                        <div class="col-lg-4 wow slideInUp" data-wow-delay="0.3s">
                                                            <div class="team-item">
                                                                <div class="position-relative rounded-top" style="z-index: 1;">
                                                                    <img class="img-fluid rounded-top w-100" src="img/team-{{ $loop->iteration }}.jpg" alt="">
                                                                    <div class="position-absolute top-100 start-50 translate-middle bg-light rounded p-2 d-flex">
                                                                        <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                                                                        <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                                                                        <a class="btn btn-primary btn-square m-1" href="#"><i class="fab fa-instagram fw-normal"></i></a>
                                                                    </div>
                                                                </div>
                                                                <div class="team-text position-relative bg-light text-center rounded-bottom p-4 pt-5">
                                                                    <h4 class="mb-2">{{ $pharmacy->name }}</h4>
                                                                    <p class="text-primary mb-0">{{ $pharmacy->location }}</p>
                                                                    <p class="text-muted small">Approved Pharmacy</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Team End -->

                                            <!-- Newsletter Start -->
                                            <div class="container-fluid position-relative pt-5 wow fadeInUp" data-wow-delay="0.1s" style="z-index: 1;">
                                                <div class="container">
                                                    <div class="bg-primary p-5">
                                                        <form class="mx-auto" style="max-width: 600px;">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control border-white p-3" placeholder="Your Email">
                                                                <button class="btn btn-dark px-4">Sign Up</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Newsletter End -->

                                            <!-- Footer Start -->
                                            <div class="container-fluid bg-dark text-light py-5 wow fadeInUp" data-wow-delay="0.3s" style="margin-top: -75px;">
                                                <div class="container pt-5">
                                                    <div class="row g-5 pt-4">
                                                        <div class="col-lg-3 col-md-6">
                                                            <h3 class="text-white mb-4">Quick Links</h3>
                                                            <div class="d-flex flex-column justify-content-start">
                                                                <a class="text-light mb-2" href="/"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                                                                <a class="text-light mb-2" href="/about"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>
                                                                <a class="text-light mb-2" href="/how-it-works"><i class="bi bi-arrow-right text-primary me-2"></i>How It Works</a>
                                                                <a class="text-light mb-2" href="/contact"><i class="bi bi-arrow-right text-primary me-2"></i>Contact Us</a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <h3 class="text-white mb-4">Services</h3>
                                                            <div class="d-flex flex-column justify-content-start">
                                                                <a class="text-light mb-2" href="/pharmacies"><i class="bi bi-arrow-right text-primary me-2"></i>Find Pharmacies</a>
                                                                <a class="text-light mb-2" href="/medicines"><i class="bi bi-arrow-right text-primary me-2"></i>Browse Medicines</a>
                                                                <a class="text-light mb-2" href="/reservations"><i class="bi bi-arrow-right text-primary me-2"></i>My Reservations</a>
                                                                <a class="text-light" href="/contact"><i class="bi bi-arrow-right text-primary me-2"></i>Support</a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <h3 class="text-white mb-4">Get In Touch</h3>
                                                            <p class="mb-2"><i class="bi bi-geo-alt text-primary me-2"></i>Kampala, Uganda</p>
                                                            <p class="mb-2"><i class="bi bi-envelope-open text-primary me-2"></i>info@medfinder.com</p>
                                                            <p class="mb-0"><i class="bi bi-telephone text-primary me-2"></i>+256 414 123 456</p>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <h3 class="text-white mb-4">Follow Us</h3>
                                                            <div class="d-flex">
                                                                <a class="btn btn-lg btn-primary btn-lg-square rounded me-2" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                                                                <a class="btn btn-lg btn-primary btn-lg-square rounded me-2" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                                                                <a class="btn btn-lg btn-primary btn-lg-square rounded me-2" href="#"><i class="fab fa-instagram fw-normal"></i></a>
                                                                <a class="btn btn-lg btn-primary btn-lg-square rounded" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="container-fluid text-light py-4" style="background: #051225;">
                                                <div class="container">
                                                    <div class="row g-0">
                                                        <div class="col-md-6 text-center text-md-start">
                                                            <p class="mb-md-0">&copy; <a class="text-white border-bottom" href="#">MedFinder</a>. All Rights Reserved.</p>
                                                        </div>
                                                        <div class="col-md-6 text-center text-md-end">
                                                            <p class="mb-0">Designed by <a class="text-white border-bottom" href="#">MedFinder Team</a></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Footer End -->

                                            <!-- Back to Top -->
                                            <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>
                                        </div>

                                        <style>
                                            .carousel-image-container {
                                                height: 320px;
                                                /* Adjust this to match your search box height */
                                                background-size: cover;
                                                background-position: center;
                                                transition: transform 0.5s ease;
                                            }

                                            #tips .carousel-item {
                                                height: 320px;
                                            }

                                            /* Subtle hover effect */
                                            .carousel-image-container:hover {
                                                transform: scale(1.02);
                                            }
                                        </style>

                                        <script>
                                            // Check for focus parameter and auto-focus search input
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const urlParams = new URLSearchParams(window.location.search);
                                                if (urlParams.get('focus') === 'search') {
                                                    const searchInput = document.getElementById('itemInput');
                                                    if (searchInput) {
                                                        // Small delay to ensure page is fully loaded
                                                        setTimeout(() => {
                                                            searchInput.focus();
                                                            searchInput.scrollIntoView({
                                                                behavior: 'smooth',
                                                                block: 'center'
                                                            });
                                                        }, 500);
                                                    }
                                                }
                                            });

                                            function addItem() {
                                                const input = document.getElementById('itemInput');
                                                const list = document.getElementById('editableItemList');
                                                const value = input.value.trim();

                                                if (value === "") return;

                                                const itemId = 'item_' + Date.now();

                                                // Create a row container
                                                const div = document.createElement('div');
                                                // Removed the icon span and kept just the input and the delete button
                                                div.className = "input-group mb-2 shadow-sm animate__animated animate__fadeIn";
                                                div.id = itemId;

                                                div.innerHTML = `
      <input type="text" name="item_names[]" class="form-control bg-white" value="${value}" placeholder="Medicine name">
      <button class="btn btn-outline-danger" type="button" onclick="document.getElementById('${itemId}').remove(); updateUI();">
        <i class="bi bi-trash"></i>
      </button>
    `;

                                                list.appendChild(div);
                                                input.value = "";
                                                input.focus();
                                                updateUI();
                                            }

                                            function updateUI() {
                                                const list = document.getElementById('editableItemList');
                                                const btn = document.getElementById('searchBtn');
                                                const count = list.children.length;
                                                btn.style.display = count > 0 ? 'block' : 'none';
                                                btn.innerHTML = `<i class="bi bi-search me-2"></i>Search ${count} Item${count > 1 ? 's' : ''}`;
                                            }
                                        </script>

                                    </div>
                                    </section>

                                    @if ($query !== '')
                                    <section class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h4 class="fw-semibold mb-0">Results for "{{ $query }}"</h4>
                                        </div>
                                        @if(count($results) > 0)
                                        <div class="table-responsive card shadow-sm border-0">
                                            <table class="table align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Medicine</th>
                                                        <th>Price (UGX)</th>
                                                        <th>Status</th>
                                                        <th>Quantity</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- 1. Group results by Pharmacy Name --}}
                                                    @foreach ($results->groupBy('pharmacy_name') as $pharmacyName => $meds)

                                                    {{-- 2. Header for each Pharmacy --}}
                                                    <tr class="table-secondary">
                                                        <td colspan="5" class="py-2 px-3">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <i class="bi bi-shop text-primary me-2"></i>
                                                                    <strong class="text-dark">{{ $pharmacyName }}</strong>
                                                                    <span class="small text-muted ms-2">({{ $meds->first()->pharmacy_location }})</span>
                                                                </div>
                                                                {{-- Show how many items from the search list are found here --}}
                                                                <span class="badge bg-primary rounded-pill">
                                                                    {{ $meds->count() }} items available
                                                                </span>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    {{-- 3. List of medicines found at THIS specific pharmacy --}}
                                                    @foreach ($meds as $item)
                                                    <tr>
                                                        <td class="ps-4">
                                                            <strong>{{ $item->medicine_name }}</strong>
                                                        </td>
                                                        <td>{{ number_format((float)($item->price ?? 0), 0) }}</td>
                                                        <td>
                                                            @if ((int)$item->quantity > 0)
                                                            <span class="badge bg-success">In stock</span>
                                                            @else
                                                            <span class="badge bg-secondary">Out of stock</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ (int)$item->quantity }}</td>
                                                        <td>
                                                            <form method="post" action="/reserve/{{ $item->id }}" class="d-flex gap-1">
                                                                @csrf
                                                                <input type="hidden" name="note" value="Request from search">
                                                                <button class="btn btn-sm btn-primary" type="submit">Reserve</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @endforeach

                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @else
                                        <div class="alert alert-info border-0 shadow-sm">No exact matches yet. Try an alternative below.</div>
                                        @endif
                                    </section>
                                    @endif

                                    <section class="mb-5">
                                        <div class="row g-3 align-items-center mb-2">
                                            <div class="col">
                                                <h5 class="fw-semibold mb-0">Alternatives</h5>
                                            </div>
                                            <div class="col-auto text-muted small">Same category suggestions</div>
                                        </div>
                                        <div class="row g-3">
                                            @forelse ($alternatives as $med)
                                            <div class="col-md-3">
                                                <div class="card h-100 shadow-sm border-0">
                                                    <div class="card-body">
                                                        <div class="fw-bold text-primary">{{ $med->name }}</div>
                                                        <div class="text-muted small">{{ $med->category ?? 'General' }}</div>
                                                        <p class="small mt-2 text-secondary">{{ $med->description ?? '' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @empty
                                            <div class="col-12 text-muted">Enter a medicine to see suggested alternatives.</div>
                                            @endforelse
                                        </div>
                                    </section>

                                    <section class="mb-5">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="fw-semibold mb-0">Nearby pharmacies</h5>
                                        </div>
                                        <div class="row g-3">
                                            @foreach ($pharmacies as $pharmacy)
                                            <div class="col-md-3">
                                                <div class="card h-100 shadow-sm border-0">
                                                    <div class="card-body text-center">
                                                        <div class="fw-semibold">{{ $pharmacy->name }}</div>
                                                        <div class="small text-muted">{{ $pharmacy->location }}</div>
                                                        <span class="badge bg-success-subtle text-success mt-2">Approved</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>

                                </div>

                                <!-- About + Features section -->
                                <div class="container py-5" id="about">
                                    <div class="row align-items-center g-5">
                                        <div class="col-lg-6 wow fadeInLeft">
                                            <img src="https://images.pexels.com/photos/40568/medical-appointment-doctor-healthcare-40568.jpeg?auto=compress&cs=tinysrgb&w=800" class="img-fluid rounded-4 shadow-lg" style="object-fit: cover; height: 450px; width: 100%;" alt="pharmacy care">
                                        </div>
                                        <div class="col-lg-6 wow fadeInRight">
                                            <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill mb-2">Why MedFinder?</span>
                                            <h2 class="display-6 fw-bold mt-2">Your health, simplified.</h2>
                                            <p class="lead text-secondary">Real-time stock visibility, fair pricing, and seamless reservation system.</p>
                                            <div class="row mt-4">
                                                <div class="col-6 mb-3"><i class="fas fa-check-circle text-primary me-2"></i> <strong>Live availability</strong><br><small class="text-muted">Updated every 2h</small></div>
                                                <div class="col-6 mb-3"><i class="fas fa-clock text-primary me-2"></i> <strong>Fast reservation</strong><br><small class="text-muted">Hold 4h, pay at pickup</small></div>
                                                <div class="col-6 mb-3"><i class="fas fa-shield-alt text-primary me-2"></i> <strong>Verified network</strong><br><small class="text-muted">Licensed providers</small></div>
                                                <div class="col-6 mb-3"><i class="fas fa-chart-line text-primary me-2"></i> <strong>Price transparency</strong><br><small class="text-muted">Compare before you go</small></div>
                                            </div>
                                            <a href="#" class="btn btn-primary rounded-pill px-5 py-2 mt-2">Explore How It Works →</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Search Modal -->
                                <div class="modal fade" id="quickSearchModal" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow-lg">
                                            <div class="modal-body p-4">
                                                <input type="text" id="quickGlobalSearch" class="form-control form-control-lg rounded-pill" placeholder="Search for any medicine...">
                                                <button class="btn btn-primary rounded-pill w-100 mt-3" id="quickSearchExec"><i class="fas fa-search me-2"></i>Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Toast container -->
                                <div class="toast-container-custom" id="toastRoot"></div>
                        </div>

                        <!-- Back to top button -->
                        <a href="#" id="backToTop" class="back-to-top"><i class="fas fa-arrow-up"></i></a>

                        @endsection

                        @section('scripts')
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
                        <script>
                            // Spinner hide on load
                            window.addEventListener('load', function() {
                                let spinner = document.getElementById('spinnerOverlay');
                                if (spinner) {
                                    spinner.style.opacity = '0';
                                    setTimeout(() => {
                                        if (spinner) spinner.style.display = 'none';
                                    }, 400);
                                }
                                new WOW().init();
                            });

                            // Toast notification system
                            function showToast(message, type = 'info') {
                                const container = document.getElementById('toastRoot');
                                if (!container) return;
                                const toast = document.createElement('div');
                                toast.className = `toast-notify`;
                                toast.style.borderLeftColor = type === 'error' ? '#dc3545' : (type === 'success' ? '#198754' : '#0b5ed7');
                                toast.innerHTML = `<div class="d-flex justify-content-between align-items-center">
            <strong>${type === 'error' ? '⚠️' : '✨'} ${type.toUpperCase()}</strong>
            <button class="btn-close btn-sm" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
        <div class="mt-1">${message}</div>`;
                                container.appendChild(toast);
                                setTimeout(() => toast.remove(), 4200);
                            }

                            // Escape HTML
                            function escapeHtml(str) {
                                return str.replace(/[&<>]/g, function(m) {
                                    if (m === '&') return '&amp;';
                                    if (m === '<') return '&lt;';
                                    if (m === '>') return '&gt;';
                                    return m;
                                });
                            }

                            // Hero section item management
                            function addHeroItem() {
                                const input = document.getElementById('heroItemInput');
                                let val = input.value.trim();
                                if (!val) {
                                    showToast('Please enter a medicine name', 'error');
                                    input.focus();
                                    return;
                                }
                                const listDiv = document.getElementById('heroEditableItemList');
                                const wrap = document.createElement('div');
                                wrap.className = 'hero-tag-item';
                                wrap.innerHTML = `<span>${escapeHtml(val)}</span><i class="fas fa-times-circle text-danger ms-1" style="cursor:pointer;" onclick="this.parentElement.remove(); updateHeroSearchBtn()"></i><input type="hidden" name="hero_item_names[]" value="${escapeHtml(val)}">`;
                                listDiv.appendChild(wrap);
                                input.value = '';
                                updateHeroSearchBtn();
                                showToast(`➕ ${val} added`, 'success');
                            }

                            function updateHeroSearchBtn() {
                                const list = document.getElementById('heroEditableItemList');
                                const btn = document.getElementById('heroSearchBtn');
                                if (btn) btn.style.display = (list.children.length > 0) ? 'inline-block' : 'none';
                            }

                            // Main search item management
                            function addItem() {
                                const input = document.getElementById('itemInput');
                                let val = input.value.trim();
                                if (!val) {
                                    showToast('Please enter medicine name', 'error');
                                    return;
                                }
                                const mainList = document.getElementById('editableItemList');
                                const wrap = document.createElement('div');
                                wrap.className = 'tag-item';
                                wrap.innerHTML = `<span>${escapeHtml(val)}</span><i class="fas fa-times-circle text-danger" style="cursor:pointer;" onclick="this.parentElement.remove(); updateSearchBtn()"></i><input type="hidden" name="item_names[]" value="${escapeHtml(val)}">`;
                                mainList.appendChild(wrap);
                                input.value = '';
                                updateSearchBtn();
                                showToast(`✓ ${val} added to search list`, 'success');
                            }

                            function updateSearchBtn() {
                                const list = document.getElementById('editableItemList');
                                const btn = document.getElementById('searchBtn');
                                if (btn) btn.style.display = (list.children.length > 0) ? 'flex' : 'none';
                                const form = document.getElementById('searchForm');
                                if (form && list.children.length) {
                                    let existHidden = form.querySelectorAll('input[name="item_names[]"]');
                                    existHidden.forEach(el => el.remove());
                                    let items = list.querySelectorAll('input[type="hidden"]');
                                    items.forEach(inp => form.appendChild(inp.cloneNode(true)));
                                }
                            }

                            // Counter animation
                            function animateCounterEl(el, target) {
                                let current = 0;
                                let step = Math.ceil(target / 45);
                                let interval = setInterval(() => {
                                    current += step;
                                    if (current >= target) {
                                        el.innerText = target.toLocaleString();
                                        clearInterval(interval);
                                    } else el.innerText = current.toLocaleString();
                                }, 20);
                            }

                            // DOM ready
                            document.addEventListener('DOMContentLoaded', function() {
                                // Animate counters
                                if (document.getElementById('medCount')) animateCounterEl(document.getElementById('medCount'), 2480);
                                if (document.getElementById('pharCount')) animateCounterEl(document.getElementById('pharCount'), 186);
                                if (document.getElementById('stockCount')) animateCounterEl(document.getElementById('stockCount'), 58200);

                                // Back to top button
                                const backBtn = document.getElementById('backToTop');
                                if (backBtn) {
                                    window.addEventListener('scroll', () => {
                                        if (window.scrollY > 400) backBtn.classList.add('show');
                                        else backBtn.classList.remove('show');
                                    });
                                    backBtn.addEventListener('click', (e) => {
                                        e.preventDefault();
                                        window.scrollTo({
                                            top: 0,
                                            behavior: 'smooth'
                                        });
                                    });
                                }

                                // Quick search modal
                                const quickSearchBtn = document.getElementById('quickSearchExec');
                                if (quickSearchBtn) quickSearchBtn.addEventListener('click', () => {
                                    let val = document.getElementById('quickGlobalSearch')?.value;
                                    if (val) showToast(`Searching for "${val}"…`, 'info');
                                });

                                updateSearchBtn();
                                updateHeroSearchBtn();

                                // Hero search button - transfer items to main form
                                const heroSearchBtn = document.getElementById('heroSearchBtn');
                                if (heroSearchBtn) {
                                    heroSearchBtn.addEventListener('click', (e) => {
                                        e.preventDefault();
                                        const heroList = document.getElementById('heroEditableItemList');
                                        const mainList = document.getElementById('editableItemList');
                                        const heroItems = heroList.querySelectorAll('input[type="hidden"]');
                                        heroItems.forEach(inp => {
                                            let value = inp.value;
                                            const wrap = document.createElement('div');
                                            wrap.className = 'tag-item';
                                            wrap.innerHTML = `<span>${escapeHtml(value)}</span><i class="fas fa-times-circle text-danger" style="cursor:pointer;" onclick="this.parentElement.remove(); updateSearchBtn()"></i><input type="hidden" name="item_names[]" value="${escapeHtml(value)}">`;
                                            mainList.appendChild(wrap);
                                        });
                                        heroList.innerHTML = '';
                                        updateHeroSearchBtn();
                                        document.getElementById('searchSection').scrollIntoView({
                                            behavior: 'smooth'
                                        });
                                        updateSearchBtn();
                                        showToast('Items transferred to search panel below', 'success');
                                    });
                                }
                            });
                        </script>
                        @endsection
