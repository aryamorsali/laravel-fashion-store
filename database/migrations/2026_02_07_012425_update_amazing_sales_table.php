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

            if (Schema::hasColumn('amazing_sales', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
            if (Schema::hasColumn('amazing_sales', 'status')) {
                $table->dropColumn('status');
            }
            if (!Schema::hasColumn('amazing_sales', 'is_active')) {
                $table->boolean('is_active')
                    ->default(false)
                    ->after('end_date');
            }

            $table->foreignId('product_variant_id')
                ->after('id')
                ->constrained('product_variants')
                ->cascadeOnDelete();

            $table->unique('product_variant_id');
        });
    }

    public function down(): void {
        //
    }
};
