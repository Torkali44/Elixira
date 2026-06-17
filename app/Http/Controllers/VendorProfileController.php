<?php

namespace App\Http\Controllers;

use App\Models\VendorProfile;
use App\Support\VendorSubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class VendorProfileController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        if ($user->vendorProfile) {
            if ($user->vendorProfile->status === 'pending') {
                return redirect()->route('vendor.pending');
            } elseif ($user->vendorProfile->status === 'approved') {
                return redirect()->route('dashboard')->with('status', __('vendor.already_approved'));
            } elseif ($user->vendorProfile->status === 'rejected') {
                return redirect()->route('dashboard')->with('error', __('vendor.permanently_rejected'));
            } elseif ($user->vendorProfile->status === 'rejected_with_notes' && ! request()->has('edit')) {
                return redirect()->route('vendor.rejected');
            }
        }

        $subscription = app(VendorSubscriptionService::class)->subscriptionInfo();

        return view('vendor.onboarding', [
            'vendorProfile' => $user->vendorProfile ?? new VendorProfile,
            'subscription' => $subscription,
            'initialStep' => (int) request('step', $user->vendorProfile?->onboarding_step ?? 1),
            'hasReceipt' => filled($user->vendorProfile?->subscription_payment_receipt),
        ]);
    }

    public function store(Request $request)
    {
        $isDraft = $request->action === 'draft';
        $requiresPayment = app(VendorSubscriptionService::class)->requiresPayment();

        $rules = [
            'brand_name' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'brand_logo' => 'nullable|image|max:2048',
            'brand_description' => $isDraft ? 'nullable|string|max:1000' : 'required|string|max:1000',
            'commercial_registration_number' => $isDraft ? 'nullable|string|max:100' : 'required|string|max:100',
            'phone_country_code' => $isDraft ? 'nullable|in:+966,+971' : 'required|in:+966,+971',
            'phone' => $isDraft ? 'nullable|string|max:20' : 'required|string|max:20',
            'instagram_link' => 'nullable|url|max:255',
            'tiktok_link' => 'nullable|url|max:255',
            'snapchat_link' => 'nullable|url|max:255',
            'store_link' => 'nullable|url|max:255',
            'store_link_description' => 'nullable|string|max:500',
            'service_countries' => $isDraft ? 'nullable|array' : 'required|array',
            'product_types' => $isDraft ? 'nullable|array' : 'required|array',
            'verification_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'subscription_payment_receipt' => ($isDraft || ! $requiresPayment) ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096' : 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'subscription_plan' => $requiresPayment && ! $isDraft ? 'required|in:monthly,semi_annual,yearly' : 'nullable|in:monthly,semi_annual,yearly',
            'onboarding_step' => 'nullable|integer|min:1|max:4',
            'action' => 'required|in:draft,submit',
        ];

        if (! $isDraft) {
            $rules['terms'] = 'required|accepted';
        }

        $request->validate($rules);

        $user = auth()->user();

        if ($user->vendorProfile && ! in_array($user->vendorProfile->status, ['draft', 'rejected_with_notes'])) {
            return redirect()->route('vendor.pending')->with('error', __('vendor.already_submitted'));
        }

        $profile = VendorProfile::query()->firstOrNew(['user_id' => $user->id]);
        $currentStep = (int) ($request->onboarding_step ?: 1);

        if (! $isDraft && $requiresPayment && ! $request->hasFile('subscription_payment_receipt') && blank($profile->subscription_payment_receipt)) {
            throw ValidationException::withMessages([
                'subscription_payment_receipt' => __('vendor.onboarding.receipt_required'),
            ]);
        }

        $profile->user_id = $user->id;
        $profile->payment_method = 'cash_on_delivery';
        $profile->onboarding_step = $currentStep;

        $this->applyStepFields($profile, $request, $isDraft, $currentStep);

        if ($request->hasFile('brand_logo')) {
            if ($profile->brand_logo) {
                Storage::disk('public')->delete($profile->brand_logo);
            }
            $profile->brand_logo = $request->file('brand_logo')->store('vendor_logos', 'public');
        }

        if ($request->hasFile('verification_document')) {
            if ($profile->verification_document) {
                Storage::disk('public')->delete($profile->verification_document);
            }
            $profile->verification_document = $request->file('verification_document')->store('vendor_docs', 'public');
        }

        if ($request->hasFile('subscription_payment_receipt')) {
            if ($profile->subscription_payment_receipt) {
                Storage::disk('public')->delete($profile->subscription_payment_receipt);
            }
            $profile->subscription_payment_receipt = $request->file('subscription_payment_receipt')->store('vendor_subscription_receipts', 'public');
            $profile->subscription_payment_status = 'pending';
            $profile->subscription_plan = $request->subscription_plan ?: $profile->subscription_plan;
        } elseif ($requiresPayment && $profile->subscription_payment_status === 'not_required') {
            $profile->subscription_payment_status = 'pending';
            if ($request->filled('subscription_plan')) {
                $profile->subscription_plan = $request->subscription_plan;
            }
        } elseif (! $requiresPayment) {
            $profile->subscription_payment_status = 'not_required';
        } elseif ($request->filled('subscription_plan')) {
            $profile->subscription_plan = $request->subscription_plan;
        }

        if ($request->action === 'submit') {
            $profile->status = 'pending';
        } else {
            $profile->status = 'draft';
        }

        $profile->save();

        if ((! $isDraft || $currentStep === 1) && $request->filled('phone')) {
            $fullPhone = ($request->phone_country_code ?? '+966').ltrim((string) $request->phone, '0');
            $user->phone = $fullPhone;
            $user->save();
        }

        if ($profile->status === 'pending') {
            return redirect()->route('vendor.pending')->with('success', __('vendor.submitted_success'));
        }

        return redirect()
            ->route('profile.edit')
            ->with('success', __('vendor.draft_saved'));
    }

    private function applyStepFields(VendorProfile $profile, Request $request, bool $isDraft, int $currentStep): void
    {
        if ($this->shouldApplyStepFields($isDraft, $currentStep, 1)) {
            $profile->brand_name = $request->input('brand_name');
            $profile->brand_description = $request->input('brand_description');

            if ($request->has('service_countries') || ! $isDraft) {
                $profile->service_countries = $request->input('service_countries', []);
            }
        }

        if ($this->shouldApplyStepFields($isDraft, $currentStep, 2)) {
            $profile->instagram_link = $request->input('instagram_link');
            $profile->tiktok_link = $request->input('tiktok_link');
            $profile->snapchat_link = $request->input('snapchat_link');
            $profile->store_link = $request->input('store_link');
            $profile->store_link_description = $request->input('store_link_description');
        }

        if ($this->shouldApplyStepFields($isDraft, $currentStep, 3)) {
            if ($request->has('product_types') || ! $isDraft) {
                $profile->product_types = $request->input('product_types', []);
            }
        }

        if ($this->shouldApplyStepFields($isDraft, $currentStep, 4)) {
            $profile->commercial_registration_number = $request->input('commercial_registration_number');
        }
    }

    private function shouldApplyStepFields(bool $isDraft, int $currentStep, int $fieldStep): bool
    {
        if (! $isDraft) {
            return true;
        }

        return $currentStep === $fieldStep;
    }

    public function pending()
    {
        $user = auth()->user();
        if (! $user->vendorProfile || $user->vendorProfile->status !== 'pending') {
            return redirect()->route('vendor.onboarding');
        }

        return view('vendor.pending');
    }

    public function rejected()
    {
        $user = auth()->user();
        if (! $user->vendorProfile || $user->vendorProfile->status !== 'rejected_with_notes') {
            return redirect()->route('vendor.onboarding');
        }

        return view('vendor.rejected', [
            'vendorProfile' => $user->vendorProfile,
        ]);
    }
}
