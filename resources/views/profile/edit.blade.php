@extends('layouts.framer')

@section('title', 'Account — Elixira')

@section('head')
<style>
    .profile-card {
        background: var(--elx-glass);
        backdrop-filter: blur(42px);
        border: 1px solid var(--elx-border);
        border-radius: var(--elx-radius-sm);
        padding: 2.5rem;
        margin-bottom: 2rem;
    }
    .form-input {
        width: 100%;
        padding: 0.8rem 1.2rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--elx-border);
        border-radius: 10px;
        color: var(--elx-white);
        margin-bottom: 1rem;
        outline: none;
        transition: var(--elx-transition);
    }
    .form-input:focus { border-color: var(--elx-cyan); }
    .form-label {
        display: block;
        color: var(--elx-gray);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }
    .profile-header {
        margin-bottom: 3rem;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container">
        {{-- Section Header --}}
        <div class="elx-section__header profile-header" data-animate>
            <h1 class="elx-hero__title">
                <span class="elx-hero__title-gradient">Your Account</span>
            </h1>
            <p class="elx-hero__subtitle">Update your details and manage your security.</p>
        </div>

        @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
            <div style="background: rgba(74, 200, 246, 0.1); color: var(--elx-cyan); padding: 1rem; border-radius: 10px; margin-bottom: 2rem; text-align: center; border: 1px solid var(--elx-cyan);" data-animate>
                Changes saved successfully.
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                {{-- Profile Info --}}
                <div class="profile-card" data-animate>
                    <h2 style="font-size: 1.2rem; color: var(--elx-accent); margin-bottom: 1.5rem;">✦ Profile Information</h2>
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')
                        
                        <label class="form-label">Full Name</label>
                        <input name="name" type="text" class="form-input" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div style="color: #ff8a8a; font-size: 0.8rem; margin-top: -0.5rem; margin-bottom: 1rem;">{{ $message }}</div>@enderror

                        <label class="form-label">Email Address</label>
                        <input name="email" type="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div style="color: #ff8a8a; font-size: 0.8rem; margin-top: -0.5rem; margin-bottom: 1rem;">{{ $message }}</div>@enderror

                        <button type="submit" class="elx-btn elx-btn--primary" style="margin-top: 1rem;">Save Changes</button>
                    </form>
                </div>

                {{-- Update Password --}}
                <div class="profile-card" data-animate>
                    <h2 style="font-size: 1.2rem; color: var(--elx-accent); margin-bottom: 1.5rem;">✦ Update Password</h2>
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')
                        
                        <label class="form-label">Current Password</label>
                        <input name="current_password" type="password" class="form-input">
                        @error('current_password', 'updatePassword')<div style="color: #ff8a8a; font-size: 0.8rem; margin-top: -0.5rem; margin-bottom: 1rem;">{{ $message }}</div>@enderror

                        <label class="form-label">New Password</label>
                        <input name="password" type="password" class="form-input">
                        @error('password', 'updatePassword')<div style="color: #ff8a8a; font-size: 0.8rem; margin-top: -0.5rem; margin-bottom: 1rem;">{{ $message }}</div>@enderror

                        <label class="form-label">Confirm New Password</label>
                        <input name="password_confirmation" type="password" class="form-input">
                        
                        <button type="submit" class="elx-btn elx-btn--glass" style="margin-top: 1rem;">Update Password</button>
                    </form>
                </div>

                {{-- Danger Zone --}}
                <div class="profile-card" style="border-color: rgba(220, 53, 69, 0.3);" data-animate>
                    <h2 style="font-size: 1.2rem; color: #ff8a8a; margin-bottom: 0.5rem;">✦ Danger Zone</h2>
                    <p style="color: var(--elx-gray); font-size: 0.9rem; margin-bottom: 1.5rem;">Once you delete your account, there is no going back. Please be certain.</p>
                    <button type="button" class="elx-btn" style="background: rgba(220, 53, 69, 0.1); color: #ff8a8a; border: 1px solid rgba(220, 53, 69, 0.3);" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        Delete Account
                    </button>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="profile-card" data-animate>
                    <h2 style="font-size: 1.2rem; color: var(--elx-accent); margin-bottom: 1.5rem;">✦ Quick Actions</h2>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <a href="{{ route('menu.index') }}" class="elx-btn elx-btn--primary" style="justify-content: center;">Browse Shop</a>
                        <a href="{{ route('orders.track') }}" class="elx-btn elx-btn--glass" style="justify-content: center;">Track Orders</a>
                    </div>
                </div>

                @if(isset($featuredItems) && $featuredItems->isNotEmpty())
                    <h3 style="font-size: 1.1rem; color: var(--elx-white); margin-bottom: 1.5rem; margin-left: 1rem;">Featured for you</h3>
                    <div style="display: flex; flex-direction: column; gap: 1rem;" data-animate>
                        @foreach($featuredItems as $item)
                        <a href="{{ route('menu.show', $item->id) }}" style="text-decoration: none; display: flex; align-items: center; gap: 1rem; background: var(--elx-glass); padding: 1rem; border-radius: 15px; border: 1px solid var(--elx-border); transition: 0.3s; color: inherit;">
                            <div style="width: 60px; height: 60px; border-radius: 10px; overflow: hidden; border: 1px solid var(--elx-border);">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; background: #1a2e38; display: flex; align-items: center; justify-content: center; color: var(--elx-cyan);">
                                        <i class="fas fa-seedling"></i>
                                    </div>
                                @endif
                            </div>
                            <div style="flex-grow: 1;">
                                <h4 style="font-size: 1rem; margin-bottom: 0.2rem;">{{ $item->name }}</h4>
                                <span style="color: var(--elx-cyan); font-weight: 700;">SAR {{ number_format($item->price, 2) }}</span>
                            </div>
                            <i class="fas fa-chevron-right" style="color: var(--elx-gray); font-size: 0.8rem;"></i>
                        </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #0f1c24; border: 1px solid var(--elx-border); color: white; border-radius: 20px;">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-header" style="border-bottom: 1px solid var(--elx-border);">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p style="color: var(--elx-gray); font-size: 0.9rem; margin-bottom: 1.5rem;">Enter your password to permanently delete this account.</p>
                    <label class="form-label">Password</label>
                    <input name="password" type="password" class="form-input" required>
                    @error('password', 'userDeletion')<div style="color: #ff8a8a; font-size: 0.8rem;">{{ $message }}</div>@enderror
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--elx-border);">
                    <button type="button" class="elx-btn elx-btn--glass" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="elx-btn" style="background: #dc3545; border: none; color: white;">Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Ensure modal works if there are errors
    @if($errors->userDeletion->isNotEmpty())
        document.addEventListener('DOMContentLoaded', function () {
            var myModal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
            myModal.show();
        });
    @endif
</script>
@endsection
