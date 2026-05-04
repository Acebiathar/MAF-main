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
    </section>
    @endsection

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('lib/twentytwenty/jquery.event.move.js') }}"></script>
    <script src="{{ asset('lib/twentytwenty/jquery.twentytwenty.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        // Medicine search functionality
        function addItem() {
            const input = document.getElementById('itemInput');
            const list = document.getElementById('editableItemList');
            const value = input.value.trim();

            if (value === "") return;

            const itemId = 'item_' + Date.now();

            // Create a row container
            const div = document.createElement('div');
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

        // Auto-focus search input if focus parameter is present
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('focus') === 'search') {
                const searchInput = document.getElementById('itemInput');
                if (searchInput) {
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

        // WOW.js initialization
        new WOW().init();

        // Owl Carousel initialization
        $('.owl-carousel').owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 3
                }
            }
        });
    </script>

</body>

</html>