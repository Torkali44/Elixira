@if($subscription['requires_payment'])
@php
    $maxFeatureCount = max(array_map(fn (array $plan) => count($plan['features']), $subscription['plans']));
@endphp
<style>
    .vendor-plans-wrap {
        margin-bottom: 2rem;
        padding: 1.5rem;
        border-radius: 24px;
        background: linear-gradient(145deg, #edf4fb 0%, #dceaf6 48%, #cfe2f2 100%);
        border: 1px solid rgba(37, 99, 235, 0.14);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.75);
    }
    .vendor-plans-wrap .vendor-label {
        color: #0f2f4d;
        font-weight: 700;
    }
    .vendor-plans {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        align-items: stretch;
    }
    .vendor-plan-card {
        border-radius: 42px 14px 42px 14px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.28);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        height: 100%;
        border: 3px solid transparent;
    }
    .vendor-plan-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 55px rgba(0, 0, 0, 0.34);
    }
    .vendor-plan-card.selected {
        border-color: var(--elx-cyan);
        box-shadow: 0 0 0 1px var(--elx-cyan), 0 24px 55px rgba(74, 200, 246, 0.22);
        transform: translateY(-8px);
    }
    .vendor-plan-card__head {
        padding: 1.75rem 1.25rem 2.25rem;
        text-align: center;
        color: #fff;
        font-weight: 800;
        font-size: 1.45rem;
        letter-spacing: 0.04em;
        border-radius: 42px 14px 42px 14px;
        position: relative;
        flex-shrink: 0;
    }
    .vendor-plan-card__head::after {
        content: '';
        position: absolute;
        right: 0;
        bottom: -1px;
        width: 72px;
        height: 36px;
        background: #fff;
        border-top-left-radius: 72px;
    }
    .vendor-plan-card__body {
        display: flex;
        flex-direction: column;
        flex: 1;
        padding: 1.35rem 1.35rem 1.5rem;
        background: #fff;
        margin-top: -0.5rem;
    }
    .vendor-plan-card__price-block {
        text-align: center;
        margin-bottom: 1.25rem;
        flex-shrink: 0;
    }
    .vendor-plan-card__price {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.1;
        color: var(--plan-color, #2563a8);
    }
    .vendor-plan-card__original {
        display: block;
        margin-top: 0.35rem;
        font-size: 0.82rem;
        color: #9aa5b1;
        text-decoration: line-through;
    }
    .vendor-plan-card__period {
        margin-top: 0.35rem;
        font-size: 0.88rem;
        color: #6b7c8f;
        font-weight: 600;
    }
    .vendor-plan-card__features {
        list-style: none;
        margin: 0 0 1.25rem;
        padding: 0;
        flex: 1;
    }
    .vendor-plan-card__feature {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        padding: 0.55rem 0;
        font-size: 0.86rem;
        color: #1e293b !important;
        border-bottom: 1px solid #e2e8f0;
    }
    .vendor-plan-card__feature span {
        color: #1e293b !important;
        flex: 1;
        line-height: 1.45;
    }
    .vendor-plan-card__feature:last-child { border-bottom: none; }
    .vendor-plan-card__feature i {
        color: var(--plan-color, #2563a8);
        margin-top: 0.15rem;
        font-size: 0.8rem;
        flex-shrink: 0;
    }
    .vendor-plan-card__feature--spacer {
        visibility: hidden;
        border-bottom-color: transparent;
    }
    .vendor-plan-card__cta-wrap {
        margin-top: auto;
        flex-shrink: 0;
    }
    .vendor-plan-card__cta {
        display: block;
        width: 100%;
        border: none;
        border-radius: 999px;
        padding: 0.85rem 1rem;
        color: #fff;
        font-weight: 800;
        font-size: 0.95rem;
        background: var(--plan-color, #2563a8);
        cursor: pointer;
        transition: opacity 0.2s ease;
    }
    .vendor-plan-card__cta:hover { opacity: 0.92; }
    .vendor-bank-box {
        padding: 1rem 1.2rem;
        border-radius: 16px;
        background: rgba(15, 47, 77, 0.06);
        border: 1px solid rgba(15, 47, 77, 0.12);
        margin-bottom: 1rem;
        color: #334155;
        font-size: 0.9rem;
    }
    .vendor-bank-box__title {
        color: #0f2f4d;
        font-weight: 700;
        margin-bottom: 0.65rem;
    }
    .vendor-bank-box__list li strong { color: #1d4f91; font-weight: 700; }
    .vendor-bank-box__list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: grid;
        gap: 0.35rem;
    }
    .vendor-receipt-required::after { content: ' *'; color: #ff9b9b; }
    @media (max-width: 900px) {
        .vendor-plans { grid-template-columns: 1fr; }
    }
</style>

<div class="vendor-plans-wrap">
    <label class="vendor-label">{{ __('vendor.onboarding.choose_plan') }}</label>

    <input type="hidden" name="subscription_plan" id="subscription_plan" value="{{ old('subscription_plan', $vendorProfile->subscription_plan ?: 'yearly') }}">

    <div class="vendor-plans">
        @foreach($subscription['plans'] as $planKey => $plan)
            <div class="vendor-plan-card {{ old('subscription_plan', $vendorProfile->subscription_plan ?: 'yearly') === $planKey ? 'selected' : '' }}"
                 data-plan="{{ $planKey }}"
                 style="--plan-color: {{ $plan['color'] }};"
                 onclick="selectVendorPlan('{{ $planKey }}', this)">
                <div class="vendor-plan-card__head" style="background: {{ $plan['color'] }};">
                    {{ $plan['label'] }}
                </div>
                <div class="vendor-plan-card__body">
                    <div class="vendor-plan-card__price-block">
                        <div class="vendor-plan-card__price">{{ $plan['price'] }} {{ $subscription['currency'] }}</div>
                        <span class="vendor-plan-card__original">{{ $plan['original_price'] }} {{ $subscription['currency'] }}</span>
                        <div class="vendor-plan-card__period">{{ $plan['period'] }}</div>
                    </div>
                    <ul class="vendor-plan-card__features">
                        @foreach($plan['features'] as $feature)
                            <li class="vendor-plan-card__feature">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                        @for($i = count($plan['features']); $i < $maxFeatureCount; $i++)
                            <li class="vendor-plan-card__feature vendor-plan-card__feature--spacer" aria-hidden="true">
                                <i class="fas fa-check-circle"></i><span>&nbsp;</span>
                            </li>
                        @endfor
                    </ul>
                    <div class="vendor-plan-card__cta-wrap">
                        <button type="button" class="vendor-plan-card__cta" style="background: {{ $plan['color'] }};">
                            {{ __('vendor.onboarding.select_plan') }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @error('subscription_plan')<div class="vendor-error">{{ $message }}</div>@enderror

    <div class="vendor-bank-box" id="vendorBankInfo">
        @php $defaultPlan = $subscription['plans'][old('subscription_plan', $vendorProfile->subscription_plan ?: 'yearly')] ?? $subscription['plans']['yearly']; @endphp
        <div class="vendor-bank-box__title">
            {{ __('vendor.onboarding.bank_transfer_title', ['amount' => $defaultPlan['price'], 'currency' => $subscription['currency']]) }}
        </div>
        <ul class="vendor-bank-box__list">
            <li><strong>{{ __('vendor.onboarding.bank_name_label') }}:</strong> {{ $subscription['bank_name'] }}</li>
            <li><strong>{{ __('vendor.onboarding.bank_holder_label') }}:</strong> {{ $subscription['bank_account_holder'] }}</li>
            <li><strong>{{ __('vendor.onboarding.bank_account_label') }}:</strong> {{ $subscription['bank_account_number'] }}</li>
            <li><strong>{{ __('vendor.onboarding.bank_iban_label') }}:</strong> {{ $subscription['bank_iban'] }}</li>
        </ul>
    </div>

    <label class="vendor-label vendor-receipt-required">{{ __('vendor.onboarding.upload_receipt') }}</label>
    <div class="vendor-file-upload" id="receiptUploadBox" onclick="document.getElementById('subscription_payment_receipt').click()">
        <i class="fas fa-receipt" style="font-size: 2rem; color: var(--elx-cyan); margin-bottom: 1rem;"></i>
        <div style="color: var(--elx-white); font-weight: 500;">{{ __('vendor.onboarding.upload_receipt') }}</div>
        <div style="color: var(--elx-light); font-size: 0.8rem; margin-top: 0.5rem;">PDF, PNG, JPG</div>
        <input type="file" id="subscription_payment_receipt" name="subscription_payment_receipt" style="display: none;" accept=".pdf,image/*" onchange="document.getElementById('receipt_name').textContent = this.files[0]?.name || ''">
        <div id="receipt_name" style="margin-top: 1rem; color: var(--elx-cyan); font-size: 0.85rem;"></div>
        @if($vendorProfile->subscription_payment_receipt ?? ($hasReceipt ?? false))
            <div style="margin-top: 0.5rem; color: #7ef0bf; font-size: 0.85rem;">
                <i class="fas fa-check-circle"></i> {{ __('vendor.onboarding.receipt_uploaded') }}
            </div>
        @endif
    </div>
    @error('subscription_payment_receipt')<div class="vendor-error">{{ $message }}</div>@enderror
</div>

<script>
    const vendorPlans = @json($subscription['plans']);
    const bankTitleTemplate = @json(__('vendor.onboarding.bank_transfer_title'));

    function selectVendorPlan(planKey, card) {
        document.getElementById('subscription_plan').value = planKey;
        document.querySelectorAll('.vendor-plan-card').forEach((el) => el.classList.remove('selected'));
        card.classList.add('selected');
        const plan = vendorPlans[planKey];
        const bankEl = document.getElementById('vendorBankInfo');
        if (plan && bankEl) {
            const titleEl = bankEl.querySelector('.vendor-bank-box__title');
            if (titleEl) {
                titleEl.textContent = bankTitleTemplate
                    .replace(':amount', plan.price)
                    .replace(':currency', @json($subscription['currency']));
            }
        }
    }
</script>
@endif
