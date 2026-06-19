<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('user_points_transactions', 'item_id')) {
            Schema::table('user_points_transactions', function (Blueprint $table) {
                $table->foreignId('item_id')->nullable()->after('order_id')->constrained()->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('user_points_transactions', 'item_id')) {
            Schema::table('user_points_transactions', function (Blueprint $table) {
                $table->dropConstrainedForeignId('item_id');
            });
        }
    }
};
