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
        Schema::table('curse_logs', function (Blueprint $table) {
            $table->index('player_hash');
            $table->index('reciever_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('curse_logs', function (Blueprint $table) {
            $table->dropIndex('player_hash');
            $table->dropIndex('reciever_hash');
        });
    }
};
