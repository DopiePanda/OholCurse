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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['web', 'game'])->default('game');
            $table->boolean('lifetime')->default(1);
            $table->string('title');
            $table->string('description');
            $table->string('badge');
            $table->string('object');
            $table->integer('goal')->default(10);
            $table->integer('xp')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
