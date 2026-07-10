<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('delivery_city_id')->nullable()->after('address')->constrained()->nullOnDelete();
            $table->decimal('delivery_fee', 10, 2)->default(0)->after('delivery_city_id');
            $table->decimal('subtotal_amount', 10, 2)->nullable()->after('total_amount');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('delivery_city_id');
            $table->dropColumn(['delivery_fee', 'subtotal_amount']);
        });
    }
};
