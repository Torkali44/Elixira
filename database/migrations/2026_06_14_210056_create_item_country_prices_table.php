<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_country_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->string('country_code', 10);
            $table->decimal('member_price', 10, 2);
            $table->decimal('guest_price', 10, 2);
            $table->timestamps();

            $table->unique(['item_id', 'country_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_country_prices');
    }
};
