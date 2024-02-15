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
        Schema::create('private_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('conversation_id');
            $table->text('message');
            $table->boolean('read');
            $table->boolean('deleted');
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreign('conversation_id')->references('id')->on('user_conversations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('private_messages');
    }
};
