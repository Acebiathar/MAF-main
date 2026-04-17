@extends('layouts.public')

@section('title', 'MedFinder | Privacy Policy')

@section('content')
<div class="container my-5">
    <div class="public-card rounded-5 p-4 p-lg-5">

        <div class="section-kicker mb-2">Privacy Policy</div>
        <h1 class="fw-bold mb-3">Your Privacy Matters</h1>
        <p class="text-muted mb-4">
            This Privacy Policy explains how MedFinder collects, uses, and protects your personal information when you use our platform.
        </p>

        {{-- 1 --}}
        <h4 class="fw-semibold mt-4">1. Information We Collect</h4>
        <p class="text-muted">
            We may collect the following information:
        </p>
        <ul class="text-muted">
            <li>Personal details (name, email, phone number)</li>
            <li>Location data to show nearby pharmacies</li>
            <li>Medicine search and reservation details</li>
            <li>Device and usage information (browser, IP address)</li>
        </ul>

        {{-- 2 --}}
        <h4 class="fw-semibold mt-4">2. How We Use Your Information</h4>
        <ul class="text-muted">
            <li>To help you find available medicines</li>
            <li>To connect you with nearby pharmacies</li>
            <li>To manage your reservations</li>
            <li>To improve system performance and user experience</li>
            <li>To send important updates and notifications</li>
        </ul>

        {{-- 3 --}}
        <h4 class="fw-semibold mt-4">3. Sharing of Information</h4>
        <p class="text-muted">
            We may share your information with:
        </p>
        <ul class="text-muted">
            <li>Pharmacies to fulfill your reservations</li>
            <li>Service providers that support our system</li>
        </ul>
        <p class="text-muted">
            We do NOT sell your personal data to third parties.
        </p>

        {{-- 4 --}}
        <h4 class="fw-semibold mt-4">4. Data Security</h4>
        <p class="text-muted">
            We use appropriate technical and security measures to protect your personal information from unauthorized access, loss, or misuse.
        </p>

        {{-- 5 --}}
        <h4 class="fw-semibold mt-4">5. Your Rights</h4>
        <ul class="text-muted">
            <li>You can access your personal data</li>
            <li>You can update or correct your information</li>
            <li>You can request deletion of your data</li>
        </ul>

        {{-- 6 --}}
        <h4 class="fw-semibold mt-4">6. Cookies and Tracking</h4>
        <p class="text-muted">
            MedFinder uses cookies to improve your browsing experience and analyze system usage.
        </p>

        {{-- 7 --}}
        <h4 class="fw-semibold mt-4">7. Children’s Privacy</h4>
        <p class="text-muted">
            Our system is not intended for children under 13. We do not knowingly collect data from children.
        </p>

        {{-- 8 --}}
        <h4 class="fw-semibold mt-4">8. Changes to This Policy</h4>
        <p class="text-muted">
            We may update this Privacy Policy from time to time. Any changes will be posted on this page.
        </p>

        {{-- 9 --}}
        <h4 class="fw-semibold mt-4">9. Contact Us</h4>
        <p class="text-muted">
            If you have any questions about this Privacy Policy, please contact us:
        </p>
        <ul class="text-muted">
            <li>Email: support@medfinder.com</li>
            <li>Phone: +256 XXX XXX XXX</li>
        </ul>

    </div>
</div>

<style>
    h4 {
        color: #198754;
    }

    ul li {
        margin-bottom: 6px;
    }
</style>
@endsection