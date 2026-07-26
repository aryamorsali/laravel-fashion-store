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
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('tags');
        });

        Schema::table('post_categories', function (Blueprint $table) {
            $table->dropColumn('tags');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn('tags');
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn('tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->text('tags')->nullable();
        });

        Schema::table('post_categories', function (Blueprint $table) {
            $table->text('tags')->nullable();
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->text('tags')->nullable();
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->text('tags')->nullable();
        });
    }
};
