<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->text('benefits_en')->nullable()->after('long_description_ar');
            $table->text('benefits_ar')->nullable()->after('benefits_en');
            $table->text('ingredients_en')->nullable()->after('benefits_ar');
            $table->text('ingredients_ar')->nullable()->after('ingredients_en');
            $table->text('usage_instructions_en')->nullable()->after('ingredients_ar');
            $table->text('usage_instructions_ar')->nullable()->after('usage_instructions_en');
            $table->text('warnings_en')->nullable()->after('usage_instructions_ar');
            $table->text('warnings_ar')->nullable()->after('warnings_en');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->text('benefits_en')->nullable()->after('long_description_ar');
            $table->text('benefits_ar')->nullable()->after('benefits_en');
            $table->text('ingredients_en')->nullable()->after('benefits_ar');
            $table->text('ingredients_ar')->nullable()->after('ingredients_en');
            $table->text('usage_instructions_en')->nullable()->after('ingredients_ar');
            $table->text('usage_instructions_ar')->nullable()->after('usage_instructions_en');
            $table->text('warnings_en')->nullable()->after('usage_instructions_ar');
            $table->text('warnings_ar')->nullable()->after('warnings_en');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn([
                'benefits_en',
                'benefits_ar',
                'ingredients_en',
                'ingredients_ar',
                'usage_instructions_en',
                'usage_instructions_ar',
                'warnings_en',
                'warnings_ar',
            ]);
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'benefits_en',
                'benefits_ar',
                'ingredients_en',
                'ingredients_ar',
                'usage_instructions_en',
                'usage_instructions_ar',
                'warnings_en',
                'warnings_ar',
            ]);
        });
    }
};
