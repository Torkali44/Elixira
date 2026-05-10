<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('brand_name')->nullable();
            $table->string('brand_logo')->nullable();
            $table->text('brand_description')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('tiktok_link')->nullable();
            $table->string('snapchat_link')->nullable();
            $table->json('other_links')->nullable();
            $table->string('store_link')->nullable();
            $table->text('store_link_description')->nullable();
            $table->json('service_countries')->nullable();
            $table->json('product_types')->nullable();
            $table->string('payment_method')->default('cash_on_delivery');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_profiles');
    }
};
