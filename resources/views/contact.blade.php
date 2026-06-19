@extends('layouts.framer')

@section('title', __('contact.page_title'))

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
    .info-item > i {
        color: var(--elx-cyan);
        width: 40px;
    }
    @media (max-width: 767.98px) {
        .elx-insights__grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container">
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title">
                <span class="elx-hero__title-gradient">{{ __('contact.hero_title') }}</span>
            </h1>
            <p class="elx-hero__subtitle">{{ __('contact.hero_subtitle') }}</p>
        </div>

        <div class="elx-insights__grid" style="grid-template-columns: 1fr 1.5fr; gap: 2rem;">
            <div data-animate>
                <div class="contact-card">
                    <h3 class="elx-product-card__name" style="font-size: 1.5rem; margin-bottom: 2rem; color: var(--elx-accent);">{{ __('contact.info_title') }}</h3>

                    <div class="info-item mb-4 d-flex align-items-start">
                        <i class="fas fa-map-marker-alt mt-1"></i>
                        <div>
                            <h5 style="color: var(--elx-white); margin-bottom: 0.3rem;">{{ __('contact.location_title') }}</h5>
                            <p style="color: var(--elx-gray); font-size: 0.9rem;">{{ __('contact.location_value') }}</p>
                        </div>
                    </div>

                    <div class="info-item mb-4 d-flex align-items-start">
                        <i class="fas fa-phone mt-1"></i>
                        <div>
                            <h5 style="color: var(--elx-white); margin-bottom: 0.3rem;">{{ __('contact.phone_title') }}</h5>
                            <p style="color: var(--elx-gray); font-size: 0.9rem;">{{ __('contact.phone_value') }}</p>
                        </div>
                    </div>

                    <div class="info-item mb-4 d-flex align-items-start">
                        <i class="fas fa-envelope mt-1"></i>
                        <div>
                            <h5 style="color: var(--elx-white); margin-bottom: 0.3rem;">{{ __('contact.email_title') }}</h5>
                            <p style="color: var(--elx-gray); font-size: 0.9rem;">{{ __('contact.email_value') }}</p>
                        </div>
                    </div>

                    <div class="info-item d-flex align-items-start">
                        <i class="fas fa-clock mt-1"></i>
                        <div>
                            <h5 style="color: var(--elx-white); margin-bottom: 0.3rem;">{{ __('contact.hours_title') }}</h5>
                            <p style="color: var(--elx-gray); font-size: 0.9rem;">{{ __('contact.hours_value') }}</p>
                        </div>
                    </div>

                    {{-- Social Links --}}
                    <div class="info-item mt-4">
                        <h5 style="color: var(--elx-white); margin-bottom: 0.8rem; text-align: center;">{{ __('contact.social_title') }}</h5>
                        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; justify-content: center;">
                                <a href="https://www.instagram.com/__elixira?igsh=bjl6d3FtMnk1a2V1"
                                   target="_blank" rel="noopener"
                                   style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: var(--elx-cyan); font-size: 1.1rem; transition: all 0.25s;" title="Instagram"
                                   onmouseover="this.style.background='rgba(74,200,246,0.2)'; this.style.borderColor='var(--elx-cyan)';"
                                   onmouseout="this.style.background='rgba(255,255,255,0.08)'; this.style.borderColor='rgba(255,255,255,0.15)';"
                                ><i class="fab fa-instagram"></i></a>

                                <a href="https://www.facebook.com/profile.php?id=61590957652478"
                                   target="_blank" rel="noopener"
                                   style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: var(--elx-cyan); font-size: 1.1rem; transition: all 0.25s;" title="Facebook"
                                   onmouseover="this.style.background='rgba(74,200,246,0.2)'; this.style.borderColor='var(--elx-cyan)';"
                                   onmouseout="this.style.background='rgba(255,255,255,0.08)'; this.style.borderColor='rgba(255,255,255,0.15)';"
                                ><i class="fab fa-facebook-f"></i></a>

                                <a href="https://wa.me/971545920050"
                                   target="_blank" rel="noopener"
                                   style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: var(--elx-cyan); font-size: 1.1rem; transition: all 0.25s;" title="WhatsApp"
                                   onmouseover="this.style.background='rgba(74,200,246,0.2)'; this.style.borderColor='var(--elx-cyan)';"
                                   onmouseout="this.style.background='rgba(255,255,255,0.08)'; this.style.borderColor='rgba(255,255,255,0.15)';"
                                ><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div data-animate>
                <div class="contact-card">
                    <h3 class="elx-product-card__name" style="font-size: 1.5rem; margin-bottom: 2rem; color: var(--elx-accent);">{{ __('contact.form_title') }}</h3>

                    @if(Session::has('success'))
                        <div style="background: rgba(74, 200, 246, 0.1); color: var(--elx-cyan); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; border: 1px solid var(--elx-cyan);">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <input type="text" class="form-input" name="name" placeholder="{{ __('contact.form_name') }}" required>
                            <input type="email" class="form-input" name="email" placeholder="{{ __('contact.form_email') }}" required>
                        </div>
                        <select class="form-input" name="reason" required style="appearance: auto;">
                            <option value="">{{ __('contact.form_reason_placeholder') }}</option>
                            <option value="inquiry">{{ __('contact.reason_inquiry') }}</option>
                            <option value="complaint">{{ __('contact.reason_complaint') }}</option>
                            <option value="technical">{{ __('contact.reason_technical') }}</option>
                            <option value="other">{{ __('contact.reason_other') }}</option>
                        </select>
                        <input type="text" class="form-input" name="subject" placeholder="{{ __('contact.form_subject') }}" required>
                        <textarea class="form-input form-textarea" name="message" rows="5" placeholder="{{ __('contact.form_message') }}" required></textarea>

                        <button type="submit" class="elx-btn elx-btn--primary" style="width: 100%; justify-content: center; padding: 1rem;">
                            {{ __('contact.form_submit') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="map-section" data-animate style="margin-top: 6rem;">
    <div class="elx-container" style="margin-bottom: 1.5rem;">
        <div style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center;">
            <select id="mapCountry" class="form-input" style="width: auto; min-width: 220px; margin-bottom: 0;">
                @foreach($locations as $countryCode => $country)
                    <option value="{{ $countryCode }}">{{ app()->getLocale() === 'ar' ? $country['label_ar'] : $country['label_en'] }}</option>
                @endforeach
            </select>
            <select id="mapBranch" class="form-input" style="width: auto; min-width: 220px; margin-bottom: 0;"></select>
        </div>
    </div>
    <iframe id="locationMap"
        src=""
        width="100%" height="450" style="border:0; filter: invert(90%) hue-rotate(180deg) brightness(0.9);" allowfullscreen="" loading="lazy"></iframe>
</section>
@endsection

@section('scripts')
<script>
    const locationData = @json($locations);
    const locale = @json(app()->getLocale());
    const countrySelect = document.getElementById('mapCountry');
    const branchSelect = document.getElementById('mapBranch');
    const mapFrame = document.getElementById('locationMap');

    function branchLabel(branch) {
        return locale === 'ar' ? branch.label_ar : branch.label_en;
    }

    function populateBranches(countryCode) {
        branchSelect.innerHTML = '';
        const branches = locationData[countryCode]?.branches || {};
        Object.entries(branches).forEach(([key, branch]) => {
            const option = document.createElement('option');
            option.value = key;
            option.textContent = branchLabel(branch);
            branchSelect.appendChild(option);
        });
    }

    function updateMap() {
        const countryCode = countrySelect.value;
        const branchKey = branchSelect.value;
        const branch = locationData[countryCode]?.branches?.[branchKey];
        if (branch?.embed_url) {
            mapFrame.src = branch.embed_url;
        }
    }

    countrySelect.addEventListener('change', () => {
        populateBranches(countrySelect.value);
        updateMap();
    });

    branchSelect.addEventListener('change', updateMap);

    populateBranches(countrySelect.value);
    updateMap();
</script>
@endsection
