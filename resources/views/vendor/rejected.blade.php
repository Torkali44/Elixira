@extends('layouts.framer')

@section('title', 'Action Required - Elixira')

@section('content')
<div class="page-content">
    <div class="elx-container" style="max-width: 600px; text-align: center; padding: 4rem 1rem;">
        
        <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(220, 53, 69, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem;" data-animate>
            <i class="fas fa-exclamation-circle" style="font-size: 3rem; color: #ff6b6b;"></i>
        </div>

        <h1 class="elx-hero__title" style="font-size: 2.5rem; margin-bottom: 1rem;" data-animate>
            Action <span style="color: #ff6b6b;">Required</span>
        </h1>
        
        <p style="color: var(--elx-light); font-size: 1.1rem; line-height: 1.6; margin-bottom: 2rem;" data-animate>
            Your application needs some changes before it can be approved. Please review the notes from our team below and update your application.
        </p>

        <div style="background: rgba(220, 53, 69, 0.05); border: 1px solid rgba(220, 53, 69, 0.2); padding: 1.5rem; border-radius: 20px; margin-bottom: 2.5rem; text-align: left;" data-animate>
            <div style="font-weight: 700; color: #ff6b6b; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-comment-alt"></i> Notes from Admin:
            </div>
            <div style="color: var(--elx-white); font-size: 1rem; line-height: 1.6; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                {{ $vendorProfile->rejection_reason }}
            </div>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: center;" data-animate>
            <a href="{{ route('vendor.onboarding', ['edit' => 1]) }}" class="elx-btn elx-btn--primary" style="padding: 1rem 2.5rem;">
                <i class="fas fa-edit me-2"></i> Edit & Resubmit Application
            </a>
            <a href="{{ route('home') }}" class="elx-btn elx-btn--glass">
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
