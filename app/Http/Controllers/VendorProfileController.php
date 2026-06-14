<?php

namespace App\Http\Controllers;

use App\Models\VendorProfile;
use App\Support\VendorSubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'instagram_link' => 'nullable|url|max:255',
            'tiktok_link' => 'nullable|url|max:255',
            'snapchat_link' => 'nullable|url|max:255',
            'store_link' => 'nullable|url|max:255',
            'store_link_description' => 'nullable|string|max:500',
            'service_countries' => $isDraft ? 'nullable|array' : 'required|array',
            'product_types' => $isDraft ? 'nullable|array' : 'required|array',
            'verification_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'subscription_payment_receipt' => ($isDraft || ! $requiresPayment) ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096' : 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
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

        $profile = $user->vendorProfile ?? new VendorProfile;
        $profile->user_id = $user->id;
        $profile->brand_name = $request->brand_name;
        $profile->brand_description = $request->brand_description;
        $profile->commercial_registration_number = $request->commercial_registration_number;
        $profile->instagram_link = $request->instagram_link;
        $profile->tiktok_link = $request->tiktok_link;
        $profile->snapchat_link = $request->snapchat_link;
        $profile->store_link = $request->store_link;
        $profile->store_link_description = $request->store_link_description;
        $profile->service_countries = $request->service_countries;
        $profile->product_types = $request->product_types;
        $profile->payment_method = 'cash_on_delivery';
        $profile->onboarding_step = (int) ($request->onboarding_step ?: 1);

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
        } elseif ($requiresPayment && $profile->subscription_payment_status === 'not_required') {
            $profile->subscription_payment_status = 'pending';
        } elseif (! $requiresPayment) {
            $profile->subscription_payment_status = 'not_required';
        }

        if ($request->action === 'submit') {
            $profile->status = 'pending';
        } else {
            $profile->status = 'draft';
        }

        $profile->save();

        if ($profile->status === 'pending') {
            return redirect()->route('vendor.pending')->with('status', __('vendor.submitted_success'));
        }

        return redirect()
            ->route('vendor.onboarding', ['step' => $profile->onboarding_step])
            ->with('status', __('vendor.draft_saved'));
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
