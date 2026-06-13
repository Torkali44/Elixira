<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add video_url to blogs
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('video_url')->nullable()->after('image')
                ->comment('YouTube/Vimeo embed URL');
        });

        // Create blog_images table for multiple images per blog
        Schema::create('blog_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained()->onDelete('cascade');
            $table->string('image');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_images');

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('video_url');
        });
    }
};
