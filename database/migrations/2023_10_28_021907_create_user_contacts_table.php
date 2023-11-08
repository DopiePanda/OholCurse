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
        Schema::create('user_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('hash')->nullOnDelete();
            $table->enum('type', ['friend', 'enemy', 'dubious']);
            $table->string('nickname')->nullable();
            $table->string('phex_hash')->nullable();
            $table->timestamps();

            $table->foreign('hash')->references('player_hash')->on('leaderboards');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_contacts');
    }
};
