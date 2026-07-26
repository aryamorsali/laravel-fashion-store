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
        Schema::table('order_items', function (Blueprint $table) {



            $table->dropForeign(['product_id']);
            $table->dropForeign(['product_variant_id']);
            $table->dropForeign(['color_id']);
            $table->dropForeign(['size_id']);


            $table->dropColumn(['product_id', 'color_id', 'size_id', 'product_variant_id']);

            $table->foreignId('product_variant_id')
                ->nullable()
                ->after('order_id')
                ->constrained('product_variants')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
