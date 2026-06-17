<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_profiles', function (Blueprint $table) {
            $table->string('subscription_plan')->nullable()->after('subscription_payment_status');
            $table->timestamp('subscription_starts_at')->nullable()->after('subscription_plan');
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_starts_at');
        });
    }

    public function down(): void
    {
        Schema::table('vendor_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_plan',
                'subscription_starts_at',
                'subscription_ends_at',
            ]);
        });
    }
};
