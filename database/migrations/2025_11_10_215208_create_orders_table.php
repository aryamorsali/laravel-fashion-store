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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('address_id')->nullable()->constrained('addresses')->onUpdate('cascade')->onDelete('set null');
            $table->json('address_snapshot')->nullable();
            // نوع پرداخت (برای گسترش آینده)
            $table->enum('payment_type', ['online', 'offline', 'wallet'])->default('online')
                ->comment('online|offline|wallet — پروژه فعلی: فقط online');
            $table->enum('payment_status', ['unpaid', 'paid', 'failed', 'returned'])->default('unpaid')
                ->comment('unpaid, paid, failed, returned');
            $table->decimal('order_final_amount', 20, 3)->comment('مبلغ نهایی قابل پرداخت پس از همه تخفیف‌ها');
            $table->decimal('order_total_products_discount_amount', 20, 3)->default(0);
            $table->decimal('order_discount_amount', 20, 3)->default(0);
            $table->foreignId('delivery_id')->nullable()->constrained('deliveries')->onUpdate('cascade')->onDelete('set null');
            $table->json('delivery_snapshot')->nullable();
            $table->decimal('delivery_amount', 20, 3)->nullable();
            $table->enum('delivery_status', ['sending', 'shipped', 'delivered', 'canceled'])->default('sending');
            $table->timestamp('delivery_date')->nullable();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->onUpdate('cascade')->onDelete('set null');
            $table->json('coupon_snapshot')->nullable();
            $table->decimal('order_coupon_discount_amount', 20, 3)->default(0);
            $table->foreignId('common_discount_id')->nullable()->constrained('common_discounts')->onUpdate('cascade')->onDelete('set null');
            $table->json('common_discount_snapshot')->nullable();
            $table->decimal('order_common_discount_amount', 20, 3)->default(0);
            $table->enum('order_status', [
                'not_checked',        // ثبت شده، بررسی نشده
                'awaiting_confirmation', // در انتظار تایید
                'confirmed',          // تایید شده
                'not_confirmed',      // تایید نشده
                'canceled',        // باطل شده
                'returned'            // مرجوع شده
            ])->default('not_checked');
            $table->timestamps();
            $table->softDeletes();

            // ایندکس‌ها برای کوئری‌های رایج
            $table->index('user_id');
            $table->index('order_status');
            $table->index('payment_status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
