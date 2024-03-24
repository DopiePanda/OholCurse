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
        Schema::create('profile_badges', function (Blueprint $table) {
            $table->id();
            $table->string('player_hash');
            $table->bigInteger('badge_id')->unsigned();
            $table->integer('achieved_at');
            $table->timestamps();

            $table->foreign('badge_id')->references('id')->on('badges');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_badges');
    }
};
