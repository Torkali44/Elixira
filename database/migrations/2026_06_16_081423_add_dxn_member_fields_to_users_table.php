<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_dxn_verified')->default(false)->after('user_code');
            $table->string('dxn_member_code')->nullable()->after('is_dxn_verified');
            $table->string('dxn_tag_color', 20)->nullable()->after('dxn_member_code');
            $table->string('dxn_badge_image')->nullable()->after('dxn_tag_color');
            $table->timestamp('dxn_verified_at')->nullable()->after('dxn_badge_image');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_dxn_verified',
                'dxn_member_code',
                'dxn_tag_color',
                'dxn_badge_image',
                'dxn_verified_at',
            ]);
        });
    }
};
