@extends('layouts.admin')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <a href="{{ route('admin.vendors.requests.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="fas fa-arrow-left"></i> {{ __('admin.vendor_requests.back') }}
        </a>
        <h2 class="mb-1"> {{ __('admin.vendor_requests.app_details') }} </h2>
        <p class="text-muted mb-0">{{ __('admin.vendor_requests.review_subtitle', ['brand' => $vendorProfile->brand_name]) }}</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        @if($vendorProfile->brand)
            <a href="{{ route('admin.brands.edit', $vendorProfile->brand) }}" class="btn btn-outline-primary">
                <i class="fas fa-store me-2"></i> {{ __('admin.vendor_requests.edit_brand') }}
            </a>
        @endif
        @if($vendorProfile->status === 'pending')
            <button type="button" class="btn btn-outline-danger" onclick="showRejectPopup()">
                <i class="fas fa-times"></i> {{ __('admin.vendor_requests.reject') }}
            </button>
            <form id="rejectForm" action="{{ route('admin.vendors.requests.update', $vendorProfile) }}" method="POST" style="display: none;">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" id="hidden_reject_status">
                <input type="hidden" name="rejection_reason" id="hidden_reject_reason">
            </form>
            <form action="{{ route('admin.vendors.requests.update', $vendorProfile) }}" method="POST" class="d-inline ms-2" data-confirm="{{ __('admin.vendor_requests.confirm_approve') }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> {{ __('admin.vendor_requests.approve_app') }}
                </button>
            </form>
        @else
            @if($vendorProfile->status === 'rejected_with_notes')
                <div class="text-end">
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2 mb-2">{{ __('admin.vendor_requests.returned_revision') }}</span>
                    <div class="small text-muted" style="max-width: 300px; text-align: left;">
                        <strong>{{ __('admin.vendor_requests.admin_notes') }}</strong> {{ $vendorProfile->rejection_reason }}
                    </div>
                </div>
            @else
                <span class="badge bg-{{ $vendorProfile->status === 'approved' ? 'success' : 'danger' }} fs-6 px-3 py-2">
                    {{ __('admin.vendor_requests.status_' . $vendorProfile->status) }}
                </span>
            @endif
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">{{ __('admin.vendor_requests.brand_info') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-flex gap-4 mb-4">
                    @if($vendorProfile->brand_logo)
                        <img src="{{ asset('storage/' . $vendorProfile->brand_logo) }}" alt="Logo" class="rounded-3" style="width: 120px; height: 120px; object-fit: cover; border: 1px solid #eaeaea;">
                    @else
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; background: #f8f9fa; border: 1px dashed #dee2e6;">
                            <i class="fas fa-store fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div>
                        <h4 class="mb-2">{{ $vendorProfile->brand_name }}</h4>
                        <p class="text-muted mb-0">{{ $vendorProfile->brand_description }}</p>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="small text-muted fw-bold text-uppercase mb-1">{{ __('admin.vendor_requests.commercial_registration') }}</label>
                        <div>{{ $vendorProfile->commercial_registration_number ?: '—' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="small text-muted fw-bold text-uppercase mb-1">{{ __('admin.vendor_requests.service_countries') }}</label>
                        <div>
                            @foreach($vendorProfile->service_countries ?? [] as $country)
                                <span class="badge bg-light text-dark border">{{ $country }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="small text-muted fw-bold text-uppercase mb-1">{{ __('admin.vendor_requests.product_types') }}</label>
                        <div>
                            @foreach($vendorProfile->product_types ?? [] as $type)
                                <span class="badge bg-light text-dark border">{{ $type }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">{{ __('admin.vendor_requests.social_links') }}</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @if($vendorProfile->instagram_link)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div>
                            <i class="fab fa-instagram fa-fw text-danger"></i> Instagram
                        </div>
                        <a href="{{ $vendorProfile->instagram_link }}" target="_blank" class="text-decoration-none">{{ __('admin.vendor_requests.visit_link') }} <i class="fas fa-external-link-alt small"></i></a>
                    </li>
                    @endif
                    
                    @if($vendorProfile->tiktok_link)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div>
                            <i class="fab fa-tiktok fa-fw text-dark"></i> TikTok
                        </div>
                        <a href="{{ $vendorProfile->tiktok_link }}" target="_blank" class="text-decoration-none">{{ __('admin.vendor_requests.visit_link') }} <i class="fas fa-external-link-alt small"></i></a>
                    </li>
                    @endif
                    
                    @if($vendorProfile->snapchat_link)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div>
                            <i class="fab fa-snapchat-ghost fa-fw text-warning"></i> Snapchat
                        </div>
                        <a href="{{ $vendorProfile->snapchat_link }}" target="_blank" class="text-decoration-none">{{ __('admin.vendor_requests.visit_link') }} <i class="fas fa-external-link-alt small"></i></a>
                    </li>
                    @endif

                    @if($vendorProfile->store_link)
                    <li class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="fw-bold">
                                <i class="fas fa-link fa-fw text-primary"></i> {{ __('admin.vendor_requests.external_store') }}
                            </div>
                            <a href="{{ $vendorProfile->store_link }}" target="_blank" class="text-decoration-none">{{ __('admin.vendor_requests.visit_store') }} <i class="fas fa-external-link-alt small"></i></a>
                        </div>
                        @if($vendorProfile->store_link_description)
                            <p class="small text-muted mb-0 ms-4">{{ $vendorProfile->store_link_description }}</p>
                        @endif
                    </li>
                    @endif
                </ul>
                
                @if(!$vendorProfile->instagram_link && !$vendorProfile->tiktok_link && !$vendorProfile->snapchat_link && !$vendorProfile->store_link)
                    <div class="text-muted fst-italic">{{ __('admin.vendor_requests.no_links') }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">{{ __('admin.vendor_requests.applicant_info') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--bs-primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem;">
                        {{ $vendorProfile->user->avatar_initials }}
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $vendorProfile->user->name }}</h6>
                        <div class="small text-muted">{{ __('admin.vendor_requests.member_since', ['date' => $vendorProfile->user->created_at->format('M Y')]) }}</div>
                    </div>
                </div>
                
                <hr>

                <div class="mb-3">
                    <label class="small text-muted fw-bold text-uppercase mb-1">{{ __('admin.vendor_requests.email') }}</label>
                    <div><a href="mailto:{{ $vendorProfile->user->email }}" class="text-decoration-none">{{ $vendorProfile->user->email }}</a></div>
                </div>

                <div class="mb-3">
                    <label class="small text-muted fw-bold text-uppercase mb-1">{{ __('admin.vendor_requests.phone') }}</label>
                    <div>{{ $vendorProfile->user->phone ?: __('admin.vendor_requests.not_provided') }}</div>
                </div>

                <div class="mb-3">
                    <label class="small text-muted fw-bold text-uppercase mb-1">{{ __('admin.vendor_requests.payment_method') }}</label>
                    <div>
                        <i class="fas fa-money-bill-wave text-success"></i>
                        {{ ucwords(str_replace('_', ' ', $vendorProfile->payment_method)) }}
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="small text-muted fw-bold text-uppercase mb-1">{{ __('admin.vendor_requests.subscription_status') }}</label>
                    <div>{{ __('admin.vendor_requests.subscription_status_'.$vendorProfile->subscription_payment_status) }}</div>
                </div>

                @if($vendorProfile->subscription_payment_receipt)
                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase mb-1">{{ __('admin.vendor_requests.subscription_receipt') }}</label>
                        <div>
                            <a href="{{ asset('storage/' . $vendorProfile->subscription_payment_receipt) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file-download"></i> {{ __('admin.vendor_requests.view_doc') }}
                            </a>
                        </div>
                    </div>
                @endif

                @if($vendorProfile->subscription_payment_status === 'pending')
                    <form action="{{ route('admin.vendors.requests.confirm-subscription', $vendorProfile) }}" method="POST" data-confirm="{{ __('admin.vendor_requests.confirm_subscription') }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success w-100">
                            {{ __('admin.vendor_requests.confirm_subscription_btn') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showRejectPopup() {
    Swal.fire({
        title: '<div class="pt-2">{{ __('admin.vendor_requests.swal.review_request') }}</div>',
        icon: 'warning',
        html: `
            <div class="text-start px-2 mb-2">
                <div class="p-3 rounded-4 bg-light border mb-4">
                    <label class="form-label fw-bold mb-3 d-block text-secondary small text-uppercase" style="letter-spacing: 0.5px;">{{ __('admin.vendor_requests.swal.rejection_action') }}</label>
                    
                    <div class="mb-3">
                        <input class="btn-check" type="radio" name="swal_status" id="swalRejectNotes" value="rejected_with_notes" checked autocomplete="off">
                        <label class="btn btn-outline-warning w-100 text-start py-3 px-4 rounded-4 d-flex align-items-center gap-3 shadow-sm border-2" for="swalRejectNotes">
                            <i class="fas fa-undo-alt fs-4"></i>
                            <div>
                                <div class="fw-bold">{{ __('admin.vendor_requests.swal.return_revision') }}</div>
                                <div class="small opacity-75">{{ __('admin.vendor_requests.swal.return_hint') }}</div>
                            </div>
                        </label>
                    </div>

                    <div>
                        <input class="btn-check" type="radio" name="swal_status" id="swalRejectPermanent" value="rejected" autocomplete="off">
                        <label class="btn btn-outline-danger w-100 text-start py-3 px-4 rounded-4 d-flex align-items-center gap-3 shadow-sm border-2" for="swalRejectPermanent">
                            <i class="fas fa-ban fs-4"></i>
                            <div>
                                <div class="fw-bold">{{ __('admin.vendor_requests.swal.perm_reject') }}</div>
                                <div class="small opacity-75">{{ __('admin.vendor_requests.swal.perm_reject_hint') }}</div>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div id="swalRejectionNotesContainer" class="px-1" style="transition: all 0.3s ease;">
                    <label for="swal_rejection_reason" class="form-label fw-bold mb-2 text-secondary small text-uppercase" style="letter-spacing: 0.5px;">{{ __('admin.vendor_requests.swal.admin_feedback') }} <span class="text-danger">*</span></label>
                    <textarea id="swal_rejection_reason" class="form-control rounded-4 border-2" style="padding: 1.2rem; font-size: 1rem; line-height: 1.6; resize: none; border-color: #dee2e6;" rows="4" placeholder="{{ __('admin.vendor_requests.swal.feedback_placeholder') }}"></textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '{{ __('admin.vendor_requests.swal.process_reject') }}',
        cancelButtonText: '{{ __('admin.vendor_requests.swal.cancel') }}',
        confirmButtonColor: '#13252D',
        cancelButtonColor: '#6c757d',
        padding: '1.5rem',
        width: '550px',
        customClass: {
            popup: 'rounded-5 border-0 shadow-lg',
            confirmButton: 'px-5 py-2 fw-bold rounded-pill',
            cancelButton: 'px-4 py-2 fw-bold rounded-pill',
            title: 'fw-bold'
        },
        didOpen: () => {
            const radioNotes = document.getElementById('swalRejectNotes');
            const radioPerm = document.getElementById('swalRejectPermanent');
            const notesContainer = document.getElementById('swalRejectionNotesContainer');
            
            const toggleNotes = () => {
                notesContainer.style.opacity = radioNotes.checked ? '1' : '0.4';
                notesContainer.style.pointerEvents = radioNotes.checked ? 'auto' : 'none';
            };
            
            radioNotes.addEventListener('change', toggleNotes);
            radioPerm.addEventListener('change', toggleNotes);
        },
        preConfirm: () => {
            const status = document.querySelector('input[name="swal_status"]:checked').value;
            const reason = document.getElementById('swal_rejection_reason').value;
            
            if (status === 'rejected_with_notes' && !reason.trim()) {
                Swal.showValidationMessage('{{ __('admin.vendor_requests.swal.validation_feedback') }}');
                return false;
            }
            
            return { status, reason };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('hidden_reject_status').value = result.value.status;
            document.getElementById('hidden_reject_reason').value = result.value.reason;
            document.getElementById('rejectForm').submit();
        }
    });
}
</script>
@endpush

@endsection
