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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnUpdate()->nullOnDelete();
            $table->decimal('amount', 20, 3)->default(0);
            $table->string('gateway', 50)->index();
            $table->string('transaction_id')->nullable()->unique();
            $table->enum('status', ['unpaid', 'paid', 'failed', 'returned'])->default('unpaid')->index();
            $table->json('first_response')->nullable();
            $table->json('second_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
