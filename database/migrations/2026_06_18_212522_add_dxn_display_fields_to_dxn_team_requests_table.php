<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dxn_team_requests', function (Blueprint $table) {
            $table->string('dxn_tag_color', 20)->nullable()->after('assigned_dxn_member_code');
            $table->string('dxn_badge_image')->nullable()->after('dxn_tag_color');
        });
    }

    public function down(): void
    {
        Schema::table('dxn_team_requests', function (Blueprint $table) {
            $table->dropColumn(['dxn_tag_color', 'dxn_badge_image']);
        });
    }
};
