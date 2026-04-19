@extends('layouts.framer')

@section('title', 'Contact Us - Elixira')

@section('head')
<style>
    .contact-card {
        background: var(--elx-glass);
        backdrop-filter: blur(42px);
        border: 1px solid var(--elx-border);
        border-radius: var(--elx-radius-sm);
        padding: 2.5rem;
        height: 100%;
        transition: var(--elx-transition);
    }
    .contact-card:hover {
        border-color: var(--elx-cyan);
        transform: translateY(-5px);
    }
    .form-input {
        width: 100%;
        padding: 1rem 1.5rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--elx-border);
        border-radius: 100px;
        color: var(--elx-white);
        margin-bottom: 1.5rem;
        outline: none;
        transition: var(--elx-transition);
        font-family: inherit;
    }
    .form-input:focus {
        border-color: var(--elx-cyan);
        background: rgba(255, 255, 255, 0.08);
    }
    .form-textarea {
        border-radius: 20px;
        resize: none;
    }
    .info-item i {
        color: var(--elx-cyan);
        width: 40px;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container">
        {{-- Section Header --}}
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title">
                <span class="elx-hero__title-gradient">Get In Touch</span>
            </h1>
            <p class="elx-hero__subtitle">We'd love to hear from you and help with your natural glow journey</p>
        </div>

        <div class="elx-insights__grid" style="grid-template-columns: 1fr 1.5fr; gap: 2rem;">
            {{-- Contact Information --}}
            <div data-animate>
                <div class="contact-card">
                    <h3 class="elx-product-card__name" style="font-size: 1.5rem; margin-bottom: 2rem; color: var(--elx-accent);">Information</h3>
                    
                    <div class="info-item mb-4 d-flex align-items-start">
                        <i class="fas fa-map-marker-alt mt-1"></i>
                        <div>
                            <h5 style="color: var(--elx-white); margin-bottom: 0.3rem;">Our Location</h5>
                            <p style="color: var(--elx-gray); font-size: 0.9rem;">123 Wellness Ave, New York, NY 10001, USA</p>
                        </div>
                    </div>

                    <div class="info-item mb-4 d-flex align-items-start">
                        <i class="fas fa-phone mt-1"></i>
                        <div>
                            <h5 style="color: var(--elx-white); margin-bottom: 0.3rem;">Call Us</h5>
                            <p style="color: var(--elx-gray); font-size: 0.9rem;">+1 (555) 123-4567</p>
                        </div>
                    </div>

                    <div class="info-item mb-4 d-flex align-items-start">
                        <i class="fas fa-envelope mt-1"></i>
                        <div>
                            <h5 style="color: var(--elx-white); margin-bottom: 0.3rem;">Email Address</h5>
                            <p style="color: var(--elx-gray); font-size: 0.9rem;">info@elixira.com</p>
                        </div>
                    </div>

                    <div class="info-item d-flex align-items-start">
                        <i class="fas fa-clock mt-1"></i>
                        <div>
                            <h5 style="color: var(--elx-white); margin-bottom: 0.3rem;">Business Hours</h5>
                            <p style="color: var(--elx-gray); font-size: 0.9rem;">Mon-Fri: 9:00 AM - 6:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Form --}}
            <div data-animate>
                <div class="contact-card">
                    <h3 class="elx-product-card__name" style="font-size: 1.5rem; margin-bottom: 2rem; color: var(--elx-accent);">Send A Message</h3>
                    
                    @if(Session::has('success'))
                        <div style="background: rgba(74, 200, 246, 0.1); color: var(--elx-cyan); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; border: 1px solid var(--elx-cyan);">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    <form action="#" method="POST">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <input type="text" class="form-input" name="name" placeholder="Full Name" required>
                            <input type="email" class="form-input" name="email" placeholder="Email Address" required>
                        </div>
                        <input type="text" class="form-input" name="subject" placeholder="Subject" required>
                        <textarea class="form-input form-textarea" name="message" rows="5" placeholder="Your Message" required></textarea>
                        
                        <button type="submit" class="elx-btn elx-btn--primary" style="width: 100%; justify-content: center; padding: 1rem;">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Map --}}
<section class="map-section" data-animate style="margin-top: 6rem; line-height: 0;">
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d193595.15830869428!2d-74.119763973046!3d40.69766374874431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2s!4v1647856485648!5m2!1sen!2s"
        width="100%" height="450" style="border:0; filter: invert(90%) hue-rotate(180deg) brightness(0.9);" allowfullscreen="" loading="lazy"></iframe>
</section>
@endsection
