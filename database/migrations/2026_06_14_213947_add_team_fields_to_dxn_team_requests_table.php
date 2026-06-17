<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dxn_team_requests', function (Blueprint $table) {
            $table->string('team_name')->nullable()->after('member_code');
            $table->unsignedSmallInteger('team_size')->nullable()->after('team_name');
            $table->json('team_members')->nullable()->after('team_size');
        });
    }

    public function down(): void
    {
        Schema::table('dxn_team_requests', function (Blueprint $table) {
            $table->dropColumn(['team_name', 'team_size', 'team_members']);
        });
    }
};
