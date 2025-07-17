<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        if (!Schema::hasColumn('product_variants', 'stock')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->unsignedInteger('stock')->default(0)->after('image');
            });
        }
    }

    public function down(): void {
        if (Schema::hasColumn('product_variants', 'stock')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->dropColumn('stock');
            });
        }
    }
};
