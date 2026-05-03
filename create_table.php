<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

if (!Schema::hasTable('special_item_offers')) {
    Schema::create('special_item_offers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('item_id')->constrained()->cascadeOnDelete();
        $table->foreignId('special_request_id')->nullable()->constrained('special_requests')->nullOnDelete();
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        $table->string('target_phone')->nullable();
        $table->string('target_email')->nullable();
        $table->unsignedInteger('quantity')->default(1);
        $table->unsignedInteger('used_quantity')->default(0);
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    echo "Created table\n";
} else {
    echo "Table already exists\n";
}

// Add the migration record back
DB::table('migrations')->insert([
    'migration' => '2026_05_02_005000_add_email_and_private_offers',
    'batch' => 13
]);
echo "Added migration record\n";
