<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add reward_points (set by admin) to items — distinct from the old 'points' popularity counter
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedInteger('reward_points')->default(0)->after('points')
                ->comment('Points awarded to user when this item is purchased');
        });

        // Add total_points to users
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('total_points')->default(0)->after('user_code');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('reward_points');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('total_points');
        });
    }
};
