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
        Schema::create('inventory_allocations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cart_item_id')
                ->constrained('cart_items')
                ->restrictOnDelete();

            $table->foreignId('warehouse_variant_id')
                ->constrained('warehouse_variants')
                ->restrictOnDelete();

            $table->foreignId('order_item_id')
                ->nullable()
                ->constrained('order_items')
                ->restrictOnDelete();

            $table->unsignedInteger('quantity');

            $table->timestamps();

            $table->unique(['cart_item_id', 'warehouse_variant_id']);
            $table->unique(['order_item_id', 'warehouse_variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_allocations');
    }
};
