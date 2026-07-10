<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_country_id')->constrained()->cascadeOnDelete();
            $table->string('name_en');
            $table->string('name_ar');
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_cities');
    }
};
