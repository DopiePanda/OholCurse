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
        Schema::create('game_leaderboards', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['weekly', 'monthly']);
            $table->unsignedBigInteger('object_id');
            $table->string('label');
            $table->string('page_title');
            $table->integer('limit')->default(10);
            $table->string('image')->default('assets/leaderboard/default.png');
            $table->timestamps();

            $table->foreign('object_id')->references('id')->on('game_objects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_leaderboards');
    }
};
