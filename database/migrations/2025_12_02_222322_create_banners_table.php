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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->comment('متن کوچک بالا');    
            $table->string('subtitle')->nullable()->comment('متن بزرگ');  
            $table->string('button_text')->nullable()->comment('متن دکمه'); 
            $table->string('button_url')->nullable()->comment('لینک دکمه'); 
            $table->string('image');
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
        Schema::dropIfExists('banners');
    }
};
