<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

     /*
        T 1691021103 6525703 a80130fa3ff5ab201c487aa44ccc3b4ab72c83cf => f115b1ee2d97f625430f6a54f21bc70cf706ded8
        T 1691021603 6525935 66d296456bc700697c65534cd3d09a9b937822d7 => 47dfe13b925d628803d33243c7babc8f3e7c220b
        A 1691022010 0f5dbab7223030bcc4cd61fbc1cddd82a38fb8d4 => b55f67da6a5e860af292e703c69c51aedba0b67c
        S 1691022010 b55f67da6a5e860af292e703c69c51aedba0b67c 2
        A 1691022609 0f5dbab7223030bcc4cd61fbc1cddd82a38fb8d4 => 1b0121b645f50f84923064f1d5794057f311d9f1
        S 1691022609 1b0121b645f50f84923064f1d5794057f311d9f1 4
        C 1691022965 6525827 9f0b59b3583173416a97d62f72163656e5fccd38 => b3b928c3bc1744d1319b4830d8fb43acf3ad44d2
    */

    public function up(): void
    {
        Schema::create('curse_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['curse', 'trust', 'all', 'score', 'forgive']);
            $table->integer('timestamp');
            $table->string('character_id')->nullable();
            $table->string('player_hash');
            $table->string('reciever_hash')->nullable();
            $table->integer('curse_score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curse_logs');
    }
};