@extends('layouts.framer')

@section('title', 'Vendor Terms & Conditions - Elixira')

@section('head')
<style>
    .terms-card {
        background: linear-gradient(180deg, rgba(19, 37, 45, 0.96), rgba(10, 26, 34, 0.96));
        border: 1px solid var(--elx-border);
        border-radius: 28px;
        padding: 3rem;
        box-shadow: 0 24px 70px rgba(0, 0, 0, 0.25);
        color: var(--elx-light);
        line-height: 1.8;
    }
    .terms-card h2 {
        color: var(--elx-white);
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-size: 1.4rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 0.5rem;
    }
    .terms-card p, .terms-card li {
        font-size: 0.95rem;
    }
    .terms-card ul {
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .terms-card li {
        margin-bottom: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="elx-container">
        <div style="max-width: 900px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 3rem;" data-animate>
                <h1 class="elx-hero__title">
                    <span class="elx-hero__title-gradient">Vendor Terms & Conditions</span>
                </h1>
                <p style="color: var(--elx-light); max-width: 600px; margin: 0 auto; margin-top: 1rem;">
                    Please read our guidelines and contractual terms carefully before applying to sell on the Elixira platform.
                </p>
            </div>

            <div class="terms-card" data-animate>
                <p>Welcome to Elixira. These Vendor Terms and Conditions ("Terms") govern your relationship with Elixira as a registered seller ("Vendor") on our platform. By registering or submitting a vendor request, you agree to comply with and be bound by these Terms.</p>

                <h2>1. Onboarding and Verification</h2>
                <p>To list items on our storefront, you must successfully register and submit your profile for verification. You agree that:</p>
                <ul>
                    <li>All submitted business credentials, licenses, or identification papers are genuine and current.</li>
                    <li>We reserve the right to approve or reject any brand application at our sole discretion.</li>
                    <li>If your application is returned as a "Draft" or "Rejected with Notes", you must address the specified remarks before re-submitting.</li>
                </ul>

                <h2>2. Product Listings and Approvals</h2>
                <p>As a vendor, you may upload products for sale. To maintain the quality and consistency of our catalog, the following conditions apply:</p>
                <ul>
                    <li>All products must be reviewed and approved by the Elixira admin team before they become live and searchable on the platform.</li>
                    <li>You must provide accurate descriptions, high-quality images, and exact pricing/stock counts for all listed items.</li>
                    <li>You are strictly forbidden from listing expired, toxic, counterfeit, or prohibited materials. Doing so will result in immediate suspension.</li>
                </ul>

                <h2>3. Ordering and Fulfillment</h2>
                <p>Vendors are responsible for executing their own orders. When a customer purchases your items:</p>
                <ul>
                    <li>You are solely responsible for preparing and dispatching the product.</li>
                    <li>You must update the order status (e.g. from Pending to Confirmed, Preparing, Ready to Ship, and Delivered) in a timely manner.</li>
                    <li>Any delay or failure in fulfillment must be communicated directly to the customer or platform support immediately.</li>
                </ul>

                <h2>4. Payments and Fees</h2>
                <p>Currently, the default payment method is Cash on Delivery (COD) or specific platform payment rails:</p>
                <ul>
                    <li>Payouts to vendors are calculated based on the successful delivery of orders.</li>
                    <li>Commission fees or platform management fees will be deducted according to your individual agreement with Elixira.</li>
                </ul>

                <h2>5. Termination and Suspensions</h2>
                <p>Elixira reserves the right to suspend or terminate any vendor account if we detect fraudulent transactions, bad product reviews, or failure to comply with safety standards.</p>

                <div style="margin-top: 3rem; text-align: center;">
                    <a href="{{ route('vendor.onboarding') }}" class="elx-btn elx-btn--primary px-5 py-3">Back to Onboarding</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
