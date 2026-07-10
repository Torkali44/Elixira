<?php

use App\Models\Order;
use App\Support\OrderReferenceGenerator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('reference', 6)->nullable()->unique()->after('id');
        });

        Order::query()->whereNull('reference')->orderBy('id')->each(function (Order $order): void {
            $order->update(['reference' => OrderReferenceGenerator::generate()]);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['reference']);
            $table->dropColumn('reference');
        });
    }
};
