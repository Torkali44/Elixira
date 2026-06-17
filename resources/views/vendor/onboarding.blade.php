@extends('layouts.framer')

@section('title', __('vendor.onboarding.page_title'))

@section('head')
<style>
    .vendor-shell {
        max-width: 1100px;
        margin: 0 auto;
    }

    .vendor-card {
        background: linear-gradient(180deg, rgba(19, 37, 45, 0.96), rgba(10, 26, 34, 0.96));
        border: 1px solid var(--elx-border);
        border-radius: 28px;
        padding: 2.5rem;
        box-shadow: 0 24px 70px rgba(0, 0, 0, 0.25);
    }

    .stepper {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2.5rem;
        position: relative;
    }

    .stepper::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 0;
        right: 0;
        height: 2px;
        background: rgba(255, 255, 255, 0.1);
        z-index: 0;
    }

    .step {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: var(--elx-transition);
    }

    .step-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #13252d;
        border: 2px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: var(--elx-light);
        transition: var(--elx-transition);
    }

    .step.active .step-circle {
        background: rgba(74, 200, 246, 0.15);
        border-color: var(--elx-cyan);
        color: var(--elx-cyan);
        box-shadow: 0 0 20px rgba(74, 200, 246, 0.2);
    }

    .step.completed .step-circle {
        background: var(--elx-cyan);
        border-color: var(--elx-cyan);
        color: #000;
    }

    .step-label {
        font-size: 0.85rem;
        color: var(--elx-light);
        font-weight: 500;
    }

    .step.active .step-label {
        color: var(--elx-white);
    }

    .vendor-form-section {
        display: none;
        animation: fadeIn 0.4s ease forwards;
    }

    .vendor-form-section.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .vendor-input-group {
        margin-bottom: 1.5rem;
    }

    .vendor-label {
        display: block;
        color: var(--elx-light);
        font-size: 0.85rem;
        letter-spacing: 0.05em;
        margin-bottom: 0.6rem;
    }

    .vendor-input, .vendor-textarea, .vendor-select {
        width: 100%;
        padding: 1rem 1.2rem;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.09);
        background: rgba(255, 255, 255, 0.04);
        color: var(--elx-white);
        outline: none;
        transition: var(--elx-transition);
    }

    .vendor-input:focus, .vendor-textarea:focus, .vendor-select:focus {
        border-color: var(--elx-cyan);
        box-shadow: 0 0 0 3px rgba(74, 200, 246, 0.12);
    }

    .vendor-select option {
        background: #13252d;
        color: #fff;
    }

    .vendor-file-upload {
        border: 2px dashed rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: var(--elx-transition);
        background: rgba(255, 255, 255, 0.02);
    }

    .vendor-file-upload:hover {
        border-color: var(--elx-cyan);
        background: rgba(74, 200, 246, 0.05);
    }

    .vendor-checkbox-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .vendor-checkbox {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        border-radius: 14px;
        border: 1px solid rgba(255, 255, 255, 0.09);
        background: rgba(255, 255, 255, 0.02);
        cursor: pointer;
        transition: var(--elx-transition);
    }

    .vendor-checkbox:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .vendor-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--elx-cyan);
    }

    .vendor-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .vendor-error {
        color: #ff9b9b;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .vendor-success {
        margin-bottom: 1.5rem;
        padding: 1rem 1.2rem;
        border-radius: 18px;
        border: 1px solid rgba(74, 200, 246, 0.26);
        background: rgba(74, 200, 246, 0.1);
        color: var(--elx-cyan);
    }

</style>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection

