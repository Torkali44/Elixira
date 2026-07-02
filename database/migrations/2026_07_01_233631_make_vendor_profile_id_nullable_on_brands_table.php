<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropForeign(['vendor_profile_id']);
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_profile_id')->nullable()->change();
            $table->foreign('vendor_profile_id')->references('id')->on('vendor_profiles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropForeign(['vendor_profile_id']);
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_profile_id')->nullable(false)->change();
            $table->foreign('vendor_profile_id')->references('id')->on('vendor_profiles')->cascadeOnDelete();
        });
    }
};
