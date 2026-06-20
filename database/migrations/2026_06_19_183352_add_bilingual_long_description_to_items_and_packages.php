<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->longText('long_description_en')->nullable()->after('long_description');
            $table->longText('long_description_ar')->nullable()->after('long_description_en');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->longText('long_description_en')->nullable()->after('long_description');
            $table->longText('long_description_ar')->nullable()->after('long_description_en');
        });

        DB::table('items')
            ->whereNotNull('long_description')
            ->update(['long_description_en' => DB::raw('long_description')]);

        DB::table('packages')
            ->whereNotNull('long_description')
            ->update(['long_description_en' => DB::raw('long_description')]);

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('long_description');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('long_description');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->longText('long_description')->nullable()->after('description_ar');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->longText('long_description')->nullable()->after('description_ar');
        });

        DB::table('items')
            ->whereNotNull('long_description_en')
            ->update(['long_description' => DB::raw('long_description_en')]);

        DB::table('packages')
            ->whereNotNull('long_description_en')
            ->update(['long_description' => DB::raw('long_description_en')]);

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['long_description_en', 'long_description_ar']);
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['long_description_en', 'long_description_ar']);
        });
    }
};
