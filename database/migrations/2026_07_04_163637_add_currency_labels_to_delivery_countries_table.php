<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_countries', function (Blueprint $table) {
            $table->string('currency_label_en', 20)->nullable()->after('currency_code');
            $table->string('currency_label_ar', 20)->nullable()->after('currency_label_en');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_countries', function (Blueprint $table) {
            $table->dropColumn(['currency_label_en', 'currency_label_ar']);
        });
    }
};
