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
        Schema::create('cronjobs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['scraping', 'processing', 'maintenance', 'other']);
            $table->string('module');
            $table->integer('completed_at')->nullable();
            $table->integer('time_elapsed')->default(0);
            $table->string('file_name')->nullable();
            $table->string('url')->nullable();
            $table->string('storage_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cronjobs');
    }
};
