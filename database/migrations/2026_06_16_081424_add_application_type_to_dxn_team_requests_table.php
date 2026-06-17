<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dxn_team_requests', function (Blueprint $table) {
            $table->string('application_type')->default('new_distributor')->after('user_id');
            $table->string('assigned_dxn_member_code')->nullable()->after('status');
            $table->text('admin_notes')->nullable()->after('assigned_dxn_member_code');
        });
    }

    public function down(): void
    {
        Schema::table('dxn_team_requests', function (Blueprint $table) {
            $table->dropColumn(['application_type', 'assigned_dxn_member_code', 'admin_notes']);
        });
    }
};
