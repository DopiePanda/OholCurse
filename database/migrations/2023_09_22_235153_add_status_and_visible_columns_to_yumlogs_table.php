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
        Schema::table('yumlogs', function (Blueprint $table) {
            $table->integer('verification_tries')->after('order')->default(0);
            $table->integer('status')->after('verified')->default(0);
            $table->boolean('visible')->after('verified')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('yumlogs', function (Blueprint $table) {
            $table->dropColumn(['verification_tries', 'status', 'visible']);
        });
    }
};
