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
            $table->json('multi_objects')->nullable()->after('id');
            $table->boolean('multi')->default(0)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaderboard_records', function (Blueprint $table) {
            $table->dropColumn('multi_objects');
            $table->dropColumn('multi');
        });
    }
};
