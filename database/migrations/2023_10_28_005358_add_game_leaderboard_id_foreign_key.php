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
        Schema::table('leaderboard_records', function (Blueprint $table) {
            $table->unsignedBigInteger('game_leaderboard_id')->nullable()->after('id')->nullOnDelete();

            $table->foreign('game_leaderboard_id')->references('id')->on('game_leaderboards');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaderboard_records', function (Blueprint $table) {
            $table->dropForeign(['game_leaderboard_id']);
        });
    }
};
