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
        Schema::table('curse_logs_temp', function (Blueprint $table) {
            $table->integer('attempts')->default(0)->after('character_id');
            $table->boolean('hide')->default(1)->after('character_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('curse_logs_temp', function (Blueprint $table) {
            $table->dropColumn('hide');
            $table->dropColumn('attempts');
        });
    }
};
