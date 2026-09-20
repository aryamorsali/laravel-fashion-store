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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('amount', 10, 2);
            $table->tinyInteger('amount_type')->default(0)->comment('0 => percentage, 1 => price');
            $table->unsignedBigInteger('discount_ceiling')->nullable();
            $table->tinyInteger('type')->default(0)->comment('0 => common, 1 => private');
            $table->foreignId('user_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('status')->default(0)->comment('0 => inactive, 1 => active, 2 => expired');
            $table->timestamp('start_date')->useCurrent();
            $table->timestamp('end_date')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupans');
    }
};
