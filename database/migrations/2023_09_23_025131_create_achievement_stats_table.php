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
        Schema::create('achievement_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->unsignedBigInteger('leaderboard_id')->nullable();
            $table->integer('achievement_id')->constrained();
            $table->integer('progress');
            $table->timestamps();

            $table->foreign('leaderboard_id')->references('leaderboard_id')->on('leaderboards');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievement_stats');
    }
};
