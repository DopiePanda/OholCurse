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
        Schema::table('life_logs', function (Blueprint $table) {
            $table->integer('pos_y')->after('gender');
            $table->integer('pos_x')->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('life_logs', function (Blueprint $table) {
            $table->dropColumn('pos_y');
            $table->dropColumn('pos_x');
        });
    }
};
