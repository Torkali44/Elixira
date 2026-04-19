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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['direct', 'whatsapp', 'instagram', 'external', 'video'])->default('direct');
            $table->string('avatar')->nullable();
            $table->string('name')->nullable();
            $table->string('age')->nullable();
            $table->string('skin_type')->nullable();
            $table->integer('rating')->nullable();
            $table->text('content')->nullable(); // review text or video URL or image path
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
