<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('admin_read_at')->nullable()->after('notes');
        });

        Schema::table('special_requests', function (Blueprint $table) {
            $table->timestamp('admin_read_at')->nullable()->after('status');
        });

        // Keep pending items unread so current badges stay meaningful; mark the rest as read.
        DB::table('orders')->where('status', '!=', 'pending')->update(['admin_read_at' => now()]);
        DB::table('special_requests')->where('status', '!=', 'pending')->update(['admin_read_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('admin_read_at');
        });

        Schema::table('special_requests', function (Blueprint $table) {
            $table->dropColumn('admin_read_at');
        });
    }
};
