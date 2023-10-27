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
        // desert/jungle/arctic/language
        Schema::table('life_logs', function (Blueprint $table) {
            $table->enum('family_type', ['language', 'arctic', 'jungle', 'desert'])->nullable()->after('yum_chain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('life_logs', function (Blueprint $table) {
            //
        });
    }
};
