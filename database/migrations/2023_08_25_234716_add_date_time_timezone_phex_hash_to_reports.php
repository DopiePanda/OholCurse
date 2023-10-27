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
        //Give the moving column a temporary name:
        Schema::table('reports', function($table)
        {
            $table->renameColumn('family_name', 'name_old');
        });

        //Add a new column with the regular name:
        Schema::table('reports', function(Blueprint $table)
        {
            $table->string('character_name')->after('user_id');
        });

        //Copy the data across to the new column:
        DB::table('reports')->update([
            'character_name' => DB::raw('name_old')   
        ]);

        //Remove the old column:
        Schema::table('reports', function(Blueprint $table)
        {
            $table->dropColumn('name_old');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->integer('unix_to')->after('user_id');
            $table->integer('unix_from')->after('user_id');
            $table->string('phex_hash')->after('phex_name')->nullable();
            $table->string('curse_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['unix_to', 'unix_from', 'phex_hash']);
        });
    }
};
