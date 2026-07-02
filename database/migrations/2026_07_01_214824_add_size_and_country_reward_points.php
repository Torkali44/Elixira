<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('size_en', 120)->nullable()->after('name_ar');
            $table->string('size_ar', 120)->nullable()->after('size_en');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->string('size_en', 120)->nullable()->after('name_ar');
            $table->string('size_ar', 120)->nullable()->after('size_en');
        });

        Schema::table('item_country_prices', function (Blueprint $table) {
            $table->unsignedInteger('reward_points')->nullable()->after('guest_price');
        });

        Schema::table('package_country_prices', function (Blueprint $table) {
            $table->unsignedInteger('reward_points')->nullable()->after('guest_price');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['size_en', 'size_ar']);
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['size_en', 'size_ar']);
        });

        Schema::table('item_country_prices', function (Blueprint $table) {
            $table->dropColumn('reward_points');
        });

        Schema::table('package_country_prices', function (Blueprint $table) {
            $table->dropColumn('reward_points');
        });
    }
};
