@extends('layouts.app')

@section('content')
@php
    $whatsAppNumber = '256773496048';
    $displayPhone = '+256 773 496 048';
    $emailAddress = 'support@maf.com';
    $socialLinks = [
        [
            'label' => 'Facebook',
            'handle' => '@mafuganda',
            'icon' => 'bi-facebook',
            'url' => 'https://facebook.com/mafuganda',
        ],
        [
            'label' => 'Instagram',
            'handle' => '@mafuganda',
            'icon' => 'bi-instagram',
            'url' => 'https://instagram.com/mafuganda',
        ],
        [
            'label' => 'LinkedIn',
            'handle' => 'MAF Uganda',
            'icon' => 'bi-linkedin',
            'url' => 'https://www.linkedin.com/company/maf-uganda',
        ],
        [
            'label' => 'WhatsApp',
            'handle' => $displayPhone,
            'icon' => 'bi-whatsapp',
            'url' => 'https://wa.me/' . $whatsAppNumber,
        ],
    ];

    $contactCards = [
        [
            'title' => 'Email Support',
            'icon' => 'bi-envelope',
            'value' => $emailAddress,
            'link' => 'mailto:' . $emailAddress,
            'copy' => 'Reach out for general questions and support.',
        ],
        [
            'title' => 'Call Us',
            'icon' => 'bi-telephone',
            'value' => $displayPhone,
            'link' => 'tel:+256773496048',
            'copy' => 'Talk to us directly for urgent assistance.',
        ],
        [
            'title' => 'WhatsApp',
            'icon' => 'bi-whatsapp',
            'value' => 'Start a chat',
            'link' => 'https://wa.me/' . $whatsAppNumber,
            'copy' => 'Fastest way to send suggestions and questions.',
        ],
        [
            'title' => 'Location',
            'icon' => 'bi-geo-alt',
            'value' => 'Kampala, Uganda',
            'link' => null,
            'copy' => 'Serving patients and pharmacies across Uganda.',
        ],
    ];
@endphp

<!-- Page Header -->
<div class="container py-5 text-center">
    <h1 class="fw-bold text-primary animate-on-scroll">Contact MedFinder</h1>
    <p class="text-muted animate-on-scroll">
        Get in touch for support, feedback, partnerships, and questions about the platform.
    </p>
</div>

<!-- Main Contact Section -->
<section class="container mb-5">
    <div class="row align-items-center g-4">
        <div class="col-lg-6 animate-on-scroll">
            <h2 class="fw-bold">Simple Ways To Reach Us</h2>
            <p class="lead text-secondary">
                We want communication to feel as easy as the MedFinder experience.
            </p>
            <p>
                Whether you have a suggestion, need support, or want to work with us, you can contact the team by
                email, phone, or WhatsApp. WhatsApp is the fastest option for quick feedback and follow-up.
            </p>

            <div class="row text-center mt-4">
                <div class="col-4">
                    <h4 class="fw-bold text-primary">24/7</h4>
                    <small>WhatsApp</small>
                </div>
                <div class="col-4">
                    <h4 class="fw-bold text-primary">4</h4>
                    <small>Contact Options</small>
                </div>
                <div class="col-4">
                    <h4 class="fw-bold text-primary">1</h4>
                    <small>Support Team</small>
                </div>
            </div>
        </div>

        <div class="col-lg-6 animate-on-scroll">
            <div class="contact-panel rounded shadow-sm p-4 p-lg-5 h-100">
                <h4 class="fw-bold mb-3">Quick Contact</h4>
                <div class="mb-3">
                    <div class="small text-white-50">Email</div>
                    <a class="text-white text-decoration-none fw-semibold" href="mailto:{{ $emailAddress }}">{{ $emailAddress }}</a>
                </div>
                <div class="mb-3">
                    <div class="small text-white-50">Phone</div>
                    <a class="text-white text-decoration-none fw-semibold" href="tel:+256773496048">{{ $displayPhone }}</a>
                </div>
                <div class="mb-0">
                    <div class="small text-white-50">Location</div>
                    <div class="fw-semibold">Kampala, Uganda</div>
                </div>
            </div>
        </div>
    </div>
</section>




<!-- Suggestion Box -->
<section class="container py-5">
    <div class="text-center animate-on-scroll">
        <h3 class="fw-bold">Suggestion Box</h3>
        <p class="text-muted mx-auto" style="max-width: 700px;">
            Send your message through WhatsApp using the form below. It is the quickest way to get feedback to the team.
        </p>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 animate-on-scroll">
                <div class="card-body p-4 p-lg-5">
                    <form id="whatsappSuggestionForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Name</label>
                                <input class="form-control rounded-3" id="contactName" name="name" placeholder="Your name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email</label>
                                <input class="form-control rounded-3" id="contactEmail" type="email" name="email" placeholder="email@example.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Topic</label>
                                <select class="form-select rounded-3" id="contactTopic" name="topic" required>
                                    <option value="">Choose a topic</option>
                                    <option value="Suggestion">Suggestion</option>
                                    <option value="Support">Support</option>
                                    <option value="Pharmacy Partnership">Pharmacy Partnership</option>
                                    <option value="General Inquiry">General Inquiry</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Preferred Reply</label>
                                <select class="form-select rounded-3" id="contactReply" name="reply">
                                    <option value="WhatsApp">WhatsApp</option>
                                    <option value="Email">Email</option>
                                    <option value="Phone Call">Phone Call</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Message</label>
                                <textarea class="form-control rounded-3" id="contactMessage" rows="5" name="message" placeholder="Tell us how we can help." required></textarea>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <button class="btn btn-success btn-lg px-5 py-3 pulse-btn" type="submit">
                                <i class="bi bi-whatsapp me-2"></i>Send Via WhatsApp
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>



@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

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

        const pulseBtn = document.querySelector('.pulse-btn');
        if (pulseBtn) {
            setInterval(() => {
                pulseBtn.classList.add('pulse-animation');
                setTimeout(() => {
                    pulseBtn.classList.remove('pulse-animation');
                }, 1000);
            }, 3000);
        }

        const form = document.getElementById('whatsappSuggestionForm');
        if (!form) {
            return;
        }

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const name = document.getElementById('contactName').value.trim();
            const email = document.getElementById('contactEmail').value.trim();
            const topic = document.getElementById('contactTopic').value;
            const reply = document.getElementById('contactReply').value;
            const message = document.getElementById('contactMessage').value.trim();

            if (!name || !email || !topic || !message) {
                form.reportValidity();
                return;
            }

            const whatsappMessage = [
                'Hello MAF,',
                '',
                'New contact form message:',
                'Name: ' + name,
                'Email: ' + email,
                'Topic: ' + topic,
                'Preferred reply: ' + reply,
                '',
                'Message:',
                message
            ].join('\n');

            const url = 'https://wa.me/{{ $whatsAppNumber }}?text=' + encodeURIComponent(whatsappMessage);
            window.open(url, '_blank', 'noopener');
        });
    });
</script>

<style>
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease-out;
    }

    .animate-on-scroll.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .contact-panel {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: #fff;
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
