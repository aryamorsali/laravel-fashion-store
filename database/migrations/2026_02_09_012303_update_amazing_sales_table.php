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
        Schema::table('amazing_sales', function (Blueprint $table) {

            $table->dropForeign(['product_variant_id']);

            $table->dropUnique('amazing_sales_product_variant_id_unique');

            $table->unique(
                ['product_variant_id', 'deleted_at'],
                'amazing_sales_variant_unique_active'
            );

            $table->foreign('product_variant_id')
                ->references('id')
                ->on('product_variants')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amazing_sales', function (Blueprint $table) {
            //
        });
    }
};
