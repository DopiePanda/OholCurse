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
        Schema::create('yumlogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->integer('timestamp');
            $table->enum('type', [
                'curse', 
                'name', 
                'my_birth', 
                'my_death', 
                'my_id',
                'my_age',
                'death', 
                'player', 
                'player_map', 
                'coord', 
                'globalMessage'
            ]);
            $table->integer('character_id')->nullable();
            $table->string('character_name')->nullable();
            $table->string('curse_name')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->float('age')->nullable();
            $table->string('died_to')->nullable();
            $table->integer('pos_x')->nullable();
            $table->integer('pos_y')->nullable();
            $table->string('order')->nullable();
            $table->boolean('verified')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yumlogs');
    }
};
