@extends('layouts.framer')

@section('title', 'Application Pending - Elixira')

@section('content')
<div class="page-content">
    <div class="elx-container" style="max-width: 600px; text-align: center; padding: 4rem 1rem;">
        
        <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(255, 193, 7, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem;" data-animate>
            <i class="fas fa-clock" style="font-size: 3rem; color: #ffd36a;"></i>
        </div>

        <h1 class="elx-hero__title" style="font-size: 2.5rem; margin-bottom: 1rem;" data-animate>
            Application <span style="color: #ffd36a;">Pending</span>
        </h1>
        
        <p style="color: var(--elx-light); font-size: 1.1rem; line-height: 1.6; margin-bottom: 2rem;" data-animate>
            Thank you for applying to become a vendor at Elixira! Your application is currently under review by our administration team. We will get back to you shortly.
        </p>

        <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); padding: 1.5rem; border-radius: 20px; margin-bottom: 2rem; text-align: left;" data-animate>
            <div style="font-weight: 600; color: var(--elx-white); margin-bottom: 0.5rem;">What happens next?</div>
            <ul style="color: var(--elx-light); font-size: 0.95rem; line-height: 1.5; padding-left: 1.2rem; margin: 0;">
                <li>Our team will review your brand details and products.</li>
                <li>We might contact you if we need any additional information.</li>
                <li>Once approved, you will get access to the Vendor Dashboard.</li>
            </ul>
        </div>

        <div data-animate>
            <a href="{{ route('profile.edit') }}" class="elx-btn elx-btn--glass">Return to Profile</a>
        </div>
    </div>
</div>
@endsection
