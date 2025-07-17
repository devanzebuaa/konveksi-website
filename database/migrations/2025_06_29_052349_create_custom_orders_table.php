<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('custom_orders', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('product_type');
        $table->text('size_detail')->nullable();
        $table->text('description')->nullable();
        $table->string('design_file')->nullable();
        $table->integer('quantity');
        $table->string('payment_method');
        $table->string('contact');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_orders');
    }
};
