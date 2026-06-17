@extends('layouts.framer')

@section('title', __('dxn_team.page_title'))

@section('head')
<style>
    .dxn-form-card {
        background: var(--elx-glass);
        backdrop-filter: blur(42px);
        border: 1px solid var(--elx-border);
        border-radius: 24px;
        padding: 2rem;
    }
    .dxn-section-title {
        color: var(--elx-cyan);
        font-size: 1rem;
        font-weight: 700;
        margin: 1.5rem 0 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--elx-border);
    }
    .dxn-section-title:first-of-type { margin-top: 0; }
    .dxn-field { margin-bottom: 1rem; }
    .dxn-field label {
        display: block;
        color: var(--elx-light);
        font-size: 0.85rem;
        margin-bottom: 0.45rem;
    }
    .dxn-input, .dxn-select, .dxn-textarea {
        width: 100%;
        padding: 0.95rem 1.2rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--elx-border);
        border-radius: 16px;
        color: var(--elx-white);
        outline: none;
        font-family: inherit;
    }
    .dxn-textarea { min-height: 110px; resize: vertical; }
    .dxn-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .dxn-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
    .dxn-contract-box {
        padding: 1.25rem;
        border: 1px dashed var(--elx-border);
        border-radius: 16px;
        background: rgba(255,255,255,0.03);
    }
    .dxn-contract-check {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-top: 1rem;
        color: var(--elx-light);
        font-size: 0.9rem;
    }
    .dxn-contract-check input { margin-top: 0.2rem; accent-color: var(--elx-cyan); }
    .dxn-form-locked { opacity: 0.45; pointer-events: none; user-select: none; filter: grayscale(0.2); }
    .dxn-hint { font-size: 0.78rem; color: rgba(255,255,255,0.45); margin-top: 0.35rem; }
    .dxn-radio-group { display: flex; gap: 1rem; flex-wrap: wrap; }
    .dxn-radio-group label { display: inline-flex; align-items: center; gap: 0.4rem; margin: 0; cursor: pointer; }
    .dxn-modal-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 2000;
        display: none; align-items: center; justify-content: center; padding: 1rem;
    }
    .dxn-modal-overlay.open { display: flex; }
    .dxn-modal {
        width: min(640px, 100%); background: #13252d; border: 1px solid var(--elx-border);
        border-radius: 20px; padding: 1.5rem; max-height: 80vh; overflow-y: auto;
    }
    .dxn-modal h3 { color: #fff; margin: 0 0 1rem; }
    .dxn-modal p, .dxn-contract-clauses p { color: rgba(255,255,255,0.75); line-height: 1.7; margin: 0 0 0.85rem; }
    .dxn-contract-clauses p:last-child { margin-bottom: 0; }
    .dxn-mode-switch {
        display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 1.5rem;
    }
    .dxn-mode-btn {
        flex: 1; min-width: 200px; padding: 0.85rem 1rem; border-radius: 14px;
        border: 1px solid var(--elx-border); background: rgba(255,255,255,0.04);
        color: var(--elx-light); cursor: pointer; font-family: inherit; transition: 0.2s;
    }
    .dxn-mode-btn.active {
        border-color: var(--elx-cyan); color: #fff; background: rgba(74,200,246,0.12);
        box-shadow: 0 0 20px rgba(74,200,246,0.15);
    }
    .dxn-panel { display: none; }
    .dxn-panel.active { display: block; }
    @media (max-width: 767px) {
        .dxn-grid-2, .dxn-grid-3 { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container" style="max-width: 900px;">
        <div class="elx-section__header" data-animate>
            <h1 class="elx-hero__title">
                <span class="elx-hero__title-gradient">{{ __('dxn_team.hero_title') }}</span>
            </h1>
            <p class="elx-hero__subtitle">{{ __('dxn_team.hero_subtitle') }}</p>
        </div>

        <div class="dxn-form-card" data-animate>
            @if(session('success'))
                <div style="background: rgba(74, 200, 246, 0.1); color: var(--elx-cyan); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid var(--elx-cyan);">
                    {{ session('success') }}
                    @if(session('whatsapp_url'))
                        <div style="margin-top: 0.75rem;">
                            <a href="{{ session('whatsapp_url') }}" target="_blank" rel="noopener" class="elx-btn elx-btn--primary" style="display: inline-flex;">
                                <i class="fab fa-whatsapp"></i> {{ __('dxn_team.send_whatsapp') }}
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            @if($errors->any())
                <div style="background: rgba(255, 100, 100, 0.1); color: #ff8a8a; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid rgba(255,100,100,0.35);">
                    <ul style="margin: 0; padding-inline-start: 1.2rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="dxn-mode-switch">
                <button type="button" class="dxn-mode-btn active" data-mode="new">{{ __('dxn_team.mode_new_distributor') }}</button>
                <button type="button" class="dxn-mode-btn" data-mode="existing">{{ __('dxn_team.mode_existing_member') }}</button>
            </div>

            <div id="existingMemberPanel" class="dxn-panel">
                <form action="{{ route('dxn-distributor.existing-member') }}" method="POST">
                    @csrf
                    <p class="dxn-hint" style="margin-bottom: 1rem;">{{ __('dxn_team.existing_member_hint') }}</p>
                    <div class="dxn-field">
                        <label>{{ __('dxn_team.existing_member_code') }}</label>
                        <input type="text" class="dxn-input" name="member_code" value="{{ old('member_code') }}" required>
                    </div>
                    <div class="dxn-grid-2">
                        <div class="dxn-field">
                            <label>{{ __('dxn_team.form_name') }}</label>
                            <input type="text" class="dxn-input" name="name" value="{{ old('name', $user?->name) }}" required>
                        </div>
                        <div class="dxn-field">
                            <label>{{ __('dxn_team.form_phone') }}</label>
                            <input type="text" class="dxn-input" name="phone" value="{{ old('phone', $user?->phone) }}" required>
                        </div>
                    </div>
                    <div class="dxn-field">
                        <label>{{ __('dxn_team.form_email') }}</label>
                        <input type="email" class="dxn-input" name="email" value="{{ old('email', $user?->email) }}" required>
                    </div>
                    <div class="dxn-field">
                        <label>{{ __('dxn_team.form_message') }}</label>
                        <textarea class="dxn-textarea" name="message">{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="elx-btn elx-btn--primary" style="width:100%;justify-content:center;padding:1rem;">
                        {{ __('dxn_team.existing_member_submit') }}
                    </button>
                </form>
            </div>

            <div id="newDistributorPanel" class="dxn-panel active">
            <form action="{{ route('dxn-distributor.store') }}" method="POST" id="dxnDistributorForm" novalidate>
                @csrf

                <div class="dxn-section-title">{{ __('dxn_team.contract_section') }}</div>
                <div class="dxn-contract-box">
                    <button type="button" class="elx-btn elx-btn--glass" id="openContractModal">
                        <i class="fas fa-file-contract"></i> {{ __('dxn_team.read_contract') }}
                    </button>
                    <label class="dxn-contract-check">
                        <input type="checkbox" name="contract_accepted" value="1" id="contractAccepted" @checked(old('contract_accepted'))>
                        <span>{{ __('dxn_team.contract_accept') }}</span>
                    </label>
                    <p class="dxn-hint" id="contractHint">{{ __('dxn_team.contract_required_hint') }}</p>
                </div>

                <div id="membershipFields" class="dxn-form-locked">
                    <div class="dxn-section-title">{{ __('dxn_team.section_membership') }}</div>

                    <div class="dxn-grid-2">
                        <div class="dxn-field">
                            <label>{{ __('dxn_team.form_country') }}</label>
                            <select class="dxn-select" name="country" required disabled>
                                <option value="">{{ __('dxn_team.form_country_placeholder') }}</option>
                                <option value="KSA" @selected(old('country') === 'KSA')>🇸🇦 {{ __('shop.country_ksa') }}</option>
                                <option value="UAE" @selected(old('country') === 'UAE')>🇦🇪 {{ __('shop.country_uae') }}</option>
                            </select>
                        </div>
                        <div class="dxn-field">
                            <label>{{ __('dxn_team.form_sponsor_code') }}</label>
                            <select class="dxn-select" id="sponsorCodeSelect" disabled>
                                <option value="">{{ __('dxn_team.form_sponsor_code_placeholder') }}</option>
                                <option value="__manual__">{{ __('dxn_team.form_sponsor_code_manual') }}</option>
                                @foreach($sponsorCodes as $code)
                                    <option value="{{ $code->code }}" data-name="{{ $code->sponsor_name }}" @selected(old('sponsor_code') === $code->code)>{{ $code->code }} — {{ $code->sponsor_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="dxn-grid-2">
                        <div class="dxn-field">
                            <label>{{ __('dxn_team.form_sponsor_code') }}</label>
                            <input type="text" class="dxn-input" name="sponsor_code" id="sponsorCodeInput" value="{{ old('sponsor_code') }}" required disabled>
                        </div>
                        <div class="dxn-field">
                            <label>{{ __('dxn_team.form_sponsor_name') }}</label>
                            <input type="text" class="dxn-input" name="sponsor_name" id="sponsorNameInput" value="{{ old('sponsor_name') }}" placeholder="{{ __('dxn_team.form_sponsor_name_placeholder') }}" required disabled>
                        </div>
                    </div>

                    <div class="dxn-field">
                        <label>{{ __('dxn_team.form_name') }}</label>
                        <input type="text" class="dxn-input" name="name" value="{{ old('name', $user?->name) }}" required disabled>
                    </div>

                    <div class="dxn-grid-3">
                        <div class="dxn-field">
                            <label>{{ __('dxn_team.form_gender') }}</label>
                            <select class="dxn-select" name="gender" required disabled>
                                <option value="">{{ __('dxn_team.form_country_placeholder') }}</option>
                                <option value="male" @selected(old('gender') === 'male')>{{ __('dxn_team.gender_male') }}</option>
                                <option value="female" @selected(old('gender') === 'female')>{{ __('dxn_team.gender_female') }}</option>
                            </select>
                        </div>
                        <div class="dxn-field">
                            <label>{{ __('dxn_team.form_dob') }}</label>
                            <input type="date" class="dxn-input" name="date_of_birth" value="{{ old('date_of_birth') }}" required disabled>
                        </div>
                        <div class="dxn-field">
                            <label>{{ __('dxn_team.form_nationality') }}</label>
                            <input type="text" class="dxn-input" name="nationality" value="{{ old('nationality') }}" required disabled>
                        </div>
                    </div>

                    <div class="dxn-grid-2">
                        <div class="dxn-field">
                            <label>{{ __('dxn_team.form_id_number') }}</label>
                            <input type="text" class="dxn-input" name="id_number" value="{{ old('id_number') }}" disabled>
                        </div>
                        <div class="dxn-field">
                            <label>{{ __('dxn_team.form_passport_number') }}</label>
                            <input type="text" class="dxn-input" name="passport_number" value="{{ old('passport_number') }}" disabled>
                        </div>
                    </div>
                    <p class="dxn-hint">{{ __('dxn_team.form_id_hint') }}</p>

                    <div class="dxn-grid-2">
                        <div class="dxn-field">
                            <label>{{ __('dxn_team.form_phone') }}</label>
                            <input type="text" class="dxn-input" name="phone" value="{{ old('phone', $user?->phone) }}" required disabled>
                        </div>
                        <div class="dxn-field">
                            <label>{{ __('dxn_team.form_email') }}</label>
                            <input type="email" class="dxn-input" name="email" value="{{ old('email', $user?->email) }}" required disabled>
                        </div>
                    </div>

                    <div class="dxn-section-title">{{ __('dxn_team.section_heir') }}</div>
                    <div class="dxn-field">
                        <label>{{ __('dxn_team.form_has_heir') }}</label>
                        <div class="dxn-radio-group">
                            <label><input type="radio" name="has_heir" value="0" @checked(! old('has_heir')) disabled> {{ __('dxn_team.form_has_heir_no') }}</label>
                            <label><input type="radio" name="has_heir" value="1" @checked(old('has_heir')) disabled> {{ __('dxn_team.form_has_heir_yes') }}</label>
                        </div>
                    </div>

                    <div id="heirFields" style="display: none;">
                        <div class="dxn-grid-2">
                            <div class="dxn-field">
                                <label>{{ __('dxn_team.form_heir_name') }}</label>
                                <input type="text" class="dxn-input" name="heir_name" value="{{ old('heir_name') }}" disabled>
                            </div>
                            <div class="dxn-field">
                                <label>{{ __('dxn_team.form_heir_relationship') }}</label>
                                <input type="text" class="dxn-input" name="heir_relationship" value="{{ old('heir_relationship') }}" disabled>
                            </div>
                        </div>
                        <div class="dxn-grid-2">
                            <div class="dxn-field">
                                <label>{{ __('dxn_team.form_heir_id_number') }}</label>
                                <input type="text" class="dxn-input" name="heir_id_number" value="{{ old('heir_id_number') }}" disabled>
                            </div>
                            <div class="dxn-field">
                                <label>{{ __('dxn_team.form_heir_passport_number') }}</label>
                                <input type="text" class="dxn-input" name="heir_passport_number" value="{{ old('heir_passport_number') }}" disabled>
                            </div>
                        </div>
                        <p class="dxn-hint">{{ __('dxn_team.form_heir_id_hint') }}</p>
                    </div>

                    <div class="dxn-section-title">{{ __('dxn_team.section_address') }}</div>
                    <div class="dxn-field">
                        <label>{{ __('dxn_team.form_address') }}</label>
                        <textarea class="dxn-textarea" name="address" required disabled>{{ old('address') }}</textarea>
                    </div>
                    <div class="dxn-grid-3">
                        <div class="dxn-field">
                            <label>{{ __('dxn_team.form_address_country') }}</label>
                            <select class="dxn-select" name="address_country" required disabled>
                                <option value="">{{ __('dxn_team.form_country_placeholder') }}</option>
                                <option value="KSA" @selected(old('address_country') === 'KSA')>🇸🇦 {{ __('shop.country_ksa') }}</option>
                                <option value="UAE" @selected(old('address_country') === 'UAE')>🇦🇪 {{ __('shop.country_uae') }}</option>
                            </select>
                        </div>
                        <div class="dxn-field">
                            <label>{{ __('dxn_team.form_address_city') }}</label>
                            <input type="text" class="dxn-input" name="address_city" value="{{ old('address_city') }}" required disabled>
                        </div>
                        <div class="dxn-field">
                            <label>{{ __('dxn_team.form_postal_code') }}</label>
                            <input type="text" class="dxn-input" name="postal_code" value="{{ old('postal_code') }}" required disabled>
                        </div>
                    </div>

                    <button type="submit" class="elx-btn elx-btn--primary" style="width: 100%; justify-content: center; padding: 1rem; margin-top: 1rem;" disabled>
                        {{ __('dxn_team.form_submit') }}
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<div class="dxn-modal-overlay" id="contractModal" role="dialog" aria-modal="true">
    <div class="dxn-modal">
        <h3>{{ __('dxn_team.contract_modal_title') }}</h3>
        <div class="dxn-contract-clauses">
            @foreach(__('dxn_team.contract_clauses') as $clause)
                <p>{{ $clause }}</p>
            @endforeach
        </div>
        <button type="button" class="elx-btn elx-btn--primary" id="closeContractModal" style="margin-top: 1rem;">
            {{ __('dxn_team.contract_understood') }}
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.dxn-mode-btn').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.dxn-mode-btn').forEach((btn) => btn.classList.remove('active'));
            button.classList.add('active');
            const isExisting = button.dataset.mode === 'existing';
            document.getElementById('existingMemberPanel').classList.toggle('active', isExisting);
            document.getElementById('newDistributorPanel').classList.toggle('active', !isExisting);
        });
    });

    const contractAccepted = document.getElementById('contractAccepted');
    const membershipFields = document.getElementById('membershipFields');
    const contractModal = document.getElementById('contractModal');
    const sponsorCodeSelect = document.getElementById('sponsorCodeSelect');
    const sponsorCodeInput = document.getElementById('sponsorCodeInput');
    const sponsorNameInput = document.getElementById('sponsorNameInput');
    const heirFields = document.getElementById('heirFields');
    const form = document.getElementById('dxnDistributorForm');

    function setFieldsEnabled(enabled) {
        membershipFields.classList.toggle('dxn-form-locked', !enabled);
        membershipFields.querySelectorAll('input, select, textarea, button[type="submit"]').forEach((el) => {
            el.disabled = !enabled;
        });
        document.getElementById('contractHint').style.display = enabled ? 'none' : 'block';
    }

    function syncContractGate() {
        setFieldsEnabled(contractAccepted.checked);
    }

    document.getElementById('openContractModal').addEventListener('click', () => contractModal.classList.add('open'));
    document.getElementById('closeContractModal').addEventListener('click', () => contractModal.classList.remove('open'));
    contractModal.addEventListener('click', (event) => {
        if (event.target === contractModal) contractModal.classList.remove('open');
    });
    contractAccepted.addEventListener('change', syncContractGate);

    sponsorCodeSelect.addEventListener('change', () => {
        const value = sponsorCodeSelect.value;
        if (!value) return;
        if (value === '__manual__') {
            sponsorCodeInput.value = '';
            sponsorNameInput.value = '';
            sponsorNameInput.readOnly = false;
            sponsorCodeInput.focus();
            return;
        }
        const option = sponsorCodeSelect.selectedOptions[0];
        sponsorCodeInput.value = value;
        sponsorNameInput.value = option.dataset.name || '';
        sponsorNameInput.readOnly = true;
    });

    document.querySelectorAll('input[name="has_heir"]').forEach((radio) => {
        radio.addEventListener('change', () => {
            heirFields.style.display = radio.value === '1' && radio.checked ? 'block' : 'none';
        });
    });

    if (document.querySelector('input[name="has_heir"][value="1"]')?.checked) {
        heirFields.style.display = 'block';
    }

    @if(old('sponsor_code'))
        const oldCode = @json(old('sponsor_code'));
        const match = Array.from(sponsorCodeSelect.options).find((opt) => opt.value === oldCode);
        if (match) {
            sponsorCodeSelect.value = oldCode;
            sponsorNameInput.readOnly = true;
        } else {
            sponsorCodeSelect.value = '__manual__';
            sponsorNameInput.readOnly = false;
        }
    @endif

    syncContractGate();
    if (@json(old('contract_accepted'))) {
        contractAccepted.checked = true;
        syncContractGate();
    }

    form.addEventListener('submit', (event) => {
        if (!contractAccepted.checked) {
            event.preventDefault();
            Swal.fire({ icon: 'warning', text: @json(__('dxn_team.validation_contract_required')) });
            return;
        }
        const idNumber = form.querySelector('[name="id_number"]').value.trim();
        const passportNumber = form.querySelector('[name="passport_number"]').value.trim();
        if (!idNumber && !passportNumber) {
            event.preventDefault();
            Swal.fire({ icon: 'warning', text: @json(__('dxn_team.validation_id_required')) });
            return;
        }
        const hasHeir = form.querySelector('input[name="has_heir"][value="1"]').checked;
        if (hasHeir) {
            const heirId = form.querySelector('[name="heir_id_number"]').value.trim();
            const heirPassport = form.querySelector('[name="heir_passport_number"]').value.trim();
            if (!heirId && !heirPassport) {
                event.preventDefault();
                Swal.fire({ icon: 'warning', text: @json(__('dxn_team.validation_heir_id_required')) });
            }
        }
    });
</script>
@endsection
