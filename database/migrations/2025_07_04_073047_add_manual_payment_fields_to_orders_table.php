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
    Schema::table('orders', function (Blueprint $table) {
        $table->string('payment_method')->nullable()->after('status');
        $table->string('bank_name')->nullable()->after('payment_method');
        $table->string('wallet_type')->nullable()->after('bank_name');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['payment_method', 'bank_name', 'wallet_type']);
    });
}
};