@section('content')
<div class="page-content" x-data="vendorStepper()" x-init="init()">
    <div class="elx-container">
        <div class="vendor-shell">
            <div style="text-align: center; margin-bottom: 2.5rem;" data-animate>
                <h1 class="elx-hero__title">
                    <span class="elx-hero__title-gradient">{{ __('vendor.onboarding.title') }}</span>
                </h1>
                <p style="color: var(--elx-light); max-width: 500px; margin: 0 auto;">{{ __('vendor.onboarding.subtitle') }}</p>
            </div>

            @if(session('success'))
                <div class="elx-container" style="margin-bottom: 1.5rem;">
                    <div style="background: rgba(74, 200, 246, 0.1); border: 1px solid #4ac8f6; color: #4ac8f6; padding: 1rem 2rem; border-radius: 12px; text-align: center;">
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="elx-container" style="margin-bottom: 1.5rem;">
                    <div style="background: rgba(220, 53, 69, 0.1); border: 1px solid #dc3545; color: #ff6b6b; padding: 1rem 2rem; border-radius: 12px; text-align: center;">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if(session('status'))
                <div class="vendor-success" data-animate>
                    {{ session('status') }}
                </div>
            @endif

            <div class="vendor-success" data-animate style="margin-bottom: 1.5rem;">
                <i class="fas fa-gift" style="margin-right: 0.5rem;"></i>
                @if($subscription['requires_payment'])
                    {{ __('vendor.onboarding.subscription_paid_notice') }}
                @else
                    {{ __('vendor.onboarding.subscription_free_notice', ['remaining' => $subscription['remaining_free_slots'], 'total' => $subscription['free_slots']]) }}
                @endif
            </div>

            @if($errors->any())
                <div class="vendor-error" style="margin-bottom: 1.5rem; padding: 1rem; border-radius: 16px; background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.2);">
                    <strong>{{ __('vendor.onboarding.validation_title') }}</strong>
                    <ul style="margin: 0.5rem 0 0; padding-inline-start: 1.2rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($vendorProfile->status === 'rejected_with_notes')
                <div class="vendor-error" style="margin-bottom: 1.5rem; padding: 1rem; border-radius: 16px; background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.2);">
                    <strong><i class="fas fa-exclamation-triangle"></i> {{ __('vendor.onboarding.returned_revision') }}</strong>
                    <p style="margin-top: 0.5rem; margin-bottom: 0;">{{ $vendorProfile->rejection_reason }}</p>
                </div>
            @endif

            <div class="vendor-card" data-animate>
                <div class="stepper">
                    <div class="step" :class="{ 'active': step === 1, 'completed': step > 1 }" @click="if (step > 1) step = 1">
                        <div class="step-circle"><i class="fas fa-store" x-show="step <= 1"></i><i class="fas fa-check" x-show="step > 1" style="display: none;"></i></div>
                        <span class="step-label">{{ __('vendor.onboarding.step_brand') }}</span>
                    </div>
                    <div class="step" :class="{ 'active': step === 2, 'completed': step > 2 }" @click="if (step > 2) step = 2">
                        <div class="step-circle"><i class="fas fa-link" x-show="step <= 2"></i><i class="fas fa-check" x-show="step > 2" style="display: none;"></i></div>
                        <span class="step-label">{{ __('vendor.onboarding.step_links') }}</span>
                    </div>
                    <div class="step" :class="{ 'active': step === 3, 'completed': step > 3 }" @click="if (step > 3) step = 3">
                        <div class="step-circle"><i class="fas fa-box-open" x-show="step <= 3"></i><i class="fas fa-check" x-show="step > 3" style="display: none;"></i></div>
                        <span class="step-label">{{ __('vendor.onboarding.step_products') }}</span>
                    </div>
                    <div class="step" :class="{ 'active': step === 4, 'completed': step > 4 }">
                        <div class="step-circle"><i class="fas fa-id-card"></i></div>
                        <span class="step-label">{{ __('vendor.onboarding.step_verification') }}</span>
                    </div>
                </div>

                <form action="{{ route('vendor.store') }}" method="POST" enctype="multipart/form-data" id="vendorForm">
                    @csrf
                    <input type="hidden" name="onboarding_step" :value="step">
                    <!-- Step 1: Subscription (if paid) + Brand Info -->
                    <div class="vendor-form-section" :class="{ 'active': step === 1 }">
                        @include('vendor.partials.subscription-plans', ['subscription' => $subscription, 'vendorProfile' => $vendorProfile, 'hasReceipt' => $hasReceipt])

                        <div class="vendor-input-group">
                            <label class="vendor-label">{{ __('vendor.onboarding.brand_name') }} *</label>
                            <input type="text" name="brand_name" class="vendor-input" value="{{ old('brand_name', $vendorProfile->brand_name) }}">
                            @error('brand_name')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group">
                            <label class="vendor-label">{{ __('vendor.onboarding.brand_logo') }}</label>
                            <div class="vendor-file-upload" onclick="document.getElementById('brand_logo').click()">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: var(--elx-cyan); margin-bottom: 1rem;"></i>
                                <div style="color: var(--elx-white); font-weight: 500;">{{ __('vendor.onboarding.upload_logo') }}</div>
                                <div style="color: var(--elx-light); font-size: 0.8rem; margin-top: 0.5rem;">{{ __('vendor.onboarding.upload_logo_hint') }}</div>
                                <input type="file" id="brand_logo" name="brand_logo" style="display: none;" accept="image/*" onchange="document.getElementById('logo_name').textContent = this.files[0]?.name || ''">
                                <div id="logo_name" style="margin-top: 1rem; color: var(--elx-cyan); font-size: 0.85rem;"></div>
                                @if($vendorProfile->brand_logo)
                                    <div style="margin-top: 0.5rem; color: var(--elx-light); font-size: 0.85rem;">Current: <img src="{{ asset('storage/' . $vendorProfile->brand_logo) }}" height="30" style="vertical-align: middle; border-radius: 6px;"></div>
                                @endif
                            </div>
                            @error('brand_logo')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group">
                            <label class="vendor-label">{{ __('vendor.onboarding.short_description') }} *</label>
                            <textarea name="brand_description" class="vendor-textarea" rows="4" placeholder="{{ __('vendor.onboarding.short_description_placeholder') }}">{{ old('brand_description', $vendorProfile->brand_description) }}</textarea>
                            @error('brand_description')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        @php
                            $phoneCountry = old('phone_country_code', str_starts_with((string) auth()->user()->phone, '+971') ? '+971' : '+966');
                            $phoneNumber = old('phone', preg_replace('/^\+966|^\+971/', '', (string) auth()->user()->phone));
                            $ksaFlag = app(\App\Support\ItemPricingService::class)->countryFlag('KSA');
                            $uaeFlag = app(\App\Support\ItemPricingService::class)->countryFlag('UAE');
                        @endphp
                        <div class="vendor-input-group">
                            <label class="vendor-label">{{ __('vendor.onboarding.phone') }} *</label>
                            <div style="display: flex; gap: 0.75rem; align-items: stretch;">
                                <select name="phone_country_code" class="vendor-select" style="width: 140px;">
                                    <option value="+966" @selected($phoneCountry === '+966')>  +966 </option>
                                    <option value="+971" @selected($phoneCountry === '+971')> <x-country-flag country="UAE" :size="22" :show-label="false" /> +971 </option>
                                </select>
                                <input type="text" name="phone" class="vendor-input" value="{{ $phoneNumber }}" placeholder="{{ __('vendor.onboarding.phone_placeholder') }}">
                            </div>
                            @error('phone')<div class="vendor-error">{{ $message }}</div>@enderror
                            @error('phone_country_code')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group">
                            <label class="vendor-label">{{ __('vendor.onboarding.service_countries') }} *</label>
                            @php $countries = old('service_countries', $vendorProfile->service_countries ?? []); @endphp
                            <div class="vendor-checkbox-group">
                                <label class="vendor-checkbox">
                                    <input type="checkbox" name="service_countries[]" value="UAE" @checked(in_array('UAE', $countries))>
                                    <x-country-flag country="UAE" :size="22" :show-label="false" />
                                </label>
                                <label class="vendor-checkbox">
                                    <input type="checkbox" name="service_countries[]" value="KSA" @checked(in_array('KSA', $countries))>
                                    <x-country-flag country="KSA" :size="22" :show-label="false" />
                                </label>
                            </div>
                            @error('service_countries')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Step 2: Social Links -->
                    <div class="vendor-form-section" :class="{ 'active': step === 2 }">
                        <div class="vendor-input-group">
                            <label class="vendor-label">{{ __('vendor.onboarding.instagram') }}</label>
                            <input type="url" name="instagram_link" class="vendor-input" value="{{ old('instagram_link', $vendorProfile->instagram_link) }}" placeholder="https://instagram.com/yourbrand">
                            @error('instagram_link')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group">
                            <label class="vendor-label">{{ __('vendor.onboarding.tiktok') }}</label>
                            <input type="url" name="tiktok_link" class="vendor-input" value="{{ old('tiktok_link', $vendorProfile->tiktok_link) }}" placeholder="https://tiktok.com/@yourbrand">
                            @error('tiktok_link')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group">
                            <label class="vendor-label">{{ __('vendor.onboarding.snapchat') }}</label>
                            <input type="url" name="snapchat_link" class="vendor-input" value="{{ old('snapchat_link', $vendorProfile->snapchat_link) }}" placeholder="https://snapchat.com/add/yourbrand">
                            @error('snapchat_link')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group" style="margin-top: 2rem;">
                            <label class="vendor-label">{{ __('vendor.onboarding.external_store') }}</label>
                            <input type="url" name="store_link" class="vendor-input" value="{{ old('store_link', $vendorProfile->store_link) }}" placeholder="https://yourstore.com">
                            @error('store_link')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group">
                            <label class="vendor-label">{{ __('vendor.onboarding.store_description') }}</label>
                            <textarea name="store_link_description" class="vendor-textarea" rows="2" placeholder="Briefly describe what this link is for...">{{ old('store_link_description', $vendorProfile->store_link_description) }}</textarea>
                            @error('store_link_description')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Step 3: Products -->
                    <div class="vendor-form-section" :class="{ 'active': step === 3 }">
                        <div class="vendor-input-group">
                            <label class="vendor-label">{{ __('vendor.onboarding.product_types') }} *</label>
                            @php $types = old('product_types', $vendorProfile->product_types ?? []); @endphp
                            <div class="vendor-checkbox-group">
                                <label class="vendor-checkbox">
                                    <input type="checkbox" name="product_types[]" value="Spices & Herbs" @checked(in_array('Spices & Herbs', $types))>
                                    <span>{{ __('vendor.onboarding.type_spices') }}</span>
                                </label>
                                <label class="vendor-checkbox">
                                    <input type="checkbox" name="product_types[]" value="Raw Materials Supplier" @checked(in_array('Raw Materials Supplier', $types))>
                                    <span>{{ __('vendor.onboarding.type_raw') }}</span>
                                </label>
                                <label class="vendor-checkbox">
                                    <input type="checkbox" name="product_types[]" value="Natural Mixes Maker" @checked(in_array('Natural Mixes Maker', $types))>
                                    <span>{{ __('vendor.onboarding.type_mixes') }}</span>
                                </label>
                                <label class="vendor-checkbox">
                                    <input type="checkbox" name="product_types[]" value="Perfumes & Incense" @checked(in_array('Perfumes & Incense', $types))>
                                    <span>{{ __('vendor.onboarding.type_perfumes') }}</span>
                                </label>
                            </div>
                            @error('product_types')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group" style="margin-top: 2rem;">
                            <label class="vendor-label">{{ __('vendor.onboarding.payment_method') }}</label>
                            <div style="padding: 1rem 1.2rem; border-radius: 16px; background: rgba(74, 200, 246, 0.05); border: 1px solid rgba(74, 200, 246, 0.2); display: flex; align-items: center; gap: 1rem;">
                                <i class="fas fa-money-bill-wave" style="color: var(--elx-cyan); font-size: 1.5rem;"></i>
                                <div>
                                    <div style="color: var(--elx-white); font-weight: 500;">{{ __('vendor.onboarding.payment_cod') }}</div>
                                    <div style="color: var(--elx-light); font-size: 0.85rem;">{{ __('vendor.onboarding.payment_cod_hint') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Verification & Submit -->
                    <div class="vendor-form-section" :class="{ 'active': step === 4 }">
                        <div class="vendor-input-group">
                            <label class="vendor-label">{{ __('vendor.onboarding.commercial_registration') }} *</label>
                            <input type="text" name="commercial_registration_number" class="vendor-input" value="{{ old('commercial_registration_number', $vendorProfile->commercial_registration_number) }}" placeholder="{{ __('vendor.onboarding.commercial_registration_placeholder') }}">
                            @error('commercial_registration_number')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group">
                            <label class="vendor-label">{{ __('vendor.onboarding.verification_document') }}</label>
                            <div style="color: var(--elx-light); font-size: 0.85rem; margin-bottom: 1rem;">{{ __('vendor.onboarding.verification_document_hint') }}</div>
                            
                            <div class="vendor-file-upload" onclick="document.getElementById('verification_document').click()">
                                <i class="fas fa-file-contract" style="font-size: 2rem; color: var(--elx-cyan); margin-bottom: 1rem;"></i>
                                <div style="color: var(--elx-white); font-weight: 500;">Click to upload document</div>
                                <div style="color: var(--elx-light); font-size: 0.8rem; margin-top: 0.5rem;">PDF, PNG, JPG up to 4MB</div>
                                <input type="file" id="verification_document" name="verification_document" style="display: none;" accept=".pdf,image/*" onchange="document.getElementById('doc_name').textContent = this.files[0]?.name || ''">
                                <div id="doc_name" style="margin-top: 1rem; color: var(--elx-cyan); font-size: 0.85rem;"></div>
                                @if($vendorProfile->verification_document)
                                    <div style="margin-top: 0.5rem; color: var(--elx-light); font-size: 0.85rem;">
                                        <i class="fas fa-check-circle text-success"></i> {{ __('vendor.onboarding.document_uploaded') }}
                                    </div>
                                @endif
                            </div>
                            @error('verification_document')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group" style="margin-top: 2rem;">
                            <label class="vendor-checkbox">
                                <input type="checkbox" name="terms" value="1" @checked(old('terms'))>
                                <span>{{ __('vendor.onboarding.terms_agree') }} <a href="{{ route('vendor.terms') }}" target="_blank" style="color: var(--elx-cyan); text-decoration: underline;">{{ __('vendor.onboarding.terms_link') }}</a>.</span>
                            </label>
                        </div>
                        
                        <div class="vendor-input-group">
                            <div style="padding: 1rem; border-radius: 12px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); font-size: 0.85rem; color: var(--elx-light);">
                                <i class="fas fa-info-circle" style="color: var(--elx-cyan); margin-right: 0.5rem;"></i>
                                {{ __('vendor.onboarding.draft_note') }}
                            </div>
                        </div>
                    </div>

                    <div class="vendor-actions">
                        <button type="button" class="elx-btn elx-btn--glass" x-show="step > 1" @click="step--">{{ __('vendor.onboarding.back') }}</button>
                        <div x-show="step === 1" style="width: 80px;"></div>

                        <div style="display: flex; gap: 1rem;">
                            <button type="submit" name="action" value="draft" class="elx-btn elx-btn--glass">{{ __('vendor.onboarding.save_draft') }}</button>
                            <button type="button" class="elx-btn elx-btn--primary" x-show="step < 4" @click="nextStep()">{{ __('vendor.onboarding.next_step') }}</button>
                            <button type="submit" name="action" value="submit" class="elx-btn elx-btn--primary" x-show="step === 4">{{ __('vendor.onboarding.submit_application') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function vendorStepper() {
        const urlStep = new URLSearchParams(window.location.search).get('step');
        const initialStep = urlStep ? parseInt(urlStep, 10) : {{ (int) ($initialStep ?? old('onboarding_step', request('step', $vendorProfile->onboarding_step ?? 1))) }};
        const requiresPayment = @json($subscription['requires_payment']);
        const hasReceipt = @json($hasReceipt ?? filled($vendorProfile->subscription_payment_receipt));

        return {
            step: initialStep > 0 && initialStep <= 4 ? initialStep : 1,
            init() {
                this.syncUrl();
                this.$watch('step', (value) => {
                    localStorage.setItem('vendor_onboarding_step', value);
                    this.syncUrl();
                });
            },
            syncUrl() {
                const url = new URL(window.location.href);
                url.searchParams.set('step', this.step);
                window.history.replaceState({}, '', url);
            },
            validateStep(step) {
                const form = document.getElementById('vendorForm');
                const errors = [];

                const req = (selector, label) => {
                    const el = form.querySelector(selector);
                    if (!el || !String(el.value || '').trim()) {
                        errors.push(label);
                    }
                };

                if (step === 1) {
                    req('[name="brand_name"]', '{{ __('vendor.onboarding.brand_name') }}: {{ __('vendor.onboarding.field_required') }}');
                    req('[name="brand_description"]', '{{ __('vendor.onboarding.short_description') }}: {{ __('vendor.onboarding.field_required') }}');
                    req('[name="phone"]', '{{ __('vendor.onboarding.phone') }}: {{ __('vendor.onboarding.field_required') }}');
                    if (!form.querySelector('[name="service_countries[]"]:checked')) {
                        errors.push('{{ __('vendor.onboarding.select_country_required') }}');
                    }
                    if (requiresPayment) {
                        const receipt = form.querySelector('#subscription_payment_receipt');
                        if (!hasReceipt && (!receipt || !receipt.files.length)) {
                            errors.push('{{ __('vendor.onboarding.receipt_required') }}');
                        }
                    }
                }

                if (step === 3) {
                    if (!form.querySelector('[name="product_types[]"]:checked')) {
                        errors.push('{{ __('vendor.onboarding.select_product_type_required') }}');
                    }
                }

                if (step === 4) {
                    req('[name="commercial_registration_number"]', '{{ __('vendor.onboarding.commercial_registration') }}: {{ __('vendor.onboarding.field_required') }}');
                    if (!form.querySelector('[name="terms"]')?.checked) {
                        errors.push('{{ __('vendor.onboarding.terms_required') }}');
                    }
                    if (requiresPayment) {
                        const receipt = form.querySelector('#subscription_payment_receipt');
                        if (!hasReceipt && (!receipt || !receipt.files.length)) {
                            errors.push('{{ __('vendor.onboarding.receipt_required') }}');
                        }
                    }
                }

                if (errors.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: @json(__('vendor.onboarding.validation_title')),
                        html: errors.map((e) => `<div style="margin:.35rem 0;">• ${e}</div>`).join(''),
                        confirmButtonText: @json(__('app.confirm')),
                    });
                    return false;
                }

                return true;
            },
            nextStep() {
                if (this.validateStep(this.step)) {
                    this.step++;
                }
            }
        };
    }

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('vendorForm');

        @if(session('success') && !request()->routeIs('vendor.pending'))
            Swal.fire({
                icon: 'success',
                text: @json(session('success')),
                confirmButtonText: @json(__('app.confirm')),
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: @json(__('vendor.onboarding.validation_title')),
                html: `{!! collect($errors->all())->map(fn ($e) => '<div style="margin:.25rem 0;">• '.e($e).'</div>')->implode('') !!}`,
                confirmButtonText: @json(__('app.confirm')),
            });
        @endif

        form?.addEventListener('submit', (event) => {
            if (event.submitter?.value === 'submit') {
                const errors = [];
                const req = (selector, label) => {
                    const el = form.querySelector(selector);
                    if (!el || !String(el.value || '').trim()) errors.push(label);
                };
                req('[name="brand_name"]', '{{ __('vendor.onboarding.brand_name') }}: {{ __('vendor.onboarding.field_required') }}');
                req('[name="brand_description"]', '{{ __('vendor.onboarding.short_description') }}: {{ __('vendor.onboarding.field_required') }}');
                req('[name="phone"]', '{{ __('vendor.onboarding.phone') }}: {{ __('vendor.onboarding.field_required') }}');
                req('[name="commercial_registration_number"]', '{{ __('vendor.onboarding.commercial_registration') }}: {{ __('vendor.onboarding.field_required') }}');
                if (!form.querySelector('[name="service_countries[]"]:checked')) errors.push('{{ __('vendor.onboarding.select_country_required') }}');
                if (!form.querySelector('[name="product_types[]"]:checked')) errors.push('{{ __('vendor.onboarding.select_product_type_required') }}');
                if (!form.querySelector('[name="terms"]')?.checked) errors.push('{{ __('vendor.onboarding.terms_required') }}');
                @if($subscription['requires_payment'])
                const receipt = form.querySelector('#subscription_payment_receipt');
                if (!@json($hasReceipt ?? filled($vendorProfile->subscription_payment_receipt)) && (!receipt || !receipt.files.length)) {
                    errors.push('{{ __('vendor.onboarding.receipt_required') }}');
                }
                @endif
                if (errors.length) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: @json(__('vendor.onboarding.validation_title')),
                        html: errors.map((e) => `<div style="margin:.35rem 0;">• ${e}</div>`).join(''),
                        confirmButtonText: @json(__('app.confirm')),
                    });
                    return;
                }
                localStorage.removeItem('vendor_onboarding_step');
            }
        });
    });
</script>
@endsection
