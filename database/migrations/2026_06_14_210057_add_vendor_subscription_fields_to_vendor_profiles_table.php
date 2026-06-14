<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_profiles', function (Blueprint $table) {
            $table->string('commercial_registration_number')->nullable()->after('brand_description');
            $table->unsignedTinyInteger('onboarding_step')->default(1)->after('status');
            $table->string('subscription_payment_receipt')->nullable()->after('verification_document');
            $table->string('subscription_payment_status')->default('not_required')->after('subscription_payment_receipt');
        });
    }

    public function down(): void
    {
        Schema::table('vendor_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'commercial_registration_number',
                'onboarding_step',
                'subscription_payment_receipt',
                'subscription_payment_status',
            ]);
        });
    }
};
