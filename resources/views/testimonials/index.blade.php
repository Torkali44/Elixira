@extends('layouts.framer')

@section('title', 'Testimonials - Elixira')

@section('content')
    <style>
        .testimonials-page {
            background-color: #0b161c;
            padding: 60px 20px;
            min-height: 100vh;
        }

        /* Header & Tabs */
        .testimonials-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .testimonials-title {
            font-size: 80px;
            font-weight: 700;
            margin-bottom: 30px;
            background: linear-gradient(180deg, #fff 0%, #4ac8f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-family: 'Istok Web', sans-serif;
            letter-spacing: -2px;
        }

        .testimonials-tabs {
            display: flex;
            justify-content: center;
            gap: 30px;
            list-style: none;
            padding: 0;
            flex-wrap: wrap;
        }

        .testimonials-tabs li a {
            color: #888;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 5px 0;
            position: relative;
        }

        .testimonials-tabs li a:hover,
        .testimonials-tabs li a.active {
            color: #fff;
        }

        .testimonials-tabs li a.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #4ac8f6;
            box-shadow: 0 0 10px #4ac8f6;
        }

        .testimonials-tabs li a.tab-write {
            color: #4ac8f6;
        }

        /* Form Container */
        .review-form-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            background-color: #0d1a20;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            font-family: 'Istok Web', sans-serif;
            color: #888;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        }

        /* ... Existing Form Styles ... */
        .review-section-title {
            font-size: 15px;
            margin-bottom: 25px;
            color: #aaa;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .avatars-wrapper {
            display: flex;
            overflow-x: auto;
            gap: 20px;
            margin-bottom: 50px;
            padding: 10px 5px;
            scrollbar-width: thin;
            scrollbar-color: #4ac8f6 rgba(255, 255, 255, 0.05);
            -webkit-overflow-scrolling: touch;
        }

        .avatars-wrapper::-webkit-scrollbar {
            height: 6px;
        }

        .avatars-wrapper::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .avatars-wrapper::-webkit-scrollbar-thumb {
            background: #4ac8f6;
            border-radius: 10px;
        }

        .avatar-label {
            flex: 0 0 calc((100% - (7 * 20px)) / 8);
            min-width: 85px;
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            gap: 15px;
            transition: transform 0.3s ease;
        }

        .avatar-img-container {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.03);
            padding: 3px;
            border: 2px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .avatar-img-container img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .avatar-radio {
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            cursor: pointer;
            transition: all 0.3s ease;
            outline: none;
        }

        .avatar-radio:checked {
            background-color: #fff;
            border-color: #4ac8f6;
            box-shadow: inset 0 0 0 4px #4ac8f6, 0 0 15px rgba(74, 200, 246, 0.6);
        }

        .avatar-label:has(.avatar-radio:checked) .avatar-img-container {
            border-color: #4ac8f6;
            box-shadow: 0 0 20px rgba(74, 200, 246, 0.35);
            background-color: rgba(74, 200, 246, 0.1);
            transform: scale(1.1);
        }

        .avatar-label:hover .avatar-img-container {
            transform: scale(1.08);
        }

        .form-wrapper {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .form-label {
            font-size: 14px;
            color: #888;
            padding-left: 5px;
        }

        .form-input {
            width: 100%;
            padding: 15px 20px;
            background-color: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            color: #fff;
            font-family: inherit;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: #4ac8f6;
            box-shadow: 0 0 0 2px rgba(74, 200, 246, 0.15);
            background-color: rgba(0, 0, 0, 0.4);
        }

        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%234ac8f6' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 20px center;
            cursor: pointer;
        }

        textarea.form-input {
            resize: vertical;
            min-height: 140px;
            line-height: 1.6;
        }

        .newsletter-group {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 5px;
        }

        .newsletter-checkbox {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            appearance: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
/* 
        .newsletter-checkbox:checked {
            background-color: #4ac8f6;
            border-color: #4ac8f6;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23000' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'%3E%3C/polyline%3E%3C/svg%3E");
            background-size: 12px;
            background-position: center;
            background-repeat: no-repeat;
        } */
 .newsletter-checkbox:checked {
            background-color: #4ac8f6;
            border-color: #4ac8f6;
        }

        .newsletter-checkbox:checked::after {
            content: '✓';
            color: #000;
            font-size: 14px;
            font-weight: 900;
        }
        .submit-btn-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: 15px;
        }

        .submit-btn {
            background: linear-gradient(135deg, rgba(74, 200, 246, 0.15) 0%, rgba(74, 200, 246, 0.05) 100%);
            border: 1px solid rgba(74, 200, 246, 0.4);
            color: #4ac8f6;
            padding: 14px 40px;
            border-radius: 40px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            letter-spacing: 0.5px;
        }

        .submit-btn:hover {
            background: rgba(74, 200, 246, 0.25);
            box-shadow: 0 8px 25px rgba(74, 200, 246, 0.3);
            transform: translateY(-3px) scale(1.02);
            color: #fff;
            border-color: #fff;
        }

        /* Grid for Screenshots & Videos */
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .screenshot-card {
            background: #0d1a20;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .screenshot-card:hover {
            transform: translateY(-10px);
        }

        .screenshot-card img {
            width: 100%;
            height: auto;
            display: block;
        }

        .video-card {
            background: #0d1a20;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            overflow: hidden;
            aspect-ratio: 16/9;
        }

        .video-card iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .testimonial-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .testimonial-card:hover {
            transform: translateY(-10px) scale(1.01);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5) !important;
            border-color: rgba(74, 200, 246, 0.3) !important;
            background: #11222b !important;
        }

        /* ... Responsive ... */
        @media (max-width: 768px) {
            .testimonials-title {
                font-size: 50px;
            }

            .testimonials-tabs {
                gap: 15px;
            }

            .testimonials-tabs li a {
                font-size: 1rem;
            }
        }
    </style>

    <div class="testimonials-page">
        <div class="testimonials-header">
            <h1 class="testimonials-title">Testimonials</h1>
            <ul class="testimonials-tabs">
                <li><a href="{{ route('testimonials.index', ['tab' => 'direct']) }}"
                        class="{{ $tab == 'direct' ? 'active' : '' }}">Direct</a></li>
                <li><a href="{{ route('testimonials.index', ['tab' => 'whatsapp']) }}"
                        class="{{ $tab == 'whatsapp' ? 'active' : '' }}">WhatsApp</a></li>
                <li><a href="{{ route('testimonials.index', ['tab' => 'instagram']) }}"
                        class="{{ $tab == 'instagram' ? 'active' : '' }}">Instagram</a></li>
                <li><a href="{{ route('testimonials.index', ['tab' => 'external']) }}"
                        class="{{ $tab == 'external' ? 'active' : '' }}">External</a></li>
                <li><a href="{{ route('testimonials.index', ['tab' => 'video']) }}"
                        class="{{ $tab == 'video' ? 'active' : '' }}">Videos</a></li>
                <li><a href="{{ route('testimonials.index', ['tab' => 'write']) }}"
                        class="tab-write {{ $tab == 'write' ? 'active' : '' }}">Write Review</a></li>
            </ul>
        </div>

        <div class="testimonials-content">
            @if($tab == 'direct')
                <div class="testimonials-grid"
                    style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto;">
                    @foreach($reviews as $rev)
                        <div class="testimonial-card"
                            style="background: #0d1a20; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 30px; padding: 40px; position: relative; box-shadow: 0 15px 40px rgba(0,0,0,0.4); display: flex; flex-direction: column; justify-content: space-between; min-height: 350px;">

                            <!-- Header: Stars -->
                            <div style="display: flex; gap: 5px; color: #4ac8f6; font-size: 1rem; margin-bottom: 20px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $rev->rating ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                            </div>

                            <!-- Content -->
                            <div style="flex-grow: 1; display: flex; align-items: center; padding: 10px 0;">
                                <p style="color: #eee; font-size: 1.1rem; line-height: 1.6; font-family: 'Istok Web', sans-serif;">
                                    {{ $rev->content }}
                                </p>
                            </div>

                            <!-- Footer: User Info -->
                            <div
                                style="display: flex; align-items: center; gap: 15px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.05);">
                                <div
                                    style="width: 45px; height: 45px; border-radius: 50%; border: 2px solid #4ac8f6; padding: 2px;">
                                    <img src="{{ $rev->avatar ?? 'https://framerusercontent.com/images/cTc7CUtNbTmlTgoiKuHSwOHME.png' }}"
                                        alt="{{ $rev->name }}"
                                        style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                </div>
                                <div style="display: flex; flex-direction: column;">
                                    <h4 style="margin: 0; color: #fff; font-size: 1rem; font-weight: 700;">{{ $rev->name }}</h4>
                                    <div style="display: flex; gap: 10px; font-size: 0.8rem; color: #666;">
                                        <span>{{ $rev->skin_type }}</span>
                                        <span>{{ $rev->age }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 50px; display: flex; justify-content: center;">
                    {{ $reviews->links() }}
                </div>

                <div style="text-align: center; margin-top: 60px;">
                    <p style="color: #888; margin-bottom: 20px;">Have a story to share?</p>
                    <a href="{{ route('testimonials.index', ['tab' => 'write']) }}" class="submit-btn"
                        style="text-decoration: none; display: inline-block;">Write Your Reflection</a>
                </div>

            @elseif($tab == 'write')
                <div class="elx-container" style="margin-top: 0; margin-bottom: 1.5rem;">
                    @if(session('success'))
                        <div style="margin-top: 2rem;">
                            <div
                                style="background: rgba(74, 200, 246, 0.1); border: 1px solid #4ac8f6; color: #4ac8f6; padding: 1rem 2rem; border-radius: 12px; text-align: center;">
                                <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>{{ session('success') }}
                            </div>
                        </div>
                    @endif
                    @if(session('error'))
                        <div style="margin-top: 2rem;">
                            <div
                                style="background: rgba(220, 53, 69, 0.1); border: 1px solid #dc3545; color: #dc3545; padding: 1rem 2rem; border-radius: 12px; text-align: center;">
                                <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>{{ session('error') }}
                            </div>
                        </div>
                    @endif
                    @if($errors->any())
                        <div style="margin-top: 2rem;">
                            <div
                                style="background: rgba(255, 193, 7, 0.12); border: 1px solid #ffc107; color: #ffc107; padding: 1rem 2rem; border-radius: 12px; text-align: left;">
                                <strong style="display: block; margin-bottom: 0.5rem; text-align: center;"><i
                                        class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>Please Contact with support
                                        :</strong>
                                <ul style="margin: 0; padding-left: 1.25rem; color: #e8c76a;">
                                    @foreach($errors->all() as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="review-form-container">
                    <form action="{{ route('testimonials.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="direct">

                        <div class="review-section-title">Select Your Avatar</div>
                        <div class="avatars-wrapper">
                            @php $defaultAvatarUrl = $avatarOptions->first()->image_url ?? ''; @endphp
                            @forelse($avatarOptions as $index => $avatar)
                                <label class="avatar-label">
                                    <div class="avatar-img-container">
                                        <img src="{{ $avatar->image_url }}" alt="{{ $avatar->name }}">
                                    </div>
                                    <input type="radio" name="avatar" value="{{ $avatar->image_url }}" class="avatar-radio"
                                        @checked(old('avatar', $defaultAvatarUrl) === $avatar->image_url)>
                                </label>
                            @empty
                                <p style="grid-column: 1 / -1; color: #ff8a8a;">
                                    No avatar options are active now. Please contact support.
                                </p>
                            @endforelse
                        </div>

                        <div class="form-wrapper">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-input" placeholder="e.g. Ryo Hazoki"
                                        value="{{ old('name') }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-input" placeholder="e.g. duke@elixira.com"
                                        value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Age Range</label>
                                    <select name="age" class="form-input" required>
                                        <option value="" disabled @selected(old('age') === null || old('age') === '')>Select...</option>
                                        <option value="18-24" @selected(old('age') === '18-24')>18 - 24 years</option>
                                        <option value="25-34" @selected(old('age') === '25-34')>25 - 34 years</option>
                                        <option value="35-44" @selected(old('age') === '35-44')>35 - 44 years</option>
                                        <option value="45+" @selected(old('age') === '45+')>45+ years</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-input" required>
                                        <option value="" disabled @selected(old('gender') === null || old('gender') === '')>Select...</option>
                                        <option value="Male" @selected(old('gender') === 'Male')>Male</option>
                                        <option value="Female" @selected(old('gender') === 'Female')>Female</option>
                                        <option value="Other" @selected(old('gender') === 'Other')>Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Rating</label>
                                    <select name="rating" class="form-input" required>
                                        <option value="" disabled @selected(old('rating') === null || old('rating') === '')>Select Stars...</option>
                                        <option value="5" @selected(old('rating') == '5')>5 Stars</option>
                                        <option value="4" @selected(old('rating') == '4')>4 Stars</option>
                                        <option value="3" @selected(old('rating') == '3')>3 Stars</option>
                                        <option value="2" @selected(old('rating') == '2')>2 Stars</option>
                                        <option value="1" @selected(old('rating') == '1')>1 Star</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">How would you describe your experience with us?</label>
                                <textarea name="content" class="form-input" placeholder="Share your thoughts..."
                                    required>{{ old('content') }}</textarea>
                            </div>
                            <label class="newsletter-group">
                                <input type="checkbox" name="newsletter" class="newsletter-checkbox">
                                <span class="newsletter-text">Keep me in the loop about new features and member benefits</span>
                            </label>
                            <div class="submit-btn-wrapper">
                                <button type="submit" class="submit-btn">Send Reflection</button>
                            </div>
                        </div>
                    </form>
                </div>
            @elseif($tab == 'video')
                <div class="testimonials-grid">
                    @foreach($reviews as $rev)
                        @php
                            // Simple YouTube URL to embed conversion
                            $url = $rev->content;
                            if (strpos($url, 'v=') !== false) {
                                $parts = explode('v=', $url);
                                $id = explode('&', $parts[1])[0];
                                $embedUrl = "https://www.youtube.com/embed/" . $id;
                            } else if (strpos($url, 'youtu.be/') !== false) {
                                $parts = explode('youtu.be/', $url);
                                $id = explode('?', $parts[1])[0];
                                $embedUrl = "https://www.youtube.com/embed/" . $id;
                            } else {
                                $embedUrl = $url;
                            }
                        @endphp
                        <div class="video-card">
                            <iframe src="{{ $embedUrl }}" allowfullscreen></iframe>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="testimonials-grid">
                    @foreach($reviews as $rev)
                        <div class="screenshot-card">
                            @php
                                $avatarSrc = \Illuminate\Support\Str::startsWith((string) $rev->avatar, ['http://', 'https://'])
                                    ? $rev->avatar
                                    : asset('storage/' . ltrim((string) $rev->avatar, '/'));
                            @endphp
                            <img src="{{ $avatarSrc }}" alt="Testimonial Screenshot">
                            @if($rev->content)
                                <div
                                    style="padding: 15px; color: #ccc; font-size: 0.9rem; border-top: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.2);">
                                    {{ $rev->content }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection