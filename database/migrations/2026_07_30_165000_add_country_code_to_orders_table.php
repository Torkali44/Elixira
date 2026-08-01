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
            $table->string('country_code', 10)->nullable()->after('delivery_city_id');
        });

        // Backfill existing orders
        $orders = DB::table('orders')->get();
        foreach ($orders as $order) {
            $countryCode = null;

            if ($order->delivery_city_id) {
                $city = DB::table('delivery_cities')
                    ->join('delivery_countries', 'delivery_cities.delivery_country_id', '=', 'delivery_countries.id')
                    ->where('delivery_cities.id', $order->delivery_city_id)
                    ->select('delivery_countries.code')
                    ->first();
                if ($city) {
                    $countryCode = $city->code;
                }
            }

            if (!$countryCode && $order->customer_phone) {
                $digits = preg_replace('/[^\d]/', '', (string) $order->customer_phone);
                if (str_starts_with($digits, '971')) {
                    $countryCode = 'UAE';
                } elseif (str_starts_with($digits, '966')) {
                    $countryCode = 'KSA';
                }
            }

            $countryCode ??= 'KSA';

            DB::table('orders')->where('id', $order->id)->update(['country_code' => $countryCode]);
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('country_code');
        });
    }
};
