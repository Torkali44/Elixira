<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('special_requests', function (Blueprint $table) {
            $table->string('email')->nullable()->after('phone');
        });

        Schema::create('special_item_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('special_request_id')->nullable()->constrained('special_requests')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('target_phone')->nullable();
            $table->string('target_email')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedInteger('used_quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('special_item_offers');

        Schema::table('special_requests', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};
