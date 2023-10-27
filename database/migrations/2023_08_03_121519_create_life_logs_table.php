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
        Schema::create('life_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['birth', 'death']);
            $table->integer('timestamp');
            $table->integer('character_id');
            $table->string('character_name')->nullable();
            $table->string('player_hash');
            $table->integer('lineage_id')->nullable();
            $table->double('age', 2, 2)->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->string('location');
            $table->integer('parent_id')->nullable();
            $table->string('died_to')->nullable();
            $table->integer('population');
            $table->integer('yum_chain')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('life_logs');
    }
};
