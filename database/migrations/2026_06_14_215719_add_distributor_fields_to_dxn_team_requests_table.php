<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dxn_team_requests', function (Blueprint $table) {
            $table->timestamp('contract_accepted_at')->nullable()->after('read_at');
            $table->string('sponsor_code')->nullable()->after('member_code');
            $table->string('sponsor_name')->nullable()->after('sponsor_code');
            $table->string('gender', 20)->nullable()->after('name');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->string('id_number')->nullable()->after('date_of_birth');
            $table->string('passport_number')->nullable()->after('id_number');
            $table->string('nationality')->nullable()->after('passport_number');
            $table->boolean('has_heir')->default(false)->after('nationality');
            $table->string('heir_name')->nullable()->after('has_heir');
            $table->string('heir_relationship')->nullable()->after('heir_name');
            $table->string('heir_id_number')->nullable()->after('heir_relationship');
            $table->string('heir_passport_number')->nullable()->after('heir_id_number');
            $table->text('address')->nullable()->after('message');
            $table->string('address_country')->nullable()->after('address');
            $table->string('address_city')->nullable()->after('address_country');
            $table->string('postal_code')->nullable()->after('address_city');
        });
    }

    public function down(): void
    {
        Schema::table('dxn_team_requests', function (Blueprint $table) {
            $table->dropColumn([
                'contract_accepted_at',
                'sponsor_code',
                'sponsor_name',
                'gender',
                'date_of_birth',
                'id_number',
                'passport_number',
                'nationality',
                'has_heir',
                'heir_name',
                'heir_relationship',
                'heir_id_number',
                'heir_passport_number',
                'address',
                'address_country',
                'address_city',
                'postal_code',
            ]);
        });
    }
};
