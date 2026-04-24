@extends('layouts.framer')

@section('title', 'Choose Avatar - Elixira')

@section('head')
<style>
    .avatar-shell {
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 32px;
        padding: 1.4rem;
        background: rgba(255,255,255,0.02);
    }
    .avatar-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 1rem; }
    .avatar-card { border:1px solid rgba(255,255,255,0.08); border-radius: 18px; padding: .9rem; background: rgba(255,255,255,0.03); text-align:center; cursor:pointer; display:flex; flex-direction:column; align-items:center; justify-content:center; }
    .avatar-card img { width:72px; height:72px; object-fit:cover; border-radius:50%; margin-bottom:.6rem; border:2px solid transparent; }
    .avatar-card input { display:none; }
    .avatar-card:has(input:checked) { border-color: var(--elx-cyan); box-shadow: 0 0 0 2px rgba(74,200,246,.2) inset; }
    .avatar-card:has(input:checked) img { border-color: var(--elx-cyan); }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container">
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title"><span class="elx-hero__title-gradient">Choose Your Avatar</span></h1>
            <p class="elx-hero__subtitle">Pick any avatar enabled by admin and save it to your account.</p>
        </div>

        @if(session('status') === 'avatar-updated')
            <div style="padding:1rem; border-radius:14px; margin-bottom:1rem; border:1px solid rgba(74,200,246,.25); background:rgba(74,200,246,.12); color:var(--elx-cyan);">
                Avatar updated successfully.
            </div>
        @endif

        <form method="POST" action="{{ route('profile.avatar-options.update') }}">
            @csrf
            @method('PATCH')
            <div class="avatar-shell" data-animate>
                <div class="avatar-grid">
                    @foreach($avatarOptions as $option)
                        <label class="avatar-card">
                            <input type="radio" name="avatar_option_id" value="{{ $option->id }}" @checked(old('avatar_option_id', $user->avatar_option_id) == $option->id)>
                            <img src="{{ $option->image_url }}" alt="{{ $option->name }}">
                            <div style="font-weight:700; text-align:center;">{{ $option->name }}</div>
                        </label>
                    @endforeach
                </div>
            </div>
            @error('avatar_option_id')
                <div style="color:#ff9b9b; margin-top:.8rem;">{{ $message }}</div>
            @enderror
            <div style="margin-top:1.3rem; display:flex; gap:.8rem;">
                <button type="submit" class="elx-btn elx-btn--primary">Save Avatar</button>
                <a href="{{ route('profile.edit') }}" class="elx-btn elx-btn--glass">Back To Account</a>
            </div>
        </form>
    </div>
</div>
@endsection
