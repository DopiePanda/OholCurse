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
        Schema::create('map_logs', function (Blueprint $table) {
            $table->id();
            $table->double('timestamp');
            $table->integer('pos_x');
            $table->integer('pos_y');
            $table->integer('object_id');
            $table->integer('use')->nullable();
            $table->integer('character_id');
            $table->index('character_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map_logs');
    }
};
