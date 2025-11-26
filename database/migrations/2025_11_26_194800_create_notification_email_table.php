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
        Schema::create('notification_email', function (Blueprint $table) {
            $table->id();
            $table->string('subject')->nullable();
            $table->text('body');

            $table->enum('status', ['draft', 'scheduled', 'sent', 'failed'])
                ->default('draft');

            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_email');
    }
};
