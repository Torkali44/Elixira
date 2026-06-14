@extends('layouts.framer')

@section('title', __('vendor.onboarding.page_title'))

@section('head')
<style>
    .vendor-shell {
        max-width: 800px;
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
<div class="page-content" x-data="vendorStepper()">
    <div class="elx-container">
        <div class="vendor-shell">
            <div style="text-align: center; margin-bottom: 2.5rem;" data-animate>
                <h1 class="elx-hero__title">
                    <span class="elx-hero__title-gradient">{{ __('vendor.onboarding.title') }}</span>
                </h1>
                <p style="color: var(--elx-light); max-width: 500px; margin: 0 auto;">{{ __('vendor.onboarding.subtitle') }}</p>
            </div>

            @if(session('status'))
                <div class="vendor-success" data-animate>
                    {{ session('status') }}
                </div>
            @endif

            <div class="vendor-success" data-animate style="margin-bottom: 1.5rem;">
                <i class="fas fa-gift" style="margin-right: 0.5rem;"></i>
                @if($subscription['requires_payment'])
                    {{ __('vendor.onboarding.subscription_paid_notice', ['amount' => $subscription['amount'], 'currency' => $subscription['currency']]) }}
                @else
                    {{ __('vendor.onboarding.subscription_free_notice', ['remaining' => $subscription['remaining_free_slots'], 'total' => $subscription['free_slots']]) }}
                @endif
            </div>

            @if($errors->any())
                <div class="vendor-error" style="margin-bottom: 1.5rem; padding: 1rem; border-radius: 16px; background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.2);">
                    Please check the form below for errors.
                </div>
            @endif

            @if($vendorProfile->status === 'rejected_with_notes')
                <div class="vendor-error" style="margin-bottom: 1.5rem; padding: 1rem; border-radius: 16px; background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.2);">
                    <strong><i class="fas fa-exclamation-triangle"></i> Application Returned for Revision</strong>
                    <p style="margin-top: 0.5rem; margin-bottom: 0;">{{ $vendorProfile->rejection_reason }}</p>
                </div>
            @endif

            <div class="vendor-card" data-animate>
                <div class="stepper">
                    <div class="step" :class="{ 'active': step === 1, 'completed': step > 1 }" @click="if (step > 1) step = 1">
                        <div class="step-circle"><i class="fas fa-store" x-show="step <= 1"></i><i class="fas fa-check" x-show="step > 1" style="display: none;"></i></div>
                        <span class="step-label">Brand Info</span>
                    </div>
                    <div class="step" :class="{ 'active': step === 2, 'completed': step > 2 }" @click="if (step > 2) step = 2">
                        <div class="step-circle"><i class="fas fa-link" x-show="step <= 2"></i><i class="fas fa-check" x-show="step > 2" style="display: none;"></i></div>
                        <span class="step-label">Links</span>
                    </div>
                    <div class="step" :class="{ 'active': step === 3, 'completed': step > 3 }" @click="if (step > 3) step = 3">
                        <div class="step-circle"><i class="fas fa-box-open" x-show="step <= 3"></i><i class="fas fa-check" x-show="step > 3" style="display: none;"></i></div>
                        <span class="step-label">Products</span>
                    </div>
                    <div class="step" :class="{ 'active': step === 4, 'completed': step > 4 }">
                        <div class="step-circle"><i class="fas fa-id-card"></i></div>
                        <span class="step-label">Verification</span>
                    </div>
                </div>

                <form action="{{ route('vendor.store') }}" method="POST" enctype="multipart/form-data" id="vendorForm">
                    @csrf
                    <input type="hidden" name="onboarding_step" :value="step">
                    <!-- Step 1: Brand Info -->
                    <div class="vendor-form-section" :class="{ 'active': step === 1 }">
                        <div class="vendor-input-group">
                            <label class="vendor-label">Brand Name *</label>
                            <input type="text" name="brand_name" class="vendor-input" value="{{ old('brand_name', $vendorProfile->brand_name) }}">
                            @error('brand_name')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group">
                            <label class="vendor-label">Brand Logo</label>
                            <div class="vendor-file-upload" onclick="document.getElementById('brand_logo').click()">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: var(--elx-cyan); margin-bottom: 1rem;"></i>
                                <div style="color: var(--elx-white); font-weight: 500;">Click to upload logo</div>
                                <div style="color: var(--elx-light); font-size: 0.8rem; margin-top: 0.5rem;">PNG, JPG up to 2MB</div>
                                <input type="file" id="brand_logo" name="brand_logo" style="display: none;" accept="image/*" onchange="document.getElementById('logo_name').textContent = this.files[0]?.name || ''">
                                <div id="logo_name" style="margin-top: 1rem; color: var(--elx-cyan); font-size: 0.85rem;"></div>
                                @if($vendorProfile->brand_logo)
                                    <div style="margin-top: 0.5rem; color: var(--elx-light); font-size: 0.85rem;">Current: <img src="{{ asset('storage/' . $vendorProfile->brand_logo) }}" height="30" style="vertical-align: middle; border-radius: 6px;"></div>
                                @endif
                            </div>
                            @error('brand_logo')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group">
                            <label class="vendor-label">Short Description *</label>
                            <textarea name="brand_description" class="vendor-textarea" rows="4" placeholder="Tell us about your brand...">{{ old('brand_description', $vendorProfile->brand_description) }}</textarea>
                            @error('brand_description')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group">
                            <label class="vendor-label">Service Countries *</label>
                            @php $countries = old('service_countries', $vendorProfile->service_countries ?? []); @endphp
                            <div class="vendor-checkbox-group">
                                <label class="vendor-checkbox">
                                    <input type="checkbox" name="service_countries[]" value="UAE" @checked(in_array('UAE', $countries))>
                                    <span>United Arab Emirates</span>
                                </label>
                                <label class="vendor-checkbox">
                                    <input type="checkbox" name="service_countries[]" value="KSA" @checked(in_array('KSA', $countries))>
                                    <span>Saudi Arabia</span>
                                </label>
                            </div>
                            @error('service_countries')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Step 2: Social Links -->
                    <div class="vendor-form-section" :class="{ 'active': step === 2 }">
                        <div class="vendor-input-group">
                            <label class="vendor-label">Instagram Link</label>
                            <input type="url" name="instagram_link" class="vendor-input" value="{{ old('instagram_link', $vendorProfile->instagram_link) }}" placeholder="https://instagram.com/yourbrand">
                            @error('instagram_link')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group">
                            <label class="vendor-label">TikTok Link</label>
                            <input type="url" name="tiktok_link" class="vendor-input" value="{{ old('tiktok_link', $vendorProfile->tiktok_link) }}" placeholder="https://tiktok.com/@yourbrand">
                            @error('tiktok_link')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group">
                            <label class="vendor-label">Snapchat Link</label>
                            <input type="url" name="snapchat_link" class="vendor-input" value="{{ old('snapchat_link', $vendorProfile->snapchat_link) }}" placeholder="https://snapchat.com/add/yourbrand">
                            @error('snapchat_link')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group" style="margin-top: 2rem;">
                            <label class="vendor-label">External Store Link (Optional)</label>
                            <input type="url" name="store_link" class="vendor-input" value="{{ old('store_link', $vendorProfile->store_link) }}" placeholder="https://yourstore.com">
                            @error('store_link')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group">
                            <label class="vendor-label">Store Link Description</label>
                            <textarea name="store_link_description" class="vendor-textarea" rows="2" placeholder="Briefly describe what this link is for...">{{ old('store_link_description', $vendorProfile->store_link_description) }}</textarea>
                            @error('store_link_description')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Step 3: Products -->
                    <div class="vendor-form-section" :class="{ 'active': step === 3 }">
                        <div class="vendor-input-group">
                            <label class="vendor-label">Allowed Product Types *</label>
                            @php $types = old('product_types', $vendorProfile->product_types ?? []); @endphp
                            <div class="vendor-checkbox-group">
                                <label class="vendor-checkbox">
                                    <input type="checkbox" name="product_types[]" value="Spices & Herbs" @checked(in_array('Spices & Herbs', $types))>
                                    <span>Spices & Herbs </span>
                                </label>
                                <label class="vendor-checkbox">
                                    <input type="checkbox" name="product_types[]" value="Raw Materials Supplier" @checked(in_array('Raw Materials Supplier', $types))>
                                    <span>Raw Materials Supplier</span>
                                </label>
                                <label class="vendor-checkbox">
                                    <input type="checkbox" name="product_types[]" value="Natural Mixes Maker" @checked(in_array('Natural Mixes Maker', $types))>
                                    <span>Natural Mixes Maker</span>
                                </label>
                                <label class="vendor-checkbox">
                                    <input type="checkbox" name="product_types[]" value="Perfumes & Incense" @checked(in_array('Perfumes & Incense', $types))>
                                    <span>Perfumes & Incense </span>
                                </label>
                            </div>
                            @error('product_types')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="vendor-input-group" style="margin-top: 2rem;">
                            <label class="vendor-label">Payment Method</label>
                            <div style="padding: 1rem 1.2rem; border-radius: 16px; background: rgba(74, 200, 246, 0.05); border: 1px solid rgba(74, 200, 246, 0.2); display: flex; align-items: center; gap: 1rem;">
                                <i class="fas fa-money-bill-wave" style="color: var(--elx-cyan); font-size: 1.5rem;"></i>
                                <div>
                                    <div style="color: var(--elx-white); font-weight: 500;">Cash on Delivery (COD)</div>
                                    <div style="color: var(--elx-light); font-size: 0.85rem;">Currently, all vendor payouts are processed via Cash on Delivery.</div>
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
                                        <i class="fas fa-check-circle text-success"></i> Document currently uploaded
                                    </div>
                                @endif
                            </div>
                            @error('verification_document')<div class="vendor-error">{{ $message }}</div>@enderror
                        </div>

                        @if($subscription['requires_payment'])
                            <div class="vendor-input-group" style="margin-top: 2rem;">
                                <label class="vendor-label">{{ __('vendor.onboarding.subscription_payment') }}</label>
                                <div style="padding: 1rem 1.2rem; border-radius: 16px; background: rgba(255, 193, 7, 0.08); border: 1px solid rgba(255, 193, 7, 0.25); margin-bottom: 1rem; color: var(--elx-light); font-size: 0.9rem;">
                                    {{ __('vendor.onboarding.subscription_bank_info', [
                                        'amount' => $subscription['amount'],
                                        'currency' => $subscription['currency'],
                                        'bank' => $subscription['bank_name'],
                                        'account' => $subscription['bank_account_number'],
                                        'holder' => $subscription['bank_account_holder'],
                                    ]) }}
                                </div>
                                <div class="vendor-file-upload" onclick="document.getElementById('subscription_payment_receipt').click()">
                                    <i class="fas fa-receipt" style="font-size: 2rem; color: var(--elx-cyan); margin-bottom: 1rem;"></i>
                                    <div style="color: var(--elx-white); font-weight: 500;">{{ __('vendor.onboarding.upload_receipt') }}</div>
                                    <input type="file" id="subscription_payment_receipt" name="subscription_payment_receipt" style="display: none;" accept=".pdf,image/*" onchange="document.getElementById('receipt_name').textContent = this.files[0]?.name || ''">
                                    <div id="receipt_name" style="margin-top: 1rem; color: var(--elx-cyan); font-size: 0.85rem;"></div>
                                    @if($vendorProfile->subscription_payment_receipt)
                                        <div style="margin-top: 0.5rem; color: var(--elx-light); font-size: 0.85rem;">
                                            <i class="fas fa-check-circle text-success"></i> {{ __('vendor.onboarding.receipt_uploaded') }}
                                        </div>
                                    @endif
                                </div>
                                @error('subscription_payment_receipt')<div class="vendor-error">{{ $message }}</div>@enderror
                            </div>
                        @endif

                        <div class="vendor-input-group" style="margin-top: 2rem;">
                            <label class="vendor-checkbox">
                                <input type="checkbox" name="terms" value="1" @checked(old('terms'))>
                                <span>I agree to the <a href="{{ route('vendor.terms') }}" target="_blank" style="color: var(--elx-cyan); text-decoration: underline;">Vendor Terms and Conditions</a>.</span>
                            </label>
                        </div>
                        
                        <div class="vendor-input-group">
                            <div style="padding: 1rem; border-radius: 12px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); font-size: 0.85rem; color: var(--elx-light);">
                                <i class="fas fa-info-circle" style="color: var(--elx-cyan); margin-right: 0.5rem;"></i>
                                <strong>Note about saving:</strong> You can click "Save Draft" to keep your progress and return later. Your application will be in "Draft" status. When you are ready, click "Submit Application" and your status will change to "Pending" for review.
                            </div>
                        </div>
                    </div>

                    <div class="vendor-actions">
                        <button type="button" class="elx-btn elx-btn--glass" x-show="step > 1" @click="step--">Back</button>
                        <div x-show="step === 1" style="width: 80px;"></div> <!-- Spacer -->

                        <div style="display: flex; gap: 1rem;">
                            <button type="submit" name="action" value="draft" class="elx-btn elx-btn--glass">Save Draft</button>
                            <button type="button" class="elx-btn elx-btn--primary" x-show="step < 4" @click="nextStep()">Next Step</button>
                            <button type="submit" name="action" value="submit" class="elx-btn elx-btn--primary" x-show="step === 4">Submit Application</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function vendorStepper() {
        return {
            step: {{ (int) old('onboarding_step', request('step', $vendorProfile->onboarding_step ?? 1)) }},
            nextStep() {
                this.step++;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('vendorForm');
        const storageKey = 'elixira_vendor_onboarding_draft';
        const saved = localStorage.getItem(storageKey);

        if (saved && !@json($errors->any())) {
            try {
                const data = JSON.parse(saved);
                Object.entries(data).forEach(([name, value]) => {
                    const field = form.querySelector(`[name="${name}"]`);
                    if (field && field.type !== 'file' && field.type !== 'checkbox') {
                        field.value = value;
                    }
                    if (field && field.type === 'checkbox' && Array.isArray(value)) {
                        form.querySelectorAll(`[name="${name}"]`).forEach((checkbox) => {
                            checkbox.checked = value.includes(checkbox.value);
                        });
                    }
                });
            } catch (e) {}
        }

        form?.addEventListener('input', () => {
            const payload = {};
            Array.from(form.elements).forEach((element) => {
                if (!element.name || element.type === 'file' || element.type === 'submit' || element.type === 'button') {
                    return;
                }
                if (element.type === 'checkbox') {
                    payload[element.name] = payload[element.name] || [];
                    if (element.checked) {
                        payload[element.name].push(element.value);
                    }
                    return;
                }
                payload[element.name] = element.value;
            });
            localStorage.setItem(storageKey, JSON.stringify(payload));
        });

        form?.addEventListener('submit', (event) => {
            if (event.submitter?.value === 'submit') {
                localStorage.removeItem(storageKey);
            }
        });
    });
</script>
@endsection
