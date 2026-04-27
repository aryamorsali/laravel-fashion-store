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
         Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('quantity')->unsigned()->default(1);
            $table->dateTime('expires_at')->nullable();


            $table->timestamps();
            $table->softDeletes();

            // جلوگیری از تکراری شدن واریانت در سبد خرید یک کاربر
            $table->unique(['user_id', 'product_variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
