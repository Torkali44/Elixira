<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('item_country_prices', function (Blueprint $table) {
            $table->string('size_en', 120)->nullable()->after('country_code');
            $table->string('size_ar', 120)->nullable()->after('size_en');
            $table->unsignedInteger('stock')->nullable()->after('reward_points');
        });

        Schema::table('package_country_prices', function (Blueprint $table) {
            $table->string('size_en', 120)->nullable()->after('country_code');
            $table->string('size_ar', 120)->nullable()->after('size_en');
            $table->unsignedInteger('stock')->nullable()->after('reward_points');
        });
    }

    public function down(): void
    {
        Schema::table('item_country_prices', function (Blueprint $table) {
            $table->dropColumn(['size_en', 'size_ar', 'stock']);
        });

        Schema::table('package_country_prices', function (Blueprint $table) {
            $table->dropColumn(['size_en', 'size_ar', 'stock']);
        });
    }
};
