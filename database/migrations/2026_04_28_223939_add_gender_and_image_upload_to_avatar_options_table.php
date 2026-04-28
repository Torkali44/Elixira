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
        Schema::table('avatar_options', function (Blueprint $table) {
            $table->string('gender')->default('both')->after('name'); // 'male', 'female', 'both'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avatar_options', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
    }
};
