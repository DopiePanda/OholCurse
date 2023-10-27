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
        Schema::table('game_leaderboards', function (Blueprint $table) {
            $table->json('multi_objects')->nullable()->after('type');
            $table->boolean('multi')->default(0)->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_leaderboards', function (Blueprint $table) {
            $table->dropColumn('multi_objects');
            $table->dropColumn('multi');
        });
    }
};
