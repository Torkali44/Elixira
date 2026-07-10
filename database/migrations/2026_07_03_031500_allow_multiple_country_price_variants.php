<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('item_country_prices', function (Blueprint $table) {
            $table->index('item_id', 'item_country_prices_item_id_index');
            $table->dropUnique(['item_id', 'country_code']);
        });

        Schema::table('package_country_prices', function (Blueprint $table) {
            $table->index('package_id', 'package_country_prices_package_id_index');
            $table->dropUnique(['package_id', 'country_code']);
        });
    }

    public function down(): void
    {
        Schema::table('item_country_prices', function (Blueprint $table) {
            $table->unique(['item_id', 'country_code']);
            $table->dropIndex('item_country_prices_item_id_index');
        });

        Schema::table('package_country_prices', function (Blueprint $table) {
            $table->unique(['package_id', 'country_code']);
            $table->dropIndex('package_country_prices_package_id_index');
        });
    }
};
