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
        Schema::create('player_messages', function (Blueprint $table) {
            $table->id();
            $table->string('server_ip');
            $table->integer('bot_id');
            $table->integer('timestamp');
            $table->integer('life_id');
            $table->string('life_name')->nullable();
            $table->string('message');
            $table->integer('pos_x');
            $table->integer('pos_y');
            $table->json('items')->nullable();  
            $table->timestamps();

            $table->index('life_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_messages');
    }
};
