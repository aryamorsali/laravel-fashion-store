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
        Schema::create('home_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('title');                
            $table->string('subtitle')->nullable();  
            $table->text('image')->nullable();   
            $table->foreignId('category_id')        
                ->nullable()
                ->constrained('product_categories')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->string('url')->nullable();       
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();                   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_boxes');
    }
};
