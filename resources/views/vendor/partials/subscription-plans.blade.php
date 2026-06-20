@if($subscription['requires_payment'])
@php
    $maxFeatureCount = max(array_map(fn (array $plan) => count($plan['features']), $subscription['plans']));
@endphp
<style>
    .vendor-plans-wrap {
        margin-bottom: 2rem;
        padding: 1.75rem 1.5rem 1.5rem;
        border-radius: 24px;
        background: linear-gradient(160deg, rgba(11, 22, 32, 0.98) 0%, rgba(15, 35, 52, 0.96) 55%, rgba(10, 26, 38, 0.98) 100%);
        border: 1px solid rgba(74, 200, 246, 0.18);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04), 0 18px 50px rgba(0, 0, 0, 0.28);
    }

    .vendor-plans-wrap .vendor-label {
        color: rgba(255, 255, 255, 0.92);
        font-weight: 700;
        margin-bottom: 1.25rem;
        display: block;
    }

    .vendor-plans {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.5rem;
        align-items: stretch;
    }

    .vendor-plan-card {
        --plan-accent: #4ac8f6;
        --plan-gradient: linear-gradient(180deg, #1b3554 0%, #12263d 52%, #0b1828 100%);
        --plan-cta: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
        border-radius: 22px;
        overflow: hidden;
        background: var(--plan-gradient);
        border: 1px solid rgba(74, 200, 246, 0.14);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .vendor-plan-card--monthly {
        --plan-accent: #5b9bd5;
        --plan-cta: linear-gradient(90deg, #1e4a72 0%, #3d6a9a 100%);
    }

    .vendor-plan-card--semi_annual {
        --plan-accent: #60a5fa;
        --plan-cta: linear-gradient(90deg, #1d4ed8 0%, #3b82f6 100%);
    }

    .vendor-plan-card--yearly {
        --plan-accent: #4ac8f6;
        --plan-cta: linear-gradient(90deg, #2563eb 0%, #4ac8f6 100%);
    }

    .vendor-plan-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 22px 48px rgba(0, 0, 0, 0.42);
        border-color: rgba(74, 200, 246, 0.28);
    }

    .vendor-plan-card.selected {
        border-color: var(--elx-cyan, #4ac8f6);
        box-shadow: 0 0 0 1px var(--elx-cyan, #4ac8f6), 0 22px 48px rgba(74, 200, 246, 0.18);
        transform: translateY(-6px);
    }

    .vendor-plan-card__head {
        padding: 1.35rem 1rem 0.5rem;
        text-align: center;
        color: #fff;
        font-weight: 800;
        font-size: 1.35rem;
        letter-spacing: 0.03em;
    }

    .vendor-plan-card__body {
        display: flex;
        flex-direction: column;
        flex: 1;
        padding: 0.75rem 1.25rem 1.35rem;
    }

    .vendor-plan-card__price-block {
        text-align: center;
        margin-bottom: 1.1rem;
        flex-shrink: 0;
    }

    .vendor-plan-card__price {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.1;
        color: var(--plan-accent, #4ac8f6);
    }

    .vendor-plan-card__original {
        display: block;
        margin-top: 0.35rem;
        font-size: 0.82rem;
        color: rgba(148, 180, 210, 0.75);
        text-decoration: line-through;
    }

    .vendor-plan-card__period {
        margin-top: 0.35rem;
        font-size: 0.86rem;
        color: rgba(255, 255, 255, 0.58);
        font-weight: 600;
    }

    .vendor-plan-card__features {
        list-style: none;
        margin: 0 0 1.15rem;
        padding: 0;
        flex: 1;
    }

    .vendor-plan-card__feature {
        display: flex;
        align-items: flex-start;
        gap: 0.7rem;
        padding: 0.55rem 0;
        font-size: 0.84rem;
        color: rgba(255, 255, 255, 0.88) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .vendor-plan-card__feature span {
        color: rgba(255, 255, 255, 0.88) !important;
        flex: 1;
        line-height: 1.45;
    }

    .vendor-plan-card__feature:last-child { border-bottom: none; }

    .vendor-plan-card__feature i {
        width: 1.15rem;
        height: 1.15rem;
        margin-top: 0.12rem;
        border-radius: 50%;
        background: var(--plan-accent, #4ac8f6);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.52rem;
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
        padding: 0.82rem 1rem;
        color: #fff;
        font-weight: 800;
        font-size: 0.92rem;
        background: var(--plan-cta);
        cursor: pointer;
        transition: opacity 0.2s ease, transform 0.2s ease;
        box-shadow: 0 8px 22px rgba(37, 99, 235, 0.28);
    }

    .vendor-plan-card__cta:hover {
        opacity: 0.95;
        transform: translateY(-1px);
    }

    .vendor-plan-card--yearly .vendor-plan-card__cta {
        box-shadow: 0 10px 28px rgba(74, 200, 246, 0.35);
    }

    .vendor-bank-box {
        padding: 1.1rem 1.25rem;
        border-radius: 18px;
        background: rgba(74, 200, 246, 0.07);
        border: 1px solid rgba(74, 200, 246, 0.2);
        margin-bottom: 1rem;
        color: rgba(255, 255, 255, 0.82);
        font-size: 0.9rem;
    }

    .vendor-bank-box__title {
        color: var(--elx-cyan, #4ac8f6);
        font-weight: 700;
        margin-bottom: 0.75rem;
        line-height: 1.5;
    }

    .vendor-bank-box__list li strong {
        color: rgba(255, 255, 255, 0.95);
        font-weight: 700;
    }

    .vendor-bank-box__list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: grid;
        gap: 0.4rem;
    }

    .vendor-receipt-panel {
        margin-top: 1.15rem;
        padding: 1.15rem 1.2rem 1.25rem;
        border-radius: 18px;
        background: linear-gradient(160deg, rgba(19, 37, 45, 0.92) 0%, rgba(10, 26, 34, 0.95) 100%);
        border: 1px solid rgba(74, 200, 246, 0.16);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04);
    }

    .vendor-receipt-panel .vendor-label {
        color: rgba(255, 255, 255, 0.92);
        font-weight: 700;
    }

    .vendor-receipt-upload {
        border: 2px dashed rgba(74, 200, 246, 0.38);
        border-radius: 16px;
        padding: 1.75rem 1.25rem;
        text-align: center;
        cursor: pointer;
        transition: 0.2s ease;
        background: rgba(255, 255, 255, 0.03);
    }

    .vendor-receipt-upload:hover {
        border-color: var(--elx-cyan, #4ac8f6);
        background: rgba(74, 200, 246, 0.06);
        box-shadow: 0 8px 24px rgba(74, 200, 246, 0.12);
    }

    .vendor-receipt-upload__icon {
        font-size: 2rem;
        color: var(--elx-cyan, #4ac8f6);
        margin-bottom: 1rem;
    }

    .vendor-receipt-upload__title {
        color: rgba(255, 255, 255, 0.92);
        font-weight: 700;
    }

    .vendor-receipt-upload__hint {
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.8rem;
        margin-top: 0.5rem;
    }

    .vendor-receipt-upload__name {
        margin-top: 1rem;
        color: var(--elx-cyan, #4ac8f6);
        font-size: 0.85rem;
        font-weight: 600;
    }

    .vendor-receipt-upload__done {
        margin-top: 0.5rem;
        color: #34d399;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .vendor-receipt-required::after { content: ' *'; color: #f87171; }

    @media (max-width: 900px) {
        .vendor-plans { grid-template-columns: 1fr; }
    }
</style>

<div class="vendor-plans-wrap">
    <label class="vendor-label">{{ __('vendor.onboarding.choose_plan') }}</label>

    <input type="hidden" name="subscription_plan" id="subscription_plan" value="{{ old('subscription_plan', $vendorProfile->subscription_plan ?: 'yearly') }}">

    <div class="vendor-plans">
        @foreach($subscription['plans'] as $planKey => $plan)
            <div class="vendor-plan-card vendor-plan-card--{{ $planKey }} {{ old('subscription_plan', $vendorProfile->subscription_plan ?: 'yearly') === $planKey ? 'selected' : '' }}"
                 data-plan="{{ $planKey }}"
                 onclick="selectVendorPlan('{{ $planKey }}', this)">
                <div class="vendor-plan-card__head">
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
                                <i class="fas fa-check"></i>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                        @for($i = count($plan['features']); $i < $maxFeatureCount; $i++)
                            <li class="vendor-plan-card__feature vendor-plan-card__feature--spacer" aria-hidden="true">
                                <i class="fas fa-check"></i><span>&nbsp;</span>
                            </li>
                        @endfor
                    </ul>
                    <div class="vendor-plan-card__cta-wrap">
                        <button type="button" class="vendor-plan-card__cta">
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

    <div class="vendor-receipt-panel">
        <label class="vendor-label vendor-receipt-required">{{ __('vendor.onboarding.upload_receipt') }}</label>
        <div class="vendor-receipt-upload" id="receiptUploadBox" onclick="document.getElementById('subscription_payment_receipt').click()">
            <i class="fas fa-cloud-upload-alt vendor-receipt-upload__icon"></i>
            <div class="vendor-receipt-upload__title">{{ __('vendor.onboarding.upload_receipt') }}</div>
            <div class="vendor-receipt-upload__hint">PDF, PNG, JPG</div>
            <input type="file" id="subscription_payment_receipt" name="subscription_payment_receipt" style="display: none;" accept=".pdf,image/*" onchange="document.getElementById('receipt_name').textContent = this.files[0]?.name || ''">
            <div id="receipt_name" class="vendor-receipt-upload__name"></div>
            @if($vendorProfile->subscription_payment_receipt ?? ($hasReceipt ?? false))
                <div class="vendor-receipt-upload__done">
                    <i class="fas fa-check-circle"></i> {{ __('vendor.onboarding.receipt_uploaded') }}
                </div>
            @endif
        </div>
        @error('subscription_payment_receipt')<div class="vendor-error">{{ $message }}</div>@enderror
    </div>
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
