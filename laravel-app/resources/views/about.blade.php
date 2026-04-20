@extends('layouts.app')

@section('content')
@php
    $teamMembers = [
        [
            'name' => 'Amina N.',
            'role' => 'Founder',
            'image' => 'images/team-1.jpeg',
            'accent' => 'primary',
            'bio' => 'Guides the platform vision.',
        ],
        [
            'name' => 'Brian K.',
            'role' => 'Lead Developer',
            'image' => 'images/team-2.png',
            'accent' => 'teal',
            'bio' => 'Builds the core experience.',
        ],
        [
            'name' => 'Clara S.',
            'role' => 'Pharmacist Advisor',
            'image' => 'images/team 3.jpeg',
            'accent' => 'gold',
            'bio' => 'Supports safe medicine data.',
        ],
        [
            'name' => 'David O.',
            'role' => 'Community Manager',
            'image' => 'images/team-1.jpeg',
            'accent' => 'rose',
            'bio' => 'Works with patients and pharmacies.',
        ],
        [
            'name' => 'Esther M.',
            'role' => 'Operations',
            'image' => 'images/team-2.png',
            'accent' => 'teal',
            'bio' => 'Keeps daily processes moving.',
        ],
        [
            'name' => 'Frank P.',
            'role' => 'Patient Support',
            'image' => 'images/team 3.jpeg',
            'accent' => 'gold',
            'bio' => 'Helps improve the user journey.',
        ],
    ];
@endphp

<style>
    .about-page {
        --about-navy: #17324d;
        --about-blue: #2b6cb0;
        --about-teal: #1f8a8a;
        --about-gold: #d7a742;
        --about-rose: #d96c5f;
        --about-text: #314454;
        --about-muted: #667887;
        --about-bg: #f6fbff;
    }

    .about-page {
        color: var(--about-text);
    }

    .about-hero {
        background: linear-gradient(135deg, #17324d 0%, #2b6cb0 100%);
        color: #fff;
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 0 18px 40px rgba(23, 50, 77, 0.14);
    }

    .about-hero h1,
    .about-section h2 {
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .about-hero p,
    .about-section p,
    .about-section li,
    .team-card p {
        color: var(--about-muted);
    }

    .about-hero p {
        color: rgba(255, 255, 255, 0.85);
        max-width: 42rem;
    }

    .about-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .about-nav a {
        text-decoration: none;
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.28);
        background: rgba(255, 255, 255, 0.1);
        padding: 0.6rem 1rem;
        border-radius: 999px;
        font-size: 0.95rem;
    }

    .about-nav a:hover {
        background: rgba(255, 255, 255, 0.18);
    }

    .about-section {
        scroll-margin-top: 100px;
    }

    .about-card,
    .team-card {
        background: #fff;
        border: 1px solid rgba(23, 50, 77, 0.08);
        border-radius: 20px;
        box-shadow: 0 12px 30px rgba(23, 50, 77, 0.08);
    }

    .about-card {
        height: 100%;
        padding: 1.5rem;
    }

    .about-card h3 {
        color: var(--about-navy);
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
    }

    .about-points {
        padding-left: 1rem;
        margin-bottom: 0;
    }

    .about-points li + li {
        margin-top: 0.45rem;
    }

    .team-card {
        overflow: hidden;
        height: 100%;
        transition: transform 180ms ease, box-shadow 180ms ease;
    }

    .team-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 36px rgba(23, 50, 77, 0.12);
    }

    .team-media {
        height: 300px;
        overflow: hidden;
    }

    .team-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .accent-primary .team-media {
        border-top: 5px solid var(--about-blue);
    }

    .accent-teal .team-media {
        border-top: 5px solid var(--about-teal);
    }

    .accent-gold .team-media {
        border-top: 5px solid var(--about-gold);
    }

    .accent-rose .team-media {
        border-top: 5px solid var(--about-rose);
    }

    .team-content {
        padding: 1.2rem 1.25rem 1.4rem;
    }

    .team-role {
        display: inline-block;
        margin-bottom: 0.6rem;
        padding: 0.3rem 0.75rem;
        background: var(--about-bg);
        border-radius: 999px;
        color: var(--about-blue);
        font-size: 0.85rem;
        font-weight: 600;
    }

    .team-content h5 {
        margin-bottom: 0.35rem;
        color: var(--about-navy);
    }

    @media (max-width: 767.98px) {
        .about-hero {
            padding: 1.75rem;
        }

        .team-media {
            height: 260px;
        }
    }
</style>

<div class="about-page py-4 py-lg-5">
    <section class="about-hero mb-4">
        <h1 class="mb-3">About Us</h1>
        <p class="mb-0">
            Medicine Availability Finder helps patients find medicine faster and connect with trusted pharmacies more easily.
        </p>

        <div class="about-nav">
            <a href="#overview">Overview</a>
            <a href="#services">What We Do</a>
            <a href="#team">Our Team</a>
        </div>
    </section>

    <section id="overview" class="about-section mb-4">
        <h2 class="mb-3 text-primary">Overview</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="about-card">
                    <h3>Our Vision</h3>
                    <p class="mb-0">To make medicine access simpler, clearer, and more dependable for patients.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="about-card">
                    <h3>Our Mission</h3>
                    <p class="mb-0">We connect patients and pharmacies through a platform that saves time and builds trust.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="about-section mb-4">
        <h2 class="mb-3 text-primary">What We Do</h2>
        <div class="about-card">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h3>Search</h3>
                    <p class="mb-0">Patients can quickly check medicine availability.</p>
                </div>
                <div class="col-lg-4">
                    <h3>Compare</h3>
                    <p class="mb-0">The platform helps users view pharmacy options with less stress.</p>
                </div>
                <div class="col-lg-4">
                    <h3>Support</h3>
                    <p class="mb-0">We make it easier for pharmacies and patients to stay connected.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section mb-4">
        <h2 class="mb-3 text-primary">Core Values</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="about-card">
                    <h3>Accessibility</h3>
                    <p class="mb-0">Useful information should be easy to find.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="about-card">
                    <h3>Transparency</h3>
                    <p class="mb-0">Clear information supports better decisions.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="about-card">
                    <h3>Reliability</h3>
                    <p class="mb-0">Patients need a service they can trust.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="team" class="about-section">
        <h2 class="mb-3 text-primary">Our Team</h2>
        <p class="text-secondary mb-4">Meet the people behind MAF.</p>

        <div class="row g-4">
            @foreach ($teamMembers as $member)
                <div class="col-md-6 col-lg-4">
                    <article class="team-card accent-{{ $member['accent'] }}">
                        <div class="team-media">
                            <img src="{{ asset($member['image']) }}" alt="{{ $member['name'] }}">
                        </div>
                        <div class="team-content">
                            <span class="team-role">{{ $member['role'] }}</span>
                            <h5 class="fw-bold">{{ $member['name'] }}</h5>
                            <p class="mb-0">{{ $member['bio'] }}</p>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
