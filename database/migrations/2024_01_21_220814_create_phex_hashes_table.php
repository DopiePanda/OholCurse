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
        Schema::create('phex_hashes', function (Blueprint $table) {
            $table->id();
            $table->string('player_hash')->nullable();
            $table->integer('character_id')->unsigned()->nullable();
            $table->string('phex_hash');
            $table->string('legacy_phex_hash')->nullable();
            $table->timestamps();

            $table->index('player_hash');
            $table->index('phex_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phex_hashes');
    }
};
