<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pomodoro_sessions', function (Blueprint $table) {
           DB::statement("
           ALTER TABLE pomodoro_sessions
           MODIFY status ENUM('idle', 'running', 'paused', 'completed', 'cancelled')
           NOT NULL default 'idle'
           ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pomodoro_sessions', function (Blueprint $table) {
            DB::statement("
                ALTER TABLE pomodoro_sessions
                MODIFY status ENUM('pending','running','paused','completed')
                NOT NULL DEFAULT 'pending'
            ");
        });
    }
};
