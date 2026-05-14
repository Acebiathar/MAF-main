<footer class="bg-dark text-white pt-5 pb-4">
    <div class="container text-center text-md-start">
        <div class="row text-center text-md-start">

            <!-- Company Info -->
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-primary">MedFinder</h5>
                <p>Connecting patients with pharmacies across Uganda. Find your medicine quickly and reliably.</p>
                <div class="mt-4">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold">Quick Links</h5>
                <p><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></p>
                <p><a href="{{ route('about') }}" class="text-white text-decoration-none">About Us</a></p>
                <p><a href="{{ route('how') }}" class="text-white text-decoration-none">How it Works</a></p>
                <p><a href="{{ route('contact') }}" class="text-white text-decoration-none">Contact</a></p>
            </div>

            <!-- Newsletter Section -->
            <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold">Newsletter</h5>
                <p>Stay updated with the latest health tips and pharmacy arrivals.</p>
                <form action="#" method="POST" class="mt-3">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" class="form-control rounded-start-pill border-0" placeholder="Your Email" aria-label="Email" required>
                        <button class="btn btn-primary rounded-end-pill px-4" type="submit">Join</button>
                    </div>
                </form>
            </div>

        </div>

        <hr class="mb-4">

        <!-- Copyright -->
        <div class="row align-items-center">
            <div class="col-md-7 col-lg-8">
                <p>© {{ date('Y') }} All rights reserved by:
                    <a href="#" class="text-primary text-decoration-none fw-bold">MedFinder Uganda</a>
                </p>
            </div>
            <div class="col-md-5 col-lg-4 text-md-end">
                <p><a href="{{ route('privacy') }}" class="text-white text-decoration-none">Privacy Policy</a> | <a href="#" class="text-white text-decoration-none">Terms of Use</a></p>
            </div>
        </div>
    </div>
</footer>