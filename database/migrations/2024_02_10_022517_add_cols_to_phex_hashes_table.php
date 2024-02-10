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
        Schema::table('phex_hashes', function (Blueprint $table) {
            $table->string('olgc_name')->nullable()->after('character_id');
            $table->string('olgc_hash_full')->nullable()->after('phex_hash');

            $table->string('px_name')->nullable()->after('olgc_hash_full');
            $table->string('px_hash_full')->nullable()->after('legacy_phex_hash');
            
            $table->renameColumn('phex_hash', 'olgc_hash');
            $table->renameColumn('legacy_phex_hash', 'px_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phex_hashes', function (Blueprint $table) {
            $table->dropColumn('olgc_name');
            $table->dropColumn('olgc_hash_full');
            $table->dropColumn('px_name');
            $table->dropColumn('px_hash_full');

            $table->renameColumn('olgc_hash', 'phex_hash');
            $table->renameColumn('px_hash', 'legacy_phex_hash');
        });
    }
};
